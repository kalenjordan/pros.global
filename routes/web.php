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

Route::get('auth/linkedin', 'LinkedInController@login');
Route::get('auth/linkedin/callback', 'LinkedInController@callback');

Route::get('auth/logout', 'LinkedInController@logout');
Route::get('auth/me', 'LinkedInController@me');

Route::get('admin/impersonate/{username}', 'AdminController@impersonate');
Route::get('admin/leave-impersonation', 'AdminController@impersonateLeave');

Route::get('upvotes/{id}', 'UpvoteController@viewHtml');
Route::get('upvotes/{id}/twitter-card', 'UpvoteController@twitterCard');

Route::get('/s/{slug}', 'SavedSearchController@viewHtml');
Route::get('/s/{slug}/twitter-card', 'SavedSearchController@twitterCard');

Route::get('/tag/{slug}', 'TagController@viewHtml');

Route::get('/', 'HomeController@index');
Route::get('/home-twitter-card', 'HomeController@twitterCard');

Route::get('/search', 'UserController@search');

Route::get('{username}', 'UserController@profile');
Route::get('{username}/twitter-card', 'UserController@twitterCard');