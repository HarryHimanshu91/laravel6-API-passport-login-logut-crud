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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['namespace' => 'Api'], function(){
    Route::post('signup','UserController@signup');
    Route::post('login','UserController@login');

    Route::group(['middleware'  =>  'auth:api'], function () { 

        Route::get('get-profile','UserController@getProfile');
        Route::post('update-profile','UserController@updateProfile');
        Route::post('logout','UserController@logoutUser');
    });

    Route::post('save-product','ProductController@storeProduct');
    Route::get('get-products','ProductController@listProduct');
    Route::get('get-products/{id}','ProductController@singleProduct');
    Route::delete('delete/{id}','ProductController@deleteProduct');
    Route::put('update-product/{id}','ProductController@updateProduct');
    Route::get('search/{key}','ProductController@searchProduct');


    Route::post('add-student','StudentController@saveStudent');
    Route::get('students','StudentController@getAllStudents');
    Route::get('edit-student/{id}','StudentController@editStudent');
    Route::put('update-student/{id}','StudentController@updateStudent');
    Route::delete('delete-student/{id}', 'StudentController@deleteStudent');

    Route::post('saveImage','ImageController@storeImage');
    Route::get('get-images','ImageController@listImages');
    Route::get('get-image/{id}','ImageController@singleImage');
    Route::put('update-image/{id}','ImageController@updateImage');
    Route::delete('delete-image/{id}', 'ImageController@deleteImage');

 });

