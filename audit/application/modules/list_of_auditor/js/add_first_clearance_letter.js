var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

var latest_gst_rate = 0, count_billing_service_info_num = 0, tmp = [], total_claim_amount = 0;
var state_own_letterhead_checkbox = true;
var clearance_history_table = "";

if($("#resend_flag").val() == 0)
{
    $("#date_tr").hide();
    $('.fye_date').attr('disabled', true);
    $("#saveLetter").text("Save");
    // $("#saveLetter").hide();
}
else if($("#resend_flag").val() != 0)
{
    $("#date_tr").show();
    // $("#saveLetter").text("Save and Resend");
}

if($("#resend_flag").val() == 2)
{
    // $("#date_tr").hide();
    $("#saveLetter").text("Save and Resend");
}

$("#clearance_history_table .tr_clearance_history").remove();

if(clearance_history_list != "")
{
    for(var a = 0; a < clearance_history_list.length; a++)
    {
        clearance_history_table += '<tr class="tr_clearance_history">'+
                    
                    '<td class="text-center" style="width:35%;"><a href="'+ base_url + '/'+folder+'/document/'+clearance_history_list[a].file_path+'/'+clearance_history_list[a].file_name+'" target="_blank" >'+ordinal_suffix_of(a+1)+'</a></td>'+
                    '<td class="text-center" style="width:65%">'+clearance_history_list[a].created_at+'</td>'+
                 '</tr>';
    }
}
else
{
    clearance_history_table = '<tr class="tr_clearance_history"><td align="center" colspan="2"><p style="margin-top:10px;">No history to display</p></td></tr>'

}

$(clearance_history_table).appendTo('#clearance_history_table');

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
        var url = base_url+"/"+folder+"/"+'list_of_auditor/getPotentialClient';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.client_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.client_name').find("option:eq(0)").html("Select Client Name");
            // console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_potential_client != null && key == data.selected_potential_client)
                    {
                        option.attr('selected', 'selected');
                        $('.client_name').attr('disabled', true);
                    }
                    // console.log(option);
                    $('.client_name').append(option);

                    $("#client_name").append($("#client_name option:gt(0)").sort(function (a, b) {
                        return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                    }));
                });
                $('#client_name').select2();
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

                    $(".our_auditor").append($(".our_auditor option:gt(0)").sort(function (a, b) {
                        return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                    }));
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

                    $(".audit_firm_name").append($(".audit_firm_name option:gt(0)").sort(function (a, b) {
                        return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                    }));
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

                    $(".our_firm_name").append($(".our_firm_name option:gt(0)").sort(function (a, b) {
                        return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                    }));
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
    cn.getPicName();
    cn.getAuditorName();
    cn.getFirmName();
    // cn.getAuthStatus();
    //cn.frequency();
    //cn.type_of_day();
    //cm.getDirectorSignature1();
});

toastr.options = {
    "positionClass": "toast-bottom-right"
}

function optionCheckService(service_element) 
{
    var tr = jQuery(service_element).parent().parent();
    console.log(jQuery(service_element).val());
    if(jQuery(service_element).val() == "1" || jQuery(service_element).val() == "0")
    {
        $(".recurring_part").hide();
        $("#type_of_day").prop("disabled", true);
        $("#days").prop("disabled", true);
        $("#from").prop("disabled", true);
        $("#to").prop("disabled", true);
    }
    else
    {
        $(".recurring_part").show();
        $("#type_of_day").prop("disabled", false);
        $("#days").prop("disabled", false);
        $("#from").prop("disabled", false);
        $("#to").prop("disabled", false);
    }
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
            },
            audit_firm_name: {
                row: '.input-group',
                validators: {
                    notEmpty: {
                        message: 'Audit firm name is required and cannot be empty'
                    }
                }
            },
            our_firm_name: {
                row: '.input-group',
                validators: {
                    notEmpty: {
                        message: 'Our firm is required and cannot be empty'
                    }
                }
            },
            fye_date: {
                row: '.input-group',
                validators: {
                    notEmpty: {
                        message: 'Financial year end date is required and cannot be empty'
                    }
                }
            },
            send_date: {
                validators: {
                    notEmpty: {
                        message: 'Send date is required and cannot be empty'
                    }
                }
            }
        }
    });
});

