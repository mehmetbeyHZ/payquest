@extends('layouts.app')

@section('content')
    <div class="">
        <div class="card wg_shadow">
            <div class="card-content">
                <div class="row" style="margin-bottom: 0px!important;">
                    <form action="" method="get">
                        <div class="col s12 m2">
                            <label>Arama Tipi</label>
                            <select name="type" class="browser-default">
                                <option value="name" @if(request('type') === "name") selected @endif>Ad Soyad</option>
                                <option value="email" @if(request('type') === "email") selected @endif>Email</option>
                                <option value="ref_code" @if(request('type') === "ref_code") selected @endif>Referans
                                    Kodu
                                </option>
                                <option value="ip" @if(request('type') === "ip") selected @endif>IP Adresi</option>
                            </select>
                        </div>
                        <div class="col s12 m2">
                            <label>Sıralama Türü</label>
                            <select name="sort_by" class="browser-default">
                                <option value="" @if(request('sort_by') === "") selected @endif>Kayıt Tarihi</option>
                                <option value="balance_cache"
                                        @if(request('sort_by') === "balance_cache") selected @endif>Bakiye
                                </option>
                                <option value="diamond_cache"
                                        @if(request('sort_by') === "diamond_cache") selected @endif>Elmas
                                </option>
                                <option value="ref_cache" @if(request('sort_by') === "ref_cache") selected @endif>
                                    Referans Sayısı
                                </option>
                            </select>
                        </div>
                        <div class="col s12 m6">
                            <label>&nbsp;</label>
                            <input type="text" name="q" placeholder="Ara..." value="{{ request('q') }}">
                        </div>
                        <div class="col s12 m2">
                            <label>&nbsp;</label>
                            <button type="submit" class="height-100 btn blue block_btn">Ara</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="card wg_shadow mb-5">
            <div class="">
                <table class="responsive-table striped">
                    <thead>
                    <tr>
                        <th>#ID</th>
                        <th>IMG</th>
                        <th>Onay</th>
                        <th>Ad Soyad</th>
                        <th>Email</th>
                        <th>TEL</th>
                        <th>REF</th>
                        <th>Bakiye ₺</th>
                        <th>Elmas</th>
                        <th>Ref</th>
                        <th>XP</th>
                        <th>LV</th>
                        <th>IP</th>
                        <th>Kayıt</th>
                        <th>Askıya Al</th>
                        <th>Düzenle</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($users as $user)
                        <tr style="{{ (int)$user->is_banned === 1 ? 'background-color:#ff171763;' : null }}">
                            <td>
                                {{ $user->id }}
                            </td>
                            <td class="center">
                                <div class="avatar_loading_div">
                                    <img src="" class="user_table_avatar lazy" data-original="{{ $user->avatar }}"
                                         alt="">
                                </div>
                            </td>
                            <td>
                                <div class="switch">
                                    <label>
                                        <input id="verify_profile"
                                               data-route="{{ route('admin.verify.user') }}"
                                               type="checkbox" data-id="{{ $user->id }}" {{ $user->is_verified === 1 ? 'checked' : null }}>
                                        <span class="lever"></span>
                                    </label>
                                </div>
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>
                                <a class="@if($user->email_verified_at === null) black-text @else green-text @endif">{{ $user->email }}</a>
                            </td>
                            <td>
                                <a class="@if($user->phone_verified_at === null) black-text @else green-text @endif">{{ $user->phone_number }}</a>
                            </td>
                            <td>{{ $user->ref_code }}</td>
                            <td>{{ $user->balance_cache }} ₺</td>
                            <td>{{ $user->diamond_cache }}</td>
                            <td>{{ $user->ref_cache }}</td>
                            <td>{{ $user->xp_cache }}</td>
                            <td>{{ xpToLevel($user->xp_cache)['level'] }}</td>
                            <td>{{ $user->ip }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>
                                <select name="" id="suspendUser" class="browser-default mt-2"
                                        data-action="{{ route('admin.users.suspend') }}" data-id="{{ $user->id }}">
                                    <option value="0" {{ (int)$user->is_banned === 0 ? "selected" : null }}>Aktif
                                    </option>
                                    <option value="1" {{ (int)$user->is_banned === 1 ? "selected" : null }}>Askıya Al
                                    </option>
                                </select>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.edit',['id' => $user->id]) }}" class="answer_item">Düzenle</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->appends(request()->input())->links('pagination.custom') }}
        </div>
    </div>
@endsection
