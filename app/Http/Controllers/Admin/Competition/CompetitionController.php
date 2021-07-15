<?php

namespace App\Http\Controllers\Admin\Competition;

use App\Http\Controllers\Controller;
use App\Model\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CompetitionController extends Controller
{

    public function competitions(Request $request)
    {
        $competition = new Competition();

        return view('admin.competition.competitions', ['competitions' => $competition->orderBy('competition', 'DESC')->paginate(50)]);
    }

    public function add()
    {
        return view('admin.competition.add-competition');
    }

    public function _add(Request $request,$updateId = 0)
    {
        request()->validate([
            'competition_title' => 'required',
            'competition_description' => 'required',
            'registration_fee' => 'required|numeric',
            'award' => 'required|numeric',
            'can_register' => 'required|numeric',
            'total_winner' => 'required',
            'max_users' => 'required',
            'start_date' => 'required',
            'start_time' => 'required',
            'last_register_date' => 'required',
            'last_register_time' => 'required'
        ]);

        $startDate = explode('-', $request->input('start_date'));
        $startTime = explode(':', $request->input('start_time'));
        $startDateFormatted = Carbon::createSafe((int)$startDate[0], (int)$startDate[1], (int)$startDate[2], (int)$startTime[0], (int)$startTime[1], 0);

        $lastRegisterDate = explode('-', $request->input('last_register_date'));
        $lastRegisterTime = explode(':', $request->input('last_register_time'));
        $lastRegisterDateFormatted = Carbon::createSafe((int)$lastRegisterDate[0], (int)$lastRegisterDate[1], (int)$lastRegisterDate[2], (int)$lastRegisterTime[0], (int)$lastRegisterTime[1], 0);

        $data = [
            'competition_title' => $request->input('competition_title'),
            'competition_description' => $request->input('competition_description'),
            'competition_image' => $request->input('competition_image'),
            'registration_fee' => $request->input('registration_fee'),
            'award' => $request->input('award'),
            'can_register' => $request->input('can_register'),
            'start_date' => $startDateFormatted,
            'last_register_date' => $lastRegisterDateFormatted,
            'total_winner' => $request->input('total_winner'),
            'max_users' => $request->input('max_users'),
        ];

        if ($updateId !== 0):
            Competition::find($updateId)->update($data);
            $msg = 'güncellendi';
        else:
            Competition::create($data);
            $msg = 'eklendi';
        endif;

        return response()->json(['status' => 'ok', 'message' => 'Yarışma '.$msg]);
    }

    public function update(Request $request, $id)
    {
        $competition = Competition::findOrFail($id);
        return view('admin.competition.edit-competition',['competition' => $competition->where('competition',$id)->with('registers.user')->with('questions')->first()]);
    }

    public function _update(Request $request, $id)
    {
        return $this->_add($request,$id);
    }

    public function _delete(Request $request)
    {
        request()->validate(['id' => 'required|numeric']);
        Competition::find($request->input('id'))->delete();
        return response()->json(['status' => 'ok', 'message' => 'Yarışma silindi.','competition' => $request->input('id')]);
    }

}
