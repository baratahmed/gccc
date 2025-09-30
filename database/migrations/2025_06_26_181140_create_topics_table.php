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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->text('topic');
            $table->text('instructions')->nullable();
            $table->integer('points');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('session_id');
            $table->date('due_date');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_topics');
    }
};
