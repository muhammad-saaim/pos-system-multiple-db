<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SyncSaleToSlave implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $saleData;

    /**
     * Create a new job instance.
     *
     * @param array $saleData
     */
    public function __construct(array $saleData)
    {
        $this->saleData = $saleData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::connection('mysql_slave')->table('sales')->insert($this->saleData);
    }
}
