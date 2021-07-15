@extends('layouts.app')

@section('content')
    <div class="mb-5">


        <div class="card wg_shadow">
            <div class="card-content">
                <div class="row" style="margin-bottom: 0px!important;">
                    <form action="" method="get">
                        <div class="col s12 m3">
                            <select name="type" class="browser-default">
                                <option value="name"  @if(request('type') === "name") selected @endif>Ad Soyad</option>
                                <option value="email"  @if(request('type') === "email") selected @endif>Email</option>
                                <option value="mission_id" @if(request('type') === "mission_id") selected @endif>Soru ID</option>
                                <option value="mission_handle_id" @if(request('type') === "mission_handle_id") selected @endif>Alınan soru ID</option>
                            </select>
                        </div>
                        <div class="col s12 m7">
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
                    <th>Görev</th>
                    <th>Cevaplama</th>
                    <th>Tarih</th>
                </tr>
                </thead>
                <tbody>
                @foreach($missions as $mission)
                    <tr>
                        <td>{{ $mission->mission_handle_id }}</td>
                        <td><div class="d-flex">
                                <div class="avatar_loading_div">
                                    <img src="" class="user_table_avatar lazy" data-original="{{ $mission->user->avatar }}" alt="">
                                </div>
                                <a href="{{ route('admin.users.edit',[$mission->user->id]) }}" class="flex_user_table_name">{{ $mission->user->name }}</a>
                            </div>
                        </td>
                        <td>
                            @if($mission->mission_detail)
                                <a class="black-text" href="{{ route('admin.missions.edit',[$mission->mission_detail->mission_id]) }}">{{ $mission->mission_detail->mission_question }}</a>
                            @else
                                Görev Bulunamadı
                            @endif
                        </td>
                        <td>{{ mission_status($mission->is_completed) }}</td>
                        <td>{{ $mission->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if($with_paginate === true)
                {{ $missions->appends(request()->input())->links('pagination.custom') }}
            @endif
        </div>
    </div>
@endsection
