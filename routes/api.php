<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/face', [AuthController::class, 'recognizeFace']);
Route::post('/user/update', [AuthController::class, 'updateUser'])->middleware('auth:sanctum');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
