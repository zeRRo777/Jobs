<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RegisteredController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::controller('VacancyController')->group(function () {
        Route::get('/vacancies', 'index')->name('vacancies')->withoutMiddleware('auth');
        Route::get('/vacancies/{vacancy}', 'show')->name('vacancy.show')->withoutMiddleware('auth');
        Route::post('/vacancies/{vacancy}/update',  'update')->name('vacancy.update');
        Route::delete('/vacancies/{vacancy}', 'delete')->name('vacancy.delete');
        Route::post('/companies/{company}/vacancyCreate',  'store')->name('vacancy.store');
        Route::get('/vacancies/likes/{user}', 'likes')->name('vacancy.likes');
    });

    Route::controller('CompanyController')->group(function () {
        Route::get('/companies', 'index')->name('companies')->withoutMiddleware('auth');
        Route::get('/', 'popular')->name('main')->withoutMiddleware('auth');
        Route::get('/companies/{company}', 'show')->name('company.show')->withoutMiddleware('auth');
        Route::post('/companies/{company}/update', 'update')->name('company.update');
        Route::post('/profile/generateSecretCode', 'generateSecretCode')->name('company.generateSecretCode');
    });

    Route::controller('UserController')->group(function () {
        Route::get('/profile/{user}', 'index')->name('profile');
        Route::post('/profile/{user}/update', 'update')->name('profile.update');
        Route::post('/profile/changePassword', 'changePassword')->name('profile.changePassword');
        Route::delete('/profile/deleteAccount', 'delete')->name('profile.deleteAccount');
    });

    Route::delete('/logout', [SessionController::class, 'destroy'])->name('logout');
});

Route::middleware('guest')->group(function () {

    Route::controller('RegisteredController')->group(function () {
        Route::post('/register', 'store')->name('register.store');
        Route::post('/admin/register', 'admin_store')->name('admin.register.store');
    });

    Route::view('/register', 'pages.auth.register')->name('register');
    Route::view('/admin/register', 'pages.auth.admin.register')->name('admin.register');
    Route::view('/login', 'pages.auth.login')->name('login');

    Route::post('/login', [SessionController::class, 'store'])->name('login.store');
});


Route::view('/about', 'pages.about')->name('about');
