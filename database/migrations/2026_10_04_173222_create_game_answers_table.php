<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('game_session_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('spinner_question_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->text('question_snapshot');

            $table->enum('selected_answer', ['mitos', 'fakta']);

            $table->enum('correct_answer_snapshot', ['mitos', 'fakta']);

            $table->boolean('is_correct');

            $table->unsignedInteger('question_order')
                ->default(0);

            $table->timestamp('answered_at')
                ->useCurrent();

            $table->timestamps();

            $table->unique(
                ['game_session_id', 'spinner_question_id'],
                'game_answers_session_question_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_answers');
    }
};