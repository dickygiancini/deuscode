<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\CustomerController::class, 'index']);
Route::post('/store', [\App\Http\Controllers\CustomerController::class, 'store'])->name('store');
Route::post('/storecustomer', [\App\Http\Controllers\CustomerController::class, 'storeCustomer'])->name('store.customer');
Route::get('/edit', [\App\Http\Controllers\CustomerController::class, 'edit'])->name('edit');
Route::post('/update', [\App\Http\Controllers\CustomerController::class, 'update'])->name('update');
Route::get('/datatables', [\App\Http\Controllers\CustomerController::class, 'datatables'])->name('datatables');
Route::post('/setstatus', [\App\Http\Controllers\CustomerController::class, 'setstatus'])->name('setstatus');
