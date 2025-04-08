<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('students', StudentController::class)->except(['show']);
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::delete('/admin/students/{id}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
Route::put('/admin/students/{id}', [StudentController::class, 'update'])->name('admin.students.update');



