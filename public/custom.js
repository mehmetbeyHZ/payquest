$(function () {
    $('.sidenav').sidenav();
    $('.modal').modal();
    $('.tabs').tabs();
    $('.dropdown-trigger').dropdown();
    $('.carousel').carousel({fullWidth:true});
    $('.parallax').parallax();
    $('.collapsible').collapsible();
    $('.datepicker').datepicker({
        i18n : {
            months: ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'],
            monthsShort: ['Ock','Şub','Mar','Nis','May','Haz','Tem','Ağus','Eyl','Ekm','Ksm','Arlk'],
            cancel: 'İptal',
            clear: 'Temizle',
            done: 'Tamam',
            weekdays : ['Pazar','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi'],
            weekdaysShort: ['Pzr','Pzt','Sal','Çar','Per','Cum','Cts']
        },
        format: 'yyyy-m-d',
        clear: 'Temizle',
        cancel: 'İptal'
    });
    $('.timepicker').timepicker({
        twelveHour: false
    });
    $("img.lazy").lazyload({effect: "fadeIn"});
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("input#verify_profile").on('change', function () {
        let is_verified = $(this).is(':checked') ? 1 : 0;
        let id = $(this).attr("data-id");
        let address = $(this).attr('data-route');
        request(null, {
            address, data: {
                id,
                is_verified
            }
        })
    })

    $("#loadingModal").modal({dismissible: false, startingTop: '40%', endingTop: '40%'});

    $("form.submit").on('submit', function (e) {
        e.preventDefault();
        request($(this));
    });

    $("form.addQuestion").on('submit', function (e) {
        e.preventDefault();
        const address = $(this).attr("action");
        let serializeData = $(this).serializeArray();
        serializeData.push({name: 'answer_list', value: JSON.stringify(getAnswerFields())})
        request(null, {address, data: serializeData})
    });

    $("a#remove_question").on('click',function (e) {
        const address = $(this).attr("data-route");
        let mission_id = $(this).attr("data-missionid");
        request(null,{address:address,data:{mission_id:mission_id}},remove_question_cb(mission_id));
    })


    $("select#ticketStatus").on('change', function () {
        let is_closed = $(this).val();
        let action = $(this).attr("data-action");
        request(null, {address: action, data: {is_closed: is_closed}})
    });

    $("select#suspendUser").on('change',function (){
        let is_banned = $(this).val();
        let action = $(this).attr("data-action");
        let id = $(this).attr("data-id");
        request(null, {address: action, data: {is_banned: is_banned,id:id}})
    });


    $("a#editPaymentRequest").on('click', function (e) {
        e.preventDefault();
        startLoading();
        let action = $(this).attr('data-action');
        $.ajax({
            url: action,
            method: "GET",
            dataType: "JSON",
            success: function (xhr) {
                endLoading()
                $("#paymentRequestModal").modal('open');
                $("img#user_request_avatar").attr('src', xhr.user.avatar);
                $("#pRequestName").html(xhr.user.name);
                $("select#pRequestStatus").val(xhr.is_confirmed).change();
                $("#iban_modal_area").html(xhr.iban);
                $("#pBankName").html(xhr.bank.bank_name)
                $("#pUserBalance").html("Bakiye: " + xhr.user.balance + " ₺")
                $("#pRequestQuantity").html("Talep: " + xhr.quantity + "₺")
                $("#paymentRequestId").val(xhr.request_id)
            }, error: function (xhr) {
                endLoading()
                M.toast({html: 'Server Error'})
            }
        });
    });


    $('#addAnswerInput').keypress(function (e) {
        if (e.which === 13) {
            e.preventDefault();
            addAnswer();
            $(this).val("")
        }
    });
    $("input#is_custom").on('click', function () {
        let isChecked = $(this).is(":checked");
        if (isChecked) {
            $("div#extraQuestionArea").removeClass('hidden')
        } else {
            $("div#extraQuestionArea").addClass('hidden')
        }
    })

    $("body").delegate("#remove_answer", 'click', function (e) {
        e.preventDefault();
        $(this).parent().remove();
    });

    $("#addAnswerFieldManual").on('click', function (e) {
        e.preventDefault();
        addAnswer()
        $("#addAnswerInput").val('')
    })

    $("input#select_all_missions").on('click', function () {
        let isChecked = $(this).is(":checked");
        $("input.mission_check").prop("checked", isChecked)
    });

    $("a#mission_check_confirm").on('click', function (e) {
        let type = $(this).attr("data-type");
        let route = $(this).attr("data-route");
        let ids = selectedCheckingMissions();
        request(null, {address: route, data: {type,ids}},endMCStatus(ids))
    })

    $("a#changeMCStatus").on('click',function () {
        let type = $(this).attr("data-type");
        let route = $(this).attr("data-route");
        let ids = [$(this).attr("data-id")];
        request(null, {address: route, data: {type,ids}},endMCStatus(ids))
    });

    $("a#remove_competition_modal").on('click',function (e){
       $("#remove_competition").attr('data-id',$(this).attr('data-id'));
       $("#confirm_remove_competition").modal('open');
    });

    $("a#remove_competition").on('click',function (e){
        e.preventDefault();
        let address = $(this).attr('data-route');
        let id = $(this).attr('data-id');
        request(null,{address:address,data:{id:id}},remove_competition_cb);
    })

});

