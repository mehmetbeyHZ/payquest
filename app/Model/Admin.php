<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';
    protected $primaryKey = 'id';

    protected $fillable = [
      'username', 'password'
    ];

    protected $hidden = ['password'];
}
