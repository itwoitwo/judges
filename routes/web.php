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

//ログイン認証するためのルーティング
Route::get('/oauth', 'OAuthController@OAuthlogin')->name('oauth');

//Callback用のルーティング
Route::get('/callBack', 'OAuthController@callBack');

//indexのルーティング
Route::get('/index', 'OAuthController@index');

//logoutのルーティング
Route::get('/logout', 'OAuthController@logout');

Route::get('/', function () {
    return view('welcome');
});

Route::resource('posts','PostsController');
Route::get('users/{screen_name}', 'OAuthController@usershow')->name('show');

Route::post('agree','Post_votesController@agree')->name('vote.agree');
Route::post('disagree','Post_votesController@disagree')->name('vote.disagree');

Route::get('/followlist', 'OAuthController@followlist');