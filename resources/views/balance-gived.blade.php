@extends('layouts.web')

@section('content')
    <div class="container">
        <h2 class="center thin">{{ $total }} kullanıcıya toplam <b>{{ number_format(200 * $total) }}₺</b> ödeme yapıldı</h2>
        <p class="center">Payquestion Son Ödemeler - <b>Ekim Ayında Ödeme Talebinde bulunanlar.</b> </p>
        <p class="center">Kalan Gönderilecek Miktar: 27.700₺</p>
        <div class="row">
                @foreach($users as $user)
                    <div class="col s12 m4">
                        <div class="card wg_shadow center">
                            <div class="card-content">
                                <div style="width: 120px; height: 120px; background:#dbdbdb;border-radius: 250px;margin-left: auto;margin-right: auto" class="center">
                                    <img src="https://i.hizliresim.com/GNTf1p.png" width="120px" height="120px" class="lazy round-full" alt="">
                                </div>
                                <br>
                                <h5>{{ $user->user->name }}</h5>
                                <label>Ödeme Onay Tarihi : {{ $user->updated_at }}</label>
                                <h6 class="green-text">200 ₺</h6>
                            </div>
                        </div>
                    </div>
                @endforeach
        </div>
    </div>
@endsection
