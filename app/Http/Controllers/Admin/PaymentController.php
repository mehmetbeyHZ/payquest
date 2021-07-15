<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Bank;
use App\Model\PaymentRequest;
use App\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function paymentRequests(Request  $request)
    {
        $paymentRequest = PaymentRequest::with('user')->with('bank')
            ->orderBy('request_id','DESC');

        if (request('q') && request('type') === "name"):
            $user = User::where('name','LIKE',"%".request('q')."%")->pluck('id')->toArray();
            if ($user):
                $paymentRequest = $paymentRequest->whereIn('user_id',$user);
            endif;
        endif;

        if ($request->has('created_at')){
            $paymentRequest->where('created_at','LIKE',"%".request('created_at')."%");
        }

        if (in_array(request('is_confirmed'),["0","1","2"]))
        {
            $paymentRequest->where('is_confirmed',request('is_confirmed'));
        }

        if ($request->input('bank_id'))
        {
            $paymentRequest->where('bank_id',request('bank_id'));
        }

        $banks = Bank::all();

        return view('admin.payment-requests',['payments' => $paymentRequest->paginate(50),'banks' => $banks]);
    }

    public function getPayment($id)
    {

        $payment =  PaymentRequest::findOrFail($id);
        $data    = $payment->with('bank')->with('user')->where('request_id',$id)->first();
        $data->user->balance = $data->user->balance();
        $data->user->xp = $data->user->totalXP();
        return $data;
    }

    public function updateRequest(Request $request)
    {
        request()->validate([
           'request_id' => 'required',
           'is_confirmed' => 'required|numeric'
        ]);


        $paymentRequest = PaymentRequest::find(request('request_id'));
        $user = User::find($paymentRequest->user_id);
        if ($user->balance() < $paymentRequest->quantity && (int)request('is_confirmed') === 1)
        {
            return response()->json(['status' => 'fail','message' => 'Bakiye eşleşmiyor!'],400);
        }

        if ((int)request('is_confirmed') === 1)
        {
            (new \App\Http\Controllers\PaymentController())->reducePayment((int)$paymentRequest->user_id,$paymentRequest->quantity,0,'send_to_iban');
        }

//        if ($paymentRequest->is_confirmed === 1 && (int)request('is_confirmed') === 2)
//        {
//            (new \App\Http\Controllers\PaymentController())->addPayment((int)$paymentRequest->user_id,$paymentRequest->quantity,0,'cancel_payment_from_iban',1);
//        }

        $paymentRequest->is_confirmed = (int)request('is_confirmed');
        $paymentRequest->save();
        return response()->json(['status' => 'ok', 'message' => 'Güncellendi']);
    }

}
