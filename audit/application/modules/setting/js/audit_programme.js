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


function Client() {
    
    var call = new ajaxCall();

    this.getAssertionDropdown = function(selected_programme_type=null, this_dropdown=null) {
        $('.programme_assertion').find('option').not(':nth-child(1)').remove();
        var url = base_url+"/"+folder+"/"+'setting/getAssertionDropdown';
        //console.log(url);
        var method = "get";
        var data = {"programme_type": selected_programme_type};
        $('.pic_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.programme_assertion').find("option:eq(0)").html("Select assertion");
            // console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    // if(selected_assertion != null && key == selected_assertion)
                    // {

                    //     option.attr('selected', 'selected');
                    //     // $('.pic_name').attr('disabled', true);
                    // }
                    // console.log(option);
                    $('.programme_assertion').append(option);
                });
                // console.log($('#objectives_line >tr').length);

                if ($('#objectives_line >tr').length < 1)
                {
                    $('#objectives_line .programme_assertion').select2();

                    if(objective_lines != "")
                    {

                        objective_lines.forEach(function (key)
                        {
                            var content = jQuery('#clone_model tr'),
                            size = jQuery('#adjustment_tbl >tbody >tr').length + 1,
                            element = null,    
                            element = content.clone();

                            if(key.id)
                            {   
                                element.find("[name='objective_id[]']").val(key.id);
                            }
                            element.find("[name='objective_text[]']").val(key.objective_text);
                            // element.find(".programme_assertion");
                            // console.log(size);
                            // element.attr('id', 'rec-'+size);
                            // element.find('.delete-record').attr('data-id', size);
                            element.appendTo('#objectives_line');
                            var this_assertion_dropdown = element.find(".programme_assertion");


                            this_assertion_dropdown.val(key.assertion);
                            // console.log(this_assertion_dropdown);
                            this_assertion_dropdown.select2();
                            // console.log('assign value')

                        });   
                        check_objective_line_length();
                    }
                }
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

}

$(document).ready(function() {
  	$('#rootwizard').bootstrapWizard({
		tabClass: 'wizard-steps',
		nextSelector: 'ul.pager li.next',
		previousSelector: 'ul.pager li.previous',
		firstSelector: null,
		lastSelector: null,
		onTabClick: function(tab, navigation, index) {
			if($('.programme_type').val() == "" && $('.index_alpha').val() == "")
			{
				toastr.error("Please select index and programme type", "Not allowed");
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

    $('.programme_type').change();
    $('.select2').select2();

    if(editing_flag)
    {
        $('.index_alpha').attr('disabled', true);
    }

    add_existing_step_and_child(procedure_lines);

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
                }
            });
            // return true if username is exist in database
            return result; 
        }, 
        "This index already exist! Try another."
    );



 });

