@extends('layouts.app')

@section('content')
    <div class="card wg_shadow">
        <div class="card-content">
            <div class="row" style="margin-bottom: 0px!important;">
                <form action="" method="get">
                    <div class="col s12 m3">
                        <select name="type" class="browser-default">
                            <option value="title"  @if(request('type') === 'title') selected @endif>Başlık</option>
                            <option value="text"  @if(request('type') === 'text') selected @endif>İçerik</option>
                        </select>
                    </div>
                    <div class="col s12 m7">
                        <input type="text" name="q" placeholder="Ara..." value="{{ request('q') }}">
                    </div>
                    <div class="col s12 m2">
                        <button type="submit" class="height-100 btn blue block_btn">Ara</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <a href="{{ route('admin.blogs.add') }}" class="answer_item">Yeni Yazı</a>
    <table class="responsive-table striped">
        <thead>
        <tr>
            <th>#ID</th>
            <th>Başlık</th>
            <th>Yazar</th>
            <th>Tarih</th>
            <th>Düzenle</th>
        </tr>
        </thead>
        <tbody>
        @foreach($blogs as $blog)
            <tr>
                <td>{{ $blog->id }}</td>
                <td>{{ $blog->title }}</td>
                <td>{{ $blog->blog_author->username }}</td>
                <td>{{ $blog->created_at }}</td>
                <td>
                    <div class="d-flex">
                        <a href="{{ route('admin.blogs.edit',['id' => $blog->id]) }}" class="answer_item">Düzenle</a>
                        <a href="{{ route('user.blog.view',['slug' => $blog->slug]) }}" target="_blank" class="answer_item">Görüntüle</a>
                        <a href="" class="answer_item red white-text">Sil</a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
