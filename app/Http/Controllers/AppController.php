<?php


namespace App\Http\Controllers;


class AppController extends Controller
{
    public function info(){
        return response()->json([
            'status' => 'ok',
            'build_number' => 5,
            'lock_app' => 1,
            'intent_link' => 'com.payquestion.payquest',
            'advertising_resets' => true,
            'advertising_seconds' =>  86400
        ]);
    }
}
