<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MediaCountController extends Controller
{
    public function responseReporter(Request $request)
    {
        request()->validate([
            'mission_handle_id' => 'required|numeric',
            'url' => 'required',
            'response' => 'required',
            'count_type' => 'required|numeric'
        ]);

        $address = $request->input('url');
        if (strpos($address, "instagram.com/p/")) {
            return $this->instagramLikeResponse($request->input('response'));
        }

        return $this->instagramFollowResponse($request->input('response'));

    }

    public function instagramLikeResponse($response)
    {
        $data = json_decode($response, true);
        $count = $data['graphql']['shortcode_media']['edge_media_preview_like']['count'] ?? -1;
        if ($count > -1) {
            $this->initRedisCount($count);
            return response()->json([
                'status' => 'ok',
                'count' => $count
            ]);
        }
        return response()->json([
            'status' => 'fail',
            'message' => 'Özel görev bilgisi alınamadı, daha sonra tekrar deneyin.'
        ],400);
    }

    public function instagramFollowResponse($response)
    {
        $data = json_decode($response, true);
        $count = $data['graphql']['user']['edge_followed_by']['count'] ?? -1;
        if ($count > -1) {
            $this->initRedisCount($count);
            return response()->json([
                'status' => 'ok',
                'count' => $count
            ]);
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Özel görev bilgisi alınamadı, daha sonra tekrar deneyin.'
        ],400);
    }

    public function initRedisCount($count)
    {
        $scKey = 'sc_'.request('mission_handle_id').'_'.Auth::id();
        $ecKey = 'ec_'.request('mission_handle_id').'_'.Auth::id();
        if ((int)request('count_type') === 1)
        {
            Cache::put($scKey,$count,now()->addMinutes(10));
        }else{
            Cache::put($ecKey,$count,now()->addMinutes(10));
        }
    }







}
