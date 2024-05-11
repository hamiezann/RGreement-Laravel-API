<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\User;

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
    // Merge in the current date and time before creating the message
    $data = $request->all();
    $data['created_at'] = Carbon::now();
    $data['updated_at'] = Carbon::now();

    $message = Message::create($data);
     //   $message = Message::create($request->all());
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


    // COnversation listed

    // public function conversation_list(Request $request)
    // {
    //   $userId = $request->input('user_id');
    
    //   // Retrieve distinct sender_ids where the user is the recipient
    //   $senderIds = Message::where('recipient_id', $userId)->distinct()->pluck('sender_id');
    
    //   // Retrieve distinct recipient_ids where the user is the sender
    //   $recipientIds = Message::where('sender_id', $userId)->distinct()->pluck('recipient_id');
    
    //   // Combine sender_ids and recipient_ids and remove duplicates
    //   $oppositeIds = $senderIds->merge($recipientIds)->unique();
    
    //   return response()->json($oppositeIds);
    // }
    public function conversation_list(Request $request)
{
    $userId = $request->input('user_id');
    
    // Retrieve distinct sender_ids where the user is the recipient
    $senderIds = Message::where('recipient_id', $userId)->distinct()->pluck('sender_id');
    
    // Retrieve distinct recipient_ids where the user is the sender
    $recipientIds = Message::where('sender_id', $userId)->distinct()->pluck('recipient_id');
    
    // Combine sender_ids and recipient_ids and remove duplicates
    $oppositeIds = $senderIds->merge($recipientIds)->unique();
    
    // Fetch names associated with oppositeIds
    $oppositeUsers = User::whereIn('id', $oppositeIds)->pluck('name', 'id');

    // Fetch the latest message snippet for each opposite ID
    $latestMessages = [];
    foreach ($oppositeIds as $oppositeId) {
        $latestMessage = Message::where(function($query) use ($userId, $oppositeId) {
            $query->where('sender_id', $userId)->where('recipient_id', $oppositeId);
        })->orWhere(function($query) use ($userId, $oppositeId) {
            $query->where('sender_id', $oppositeId)->where('recipient_id', $userId);
        })->latest()->first();

        // If there's a message, extract the snippet, otherwise set to null
        $snippet = $latestMessage ? $latestMessage->content : null;

        $latestMessages[$oppositeId] = $snippet;
    }

    // Combine user names and latest messages into a single array
    $conversations = [];
    foreach ($oppositeUsers as $oppositeId => $name) {
        $conversations[] = [
            'id' => $oppositeId,
            'name' => $name,
            'latest_message' => $latestMessages[$oppositeId]
        ];
    }

    return response()->json($conversations);
}

    
    
      


    public function conversation_show(Request $request, $senderId, $recipientId)
    {
        $messages =Message::where(function($query) use ($senderId, $recipientId){
            $query->where('sender_id', $senderId)
            ->where('recipient_id', $recipientId);

        })->orWhere(function($query) use ($senderId, $recipientId) {
        $query->where('sender_id', $recipientId)
              ->where('recipient_id', $senderId);
    })->get();

    return response()->json($messages);
    }



    

}
