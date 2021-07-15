<div style="margin-left: auto;
        margin-right: auto;
        justify-content: center;
         padding:20px;
        max-width: 90%;
        border-radius: 5px;
        -webkit-box-shadow: 0 1px 15px 1px rgba(81, 77, 92, .08) !important;
        box-shadow: 0 1px 15px 1px rgba(81, 77, 92, .08) !important;">
    <div style="width: 100%;
        margin-left: auto;
        margin-right: auto;">
        <h1 style="font-family:arial">Merhaba {{ $user->name }}</h1>
        <p  style="font-family:arial;">Görünüşe göre payquestion şifrenizi değiştirmek istiyorsunuz. Aşağıdaki bağlantıya tıklayarak gidebilirsiniz.</p>
        <br>
        <a href="{{ route('user.password.reset',['key' => $reset_key]) }}" style="background-color:#433efe;
        color:#fff;
        margin-top:7px;
        max-width: 100%;
        padding: 14px;
        width: 100%;
        font-family:arial;
        font-size: 17px;
        text-decoration:none;
        border-radius:5px;
        text-align:center;">Şifremi Sıfırla</a>
    </div>
    <br>
</div>
