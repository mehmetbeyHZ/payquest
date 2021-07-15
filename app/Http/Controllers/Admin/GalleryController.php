<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function images()
    {
        $uploads = new Uploads();
        return $uploads->orderBy('upload_id','DESC')
            ->get();
    }
    public function _delete(Request $request)
    {
        request()->validate(['id' => 'required|numeric']);
        $item = Uploads::find($request->input('id'));
        $path = str_replace(env('APP_URL').'/storage/',"",$item->path);
        Storage::delete($path);
        $item->delete();
        return response()->json(['status' => 'ok', 'message' => 'Silindi', 'upload_id' => $request->input('id'),'path' => $path]);
    }
}
