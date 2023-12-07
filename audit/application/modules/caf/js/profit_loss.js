$(".print_icon").hide();
$(".access_icon").hide();

$(".addline_icon").addClass("disable");
document.getElementById("addline_icon").src = base_url + "img/add_line-disable.png";

$(".orientation_icon").addClass("disable");
document.getElementById("orientation_icon").src = base_url + "img/orientation-disable.png";

$(".textbox_icon").addClass("disable");
document.getElementById("textbox_icon").src = base_url + "img/textbox-disable.png";

if(!show_data_content)
{
	$(".export_icon").addClass("disable");
	document.getElementById("export_icon").src = base_url + "img/export-disable.png";
}

$(document).ready(function () {
	$('.select2').select2();
	// $('[data-toggle="tooltip"]').tooltip();
	$("body").tooltip({ selector: '[data-toggle=tooltip]' });
});

load_index();

function addRow(element,last_ye)
{
	var tr = $(element).parent().parent();
	var line_template = '';

	// console.log(tr);
	parent_tr_id = $(tr).find('.PL_audit_categorized_account_id').val();
	var class_row = 'rows-for-'+parent_tr_id;

	var assertion_dropdown = $("#pl_assertion_clone");
	var risk_lvl_dropdown = $("#risk_level_clone");

    // assertion_dropdown.find("option[value = '1']").attr("selected", "false");
	// assertion_dropdown.find("option[value = '0']").attr("selected", "true");

	// console.log(assertion_dropdown);

	// console.log($(tr).find('.ref_no').text());

	var last_row = 	$("."+class_row+":last");
	// var last_row_index = parseInt($(last_row).find('.ref_no').text());
	console.log(last_row);


	line_template += '<tr class="'+class_row+'">'+
						'<td ></td>'+
						'<td ></td>';

	if(last_ye)
	{
		line_template += '<td></td>';
	}


	line_template += '<td></td>'+
						'<td></td>'+
						'<td style="text-align: center;"><input type="hidden" name="line_item_id['+parent_tr_id+'][]" value=""/></td>'+
						'<td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="0"></td>'+
						'<td style="text-align: center;" class="ref_no"></td>'+
						'<td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['+parent_tr_id+'][]"  rows="1" style="width:100%"></textarea></td>'+
						'<td style="text-align: center;"><select name="pl_assertion['+parent_tr_id+'][]" class="pl_assertion select2">'+assertion_dropdown.html()+'</select></td>'+
						'<td style="text-align: center;"><select name="risk_level['+parent_tr_id+'][]" class="risk_level select2">'+risk_lvl_dropdown.html()+'</select></td>'+
						'<td style="text-align: center;"><textarea class="form-control compulsory" name="response['+parent_tr_id+'][]"  rows="1" style="width:100%"></textarea></td>'+					
					'</tr>';



	// console.log(last_row);

	$(last_row).after( line_template );
	$('.select2').select2();

	load_index();
}

$(document).on('click',".save_icon",function(e) 
{
    $('#loadingMessage').show();
    $(".save_icon").addClass("disable");
   	var form = $('#form_state_detailed_profit_loss').serialize();

    $.ajax({
        type: 'post',
        url: save_profit_loss_url,
        data: form ,
        dataType: 'json',
        success: function (response) 
        {
        	$('#loadingMessage').hide();
        	setTimeout(function(){ $(".save_icon").removeClass("disable"); }, 3000);
        	check_compulsory_field();
        	location.reload();
        }
    });

});

function check_compulsory_field()
{
	$( ".compulsory" ).each(function() {
	  	if(!$(this).val())
	  	{
	  		alert("Data saved successfully! Warning: Some of the field(s) is not completed.");
	  		location.reload();
	  		return false;
	  	}
	});
}

$(document).on('click',".delete_icon",function(e) 
{
    //check if there is any selected checkbox
    var check_cbx = false;
    var selected_line = [];
    $( ".cbx" ).each(function() {
	  	if($(this).is(':checked'))
	  	{
	  		check_cbx = true;
	  		selected_line.push($(this).val());
	  	}
	});

	if(check_cbx)
	{
		bootbox.confirm({
            message: "Do you want to delete selected line(s)?",
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
                	console.log(selected_line);
                    $.post(delete_pl_line_item_url, { 'line_item_ids': selected_line }, function(data, status){
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
		alert("No selected line");
	}

});

$(document).on('click',".export_icon",function(e) 
{
    $('#loadingMessage').show();
    var allow_export = true;
   	// var form = $('#form_balance_sheet').serialize();

   	// console.log($(".bs_assertion"));
   	$( ".pl_assertion" ).each(function() {
   		// console.log($(this).val());
   		if($(this).val() == "")
	  	{
	  		alert("Couldn't export data! Assertion is not complete.");
	  		$("#loadingMessage").hide();
	  		allow_export = false;
	  		return false;
	  	}
	  });


	  if(allow_export){
	  		bootbox.confirm({
            message: "Data will be saved before export. Continue?",
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
	               	var form = $('#form_state_detailed_profit_loss').serialize();

	                $.ajax({
				        type: 'post',
				        url: save_profit_loss_url,
				        data: form ,
				        dataType: 'json',
				        success: function (response) 
				        {
				        	// location.reload();
				        }
				    });

	             	$.ajax({
				        type: 'post',
				        url: export_profitloss_pdf_url,
				        dataType: 'json',
				        success: function (response) 
				        {
				        	$('#loadingMessage').hide();
				        	window.open(
				              response.link,
				              '_blank' // <- This is what makes it open in a new window.
				            );
				        	location.reload();

				        }
				    });
	            }
	            else
	            {
	            	$('#loadingMessage').hide();
	            }
            }
        })
	  }
   	// var form = $('#form_balance_sheet').serialize();



});


function load_index(){

	var ref_index = 1;
	$('.ref_no').each(function(index, tr) { 


	   	$(this).html(ref_index);

	   	ref_index++;
	   


	});
}
