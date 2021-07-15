@extends('layouts.web')

@section('content')
    <div class="container mt-4">
        <div class="card wg_shadow auth_form">
            <div class="card-content">
                <h4 class="center">Telefonunuzu Onaylayın</h4>
                <p class="center mb-2">Telefonunuzu onaylamanız içiz size kod göndereceğiz.</p>
                <form action="" class="submit">
                    @csrf
                    <label>Telefon numaranız (0) olmadan</label>
{{--                    <input type="text" name="phone" placeholder="5xx xxx xx xx (sıfır olmadan)" value="{{ auth()->user()->phone_number }}"/>--}}
                    <input type="tel" id="phone" name="phone" placeholder="5xx-xxx-xx-xx" pattern="5[0,3,4,5,6][0-9]\d\d\d\d\d\d\d" autocomplete="off" required>
                    <button type="submit" class="btn btn_custom block_btn mt-1">Onay Kodu Gönder</button>
                </form>
            </div>
        </div>
        <div class="card wg_shadow auth_form">
            <div class="card-content center">
                <label class="center">Bir telefon numarasını yalnızca 1 hesapta onaylayabilirsiniz. <br>
                    <i>BDDMEDYA</i>
                </label>
            </div>
        </div>
    </div>
@endsection
