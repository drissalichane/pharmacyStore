<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\AiChatService;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    protected $ai;

    public function __construct(AiChatService $ai)
    {
        $this->ai = $ai;
    }

    public function message(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:2000',
            'context' => 'nullable|array'
        ]);

        try {
            $reply = $this->ai->sendMessage($data['message'], $data['context'] ?? []);

            return response()->json([
                'role' => 'assistant',
                'message' => $reply,
            ]);
        } catch (\Exception $e) {
            // Log full exception for debugging
            Log::error('ChatController error: '.$e->getMessage(), ['exception' => $e]);
            $msg = $e->getMessage();
            // Return a concise error message to the client to avoid leaking secrets but remain helpful
            return response()->json(['error' => 'AI service unavailable: '.(strlen($msg) > 160 ? substr($msg,0,160).'...' : $msg)], 502);
        }
    }
}
