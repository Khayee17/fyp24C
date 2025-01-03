<div>
    <h2>Order Details - Order ID: {{ $order->id }}</h2>
    <p><strong>Customer Count:</strong> {{ $order->customer_count }}</p>
    <p><strong>Phone:</strong> {{ $order->phone }}</p>
    <p><strong>Subtotal:</strong> {{ $order->subtotal }}</p>
    <p><strong>SST:</strong> {{ $order->sst }}</p>
    <p><strong>Rounding:</strong> {{ $order->rounding }}</p>
    <p><strong>Total:</strong> {{ $order->total }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Table:</strong> {{ $order->table_id }}</p>

    <h3>Items:</h3>
    <ul>
        @foreach ($order->items as $item)
            <li>
                <strong>{{ $item['name'] }}</strong> - Quantity: {{ $item['quantity'] }}, Price: {{ $item['price'] }}
            </li>
        @endforeach
    </ul>
</div>