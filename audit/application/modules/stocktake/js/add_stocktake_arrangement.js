var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

var latest_gst_rate = 0, count_billing_service_info_num = 0, tmp = [], total_claim_amount = 0;
var state_own_letterhead_checkbox = true;


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

function delete_arrangement_info(element){
    var div         = $(element).parent();
    var arrangement_info_id     = div.find('.arrangement_info_id').val();
    console.log(arrangement_info_id);

    if(arrangement_info_id != ""){
        bootbox.confirm({
            message: "Do you want to delete this selected info?",
            closeButton: false,
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn_blue'
                },
                cancel: {
                    label: 'No',
                    className: 'btn_cancel'
                }
            },
            callback: function (result) {
                //console.log(result);
                if(result == true)
                {
                    $.post(delete_arrangement_info_url, { 'arrangement_info_id': arrangement_info_id }, function(data, status){
                        if(data){
                            location.reload();
                        }
                    });
                }
            }
        })
    }
    else
    {
        delete_arrangement(element);
    }
    
}


$(document).ready(function() {
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
    });
	// $a=""; 
 //        /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
 //        $a += '<tr class="editing tr_payment_receipt" method="post" name="form" id="form" num="">';
 //        //$a += '<div class="hidden"><input type="text" class="form-control" name="supplier_code" value=""/></div>';
 //        $a += '<div class="hidden"><input type="text" class="form-control" name="payment_receipt_service_id" value=""/></div>';
 //        $a += '<div class="hidden"><input type="text" class="form-control" name="vendor_payment_receipt_info_id[]" id="vendor_payment_receipt_info_id" value=""/></div>';
 //        $a += '<td style="width: 150px;"><div class="select-input-group"><select class="input-sm form-control" name="" id="type" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Type</option></select><div id="form_service"></div></div></td>';
 //        $a += '<td><div class="input-group mb-md"><textarea class="form-control" name="payment_receipt_description[]"  id="payment_receipt_description" rows="3" style="width:420px"></textarea></div></td>';
 //        //<div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_payment_receipt_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+payment_receipt_below_info[t]["period_start_date"]+'"></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_payment_receipt_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+payment_receipt_below_info[t]["period_end_date"]+'"></div></div>
 //        $a += '<td style="width:150px"><div class="input-group"><input type="text" name="amount[]" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></td>';
 //        $a += "<td style='width:100px'><div class='input-group'><input type='file' style='display:none' id='attachment' multiple='' name='attachment[][]'><label for='attachment class='btn btn-primary attachment>Select Attachment</label><br/><span class='file_name' id='file_name'></span><input type='hidden' class='hidden_attachment' name='hidden_attachment' value=''/></div></td>";
 //        // $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_payment_receipt_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
 //        $a += '<td class="action"><button type="button" class="btn btn-primary delete_payment_receipt_button" onclick="delete_payment_receipt(this)" style="display: none;">Delete</button></td>';
 //        $a += '</tr>';

        /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
        // $("#body_add_stocktake_arrangement").append($a); 

    // add new arrangement
    if (edit_stocktake_arrangement == "")
    {
    	var content = jQuery('#clone_model tr'),
		size = jQuery('#arrangement_tbl >tbody >tr').length + 1,
		element = null,    
		element = content.clone();
		// console.log(size);
		// element.attr('id', 'rec-'+size);
		// element.find('.delete-record').attr('data-id', size);
		element.appendTo('#body_add_stocktake_arrangement');
        var this_auditor = element.find(".our_auditor");

		if($("#arrangement_tbl >tbody >tr").length > 1)
        {
            $('.delete_arrangement_button').css('display','block');
        }

        $('.stocktake_date').datepicker({ 
		    dateFormat:'dd/mm/yyyy',
		    autoclose: true,
		});

        this_auditor.multiselect({
            allSelectedText: 'All',
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            maxHeight: 200,
            includeSelectAllOption: true,
            buttonWidth: '100%'
        });

        // $(".our_auditor").multiselect('selectAll', false);
        this_auditor.multiselect('updateButtonText');
        this_auditor.multiselect("refresh");
    }  
    // loop edit info array
    else{

        edit_stocktake_arrangement.forEach(function (key)
        {
            var content = jQuery('#clone_model tr'),
            size = jQuery('#arrangement_tbl >tbody >tr').length + 1,
            element = null,    
            element = content.clone();
            var this_auditor = element.find(".our_auditor");
            // console.log(edit_stocktake_arrangement);
            // console.log(key.auditor_id);
            var auditor_arr = (key.auditor_id).split(',');

            // element.attr('id', 'rec-'+size);
            // element.find('.delete-record').attr('data-id', size);
            // console.log(element.find("[name='our_auditor[]']"));
            element.find("[name='our_auditor[]']").val(auditor_arr);
            element.find(".arrangement_info_id").attr("value", key.id);
            element.find("[name='stocktake_date[]']").attr("value", key.stocktake_date);
            element.find("[name='stocktake_time[]']").attr("value", key.stocktake_time);
            element.find("[name='stocktake_address[]']").val(key.stocktake_address);
            element.find("[name='client_pic[]']").attr("value", key.client_pic);
            element.find(".hidden_our_auditor").val(key.auditor_id);
            // info_id.val(key.id);
            // console.log(info_id[0]);
            // info_id.val(key.id);


            element.appendTo('#body_add_stocktake_arrangement');

            if($("#arrangement_tbl >tbody >tr").length > 1)
            {
                $('.delete_arrangement_button').css('display','block');
            }

            $('.stocktake_date').datepicker({ 
                dateFormat:'dd/mm/yyyy',
                autoclose: true,
            });

            this_auditor.multiselect({
                allSelectedText: 'All',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                maxHeight: 200,
                includeSelectAllOption: true,
                buttonWidth: '100%'
            });

            // $(".our_auditor").multiselect('selectAll', false);
            this_auditor.multiselect('updateButtonText');
            this_auditor.multiselect("refresh");


        });   
    }
	// element.find('.sn').html(size);

    // $('.fye_date').attr('disabled', true);

    $('#create_stocktake_arrangement_form').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        fields: {
            client_name: {
                row: '.input-group',
                validators: {
                    notEmpty: {
                        message: 'Client name is required and cannot be empty'
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
            }
        }
    });


    // $('.our_auditor').on('change', function() {
    //     var element = $(this);
    //     var div     = $(element).parent().parent();
    //     var hidden_our_auditor = div.find('.hidden_our_auditor');

    //     console.log(hidden_our_auditor);

    //     var data = [];
    //     $('option:selected', $(this)).each(function() {
    //         data.push($(this).val());
    //     });


    // });





    
});

