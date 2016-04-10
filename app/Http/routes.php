<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'PageController@index');
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', 'PageController@adminindex');
    Route::get('/adminget', 'PageController@adminget');
    Route::get('/admingetpage/{id}', 'PageController@admingetpage');
    Route::post('/deleti', 'PageController@destroyItem');
    Route::post('/editi', 'PageController@editItem');
    Route::post('/adminsend', 'PageController@adminset');
    Route::post('/adminsent', 'PageController@adminseti');

});
Route::group(['prefix' => 'data'], function () {
	Route::get('/records/{num}', 'PageController@getrecords');
	Route::post('/records/editi', 'PageController@editRecord');
	Route::post('/records/create', 'PageController@createRecord');
	Route::post('/records/delete', 'PageController@deleteRecord');


	});

Route::get('/{page}', 'PageController@pages');


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
