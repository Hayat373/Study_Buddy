<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add username if it doesn't exist
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('id');
            }
            
            // Add profile_picture if it doesn't exist
            if (!Schema::hasColumn('users', 'profile_picture')) {
                $table->string('profile_picture')->nullable()->after('email');
            }
            
            // Add role if it doesn't exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['student', 'teacher', 'parent', 'lifelong learner'])->default('student')->after('profile_picture');
            }
            
            // Add face_descriptor if it doesn't exist
            if (!Schema::hasColumn('users', 'face_descriptor')) {
                $table->text('face_descriptor')->nullable()->after('role');
            }
            
            // REMOVE THIS LINE - email unique index already exists
            // $table->string('email')->unique()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the custom columns only if they exist
            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn('username');
            }
            if (Schema::hasColumn('users', 'profile_picture')) {
                $table->dropColumn('profile_picture');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'face_descriptor')) {
                $table->dropColumn('face_descriptor');
            }
        });
    }
};