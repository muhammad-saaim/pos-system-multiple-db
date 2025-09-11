<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function dailyAnalytics()
    {
        // Use third DB connection
        $analytics = DB::connection('mysql_third')
            ->table('sales_analytics')
            ->select('day', 'product_name', 'total_quantity', 'total_sales')
            ->orderByDesc('day')
            ->get();

        return view('analytics.daily', compact('analytics'));
    }
}
