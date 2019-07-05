<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'API\PassportController@login');
Route::post('register', 'API\PassportController@register');

Route::get('realtimeflight/{id}', 'API\RealTimeFlights@show');

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'API\PassportController@user');
    Route::get('logout', 'API\PassportController@logout');

    Route::get('users', 'API\UsersController@index');

    // Holidays
    Route::resource('holiday', 'API\HolidayController')->except([
        'create', 'edit'
    ]);
    Route::put('holiday/{id}/permissions', 'API\HolidayController@updatePermissions');

    // Days
    Route::get('day/{id}', 'API\DayController@show')->name('day.show');

    // Hotels
    Route::resource('hotels', 'API\HotelController')->except([
        'create', 'edit'
    ]);

    // Comments
    Route::resource('comment', 'API\CommentController')->except([
        'index', 'create', 'edit'
    ]);

    // Videos
    Route::resource('videos', 'API\VideoController')->except([
        'index', 'create', 'edit'
    ]);

    // Flights
    Route::resource('flight', 'API\FlightController')->except([
        'index', 'create', 'edit'
    ]);

    // Airlines
    Route::get('airlines', 'API\AirlineController@index')->name('airlines.index');
    Route::get('airlines/{airline}', 'API\AirlineController@show')->name('airlines.show');
    

    // Comment Images
    Route::resource('comment-images', 'API\CommentImageController')->except([
        'index', 'create', 'edit', 'update'
    ]);

    // Images
    Route::resource('images', 'API\ImagesController')->except([
        'index', 'create', 'edit', 'update'
    ]);

    // Activities
    Route::put('activities/{id}', 'API\ActivityController@update');


    Route::middleware('admin')->group(function () {
        // Airline CRUD
        Route::resource('airlines', 'API\AirlineController')->except([
            'create', 'edit', 'show', 'index'
        ]);

        // TinyPNG
        Route::get('tinypng/compressionCount', 'API\TinyPNGController@compressionCount');
    });

});