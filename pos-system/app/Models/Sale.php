<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $connection = 'mysql_master';
    protected $fillable = ['product_id', 'quantity', 'total'];
}
