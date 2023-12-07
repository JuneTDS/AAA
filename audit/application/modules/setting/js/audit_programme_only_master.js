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

    $("#audit_programme_info_form").validate({
        rules: {
            "index_alpha": {
                checkIndexUnique: true
            }
        }
    });

    if(form_is_valid)
    {
        // console.log(form_is_valid);
        programme_type_id = $("#programme_type_id").val();
        $.ajax({
            type: 'POST',
            url: save_only_master_programme_setting_url,
            data: $("#audit_programme_info_form").serialize(),
            dataType: 'json',
            success: function(response){

                // }
                if(response.status == "success")
                {
                    $("#loadingmessage").hide();
                    
                    window.location.href = base_url+'/audit/setting/add_audit_programme_only_master/'+programme_type_id+'/'+response.master_id;

                    
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

    programme_type_id = $("#programme_type_id").val();

    $("#audit_programme_info_form").validate({
        rules: {
            "index_alpha": {
                checkIndexUnique: true

            }
        }
    });

    $.ajax({
           type: "POST",
           url:  retrieve_previous_record_url,
           data: '&index=' + selected_index  + '&programme_type=' + programme_type_id ,
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
          
                }
                else
                {
                    $(".programme_title").val("");
                    $('.programme_type').val("").change();
    
                }


                $('#loadingmessage').hide();
           }
       });
});

