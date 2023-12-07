$(".print_icon").hide();
$(".access_icon").hide();

$(".addline_icon").addClass("disable");
document.getElementById("addline_icon").src = base_url + "img/add_line-disable.png";

$(".orientation_icon").addClass("disable");
document.getElementById("orientation_icon").src = base_url + "img/orientation-disable.png";

$(".textbox_icon").addClass("disable");
document.getElementById("textbox_icon").src = base_url + "img/textbox-disable.png";

$(".delete_icon").addClass("disable");
document.getElementById("delete_icon").src = base_url + "img/delete-disable.png";

$(".save_icon").addClass("disable");
document.getElementById("save_icon").src = base_url + "img/save-disable.png";

if(!show_data_content)
{
	$(".export_icon").addClass("disable");
	document.getElementById("export_icon").src = base_url + "img/export-disable.png";
}

$(document).ready(function () {
	$('.select2').select2();
});


// $(document).on('click',".save_icon",function(e) 
// {
//     $('#loadingMessage').show();
//     $(".save_icon").addClass("disable");
//    	var form = $('#form_state_detailed_profit_loss').serialize();

//     $.ajax({
//         type: 'post',
//         url: save_profit_loss_url,
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
//                     $.post(delete_pl_line_item_url, { 'line_item_ids': selected_line }, function(data, status){
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

$(document).on('click',".export_icon",function(e) 
{
    $('#loadingMessage').show();
    
    $.ajax({
        type: 'post',
        url: export_farpl_pdf_url,
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


// function load_index(){

// 	var ref_index = 1;
// 	$('.ref_no').each(function(index, tr) { 


// 	   	$(this).html(ref_index);

// 	   	ref_index++;
	   


// 	});
// }
