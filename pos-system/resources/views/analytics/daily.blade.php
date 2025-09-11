<!DOCTYPE html>
<html>
<head>
    <title>Daily Sales Analytics</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        h1 { color: #333; }
        table { border-collapse: collapse; width: 80%; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        a { display: inline-block; margin-top: 20px; text-decoration: none; color: #007BFF; }
        a:hover { text-decoration: underline; }
        tfoot td { font-weight: bold; background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Daily Sales Analytics (Third DB)</h1>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Quantity Sold</th>
                <th>Total ($)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandQuantity = 0;
                $grandTotal = 0;
            @endphp

            @forelse($analytics as $data)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($data->day)->format('M d, Y') }}</td>
                    <td>{{ $data->product_name }}</td>
                    <td>{{ $data->total_quantity }}</td>
                    <td>${{ number_format($data->total_sales, 2) }}</td>
                </tr>
                @php
                    $grandQuantity += $data->total_quantity;
                    $grandTotal += $data->total_sales;
                @endphp
            @empty
                <tr>
                    <td colspan="4">No analytics found</td>
                </tr>
            @endforelse
        </tbody>
        @if(count($analytics) > 0)
        <tfoot>
            <tr>
                <td colspan="2">Grand Total</td>
                <td>{{ $grandQuantity }}</td>
                <td>${{ number_format($grandTotal, 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <a href="{{ url('/') }}">&#8592; Back to POS</a>
</body>
</html>
