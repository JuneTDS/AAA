var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

var latest_gst_rate = 0, count_billing_service_info_num = 0, tmp = [], total_claim_amount = 0;
var state_own_letterhead_checkbox = true;
var clearance_history_table = "";

$('[data-toggle="tooltip"]').tooltip();

function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        //console.log(data);
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
          //console.log(e);
          alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
        };
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: successRes,
            error: errorRes,
            dataType: type,
            timeout: 60000
        });

    }

}

function Client() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();

    this.getClientName = function() {
        var url = base_url+"/"+folder+"/"+'engagement/getEngagementPotentialClient';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.client_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.client_name').find("option:eq(0)").html("Select Client Name");
            // console.log(data);
            console.log(selected_el.company_code+"HERE");
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);

                    if(selected_el.company_code != null && key == selected_el.company_code)
                    {

                        option.attr('selected', 'selected');
                        $('.client_name').attr('disabled', true);
                        
                        
                    }
                    // if(selected_el.company_code != null)
                    // {

                    //     option.attr('value', selected_el.company_code).text(selected_el.company_name);
                    //     option.attr('selected', 'selected');
                    //     $('.client_name').attr('disabled', true);
                        
                        
                    // }
                    // console.log(option);
                    $('.client_name').append(option);
                });
                $('#client_name').select2();
                if(selected_el.company_code != null)
                {
                    var option = $('<option />');
                    option.attr('value', selected_el.company_code).text(selected_el.company_name);
                    option.attr('selected', 'selected');
                    $('.client_name').append(option);
                    $('.client_name').change();
                }
                
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getPicName = function() {
        var url = base_url+"/"+folder+"/"+'list_of_auditor/getPicName';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.our_auditor').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.our_auditor').find("option:eq(0)").html("Select Auditor Name");
            // console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_auditor_name != null && key == data.selected_auditor_name)
                    {
                        option.attr('selected', 'selected');
                        $('.our_auditor').attr('disabled', true);
                    }
                    $('.our_auditor').append(option);
                });
                // $('#our_auditor').select2();
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getAuditorName = function() {
        var url = base_url+"/"+folder+"/"+'list_of_auditor/getAuditorName';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.audit_firm_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.audit_firm_name').find("option:eq(0)").html("Select Audit Firm Name");
            // console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_auditor_name != null && key == data.selected_auditor_name)
                    {
                        option.attr('selected', 'selected');
                        $('.audit_firm_name').attr('disabled', true);
                    }
                    $('.audit_firm_name').append(option);
                });
                $('#audit_firm_name').select2({
                    escapeMarkup: function (markup) { return markup; },
                    language: {
                        noResults: function () {
                             return "<a href='"+auditor_url+"'>Audit firm not exist. Add here</a>";
                        }
                    }
                });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getFirmName = function() {
        var url = base_url+"/"+folder+"/"+'list_of_auditor/getFirmName';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.our_firm_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.our_firm_name').find("option:eq(0)").html("Select Our Firm Name");
            // console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_firm_name != null && key == data.selected_firm_name)
                    {
                        option.attr('selected', 'selected');
                        $('.our_firm_name').attr('disabled', true);
                    }
                    $('.our_firm_name').append(option);
                });
                $('#our_firm_name').select2();
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };


    // this.getAuthStatus = function() {
    //     var url = base_url+"/"+folder+"/"+'bank/getAuthStatus';
    //     //console.log(url);
    //     var method = "get";
    //     var data = {};
    //     $('.auth_status').find("option:eq(0)").html("Please wait..");
    //     call.send(data, url, method, function(data) {
    //         //console.log(data);
    //         $('.auth_status').find("option:eq(0)").html("Select Status:");
    //         //console.log(data);
    //         if(data.tp == 1){
    //             $.each(data['result'], function(key, val) {
    //                 var option = $('<option />');
    //                 console.log(data.select_auth_status);
    //                 option.attr('value', key).text(val);
    //                 if(data.select_auth_status != null && key == data.select_auth_status)
    //                 {
    //                     option.attr('selected', 'selected');
    //                 }
    //                 $('.auth_status').append(option);
    //             });
    //             //$(".nationality").prop("disabled",false);
    //         }
    //         else{
    //             alert(data.msg);
    //         }
    //     }); 
    // };

}

$(function() {
    var cn = new Client();
    cn.getClientName();
    // cn.getPicName();
    // cn.getAuditorName();
    // cn.getFirmName();
});

