<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payquest @yield('extra_title')</title>
    <link rel="shortcut icon" href="{{ asset('logo_default.png') }}" type="image/png"/>
    <link rel="stylesheet" href="{{ asset('custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="{{ asset('js/lazy.js') }}"></script>
    <script src="{{ asset('custom.js') }}"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="yandex-verification" content="7a768add0e96a08d" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="bFLXOp6bPnwGFs6AuioySYOLwMLugWhaPoX5jGdhTeY" />
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-6931530475516594",
            enable_page_level_ads: true
        });
    </script>
</head>
<body>


<nav class="custom_menu white wg_shadow">
    <div class="nav-wrapper container">
        <a href="#!" class="brand-logo"><img src="{{ asset('logo_default.png') }}" width="50px" alt=""></a>
        <a href="#" data-target="mobile-demo" class="sidenav-trigger black-text"><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
            <li><a href="{{ route('user.welcome') }}">@lang('web.home_page')</a></li>
            <li><a href="{{ route('user.privacy') }}">@lang('web.privacy_policy')</a></li>
            <li><a href="{{ route('user.sss') }}">SSS</a></li>
            <li><a href="{{ route('terms') }}">Kullanım Koşulları</a></li>
            <li><a href="https://bit.ly/payquestion">@lang('web.play_store')</a></li>
{{--            <li><a onclick="M.toast({html:'@lang('web.app_not_publish')'})">@lang('web.app_store')</a></li>--}}
            @auth()
                <li><a href="{{ route('user.insight') }}">Profil</a></li>
                <li><a href="{{ route('user.logout') }}">Çıkış</a></li>
            @else
                <li><a href="{{ route('user.login') }}">Giriş</a></li>
            @endauth
        </ul>
    </div>
</nav>


<ul class="sidenav" id="mobile-demo">
    <li><a href="{{ route('user.welcome') }}">@lang('web.home_page')</a></li>
    <li><a href="{{ route('user.privacy') }}">@lang('web.privacy_policy')</a></li>
    <li><a href="{{ route('user.sss') }}">SSS</a></li>
    <li><a href="{{ route('terms') }}">Kullanım Koşulları</a></li>
    <li><a href="https://bit.ly/payquestion">@lang('web.play_store')</a></li>
{{--    <li><a onclick="M.toast({html:'@lang('web.app_not_publish')'})">@lang('web.app_store')</a></li>--}}
    @auth()
        <li><a href="{{ route('user.insight') }}">Profil</a></li>
        <li><a href="{{ route('user.logout') }}">Çıkış</a></li>
    @else
        <li><a href="{{ route('user.login') }}">Giriş</a></li>
    @endauth
</ul>

@yield('content')


<div id="loadingModal" class="modal">
    <div class="center loading_center">
        <div class="preloader-wrapper big active">
            <div class="spinner-layer spinner-blue-only">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
