<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => 'api_token'], function () {
    Route::post('post', 'Api\PostController@store')->middleware('user_can_manage_post');

    //Authentication
    Route::post('logout', 'Api\UserController@logout');
    Route::post('reset-password', 'Api\UserController@resetPassword');
    Route::post('change-password', 'Api\UserController@changePassword');
    Route::get('current-user', 'Api\UserController@getCurrentUser');
    Route::post('update-profile', 'Api\UserController@updateProfile');

    //Review
	Route::post('add-review', 'Api\CommentController@addReview');

	//Add Cart
	Route::post('add-cart', 'Api\BookingController@addToCart');
	Route::get('get-cart', 'Api\BookingController@getCart');

	//Checkout
	Route::post('checkout', 'Api\BookingController@checkout');
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('token', 'Api\APIController@token');

    //Authentication
    Route::post('login', 'Api\UserController@login');
    Route::post('register', 'Api\UserController@register');

    //Language
    Route::get('languages', 'Api\LanguageController@index');

    //Post
    Route::get('posts', 'Api\PostController@index');
    Route::get('post/{id?}', 'Api\PostController@show');

    //Page
    Route::get('page/{id?}', 'Api\PageController@show');

    //Home
	Route::get('home/search', 'Api\HomeController@search');
	Route::get('home/filters', 'Api\HomeController@getFilters');
	Route::get('home/availability/{id?}', 'Api\HomeController@getAvailability');
    Route::get('home/time-availability/{id?}', 'Api\HomeController@getTimeAvailability');
    Route::get('home/price-realtime/{id?}', 'Api\HomeController@getPriceRealtime');
    Route::get('home/{id?}', 'Api\HomeController@show');


	//Experience
	Route::get('experience/search', 'Api\ExperienceController@search');
	Route::get('experience/filters', 'Api\ExperienceController@getFilters');
    Route::get('experience/availability/{id?}', 'Api\ExperienceController@getAvailability');
    Route::get('experience/price-realtime/{id?}', 'Api\ExperienceController@getPriceRealtime');
    Route::get('experience/{id?}', 'Api\ExperienceController@show');

    //Car
	Route::get('car/search', 'Api\CarController@search');
	Route::get('car/filters', 'Api\CarController@getFilters');
    Route::get('car/availability/{id?}', 'Api\CarController@getAvailability');
    Route::get('car/time-availability/{id?}', 'Api\CarController@getTimeAvailability');
        Route::get('car/price-realtime/{id?}', 'Api\CarController@getPriceRealtime');
    Route::get('car/{id?}', 'Api\CarController@show');

    //Booking
	Route::get('payment-gateways', 'Api\BookingController@getPaymentGateways');
    Route::get('booking-detail/{token_code?}', 'Api\BookingController@getBookingDetail');

    //Comment
	Route::get('reviews', 'Api\CommentController@getReviews');

	//Other data
	Route::get('countries', 'Api\OtherController@getCountries');
});
