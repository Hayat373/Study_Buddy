<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AIService;

class TestOpenAI extends Command
{
    protected $signature = 'openai:test';
    protected $description = 'Test OpenAI API connection';

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