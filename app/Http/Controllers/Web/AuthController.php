<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Model\PasswordReset;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function _login(Request $request)
    {
        request()->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $auth = Auth::attempt($request->only(['email', 'password']));
        if ($auth):
            return response()->json(['status' => 'ok', 'message' => __('app.login_success'), 'redirect' => route('user.insight')]);
        endif;
        return response()->json(['status' => 'fail', 'message' => __('app.login_failed')], 400);
    }

    public function logout()
    {
        Auth::logout();;
        return redirect()->route('user.welcome');
    }

    public function resetPassword($key)
    {
        $pr = (new PasswordReset())->where('reset_key', $key)->where('last_activation', '>', now())->first();
        if (!$pr):
            abort(404);
        endif;
        return view('auth.forgot-password', ['key' => $key]);
    }

    public function _resetPassword($key,Request $request)
    {

        request()->validate([
            'password' => 'required|min:6',
            're_password' => 'required|same:password'
        ]);

        $pr = (new PasswordReset())->where('reset_key', $key)->where('last_activation', '>', now())->first();
        if (!$pr):
            return response()->json(['status' => 'fail', 'message' => 'Bir sorun oluştu!', 'redirect' => route('user.login')]);
        endif;
        $user = User::find($pr->user_id);
        if (!$user):
            return response()->json(['status' => 'fail', 'message' => 'Kullanıcı bulunamadı', 'redirect' => route('user.login')]);
        endif;
        $user->password = Hash::make(request('password'));
        $user->save();
        Auth::loginUsingId($user->id,1);
        $pr->delete();
        return response()->json(['status' => 'ok', 'message' => 'Şifreniz güncellendi.','redirect' => route('user.insight')]);
    }


    public function sendConfirmationManual($email)
    {
        $email = base64_decode($email);
        $user = (new User())->where('email',$email)->first();
        if (!$user):
            abort(404);
        endif;
        if ($user->email_verified_at !== null):
            return view('email-confirmed',['message' => "Mail adresiniz zaten onaylıdır."]);
        endif;
        (new \App\Http\Controllers\AuthController())->sendVerifyEmail($user);
        return view('email-confirmed',['message' => "Lütfen <b>{$email}</b> adresinizi kontrol edin."]);
    }

    public function confirmEmail($key)
    {
        $pr = (new PasswordReset())->where('reset_key', $key)->where('last_activation', '>', now())->first();
        if (!$pr):
            return view('email-confirmed');
        endif;
        $user = User::find($pr->user_id);
        $user->update(['email_verified_at' => now(),'is_banned' => 0]);
        $pr->delete();
        return view('email-confirmed',['user' => $user]);
    }

    public function forgotPassword()
    {
        return view('auth.send-forgot-mail');
    }
}
