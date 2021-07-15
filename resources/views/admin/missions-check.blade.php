@extends('layouts.app')

@section('content')
    <style>
        [type="checkbox"]+span:not(.lever){
            width: 0px!important;
            height: 20px!important;
        }
    </style>

    <div class="card wg_shadow">
            <div class="card-content">
                <div class="row">
                    <form action="" method="get">
                        <div class="col s4 m4">
                            <select name="type" class="browser-default">
                                <option value="name" {{ request('type') === "name" ? "selected" : null }}>Ad Soyad</option>
                                <option value="email" {{ request('type') === "email" ? "selected" : null }}>Email</option>
                                <option value="ref_code" {{ request('type') === "ref_code" ? "selected" : null }}>Referans Kodu</option>
                            </select>
                        </div>
                        <div class="col s7 m7">
                            <input type="text" placeholder="Ara..." name="q" value="{{ request('q') }}">
                        </div>
                        <div class="col s1 m1">
                            <button type="submit" class="btn blue">Ara</button>
                        </div>
                    </form>
                </div>
            </div>
    </div>

    <div class="card wg_shadow sticky_item">
        <div class="card-content">

            <div class="d-flex d-space-between">
                <div style="margin-top: auto; margin-bottom: auto">
                    <label>Görevleri Güncelle</label>
                </div>

                <a class="dropdown-trigger btn" style="background: #433efe" href="#" data-target="actionMissionStatus"><i class="material-icons right">expand_more</i>Görevleri Güncelle</a>
                <ul id="actionMissionStatus" class="dropdown-content" tabindex="0">
                    <li><a  id="mission_check_confirm" data-type="1" data-route="{{ route('admin.missions.check.post') }}"><i class="material-icons">check</i>Onayla</a></li>
                    <li><a  id="mission_check_confirm" data-type="2" data-route="{{ route('admin.missions.check.post') }}"><i class="material-icons">cancel</i>Reddet</a></li>
                </ul>
            </div>

        </div>
    </div>
    <div class="mb-5">
        <div class="card wg_shadow">

            <table class="responsive-table striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th class="center">
                        <label>
                            <input type="checkbox" id="select_all_missions" />
                            <span></span>
                        </label>
                    </th>
                    <th>Kullanıcı</th>
                    <th>REF</th>
                    <th>Görev</th>
                    <td>Link</td>
                    <th>Tarih</th>
                    <th>Durum</th>
                </tr>
                </thead>
                <tbody>
                @foreach($missions as $mission)
                    <tr id="check_{{ $mission->mission_handle_id }}">
                        <td>{{ $mission->mission_handle_id }}</td>
                        <td class="center">
                            <label>
                                <input type="checkbox" class="mission_check" data-id="{{ $mission->mission_handle_id }}" />
                                <span></span>
                            </label>
                        </td>
                        <td><div class="d-flex"><img src="{{ $mission->user->avatar }}" class="user_table_avatar" alt="">
                                <a href="{{ route('admin.users.edit',[$mission->user->id]) }}" class="flex_user_table_name">{{ $mission->user->name }}</a>
                            </div></td>
                        <td>{{ $mission->user->ref_code }}</td>
                        <td>
                            @if($mission->mission_detail)
                                {{ $mission->mission_detail->mission_question }}
                            @else
                                Görev Bulunamadı
                            @endif
                        </td>
                        <td>
                            @if($mission->mission_detail)
                                {{ $mission->mission_detail->intent_link }}
                            @else
                                Görev Bulunamadı
                            @endif
                        </td>
                        <td>{{ $mission->created_at }}</td>
                        <td class="d-flex">
                            <a id="changeMCStatus" class="answer_item c-pointer" data-id="{{ $mission->mission_handle_id }}" data-route="{{ route('admin.missions.check') }}" data-type="1">Onayla</a>
                            <a id="changeMCStatus" class="answer_item red white-text c-pointer" data-id="{{ $mission->mission_handle_id }}" data-route="{{ route('admin.missions.check') }}" data-type="2">IPTAL</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $missions->appends(request()->input())->links('pagination.custom') }}
        </div>
    </div>
@endsection
