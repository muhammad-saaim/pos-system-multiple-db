<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    // Show POS form
    public function index()
    {
        $products = Product::all();
        return view('pos.index', compact('products'));
    }

    // Record a sale
    public function createSale(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock');
        }

        $total = $product->price * $request->quantity;

        // Insert into Master DB
        $sale = Sale::create([
            'product_id' => $product->id,
            'quantity'   => $request->quantity,
            'total'      => $total,
        ]);

        // Decrement stock in Master DB
        $product->decrement('stock', $request->quantity);

        DB::connection('mysql_slave')->table('sales')->insert([
            'id'         => $sale->id,
            'product_id' => $sale->product_id,
            'quantity'   => $sale->quantity,
            'total'      => $sale->total,
            'created_at' => $sale->created_at,
            'updated_at' => $sale->updated_at,
        ]);
        return back()->with('success', 'Sale recorded! Total: $' . number_format($total, 2));
    }
}
