<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
   public function dailySales()
{
    $sales = DB::connection('mysql_slave')
        ->table('sales')
        ->selectRaw('DATE(created_at) as day, product_name, price, SUM(quantity) as total_items, SUM(total) as total_sales')
        ->groupBy('day', 'product_name', 'price')
        ->orderByDesc('day')
        ->get();
        $totals = DB::connection('mysql_slave')
        ->table('sales')
        ->selectRaw('DATE(created_at) as day, SUM(quantity) as total_items, SUM(total) as total_sales')
        ->groupBy('day')
        ->orderByDesc('day')
        ->get()
        ->keyBy('day'); // so we can access easily in blade

    return view('pos.report', compact('sales', 'totals'));
}
}
