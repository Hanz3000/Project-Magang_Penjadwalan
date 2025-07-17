<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubTaskController;

Route::get('/', [TaskController::class, 'index']);

Route::resource('tasks', TaskController::class);
Route::resource('subtasks', SubTaskController::class)->except(['index', 'create', 'show']);

Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
Route::patch('/subtasks/{subtask}/toggle', [SubTaskController::class, 'toggle'])->name('subtasks.toggle');

Route::patch('/subtasks/bulk-toggle', [SubTaskController::class, 'bulkToggle'])->name('subtasks.bulk-toggle');