<?php

namespace App;

use App\Model\Diamond;
use App\Model\FcmToken;
use App\Model\Mission;
use App\Model\MissionHandle;
use App\Model\PayCoin;
use App\Model\Payment;
use App\Model\Reference;
use App\Model\TicketsMessages;
use App\Model\TicketsThread;
use App\Model\UsersLog;
use App\Model\XP;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App
 * @mixin Builder
 */
class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','gender','ref_code','phone_verified_at','phone_number','avatar', 'is_banned','balance_cache', 'xp_cache', 'diamond_cache','email_verified_at','ref_cache','ip', 'questions_completed_at',
        'is_verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','updated_at','created_at','is_banned', 'balance_cache'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return HasMany
     */
    public function missions(): HasMany
    {
        return $this->hasMany(MissionHandle::class,'mission_user','id');
    }

    public function totalRef()
    {
        return $this->hasMany(Reference::class,'from','id')->count();
    }

    /**
     * @return int
     */
    public function totalXP() : int
    {
        return $this->hasMany(XP::class,'user_id','id')->sum('value');
    }

    public function totalQuestions()
    {
        return $this->hasMany(MissionHandle::class,'mission_user')->count();
    }

    public function xp()
    {
        return $this->hasMany(XP::class,'user_id','id');
    }


    public function fcmToken()
    {
        return $this->hasOne(FcmToken::class,'user_id','id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class,'user_id','id');
    }

    public function logs()
    {
        return $this->hasMany(UsersLog::class,'log_user_id','id')->orderBy('log_id','DESC');
    }

    public function balance()
    {
        $add_payment    = $this->payments()->where('payment_type',1)->where('payment_token_confirmed',1)->sum('payment_value');
        $reduce_payment = $this->payments()->where('payment_type',2)->where('payment_token_confirmed',1)->sum('payment_value');
        return round($add_payment - $reduce_payment,2,PHP_ROUND_HALF_ODD);
    }

    public function diamonds(){
        return $this->hasMany(Diamond::class,'user_id','id');
    }

    public function totalDiamond()
    {
        $added   = $this->diamonds()->where('type',1)->where('diamond_token_confirmed',1)->sum('value');
        $reduced = $this->diamonds()->where('type',2)->where('diamond_token_confirmed',1)->sum('value');
        return $added - $reduced;
    }

    public function paycoin()
    {
        $added  = $this->hasMany(PayCoin::class,'user_id','id')->where('payment_type',1)->sum('coin_value');
        $reduce = $this->hasMany(PayCoin::class,'user_id','id')->where('payment_type',1)->sum('coin_value');
        return $added - $reduce;
    }

    public function totalActiveMissions()
    {
        return $this->hasMany(MissionHandle::class,'mission_user','id')->where('is_completed',0)->count('mission_handle_id');
    }

    public function ticketThreads()
    {
        return $this->hasMany(TicketsThread::class,'thread_user','id');
    }

    public function canCreateThread()
    {
        return $this->hasMany(TicketsThread::class,'thread_user','id')->where('is_closed',0)->count() <= 0;
    }


    public function orderUsersByBalance()
    {


        $users = User::all();
        $createIndex = [];
        foreach ($users as $user)
        {
            $createIndex[] = ['balance' => $user->balance(),'user_id' => $user->id];
        }
        usort($createIndex, "cmp");
        $balanceOrdered = array_reverse($createIndex);

        $onlyIds = array_map(function ($item){
            return $item['user_id'];
        },$balanceOrdered);



        $users = new User();
        return $users->whereIn('id',$onlyIds)->orderByRaw("FIELD(id,".implode(",",$onlyIds).")");

    }




}
