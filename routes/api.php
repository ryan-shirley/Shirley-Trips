<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Inspiring;

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

Route::middleware('auth:api')->group(function () {
    Route::get('user', 'API\PassportController@user');
    Route::get('logout', 'API\PassportController@logout');

    Route::get('inspiration', function () { return Inspiring::quote(); });

    // Holidays
    Route::resource('holiday', 'API\HolidayController')->except([
        'create', 'edit'
    ]);

    // Days
    Route::get('day/{id}', 'API\DayController@show');

    // Comments
    Route::resource('comment', 'API\CommentController')->except([
        'index', 'create', 'edit'
    ]);

    // Flights
    Route::resource('flight', 'API\FlightController')->except([
        'index', 'create', 'edit'
    ]);

    // Airlines
    Route::resource('airlines', 'API\AirlineController')->except([
        'create', 'edit'
    ]);

    // Comment Images
    Route::resource('comment-images', 'API\CommentImageController')->except([
        'index', 'create', 'edit', 'update'
    ]);

    // Images
    Route::resource('images', 'API\ImagesController')->except([
        'index', 'create', 'edit', 'update'
    ]);

});