<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Middleware\SuperAdmin;
use App\Http\Middleware\Admin;

// route open to all
Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/api/passedEvents', [IndexController::class, 'passedEvents'])->name('passedEvents');
Route::get('/showEvent/{id}', [IndexController::class, 'showEvent'])->name('showEvent');

// route for register
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// route for login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// route for dashboard
Route::get('/dashboard', [DashboardController::class, 'handle'])->middleware(['auth'])->name('dashboard');
Route::post('/changePassword', [RegisterController::class, 'changePassword'])->middleware(['auth'])->name('user.change_password');

// route for admin and super admin
Route::get('/createEvent', [EventController::class, 'index'])->middleware(['auth', Admin::class])->name('events.create');
Route::post('/createEvent', [EventController::class, 'store'])->middleware(['auth', Admin::class])->name('events.store');
Route::get('/editEvent/{id}', [EventController::class, 'edit'])->middleware(['auth', Admin::class])->name('events.edit');
Route::post('/editEvent/{id}', [EventController::class, 'update'])->middleware(['auth', Admin::class])->name('events.update');
Route::delete('/deleteEvent/{id}', [EventController::class, 'destroy'])->middleware(['auth', Admin::class])->name('events.delete');
Route::delete('/deleteUser/{id}', [RegisterController::class, 'destroy'])->middleware(['auth', SuperAdmin::class])->name('users.delete');
Route::post('/userChangeRole/{id}', [RegisterController::class, 'changeRole'])->middleware(['auth', SuperAdmin::class])->name('users.change_permission');

// route for like the event
Route::post('/likeEvent/{id}', [EventController::class, 'like'])->middleware(['auth'])->name('events.like');
Route::post('/unlikeEvent/{id}', [EventController::class, 'unlike'])->middleware(['auth'])->name('events.unlike');

// route for unauthorized actions
Route::get('/unauthorized', function () {
    return view('unauthorized');
})->name('unauthorized');