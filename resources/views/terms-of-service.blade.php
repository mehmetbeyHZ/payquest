@extends('layouts.web')
@section('extra_title') - Kullanım Koşulları@endsection
@section('content')
    <h1 class="center">Kullanım Koşulları</h1>
    <div class="container">
        <div class="card wg_shadow" style="width: 700px!important;max-width: 100%;margin-left: auto;margin-right: auto">
            <div class="card-content">

                <h6><b>Payquestion'a Hoş Geldiniz!</b></h6> <br>
                <p>Payquestion kullanımınızın tabi olduğu bu Kullanım Koşulları, Payquest Bilgi Yarışması Hizmeti
                    hakkında aşağıda
                    belirtilen bilgileri sağlamaktadır. Bir Payquestion hesabı oluşturduğunuzda veya Payquestion'ı
                    kullandığınızda bu koşulları kabul etmiş sayılırsınız.</p>


                <div class="mt-2" id="blocking">
                    <h6><b>Hesabınızın Devre Dışı Bırakılması veya Kapatılması - Bakiyenizin Sıfırlanması</b></h6>
                    <p> > Kullanıcılara yanıltıcı bilgi verme, referans kodunu yanlış bilgi vererek girdime vb.
                        durumlarda
                        olayı gerçekleştiren kullanıcının hesabındaki bakiyeleri silme, sıfırlama; referanslarını silme
                        ve referanslarından kazandıkları ödülleri tamamen sıfırlama hakkımızı saklı tutarız.
                    </p>
                    <p>
                        > Hile programları kullanmak, reklamları izlememek hile yapmaya yeltenmek de hesabınızın
                        kapatılması için gerekli koşullardan biridir.
                    </p>
                    <p>
                        > Yöneticilere ve uygulamadaki editörlere sosyal medyada, destek talebi alanında veya
                        herhangi bir yerde hakarette bulunmak. Kötü söz söylemleri, yanıltıcı içerikler paylaşmak
                        ve diğer kullanıcıları da buna teşvik etmeye çalışmak.
                    </p>
                </div>
                <div class="mt-2">
                    <h6><b>Soru Ödülleri ve XP'ler</b></h6>
                    <p>
                        > Sorular ve xp miktarları soru tiplerine göre belirlenmiş olup her an değişikliğe
                        gidilebilir. Soru fiyatları sabit değildir ve fiyatları yükselip alçalabilir kullanıcı bununla
                        ilgili
                        hiçbir şekilde hak talebinde bulunamaz. Kullanıcının bakiyesi önceki sorular için hiçbir şekilde
                        düşürülmez.
                    </p>
                    <p>
                        > Level sistemi değişmeye açıktır, soru tipleri ve ücretleri buna bağlı değişebilir, içerik
                        güncellemeleri olabilir
                        bu tür güncellemelerde kullanıcı kazanmadığı bir şey için hak talebinde bulunamaz.
                    </p>
                    <p>
                        > Payquestion bir bilgi yarışmasıdır, ilerleyen seviyelerde sorular zorlaşabilir, soru bakiyeleri ve limitleri xp değerleri değişebilir
                        bu değişimler sabit değildir soru tipine ve zaman dilimlerine bağlı soru değerleri, soru süresi, xp miktarları değişebilir.
                    </p>

                </div>
                <div class="mt-2">
                    <h6><b>Özel Görevler</b></h6>
                    <p>
                        > Kullanıcı özel görevleri yapmakla yükümlüdür. Özel görevi "Tamamladım" ibaresine
                        dokunup yapmayanlar için yanıltıcı bilgi verme <a href="#blocking">Hesabınızın kapatılması</a>
                        koşullarından kapatılabilir. Bu durumda kullanıcı hiçbir şekilde hak talep edemez.
                        Kullanıcı görevi yapmayacağı beklenmedik durumlarda veya özel göreve erişim sağlayamadığı
                        durumlarda özel görevin süresinin dolmasını veya yönetici tarafından görevin kaldırılmasını beklemelidir.
                    </p>
                </div>
                <div class="mt-2">
                    <h6><b>Ödeme Koşulları</b></h6>
                    <p>Her kullanıcı ayda 1 defa ödeme bildirimi yapma hakkına sahiptir.
                        Ödeme bildirimini hatalı yapmak, hatalı banka bilgisi göndermek tamamen kullanıcının
                        sorumluluğundadır. Hatalı banka bilgilerinde gönderimler telafi edilmez ve kullanıcı
                        bununla ilgili hiçbir şekilde hak talebinde bulunamaz.
                    </p>
                    <p>
                        Kullanıcılarımızdan hiçbir şekilde ödeme almayız. Payquestion uygulamasını kullanmak tamamen ücretsizdir. Uygulama içi satın alma, paket işlemleri
                        uygulama içinde hiçbir şekilde yoktur. Bu sebeple kullanıcılarımıza verdiğimiz ödüller ve ücretler
                        tamamen kontrollü koşullar altında gerçekleşir, politikalar incelenir, şüpheli hesaplarda ödemeler ayın 1 ile 10'u arasını
                        geçebilir. Kullanıcının hiçbir şekilde "gerçek" ödeme işlemi
                        yapmadığından bu sürenin uzaması ile ilgili hiçbir şekilde hak talebinde bulunamaz.
                    </p>
                </div>

                <label>Payquestion Uygulaması yukarıdaki kullanım koşullarını değiştirme haklarına sahiptir.</label>
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
