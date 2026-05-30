<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\ReceiptController;

// Auth Routes (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('home');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected Routes (JWT Session Middleware)
Route::middleware('jwt')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users CRUD
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Rooms CRUD
    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('index');
        Route::get('/create', [RoomController::class, 'create'])->name('create');
        Route::post('/', [RoomController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [RoomController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RoomController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoomController::class, 'destroy'])->name('destroy');
    });

    // Items CRUD
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index');
        Route::get('/create', [ItemController::class, 'create'])->name('create');
        Route::post('/', [ItemController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ItemController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ItemController::class, 'update'])->name('update');
        Route::delete('/{id}', [ItemController::class, 'destroy'])->name('destroy');
    });

    // Procurement Routes (Kalab & Kaprodi)
    Route::prefix('procurements')->name('procurements.')->group(function () {
        Route::get('/', [ProcurementController::class, 'index'])->name('index');
        Route::get('/create', [ProcurementController::class, 'create'])->name('create');
        Route::post('/', [ProcurementController::class, 'store'])->name('store');
        Route::get('/{id}', [ProcurementController::class, 'show'])->name('show');
        Route::post('/{id}/submit', [ProcurementController::class, 'submit'])->name('submit');
        Route::post('/detail/{detailId}/review', [ProcurementController::class, 'review'])->name('review');
        Route::post('/{id}/finalize', [ProcurementController::class, 'finalize'])->name('finalize');
    });

    // Item Receipt Routes (Staf Admin & Admin)
    Route::prefix('receipts')->name('receipts.')->group(function () {
        Route::get('/', [ReceiptController::class, 'index'])->name('index');
        Route::get('/create', [ReceiptController::class, 'create'])->name('create');
        Route::post('/', [ReceiptController::class, 'store'])->name('store');
    });
});
