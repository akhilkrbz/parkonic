<?php

use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', [Dashboard::class, 'index']);


Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');
Route::get('/dashboardSummary', [Dashboard::class, 'dashboardSummary'])->name('dashboardSummary');