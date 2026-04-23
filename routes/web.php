<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\GuestbookController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;

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
Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
Route::post('/blog/store', [BlogController::class, 'store'])->name('blog.store.action');

// Загрузка CSV в блог
Route::get('/blog/upload', [BlogController::class, 'showUploadForm'])->name('blog.upload.form');
Route::post('/blog/upload', [BlogController::class, 'uploadCsv'])->name('blog.upload');

// Маршруты аутентификации
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
