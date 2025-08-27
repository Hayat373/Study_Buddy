<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('quiz_attempts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
        $table->integer('score')->default(0);
        $table->integer('total_questions');
        $table->integer('time_taken')->default(0); // in seconds
        $table->timestamp('completed_at')->nullable();
        $table->timestamps();
    });
    
    Schema::create('quiz_attempt_answers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('quiz_attempt_id')->constrained()->onDelete('cascade');
        $table->foreignId('quiz_question_id')->constrained()->onDelete('cascade');
        $table->text('user_answer');
        $table->boolean('is_correct')->default(false);
        $table->timestamps();
    });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
