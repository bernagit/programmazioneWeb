<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Middleware\SuperAdmin;

// route open to all
Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/api/passedevents', [IndexController::class, 'passedEvents'])->name('passedEvents');
Route::get('/passedevents/{id}', [IndexController::class, 'passedEvent'])->name('passedEvent');

// route for register
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// route for login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// route for dashboard
Route::get('/dashboard', [DashboardController::class, 'handle'])->middleware(['auth'])->name('dashboard');

// route for admin and super admin
Route::get('/createEvent', [EventController::class, 'index'])->middleware(['auth', SuperAdmin::class])->name('events.index');
Route::post('/createEvent', [EventController::class, 'store'])->middleware(['auth', SuperAdmin::class])->name('events.store');