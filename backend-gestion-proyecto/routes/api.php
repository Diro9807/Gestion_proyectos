<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;


// LOGIN / REGISTER
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


// RUTAS PROTEGIDAS
Route::middleware('auth:sanctum')->group(function () {

    // RUTAS USUARIOS
    Route::get('/user', [AuthController::class, 'index']);
    Route::get('/users', [AuthController::class, 'index']);

    // RUTAS ROLES
    Route::get('/rol', [RolController::class, 'index']);
    Route::put('/projects/{project}/users/{user}/role', [ProjectController::class, 'changeRole']);

    // RUTAS PROYECTOS
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::get('/owned-projects', [ProjectController::class, 'ownedProjects']);
    Route::get('/shared-projects', [ProjectController::class, 'sharedProjects']);

    // RUTAS USUARIOS PROYECTOS
    Route::post('/projects/{project}/users', [ProjectController::class, 'addUser']);
    Route::delete('/projects/{project}/users/{user}', [ProjectController::class, 'removeUser']);

    // RUTAS TAREAS
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

});
