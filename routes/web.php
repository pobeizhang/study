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

//显示登录页面
Route::get( 'login', 'SessionsController@create' )->name( 'login' );
//创建新会话(登录)
Route::post( 'login', 'SessionsController@store' )->name( 'login' );
//销毁会话(退出登录)
Route::delete( 'logout', 'SessionsController@destroy' )->name( 'logout' );