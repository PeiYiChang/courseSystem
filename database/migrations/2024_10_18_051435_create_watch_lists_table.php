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
        Schema::create('watch_lists', function (Blueprint $table) {
            $table->id();
            $table->string('studentID');
            $table->string('courseID')->unique();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('studentID')->references('studentID')->on('users')->onDelete('cascade');
            //$table->foreign('courseID')->references('courseID')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watch_lists');
    }
};
