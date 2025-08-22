<?php

namespace App\Services;

use GuzzleHttp\Client;

class AiChatService
{
    protected $client;
    protected $provider;

    public function __construct()
    {
        $this->provider = env('AI_PROVIDER', 'gemini');
        $this->client = new Client([
            'timeout' => 30,
        ]);
    }

    /**
     * Send a message to the AI provider and return a string reply.
     * For Gemini (Google Generative API) this uses the REST generate endpoint.
     */
    public function sendMessage(string $message, array $context = []): string
    {
        if ($this->provider === 'gemini') {
            return $this->sendToGemini($message, $context);
        }

        // Future: support other providers
        throw new \RuntimeException('Unsupported AI provider: '.$this->provider);
    }

    protected function sendToGemini(string $message, array $context = []): string
    {
        $apiKey = env('GEMINI_API_KEY');
        $model = env('GEMINI_MODEL', 'gemini-2.0-flash');
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent';

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $message]
                    ]
                ]
            ]
        ];

        try {
            $response = $this->client->post($url, [
                'json' => $payload,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-goog-api-key' => $apiKey,
                ],
            ]);

            $body = json_decode((string)$response->getBody(), true);

            if (isset($body['candidates'][0]['content']['parts'][0]['text'])) {
                return $body['candidates'][0]['content']['parts'][0]['text'];
            }

            throw new \RuntimeException('Unexpected AI response structure: '.json_encode($body));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AiChatService error: '.$e->getMessage());
            throw new \RuntimeException('AI service error: '.$e->getMessage());
        }
    }

    protected function buildPrompt(string $message, array $context = []): string
    {
        // Simple prompt builder; can be expanded to supply system instructions
        $prompt = '';
        if (!empty($context)) {
            foreach ($context as $c) {
                $prompt .= (is_string($c) ? $c : json_encode($c))."\n";
            }
            $prompt .= "\n";
        }
        $prompt .= $message;
        return $prompt;
    }
}
