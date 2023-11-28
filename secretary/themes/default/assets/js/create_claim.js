var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

var latest_gst_rate = 0;
var state_own_letterhead_checkbox = true;
var uniqueArray = [], remarks = "", selectedValues = [];

$('#create_claim_form').formValidation({
    framework: 'bootstrap',
    icon: {

    },

    fields: {
        claim_date: {
            row: '.claim_date_div',
            validators: {
                notEmpty: {
                    message: 'The Date field is required.'
                }
            }
        },
        claim_no: {
            row: '.validate',
            validators: {
                notEmpty: {
                    message: 'The Payment Voucher No field is required.'
                }
            }
        },
        user_name: {
            row: '.input-group',
            validators: {
                callback: {
                    message: 'The Vendor Name field is required.',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('user_name').val();
                        //console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        },
        rate: {
            row: '.rate-input-group',
            validators: {
                notEmpty: {
                    message: 'The Rate field is required.'
                }
            }
        },
        address: {
            row: '.input-group',
            validators: {
                notEmpty: {
                    message: 'The Address field is required.'
                }
            }
        },
        currency: {
            row: '.input-group',
            validators: {
                callback: {
                    message: 'The Currency field is required.',
                    callback: function(value, validator, $field) {
                        var options = validator.getFieldElements('currency').val();
                        //console.log(options);
                        return (options != null && options != "0");
                    }
                }
            }
        }
    }
});

var claim_description = {
        row: '.input-group',
        validators: {
            notEmpty: {
                message: 'The Payment Voucher Description field is required.'
            }
        }
    },
    amount = {
        row: '.input-group',
        validators: {
            notEmpty: {
                message: 'The Amount field is required.'
            }
        }
    },
    type = {
        //excluded: [':disabled', ':hidden', ':not(:visible)'],
        row: '.select-input-group',
        validators: {
            callback: {
                message: 'The Type field is required.',
                callback: function(value, validator, $field) {
                    var num = jQuery($field).parent().parent().parent().attr("num");
                    var options = validator.getFieldElements('type['+num+']').val();
                    //console.log(options);
                    return (options != null && options != "0");
                }
            }
        }
    },
    validate_unit_pricing = {
        //excluded: [':disabled', ':hidden', ':not(:visible)'],
        row: '.select-input-group',
        validators: {
            callback: {
                message: 'The Unit Pricing field is required.',
                callback: function(value, validator, $field) {
                    var num = jQuery($field).parent().parent().parent().attr("num");
                    var options = validator.getFieldElements('unit_pricing['+num+']').val();
                    //console.log(options);
                    return (options != null && options != "0");
                }
            }
        }
    };

function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        //console.log(data);
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
            //console.log(e);
            if(e.status != 200)
            {
                alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
            }
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

function Claim() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();

    this.getCurrency = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getCurrency';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.currency').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.currency').find("option:eq(0)").html("Select Currency");
            //console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_currency != null && key == data.selected_currency)
                    {
                        option.attr('selected', 'selected');
                        //$('.currency').attr('disabled', true);
                    }
                    else if(key == firm_info[0]["firm_currency"] && data.selected_currency == null)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.currency').append(option);
                });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getBankAcc = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getBankAcc';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.bank_account').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.bank_account').find("option:eq(0)").html("Select Bank Account");
            //console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_bank_acc != null && key == data.selected_bank_acc)
                    {
                        option.attr('selected', 'selected');
                        //$('.currency').attr('disabled', true);
                    }
                    
                    $('.bank_account').append(option);
                });
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getUserName = function() {
        var url = base_url+"/"+folder+"/"+'companytype/getUserName';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.user_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            $('.user_name').find("option:eq(0)").html("Select User Name");

            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    if(data.group_id != 2 && data.group_id != 5 && data.group_id != 6)
                    {
                        if(key == 67 || key == data.selected_user_name)
                        {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                        }
                    }
                    else
                    {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                    }

                    if(data.selected_user_name != null && key == data.selected_user_name)
                    {
                        option.attr('selected', 'selected');
                    }
                    $('.user_name').append(option);
                });
                $('#user_name').select2();
            }
            else{
                alert(data.msg);
            }
        }); 
    };
}

