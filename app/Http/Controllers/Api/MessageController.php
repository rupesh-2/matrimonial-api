<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Send a message to a matched user
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $currentUser = $request->user();
        $toUser = User::findOrFail($request->to_user_id);

        // Check if users are matched
        if (!$currentUser->isMatchedWith($toUser)) {
            return response()->json([
                'message' => 'You can only send messages to matched users',
            ], 403);
        }

        // Create message
        $message = Message::create([
            'from_user_id' => $currentUser->id,
            'to_user_id' => $toUser->id,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message->load('sender', 'recipient'),
        ], 201);
    }

    /**
     * Get chat history with a specific user
     */
    public function getChatHistory(Request $request, User $user)
    {
        $currentUser = $request->user();

        // Check if users are matched
        if (!$currentUser->isMatchedWith($user)) {
            return response()->json([
                'message' => 'You can only view chat history with matched users',
            ], 403);
        }

        $messages = Message::betweenUsers($currentUser->id, $user->id)
                          ->with(['sender:id,name', 'recipient:id,name'])
                          ->orderBy('created_at', 'asc')
                          ->get();

        return response()->json([
            'messages' => $messages,
            'total' => $messages->count(),
        ]);
    }

    /**
     * Get all conversations for the authenticated user
     */
    public function getConversations(Request $request)
    {
        $user = $request->user();

        // Get all matched users
        $matches = $user->matches()->with('preferences')->get();
        $matchedBy = $user->matchedBy()->with('preferences')->get();
        $allMatches = $matches->merge($matchedBy)->unique('id')->values();

        $conversations = [];

        foreach ($allMatches as $match) {
            // Get last message in conversation
            $lastMessage = Message::betweenUsers($user->id, $match->id)
                                ->orderBy('created_at', 'desc')
                                ->first();

            // Get unread count
            $unreadCount = Message::where('from_user_id', $match->id)
                                ->where('to_user_id', $user->id)
                                ->where('is_read', false)
                                ->count();

            $conversations[] = [
                'user' => $match,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
            ];
        }

        // Sort by last message time
        usort($conversations, function ($a, $b) {
            if (!$a['last_message'] && !$b['last_message']) return 0;
            if (!$a['last_message']) return 1;
            if (!$b['last_message']) return -1;
            return $b['last_message']->created_at <=> $a['last_message']->created_at;
        });

        return response()->json([
            'conversations' => $conversations,
            'total' => count($conversations),
        ]);
    }
} 