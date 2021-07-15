@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="card wg_shadow">
            <div class="card-content">
                <label>Soru ekle</label>

                <form action="{{ route('admin.missions.edit.post',['id' => $mission->mission_id]) }}" class="addQuestion" method="post">
                    @csrf
                    <textarea placeholder="Soruyu yazın." name="mission_question"
                              style="height: 120px">{{ $mission->mission_question }}</textarea>

                    <div class="d-flex">
                        <div style="width: 100%">
                            <label>Level</label>
                            <input type="number" name="mission_level" placeholder="Level" class="mr-1"
                                   value="{{ $mission->mission_level }}"/>
                        </div>
                        <div style="width: 100%">
                            <label class="ml-1" id="mission_xp">XP</label>
                            <input type="number" id="mission_xp" name="mission_xp" placeholder="XP" class="ml-1"
                                   value="{{ $mission->mission_xp }}">
                        </div>
                    </div>

                    <div class="field">
                        <label>Soru Değeri</label>
                        <input type="number" id="price" name="mission_value" placeholder="Değer ₺" step="any"
                               value="{{ $mission->mission_value }}"/>
                    </div>

                    <div class="field">
                        <label>Cevap süresi</label>
                        <input type="number" id="seconds" name="mission_second" value="{{ $mission->mission_second }}"
                               placeholder="Cevaplama süresi (saniye)"/>
                    </div>

                    <label>Cevap Ekle</label>
                    <div class="d-flex">
                        <input type="text" placeholder="Cevap" id="addAnswerInput">
                        <button class="btn btn_custom height-100" id="addAnswerFieldManual">Ekle</button>
                    </div>

                    <div id="question_answer_field" class="mt-2 mb-2">
                        @foreach(json_decode($mission->mission_question_answers,true) as $key => $value)

                            <div class="answer_list wg_shadow pd-2 mb-2 d-flex d-space-between">
                                <label><input class="with-gap checkAnswerItem" name="answer" type="radio"
                                              value="{{ $value }}" @if($key === $mission->correct_index) checked @endif/><span>{{ $value }}</span></label>
                                <a id="remove_answer" class="c-pointer">Sil</a>
                            </div>
                        @endforeach
                    </div>

                    <label>
                        <input type="checkbox" class="filled-in" id="is_custom" name="is_custom" @if($mission->is_question === 2) checked @endif/>
                        <span>Özel Görev</span>
                    </label><br>

                    <div class="@if($mission->is_question === 1) hidden @endif mt-2" id="extraQuestionArea">
                        <select name="type" id="" class="browser-default mb-1">
                            <option value="0" @if($mission->type === 0) selected @endif>Soru</option>
                            <option value="1" @if($mission->type === 1) selected @endif>Youtube Subscription</option>
                            <option value="2" @if($mission->type === 2) selected @endif>Youtube Views</option>
                            <option value="3" @if($mission->type === 3) selected @endif>Youtube Likes</option>
                            <option value="4" @if($mission->type === 4) selected @endif>Youtube Comments</option>
                            <option value="5" @if($mission->type === 5) selected @endif>Google play download/comment/star</option>
                            <option value="6" @if($mission->type === 6) selected @endif>Link</option>
                        </select>

                        <select name="partial_sending" id="" class="browser-default mb-1">
                            <option value="1" @if($mission->partial_sending === 1) selected @endif>Parçalı Gönderim</option>
                            <option value="2" @if($mission->partial_sending === 2) selected @endif>Direkt Gönderim</option>
                        </select>

                        <label>Kaç kullanıcı alabilsin</label>
                        <input type="number" value="{{ $mission->mission_take_limit }}" placeholder="Kaç kullanıcıya dağıtılsın" name="mission_take_limit" />

                        <input type="text" placeholder="intent link" name="intent_link" value="{{ $mission->intent_link }}" />
                        <blockquote>
                            <label><b>Youtube Subs: </b> <i class="red-text">UCDJXxpSMG7zy8SGj-zw3VMg</i>  (channel ID)</label><br>
                            <label><b>Youtube Views/Likes/Comments: </b> <i class="red-text">EL_qo2Ddxrc&list=RDCpCG3ClOzY4</i>  (play ID watch?v=HERE)</label><br>
                            <label><b>Google Play Download/Comment+Star</b> <i class="red-text">com.payquestion.payquest</i>  (packageName)</label>
                        </blockquote>
                    </div>

                    @if($youtube)
                        <div class="card wg_shadow center">
                            <div class="card-content">
                                <img src="{{ $youtube['info']['data']['picture'] }}" alt="" width="100px" class="round-full">
                                <h6>{{ $youtube['info']['data']['name'] }} - <b>{{ number_format($youtube['subs']['data']['subscribers']) }}</b> Abone</h6>
                            </div>
                        </div>
                    @endif
                    <div class="mt-2">
                        <label for="is_deleted">Bu soruyu yayından kaldırırsanız sonraki soru isteklerinde kullanıcıya gösterilmeyecek.</label>
                        <select name="is_deleted" id="is_deleted" class="browser-default">
                            <option value="0" @if($mission->is_deleted === 0) selected @endif>Yayında</option>
                            <option value="1" @if($mission->is_deleted === 1) selected @endif>Bu soruyu yayından kaldır</option>
                        </select>
                    </div>


                    <button type="submit" class="btn blue mt-2">Düzenle</button>
                </form>


            </div>
        </div>
    </div>
@endsection
