<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpinnerQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'spinner_project_id',
        'question_text',
        'spinner_label',
        'correct_answer',
        'explanation',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(SpinnerProject::class, 'spinner_project_id');
    }

    public function gameAnswers(): HasMany
    {
        return $this->hasMany(GameAnswer::class);
    }
}