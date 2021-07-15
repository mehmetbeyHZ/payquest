@extends('layouts.app')

@section('content')
    <div class="container">
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <style>.note-frame {
                background: #ffffff
            }</style>
        <div class="mt-3">

            <form action="#" id="imageUploadForm">
                <div class="file-field input-field d-flex">
                    <div class="answer_item">
                        <span>Öne Çıkan Görsel</span>
                        <input type="file" name="image" id="uploadFile"  accept="image/*" style="width: auto!important;">
                    </div>
                    <div class="ml-1" style="margin-top: auto;margin-bottom: auto">
                        <label class="file-path validate" id="form_image_uri">...</label>
                        <input class="file-path validate" type="hidden">
                    </div>
                    <div id="remove_image_area" class="answer_item c-pointer" style="margin-top: auto; margin-bottom: auto;margin-left: auto">
                        <label class="c-pointer">X</label>
                    </div>
                </div>
            </form>

            <form action="{{ route('admin.blogs.add.post') }}" method="post" class="submit">
                <input type="hidden" name="image" id="blog_image">
                <input type="text" name="title" placeholder="Başlık" >
                <textarea id="summernote" name="text"></textarea>
                <select name="is_published" id="" class="browser-default mt-1">
                    <option value="1">Yayınla</option>
                    <option value="2">Taslak Olarak Kaydet</option>
                </select>
                <button type="submit" class="btn btn_custom mt-1">Güncelle</button>
            </form>
        </div>
        <script>
            $('#summernote').summernote({
                height: 420,
                lang: 'tr-TR',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function (files, editor, welEditable) {
                        console.log(files);
                        if (files.length > 0)
                        {
                            for (let i = 0; i < files.length; i++)
                            {
                                sendFile(files[i], editor, welEditable);
                            }
                        }
                    }
                }
            });

            $("#remove_image_area").on('click',function (){
                $("input#blog_image").val('');
                $("label#form_image_uri").html('...');
            });

            $('#uploadFile').change(function(){
                ajaxFileUpload();
            });

            function ajaxFileUpload()
            {
                startLoading();
                let formData = new FormData(document.getElementById("imageUploadForm"));
                $.ajax({
                    type: "POST",
                    url : '{{ route('admin.upload_blog_image') }}',
                    cache:false,
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'JSON',
                    success: function(xhr){
                        endLoading();
                        $("input#blog_image").val(xhr.url);
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


            function sendFile(file, editor, welEditable) {
                startLoading();
                let data = new FormData();
                data.append("image", file);
                $.ajax({
                    data: data,
                    type: "POST",
                    url: '{{ route('admin.upload_blog_image') }}',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (xhr) {
                        var image = $('<img>').attr('src', xhr.url);
                        $('#summernote').summernote("insertNode", image[0]);
                        $("#loadingModal").modal('close');
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
                });
            }

        </script>
    </div>
@endsection
