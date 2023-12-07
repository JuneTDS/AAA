
var array_client_billing_info_id = [];
// var options = {
// 	excluded: ":disabled",
// 		fields: {
//         'service[]': {
//         	group: ".input-dropdown",
//             validators: {
// 		        notEmpty: {
//                     message: 'The rating is required'
//                 }
// 		    }
//         },
//         'invoice_description[]': {
//         	group: '.input-group',
//         	validators: {
//                 notEmpty: {
//                     message: 'The rating is required'
//                 }
//             }
//         }
//     }
// }
// $('#billing_form').bootstrapValidator(options);
// $(document).ready(function() {
// 	load_test();
// });


function search_billing_function()
{
	console.log(10);
	$('#loadingmessage').show();
	$.ajax({
        type: 'POST',
        url: search_client_billing_url,
        data: {"company_code": $("#w2-billing .company_code").val()},
        dataType: 'json',
        success: function(response){
        	$('#loadingmessage').hide();
        	$(".billing_collapsible").remove();
        	$(".billing_content").remove();
        	// console.log(response["service_category"]);
        	// console.log(response["client_billing_info"]);
        	service_category_list = response["service_category"];
        	client_billing_info = response["client_billing_info"];

        	var service_category = Object.keys(response["service_category"]);
        	// console.log(response["service_category"]);

        	// for(var a = 1; a <= service_category.length; a++)
        	service_category.forEach(function (key) 
        	{
        		// console.log(key);
        		// load_test();

        		var service_category_id = key;
        		var service_category_name = response["service_category"][key];
				var tbl_service_category_name = response["service_category"][key].replace(/\s/g, '');
        		var client_info = response["client_billing_info"];

        		$a = '';
        		$a += '<button type="button" class="billing_collapsible" style="margin-top: 10px;">';
        		$a += '<span style="font-size: 2.4rem;">'+service_category_name+'</span>';
        		$a += '</button>';
        		$a += '<div class="billing_content">';
        		$a += '<div class="row"><div class="col-lg-12 col-xl-6">';
        		$a += '<div style="display: table; border-collapse: collapse; margin-top: 20px; margin-bottom: 20px; width: 100%"><thead><div class="tr"> ';
        		$a += '<div class="th" valign=middle style="width:250px;text-align: center">Service</div>';
        		$a += '<div class="th" valign=middle style="width:250px;text-align: center">Invoice Description</div>';
        		$a += '<div class="th" style="width:150px;text-align: center">Fee</div>';
        		$a += '<div class="th" style="width:150px;text-align: center">Unit Pricing</div>';
        		$a += '<div class="th" style="width:150px;text-align: center">Servicing Firm</div>';
        		$a += '<div class="th" style="width:70px;text-align: center">Deactivate</div>';
        		// $a += '<a href="javascript: void(0);" class="th" rowspan =2 style="color: #D9A200;width:50px; outline: none !important;text-decoration: none;"><span class="billing_info_Add" id="billing_info_Add" data-service_category_id="'+service_category_id+'" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Service Engagement Information" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add</span></a>';
        		$a += '</div></thead><div class="tbody" id="body_'+tbl_service_category_name+'_info">';
        		$a += '<div class="tr '+tbl_service_category_name+'_empty_row">';
        		$a += '<div class="td" style="padding-bottom:30px; border-right: none !important;">';
        		$a += '<div style="width:200px;"></div>';
        		$a += '</div>';
        		$a += '<div class="td" style="border-right: none !important; border-left: none !important;">';
        		$a += '<div style="width:245px;"></div>';
        		$a += '</div>';
        		$a += '<div class="td" style="border-right: none !important; border-left: none !important;">';
        		$a += '<div style="width:87px;"><span style="font-weight:bold; font-size:20px;">N/A</span></div>';
        		$a += '</div>';
        		$a += '<div class="td" style="border-right: none !important; border-left: none !important;">';
        		$a += '<div style="width:100px;"></div>';
        		$a += '</div>';
        		$a += '<div class="td" style="border-right: none !important; border-left: none !important;">';
        		$a += '<div style="width:100px;"></div>';
        		$a += '</div>';
        		$a += '<div class="td" style="border-right: none !important; border-left: none !important;">';
        		$a += '<div style="width:70px;"></div>';
        		$a += '</div>';
        		$a += '<div class="td" style="border-left: none !important;">';
        		$a += '<div style="width:50px;"></div>';
        		$a += '</div>';
        		$a += '</div>';
        		$a += '</div></div>';
        		$a += '</div></div></div>';

        	   	$("#billing_form").append($a);

	        	for(var i = 0; i < client_info.length; i++)
	    		{	
	    			if(client_info[i]['service_type'] == service_category_id)
	    			{
	    				$("." + client_info[i]['category_description'].replace(/\s/g, '') + "_empty_row").remove();

		    			$b = '';
		    			$b += '<div class="tr editing" method="post" name="form'+i+'" id="form'+i+'" num="'+i+'">';
			            $b += '<div class="hidden"><input type="text" class="form-control company_code" name="company_code" value="'+company_code+'"/></div>';
			            $b += '<div class="hidden"><input type="text" class="form-control client_billing_info_id" name="client_billing_info_id[]" id="client_billing_info_id" value="'+client_info[i]["client_billing_info_id"]+'"/></div>';
			            $b += '<div class="td"><div class="input-dropdown" style="width:250px; margin-bottom: 55px !important;"><select class="form-control service" style="width: 100%;" name="service[]" id="service'+i+'" onchange="optionCheckBilling(this);"><option value="" >Select Service</option></select><div id="form_service"></div></div></div>';
			            $b += '<div class="td"><div class="test-group mb-md" style="width:100%"><textarea class="form-control" name="invoice_description[]"  id="invoice_description" rows="3" style="width:100%">'+client_info[i]["invoice_description"]+'</textarea></div></div>';
			            $b += '<div class="td"><div class="input-dropdown"><select class="form-control" style="text-align:right;width: 100%;" name="currency[]" id="currency"><option value="" >Select Currency</option></select></div><br/><div class="input-group"><input type="text" name="amount[]" class="numberdes form-control amount" value="'+ addCommas(client_info[i]["amount"])+'" id="amount" style="width:100%;text-align:right;"/></div></div>';
			            $b += '<div class="td"><div class="input-dropdown"><select class="form-control" style="width: 100%;" name="unit_pricing[]" id="unit_pricing"><option value="" >Select Unit Pricing</option></select></div></div>';
			            $b += '<div class="td"><div class="input-dropdown"><select class="form-control" style="width: 100%;" name="servicing_firm[]" id="servicing_firm"><option value="" >Select Servicing Firm</option></select></div></div>';
			            //$a += '<div class="td"><div class="div_billing_cycle"><div>Start Date: </div><div class="from_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="from_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker from_billing_cycle_datepicker" id="from_billing_cycle" name="from_billing_cycle['+i+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+client_info[i]["from_billing_cycle"]+'"></div><div id="form_from_billing_cycle"></div></div><div class="mb-md"><div>End Date: </div><div class="to_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="to_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker to_billing_cycle_datepicker" id="to_billing_cycle" name="to_billing_cycle['+i+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="'+client_info[i]["to_billing_cycle"]+'"></div><div id="form_to_billing_cycle"></div></div></div></div></div>';
			            $b += '<div class="td action"><label class="switch"><input name="deactive_switch" class="deactive_switch" type="checkbox" '+((client_info[i]["deactive"] == 1)?"checked":"")+'><span class="slider round"></span></label><input type="hidden" class="hidden_deactive_switch" name="hidden_deactive_switch[]" value="'+client_info[i]["deactive"]+'"/></div>';
			            // $b += '<div class="td action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></div>';
			            $b += '</div>';

			            // var doc = new DOMParser().parseFromString($b, "text/xml");
			            // console.log(doc);

			            // $all = $("#body_"+client_info[i]['category_description'].replace(/\s/g, '')+"_info");
			            // $all.insertBefore(doc);

			            $all = $("#body_"+client_info[i]['category_description'].replace(/\s/g, '')+"_info").append($b);

			            // console.log($all);

			            if(client_info[i]["frequency_name"] == "Non-recurring")
			            {
			                $("#form"+i+" .div_recurring").hide();
			                $("#form"+i+" #to").attr('disabled', 'disabled');
			                $("#form"+i+" #from").attr('disabled', 'disabled');
			            }
			            else
			            {
			                $("#form"+i+" .div_recurring").show();
			                $("#form"+i+" #to").attr('disabled', false);
			                $("#form"+i+" #from").attr('disabled', false);
			            }

			         
			            
			            $('.from_datepicker').datepicker({ 
			                dateFormat:'dd/mm/yyyy',
			                autoclose: true,
			            })
			            .on('changeDate', function (selected) {
			                var startDate = new Date(selected.date.valueOf());
			                $(this).parent().parent().parent().parent().find('.to_datepicker').datepicker('setStartDate', startDate);

			                var num = $(this).parent().parent().parent().parent().parent().attr("num");
			                //console.log(startDate);
			                $('#billing_form').formValidation('revalidateField', 'from['+num+']');
			            }).on('clearDate', function (selected) {
			                $(this).parent().parent().parent().parent().find('.to_datepicker').datepicker('setStartDate', null);
			            });

			            $('.to_datepicker').datepicker({ 
			                dateFormat:'dd/mm/yyyy',
			                autoclose: true,
			            }).on('changeDate', function (selected) {

			                var endDate = new Date(selected.date.valueOf());
			                $(this).parent().parent().parent().parent().find('.from_datepicker').datepicker('setEndDate', endDate);

			                var num = $(this).parent().parent().parent().parent().parent().attr("num");
			                //$('#setup_form').formValidation('revalidateField', 'to['+num+']');
			            }).on('clearDate', function (selected) {
			               $(this).parent().parent().parent().parent().find('.from_datepicker').datepicker('setEndDate', null);
			            });

			            $('.from_billing_cycle_datepicker').datepicker({ 
			                dateFormat:'dd/mm/yyyy',
			                autoclose: true,
			            })
			            .on('changeDate', function (selected) {
			                var startDate = new Date(selected.date.valueOf());
			                $(this).parent().parent().parent().parent().find('.to_billing_cycle_datepicker').datepicker('setStartDate', startDate);

			                var num = $(this).parent().parent().parent().parent().attr("num");
			                $('#billing_form').formValidation('revalidateField', 'from['+num+']');
			            }).on('clearDate', function (selected) {
			                $(this).parent().parent().parent().parent().find('.to_billing_cycle_datepicker').datepicker('setStartDate', null);
			            });

			            $('.to_billing_cycle_datepicker').datepicker({ 
			                dateFormat:'dd/mm/yyyy',
			                autoclose: true,
			            }).on('changeDate', function (selected) {

			                var endDate = new Date(selected.date.valueOf());
			                $(this).parent().parent().parent().parent().find('.from_billing_cycle_datepicker').datepicker('setEndDate', endDate);

			                var num = $(this).parent().parent().parent().parent().parent().attr("num");
			                //$('#setup_form').formValidation('revalidateField', 'to['+num+']');
			            }).on('clearDate', function (selected) {
			               $(this).parent().parent().parent().parent().find('.from_billing_cycle_datepicker').datepicker('setEndDate', null);
			            });
			            

			            !function (i) {

			                $.ajax({
			                    type: "POST",
			                    url: get_billing_info_service_url,
			                    data: {"company_code": company_code, "service": client_info[i]["service"]},//, 'is_template': change_template
			                    dataType: "json",
			                    async: false,
			                    success: function(data){
			                        $("#form"+i+" #service").find("option:eq(0)").html("Select Service");
			                        if(data.tp == 1){
			                            var category_description = '';
			                            var optgroup = '';

			                            for(var t = 0; t < data.selected_billing_info_service_category.length; t++)
			                            {
			                            	if(parseInt(data.selected_billing_info_service_category[t]['id']) == service_category_id)
			                            	{
				                                if(category_description != data.selected_billing_info_service_category[t]['category_description'])
				                                {
				                                    if(optgroup != '')
				                                    {
				                                        $("#form"+i+" #service").append(optgroup);
				                                    }
				                                    optgroup = $('<optgroup label="' + data.selected_billing_info_service_category[t]['category_description'] + '" />');
				                                }

				                                category_description = data.selected_billing_info_service_category[t]['category_description'];

				                                for(var h = 0; h < data.result.length; h++)
				                                {
				                                    if(category_description == data.result[h]['category_description'])
				                                    {
				                                        var option = $('<option />');
				                                        option.attr('data-description', data.result[h]['invoice_description']).attr('data-currency', data.result[h]['currency']).attr('data-unit_pricing', data.result[h]['unit_pricing']).attr('data-amount', data.result[h]['amount']).attr('value', data.result[h]['id']).text(data.result[h]['service_name']).appendTo(optgroup);

				                                        if(data.selected_service != null && data.result[h]['id'] == data.selected_service)
				                                        {
				                                            option.attr('selected', 'selected');
				                                        }
				                                    }
				                                }
				                            }
			                            }


			                            $("#form"+i+" #service"+i).append(optgroup);

			                            // console.log($("#form"+i+" #service"+i+" option"));
			                            $("#form"+i+" #service"+i+" option").filter(function()
			                            {
			                            	// console.log($.inArray($(this).val(),data.selected_query));
			                                return $.inArray($(this).val(),data.selected_query)>-1;
			                            }).attr("disabled","disabled");  

			                            $("#form"+i+" #service"+i+" option").filter(function()
			                            {
			                                return $(this).val() === data.selected_service;
			                            }).attr("disabled", false);
			                            
			                            $("#form"+i+" #service"+i).select2({
			                                formatNoMatches: function () {
			                                    return "No Result. <a href='our_firm/edit/"+data.firm_id+"' onclick='open_new_tab("+data.firm_id+")' target='_blank'>Click here to add Service</a>"
			                                },
			                                width: '250px'
			                            });
			                            $("#form"+i+" #service"+i).select2("enable", false);



			                        }
			                        else{
			                            alert(data.msg);
			                        }  
			                    }               
			                });
			            } (i);

			            !function (i) {
			                $.ajax({
			                    type: "GET",
			                    url: get_currency_url,
			                    dataType: "json",
			                    async: false,
			                    success: function(data){
			                        //console.log(data);
			                        if(data.tp == 1){
			                            $.each(data['result'], function(key, val) {
			                                var option = $('<option />');
			                                option.attr('value', key).text(val);
			                                if(client_info[i]["currency"] != null && key == client_info[i]["currency"])
			                                {
			                                    option.attr('selected', 'selected');
			                                }
			                                $("#form"+i+" #currency").append(option);
			                            });
			                        }
			                        else{
			                            alert(data.msg);
			                        }  
			                    }               
			                });
			            }(i);

			            !function (i) {
			                $.ajax({
			                    type: "GET",
			                    url: get_unit_pricing_url,
			                    async:false,
			                    dataType: "json",
			                    success: function(data){
			                        //console.log(data);
			                        if(data.tp == 1){
			                            $.each(data['result'], function(key, val) {
			                                var option = $('<option />');
			                                option.attr('value', key).text(val);
			                                if(client_info[i]["unit_pricing"] != null && key == client_info[i]["unit_pricing"])
			                                {
			                                    option.attr('selected', 'selected');
			                                }
			                                
			                                $("#form"+i+" #unit_pricing").append(option);
			                            });
			                        }
			                        else{
			                            alert(data.msg);
			                        }  
			                    }               
			                });
			            }(i);

			            !function (i) {
			                $.ajax({
			                    type: "GET",
			                    url: get_servicing_firm_url,
			                    async:false,
			                    dataType: "json",
			                    success: function(data){
			                        //console.log(data);
			                        if(data.tp == 1){
			                            $.each(data['result'], function(key, val) {
			                                var option = $('<option />');
			                                option.attr('value', key).text(val);
			                                if(client_info[i]["servicing_firm"] != null && key == client_info[i]["servicing_firm"])
			                                {
			                                    option.attr('selected', 'selected');
			                                }
			                                
			                                $("#form"+i+" #servicing_firm").append(option);
			                            });
			                        }
			                        else{
			                            alert(data.msg);
			                        }  
			                    }               
			                });
			            }(i);

			            !function (i) {
			                $.ajax({
			                    type: "POST",
			                    url:  get_billing_info_frequency_url,
			                    data: {"frequency": client_info[i]["frequency"]},
			                    dataType: "json",
			                    success: function(data){
			                        //console.log(data);
			                        $('#loadingmessage').hide();
			                        $("#form"+i+" #frequency").find("option:eq(0)").html("Select Frequency");
			                        if(data.tp == 1){
			                            $.each(data['result'], function(key, val) {
			                                var option = $('<option />');
			                                option.attr('value', key).text(val);
			                                if(data.selected_frequency != null && key == data.selected_frequency)
			                                {
			                                    option.attr('selected', 'selected');
			                                }
			                                $("#form"+i+" #frequency").append(option);
			                            });
			                        }
			                        else{
			                            alert(data.msg);
			                        } 
			                    }               
			                });
			            } (i);

			            !function (i) {
			                $.ajax({
			                    type: "POST",
			                    url: get_type_of_day_url,
			                    data: {"type_of_day": client_info[i]["type_of_day"]},
			                    dataType: "json",
			                    success: function(data){
			                        //console.log(data);
			                        if(data.tp == 1){
			                            $.each(data['result'], function(key, val) {
			                                var option = $('<option />');
			                                option.attr('value', key).text(val);
			                                if(data.selected_type_of_day != null && key == data.selected_type_of_day)
			                                {
			                                    option.attr('selected', 'selected');
			                                }
			                                $("#form"+i+" #type_of_day").append(option);
			                            });
			                        }
			                        else{
			                            alert(data.msg);
			                        }  
			                    }               
			                });
			            }(i);

			            // if(client_info[i]["frequency"] != 1 && client_info[i]["from_billing_cycle"] != '' && client_info[i]["to_billing_cycle"] != '')
			            // {
			            //     !function (i) {
			            //         $.ajax({
			            //             type: "POST",
			            //             url: check_next_recurring_date_url,
			            //             data: {"type_of_day": client_info[i]["type_of_day"], "days": client_info[i]["days"], "frequency": client_info[i]["frequency"], "to_billing_cycle": client_info[i]["to_billing_cycle"], "from": client_info[i]["from"], "to": client_info[i]["to"]},
			            //             dataType: "json",
			            //             success: function(data){
			            //                 //console.log(data);
			            //                 $("#form"+i+" .remark").text("");
			            //                 if(data.status == 1)
			            //                 {
			            //                     $("#form"+i+" .remark").text("Remarks: Your next recurring bill will issue on "+data.issue_date+" for your billing cycle "+data.next_from_billing_cycle+" to "+data.next_to_billing_cycle+"");
			            //                 }

			                            
			            //             }               
			            //         });
			            //     }(i);
			            // }

			            load_test();

					    var validator = $('#billing_form').data('bootstrapValidator');
					    var service   = $all.find('[name="service[]"]');
					    var invoice_description  = $all.find('[name="invoice_description[]"]');
					    var amount   = $all.find('[name="amount[]"]');
					    var currency   = $all.find('[name="currency[]"]');
					    var unit_pricing   = $all.find('[name="unit_pricing[]"]');
					    var servicing_firm   = $all.find('[name="servicing_firm[]"]');

					    
					    // $('#billing_form').bootstrapValidator('addField', service);


					    validator.addField(service.last());
					    validator.addField(invoice_description.last());
					    validator.addField(amount.last());
					    validator.addField(currency.last());
					    validator.addField(unit_pricing.last());
					    validator.addField(servicing_firm.last());
	
			        }
		        }

        	});



			coll = document.getElementsByClassName("billing_collapsible");

			for (var g = 0; g < coll.length; g++) {
			    coll[g].classList.toggle("billing_active");
			    coll[g].nextElementSibling.style.maxHeight = "100%";
			}

			for (var i = 0; i < coll.length; i++) {
			  coll[i].addEventListener("click", function() {
			    this.classList.toggle("billing_active");
			    var content = this.nextElementSibling;
			    if (content.style.maxHeight){
			      content.style.maxHeight = null;
			    } else {
			      content.style.maxHeight = "100%";
			    } 
			  });
			}

			$('input[type="text"], textarea').attr('readonly','readonly');
			$('select').attr('disabled',true);
			$('input[type="checkbox"]').attr('disabled',true);
        }
    })
	
}

