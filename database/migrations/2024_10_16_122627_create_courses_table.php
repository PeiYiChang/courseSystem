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
        Schema::create('courses', function (Blueprint $table) {
            $table->id(); //autoincrement
            $table->string('courseID');
            $table->string('courseTitle');
            $table->integer('credit');
            $table->boolean('mandatory');
            $table->string('major');
            $table->integer('grade');
            $table->integer('day');
            $table->integer('period');
            $table->string('instructor');
            $table->integer('maxCapacity');
            $table->integer('currentCapacity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
