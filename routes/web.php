<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TimeTrackerController;

// Rotas de autenticação (apenas para usuários não autenticados)
Route::middleware('guest')->group(function () {
    Route::get('/', [WebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login'])->name('login.post');
});

// Rota de logout (apenas para usuários autenticados)
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rotas protegidas (apenas para usuários autenticados)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Projects
    Route::resource('projects', ProjectController::class);
    
    // Tasks
    Route::resource('tasks', TaskController::class);
    
    // Time Trackers
    Route::resource('time-trackers', TimeTrackerController::class);
    Route::post('/time-trackers/start', [TimeTrackerController::class, 'start'])->name('time-trackers.start');
    Route::post('/time-trackers/{timeTracker}/stop', [TimeTrackerController::class, 'stop'])->name('time-trackers.stop');
});
