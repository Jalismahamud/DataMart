@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Order Details</h3>
    </div>
    <div class="card-body">
        <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
        <p><strong>Phone:</strong> {{ $order->phone }}</p>
        <p><strong>Address:</strong> {{ $order->address }}</p>
        <p><strong>City:</strong> {{ $order->city }}</p>
        <p><strong>Product:</strong> {{ $order->product?->name }}</p>
        <p><strong>Variation:</strong> {{ $order->variation?->name }}</p>
        <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
        <p><strong>Unit Price:</strong> {{ number_format($order->unit_price, 0) }} ৳</p>
        <p><strong>Shipping:</strong> {{ number_format($order->shipping_cost, 0) }} ৳</p>
        <p><strong>Total:</strong> {{ number_format($order->total_amount, 0) }} ৳</p>
        <p><strong>Status:</strong> {{ $order->status }}</p>
    </div>
</div>
@endsection
