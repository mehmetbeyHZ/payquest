@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card wg_shadow">
            <div class="chat_header pd-2 mt-2 d-flex d-space-between" style="background: #f7f7f7">
                 <div class="d-flex">
                     <a href="{{ route('admin.users.edit',['id' => $ticket->user->id]) }}">
                         <img src="{{ $ticket->user->avatar }}" class="user_table_avatar" alt="">
                         <a class="flex_user_table_name black-text" href="{{ route('admin.users.edit',['id' => $ticket->user->id]) }}">{{ $ticket->user->name }}</a> &nbsp;
                     </a>
                 </div>
                <a class="black-text" style="margin-top: auto;margin-bottom: auto"><b>{{ $ticket->thread_title }}</b></a>

            </div>
            <div class="chatbox">
                @foreach($tp as $message)
                    <div class="@if ($message->sender_is_admin === 1) me @else you @endif">
                        {{ $message->message }}<br>
                        <label>{{ $message->created_at }}</label>
                        @if($message->sender_is_admin === 1)
                            <label>@if($message->is_seen === 1) <b>Görüldü!</b> @else Görülmedi @endif</label>
                        @endif
                    </div>
                @endforeach
                    <div class="strike" style="float: left!important; width: 100%!important;">
                        <span>Önceki Mesajlar</span>
                    </div>
                @foreach($ticket->messages as $message)
                    <div class="@if ($message->sender_is_admin === 1) me @else you @endif">
                        {{ $message->message }}<br>
                        <label>{{ $message->created_at }}</label>
                        @if($message->sender_is_admin === 1)
                            <label>@if($message->is_seen === 1) <b>Görüldü!</b> @else Görülmedi @endif</label>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <script>
            {{--$(function (){--}}
            {{--   request(null,{address:"{{ route('admin.tickets.previous') }}", data : {client_id:"{{ $ticket->user->id }}", thread_id:"{{ $ticket->thread_id }}"}},previous_ticket_callback)--}}

            {{--});--}}
            {{--function previous_ticket_callback(xhr)--}}
            {{--{--}}
            {{--    console.log(xhr);--}}
            {{--}--}}
        </script>


        <div class="card wg_shadow mt-2">
            <div class="card-content">
                <div class="answer">
                    <form action="{{ route('admin.tickets.answer',['id' => $ticket->thread_id]) }}" class="submit">
                        @csrf
                        <input type="hidden" name="thread_id">
                        <textarea id="message_area" class="autocomplete" name="message" cols="30" rows="10" placeholder="Mesaj" style="height: 80px"></textarea>
                        <button type="submit" class="btn blue block_btn">Gönder</button>
                    </form>
                </div>
                <select name="is_closed" id="ticketStatus" class="browser-default mt-1" data-action="{{ route('admin.tickets.status',['id' => $ticket->thread_id]) }}">
                    <option value="0" @if($ticket->is_closed == 0) selected="selected" @endif>Açık</option>
                    <option value="1" @if($ticket->is_closed == 1) selected="selected" @endif>Kapalı</option>
                </select>
                <br>
                <div id="autocomplete_area"></div>
            </div>
        </div>
    </div>


    <script>
        $(".chatbox").scrollTop($(".chatbox")[0].scrollHeight);
        Array.prototype.toLowerCase = function() {
            for (var i = 0; i < this.length; i++) {
                this[i] = this[i].toString().toLowerCase();
            }
        }

        $(function (){
            let items = [
                "Lütfen güncellenmesini istediğiniz adınızı içeren yeni bir destek talebi oluşturun",
                "Lütfen www.payquestion.com/sss alanını okuyunuz.",
                "60 Gün içerisinde ödeme bildiriminde bulunmayan her kullanıcının bakiyesi sıfırlanır.",
                "Uygulamamızda her hafta düzenli olarak yeni sorular eklenir.",
                "Ödeme Limiti: 200₺ ve üzeri her bakiyenizi çekebilirsiniz.",
                "Özel görevler ödeme bildiriminiz sırasında kontrol edilir ve sponsorlarımızdan aldığımız bilgilerle görevi tamamlayıp tamamlamadığınızı kontrol eder ve durumunuza göre bakiyeniz güncellenir.",
                "Referans, uygulamaya bizzat sizin davet ettiğiniz yakın arkadaşınız, aileniz veya herhangi bir bireydir. Uygulamayı indirip kayıt olan kullanıcı kayıt sırasında sizin referans kodunuzu kullanarak kaydolursa referans sayınız 1 artacaktır. 1 kullanıcı en fazla 1 kişinin referans kodunu girebilir.",
                "ininal karta ödememiz yoktur."
            ];
            let items_lower = items.map(item => item.toLowerCase());

            $("textarea.autocomplete").on('keyup',function (){
                let searchKey = $(this).val();
                let parseAllLines = searchKey.split(" ");
                $("div#autocomplete_area").html('');
                let putted = [];

                parseAllLines = parseAllLines.filter(function (el) {
                    return el !== "" && el.length >= 3;
                });

                console.log(parseAllLines);

                for(let i = 0; i < items.length; i++)
                {
                    let item = items[i];
                    for(let j = 0; j < parseAllLines.length; j++)
                    {
                        if ((item.toLowerCase()).search(parseAllLines[j].toLowerCase()) !== -1)
                        {
                            if(putted.indexOf(item) === -1)
                            {
                                $("div#autocomplete_area").append(`<br><div id="inject_message" data-message="${item}" class="chip c-pointer" style="height: auto!important;">${item}</div>`);
                                putted.push(item);
                            }
                        }
                    }
                }
            });

            $("body").delegate('div#inject_message','click',function (){
                $("textarea#message_area").val($(this).attr('data-message'));
            })

        })
    </script>

@endsection