$(document).on('click',"#stocktake_arrangement_Add",function() {
    
    var content = jQuery('#clone_model tr'),
	size = jQuery('#arrangement_tbl >tbody >tr').length + 1,
	element = null,    
	element = content.clone();
	// console.log(size);
	// element.attr('id', 'rec-'+size);
	// element.find('.delete-record').attr('data-id', size);
	element.appendTo('#body_add_stocktake_arrangement');
    var this_auditor = element.find(".our_auditor");

    if($("#arrangement_tbl >tbody >tr").length > 1)
    {
        $('.delete_arrangement_button').css('display','block');

    }

    $('.stocktake_date').datepicker({ 
	    dateFormat:'dd/mm/yyyy',
	    autoclose: true,
	});

    this_auditor.multiselect({
        allSelectedText: 'All',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 200,
        includeSelectAllOption: true,
        buttonWidth: '100%'
    });

    // $(".our_auditor").multiselect('selectAll', false);
    this_auditor.multiselect('updateButtonText');
    this_auditor.multiselect("refresh");

    // $(".our_auditor").multiselect("refresh");

    // $.each(type_array, function(key, val) {
    //     var option = $('<option />');
    //     option.attr('value', key).text(val);
        
    //     $("#form"+$count_payment_receipt_service_info+" #type").append(option);
    // });

    // //$("#form"+0+" #service").append(optgroup);

    // $("#form"+$count_payment_receipt_service_info+" #type").select2();

    // $('#create_payment_receipt_form').formValidation('addField', 'type['+$count_payment_receipt_service_info+']', type);
    // $('#create_payment_receipt_form').formValidation('addField', 'payment_receipt_description['+$count_payment_receipt_service_info+']', payment_receipt_description);
    // $('#create_payment_receipt_form').formValidation('addField', 'amount['+$count_payment_receipt_service_info+']', amount);
    // //$('#create_payment_receipt_form').formValidation('addField', 'unit_pricing['+$count_payment_receipt_service_info+']', validate_unit_pricing);

    // $count_payment_receipt_service_info++;
});

function delete_arrangement(element) 
{
    var tr = jQuery(element).parent().parent();
        // payment_receipt_service_id = tr.find('input[name="payment_receipt_service_id[]"]').val();

    tr.closest("tr").remove();

    if($("#arrangement_tbl >tbody >tr").length == 1)
    {
        if($('.delete_arrangement_button').css('display') == 'block')
        {
            $('.delete_arrangement_button').css('display','none');
        }
    }

}

