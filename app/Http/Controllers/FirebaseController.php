<?php

namespace App\Http\Controllers;

use App\Model\FcmToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FirebaseController extends Controller
{
    public function registerFCMToken(Request $request)
    {
        $validator = Validator::make($request->all(),[
           'token' => 'required'
        ]);

        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()],400);
        endif;
        $token = $request->input('token');
        $user  = Auth::id();
        FcmToken::updateOrCreate(['user_id' => $user],[
            'fcm_token' => $token
        ]);
        return response()->json(['status' => 'ok', 'message' => 'Token kaydedildi.']);
    }
}
