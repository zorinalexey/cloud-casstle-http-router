<?php

declare(strict_types=1);

/**
 * Пример файла с маршрутами для компиляции
 * Использование: ./vendor/bin/router compile examples/routes.php ./cache
 */

use CloudCastle\Http\Router\Facade\Route;

return function () {
    // Главная страница
    Route::get('/', 'HomeController@index')->name('home');

    // Публичные страницы
    Route::get('/about', 'PageController@about')->name('about');
    Route::get('/contact', 'PageController@contact')->name('contact');
    Route::post('/contact', 'PageController@sendContact')->name('contact.send');

    // Пользователи
    Route::get('/users', 'UserController@index')->name('users.index');
    Route::get('/users/{id:\d+}', 'UserController@show')->name('users.show');
    Route::post('/users', 'UserController@store')->name('users.store');
    Route::put('/users/{id:\d+}', 'UserController@update')->name('users.update');
    Route::delete('/users/{id:\d+}', 'UserController@destroy')->name('users.destroy');

    // API v1
    Route::group([
        'prefix' => '/api/v1',
        'middleware' => 'api',
    ], function () {
        Route::get('/users', 'Api\V1\UserController@index')
            ->name('api.v1.users.index')
            ->tag(['api', 'public']);

        Route::post('/users', 'Api\V1\UserController@store')
            ->name('api.v1.users.store')
            ->middleware('auth')
            ->tag('api');

        Route::get('/posts', 'Api\V1\PostController@index')
            ->name('api.v1.posts.index')
            ->tag(['api', 'public']);
    });

    // API v2
    Route::group([
        'prefix' => '/api/v2',
        'middleware' => ['api', 'throttle'],
    ], function () {
        Route::get('/users', 'Api\V2\UserController@index')
            ->name('api.v2.users.index')
            ->tag('api');

        Route::get('/users/{id}', 'Api\V2\UserController@show')
            ->name('api.v2.users.show')
            ->tag('api');
    });

    // Админ панель
    Route::group([
        'prefix' => '/admin',
        'middleware' => ['auth', 'admin'],
        'whitelistIp' => ['127.0.0.1', '::1'],
    ], function () {
        Route::get('/dashboard', 'Admin\DashboardController@index')
            ->name('admin.dashboard');

        Route::get('/users', 'Admin\UserController@index')
            ->name('admin.users.index');

        Route::get('/settings', 'Admin\SettingsController@index')
            ->name('admin.settings');
    });
};

