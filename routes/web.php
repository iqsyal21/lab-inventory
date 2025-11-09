<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoanController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('items.index');
});

Route::get('items/export', [ItemController::class, 'export'])->name('items.export');
Route::resource('items', ItemController::class);

Route::get('loans/export', [LoanController::class, 'export'])->name('loans.export');
Route::get('/loans/{id}/print', [LoanController::class, 'print'])->name('loans.print');
Route::get('/loans/print-multiple', [LoanController::class, 'printMultiple'])->name('loans.printMultiple');
Route::resource('loans', LoanController::class);


Route::get('employee/export', [EmployeeController::class, 'export'])->name('employee.export');
Route::resource('employees', EmployeeController::class);
