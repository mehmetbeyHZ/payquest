@extends('layouts.web')
@section('extra_title',' - ' . $blog->title)
@section('content')
    @if($blog->image)

        <div id="index-banner" class="parallax-container"  style="background: #0000008f;height: 250px!important;">
            <div class="section no-pad-bot">
                <h1 class="header center white-text">{{ $blog->title }}</h1>
            </div>
            <div class="parallax"><img src="{{ $blog->image }}"></div>
        </div>

    @else
        <h2 class="center thin">{{ $blog->title }}</h2>
    @endif
    <div class="container">
        <div class="card wg_shadow">
            <div class="card-content">
                {!! $blog->text !!}
            </div>
        </div>
    </div>
@endsection
