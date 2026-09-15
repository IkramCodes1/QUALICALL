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
        // TABLE TOPICS
        Schema::create('topics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conversation_id')
                ->constrained('conversations')
                ->onDelete('cascade');

            $table->string('name');

            $table->float('start_time', 8, 2)->nullable();
            $table->float('end_time', 8, 2)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // TABLE RESUME GENERAL
        Schema::create('resume_general', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conversation_id')
                ->constrained('conversations')
                ->onDelete('cascade');

            $table->longText('text');

            $table->timestamps();
            $table->softDeletes();
        });

        // TABLE RESUME TOPIC
        Schema::create('resume_topic', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conversation_id')
                ->constrained('conversations')
                ->onDelete('cascade');

            $table->foreignId('topic_id')
                ->constrained('topics')
                ->onDelete('cascade');

            $table->longText('text');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_topic');
        Schema::dropIfExists('resume_general');
        Schema::dropIfExists('topics');
    }
};