$('.fye_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).on('changeDate', function (selected) {
    $('#create_first_clearance_letter_form').bootstrapValidator('revalidateField', 'fye_date');
    //console.log($('.billing_date').val());
    // var auth_date = $('.billing_date').val();
    // get_gst_rate(billing_date);
    
    // $('#create_billing_form').formValidation('revalidateField', 'billing_date');
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

if(edit_first_letter != "")
{
   $('[name="fye_date"]').val(edit_first_letter[0]["fye_date"]); 
}

if(edit_first_letter != "")
{
   $('[name="send_date"]').val(edit_first_letter[0]["send_date"]); 
}



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



$(document).on('change','#service',function(e){
    var num = $(this).parent().parent().parent().attr("num");
    
    var descriptionValue = $(this).find(':selected').data('description');
    var amountValue = $(this).find(':selected').data('amount');
    // var currencyValue = $(this).find(':selected').data('currency');
    var unit_pricingValue = $(this).find(':selected').data('unit_pricing');

    $(this).parent().parent().parent().find('#invoice_description').text(descriptionValue);
    $(this).parent().parent().parent().find('#amount').val(addCommas(amountValue));
    //$(this).parent().parent().parent().find('#currency').val(currencyValue);
    $(this).parent().parent().parent().find('#unit_pricing').val(addCommas(unit_pricingValue));

    $('#create_billing_form').formValidation('revalidateField', 'service['+num+']');
    $('#create_billing_form').formValidation('revalidateField', 'invoice_description['+num+']');
    $('#create_billing_form').formValidation('revalidateField', 'amount['+num+']');
    $('#create_billing_form').formValidation('revalidateField', 'unit_pricing['+num+']');
    sum_total();
});



//$count_billing_service_info = 1;
$(document).on('click',"#billing_service_info_Add",function() {
    
    $a=""; 
    /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
    $a += '<div class="tr editing tr_billing" method="post" name="form'+$count_billing_service_info+'" id="form'+$count_billing_service_info+'" num="'+$count_billing_service_info+'">';
    $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_service_info+']" id="client_billing_info_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="claim_service_id['+$count_billing_service_info+']" id="claim_service_id" value=""/></div>';
    $a += '<div class="td"><div class="select-input-group"><select class="input-sm form-control" name="service['+$count_billing_service_info+']" id="service" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+$count_billing_service_info+']" id="payment_voucher_type" value=""/><div id="form_service"></div></div></div>';
    $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div></div>';
    $a += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+$count_billing_service_info+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
    $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_billing_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select><div id="form_unit_pricing"></div></div></div>';
    /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_billing_info(this);">Save</button></div></div>';*/
    $a += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: block;">Delete</button></div>';
    $a += '</div>';

    /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
    $("#body_create_billing").append($a); 

    if($("#body_create_billing > div").length > 1)
    {
        $('.delete_billing_button').css('display','block');
    }

    $('.period_start_date').datepicker({ 
        dateFormat:'dd/mm/yyyy',
    }).datepicker('setStartDate', "01/01/1920");

    $('.period_end_date').datepicker({ 
        dateFormat:'dd/mm/yyyy',
    }).datepicker('setStartDate', "01/01/1920");

    $.each(unit_pricing, function(key, val) {
        //console.log(val['unit_pricing_name']);
        var option = $('<option />');
        option.attr('value', val['id']).text(val['unit_pricing_name']);
        
        $("#form"+$count_billing_service_info+" #unit_pricing").append(option);
    });

    var category_description = '';
    var optgroup = '';

    for(var t = 0; t < service_category_array.length; t++)
    {
        // if(category_description != service_category_array[t]['category_description'])
        // {
            if(category_description != service_category_array[t]['category_description'])
            {
                if(optgroup != '')
                {
                    $("#form"+$count_billing_service_info+" #service").append(optgroup);
                }
                optgroup = $('<optgroup label="' + service_category_array[t]['category_description'] + '" />');
                //console.log(service_category_array[t]['category_description']);
            }

            category_description = service_category_array[t]['category_description'];
            
            for(var h = 0; h < service_array.length; h++)
            {
                if(category_description == service_array[h]['category_description'])
                {
                    //console.log(service_array[h]['service_name']);
                    var option = $('<option />');
                    option.attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']).appendTo(optgroup);
                }
            }
            //}
        

        
    }
    $("#form"+$count_billing_service_info+" #service").append(optgroup);   

    $("#form"+$count_billing_service_info+" #service").select2();
    $('#create_billing_form').formValidation('addField', 'service['+$count_billing_service_info+']', service);
    $('#create_billing_form').formValidation('addField', 'invoice_description['+$count_billing_service_info+']', invoice_description);
    $('#create_billing_form').formValidation('addField', 'amount['+$count_billing_service_info+']', amount);
    $('#create_billing_form').formValidation('addField', 'unit_pricing['+$count_billing_service_info+']', validate_unit_pricing);

    $count_billing_service_info++;
});


