<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Class TicketsThread
 * @package App\Model
 * @mixin Builder
 */
class TicketsThread extends Model
{
    protected $table = 'tickets_thread';
    protected $primaryKey = 'thread_id';
    protected $fillable = [
        'thread_title', 'thread_user', 'is_closed'
    ];


    public function messages()
    {
        return $this->hasMany(TicketsMessages::class,'thread_id','thread_id')->orderBy('message_id','ASC');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','thread_user');
    }

    public function unseen()
    {
        return $this->messages()->where('is_seen',0)->where('sender_is_admin',1);
    }

    public function unseenAdmin()
    {
        return $this->messages()->where('is_seen_by_admin',0)->where('sender_is_admin',0);
    }


    public function unSeenByAdmin()
    {
        return $this->hasMany(TicketsMessages::class,'thread_id')
            ->where('is_seen_by_admin',0)
            ->where('receiver',0)
            ->where('sender_is_admin',0)
            ->count();
    }

    protected function serializeDate(\DateTimeInterface $date) {
        return Carbon::parse($date->getTimestamp())->format('d.m.Y H:i:s');
    }

}
