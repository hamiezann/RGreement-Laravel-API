<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function index(Request $request)
    {
        $senderId = $request->input('sender_id');
       $recipientId = $request->input('recipient_id');
    

    
        $messages = Message::where(function($query) use ($senderId, $recipientId) {
            $query->where('sender_id', $senderId)
                  ->where('recipient_id', $recipientId);
        })->orWhere(function($query) use ($senderId, $recipientId) {
            $query->where('sender_id', $recipientId)
                  ->where('recipient_id', $senderId);
        })->get();
    
        // if ($messages->isEmpty()) {
        //     return response()->json(['message' => 'No messages found'], 404);
        // }
    
        return response()->json($messages);
    }
    

    public function store(Request $request)
    {
        $request->validate(
            [
                'sender_id' => 'required|integer',
                'recipient_id' => 'required|integer',
                'content' => 'required|string',
            ]
            );

        $message = Message::create($request->all());
        return response()->json($message, 201);
    }

    public function show($id)
    {
        $message = Message::findOrFail($id);

        return response()->json($message);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sender_id' => 'required|integer',
            'recipient_id' => 'required|integer',
            'content' => 'required|string',
        ]);

        $message = Message::findOrFail($id);
        $message->update($request->all());

        return response()->json($message, 200);
    }

    public function destroy($id) {
        $message = Message::findOrFail($id);
        $message->delete();

        return response()->json(null, 204);
    }
}
