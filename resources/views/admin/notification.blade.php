@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card wg_shadow">
            <div class="card-content">
                <label>Kullanıcılara bildirim gönder</label>
                <form action="{{ route('admin.notification.post') }}" class="submit mt-2">
                    @csrf
                    <input type="text" placeholder="Bildirim başlığı" name="title" />
                    <textarea name="body" id="" cols="30" rows="10" placeholder="Mesaj" class="mt-1" style="height: 150px"></textarea>
                    <button type="submit" class="btn blue mt-1">Bildirimi Gönder</button>
                </form>

            </div>
        </div>
    </div>
@endsection
