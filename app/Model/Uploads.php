<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

/**
 * Class Uploads
 * @package App\Model
 * @method static create($array = [])
 * @property $path
 * @property $type
 * @property $size
 * @property $user_id
 * @mixin Builder
 */
class Uploads extends Model
{
    protected $primaryKey = 'upload_id';
    protected $table = 'uploads';

    protected $fillable = ['path','user_id','size','type','by_admin'];

    public function update(array $attributes = [], array $options = [])
    {
        return parent::update($attributes, $options); // TODO: Change the autogenerated stub
    }

    public function getPathAttribute($path)
    {
        return env('APP_URL').'/'.$path;
    }

    protected function serializeDate($value) {
        return (new Carbon($value))->toDateTimeString();
    }

}