$(function() {
    var cn = new Claim();
    cn.getUserName();
    cn.getCurrency();
    cn.getBankAcc();
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
  if(day.toString().length==1)  
  {
    day="0"+day;
  }
    
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + '/' + monthNames[monthIndex] + '/' + year;
}

$(document).ready(function() {
    $('#loadingPaymentVoucher').hide();
});

$('.claim_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
}).datepicker("setDate", "0")
.on('changeDate', function (selected) {
    var claim_date = $('.claim_date').val();
    
    $('#create_claim_form').formValidation('revalidateField', 'claim_date');
});

if(claim_top_info == undefined)
{
    $.ajax({
        type: "GET",
        url: "payment_voucher/get_claim_no",
        asycn: false,
        dataType: "json",
        success: function(response){
            $('[name="claim_no"]').val(response.claim_no);
        }
    });

     $('#rate').val("1.0000");

    var d = new Date();

    var today_date = formatDateFunc(d);

    $("[name='hidden_own_letterhead_checkbox']").val(1);
    $(".dropdown_user_name").show();
    $('.text_user_name').attr('disabled', true);
    $('#claim_no').attr('disabled', true);
}
else
{

    if(claim_top_info[0]["postal_code"] != "" || claim_top_info[0]["street_name"] != "")
    {
        var units = " ";

        if(claim_top_info[0]["unit_no1"] != "" || claim_top_info[0]["unit_no2"] != "")
        {
            units = '\n#'+claim_top_info[0]["unit_no1"] + " - " + claim_top_info[0]["unit_no2"];
        }
        else if(claim_top_info[0]["unit_no1"] != "")
        {
            units = claim_top_info[0]["unit_no1"];
        }
        else if(claim_top_info[0]["unit_no2"] != "")
        {
            units = claim_top_info[0]["unit_no2"];
        }
        var nonedit_address = claim_top_info[0]["street_name"]+units+' '+claim_top_info[0]["building_name"]+'\nSingapore '+claim_top_info[0]["postal_code"];
    }
    else
    {
        var nonedit_address = claim_top_info[0]["foreign_address"];
    }

    $('[name="user_name"]').val(claim_top_info[0]["supplier_code"]);
    $('[name="text_user_name"]').val(claim_top_info[0]["user_name"]);
    $('[name="claim_no"]').val(claim_top_info[0]["claim_no"]);
    $('[name="previous_claim_no"]').val(claim_top_info[0]["claim_no"]);
    $('[name="claim_date"]').val(claim_top_info[0]["claim_date"]);
    $('[name="address"]').val(nonedit_address);
    $('[name="rate"]').val(claim_top_info[0]["rate"]);
    $('[name="cheque_number"]').val(claim_top_info[0]["cheque_number"]);

    $('[name="claim_date"]').attr('disabled', true);
    $('[name="address"]').attr('disabled', true);
    $(".input_user_name").show();
    $('.text_user_name').attr('disabled', true);
    $('#claim_no').attr('disabled', true);

}

$(".amount").live('change',function(){
    sum_total();
});
$(".rate").live('change',function(){
    sum_total();
});
$(".currency").live('change',function(){
    sum_total();
});


