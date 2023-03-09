<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CMS Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register cms auth routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('login', 'LoginController@login')->name('login');
Route::post('logout', 'LoginController@logout')->name('logout');
Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
