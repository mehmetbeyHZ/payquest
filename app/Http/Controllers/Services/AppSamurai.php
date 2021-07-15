<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppSamurai extends Controller
{
    public function callbackService($token,Request $request)
    {
        return response()->json(['status' => 'ok', 'message' => 'Callback received.'],200);
    }
}
