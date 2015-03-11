<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('/', array('as' => 'home', 'uses' => 'ProductsController@index'));
Route::get('/product/{str}',['as' => 'product.from','uses' => 'ProductsController@from']);
Route::resource('product', 'ProductsController');
Route::controller('api', 'ApiController');
Route::get('/feed/{str?}', [
    'as' => 'product.feed',
    'uses' => 'ProductsController@feed'
]);