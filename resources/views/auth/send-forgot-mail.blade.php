@extends('layouts.web')

@section('content')
<div class="container mt-4">
    <div class="card wg_shadow auth_form">
        <div class="card-content">
            <h4 class="center">Şifremi Unuttum</h4>
            <p class="center mb-2">Kayıtlı olduğunuz e-posta adresinizi yazın.</p>
            <form action="{{ route('auth.forgotten.api') }}" class="submit">
                @csrf
                <input type="email" name="email" placeholder="E-Posta adresiniz" class="mb-1"/>
                <button type="submit" class="btn block_btn btn_custom">Bağlantı Gönder</button>
            </form>
        </div>
    </div>
</div>
@endsection