toastr.options = {
    "positionClass": "toast-bottom-right"
}

function formatDateFunc(date) {
    //console.log(date);
  var monthNames = [
    "01", "02", "03",
    "04", "05", "06", "07",
    "08", "09", "10",
    "11", "12"
  ];

  var day = date.getDate();
  //console.log(day.length);
  if(day.toString().length==1)  
  {
    day="0"+day;
  }
    
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + '/' + monthNames[monthIndex] + '/' + year;
}

$(document).ready(function() {
    $('#loadingBilling').hide();
    
    // console.log("nani");

     $('#create_first_clearance_letter_form').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        excluded: [':disabled', ':hidden', ':not(:visible)'],
        fields: {
            client_name: {
                row: '.input-group',
                validators: {
                    notEmpty: {
                        message: 'Client name is required and cannot be empty'
                    }
                }
            }
        }
    });
});


$('.send_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).datepicker("setDate", "0")
.on('changeDate', function (selected) {
    $('#create_first_clearance_letter_form').bootstrapValidator('revalidateField', 'send_date');
    //console.log($('.billing_date').val());
    // var auth_date = $('.billing_date').val();
    // get_gst_rate(billing_date);
    
    // $('#create_billing_form').formValidation('revalidateField', 'billing_date');
});

//console.log(billing_top_info);
// new Date('2015-1-1').to('dd.MM.yy')    
// $('[name="auth_date"]').val(edit_bank_auth[0]["auth_date"]);
// console.log(edit_bank_auth);
// if(edit_bank_auth != "")
// {
//    $('[name="auth_date1"]').val(edit_bank_auth[0]["auth_date"]); 
// }



$("[name='own_letterhead_checkbox']").bootstrapSwitch({
    state: state_own_letterhead_checkbox,
    size: 'normal',
    onColor: 'primary',
    onText: 'YES',
    offText: 'NO',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '75px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'


});

// Triggered on switch state change.
$("[name='own_letterhead_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    console.log(state); // true | false

    if(state == true)
    {
        $("[name='hidden_own_letterhead_checkbox']").val(1);
    }
    else
    {
        $("[name='hidden_own_letterhead_checkbox']").val(0);
    }
});


var service_array = "";
var service_category_array = "";
var unit_pricing = "";



$(".amount").on('change',function(){
    sum_total();
});
$(".rate").on('change',function(){
    sum_total();
});
$(".currency").on('change',function(){
    sum_total();
});




function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function delete_billing(element) {
    /*if(confirm("Delete This Record?"))
    {*/
        var tr = jQuery(element).parent().parent(),
            billing_service_id = tr.find('input[name="billing_service_id[]"]').val();

        tr.closest("DIV.tr").remove();
        //console.log($("#allotment_add > div").length);
        if($("#body_create_billing > div").length == 1)
        {
            if($('.delete_billing_button').css('display') == 'block')
            {
                $('.delete_billing_button').css('display','none');
            }
        }
        sum_total();
    //}
}





$(document).on('change','.claim_id',function(e){
    var checked = $(this).val();

    if ($(this).is(':checked')) {
        tmp.push(checked);
        total_claim_amount = total_claim_amount + parseFloat($(this).data('amount'));
    } else {
        tmp.splice($.inArray(checked, tmp),1);
        total_claim_amount = total_claim_amount - parseFloat($(this).data('amount'));
    }

    $(".total_selected").html(addCommas(total_claim_amount.toFixed(2)));
});

$(document).on('click',"#selectClaimList",function(e){
    $("#modal_claim_list").modal("hide");
    //console.log($("#service").includes("DISBURSEMENT - Printing"));
    var opt = 'DISBURSEMENTS';
    if ($('#service option:contains('+ opt +')').length) {
        $("select[name='service[0]']").select2("val", $("#service option:contains('DISBURSEMENTS')").val()).trigger('change');
        $("#amount").val(addCommas(total_claim_amount.toFixed(2)));
        //console.log($("#claim_service_id"));
        $("#claim_service_id").attr('value', JSON.stringify(tmp));
    }
    else
    {
        toastr.error("Please set the DISBURSEMENTS in Service Engagement inside Client module.", "Error");
    }
    
    //$("select[name='service[0]'] option:contains(DISBURSEMENT - Printing)").attr('selected', 'selected');
});

