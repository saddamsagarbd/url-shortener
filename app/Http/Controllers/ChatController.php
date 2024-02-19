<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\Chat;

class ChatController extends Controller
{
    public function message(Request $request){

        $chat = event(new Chat($request->input("username"), $request->input("message")));

        return ["chat" => $chat];

    }
}