function sum_total(){
    var sum = 0;
    var before_gst = 0, gst = 0, gst_rate = 0, grand_total = 0, gst_with_rate = 0;
    $(".amount").each(function(){
        if($(this).val() == '')
        {
            sum += 0;
        }
        else
        {
            sum += +parseFloat($(this).val().replace(/\,/g,''),2);

            if(claim_below_info)
            {
                //assign gst
                gst_rate = 0;
            }
            else
            {
                gst_rate = latest_gst_rate;
            }

            before_gst = ((gst_rate / 100) * parseFloat($(this).val().replace(/\,/g,''),2));
            gst += parseFloat(before_gst.toFixed(2));
        }
    });

    $("#sub_total").text(addCommas(sum.toFixed(2)));

    if($(".currency").val() == "0" || $(".currency").val() == "1" || gst == "0.00")
    {
        gst_with_rate = " ";
        $("#gst_with_rate").text(gst_with_rate);
    }
    else if($(".currency").val() != "1")
    {
        gst_with_rate = gst * parseFloat($(".rate").val());
        $("#gst_with_rate").text("( SGD"+addCommas(gst_with_rate.toFixed(2))+" )");
    }

    $("#gst").text(addCommas(gst.toFixed(2)));
    grand_total = sum + gst;
    $("#grand_total").text(addCommas(grand_total.toFixed(2)));

    $("input[id=hidden_grand_total]").val(addCommas(grand_total.toFixed(2)));
    
}

function sum_first_total(){
    var sum = 0;
    var before_gst = 0, gst = 0, gst_rate = 0, grand_total = 0, gst_with_rate = 0;
    $(".amount").each(function(){
        if($(this).val() == '')
        {
            sum += 0;
        }
        else
        {
            sum += +parseFloat($(this).val().replace(/\,/g,''),2);

            if(claim_below_info)
            {
                //assign gst
                gst_rate = 0;
            }
            else
            {
                gst_rate = latest_gst_rate;
            }

            before_gst = ((gst_rate / 100) * parseFloat($(this).val().replace(/\,/g,''),2));
            gst += parseFloat(before_gst.toFixed(2));
        }
    });

    $("#sub_total").text(addCommas(sum.toFixed(2)));

    if(claim_below_info)
    {
        if(claim_below_info[0]["currency_id"] == "1")
        {
            gst_with_rate = " ";
            $("#gst_with_rate").text(gst_with_rate);
        }
        else if(claim_below_info[0]["currency_id"] != "1")
        {
            gst_with_rate = gst * parseFloat(claim_below_info[0]["rate"]);
            $("#gst_with_rate").text("( SGD"+addCommas(gst_with_rate.toFixed(2))+" )");
        }
    }
    else
    {
        if($("#currency").val() == "1")
        {
            gst_with_rate = " ";
            $("#gst_with_rate").text(gst_with_rate);
        }
        else if($("#currency").val() != "1")
        {
            gst_with_rate = gst * parseFloat($("#rate").val());
            $("#gst_with_rate").text("( SGD"+addCommas(gst_with_rate.toFixed(2))+" )");
        }
    }

    $("#gst").text(addCommas(gst.toFixed(2)));
    grand_total = sum + gst;
    $("#grand_total").text(addCommas(grand_total.toFixed(2)));

    $("input[id=hidden_grand_total]").val(addCommas(grand_total.toFixed(2)));
}

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

function delete_claim(element) 
{
    var tr = jQuery(element).parent().parent(),
        claim_service_id = tr.find('input[name="claim_service_id[]"]').val();

    tr.closest("DIV.tr").remove();

    if($("#body_create_claim > div").length == 1)
    {
        if($('.delete_claim_button').css('display') == 'block')
        {
            $('.delete_claim_button').css('display','none');
        }
    }

    check_client_company_code();

    sum_total();
}

