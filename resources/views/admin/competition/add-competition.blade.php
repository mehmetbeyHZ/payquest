@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="card wg_shadow">
            <div class="card-content">
                <div class="d-flex">
                    <div style="width: 100px; height: 100px; border: 2px solid#dbdbdb;border-radius: 4px;" >
                        <div style="width: 100px; height: 100px;max-height: 100%;max-width: 100%;">
                            <img src="" alt="" id="preview_image"
                                 style="width: 100px;height: 100px;max-height: 100%;max-width: 100%;">
                        </div>
                    </div>
                    <div style="width: 100%;" class="ml-1">
                        <form action="#" id="imageUploadForm">
                            <div class="file-field input-field d-flex">
                                <div class="answer_item">
                                    <span>Yükle</span>
                                    <input type="file" name="image" id="uploadFile" accept="image/*" style="width: auto!important;">
                                </div>
                                <div class="ml-1" style="margin-top: auto;margin-bottom: auto; max-width: 40%;word-wrap: break-word;overflow:hidden; text-overflow:  ellipsis;white-space: nowrap">
                                    <label class="file-path validate" id="form_image_uri">...</label>
                                    <input class="file-path validate" type="hidden">
                                </div>
                            </div>
                        </form>
                        <div id="remove_image_area" class="answer_item c-pointer"
                             style="margin-top: auto; margin-bottom: auto;margin-left: auto">
                            <label class="c-pointer">Görseli Kaldır</label>
                        </div>
                        <a class="answer_item" id="selectFromGallery" data-route="{{ route('admin.gallery.images') }}">Galeriden Seç</a>

                    </div>
                </div>
            </div>
        </div>

        <div class="card wg_shadow">
            <div class="card-content">

                <form action="{{ route('admin.competitions.add.post') }}" method="post" class="submit">
                    @csrf
                    <input type="hidden" id="competition_image" value="" name="competition_image">
                    <div class="field">
                        <label for="">Yarışma başlığı</label>
                        <input type="text" placeholder="Yarışma başlığı" name="competition_title"/>
                    </div>
                    <div class="field">
                        <label for="">Yarışma açıklaması</label>
                        <textarea style="height: 80px" placeholder="Yarışma açıklaması"
                                  name="competition_description"></textarea>
                    </div>

                    <div class="field">
                        <label for="">Kayıt ücreti</label>
                        <input type="number" placeholder="Kayıt ücreti" name="registration_fee" step="any"/>
                    </div>

                    <div class="field">
                        <label for="">Ödül</label>
                        <input type="number" placeholder="Ödül" name="award" step="any"/>
                    </div>

                    <div class="field">
                        <label for="">Kayıt Durumu</label>
                        <select name="can_register" id="" class="browser-default">
                            <option value="1">Kayıt Açık</option>
                            <option value="2">Kayıt Kapalı</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="">Toplam kazanacak kişi</label>
                        <input type="number" placeholder="Toplam kazanacak kişi" name="total_winner"/>
                    </div>


                    <div class="field">
                        <label for="">Maximum katılım</label>
                        <input type="number" placeholder="Maximum katılım" name="max_users"/>
                    </div>

                    <div id="start_date" class="field">
                        <label>Başlangıç tarihi/Saati</label>
                        <div class="d-flex d-space-between">
                            <input type="text" name="start_date" class="datepicker mr-1" placeholder="Başlangıç tarihi"
                                   autocomplete="off">
                            <input type="text" name="start_time" class="timepicker ml-1" placeholder="Başlangıç saati"
                                   autocomplete="off">
                        </div>
                    </div>


                    <div id="start_date" class="field">
                        <label>Son kayıt tarihi/Saati</label>
                        <div class="d-flex d-space-between">
                            <input type="text" name="last_register_date" class="datepicker mr-1"
                                   placeholder="Son kayıt tarihi" autocomplete="off">
                            <input type="text" name="last_register_time" class="timepicker ml-1"
                                   placeholder="Son kayıt saati" autocomplete="off">
                        </div>
                    </div>
                    <button type="submit" class="btn btn_custom">Kaydet</button>
                </form>

            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('#uploadFile').change(function () {
                ajaxFileUpload();
            });

            $("#remove_image_area").on('click',function (e){
                $("input#competition_image").val('');
                $("img#preview_image").attr('src','');
                $("label#form_image_uri").html('...');
            });

            function ajaxFileUpload() {
                startLoading();
                let formData = new FormData(document.getElementById("imageUploadForm"));
                $.ajax({
                    type: "POST",
                    url: '{{ route('admin.upload_blog_image') }}',
                    cache: false,
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'JSON',
                    success: function (xhr) {
                        endLoading();
                        $("input#competition_image").val(xhr.url);
                        $("img#preview_image").attr('src', xhr.url);
                        $("label#form_image_uri").html(xhr.url);
                    },
                    error: function () {
                        endLoading();
                        if (XMLHttpRequest.hasOwnProperty("responseJSON")) {
                            if (XMLHttpRequest.responseJSON.hasOwnProperty("message")) {
                                M.toast({html: XMLHttpRequest.responseJSON.message});

                            }
                            if (XMLHttpRequest.responseJSON.hasOwnProperty("redirect")) {
                                window.parent.location.href = XMLHttpRequest.responseJSON.redirect;
                            }
                        }
                    }
                })
            }

            $("a#selectFromGallery").on('click', function (e) {
                e.preventDefault();
                request(null, {address: $(this).attr('data-route'), data: {limit: 10}}, images_listed)
            });
            $("body").delegate('#remove_image','click',function (e){
                let id = $(this).attr('data-id');
                request(null,{address: '{{ route('admin.gallery.images.delete') }}', data : {id:id}},image_deleted)
            });
            $("body").delegate('#use_image','click',function (e){
                e.preventDefault();
                let url = $(this).attr('data-url')
                $("input#competition_image").val(url);
                $("img#preview_image").attr('src',url);
                $("label#form_image_uri").html(url);
                $("#modal1").modal('close');
            })

        });

        function image_deleted(xhr)
        {
            if (xhr.status === 'ok')
            {
                $("div#myImage"+xhr.upload_id).remove();
            }
        }

        function images_listed(items) {
            const area = $("div#imagesList");
            area.html('');
            for (let i = 0; i < items.length; i++) {
                area.append(`<div class="col s6 m4" id="myImage${items[i].upload_id}">
                  <div class="card wg_shadow">
                    <div class="card-image">
                      <img src="${items[i].path}" height="160px">
                    </div>
                    <div class="card-content">
                      <p>${items[i].type} <br> <label>${items[i].created_at}</label></p>
                    </div>
                    <div class="card-action">
                      <a class="answer_item c-pointer black-text" id="use_image" data-url="${items[i].path}">Kullan</a>
                      <a class="answer_item white-text c-pointer red" id="remove_image" data-id="${items[i].upload_id}">Sil</a>
                    </div>
                  </div>
                </div>`)
            }
            $("#modal1").modal('open');
        }

    </script>

    <div id="modal1" class="modal">
        <div class="modal-content">
            <h4>Galeri</h4>
            <div id="imagesList" class="row"></div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Kapat</a>
        </div>
    </div>

@endsection
