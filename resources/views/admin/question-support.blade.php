@extends('layouts.app')

@section('content')
    <div class="wg_shadow">
        <div class="card-content">
            <table class="striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Soru</th>
                    <th>Cevaplar</th>
                    <th>Seviye</th>
                    <th>Fiyat</th>
                    <th>XP</th>
                    <th>Kullanıcı</th>
                    <th>Tarih</th>
                    <th>Onay</th>
                </tr>
                </thead>

                <tbody>
                @foreach($questions as $question)
                    <tr id="questionTRField{{$question->id}}">
                        <td>{{ $question->id }}</td>
                        <td>{{ $question->question }}</td>
                        <td>
                            @foreach(json_decode($question->question_answers,true) as $key => $value)
                                <a class="answer_item @if($question->correct_index === $key) correct_answer @endif">{{ $value }}</a>
                            @endforeach
                        </td>
                        <td>
                            <input type="number" placeholder="Seviye" id="level" data-refid="{{ $question->id }}">
                        </td>
                        <td>
                            <input type="number" placeholder="Fiyat" step="any" id="questionPrice{{$question->id}}">
                        </td>
                        <td>
                            <input type="number" placeholder="XP" id="questionXP{{$question->id}}">
                        </td>
                        <td>{{ $question->user->name }}</td>
                        <td>{{ $question->created_at }}</td>
                        <td>
                            <div class="d-flex">
                                <a id="confirm_support_question" data-id="{{ $question->id }}" class="answer_item c-pointer">Onayla</a>
                                <a data-id="{{ $question->id }}" class="answer_item c-pointer" href="{{ route('admin.support.questions.edit',['id' => $question->id]) }}">Düzenle</a>
                                <a id="delete_question_support" data-id="{{ $question->id }}" class="answer_item red white-text c-pointer">Sil</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $questions->appends(request()->input())->links('pagination.custom') }}

        </div>
    </div>
    <script>
        $(function () {

            const randomizer = [
                ["0.05", "0.06", "0.07"],
                ["0.06", "0.07", "0.08"],
                ["0.07", "0.08", "0.09"],
                ["0.08", "0.09", "0.10"],
                ["0.09", "0.10", "0.11"],
                ["0.10", "0.11", "0.12"],
                ["0.11", "0.12", "0.13"],
                ["0.12", "0.13", "0.14"]
            ]

            $("a#confirm_support_question").on('click', function (e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                let level = $("input#level[data-refid='"+id+"']").val();
                let price = $("input#questionPrice"+id).val();
                let xp = $("input#questionXP"+id).val();
                request(null,{
                    address: '{{ route('admin.support.questions.add') }}',
                    data : {
                        id : id,
                        mission_value: price,
                        mission_level: level,
                        mission_xp:xp
                    }
                },added_question_support)
            })

            $("input#level").on('keyup', function () {
                let refId = $(this).attr("data-refid");
                let vl = $(this).val();
                $("input#questionXP" + refId).val(parseInt(vl) * 10);

                let priceField = randomizer[(parseInt(vl) - 1)];
                let takeRandomPrice = priceField[Math.floor(Math.random() * priceField.length)];
                $("input#questionPrice" + refId).val(takeRandomPrice);

            });

            $("a#delete_question_support").on('click',function (){
               request(null,{address:'{{ route('admin.support.questions.delete') }}',data:{id:$(this).attr("data-id")}},deleted_question_support)
            });

            function deleted_question_support(xhr)
            {
                $("tr#questionTRField"+xhr.id).remove();
            }
            function added_question_support(xhr)
            {
                $("tr#questionTRField"+xhr.id).remove();
            }

        });
    </script>
    <style>
        #toast-container {
            top: auto !important;
            right: auto !important;
            bottom: 10%;
            left:7%;
        }
    </style>
@endsection
