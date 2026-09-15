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
        Schema::create('evaluations', function (Blueprint $table) {

            $table->id();
        
            $table->foreignId('conversation_id')
                  ->constrained()
                  ->onDelete('cascade');
        
            $table->integer('score_global')->nullable();
        
            $table->integer('politesse_score')->nullable();
        
            $table->integer('clarte_score')->nullable();
        
            $table->integer('respect_script_score')->nullable();
        
            $table->integer('satisfaction_client_score')->nullable();
        
            $table->text('points_forts')->nullable();
        
            $table->text('points_faibles')->nullable();
        
            $table->text('commentaire_ai')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
