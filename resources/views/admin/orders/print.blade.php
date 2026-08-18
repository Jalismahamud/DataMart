<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #111827;
        }
        .invoice-box {
            max-width: 700px;
            margin: 0 auto;
            border: 1px solid #e5e7eb;
            padding: 30px;
            border-radius: 12px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #111827;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        h1 {
            margin: 0;
            font-size: 28px;
        }
        .meta {
            text-align: right;
            font-size: 14px;
        }
        .section {
            margin-top: 20px;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            min-width: 130px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #f3f4f6;
        }
        .totals {
            margin-top: 18px;
            width: 250px;
            margin-left: auto;
        }
        .totals tr td:last-child {
            text-align: right;
            font-weight: bold;
        }
        .footer {
            margin-top: 28px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div>
                <h1>DadaGarments</h1>
            </div>
            <div class="meta">
                <div><strong>Invoice:</strong> {{ $order->invoice_number }}</div>
                <div><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</div>
            </div>
        </div>

        <div class="section grid">
            <div>
                <div><span class="label">Customer:</span> {{ $order->customer_name }}</div>
                <div><span class="label">Phone:</span> {{ $order->phone }}</div>
                <div><span class="label">Address:</span> {{ $order->address }}</div>
                <div><span class="label">City:</span> {{ $order->city }}</div>
            </div>
            <div>
                <div><span class="label">Product:</span> {{ $order->product?->name ?? '—' }}</div>
                <div><span class="label">Variation:</span> {{ $order->variation?->name ?? '—' }}</div>
                <div><span class="label">Size:</span> {{ $order->selected_size ?? '—' }}</div>
                <div><span class="label">Color:</span> {{ $order->selected_color ?? '—' }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $order->product?->name ?? 'Product' }} ({{ $order->variation?->name ?? 'Variation' }})</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ number_format($order->unit_price, 2) }} ৳</td>
                    <td>{{ number_format($order->unit_price * $order->quantity, 2) }} ৳</td>
                </tr>
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <td>Shipping</td>
                <td>{{ number_format($order->shipping_cost, 2) }} ৳</td>
            </tr>
            <tr>
                <td>Grand Total</td>
                <td>{{ number_format($order->total_amount, 2) }} ৳</td>
            </tr>
        </table>

        <div class="footer">
            <div>Thank you for shopping with DadaGarments.</div>
            <div>Payment Method: {{ $order->payment_method }}</div>
        </div>

        <div class="no-print" style="margin-top: 24px; text-align: center;">
            <button onclick="window.print()" style="padding: 10px 18px; border: none; background: #111827; color: #fff; border-radius: 8px; cursor: pointer;">Print</button>
        </div>
    </div>
</body>
</html>
