<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Blog;
use App\Model\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{

    public function _upload(Request $request)
    {
        Validator::make($request->all(),['image' => 'required|image'])->validate();

        $image = request()->file('image');
        $store = $image->store('uploads');
        Uploads::create(['path' => 'storage/'.$store, 'user_id' => Auth::guard('admin')->id(), 'size' => $image->getSize(), 'type' => $image->getMimeType(),'by_admin' => 1]);

        return response()->json(['status' => 'ok','message' => 'uploaded','url' => env('APP_URL').'/storage/'.$store]);
    }

    public function blogs(Request $request)
    {
        $blogs = Blog::with('blog_author');

        if ($request->input('type') && in_array($request->input('type'),['title','text'])):
             $blogs->where(request('type'),'LIKE',"%".request('q')."%");
        endif;

        return view('admin.blogs', ['blogs' => $blogs->orderBy('id','DESC')->paginate(50)]);
    }

    public function add()
    {
        return view('admin.blogs-add');
    }

    public function _add(Request $request)
    {
        Validator::make($request->all(), [
            'title' => 'required',
            'text' => 'required',
            'is_published' => 'required'
        ])->validate();

        Blog::create([
            'image' => request('image'),
            'title' => request('title'),
            'slug' => Str::slug(request('title')),
            'text' => request('text'),
            'is_published' => (int)request('is_published'),
            'author' => Auth::guard('admin')->id()
        ]);
        return response()->json(['status' => 'ok', 'message' => 'Blog eklendi']);
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs-edit', ['blog' => $blog]);
    }

    public function _edit(Request $request,$id)
    {
        Validator::make($request->all(), [
            'title' => 'required',
            'text' => 'required',
            'is_published' => 'required'
        ])->validate();

        $blog = Blog::findOrFail($id);
        $update = $blog->update([
            'image' => request('image'),
            'title' => request('title'),
            'slug' => Str::slug(request('title')),
            'text' => request('text'),
            'is_published' => (int)request('is_published'),
        ]);
        if ($update):
            return response()->json(['status' => 'ok','message' => 'Blog güncellendi']);
        endif;
        return response()->json(['status' => 'ok','message' => 'Blog güncellenemedi!'],400);
    }
}
