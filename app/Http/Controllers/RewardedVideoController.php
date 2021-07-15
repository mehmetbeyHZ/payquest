<?php

namespace App\Http\Controllers;

use App\Model\Diamond;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class RewardedVideoController extends Controller
{

    public const NEED_DIAMOND = 50;
    public function diamondDetails()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'Bekleme sürenizi sıfırlamak istiyor musunuz? 50 Diamond ile sıfırlayın.'
        ]);
    }

    public function skipTheSeconds(){
        $user = User::find(Auth::id());
        $totalDiamond = $user->totalDiamond();
        if ($totalDiamond < self::NEED_DIAMOND)
        {
            return response()->json(['status' => 'fail', 'message' => 'Yeterli elmasınız yok!'],400);
        }
        $user->update(['questions_completed_at' => now()->subDays(1)]);
        $diamond = new Diamond();
        $diamond->reduceDiamond(Auth::id(),self::NEED_DIAMOND,md5(time()));
        return response()->json(['status' => 'ok', 'message' => 'Bekleme süresi atlandı, yeni sorular alabilirsiniz!']);
    }

    public function createAdDiamond(Request $request)
    {
        $total_viewed = Cache::get('rewarded_ad_viewed:'.Auth::id(),0);
        $user = Auth::user();
        $unix = Carbon::parse($user->questions_completed_at,'Europe/Istanbul')->unix();
        $diff = now()->unix() - $unix;
        if ($diff > 900)
        {
            return response()->json(['status' => 'fail', 'message' => 'şuan daha fazla reklam izleyemezsiniz.'],400);
        }
        if ((int)$total_viewed > 4)
        {
            return response()->json(['status' => 'fail', 'message' => 'şuan daha fazla reklam izleyemezsiniz.'],400);
        }
        $token = md5(now()."_".microtime()."_".$user->id.'_'.microtime());
        $d = new Diamond();
        $d->addDiamond($user->id,1,$token,0);
        Cache::put('rewarded_ad_viewed:'.Auth::id(),$total_viewed + 1,now()->addMinutes(5));
        return response()->json(['status' => 'ok', 'message' => $token]);
    }

    public function checkAdDiamond(Request $request)
    {
        request()->validate(['token' => 'required']);
        $diamond = new Diamond();
        $item = $diamond->where('user_id',Auth::id())->where('diamond_token',$request->input('token'))->first();
        if (!$item){
            return response()->json(['status' => 'fail', 'message' => 'Böyle bir erişim jetonu yok!'],400);
        }
        $item->update(['diamond_token_confirmed' => 1]);
        return response()->json(['status' => 'ok', 'message' => '1 Diamond Kazandınız!']);
    }



}
