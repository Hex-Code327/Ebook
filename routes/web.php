<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Guest Controllers
use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Guest\EbookController as GuestEbookController;

// User Controllers
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\EbookController as UserEbookController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EbookController as AdminEbookController;
use App\Http\Controllers\Admin\ChapterController as AdminChapterController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

/*
|--------------------------------------------------------------------------
| Guest Routes (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/
Route::name('guest.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Ebook
    Route::get('/ebooks', [GuestEbookController::class, 'index'])->name('ebooks.index');
    Route::get('/ebooks/{ebook}', [GuestEbookController::class, 'show'])->name('ebooks.show');

    // Categories
    Route::get('/categories', [GuestEbookController::class, 'categories'])->name('categories.index');
    Route::get('/categories/{category}', [GuestEbookController::class, 'category'])->name('categories.show');

    // Static Pages
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
    Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Harus login & verifikasi)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // User Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // My Ebooks
    Route::prefix('my')->group(function () {
        Route::get('/ebooks', [UserEbookController::class, 'index'])->name('user.ebooks.index');
        Route::get('/ebooks/{ebook}/read', [UserEbookController::class, 'read'])->name('user.ebooks.read');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Login sebagai admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Ebook Management
    Route::resource('ebooks', AdminEbookController::class)->except(['show']);

    // Chapter Management (shallow untuk menghindari nested parameter URL panjang)
    Route::resource('ebooks.chapters', AdminChapterController::class)->shallow();

    // User Management
    Route::resource('users', AdminUserController::class)->except(['create', 'store']);

    // Reports & Settings
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
});

/*
|--------------------------------------------------------------------------
| User Extended Routes (Prefix user/)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/reading-history', [UserDashboardController::class, 'readingHistory'])->name('reading.history');
    Route::get('/my-ebooks', [UserDashboardController::class, 'myEbooks'])->name('my-ebooks');
    Route::post('/ebooks/{ebook}/read', [UserDashboardController::class, 'markAsReading'])->name('ebooks.read');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Login, Register, Forgot Password, etc)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
