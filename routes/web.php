<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\UserController;

// Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register.store');
Route::post('/login', [UserController::class, 'login'])->name('login.store');
Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/links', [LinkController::class, 'index'])->name('links.index');
    Route::post('/links', [LinkController::class, 'store'])->name('links.store');
    Route::get('/links/{id}/edit', [LinkController::class, 'edit'])->name('links.edit');
    Route::put('/links/{id}', [LinkController::class, 'update'])->name('links.update');
    Route::delete('/links/{id}', [LinkController::class, 'destroy'])->name('links.destroy');
    Route::get('/links/{id}/visit', [LinkController::class, 'visit'])->name('links.visit');

    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});