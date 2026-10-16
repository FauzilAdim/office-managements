<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication & Quick Access Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['GET', 'POST'], '/quick-login', [AuthController::class, 'quickLogin'])->name('quick-login');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Project Management
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');

    // Task Management
    Route::resource('tasks', TaskController::class);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');

    // Employee Profiles & Directory
    Route::resource('employees', EmployeeController::class)->only(['index', 'show', 'edit', 'update']);

    // Organizational Hierarchy Structure
    Route::get('/organization', [OrganizationController::class, 'index'])->name('organization.index');
});