$(document).on('change','#create_billing_form #currency',function(e){
    //console.log($(this).val());
    if($(this).val() == "1")
    {
        $("#rate").val("1.0000");
    }
    $('#create_billing_form').formValidation('revalidateField', 'currency');
});




$(document).on("submit",function(e){
    
        e.preventDefault();
        var $form = $(e.target);
        

        if($('#create_first_clearance_letter_form').css('display') != 'none')
        {
            //$("#create_billing_form").formValidation('destroy');
            //$('[name="invoice_no"]').attr('disabled', false);
            // $('[name="billing_date"]').attr('disabled', false);
            // $('[name="address"]').attr('disabled', false);
            // $('.currency').attr('disabled', false);

            $('.client_name').attr('disabled', false);
            $('.audit_firm_name').attr('disabled', false);
            $('.our_firm_name').attr('disabled', false);
            //console.log($(".client_name option:selected").text());
            var selected_company_name = $(".client_name option:selected").text();
            $.ajax({
                type: 'POST',
                url: save_first_letter_url,
                data: $form.serialize() + '&company_name=' + encodeURIComponent(selected_company_name),
                dataType: 'json',
                success: function(response){
                    //console.log(response.Status);

                    // if (response.Status === 1) 
                    // {
                    //     toastr.success(response.message, response.title);
                    //     //console.log(response);
                    //     var getUrl = window.location;
                    //     var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/billings";
                    //     //console.log(baseUrl);
                    //     window.location.href = baseUrl;

                    // }
                 

                    if(response.status == "success")
                    {
                        $('.client_name').attr('disabled', true);
                        $('.audit_firm_name').attr('disabled', true);
                        $('.our_firm_name').attr('disabled', true);

                        if($("#resend_flag").val() == 1)
                        {
                            generate_first_clearance_letter(response.letter_id, $('.send_date').val());
                        }

                        window.location.href = auditor_url;
                        // console.log(bank_url);
                        
                    }
                    else
                    {
                        alert("Selected company may not assigned to any service or servicing firm. Failed to add first letter.");
                        window.location.href = auditor_url;
                    }
                 
                   
                    
                }
            });
        }
        else
        {
            toastr.error("Please set service engagement in this client.", "error");
        }
        

});

function generate_first_clearance_letter(letter_id, send_date)
{


    $.ajax({ //Upload common input
      url       : generate_first_letter_url,
      type      : "POST",
      dataType  : 'json',
      data  : {"letter_id": letter_id, "send_date": send_date},
      beforeSend: function()
        {
            $('#loadingMessage').show();
        },
      success   : function (response,data) {
        $('#loadingMessage').hide();
        //console.log(response.pdf_link);
        console.log("done");
            window.open(
              response.pdf_link,
              '_blank' // <- This is what makes it open in a new window.
            );

            // setTimeout(function(){ deletePDF(response.path); }, 5000);
        }
    })
}

function generate_auth(auth_id)
{
    // var auth_id     = div.find('.auth_id').val();
    // console.log(auth_id);

    $.ajax({ //Upload common input
      url       : generate_auth_document_url,
      type      : "POST",
      dataType  : 'json',
      data  : {"auth_id": auth_id},
      beforeSend: function()
        {
            $('#loadingMessage').show();
        },
      success   : function (response,data) {
        $('#loadingMessage').hide();
        //console.log(response.pdf_link);
        console.log("done");
            window.open(
              response.pdf_link,
              '_blank' // <- This is what makes it open in a new window.
            );

            setTimeout(function(){ deletePDF(response.path); }, 5000);
        }
    })
}

$(document).on('click',"#saveLetter",function(e){
    var bootstrapValidator = $("#create_first_clearance_letter_form").data('bootstrapValidator');
    bootstrapValidator.validate();
    if(bootstrapValidator.isValid())
        $("#create_first_clearance_letter_form").submit();
    else return;
    // $("#create_bank_auth_form").submit();
});


function ordinal_suffix_of(i) {
    var j = i % 10,
        k = i % 100;
    if (i == 1)
    {
        return i + "<sup>st</sup> letter";
    }
    if (j == 1 && k != 11) {
        return i + "<sup>st</sup> follow up";
    }
    if (j == 2 && k != 12) {
        return i + "<sup>nd</sup> follow up";
    }
    if (j == 3 && k != 13) {
        return i + "<sup>rd</sup> follow up";
    }
    return i + "<sup>th</sup> follow up";
}

