<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class Diamond
 * @package App\Model
 * @mixin Builder
 */
class Diamond extends Model
{
    protected $table = 'diamonds';



    protected $fillable = [
      'user_id', 'value','type', 'diamond_token', 'diamond_token_confirmed'
    ];

    public function addDiamond($user,$value,$token,$tokenConfirmed = 0){
        return $this->insert(['user_id' => $user, 'value' => $value,'type' => 1, 'diamond_token' => $token, 'diamond_token_confirmed' => $tokenConfirmed, 'created_at' => now(), 'updated_at' => now()]);
    }

    public function reduceDiamond($user,$value,$token){
        return $this->insert(['user_id' => $user, 'value' => $value,'type' => 2, 'diamond_token' => $token, 'diamond_token_confirmed' => 1,'created_at' => now(), 'updated_at' => now()]);
    }


}
