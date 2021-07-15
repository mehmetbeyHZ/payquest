<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class Payment
 * @package App\Model
 * @mixin Builder
 */
class Payment extends Model
{
    protected $table = 'payment';

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'user_id', 'mission_id', 'payment_value', 'payment_type', 'payment_token',
        'payment_token_confirmed', 'payment_description'
    ];

    public function mission()
    {
        return $this->hasOne(MissionHandle::class,'mission_handle_id','mission_id');
    }



}
