<?php


namespace App\Classes;


class YoutubeClient
{
    public function userInfo($channelId)
    {
        $infoData = (new \MClient\Request('https://counts.live/api/youtube-subscriber-count/'.$channelId.'/data'))
            ->addHeader('user-agent','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36')
            ->addHeader('sec-fetch-site','cross-site')
            ->addHeader('referer','https://akshatmittal.com/youtube-realtime/')
            ->execute()
            ->getDecodedResponse(true);

        $subsData = (new \MClient\Request('https://counts.live/api/youtube-subscriber-count/'.$channelId.'/live'))
            ->addHeader('user-agent','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36')
            ->addHeader('sec-fetch-site','cross-site')
            ->addHeader('referer','https://akshatmittal.com/youtube-realtime/')
            ->execute()
            ->getDecodedResponse(true);
        if (is_array($infoData) && is_array($subsData)){
            return [
                'info' => $infoData,
                'subs' => $subsData
            ];
        }
        return null;

    }
}
