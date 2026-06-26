<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'options',
        'correct_option',
        'is_code_exercise',
        'code_starter',
        'code_test_cases'
    ];

    protected $casts = [
        'options' => 'array',
        'code_test_cases' => 'array',
        'is_code_exercise' => 'boolean'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
