<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POSController; // ✅ import the controller
use App\Http\Controllers\ReportController;

Route::get('/', [POSController::class, 'index']);
Route::post('/sale', [POSController::class, 'createSale'])->name('sale.create');
Route::get('/report', [ReportController::class, 'dailySales'])->name('report');
