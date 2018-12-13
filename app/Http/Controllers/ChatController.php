<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use App\Message;
use App\Events\SendMessageEvent;
class ChatController extends Controller
{
    public function openConversation(Request $request)
    {
        $conversation_id = $request->conversation_id;
        $messages = Message::with('user')->where('conversation_id', $conversation_id)->get();
        return response()->json($messages);
    }

    public function openConversationUser(Request $request)
    {
        $user_id = $request->user_id;
        $conversation = Conversation::with('user')->where([
            ['user_id', $user_id],
            ['status', 1],
        ])->orderBy('id')->first();
        $messages = Message::with('user')->where('conversation_id', $conversation->id)->get();
        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages
        ]);
    }

    public function sendMessage(Request $request)
    {
        $conversation_id = $request->conversation_id;
        $message_text = $request->message;
        $user_id = $request->user_id;

        $insert_message = new Message;
        $insert_message->conversation_id = $conversation_id;
        $insert_message->message = $message_text;
        $insert_message->user_id = $user_id;
        $insert_message->save();

        $message = Message::with('user')->where('conversation_id', $conversation_id)->orderBy('id', 'desc')->first();

        $client = new \GuzzleHttp\Client();
        $post_message = $client->post(url('api/post-message'), [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => $message,
        ]);

        return response()->json($message);
    }
}
