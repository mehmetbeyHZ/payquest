<?php

namespace App\Http\Controllers;

use App\Model\Mission;
use App\Model\MissionHandle;
use App\Model\Payment;
use App\Model\UsersLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Mavinoo\Batch\Batch;

class MissionController extends Controller
{
    public function all(): JsonResponse
    {
        $data = MissionHandle::with('mission_detail')
            ->where('mission_user', Auth::id())
            ->orderBy('mission_handle_id', 'DESC')
            ->whereNotIn('is_completed', [0])
            ->get();
        return response()->json([
            'status' => 'ok',
            'questions' => $data
        ]);
    }

    public function current()
    {
        $data = $this->currentMissions();
        return response()->json([
            'status' => 'ok',
            'questions' => $data
        ]);
    }

    public function currentMissions()
    {
        return MissionHandle::with('mission_detail')
            ->where('mission_user', Auth::id())
            ->orderBy('mission_handle_id', 'DESC')
            ->where('is_completed', 0)
            ->get();
    }

    public function takeNewMissions()
    {

        $cKey = 'clicked_take:'.Auth::id();
        if (!Cache::has($cKey)):
            Cache::put('clicked_take:'.Auth::id(),1,now()->addMinutes(1));
        else:
            return response()->json(['status' => 'fail', 'message' => 'Lütfen bekleyin.'],400);
        endif;

        $handle = new MissionHandle();
        $log = new UserLogController();
        $timeAccess = $log->takeNewTimeAccess();

        if ($handle->hasAwaitingMission(Auth::id())) {
            return response()->json([
                'status' => 'ok',
                'message' => __('app.missions_sent'),
                'questions' => $this->current()->getData(true)['questions']
            ]);
        }

        if ($timeAccess['can_take'] === false) {
            Cache::forget($cKey);
            return response()->json([
                'status' => 'fail',
                'message' => __('app.mission_wait_second'),
                'remains_second' => $timeAccess['remains']
            ], 400);
        }

        $insertData = [];

        if (count($insertData) <= 0) {
            Cache::forget($cKey);
            return response()->json([
                'status' => 'fail',
                'message' => 'Seviyenizdeki bütün soruları aldınız. Lütfen editörlerimizin yeni soru eklemesini bekleyin.'
            ], 400);
        }


        MissionHandle::insert($insertData);
        $currentMissions = $this->currentMissions();

        Cache::forget($cKey);
        return response()->json([
            'status' => 'ok',
            'message' => __('app.missions_sent'),
            'questions' => $currentMissions
        ]);
    }

    public function getMission(Request $request)
    {
        $userLog = (new UserLogController());
        $validator = Validator::make($request->all(), [
            'mission_id' => 'required',
        ]);
        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()]);
        endif;


        $mission=Cache::remember("mission_handle:{$request->input('mission_id')}",now()->addMinutes(15),static function() use($request){
            $ms =  (new MissionHandle())->find($request->input('mission_id'));
            $ms->viewed_at === null ? $ms->viewed_at = now() : null;
            $ms->save();
            return $ms;
        });
       // $mission = (new MissionHandle())->find($request->input('mission_id'));
        if ($mission->viewed_at === null)
        {
            $mission->update(['viewed_at' => now()]);
        }
        //Cache::put('ms_handle_'.$request->input('mission_id'),$mission,now()->addMinutes(15));
        try {
            //$this->authorize('view', $mission);
//            Cache::put('vms_detail_'.$request->input('mission_id'),$detail,now()->addSeconds(160));

           $detail = Cache::remember("v_ms_detail_".$mission->real_mission_id,now()->addHours(48),static function() use($mission){
                return Mission::find($mission->real_mission_id);
            });

            if (!$detail)
            {
                $this->updateMission($mission,3);
                return response()->json(['status' => 'fail','message' => 'Bu soruya ulaşılamıyor.'],400);
            }

            $timeAccess = $userLog->isTimeExpired($mission->viewed_at, $detail->mission_second);
            if ($timeAccess['status']) {
                $this->updateMission($mission, 2);
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Bu soruyu zamanında cevaplayamadınız.'
                ], 400);
            }
//            $userLog->questionViewed(Auth::id(),$request->input('mission_id'));
            return response()->json([
                'status' => 'ok',
                'mission_id' => $mission->mission_handle_id,
                'mission_question' => $detail->mission_question,
                'mission_answers' => json_decode($detail->mission_question_answers, true),
                'type' => $detail->type,
                'intent_link' => $detail->intent_link,
                'scrape_link' => null,
                'remains_answer_sec' => $timeAccess['remains']
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => 'fail', 'message' => 'Bu soruyu görüntüleme yetkiniz yok'], 400);
        }
    }

    public function socialMediaLink($intent)
    {
        if (strpos($intent,"instagram.com"))
        {
            return $intent.'?__a=1';
        }
        return null;
    }

    public function setTimeExpired(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mission_id' => 'required',
        ]);
        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()]);
        endif;

        $mission = MissionHandle::find(request('mission_id'));
        if ($mission->mission_user !== Auth::id())
        {
            return response()->json(['status' => 'fail', 'message' => 'Access Error'],400);
        }
        $this->updateMission($mission,2);
        return response()->json(['status' => 'ok', 'message' => 'Updated'],200);
    }

    public function answerMission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mission_id' => 'required',
            'answer_index' => 'required|numeric'
        ]);
        if (!Cache::has('mission_answered:'.$request->input('mission_id'))):
            Cache::put('mission_answered:'.$request->input('mission_id'),true,now()->addMinutes(5));
        else:
            return response()->json(['status' => 'fail', 'message' => 'Bu soru için birden fazla cevap veremezsin'],400);
        endif;
        $answer_index = (int)$request->input('answer_index');
        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()]);
        endif;
