<?php

use Illuminate\Support\Facades\Route;
use Leave\LeavePortal\Http\Controllers\LeavePortalController;

/*
|--------------------------------------------------------------------------
| Tool Routes
|--------------------------------------------------------------------------
|
| Here is where you may register Inertia routes for your tool. These are
| loaded by the ServiceProvider of the tool. The routes are protected
| by your tool's "Authorize" middleware by default. Now - go build!
|
*/

Route::get('/', [LeavePortalController::class, 'index']);
