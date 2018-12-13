<?php

use Illuminate\Http\Request;
use Pusher\Pusher;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('post-approve', function(Request $request){
    $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        ]
    );

    $data = $request->all();
    $pusher->trigger(
        'channel-approve-'.$request->user_id, 
        'event-approve-'.$request->user_id, $data);
});

Route::post('send-message', function(Request $request){
    $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        ]
    );

    $data = $request->all();
    $pusher->trigger(
        'channel-message-'.$request->conversation_id, 
        'event-message-'.$request->conversation_id, $data);
});

Route::post('break-conversation', function(Request $request){
    $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        ]
    );

    $data = $request->all();
    $pusher->trigger(
        'channel-break-conversation-'.$request->id, 
        'event-break-conversation-'.$request->id, $data);
});