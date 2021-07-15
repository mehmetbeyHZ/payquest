@extends('layouts.web')
@section('content')
    <div class="container mt-2">
        <div class="card wg_shadow">
            <div class="card-content">
                @if(isset($message))
                    <h4 class="thin center">{!! $message !!}</h4>
                @else
                    @if(isset($user))
                        <h4 class="thin center"><b>{{ $user->name }}</b> Hesabın onaylandı!</h4>
                    @else
                        <h4 class="thin center">Bu bağlantı artık geçersiz.</h4>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
