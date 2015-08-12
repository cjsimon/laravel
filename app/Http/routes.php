<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// PostController Index
Route::get('/', function() {
	return redirect()->action('PostController@index');
});

// Create a resource controller for model actions
Route::resource('posts', 'PostController');
// Nested comments routes for posts
Route::resource('posts.comments', 'CommentController');