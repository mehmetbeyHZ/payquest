<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class Mission
 * @package App\Model
 * @property $mission_level
 * @property $mission_value
 * @property int $mission_second
 * @property int $mission_xp
 * @property int $is_question
 * @property int $mission_question
 * @property string $mission_question_answers
 * @property int $correct_index
 * @property int $is_deleted
 * @property int $type
 * @property int $intent_link
 *               0 : Question Default
 *               1 : youtube subs
 *               2 : youtube views
 *               3 : youtube likes
 *               4 : Google Play App Download
 * @mixin Builder
 */
class Mission extends Model
{
    protected $table = 'mission';
    protected $primaryKey = 'mission_id';

    protected $hidden = [
        'correct_index', 'created_at', 'updated_at', 'is_deleted', 'mission_question', 'mission_question', 'mission_question_answers'
    ];

    protected $fillable = [
        'mission_level', 'mission_value','mission_second','mission_xp','mission_take_limit','is_question','mission_question','type','intent_link','mission_question_answers','correct_index','partial_sending','is_deleted'
    ];

    public function real_mission()
    {
        return $this->hasMany(MissionHandle::class, 'real_mission_id', 'mission_id');
    }


}
