<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AnalyticsController;

// POS Routes
Route::get('/', [POSController::class, 'index'])->name('pos.index');
Route::post('/sale', [POSController::class, 'createSale'])->name('sale.create');

// Report (Slave DB) Route
Route::get('/report', [ReportController::class, 'dailySales'])->name('report');

// Analytics (Third DB) Route
Route::get('/analytics', [AnalyticsController::class, 'dailyAnalytics'])->name('analytics.daily');