//get assertion dropdown based on programme type
$(document).on('change',".programme_type",function(){
    
    var selected_programme_type = $('.programme_type').val();
    var cn = new Client();
    cn.getAssertionDropdown(selected_programme_type);


    

});

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
           data: '&index=' + selected_index  + '&programme_type=' + 1,
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

                    if(data.objective_lines)
                    {
                        $('#objectives_line').empty();
                        objective_lines = data.objective_lines;
                        check_objective_line_length();
                    }

                    if(data.procedure_lines)
                    {
                        $('#audit_procedure_table').empty();
                        add_existing_step_and_child(data.procedure_lines);
                    }

                    if(data.contentTree_json)
                    {
                        // console.log(data.contentTree_json);
                        $('#programme_content_tree').jstree().settings.core.data = data.contentTree_json;
                        $('#programme_content_tree').jstree(true).refresh();
                    }
                }
                else
                {
                    $(".programme_title").val("");
                    $('.programme_type').val("").change();

                    $('#objectives_line').empty();
                    add_objective_line();
                        


                    $('#audit_procedure_table').empty();
                    add_step_line();

                    $('#programme_content_tree').jstree('destroy');
                    $('#programme_content_tree').jstree({
                        "core": {
                            'data': {
                                // 'url': "paf_upload/categoriedDefaultData/" + $('#fs_company_info_id').val(),
                                'url': contentAllData_url + "/" + $('#master_id').val(),
                                'dataType': 'json'
                                // 'data': function (node) {
                                //     console.log(node);
                                //     return { 'id': node.id, 'parent': node.parent, 'type': node.type };
                                // }
                            },
                            'check_callback': function (operation, node, node_parent, node_position, more) {
                                if (operation === "move_node") {
                                    if (node_parent.parents.length >= 4 || node.data.Type === "level_4") {
                                        return false;
                                    }
                                    else
                                    {
                                        // console.log(node.parents.length);
                                    }
                                }
                                return true;  //allow all other operations
                            }
                        },
                        "types" : {
                            "level_1" : {
                                "icon" : false,
                                "a_attr" : { "style" : "font-weight:bold;font-style:italic;"}
                            },
                            "level_2" : {
                                "icon" : false
                            },
                            "level_3" : {
                                "icon" : false
                            },
                            "level_4" : {
                                "icon" : false,
                                "a_attr" : { "style" : "color:#154069 !important" }
                            }
                        },

                        "plugins": [
                            "themes",
                            "types",
                            "contextmenu",
                            "dnd"
                        ],


                        'contextmenu' : {
                            'items' : customMenu
                        }
                    })
                    .on('move_node.jstree', function (e, data) {
                        level = data.node.parents.length;
                        // console.log(data.node.parents.length);
                        var tree = $(this).jstree(true);

                        if(level == 1)
                        {
                            tree.set_type(data.node.id, 'level_1');
                        }
                        else if(level == 2)
                        {
                            tree.set_type(data.node.id, 'level_2');
                        }
                        else if(level == 3)
                        {
                            tree.set_type(data.node.id, 'level_3');
                        }
                        else if(level == 4)
                        {
                            tree.set_type(data.node.id, 'level_4');
                        }
                        
                    })
                    .bind("loaded.jstree", function (event, data) {
                        $(this).jstree("open_all");
                    });
                }


                $('#loadingmessage').hide();
           }
       });

    

});

$(document).on('click',"#save_objective_line",function() 
{   
    submit_all_form();

    // if(arr_deleted_objtv.length > 0 )
    // {
    //     delete_data = '&arr_deleted_objtv=' + encodeURIComponent(arr_deleted_objtv);
    // }
    // else
    // {
    //     delete_data = "";
    // }


    // $('.index_alpha').attr('disabled', false);
    // $.ajax({
    //     type: 'POST',
    //     url: save_objective_line_url,
    //     data: $("#audit_programme_info_form, #audit_objectives_form").serialize() + delete_data,
    //     dataType: 'json',
    //     success: function(response){

    //         // }
    //         if(response.status == "success")
    //         {
                
    //             window.location.href = base_url+'/audit/setting/add_audit_programme/'+response.master_id;
                
    //         }
    //         else
    //         {
    //             // alert("Selected company may not assigned to any service or servicing firm. Failed to add bank authorization.");
    //         }
         
    //         // window.location.href = auditor_url;
            
    //     }
    // });
});

$(document).on('click',"#save_ra_factor",function() {
    
    submit_all_form();

    // $('.index_alpha').attr('disabled', false);
    // $.ajax({
    //     type: 'POST',
    //     url: save_ra_factor_url,
    //     data: $("#audit_programme_info_form, #ra_factor_form").serialize(),
    //     dataType: 'json',
    //     success: function(response){

    //         // }
    //         if(response.status == "success")
    //         {
            
    //             window.location.href = base_url+'/audit/setting/add_audit_programme/'+response.master_id;
                
    //         }
    //         else
    //         {
    //             // alert("Selected company may not assigned to any service or servicing firm. Failed to add bank authorization.");
    //         }
         
    //         // window.location.href = auditor_url;
            
    //     }
    // });
});

