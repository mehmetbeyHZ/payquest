<?php

namespace App\Http\Controllers;

use App\Model\Reference;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReferenceController extends Controller
{
    public function addReference($registeredId,$from)
    {
        $ref = new Reference();
        $ref->from = $from;
        $ref->registered_id = $registeredId;
        return $ref->save();
    }

    public function enterRefCode(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'code' => 'required'
        ]);

        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()],400);
        endif;

        $ref_code = $request->input('code');

        $refModel = new Reference();
        $myCodes  = $refModel->where('registered_id',Auth::id())->count();
        if (Auth::id() === 1):
            $myCodes = 0;
        endif;
        if ($myCodes > 0):
            return response()->json(['status' => 'fail', 'message' => 'Birden fazla referans ekleyemezsiniz.'],400);
        endif;


        if(Auth::user()->ref_code === $ref_code):
            return response()->json(['status' => 'fail', 'message' => 'Kendi referans kodunuzu giremezsiniz.'],400);
        endif;

        $user = new User();
        $refUser = $user->where('ref_code',$ref_code)->where('is_banned',0)->first();

        if ($refUser):
            // add balance $refUser->id
            $userRefs = new Reference();
            $himRefs = $userRefs->where('from',$refUser->id)->count('from');
            if($himRefs < 100):
                $this->addReference(Auth::id(),$refUser->id);
                (new PaymentController())->addPayment($refUser->id,0.25,0,"add_balance_reference",1);
                return response()->json(['status' => 'ok', 'message' => 'Referans eklendi.'],200);
            endif;
            return response()->json(['status' => 'fail', 'message' => 'Bu kullanıcı maximum referans sayısına ulaştı.'],400);
        else:
            return response()->json(['status' => 'fail', 'message' => 'Bu referans koduna bağlı bir kullanıcı yok.'],400);
        endif;
    }


    public function myReferenceUsers()
    {
        $data = Reference::with(['user_info' => static function($query) {$query->select(['id', 'name','avatar','is_verified','phone_verified_at']); }])->where('from',Auth::id())->get();
        return response()->json([
           'status'     => 'ok',
           'references' => $data
        ]);
    }

    public function generateRefKey()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 7; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