$(document).on('change',"[name='deactive_switch']",function() {
	var checkbox = $(this);
	var checked = this.checked;
	var client_billing_info_id = $(this).parent().parent().parent().find(".client_billing_info_id").val();
	var company_code = $(this).parent().parent().parent().find(".company_code").val();
	var hidden_deactive_switch = $(this).parent().parent().find(".hidden_deactive_switch");

	if(checked == false)
	{
		var confirmDeactivate = "Do you wanna Activate this service engagement?"
	}
	else
	{
		var confirmDeactivate = "Do you wanna Deactivate this service engagement?"
	}
	bootbox.confirm(confirmDeactivate, function (result) {
        if (result) 
        {
        	if(checked == false)
        	{
        		hidden_deactive_switch.val(0);
        	}
        	else
        	{
        		hidden_deactive_switch.val(1);
        	}
			// $.ajax({
			// 	type: "POST",
			// 	url: "masterclient/deactivateServiceEngagement",
			// 	data: {"checked":checked, "client_billing_info_id": client_billing_info_id, "company_code": company_code}, // <--- THIS IS THE CHANGE
			// 	dataType: "json",
			// 	success: function(response){
			// 		if(response.Status == 1)
			// 		{
			// 			toastr.success(response.message, response.title);
			// 		}
			// 	}
			// });
		}
		else
		{
			if(checked)
			{
				checkbox.prop('checked', false);
			}
			else
			{
				checkbox.prop('checked', true);
			}
		}
	});
});

