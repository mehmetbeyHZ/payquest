<?php

namespace App\Http\Controllers\Admin;

use App\Classes\FirebaseCloudMessaging;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller
{
    public function notification()
    {
        return view('admin.notification');
    }

    public function pushNotification(Request $request)
    {
        $validate = Validator::make($request->all(),[
           'title' => 'required',
           'body' => 'required'
        ]);
        if ($validate->fails()):
            return response()->json(['status' => 'fail', 'message' => $validate->errors()->first()],400);
        endif;

        $httpRequest = (new FirebaseCloudMessaging())->sendTopicsAll(request('title'),request('body'));

        return response()->json(['status' => 'ok','message' => 'Veri işlendi','data' => $httpRequest],200);

    }
}
