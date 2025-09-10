<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportSale extends Model
{
    protected $table = 'sales'; // assumes sales table

    // 🔑 Tell this model to use the slave DB
    protected $connection = 'mysql_slave';

    protected $fillable = [
        'product_name',
        'quantity',
        'price',
        'total',
        'created_at',
    ];
}
