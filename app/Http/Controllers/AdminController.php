<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Conversation;
use App\Message;
use Carbon\Carbon;
class AdminController extends Controller
{
    public function registerPage()
    {
        if(session('session') == true)
        {
            return redirect(url('admin/chat'));
        }
        else
        {
            return view('admin/register', ['title' => 'Register']);
        }
        
    }

    public function register(Request $request)
    {
        $email = $request->email;
        $full_name = $request->full_name;
        $password = hash('sha512', $request->password);

        $select_data = User::where([
            ['email', $email],
            ['roles', 'admin'],
        ])->first();

        if($select_data == false)
        {
            $insert_data = User::create([
                'name' => $full_name,
                'email' => $email,
                'password' => $password,
                'roles' => 'admin',
                'status' => 1
            ]);

            return response()->json([
                'status' => 'insert'
            ]);
        }
        else
        {
            session()->put('session' ,[
                'id' => $select_data->id,
                'name' => $select_data->name,
                'email' => $select_data->email,
                'roles' => 'admin',
                'status' => 1,
            ]);

            return response()->json([
                'status' => 'exist',
                'data' =>  session('session'),
            ]);
        }
    }

    public function loginPage()
    {
        if(session('session') == true)
        {
            return redirect(url('admin/chat'));
        }
        else
        {
            return view('admin/login', ['title' => 'Login']);
        }
    }

    public function login(Request $request)
    {
        $email = $request->email;
        $password = hash('sha512', $request->password);

        $select_data = User::where([
            ['email', $email],
            ['password', $password],
            ['roles', 'admin']
        ])->first();

        if($select_data == false)
        {
            return response()->json([
                'status' => 'failed'
            ]);
        }
        else
        {
            session()->put('session' ,[
                'id' => $select_data->id,
                'name' => $select_data->name,
                'email' => $select_data->email,
                'roles' => 'admin',
            ]);

            return response()->json([
                'status' => 'success',
                'data' => session('session'),
            ]);
        }
    }

    public function dashboard()
    {
        if(session('session') == true)
        {
            return view('admin/chat', ['title' => 'Conversation']);
        }
        else
        {
            return redirect('admin/login');
        }
    }

    public function logout()
    {
        session()->flush('session');
        return redirect('admin/login');
    }

    public function conversationList(Request $request)
    {
        $admin_id = $request->admin_id;
        $conversation_list = Conversation::with('admin', 'user')
        ->where([
            ['admin_id', $admin_id],
            ['status', 1],
        ])->get();

        return response()->json($conversation_list);
    }

    public function unapprovedList()
    {
        $unapproved_list = User::where([
            ['status', 0],
            ['roles', 'user'],
        ])->get();

        return response()->json($unapproved_list);
    }

    public function approved(Request $request)
    {
        $admin_id = $request->admin_id;
        $user_id = $request->user_id;
        
        $user = User::find($user_id);
        $user->status = 1;
        $user->save();

        // Create Conversation
        $conversation = new Conversation;
        $conversation->admin_id = $admin_id;
        $conversation->user_id = $user_id;
        $conversation->status = 1;
        $conversation->save();

        $conversation = Conversation::with('admin', 'user')->where([
            ['admin_id', $admin_id],
            ['user_id', $user_id],
        ])
        ->orderBy('id', 'desc')
        ->first();

        // Send Approved Status User to Open Chat Box User
        // $client = new \GuzzleHttp\Client();
        // $post_approve = $client->post(url('api/post-approve'), [
        //     'headers' => [
        //         'Content-Type' => 'application/json'
        //     ],
        //     'body' => $conversation,
        // ]);

        // Send Greeting Message
        $message = new Message;
        $message->conversation_id = $conversation->id;
        $message->user_id = $conversation->admin_id;
        $message->message = 'Halo '.$conversation->user->name.
            '! Terima kasih telah menghubungi kami, saya '.$conversation->admin->name.
            ' dari tim support akan melayani pertanyaan anda. Ada yang bisa saya bantu?';
        $message->save();

        return response()->json($conversation);
    }

    public function openConversation(Request $request)
    {
        $conversation_id = $request->conversation_id;

        $conversation = Conversation::with('admin', 'user')->find($conversation_id);
        $messages = Message::with('user')->where('conversation_id', $conversation_id)->get();

        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages
        ]);
    }

    public function breakConversation(Request $request)
    {
        $id = $request->id;

        $conversation = Conversation::find($id);
        $conversation->status = 0;
        $conversation->save();

        // Send message
        // $client = new \GuzzleHttp\Client();
        // $break_conversation = $client->post(url('api/break-conversation'), [
        //     'headers' => [
        //         'Content-Type' => 'application/json'
        //     ],
        //     'body' => $conversation,
        // ]);

        return response()->json(['status' => 'success']);
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

