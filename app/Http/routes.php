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

Route::get('/', 'WelcomeController@index');


// ユーザ登録
Route::get('signup', 'Auth\AuthController@getRegister')->name('signup.get');
Route::post('signup', 'Auth\AuthController@postRegister')->name('signup.post');


// ログイン認証
Route::get('login', 'Auth\AuthController@getLogin')->name('login.get');
Route::post('login', 'Auth\AuthController@postLogin')->name('login.post');
Route::get('logout', 'Auth\AuthController@getLogout')->name('logout.get');

// まとめて認証を通す
Route::group(['middleware' => 'auth'], function () {
    Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
    Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]);

    
    Route::group(['prefix' => 'users/{id}'], function () { 
        // フォロー／アンフォロー
        Route::post('follow', 'UserFollowController@store')->name('user.follow');
        Route::delete('unfollow', 'UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings', 'UsersController@followings')->name('users.followings');
        Route::get('followers', 'UsersController@followers')->name('users.followers');
        
        // お気に入り
        Route::post('add_favorite', 'FavoriteController@store')->name('user.add_favorite');
        Route::delete('remove_favorite', 'FavoriteController@destroy')->name('user.remove_favorite');
        Route::get('favorite_microposts', 'UsersController@favorite_microposts')->name('users.favorite_microposts');
    });
    
    Route::group(['prefix' => 'micorposts/{id}'], function () { 
        Route::post('add_favorite', 'FavoriteController@favorite_users')->name('user.favorite_users');
    });

});


Route::group(['middleware' => 'auth'], function () {
    Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
    
    Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]);
});