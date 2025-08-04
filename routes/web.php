<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Public Routes (tidak perlu auth)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Protected Routes (harus login)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard/Home
    Route::get('/', [TaskController::class, 'index'])->name('home');
    
    // Tasks
    Route::resource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
    
    // Subtasks - Enhanced with collaboration support
    Route::post('/subtasks', [SubTaskController::class, 'store'])->name('subtasks.store');
    Route::get('/subtasks/{subtask}', [SubTaskController::class, 'show'])->name('subtasks.show');
    Route::put('/subtasks/{subtask}', [SubTaskController::class, 'update'])->name('subtasks.update');
    Route::delete('/subtasks/{subtask}', [SubTaskController::class, 'destroy'])->name('subtasks.destroy');
    Route::get('/subtasks/task/{taskId}', [SubTaskController::class, 'getTaskSubtasks'])->name('subtasks.task');
    Route::patch('/subtasks/{subtask}/toggle', [TaskController::class, 'toggleSubtask'])->name('subtasks.toggle');
    
    // Categories
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);

    // Collaboration Routes
    Route::prefix('collaboration')->group(function () {
        Route::get('/invites', [CollaborationController::class, 'getInvites']);
        Route::get('/invites-page', function () {
            return view('collaboration.invites');
        })->name('collaboration.invites');
        Route::get('/my-tasks', [CollaborationController::class, 'getMyCollaboratedTasks']);
        Route::get('/pending-revisions', [CollaborationController::class, 'getPendingRevisions']);
        
        Route::post('/invite/{task}', [CollaborationController::class, 'invite'])->name('collaboration.invite');
        Route::post('/respond/{collaborator}', [CollaborationController::class, 'respondToInvite'])->name('collaboration.respond');
        
        Route::get('/task-status/{task}', [CollaborationController::class, 'getCollaborationStatus']);
        Route::delete('/remove/{task}/{collaborator}', [CollaborationController::class, 'removeCollaborator'])->name('collaboration.remove');
        
        // Fix: Consistent route parameter
        Route::post('/revisions/{revision}', [CollaborationController::class, 'reviewRevision'])->name('collaboration.review');
    });
});