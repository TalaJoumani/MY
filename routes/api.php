<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('addUser', [AdminController::class, 'addUser']);
    Route::delete('deleteUser', [AdminController::class, 'deleteUser']);
    Route::get('getAllUsers', [AdminController::class, 'getAllUsers']);
    Route::put('updateUser', [AdminController::class, 'updateUser']);
    Route::get('verifyAndCalculateCode',[AdminController::class,'verifyAndCalculateCode']);
    Route::get('getMonthlyEarningsReport', [AdminController::class, 'getMonthlyEarningsReport']);


    Route::post('generateUserCode', [UserController::class, 'generateUserCode']);
    Route::post('updateCodeStatus', [UserController::class, 'updateCodeStatus']);
    Route::get('getUserEarnings', [UserController::class, 'getUserEarnings']);
});