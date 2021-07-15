<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class FcmToken
 * @package App\Model
 * @mixin Builder
 */
class FcmToken extends Model
{
    protected $table = 'fcm_token';

    protected $primaryKey = 'register_id';

    protected $fillable = ['user_id','fcm_token'];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

}
