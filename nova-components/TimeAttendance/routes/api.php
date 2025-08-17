<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Company\TimeAttendance\Http\Controllers\TimeAttendanceController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::post('/clock-in', [TimeAttendanceController::class, 'clockIn']);
Route::post('/clock-out', [TimeAttendanceController::class, 'clockOut']);
Route::post('/location', [TimeAttendanceController::class, 'getLocation']);
Route::get('/stats', [TimeAttendanceController::class, 'getStats']);
