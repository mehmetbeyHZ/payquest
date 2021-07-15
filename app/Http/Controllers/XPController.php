<?php

namespace App\Http\Controllers;

use App\Model\XP;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class XPController extends Controller
{
    public function addXP($user,$value,$description = 'add_xp')
    {
        $xp = new XP();
        $xp->user_id = $user;
        $xp->value = (int)$value;
        $xp->description = $description;
        $this->incrementRedisXP($user,$value);
        $xp->save();
    }

    public function getUserXp($userId)
    {
        if (Cache::has('user_total_xp_redis:'.$userId)):
            return Cache::get('user_total_xp_redis:'.$userId);
        endif;
        $totalXP = User::find($userId)->totalXP();
        $this->updateRedisXP($userId,$totalXP);
        return $totalXP;
    }

    protected function updateRedisXP($userId,$totalXP)
    {
        Cache::put("user_total_xp_redis:{$userId}",$totalXP,now()->addDays(1));
    }

    public function incrementRedisXP($userId,$incrementValue)
    {
        $currentXP = $this->getUserXp($userId);
        $this->updateRedisXP($userId,($currentXP + $incrementValue));
    }

}
