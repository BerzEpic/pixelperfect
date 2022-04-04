<?php

use App\Http\Controllers\AuthUsersController;
use App\Http\Controllers\IssueController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('/register', [AuthUsersController::class, 'register']);
Route::post('/login', [AuthUsersController::class, 'login']);


// Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
	Route::get('/issues', [IssueController::class, 'index'])->middleware('ability:display-issues');
	Route::get('/issues/{id}', [IssueController::class, 'show'])->middleware('ability:display-issue');
    Route::post('/issues', [IssueController::class, 'store'])->middleware('ability:create-issue');
    Route::put('/issues/{id}', [IssueController::class, 'update'])->middleware('ability:update-issue');
    Route::put('/issues/status/{id}', [IssueController::class, 'updatestatus'])->middleware('ability:update-status');
    Route::post('/logout', [AuthUsersController::class, 'logout']);
});




