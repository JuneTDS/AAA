$(document).ready(function () {
	$('.select2').select2();
	hideshow_2nd_partner();
});

function hideshow_2nd_partner()
{
	if($(".require_switch").is(":checked"))
	{
		$(".hide_show_tr").show();
	}
	else
	{
		$(".hide_show_tr").hide();
	}
}

$(document).on('click',".save_icon",function(e) 
{
    $('#loadingMessage').show();
    $(".save_icon").addClass("disable");
   	var form = $('#user_audit_programme_fcm_form').serialize();

    $.ajax({
        type: 'post',
        url: save_programme_fcm_input_url,
        data: form ,
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
        url: export_programme_fcm_pdf_url,
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
