<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\SavingAccountController;
use App\Http\Controllers\FundTransferController;


Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/', function () {
    
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('accounts.my'); // default for 'user' or others
    }

    return redirect()->route('accounts.my');
})->middleware('auth');

Route::middleware(['auth'])->group(function () {

	Route::view('/two-factor-auth', 'profile.two-factor-auth')->name('two-factor-auth');;
    
    Route::get('/exchange-rate', [FundTransferController::class, 'getExchangeRate']);
    Route::get('/my-accounts', [AccountController::class, 'myAccounts'])->name('accounts.my');

    Route::middleware('admin')->group(function () {
        Route::post('/accounts', [SavingAccountController::class, 'store'])->name('accounts.store');
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users'); // Used
        Route::get('admin/user-accounts', [AdminController::class, 'accountList'])->name('admin.account.list');
    });

    Route::get('/transactions/history', [TransactionController::class, 'history'])->name('transactions.history');
    Route::get('/transactions/history/ajax', [TransactionController::class, 'historyAjax'])->name('transactions.history.ajax');

    Route::get('/transfer', [FundTransferController::class, 'create'])->name('transfer.form');
    Route::post('/transfer', [FundTransferController::class, 'store'])->name('transfer.store');
    
});



