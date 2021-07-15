<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Class Competition
 * @package App\Model
 * @mixin Builder
 */
class Competition extends Model
{
    protected $table = 'competitions';
    protected $primaryKey = 'competition';
    protected $fillable = [
        'competition_title', 'competition_description', 'competition_image',
        'registration_fee', 'award', 'can_register', 'start_date', 'last_register_date', 'total_winner',
        'max_users'
    ];

    public function registers()
    {
        return $this->hasMany(CompetitionRegister::class,'competition_id');
    }

    public function isRegistered()
    {
        return $this->hasOne(CompetitionRegister::class,'competition_id')->where('user_id',Auth::id());
    }

    public function totalRegister()
    {
        return $this->hasMany(CompetitionRegister::class,'competition_id');
    }

    public function questions()
    {
        return $this->hasMany(CompetitionQuestion::class,'competition_id');
    }

}
