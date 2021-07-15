<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class Blog
 * @package App\Model
 * @mixin Builder
 */
class Blog extends Model
{
    protected $table = 'blogs';
    protected $fillable = [
        'slug', 'image','title','text','author','is_published'
    ];

    public function blog_author()
    {
        return $this->hasOne(Admin::class,'id','author');
    }

}
