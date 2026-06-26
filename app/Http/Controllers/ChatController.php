<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get all users who have exchanged messages with current user
        $messageUserIds = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->get()
            ->map(function ($msg) use ($user) {
                return $msg->sender_id === $user->id ? $msg->receiver_id : $msg->sender_id;
            })
            ->unique()
            ->toArray();

        // Also include connected users from professional network
        $myConnections = Connection::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('connected_user_id', $user->id);
        })->where('status', 'Accepté')->get();

        $connectedUserIds = [];
        foreach ($myConnections as $conn) {
            $connectedUserIds[] = $conn->user_id === $user->id ? $conn->connected_user_id : $conn->user_id;
        }

        $allChatUserIds = array_unique(array_merge($messageUserIds, $connectedUserIds));

        $chatUsers = User::whereIn('id', $allChatUserIds)->get();

        // Selected user to chat with
        $activeUser = null;
        if ($request->filled('user_id')) {
            $activeUser = User::find($request->user_id);
        } elseif ($chatUsers->count() > 0) {
            $activeUser = $chatUsers->first();
        }

        return view('chat.index', compact('chatUsers', 'activeUser'));
    }

    public function fetchMessages(User $user)
    {
        $me = Auth::user();

        // Fetch messages between current user and specified user
        $messages = Message::where(function ($query) use ($me, $user) {
            $query->where('sender_id', $me->id)->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($me, $user) {
            $query->where('sender_id', $user->id)->where('receiver_id', $me->id);
        })->oldest()->get();

        // Mark incoming messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $me->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $me = Auth::user();

        $message = Message::create([
            'sender_id' => $me->id,
            'receiver_id' => $user->id,
            'content' => $request->content,
            'is_read' => false
        ]);

        // Create db notification
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'title' => '💬 Nouveau message',
            'message' => "Vous avez reçu un message de {$me->name}.",
            'type' => 'message',
            'link' => route('chat.index', ['user_id' => $me->id])
        ]);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
