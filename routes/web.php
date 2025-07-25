<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\CategoryController;

Route::get('/', [TaskController::class, 'index']);

Route::resource('tasks', TaskController::class);
Route::resource('subtasks', SubTaskController::class)->except(['index', 'create', 'show']);

Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');


Route::patch('/subtasks/bulk-toggle', [SubTaskController::class, 'bulkToggle'])->name('subtasks.bulk-toggle');

Route::put('/categories/{category}', [CategoryController::class, 'update']);
// Atau jika pakai resource:
Route::resource('categories', CategoryController::class)->except(['show']);

Route::patch('/subtasks/{subtask}/toggle', [TaskController::class, 'toggleSubtask'])->name('subtasks.toggle');