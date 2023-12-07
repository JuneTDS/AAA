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

	// show_FP_unbalanced_msg();
});



function FP_calculation(parent_fs_categorized_account_id, type, description_type, group_company)
{
	var subtotal = find_sub_total_lye(parent_fs_categorized_account_id, type);

	// console.log(type);

	var total_assets = 0.00;
	var total_equity = 0.00;
	var total_liabilities = 0.00;
	var total_equity_and_liabilities = 0.00;

	// DISPLAY AND CALCULATE TOTAL ASSETS
	if(description_type == "Assets")
	{
		// total_assets = parseFloat(find_sub_total_by_classname(type + "_account_code_S0001")) + parseFloat(find_sub_total_by_classname(type + "_account_code_S0002"));
    total_assets = parseFloat(find_sub_total_by_classname(type + "_account_code_Assets"));

		$('#' + type + "_total_assets").text(total_assets);
	}
	// DISPLAY AND CALCULATE EQUITY AND LIABILITIES (INPUT FROM EQUITY)
	else if(description_type == "Equity")
	{
		// total_equity 	    = parseFloat(find_sub_total_by_classname(type + "_account_code_S0003"));
		// total_liabilities = parseFloat(find_sub_total_by_classname(type + "_account_code_S0004")) + parseFloat(find_sub_total_by_classname(type + "_account_code_S0005"));
    total_equity       = parseFloat(find_sub_total_by_classname(type + "_account_code_Equity"));
    total_liabilities = parseFloat(find_sub_total_by_classname(type + "_account_code_Liabilities"));

		$('#' + type + "_total_equity_liabilities").text(total_equity + total_liabilities);
	}
	// DISPLAY AND CALCULATE EQUITY AND LIABILITIES (INPUT FROM LIABILITIES)
	else if(description_type == "Liabilities")
	{	
		// total_equity 	  =   parseFloat(find_sub_total_by_classname(type + "_account_code_S0003"));
		// total_liabilities = parseFloat(find_sub_total_by_classname(type + "_account_code_S0004")) + parseFloat(find_sub_total_by_classname(type + "_account_code_S0005"));
    total_equity       = parseFloat(find_sub_total_by_classname(type + "_account_code_Equity"));
    total_liabilities = parseFloat(find_sub_total_by_classname(type + "_account_code_Liabilities"));

		$('#' + type + "_total_liabilities").text(total_liabilities);
		$('#' + type + "_total_equity_liabilities").text(total_equity + total_liabilities);
	}

  show_FP_unbalanced_msg();
}

// function show_FP_unbalanced_msg()
// {
//   var total_assets         = $("#form_balance_sheet .total_assets").find('.total_assets').text();
//   var total_assets_end       = $("#form_balance_sheet .total_assets").find('.total_assets_end').text();
//   // var total_assets_beg        = $("#form_balance_sheet .total_assets").find('.total_assets_beg').text();
//   var total_equity_liabilities   = $("#form_balance_sheet .total_equity_liabilities").find('.total_equity_liabilities').text();
//   var total_equity_liabilities_end = $("#form_balance_sheet .total_equity_liabilities").find('.total_equity_liabilities_end').text();
//   // var total_equity_liabilities_beg = $("#form_balance_sheet .total_equity_liabilities").find('.total_equity_liabilities_beg').text();

//   if(total_assets != total_equity_liabilities || total_assets_end != total_equity_liabilities_end)
//   {

//       bootbox.alert({
//         message: "Account is not balance. Please check the account and do modification in 'Categorization'.<br/><br/>"
//       });

//       $('#form_balance_sheet #account_balance_msg').text("* Account is not balance. Please make changes in 'Categorization'.");
    
//   }
//   else
//   {
//     $('#form_balance_sheet #account_balance_msg').text('');
//   }
// }



// $(document).on('click',".save_icon",function(e) 
// {
//     $('#loadingMessage').show();
//     $(".save_icon").addClass("disable");
//    	var form = $('#form_balance_sheet').serialize();

//     $.ajax({
//         type: 'post',
//         url: save_balance_sheet_url,
//         data: form ,
//         dataType: 'json',
//         success: function (response) 
//         {
//         	$('#loadingMessage').hide();
//         	setTimeout(function(){ $(".save_icon").removeClass("disable"); }, 3000);
//         	check_compulsory_field();
//         }
//     });

// });

// function check_compulsory_field()
// {
// 	$( ".compulsory" ).each(function() {
// 	  	if(!$(this).val())
// 	  	{
// 	  		alert("Data saved successfully! Warning: Some of the field(s) is not completed.");
// 	  		return false;
// 	  	}
// 	});
// }


// $(document).on('click',".delete_icon",function(e) 
// {
//     //check if there is any selected checkbox
//     var check_cbx = false;
//     var selected_line = [];
//     $( ".cbx" ).each(function() {
// 	  	if($(this).is(':checked'))
// 	  	{
// 	  		check_cbx = true;
// 	  		selected_line.push($(this).val());
// 	  	}
// 	});

// 	if(check_cbx)
// 	{
// 		bootbox.confirm({
//             message: "Do you want to delete selected line(s)?",
//             closeButton: false,
//             buttons: {
//                 confirm: {
//                     label: 'Yes',
//                     className: 'btn_blue'
//                 },
//                 cancel: {
//                     label: 'No',
//                     className: 'btn_cancel'
//                 }
//             },
//             callback: function (result) {
//                 //console.log(result);
//                 if(result == true)
//                 {
//                 	console.log(selected_line);
//                     $.post(delete_bs_line_item_url, { 'line_item_ids': selected_line }, function(data, status){
//                         if(data){
//                             location.reload();
//                         }
//                     });
//                 }
//             }
//         })
// 	}
// 	else
// 	{
// 		alert("No selected line");
// 	}

// });


// function load_index(){

// 	var ref_index = 1;
// 	$('.ref_no').each(function(index, tr) { 


// 	   	$(this).html(ref_index);

// 	   	ref_index++;
	   


// 	});
// }

$(document).on('click',".export_icon",function(e) 
{
    $('#loadingMessage').show();

   	// var form = $('#form_balance_sheet').serialize();

   	// console.log($(".bs_assertion"));

    $.ajax({
        type: 'post',
        url: export_farbs_pdf_url,
        dataType: 'json',
        success: function (response) 
        {
        	$('#loadingMessage').hide();
        	window.open(
              response.link,
              '_blank' // <- This is what makes it open in a new window.
            );
        }
    });
            

});