$(document).on('click',"#saveArrangement",function(e){
    // $('.fye_date').prop('disabled',false);
    var bootstrapValidator = $("#create_stocktake_arrangement_form").data('bootstrapValidator');
    bootstrapValidator.validate();
    if(bootstrapValidator.isValid())
    {
        if(check_duplicate_flag)
        {
            $("#create_stocktake_arrangement_form").submit();
            // Swal.fire({
            //   icon: 'success',
            //   title: 'Error',
            //   text: 'The time slot on the same day already exist!',
            // })
        }
        else
        {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'The time slot on the same day already exist!',
            })
        }
        
    }  
    else
    {
        return;
    }
      
});

$(document).on("submit",function(e){
    
        e.preventDefault();
        var $form = $(e.target);
        

        if($('#create_stocktake_arrangement_form').css('display') != 'none')
        {
            //$("#create_billing_form").formValidation('destroy');
            //$('[name="invoice_no"]').attr('disabled', false);
            // $('[name="billing_date"]').attr('disabled', false);
            // $('[name="address"]').attr('disabled', false);
            // $('.currency').attr('disabled', false);

            // $('.fye_date').attr('disabled', false);
            $('.client_name').attr('disabled', false);
            // console.log($(".client_name option:selected").val());
            var selected_company_name = $(".client_name option:selected").val();
            $.ajax({
                type: 'POST',
                url: save_stocktake_arrangement_url,
                data: $form.serialize() + '&reminder_id=' + encodeURIComponent(selected_company_name),
                dataType: 'json',
                success: function(response){
                    // console.log("fffffffffffffffffffffffffffffff");
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
                        // $('.fye_date').attr('disabled', true);
                        $('.client_name').attr('disabled', true);
                        // generate_first_clearance_letter(response.letter_id);
                        // console.log(bank_url);
                        window.location.href = stocktake_url;
                        
                    }
                    else
                    {
                        alert("Selected company may not assigned to any service or servicing firm. Failed to add bank authorization.");
                    }
                 
                    // window.location.href = auditor_url;
                    
                }
            });
        }
        else
        {
            toastr.error("Please set service engagement in this client.", "error");
        }
        

});



// $('.fye_date').datepicker({ 
//     dateFormat:'dd/mm/yyyy',
//     autoclose: true,
// });

function Client() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();
    var client = this;

    this.getFyeDate = function(company_code=null) {
        var url = base_url+"/"+folder+"/"+'stocktake/getFyeDate';
        //console.log(url);
        var method = "get";
        var data = {"reminder_id": company_code};
        // $("#bank_name").empty()=;
   
        // var option = $('<option />');
        // option.attr('value', '').text("Select Bank Name");
        // $('.bank_name').append(option);

            // var option = $('<option />');
        // $('.bank_name').find("option:eq(0)").html("Please wait..");
        // if(company_code == null || company_code == 0)
        // {
        //     $(".bank_name").attr('disabled', true);
        // }
        // else
        // {
        //     $(".bank_name").attr('disabled', false);
        // }

        call.send(data, url, method, function(data) {
            //console.log(data);
            // $('.bank_name').find("option:eq(0)").html("Select Bank Name");
            // console.log(data);
            if(data.tp == 1){
                // $.each(data['result'], function(key, val) {
                //     var option = $('<option />');
                //     option.attr('value', key).text(val);
                //     if(data.selected_bank_name != null && key == data.selected_bank_name)
                //     {
                //         option.attr('selected', 'selected');
                //         $('.bank_name').attr('disabled', true);
                //     }
                //     $('.bank_name').append(option);
                // });
                // $('#bank_name').select2({
                //     escapeMarkup: function (markup) { return markup; },
                //     language: {
                //         noResults: function () {
                //              return "<a href='"+bank_url+"'>Bank not exist. Add bank here</a>";
                //         }
                //     }
                // });
                //$(".nationality").prop("disabled",false);
                if(data['result'])
                {
                    $(".fye_date").val(data['result']);
                }

                

                $('#create_stocktake_arrangement_form').bootstrapValidator('revalidateField', 'fye_date');


            }
            else{
                $('#create_stocktake_arrangement_form').bootstrapValidator('revalidateField', 'fye_date');
                alert(data.msg);
            }
        }); 
    };

   

    this.getStocktakeClient = function() {
        var url = base_url+"/"+folder+"/"+'stocktake/getStocktakeClient';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.client_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.client_name').find("option:eq(0)").html("Select Client Name");
            // console.log(data);
            if(data.tp == 1){
                // console.log(data.selected_client_name);
                if(data.selected_client_name === undefined || data.selected_client_name.length == 0){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        // if(data.selected_client_name != null && key == data.selected_client_name)
                        // {
                        //     option.attr('selected', 'selected');
                        //     $('.client_name').attr('disabled', true);
                        //     client.getFyeDate(data.selected_client_name);
                        //     // console.log(this);
                        // }
                        // console.log(option);
                        $('.client_name').append(option);
                    });
                }
                else
                {
                    var option = $('<option />');
                    option.attr('value', data.selected_client_name.reminder_id).text(data.selected_client_name.company_name);
                    option.attr('selected', 'selected');
                    $('.client_name').attr('disabled', true);
                    client.getFyeDate(data.selected_client_name.reminder_id);
                    $('.client_name').append(option);
                }
                
                $('#client_name').select2();
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getPicName = function(selected_pic_name=null) {
        $('#pic_name').find('option').not(':nth-child(1)').remove();
        var url = base_url+"/"+folder+"/"+'bank/getPicName';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.pic_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.pic_name').find("option:eq(0)").html("Select PIC Name");
            // console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_pic_name != null && key == data.selected_pic_name)
                    {

                        option.attr('selected', 'selected');
                        // $('.pic_name').attr('disabled', true);
                    }
                    // console.log(option);
                    $('.pic_name').append(option);
                });
                $('#pic_name').select2();
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };


    // this.getAuditorName = function() {
    //     var url = base_url+"/"+folder+"/"+'list_of_auditor/getPicName';
    //     //console.log(url);
    //     var method = "get";
    //     var data = {};
    //     $('.our_auditor').find("option:eq(0)").html("Please wait..");
    //     call.send(data, url, method, function(data) {
    //         //console.log(data);
    //         $('.our_auditor').find("option:eq(0)").html("Select Auditor Name");
    //         // console.log(data);
    //         if(data.tp == 1){
    //             $.each(data['result'], function(key, val) {
    //                 var option = $('<option />');
    //                 option.attr('value', key).text(val);
    //                 if(data.selected_auditor_name != null && key == data.selected_auditor_name)
    //                 {
    //                     option.attr('selected', 'selected');
    //                     $('.our_auditor').attr('disabled', true);
    //                 }
    //                 $('.our_auditor').append(option);
    //             });
    //             // $('#our_auditor').select2();
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
    cn.getStocktakeClient();
    cn.getPicName();
    // cn.getAuditorName();

    // cn.getAuthStatus();
    //cn.frequency();
    //cn.type_of_day();
    //cm.getDirectorSignature1();
});


