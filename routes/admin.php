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
        // users
        Route::group(['prefix' => 'users', 'as' => 'users.',], function () {
            Route::get('all', 'UserController@index')->name('index');
            Route::get('ajax', 'UserController@ajax')->name('ajax');
            Route::get('show/{id}', 'UserController@show'); // ->where('id', '[0-9]+');
            Route::post('change_status', 'UserController@change_status')->name('change_status');
            Route::post('delete', 'UserController@delete')->name('delete');
        });
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
        // products
        Route::group(['prefix' => 'products', 'as' => 'products.',], function () {
            Route::get('all', 'ProductController@index')->name('index');
            Route::get('ajax', 'ProductController@ajax')->name('ajax');
            Route::get('create', 'ProductController@create')->name('create');
            Route::get('show/{id}', 'ProductController@show')->name('show'); // ->where('id', '[0-9]+');
            Route::get('edit/{id}', 'ProductController@edit')->name('edit'); // ->where('id', '[0-9]+');
            Route::post('change_status', 'ProductController@change_status')->name('change_status');
        });
    });

});

