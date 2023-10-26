<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PaymentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users', [UserController::class,'show']);
Route::post('users', [UserController::class,'signup']);
Route::get('loans/{id}', [LoanController::class,'show']);
Route::post('loans/{id}/request', [LoanController::class,'LoanRequest']);
Route::put('admin/{id}/request', [AdminController::class,'LoanRequest']);
Route::put('repayment/{id}', [PaymentController::class,'Repayment']);
