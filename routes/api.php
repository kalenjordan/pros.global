<?php

use Illuminate\Http\Request;

Route::prefix('v1')->group(function () {
    Route::get('saved-searches', 'SavedSearchController@list');
    Route::get('saved-searches/{slug}', 'SavedSearchController@view');
    Route::get('saved-searches/{slug}/related', 'SavedSearchController@related');

    Route::get('tags', 'TagController@index');
    Route::get('tags/{slug}', 'TagController@view');

    Route::get('users', 'UserController@list');
    Route::get('users/{username}', 'UserController@view');

    Route::get('upvotes/{id}', 'UpvoteController@view');

    Route::group(['middleware' => ['auth:api']], function () {

        Route::get('me', 'UserController@me');

        Route::get('messages/with-other-user/{otherUserId}', 'MessageController@withOtherUser');
        Route::post('messages', 'MessageController@send');

        Route::get('notifications', 'NotificationController@list');
        Route::get('notifications/mark-read', 'NotificationController@markRead');

        Route::post('saved-searches', 'SavedSearchController@create');
        Route::post('saved-searches/{id}', 'SavedSearchController@edit');
        Route::post('saved-searches/{slug}/related', 'SavedSearchController@newRelated');
        Route::post('saved-searches/{slug}/related/remove', 'SavedSearchController@removeRelated');

        Route::post('tag/{slug}', 'TagController@edit');

        Route::get('twitter/add-user/{username}', 'TwitterController@addUser');

        Route::post('upvotes/{id}', 'UpvoteController@post');

        Route::post('users', 'UserController@newUser');
        Route::post('users/{username}', 'UserController@post');
        Route::post('users/{username}/add-tag', 'UserController@addTag');
        Route::get('users/{username}/delete-tag/{tag}', 'UserController@deleteTag');
        Route::get('users/{username}/upvote-tag/{tag}', 'UserController@upvoteTag');
        Route::get('users/{username}/merge/{merging_username}', 'UserController@merge');
    });
});
