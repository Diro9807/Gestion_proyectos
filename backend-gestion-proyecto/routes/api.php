<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;


// RUTAS INICIO SESIÓN
Route::get('/user', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/rol', [RolController::class, 'index']);

// RUTAS PROYECTO
Route::post('/projects', [ProjectController::class, 'store']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
Route::put('/projects/{project}', [ProjectController::class, 'update']);

// RUTAS TAREAS
Route::get('/projects/{id}/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::put('/tasks/{task}', [TaskController::class, 'update']);
Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
