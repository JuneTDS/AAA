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

    $('.select2').select2();

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
                }
            });
            // return true if username is exist in database
            return result; 
        }, 
        "This index already exist! Try another."
    );



 });

//get assertion dropdown based on programme type
// $(document).on('change',".programme_type",function(){
    
//     var selected_programme_type = $('.programme_type').val();
//     var cn = new Client();
//     cn.getAssertionDropdown(selected_programme_type);


    

// });

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
           data: '&index=' + selected_index + '&programme_type=' + 2,
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

                    $('#programme_content_tree').jstree('destroy');
                    $('#programme_content_tree').jstree({
                        "core": {
                            'data': {
                                // 'url': "paf_upload/categoriedDefaultData/" + $('#fs_company_info_id').val(),
                                'url': contentAllData_yn_url + "/" + $('#master_id').val(),
                                'dataType': 'json'
                                // 'data': function (node) {
                                //     console.log(node);
                                //     return { 'id': node.id, 'parent': node.parent, 'type': node.type };
                                // }
                            },
                            'check_callback': function (operation, node, node_parent, node_position, more) {
                                if (operation === "move_node") {
                                    if (node_parent.parents.length >= 3 || node.data.Type === "level_3") {
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
                            }
                            // "level_4" : {
                            //     "icon" : false,
                            //     "a_attr" : { "style" : "color:#154069 !important" }
                            // }
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
                        // else if(level == 4)
                        // {
                        //     tree.set_type(data.node.id, 'level_4');
                        // }
                        
                    })
                    .bind("loaded.jstree", function (event, data) {
                        $(this).jstree("open_all");
                    });
                }


                $('#loadingmessage').hide();
           }
       });

    

});



$(document).on('click',"#save_programme_content",function() {

    
    submit_all_form();


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
            url: save_all_yn_programme_setting_url,
            data: $("#audit_programme_info_form").serialize() + '&' + $.param({ 'programme_content_tree': programme_content_tree }),
            dataType: 'json',
            success: function(response){

                // }
                if(response.status == "success")
                {
                    $("#loadingmessage").hide();
                    
                    window.location.href = base_url+'/audit/setting/add_audit_programme_yn/'+response.master_id;

                    
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


$(document).ready(function() {

    $('#programme_content_tree').jstree({
        "core": {
            'data': {
                // 'url': "paf_upload/categoriedDefaultData/" + $('#fs_company_info_id').val(),
                'url': contentAllData_yn_url + "/" + $('#master_id').val(),
                'dataType': 'json'
                // 'data': function (node) {
                //     console.log(node);
                //     return { 'id': node.id, 'parent': node.parent, 'type': node.type };
                // }
            },
            'check_callback': function (operation, node, node_parent, node_position, more) {
                if (operation === "move_node") {
                    if (node_parent.parents.length >= 3 || node.data.Type === "level_3") {
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
            }
            // "level_4" : {
            //     "icon" : false,
            //     "a_attr" : { "style" : "color:#154069 !important" }
            // }
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
        // else if(level == 4)
        // {
        //     tree.set_type(data.node.id, 'level_4');
        // }
        
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
        delete items.createLevel4;
    }
    // else if (node.type === 'level_4') {
    //     delete items.createLevel2;
    //     delete items.createLevel3;
    //     delete items.createLevel4;

    // }

    return items;
}