if(claim_below_info != undefined)
{
    $('#create_claim_service').show();
    $('#body_create_claim').show();
    $('#sub_total_create_claim').show();
    $('#gst_create_claim').show();
    $('#grand_total_create_claim').show();

    //assign gst
    $('#gst_rate').val(claim_below_info[0]["gst_rate"]);

    for(var i= 0; i < claim_below_info.length; i++)
    {
        if(uniqueArray.indexOf(claim_below_info[i]["company_code"]) === -1) {
            uniqueArray.push(claim_below_info[i]["company_code"]);
        }
    }

    for(var t = 0; t < claim_below_info.length; t++)
    {
        $count_claim_service_info = t;
        $a=""; 
        /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
        $a += '<div class="tr editing tr_claim" method="post" name="form'+$count_claim_service_info+'" id="form'+$count_claim_service_info+'" num="'+$count_claim_service_info+'">';
        $a += '<div class="hidden"><input type="text" class="form-control" name="supplier_code" value=""/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="claim_service_id[]" value="'+claim_below_info[t]["claim_service_id"]+'"/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="vendor_claim_info_id['+$count_claim_service_info+']" id="vendor_claim_info_id" value="'+claim_below_info[t]["vendor_claim_info_id"]+'"/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id['+$count_claim_service_info+']" id="billing_service_id" value="'+claim_below_info[t]["billing_service_id"]+'"/></div>';
        $a += '<div class="td" style="width: 220px;"><div class="select-input-group"><select class="input-sm form-control" name="type['+$count_claim_service_info+']" id="type'+$count_claim_service_info+'" style="width:220px;"><option value="0" data-invoice_description="" data-amount="">Select Type</option></select><div id="form_service"></div></div></div>';
        $a += '<div class="td"><div class="select-input-group"><select class="input-sm form-control client" name="client['+$count_claim_service_info+']" id="client'+$count_claim_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">General</option></select><input type="hidden" class="hidden_client_name" name="hidden_client_name['+$count_claim_service_info+']" value="'+claim_below_info[t]["client_name"]+'"/><div id="form_service"></div></div></div>';
        $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="claim_description['+$count_claim_service_info+']"  id="claim_description" rows="3" style="width:250px">'+claim_below_info[t]["claim_description"]+'</textarea></div></div>';
        //<div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_claim_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+claim_below_info[t]["period_start_date"]+'"></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_claim_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+claim_below_info[t]["period_end_date"]+'"></div></div>
        $a += '<div class="td" style="width:100px"><div class="input-group"><input type="text" name="amount['+$count_claim_service_info+']" class="numberdes form-control text-right amount" value="'+addCommas(claim_below_info[t]["claim_service_amount"])+'" id="amount" style="width:100px"/><div id="form_amount"></div></div></div>';
        $a += "<div class='td' style='width:100px'><div class='input-group'><input type='file' style='display:none' id='attachment"+$count_claim_service_info+"' multiple='' name='attachment["+$count_claim_service_info+"][]'><label for='attachment"+$count_claim_service_info+"' class='btn btn-primary attachment"+$count_claim_service_info+"'>Attachment</label><br/><span class='file_name' id='file_name"+$count_claim_service_info+"'></span><input type='hidden' class='hidden_attachment' name='hidden_attachment["+$count_claim_service_info+"]' value='"+claim_below_info[t]["attachment"]+"'/></div></div>";
        // $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_claim_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
        $a += '<div class="td action"><button type="button" class="btn btn-primary delete_claim_button" onclick="delete_claim(this)" style="display: none;">Delete</button></div>';
        $a += '</div>';

        /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
        $("#body_create_claim").append($a); 

        if($("#body_create_claim > div").length > 1)
        {
            $('.delete_claim_button').css('display','block');
        }

        var file_result = JSON.parse(claim_below_info[t]["attachment"]);
        var filename = "";

        for(var i = 0; i < file_result.length; i++)
        {
            if(i == 0)
            {
                filename = '<a href="'+base_url+'uploads/claim_receipt/'+file_result[i]+'" target="_blank">'+file_result[i]+'</a>';
            }
            else
            {
                filename = filename + ", " + '<a href="'+base_url+'uploads/claim_receipt/'+file_result[i]+'" target="_blank">'+file_result[i]+'</a>';
            }
        }
        $("#file_name"+t).html(filename);

        $.each(type_array, function(key, val) {
            var option = $('<option />');
            option.attr('value', val["id"]).text(val["type_name"]);

            if(claim_below_info[t]["type_id"] != null && val["id"] == claim_below_info[t]["type_id"])
            {
                option.attr('selected', 'selected');
            }
            
            $("#form"+$count_claim_service_info+" #type"+$count_claim_service_info).append(option);
        });

        $("#form"+$count_claim_service_info+" #type"+$count_claim_service_info).select2();

        if(client_array != null)
        {
            $.each(client_array, function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);

                if(claim_below_info[t]["company_code"] != null && key == claim_below_info[t]["company_code"])
                {
                    option.attr('selected', 'selected');
                }
                
                $("#form"+$count_claim_service_info+" #client"+$count_claim_service_info).append(option);
            });
        }

        $("#form"+$count_claim_service_info+" #client"+$count_claim_service_info).select2();

        if(claim_top_info[0]['status'] == 2 || claim_top_info[0]['status'] == 3 || claim_top_info[0]['status'] == 4)
        {
            $("#create_claim_form :input").prop('readonly', true);
            $('#create_claim_form select').attr('disabled', true);
            $('.attachment'+$count_claim_service_info+'').hide();
            $('.delete_claim_button').hide();
        }

        $('#create_claim_form').formValidation('addField', 'type['+$count_claim_service_info+']', type);
        $('#create_claim_form').formValidation('addField', 'claim_description['+$count_claim_service_info+']', claim_description);
        $('#create_claim_form').formValidation('addField', 'amount['+$count_claim_service_info+']', amount);
        //$('#create_claim_form').formValidation('addField', 'unit_pricing['+$count_claim_service_info+']', validate_unit_pricing);
    }

    var claim_company_name = "";

    if(claim_for_transport_query.length > 0)
    {  
        for(var s = 0; s < uniqueArray.length; s++)
        {
            for(var y = 0; y < claim_for_transport_query.length; y++)
            {   
                if(uniqueArray[s] != "0")
                {
                    if(claim_for_transport_query[y]["total_ammount"] >= 150)
                    {
                        if(claim_for_transport_query[y]["company_code"] == uniqueArray[s])
                        {
                            if(remarks == "")
                            {
                                remarks = remarks + "<div class='remarks_chill'><b><u>Transport</u></b><br />";
                            }

                            if(y == 0)
                            {   
                                claim_company_name = claim_for_transport_query[y]["client_name"];
                            
                                remarks = remarks + "Total transport claim for " + claim_for_transport_query[y]["client_name"] + " of $" + claim_for_transport_query[y]["total_ammount"] + " exceeds the cap of $150 per client.<br />";
                            }
                            else
                            {
                                if(claim_company_name != claim_for_transport_query[y]["client_name"])
                                {
                                    claim_company_name = claim_for_transport_query[y]["client_name"];
                                    remarks = remarks + "Total transport claim for " + claim_for_transport_query[y]["client_name"] + " of $" + addCommas(claim_for_transport_query[y]["total_ammount"]) + " exceeds the cap of $150 per client.<br />";
                                }
                            }
                        }
                    }
                }
            }
        }
        remarks = remarks + '</div>';
        $(".remarks").html(remarks);
    }
    sum_first_total();
}
else
{
    showRow();
}

