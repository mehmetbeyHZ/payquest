<?php

namespace App\Http\Controllers\Competition;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function registerCompetition(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'competition' => 'required',
        ]);
        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => 'Hatalı yarışma ID']);
        endif;

    }
}
