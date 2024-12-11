<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.index')->name('main');

Route::view('/login', 'pages.auth.login')->name('login');

Route::view('/register', 'pages.auth.register')->name('register');

Route::view('/admin/register', 'pages.auth.admin.register')->name('admin.register');
