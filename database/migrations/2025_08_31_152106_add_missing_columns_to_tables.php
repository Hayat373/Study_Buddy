<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add subject to flashcard_sets if it doesn't exist
        if (Schema::hasTable('flashcard_sets') && !Schema::hasColumn('flashcard_sets', 'subject')) {
            Schema::table('flashcard_sets', function (Blueprint $table) {
                $table->string('subject')->nullable()->after('description');
            });
        }
        
        // Add flashcard_id to user_progress if it doesn't exist
        if (Schema::hasTable('user_progress') && !Schema::hasColumn('user_progress', 'flashcard_id')) {
            Schema::table('user_progress', function (Blueprint $table) {
                $table->foreignId('flashcard_id')->nullable()->constrained()->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        // Remove the columns if they exist
        if (Schema::hasTable('flashcard_sets') && Schema::hasColumn('flashcard_sets', 'subject')) {
            Schema::table('flashcard_sets', function (Blueprint $table) {
                $table->dropColumn('subject');
            });
        }
        
        if (Schema::hasTable('user_progress') && Schema::hasColumn('user_progress', 'flashcard_id')) {
            Schema::table('user_progress', function (Blueprint $table) {
                $table->dropForeign(['flashcard_id']);
                $table->dropColumn('flashcard_id');
            });
        }
    }
};