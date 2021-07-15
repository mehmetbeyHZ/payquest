<?php


namespace App\Model;
use Jenssegers\Mongodb\Eloquent\Model;


class MongoTester extends Model
{
    public $connection = 'mongodb';
    public $collection = 'users_log';
    public $fillable = [
      'log_name'
    ];
}
