<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class XP
 * @package App\Model
 * @property $value
 * @property $description
 * @property $user_id
 * @mixin Builder
 */
class XP extends Model
{
    protected $table = 'xp';
    protected $primaryKey = 'xp_id';
    protected $fillable = [
        'value','user_id','description'
    ];

}
