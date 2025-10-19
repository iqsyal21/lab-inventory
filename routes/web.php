<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReturnController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('items.index');
});

Route::get('items/export', [ItemController::class, 'export'])->name('items.export');
Route::resource('items', ItemController::class);

Route::get('loans/export', [LoanController::class, 'export'])->name('loans.export');
Route::resource('loans', LoanController::class);

Route::get('returns/export', [ReturnController::class, 'export'])->name('returns.export');
Route::resource('returns', ReturnController::class);