//        $mission = (new MissionHandle())->find($request->input('mission_id'));
        $mission = Cache::get("mission_handle:{$request->input('mission_id')}");
        try {
            $this->authorize('view', $mission);
//            $missionDetail = $mission->mission_detail;

            $missionDetail = Cache::get('v_ms_detail_'.$mission->real_mission_id);


//            $UserAnswerTimeAccess = $userLog->isTimeExpired($mission->mission_handle_id,$missionDetail->mission_second);
//            if ($UserAnswerTimeAccess['status']) {
//                $this->updateMission($mission, 2);
//                return response()->json([
//                    'status' => 'fail',
//                    'message' => 'Bu soruyu zamanında cevaplayamadınız.'
//                ], 400);
//            }



            if ($missionDetail->is_question === 2) {
                if ($answer_index !== 0) {
                    return response()->json(['status' => 'fail', 'message' => 'Bu soruya farklı bir cevap veremezsiniz.'], 400);
                }
                $scKey = "sc_{$mission->mission_handle_id}_".Auth::id();
                $ecKey = "ec_{$mission->mission_handle_id}_".Auth::id();
                $startCount = (int)Cache::get($scKey,-1);
                $endCount   = (int)Cache::get($ecKey,-1);

                if ($startCount !== -1 && $endCount !== -1)
                {
                    $sub = $endCount - $startCount;
                    if ($sub === 1) {
                        $this->updateMission($mission, 1);
                        (new PaymentController())->addPayment(Auth::id(), $missionDetail->mission_value, $mission->mission_handle_id,"add_balance_mission",1);
                        (new XPController())->addXP(Auth::id(), $missionDetail->mission_xp, json_encode(['message' => 'XP From Question ID : ' . $missionDetail->mission_id]));
                        return response()->json(['status' => 'ok', 'message' => 'Özel görevin onaylandı!']);
                    }

                    if ($sub === 0) {
                        $this->updateMission($mission,2);
                        return response()->json(['status' => 'ok', 'message' => 'Özel görev başarısız!']);
                    }
                }

                $this->updateMission($mission, 4);
                return response()->json(['status' => 'ok', 'message' => 'Verdiğin cevabı inceliyoruz.']);
            }

            if ($missionDetail->correct_index === $answer_index) {
                $this->updateMission($mission, 1);
                $token = (new PaymentController())->addPayment(Auth::id(), $missionDetail->mission_value, $mission->mission_handle_id);
                (new XPController())->addXP(Auth::id(), $missionDetail->mission_xp, json_encode(['message' => 'XP From Question ID : ' . $missionDetail->mission_id]));
//                (new UserLogController())->createLog(Auth::id(),"question_answered",['mission' => $request->input('mission_id'), 'answer' => $request->input('answer_index'),'status' => "DOĞRU"]);
                return response()->json(['status' => 'ok', 'message' => 'Doğru Cevap!', 'token' => $token], 200);
            }
            $this->updateMission($mission, 2);
//            (new UserLogController())->createLog(Auth::id(),"question_answered",['mission' => $request->input('mission_id'), 'answer' => $request->input('answer_index'),'status' => "YANLIŞ"]);
            return response()->json(['status' => 'fail', 'message' => 'Yanlış Cevap!'], 400);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => 'fail', 'message' => 'Bu mesajı cevaplama yetkiniz yok'], 400);
        }
    }

    public function updateMission(MissionHandle $missionHandle, $isCompeted)
    {
        MissionHandle::find($missionHandle->mission_handle_id)->update(['is_completed' => $isCompeted]);
        if (Auth::user()->totalActiveMissions() <= 0) {
            Auth::user()->update(['questions_completed_at' => now()]);
        }
    }

    public function checkPayment(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validate->fails()):
            return response()->json(['status' => 'fail', 'message' => $validate->errors()->first()], 400);
        endif;
        $token = $request->input('token');
        $payment = Payment::where('payment_token', '=', $token)
            ->where('user_id', Auth::id())
            ->first();
        if (!$payment) {
            return response()->json(['status' => 'fail', 'message' => 'Böyle bir token kodu yok!'], 400);
        }


        $timeAccess = 0;
        $logUnix = Carbon::createFromFormat('Y-m-d H:i:s', $payment->created_at)->timestamp;
        $remainsInit = ($logUnix - now()->subSeconds($timeAccess)->unix());
        if (!($remainsInit <= 0)) {
            $payment->update(['payment_token_confirmed' => 2,'payment_description' => 'require_wait_ads']);
            return response()->json(['status' => 'fail', 'message' => 'Token Zaman aşımına uğradı.', 'time' => $remainsInit], 400);
        }

        $payment->update(['payment_token_confirmed' => 1]);
        //(new UserLogController())->createLog(Auth::id(),"payment_confirmed",["status" => "ok"]);

        return response()->json(['status' => 'fail', 'message' => 'Bakiye onaylandı.']);

    }

    private function completeStatus()
    {
        return [
            0 => 'Görev Bekliyor',
            1 => 'Görev Tamamlandı',
            2 => 'Görev Başarısız',
            3 => 'Görev Iptal',
            4 => 'Görev Kontrol Ediliyor'
        ];
    }

}
