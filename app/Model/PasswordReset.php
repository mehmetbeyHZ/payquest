<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class PasswordReset
 * @package App\Model
 * @mixin Builder
 */
class PasswordReset extends Model
{
    public $table = 'password_resets';
    protected $fillable = [
      'user_id', 'reset_key', 'last_activation'
    ];
}
