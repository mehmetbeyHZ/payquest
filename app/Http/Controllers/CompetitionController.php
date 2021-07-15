<?php

namespace App\Http\Controllers;

use App\Model\Competition;
use App\Model\CompetitionRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompetitionController extends Controller
{
    public function competitions()
    {
        $now = now();
        $competition = new Competition();
        $items = $competition->with('isRegistered.user')
            ->withCount('totalRegister')
            ->where('last_register_date','<',$now)
            ->orderBy('competition','DESC')
            ->get();
        return response()->json(['status' => 'ok', 'competitions' => $items]);
    }

    public function registered()
    {
        $registers = new CompetitionRegister();
        $competition = new Competition();
        $ids =  $registers->where('user_id',Auth::id())->pluck('competition_id');
        $items = $competition->with('isRegistered.user')
            ->withCount('totalRegister')
            ->where('last_register_date','<',now())
            ->whereIn('competition',$ids)
            ->orderBy('competition','DESC')
            ->get();
        return response()->json(['status' => 'ok', 'competitions' => $items]);
    }
}
