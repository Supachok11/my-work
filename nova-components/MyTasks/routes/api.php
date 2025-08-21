<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Company\MyTasks\Http\Controllers\MyTasksController;

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

Route::get('/my-tasks', [MyTasksController::class, 'index']);
Route::get('/task/{id}', [MyTasksController::class, 'show']);
Route::post('/task/{id}/update-status', [MyTasksController::class, 'updateStatus']);
Route::post('/task/{id}/add-comment', [MyTasksController::class, 'addComment']);
