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

    // 查找可用的桌位，按容量排序
    $availableTables = Table::where('status', 'available')
                             ->where('capacity', '>=', 1)  // 保证桌位有足够容量
                             ->orderBy('capacity', 'asc')
                             ->get();

     // 尝试寻找单个桌位是否能够满足顾客数量
    $assignedTables = [];
    $totalCapacity = 0;

    foreach ($availableTables as $table) {
         // 如果当前桌位容量刚好等于顾客数量，则分配该桌位
        if ($totalCapacity + $table->capacity == $customerCount) {
            $assignedTables[] = $table;
            $totalCapacity += $table->capacity;
            break; 
        }
     }

    // 如果没有刚好适合的桌位，继续按容量递增的顺序进行分配
    if ($totalCapacity < $customerCount) {
        foreach ($availableTables as $table) {
            if ($totalCapacity >= $customerCount) break;  

            $assignedTables[] = $table;
            $totalCapacity += $table->capacity;
        }
    }

    // 检查是否有足够的桌位
    if ($totalCapacity < $customerCount) {
        return response()->json(['error' => 'No suitable tables available'], 400);
    }

    // 更新订单状态和分配的桌位，存储多个桌位ID
    $tableIds = collect($assignedTables)->pluck('id')->toArray(); // 使用 collect 将数组转为集合
    $order->table_ids = json_encode($tableIds); // 将多个桌位ID存储为JSON格式
    $order->status = 'seated';
    $order->save();

    // 更新桌位状态
    foreach ($assignedTables as $table) {
        $table->status = 'occupied';
        $table->save();
    }

    return response()->json(['success' => true, 'order' => $order, 'assignedTables' => $assignedTables]);
}




 public function completeOrder(Request $request, $orderId)
{
    $order = MyOrder::find($orderId);
    if (!$order || $order->status != 'seated') {
        return response()->json(['error' => 'Order not found or not seated'], 400);
    }

    $tableIds = json_decode($order->table_ids, true);
    if (is_array($tableIds)) {
        foreach ($tableIds as $tableId) {
            $table = Table::find($tableId);
            if ($table) {
                $table->status = 'available';
                $table->save();
            }
        }
    }

    $order->status = 'history';
    $order->save();

    return response()->json(['success' => true, 'order' => $order]);
}


public function showWaitlist()
{
    $waitlist = MyOrder::with('userInfo')
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc') 
        ->get(); 

    $seatedOrders = MyOrder::with('userInfo')
        ->where('status', 'seated')
        ->orderBy('updated_at', 'desc')
        ->get(); 

    $historyOrders = MyOrder::with('userInfo')
        ->where('status', 'history')
        ->orderBy('updated_at', 'desc') 
        ->get(); 

    $tables = Table::all(); 

     foreach ($seatedOrders as $order) {
        $tableIds = json_decode($order->table_ids, true);
        $order->tableNames = Table::whereIn('id', $tableIds)->pluck('name')->toArray();
    }

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
            'message' => 'Invalid phone number format, please use international format.',
        ], 400);
    }

    $sid='ACf67a8e06f8237213ecfefbdd2b7a1981';
    $token='d39ecf05e2b62f3d4e7805d9b53cfd64';
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
            'message' => 'WhatsApp message sent successfully!',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Send failed:' . $e->getMessage(),
        ], 500);
    }
}

public function show($id)
{
    $order = MyOrder::findOrFail($id);
    $order->items = json_decode($order->items, true); // 解码 JSON 数据
    $tableIds = json_decode($order->table_ids, true) ?? []; // 确保 table_ids 存在
    $tables = Table::whereIn('id', $tableIds)->get();

    Log::debug('Order Items:', $order->items);
    Log::debug('Table IDs:', $tableIds);
    
    // 返回一个只包含订单详情内容的视图
    return view('admin.order.myOrderDetails', [
        'order' => $order,
        'tables' => $tables,
    ]);
}

public function getEstimatedWaitingTime()
{
    $totalTables = Table::count(); 

    $seatedOrders = MyOrder::where('status', 'seated')->count(); 

    $pendingOrders = MyOrder::where('status', 'pending')->count(); 

    // 餐厅未满座，pending 无订单
    if ($seatedOrders < $totalTables && $pendingOrders == 0) {
        return response()->json([
            'estimated_waiting_time' => 0,
            'groups_ahead' => 0
        ]);
    }

    // 餐厅未满座，pending 有订单
    if ($seatedOrders < $totalTables && $pendingOrders > 0) {
        $averageDiningTime = 60; // 假设每组顾客用餐时间为60分钟
        $estimatedWaitTime = $pendingOrders * $averageDiningTime;
        return response()->json([
            'estimated_waiting_time' => $estimatedWaitTime,
            'groups_ahead' => $pendingOrders
        ]);
    }

    // 餐厅满座，pending 有订单
    if ($seatedOrders >= $totalTables && $pendingOrders > 0) {
        $averageDiningTime = 60; 
        $estimatedWaitTime = $pendingOrders * $averageDiningTime;
        return response()->json([
            'estimated_waiting_time' => $estimatedWaitTime,
            'groups_ahead' => $pendingOrders
        ]);
    }

    // 餐厅满座，pending 无订单
    if ($seatedOrders >= $totalTables && $pendingOrders == 0) {
        // 获取最早的“seated”订单
        $seatedOrder = MyOrder::where('status', 'seated')
            ->orderBy('updated_at', 'asc')
            ->first(); 

        if ($seatedOrder) {
            $averageDiningTime = 60; 
            $releaseTime = \Carbon\Carbon::parse($seatedOrder->updated_at)->addMinutes($averageDiningTime);
            $currentTime = \Carbon\Carbon::now();
            $estimatedWaitTime = $releaseTime->diffInMinutes($currentTime); 
            return response()->json([
                'estimated_waiting_time' => $estimatedWaitTime,
                'groups_ahead' => 0
            ]);
        }
    }

}


//order management
public function index(Request $request)
    {
        $query = MyOrder::query();

        // 按日期过滤
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $orders = $query->latest()->paginate(10); // 分页显示订单

        foreach ($orders as $order) {
            $order->items = json_decode($order->items, true); // 确保 items 是数组
        }
        
        return view('admin.order.orderlist', [
            'orders' => $orders,
        ]);
    }

// 单个订单删除
public function deleteOrder($orderId)
{
    $order = MyOrder::find($orderId);

    $order->delete();

    return redirect()->route('orders.index')->with('success', 'Order "' . $order->id . '" deleted successfully.');
}

// 批量订单删除
public function deleteSelectedOrders(Request $request)
{
    $orderIds = $request->input('order_ids');

    $deletedCount = MyOrder::whereIn('id', $orderIds)->delete();

    session()->flash('success', "$deletedCount orders deleted successfully.");

    return response()->json(['success' => true]);


}










}
