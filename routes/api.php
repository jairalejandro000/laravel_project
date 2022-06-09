<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
//Route::get('prueba', 'UsersController@Prueba');
Route::post('insertar/usuario', 'UsersController@usuario');
Route::post('login', 'UsersController@LogIn');
//Posts
Route::middleware('auth:sanctum')->post('agregar/archivo', 'FilesController@Insert');
Route::middleware('auth:sanctum')->get('ver/archivo', 'FilesController@Show');
Route::get('descargar', 'FilesController@Download');