$(document).on('change','#create_billing_form #client_name',function(e){
    showRow();
});

$(document).on('change','#create_billing_form #currency',function(e){
    showRow();
});

function showRow(){
    var company_code = $('#client_name option:selected').val();

    //console.log(company_code);
    if($("#currency option:selected").val() == 0)
    {
        toastr.error("Please select a currency.", "Error");
    }
    else
    {
        $.ajax({
            type: "POST",
            url: "billings/get_company_service",
            data: {"company_code":company_code, "currency": $("#currency option:selected").val()}, // <--- THIS IS THE CHANGE
            dataType: "json",
            success: function(response){
                $(".tr_billing").remove();
                console.log(response);
                if(response.Status == 1)
                {
                    if(company_code == 0)
                    {
                        $('[name="address"]').val("");
                        $('[name="hidden_postal_code"]').val("");
                        $('[name="hidden_street_name"]').val("");
                        $('[name="hidden_building_name"]').val("");
                        $('[name="hidden_unit_no1"]').val("");
                        $('[name="hidden_unit_no2"]').val("");
                        $('#create_billing_form').formValidation('revalidateField', 'address');
                        $("#address").attr('readOnly', false);
                    }
                    else
                    {
                        $('[name="address"]').val(response.address);
                        $('[name="hidden_postal_code"]').val(response.postal_code);
                        $('[name="hidden_street_name"]').val(response.street_name);
                        $('[name="hidden_building_name"]').val(response.building_name);
                        $('[name="hidden_unit_no1"]').val(response.unit_no1);
                        $('[name="hidden_unit_no2"]').val(response.unit_no2);
                        $('#create_billing_form').formValidation('revalidateField', 'address');
                        $("#address").attr('readOnly', true);
                    }
                    

                    service_array = response.service;
                    service_category_array = response.selected_billing_info_service_category;
                    unit_pricing = response.unit_pricing;
                    claim_result = response.claim_result;

                    if(response.service.length != 0)
                    {
                        $('#create_billing_service').show();
                        $('#body_create_billing').show();
                        $('#sub_total_create_billing').show();
                        $('#gst_create_billing').show();
                        $('#grand_total_create_billing').show();
                    }
                    else
                    {
                        $('#create_billing_service').hide();
                        $('#body_create_billing').hide();
                        $('#sub_total_create_billing').hide();
                        $('#gst_create_billing').hide();
                        $('#grand_total_create_billing').hide();
                    }
                    //console.log(claim_result.length);
                    // if(claim_result.length == 0)
                    // {
                        count_billing_service_info_num = 0;

                        $a0=""; 

                        $a0 += '<div class="tr editing tr_billing" method="post" name="form'+0+'" id="form'+0+'" num="'+0+'">';
                        $a0 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
                        $a0 += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value=""/></div>';
                        $a0 += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+0+']" id="client_billing_info_id" value=""/></div>';
                        $a0 += '<div class="hidden"><input type="text" class="form-control" name="claim_service_id['+0+']" id="claim_service_id" value=""/></div>';
                        $a0 += '<div class="td"><div class="select-input-group"><select class="input-sm form-control" name="service['+0+']" id="service" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+0+']" id="payment_voucher_type" value=""/><div id="form_service"></div></div></div>';
                        $a0 += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+0+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div></div>';
                        $a0 += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+0+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
                        /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_billing_info(this);">Save</button></div></div>';*/
                        $a0 += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+0+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
                        $a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: none;">Delete</button></div>';
                        $a0 += '</div>';

                        $("#body_create_billing").append($a0); 

                        if($("#body_create_billing > div").length > 1)
                        {
                            $('.delete_billing_button').css('display','block');
                        }

                        $('.period_start_date').datepicker({ 
                            dateFormat:'dd/mm/yyyy',
                        }).datepicker('setStartDate', "01/01/1920");

                        $('.period_end_date').datepicker({ 
                            dateFormat:'dd/mm/yyyy',
                        }).datepicker('setStartDate', "01/01/1920");

                        $.each(unit_pricing, function(key, val) {
                            //console.log(val['unit_pricing_name']);
                            var option = $('<option />');
                            option.attr('value', val['id']).text(val['unit_pricing_name']);
                            
                            $("#form"+0+" #unit_pricing").append(option);
                        });

                        var category_description = '';
                        var optgroup = '';

                        for(var t = 0; t < service_category_array.length; t++)
                        {
                            if(category_description != service_category_array[t]['category_description'])
                            {
                                if(optgroup != '')
                                {
                                    $("#form"+0+" #service").append(optgroup);
                                }
                                optgroup = $('<optgroup label="' + service_category_array[t]['category_description'] + '" />');
                            }

                            category_description = service_category_array[t]['category_description'];
                            
                            for(var h = 0; h < service_array.length; h++)
                            {
                                if(category_description == service_array[h]['category_description'])
                                {
                                    var option = $('<option />');
                                    option.attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']).appendTo(optgroup);
                                }
                            }
                        }
                        $("#form"+0+" #service").append(optgroup);

                        $("#form"+0+" #service").select2();

                        $('#create_billing_form').formValidation('addField', 'service['+0+']', service);
                        $('#create_billing_form').formValidation('addField', 'invoice_description['+0+']', invoice_description);
                        $('#create_billing_form').formValidation('addField', 'amount['+0+']', amount);  
                        $('#create_billing_form').formValidation('addField', 'unit_pricing['+0+']', validate_unit_pricing);  
                    //}
                    if(claim_result.length > 0)
                    { 
                        $("#modal_claim_list").modal("show");
                        //toastr.error("This Client have "+claim_result.length+" not paying claim.", "Claim Info");
                        $(".claim_list_tr").remove();
                        for(var f = 0; f < claim_result.length; f++)
                        {
                            $b = "";
                            $b = '<tr class="claim_list_tr"><td style="text-align: center; vertical-align: middle;"><input type="checkbox" class="claim_id" id="claim_id" value="'+claim_result[f]["id"]+'" data-amount="'+claim_result[f]["amount"]+'"></td><td><div class="type">'+claim_result[f]["type_name"]+'</div></td><td><div class="desciption">'+claim_result[f]["claim_description"]+'</div></td><td><div class="desciption">'+claim_result[f]["currency_name"]+'</div></td><td style="text-align: right"><div class="amount">'+addCommas(claim_result[f]["amount"])+'</div></td></tr>';
                            $("#claim_info").append($b); 
                        }
                    }
                }

            }               
        });
        $('#create_billing_form').formValidation('revalidateField', 'client_name');
    }
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
            $('.fye_date').attr('disabled', false);
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
                        $('.fye_date').attr('disabled', false);

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