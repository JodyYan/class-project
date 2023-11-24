<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/consultant_login', 'App\Http\Controllers\ConsultantController@login');

Route::post('/consultant', 'App\Http\Controllers\ConsultantController@store');

Route::get('/consultants', 'App\Http\Controllers\ConsultantController@index');

Route::post('/class', 'App\Http\Controllers\ClassesController@store');

Route::get('/classes', 'App\Http\Controllers\ClassesController@show');

Route::patch('/class/{id}', 'App\Http\Controllers\ClassesController@update');

Route::delete('/class/{id}', 'App\Http\Controllers\ClassesController@destroy');

Route::post('/student_class', 'App\Http\Controllers\StudentClassController@store');

Route::get('/student/{id}/classes', 'App\Http\Controllers\StudentClassController@show');