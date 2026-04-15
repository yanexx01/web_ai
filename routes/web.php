<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\GuestbookController;
use App\Http\Controllers\BlogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/interests', [HomeController::class, 'interests'])->name('interests');
Route::get('/study', [HomeController::class, 'study'])->name('study');
Route::get('/photos', [HomeController::class, 'photos'])->name('photos');
Route::get('/history', [HomeController::class, 'history'])->name('history');
Route::match(['get', 'post'], '/contacts', [HomeController::class, 'contacts'])->name('contacts');
Route::match(['get', 'post'], '/test', [TestController::class, 'index'])->name('test');

// Гостевая книга
Route::get('/guestbook', [GuestbookController::class, 'index'])->name('guestbook.index');
Route::post('/guestbook', [GuestbookController::class, 'store'])->name('guestbook.store');
Route::get('/guestbook/upload', [GuestbookController::class, 'uploadForm'])->name('guestbook.upload.form');
Route::post('/guestbook/upload', [GuestbookController::class, 'upload'])->name('guestbook.upload');

// Блог
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