if(claim_below_info)
{
    $count_claim_service_info = claim_below_info.length;
}
else
{
    $count_claim_service_info = 1;
}

$(document).on('click',"#claim_service_info_Add",function() {
    
    $a=""; 
    /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
    $a += '<div class="tr editing tr_claim" method="post" name="form'+$count_claim_service_info+'" id="form'+$count_claim_service_info+'" num="'+$count_claim_service_info+'">';
    $a += '<div class="hidden"><input type="text" class="form-control" name="supplier_code" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="claim_service_id[]" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="vendor_claim_info_id['+$count_claim_service_info+']" id="vendor_claim_info_id" value=""/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id['+$count_claim_service_info+']" id="billing_service_id" value="0"/></div>';
    $a += '<div class="td"><div class="select-input-group"><select class="input-sm form-control" name="type['+$count_claim_service_info+']" id="type'+$count_claim_service_info+'" style="width:220px;"><option value="0" data-invoice_description="" data-amount="">Select Type</option></select><div id="form_service"></div></div></div>';
    $a += '<div class="td"><div class="select-input-group"><select class="input-sm form-control client" name="client['+$count_claim_service_info+']" id="client'+$count_claim_service_info+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">General</option></select><input type="hidden" class="hidden_client_name" name="hidden_client_name['+$count_claim_service_info+']" value=""/><div id="form_service"></div></div></div>';
    $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="claim_description['+$count_claim_service_info+']"  id="claim_description" rows="3" style="width:250px"></textarea></div></div>';
    //<div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_claim_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_claim_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div>
    $a += '<div class="td" style="width:100px"><div class="input-group"><input type="text" name="amount['+$count_claim_service_info+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:100px"/><div id="form_amount"></div></div></div>';
    $a += '<div class="td" style="width:100px"><div class="input-group"><input type="file" style="display:none" id="attachment'+$count_claim_service_info+'" multiple="" name="attachment['+$count_claim_service_info+'][]"><label for="attachment'+$count_claim_service_info+'" class="btn btn-primary" class="attachment">Attachment</label><br/><span class="file_name"></span><input type="hidden" class="hidden_attachment" name="hidden_attachment['+$count_claim_service_info+']" value=""/></div></div>';
    //$a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_claim_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select><div id="form_unit_pricing"></div></div></div>';
    /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_claim_info(this);">Save</button></div></div>';*/
    $a += '<div class="td action"><button type="button" class="btn btn-primary delete_claim_button" onclick="delete_claim(this)" style="display: block;">Delete</button></div>';
    $a += '</div>';

    /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
    $("#body_create_claim").append($a); 

    if($("#body_create_claim > div").length > 1)
    {
        $('.delete_claim_button').css('display','block');
    }

    $.each(type_array, function(key, val) {
        if(val["deleted"] == 0)
        {
            var option = $('<option />');
            option.attr('value', val["id"]).text(val["type_name"]);
            
            $("#form"+$count_claim_service_info+" #type"+$count_claim_service_info).append(option);
        }
    });

    $("#form"+$count_claim_service_info+" #type"+$count_claim_service_info).select2();

    if(client_array != null)
    {
        $.each(client_array, function(key, val) {
            var option = $('<option />');
            option.attr('value', key).text(val);
            
            $("#form"+$count_claim_service_info+" #client"+$count_claim_service_info).append(option);
        });
    }

    $("#form"+$count_claim_service_info+" #client"+$count_claim_service_info).select2();

    $('#create_claim_form').formValidation('addField', 'type['+$count_claim_service_info+']', type);
    $('#create_claim_form').formValidation('addField', 'claim_description['+$count_claim_service_info+']', claim_description);
    $('#create_claim_form').formValidation('addField', 'amount['+$count_claim_service_info+']', amount);
    //$('#create_claim_form').formValidation('addField', 'unit_pricing['+$count_claim_service_info+']', validate_unit_pricing);

    $count_claim_service_info++;
});

