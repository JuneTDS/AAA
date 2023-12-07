// $(".print_icon").hide();
// $(".access_icon").hide();

// $(".addline_icon").addClass("disable");
// document.getElementById("addline_icon").src = base_url + "img/add_line-disable.png";

// $(".orientation_icon").addClass("disable");
// document.getElementById("orientation_icon").src = base_url + "img/orientation-disable.png";

// $(".textbox_icon").addClass("disable");
// document.getElementById("textbox_icon").src = base_url + "img/textbox-disable.png";

$(document).on('click',".save_icon",function(e) 
{
    $('#loadingMessage').show();
    $(".save_icon").addClass("disable");
   	var form = $('#user_audit_programme_qa_form').serialize();

    $.ajax({
        type: 'post',
        url: save_programme_qa_input_url,
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
        url: export_programme_qa_pdf_url,
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
