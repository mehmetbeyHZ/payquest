<?php

namespace App\Http\Controllers;

use App\Model\Bank;
use App\Model\PaymentRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentRequestController extends Controller
{
    public function getBanks()
    {
        return [
            'status' => 'ok',
            'banks'  => Bank::all()->toArray()
        ];
    }

    public function newPaymentRequest(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'iban' => 'required',
            'bank_id' => 'required|numeric',
            'quantity' => 'required|numeric|min:200'
        ]);


        if ($request->input('quantity') && (int)$request->input('quantity') < 200)
        {
            return response()->json(['status' => 'fail','message' => 'Ödeme miktarı en az 200 olmalıdır.'],400);
        }

        $user = (new User())->find(Auth::id());
        $balance = $user->balance();

        if ($balance < 200 || $balance < (int)$request->input('quantity')):
            return response()->json(['status' => 'fail','message' => 'Yeterli bakiyeniz yok.'],400);
        endif;


        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()],400);
        endif;


         if ((int)$request->input('bank_id') !== 15):
             if (!isValidIBAN($request->input('iban')))
             {
                 return response()->json(['status' => 'fail', 'message' => 'Hatalı IBAN'],400);
             }
         endif;

        $pr = new PaymentRequest();
        $all = $pr->where('user_id',Auth::id())->where('is_confirmed',0)->count();
        if ($all > 0)
        {
            return response()->json(['status' => 'fail','message' => 'Zaten bir ödeme talebiniz mevcut.'],400);
        }

        if ($user->totalRef() < 5)
        {
            return response()->json(['status' => 'fail', 'message' => 'En az 5 referansa ihtiyacınız var.'],400);
        }

        $pr = new PaymentRequest();
        $pr->user_id  = Auth::id();
        $pr->iban     = $request->input('iban');
        $pr->bank_id  = $request->input('bank_id');
        $pr->quantity = $request->input('quantity');
        $save = $pr->save();
        if ($save):
            return response()->json(['status' => 'ok', 'message' => 'Ödeme talebi oluşturuldu.']);
        else:
            return response()->json(['status' => 'fail', 'message' => 'Bir sorun oluştu.'],400);
        endif;

    }

    public function getAllRequests()
    {
        return [
            'status' => 'ok',
            'payment_requests' =>  PaymentRequest::with('bank')->orderBy('request_id','desc')->where('user_id',Auth::id())->get()->toArray()
        ];
    }

}
