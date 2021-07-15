<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompetitionAnswer extends Model
{
    protected $table = 'competition_answers';
    protected $primaryKey = 'answer';
    protected $fillable = [
      'question_id', 'user_id', 'answer_index', 'is_correct'
    ];
}
