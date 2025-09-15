<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $apiKey;
    protected $apiUrl;
     protected $model;
    protected $maxTokens;
    protected $temperature;
    
   public function __construct()
{
     $this->apiKey = config('services.openrouter.api_key'); // Make sure this matches
    $this->apiUrl = config('services.openrouter.api_url', 'https://openrouter.ai/api/v1/chat/completions');
    $this->model = config('services.openrouter.model', 'google/gemini-flash-1.5');
    
    // Disable SSL verification for development
    if (app()->environment('local')) {
        Http::withOptions(['verify' => false]);
    }


}
    
   public function generateFlashcards($topic, $count = 5)
{
    try {
        $prompt = $this->createTopicPrompt($topic, $count);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url'),
            'X-Title' => config('app.name'),
        ])->post($this->apiUrl, [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert educational assistant that creates high-quality flashcards. Create clear, concise question-answer pairs.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 2000,
            'temperature' => 0.7
        ]);

        // Check if request failed
        if ($response->failed()) {
            Log::error('OpenRouter API Error: ' . $response->body());
            throw new \Exception('AI service unavailable: ' . $response->body());
        }

        $result = $response->json();
        
        // Debug: log the full response
        Log::debug('OpenRouter API Response:', $result);

        // Extract the content from the response
        $content = $result['choices'][0]['message']['content'] ?? null;
        
        if (!$content) {
            throw new \Exception('No content in API response');
        }

        // Parse the response into flashcards
        return $this->parseFlashcardsFromResponse($content);

    } catch (\Exception $e) {
        Log::error('AI Flashcard Generation Error: ' . $e->getMessage());
        
        // Return mock data as fallback
        return $this->getMockFlashcards($topic, $count);
    }
}

     protected function createTopicPrompt($topic, $count)
    {
        return <<<PROMPT
Create {$count} high-quality flashcards about: {$topic}

For each flashcard, provide:
1. A clear question that tests understanding of key concepts
2. A concise answer that directly addresses the question
3. Focus on the most important information

Please respond with exactly {$count} flashcards in the following JSON format:

{
  "flashcards": [
    {
      "question": "Clear question here",
      "answer": "Concise answer here"
    }
  ]
}
PROMPT;
    }
    
        // For now, return mock data since we don't have an API key
        // Remove this when you add your OpenAI API key
        // return $this->getMockFlashcards($topic, $count);
        
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

     // New method for file analysis
 // app/Services/AIService.php
public function generateFlashcardsFromFile($filePath, $fileType, $count = 10)
{
    try {
        \Log::info('Generating flashcards from file: ' . $filePath);
        
        // Read file content based on file type
        $content = $this->extractFileContent($filePath, $fileType);
        
        if (empty($content)) {
            throw new \Exception('Could not extract content from file');
        }

        \Log::info('File content extracted successfully, length: ' . strlen($content));
        
        // TEMPORARY: Use mock data until API is configured
        if (empty($this->apiKey)) {
            \Log::warning('OpenRouter API key not configured, using mock data');
            return $this->generateMockFlashcardsFromContent($content, $count);
        }

        // Prepare prompt for AI
        $prompt = $this->createFileAnalysisPrompt($content, $count);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url'),
            'X-Title' => config('app.name'),
        ])->withOptions(['verify' => app()->environment('local') ? false : true])
        ->post($this->apiUrl, [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert educational assistant that creates high-quality flashcards from provided content. Create clear, concise question-answer pairs.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 2000,
            'temperature' => 0.7
        ]);

        if ($response->failed()) {
            \Log::error('OpenRouter API Error: ' . $response->body());
            throw new \Exception('AI service unavailable: ' . $response->body());
        }

        $result = $response->json();
        $content = $result['choices'][0]['message']['content'] ?? '';

        if (empty($content)) {
            throw new \Exception('Empty response from AI service');
        }

        // Parse the response into flashcards
        return $this->parseFlashcardsFromResponse($content);

    } catch (\Exception $e) {
        \Log::error('AI Flashcard Generation Error: ' . $e->getMessage());
        
        // Return mock data as fallback
        return $this->generateMockFlashcardsFromContent('file_content', $count);
    }
}

// Add this new method for mock data generation from file content
protected function generateMockFlashcardsFromContent($content, $count)
{
    \Log::info('Generating mock flashcards from content');
    
    // Extract some keywords from the content for more relevant mock data
    $keywords = $this->extractKeywords($content);
    
    $mockFlashcards = [];
    for ($i = 1; $i <= $count; $i++) {
        $mockFlashcards[] = [
            'question' => "Question about " . ($keywords[0] ?? 'content') . " #$i",
            'answer' => "Answer explaining " . ($keywords[0] ?? 'content') . " concept #$i"
        ];
    }
    
    return $mockFlashcards;
}

