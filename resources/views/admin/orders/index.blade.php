@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Orders</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 mb-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by invoice or phone number...">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Product</th>
                        <th>City</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->invoice_number ?? '—' }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->phone }}</td>
                            <td>{{ $order->product?->name }}</td>
                            <td>{{ $order->city }}</td>
                            <td>{{ number_format($order->total_amount, 0) }} ৳</td>
                            <td>{{ $order->status }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
