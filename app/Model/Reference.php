<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Class Reference
 * @package App\Model
 * @property $from
 * @property $registered_id
 * @mixin Builder
 */
class Reference extends Model
{
    protected $table = 'reference';

    protected $primaryKey = 'reference_id';

    protected $hidden = ['updated_at'];

    public function user_info()
    {
        return $this->hasOne(User::class,'id','registered_id');
    }

    public function from_user()
    {
        return $this->hasOne(User::class,'id','from');
    }

    protected function serializeDate(\DateTimeInterface $date) {
        return Carbon::parse($date->getTimestamp())->format('d.m.Y H:i:s');
    }

}