function optionCheckBilling(billing_element) {
    
    var tr = jQuery(billing_element).parent().parent();

    var input_num = tr.parent().attr("num");

    jQuery(this).find("input").val('');

    if(tr.find('#service'+input_num).val() == "1")
    {
        tr.parent().find('select[name="frequency['+input_num+']"]').val("4");
        $("#form"+input_num+" .div_recurring").show();
        tr.parent().find("input").attr('disabled', false);
    }
    else if(tr.find('#service'+input_num).val() == "2")
    {
        tr.parent().find('select[name="frequency['+input_num+']"]').val("5");
        $("#form"+input_num+" .div_recurring").show();
        tr.parent().find("input").attr('disabled', false);
    }
    else if(tr.find('#service'+input_num).val() == "0")
    {
        tr.parent().find("input").attr('disabled', false);
        $("#form"+input_num+" .div_recurring").show();
        tr.parent().find("select").val('0');

    }
    else
    {
        tr.parent().find('select[name="frequency['+input_num+']"]').val("1");

        $("#form"+input_num+" .div_recurring").hide();

        tr.parent().find('input[name="from['+input_num+']"]').attr('disabled', 'disabled');
        tr.parent().find('input[name="to['+input_num+']"]').attr('disabled', 'disabled');

        tr.parent().find('input[name="from['+input_num+']"]').val("");
        tr.parent().find('input[name="to['+input_num+']"]').val("");

        tr.parent().find('.from_div').removeClass("has-error");
        tr.parent().find('.from_div').removeClass("has-success");
        tr.parent().find('.from_div .help-block').hide();
    }

    //Prevent Multiple Selections of Same Value

    $("select.service option").attr("disabled",false); //enable everything
 
    var arr = $.map
    (
        $("select.service option:selected"), function(n)
        {
            return n.value;
        }
    );

    $("select.service").each(function() {

        var other_num = $(this).parent().parent().parent().attr("num");

        // var selected_dropdown_value = $('select[name="service['+other_num+']"]').val();
		
        var selected_dropdown_value = $('#service'+other_num).val();
		

        $('#service'+other_num+' option').filter(function()
        {
            return $.inArray($(this).val(),arr)>-1;
        }).prop("disabled", true); 

        $('#service'+other_num+' option').filter(function()
        {
            return $(this).val() === selected_dropdown_value;
        }).attr("disabled", false);
    });

    $("select.service").select2();
}

