<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_session_id',
        'spinner_question_id',
        'question_snapshot',
        'selected_answer',
        'correct_answer_snapshot',
        'is_correct',
        'question_order',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'answered_at' => 'datetime',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(GameSession::class, 'game_session_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(SpinnerQuestion::class, 'spinner_question_id');
    }
}