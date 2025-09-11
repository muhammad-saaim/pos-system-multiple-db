<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Jobs\SyncSaleToSlave;
use App\Jobs\UpdateSalesAnalytics; // ✅ Add this for third DB analytics

class POSController extends Controller
{
    // Show POS form or redirect to appropriate view
    public function index(Request $request)
    {
        // Default = master
        $db = $request->input('db', 'mysql_master');

        // Redirect if slave or third DB selected
        if ($db === 'mysql_slave') {
            return redirect()->route('report');
        } elseif ($db === 'mysql_third') {
            return redirect()->route('analytics.daily');
        }

        // Default: master DB products
        $products = (new Product)->setConnection($db)->get();

        return view('pos.index', compact('products', 'db'));
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

        // ✅ Dispatch background job to sync with Slave DB
        SyncSaleToSlave::dispatch([
            'id'           => $sale->id,
            'product_id'   => $sale->product_id,
            'product_name' => $product->name,
            'price'        => $product->price,
            'quantity'     => $sale->quantity,
            'total'        => $sale->total,
            'created_at'   => $sale->created_at,
            'updated_at'   => $sale->updated_at,
        ]);

        // ✅ Dispatch background job to update Third DB analytics
        UpdateSalesAnalytics::dispatch([
            'day'          => $sale->created_at->format('Y-m-d'),
            'product_id'   => $sale->product_id,
            'product_name' => $product->name,
            'quantity'     => $sale->quantity,
            'total'        => $sale->total,
        ]);

        return back()->with('success', 'Sale recorded! Total: $' . number_format($total, 2));
    }
}
