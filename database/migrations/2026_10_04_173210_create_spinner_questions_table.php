<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spinner_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('spinner_project_id')
                ->constrained('spinner_projects')
                ->cascadeOnDelete();

            $table->text('question_text');

            $table->enum('correct_answer', ['mitos', 'fakta']);

            $table->text('explanation')->nullable();

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spinner_questions');
    }
};