<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RegisteredController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\VerifyController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::controller(VacancyController::class)->group(function () {
        Route::get('/vacancies', 'index')
            ->name('vacancies')
            ->withoutMiddleware('auth');

        Route::get('/vacancies/{vacancy}', 'show')
            ->name('vacancy.show')
            ->withoutMiddleware('auth');

        Route::put('/vacancies/{vacancy}/update',  'update')
            ->name('vacancy.update')
            ->can('update', 'vacancy');

        Route::delete('/vacancies/{vacancy}', 'delete')
            ->name('vacancy.delete')
            ->can('delete', 'vacancy');

        Route::post('/companies/{company}/vacancyCreate',  'store')
            ->name('vacancy.store');

        Route::get('/vacancies/likes/{user}', 'likes')
            ->name('vacancy.likes')
            ->can('base', 'user');
    });

    Route::controller(CompanyController::class)->group(function () {
        Route::get('/companies', 'index')
            ->name('companies')
            ->withoutMiddleware('auth');

        Route::get('/', 'popular')
            ->name('main')
            ->withoutMiddleware('auth');

        Route::get('/companies/{company}', 'show')
            ->name('company.show')
            ->withoutMiddleware('auth');

        Route::put('/companies/{company}/update', 'update')
            ->name('company.update')
            ->can('update', 'company');

        Route::patch('/profile/{user}/generateSecretCode/{company}', 'generateSecretCode')
            ->name('company.generateSecretCode')
            ->can('generateCode', 'company');

        Route::delete('/profile/{user}/generateSecretCode/{company}', 'deleteSecretCode')
            ->name('company.generateSecretCode.delete')
            ->can('generateCode', 'company');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/profile/{user}', 'index')
            ->name('profile')
            ->can('base', 'user');

        Route::put('/profile/{user}/update', 'update')
            ->name('profile.update')
            ->can('base', 'user');

        Route::patch('/profile/changePassword', 'changePassword')
            ->name('profile.changePassword');

        Route::delete('/profile/deleteAccount', 'delete')
            ->name('profile.deleteAccount');

        Route::get('/users/{user}', 'show')
            ->name('user.show')
            ->can('viewUserDetail', 'user');

        Route::get('/users', 'all')
            ->name('users')
            ->can('admin', 'App\Models\User');

        Route::post('/users/{user}/offers', 'offers')
            ->name('user.offers');

        Route::delete('/profile/deleteCompany', 'deleteCompany')
            ->name('user.deleteCompany')
            ->can('admin', 'App\Models\User')
            ->middleware('verified');

        Route::post('/users/addCompany', 'addCompany')
            ->name('user.addCompany')->middleware('verified');
    });

    Route::delete('/logout', [SessionController::class, 'destroy'])
        ->name('logout');

    Route::controller(VerifyController::class)->group(function () {
        Route::get('/email/verify/{id}/{hash}', 'verify')
            ->name('verification.verify')
            ->middleware('signed');
    });
});

Route::middleware('guest')->group(function () {

    Route::controller(RegisteredController::class)->group(function () {
        Route::post('/register', 'store')
            ->name('register.store');

        Route::post('/admin/register', 'admin_store')
            ->name('admin.register.store');
    });

    Route::view('/register', 'pages.auth.register')
        ->name('register');

    Route::view('/admin/register', 'pages.auth.admin.register')
        ->name('admin.register');

    Route::view('/login', 'pages.auth.login')
        ->name('login');

    Route::post('/login', [SessionController::class, 'store'])
        ->name('login.store');
});

Route::view('/about', 'pages.about')->name('about');
