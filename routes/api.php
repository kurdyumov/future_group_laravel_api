<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskManagerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/tasks', [TaskManagerController::class, 'post_task']);
Route::get('/tasks', [TaskManagerController::class, 'get_tasks']);
Route::get('/tasks/{id}', [TaskManagerController::class, 'get_task']);
Route::put('/tasks/{id}', [TaskManagerController::class, 'put_task']);
Route::delete('/tasks/{id}', [TaskManagerController::class, 'delete_task']);
