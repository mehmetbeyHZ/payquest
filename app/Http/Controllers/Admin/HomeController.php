<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Mission;
use App\Model\MissionHandle;
use App\Model\Payment;
use App\Model\PaymentRequest;
use App\Model\TicketsThread;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $user = new User();
        $missions = new Mission();
        $missionHandle = new MissionHandle();
        $paymentRequests = new PaymentRequest();
        $tickets = new TicketsThread();
        $data = Cache::remember('admin_insight_data3',now()->addMinutes(20), static function() use($user,$missions,$missionHandle,$paymentRequests,$tickets){
            return [
                'Kullanıcılar' => $user->count('id'),
                'Onaylı Kullanıcılar' => $user->where('phone_verified_at','!=','')->count('id'),
                'Toplam Görev' => $missions->count('mission_id'),
                'Alınan Görevler' => $missionHandle->where('mission_handle_id','!=',0)->count('mission_handle_id'),
                'Ödeme Bildirimleri' => $paymentRequests->count('request_id'),
                'Onaylanan Ödemeler' => $paymentRequests->where('is_confirmed', 1)->count('request_id'),
                'Toplam Destek Talebi' => $tickets->count('thread_id'),
                'Kazanılan Bakiye' => 0,
            ];
        });

        return view('admin.home', ['labels' => $data]);
    }


    public function insight(Request $request)
    {
        return Cache::remember('insight_'.(int)$request->input('last_x').'_'.$request->input('format'),now()->addHours(4), static function() use($request) {
            return (new HomeController())->earnInsight((int)$request->input('last_x'),$request->input('format'));
        });
    }

    public function charges_money($subDays)
    {

        //    $date = now()->subDays($subDays)->format('Y-m-d');

        $payment = (new Payment());
        $earn = $payment->where('payment_token_confirmed', 1)
            ->where('payment_type', 1)
            ->sum('payment_value');

        return round($earn,2,PHP_ROUND_HALF_ODD);
    }

    public function earnInsight($lasXDay = 7,$dateFormat = 'Y-m-d')
    {
        $payment = (new Payment());
        $earns   = [];
        $dates   = [];
        for($i = 0; $i < $lasXDay; $i++)
        {
            $date = Carbon::now('Europe/Istanbul');
            $date = ($dateFormat === 'Y-m') ? $date->subMonths($i) : $date->subDays($i);
            $date = $date->format($dateFormat);

            $earn = $payment->where('payment_token_confirmed', 1)
                ->where('payment_type', 1)
                ->where('created_at','LIKE',"%$date%")
                ->sum('payment_value');

            $earns[] = round($earn,2,PHP_ROUND_HALF_ODD);
            $dates[] = $date;
        }
        return [
            'earns' => array_reverse($earns),
            'dates' => array_reverse($dates)
        ];
    }


}
