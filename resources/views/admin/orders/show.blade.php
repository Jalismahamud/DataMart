@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Order Details</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="btn btn-outline-primary btn-sm">Print Slip</a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Invoice Number:</strong> {{ $order->invoice_number }}</p>
                <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                <p><strong>Phone:</strong> {{ $order->phone }}</p>
                <p><strong>Address:</strong> {{ $order->address }}</p>
                <p><strong>Delivery Area:</strong> {{ $order->city }}</p>
                <p><strong>Delivery Zone:</strong> {{ $order->delivery_zone }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Product:</strong> {{ $order->product?->name }}</p>
                <p><strong>Variation:</strong> {{ $order->variation?->name }}</p>
                <p><strong>Selected Size:</strong> {{ $order->selected_size ?? '—' }}</p>
                <p><strong>Selected Color:</strong> {{ $order->selected_color ?? '—' }}</p>
                <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
                <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
            </div>
        </div>

        <hr>

        <p><strong>Unit Price:</strong> {{ number_format($order->unit_price, 0) }} ৳</p>
        <p><strong>Shipping Cost:</strong> {{ number_format($order->shipping_cost, 0) }} ৳</p>
        <p><strong>Total Amount:</strong> {{ number_format($order->total_amount, 0) }} ৳</p>
        <p><strong>Status:</strong> {{ $order->status }}</p>
        <p><strong>Notes:</strong> {{ $order->notes ?? '—' }}</p>
    </div>
</div>
@endsection
