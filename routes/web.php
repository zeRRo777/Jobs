<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RegisteredController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'pages.index')->name('main');



// Route::view('/register', 'pages.auth.register')->name('register');

// Route::view('/admin/register', 'pages.auth.admin.register')->name('admin.register');



// Route::view('/vacancies', 'pages.vacancy.index')->name('vacancies');

// Route::view('/companies', 'pages.company.index')->name('companies');

// Route::view('/companies/1', 'pages.company.show')->name('company.show');

Route::view('/vacancies/likes/1', 'pages.vacancy.like')->name('vacancy.like');

// Route::view('/vacancies/1', 'pages.vacancy.show')->name('vacancy.show');

// Route::view('/profile/1', 'pages.profile')->name('profile');





Route::get('/', [CompanyController::class, 'popular'])->name('main');

Route::view('/about', 'pages.about')->name('about');

Route::get('/vacancies', [VacancyController::class, 'index'])->name('vacancies');

Route::get('/companies', [CompanyController::class, 'index'])->name('companies');

Route::get('/register', [RegisteredController::class, 'create'])->name('register');

Route::post('/register', [RegisteredController::class, 'store'])->name('register.store');

Route::get('/admin/register', [RegisteredController::class, 'admin_create'])->name('admin.register');

Route::post('/admin/register', [RegisteredController::class, 'admin_store'])->name('admin.register.store');

Route::view('/login', 'pages.auth.login')->name('login');

Route::post('/login', [SessionController::class, 'store'])->name('login.store');

Route::delete('/logout', [SessionController::class, 'destroy'])->name('logout');

Route::get('/profile/{user}', [UserController::class, 'index'])->name('profile');

Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show'])->name('vacancy.show');

Route::post('/vacancies/{vacancy}/update', [VacancyController::class, 'update'])->name('vacancy.update');

Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('company.show');

Route::post('/companies/{company}/update', [CompanyController::class, 'update'])->name('company.update');

Route::post('/profile/{user}/update', [UserController::class, 'update'])->name('profile.update');

Route::post('/profile/changePassword', [UserController::class, 'changePassword'])->name('profile.changePassword');

Route::post('/profile/generateSecretCode', [CompanyController::class, 'generateSecretCode'])->name('company.generateSecretCode');