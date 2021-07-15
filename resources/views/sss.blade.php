@extends('layouts.web')
@section('content')
    <h1 class="center thin"><b>S</b>ıkça <b>S</b>orulan <b>S</b>orular</h1>
    <div class="container">
        <div class="card wg_shadow">
            <div class="card-content">
                <ul>
                    <li><b>Sistem Hakkında</b></li>
                    <ol><a href="#what_is_payquestion">Payquestion Nedir?</a></ol>
                    <ol><a href="#what_is_level">Seviye ve XP nedir?</a></ol>
                    <ol><a href="#what_is_level2">En fazla kaç seviye vardır?</a></ol>
                    <ol><a href="#what_is_level3">Nasıl seviye atlanır?</a></ol>
                    <ol><a href="#what_is_level4">Sorular bitti ne zaman eklenir?</a></ol>
                    <ol><a href="#what_is_level5">Neden Hata(Error) Alıyorum?</a></ol>
                    <ol><a href="#what_is_level6">Diamond Nedir?</a></ol>
                    <ol><a href="#what_is_level7">Diamond Nerede Kullanılır?</a></ol>
                    <li><b>Ödeme Hakkında</b></li>
                    <ol><a href="#payment1">Ödememi ne zaman alabilirim?</a></ol>
                    <ol><a href="#paymenttc">Ödememe için T.C No gerekli mi?</a></ol>
                    <ol><a href="#payment2">Bakiyem sıfırlanır mı?</a></ol>
                    <ol><a href="#payment3">Bankadaki adım ile uygulamadaki adım aynı olmalı mı?</a></ol>
                    <ol><a href="#payment4">Ödeme almak için kaç referans gereklidir?</a></ol>
                    <ol><a href="#payment5">Ödeme limiti ne kadar?</a></ol>
                    <li><b>Referans Hakkında</b></li>
                    <ol><a href="#what_is_reference">Referans ne demektir?</a></ol>
                    <ol><a href="#what_is_reference1">Referans Kodum Nerededir?</a></ol>
                    <ol><a href="#what_is_reference2">Referans sınırı var mıdır?</a></ol>
                    <ol><a href="#what_is_reference3">Her referans için ne kadar ödül alırım?</a></ol>
                    <ol><a href="#what_is_reference4">Referans getirmek zorunlu mu?</a></ol>
                    <ol><a href="#what_is_reference5">Referanslarım Telefonunu Onaylamak Zorunda Mı?</a></ol>
                    <li>
                        <div class="d-flex"><img src="https://i.hizliresim.com/SrcTBl.png" width="20" alt=""><b>Özel
                                Görevler Hakkında</b></div>
                    </li>
                    <ol><a href="#private_missions">Özel görev nedir?</a></ol>
                    <ol><a href="#private_missions1">Özel görev ne zaman onaylanır?</a></ol>
                    <ol><a href="#private_missions2">Özel görevi yapmak zorunlu mudur?</a></ol>
                    <li><b>Kullanım Koşulları</b></li>
                    <ol><a href="#banned_status">Hangi durumlarda hesabım kapatılır?</a></ol>
                    <li><b>Reklamlar</b></li>
                    <ol><a href="#advertising1">Reklam Kimliği Nedir?</a></ol>
                    <ol><a href="#advertising2">Reklam Kimliği Nasıl Sıfırlanır?</a></ol>
                </ul>
                <hr style="border: 1px solid #f7f7f7">
                <div id="what_is_payquestion">
                    <h5>Payquestion Nedir?</h5>
                    <p> Payquestion, kullanıcılar arasında soru & cevap yarışması düzenleyen, kullanıcılara her 15
                        dakikada bir 10 soru veren. Bu soruları doğru
                        cevaplayan kullanıcılara soruya göre ödül ve xp veren bir mobil uygulamadır.
                        Uygulama içerisinde farklı alanlardan farkı sorular <b><a
                                href="#what_is_level">Seviyenize</a></b> göre karşınıza çıkar ve
                        belirtilen sürede soruları cevaplamaya çalışırsınız.
                    </p>
                </div>
                <div id="what_is_level">
                    <h5>Seviye Ve XP Nedir?</h5>
                    <p>Sistemde çözdüğünüz her sorunun karşılığı olan bir ₺ ve <b>XP</b>(PUAN) değeri vardır. Doğru
                        çözdüğünüz
                        sorulardan xp (PUAN) kazandıkça seviyeniz artar. Her seviyenin kendine özel soruları vardır.
                        Seviyeniz ilerledikçe
                        kazandığınız para ve xp miktarı da buna oranla artar.
                    </p>
                </div>
                <div id="what_is_level2">
                    <h5>En fazla kaç seviye vardır?</h5>
                    <p>Payquestion uygulamasında en fazla 8. seviyeye kadar ulaşabilirsiniz. Henüz daha ilerleyen
                        bölümlerin kilidi açılmamıştır.</p>
                </div>
                <div id="what_is_level3">
                    <h5>Nasıl seviye atlanır?</h5>
                    <p>Uygulamamızda düzenli olarak soru çözersiniz ve doğru bildiğiniz sorulardan aldığınız xp'ye
                        oranla seviyeniz artar.</p>
                </div>
                <div id="what_is_level4">
                    <h5>Sorular bitti ne zaman eklenir?</h5>
                    <p>Uygulamamızda her hafta düzenli olarak yeni sorular eklenir.</p>
                </div>
                <div id="what_is_level5">
                    <h5>Neden Hata(Error) Alıyorum?</h5>
                    <p>Uygulamamızda yoğunluktan kaynaklı bu tür sorunlar oluşabiliyor. Gerekli düzenlemeler üzerinde çalışıyoruz.</p>
                </div>


                <div id="what_is_level6">
                    <h5>Diamond Nedir?</h5>
                    <p>Diamond, payquestion uygulaması içerisinde kullanabileceğiniz bir birimdir. Paraya çevirilemez.</p>
                </div>
                <div id="what_is_level7">
                    <h5>Diamond Nerede Kullanılır?</h5>
                    <p>Süreyi beklemek istemediğiniz zamanlarda diamond kullanarak 1 seferliğine soruyu atlayabilirsiniz.</p>
                </div>
                <hr style="border: 1px solid #f7f7f7">
                <div id="payment1">
                    <h5>Ödememi ne zaman alabilirim?</h5>
                    <p>Ödeme bildiriminde bulunmuş her kullanıcının ödemesi ayın 1-10 arasında gerçekleştirilir. ayın
                        1-10 arasında yapılan ödeme bildirimleri bir sonraki
                        ayın 1-10 arasında gerçekleştirilir.
                    </p>
                </div>
                <div id="paymenttc">
                    <h5>Ödememe için T.C No gerekli mi?</h5>
                    <p>Şu an için sizin hiçbir kişisel bilginizi istemiyoruz.
                    </p>
                </div>
                <div id="payment2">
                    <h5>Bakiyem sıfırlanır mı?</h5>
                    <p>Hayır sıfırlanmaz.
                    </p>
                </div>
                <div id="payment3">
                    <h5>Bankadaki adım ile uygulamadaki adım aynı olmalı mı?</h5>
                    <p>Ödeme bildirimi yaptığınız hesabınız ile banka hesabınızdaki adınız aynı olması gerekmektedir.
                        Gönderilen bakiyeniz uygulamadaki
                        adınız kullanılarak banka hesabınıza gönderilir.
                    </p>
                </div>
                <div id="payment4">
                    <h5>Ödeme almak için kaç referans gereklidir?</h5>
                    <p>Ödeme alabilmeniz için En Az 5 Mobil Onaylı referansınsa sahip olmanız gerekmektedir.</p>
                </div>
                <div id="payment5">
                    <h5>Ödeme limitleri ne kadar?</h5>
                    <p>200₺ ve üzeri her bakiyenizi çekebilirsiniz.</p>
                </div>
                <hr style="border: 1px solid #f7f7f7">
                <div id="what_is_reference">
                    <h5>Referans ne demektir?</h5>
                    <p>Referans, uygulamaya bizzat sizin davet ettiğiniz yakın arkadaşınız, aileniz veya herhangi bir
                        bireydir.
                        Uygulamayı indirip kayıt olan kullanıcı kayıt sırasında sizin referans kodunuzu kullanarak
                        kaydolursa referans
                        sayınız <i>1 artacaktır</i>. <b>1 kullanıcı en fazla 1 kişinin referans kodunu girebilir.</b>
                    </p>
                </div>
                <div id="what_is_reference1">
                    <h5>Referans Kodum Nerededir?</h5>
                    <p>Referans kodunuz <b>Profil</b> > <b>Referans Kodum</b> alanında bulunmaktadır. <br>
                        <b>Profil</b> > <b>Referans Kodu Gir</b> alanında farklı bir kişinin referans kodunu girebilirsiniz.
                    </p>
                </div>
                <div id="what_is_reference2">
                    <h5>Referans sınırı var mıdır?</h5>
                    <p>En fazla <i>100</i> kullanıcı sizin referans kodunuzu kullanarak size referans olabilir.
                    </p>
                </div>
                <div id="what_is_reference3">
                    <h5>Her referans için ne kadar ödül alırım?</h5>
                    <p>Referans kodunuzu kullanan her kullanıcı için 0.25₺ ödül alırsınız.
                    </p>
                </div>
                <div id="what_is_reference4">
                    <h5>Referans getirmek zorunlu mu?</h5>
                    <p>Her kullanıcı sadece 1 defa 5 referans getirmesi zorunludur. Daha sonraki ödeme talepleri için
                        tekrar 5 referans getirmesine gerek yoktur.
                    </p>
                </div>
                <div id="what_is_reference4">
                    <h5>Referanslarım Telefonunu onaylamak zorunda mı?</h5>
                    <p>Ödeme alabilmeniz için En Az 5 Mobil Onaylı referansınsa sahip olmanız gerekmektedir.
                    </p>
                </div>
                <hr style="border: 1px solid #f7f7f7">
                <div id="private_missions">
                    <h5>Özel görev nedir?</h5>
                    <p>Özel görevler sponsorlarımız tarafından verilen reklamlardır. Normal sorulara göre ödül ve xp
                        miktarları daha yüksektir.
                    </p>
                </div>
                <div id="private_missions1">
                    <h5>Özel görev ne zaman onaylanır?</h5>
                    <p>Özel görevler <b>ödeme bildiriminiz sırasında</b> kontrol edilir ve sponsorlarımızdan aldığımız
                        bilgilerle görevi tamamlayıp tamamlamadığınızı kontrol eder
                        ve durumunuza göre bakiyeniz güncellenir.
                    </p>
                </div>
                <div id="private_missions2">
                    <h5>Özel görevi yapmak zorunlu mudur?</h5>
                    <p>Özel görevi yapmanız zorunlu değildir fakat her kullanıcımıza yapmasını öneririz.
                        Özel görevi yapmadan "Tamamladım" ibaresi ile geçmek kullanım koşulları politikamıza aykırıdır.
                    </p>
                </div>
                <hr style="border: 1px solid #f7f7f7">
                <div id="banned_status">
                    <h5>Hangi durumlarda hesabım kapatılır?</h5>
                    <p>
                    <ul>
                        <li><a class="red-text">Hile Programları Kullanmak</a></li>
                        <li><a class="red-text">Reklam engelleyici kullanmak</a></li>
                        <li><a class="red-text">Birden fazla hesap açmak</a></li>
                        <li><a class="red-text">Referans hileleri kullanmaya çalışmak</a></li>
                        <li><a class="red-text">Kullanıcılara yanlış bilgi vermek</a> <i>(Referans kodumu gir 50₺ kazan gibi)</i></li>
                    </ul>
                    </p>
                </div>
                <hr style="border: 1px solid #f7f7f7">
                <div id="advertising1">
                    <h5>Reklam Kimliği Nedir?</h5>
                    <p>Reklam kimliği google tarafından sunulan, uygulama içi ve site reklamlarında kişiye bu kimlik doğrultusunda reklam gösterdiği takip sistemidir.
                    </p>
                </div>
                <div id="advertising2">
                    <h5>Reklam Kimliği Nasıl Sıfırlanır?</h5>
                    <a href="https://youtu.be/3qYFlN8x8LA">https://youtu.be/3qYFlN8x8LA</a>
                    <p><b>Ayarlar</b> > <b>Google</b> > <b>Reklamlar</b> alanına gidip <b>[Reklam Kimliğini Sıfırla]</b> dokunarak tıklayarak sıfırlayabilirsiniz. Yardımcı Video; </p>
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/3qYFlN8x8LA" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>

            </div>
        </div>
    </div>
    <script>
        $("a").on('click', function () {
            let href = $(this).attr('href');
            $(href).fadeOut(500).fadeIn(500);
        });
    </script>
@endsection
