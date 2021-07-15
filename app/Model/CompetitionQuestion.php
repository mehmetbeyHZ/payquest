<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompetitionQuestion extends Model
{
    protected $table = 'competition_questions';
    protected $primaryKey = 'question';
    protected $fillable = [
        'question_text', 'question_answers', 'question_time', 'question_view_date', 'correct_index'
    ];
}
