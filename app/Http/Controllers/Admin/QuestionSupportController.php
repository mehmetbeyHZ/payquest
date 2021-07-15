<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Mission;
use App\Model\QuestionSupport;
use Illuminate\Http\Request;

class QuestionSupportController extends Controller
{
    public function question()
    {
        $qs = QuestionSupport::with('user');
        return view('admin.question-support',['questions' => $qs->orderBy('id','DESC')->paginate(100)]);
    }

    public function _delete(Request $request)
    {
        request()->validate(['id' => 'required|numeric']);
        QuestionSupport::find($request->input('id'))->delete();
        return response()->json(['status' => 'ok', 'message' => 'Silindi!', 'id' => $request->input('id')]);
    }

    public function _add(Request $request)
    {
        request()->validate(['mission_value' => 'required','mission_level' => 'required', 'mission_xp' => 'required','id' => 'required']);
        $qs = QuestionSupport::find($request->input('id'));

        if (count(json_decode($qs->question_answers,true)) !== 4):
            return response()->json(['status' => 'fail', 'message' => 'En az 4 şık olması gerekli.'],400);
        endif;

        $mission = new Mission();
        $mission->type = 0;
        $mission->intent_link = null;
        $mission->mission_level = (int)request('mission_level');
        $mission->mission_value = request('mission_value');
        $mission->mission_second = 20;
        $mission->mission_xp = round((int)request('mission_xp'));
        $mission->is_question = 1;
        $mission->mission_question = $qs->question;
        $mission->mission_question_answers = $qs->question_answers;
        $mission->mission_take_limit = 0;
        $mission->correct_index = $qs->correct_index;
        $mission->is_deleted = 0;
        $mission->save();
        $qs->delete();
        return response()->json(['status' => 'ok', 'message' => 'Soru eklendi', 'id' => $request->input('id')]);

    }

    public function edit($id)
    {
        $ms = QuestionSupport::findOrFail($id);
        return view('admin.question-support-edit',['question' => $ms]);
    }
    public function _edit($id, Request $request)
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

        QuestionSupport::find($id)->update([
            'question' => request('mission_question'),
            'correct_index' => $correct,
            'question_answers' => request('answer_list')
        ]);

        return response()->json(['status' => 'ok', 'message' => 'Güncellendi.']);
    }

}
