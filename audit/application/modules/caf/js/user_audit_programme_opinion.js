// $(".print_icon").hide();
// $(".access_icon").hide();

// $(".addline_icon").addClass("disable");
// document.getElementById("addline_icon").src = base_url + "img/add_line-disable.png";

// $(".orientation_icon").addClass("disable");
// document.getElementById("orientation_icon").src = base_url + "img/orientation-disable.png";

// $(".textbox_icon").addClass("disable");
// document.getElementById("textbox_icon").src = base_url + "img/textbox-disable.png";

$(document).ready(function () {
    $('.select2').select2();

    $('.form_date').datetimepicker({
		format: 'DD/MM/YYYY'
	});

    $(".check_B").trigger("change");
    $(".check_D").trigger("change");
    $(".check_E").trigger("change");
    $(".check_F").trigger("change");
    $(".check_G").trigger("change");
    $(".check_H").trigger("change");
    rewrite_alpha_index();


});

$(document).on('click',".save_icon",function(e) 
{
    $('#loadingMessage').show();
    $(".save_icon").addClass("disable");
   	var form = $('#user_audit_programme_opinion_form').serialize();

    $.ajax({
        type: 'post',
        url: save_programme_opinion_input_url,
        data: form,
        dataType: 'json',
        success: function (response) 
        {
        	$('#loadingMessage').hide();
        	setTimeout(function(){ $(".save_icon").removeClass("disable"); }, 3000);
        	location.reload();
        }
    });

});

$(document).on('click',".export_icon",function(e) 
{
    $('#loadingMessage').show();
    // var form = $('#form_balance_sheet').serialize();

    $.ajax({
        type: 'post',
        url: export_programme_opinion_pdf_url,
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


$(document).on('change',".check_B",function(){ 
    
    $('#loadingmessage').show();
    var selected_value = $(this).val();

    if(selected_value == "Unqualified opinion")
    {
    	$(".hide_show_B").hide();
    }
    else
    {
    	$(".hide_show_B").show();
    }

    rewrite_alpha_index();
});

$(document).on('change',".check_D",function(){ 
    
    $('#loadingmessage').show();
    var selected_value = $(this).val();

    if(selected_value == "Appropriate")
    {
    	$(".hide_show_D").hide();
    }
    else
    {
    	$(".hide_show_D").show();
    }

    rewrite_alpha_index();
});

$(document).on('change',".check_E",function(){ 
    
    $('#loadingmessage').show();
    var selected_value = $(this).val();

    if(selected_value == "Yes")
    {
        $(".hide_show_E").show();
    }
    else
    {
        $(".hide_show_E").hide();
    }

    rewrite_alpha_index();
});


$(document).on('change',".check_F",function(){ 
    
    $('#loadingmessage').show();
    var selected_value = $(this).val();

    if(selected_value == "Yes")
    {
    	$(".hide_show_F").show();
    }
    else
    {
    	$(".hide_show_F").hide();
    }

    rewrite_alpha_index();
});

$(document).on('change',".check_G",function(){ 
    
    $('#loadingmessage').show();
    var selected_value = $(this).val();

    if(selected_value == "Yes")
    {
    	$(".hide_show_G").show();
    }
    else
    {
    	$(".hide_show_G").hide();
    }

    rewrite_alpha_index();
});


$(document).on('change',".check_H",function(){ 
    
    $('#loadingmessage').show();
    var selected_value = $(this).val();

    if(selected_value == "Unqualified opinion")
    {
    	$(".hide_show_H").hide();
    }
    else
    {
    	$(".hide_show_H").show();
    }

    rewrite_alpha_index();
});

function rewrite_alpha_index()
{
    $('.alpha_index:visible').each(function(index, value) {
        $(this).html((String.fromCharCode((index+1) + 64)) + ". ");
    });
}