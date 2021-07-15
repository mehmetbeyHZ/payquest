@extends('layouts.web')

@section('content')
    <div style="width: 100%; background: #00aa19; text-align: center; color: #fff; padding: 5px">
        Bu alanda sadece soru isteği gönderebilirsiniz.
    </div>
    <div class="container mt-2">
        <div class="row">
            <div class="col s12 m4">
                <div class="card wg_shadow">
                    <div class="card-content">
                        <div class="d-flex">
                            <img src="{{ auth()->user()->avatar }}" alt="" class="user_edit_avatar center">
                            <div class="ml-2">
                                <h6>{{ auth()->user()->name }}</h6>
                                <p style="color: #808080">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="collection" style="border:0px solid #fff">
                        @foreach($items as $key => $value)
                            <a href="#!" class="collection-item black-text" style="border:0px solid #fff"> <span
                                    class="new badge white-text" data-badge-caption=""
                                    style="border-radius: 10px; background: #433efe!important;">{{ $value }}</span>{{ $key }}
                            </a>
                        @endforeach
                    </div>

                </div>
            </div>
            <div class="col s12 m8">
                @if(!auth()->user()->phone_verified_at)
                    <div class="m6 s6">
                        <div class="card wg_shadow">
                            <div class="card-content">
                                <h3 class="thin center">Telefon Numaranızı Onaylayın</h3>
                                <p class="center mb-2">Telefon numaranızı onaylamanız gerekmektedir. Bunun için size bir
                                    kod göndereceğiz</p>
                                <a href="{{ route('user.sms.verify') }}" class="btn block_btn btn_custom">Onay Sayfasına Git</a>
                            </div>
                        </div>
                    </div>
                @else
                    <form action="{{ route('user.add.question') }}" method="post" class="addQuestion">
                        @csrf
                        <div class="card wg_shadow">
                            <div class="card-content">
                                <textarea placeholder="Soruyu yazın." name="mission_question" style="height: 120px"></textarea>

                                <label>Cevap Ekle</label>
                                <div class="d-flex">
                                    <input type="text" placeholder="Cevap" id="addAnswerInput">
                                    <button class="btn btn_custom height-100" id="addAnswerFieldManual">Ekle</button>
                                </div>

                                <div id="question_answer_field" class="mt-2 mb-2"></div>

                                <button type="submit" class="btn blue">Gönder</button>
                            </div>
                        </div>
                    </form>
                @endif


                {{--                <script>--}}
                {{--                    // $(function (){--}}
                {{--                    //    $("#testme").on('click',function (){--}}
                {{--                    //        var popupWindow = parent.window.open("https://www.instagram.com", "MyParentWindowPopUp", "width=1000, height=800");--}}
                {{--                    //--}}
                {{--                    //    });--}}
                {{--                    // });--}}
                {{--                </script>--}}

                {{--                <div class="col s12 m4">--}}
                {{--                    <div class="card wg_shadow">--}}
                {{--                        <div class="balance" style="position: absolute; background: #5aff00; color: #fff;padding: 8px;border-radius: 4px 4px 40px 0px">--}}
                {{--                            <span style="padding-right: 5px">0.3₺</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="balance" style="position: absolute; background: #b300ff; color: #fff;padding: 8px;border-radius: 4px 4px 0px 40px; right: 0">--}}
                {{--                            <span style="padding-left: 5px">50XP</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="card-content center">--}}
                {{--                            <img src="https://image.flaticon.com/icons/svg/174/174855.svg" alt="" width="80px" height="80px">--}}
                {{--                            <h6>@mt.ks</h6>--}}
                {{--                            <p style="color: grey">Instagram hesabını takip et</p>--}}
                {{--                            <button type="submit" class="btn btn_custom mt-1 block_btn" disabled>Takip Et</button>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="col s12 m4">--}}
                {{--                    <div class="card wg_shadow">--}}
                {{--                        <div class="balance" style="position: absolute; background: #5aff00; color: #fff;padding: 8px;border-radius: 4px 4px 40px 0px">--}}
                {{--                            <span style="padding-right: 5px">0.3₺</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="balance" style="position: absolute; background: #b300ff; color: #fff;padding: 8px;border-radius: 4px 4px 0px 40px; right: 0">--}}
                {{--                            <span style="padding-left: 5px">50XP</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="card-content center">--}}
                {{--                            <img src="https://image.flaticon.com/icons/svg/174/174855.svg" alt="" width="80px" height="80px" style="max-width: 100%">--}}
                {{--                            <h6>@madlove35</h6>--}}
                {{--                            <p style="color: grey">Instagram hesabını takip et</p>--}}
                {{--                            <button type="submit" class="btn btn_custom mt-1 block_btn" disabled>Takip Et</button>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}


                {{--                <div class="col s12 m4">--}}
                {{--                    <div class="card wg_shadow">--}}
                {{--                        <div class="balance" style="position: absolute; background: #5aff00; color: #fff;padding: 8px;border-radius: 4px 4px 40px 0px">--}}
                {{--                            <span style="padding-right: 5px">0.3₺</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="balance" style="position: absolute; background: #b300ff; color: #fff;padding: 8px;border-radius: 4px 4px 0px 40px; right: 0">--}}
                {{--                            <span style="padding-left: 5px">50XP</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="card-content center">--}}
                {{--                            <img src="https://image.flaticon.com/icons/svg/1076/1076995.svg" alt="" width="80px" height="80px" style="max-width: 100%">--}}
                {{--                            <h6>@NetD Müzik</h6>--}}
                {{--                            <p style="color: grey">Youtube kanalını takip et</p>--}}
                {{--                            <button type="submit" class="btn btn_custom mt-1 block_btn" disabled>Takip Et</button>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="col s12 m4">--}}
                {{--                    <div class="card wg_shadow">--}}
                {{--                        <div class="balance" style="position: absolute; background: #5aff00; color: #fff;padding: 8px;border-radius: 4px 4px 40px 0px">--}}
                {{--                            <span style="padding-right: 5px">0.3₺</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="balance" style="position: absolute; background: #b300ff; color: #fff;padding: 8px;border-radius: 4px 4px 0px 40px; right: 0">--}}
                {{--                            <span style="padding-left: 5px">50XP</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="card-content center">--}}
                {{--                            <img src="https://image.flaticon.com/icons/svg/1076/1076995.svg" alt="" width="80px" height="80px" style="max-width: 100%">--}}
                {{--                            <h6>Bil Bakalım</h6>--}}
                {{--                            <p style="color: grey">Youtube kanalını takip et</p>--}}
                {{--                            <button type="submit" class="btn btn_custom mt-1 block_btn" disabled>Takip Et</button>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="col s12 m4">--}}
                {{--                    <div class="card wg_shadow">--}}
                {{--                        <div class="balance" style="position: absolute; background: #5aff00; color: #fff;padding: 8px;border-radius: 4px 4px 40px 0px">--}}
                {{--                            <span style="padding-right: 5px">0.3₺</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="balance" style="position: absolute; background: #b300ff; color: #fff;padding: 8px;border-radius: 4px 4px 0px 40px; right: 0">--}}
                {{--                            <span style="padding-left: 5px">50XP</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="card-content center">--}}
                {{--                            <img src="https://image.flaticon.com/icons/svg/226/226235.svg" alt="" width="80px" height="80px" style="max-width: 100%">--}}
                {{--                            <h6>@9gag</h6>--}}
                {{--                            <p style="color: grey">Twitter hesabını takip et</p>--}}
                {{--                            <button type="submit" class="btn btn_custom mt-1 block_btn" disabled>Takip Et</button>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}


                {{--                <div class="col s12 m4">--}}
                {{--                    <div class="card wg_shadow">--}}
                {{--                        <div class="balance" style="position: absolute; background: #5aff00; color: #fff;padding: 8px;border-radius: 4px 4px 40px 0px">--}}
                {{--                            <span style="padding-right: 5px">0.3₺</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="balance" style="position: absolute; background: #b300ff; color: #fff;padding: 8px;border-radius: 4px 4px 0px 40px; right: 0">--}}
                {{--                            <span style="padding-left: 5px">50XP</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="card-content center">--}}
                {{--                            <img src="https://image.flaticon.com/icons/svg/226/226235.svg" alt="" width="80px" height="80px" style="max-width: 100%">--}}
                {{--                            <h6>@mehmetiscod</h6>--}}
                {{--                            <p style="color: grey">Twitter hesabını takip et</p>--}}
                {{--                            <button type="submit" class="btn btn_custom mt-1 block_btn" disabled>Takip Et</button>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}



                {{--                <div class="col s12 m4">--}}
                {{--                    <div class="card wg_shadow">--}}
                {{--                        <div class="balance" style="position: absolute; background: #5aff00; color: #fff;padding: 8px;border-radius: 4px 4px 40px 0px">--}}
                {{--                            <span style="padding-right: 5px">0.3₺</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="balance" style="position: absolute; background: #b300ff; color: #fff;padding: 8px;border-radius: 4px 4px 0px 40px; right: 0">--}}
                {{--                            <span style="padding-left: 5px">50XP</span>--}}
                {{--                        </div>--}}
                {{--                        <div class="card-content center">--}}
                {{--                            <img src="https://image.flaticon.com/icons/svg/1532/1532534.svg" alt="" width="80px" height="80px" style="max-width: 100%">--}}
                {{--                            <h6>PayQuestion</h6>--}}
                {{--                            <p style="color: grey">Uygulamasını indir</p>--}}
                {{--                            <button type="submit" class="btn btn_custom mt-1 block_btn" disabled>İndir</button>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}


            </div>
        </div>
    </div>
@endsection
