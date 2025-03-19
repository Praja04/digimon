<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\RoleMiddleware;

// Login & Logout
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Dashboard umum
Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Dashboard per-role
Route::middleware(['auth', RoleMiddleware::class . ':analis'])->get('/analis', function () {
    return view('roles.analis');
})->name('analis.dashboard');

Route::middleware(['auth', RoleMiddleware::class . ':foreman'])->get('/foreman', function () {
    return view('roles.foreman');
})->name('foreman.dashboard');

Route::middleware(['auth', RoleMiddleware::class . ':supervisor'])->get('/supervisor', function () {
    return view('roles.supervisor');
})->name('supervisor.dashboard');

Route::middleware(['auth', RoleMiddleware::class . ':dept_head'])->get('/dept_head', function () {
    return view('roles.dept_head');
})->name('dept_head.dashboard');
