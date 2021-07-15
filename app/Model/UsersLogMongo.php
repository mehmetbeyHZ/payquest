<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class UsersLogMongo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'users_log';
    protected $fillable = [
      'log_user_id','log_name','log_text','log_mission_handle_id'
    ];
}
