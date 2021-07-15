@extends('layouts.web')

@section('content')
    <div class="container mt-4">
        <div class="card wg_shadow auth_form">
            <div class="card-content">
                <h4 class="center">Kodu Girin</h4>
                <p class="center mb-2">{{ session('sms_phone') }} numarasına gönderdiğimiz 6 haneli kodu girin.</p>
                <form action="" class="submit">
                    @csrf
                    <input type="number" name="code" placeholder="Onay kodunuz" class="mb-1"/>
                    <button type="submit" class="btn block_btn btn_custom">Onayla</button>
                </form>
            </div>
        </div>
    </div>
@endsection
