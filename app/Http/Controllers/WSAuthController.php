<?php

namespace App\Http\Controllers;

use App\Jobs\CompetitionBrain;
use App\Model\Competition;
use App\Model\CompetitionRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Pusher\PusherException;

class WSAuthController extends Controller
{
    public function authRouter(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'channel_name' => 'required',
            'socket_id' => 'required'
        ]);
        if ($validator->fails()):
            return response()->json(['status' => 'fail','message' => $validator->errors()->first()],400);
        endif;
        if (strpos(request('channel_name'),'competition-manager') !== false)
        {
            return $this->authCompetition($request);
        }
        return response()->json(['status' => 'fail'],400);
    }

    public function authCompetition(Request $request)
    {
        $competitionId = explode('.',$request->input('channel_name'));
        if (!isset($competitionId[1]) || !is_numeric($competitionId[1])):
            return response()->json(['status' => 'fail', 'message' => 'Channel not found'],400);
        endif;
        $competitionId = $competitionId[1];
        $competition = Competition::find($competitionId);
        if (!$competition):
            return response()->json(['status' => 'fail', 'message' => 'Channel not found'],400);
        endif;
        $competition = Competition::with('registers')->find($competitionId);
        $isRegistered = CompetitionRegister::where('user_id',Auth::id())->where('competition_id',$competitionId)->first();
        if (!$isRegistered):
            return response()->json(['status' => 'fail', 'message' => 'Bu yarışmaya kaydınız yok!'],400);
        endif;

//        $now = now();
//        if ($competition->start_date > $now):
//            return response()->json(['status' => 'fail','message' => 'Henüz katılamazsınız.'],400);
//        endif;
//        if ($competition->start_date <= $now->subSeconds(30)):
//            return response()->json(['status' => 'fail', 'message' => 'Katılma zaman aşımına uğradı.'],400);
//        endif;

        dispatch((new CompetitionBrain($competitionId,'Yarış başladı katılım kapandı'))->delay(10));


        try {
            return pusher()->presence_auth($request->input('channel_name'),$request->input('socket_id'),Auth::id());
        } catch (PusherException $e) {
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()],400);
        }
    }
}
