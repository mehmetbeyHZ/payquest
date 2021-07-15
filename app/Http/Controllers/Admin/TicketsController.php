<?php

namespace App\Http\Controllers\Admin;

use App\Classes\FirebaseCloudMessaging;
use App\Http\Controllers\Controller;
use App\Model\FcmToken;
use App\Model\TicketsMessages;
use App\Model\TicketsThread;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TicketsController extends Controller
{
    public function tickets()
    {
        $tickets = TicketsThread::withCount('unseenAdmin')->with('user')
            ->orderBy(request('sort','thread_id'),'DESC');

        if (request('q') && request('type') === "name"):
            $user = User::where('name','LIKE',"%".request('q')."%")->pluck('id')->toArray();
            if ($user):
                $tickets = $tickets->whereIn('thread_user',$user);
            endif;
        endif;

        if (is_numeric(request('is_closed'))):
            $tickets = $tickets->where('is_closed',(int)request('is_closed'));
        endif;

        if (request('q') && request('type') === "title"):
            $tickets = $tickets->where('thread_title','LIKE','%'.request('q').'%');
        endif;

        if (request('q') && request('type') === "id"):
            $tickets = $tickets->where('thread_id',request('q'));
        endif;

        return view('admin.tickets',['tickets' => $tickets->paginate(50)]);
    }

    public function showTicket($id)
    {
        TicketsMessages::where('thread_id',$id)->update(['is_seen_by_admin' => 1]);
        $ticket = TicketsThread::with('messages')->with('user')->findOrFail($id);
        $ticketPrevious = (new TicketsMessages())->where('thread_id','!=',$id)->whereIn('sender',[$ticket->user->id,0])->whereIn('receiver',[$ticket->user->id,0])->get();
        return view('admin.tickets-show',['ticket' => $ticket,'tp' => $ticketPrevious]);
    }

    public function previousReload()
    {
        request()->validate(['thread_id' => 'required', 'client_id' => 'required']);
        return (new TicketsMessages())->where('thread_id','!=',request('thread_id'))->whereIn('sender',[request('client_id'),0])->whereIn('receiver',[request('client_id'),0])->get();
    }

    public function answerTicket($id,Request $request)
    {
        $validator = Validator::make($request->all(),[
           'message' => 'required'
        ]);

        if ($validator->fails()):
            return response()->json(['status' => 'fail','message' => $validator->errors()->first()],400);
        endif;

        $thread = TicketsThread::find($id);

        TicketsMessages::create([
            'thread_id' => $id,
            'message' => request('message'),
            'receiver' => $thread->thread_user,
            'sender' => 0,
            'sender_is_admin' => 1
        ]);


        $fcm = FcmToken::where('user_id',$thread->thread_user)->first();
        if ($fcm)
        {
            (new FirebaseCloudMessaging())->sendNotification($fcm->fcm_token,"Destek","Destek talebiniz yanıtlandı, ana menüden ulaşabilirsiniz.");
        }

        return response()->json(['status' => 'ok', 'message' => 'Ticket gönderildi','redirect' => route('admin.tickets.show',['id' => $id])]);

    }

    public function changeStatus($id,Request $request)
    {
        $validator = Validator::make($request->all(),[
            'is_closed' => 'required|numeric'
        ]);

        if ($validator->fails()):
            return response()->json(['status' => 'fail','message' => $validator->errors()->first()],400);
        endif;

        TicketsThread::find($id)->update([
           'is_closed' => request('is_closed')
        ]);

        return response()->json(['status' => 'ok','message' => 'Güncellendi.']);

    }
}
