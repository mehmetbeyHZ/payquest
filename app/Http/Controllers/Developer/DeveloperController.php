<?php

namespace App\Http\Controllers\Developer;

use App\Classes\FirebaseCloudMessaging;
use App\Classes\SMSVerify;
use App\Classes\TcCheck;
use App\Classes\VerifyEmail;
use App\Events\CompetitionManager;
use App\Events\TestMessage;
use App\Http\Controllers\AuthInfoController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentRequestController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\UserLogController;
use App\Jobs\CompetitionBrain;
use App\Model\Admin;
use App\Model\Competition;
use App\Model\CompetitionRegister;
use App\Model\Diamond;
use App\Model\FcmToken;
use App\Model\Mission;
use App\Model\MissionHandle;
use App\Model\MongoTester;
use App\Model\PasswordReset;
use App\Model\Payment;
use App\Model\PaymentRequest;
use App\Model\QuestionSupport;
use App\Model\Reference;
use App\Model\TicketsMessages;
use App\Model\TicketsThread;
use App\Model\Uploads;
use App\Model\UsersLog;
use App\Model\UsersLogMongo;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Pusher\Pusher;


class DeveloperController extends Controller
{

    public function test(Request $request)
    {


        $pr = new PaymentRequest();
        return $pr
            ->where('created_at','LIKE','%2020-10%')
            ->where('is_confirmed',2)
            ->sum('quantity');

//
//        $total = User::count('id');
//        echo "Toplam :". $total."<br>";

//        $u = new User();
//        return $u->where('phone_verified_at','=',null)->where('created_at','<',now()->subDays(8))
//            ->update([
//               'is_banned' => 1
//            ]);



//        $scKey = 'sc_45598169_1';
//        $ecKey = 'ec_45598169_1';
//
//
//        var_dump((int)Cache::get($scKey,-1));
//        echo "<br>";
//        var_dump((int)Cache::get($ecKey,-1));
//
//       return MissionHandle::insert([
//            'real_mission_id' => 37366,
//            'mission_user' => 1,
//            'is_completed' => 0,
//        ]);

//        return response()->json([
//            'headers' => [
//                'key:value',
//                'key2:value',
//                'key3:value'
//            ],
//            'url' => 'https://instagram.com/p/?__a=1'
//        ]);

//        $p = new Payment();
//        return $p->with('mission')->where('user_id',1)->where('mission_id','!=',0)->orderBy('payment_id','DESC')->get();
//        foreach ($items as $item)
//        {
//            if ($item->mission){
//                $date = Carbon::parse($item->created_at);
//                $viewed = Carbon::parse($item->mission->viewed_at);
//                echo $date->diffInSeconds($viewed)."<br>";
//            }
//        }


//        $port = rand(20000,21000);
//        $htmlData = (new \MClient\Request("https://www.instagram.com/p/CGueYHzDJkW/embed/captioned/"))
//            ->addHeader("accept-language","tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7")
//            ->setUserAgent("Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36")
//            ->addCurlOptions(CURLOPT_SSL_VERIFYHOST,false)
//            ->addCurlOptions(CURLOPT_SSL_VERIFYPEER,false)
//            ->setProxy('155.138.205.214:'.$port)
//            ->addCurlOptions(CURLOPT_REFERER,'https://google.com')
//            ->execute()
//            ->getResponse();
//
//
//        return $htmlData;
//
//        preg_match_all('@<script type="text/javascript">window.__additionalDataLoaded\(\'extra\',(.*?)\);</script>@im',$htmlData,$rp);
//        print_r($rp[1][0]);


//        $tm = new TicketsMessages();
//        $tm->where('is_seen_by_admin',0)->update(['is_seen_by_admin' => 1]);

//        try{
//            $m = new Mission();
//            return $m->where('mission_level',8)->update(['mission_xp' => 40]);
//
//        }catch (\Exception $e)
//        {
//            return $e->getMessage();
//        }

//        $u = new User();
//        return $u->where('phone_verified_at','!=',null)->where('xp_cache','<',11200)->count();

//        return Cache::get('rewarded_ad_viewed:125413',0);

//        return [
//            [
//                'image' => 'https://i.hizliresim.com/Xb7Pyn.png',
//                'title' => 'Telefon numaranızı onaylayın.',
//                'text' => "Bu alana tıklayıp websitemize giderek telefon numaranızı onaylayın.",
//                'route' => route('user.login.access',['token' => "1"])
//            ]
//        ];


        //        $giveForUser = [];
//        $giveEnd     = [];
//        $PMGiveLimit = 4;
//        $takeUniq    = now()->format("d_H");
//        $hourlyTake  = 15;
//
//        $privateMissions = (new Mission())->where('intent_link', '!=', null)
//           // ->where('is_deleted', 0)
//            ->orderBy('mission_id', 'ASC')
//            ->whereIn('mission_id',[33078])
//            ->get();
//
//
//        $date = now();
//        foreach ($privateMissions as $pm):
//            $totalTakeInHour = Cache::get("pm_taken_at_{$takeUniq}".$pm->mission_id,0);
//
//            if (!Cache::has('pm_taken:' . $pm->mission_id)):
//                Cache::put('pm_taken:' . $pm->mission_id, 0, now()->addDays(2));
//            endif;
//
//            $totalTaken = Cache::get('pm_taken:' . $pm->mission_id);
//            if ($totalTaken >= $pm->mission_take_limit):
//                $giveEnd[] = $pm->mission_id;
//            else:
//                if ($totalTakeInHour < $hourlyTake):
//                    $giveForUser[] = ['real_mission_id' => $pm->mission_id, 'mission_user' => $user->id, 'is_completed' => 0, 'created_at' => $date, 'updated_at' => $date];
//                endif;
//            endif;
//        endforeach;
//
//        $leastPMList = [];
//        if (count($giveForUser) > 0):
//            $PMGiveLimit = count($giveForUser) >= $PMGiveLimit ? $PMGiveLimit : count($giveForUser);
//            $selectRM = array_rand($giveForUser, $PMGiveLimit);
//            $selectRM = is_array($selectRM) ? $selectRM : [$selectRM];
//            foreach ($selectRM as $key):
//                $item       = $giveForUser[$key];
//                $totalTaken      = Cache::get('pm_taken:' . $item['real_mission_id'],0);
//                $totalTakeInHour = Cache::get("pm_taken_at_{$takeUniq}".$item['real_mission_id'],0);
//
//                Cache::put("pm_taken_at_{$takeUniq}".$item['real_mission_id'],$totalTakeInHour + 1,now()->addMinutes(90));
//                Cache::put('pm_taken:' . $item['real_mission_id'], $totalTaken + 1,now()->addDays(2));
//                $leastPMList[] = $item;
//            endforeach;
//        endif;


//        $mission = new Mission();
//        return $mission->where('mission_level',205)->limit(500)->update([
//            'mission_level' => 5
//        ]);


//        return (new \MClient\Request('https://counts.live/api/youtube-subscriber-count/UC1QWq4aQkLYtVNdU4SEGXMQ/data'))
//            ->addHeader('user-agent','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36')
//            ->addHeader('sec-fetch-site','cross-site')
//            ->addHeader('referer','https://akshatmittal.com/youtube-realtime/')
//            ->execute()
//            ->getResponse();


//        $time = now();
//
//
//        $datas = DB::table('sorular_bellek')
//            ->where('image',"")
//            ->limit(1000);
//
//        $builder = [];
//        foreach ($datas->get() as $data){
//            $answers = json_encode([$data->option_a,$data->option_b,$data->option_c,$data->option_d],JSON_UNESCAPED_UNICODE);
//            $builder[] = [
//              'user_id' => 3,
//              'question' => $data->question,
//              'question_answers' => $answers,
//              'correct_index' => $data->answer,
//              'created_at' => $time,
//              'updated_at' => $time
//            ];
//        }
//
//        $datas->delete();
//
//        return QuestionSupport::insert($builder);


//

//        try{
//            $user = User::find(1);
//            $user->email = "mehmetiscod@hotmail.com";
//            $resetKey = "123456";
//            Mail::send('emails.forgot-password',['user' => $user,'reset_key' => $resetKey],function($m) use($user){
//                $m->from('support@payquestion.com','Payquestion');
//                $m->to($user->email, $user->name)->subject('@mt.ks Payquestion Şifrenizi Sıfırlayın!');
//            });
//
//        }catch (\Exception $e)
//        {
//            print_r($e->getMessage());
//        }

//

//

        // Cache::put('user_registered:'.$request->ip(),now(),now()->addHours(1));

//        return DB::table('mission')->where('mission_level',5)->where('updated_at','LIKE','%2020-10-08%')->get();


//        $items =  MissionHandle::with('mission_detail')
//            ->where('mission_user', 1)
//            ->orderBy('mission_handle_id', 'DESC')
//            ->where('is_completed', 0)
//            ->get();
//
//        foreach ($items as $item)
//        {
//            $iter = Cache::remember("mission_handle:".$item->mission_handle_id,now()->addHours(1),static function() use($item){
//                return $item;
//            });
//        }


//        return MissionHandle::find(363913)->update(['is_completed' => 4]);

//        $r = new \MClient\Request('httpsdf');
//        $r->execute();

//


//        $prices = [0.10,0.11,0.12];
//        $answers = [];
//        foreach ($data as $item)
//        {
//            $answers = json_encode([$item["A"],$item["B"],$item["C"],$item["D"]],JSON_UNESCAPED_UNICODE);
//            if ($item["CEVAP"] === "A")
//            {
//                $correct_index = 0;
//            }elseif ($item["CEVAP"] === "B")
//            {
//                $correct_index = 1;
//            }elseif ($item["CEVAP"] === "C")
//            {
//                $correct_index = 2;
//            }else{
//                $correct_index = 3;
//            }
//            $builder[] = [
//                    'mission_level' => 8,
//                    'mission_value' => $prices[array_rand($prices)],
//                    'mission_second' => 20,
//                    'mission_xp' => 80,
//                    'mission_take_limit' => 0,
//                    'is_question' => 1,
//                    'mission_question' => $item['Soru'],
//                    'type' => 0,
//                    'intent_link' => null,
//                    'mission_question_answers' => $answers,
//                    'correct_index' => $correct_index,
//                    'is_deleted' => 0,
//                    'created_at' => now(),
//                    'updated_at' => now()
//                ];
//        }

//        return $builder;
//
//        return Mission::insert($builder);
//        $registers = new CompetitionRegister();
//        return $registers->where('user_id',1)->with('competition')->get();


//        Cache::forget('ip_blocking');

//        $m = MissionHandle::find(234692);
//        $m->update(['is_completed',3]);
//        $p = new PaymentController();
//        $p->addPayment(1126,0.30,235602,"add_balance",1);
//        return $m->update(['is_completed' => 1]);
//        return Cache::get('ip_blocking');


//        $mission = new Mission();
//        DB::table('mission')->update(['mission_second' => 20]);

//        return User::find(1)->update(['is_banned' => 0]);

//        DB::table('oauth_access_tokens')
//            ->where('user_id', 1)
//            ->delete();


//        var_dump(Auth::user());


//
        $ids =  collect(pusher()->get_users_info('presence-competition-manager.1'));
//
//        if (isset($ids['users']))
//        {
//           $list = array_map(function ($user){
//               return $user->id;
//           },$ids['users']);
//           return User::whereIn('id',$list)->get();
//        }

//        broadcast(new TestMessage('Test DEV'));

//        broadcast(new CompetitionManager(1,'message_view',['status' => 'ok']));
//
//        $user = User::find(1);
//        return $user->createToken('PayQuest')->accessToken;

//        CompetitionRegister::create([
//           'user_id' => 2,
//           'competition_id' => 1
//        ]);

//        return Competition::create([
//            'competition_title' => 'İlk bilgi yarışması',
//            'competition_description' => 'İlk bilgi yarışması testi',
//            'competition_image' => null,
//            'registration_fee' => 1,
//            'award' => 9,
//            'can_register' => 1,
//            'start_date' => now()->addHours(2),
//            'last_register_date' => now()->addHours(2),
//            'total_winner' => 1,
//            'max_users' => 10
//        ]);

//        $user = new User();
//        return $user->where('avatar','https://payquestion.com/logo_default.png')->update([
//            'avatar' => 'https://payquestion.com/storage/avatar/avatar_blank.png'
//        ]);
//
//        $mission = new Mission();
//        return $mission->where('mission_level',10)->limit(100)->update([
//           'mission_level' => 7
//        ]);


//        symlink(storage_path('app'),public_path('storage'));

//        $pusher = new Pusher(
//            config('broadcasting.connections.pusher.key'),
//            config('broadcasting.connections.pusher.secret'),
//            config('broadcasting.connections.pusher.app_id'),
//            config('broadcasting.connections.pusher.options')
//        );
//
//        event(new NewMessage('asastest'));
//        dd($pusher->get_channels());


//        return public_path();
//            ->update([
//                'mission_level' => 8
//            ]);


//        TicketsThread::create([
//            'thread_title' => 'Uygulama',
//            'thread_user' => 161
//        ]);


//        $m = new Mission();
//        return $m->where('mission_level',10)
//            ->update([
//                'mission_level' => 8
//            ]);

//
//        $prices = [0.6,0.7,0.8];
//        $file = storage_path('questions/level2.csv');
//        $csv = array_map('str_getcsv', file($file));
//        $csvData = $this->csvToArray($file);
//        $builder = [];
//        $i = 0;
//        foreach ($csvData as $data) {
//
//
//                $answers = json_encode([$data["option_a"],$data["option_b"],$data["option_c"],$data["option_d"]],JSON_UNESCAPED_UNICODE);
//
//                $correct_index = -1;
//                if ($data["right_answer"] === "A")
//                {
//                    $correct_index = 0;
//                }
//                if ($data["right_answer"] === "B")
//                {
//                    $correct_index = 1;
//                }
//                if ($data["right_answer"] === "C")
//                {
//                    $correct_index = 2;
//                }
//
//                if ($data["right_answer"] === "D")
//                {
//                    $correct_index = 3;
//                }
//
//                $builder[] = [
//                    'mission_level' => 2,
//                    'mission_value' => $prices[array_rand($prices)],
//                    'mission_second' => 30,
//                    'mission_xp' => 20,
//                    'mission_take_limit' => 0,
//                    'is_question' => 1,
//                    'mission_question' => $data['question'],
//                    'type' => 0,
//                    'intent_link' => null,
//                    'mission_question_answers' => $answers,
//                    'correct_index' => $correct_index,
//                    'is_deleted' => 0,
//                    'created_at' => now(),
//                    'updated_at' => now()
//                ];
//
//        }
//
////        return $builder;
//////
//        return Mission::insert($builder);


//        $model = (new Mission())->where('mission_level',6)->get();
//        $safeUpdate = [];
//        $prices = [0.10,0.11,0.12];
//
//        foreach ($model as $item)
//        {
//            $safeUpdate[] = [
//                'mission_id' => $item->mission_id,
//                'mission_value' => $prices[array_rand($prices)]
//            ];
//        }
//
//         batch()->update(new Mission(),$safeUpdate,'mission_id');


//        $users = FcmToken::all()
//            ->groupBy('fcm_token');
//        return $users;

        // https://fcm.googleapis.com/fcm/send
        // AAAAyDADHH4:APA91bEcPiij-0kQUQNYAIZ_sBlYN3afeP2n4fZD-Zlt0aRIrRneJ9KsUzU2pAn01WS7xDB2JBclJR3WqWLKtWGgfOEXPResnIWVTf_K4gEFJU653OiDF6A8RsIy402GcZGqd4pWLTBA

//        $user = User::with('ticketThreads.messages')->where('id',1)->first();
//        return $user;

//        return TicketsMessages::create([
//            'thread_id' => 2,
//            'message' => 'Uzun uzun kavaklar',
//            'sender' => 1,
//            'receiver' => 1,
//            'sender_is_admin' => 1,
//            'is_seen' => 0
//        ]);

//        $curl = curl_init();
//        $opt = [
//            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
//            CURLOPT_RETURNTRANSFER => TRUE,
//            CURLOPT_FOLLOWLOCATION => TRUE,
//            CURLOPT_POST => true,
//            CURLOPT_POSTFIELDS => json_encode(["to" => "fahb_cPdSJu0WMpOSMumQJ:APA91bH3ZiLz2CNKQVhGaDqPxiyp7lO42dIfC0d6pi802BoOcGI9xyd-Ex-6r_dXUQKBNDpHvzsFeqWwl-msfluX9eWnZtKTNm7v3dXob42mUgDDjCJJO_gUuI8pPzxOdcH9OmgkNzsT", "notification" => ['title' => 'Hacı beco geliyo', 'body' => 'qweqwe']]),
//            CURLOPT_HTTPHEADER => [
//                'Content-Type: application/json',
//                'Authorization: key=AAAAyDADHH4:APA91bEcPiij-0kQUQNYAIZ_sBlYN3afeP2n4fZD-Zlt0aRIrRneJ9KsUzU2pAn01WS7xDB2JBclJR3WqWLKtWGgfOEXPResnIWVTf_K4gEFJU653OiDF6A8RsIy402GcZGqd4pWLTBA'
//            ]
//        ];
//        curl_setopt_array($curl,$opt);
//        $resp = curl_exec($curl);
//        curl_close($curl);
//        return $resp;

    }

    public function csvToArray($file)
    {
        $rows = array();
        $headers = array();
        if (file_exists($file) && is_readable($file)) {
            $handle = fopen($file, 'r');
            while (!feof($handle)) {
                $row = fgetcsv($handle, 10240, ',', '"');
                if (empty($headers))
                    $headers = $row;
                else if (is_array($row)) {
                    array_splice($row, count($headers));
                    $rows[] = array_combine($headers, $row);
                }
            }
            fclose($handle);
        } else {
            throw new \Exception($file . ' doesn`t exist or is not readable.');
        }
        return $rows;
    }

}
