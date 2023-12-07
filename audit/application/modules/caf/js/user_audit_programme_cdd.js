$(document).ready(function () {
	$('.select2').select2();
    autofill();
});

$(document).on('click',".save_icon",function(e) 
{
    $('#loadingMessage').show();
    $(".save_icon").addClass("disable");
   	var form = $('#user_audit_programme_cdd_form').serialize();

    $.ajax({
        type: 'post',
        url: save_programme_cdd_input_url,
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
        url: export_programme_cdd_pdf_url,
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

$(document).on('change',".check_answer input[type=radio]",function(){ 
    autofill();
});

function autofill()
{
    var all_no = true;

    if($('.check_answer input[type=radio]:checked').size() == 31)
    {
        $('.check_answer input[type=radio]:checked').each(function(index, value) {
            if($(this).val() == 1)
            {
                // console.log($('.s3_risk'));
                // $('.s3_risk').html("High");
                // $('.s3_cdd').hmtl("Enhanced CDD");

                all_no = false;

                return false;
            }

        });

        if(!all_no)
        {
            $('.s3_risk').text("High");
            $('.s3_cdd').text("Enhanced CDD");
        }

        if(all_no)
        {
            $('.s3_risk').text("Low");
            $('.s3_cdd').text("Normal CDD");
        }
    }
    else
    {
        $('.s3_risk').text("");
        $('.s3_cdd').text("");
    }

    var all_no_s1 = true;

    if($('.check_s1 input[type=radio]:checked').size() == 4)
    {
        $('.check_s1 input[type=radio]:checked').each(function(index, value) {
            if($(this).val() == 1)
            {
                // console.log($('.s3_risk'));
                // $('.s3_risk').html("High");
                // $('.s3_cdd').hmtl("Enhanced CDD");

                all_no_s1 = false;

                return false;
            }

        });

        if(!all_no_s1)
        {
            $('.s4_relationship').text("Terminate");
        }

        if(all_no_s1)
        {
            $('.s4_relationship').text("Maintain");
        }
    }
    else
    {
        $('.s4_relationship').text("");
    }

}

