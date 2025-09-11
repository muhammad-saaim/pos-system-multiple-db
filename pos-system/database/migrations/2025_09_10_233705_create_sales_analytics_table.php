<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('mysql_third')->create('sales_analytics', function (Blueprint $table) {
            $table->id();
            $table->date('day');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->integer('total_quantity')->default(0);
            $table->decimal('total_sales', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['day', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::connection('mysql_third')->dropIfExists('sales_analytics');
    }
};