// Helper method to extract keywords
protected function extractKeywords($content)
{
    // Simple keyword extraction - you can improve this later
    $words = str_word_count($content, 1);
    $filtered = array_filter($words, function($word) {
        return strlen($word) > 5 && !in_array(strtolower($word), ['the', 'and', 'for', 'with', 'that', 'this']);
    });
    
    return array_slice(array_unique($filtered), 0, 5);
}




   protected function extractFileContent($filePath, $fileType)
{
    $fullPath = storage_path('app/public/' . $filePath);
    
    switch ($fileType) {
        case 'txt':
        case 'md':
            return file_get_contents($fullPath);
            
        case 'pdf':
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($fullPath);
            return $pdf->getText();
            
        case 'docx':
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($fullPath);
            $content = '';
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                        foreach ($element->getElements() as $text) {
                            $content .= $text->getText();
                        }
                    } elseif ($element instanceof \PhpOffice\PhpWord\Element\Text) {
                        $content .= $element->getText();
                    }
                    $content .= "\n";
                }
            }
            return $content;
            
        default:
            throw new \Exception("Unsupported file type: {$fileType}");
    }
}


    protected function createFileAnalysisPrompt($content, $count)
    {
        $contentSnippet = substr($content, 0, 4000); // Limit content length
        
        return <<<PROMPT
Analyze the following content and create {$count} high-quality flashcards. For each flashcard, provide:

1. A clear question that tests understanding of key concepts
2. A concise answer that directly addresses the question
3. Focus on the most important information

Content to analyze:
{$contentSnippet}

Please respond with exactly {$count} flashcards in the following JSON format:

{
  "flashcards": [
    {
      "question": "Clear question here",
      "answer": "Concise answer here"
    }
  ]
}
PROMPT;
    }

   protected function parseFlashcardsFromResponse($content)
{
    try {
        Log::debug('API Response Content:', ['content' => $content]);
        
        // First, try to extract JSON from the response
        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $content, $matches)) {
            $data = json_decode($matches[0], true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                if (isset($data['flashcards'])) {
                    return $data['flashcards'];
                }
                
                // If the response is a direct array of flashcards
                if (isset($data[0]['question']) && isset($data[0]['answer'])) {
                    return $data;
                }
            }
        }
        
        // Try to find array format
        if (preg_match('/\[(.*)\]/s', $content, $matches)) {
            $data = json_decode($matches[0], true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                if (isset($data[0]['question']) && isset($data[0]['answer'])) {
                    return $data;
                }
            }
        }
        
        // Fallback: manual parsing for text format
        $lines = explode("\n", $content);
        $flashcards = [];
        $currentCard = [];
        $inQuestion = false;
        $inAnswer = false;
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (empty($line)) continue;
            
            // Look for question markers
            if (preg_match('/^(?:question|q)[:\s]*(.+)/i', $line, $matches)) {
                if (!empty($currentCard)) {
                    $flashcards[] = $currentCard;
                }
                $currentCard = ['question' => trim($matches[1]), 'answer' => ''];
                $inQuestion = true;
                $inAnswer = false;
            }
            // Look for answer markers
            elseif (preg_match('/^(?:answer|a)[:\s]*(.+)/i', $line, $matches) && !empty($currentCard)) {
                $currentCard['answer'] = trim($matches[1]);
                $inQuestion = false;
                $inAnswer = true;
            }
            // Continue question or answer
            elseif (!empty($currentCard)) {
                if ($inQuestion) {
                    $currentCard['question'] .= ' ' . $line;
                } elseif ($inAnswer) {
                    $currentCard['answer'] .= ' ' . $line;
                }
            }
        }
        
        // Add the last card
        if (!empty($currentCard)) {
            $flashcards[] = $currentCard;
        }
        
        if (!empty($flashcards)) {
            return $flashcards;
        }
        
        throw new \Exception('Could not parse any flashcards from response');
            
    } catch (\Exception $e) {
        Log::error('Failed to parse AI response: ' . $e->getMessage());
        throw new \Exception('Failed to parse flashcards from AI response: ' . $e->getMessage());
    }
}

public function handle()
{
    $aiService = new AIService();
    
    try {
        $this->info('Testing OpenAI API connection...');
        
        // Test with a simple prompt
        $flashcards = $aiService->generateFlashcards('basic math', 2);
        
        $this->info('✅ OpenAI API is working!');
        $this->info('Generated flashcards:');
        
        // Check if flashcards is null
        if ($flashcards === null) {
            $this->error('❌ API returned null response');
            return;
        }
        
        // Check if flashcards is an array
        if (!is_array($flashcards)) {
            $this->error('❌ API returned non-array response: ' . gettype($flashcards));
            $this->line('Response: ' . json_encode($flashcards));
            return;
        }
        
        foreach ($flashcards as $index => $flashcard) {
            $this->line(($index + 1) . '. Q: ' . $flashcard['question']);
            $this->line('   A: ' . $flashcard['answer']);
            $this->line('');
        }
        
    } catch (\Exception $e) {
        $this->error('❌ OpenAI API Error: ' . $e->getMessage());
        $this->line('Check your API key in the .env file:');
        $this->line('OPENAI_API_KEY=' . config('services.openai.api_key'));
    }
}


}