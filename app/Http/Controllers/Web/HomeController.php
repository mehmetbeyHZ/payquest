<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Model\Blog;
use App\Model\PaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $blog = new Blog();
        return view('welcome',['blogs' => $blog->where('is_published',1)->limit(9)->get()]);
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function howToPlay()
    {
        return view('how-to-play');
    }

    public function paymentConfirm()
    {
        $data = Cache::remember('payment_gived_x',now()->addSeconds(10),static function(){
            $pr = new PaymentRequest();
            $users = $pr->with('user')->where('created_at','LIKE','%2020-10%')->where('is_confirmed',1);
            return ['users' => $users->get(), 'total_charges' => $users->sum('quantity'),'total' => $users->count()];
        });
        return view('balance-gived',$data);
    }
}
