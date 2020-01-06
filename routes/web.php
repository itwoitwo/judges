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
Route::get('/oauth', 'OAuthController@login');

//Callback用のルーティング
Route::get('/callBack', 'OAuthController@callBack');

//indexのルーティング
Route::get('/index', 'OAuthController@index');

//logoutのルーティング
Route::get('/logout', 'OAuthController@logout');

//logout後のリダイレクト先
Route::get('/', function () {
    return view('welcome');
    
//ユーザー登録
Route::get('/signup', 'Auth\RegisterController@register')->name('signup.get');
Route::post('/signup', 'Auth\RegisterController@register')->name('signup.post');


});