<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Model\MissionHandle;
use App\Model\QuestionSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInsightController extends Controller
{
    public function index()
    {
        $userXP = auth()->user()->totalXP();
        $data['items'] = [
            'Seviye' => xpToLevel($userXP)['level'],
            'Gerekli Referans' => auth()->user()->totalRef() . '/5',
            'Aylık minimum ödeme' => auth()->user()->balance() . '₺/200₺',
            'Sonraki seviye' => $userXP.'XP/'. (xpToLevel($userXP)['xp_need'] + $userXP) . 'XP',
            'Toplam alınan soru' => auth()->user()->totalQuestions()
        ];

        $data['missions'] = MissionHandle::with('mission_detail')
            ->where('mission_user', Auth::id())
            ->orderBy('mission_handle_id', 'DESC')
            ->where('is_completed', 0)
            ->get();

        return view('user.insight',$data);
    }

    public function addQuestion(Request $request)
    {
        request()->validate([
            'mission_question' => 'required',
            'answer_list' => 'required'
        ]);

        $myData = json_decode(request('answer_list'), true);
        if (!is_array($myData) || count($myData) !== 4) {
            return response()->json(['status' => 'fail', 'message' => '4 cevap eklemeniz gerekli'], 400);
        }

        $correct = array_search(request('answer'), $myData, true);
        if ($correct === false):
            return response()->json(['status' => 'fail', 'message' => '1 Doğru cevap seçin.'], 400);
        endif;

        QuestionSupport::insert([
            'question' => request('mission_question'),
            'user_id' => Auth::id(),
            'question_answers' => request('answer_list'),
            'correct_index' => $correct,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['status' => 'ok', 'message' => 'Soru yönetici onayına gönderildi.']);
    }
}
