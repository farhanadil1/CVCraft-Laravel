<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;


//Auth Routes 

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Protected routes
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('verify.jwt');


//Resume Routes
Route::middleware('verify.jwt')->group(function () {

    Route::post('/resume/create', [ResumeController::class, 'store']);

    Route::get('/resume/my', [ResumeController::class, 'index']);

    Route::get('/resume/my/{id}', [ResumeController::class, 'show']);

    Route::put('/resume/update/{id}', [ResumeController::class, 'update']);

    Route::delete('/resume/delete/{id}', [ResumeController::class, 'destroy']);
});
// Admin routes
Route::middleware('verify.jwt')->group(function () {

    Route::get('/admin/users', [AdminController::class, 'getAllUsers']);
});