$('#client_name').on('change', function() {
    var data = $(".client_name option:selected").val();

    // console.log($(".fye_date"));
    $(".fye_date").val("");

   
    // $(".bank_name option").remove();
    var cn = new Client();
    // cn.getBankName(data);
    cn.getFyeDate(data);

})


function assign_hidden_val(element){

    // var data = $(".our_auditor option:selected").val();
    // var element = $(this);
    var div     = $(element).parent().parent();
    var hidden_our_auditor = div.find('.hidden_our_auditor');

    // console.log(hidden_our_auditor);

    var data = [];
    $('option:selected', $(element)).each(function() {
        data.push($(this).val());
    });

    hidden_our_auditor.val(data.join());

    // console.log(data);
    
}

function check_duplicate(element)
{
    var row = $(element).parent().parent().parent();
    // console.log(row);
    var current_time = row.find('.stocktake_time').val();
    var current_date = row.find('.stocktake_date').val();

    var table = $("#body_add_stocktake_arrangement");
    var outer_loop_flag = true;
    // console.log(current_time);
    // console.log(current_date);

    $(table).find('.stocktake_date').each(function() {
        if ($(this).val() == current_date && !($(this).is(row.find('.stocktake_date'))) && $(this).val() != "")
        {   // console.log($(this).val());
        //      console.log(row.find('.stocktake_date').val());
        //      console.log("----------------------------");

            $(table).find('.stocktake_time').each(function()
            {
                // console.log($(this).val());
                // console.log(row.find('.stocktake_time').val());
                // console.log("----------------------------");
                if ($(this).val() == current_time && !($(this).is(row.find('.stocktake_time'))) && $(this).val() != "")
                {
                    Swal.fire({
                      icon: 'error',
                      title: 'Error',
                      text: 'The time slot on the same day already exist!',
                    })
                    check_duplicate_flag = false;
                    outer_loop_flag = false;
                    return false;       
                }
                else
                {
                    check_duplicate_flag = true;
                    outer_loop_flag = true;
                }

            });
            // check_duplicate_flag = true;
            // alert('duplicate found!');
        }
        else
        {   
            if(outer_loop_flag)
            {

                check_duplicate_flag = true;
            }
            else
            {
                outer_loop_flag = true;
                return false;
            }
            
        }

        


    });
}