$(document).on('click',"#save_procedure_design",function() {

    submit_all_form();


    // if(arr_deleted_step.length > 0 )
    // {
    //     delete_data = '&arr_deleted_step=' + encodeURIComponent(arr_deleted_step);
    // }
    // else
    // {
    //     delete_data = "";
    // }

    // $('.index_alpha').attr('disabled', false);
    // $.ajax({
    //     type: 'POST',
    //     url: save_procedure_design_url,
    //     data: $("#audit_programme_info_form, #procedure_design_form").serialize() + delete_data,
    //     dataType: 'json',
    //     success: function(response){

    //         // }
    //         if(response.status == "success")
    //         {
            
    //             window.location.href = base_url+'/audit/setting/add_audit_programme/'+response.master_id;
                
    //         }
    //         else
    //         {
    //             // alert("Selected company may not assigned to any service or servicing firm. Failed to add bank authorization.");
    //         }
         
    //         // window.location.href = auditor_url;
            
    //     }
    // });
});

$(document).on('click',"#save_programme_content",function() {

    
    submit_all_form();

    // $('.index_alpha').attr('disabled', false);

    // var v = $('#programme_content_tree').jstree(true).get_json('#', { flat: true });
    // var programme_content_tree = JSON.parse(JSON.stringify(v));

    // $.ajax({
    //     type: 'POST',
    //     url: save_programme_content_url,
    //     data: $("#audit_programme_info_form").serialize() + '&' + $.param({ 'programme_content_tree': programme_content_tree }),
    //     dataType: 'json',
    //     success: function(response){

    //         // }
    //         if(response.status == "success")
    //         {
            
    //             window.location.href = base_url+'/audit/setting/add_audit_programme/'+response.master_id;
                
    //         }
    //         else
    //         {
    //             // alert("Selected company may not assigned to any service or servicing firm. Failed to add bank authorization.");
    //         }
         
    //         // window.location.href = auditor_url;
            
    //     }
    // });
});

