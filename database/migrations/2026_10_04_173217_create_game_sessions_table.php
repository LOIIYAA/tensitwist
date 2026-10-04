<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();

            $table->foreignId('spinner_project_id')
                ->constrained('spinner_projects')
                ->cascadeOnDelete();

            $table->string('player_name')
                ->default('Anonim');

            $table->unsignedInteger('total_questions')
                ->default(0);

            $table->unsignedInteger('correct_count')
                ->default(0);

            $table->unsignedInteger('wrong_count')
                ->default(0);

            $table->enum('status', ['in_progress', 'completed', 'abandoned'])
                ->default('in_progress');

            $table->timestamp('started_at')
                ->useCurrent();

            $table->timestamp('finished_at')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};