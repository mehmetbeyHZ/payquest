<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Model\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function get($slug)
    {
        $blog = new Blog();
        $content = $blog->where('is_published',1)->where('slug',$slug)->first();
        if (!$content):
            return abort(404);
        endif;

        return view('blog.view',['blog' => $content]);
    }
}
