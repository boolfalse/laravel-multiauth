<?php

Route::get('/', function () {
    return view('welcome');
})->name('front');

Route::group([
    'namespace' => 'User\\Auth',
], function () {

    // Authentication Routes...
    Route::get('login', 'LoginController@showLoginForm')->name('login_page');
    Route::post('login', 'LoginController@login')->name('login');
    Route::post('logout', 'LoginController@logout')->name('logout');

    // Registration Routes...
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register_page');
    Route::post('register', 'RegisterController@register')->name('register');
    Route::get('register/activate/{token}', 'RegisterController@confirm')->name('email_confirm');

    // Password Reset Routes...
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset');

});

Route::get('home', 'User\\UserController@index')->name('home');
