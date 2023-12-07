// $(".print_icon").hide();
// $(".access_icon").hide();

// $(".addline_icon").addClass("disable");
// document.getElementById("addline_icon").src = base_url + "img/add_line-disable.png";

// $(".orientation_icon").addClass("disable");
// document.getElementById("orientation_icon").src = base_url + "img/orientation-disable.png";

// $(".textbox_icon").addClass("disable");
// document.getElementById("textbox_icon").src = base_url + "img/textbox-disable.png";

$(document).ready( function () {
	$('.form_datetime').datetimepicker({
		// weekStart: 1,
		// todayBtn: 1,
		// autoclose: 1,
		// todayHighlight: 1,
		// startView: 2,
		// forceParse: 0,
		// showMeridian: 1
		format: 'DD/MM/YYYY hh:mm a'
	});

	$('.form_date').datetimepicker({
		format: 'DD/MM/YYYY'
	});
});

$(document).on('click',".save_icon",function(e) 
{
    $('#loadingMessage').show();
    $(".save_icon").addClass("disable");

    if(arr_deleted_attendees.length > 0 )
    {
        delete_data_attendees = '&arr_deleted_attendees=' + encodeURIComponent(arr_deleted_attendees);
    }
    else
    {
        delete_data_attendees = "";
    }

    if(arr_deleted_agenda.length > 0 )
    {
        delete_data_agenda = '&arr_deleted_agenda=' + encodeURIComponent(arr_deleted_agenda);
    }
    else
    {
        delete_data_agenda = "";
    }

    if(arr_deleted_absent.length > 0 )
    {
        delete_data_absent = '&arr_deleted_absent=' + encodeURIComponent(arr_deleted_absent);
    }
    else
    {
        delete_data_absent = "";
    }

   	var form = $('#user_audit_programme_meeting_form').serialize();

    $.ajax({
        type: 'post',
        url: save_programme_meeting_input_url,
        data: form + delete_data_attendees + delete_data_agenda + delete_data_absent,
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
        url: export_programme_meeting_pdf_url,
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

function hideshow_absent_tbl()
{

	if($(".absent_switch").is(":checked"))
	{
		$("#absent_tbl").show();
	}
	else
	{
		$("#absent_tbl").hide();
	}
}

hideshow_absent_tbl();

function add_attendees_line()
{
	var content = jQuery('#attendees_clone_model tr'),

	element = null,    
	element = content.clone();

	element.appendTo('#attendees_line');

	check_tbl_line_length("attendees");

}

function add_agenda_line()
{
	var content = jQuery('#agenda_clone_model tr'),

	element = null,    
	element = content.clone();

	element.appendTo('#agenda_line');

	check_tbl_line_length("agenda");


}

function add_absent_line()
{
	var content = jQuery('#absent_clone_model tr'),

	element = null,    
	element = content.clone();

	element.appendTo('#absent_line');
	var this_date_communicated     = element.find(".form_date");
	$(this_date_communicated).datetimepicker({
		format: 'DD/MM/YYYY'
	});

	check_tbl_line_length("absent");


}

function remove_attendees_line(element) 
{
    var tr = $(element).parent().parent();
    var deleted_row_id = tr.find('.attendees_id').val();

    arr_deleted_attendees.push(deleted_row_id);

    // arr_deleted_ques.push(deleted_row_id);
    tr.closest("tr").remove();

    check_tbl_line_length("attendees");


}

function remove_agenda_line(element) 
{
    var tr = $(element).parent().parent();
    var deleted_row_id = tr.find('.agenda_id').val();

    arr_deleted_agenda.push(deleted_row_id);

    // arr_deleted_ques.push(deleted_row_id);
    tr.closest("tr").remove();

    check_tbl_line_length("agenda");


}

function remove_absent_line(element) 
{
    var tr = $(element).parent().parent();
    var deleted_row_id = tr.find('.absent_id').val();

    arr_deleted_absent.push(deleted_row_id);

    // arr_deleted_ques.push(deleted_row_id);
    tr.closest("tr").remove();

    check_tbl_line_length("absent");


}


function check_tbl_line_length(tbl_name)
{
	var tbl_tr = "#" + tbl_name + "_line tr";
	var remove_icon = ".remove_" + tbl_name + "_line";
	var rowCount = $(tbl_tr).length;
	// console.log(rowCount);
	if(rowCount == 1)
	{
		$(remove_icon).hide();
	}
	else 
	{
		$(remove_icon).show();
	}
}

if ($('#attendees_line >tr').length < 1)
{

    if(meeting_attendees != "")
    {

        meeting_attendees.forEach(function (key)
        {
            var content = jQuery('#attendees_clone_model tr'),
      
            element = null,    
            element = content.clone();

            if(key.id)
            {   
                element.find("[name='attendees_id[]']").val(key.id);
            }
            element.find("[name='attendees_name[]']").val(key.attendees_name);
            element.find("[name='designation[]']").val(key.attendees_designation);
     
            element.appendTo('#attendees_line');

        });   
        check_tbl_line_length("attendees");
    }
}

if ($('#agenda_line >tr').length < 1)
{

    if(meeting_agenda != "")
    {

        meeting_agenda.forEach(function (key)
        {
            var content = jQuery('#agenda_clone_model tr'),
      
            element = null,    
            element = content.clone();

            if(key.id)
            {   
                element.find("[name='agenda_id[]']").val(key.id);
            }
            element.find("[name='agenda_text[]']").val(key.agenda_text);
            element.find("[name='minutes_of_meeting[]']").val(key.minutes_of_meeting);
     
            element.appendTo('#agenda_line');

        });   
        check_tbl_line_length("agenda");
    }
}

if ($('#absent_line >tr').length < 1)
{

    if(meeting_absent != "")
    {

        meeting_absent.forEach(function (key)
        {
            var content = jQuery('#absent_clone_model tr'),
      
            element = null,    
            element = content.clone();

            if(key.id)
            {   
                element.find("[name='absent_id[]']").val(key.id);
            }
            element.find("[name='absent_name[]']").val(key.absent_name);
            element.find("[name='engagement_role[]']").val(key.engagement_role);
            element.find("[name='absent_subject[]']").val(key.absent_subject);
            element.find("[name='date_communicated[]']").val(key.date_communicated);
     
            element.appendTo('#absent_line');

        });   
        check_tbl_line_length("absent");
    }
}