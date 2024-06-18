<?php

use App\Http\Controllers\BuildingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FiltersController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TasksStatusesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index']);

Route::get('/buildings', [BuildingController::class, 'index']);

Route::get('/status', [TasksStatusesController::class, 'index']);

Route::post('/tasks', [TaskController::class, 'store']);
Route::get('/tasks/{id}', [TaskController::class, 'tasksByBuilding']);
Route::put('/tasks/{id}', [TaskController::class, 'update']);

Route::post('/comment', [CommentController::class, 'store']);
Route::put('/comment/{id}', [CommentController::class, 'update']);

Route::get('/filters', [FiltersController::class, 'index']);