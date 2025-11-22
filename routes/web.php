<?php

use App\Http\Controllers\Dashboard;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [Dashboard::class, 'index']);


Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');
Route::get('/dashboardSummary', [Dashboard::class, 'dashboardSummary'])->name('dashboardSummary');
Route::get('/dailyHourlySessionTracker', [Dashboard::class, 'dailyHourlySessionTracker'])->name('dailyHourlySessionTracker');
Route::get('/sessionsByBuilding', [Dashboard::class, 'sessionsByBuilding'])->name('sessionsByBuilding');

Route::get('/sessions_report', [ReportController::class, 'sessionsReport'])->name('sessions_report');
Route::get('/sessionsReportFilter', [ReportController::class, 'sessionsReportFilter'])->name('sessionsReportFilter');