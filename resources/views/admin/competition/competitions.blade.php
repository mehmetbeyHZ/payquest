@extends('layouts.app')

@section('content')
    <table class="responsive-table striped">
        <thead>
        <tr>
            <th>#ID</th>
            <th>Başlık</th>
            <th>Toplam Soru</th>
            <th>Kayıt Ücreti</th>
            <th>Ödül</th>
            <th>Kayıt Durumu</th>
            <th>Başlangıç Tarihi</th>
            <th>Son Kayıt Tarihi</th>
            <th>Toplam Kazanacak</th>
            <th>Katılım/MaxKatılım</th>
            <th>Düzenle</th>
        </tr>
        <tbody>
            @foreach($competitions as $competition)
                <tr id="competition_{{$competition->competition}}">
                    <td>{{ $competition->competition }}</td>
                    <td>{{ $competition->competition_title }}</td>
                    <td>{{ $competition->questions()->count() }}</td>
                    <td>{{ $competition->registration_fee }}₺</td>
                    <td>{{ $competition->award }}₺</td>
                    <td>@if($competition->can_register === 1) Kayıt Açık @else Kayıt Kapalı @endif</td>
                    <td>{{ $competition->start_date }}</td>
                    <td>{{ $competition->last_register_date }}</td>
                    <td>{{ $competition->total_winner }}</td>
                    <td>{{ $competition->registers()->count()  }}/{{ $competition->max_users }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('admin.competitions.update',['id' => $competition->competition]) }}" class="answer_item">Düzenle</a>
                            <a href="" class="answer_item">Sorular</a>
                            <a data-id="{{ $competition->competition }}" id="remove_competition_modal" class="answer_item white-text red c-pointer">Sil</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </thead>
    </table>

    <div id="confirm_remove_competition" class="modal">
        <div class="modal-content">
            <h4 class="thin center">Yarışmayı Sil</h4>
            <p class="center">Yarışmayı kalıcı olarak silmek istediğinize emin misiniz?</p>
        </div>
        <div class="modal-footer">
            <a id="remove_competition" data-id="" data-route="{{ route('admin.competitions.delete') }}" class="modal-close waves-effect waves-green btn-flat">Sil</a>
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Vazgeç</a>
        </div>
    </div>
@endsection
