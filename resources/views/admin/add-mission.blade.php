@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="card wg_shadow">
            <div class="card-content">
                <label>Soru ekle</label>

                <form action="{{ route('admin.missions.add.post') }}" class="addQuestion" method="post">
                    @csrf
                    <textarea placeholder="Soruyu yazın." name="mission_question" style="height: 120px"></textarea>

                    <div class="d-flex">
                        <div style="width: 100%">
                            <label>Level</label>
                            <input type="number" name="mission_level" placeholder="Level" class="mr-1"/>
                        </div>
                        <div style="width: 100%">
                            <label class="ml-1" id="mission_xp">XP</label>
                            <input type="number" id="mission_xp" name="mission_xp" placeholder="XP" class="ml-1">
                        </div>
                    </div>

                    <div class="field">
                        <label>Soru Değeri</label>
                        <input type="number" id="price" name="mission_value" placeholder="Değer ₺" step="any"/>
                    </div>

                    <div class="field">
                        <label>Cevap süresi</label>
                        <input type="number" id="seconds" name="mission_second" placeholder="Cevaplama süresi (saniye)"/>
                    </div>

                    <label>Cevap Ekle</label>
                    <div class="d-flex">
                        <input type="text" placeholder="Cevap" id="addAnswerInput">
                        <button class="btn btn_custom height-100" id="addAnswerFieldManual">Ekle</button>
                    </div>

                    <div id="question_answer_field" class="mt-2 mb-2">

                    </div>

                    <label>
                        <input type="checkbox" class="filled-in" name="is_custom" id="is_custom" />
                        <span>Özel Görev</span>
                    </label><br>

                    <div class="hidden mt-2" id="extraQuestionArea">
                        <select name="type" id="" class="browser-default mb-1">
                            <option value="0">Soru</option>
                            <option value="1">Youtube Subscription</option>
                            <option value="2">Youtube Views</option>
                            <option value="3">Youtube Likes</option>
                            <option value="4">Youtube Comments</option>
                            <option value="5">Google play download/comment/star</option>
                            <option value="6">Link</option>
                        </select>

                        <select name="partial_sending" id="" class="browser-default mb-1">
                            <option value="1">Parçalı Gönderim</option>
                            <option value="2">Direkt Gönderim</option>
                        </select>

                        <label>Kaç kullanıcı alabilsin</label>
                        <input type="number" placeholder="Kaç kullanıcıya dağıtılsın" name="mission_take_limit" value="0"/>

                        <input type="text" placeholder="intent link" name="intent_link" />
                        <blockquote>
                            <label><b>Youtube Subs: </b> <i class="red-text">UCDJXxpSMG7zy8SGj-zw3VMg</i>  (channel ID)</label><br>
                            <label><b>Youtube Views/Likes/Comments: </b> <i class="red-text">EL_qo2Ddxrc&list=RDCpCG3ClOzY4</i>  (play ID watch?v=HERE)</label><br>
                            <label><b>Google Play Download/Comment+Star</b> <i class="red-text">com.payquestion.payquest</i>  (packageName)</label>
                        </blockquote>
                    </div>

                    <button type="submit" class="btn blue mt-2">Ekle</button>
                </form>


            </div>
        </div>
    </div>
@endsection
