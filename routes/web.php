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

//  Frontend
Route::get('/', [BlogController::class, 'index'])->name('home');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

//  Like + Comment
Route::middleware(['auth'])->group(function () {
    Route::post('/blogs/{blog}/toggle-like', [BlogController::class, 'toggleLike'])->name('blog.toggleLike');
    Route::post('/blogs/{blog}/comment', [BlogController::class, 'storeComment'])->name('blog.comment');
    Route::put('/blogs/comment/{comment}/edit', [BlogController::class, 'updateComment'])->name('blog.comment.update');
});

// Authentication (Laravel Breeze )
require __DIR__ . '/auth.php';

//  Admin 
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    require __DIR__ . '/admin.php';
});
