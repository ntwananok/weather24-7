<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', 'App\Http\Controllers\WeatherController@getWeatherDetails')->name('getWeather');

Route::post('/weather', 'App\Http\Controllers\WeatherController@getWeatherDetails')->name('getWeather');