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
        Schema::create('study_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('subject')->nullable();
            $table->integer('max_members')->default(10);
            $table->boolean('is_public')->default(true);
            $table->string('join_code')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_groups');
    }
};
