@extends('layouts.app')
@section('content')

    <div class="card wg_shadow" style="margin:0!important;">
        <div class="card-content">
            <div class="row" style="margin-bottom: 0px!important;">
                <form action="" method="get">
                    <div class="col s12 m2">
                        <label>Arama Tipi</label>
                        <select name="type" class="browser-default">
                            <option value="mission_question"  @if(request('type') === "mission_question") selected @endif>Soru</option>
                            <option value="mission_question_answers" @if(request('type') === "mission_question_answers") selected @endif>Cevaplar</option>
                            <option value="mission_level" @if(request('type') === "mission_level") selected @endif>Level</option>
                            <option value="intent_link" @if(request('type') === "intent_link") selected @endif>Intent link</option>
                        </select>
                    </div>
                    <div class="col s12 m2">
                        <label>Yayın Durumu</label>
                        <select name="is_deleted" class="browser-default">
                            <option value="">Hepsi</option>
                            <option value="0"  @if(request('is_deleted') === "0") selected @endif>Yayında olan</option>
                            <option value="1" @if(request('is_deleted') === "1") selected @endif>Yayından kaldırılan</option>
                        </select>
                    </div>
                    <div class="col s12 m2">
                        <label>Soru Tipi</label>
                        <select name="is_question" class="browser-default">
                            <option value="" >Hepsi</option>
                            <option value="1" @if(request('is_question') === "1") selected @endif>Normal Soru</option>
                            <option value="2"  @if(request('is_question') === "2") selected @endif>Özel Görev</option>
                        </select>
                    </div>
                    <div class="col s12 m4">
                        <label>&nbsp;</label>
                        <input type="text" name="q" placeholder="Ara..." value="{{ request('q') }}">
                    </div>
                    <div class="col s12 m2">
                        <label>&nbsp;</label>
                        <button type="submit" class="height-100 btn blue block_btn">Ara</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div>
        <label>{{ $total }} sonuç listelendi</label>
    </div>

    <div>
        <div class="card wg_shadow mb-5">
            <div class="">
                <table class="responsive-table striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Soru</th>
                        <th>Cevaplar</th>
                        <th>XP</th>
                        <th>Level</th>
                        <th>Değer ₺</th>
                        <th>Eklenme</th>
                        <th>Ayarlar</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($missions as $mission)
                        <tr id="mission_item_{{$mission->mission_id}}">
                            <td>{{ $mission->mission_id }}</td>
                            <td>{{ $mission->mission_question }}</td>
                            <td>
                                @if($mission->is_question !== 1)
                                    <b>{{ \Illuminate\Support\Facades\Cache::get('pm_taken:'.$mission->mission_id) ?: 0 }}</b> / {{ $mission->mission_take_limit }} -
                                    <span class="green-text">{{ \Illuminate\Support\Facades\Cache::get('pm_taken_at_'.now()->format("d_H").$mission->mission_id,0) }}</span>
                                @endif
                                @foreach(json_decode($mission->mission_question_answers,true) as $key => $value)
                                    <a class="answer_item @if($mission->correct_index === $key) correct_answer @endif">{{ $value }}</a>
                                @endforeach
                            </td>
                            <td>{{ $mission->mission_xp }}</td>
                            <td>{{ $mission->mission_level }}</td>
                            <td>{{ $mission->mission_value }} ₺</td>
                            <td>{{ $mission->created_at }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.missions.edit',['id' => $mission->mission_id]) }}" class="answer_item">Düzenle</a>
                                    <a id="remove_question" data-route="{{ route('admin.missions.delete') }}" data-missionid="{{ $mission->mission_id }}" class="answer_item red white-text c-pointer">Sil</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $missions->appends(request()->input())->links('pagination.custom') }}
        </div>

    </div>
@endsection
