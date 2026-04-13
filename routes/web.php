<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;

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
