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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'auth'], function ($router) {
    $router->post('login', 'API\AuthController@login');

    $router->group(['middleware' => 'auth:api'], function($router) {
        $router->post('logout', 'API\AuthController@logout');
        $router->post('refresh', 'API\AuthController@refresh');
        $router->get('me', 'API\AuthController@me');
    });
});

Route::api('amenity');
Route::api('currency');
Route::api('hotel');
