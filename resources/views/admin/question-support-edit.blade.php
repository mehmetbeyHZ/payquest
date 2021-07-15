@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <form action="{{ route('admin.support.questions.edit.post',['id' => $question->id]) }}" method="post" class="addQuestion">
            @csrf
            <div class="card wg_shadow">
                <div class="card-content">
                    <textarea placeholder="Soruyu yazın." name="mission_question" style="height: 120px">{{ $question->question }}</textarea>

                    <label>Cevap Ekle</label>
                    <div class="d-flex">
                        <input type="text" placeholder="Cevap" id="addAnswerInput">
                        <button class="btn btn_custom height-100" id="addAnswerFieldManual">Ekle</button>
                    </div>

                    <div id="question_answer_field" class="mt-2 mb-2">
                        @foreach(json_decode($question->question_answers,true) as $key => $value)

                            <div class="answer_list wg_shadow pd-2 mb-2 d-flex d-space-between">
                                <label><input class="with-gap checkAnswerItem" name="answer" type="radio"
                                              value="{{ $value }}" @if($key === $question->correct_index) checked @endif/><span>{{ $value }}</span></label>
                                <a id="remove_answer" class="c-pointer">Sil</a>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn blue">Düzenle</button>
                </div>
            </div>
        </form>
    </div>
@endsection
