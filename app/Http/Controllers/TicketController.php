<?php

namespace App\Http\Controllers;

use App\Model\Diamond;
use App\Model\TicketsMessages;
use App\Model\TicketsThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function createThread(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'thread_title' => 'required',
            'thread_message' => 'required'
        ]);

        return response()->json(['status'=>'fail','message' => 'Şuanda destek talebi oluşturamazsınız.'],400);

        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()], 400);
        endif;

        $user = Auth::user();

        if (!$user->canCreateThread()):
            return response()->json(['status' => 'fail', 'message' => 'Önceki destek talebinizin cevaplandırılmasını bekleyin.'], 400);
        endif;

        if ($user->totalDiamond() < 10):
            return response()->json(['status' => 'fail', 'message' => 'Destek talebi için 10 diamond gereklidir.'],400);
        endif;

        $d = new Diamond();
        $d->reduceDiamond($user->id, 10, md5(time()));

        $thread = TicketsThread::create(['thread_title' => request('thread_title'), 'thread_user' => $user->id, 'is_closed' => 0]);
        TicketsMessages::create(['thread_id' => $thread->thread_id, 'sender' => $user->id, 'receiver' => 0, 'message' => request('thread_message')]);
        return response()->json(['status' => 'ok', 'message' => 'Destek talebi oluşturuldu']);
    }

    public function createMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'thread_id' => 'required'
        ]);
        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => 'Mesaj alanı zorunludur.'], 400);
        endif;

        $thread = TicketsThread::find(request('thread_id'));
        if (!$thread || $thread->thread_user !== Auth::id()):
            return response()->json(['status' => 'fail', 'message' => 'Bu destek talebine artık cevap veremezsin. Lütfen yenisini oluştur.'], 400);
        endif;

        if ($thread->is_closed === 1):
            return response()->json(['status' => 'fail', 'message' => 'Bu talep kapatıldı. Yeni bir tane oluşturun.'], 400);
        endif;

        TicketsMessages::create([
            'thread_id' => request('thread_id'),
            'message' => request('message'),
            'sender' => Auth::id(),
            'receiver' => 0
        ]);
        return response()->json(['status' => 'fail', 'message' => 'Mesaj Gönderildi.']);
    }

    public function getThreads()
    {
        $thread = TicketsThread::withCount('unseen')
            ->where('thread_user', Auth::id())
            ->orderBy('thread_id', 'DESC')
            ->get();

        return response()->json([
            'status' => 'ok',
            'total_unseen' => array_sum(array_column($thread->toArray(), 'unseen_count')),
            'threads_list' => $thread
        ]);
    }

    public function threadDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'thread_id' => 'required'
        ]);

        if ($validator->fails()):
            return response()->json(['status' => 'fail', 'message' => $validator->errors()->first()], 400);
        endif;

        $tread = TicketsThread::with('messages')
            ->where('thread_id', request('thread_id'))
            ->where('thread_user', Auth::id())
            ->first();

        if (!$tread) {
            return response()->json(['status' => 'fail', 'message' => 'Bu içeriği görmek için yetkiniz yok!'], 400);
        }

        $messages = new TicketsMessages();
        $messages->where('thread_id', request('thread_id'))->update(['is_seen' => 1]);

        return response()->json(['status' => 'ok', 'thread_messages' => $tread->messages]);

    }

    public function ticketInformations()
    {
        return [
            [
                'image' => 'https://i.hizliresim.com/Xb7Pyn.png',
                'title' => 'DESTEK TALEBI 10 DIAMOND',
                'text' => "Her destek talebi için 10 diamond gereklidir.",
                'route' => null
            ],
            [
                'image' => 'https://i.hizliresim.com/Xb7Pyn.png',
                'title' => 'SIKÇA SORULAN SORULAR',
                'text' => "Bu alana tıklayınız. Bu alanda yer alan cevaplar destek talebinde aynı şekilde iletilir.",
                'route' => "https://www.payquestion.com/sss"
            ]
        ];
    }


}