$(document).on('change','#create_claim_form #user_name',function(e){

    var supplier_code = $('#user_name option:selected').val();

    $.ajax({
        type: "POST",
        url: "payment_voucher/get_vendor_address",
        data: {"supplier_code":supplier_code}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            //$(".tr_payment_voucher").remove();

            if(response.Status == 1)
            {
                if(supplier_code == 0)
                {
                    $('[name="address"]').val("");
                    $('[name="hidden_postal_code"]').val("");
                    $('[name="hidden_street_name"]').val("");
                    $('[name="hidden_building_name"]').val("");
                    $('[name="hidden_unit_no1"]').val("");
                    $('[name="hidden_unit_no2"]').val("");
                    $('#create_claim_form').formValidation('revalidateField', 'address');
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
                    $('#create_claim_form').formValidation('revalidateField', 'address');
                    $("#address").attr('readOnly', true);
                }
            }
        }
    });
});

function showRow(){
    var supplier_code = $('#user_name option:selected').val();

    $.ajax({
        type: "GET",
        url: "companytype/get_payment_voucher_type",
        //data: {"supplier_code":supplier_code, "currency": $("#currency option:selected").val()}, // <--- THIS IS THE CHANGE
        dataType: "json",
        success: function(response){
            if(response.tp == 1)
            {

                $a0=""; 

                $a0 += '<div class="tr editing tr_claim" method="post" name="form'+0+'" id="form'+0+'" num="'+0+'">';
                $a0 += '<div class="hidden"><input type="text" class="form-control" name="supplier_code" value=""/></div>';
                $a0 += '<div class="hidden"><input type="text" class="form-control" name="claim_service_id[]" value=""/></div>';
                $a0 += '<div class="hidden"><input type="text" class="form-control" name="vendor_claim_info_id['+0+']" id="vendor_claim_info_id" value=""/></div>';
                $a0 += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id['+0+']" id="billing_service_id" value="0"/></div>';
                $a0 += '<div class="td"><div class="select-input-group"><select class="input-sm form-control" name="type['+0+']" id="type'+0+'" style="width:220px;"><option value="0" data-invoice_description="" data-amount="">Select Type</option></select><div id="form_service"></div></div></div>';
                $a0 += '<div class="td"><div class="select-input-group"><select class="input-sm form-control client" name="client['+0+']" id="client'+0+'" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">General</option></select><input type="hidden" class="hidden_client_name" name="hidden_client_name['+0+']" value=""/><div id="form_service"></div></div></div>';
                $a0 += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="claim_description['+0+']"  id="claim_description" rows="3" style="width:250px"></textarea></div></div>';
                //<div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div>
                $a0 += '<div class="td" style="width:100px"><div class="input-group"><input type="text" name="amount['+0+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:100px"/><div id="form_amount"></div></div></div>';
                $a0 += '<div class="td" style="width:100px"><div class="input-group"><input type="file" style="display:none" id="attachment'+0+'" multiple="" name="attachment['+0+'][]"><label for="attachment'+0+'" class="btn btn-primary" class="attachment">Attachment</label><br/><span class="file_name"></span><input type="hidden" class="hidden_attachment" name="hidden_attachment['+0+']" value=""/></div></div>';
                /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_claim_info(this);">Save</button></div></div>';*/
                // $a0 += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+0+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
                $a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_claim_button" onclick="delete_claim(this)" style="display: none;">Delete</button></div>';
                $a0 += '</div>';

                
                $("#body_create_claim").append($a0); 

                if($("#body_create_claim > div").length > 1)
                {
                    $('.delete_claim_button').css('display','block');
                }

                type_array = response.result;
                $.each(response.result, function(key, val) {
                    if(val["deleted"] == 0)
                    {
                        var option = $('<option />');
                        option.attr('value', val["id"]).text(val["type_name"]);
                        
                        $("#form"+0+" #type"+0).append(option);
                    }
                });

                $("#form"+0+" #type"+0).select2();

                client_array = response.client_result;
                if(client_array != null)
                {
                    $.each(response.client_result, function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        $("#form"+0+" #client"+0).append(option);
                    });
                }

                $("#form"+0+" #client"+0).select2();

                $('#create_claim_form').formValidation('addField', 'type['+0+']', type);
                $('#create_claim_form').formValidation('addField', 'claim_description['+0+']', claim_description);
                $('#create_claim_form').formValidation('addField', 'amount['+0+']', amount);  
                $('#create_claim_form').formValidation('addField', 'unit_pricing['+0+']', validate_unit_pricing);  
            }
        }               
    });
}

