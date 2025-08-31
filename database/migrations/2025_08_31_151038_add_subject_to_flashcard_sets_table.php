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
            if (!Schema::hasColumn('flashcard_sets', 'subject')) {
                $table->string('subject')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down()
    {
        Schema::table('flashcard_sets', function (Blueprint $table) {
            if (Schema::hasColumn('flashcard_sets', 'subject')) {
                $table->dropColumn('subject');
            }
        });
    }
};
