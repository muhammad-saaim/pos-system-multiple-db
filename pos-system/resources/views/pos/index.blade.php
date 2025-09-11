<!DOCTYPE html>
<html>
<head>
    <title>Mini POS</title>
</head>
<body>
    <h1>POS System</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    {{-- ✅ DB Selection --}}
    <form method="GET" action="{{ route('pos.index') }}">
        <label>Select Database:</label>
        <select name="db" onchange="this.form.submit()">
            <option value="mysql_master" {{ $db === 'mysql_master' ? 'selected' : '' }}>Master DB</option>
            <option value="mysql_slave" {{ $db === 'mysql_slave' ? 'selected' : '' }}>Slave DB</option>
            <option value="mysql_third" {{ $db === 'mysql_third' ? 'selected' : '' }}>Third DB</option>
        </select>
    </form>

    <br>

    {{-- ✅ Sale Form --}}
    <form method="POST" action="{{ route('sale.create') }}">
        @csrf
        <input type="hidden" name="db" value="{{ $db }}">

        <label>Product:</label>
        <select name="product_id">
            @foreach($products as $product)
                <option value="{{ $product->id }}">
                    {{ $product->name }} (${{ $product->price }}) - Stock: {{ $product->stock }}
                </option>
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
