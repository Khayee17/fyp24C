<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\userInfo;
use App\Models\MyOrder;
use App\Models\Table;
use Carbon\Carbon;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class myOrderController extends Controller
{

    public function store(Request $request)
{
    // Log incoming request data
    Log::info('Incoming request data:', $request->all());

    // Validate the data
    $validatedData = $request->validate([
        'numberOfCustomers' => 'required|integer|min:1',
        'phone' => 'required|string|min:10|max:15',
        'items' => 'required|array',
        'items.*.name' => 'required|string',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric',
        'items.*.variant_text' => 'nullable|string',
        'items.*.remark' => 'nullable|string',
        'subtotal' => 'required|numeric',
        'sst' => 'required|numeric',
        'rounding' => 'required|numeric',
        'total' => 'required|numeric',
        'status' => 'nullable|string', // 订单状态
        'table_id' => 'nullable|exists:tables,id',
    ]);

    // Log validated data
    Log::info('Validated data:', $validatedData);

    // Begin a database transaction
    DB::beginTransaction();

    try {
        // Save the order data to the database
        $order = MyOrder::create([
            'customer_count' => $validatedData['numberOfCustomers'],
            'phone' => $validatedData['phone'],
            'items' => json_encode($validatedData['items']),
            'subtotal' => $validatedData['subtotal'],
            'sst' => $validatedData['sst'],
            'rounding' => $validatedData['rounding'],
            'total' => $validatedData['total'],
            'status' => 'pending', // 订单默认是预订单
        ]);

        // Save user information
        $userInfo = UserInfo::create([
            'phone' => $validatedData['phone'],
            'numberOfCustomers' => $validatedData['numberOfCustomers'],
            'order_id' => $order->id,  // Link user info to the order
        ]);

         // Save each item in the OrderItem table
         foreach ($validatedData['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,            // Link to the created order
                'product_name' => $item['name'],     // Product name
                'quantity' => $item['quantity'],     // Quantity
                'price' => $item['price'],           // Price
                'variant_text' => $item['variant_text'] ?? null, // Variant (optional)
                'remark' => $item['remark'] ?? null, // Remark (optional)
            ]);
        }


        // Commit the transaction
        DB::commit();

        // Log the created order
        Log::info('Order created:', ['order_id' => $order->id]);

        // Return a successful JSON response
        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            'message' => 'Order saved successfully!',
        ], 201);

    } catch (\Exception $e) {
        // Rollback the transaction in case of an error
        DB::rollBack();

        // Log the exception
        Log::error('Order creation failed:', ['error' => $e->getMessage()]);

        // Return an error response
        return response()->json([
            'success' => false,
            'message' => 'Failed to save order. Please try again.',
        ], 500);
    }
}


// decode json dataType的方法 然后就可以read items里面的东西

public function showOrder($orderId)
{
    // Retrieve the order from the database
    $order = MyOrder::find($orderId);

    if ($order) {
        // Decode the JSON string into a PHP array
        $items = json_decode($order->items, true);

        // Now you can iterate over the items or manipulate them as needed
        foreach ($items as $item) {
            echo "Name: " . $item['name'] . "<br>";
            echo "Quantity: " . $item['quantity'] . "<br>";
            echo "Price: " . $item['price'] . "<br>";
        }
    } else {
        echo "Order not found.";
    }
}

 // 处理订单分配桌位
 public function assignTable($orderId)
 {
     $order = MyOrder::find($orderId);
     if (!$order || $order->status != 'pending') {
         return response()->json(['error' => 'Order not found or already seated'], 400);
     }
 
     // 获取订单的顾客数量
     $customerCount = $order->userInfo ? $order->userInfo->numberOfCustomers : 0;
     if ($customerCount <= 0) {
         return response()->json(['error' => 'Invalid customer count'], 400);
     }
 
     // 查找一个可用的桌位，且其容量足够容纳当前订单
     $table = Table::where('status', 'available')
         ->where('capacity', '>=', $customerCount)
         ->first();
 
     if ($table) {
         // 分配桌位并更新状态
         $order->table_id = $table->id;
         $order->status = 'seated';
         $order->save();
 
         // 更新桌位状态
         $table->status = 'occupied';
         $table->save();
 
         return response()->json(['success' => true, 'order' => $order, 'table' => $table]);
     } else {
         return response()->json(['error' => 'No suitable table available'], 400);
     }
 }
 

 public function completeOrder($orderId)
{
    $order = MyOrder::find($orderId);
    if (!$order || $order->status != 'seated') {
        return response()->json(['error' => 'Order not found or not seated'], 400);
    }

    // 更新订单为历史状态
    $order->status = 'history';
    $order->save();

    // 释放桌位
    $table = Table::find($order->table_id);
    if ($table) {
        $table->status = 'available';
        $table->save();
    }

    return response()->json(['success' => true, 'order' => $order]);
}


public function showWaitlist()
{
    $waitlist = MyOrder::with('userInfo')
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc') // 按创建时间降序排列
        ->get(); // 获取待分配的订单

    $seatedOrders = MyOrder::with('userInfo')
        ->where('status', 'seated')
        ->orderBy('created_at', 'desc') // 按创建时间降序排列
        ->get(); // 获取已分配桌位的订单

    $historyOrders = MyOrder::with('userInfo')
        ->where('status', 'history')
        ->orderBy('updated_at', 'desc') // 按创建时间降序排列
        ->get(); // 获取历史订单

    $tables = Table::all(); // 获取所有桌位信息

    return view('admin.order.waitlist', compact('waitlist', 'seatedOrders', 'historyOrders', 'tables'));
}


public function sendMessage(Request $request)
{
    $request->validate([
        'phone' => 'required|string',
        'message' => 'required|string',
    ]);

    // 修复电话号码格式
    $phone = $request->phone;
    if (substr($phone, 0, 1) === '0') {
        // 替换前导0为国家代码 +60
        $phone = '+60' . substr($phone, 1);
    } elseif (!str_starts_with($phone, '+')) {
        return response()->json([
            'success' => false,
            'message' => '电话号码格式无效，请使用国际格式。',
        ], 400);
    }

    $sid = 'ACf67a8e06f8237213ecfefbdd2b7a1981';
    $token = 'bcd652d306dbac8aa20f4bc35d1026e2';
    $twilio = new Client($sid, $token);

    try {
        $twilio->messages->create(
            "whatsapp:$phone", // 修复后的电话号码
            [
                "from" => "whatsapp:+14155238886", // Twilio 提供的 WhatsApp 号码
                "body" => $request->message,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'WhatsApp 消息发送成功！',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => '发送失败：' . $e->getMessage(),
        ], 500);
    }
}












}
