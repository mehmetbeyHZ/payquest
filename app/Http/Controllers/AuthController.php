<?php

namespace App\Http\Controllers;

use App\Model\FcmToken;
use App\Model\PasswordReset;
use App\Model\Reference;
use App\Model\Uploads;
use App\Model\UsersLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function authWithBearer($token,Request $request)
    {
        try{
            if (!$token){ redirect()->route('user.insight'); }
            $token = Cache::get($token);
            if (!$token):
                return redirect()->route('user.insight');
            endif;

            $data = (new \MClient\Request('https://payquestion.com/api/user/info'))
                ->addHeader('Authorization',"Bearer {$token}")
                ->addHeader('Accept','application/json')
                ->execute();

            if($data->getHeaderLine('http_code') !== "HTTP/1.1 200 OK"):
                return redirect()->route('user.insight');
            endif;

            $decrypt = $data->getDecodedResponse(true);
            $user  = (User::find($decrypt['id']));
            if (!$user->phone_verified_at):
                Auth::loginUsingId($decrypt['id'],1);
            endif;
            return redirect()->route('user.insight');

        }catch (\Exception $e)
        {
            return redirect()->route('user.insight');
        }
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password'),'is_banned' => 0])) {
            $user = Auth::user();
            if ($user) {

//                $fcm = new FcmToken();
//                $fcm->updateOrInsert(['user_id' => Auth::id()],[
//                   'fcm_token' => $request->header('fcm-token')
//                ]);


                $success['token'] = $user->createToken('PayQuest')->accessToken;
                $success['user_id'] = $user->id;
                $success['email'] = $user->email;
                $success['name'] = $user->name;
                $success['balance'] = $user->balance();
                $success['xp'] = $user->totalXP();
                return response()->json(['status' => 'ok', 'message' => 'Giriş başarılı', 'user' => $success], 200);
            }
            return response()->json(['status' => 'fail', 'message' => __('request.request_error')]);
        }

        return response()->json(['status' => 'fail', 'message' => __('auth.incorrect_info')], 401);
    }

    private function logoutOther()
    {
        $token = Auth::user()->token();
        DB::table('oauth_access_tokens')
            ->whereNotIn('id', [$token->id])
            ->where('user_id', Auth::id())
            ->delete();

    }

    public function changeAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);

        return response()->json(['status' => 'fail', 'message' => 'Profil fotoğrafı ekleme geçici olarak devredışıdır.'],400);

        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()], 400);
        endif;

        $image  = $request->file('image');
        $store  = 'storage/'.$image->store('avatar');
        $authId = Auth::id();
        if ($store) {
            $avatar = "https://payquestion.com/" . $store;
            User::find($authId)->update(['avatar' => $avatar]);
            (new UserLogController())->createLog($authId,'avatar_changed',['status' => 'ok','message' => 'avatar changed']);
            Uploads::create(['path' => $store, 'user_id' => Auth::id(), 'size' => $image->getSize(), 'type' => $image->getMimeType()]);
            return response()->json(['status' => 'ok', 'avatar' => $avatar]);
        }
        return response()->json(['status' => 'ok', 'message' => 'Bir sorun oluştu.'], 400);

    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()], 400);
        endif;

        if (Hash::check(request('old_password'), Auth::user()->password) === false) {
            return response()->json(['status' => 'fail', 'message' => 'Şuanki şifreniz hatalı.'], 400);
        }

        if ((Hash::check(request('new_password'), Auth::user()->password)) === true) {
            return response()->json(['status' => 'fail', 'message' => 'Yeni şifreniz mevcut şifreden farklı olmalıdır.'], 400);
        }

        $user = User::find(Auth::id());
        $user->password = Hash::make(request('new_password'));
        $user->save();

        if ($request->input('logout_other') === "logout") {
            $this->logoutOther();
        }

        return response()->json(['status' => 'ok', 'message' => 'Şifreniz güncellendi.']);

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'gender' => 'required|numeric',
            'repeat_password' => 'required|same:password',
            'phone_number' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()], 400);
        }

