@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="card wg_shadow">
            <div class="card-content">

                <div class="center mb-3 mt-2">
                    <img
                        src="@if($user->avatar != null) {{ $user->avatar }} @else {{ asset('logo_default.png') }} @endif "
                        class="user_edit_avatar" alt="">
                    <br><a href="">{{ $user->name }}</a><br>
                    <a class="black-text">{{ $user->email }}</a>
                    <a class="">{{ $user->ref_code }}</a>
                    <a class="answer_item">Bakiye: <b>{{ $user->balance() }}</b></a>
                    <a class="answer_item">Elmas: <b>{{ $user->totalDiamond() }}</b></a>
                    <a class="answer_item">LEVEL: <b>{{ xpToLevel($user->totalXP())['level'] }}</b></a>
                    <a class="answer_item">Cevap ORT. <b>{{ $average }}sn</b></a>
                </div>

                <div class="row">
                    <div class="col s12">
                        <ul class="tabs tabs-fixed-width tab-demo">
                            <li class="tab"><a class="active" href="#profile">Profil Ayarları</a></li>
                            <li class="tab"><a href="#activity">Aktivite</a></li>
                            <li class="tab"><a href="#payment">Para hareketleri</a></li>
                            <li class="tab"><a href="#change_password">Şifreyi değiştir</a></li>
                            <li class="tab"><a href="#references">REF <span
                                        class="blue count_ref white-text badge">{{ $user->totalRef() }}</span></a></li>
                            <li class="tab"><a href="#multiple">Register <span
                                        class="blue count_ref white-text badge">{{ $devices->count() }}</span></a></li>
                        </ul>
                    </div>
                    <div id="profile" class="col s12 mt-2">

                        <form action="{{ route('admin.users.edit.post',['id' => $user->id]) }}" class="submit"
                              method="post">
                            @csrf
                            <div class="field">
                                <label>Email</label>
                                <input type="text" name="email" placeholder="Email" value="{{ $user->email }}">
                            </div>
                            <div class="field">
                                <label>Ad Soyad</label>
                                <input type="text" placeholder="Ad Soyad" value="{{ $user->name }}" name="name">
                            </div>
                            <div class="field">
                                <label>Telefon Numarası</label>
                                <input type="text" placeholder="Telefon numarası" value="{{ $user->phone_number }}"
                                       name="phone_number">
                            </div>
                            <div class="field mt-1 mb-1">
                                <label>Kayıt: {{ $user->created_at }}</label>
                            </div>
                            <div class="field">
                                <button class="btn blue" type="submit">Güncelle</button>
                            </div>
                        </form>

                        <select name="" id="suspendUser" class="browser-default mt-2"
                                data-action="{{ route('admin.users.suspend') }}" data-id="{{ $user->id }}">
                            <option value="0" {{ (int)$user->is_banned === 0 ? "selected" : null }}>Aktif</option>
                            <option value="1" {{ (int)$user->is_banned === 1 ? "selected" : null }}>Askıya Al</option>
                        </select>

                        @if(isset($ref_from->from_user))
                            <div class="mt-2">
                                <label>Davet Eden: <a
                                        href="{{ $ref_from->from_user->id }}"> {{ $ref_from->from_user->name }}
                                        ({{ $ref_from->from_user->email }})</a></label>
                            </div>
                        @endif
                    </div>
                    <div id="activity" class="col s12 mt-2">
                        <table class="responsive-table striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Log Name</th>
                                <th>Log Text</th>
                                <th>Log Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->log_id }}</td>
                                    <td>{{ $log->log_name }}</td>
                                    <td>{{ $log->log_text  }}</td>
                                    <td>{{ $log->created_at  }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $logs->appends(array_merge(request()->input(),["switch" => "activity"]))->links('pagination.custom') }}

                    </div>
                    <div id="payment" class="col s12 mt-2">

                        <table class="responsive-table striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Değer ₺</th>
                                <th>Onay</th>
                                <th>Desc</th>
                                <th>Cevap Süresi/sn</th>
                                <th>Tarih</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_id }}</td>
                                    <td>@if($payment->payment_type === 1) + @else - @endif
                                        <a class="@if($payment->payment_type === 1) green-text @else red-text @endif">{{ $payment->payment_value }}</a>
                                    </td>
                                    <td>
                                        @if($payment->payment_token_confirmed === 1)
                                            Onaylandı
                                        @else
                                            Onaylanmadı
                                        @endif
                                    </td>
                                    <td>{{ $payment->payment_description  }}</td>
                                    <td>
                                        @if($payment->mission)
                                            {{ (\Illuminate\Support\Carbon::parse($payment->created_at))->diffInSeconds(\Illuminate\Support\Carbon::parse($payment->mission->viewed_at)) }}
                                        @endif

                                    </td>
                                    <td>{{ $payment->created_at  }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $payments->appends(array_merge(request()->input(),['switch' => 'payment']))->links('pagination.custom') }}

                    </div>
                    <div id="change_password" class="col s12 mt-2">

                        <form action="{{ route('admin.users.change.password',['id' => $user->id]) }}" method="post"
                              class="submit">
                            @csrf
                            <div class="field">
                                <label for="password">Yeni şifre</label>
                                <input type="password" id="confirm_password" name="password" placeholder="Yeni şifre"/>
                            </div>
                            <div class="field">
                                <label for="confirm_password">Yeni şifre</label>
                                <input type="password" name="confirm_password" id="confirm_password"
                                       placeholder="Yeni şifre"/>
                            </div>
                            <button type="submit" class="btn blue">Değiştir</button>
                        </form>

                    </div>
                    <div id="references" class="col s12 mt-2">
                        <table class="responsive-table striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Avatar</th>
                                <th>Ad Soyad</th>
                                <th>Email</th>
                                <th>Tel</th>
                                <th>Level</th>
                                <th>Suspend</th>
                                <th>IP</th>
                                <th>Referans Tarihi</th>
                                <th>Sil</th>
                                <th>Düzenle</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($references as $ref)
                                <tr id="referenceId{{$ref->reference_id}}">
                                    <td>{{ $ref->reference_id }}</td>
                                    <td><img src="{{ $ref->user_info->avatar }}" class="user_table_avatar" alt=""></td>
                                    <td>{{ $ref->user_info->name }}</td>
                                    <td>
                                        <a class="@if($ref->user_info->email_verified_at === null) red-text @else green-text @endif">{{ $ref->user_info->email }}</a>
                                    </td>
                                    <td>
                                        <a class="@if($ref->user_info->phone_verified_at === null) black-text @else green-text @endif">{{ $ref->user_info->phone_number }}</a>
                                    </td>
                                    <td>{{ xpToLevel($ref->user_info->xp_cache )['level'] }}</td>
                                    <td>
                                        <select name="" id="suspendUser" class="browser-default mt-2"
                                                data-action="{{ route('admin.users.suspend') }}"
                                                data-id="{{ $ref->user_info->id }}">
                                            <option
                                                value="0" {{ (int)$ref->user_info->is_banned === 0 ? "selected" : null }}>
                                                Aktif
                                            </option>
                                            <option
                                                value="1" {{ (int)$ref->user_info->is_banned === 1 ? "selected" : null }}>
                                                Askıya Al
                                            </option>
                                        </select>
                                    </td>
                                    <td>{{ $ref->user_info->ip }}</td>
                                    <td>{{ $ref->created_at  }}</td>
                                    <td><a id="remove_reference" data-refid="{{$ref->reference_id}}" data-from="{{ $user->id }}" data-regid="{{$ref->user_info->id}}" class="c-pointer  answer_item red white-text">Sil</a></td>
                                    <td>
                                        <a href="{{ route('admin.users.edit',['id' => $ref->user_info->id]) }}"
                                           class="answer_item">Düzenle</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="multiple" class="col s12 mt-2">
                        <table class="responsive-table striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Avatar</th>
                                <th>Ad Soyad</th>
                                <th>Email</th>
                                <th>Level</th>
                                <th>Kayıt Tarihi</th>
                                <th>Düzenle</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($devices as $user)

                                <tr style="{{ (int)$user->user->is_banned === 1 ? 'background-color:#ff171763;' : null }}">
                                    <td>{{ $user->user->id }}</td>
                                    <td><img src="{{ $user->user->avatar }}" class="user_table_avatar" alt=""></td>
                                    <td>{{ $user->user->name }}</td>
                                    <td>
                                        <a class="@if($user->user->email_verified_at === null) red-text @else green-text @endif">{{ $user->user->email }}</a>
                                    </td>
                                    <td>{{ xpToLevel($user->user->xp_cache)['level'] }}</td>
                                    <td>{{ $user->user->created_at  }}</td>
                                    <td>
                                        <select name="" id="suspendUser" class="browser-default mt-2"
                                                data-action="{{ route('admin.users.suspend') }}"
                                                data-id="{{ $user->user->id }}">
                                            <option
                                                value="0" {{ (int)$user->user->is_banned === 0 ? "selected" : null }}>
                                                Aktif
                                            </option>
                                            <option
                                                value="1" {{ (int)$user->user->is_banned === 1 ? "selected" : null }}>
                                                Askıya Al
                                            </option>
                                        </select>
                                    </td>

                                    <div class="switch">
                                        <label>
                                            <input id="verify_profile"
                                                   data-route="{{ route('admin.verify.user') }}"
                                                   type="checkbox"
                                                   data-id="{{ $user->id }}" {{ $user->is_verified === 1 ? 'checked' : null }}>
                                            <span class="lever"></span>
                                        </label>
                                    </div>

                                    <td>
                                        <a href="{{ route('admin.users.edit',['id' => $user->user->id]) }}"
                                           class="answer_item">Düzenle</a>
                                    </td>
                                </tr>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>


            </div>
        </div>
    </div>
    @if(request('switch'))
        <script>
            $(function () {
                $(".tabs").tabs('select', '{{request('switch')}}');
            });
        </script>
    @endif

    <script>
        $(function (){
            $("a#remove_reference").on('click', function (e) {
                e.preventDefault();
                let from = $(this).attr("data-from");
                let reference_id = $(this).attr("data-refid")
                let registered_id = $(this).attr("data-regid");
                request(null, {
                    address: "{{ route('admin.users.reference.delete') }}",
                    data: {from, reference_id, registered_id}
                }, reference_removed);
            });
        });
        function reference_removed(xhr)
        {
            $("tr#referenceId"+xhr.reference_id).remove();
        }
    </script>
@endsection
