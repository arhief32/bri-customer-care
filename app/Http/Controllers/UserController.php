<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Conversation;
use App\Message;

class UserController extends Controller
{
    public function startPage()
    {
        if(session('session') == true)
        {
            return redirect(url('chat'));
        }
        else
        {
            return view('start', ['title' => 'Start']);
        }
        
    }

    public function start(Request $request)
    {
        $email = $request->email;
        $full_name = $request->full_name;
        
        $select_data = User::where('email', $email)->first();

        if($select_data == false)
        {
            $insert_data = User::create([
                'name' => $full_name,
                'email' => $email,
                'password' => hash('sha512', 'password'),
                'roles' => 'user',
                'status' => 0,
            ]);

            $select_data = User::where('email', $email)->first();

            session()->put('session' ,[
                'id' => $select_data->id,
                'name' => $select_data->name,
                'email' => $select_data->email,
                'roles' => 'user',
                'status' => 0,
            ]);

            return response()->json([
                'status' => 'insert',
                'data' =>  session('session'),
            ]);
        }

        session()->put('session' ,[
            'id' => $select_data->id,
            'name' => $select_data->name,
            'email' => $select_data->email,
            'roles' => 'user',
            'status' => 0,
        ]);

        return response()->json([
            'status' => 'insert',
            'data' =>  session('session'),
        ]);
    }

    public function dashboard()
    {
        if(session('session') == true)
        {
            return view('chat');
        }
        else
        {
            return redirect('start');
        }
    }

    public function requestConversation(Request $request)
    {
        $id = $request->id;

        $user = User::find($id);
        $user->status = 0;
        $user->save();

        return response()->json(['status' => 'success']);
    }

    public function logout()
    {
        session()->flush('session');
        return redirect('start');
    }

    public function checkConversation(Request $request)
    {
        $user_id = $request->user_id;

        $conversation = Conversation::with('admin', 'user')->where([
            ['user_id', $user_id],
            ['status', 1],
        ])->orderBy('id','desc')->first();
        $messages = Message::with('user')->where('conversation_id', $conversation->id)->get();

        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages
        ]);
    }

    public function sendMessage(Request $request)
    {
        $conversation_id = $request->conversation_id;
        $user_id = $request->user_id;
        $message_text = $request->message;

        $message = new Message;
        $message->conversation_id = $conversation_id;
        $message->user_id = $user_id;
        $message->message = $message_text;
        $message->save();

        $message = Message::where('id', $message->id)->with('user')->first();

        // Send message
        // $client = new \GuzzleHttp\Client();
        // $send_message = $client->post(url('api/send-message'), [
        //     'headers' => [
        //         'Content-Type' => 'application/json'
        //     ],
        //     'body' => $message,
        // ]);
        
        return response()->json($message);
    }
}
