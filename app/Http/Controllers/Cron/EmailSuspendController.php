<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailSuspendController extends Controller
{
    public function suspend()
    {
//
//        $connection = imap_open('{mail.payquestion.com:993/imap/ssl/novalidate-cert}INBOX', 'support@payquestion.com', '*Mehmet2x*') or die('Cannot connect to Gmail: ' . imap_last_error());
//        $emails = imap_search($connection,"UNSEEN");
//        $suspendMails = [];
//        if(!empty($emails)){
//
//            foreach($emails as $email){
//                $overview = imap_fetch_overview($connection, $email);
//                $overview = $overview[0];
//                $message   = imap_fetchbody($connection, $email, 1);
//                if ($overview->subject === "Undelivered Mail Returned to Sender")
//                {
//                    preg_match_all("@<(.*?)>:@si",$message,$mathes);
//                    $suspendMails[] = $mathes[1][0];
//                }
//            }
//        }
//
//        $users = new User();
//        $users->whereIn('email',$suspendMails)->update(['is_banned' => 1]);
//        return $suspendMails;

    }
}
