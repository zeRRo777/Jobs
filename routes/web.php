<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.index')->name('main');

Route::view('/login', 'pages.auth.login')->name('login');

Route::view('/register', 'pages.auth.register')->name('register');

Route::view('/admin/register', 'pages.auth.admin.register')->name('admin.register');

Route::view('/about', 'pages.about')->name('about');

Route::view('/vacancies', 'pages.vacancy.index')->name('vacancies');

Route::view('/companies', 'pages.company.index')->name('companies');

Route::view('/companies/1', 'pages.company.show')->name('company.show');

Route::view('/vacancies/likes/1', 'pages.vacancy.like')->name('vacancy.like');

Route::view('/vacancies/1', 'pages.vacancy.show')->name('vacancy.show');

Route::view('/profile/1', 'pages.profile')->name('profile');
