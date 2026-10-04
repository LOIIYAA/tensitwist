<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spinner_questions', function (Blueprint $table) {
            $table->string('spinner_label', 50)
                ->nullable()
                ->after('question_text');
        });
    }

    public function down(): void
    {
        Schema::table('spinner_questions', function (Blueprint $table) {
            $table->dropColumn('spinner_label');
        });
    }
};