<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['product', 'variation'])->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $orders = $query->get();

        return view('admin.orders.index', compact('orders', 'search'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function print(Order $order)
    {
        return view('admin.orders.print', compact('order'));
    }
}
