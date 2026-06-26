<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Connection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NetworkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Feed posts
        $posts = Post::with(['user.student', 'user.badges'])->latest()->get();

        // Get current user connections
        $myConnections = Connection::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('connected_user_id', $user->id);
        })->get();

        $connectedUserIds = [];
        $pendingRequestIds = [];

        foreach ($myConnections as $conn) {
            if ($conn->status === 'Accepté') {
                $connectedUserIds[] = $conn->user_id === $user->id ? $conn->connected_user_id : $conn->user_id;
            } else {
                // Pending
                $pendingRequestIds[] = $conn->user_id === $user->id ? $conn->connected_user_id : $conn->user_id;
            }
        }

        // Active connections
        $connections = User::whereIn('id', $connectedUserIds)->get();

        // Pending incoming requests
        $incomingRequests = Connection::with('user')
            ->where('connected_user_id', $user->id)
            ->where('status', 'En attente')
            ->get();

        // Suggested users (exclude current user, already connected users)
        $suggestions = User::where('id', '!=', $user->id)
            ->whereNotIn('id', array_merge($connectedUserIds, $pendingRequestIds))
            ->role(['Etudiant', 'Entreprise', 'Encadreur'])
            ->limit(5)
            ->get();

        return view('network.index', compact('posts', 'connections', 'incomingRequests', 'suggestions'));
    }

    public function storePost(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('network/posts', 'public');
        }

        Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'image_path' => $path
        ]);

        return redirect()->back()->with('success', 'Votre message a été partagé sur le réseau.');
    }

    public function likePost(Post $post)
    {
        $post->increment('likes_count');
        return redirect()->back();
    }

    public function connect(User $user)
    {
        $me = Auth::user();

        // Check if already exists
        $exists = Connection::where(function ($query) use ($me, $user) {
            $query->where('user_id', $me->id)->where('connected_user_id', $user->id);
        })->orWhere(function ($query) use ($me, $user) {
            $query->where('user_id', $user->id)->where('connected_user_id', $me->id);
        })->exists();

        if (!$exists) {
            Connection::create([
                'user_id' => $me->id,
                'connected_user_id' => $user->id,
                'status' => 'En attente'
            ]);

            // Notify user
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => '👥 Demande de connexion',
                'message' => "{$me->name} souhaite vous ajouter à son réseau professionnel.",
                'type' => 'network',
                'link' => route('network.index')
            ]);
        }

        return redirect()->back()->with('success', 'Demande de connexion envoyée.');
    }

    public function acceptConnection(Connection $connection)
    {
        // Ensure recipient is accepting
        if ($connection->connected_user_id !== Auth::id()) {
            abort(403);
        }

        $connection->update(['status' => 'Accepté']);

        // Notify sender
        \App\Models\Notification::create([
            'user_id' => $connection->user_id,
            'title' => '👥 Demande de connexion acceptée',
            'message' => Auth::user()->name . " a accepté votre demande de connexion.",
            'type' => 'network',
            'link' => route('network.index')
        ]);

        return redirect()->back()->with('success', 'Connexion acceptée.');
    }
}
