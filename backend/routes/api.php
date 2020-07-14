<?php

Route::group([
    'middleware' => 'api'
], function () {

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
});
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('profile', 'AuthController@profile');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('books', 'BookController@index');
    Route::post('books', 'BookController@store');
    Route::post('books/{id}/reviews', 'BookController@review');
    Route::get('books/reviews', 'BookController@GetReview');
});
