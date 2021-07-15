@extends('layouts.app')

@section('content')

    <div class="card wg_shadow">
        <div class="card-content">
            <div class="row" style="margin-bottom: 0px!important;">
                <form action="" method="get">
                    <div class="col s12 m1">
                        <label>Filtre</label>
                        <select name="type" class="browser-default">
                            <option value="name" @if(request('type') === "name") selected @endif>Ad Soyad</option>
                        </select>
                    </div>
                    <div class="col s12 m1">
                        <label>Onay</label>
                        <select name="is_confirmed" class="browser-default">
                            <option value="" @if(request('is_confirmed') === "is_confirmed") selected @endif>Hepsi</option>
                            <option value="0" @if(request('is_confirmed') === "0") selected @endif>Onay Bekliyor</option>
                            <option value="1" @if(request('is_confirmed') === "1") selected @endif>Onaylı</option>
                            <option value="2" @if(request('is_confirmed') === "2") selected @endif>Reddedilmiş</option>
                        </select>
                    </div>
                    <div class="col s12 m1">
                        <label>Tarih</label>
                        <select name="created_at" class="browser-default">
                            <option value="" @if(request('created_at') === "") selected @endif>Hepsi</option>
                            @for($i = 0; $i <= 12; $i++)
                                <option value="{{ now()->subMonths($i)->format("Y-m") }}"
                                        @if(request('created_at') ===  now()->subMonths($i)->format("Y-m")) selected @endif>{{ now()->subMonths($i)->format("Y-m") }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col s12 m1">
                        <label>Banka</label>
                        <select name="bank_id" class="browser-default">
                            <option value="" @if(request('bank_id') === "") selected @endif>Hepsi</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->bank_id }}" @if(request('bank_id') === (string)$bank->bank_id) selected @endif>{{ $bank->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col s12 m6">
                        <label>Ara</label>
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
    <label>Toplam: {{ $payments->total() }}</label>
    <div class="card wg_shadow">
        <div class="">
            <table class="responsive-table striped">
                <thead>
                <tr>
                    <th class="center">#</th>
                    <th>Avatar</th>
                    <th>Kullanıcı</th>
                    <th>Onay</th>
                    <th>Tel</th>
                    <th>Level</th>
                    <th>Talep</th>
                    <th>Referanslar</th>
                    <th>Onay Durumu</th>
                    <th>Oluşturma Tarihi</th>
                    <th>Düzenle</th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td class="center">{{ $payment->request_id }}</td>
                        <td><img src="{{ $payment->user->avatar }}" class="user_table_avatar" alt=""></td>
                        <td>
                            <a href="{{ route('admin.users.edit',['id' => $payment->user->id]) }}" class="@if($payment->user->is_banned === 1) red-text @endif">{{ $payment->user->name }}</a>
                        </td>
                        <td>
                            <div class="switch">
                                <label>
                                    <input id="verify_profile"
                                           data-route="{{ route('admin.verify.user') }}"
                                           type="checkbox"
                                           data-id="{{ $payment->user->id }}" {{ $payment->user->is_verified === 1 ? 'checked' : null }}>
                                    <span class="lever"></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <a class="@if($payment->user->phone_verified_at === null) black-text @else green-text @endif">{{ $payment->user->phone_number }}</a>
                        </td>
                        <td>{{ xpToLevel($payment->user->xp_cache)['level'] }}</td>
                        <td>{{ $payment->quantity  }} ₺</td>
                        <td class="center">{{ $payment->user->ref_cache }} </td>
                        <td>
                            @if($payment->is_confirmed === 1)
                                Onaylandı
                            @elseif($payment->is_confirmed === 2)
                                <a class="red-text">Reddedildi</a>
                            @else
                                Onay Bekliyor
                            @endif
                        </td>
                        <td>{{ $payment->created_at  }}</td>
                        <td>
                            <a href="" id="editPaymentRequest"
                               data-action="{{ route('admin.payment.requests.get',['id' => $payment->request_id]) }}"
                               class="answer_item">Düzenle</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $payments->appends(request()->input())->links('pagination.custom') }}
        </div>
    </div>

    <div id="paymentRequestModal" class="modal">
        <div class="modal-content">
            <div class="center mb-2">
                <img src="" class="user_edit_avatar center" id="user_request_avatar" alt="">
                <h6 id="pRequestName">Username</h6>
                <a class="answer_item" id="pUserBalance"></a><br>
                <a class="iban_modal_area" id="iban_modal_area"></a>
                <a class="iban_modal_area" id="pBankName"></a>
                <a class="iban_modal_area" id="pRequestQuantity"></a>
            </div>

            <form action="{{ route('admin.payment.requests.update') }}" id="update_payment_request" class="submit">
                @csrf
                <input type="hidden" id="paymentRequestId" name="request_id" value="">
                <select name="is_confirmed" id="pRequestStatus" class="browser-default">
                    <option value="0">Onay Bekliyor</option>
                    <option value="1">Onaylandı</option>
                    <option value="2">Reddedildi</option>
                </select>
            </form>

        </div>
        <div class="modal-footer">
            <button type="submit" class="waves-effect waves-green btn-flat" form="update_payment_request">Kaydet
            </button>
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Kapat</a>
        </div>
    </div>
@endsection
