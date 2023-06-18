<h1>Receipt</h1>

<p>Status: {{ $order->status }}</p>
<p>Customer ID: {{ $order->customer_id }}</p>
<p>Date: {{ $order->date }}</p>
<p>Total Price: {{ $order->total_price }}</p>

<h2>Order Items</h2>
<ul>
    @foreach ($order->orderItems as $orderItem)
        <li>
            Size: {{ $orderItem->size }},
            Color: {{ $orderItem->color_code }},
            Quantity: {{ $orderItem->qty }},
            T-Shirt Image ID: {{ $orderItem->tshirt_image_id }}
        </li>
    @endforeach
</ul>