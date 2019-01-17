<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('{any?}', function () {
    return view('app');
});

Route::get('tag/{thing}', function () {
    return view('app');
});

Route::get('tags', function () {
    return view('app');
});

Route::get('search/{thing}', function () {
    return view('app');
});

Route::get('/auth/linkedin',            'Auth\LinkedInController@login');
Route::get('/auth/linkedin/callback',   'Auth\LinkedInController@callback');

//Route::get('/admin/login-as/{id}', function($id) {
//    Auth::loginUsingId($id);
//    return redirect('/');
//});