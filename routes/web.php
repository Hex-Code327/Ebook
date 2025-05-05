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
| Guest Routes (Tanpa login)
|-------------------------------------------------------------------------- 
*/
Route::name('guest.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/search', [HomeController::class, 'search'])->name('search');

    // Ebooks
    Route::get('/ebooks', [GuestEbookController::class, 'index'])->name('ebooks.index');
    Route::get('/ebooks/{ebook}', [GuestEbookController::class, 'show'])->name('ebooks.show');

    // Halaman statis
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
    Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

    // Filter & kategori
    Route::get('/ebooks/type/{type}', [HomeController::class, 'byType'])->name('ebooks.byType');
    Route::get('/category/{category}', [HomeController::class, 'byCategory'])->name('ebooks.byCategory');
});

/*
|-------------------------------------------------------------------------- 
| Authenticated User Routes 
|-------------------------------------------------------------------------- 
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Redirect based on role after login
    Route::get('/dashboard', function () {
        // Cek role user untuk mengarahkan ke dashboard yang sesuai
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    // Ebooks milik user
    Route::prefix('my')->name('user.')->group(function () {
        Route::get('/ebooks', [UserEbookController::class, 'index'])->name('ebooks.index');
        Route::get('/ebooks/{ebook}/read', [UserEbookController::class, 'read'])->name('ebooks.read');
    });

    // Profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|-------------------------------------------------------------------------- 
| Admin Routes 
|-------------------------------------------------------------------------- 
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Ebook & Chapter
    Route::resource('ebooks', AdminEbookController::class)->except(['show']);
    Route::resource('ebooks.chapters', AdminChapterController::class)->shallow();

    // Manajemen user (Menambah user, dll.)
    Route::get('users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
    Route::resource('users', AdminUserController::class)->except(['create', 'store']);

    // Halaman tambahan
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
});

/*
|-------------------------------------------------------------------------- 
| Authentication Routes (Fortify) 
|-------------------------------------------------------------------------- 
*/
require __DIR__.'/auth.php';
