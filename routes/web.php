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
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function(){
    Route::get('/', function(){
        return redirect(url('admin/login'));    
    });
    Route::get('login', 'AdminController@loginPage');
    Route::post('login-validation', 'AdminController@login');
    Route::get('register', 'AdminController@registerPage');
    Route::post('register-validation', 'AdminController@register');
    Route::get('chat', 'AdminController@dashboard');
    Route::get('logout', 'AdminController@logout');
    
    // Open tabs
    Route::get('conversation-list', 'AdminController@conversationList');
    Route::get('unapproved-list', 'AdminController@unapprovedList');

    // Button conversation
    Route::get('approved', 'AdminController@approved');

    Route::get('open-conversation', 'AdminController@openConversation');
    Route::get('break-conversation', 'AdminController@breakConversation');

    Route::post('send-message', 'AdminController@sendMessage');
});


Route::get('start', 'UserController@startPage');
Route::post('start-validation', 'UserController@start');
Route::get('chat', 'UserController@dashboard');
Route::get('logout', 'UserController@logout');

// Request Chat for User
Route::get('request-conversation', 'UserController@requestConversation');

// Chat User
Route::get('check-conversation', 'UserController@checkConversation');
Route::post('send-message', 'UserController@sendMessage');
