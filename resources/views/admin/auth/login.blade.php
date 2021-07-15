@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card wg_shadow login_screen">
            <div class="card-content">
                <div class="center mb-2 mt-2"><img src="{{ asset('logo_default.png') }}" width="50px" alt=""></div>
                <form action="{{ route('admin.login.post') }}" class="submit" method="post">
                    @csrf
                    <input type="text" name="username" autocomplete="off" placeholder="Kullanıcı adınız" class="mb-1">
                    <input type="password" name="password" placeholder="Şifreniz" class="mb-1">
                    <button class="btn block_btn btn_custom" type="submit">Loginn</button>
                </form>
            </div>
        </div>
    </div>
@endsection
