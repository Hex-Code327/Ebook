<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EbookController; // <- pastikan ini ditambahkan
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Guest\HomeController;
use Illuminate\Support\Facades\Route;

// ================= ROUTE GUEST (TANPA LOGIN) =================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ================= ROUTE AUTHENTIKASI =================
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard User Biasa
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Profile User
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ================= ROUTE ADMIN =================
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // CRUD Ebooks (tanpa show)
    Route::resource('ebooks', EbookController::class)->except(['show']);

    // Chapter Management
    Route::resource('ebooks.chapters', Admin\ChapterController::class);
});

// ================= ROUTE AUTH (LOGIN/REGISTER DLL) =================
require __DIR__.'/auth.php';
