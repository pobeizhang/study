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
//显示首页
Route::get( '/', 'StaticPagesController@home' )->name( 'home' );
//显示帮助页
Route::get( '/help', 'StaticPagesController@help' )->name( 'help' );
//显示关于页
Route::get( '/about', 'StaticPagesController@about' )->name( 'about' );

//用户相关资源路由
Route::resource( 'users', 'UsersController' );
//用户激活路由
Route::get( 'signup/confirm/{token}', 'UsersController@confirmEmail' )->name( 'confirm_email' );

//显示登录页面
Route::get( 'login', 'SessionsController@create' )->name( 'login' );
//创建新会话(登录)
Route::post( 'login', 'SessionsController@store' )->name( 'login' );
//销毁会话(退出登录)
Route::delete( 'logout', 'SessionsController@destroy' )->name( 'logout' );

//显示重置密码的邮箱发送页面
Route::get( 'password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm' )->name( 'password.request' );
//邮件发送重置密码页面
Route::post( 'password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail' )->name( 'password.email' );
//密码更新页面
Route::get( 'password/reset/{token}', 'Auth\ResetPasswordController@showResetForm' )->name( 'password.reset' );
//执行密码更新操作
Route::post( 'password/reset', 'Auth\ResetPasswordController@reset' )->name( 'password.update' );

//微博相关资源路由
Route::resource( 'statuses', 'StatusesController', [ 'only' => [ 'store', 'destroy' ] ] );

//显示用户关注的用户列表
Route::get( '/users/{user}/followings', 'UsersController@followings' )->name( 'users.followings' );
//显示用户的粉丝列表
Route::get( '/users/{user}/followers', 'UsersController@followers' )->name( 'users.followers' );

//关注用户
Route::post( '/users/followers/{user}', 'FollowersController@store' )->name( 'followers.store' );
//取消关注用户
Route::delete( '/users/followers/{user}', 'FollowersController@destroy' )->name( 'followers.destroy' );