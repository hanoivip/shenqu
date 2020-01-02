<?php
use Illuminate\Support\Facades\Route;

Route::middleware('web')->namespace('Hanoivip\Shenqu\Controllers')->group(function () {
    Route::get('/login', 'Shenqu@login')->name('login');
    Route::post('/login', 'Shenqu@doLogin')->name('doLogin');
    Route::get('/logout', 'Shenqu@logout')->name('logout');
    Route::get('/logoutsuccess', 'Shenqu@onLogout')->name('logoutsuccess');
    Route::get('/register', 'Shenqu@register')->name('register');
    Route::get('/password/request', 'Shenqu@forgotPass')->name('password.request');
});