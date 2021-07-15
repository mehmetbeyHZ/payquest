<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payquest</title>
    <link rel="shortcut icon" href="{{ asset('logo_default.png') }}" type="image/png" />
    <link rel="stylesheet" href="{{ asset('custom.css?v=1.2') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="{{ asset('js/lazy.js') }}"></script>
    <script src="{{ asset('custom.js?v=1.') }}"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div class="show-on-med-and-down">
    <nav class="top-nav white wg_shadow ">
        <a href="#" data-target="slide-out" class="sidenav-trigger black-text"><i class="material-icons">menu</i></a>

    </nav>
</div>

<ul id="slide-out" class="sidenav sidenav-fixed white wg_shadow">
    @if(auth()->guard('admin')->check())
        <li><div class="user-view">
                <div class="background" style="background: #433efe">
                </div>
                <a href="#user"><img class="circle" src="{{ asset('logo_black.png') }}"></a>
                <a href="#name"><span class="white-text name">Payquest Admin</span></a>
                <a href="#email"><span class="white-text email">{{ auth()->guard('admin')->user()->username }}</span></a>
            </div>
        </li>
        <li class="{{ is_active('admin.home') }}"><a href="{{ route('admin.home') }}"><i class="material-icons">home</i>Anasayfa</a></li>
        <li class="{{ is_active('admin.users') }}"><a href="{{ route('admin.users') }}"><i class="material-icons">supervisor_account</i>Kullanıcılar</a></li>
        <li class="{{ is_active('admin.payment.requests') }}"><a href="{{ route('admin.payment.requests') }}"><i class="material-icons">payment</i>Ödeme Bildirimleri</a></li>
        <li class="{{ is_active('admin.missions.taken') }}"><a href="{{ route('admin.missions.taken') }}"><i class="material-icons">filter_list</i>Alınan Görevler</a></li>
        <li class="{{ is_active('admin.missions.check') }}"><a href="{{ route('admin.missions.check') }}"><i class="material-icons">check_circle</i>Onay Bekleyen Görevler</a></li>
        <li class="{{ is_active('admin.missions') }}"><a href="{{ route('admin.missions') }}"><i class="material-icons">help_outline</i>Sorular</a></li>
        <li class="{{ is_active('admin.missions.add') }}"><a href="{{ route('admin.missions.add') }}"><i class="material-icons">playlist_add</i>Soru Ekle</a></li>
        <li class="{{ is_active('admin.support.questions') }}"><a href="{{ route('admin.support.questions') }}"><i class="material-icons">playlist_add</i>Gönderilen Sorular</a></li>
        <li class="{{ is_active('admin.tickets') }}"><a href="{{ route('admin.tickets') }}"><i class="material-icons">question_answer</i>Destek  {!!  admin_ticket_notification() !!}</a></li>
        <li class="{{ is_active('admin.notification') }}"><a href="{{ route('admin.notification') }}"><i class="material-icons">notifications_active</i>Bildirim</a></li>
        <li class="{{ is_active('admin.blogs') }}"><a href="{{ route('admin.blogs') }}"><i class="material-icons">bookmark_border</i>Blog</a></li>
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
                <li class="{{ is_active('admin.competitions') . is_active('admin.competitions.add') . is_active('admin.competitions.update')}}">
                    <a class="collapsible-header" style="padding: 0 32px!important;">Yarışma<i class="material-icons">arrow_drop_down</i></a>
                    <div class="collapsible-body">
                        <ul>
                            <li class="{{ is_active('admin.competitions') . is_active('admin.competitions.update') }}"><a href="{{ route('admin.competitions') }}">Yarışmalar</a></li>
                            <li class="{{ is_active('admin.competitions.add') }}"><a href="{{ route('admin.competitions.add') }}">Yeni Yarışma</a></li>
                            <li><a href="#!">Soru Ekle</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
        <li class="{{ is_active('admin.logout') }}"><a href="{{ route('admin.logout') }}"><i class="material-icons">exit_to_app</i>Çıkış yap</a></li>
    @else
        <li><a href="{{ route('admin.login') }}">Giriş yap</a></li>
    @endif
</ul>

<!-- Modal Structure -->
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
<style>
    header, main, footer {
        padding-left: 300px;
    }
    .show-on-med-and-down{display: none}
    @media only screen and (max-width: 992px){
        .show-on-med-and-down{display: block!important;}
        header,main,footer{padding-left:0}}
</style>
<main>
    @yield("content")
</main>

</body>
</html>
