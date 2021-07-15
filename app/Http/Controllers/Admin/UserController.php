<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Model\FcmToken;
use App\Model\Payment;
use App\Model\Reference;
use App\Model\UsersLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function users(Request $request)
    {
        $users = new User();
        if (request('type') && in_array(request('type'), ["ip", "name", "email", "ref_code", "phone_number"])) {
            $users = $users->where(request('type'), "LIKE", "%" . request('q') . "%");
        }

        if ($request->input('sort_by'))
        {
            $users = new User();
            $users = $users->orderBy($request->input('sort_by'),'DESC');
        }else{
            $users = $users->orderBy('id','DESC');
        }

        return view('admin.users', ['users' => $users->paginate(20)]);
    }

    public function suspend(Request $request)
    {

        request()->validate([
           'id' => 'required|numeric',
           'is_banned' => 'required|numeric'
        ]);
        DB::table('oauth_access_tokens')->where('user_id', request('id'))->delete();
        User::find(request('id'))->update(['is_banned' => request('is_banned'),'ref_cache' => 0]);

        $ref = new Reference();
        $hasRef = $ref->where('registered_id',request('id'))->first();
        if ($hasRef && (int)request('is_banned') === 1):
            $pc = new PaymentController();
            $pc->reducePayment($hasRef->from,0.25,0,'reduce_reference_suspend');
            $hasRef->delete();
        endif;

        return response()->json(['status' => 'ok', 'message' => 'Güncellendi']);
    }

    public function deleteReference(Request $request)
    {
        request()->validate([
            'from' => 'required',
            'registered_id' => 'required',
            'reference_id' => 'required'
        ]);

        Reference::find(request('reference_id'))->delete();
        (new PaymentController())->reducePayment((int)request('from'),0.25,0,"reduce_reference_remove");
        return response()->json(['status' => 'ok', 'message' => 'Referans Silindi.', 'reference_id' => request('reference_id')]);

    }

    public function verifyUser(Request $request)
    {
        request()->validate([
           'id' => 'required|numeric',
           'is_verified' => 'required|numeric'
        ]);
        User::find(request('id'))->update(['is_verified' => request('is_verified')]);
        return response()->json(['status' => 'ok', 'message' => 'Güncellendi']);
    }


    public function editUser($id)
    {
        $user = User::findOrFail($id);

        $userToken = new FcmToken();
        $authId = $userToken->where('user_id',$id)->first();
        $fcmToken = null;
        if ($authId):
            $fcmToken = $authId->fcm_token;
        endif;
        $devices = FcmToken::with('user')->whereNotIn('user_id',[$id])->where('fcm_token',$fcmToken)->get();
        $ref  = Reference::with('user_info')->where('from',$id)->orderBy('reference_id','DESC')->get();

        $refFrom = Reference::with(['from_user','user_info'])
            ->where('registered_id',$id)
            ->first();

        $p = new Payment();
        $payments = $p->with('mission')->where('user_id',$id)->orderBy('payment_id','DESC')->paginate(50);

        $average = Cache::remember('user_answer_average2:'.$id,now()->addMinutes(1),function () use ($id){
            $p = new Payment();
            $i = 0;
            $total = 0;
            $payments = $p->with('mission')->where('user_id',$id)->where('payment_description','add_balance')->orderBy('payment_id','DESC')->limit(200)->get();
            foreach ($payments as $payment)
            {
                if ($payment->mission){
                    $i++;
                    $total += (\Illuminate\Support\Carbon::parse($payment->created_at))->diffInSeconds(\Illuminate\Support\Carbon::parse($payment->mission->viewed_at));
                }
            }
            return $i > 0 ? round($total/$i) : 0;
        });

        return view('admin.edit-user', ['user' => $user, 'logs' => $user->logs()->paginate(15), 'payments' => $payments,'references' => $ref,'devices' => $devices,'ref_from' => $refFrom,'average' => $average]);
    }

    public function doEditUser($id)
    {
        request()->validate([
            'email' => 'required',
            'name' => 'required',
            'phone_number' => 'required'
        ]);
        User::find($id)->update([
           'email' => request('email'),
           'name' => request('name'),
           'phone_number' => request('phone_number'),
        ]);
        return response()->json(['status' => 'ok', 'message' => 'Güncellendi.']);
    }

    public function changePassword($id)
    {
        request()->validate([
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);
        User::find($id)->update([
            'password' => Hash::make(request('password'))
        ]);
        return response()->json(['status' => 'ok', 'message' => 'Şifre değiştirildi.']);
    }


}
