<?php

namespace App\Model;

use App\Scopes\ActiveBankScope;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'bank';

    protected $primaryKey = 'bank_id';

    protected $hidden = ['created_at','updated_at','is_active'];

    protected static function booted()
    {
        static::addGlobalScope(new ActiveBankScope);
    }
}