$(document).on('change','#create_claim_form #currency',function(e){
    if($(this).val() == "1")
    {
        $("#rate").val("1.0000");
    }
    $('#create_claim_form').formValidation('revalidateField', 'currency');
});

function check_client_company_code()
{
    selectedValues = [];
    uniqueArray = [];

    $(".client option:selected").each(function(){
        selectedValues.push($(this).val()); 
    });

    for(var i= 0; i < selectedValues.length; i++)
    {
        if(uniqueArray.indexOf(selectedValues[i]) === -1) {
            uniqueArray.push(selectedValues[i]);
        }
    }

    $.ajax({
        type: "GET",
        url: "payment_voucher/get_the_transport_claim",
        success: function(response){
            var response = JSON.parse(response);
            var claim_for_transport_query = response.claim_for_transport_query;

            if(claim_for_transport_query.length > 0)
            {
                $(".remarks .remarks_chill").remove();
                remarks = "";
                var claim_company_name = "";
                for(var s = 0; s < uniqueArray.length; s++)
                {
                    for(var y = 0; y < claim_for_transport_query.length; y++)
                    {
                        if(uniqueArray[s] != "0")
                        {
                            if(claim_for_transport_query[y]["total_ammount"] >= 150)
                            {
                                if(claim_for_transport_query[y]["company_code"] == uniqueArray[s])
                                {
                                    if(remarks == "")
                                    {
                                        remarks = remarks + "<div class='remarks_chill'><b><u>Transport</u></b><br />";
                                    }

                                    if(y == 0)
                                    {   
                                        claim_company_name = claim_for_transport_query[y]["client_name"];
                                                                        
                                        remarks = remarks + "Total transport claim for " + claim_for_transport_query[y]["client_name"] + " of $" + addCommas(claim_for_transport_query[y]["total_ammount"]) + " exceeds the cap of $150 per client.<br />";
                                    }
                                    else
                                    {
                                        if(claim_company_name != claim_for_transport_query[y]["client_name"])
                                        {
                                            claim_company_name = claim_for_transport_query[y]["client_name"];
                                            remarks = remarks + "Total transport claim for " + claim_for_transport_query[y]["client_name"] + " of $" + addCommas(claim_for_transport_query[y]["total_ammount"]) + " exceeds the cap of $150 per client.<br />";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                remarks = remarks + '</div>';
                $(".remarks").html(remarks);
            }
        }
    });
}

$(document).on('change','#create_claim_form #client',function(){
    $client_name = $("[name='client["+$(this).parent().parent().parent().attr("num")+"]'] option:selected").text();
    $client_selected_value = $("[name='client["+$(this).parent().parent().parent().attr("num")+"]'] option:selected").val();
    check_client_company_code();
    $(this).parent().find("input[name='hidden_client_name["+$(this).parent().parent().parent().attr("num")+"]']").attr("value", $client_name);
});

$(document).on('change','[type=file]',function(){
    var filename = "";
    for(var i = 0; i < this.files.length; i++)
    {
    if(i == 0)
    {
        filename = this.files[i].name;
    }
    else
    {
        filename = filename + ", " + this.files[i].name;
    }
    }
    $(this).parent().find(".file_name").html(filename);
    $(this).parent().find(".hidden_attachment").val("");
});

$(document).on("submit",function(e){
    e.preventDefault();
    var $form = $(e.target);

    var fv = $form.data('formValidation');
    // Get the first invalid field
    var $invalidFields = fv.getInvalidFields().eq(0);
    // Get the tab that contains the first invalid field
    var $tabPane     = $invalidFields.parents();
    var valid_setup = fv.isValidContainer($tabPane);

    if(valid_setup)
    {
        if($('#create_claim_service').css('display') != 'none')
        {
            $('[name="claim_date"]').attr('disabled', false);
            $('.currency').attr('disabled', false);
            $('.user_name').attr('disabled', false);
            $('#claim_no').attr('disabled', false);
            $('.bank_account').attr('disabled', false);


            var formData = new FormData($('form')[0]);
            formData.append('user_name_text', $(".user_name option:selected").text());
            $('#loadingPaymentVoucher').show();
            $.ajax({
                type: 'POST',
                url: "payment_voucher/save_claim",
                data: formData,
                dataType: 'json',
                // Tell jQuery not to process data or worry about content-type
                // You *must* include these options!
                // + '&user_name_text=' + $(".user_name option:selected").text()
                cache: false,
                contentType: false,
                processData: false,
                success: function(response){
                    $('#loadingPaymentVoucher').hide();
                    if (response.Status === 1) 
                    {
                        toastr.success(response.message, response.title);
                        var getUrl = window.location;
                        var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/payment_voucher";
                        window.location.href = baseUrl;
                    }
                }
            });
        }
        else
        {
            toastr.error("Please set service engagement in this Vendor.", "error");
        }
    }
});

$(document).on('click',"#saveClaim",function(e){
    $("#create_claim_form").submit();
});
