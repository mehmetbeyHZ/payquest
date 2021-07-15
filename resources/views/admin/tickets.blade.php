@extends('layouts.app')
@section('content')
    <div class="mb-5">

        <div class="card wg_shadow">
            <div class="card-content">
                <div class="row" style="margin-bottom: 0px!important;">
                    <form action="" method="get">
                        <div class="col s12 m2">
                            <select name="is_closed" class="browser-default">
                                <option value="">Hepsi</option>
                                <option value="0" @if(request('is_closed') === "0") selected @endif>Açık</option>
                                <option value="1"  @if(request('is_closed') === "1") selected @endif>Kapalı</option>
                            </select>
                        </div>
                        <div class="col s12 m2">
                            <select name="type" class="browser-default">
                                <option value="name"  @if(request('type') === "name") selected @endif>Ad Soyad</option>
                                <option value="title" @if(request('type') === "title") selected @endif>Başlık</option>
                                <option value="id" @if(request('type') === "id") selected @endif>Destek ID</option>
                            </select>
                        </div>
                        <div class="col s12 m1">
                            <select name="sort" class="browser-default">
                                <option value="thread_id"  @if(request('sort') === "thread_id") selected @endif>Tarihe Göre</option>
                                <option value="unseen_admin_count" @if(request('sort') === "unseen_admin_count") selected @endif>Okunmamış</option>
                            </select>
                        </div>
                        <div class="col s12 m5">
                            <input type="text" name="q" placeholder="Ara..." value="{{ request('q') }}">
                        </div>
                        <div class="col s12 m2">
                            <button type="submit" class="height-100 btn blue block_btn">Ara</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card wg_shadow">
            <table class="responsive-table striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Kullanıcı</th>
                    <th>Başlık</th>
                    <th>Durum</th>
                    <th>Oluşturulma Tarihi</th>
                    <th>Düzenle</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->thread_id }}</td>
                        <td class="d-flex">
                            <div class="avatar_loading_div">
                                <img src="" class="user_table_avatar lazy" data-original="{{ $ticket->user->avatar }}" alt="">
                            </div>
                            <a href="{{ route('admin.users.edit',['id' => $ticket->user->id]) }}" class="flex_user_table_name">{{ $ticket->user->name }}</a>
                        </td>
                        <td><a href="{{ route('admin.tickets.show',['id' => $ticket->thread_id]) }}">{{ $ticket->thread_title }}</a> @if($ticket->unseen_admin_count)   <span class="new badge green" data-badge-caption="mesaj">{{ $ticket->unseen_admin_count }}</span>@endif</td>
                        <td>
                            <select name="is_closed" id="ticketStatus" class="browser-default mt-1" data-action="{{ route('admin.tickets.status',['id' => $ticket->thread_id]) }}">
                                <option value="0" @if($ticket->is_closed === 0) selected="selected" @endif>Açık</option>
                                <option value="1" @if($ticket->is_closed === 1) selected="selected" @endif>Kapalı</option>
                            </select>
                        </td>
                        <td>{{ $ticket->created_at  }}</td>
                        <td>
                            <a href="{{ route('admin.tickets.show',['id' => $ticket->thread_id]) }}"
                               class="answer_item">Düzenle</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $tickets->appends(request()->input())->links('pagination.custom') }}

        </div>
    </div>
@endsection
