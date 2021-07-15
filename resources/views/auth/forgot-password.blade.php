@extends('layouts.web')

@section('content')
    <div class="container">
        <div class="card wg_shadow mt-3 auth_form">
            <div class="card-content">
                <form action="{{ route('user.password.reset.post',['key' => $key]) }}" method="post" class="submit">
                    @csrf
                    <div class="field">
                        <label for="password">@lang('app.password')</label>
                        <input type="password" placeholder="@lang('app.password')" name="password" id="password" autocomplete="off">
                    </div>
                    <div class="field mt-1">
                        <label for="re_password">@lang('app.re_password')</label>
                        <input type="password" placeholder="@lang('app.re_password')" name="re_password" id="re_password" autocomplete="off">
                    </div>
                    <button type="submit" class="btn block_btn btn_custom mt-1">@lang('app.change_password')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
