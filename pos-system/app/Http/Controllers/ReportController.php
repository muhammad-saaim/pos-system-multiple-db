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
        ->selectRaw('DATE(created_at) as day, SUM(total) as total_sales, SUM(quantity) as total_items')
        ->groupBy('day')
        ->orderByDesc('day')
        ->get();

    return view('pos.report', compact('sales'));
}

}
