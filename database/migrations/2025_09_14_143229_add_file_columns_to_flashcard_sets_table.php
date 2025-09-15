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
        Schema::table('flashcard_sets', function (Blueprint $table) {
            $table->string('original_filename')->nullable()->after('description');
            $table->string('file_path')->nullable()->after('original_filename');
            $table->string('file_type')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down()
    {
        Schema::table('flashcard_sets', function (Blueprint $table) {
            $table->dropColumn(['original_filename', 'file_path', 'file_type']);
        });
    }
};
