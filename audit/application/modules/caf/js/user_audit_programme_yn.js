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
});

$(document).on('click',".save_icon",function(e) 
{
    // $('#loadingMessage').show();
    $(".save_icon").addClass("disable");
   	var form = $('#user_audit_programme_yn_form').serialize();

    $.ajax({
        type: 'post',
        url: save_programme_yn_input_url,
        data: form ,
        dataType: 'json',
        success: function (response) 
        {
        	// $('#loadingMessage').hide();
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
        url: export_programme_yn_pdf_url,
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

//child will have same answer if parent choose an answer
$(document).on('change',".first_level .y_n_na",function(){  
    
    var tr = $(this).parent().parent();
    selected_parent_value = $(this).val();


    var tr_class = ($(tr).attr("class")).split(" ");

    const checker = value => ['group'].some(element => value.includes(element));
    // console.log(tr_class.filter(checker));

    var group_class = tr_class.filter(checker);
    // console.log(tr_class);
    if(selected_parent_value != "")
    {
             
        $.each($('.'+group_class), function( k, v ) {
            if(!$(this).hasClass("first_level"))
            {
                var dropdown = $(this).find('.y_n_na');
                // console.log(dropdown);
                dropdown.val(selected_parent_value).trigger('change.select2');
                
            }

        });
    }
});

$(document).on('change',".second_level .y_n_na",function(){  
    
    var tr = $(this).parent().parent();
    selected_parent_value = $(this).val();


    var tr_class = ($(tr).attr("class")).split(" ");

    const checker = value => ['second_gp'].some(element => value.includes(element));
    // console.log(tr_class.filter(checker));

    var group_class = tr_class.filter(checker);
    // console.log(tr_class);
    if(selected_parent_value != "")
    {
             
        $.each($('.'+group_class), function( k, v ) {
            if(!$(this).hasClass("second_level"))
            {
                var dropdown = $(this).find('.y_n_na');
                // console.log(dropdown);
                dropdown.val(selected_parent_value).trigger('change.select2');
                
            }

        });
    }
});

$(document).on('change',".y_n_na",function(){  
    
    var tr = $(this).parent().parent();
    selected_value = $(this).val();

    var tr_class = ($(tr).attr("class")).split(" ");

    const checker = value => ['group'].some(element => value.includes(element));
    // console.log(tr_class.filter(checker));
    var group_class = tr_class.filter(checker);

    const second_gp_checker = value => ['second_gp'].some(element => value.includes(element));
    // console.log(tr_class.filter(checker));
    var group_class = tr_class.filter(checker);
    var second_gp_class = tr_class.filter(second_gp_checker);

    // console.log(tr_class);
    var prev_value = selected_value;
    $.each($('.'+group_class[0]), function( k, v ) {
        if(!$(this).hasClass("first_level"))
        {
            var dropdown_value = ($(this).find('.y_n_na')).val();
            if (dropdown_value != prev_value) {
                $('.'+group_class[0]+'.first_level .y_n_na').val('').trigger('change.select2');
            }

            prev_value = dropdown_value;
        }


    });

    // var prev_value = selected_value;
    // console.log(group_class);
    var prev_value2 = selected_value;

    if(second_gp_class.length > 0)
    {
        if($('.'+second_gp_class[0]).length > 1)
        {
            $.each($('.'+second_gp_class[0]), function( k, v ) {
                
                if(!$(this).hasClass("second_level"))
                {
                    // console.log($(this).attr('class'));
                    var dropdown_value = ($(this).find('.y_n_na')).val();
                    if (dropdown_value != prev_value2) {
                        $('.'+second_gp_class[0]+'.second_level .y_n_na').val('').trigger('change.select2');
                    }

                   prev_value2 = dropdown_value;
                }
                

            });
        }
        else
        {
             $('.'+second_gp_class[0]+'.second_level .y_n_na').val(prev_value2).trigger('change.select2');
        }
    }
    
});



// draw line for each main content
$.each(programme_content, function( k, v ) {
    $(".group-"+v.id+" .y_n_td").last().css( "border-bottom", "1px solid black" );
    $(".group-"+v.id+" .review_td").last().css( "border-bottom", "1px solid black" );
});
