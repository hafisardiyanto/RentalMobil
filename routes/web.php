<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingFineController;

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
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'processResetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Panel Routes
    Route::middleware('is_admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Cars CRUD
        Route::middleware('can:view_cars')->group(function () {
            Route::get('/cars', [AdminController::class, 'index'])->name('admin.cars.index');
            Route::get('/cars/{car}', [AdminController::class, 'show'])->name('admin.cars.show');
            Route::get('/maintenances', [\App\Http\Controllers\Admin\MaintenanceController::class, 'index'])->name('admin.maintenances.index');
        });
        Route::middleware('can:create_cars')->group(function () {
            Route::get('/cars/create', [AdminController::class, 'create'])->name('admin.cars.create');
            Route::post('/cars', [AdminController::class, 'store'])->name('admin.cars.store');
            Route::get('/cars/{car}/maintenance/create', [\App\Http\Controllers\Admin\MaintenanceController::class, 'create'])->name('admin.maintenances.create');
            Route::post('/cars/{car}/maintenance', [\App\Http\Controllers\Admin\MaintenanceController::class, 'store'])->name('admin.maintenances.store');
        });
        Route::middleware('can:edit_cars')->group(function () {
            Route::get('/cars/{car}/edit', [AdminController::class, 'edit'])->name('admin.cars.edit');
            Route::put('/cars/{car}', [AdminController::class, 'update'])->name('admin.cars.update');
        });
        Route::middleware('can:delete_cars')->group(function () {
            Route::delete('/cars/{car}', [AdminController::class, 'destroy'])->name('admin.cars.destroy');
        });

        // Bookings Management
        Route::middleware('can:view_bookings')->group(function () {
            Route::get('/bookings', [AdminController::class, 'bookingsIndex'])->name('admin.bookings.index');
            Route::get('/bookings/{booking}/detail', [AdminController::class, 'showBooking'])->name('admin.bookings.show');
            Route::get('/calendar', [\App\Http\Controllers\Admin\CalendarController::class, 'index'])->name('admin.calendar.index');
        });

        Route::middleware('can:edit_bookings')->group(function () {
            Route::put('/payments/{payment}/verify', [\App\Http\Controllers\Admin\BookingPaymentController::class, 'verify'])->name('admin.payments.verify');
        });

        Route::middleware('can:delete_bookings')->group(function () {
            Route::delete('/bookings/{booking}', [AdminController::class, 'destroyBooking'])->name('admin.bookings.destroy');
        });

        Route::middleware('can:edit_bookings')->group(function () {
            Route::put('/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('admin.bookings.update-status');
            Route::put('/bookings/{booking}/payment-status', [AdminController::class, 'updatePaymentStatus'])->name('admin.bookings.update-payment-status');
            Route::post('/bookings/{booking}/handover', [AdminController::class, 'processHandover'])->name('admin.bookings.process-handover');
            Route::post('/bookings/{booking}/return', [AdminController::class, 'processReturn'])->name('admin.bookings.process-return');
            Route::post('/bookings/{booking}/finalize', [AdminController::class, 'finalizeInvoice'])->name('admin.bookings.finalize');
        });

        Route::middleware('can:manage_fines')->group(function () {
            Route::post('/bookings/{booking}/fines', [BookingFineController::class, 'store'])->name('admin.fines.store');
            Route::put('/fines/{fine}', [BookingFineController::class, 'update'])->name('admin.fines.update');
            Route::delete('/fines/{fine}', [BookingFineController::class, 'destroy'])->name('admin.fines.destroy');
        });

        // Reports
        Route::middleware('can:view_reports')->group(function () {
            Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports.index');
            Route::get('/reports/fleet-utilization', [AdminController::class, 'fleetUtilization'])->name('admin.reports.fleet');
        });

    });

    // ==========================================
    // OWNER EXCLUSIVE ROUTES
    // ==========================================
    Route::middleware([\App\Http\Middleware\IsOwner::class])->prefix('owner')->name('owner.')->group(function () {
        Route::resource('admins', \App\Http\Controllers\AdminManagementController::class)->except(['show']);
        Route::resource('roles', \App\Http\Controllers\AdminRoleController::class)->except(['show']);
    });

    // User Booking Routes
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{car}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    // User Profile Routes
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.index');
    Route::put('/profile/identity', [ProfileController::class, 'updateIdentity'])->name('profile.identity');

    // User Payment & Invoice Routes
    Route::get('/bookings/{booking}/payment', [BookingController::class, 'paymentForm'])->name('bookings.payment');
    Route::post('/bookings/{booking}/payment', [BookingController::class, 'uploadPayment'])->name('bookings.payment.upload');

    // Cancellation
    Route::put('/bookings/{booking}/cancel', [BookingController::class, 'cancelBooking'])->name('bookings.cancel');

    // Invoice
    Route::get('/bookings/{booking}/invoice', [BookingController::class, 'invoice'])->name('bookings.invoice');
});