$('.engagement_letter_date').datepicker({ 
    format: 'dd MM yyyy'
});

$('.fye_date').datepicker({ 
    format: 'dd MM yyyy'
});

$('.start_fye_date').datepicker({ 
    format: 'dd MM yyyy'
});

$(".client_name").change(function(){


    $('#loadingMessage').show();
    getEngagementLetterInterface();
    
});

if(selected_el != "")
{

    // $(".client_name").prop("disabled", true);
    $("#el_id").val(selected_el.id);
    $("#el_uen").val(selected_el.uen);
    $(".engagement_letter_date").val(selected_el.engagement_letter_date);
    $(".fye_date").val(selected_el.fye_date);
    $(".start_fye_date").val(selected_el.start_fye_date);
    $("#director_signing").val(selected_el.director_signing);
    // $(".client_name").trigger("change");
}

function getEngagementLetterInterface()
{

    $(".client_name").attr("disabled", false);
    var company_code_selected = $(".client_name :selected").val();
    var selected_el_id = selected_el.id;
    $("#body_engagement_letter tr").remove();
    console.log(company_code_selected);
    $.post(get_engagement_letter_detail_url, {company_code: company_code_selected, el_id: selected_el_id}, function(data){
        $('#loadingMessage').hide();
        if (selected_el != "")
        {
            $(".client_name").attr("disabled", true);
        }
        
        // console.log(JSON.parse(data));
        data = JSON.parse(data);

        var currency = data["currency"];
        var unit_pricing_name = data["unit_pricing_name"];
        var get_all_firm_info = data["get_all_firm_info"];
        var engagement_letter_info = data['engagement_letter_info'];

        if (selected_el == "")
        {
            if(data["client_info"] != false)
            {
                $('#el_uen').val(data["client_info"][0]["registration_no"]);

                $('.fye_date').datepicker({ 
                    format: 'dd MM yyyy',
                }).datepicker('setStartDate', "01/01/1920").datepicker('setDate', data["client_info"][0]["year_end"]);
            }
            else
            {
                $('.fye_date').datepicker({ 
                    format: 'dd MM yyyy',
                }).datepicker('setStartDate', "01/01/1920");
            }
        }


        $('.engagement_letter_date').datepicker({ 
            format: 'dd MM yyyy',
        }).datepicker('setStartDate', "01/01/1920");

        if(data["director_result_1"] != false)
        {
            $('#director_signing').val(data["director_result_1"]);
        }
        else
        {
            $('#director_signing').val();
        }

        if(engagement_letter_type_list)
        {
            

            for($i = 0; $i < engagement_letter_type_list.length; $i++)
            {
                $b =""; 
                $b += '<tr class="service_section">';
                $b += '<td style="text-align:center;"><input type="checkbox" class="selected_el_id" id="selected_el_id'+$i+'" name="selected_el_id[]" value="'+engagement_letter_type_list[$i]["id"]+'"><input class="form-control hidden_selected_el_id" id="hidden_selected_el_id'+$i+'" type="hidden" name="hidden_selected_el_id[]" value=""></td>';
                $b += '<td>'+engagement_letter_type_list[$i]["engagement_letter_list_name"]+'</td>';
                $b += '<td><select class="form-control" style="text-align:right;width: 100%;" name="currency[]" id="currency'+$i+'"><option value="0" >Select Currency</option></select></td>';
                $b += '<td><input class="form-control numberdes" type="text" name="fee[]" value="" id="fee'+$i+'" style="text-align:right;"></td>';
                $b += '<td><select class="form-control" style="text-align:right;width: 100%;" name="unit_pricing[]" id="unit_pricing'+$i+'"><option value="0" >Select Unit Pricing</option></select></td>';
                // $b += '<td><input class="form-control" type="text" name="unit_pricing[]" id="unit_pricing'+$i+'" value=""></td>';
                $b += '<td><select class="form-control" style="text-align:right;width: 100%;" name="servicing_firm[]" id="servicing_firm'+$i+'"><option value="0" >Select Servicing Firm</option></select></td>';
                $b += '</tr>';

                $("#body_engagement_letter").append($b);

                $.each(currency, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);

                    $("#currency"+$i).append(option);
                });

                $.each(unit_pricing_name, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);

                    $("#unit_pricing"+$i).append(option);
                });

                $.each(get_all_firm_info, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);

                    $("#servicing_firm"+$i).append(option);
                });

                if(data["get_service_proposal_service_info"] && !engagement_letter_info)
                {
                    for($b = 0; $b < data["get_service_proposal_service_info"].length; $b++)
                    {
                        if(data["get_service_proposal_service_info"][$b]["engagement_letter_list_id"] == engagement_letter_type_list[$i]["id"])
                        {
                            $("#selected_el_id"+$i).prop('checked', true);
                            //$("#trans_master_service_proposal_id").val(array_for_engagement_letter[0]["get_service_proposal_service_info"][$b]["id"]);
                            $("#hidden_selected_el_id"+$i).val(data["get_service_proposal_service_info"][$b]["engagement_letter_list_id"]);
                            $("#currency"+$i).val(data["get_service_proposal_service_info"][$b]["currency_id"]);
                            $("#fee"+$i).val(addCommas(data["get_service_proposal_service_info"][$b]["fee"]));
                            $("#unit_pricing"+$i).val(data["get_service_proposal_service_info"][$b]["unit_pricing"]);
                            $("#servicing_firm"+$i).val(data["get_service_proposal_service_info"][$b]["servicing_firm"]);
                        }
                    }
                }


                if(engagement_letter_info)
                {
                    for($b = 0; $b < engagement_letter_info.length; $b++)
                    {
                        if(engagement_letter_info[$b]["engagement_letter_type"] == engagement_letter_type_list[$i]["id"])
                        {
                            $("#selected_el_id"+$i).prop('checked', true);
                            $("#hidden_selected_el_id"+$i).val(engagement_letter_info[$b]["engagement_letter_type"]);
                            $("#currency"+$i).val(engagement_letter_info[$b]["currency_id"]);
                            $("#fee"+$i).val(addCommas(engagement_letter_info[$b]["fee"]));
                            $("#unit_pricing"+$i).val(engagement_letter_info[$b]["unit_pricing"]);
                            $("#servicing_firm"+$i).val(engagement_letter_info[$b]["servicing_firm"]);
                        }
                    }
                }
            }
        }

        $('.selected_el_id').change(function(e) {
            e.preventDefault();
            if($(this).is(":checked")) {
                $(this).parent().find(".hidden_selected_el_id").val($(this).val());
            }
            else
            {
                $(this).parent().find(".hidden_selected_el_id").val("");
            }
        });

    });


}



