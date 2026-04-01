<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister']);
    Route::get('/register/success', [AuthController::class, 'showRegisterSuccess'])->name('register.success');

    // Google Login
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

    // Forgot Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'processForgotPassword'])->name('password.email');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Panel Routes
    Route::middleware('is_admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Cars CRUD
        Route::get('/cars', [AdminController::class, 'index'])->name('admin.cars.index');
        Route::get('/cars/create', [AdminController::class, 'create'])->name('admin.cars.create');
        Route::get('/cars/{car}', [AdminController::class, 'show'])->name('admin.cars.show');
        Route::post('/cars', [AdminController::class, 'store'])->name('admin.cars.store');
        Route::get('/cars/{car}/edit', [AdminController::class, 'edit'])->name('admin.cars.edit');
        Route::put('/cars/{car}', [AdminController::class, 'update'])->name('admin.cars.update');
        Route::delete('/cars/{car}', [AdminController::class, 'destroy'])->name('admin.cars.destroy');

        // Bookings Management
        Route::get('/bookings', [AdminController::class, 'bookingsIndex'])->name('admin.bookings.index');
        Route::put('/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('admin.bookings.update-status');
        Route::delete('/bookings/{booking}', [AdminController::class, 'destroyBooking'])->name('admin.bookings.destroy');
    });

    // User Booking Routes
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{car}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
});
