<?php

Route::group([
    'namespace' => 'Auth',
], function () {
    // Authentication Routes...
    Route::get('login', 'LoginController@showLoginForm')->name('login_page');
    Route::post('login', 'LoginController@login')->name('login');
    Route::post('logout', 'LoginController@logout')->name('logout');
});

Route::group([
    'middleware' => [
        'auth:admin',
    ],
], function () {

    // for all admins
    Route::get('/', 'AdminController@index')->name('dashboard');
    Route::get('home', 'AdminController@index')->name('dashboard');
    Route::get('dashboard', 'AdminController@index')->name('dashboard');

    // for administrator
    Route::group(['middleware' => ['role:administrator']], function () {
        //
    });

    // for moderators
    Route::group([
        'middleware' => ['role:administrator|moderator'],
    ], function () {
        // users
        Route::group(['prefix' => 'users', 'as' => 'users.',], function () {
            Route::get('all', 'UserController@index')->name('index');
        });
    });

    // for managers
    Route::group(['middleware' => ['role:administrator|moderator|manager']], function () {
        //
    });

});

