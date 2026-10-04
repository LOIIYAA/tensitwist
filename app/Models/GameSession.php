<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'spinner_project_id',
        'player_name',
        'total_questions',
        'correct_count',
        'wrong_count',
        'status',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(SpinnerProject::class, 'spinner_project_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(GameAnswer::class)
            ->orderBy('question_order');
    }
}