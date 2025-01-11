<div class="order-details-container" style="font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; border: 1px solid #ddd; border-radius: 8px; padding: 20px; background-color: #f9f9f9;">
    <!-- 订单标题 -->
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 24px;">Order ID: {{ $order->id }}</h2>
        <p><strong>Status:</strong> <span class="badge badge-{{ $order->status == 'completed' ? 'success' : 'warning' }}">{{ ucfirst($order->status) }}</span></p>
    </div>

    <!-- 客户信息 -->
    <div style="margin-bottom: 20px;">
        <h3 style="font-size: 18px; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px;">Customer Info</h3>
        <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
            <tr>
                <td style="padding: 5px; font-weight: bold;">Customer Count:</td>
                <td style="padding: 5px;">{{ $order->customer_count }}</td>
            </tr>
            <tr>
                <td style="padding: 5px; font-weight: bold;">Phone:</td>
                <td style="padding: 5px;">{{ $order->phone }}</td>
            </tr>
        </table>
    </div>

    <!-- 订单项目 -->
    <div>
        <h3 style="font-size: 18px; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px;">Order Items</h3>
        <ul style="font-size: 14px; padding-left: 20px;">
            @foreach ($order->items as $item)
                <div class="order-item" style="display: flex; margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 8px; background-color: #fff;">
                    
                    <div class="item-details" style="flex-grow: 1; font-size: 14px;">
                        <p style="margin: 0; font-weight: bold;">{{ $item['name'] }}</p>
                        <p class="item-note" style="margin: 5px 0; color: #666;">{{ $item['variantText'] ?? '' }}</p>
                        <p class="item-price" style="margin: 0; font-size: 14px;">
                        RM {{ number_format($item['price'], 2) }}
                            <span class="item-quantity" style="color: #666;">x {{ $item['quantity'] }}</span>
                        </p>
                        

                    </div>
                </div>
            @endforeach
        </ul>
    </div>

    <!-- 费用信息 -->
    <div style="margin-bottom: 20px;">
        <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
            <tr>
                <td style="padding: 5px; font-weight: bold;">Subtotal : </td>
                <td style="padding: 5px;">RM {{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 5px; font-weight: bold;">SST : </td>
                <td style="padding: 5px;">RM {{ number_format($order->sst, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 5px; font-weight: bold;">Rounding :</td>
                <td style="padding: 5px;">RM {{ number_format($order->rounding, 2) }}</td>
            </tr>
            <tr style="border-top: 1px solid #ddd;">
                <td style="padding: 5px; font-weight: bold; font-size: 16px;">Total: </td>
                <td style="padding: 5px; font-size: 16px; color: #e74c3c;">RM {{ number_format($order->total, 2) }}</td>
            </tr>
        </table>
    </div>
</div>
