<?php

namespace App\Http\Controllers;

use App\Model\UsersLog;
use App\Model\UsersLogMongo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLogController extends Controller
{
    /**
     * @param $authId
     * @param $logName
     * @param array $logData
     * @param int $mission_handle_id
     * @return bool
     */
    public function createLog($authId,$logName,$logData = [],$mission_handle_id = 0)
    {
        $data = [
            'log_user_id' => $authId,
            'log_name' => $logName,
            'log_mission_handle_id' => $mission_handle_id,
            'log_text' =>  json_encode($logData,JSON_UNESCAPED_UNICODE)
        ];
        return UsersLog::create($data);
    }

    public function allQuestionsCompleted()
    {
        return $this->createLog(Auth::id(),'all_questions_completed',['status' => 'ok']);
    }

    public function newQuestionsReceived()
    {
        return $this->createLog(Auth::id(),'new_questions_received',['status' => 'ok']);
    }

    public function lastCompleted(): int
    {
        $log = Auth::user()->questions_completed_at;

        $created_at = $log ?? now()->subDays(50);

        return Carbon::createFromFormat('Y-m-d H:i:s',$created_at)->unix();

    }

    public function takeNewTimeAccess() : array
    {
        $timeAccess    = 900;
        $lastCompleted = $this->lastCompleted();
        $now           = now()->unix();
        return [
            'can_take' =>  ($now - $lastCompleted) > $timeAccess,
            'remains'  => $timeAccess - ($now - $lastCompleted)
        ];
    }

    public function isTimeExpired($viewed_at,$missionSecond) : array
    {
        $timeAccess = $missionSecond;
//        $log = new UsersLog();
//        $log = $log->where('log_mission_handle_id',$missionId)
//            ->where('log_name','question_viewed')
//            ->orderBy('created_at','DESC')
//            ->first();
        if (!$viewed_at){
            return [
                'has_any_input' => false,
                'status'        => false,
                'remains'       => $timeAccess
            ];
        }

        $logUnix = Carbon::createFromFormat('Y-m-d H:i:s',$viewed_at)->timestamp;
        $remainsInit = ($logUnix - now()->subSeconds($timeAccess)->unix());

         return [
            'has_any_input' => true,
            'status' => $viewed_at < now()->subSeconds($timeAccess),
            'remains' => $remainsInit < 0 ? 0 : $remainsInit
        ];
    }

    public function questionViewed($id,$questionId)
    {
        return $this->createLog($id,'question_viewed',['message' => $questionId.' viewed.'],$questionId);
    }





}
