<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin']);
    Route::post('/', [AuthController::class, 'login']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tool Catalog & Inventory Management
    Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
    Route::get('/tools/{id}', [ToolController::class, 'show'])->name('tools.show')->where('id', '[0-9]+');

    // Custodian & Admin Tool Management
    Route::middleware('role:admin,custodian')->group(function () {
        Route::get('/tools/create', [ToolController::class, 'create'])->name('tools.create');
        Route::post('/tools', [ToolController::class, 'store'])->name('tools.store');
        Route::get('/tools/{id}/edit', [ToolController::class, 'edit'])->name('tools.edit')->where('id', '[0-9]+');
        Route::put('/tools/{id}', [ToolController::class, 'update'])->name('tools.update')->where('id', '[0-9]+');
        Route::delete('/tools/{id}', [ToolController::class, 'destroy'])->name('tools.destroy')->where('id', '[0-9]+');
    });

    // Borrowing Transactions & Flow
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::get('/borrowings/{id}', [BorrowingController::class, 'show'])->name('borrowings.show')->where('id', '[0-9]+');

    // Custodian Approval, Release & Return Handover Actions
    Route::middleware('role:admin,custodian')->group(function () {
        Route::post('/borrowings/{id}/approve', [BorrowingController::class, 'approve'])->name('borrowings.approve');
        Route::post('/borrowings/{id}/reject', [BorrowingController::class, 'reject'])->name('borrowings.reject');
        Route::post('/borrowings/{id}/release', [BorrowingController::class, 'release'])->name('borrowings.release');
        Route::post('/borrowings/{id}/return', [BorrowingController::class, 'processReturn'])->name('borrowings.return');

        // User Management & Approval Queue
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{id}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::post('/users/{id}/reject', [UserController::class, 'reject'])->name('users.reject');
        Route::put('/users/{id}/role', [UserController::class, 'updateRole'])->name('users.update-role');

        // Reports & Print Outputs
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('/reports/borrowing-history', [ReportController::class, 'borrowingHistory'])->name('reports.borrowing_history');
        Route::get('/reports/overdue', [ReportController::class, 'overdue'])->name('reports.overdue');
        Route::get('/reports/condition-audit', [ReportController::class, 'conditionAudit'])->name('reports.condition_audit');
    });
});
