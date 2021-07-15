<?php


namespace App\Classes;


use MClient\Request;

class FirebaseCloudMessaging
{
    public const AUTH_TOKEN = "AAAAyDADHH4:APA91bEcPiij-0kQUQNYAIZ_sBlYN3afeP2n4fZD-Zlt0aRIrRneJ9KsUzU2pAn01WS7xDB2JBclJR3WqWLKtWGgfOEXPResnIWVTf_K4gEFJU653OiDF6A8RsIy402GcZGqd4pWLTBA";

    public function sendNotification($to,$title,$body)
    {
        return (new Request('https://fcm.googleapis.com/fcm/send'))
            ->addHeader('Content-Type','application/json')
            ->addHeader('Authorization','key='.self::AUTH_TOKEN)
            ->addPost('to',$to)
            ->addPost('notification',[
                'title' => $title,
                'body' => $body
            ])
            ->setJsonPost(true)
            ->execute()
            ->getResponse();
    }

    public function sendMultiple($title,$body,$registerIds = [])
    {
        return (new Request('https://fcm.googleapis.com/fcm/send'))
            ->addHeader('Content-Type','application/json')
            ->addHeader('Authorization','key='.self::AUTH_TOKEN)
            ->addPost('registration_ids',$registerIds)
            ->addPost('notification',[
                'title' => $title,
                'body' => $body
            ])
            ->setJsonPost(true)
            ->execute()
            ->getResponse();
    }

    public function sendTopicsAll($title,$body)
    {
        return $this->sendNotification('/topics/all',$title,$body);
    }

}
