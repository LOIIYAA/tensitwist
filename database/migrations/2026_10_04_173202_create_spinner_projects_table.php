<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spinner_projects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();

            $table->enum('status', ['draft', 'published'])
                ->default('draft');

            $table->string('theme_color')
                ->default('#93E5F2');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spinner_projects');
    }
};