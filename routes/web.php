<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/order', function () {
    return view('order');
});

Route::get('/track-order', function () {
    return view('track');
});
