function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        //console.log(data);
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
          //console.log(e);
          // alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
        };
        $.ajax({
            url: url,
            type: method,
            data: data,
            success: successRes,
            error: errorRes,
            dataType: type,
            timeout: 60000
        });

    }

}

$(document).ready(function() {
    $('#rootwizard').bootstrapWizard({
        tabClass: 'wizard-steps',
        nextSelector: 'ul.pager li.next',
        previousSelector: 'ul.pager li.previous',
        firstSelector: null,
        lastSelector: null,
        onTabClick: function(tab, navigation, index) {
            if($('.index_alpha').val() == "")
            {
                toastr.error("Please select index", "Not allowed");
                return false;
            }
            else
            {
                // console.log($('.programme_type').val());
                // console.log($('.index_alpha').val());
                return true;
            }
        }
    });

    if(editing_flag)
    {
        $('.index_alpha').attr('disabled', true);
    }

    // add_existing_step_and_child(procedure_lines);

    $.validator.addMethod("checkIndexUnique", 
        function(value, element) {
            var result = false;
            $.ajax({
                type:"POST",
                async: false,
                url: check_avail_index_url, // script to validate in server side
                data: {index: value},
                success: function(data) {
                    result = (data == "true") ? true : false;
                    // console.log(result);
                    form_is_valid = result;
                    // $('#loadingmessage').hide();
                }
            });
            // return true if username is exist in database
            return result; 
        }, 
        "This index already exist! Try another."
    );



});

$(document).on('click',"#save_programme_content",function() {
    
    submit_all_form();

});


function submit_all_form()
{
    $("#loadingmessage").show();
    $('.index_alpha').attr('disabled', false);

    if(arr_deleted_ques.length > 0 )
    {
        delete_data_ques = '&arr_deleted_ques=' + encodeURIComponent(arr_deleted_ques);
    }
    else
    {
        delete_data_ques = "";
    }


    $("#audit_programme_info_form").validate({
        rules: {
            "index_alpha": {
                checkIndexUnique: true
            }
        }
    });

    if(form_is_valid)
    {
        console.log(form_is_valid);
        $.ajax({
            type: 'POST',
            url: save_all_qa_programme_setting_url,
            data: $("#audit_programme_info_form, #audit_question_form").serialize() + delete_data_ques,
            dataType: 'json',
            success: function(response){

                // }
                if(response.status == "success")
                {
                    $("#loadingmessage").hide();
                    
                    window.location.href = base_url+'/audit/setting/add_audit_programme_qa/'+response.master_id;

                    
                }
                else
                {
                    // alert("Selected company may not assigned to any service or servicing firm. Failed to add bank authorization.");
                }
             
                // window.location.href = auditor_url;
                
            }
        });
    }
    else
    {
        toastr.error("The programme index already exist", "Unable to save")
        return;
    } 

    
}


$(document).on('change',".index_alpha",function(){ 
    
    $('#loadingmessage').show();
    var selected_index = $('.index_alpha').val();

    $("#audit_programme_info_form").validate({
        rules: {
            "index_alpha": {
                checkIndexUnique: true

            }
        }
    });

    $.ajax({
           type: "POST",
           url:  "setting/retrieve_previous_record",
           data: '&index=' + selected_index  + '&programme_type=' + 3,
           success: function(data)
           {
                data = JSON.parse(data);

                // console.log(data);
                if(data){
                    if(data.edit_programme)
                    {
                        $(".programme_title").val(data.edit_programme.title);
                        $('.programme_type').val(data.edit_programme.type).change();
                    }

                    if(data.question_lines)
                    {
                        $('#question_line').empty();
                        question_lines = data.question_lines;

                      
					    if(question_lines != "")
					    {

					        question_lines.forEach(function (key)
					        {
					            var content = jQuery('#clone_model tr'),
					      
					            element = null,    
					            element = content.clone();

					            if(key.id)
					            {   
					                element.find("[name='question_id[]']").val(key.id);
					            }
					            element.find("[name='question_text[]']").val(key.question_text);
					            // element.find(".programme_assertion");
					            // console.log(size);
					            // element.attr('id', 'rec-'+size);
					            // element.find('.delete-record').attr('data-id', size);
					            element.appendTo('#question_line');


					        });   
					        check_question_line_length();
					        rewrite_question_index();
					    }
						
                    }

          
                }
                else
                {
                    $(".programme_title").val("");
                    $('.programme_type').val("").change();

                    $('#question_line').empty();
                    add_question_line();
    
                }


                $('#loadingmessage').hide();
           }
       });
});


function add_question_line()
{
	var content = jQuery('#clone_model tr'),

	element = null,    
	element = content.clone();
	// console.log(size);
	// element.attr('id', 'rec-'+size);
	// element.find('.delete-record').attr('data-id', size);
	element.appendTo('#question_line');
    var this_objective_text     = element.find("textarea");
    var this_index_td	        = element.find(".index_td");

    $(this_objective_text).focus();
    $(this_index_td).html($('#question_line tr').length+". ");

	check_question_line_length();

    // var selected_programme_type = $('.programme_type').val();
    // var cn = new Client();
    // cn.getAssertionDropdown(selected_programme_type, this_assertion_dropdown);
}

function remove_question_line(element) 
{
    var tr = $(element).parent().parent();
    var deleted_row_id = tr.find('.question_id').val();

    // console.log(tr);
    // console.log(deleted_row_id);
    // console.log($(tr));
        // payment_receipt_service_id = tr.find('input[name="payment_receipt_service_id[]"]').val();
    arr_deleted_ques.push(deleted_row_id);
    tr.closest("tr").remove();

    check_question_line_length();
    rewrite_question_index();

}

function check_question_line_length()
{
	var rowCount = $('#question_line tr').length;
	// console.log(rowCount);
	if(rowCount == 1)
	{
		$('.remove_question_line').hide();
	}
	else 
	{
		$('.remove_question_line').show();
	}
}

function rewrite_question_index()
{
	$('#question_line .index_td').each(function(index, value) {
	  	$(this).html(index+1);
	});
}

if ($('#question_line >tr').length < 1)
{

    if(question_lines != "")
    {

        question_lines.forEach(function (key)
        {
            var content = jQuery('#clone_model tr'),
      
            element = null,    
            element = content.clone();

            if(key.id)
            {   
                element.find("[name='question_id[]']").val(key.id);
            }
            element.find("[name='question_text[]']").val(key.question_text);
            // element.find(".programme_assertion");
            // console.log(size);
            // element.attr('id', 'rec-'+size);
            // element.find('.delete-record').attr('data-id', size);
            element.appendTo('#question_line');


        });   
        check_question_line_length();
        rewrite_question_index();
    }
}
