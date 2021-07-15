<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PayCoin extends Model
{
    public $table = 'paycoin';
    public $fillable = [
      'user_id', 'coin_value', 'payment_type', 'payment_description'
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

}
