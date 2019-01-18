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

Route::get('auth/linkedin', 'Auth\LinkedInController@login');
Route::get('auth/linkedin/callback', 'Auth\LinkedInController@callback');

Route::get('auth/logout', 'Auth\LoginController@logout'); // by default it just does post

Route::get('/admin/impersonate/{id}', 'AdminController@impersonate');
Route::get('/admin/leave-impersonation', 'AdminController@impersonateLeave');

Route::get('{any?}', function () {
    return view('app');
});
Route::get('{thing1}/{thing2}', function () {
    return view('app');
});

