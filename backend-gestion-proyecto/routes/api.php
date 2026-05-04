<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\ProjectController;

Route::get('/user', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/rol', [RolController::class, 'index']);
Route::apiResource('projects', ProjectController::class);