function remove_competition_cb(xhr)
{
    if (xhr.status === 'ok' && xhr.hasOwnProperty('competition'))
    {
        $("tr#competition_"+xhr.competition).remove();
    }
}

function endMCStatus(ids) {
    for (let i = 0; i < ids.length; i++)
    {
        $("tr#check_"+ids[i]).remove()
    }
}


function remove_question_cb(mission_id)
{
    $("tr#mission_item_"+mission_id).remove();
}

function random_rgba() {
    var num = Math.round(0xffffff * Math.random());
    var r = num >> 16;
    var g = num >> 8 & 255;
    var b = num & 255;
    return 'rgb(' + r + ', ' + g + ', ' + b + ')';
}

function generate_random_rgba(count) {
    let colors = [];
    for(let i = 0; i < count; i++)
    {
        colors.push(random_rgba());
    }
    return colors;
}

function startLoading() {
    $("#loadingModal").modal('open');
}

function endLoading() {
    $("#loadingModal").modal('close');
}

function request(parent, postData = {},callback = null) {
    $("#loadingModal").modal('open');
    const address = (parent === null && postData.hasOwnProperty("address")) ? postData.address : parent.attr("action");
    const data = (parent === null && postData.hasOwnProperty("data")) ? postData.data : parent.serializeArray();

    $.ajax({
        url: address,
        type: "POST",
        data: data,
        dataType: 'JSON',
        success: function (xhr) {
            $("#loadingModal").modal('close');
            if (xhr.hasOwnProperty("message"))
            {
                M.toast({html: xhr.message});
            }
            if (xhr.redirect) {
                window.parent.location.href = xhr.redirect
            }

            if (callback != null)
            {
                callback(xhr);
            }

        }, error: function (XMLHttpRequest, textStatus, errorThrown) {
            $("#loadingModal").modal('close');
            if (XMLHttpRequest.hasOwnProperty("responseJSON")) {
                if (XMLHttpRequest.responseJSON.hasOwnProperty("errors"))
                {
                    for (const [key, value] of Object.entries(XMLHttpRequest.responseJSON.errors)) {
                        M.toast({html: value[0]});
                        $("[name="+key+"]").fadeOut();
                        $("[name="+key+"]").fadeIn();
                    }
                }else{
                    if (XMLHttpRequest.responseJSON.hasOwnProperty("message")) {
                        M.toast({html: XMLHttpRequest.responseJSON.message});

                    }
                }
                if (XMLHttpRequest.responseJSON.hasOwnProperty("redirect")) {
                    window.parent.location.href = XMLHttpRequest.responseJSON.redirect;
                }
            }
        }
    });
}

function addAnswer() {
    let answer = $("#addAnswerInput").val();
    if (answer === "") {
        return;
    }
    $("div#question_answer_field").append(`<div class="answer_list wg_shadow pd-2 mb-2 d-flex d-space-between">
     <label><input class="with-gap checkAnswerItem" name="answer" type="radio" value="${answer}" /><span>${answer}</span></label>
     <a id="remove_answer" class="c-pointer">Sil</a>
    </div>`);
}

function getAnswerFields() {
    let field = [];
    $("input.checkAnswerItem").each(function () {
        field.push($(this).attr('value').trim())
    })
    return field
}

function selectedCheckingMissions() {
    let data = [];
    $.each($("input.mission_check"),function () {
        const item = $(this);
        if (item.is(":checked"))
        {
            data.push(item.attr("data-id"));
        }
    });
    return data;
}
