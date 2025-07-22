<?php

namespace App\Http\Controllers\Chat;

use App\Events\PusherBroadcast;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class pusherController extends Controller
{
    public function index()
    {
        return view('chat.index');

    }

    public function broadcast(Request $request)
    {
        broadcast(new PusherBroadcast($request->get('message')))->toOthers();

        return view('chat.broadcast', ['message' => $request->get('message')]);

    }

    public function receive(Request $request)
    {
        return view('chat.receive', ['message' => $request->get('message')]);

    }
}
