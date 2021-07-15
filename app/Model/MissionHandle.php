<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Class MissionHandle
 * @package App\Model
 * @mixin Builder
 */
class MissionHandle extends Model
{
    protected $table = 'mission_handle';

    protected $primaryKey = 'mission_handle_id';

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = ['real_mission_id', 'mission_user', 'is_completed', 'viewed_at'];

    /**
     * @return HasOne
     * @noinspection PhpUnused
     */
    public function mission_detail(): HasOne
    {
        return $this->hasOne(Mission::class, 'mission_id', 'real_mission_id');
    }

    public function hasAwaitingMission($authId): bool
    {
        return $this->where('mission_user', $authId)->where('is_completed', 0)->count('mission_handle_id') > 0;
    }

    public function getAwaitingMissionRedis($authId)
    {
        if (Cache::has('user_mission_awaiting:'.$authId)):
            return Cache::get('user_mission_awaiting:'.$authId);
        else:
            $total = $this->where('mission_user', $authId)->where('is_completed', 0)->count();
            $this->setAwaitingMissionRedis($authId,$total);
            return $total;
        endif;
    }

    public function setAwaitingMissionRedis($authId,$totalAwaiting)
    {
        Cache::put('user_mission_awaiting:'.$authId,$totalAwaiting,now()->addHours(2));
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'mission_user');
    }

    public function alreadyTakenMissions($authId): array
    {
        return $this->where('mission_user', $authId)->pluck('real_mission_id')->toArray();
    }

    public function alreadyTakenGetOrSaveRedis($userId, $extraIds = [])
    {
        if (Cache::has('user_taken_missions_data:' . $userId)):
            $previous = Cache::get('user_taken_missions_data:' . $userId);
            if (count($extraIds) > 0):
                $merge = array_merge($previous, $extraIds);
                Cache::put('user_taken_missions_data:' . $userId, $merge, now()->addDays(2));
                return $merge;
            endif;
            return $previous;
        else:
            $takenFromDB = $this->alreadyTakenMissions($userId);
            Cache::put('user_taken_missions_data:' . $userId, $takenFromDB, now()->addDays(2));
            return $takenFromDB;
        endif;

    }

    public function newMissions($authId, $limit = 10): array
    {
        $user = User::find($authId);
        $ids = $this->alreadyTakenGetOrSaveRedis($authId);
        $mission = new Mission();
        $all = $mission->whereNotIn('mission_id', $ids)
            ->where('is_deleted', 0)
            ->where('mission_take_limit', 0)
            ->where('is_question', 1)
            ->where('mission_level', xpToLevel($user->totalXP()))
            ->get()
            ->toArray();


        $giveForUser = [];
        $giveEnd     = [];
        $leastPMList = [];
        $date        = now();
        $PMGiveLimit = 4;
        $takeUniq    = now()->format("d_H");
        $hourlyTake  = 25;
        if (xpToLevel($user->xp_cache)['level'] >= 1):


            $privateMissions = (new Mission())->where('intent_link', '!=', null)
                ->where('is_deleted', 0)
                ->whereNotIn('mission_id', $ids)
                ->orderBy('mission_id','ASC')
                ->get();



            foreach ($privateMissions as $pm):
                $totalTakeInHour = Cache::get("pm_taken_at_{$takeUniq}".$pm->mission_id,0);

                if (!Cache::has('pm_taken:' . $pm->mission_id)):
                    Cache::put('pm_taken:' . $pm->mission_id, 0, now()->addDays(5));
                endif;

                $totalTaken = Cache::get('pm_taken:' . $pm->mission_id);
                if ($totalTaken >= $pm->mission_take_limit):
                    $giveEnd[] = $pm->mission_id;
                else:
                    if ($pm->partial_sending === 1){
                        if ($totalTakeInHour < $hourlyTake):
                            $giveForUser[] = ['real_mission_id' => $pm->mission_id, 'mission_user' => $user->id, 'is_completed' => 0, 'created_at' => $date, 'updated_at' => $date];
                        endif;
                    }else{
                        $giveForUser[] = ['real_mission_id' => $pm->mission_id, 'mission_user' => $user->id, 'is_completed' => 0, 'created_at' => $date, 'updated_at' => $date];
                    }
                endif;
            endforeach;

            if (count($giveForUser) > 0):
                $PMGiveLimit = count($giveForUser) >= $PMGiveLimit ? $PMGiveLimit : count($giveForUser);
                $selectRM = array_rand($giveForUser, $PMGiveLimit);
                $selectRM = is_array($selectRM) ? $selectRM : [$selectRM];
                foreach ($selectRM as $key):
                    $item       = $giveForUser[$key];
                    $totalTaken      = Cache::get('pm_taken:' . $item['real_mission_id'],0);
                    $totalTakeInHour = Cache::get("pm_taken_at_{$takeUniq}".$item['real_mission_id'],0);

                    Cache::put("pm_taken_at_{$takeUniq}".$item['real_mission_id'],$totalTakeInHour + 1,now()->addMinutes(90));
                    Cache::put('pm_taken:' . $item['real_mission_id'], $totalTaken + 1,now()->addDays(5));
                    $leastPMList[] = $item;
                endforeach;
            endif;

            $giveForUser = $leastPMList;

            if (count($giveEnd) > 0) {
                Mission::whereIn('mission_id', $giveEnd)->update(['is_deleted' => 1]);
            }


        endif;

        $limit = count($all) >= $limit ? $limit : count($all);

        if (count($all) <= 0) {
            return $giveForUser;
        }

        $rndKeys = array_rand($all, $limit);
        $rndKeys = is_array($rndKeys) ? $rndKeys : [$rndKeys];
        $myData = [];
        foreach ($rndKeys as $key) {
            $currentItem = $all[$key];
            $myData[] = [
                'real_mission_id' => (int)$currentItem['mission_id'],
                'mission_user' => (int)$authId,
                'is_completed' => 0,
                'created_at' => $date,
                'updated_at' => $date
            ];
        }
        $currentItems = array_merge($myData, $giveForUser);

        if (count($currentItems) > 0):
            $map = array_map(static function ($item) {
                return $item['real_mission_id'];
            }, $currentItems);
            $this->alreadyTakenGetOrSaveRedis(Auth::id(), $map);
        endif;

        return $currentItems;
    }

}
