<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mysql_master';
    protected $fillable = ['name', 'price', 'stock'];
}
