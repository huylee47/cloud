<?php

namespace App\Service;

use App\Events\MessageSent;
use App\Events\NewChatCreated;
use App\Models\Chats;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatsService
{
// ADMIN
public function index()
{
    $adminId = Auth::id();

    $chats = Chats::with(['customer' => function ($query) {
        $query->where('role_id', 2);
    }])
    ->where(function ($query) use ($adminId) {
        $query->whereNull('staff_id')
              ->orWhere('staff_id', $adminId);
    })
    ->where(function ($query) {
        $query->whereHas('customer', function ($q) {
            $q->where('role_id', 2);
        })->orWhereNotNull('guest_id');
    })
    ->orderBy('created_at', 'desc')
    ->get();

    return view('admin.message.index', compact('chats'));
}



public function loadMessageAdmin($chatId)
{
    $messages = Message::where('chat_id', $chatId)->orderBy('created_at', 'asc')->get();

    $messages->each(function ($msg) {
        $user = User::find($msg->sender_id);
        $msg->customer_name = $user ? $user->name : "Guest";
        $msg->role_id = $user ? $user->role_id : null;
        $msg->gender = $user ? $user->gender : null;
    });

    return response()->json(['messages' => $messages]);
}

public function sendMessageAdmin($request, $chatId)
{
    $adminId = Auth::id();

    $chat = Chats::find($chatId);
    if ($chat && is_null($chat->staff_id)) {
        $chat->staff_id = $adminId;
        $chat->save();
    }

    $message = new Message();
    $message->chat_id = $chatId;
    $message->sender_id = $adminId;
    $message->guest_id = null;
    $message->message = $request->message;
    $message->save();
    $message->load('User');

    broadcast(new MessageSent($message))->toOthers();

    return response()->json([
        'success' => true, 
        'message' => $message
    ]);
}

// CLIENT
public function sendMessage($request)
{
    $message = $request->input('message');

    if (!$message) {
        return response()->json(['error' => 'Tin nhắn không được để trống'], 400);
    }

    if (Auth::check()) {
        $senderId = Auth::id();
        $guestId = null;
    } else {
        $senderId = null;
        $guestId = session()->getId();
    }

    $chat = Chats::where(function ($query) use ($senderId, $guestId) {
            if ($senderId) {
                $query->where('customer_id', $senderId);
            } else {
                $query->where('guest_id', $guestId);
            }
        })->first();

    $isNewChat = false;

    if (!$chat) {
        $chat = Chats::create([
            'customer_id' => $senderId,
            'guest_id' => $guestId,
            'status_id' => 1
        ]);
        $isNewChat = true;
    }

    $newMessage = Message::create([
        'chat_id' => $chat->id,
        'sender_id' => $senderId,
        'guest_id' => $guestId,
        'message' => $message
    ]);

    $newMessage->load('User');

    broadcast(new MessageSent($newMessage))->toOthers();

    if ($isNewChat) {
        broadcast(new NewChatCreated($chat))->toOthers();
    }

    return response()->json([
        'success' => true,
        'chat_id' => $chat->id,
        'message' => $newMessage,
    ]);
}

public function loadMessage()
{
    $user = Auth::user();
    $guestId = session()->getId();

    $chat = Chats::where(function ($query) use ($user, $guestId) {
            if ($user) {
                $query->where('customer_id', $user->id);
            } else {
                $query->where('guest_id', $guestId);
            }
        })->first();

    if (!$chat) {
        return response()->json([]);
    }

    $messages = Message::with('User')
        ->where('chat_id', $chat->id)
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function ($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'sender_id' => $message->sender_id,
                'guest_id' => $message->guest_id,
                'role_id' => $message->User?->role_id ?? null,
                'customer_name' => $message->User?->name ?? null,
            ];
        });

    return response()->json([
        'chat_id' => $chat->id,
        'messages' => $messages
    ]);
}

}
