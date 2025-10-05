<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Admin\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Public frontend and protected admin routes
|
*/

// Frontend Routes (Public)
Route::get('/', [BlogController::class, 'index'])->name('home');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');





// Authentication Routes (Laravel Breeze )
require __DIR__.'/auth.php';

// Admin Routes (Protected by auth)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    require __DIR__.'/admin.php';
});
