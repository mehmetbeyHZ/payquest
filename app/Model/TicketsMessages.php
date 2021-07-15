<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class TicketsMessages
 * @package App\Model
 * @mixin Builder
 */
class TicketsMessages extends Model
{
    protected $table = 'tickets_messages';
    protected $primaryKey = 'message_id';
    protected $fillable = [
        'thread_id', 'message', 'receiver', 'sender', 'sender_is_admin', 'is_seen'
    ];



    protected function serializeDate(\DateTimeInterface $date) {
        return Carbon::parse($date->getTimestamp())->format('d.m.Y H:i:s');
    }

    public function unseen_total_admin()
    {
        return $this->where('is_seen_by_admin',0)
            ->select('thread_id', DB::raw('count(*) as unseen_count'))
            ->groupBy('thread_id')
            ->get()->count();
    }



}
