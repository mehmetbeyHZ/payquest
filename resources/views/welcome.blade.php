@extends('layouts.web')
@section('content')
    <h1 class="center">@lang('web.payquest_title')</h1>
    <h5 class="center thin">@lang('web.payquest_description')</h5>
    <div class="center">
        <a href="https://bit.ly/payquestion" class="btn blue">Google Play</a>
        <a onclick="M.toast({html:'@lang('web.app_not_publish')'})" class="btn black">App Store</a>
    </div>
    <div class="center mt-3">
        <img class="center" width="300px" src="{{ asset('images/game_picture.jpg') }}" alt="">
        <img class="center" width="300px" src="{{ asset('images/home.jpg') }}" alt="">
        <img class="center" width="300px" src="{{ asset('images/game_question.jpg') }}" alt="">
        <img class="center" width="300px" src="{{ asset('images/game_correct.jpg') }}" alt="">
    </div>

    <h2 class="center">Blog</h2>
    <div class="container">
        <div class="row">
            @foreach($blogs as $blog)
                <div class="col s12 m4">
                    <div class="card wg_shadow">
                        @if($blog->image)
                            <div class="card-image">
                                <img src="{{ $blog->image }}" alt="">
                            </div>
                        @endif
                        <div class="card-content">
                            <div class="card-title">{{ $blog->title }}</div>
                            {!! substr_text($blog->text,300) !!}
                        </div>
                        <div class="card-action">
                            <a href="{{ route('user.blog.view',['slug' => $blog->slug]) }}">Görüntüle</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

{{--    <script src="https://js.pusher.com/4.3/pusher.min.js"></script>--}}
{{--    <script>--}}
{{--        let pusher = new Pusher("ABCDEFG",{--}}
{{--            wsHost : 'payquestion.com',--}}
{{--            wssPort: 7575,--}}
{{--            authEndpoint: '/socket/auth',--}}
{{--            auth: {--}}
{{--                headers: {--}}
{{--                    'X-CSRF-Token': "{{ csrf_token() }}",--}}
{{--                    'X-App-ID': 'ABCDEFG',--}}
{{--                    'Accept': ' application/json',--}}
{{--                    'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNzljNGYxNjU5Y2MzOGZmYzQwMjZkNGZiMDFmMTA5MDRlMTVhNWE4YWJiNWU1YTc3NWIzMzE5NmRiYjIzMjY5NzFhOWQ3ZTA0MzMyM2M5NDAiLCJpYXQiOjE1OTYzNzk2NjYsIm5iZiI6MTU5NjM3OTY2NiwiZXhwIjoxNjI3OTE1NjY2LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.Ww3U4ht1gMKbq7By7iIZe8Sr9sEFcHeqZx_N072MYRkuJJhHFTcgiRX1YB3IZtuqGcdcoWxrBVxcXo5tnErJ7lLCoRqlllHpXuCZafa_yXkwUCJsfCvP4rNIvz7y3BFf5GRdvXVnlylprN9mfWWQphpC9LgrQbNqZVZxOXMlT2rxE7AUWw5mKjRgxpqPs0IbhiMLGsBmVKAfAGVc7o_rT9ecFFGWIlg8D2lHSJBhiai72PJKxrvVO5cPEOqhqQNlp6qjfUVFJ2SfThHMTCflw1luI2c4ZKNlEc6ldBQ0jHTkm8oWt4hYh5Qa04JSBZSbevhTJJXLntVXhFmcLNFVfTMnAqvMMIRmOvuNtFiqZm70A4839czH8ihZ82e4UqRS-7yyx4Pf5ZOOqNgB9AP7obD6K9TjnCsHxIBzXz-5Hp82ixngYo3WWE-CHxuELcxTt5P3bP-F-IO2CFoqUIuIJWSzEkdVYNMqQD4TW6BpJ7Ve-1M_HkNeXdJZX9nCnUMh30Y2ZoltKXxL2gf13XhpsO-zBmWpHFTNUUjqJM0zfg8k4whYM-vz91ndEscZfpTUuunrQcqvGst4MZ4gsT94sCCuf1PPei6dcYv0zsSIpPhQ_Z3IOtsbPwRqwJ-_CTRwLxb945vlgcOY2v8IyAylJzY5JAGJzn44JL8GUPx89LQ'--}}
{{--                }--}}
{{--            }--}}
{{--        });--}}

{{--        pusher.connection.connect();--}}

{{--        pusher.subscribe('presence-competition-manager.1').bind('App\\Events\\CompetitionManager', (data) => {--}}
{{--            console.log(data);--}}
{{--        });--}}





{{--    </script>--}}

@endsection
