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
        // For now, return mock data since we don't have an API key
        // Remove this when you add your OpenAI API key
        return $this->getMockFlashcards($topic, $count);
        
        /*
        // Actual implementation when you have an API key:
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
        */
    }
    
    // Mock data for development
    protected function getMockFlashcards($topic, $count)
    {
        $mockFlashcards = [
            'math' => [
                ['question' => 'What is 2 + 2?', 'answer' => '4'],
                ['question' => 'What is the square root of 16?', 'answer' => '4'],
                ['question' => 'Solve for x: 2x + 3 = 11', 'answer' => 'x = 4'],
                ['question' => 'What is π approximately equal to?', 'answer' => '3.14159'],
                ['question' => 'What is the area of a circle with radius 3?', 'answer' => '28.27 units²'],
            ],
            'science' => [
                ['question' => 'What is H₂O?', 'answer' => 'Water'],
                ['question' => 'What is the chemical symbol for gold?', 'answer' => 'Au'],
                ['question' => 'What planet is known as the Red Planet?', 'answer' => 'Mars'],
                ['question' => 'What is the largest organ in the human body?', 'answer' => 'Skin'],
                ['question' => 'What is photosynthesis?', 'answer' => 'Process plants use to convert sunlight into energy'],
            ],
            'history' => [
                ['question' => 'In what year did World War II end?', 'answer' => '1945'],
                ['question' => 'Who was the first President of the United States?', 'answer' => 'George Washington'],
                ['question' => 'What was the name of the ship that brought the Pilgrims to America?', 'answer' => 'Mayflower'],
                ['question' => 'Which ancient civilization built the pyramids?', 'answer' => 'Egyptians'],
                ['question' => 'When did the French Revolution begin?', 'answer' => '1789'],
            ],
            'programming' => [
                ['question' => 'What does HTML stand for?', 'answer' => 'HyperText Markup Language'],
                ['question' => 'What language is known as the "language of the web"?', 'answer' => 'JavaScript'],
                ['question' => 'What is a variable?', 'answer' => 'A container for storing data values'],
                ['question' => 'What does CSS stand for?', 'answer' => 'Cascading Style Sheets'],
                ['question' => 'What is an API?', 'answer' => 'Application Programming Interface'],
            ]
        ];
        
        $topicKey = strtolower($topic);
        if (array_key_exists($topicKey, $mockFlashcards)) {
            return array_slice($mockFlashcards[$topicKey], 0, $count);
        }
        
        // Default mock flashcards
        return [
            ['question' => "What is {$topic}?", 'answer' => "{$topic} is a subject worth studying"],
            ['question' => "Who discovered {$topic}?", 'answer' => "Important figures in history"],
            ['question' => "Why is {$topic} important?", 'answer' => "It helps us understand the world better"],
            ['question' => "What are the main concepts of {$topic}?", 'answer' => "Fundamental principles and theories"],
            ['question' => "How is {$topic} applied in real life?", 'answer' => "Practical applications and examples"],
        ];
    }
}