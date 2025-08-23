<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// routes/api.php

use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-face', [AuthController::class, 'verifyFace']);
Route::post('/face-recognition', [AuthController::class, 'recognizeFace']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
