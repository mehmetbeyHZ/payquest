<?php

namespace App\Http\Controllers;

use App\Model\Payment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function initPayment($user_id,$value,$type = 1,$description = '',$missionId = 0,$directConfirm = 0)
    {
        $paymentToken = $this->paymentHash($user_id);
        $payment = new Payment();
        $payment->payment_value = $value;
        $payment->payment_type  = $type;
        $payment->user_id       = $user_id;
        $payment->payment_description   = $description;
        $payment->mission_id    = $missionId;
        $payment->payment_token = $paymentToken;
        $payment->payment_token_confirmed = $directConfirm;
        $payment->save();
        return $paymentToken;
    }

    public function addPayment($user,$value,$missionId = 0,$description = 'add_balance',$directConfirm = 0)
    {
        return $this->initPayment($user,$value,1,$description,$missionId,$directConfirm);
    }

    public function reducePayment($user,$value,$missionId = 0, $description = 'reduce_balance') : bool
    {
        $user = (new User())->find($user);
        if ($user->balance() >= $value)
        {
            return $this->initPayment($user->id,$value,2,$description,$missionId,1);
        }
        return false;
    }

    private function paymentHash($user_id)
    {
        return Str::random(40).$user_id;
    }
}
