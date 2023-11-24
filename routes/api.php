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

Route::post('/consultant', 'App\Http\Controllers\ConsultantController@createConsultant');

Route::get('/consultants', 'App\Http\Controllers\ConsultantController@index');