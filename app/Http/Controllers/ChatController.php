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

            // Format the reply - convert markdown to HTML and handle line breaks
            $formattedReply = $this->markdownToHtml($reply);

            return response()->json([
                'role' => 'assistant',
                'message' => $formattedReply,
            ]);
        } catch (\Exception $e) {
            // Log full exception for debugging
            Log::error('ChatController error: '.$e->getMessage(), ['exception' => $e]);
            $msg = $e->getMessage();
            // Return a concise error message to the client to avoid leaking secrets but remain helpful
            return response()->json(['error' => 'AI service unavailable: '.(strlen($msg) > 160 ? substr($msg,0,160).'...' : $msg)], 502);
        }
    }

    /**
     * Convert markdown formatting to HTML
     */
    private function markdownToHtml(string $text): string
    {
        // First escape all HTML to prevent XSS
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        
        // Convert markdown bold **text** to <strong>text</strong>
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        
        // Convert markdown italic *text* to <em>text</em>
        $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
        
        // Convert headers ## Title to <h4 class="chat-title">Title</h4>
        $text = preg_replace('/##\s+(.*?)(?=\n|$)/', '<h4 class="chat-title">$1</h4>', $text);
        
        // Convert subheaders ### Subtitle to <h5 class="chat-subtitle">Subtitle</h5>
        $text = preg_replace('/###\s+(.*?)(?=\n|$)/', '<h5 class="chat-subtitle">$1</h5>', $text);
        
        // Convert line breaks
        $text = nl2br($text);
        
        return $text;
    }
}