// console.log(client_billing_info);

if(client_billing_info)
{   
    var array_length = client_billing_info.length - 1;
    //console.log(array_length);
    count_billing_info = parseInt(client_billing_info[array_length]["client_billing_info_id"]) + 1;
}
else
{
    count_billing_info = 1;
}


$(document).on('click',".billing_info_Add",function() {
    
    var check_service_category_id = this.getAttribute("data-service_category_id");

	var service_category_name = service_category_list[check_service_category_id].replace(/\s/g, '');

	$("." + service_category_name + "_empty_row").remove();

    $a=""; 
    $a += '<div class="tr editing" style="border-bottom: 2px solid #dddddd;" method="post" name="form'+count_billing_info+'" id="form'+count_billing_info+'" num="'+count_billing_info+'">';
    $a += '<div class="hidden"><input type="text" class="form-control company_code" name="company_code" value="'+company_code+'"/></div>';
    $a += '<div class="hidden"><input type="text" class="form-control client_billing_info_id" name="client_billing_info_id[]" id="client_billing_info_id" value="'+count_billing_info+'"/></div>';
    $a += '<div class="td"><div class="input-dropdown" style="width:250px; margin-bottom: 55px !important;"><select class="form-control service" style="width: 100%;" name="service[]" id="service'+count_billing_info+'" onchange="optionCheckBilling(this);"><option value="" >Select Service</option></select></div></div>';
    $a += '<div class="td"><div class="test-group mb-md" style="width:100%"><textarea class="form-control" name="invoice_description[]"  id="invoice_description" rows="3" style="width:100%"></textarea></div></div>';
    $a += '<div class="td"><div class="input-dropdown"><select class="form-control currency" style="text-align:right;width: 100%;" name="currency[]" id="currency"><option value="" >Select Currency</option></select></div><br/><div class="input-group"><input type="text" name="amount[]" class="numberdes form-control amount" value="" id="amount" style="width:100%;text-align:right;"/></div></div>';
    $a += '<div class="td"><div class="input-dropdown" style="width:100%"><select class="form-control" style="width: 100%;" name="unit_pricing[]" id="unit_pricing"><option value="" >Select Unit Pricing</option></select></div></div>';
    $a += '<div class="td"><div class="input-dropdown"><select class="form-control" style="width: 100%;" name="servicing_firm[]" id="servicing_firm"><option value="" >Select Servicing Firm</option></select></div></div>';
    //$a += '<div class="td"><div class="div_billing_cycle"><div>Start Date: </div><div class="from_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="from_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker from_billing_cycle_datepicker" id="from_billing_cycle" name="from_billing_cycle['+count_billing_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div><div id="form_from_billing_cycle"></div></div><div class="mb-md"><div>End Date: </div><div class="to_billing_cycle_div mb-md"><div class="input-group" style="width: 100%" id="to_billing_cycle_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker to_billing_cycle_datepicker" id="to_billing_cycle" name="to_billing_cycle['+count_billing_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div><div id="form_to_billing_cycle"></div></div></div></div></div>';
    $a += '<div class="td action"><label class="switch"><input name="deactive_switch" class="deactive_switch" type="checkbox"><span class="slider round"></span></label><input type="hidden" class="hidden_deactive_switch" name="hidden_deactive_switch[]" value="0"/></div>';
    $a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="delete_billing_info(this);">Delete</button></div></div>';
    $a += '</div>';

    // console.log($a);


    $all = $("#body_"+service_category_name+"_info").prepend($a); 

    !function (count_billing_info) {
        $.ajax({
            type: "POST",
            url: get_billing_info_service_url,
            data: {"company_code": $("#w2-billing .company_code").val()},
            dataType: "json",
            async: false,
            success: function(data){
                if(data.tp == 1){
                    var category_description = '';
                    var optgroup = '';
                    for(var t = 0; t < data.selected_billing_info_service_category.length; t++)
                    {
                    	if(parseInt(data.selected_billing_info_service_category[t]['id']) == check_service_category_id)
	                    {
	                        if(category_description != data.selected_billing_info_service_category[t]['category_description'])
	                        {
	                            if(optgroup != '')
	                            {
	                                $("#form"+count_billing_info+" #service").append(optgroup);
	                            }
	                            optgroup = $('<optgroup label="' + data.selected_billing_info_service_category[t]['category_description'] + '" />');
	                        }

	                        category_description = data.selected_billing_info_service_category[t]['category_description'];

	                        for(var h = 0; h < data.result.length; h++)
	                        {
	                            if(category_description == data.result[h]['category_description'])
	                            {
	                                var option = $('<option />');
	                                option.attr('data-description', data.result[h]['invoice_description']).attr('data-currency', data.result[h]['currency']).attr('data-unit_pricing', data.result[h]['unit_pricing']).attr('data-amount', data.result[h]['amount']).attr('value', data.result[h]['id']).text(data.result[h]['service_name']).appendTo(optgroup);
	                            }
	                        }
	                    }
                    }
                    $("#form"+count_billing_info+" #service"+count_billing_info).append(optgroup);
                    $("#form"+count_billing_info+" #service"+count_billing_info).select2({
                        formatNoMatches: function () {
                            return "No Result. <a href='our_firm/edit/"+data.firm_id+"' onclick='open_new_tab("+data.firm_id+")' target='_blank'>Click here to add Service</a>"
                         },
                         width: '250px'
                    });

                    var arr = $.map
                    (
                        $("select.service option:selected"), function(n)
                        {
                            return n.value;
                        }
                    );

                    $('#service'+count_billing_info+' option').filter(function()
                    {
                        return $.inArray($(this).val(),arr)>-1;
                     }).attr("disabled","disabled"); 
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    }(count_billing_info);

    !function (count_billing_info) {
        $.ajax({
            type: "GET",
            url: get_currency_url,
            dataType: "json",
            success: function(data){
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        $("#form"+count_billing_info+" #currency").append(option);
                    });
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    }(count_billing_info);

    !function (count_billing_info) {
        $.ajax({
            type: "GET",
            url: get_servicing_firm_url,
            dataType: "json",
            success: function(data){
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        $("#form"+count_billing_info+" #servicing_firm").append(option);
                    });
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    }(count_billing_info);

    !function (count_billing_info) {
        $.ajax({
            type: "GET",
            url: get_unit_pricing_url,
            dataType: "json",
            success: function(data){
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        $("#form"+count_billing_info+" #unit_pricing").append(option);
                    });
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    }(count_billing_info);


    !function (count_billing_info) {
        $.ajax({
            type: "GET",
            url:  get_billing_info_frequency_url,
            dataType: "json",
            success: function(data){
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        $("#form"+count_billing_info+" #frequency").append(option);
                    });
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    }(count_billing_info);

    !function (count_billing_info) {
        $.ajax({
            type: "GET",
            url: get_type_of_day_url,
            dataType: "json",
            success: function(data){
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        
                        $("#form"+count_billing_info+" #type_of_day").append(option);
                    });
                }
                else{
                    alert(data.msg);
                }  
            }               
        });
    }(count_billing_info);	

    
    load_test();
    
    // $('#billing_form').bootstrapValidator();
    var validator = $('#billing_form').data('bootstrapValidator');
    var service   = $all.find('[name="service[]"]');
    var invoice_description   = $all.find('[name="invoice_description[]"]');
    console.log(validator);
    console.log($('#form'+count_billing_info));
    console.log(invoice_description);
    console.log(invoice_description.first());
    var amount   = $all.find('[name="amount[]"]');
    var currency   = $all.find('[name="currency[]"]');
    var unit_pricing   = $all.find('[name="unit_pricing[]"]');
    var servicing_firm   = $all.find('[name="servicing_firm[]"]');
    // console.log(invoice_description.last());
    // $('#billing_form').formValidation('addField', 'service['+count_billing_info+']', serviceValidators);
    // $('#billing_form').formValidation('addField', 'invoice_description['+count_billing_info+']', invoiceDescriptionValidators);
    // $('#billing_form').formValidation('addField', 'amount['+count_billing_info+']', amountValidators);
    // $('#billing_form').formValidation('addField', 'currency['+count_billing_info+']', currencyValidators);
    // $('#billing_form').formValidation('addField', 'unit_pricing['+count_billing_info+']', unitPricingValidators);
    // $('#billing_form').formValidation('addField', 'servicing_firm['+count_billing_info+']', servicingFirmValidators);

    // console.log(invoice_description.first());
    
    //$('#billing_form').bootstrapValidator('addField', invoice_description).bootstrapValidator('validateField', invoice_description);
    validator.addField(service.first());
    validator.addField(invoice_description);
    //console.log(validator.addField(invoice_description.first()));
    validator.addField(amount.first());
    validator.addField(currency.first());
    validator.addField(unit_pricing.first());
    validator.addField(servicing_firm.first());

    // $('#billing_form').bootstrapValidator('addField', service);
    // $('#billing_form').bootstrapValidator('addField', invoice_description);

    // console.log($('#billing_form').bootstrapValidator('addField', $service));
    
    count_billing_info++;
});

$(document).on('change','.service',function(e){
    var num = $(this).parent().parent().parent().attr("num");
    //console.log(num);
    var descriptionValue = $(this).find(':selected').data('description');
    var amountValue = $(this).find(':selected').data('amount');
    var currencyValue = $(this).find(':selected').data('currency');
    var unit_pricingValue = $(this).find(':selected').data('unit_pricing');

    $(this).parent().parent().parent().find('#invoice_description').text(descriptionValue);
    $(this).parent().parent().parent().find('#amount').val(addCommas(amountValue));
    $(this).parent().parent().parent().find('#currency').val(currencyValue);
    $(this).parent().parent().parent().find('#unit_pricing').val(unit_pricingValue);

    // $('#billing_form').formValidation('revalidateField', 'invoice_description['+num+']');
    // $('#billing_form').formValidation('revalidateField', 'amount['+num+']');
    // $('#billing_form').formValidation('revalidateField', 'currency['+num+']');
    // $('#billing_form').formValidation('revalidateField', 'unit_pricing['+num+']');
    // $('#billing_form').bootstrapValidator('addField', 'service[]');
    
	$('#billing_form').bootstrapValidator('revalidateField', 'service[]');
	$('#billing_form').bootstrapValidator('revalidateField', 'invoice_description[]');
	$('#billing_form').bootstrapValidator('revalidateField', 'amount[]');
    $('#billing_form').bootstrapValidator('revalidateField', 'currency[]');
    $('#billing_form').bootstrapValidator('revalidateField', 'unit_pricing[]');
    $('#billing_form').bootstrapValidator('revalidateField', 'servicing_firm[]');
	      
});


function delete_billing_info(element)
{
    var tr = jQuery(element).parent().parent();

    var client_billing_info_id = tr.find('.client_billing_info_id').val();
    var company_code = tr.find('.company_code').val();

    if(client_billing_info_id != "")
    {
        $.ajax({ //Upload common input
            url: delete_client_billing_info_url,
            type: "POST",
            data: {"client_billing_info_id": client_billing_info_id, "company_code": company_code},
            dataType: 'json',
            success: function (response) {
                if(response.Status == 1)
                {
                    array_client_billing_info_id.push(client_billing_info_id);
                    toastr.success("Updated Information", "Success");
                    
                    // load_test();
				    // var validator = $('#billing_form').data('bootstrapValidator');

        //             var service = tr.find('[name="service[]"]');
        //             var invoice_description = tr.find('[name="invoice_description[]"]');
        //             var amount = tr.find('[name="amount[]"]');
        //             var currency = tr.find('[name="currency[]"]');
        //             var unit_pricing = tr.find('[name="unit_pricing[]"]');
        //             var servicing_firm = tr.find('[name="servicing_firm[]"]');
               
        	            tr.remove();

        //             validator.removeField(service);
        //             validator.removeField(invoice_description);
        //             validator.removeField(amount);
        //             validator.removeField(currency);
        //             validator.removeField(unit_pricing);
        //             validator.removeField(servicing_firm);
                    // $('#billing_form').bootstrapValidator('removeField', service);

                    $("select.service option").attr("disabled",false); //enable everything
         
                     //collect the values from selected;
                    var arr = $.map
                    (
                        $("select.service option:selected"), function(n)
                        {
                            return n.value;
                        }
                    );

                    $("select.service").each(function() {

                        var other_num = $(this).parent().parent().parent().attr("num");

                        var selected_dropdown_value = $('#service'+other_num).val();

                        $('#service'+other_num+' option').filter(function()
                        {
                            return $.inArray($(this).val(),arr)>-1;
                        }).attr("disabled","disabled"); 

                        $('#service'+other_num+' option').filter(function()
                        {
                            return $(this).val() === selected_dropdown_value;
                        }).attr("disabled", false);

                    });
                }
                else
                {
                    toastr.error("Cannot be delete. This service is use in billing.", "Error");
                }
            }
        });
    }
}

// $(document).ready(function(){
	// $('#billing_form').bootstrapValidator({
 // 		excluded: ":disabled",
 // 		fields: {
	//         'service[]': {
	//         	group: ".input-dropdown",
	//             validators: {
	// 		        notEmpty: {
 //                        message: 'Service is required'
 //                    }
	// 		    }
	//         },
	//         'invoice_description[]': {
	//         	group: '.input-group',
	//         	validators: {
 //                    notEmpty: {
 //                        message: 'The invoice description is required'
 //                    }
 //                }
	//         },
	//         'amount[]': {
	//         	group: '.input-group',
	//         	validators: {
 //                    notEmpty: {
 //                        message: 'The amount is required'
 //                    }
 //                }
	//         },
	//         'currency[]': {
	//         	group: '.input-dropdown',
	//         	validators: {
 //                    notEmpty: {
 //                        message: 'The currency is required'
 //                    }
 //                }
	//         },
	//         'unit_pricing[]': {
	//         	group: '.input-dropdown',
	//         	validators: {
 //                    notEmpty: {
 //                        message: 'The unit pricing is required'
 //                    }
 //                }
	//         },
	//         'servicing_firm[]': {
	//         	group: ".input-dropdown",
	//             validators: {
	// 		        notEmpty: {
 //                        message: 'Servicing firm is required'
 //                    }
	// 		    }
	//         }
	//     }
 //    });

// });

function load_test(){
	// console.log($('#billing_form'));
 	$('#billing_form').bootstrapValidator({
 		excluded: [':disabled', ':hidden', ':not(:visible)'],
 		fields: {
	        'service[]': {
	        	group: ".input-dropdown",
	            validators: {
			        notEmpty: {
                        message: 'Service is required'
                    }
			    }
	        },
	        'invoice_description[]': {
	        	group: '.test-group',
	        	validators: {
                    notEmpty: {
                        message: 'The invoice description is required'
                    }
                }
	        },
	        'amount[]': {
	        	group: '.input-group',
	        	validators: {
                    notEmpty: {
                        message: 'The amount is required'
                    }
                }
	        },
	        'currency[]': {
	        	group: '.input-dropdown',
	        	validators: {
                    notEmpty: {
                        message: 'The currency is required'
                    }
                }
	        },
	        'unit_pricing[]': {
	        	group: '.input-dropdown',
	        	validators: {
                    notEmpty: {
                        message: 'The unit pricing is required'
                    }
                }
	        },
	        'servicing_firm[]': {
	        	group: ".input-dropdown",
	            validators: {
			        notEmpty: {
                        message: 'Servicing firm is required'
                    }
			    }
	        }
	    }
    });
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