<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::resource('websites', WebsiteController::class)->except(['create', 'edit']);
    Route::post('websites/{id}/vote', [WebsiteController::class, 'vote']);
    Route::post('websites/{id}/unvote', [WebsiteController::class, 'unvote']);
    Route::resource('categories', CategoryController::class)->except(['create', 'edit']);

    Route::middleware('admin')->group(function () {
        Route::delete('websites/{id}', [WebsiteController::class, 'destroy']);
    });
});

Route::get('websites', [WebsiteController::class, 'index']);
Route::get('categories', [CategoryController::class, 'index']);
