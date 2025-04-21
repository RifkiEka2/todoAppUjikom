<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorkspaceController;


Route::get('/', [WorkspaceController::class, 'index'])->name('workspace.index');
Route::post('/workspaces', [WorkspaceController::class, 'store'])->name('workspace.store');
Route::patch('/workspaces/{workspace}', [WorkspaceController::class, 'update'])->name('workspace.update');
Route::delete('/workspaces/{workspace}', [WorkspaceController::class, 'destroy'])->name('workspaces.destroy');

Route::get('/tasks/{workspace}', [TaskController::class, 'task'])->name('task.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('task.store');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('task.destroy');
Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('task.update');

Route::patch('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('task.updateStatus');