function submit_all_form()
{
    $("#loadingmessage").show();
    $('.index_alpha').attr('disabled', false);

    if(arr_deleted_objtv.length > 0 )
    {
        delete_data_ojbtv = '&arr_deleted_objtv=' + encodeURIComponent(arr_deleted_objtv);
    }
    else
    {
        delete_data_ojbtv = "";
    }

    if(arr_deleted_step.length > 0 )
    {
        delete_data_step = '&arr_deleted_step=' + encodeURIComponent(arr_deleted_step);
    }
    else
    {
        delete_data_step = "";
    }

    var v = $('#programme_content_tree').jstree(true).get_json('#', { flat: true });
    var programme_content_tree = JSON.parse(JSON.stringify(v));

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
        $.ajax({
            type: 'POST',
            url: save_all_programme_setting_url,
            data: $("#audit_programme_info_form, #audit_objectives_form, #ra_factor_form, #procedure_design_form").serialize() + delete_data_ojbtv + delete_data_step + '&' + $.param({ 'programme_content_tree': programme_content_tree }),
            dataType: 'json',
            success: function(response){

                // }
                if(response.status == "success")
                {
                    $("#loadingmessage").hide();
                    
                    window.location.href = base_url+'/audit/setting/add_audit_programme/'+response.master_id;

                    
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

    

    // $.ajax({
    //     type: 'POST',
    //     url: save_ra_factor_url,
    //     data: $("#audit_programme_info_form, #ra_factor_form").serialize(),
    //     dataType: 'json',
    //     success: function(response){

    //         // }
    //         if(response.status == "success")
    //         {
            
    //             window.location.href = base_url+'/audit/setting/add_audit_programme/'+response.master_id;
                
    //         }
    //         else
    //         {
    //             // alert("Selected company may not assigned to any service or servicing firm. Failed to add bank authorization.");
    //         }
         
    //         // window.location.href = auditor_url;
            
    //     }
    // });

   

    // // $('.index_alpha').attr('disabled', false);
    // $.ajax({
    //     type: 'POST',
    //     url: save_procedure_design_url,
    //     data: $("#audit_programme_info_form, #procedure_design_form").serialize() + delete_data,
    //     dataType: 'json',
    //     success: function(response){

    //         // }
    //         if(response.status == "success")
    //         {
            
    //             window.location.href = base_url+'/audit/setting/add_audit_programme/'+response.master_id;
                
    //         }
    //         else
    //         {
    //             // alert("Selected company may not assigned to any service or servicing firm. Failed to add bank authorization.");
    //         }
         
    //         // window.location.href = auditor_url;
            
    //     }
    // });


    // $.ajax({
    //     type: 'POST',
    //     url: save_programme_content_url,
    //     data: $("#audit_programme_info_form").serialize() + '&' + $.param({ 'programme_content_tree': programme_content_tree }),
    //     dataType: 'json',
    //     success: function(response){

    //         // }
    //         if(response.status == "success")
    //         {
            
    //             window.location.href = base_url+'/audit/setting/add_audit_programme/'+response.master_id;
                
    //         }
    //         else
    //         {
    //             // alert("Selected company may not assigned to any service or servicing firm. Failed to add bank authorization.");
    //         }
         
    //         // window.location.href = auditor_url;
            
    //     }
    // });
}

function add_objective_line()
{
	var content = jQuery('#clone_model tr'),
	size = jQuery('#adjustment_tbl >tbody >tr').length + 1,
	element = null,    
	element = content.clone();
	// console.log(size);
	// element.attr('id', 'rec-'+size);
	// element.find('.delete-record').attr('data-id', size);
	element.appendTo('#objectives_line');
	var this_assertion_dropdown = element.find(".programme_assertion");
    var this_objective_text     = element.find("textarea");
    $(this_objective_text).focus();

	this_assertion_dropdown.select2();

	check_objective_line_length();

    // var selected_programme_type = $('.programme_type').val();
    // var cn = new Client();
    // cn.getAssertionDropdown(selected_programme_type, this_assertion_dropdown);
}

function remove_objective_line(element) 
{
    var tr = $(element).parent().parent();
    var deleted_row_id = tr.find('.objective_id').val();

    console.log(tr);
    // console.log(tr);
    // console.log(deleted_row_id);
    // console.log($(tr));
        // payment_receipt_service_id = tr.find('input[name="payment_receipt_service_id[]"]').val();
    arr_deleted_objtv.push(deleted_row_id);
    tr.closest("tr").remove();

    check_objective_line_length();


}

function check_objective_line_length()
{
	var rowCount = $('#objectives_line tr').length;
	// console.log(rowCount);
	if(rowCount == 1)
	{
		$('.remove_objective_line').hide();
	}
	else 
	{
		$('.remove_objective_line').show();
	}
}

function add_step_line()
{
	var content = jQuery('#clone_model_procedure > tbody > tr'),
	size = jQuery('#audit_procedure_table .parent_line').length + 1,
	element = null,    
	element = content.clone();
	// console.log(size);
	// element.attr('id', 'rec-'+size);
	element.find('.parent_line').val("Step " + size);

    element.find('.procedure_id').attr("name", "procedure["+temp_parent_id_count+"][id]");
    element.find('.parent_line').attr("name", "procedure["+temp_parent_id_count+"][step_text]");
    element.find('.child_line').attr("name", "procedure["+temp_parent_id_count+"][child_line][]");
    element.find('.temp_parent_id').val(temp_parent_id_count);

	element.appendTo('#audit_procedure_table');
    var this_child_line  = element.find('.child_line');
    // console.log(this_child_line);
    this_child_line.focus();

	// var this_assertion_dropdown = element.find(".programme_type");

	// this_assertion_dropdown.select2();
    temp_parent_id_count++

	check_step_line_length();
}

function add_existing_step_and_child(data)
{

    // console.log(data);
    var content = jQuery('#clone_model_procedure > tbody > tr'),
    size = jQuery('#audit_procedure_table .parent_line').length + 1,
    element = null;    
    // console.log(size);
    // element.attr('id', 'rec-'+size);
    if(data)
    {   
        data.forEach(function(entry) {
            // console.log(entry);
            element = content.clone();
            element.find('.parent_line').val(entry.step_text);
            if(entry.id)
            {
                element.find('.procedure_id').val(entry.id);
            }
            if ((entry.child_text).length > 0){
                element.find('.child_line').val(entry.child_text[0]);
            }

            element.find('.procedure_id').attr("name", "procedure["+temp_parent_id_count+"][id]");
            element.find('.parent_line').attr("name", "procedure["+temp_parent_id_count+"][step_text]");
            element.find('.child_line').attr("name", "procedure["+temp_parent_id_count+"][child_line][]");
            element.find('.temp_parent_id').val(temp_parent_id_count);
            element.appendTo('#audit_procedure_table');

            if ((entry.child_text).length > 1){
                var nested_table = element.find('.nested_audit_procedure_table');
                $.each(entry.child_text, function(k, v){
                    if(k > 0){
                        var content = '<tr>'+
                                            '<td class="borderless text-center" style="width:3%;">'+
                                                '<a class="amber remove_procedure_child" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;top:50px;" data-original-title="Remove this line"  onclick=remove_procedure_child(this) ><i class="fa fa-minus-circle amber" style="font-size:13px;"></i></a>'+
                                            '</td>'+
                                            '<td class="borderless text-left" style="width:97%;">'+
                                                '<input class="form-control" type="text" name="procedure['+temp_parent_id_count+'][child_line][]" value="'+v+'">'+
                                            '</td>'+
                                        '</tr>';

                        $(nested_table).append(content); 
                    }
                });

            }

            temp_parent_id_count++
        });
    }
    
    // var this_assertion_dropdown = element.find(".programme_type");

    // this_assertion_dropdown.select2();
    

    check_step_line_length();
}

function remove_step_line(element)
{
	var tr = $(element).parent().parent();
    var deleted_row_id = tr.find('.procedure_id').val();
    // console.log(tr);
    // console.log(deleted_row_id);
    // console.log($(tr));
        // payment_receipt_service_id = tr.find('input[name="payment_receipt_service_id[]"]').val();
    arr_deleted_step.push(deleted_row_id);
    tr.closest("tr").next().remove();
    tr.closest("tr").remove();
    

    check_step_line_length();
}

function add_procedure_child(element)
{
	var nested_table = $(element).parent().parent().parent();

    var parent_id = nested_table.find('.temp_parent_id').val();

	var content = "";

	content += '<tr>'+
            		'<td class="borderless text-center" style="width:3%;">'+
    					'<a class="amber remove_procedure_child" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;top:50px;" data-original-title="Remove this line"  onclick=remove_procedure_child(this) ><i class="fa fa-minus-circle amber" style="font-size:13px;"></i></a>'+
    				'</td>'+
                    '<td class="borderless text-left" style="width:97%;">'+
                    	'<input class="form-control" type="text" name="procedure['+parent_id+'][child_line][]">'+
                    '</td>'+
                '</tr>';

    content = $(content);

	$(nested_table).append(content); 
    var this_procedure_line  = content.find("input[type='text']");
    
    this_procedure_line.focus();


}

function remove_procedure_child(element)
{
	var tr = $(element).parent().parent();
    var deleted_row_id = tr.find('.adjustment_info_id').val();
    // console.log(tr);
    // console.log(deleted_row_id);
    // console.log($(tr));
        // payment_receipt_service_id = tr.find('input[name="payment_receipt_service_id[]"]').val();
    tr.closest("tr").remove();

}



function check_step_line_length()
{
	var rowCount = $('#audit_procedure_table .parent_line').length;
	// console.log(rowCount);
	if(rowCount == 1)
	{
		$('.remove_step_line').hide();
	}
	else 
	{
		$('.remove_step_line').show();
	}
}

$(document).ready(function() {

    $('#programme_content_tree').jstree({
        "core": {
            'data': {
                // 'url': "paf_upload/categoriedDefaultData/" + $('#fs_company_info_id').val(),
                'url': contentAllData_url + "/" + $('#master_id').val(),
                'dataType': 'json'
                // 'data': function (node) {
                //     console.log(node);
                //     return { 'id': node.id, 'parent': node.parent, 'type': node.type };
                // }
            },
            'check_callback': function (operation, node, node_parent, node_position, more) {
                if (operation === "move_node") {
                    if (node_parent.parents.length >= 4 || node.data.Type === "level_4") {
                        return false;
                    }
                    else
                    {
                        // console.log(node.parents.length);
                    }
                }
                return true;  //allow all other operations
            }
        },
        "types" : {
            "level_1" : {
                "icon" : false,
                "a_attr" : { "style" : "font-weight:bold;font-style:italic;"}
            },
            "level_2" : {
                "icon" : false
            },
            "level_3" : {
                "icon" : false
            },
            "level_4" : {
                "icon" : false,
                "a_attr" : { "style" : "color:#154069 !important" }
            }
        },

        "plugins": [
            "themes",
            "types",
            "contextmenu",
            "dnd"
        ],


        'contextmenu' : {
            'items' : customMenu
        }
    })
    .on('move_node.jstree', function (e, data) {
        level = data.node.parents.length;
        // console.log(data.node.parents.length);
        var tree = $(this).jstree(true);

        if(level == 1)
        {
            tree.set_type(data.node.id, 'level_1');
        }
        else if(level == 2)
        {
            tree.set_type(data.node.id, 'level_2');
        }
        else if(level == 3)
        {
            tree.set_type(data.node.id, 'level_3');
        }
        else if(level == 4)
        {
            tree.set_type(data.node.id, 'level_4');
        }
        
    })
    .bind("loaded.jstree", function (event, data) {
        $(this).jstree("open_all");
    });
});

function add_index()
{
     $("#programme_content_tree").jstree("create_node", "#", {type: "level_1" ,data:{"id":""}}, "last", function (node) {
        this.edit(node);
    });
}

function customMenu(node)
{
    // console.log(node.type);

    var tree = $("#programme_content_tree").jstree(true);
    var items = {
        createLevel2 : {
            "separator_before": false,
            "separator_after": false,
            "label": "Add child",
            action: function (obj) {
                node = tree.create_node(node, { text: 'New Sub Index', type: 'level_2',data:{"id":""} });
                tree.edit(node);
                tree.deselect_all();
                tree.select_node(node);
            }
        },
        createLevel3 : {
            "separator_before": false,
            "separator_after": false,
            "label": "Add child",
            action: function (obj) {
                node = tree.create_node(node, { text: 'New Sub Index', type: 'level_3',data:{"id":""} });
                tree.edit(node);
                tree.deselect_all();
                tree.select_node(node);
            }
        },
        createLevel4 : {
            "separator_before": false,
            "separator_after": false,
            "label": "Add child",
            action: function (obj) {
                node = tree.create_node(node, { text: 'New Sub Index', type: 'level_4',data:{"id":""} });
                tree.edit(node);
                tree.deselect_all();
                tree.select_node(node);
            }
        },
        renameItem : {
            "separator_before": false,
            "separator_after": false,
            "label": "Edit",
            action: function (obj) {
                tree.edit(node);                                    
            }
        },
        removeItem : {
            "separator_before": false,
            "separator_after": false,
            "label": "Remove",
            action: function (obj) {
                tree.delete_node(node);
            }
        }
        

    }

    if (node.type === 'level_1') {
        delete items.createLevel3;
        delete items.createLevel4;
    }
    else if (node.type === 'level_2') {
        delete items.createLevel2;
        delete items.createLevel4;
    }
    else if (node.type === 'level_3') {
        delete items.createLevel2;
        delete items.createLevel3;
    }
    else if (node.type === 'level_4') {
        delete items.createLevel2;
        delete items.createLevel3;
        delete items.createLevel4;

    }

    return items;
}




