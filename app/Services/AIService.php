<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $apiKey;
    protected $apiUrl;
    
    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->apiUrl = 'https://api.openai.com/v1/chat/completions';
    }
    
    public function generateFlashcards($topic, $count = 5)
    {
        $prompt = "Generate {$count} educational flashcards about {$topic}. 
        Return only a JSON array with each element having 'question' and 'answer' keys.
        Example: [{'question': 'What is PHP?', 'answer': 'A server-side scripting language'}]";
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000,
            ]);
            
            if ($response->successful()) {
                $content = $response->json();
                $flashcardsJson = $content['choices'][0]['message']['content'];
                
                // Clean the response to extract only JSON
                preg_match('/\[.*\]/s', $flashcardsJson, $matches);
                
                if (!empty($matches)) {
                    $flashcards = json_decode($matches[0], true);
                    
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $flashcards;
                    }
                }
                
                throw new \Exception('Invalid JSON response from AI service');
            } else {
                throw new \Exception('AI service request failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('AI Flashcard generation failed: ' . $e->getMessage());
            throw $e;
        }
    }
}