$(document).on('click',"#submitEngagementLetterInfo",function(e){
    $('#loadingmessage').show();
     $(".client_name").attr("disabled", false);

    $.ajax({ //Upload common input
      url: save_engagement_letter_url,
      type: "POST",
      data: $('form#engagement_letter_form').serialize() + '&company_name=' + encodeURIComponent($(".client_name :selected").text()),
      dataType: 'json',
      success: function (response,data) {
        $('#loadingmessage').hide();
        $(".client_name").attr("disabled", true);


          if (response.status == 1) 
          {
            generate_engagement_letter(response.el_id);
            toastr.success(response.message, response.title);
            window.location.href = engagement_url;
            // $("#body_appoint_new_director .row_appoint_new_director").remove();
            //console.log($("#transaction_trans #transaction_master_id"));
            //$(".transaction_change_FYE_id").val(response.transaction_change_FYE_id);
            $("#transaction_trans #transaction_code").val(response.transaction_code);
            $("#transaction_trans #transaction_master_id").val(response.transaction_master_id);
            // $(".dropdown_client_name").prop("disabled", true);
            //getChangeRegOfisInterface(response.transaction_change_regis_office_address);
          }
        }
    });
});


function generate_engagement_letter(el_id)
{
    $('#loadingMessage').show();
    // console.log(auth_id);

    $.ajax({ //Upload common input
      url       : generate_engagement_letter_url,
      type      : "POST",
      dataType  : 'json',
      data  : {"el_id": el_id},
      beforeSend: function()
        {
            $('#loadingMessage').show();
        },
      success   : function (response) {
        $('#loadingMessage').hide();
        //console.log(response.pdf_link);
        console.log(response.file_link);
        window.open(
          response.file_link,
          '_blank' // <- This is what makes it open in a new window.
        );
            // location.href = response.file_name;

            // setTimeout(function(){ deletePDF(response.path); }, 5000);
        }
    })
}