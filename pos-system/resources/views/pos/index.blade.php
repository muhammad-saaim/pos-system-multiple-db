<!DOCTYPE html>
<html>
<head>
    <title>Mini POS</title>
</head>
<body>
    <h1>POS System</h1>

    @if(session('success')) <p style="color:green;">{{ session('success') }}</p> @endif
    @if(session('error')) <p style="color:red;">{{ session('error') }}</p> @endif

    <form method="POST" action="{{ route('sale.create') }}">
        @csrf
        <label>Product:</label>
        <select name="product_id">
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} (${{ $product->price }}) - Stock: {{ $product->stock }}</option>
            @endforeach
        </select>

        <label>Quantity:</label>
        <input type="number" name="quantity" min="1" required>

        <button type="submit">Sell</button>
    </form>

    <br>
    <a href="{{ route('report') }}">View Daily Sales Report</a>
</body>
</html>
