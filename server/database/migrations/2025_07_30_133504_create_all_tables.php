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
        // Table societes
        Schema::create('societes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // Table categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('societe_id')->constrained('societes')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // Table roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('societe_id')->nullable()->constrained('societes')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // Table users
         Schema::table('users', function (Blueprint $table) {
            $table->string('langue')->nullable()->after('email');
            $table->boolean('is_active')->default(true)->after('email');
            $table->boolean('is_owner')->default(false)->after('email');
            $table->boolean('must_change_pwd')->default(false)->after('password');
            $table->dateTime('date_block')->nullable()->after('must_change_pwd');
            $table->integer('block_count')->default(0)->after('date_block');
            $table->integer('failed_attempts')->default(0)->after('must_change_pwd');
            $table->timestamp('last_failed_at')->nullable()->after('failed_attempts');
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null')->after('must_change_pwd');
            $table->foreignId('societe_id')->nullable()->constrained('societes')->onDelete('cascade')->after('failed_attempts');
        });

        // Table permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Table permission_role
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // Table conversations
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->string('duration')->nullable();
            $table->dateTime('call_date')->nullable();
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('societe_id')->constrained('societes')->onDelete('cascade');
            $table->float('note_ai')->nullable();
            $table->boolean('is_transcripted')->default(false);
            $table->boolean('is_evaluate')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Table dialogues
        Schema::create('dialogues', function (Blueprint $table) {
            $table->id();
            $table->string('actor')->nullable();
            $table->text('text')->nullable();
            $table->float('start', 8, 2)->nullable()->comment('start time in seconds');
            $table->float('end', 8, 2)->nullable()->comment('end time in seconds');
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // Table questions
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // Table reponses
        Schema::create('reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->text('reponse');
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // Table resume
        Schema::create('resume', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // Table commentaires
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            $table->text('commentaire');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commentaires');
        Schema::dropIfExists('resume');
        Schema::dropIfExists('reponses');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('dialogues');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
            $table->dropColumn('langue');
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->dropForeign(['societe_id']);
            $table->dropColumn('societe_id');
        });
        Schema::dropIfExists('roles');
        Schema::dropIfExists('agents');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('societes');
    }
};
