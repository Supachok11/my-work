<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaveRequestController;


// Authentication Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [LeaveRequestController::class, 'index'])->name('dashboard');
    Route::post('/leave-request', [LeaveRequestController::class, 'store'])->name('leave-request.store');
    Route::get('/leave-history', [LeaveRequestController::class, 'history'])->name('leave-history');
});

// Email Action Routes (ไม่ต้องมี auth เพราะเข้าจากอีเมล)
Route::get('/leave-request/approve/{id}', [LeaveRequestController::class, 'approve'])->name('leave-request.approve');
Route::get('/leave-request/reject/{id}', [LeaveRequestController::class, 'reject'])->name('leave-request.reject');
