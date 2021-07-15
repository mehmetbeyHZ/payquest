<?php

namespace App\Http\Controllers;

use App\Model\FcmToken;
use App\Model\MissionHandle;
use App\Model\Reference;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PhpParser\Builder;

class AuthInfoController extends Controller
{
    public const REQUIRE_REF = 5;
    public const REQUIRE_MIN_PAYMENT = 200;
    public function info()
    {
        return Cache::remember('user_profile_info:'.Auth::id(),now()->addMinutes(2),static function(){
            $user = Auth::user();
            $userFind = (new User())->find(Auth::id());
            $balance = $userFind->balance();
            $diamond = $user->totalDiamond();
            $xp =  (new XPController())->getUserXp($user->id);
            $user->balance = $balance;
            $user->xp = $xp;
            $user->diamond = $diamond;
            $user->avatar = 'https://i.hizliresim.com/GNTf1p.png';
            $user->level = xpToLevel($user->xp)['level'];
            $userFind->update(['balance_cache' => $balance,'xp_cache' => $xp,'ip' => request()->ip(),'ref_cache' => (new AuthInfoController())->totalReference(), 'diamond_cache' => $diamond]);
            return $user;
        });
    }


    public function profile()
    {
        return [
            'user' => (new AuthInfoController())->info(),
            'statistic' => (new AuthInfoController())->statistic()
        ];
    }

    private function statistic() : array {

        return Cache::remember('user_statistic_v2:'.Auth::id(),now()->addMinutes(2),static function(){
            $user     = (new User())->find(Auth::id());
            $totalXp  =  (new XPController())->getUserXp($user->id);
            $balance  = $user->balance();

            $ref = new Reference();
            $totalRef = $ref->where('from',Auth::id())->count('reference_id');


            $ms = new MissionHandle();
            $totalAnswered = $ms->where('mission_user',Auth::id())->count('mission_user');

            $wrong = $ms->where('mission_user',Auth::id())->where('is_completed',2)->count('mission_user');

            $vs = [];
            if ($user->is_verified === 1):
                $vs = [
                    [
                        'text' => 'Profil Durumu',
                        'progress' => 100,
                        'label' => 'GÜVENİLİR'
                    ]
                ];
            endif;
            if ($user->phone_verified_at !== null):
                $vs[] = ['text' => 'Telefon Onayı', 'progress' => 100, 'label' => 'Onaylı'];
            endif;
            $xpToLevel = xpToLevel($totalXp);
            $deta = [
                [
                    'text'          => 'Referans kodumu girenler',
                    'progress'      => round($totalRef / self::REQUIRE_REF * 100),
                    'label'         => $totalRef.'/'. self::REQUIRE_REF
                ],
                [
                    'text'          => 'Aylık minimum ödeme',
                    'progress'      => round($balance / self::REQUIRE_MIN_PAYMENT * 100),
                    'label'         => $balance. '/' . self::REQUIRE_MIN_PAYMENT
                ],
                [
                    'text'          => 'Sonraki seviye',
                    'progress'      => round($totalXp / ((int)$xpToLevel['xp_need'] + (int)$totalXp) * 100),
                    'label'         => $totalXp. 'XP/'. ((int)$xpToLevel['xp_need'] + (int)$totalXp)."XP"
                ],
                [
                    'text'          => 'Toplam alınan soru',
                    'progress'      =>  100,
                    'label'         => $totalAnswered
                ],
                [
                    'text'          => 'Hatalı cevaplanan sorular',
                    'progress'      =>  100,
                    'label'         => $wrong
                ],
                [
                    'text'          => 'İstatistikler ve bakiye güncellenmesi.',
                    'progress'      => 100,
                    'label'         => '2DK'
                ]
            ];
            return count($vs) > 0 ? array_merge($vs,$deta) : $deta;
        });

    }

    public function totalReference() : int
    {
        $ref = new Reference();
        return $ref->where('from',Auth::id())->count('from');
    }

    public function notifications()
    {
        $user = Auth::user();
        $binding = [];
        if (!$user->phone_verified_at):
            $accessToken = md5(time().Auth::id().microtime());
            $keyBuilder  = Cache::put($accessToken,request()->bearerToken(),now()->addHours(2));
            $binding = [
                [
                    'image' => 'https://i.hizliresim.com/G3s51P.png',
                    'title' => 'Telefon numaranızı onaylayın.',
                    'text' => "Bu alana tıklayıp websitemize giderek telefon numaranızı onaylayın. \nTelefon numarasını onaylamayan kullanıcılar ödeme alamaz!",
                    'route' => route('user.login.access',['token' => $accessToken])
                ]
            ];
        endif;
        $hardbin = [
            [
                'image' => 'https://i.hizliresim.com/NS3l2Q.png',
                'title' => 'REKLAM KİMLİĞİ NASIL SIFIRLANIR?',
                'text' => "Bu alana tıklayınız ve youtube videosunu izleyin..",
                'route' => "https://youtu.be/3qYFlN8x8LA"
            ],
            [
                'image' => 'https://i.hizliresim.com/Xb7Pyn.png',
                'title' => 'SIKÇA SORULAN SORULAR.',
                'text' => "Bu alana tıklayınız. Bu alanda yer alan cevaplar destek talebinde aynı şekilde iletilir.",
                'route' => "https://www.payquestion.com/sss"
            ],
            [
                'image' => 'https://i.hizliresim.com/Xb7Pyn.png',
                'title' => 'KULLANIM KOŞULLARI.',
                'text' => "Bu alana tıklayın ve kullanım koşullarını okuyun",
                'route' => "https://www.payquestion.com/kullanim-kosullari"
            ],
            [
                'image' => 'https://i.hizliresim.com/OWsMjv.png',
                'title' => 'Telegram Yardımlaşma Grubu',
                'text' => "Telegram yardımlaşma grubu için 'Telegram' uygulamasını indirip bu alana tıklayınız.",
                'route' => "https://t.me/payquestion"
            ],
            [
                'image' => 'https://i.hizliresim.com/Xb7Pyn.png',
                'title' => 'Ödeme Kanıtları',
                'text' => "2020 Eylül ayı ödemeleri gerçekleşmiştir. Bu alana tıklayarak görüntüleyebilirsiniz.",
                'route' => "https://www.instagram.com/p/CE6kixZD6aO/"
            ],
            [
                'image' => 'https://i.hizliresim.com/Xb7Pyn.png',
                'title' => 'Önemli Bilgilendirme',
                'text' => "Aynı cihazdan açılıp referans olmuş hesapların referanslığı geçersiz sayılacaktır. Her referans için 0.25₺ bakiye eklenir.",
                'route' => null
            ],
            [
                'image' => 'https://i.hizliresim.com/62ii5J.png',
                'title' => 'Bilgilendirme',
                'text' => 'İzlenmeden kapatılan ödüllü reklamlarda ödülleriniz ve XPleriniz tanımlanmaz.',
                'route' => null
            ],
            [
                'image' => 'https://i.hizliresim.com/qnQk85.png',
                'title' => 'Referans Hakkında',
                'text' => "Ödülünüzü kullanabilmek için en az 5 referans kullanıcısına  ihtiyaç vardır. 5 Referans ve 200₺ değerine ulaştığınızda talep gönderebilirsiniz.",
                'route' => null
            ]
        ];
        $deta = array_merge($binding,$hardbin);
        return response()->json($deta);
    }


}
