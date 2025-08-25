<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('user_progress', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('flashcard_set_id')->nullable()->constrained()->onDelete('set null');
        $table->string('type'); // 'study', 'quiz', etc.
        $table->text('description');
        $table->integer('study_time')->default(0); // in minutes
        $table->decimal('mastery_level', 5, 2)->default(0); // 0-100%
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
