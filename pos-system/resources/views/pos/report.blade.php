<!DOCTYPE html>
<html>
<head>
    <title>Daily Sales Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        h1 { color: #333; }
        table { border-collapse: collapse; width: 60%; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        a { display: inline-block; margin-top: 20px; text-decoration: none; color: #007BFF; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>Daily Sales Report</h1>

    <table>
        <tr>
            <th>Date</th>
            <th>Total Items Sold</th>
            <th>Total Sales ($)</th>
        </tr>
        @forelse($sales as $sale)
        <tr>
            <td>{{ \Carbon\Carbon::parse($sale->day)->format('M d, Y') }}</td>
            <td>{{ $sale->total_items }}</td>
            <td>${{ number_format($sale->total_sales, 2) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3">No sales found</td>
        </tr>
        @endforelse
    </table>

    <a href="{{ url('/') }}">&#8592; Back to POS</a>
</body>
</html>
