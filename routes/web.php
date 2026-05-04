<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\User;

// Halaman utama → redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// ============================================================
// ROUTE ADMIN (Koperasi)
// ============================================================
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // Master SPPG
        Route::get('/sppg', [Admin\SppgController::class, 'index'])
            ->name('sppg.index');
        Route::get('/sppg/{id}', [Admin\SppgController::class, 'show'])
            ->name('sppg.show');

        // Produk / Bahan Baku (CRUD)
        Route::resource('products', Admin\ProductController::class);

        // Foto Menu Mingguan
        Route::get('/menus', [Admin\WeeklyMenuController::class, 'index'])
            ->name('menus.index');
        Route::get('/menus/{id}', [Admin\WeeklyMenuController::class, 'show'])
            ->name('menus.show');
    });

// ============================================================
// ROUTE USER (SPPG)
// ============================================================
Route::prefix('user')
    ->name('user.')
    ->middleware(['auth', 'user'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [User\DashboardController::class, 'index'])
            ->name('dashboard');

        // Katalog Produk
        Route::get('/catalog', [User\CatalogController::class, 'index'])
            ->name('catalog.index');
        Route::get('/catalog/{id}', [User\CatalogController::class, 'show'])
            ->name('catalog.show');

        // Upload Menu Mingguan
        Route::get('/menus', [User\WeeklyMenuController::class, 'index'])
            ->name('menus.index');
        Route::get('/menus/create', [User\WeeklyMenuController::class, 'create'])
            ->name('menus.create');
        Route::post('/menus', [User\WeeklyMenuController::class, 'store'])
            ->name('menus.store');
        Route::delete('/menus/{id}', [User\WeeklyMenuController::class, 'destroy'])
            ->name('menus.destroy');

        // Profil SPPG
        Route::get('/profile', [User\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::put('/profile', [User\ProfileController::class, 'update'])
            ->name('profile.update');
    });

// Auth Routes (dari Breeze)
require __DIR__.'/auth.php';