<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Class PaymentRequest
 * @package App\Model
 * @property $user_id
 * @property $iban
 * @property $bank_id
 * @property $is_confirmed
 * @property $quantity
 * @mixin Builder
 */
class PaymentRequest extends Model
{
    protected $table = 'payment_request';
    protected $primaryKey = 'request_id';
    public function bank()
    {
        return $this->hasOne(Bank::class,'bank_id','bank_id');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    protected function serializeDate(\DateTimeInterface $date) {
        return Carbon::parse($date->getTimestamp())->format('d.m.Y H:i:s');
    }

}
