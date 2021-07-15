@extends('layouts.web')

@section('content')
    <div class="container">
        <div class="card wg_shadow mt-3 auth_form">
            <div class="card-content">
                <form action="{{ route('user.login.post') }}" method="post" class="submit">
                    @csrf
                    <div class="field">
                        <label for="email">@lang('app.email')</label>
                        <input type="text" placeholder="@lang('app.email')" name="email" id="email">
                    </div>
                    <div class="field mt-1">
                        <label for="password">@lang('app.password')</label>
                        <input type="password" placeholder="@lang('app.password')" name="password" id="password">
                    </div>
                    <button type="submit" class="btn block_btn btn_custom mt-1 mb-2">@lang('app.login')</button>
                </form>
                <a href="{{ route('user.forgot.password') }}" class="mt-3">Şifreni mi unuttun?</a>
            </div>
        </div>
    </div>
@endsection
