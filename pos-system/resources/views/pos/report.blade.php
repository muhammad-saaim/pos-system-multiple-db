<!DOCTYPE html>
<html>
<head>
    <title>Daily Sales Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        h1 { color: #333; }
        table { border-collapse: collapse; width: 90%; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        a { display: inline-block; margin-top: 20px; text-decoration: none; color: #007BFF; }
        a:hover { text-decoration: underline; }
        .total-row { font-weight: bold; background: #eaeaea; }
    </style>
</head>
<body>
    <h1>Daily Sales Report</h1>

    <table>
        <tr>
            <th>Date</th>
            <th>Product</th>
            <th>Price ($)</th>
            <th>Total Items Sold</th>
            <th>Total Sales ($)</th>
        </tr>
        @php $currentDay = null; @endphp

        @forelse($sales as $sale)
            @if($currentDay !== $sale->day)
                {{-- Close previous day section with totals --}}
                @if($currentDay !== null)
                    <tr class="total-row">
                        <td colspan="3">Total for {{ \Carbon\Carbon::parse($currentDay)->format('M d, Y') }}</td>
                        <td>{{ $totals[$currentDay]->total_items }}</td>
                        <td>${{ number_format($totals[$currentDay]->total_sales, 2) }}</td>
                    </tr>
                @endif

                {{-- Start new day --}}
                @php $currentDay = $sale->day; @endphp
            @endif

            <tr>
                <td>{{ \Carbon\Carbon::parse($sale->day)->format('M d, Y') }}</td>
                <td>{{ $sale->product_name }}</td>
                <td>${{ number_format($sale->price, 2) }}</td>
                <td>{{ $sale->total_items }}</td>
                <td>${{ number_format($sale->total_sales, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No sales found</td>
            </tr>
        @endforelse

        {{-- Add total row for the last day --}}
        @if($currentDay !== null)
            <tr class="total-row">
                <td colspan="3">Total for {{ \Carbon\Carbon::parse($currentDay)->format('M d, Y') }}</td>
                <td>{{ $totals[$currentDay]->total_items }}</td>
                <td>${{ number_format($totals[$currentDay]->total_sales, 2) }}</td>
            </tr>
        @endif
    </table>

    <a href="{{ url('/') }}">&#8592; Back to POS</a>
</body>
</html>
