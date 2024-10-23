<?php

namespace App\Http\Controllers;

// use App\Events\PublicChatEvent;
use App\Models\Message;
use Illuminate\Http\Request;

class PublicMessageController extends Controller
{
    public function index()
    {
        $message = Message::all();
        return response()->json([
            'status' => 'success',
            'data' => $message
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required',
        ]);

        $data['message'] = $validated['message'];
        $data['user_id'] = auth()->user()->id;

        $message = Message::create($data);
        broadcast(new \App\Events\PublicChatEvent($message));

        return response()->json([
            'status' => 'success',
            'data' => $message
        ]);
    }
}
