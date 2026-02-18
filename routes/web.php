<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('transaction');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Transaction Routes (Public/User)
Route::post('/transaction', [TransactionController::class, 'store'])->name('transaction.store');
Route::post('/transaction/inquiry', [TransactionController::class, 'inquiry'])->name('transaction.inquiry');
Route::get('/history', [TransactionController::class, 'history'])->name('history');

Route::get('/transaction/{transaction}', [TransactionController::class, 'show'])->name('transaction.show');
Route::post('/transaction/{transaction}/payment', [TransactionController::class, 'updatePayment'])->name('transaction.payment');
Route::get('/transaction/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transaction.receipt');
Route::get('/transaction/{transaction}/receipt-pos', [TransactionController::class, 'receiptPos'])->name('transaction.receipt.pos');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [TransactionController::class, 'index'])->name('dashboard');
    Route::put('/transaction/{transaction}/token', [TransactionController::class, 'updateToken'])->name('transaction.token');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
