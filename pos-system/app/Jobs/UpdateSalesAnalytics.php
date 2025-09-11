<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateSalesAnalytics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $saleData;

    public function __construct(array $saleData)
    {
        $this->saleData = $saleData;
    }

    public function handle(): void
    {
        $dbThird = 'mysql_third';

        DB::connection($dbThird)->table('sales_analytics')->updateOrInsert(
            [
                'day' => $this->saleData['day'],
                'product_id' => $this->saleData['product_id']
            ],
            [
                'product_name' => $this->saleData['product_name'],
                'total_quantity' => DB::raw("total_quantity + {$this->saleData['quantity']}"),
                'total_sales' => DB::raw("total_sales + {$this->saleData['total']}"),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
