$(document).ready(function() {
    $('.datatable-programme').DataTable({
            
        "order": [[ 0, 'asc' ]]
    });

    $('.datatable-archived-programme').DataTable({
        
        "order": [[ 0, 'asc' ]]
    });

    $('#addprogramme_popup .select2').select2();


});

$('.create_audit_programme').click(function() {
    $("#addprogramme_popup").modal("show");

});


function archive_programme(element){
	var div 		= $(element).parent();
	var programme_id 	= div.find('.programme_id').val();
	//console.log(holiday_id);
	bootbox.confirm({
        message: "Do you want to archive this selected info? Cannot revert after archive.",
        closeButton: false,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn_blue'
            },
            cancel: {
                label: 'No',
                className: 'btn_cancel'
            }
        },
        callback: function (result) {
        	//console.log(result);
        	if(result == true)
        	{
        		$.post("setting/archive_programme", { 'programme_id': programme_id }, function(data, status){
		    	 	if(data){
		    	 		window.location.href = setting_url;
		    	 	}
		    	});
        	}
        }
    })
}


$(document).on('click',"#addProgramme",function(e)
{
    // $("#addline_popup").modal("hide");
    // $("#loadingMessage").show();
    var selected_programme_type = $('.programme_type').val();
    console.log(selected_programme_type);
    if(selected_programme_type == "1")
    {
        window.location.href = audit_programme_url;
        // console.log("hiiiiiii")
    }
    else if(selected_programme_type == "2")
    {
        window.location.href = audit_programme_yn_url;
    }
    else if(selected_programme_type == "3")
    {
        window.location.href = audit_programme_qa_url;
    }
    else if(selected_programme_type == "4" || selected_programme_type == "5"|| selected_programme_type == "6"|| selected_programme_type == "7"|| selected_programme_type == "8" || selected_programme_type == "9")
    {
        window.location.href = audit_programme_only_master_url+"/"+selected_programme_type;
    }
    
    // $("#adjustment_form").submit();
    // $("#adjustment_form").validate();
});
// console.log(setting_load_default_mapping);
/* ----- Mapping Default list ----- */
$('#mapping_default_Treeview').jstree({
    "core" : {
        "check_callback" : true,
        "expand_selected_onload": true,
        "animation" : 0,
        "themes": { "icons": false },
        'data': {
            'url': setting_load_default_mapping,
            'dataType': 'json',
            'data': function (node) {
            //     return { 'id': node.id, 'parent': node.parent, 'type': node.type };
            }
        }
    },
    "grid": {
        columns: [{
            'minWidth': 250,
            'header': 'Account name'
        },
        {
            'cellClass': "acenter",
            'minWidth': 100,
            'header': 'Reference ID',
            'value': 'account_code'
        },
        {
            'minWidth': 300,
            'header': 'Description',
            'value': 'description'
        }],
        resizable:true,
        // draggable:true,
        contextmenu:true,
        gridcontextmenu: function (grid,tree,node,val,col,t,target)
        {
            var this_obj = t.get_node(node);
            
            return {
                "edit": {
                    label: "Edit",
                    icon: "glyphicon glyphicon-pencil",
                    "action": function (data) {

                        var obj = t.get_node(node);

                        var data_ref_id = data.reference.id;
                        var id_length = data_ref_id.length;

                        var col_num = data_ref_id.substring(id_length - 1, id_length);

                        grid._edit(obj,col,target);
                    }
                }
            }
        }
    },
    "dnd" : {
        "is_draggable" : function(node) 
        {
            if(node[0]['parent'] == "#")
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    },
    "types" : {
        "#" : {
          "max_depth": 5,
          "valid_children" : ["Branch"]
        },
        "Branch": {
            "valid_children": ["Branch", "Leaf", "UncategoriedLeaf", "Round_off"],
            "a_attr": { "style": "color:green !important" }
        },
    },
    "contextmenu": {
        items: function customMenu(node) {
            var ref = $('#mapping_default_Treeview').jstree(true);
            // The default set of all items
            var items = {
                createItem: { // The "create" menu item
                    label: "Create",
                    action: function () {
                        var sel = ref.get_selected();
                        if (!sel.length) { return false; }
                        sel = sel[0];
                        sel = ref.create_node(sel, { "text": "New Category", "type": "Branch", 'data': { 'account_code':'', 'description':'New Category', 'value':'', 'fs_default_acc_category_id':'', 'id':'' }});

                        if (sel) {
                            ref.edit(sel);
                        }
                    }
                },
                EditItem: { // The "rename" menu item
                    label: "Edit",
                    action: function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference);
                        inst.edit(obj);
                    }
                },
                deleteItem: { // The "delete" menu item
                    label: "Delete",
                    action: function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference);

                        var children_id = $("#mapping_default_Treeview").jstree(true).get_node(obj.id).children;

                        // delete node from categorized tree
                        if (inst.is_selected(obj)) {
                            inst.delete_node(inst.get_selected());
                        }
                        else {
                            inst.delete_node(obj);
                        }
                    }
                }
            };

            // remove create selection from menu from level 5
            if (ref.get_selected(node)[0].parents.length > 4) {
                delete items.createItem;
            }

            return items;
        }
    },
    "plugins" : [
        "contextmenu", "grid", "dnd", "state", "types"
    ]
})
.bind("loaded.jstree", function (event, data) {
    $(this).jstree("open_all");
});

function rearrange_cu_accounts_arr(tree)
{
  var output = [];

  for (var i = 0; i < tree.length; i++) 
  {
    var temp = {
                'id':     tree[i]['id'],
                'parent': tree[i]['parent'],
                'type':   tree[i]['type'],
                'text':   tree[i]['text'],
                'data':   tree[i]['data']
              };

    output.push(temp);
  }

  return output;
}

// save fs_default_acc_category
function save_fs_default_acc_list()
{
    $('#loadingMessage').show();

    var v = $('#mapping_default_Treeview').jstree(true).get_json('#', { flat: true });
    var default_acc_category_tree = JSON.parse(JSON.stringify(v));

    default_acc_category_tree = rearrange_cu_accounts_arr(default_acc_category_tree);

    $.ajax({
        type: 'post',
        url: save_fs_default_acc_list_url,
        dataType: 'json',
        data: { default_acc_category_tree: default_acc_category_tree },
        success: function (response) 
        {
            alert(response.message);

            if(response.result)
            {
                $('#Categoried_Treeview').jstree(true).refresh();
                $('#Uncategoried_Treeview').jstree(true).refresh();
            }

            $('#loadingMessage').hide();
        }
    });
}
/* ----- END OF Mapping Default list ----- */
