<?php

namespace App\Http\Controllers\Admin;

use App\Classes\FirebaseCloudMessaging;
use App\Classes\YoutubeClient;
use App\Http\Controllers\Controller;
use App\Model\Mission;
use App\Model\MissionHandle;
use App\Model\Payment;
use App\Model\XP;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class MissionController extends Controller
{
    public function missions(Request $request)
    {
        $mission = new Mission();
        if (in_array(request('type'),['mission_question','mission_question_answers','intent_link'])) {
            $mission = $mission->where(request('type'), "LIKE", "%" . request('q') . "%");
        }
        if (request('type') === "mission_level")
        {
            $mission = $mission->where('mission_level',request('q'));
        }
        if (in_array((int)request('is_question'), [1, 2], true))
        {
            $mission = $mission->where('is_question',(int)request('is_question'));
        }
        if (in_array(request('is_deleted'), ["0","1"], true))
        {
            $mission = $mission->where('is_deleted',(int)request('is_deleted'));
        }
        $total = $mission->count();
        return view('admin.missions', ['missions' => $mission->orderBy('mission_id', 'desc')->paginate(100),'total' => $total]);
    }

    public function addMission()
    {
        return view('admin.add-mission');
    }

    public function hardDelete(Request $request)
    {
        request()->validate([
           'mission_id' => 'required'
        ]);
        Mission::find(request('mission_id'))->delete();
        return response()->json([
           'status' => 'ok',
           'message' => 'Soru tamamen silindi.'
        ]);
    }

    public function takenMissions(Request $request)
    {

        $wp = false;
        $mission = MissionHandle::with('mission_detail')
            ->with('user')
            ->limit(200)
            ->orderBy('mission_handle_id', 'DESC');


        if (request('type') === "name"):
            $wp = true;
            $user = new User();
            $user = $user->where('name','LIKE',"%".request('q')."%")->pluck('id');
            $mission = $mission->whereIn('mission_user',$user);
        endif;

        if (request('type') === "email"):
            $wp = true;
            $user = new User();
            $user = $user->where('email',request('q'))->pluck('id');
            $mission = $mission->whereIn('mission_user',$user);
        endif;

        if ($wp === true):
            $mission = $mission->paginate(100);
        else:
            $mission = $mission->get();
        endif;



        return view('admin.taken-missions', ['missions' => $mission, 'with_paginate' => $wp]);
    }

    public function checkMissions()
    {
        $missions = MissionHandle::with('mission_detail')->with('user')
            ->where('is_completed', 4)
            ->orderBy('mission_handle_id', 'DESC');

        if (request('q') && request('type') && in_array(request('type'),["name","email","ref_code"]))
        {
            $user = new User();
            $user = $user->where(request('type'),'LIKE',"%".request('q')."%")->pluck('id');
            $missions = $missions->whereIn('mission_user',$user);
        }

        return view('admin.missions-check', ['missions' => $missions->paginate(50)]);
    }

    public function doCheckMissions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required',
            'type' => 'required|numeric'
        ]);
        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()], 400);
        endif;

        $ids = is_array(request('ids')) ? request('ids') : [request('ids')];

        if (!is_array($ids) || !in_array((int)request('type'), [1, 2], true)):
            return response()->json(['status' => 'fail', 'message' => 'Geçersiz format'], 400);
        endif;

        $type     = (int)request('type');
        $missions = MissionHandle::with('mission_detail')->whereIn('mission_handle_id', $ids);
        $add      = [];
        $addXP    = [];
        $notificationUsers = [];
        if ($type === 1)
        {
            $date = now();
            foreach ($missions->get() as $mission) {
                if ($mission->mission_detail):
                    $notificationUsers[] = $mission->mission_user;
                    $add[] = [
                        'user_id' => $mission->mission_user,
                        'mission_id' => $mission->real_mission_id,
                        'payment_value' => $mission->mission_detail->mission_value,
                        'payment_type' => 1,
                        'payment_token' => md5(time()),
                        'payment_token_confirmed' => 1,
                        'payment_description' => json_encode(['message' => "manual check mission {$mission->mission_handle_id}"],JSON_UNESCAPED_UNICODE),
                        'created_at' => $date,
                        'updated_at' => $date
                    ];
                    $addXP[] = ['value' => $mission->mission_detail->mission_xp,'user_id' => $mission->mission_user,'description' => json_encode(['message' => 'XP From Question ID '. $mission->mission_handle_id],JSON_UNESCAPED_UNICODE)];
                endif;
            }
            XP::insert($addXP);
            Payment::insert($add);
        }

        $missions->update([
            'is_completed' => $type === 1 ? 1 : 3
        ]);

        if(count($notificationUsers) > 0):
            $fcmTokens = User::with('fcmToken')->whereIn('id',$notificationUsers)->get()->toArray();
            $registerTokens = array_map(function ($item){
                return $item['fcm_token']['fcm_token'] ?? null;
            },$fcmTokens);
            (new FirebaseCloudMessaging())->sendMultiple('Görev Onayı','Özel göreviniz onaylanıp ödülünüz tanımlanmıştır.',$registerTokens);
        endif;

        return response()->json(['status' => 'ok','message' => 'Güncellendi']);

    }

    public function doAddMission(Request $request, $update = 0)
    {
        $validator = Validator::make($request->all(), [
            'mission_question' => 'required',
            'mission_level' => 'required|numeric',
            'mission_value' => 'required|numeric',
            'mission_second' => 'required|numeric',
            'mission_xp' => 'required|numeric',
        ]);

        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()], 400);
        endif;


        if (request('is_custom') === "on"):
            $validator = Validator::make($request->all(), [
                'intent_link' => 'required',
                'mission_take_limit' => 'required|numeric',
                'type' => 'required'
            ]);
            if ($validator->fails()):
                return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()], 400);
            endif;
        else:
            $myData = json_decode(request('answer_list'), true);
            if (!is_array($myData) || count($myData) <= 0) {
                return response()->json(['status' => 'fail', 'message' => 'en az 1 cevap eklemeniz gerekli'], 400);
            }

            $correct = array_search(trim(request('answer')), $myData, true);
            if ($correct === false):
                return response()->json(['status' => 'fail', 'message' => '1 Doğru cevap seçin.'], 400);
            endif;
        endif;

        if (request('is_custom') === "on" && (int)request('type') === 0):
            return response()->json(['status' => 'fail', 'message' => 'Özel görevler soru olarak seçilemez.'],200);
        endif;

        $mission = ($update === 0) ? new Mission() : Mission::find($update);
        if ($update !== 0):
            Cache::forget('v_ms_detail_'.$update);
        endif;
        $mission->type = request('type');
        $mission->intent_link = request('intent_link');
        $mission->mission_level = (int)request('mission_level');
        $mission->mission_value = request('mission_value');
        $mission->mission_second = round((int)request('mission_second'));
        $mission->mission_xp = round((int)request('mission_xp'));
        $mission->is_question = request('is_custom') === "on" ? 2 : 1;
        $mission->mission_question = request('mission_question');
        $mission->mission_question_answers = request('answer_list');
        $mission->mission_take_limit = (request('mission_take_limit')) === null ? 0 : request('mission_take_limit');
        $mission->correct_index = $correct ?? 0;
        $mission->partial_sending = (int)request('partial_sending',1);
        $mission->is_deleted = (request('is_deleted')) ?: 0;
        $mission->save();
        return response()->json(['status' => 'ok', 'message' => 'Soru başarıyla ' . (($update === 0) ? 'eklendi' : 'güncellendi')]);
    }

    public function editMission($id)
    {
        $mission = Mission::findOrFail($id);
        $taken = Cache::get('pm_taken:'.$id);
        $youtubeSub = null;
//        if($mission->is_question === 2 && $mission->type === 1):
//            $youtubeSub = (new YoutubeClient())->userInfo($mission->intent_link);
//        endif;
        return view('admin.edit-mission', ['mission' => $mission,'taken_count' => $taken, 'youtube' => $youtubeSub]);
    }




    public function doEditMission($id, Request $request)
    {
        return $this->doAddMission($request, $id);
    }

    public function deleteMission()
    {

    }

    public function updateMission()
    {

    }

}
