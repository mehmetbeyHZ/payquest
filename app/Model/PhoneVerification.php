<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class PhoneVerification
 * @package App\Model
 * @mixin Builder
 */
class PhoneVerification extends Model
{
    protected $table = 'phone_verifications';

    protected $fillable = [
      'user_id', 'code','expired_at'
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }


}
