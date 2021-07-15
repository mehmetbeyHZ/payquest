<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('admin.auth.login');
    }

    public function doLogin(Request $request)
    {
        $validator = Validator::make($request->all(),[
           'username' => 'required',
           'password' => 'required'
        ]);

        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()]);
        endif;

        $credentials = $request->only(['username','password']);
        if (Auth::guard('admin')->attempt($credentials))
        {
            return response()->json(['status' => 'ok','message' => 'Giriş başarılı', 'redirect' => route('admin.home')]);
        }

        return response()->json(['status' => 'fail', 'message' => 'Hatalı bilgiler.'],400);

    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
