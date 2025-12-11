<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);

    Route::resource('customers', CustomerController::class);

    Route::resource('projects', ProjectController::class);

    Route::resource('tasks', TaskController::class);

    Route::resource('orders', OrderController::class);

    Route::resource('finances', FinanceController::class);

    Route::resource('roles', RoleController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
