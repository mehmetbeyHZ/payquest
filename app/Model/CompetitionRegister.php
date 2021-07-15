<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class CompetitionRegister
 * @package App\Model
 * @mixin Builder
 */
class CompetitionRegister extends Model
{
    protected $table = 'competition_registers';
    protected $primaryKey = 'register_id';
    protected $fillable = ['user_id','competition_id'];


    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function competition()
    {
        return $this->hasOne(Competition::class,'competition','competition_id');
    }
}
