<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class UsersLog
 * @package App\Model
 * @property $log_user_id
 * @property $log_name
 * @property $log_text
 * @property $log_mission_handle_id
 * @mixin Builder
 */
class UsersLog extends Model
{
    protected $table = 'users_log';

    protected $primaryKey = 'log_id';

    protected $fillable = ['log_user_id','log_name','log_text','log_mission_handle_id'];

}
