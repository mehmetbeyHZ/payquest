<?php

namespace App\Http\Controllers;

use App\Classes\SMSVerify;
use App\Model\PhoneVerification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class PhoneVerificationController extends Controller
{

    public function sendSMS()
    {
        return view('auth.sms-send-code');
    }


    public function _sendSMS(Request $request)
    {
        $v = Validator::make($request->input(),[
            'phone' => 'required|regex:/5[0,3,4,5,6][0-9]\d\d\d\d\d\d\d$/'
        ]);
        $phone = ltrim(request('phone'),"0");

        if ($v->fails()):
            return response()->json(['status' => 'fail', 'message' => 'Geçersiz telefon formatı.'],400);
        endif;
        if (Auth::user()->phone_verified_at):
            return response()->json(['status' => 'fail', 'message' => 'Telefon numaranız zaten onaylı!'],400);
        endif;
        $hasAnyUser = (new User())->where('phone_number',$phone)
            ->where('phone_verified_at','!=',null)
            ->first();
        if ($hasAnyUser):
            return response()->json(['status' => 'fail', 'message' => 'Bu telefon numarası başka kullanıcı tarafından onaylanmış.'],400);
        endif;

        if (Cache::has('user_sms_verify_resend:'.Auth::id())):
            return response()->json(['status' => 'fail', 'message' => 'Tekrar kod göndermek için lütfen bekleyin.'],400);
        endif;

        Cache::put('user_sms_verify_resend:'.Auth::id(),1,now()->addMinute());
        $code = random_int(111111,999999);
        $sendVerify = (new SMSVerify())->sendSingle($phone,$code)->getDecodedResponse(true);
        if ((bool)$sendVerify['result'] === false):
            return response()->json(['status' => 'fail', 'message' => 'SMS Gönderirken bir sorun oluştu'],400);
        endif;
        (new PhoneVerification())->insert(['user_id' => Auth::id(),'code' => $code,'expired_at' => now()->addHours(1)]);
        session()->put(['sent_code' => 1,'sms_phone' => $phone]);
        return response()->json(['status' => 'ok', 'message' => 'Onay kodu gönderildi.', 'redirect' => route('user.sms.confirm')]);

    }

    public function verify()
    {
        if (!session('sent_code')): return redirect()->back(); endif;
        return view('auth.sms-verify');
    }

    public function _verify(Request $request)
    {
        if (!session('sent_code')):
            return response()->json(['status' => 'fail', 'message' => 'Lütfen önce kod gönderin.','redirect' => route('user.insight')]);
        endif;
        request()->validate([
           'code' => 'required|numeric'
        ]);
        $hasCode = (new PhoneVerification())->where('code',request('code'))
            ->where('user_id',Auth::id())
            ->first();
        if (!$hasCode):
            return response()->json(['status' => 'fail', 'message' => 'Hatalı onay kodu.'],400);
        endif;
        (User::find(Auth::id()))->update(['phone_verified_at' => now(),'phone_number' => session('sms_phone')]);
        $hasCode->delete();
        session()->forget(['sms_phone','sent_code']);
        return response()->json(['status' => 'ok', 'message' => 'Telefon numaranız onaylandı.', 'redirect' => route('user.insight')]);


    }

}
