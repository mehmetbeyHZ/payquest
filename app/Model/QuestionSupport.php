<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class QuestionSupport
 * @package App\Model
 * @mixin Builder
 */
class QuestionSupport extends Model
{
    protected $table = 'question_support';
    protected $fillable = [
      'user_id', 'question','question_answers', 'correct_index','created_at','updated_at'
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

}
