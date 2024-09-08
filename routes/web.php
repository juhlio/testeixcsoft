<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth'])->group(function () {
    Route::get('/transfer', [TransactionController::class, 'showTransferForm'])->name('transfer.page');
    Route::post('/transfer', [TransactionController::class, 'transfer'])->name('transfer');
});
