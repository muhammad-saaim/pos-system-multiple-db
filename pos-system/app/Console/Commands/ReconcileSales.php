<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\SyncSaleToSlave;

class ReconcileSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reconcile-sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reconcile missing sales from master DB to slave DB';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting reconciliation...');

        // Get all sale IDs from master
        $masterSales = DB::connection('mysql_master')->table('sales')->pluck('id')->toArray();

        // Get all sale IDs already in slave
        $slaveSales = DB::connection('mysql_slave')->table('sales')->pluck('id')->toArray();

        // Find missing sales
        $missing = array_diff($masterSales, $slaveSales);

        if (empty($missing)) {
            $this->info('No missing sales found. Slave is up-to-date.');
            return;
        }

        $this->info(count($missing) . ' missing sales found. Dispatching jobs...');

        // Process in chunks to avoid memory issues
        foreach (array_chunk($missing, 100) as $chunk) {
            $sales = DB::connection('mysql_master')
                        ->table('sales')
                        ->whereIn('id', $chunk)
                        ->get();

            foreach ($sales as $sale) {
                $product = DB::connection('mysql_master')
                             ->table('products')
                             ->where('id', $sale->product_id)
                             ->first();

                // Dispatch job to slave
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
            }
        }

        $this->info('Reconciliation jobs dispatched successfully.');
    }
}
