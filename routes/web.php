<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Pos\PosController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Redirect after login based on role
Route::get('/dashboard', function () {
    if (auth()->user()->isOwner()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('pos.index');
})->middleware(['auth'])->name('dashboard');

// Admin routes (owner only)
Route::prefix('admin')
    ->middleware(['auth', 'role:owner'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{order}', [TransactionController::class, 'show'])->name('transactions.show');
    });

// POS routes (kasir and owner can access)
Route::prefix('pos')
    ->middleware(['auth', 'role:kasir'])
    ->name('pos.')
    ->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::get('/products', [PosController::class, 'products'])->name('products');
        Route::post('/calculate', [PosController::class, 'calculate'])->name('calculate');
        Route::post('/checkout', [PosController::class, 'checkout'])->name('checkout');
        Route::get('/receipt/{order}', [PosController::class, 'receipt'])->name('receipt');
    });

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
