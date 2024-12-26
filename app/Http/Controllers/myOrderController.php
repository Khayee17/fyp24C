<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\userInfo;
use App\Models\MyOrder;
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
        'customer_count' => 'required|integer|min:1',
        'items' => 'required|array',
        'items.*.name' => 'required|string',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric',
        'items.*.remark' => 'nullable|string',
        'subtotal' => 'required|numeric',
        'sst' => 'required|numeric',
        'rounding' => 'required|numeric',
        'total' => 'required|numeric',
    ]);

    // Log validated data
    Log::info('Validated data:', $validatedData);

    try {
        // Save the order data to the database
        $order = MyOrder::create([
            'customer_count' => $validatedData['customer_count'],
            'items' => json_encode($validatedData['items']),
            'subtotal' => $validatedData['subtotal'],
            'sst' => $validatedData['sst'],
            'rounding' => $validatedData['rounding'],
            'total' => $validatedData['total'],
        ]);

        // Log created order
        Log::info('Order created:', $order->toArray());

        // Return a successful JSON response
        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'message' => 'Order saved successfully!',
        ], 201);
    } catch (\Exception $e) {
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
}