//        $ip = request()->ip();
//        $cache = Cache::has('ip_blocking') ? Cache::get('ip_blocking') : [];
//        $pool = array_unique(array_merge($cache,[$ip]));
//        if (in_array($ip, $cache, true))
//        {
//            return response()->json(['status' => 'fail','message' => 'Daha fazla hesap oluşturamazsınız.'],400);
//        }

        if (!$request->hasHeader('fcm-token') && $request->header('fcm-token') !== null):
            return response()->json(['status' => 'fail', 'message' => 'Lütfen uygulamayı kapatıp yeniden açın! Güncel değilse güncelleyin.'], 400);
        endif;

        $fcm = new FcmToken();
        $myToken = $fcm->where('fcm_token',$request->header('fcm-token'));
        if ($myToken->count('fcm_token') >= 1):
            return response()->json(['status' => 'fail', 'message' => 'Aynı cihaz üzerinden birden fazla hesap oluşturamazsınız.'],400);
        endif;

        if (Cache::has('user_registered:'.$request->ip())):
            return response()->json(['status' => 'fail', 'message' => 'Birden fazla hesap oluşturamazsınız.'],400);
        endif;

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['gender'] = request('gender');
        $input['ref_code'] = (new ReferenceController())->generateRefKey();
        $input['avatar'] = 'https://payquestion.com/storage/avatar/avatar_blank.png';
        $input['ip'] = request()->ip();
        $user = User::create($input);
        if ($user->save()) {
            Cache::put('user_registered:'.$request->ip(),now(),now()->addHours(1));
            $user->token = $user->createToken('PayQuest')->accessToken;
            $user->xp = $user->totalXP();

            $fcm = new FcmToken();
            $fcm->user_id = $user->id;
            $fcm->fcm_token = $request->header('fcm-token');
            $fcm->save();

            if ($request->input('reference_code')):
                $code = (new User())->where('ref_code',request('reference_code'))->first();
                if($code):
                    $userRefs = new Reference();
                    $himRefs = $userRefs->where('from',$code->id)->count('from');
                    if ($himRefs < 100):
                        (new ReferenceController())->addReference($user->id,$code->id);
                        (new PaymentController())->addPayment($code->id,0.25,0,"add_balance_reference",1);
                    endif;
                endif;
            endif;

            return response()->json(['status' => 'ok', 'message' => 'Kayıt başarılı.', 'user' => $user], 200);
        }

        return response()->json(['status' => 'ok', 'message' => __('auth.request_error')], 400);
    }


    public function sendVerifyEmail(User $user)
    {
        $pr = new PasswordReset();
        $pr->user_id = $user->id;
        $resetKey = md5(Str::random(20)."_".$user->id);
        $pr->reset_key = $resetKey;
        $pr->last_activation = now()->addHours(1);
        $pr->save();

        Mail::send('emails.confirmation',['user' => $user,'key' => $resetKey], function ($m) use ($user){
            $m->from('support@payquestion.com','Payquestion');
            $m->to($user->email, $user->name)->subject('Hesabınızı Onaylayın!');
        });
    }

    public function forgotPassword(Request $request)
    {

        if (Cache::has('mail_send_forgot:'.request('email')))
        {
            return response()->json(['status' => 'fail', 'message' => '60 saniyede 1 şifremi unuttum bağlantısı gönderebilirsiniz']);
        }

        $user = new User();
        $user = $user->where('email',request('email'))->first();
        if ($user && $user->is_banned === 0):
            $pr = new PasswordReset();
            $pr->user_id = $user->id;
            $resetKey = md5(Str::random(20)."_".$user->id);
            $pr->reset_key = $resetKey;
            $pr->last_activation = now()->addHours(12);
            $pr->save();

            try{
                Mail::send('emails.forgot-password',['user' => $user,'reset_key' => $resetKey],function($m) use($user){
                    $m->from('support@payquestion.com','Payquestion');
                    $m->to($user->email, $user->name)->subject('Payquestion Şifrenizi Sıfırlayın!');
                });
                Cache::put('mail_send_forgot:'.request('email'),1,now()->addMinutes(1));

            }catch (\Exception $e)
            {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Mail gönderirken bir sorun oluştu!'
                ],400);
            }

            return response()->json([
                'status' => 'ok',
                'message' => 'Mail adresinize sıfırlama bağlantısı gönderildi.'
            ]);
        else:
            return response()->json([
                'status' => 'fail',
                'message' => 'Kullanıcı bulunamadı!'
            ],400);
        endif;

    }
}
