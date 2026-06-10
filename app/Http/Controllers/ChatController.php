<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(private ChatService $chatService)
    {
    }

    public function history(Request $request)
    {
        $sessionId = $request->session()->getId();

        return response()->json([
            'messages' => $this->chatService->getHistory($sessionId),
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $sessionId = $request->session()->getId();
        $userId = auth()->id();

        $reply = $this->chatService->reply(
            $sessionId,
            $request->message,
            $userId
        );

        return response()->json([
            'reply' => $reply,
            'time' => now()->format('H:i'),
        ]);
    }
}
