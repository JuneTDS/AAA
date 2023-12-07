$(".print_icon").hide();
$(".access_icon").hide();

$(".delete_icon").addClass("disable");
document.getElementById("delete_icon").src = base_url + "img/delete-disable.png";

$(".addline_icon").addClass("disable");
document.getElementById("addline_icon").src = base_url + "img/add_line-disable.png";

$("[data-toggle=tooltip]").tooltip();

$(".orientation_icon").addClass("disable");
document.getElementById("orientation_icon").src = base_url + "img/orientation-disable.png";

$(".adjustment_icon").addClass("disable");
document.getElementById("adjustment_icon").src = base_url + "img/adjustment-disable.png";

$(".rp_icon").addClass("disable");
document.getElementById("rp_icon").src = base_url + "img/review-disable.png";

$(".crossref_icon").addClass("disable");
document.getElementById("crossref_icon").src = base_url + "img/cross-reff-disable.png";

$(".osm_icon").addClass("disable");
document.getElementById("osm_icon").src = base_url + "img/osm-disable.png";

$(".textbox_icon").addClass("disable");
document.getElementById("textbox_icon").src = base_url + "img/textbox-disable.png";

$(".export_icon").addClass("disable");
document.getElementById("export_icon").src = base_url + "img/export-disable.png";

/* Drag Bar */
var handler = document.querySelector('.handler');
var wrapper = handler.closest('.wrapper');
var boxA = wrapper.querySelector('.box');
var isHandlerDragging = false;

document.addEventListener('mousedown', function (e) {
    // If mousedown event is fired from .handler, toggle flag to true
    if (e.target === handler) {
        isHandlerDragging = true;
    }
});

document.addEventListener('mousemove', function (e) {
    // Don't do anything if dragging flag is false
    if (!isHandlerDragging) {
        return false;
    }

    // Get offset
    var containerOffsetLeft = wrapper.offsetLeft;

    // Get x-coordinate of pointer relative to container
    var pointerRelativeXpos = e.clientX - containerOffsetLeft;

    // Resize box A
    // * 8px is the left/right spacing between .handler and its inner pseudo-element
    // * Set flex-grow to 0 to prevent it from growing
    boxA.style.width = (pointerRelativeXpos - 8) + 'px';
    boxA.style.flexGrow = 0;
});

document.addEventListener('mouseup', function (e) {
    // Turn off dragging flag when user mouse is up
    isHandlerDragging = false;
});
/* End of Drag Bar */

function numberWithCommas(x) 
{
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function negative_bracket_to_number(number)
{
  var ori_num = number;

  if(number == '')
  {
    number = 0.00;
  }
  else
  {
    number = number.replaceAll(',', '');
    number = number.replace('(', '-');
    number = number.replace(')', '');

    if(isNaN(parseFloat(number)) || number == '-')
    {
      number = 0.00;
    }
  }

  return parseFloat(number);
}

function show_trial_b_info()
{
    $('#trial_b_error_msg_modal').modal("show");
}

 $('.upload_TB').on('change', function() {

    var upload_button = this;
    bootbox.confirm({
        message: 'Unsaved changes will be lost after upload. Please save before proceed. Continue?',
        buttons: {
            confirm:
            {
                label: 'Yes',
                className: 'btn-primary'
            },
            cancel: {
                label: 'No'
            }
        },
        callback: function(result)
        {
            if (result) 
            {
                var filename = readURL(upload_button, 1);
                $(upload_button).parent().children('span').html(filename);
            }
        }
    });

    // var filename = readURL(this, 1);
    // $(this).parent().children('span').html(filename);
});

 $(".upload_LY_TB").change(function() 
{
    var upload_button = this;
    bootbox.confirm({
        message: 'Unsaved changes will be lost after upload. Please save before proceed. Continue?',
        buttons: {
            confirm:
            {
                label: 'Yes',
                className: 'btn-primary'
            },
            cancel: {
                label: 'No'
            }
        },
        callback: function(result)
        {
            if (result) 
            {
                var filename = readURL(upload_button, 0);
                $(upload_button).parent().children('span').html(filename);
            }
        }
    });
    
});




// Read File and return value  
function readURL(input, current_year_tb) 
{
    var url = input.value;
    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();

    if (input.files && input.files[0] && (ext == "xlsx" || ext == "xls" || ext == "CSV")) 
    {
        var path = $(input).val();
        var filename = path.replace(/^.*\\/, "");

        // console.log(input.files[0]);
        var data = new FormData();
        data.append('excel_file', input.files[0]);
        data.append('assignment_id', $("#assignment_id").val());
        data.append('current_year_tb', current_year_tb);

        $.ajax({
            url: read_extract_excel_url,
            type: 'POST',
            processData: false, // important
            contentType: false, // important
            dataType : 'json',
            data: data,
            success: function (response, data) 
            {
                // console.log(response);

                alert(response.message);
                // $('#trial_b_error_msg_modal .modal-body').html(response.message);
                // $('#trial_b_error_msg_modal').modal("show");

                if(!response.status)
                {
                    $(".upload_TB").parent().children('span').html("Upload Trial Balance");
                }
                else
                {
                    // $('#Categoried_Treeview').jstree(true).refresh();
                    // $('#Uncategoried_Treeview').jstree(true).refresh();
                    location.reload();
                }
            },
            error: function (error)
            {
                console.log(error);
                alert("Error. Something went wrong. Please try again later.");

                $(".upload_TB").val("");
                $(".upload_TB").parent().children('span').html("Upload Trial Balance");
            }
        })
        return "Uploaded file : " + filename;
        // console.log(url);
    } else {
        $(input).val("");
        return "Only excel format are allowed!";
    }
}

$("input#uncategorized_account_search").keyup(function (e) {

    var tree = $("#Uncategoried_Treeview").jstree();
    tree.search($(this).val());
});


$('#Uncategoried_Treeview').jstree({
    "core": {
        "animation": 0,
        'check_callback': true,
        "themes": { "icons": false },
        'data': {
            //'url': "/AccountCategory/UncategoriedData?AccountId=" + AccountId,
            'url': uncategoriedData_url + "/" + $('#assignment_id').val(),
            'dataType': 'json'
            // 'data': function (node) {
            //     console.log(node);
            //     return { 'id': node.id, 'parent': node.parent, 'type': node.type };
            // }
        }
    },
    "grid": {
        "columns": [{
            'minWidth': 200,
            'header': 'Description'
        },
        {
            'minWidth': 130,
            'contentAlignment': 'right',
            'header': 'Current Year Value',
            'value': 'value'
        },
        {
            'minWidth': 130,
            'contentAlignment': 'right',
            'header': 'Last Year Value',
            'value': 'company_end_prev_ye_value'
        }],
        resizable:true,
        // draggable:true,
        contextmenu:true,
        gridcontextmenu: function (grid,tree,node,val,col,t,target)
        {
            return {
                "edit": {
                    label: "Change value",
                    icon: "glyphicon glyphicon-pencil",
                    "action": function (data) {
                        // console.log(data);
                        var obj = t.get_node(node);
                        grid._edit(obj,col,target);
                    }
                }
            }
            
        }
    },
    "types": {
        "#": {
            "valid_children": ["Leaf", "UncategoriedLeaf", "Round_off"]
        },
        "Leaf": {
            "valid_children": [],
            "hover_node": true
        },
        "UncategoriedLeaf": {
            "valid_children": []
        },
        "Round_off": {
            "valid_children": [],
            "a_attr": { "style": "color:#00008B !important; font-weight:bold;" }
        },
        "default": {
            "valid_children": ["UncategoriedLeaf", "Leaf", "Round_off"]
        }
    },
    "contextmenu": {
        items: function customMenu(node) {
            var ref = $('#Categoried_Treeview').jstree(true);
            // The default set of all items
            var items = {
                renameItem: { // The "rename" menu item
                    label: "Rename",
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

                        var children_id = $("#Categoried_Treeview").jstree(true).get_node(obj.id).children;

                        // create node to uncategorized 
                        // for(i = 0; i < children_id.length; i++)
                        // {
                        //     $('#Uncategoried_Treeview').jstree().create_node('#', $("#Categoried_Treeview").jstree(true).get_node(children_id[i]), "first", function(){});
                        // }

                        // delete node from uncategorized tree
                        if (inst.is_selected(obj)) {
                            inst.delete_node(inst.get_selected());
                        }
                        else {
                            inst.delete_node(obj);
                        }
                    }
                }
            };

            // remove context menu selection
            if (node.type == "Round_off") {
                delete items.renameItem;
                delete items.deleteItem;
            }

            return items;
        }
    },
    "plugins": [
        "contextmenu", "grid", "dnd", "state", "types", "themes","json","search", "wholerow"
    ]
})
.on('copy_node.jstree', function (e, data) {
    $('#Uncategoried_Treeview').jstree(true).set_id(data.node, data.original.id);
})
.on('update_cell.jstree-grid',function (e,data) {   // replace (-) sign and insert neagtive brackets when value is changed.

    // for this year value
    var value_this_year = data.node.data.value;

    if (value_this_year.includes("-") || value_this_year.includes("(") || value_this_year.includes(")"))
    {
        value_this_year = value_this_year.replace(/,/g, "");
        value_this_year = value_this_year.replace(/-/g, "");      // replace (-) sign
        value_this_year = value_this_year.replace(/[()]/g, "");   // replace "()" brackets
        value_this_year = parseFloat(value_this_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_this_year = numberWithCommas(value_this_year);      // thousand separator
        value_this_year = '(' + value_this_year.toString() + ')'; // add negative brackets
    }
    else
    {
        value_this_year = value_this_year.replace(/,/g, "");
        value_this_year = parseFloat(value_this_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_this_year = numberWithCommas(value_this_year);      // thousand separator
        value_this_year = value_this_year.toString();             // add negative brackets
    }

    data.node.data.value = value_this_year;   // update this year value

    // for this year value
    var value_last_year = data.node.data.company_end_prev_ye_value;

    if (value_last_year.includes("-") || value_last_year.includes("(") || value_last_year.includes(")"))
    {
        value_last_year = value_last_year.replace(/,/g, "");
        value_last_year = value_last_year.replace(/-/g, "");
        value_last_year = value_last_year.replace(/[()]/g, "");
        value_last_year = parseFloat(value_last_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_last_year = numberWithCommas(value_last_year);      // thousand separator 
        value_last_year = '(' + value_last_year.toString() + ')'; // add negative brackets
    }
    else
    {
        value_last_year = value_last_year.replace(/,/g, "");
        value_last_year = parseFloat(value_last_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_last_year = numberWithCommas(value_last_year);      // thousand separator 
        value_last_year = value_last_year.toString();             // add negative brackets
    }

    data.node.data.company_end_prev_ye_value = value_last_year;   // update last year value
});

$("input#categorized_account_search").keyup(function (e) {
    // console.log($(this).val());
    var tree = $("#Categoried_Treeview").jstree();
    tree.search($(this).val());

    // var searchResult = tree.search($(this).val());
    // $(searchResult).find('.jstree-search').focus();

    // $('#Categoried_Treeview').jstree(true).show_all();
    // $('#Categoried_Treeview').jstree('search', $(this).val());


});


$("#Categoried_Treeview")
.jstree({
    "core": {
        'check_callback': function (operation, node, node_parent, node_position, more) {
            if (operation === "move_node") {
                if (node_parent.parents.length >= 5 && node.data.Type === "Branch") {
                    return false;
                }
                
                if(node_parent['id'] == "#")
                {
                    return false;
                }
            }
            else if(operation === "copy_node")
            {

                if(!allow_ly_auto_calculation() && !(node.data.company_end_prev_ye_value == '0.00'))
                {
                    if(!confirm('Custom input parent node is activated. Are you sure to remove "Last year" value for current node?'))
                    {
                        return false;
                    }
                    else
                    {
                        node.data.company_end_prev_ye_value = '0.00';
                    }
                }
                
            }

            return true;  //allow all other operations
        },
        "expand_selected_onload": true,
        "animation": 0,
        "themes": { "icons": false },
        'data': {
            'url': categoriedDefaultData_url + "/" + $('#assignment_id').val(),
            'dataType': 'json',
            'data': function (node) {
                // console.log(node);
                // return { 'id': node.id, 'parent': node.parent, 'type': node.type };
            }
        }
    },
    "grid": {
        columns: [{
            'minWidth': 250,
            'header': 'Category & Description'
        },
        {
            'cellClass': "acenter",
            'minWidth': 100,
            'header': 'Reference ID',
            'value': 'account_code'
        },
        {
            'minWidth': 100,
            'header': 'Current Year Value',
            'value': 'value'
        },
        {
            'minWidth': 100,
            'header': 'Last Year Value',
            'value': 'company_end_prev_ye_value'
        }],
        resizable:true,
        // draggable:true,
        contextmenu:true,
        gridcontextmenu: function (grid,tree,node,val,col,t,target)
        {
            var this_obj = t.get_node(node);

            // exclude column with name of "Account Code", else others can have change value menu to edit value. 
            if(col['header'] != "Reference ID")
            {
                if(this_obj.type == "Round_off")
                {
                    return {};
                }
                else
                {
                    return {
                        "edit": {
                            label: "Change value",
                            icon: "glyphicon glyphicon-pencil",
                            "action": function (data) {

                                var obj = t.get_node(node);

                                if(obj.data.value == '') // to avoid value become NaN
                                {
                                   obj.data.value = '0'; 
                                }

                                var data_ref_id = data.reference.id;
                                var id_length = data_ref_id.length;

                                var col_num = data_ref_id.substring(id_length - 1, id_length);

                                if(col_num == '3') // for column "Last Year Value"
                                {
                                    clear_ly_child_val(obj.type);
                                }

                                grid._edit(obj,col,target);
                            }
                        }
                    }
                }
            }
            else if(col['header'] == "Reference ID" && this_obj.type != "Leaf" && this_obj.parent != "#")
            {
                return {
                    "edit": {
                        label: "Change Reference ID",
                        icon: "glyphicon glyphicon-pencil",
                        "action": function (data) {

                            var obj = t.get_node(node);

                            // grid._edit(obj,col,target);
                            grid_function = grid;
                            grid_obj    = obj;      // set global value
                            grid_col    = col;      // set global value
                            grid_target = target;   // set global value

                            // console.log(grid);
                            // console.log(target);
                            // console.log(obj);

                            load_sub_account_list_without_input_desc_name(obj);
                            $("#edit_account_code_list").modal("show"); 
                        }
                    }
                }
            }
            else
            {
                return {};
            }
        },
    },
    "dnd" : {
        "is_draggable" : function(node) 
        {
            // if(main_account_code_list.includes(node[0]['data']['account_code']))
            // {
            //     return false;
            // }
    
            if(node[0]['parent'] == "#")
            {
                return false;
            }
            else
            {
                return true;
            }
            // return true;
            
        }
    },
    "types": {
        "#": {
            "valid_children": ["Branch", "Leaf", "UncategoriedLeaf", "Round_off"],
            "max_depth": 6
        },
        "Branch": {
            "valid_children": ["Branch", "Leaf", "UncategoriedLeaf", "Round_off"],
            "a_attr": { "style": "color:green !important" }
        },
        "Leaf": {
            "valid_children": [],
            "hover_node": true
        },
        "UncategoriedLeaf": {
            "valid_children": []
        },
        "Round_off": {
            "valid_children": [],
            "a_attr": { "style": "color:#00008B !important; font-weight:bold;" }
        },
        "default": {
            "valid_children": ["Leaf", "UncategoriedLeaf", "Round_off"]
        }
    },
    "contextmenu": {
        items: function customMenu(node) {
            var ref = $('#Categoried_Treeview').jstree(true);
            // The default set of all items
            var items = {
                createItem: { // The "create" menu item
                    label: "Create",
                    action: function () {
                        // console.log(ref.get_selected());
                        // console.log($("#Categoried_Treeview").jstree(true).get_selected());
                        load_sub_account_list();
                        $("#sub_account_list").modal("show"); 

                        // var sel = ref.get_selected();
                        // if (!sel.length) { return false; }
                        // sel = sel[0];
                        // sel = ref.create_node(sel, { "text": "New Category", "type": "Branch", 'data': { 'Type': 'Branch' } });

                        // if (sel) {
                        //     ref.edit(sel);
                        // }
                    }
                },
                renameItem: { // The "rename" menu item
                    label: "Rename",
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

                        var children_id = $("#Categoried_Treeview").jstree(true).get_node(obj.id).children;

                        // create node to uncategorized 
                        for(i = 0; i < children_id.length; i++)
                        {
                            $('#Uncategoried_Treeview').jstree().create_node('#', $("#Categoried_Treeview").jstree(true).get_node(children_id[i]), "first", function(){});
                        }

                        // delete node from categorized tree
                        if (inst.is_selected(obj)) {
                            inst.delete_node(inst.get_selected());
                        }
                        else {
                            inst.delete_node(obj);
                        }
                    }
                },
                sortItem: {
                    label: "Sort",
                    action: function (data) {
                        var inst = $.jstree.reference(data.reference),
                            obj = inst.get_node(data.reference);

                        var children_id = $("#Categoried_Treeview").jstree(true).get_node(obj).children;
                        children_id.sort(function(a, b){
                            var nodeA = $('#Categoried_Treeview').jstree(true).get_node(a);
                            var nodeB = $('#Categoried_Treeview').jstree(true).get_node(b);
                            var lengthA = nodeA.children.length;
                            var lengthB = nodeB.children.length;                
                            if ((lengthA == 0 && lengthB == 0) || (lengthA > 0 && lengthB > 0))
                                return $('#Categoried_Treeview').jstree(true).get_text(a).toLowerCase() > $('#Categoried_Treeview').jstree(true).get_text(b).toLowerCase() ? 1 : -1;
                            else
                                return lengthA > lengthB ? -1 : 1;
                        });
                        for (var i = 0; i < children_id.length; i++) {
                            // console.log($('#Categoried_Treeview').jstree(true).get_node(children_id[i]));
                            var child = "#"+children_id[i]+"_anchor";
                            var parent = "#"+obj.id+"_anchor";
                            // console.log(child+"-------------"+parent);
                            // console.log(i);

                            $('#Categoried_Treeview').jstree("move_node", child, parent, "last");
                        }

                         // $('#Categoried_Treeview').jstree("move_node", child, parent, 1);

                    }
                }
            };

            // remove context menu selection
            if (node.type == "Leaf" || node.type == "Uncategoried") {
                delete items.createItem;
                // delete items.renameItem;
                delete items.deleteItem;
                delete items.sortItem;
            }
            else if(node.type == "Round_off")
            {
                delete items.createItem;
                delete items.renameItem;
                delete items.deleteItem;
                delete items.sortItem;
            }

            // remove create selection from menu from level 5
            if (ref.get_selected(node)[0].parents.length > 4) {
                delete items.createItem;
            }

            // remove delete selection from menu only
            if(node.parent == "#" || node.data.account_code == "C101" || node.data.account_code == "C102")
            {
                delete items.deleteItem;
            }

            return items;
        }
    },
    "search": {
        'case_insensitive': true,
        'show_only_matches' : true,
        "show_only_matches_children" : true
    },
    "plugins": [
        "contextmenu", "grid", "dnd", "state", "types", "themes","json","search"
    ]
})
.on('copy_node.jstree', function (e, data) {
    $('#Categoried_Treeview').jstree(true).set_id(data.node, data.original.id);
})
.bind("loaded.jstree", function (event, data) {
    $(this).jstree("open_all");
})
.on('update_cell.jstree-grid',function (e,data) {   // replace (-) sign and insert neagtive brackets when value is changed.

    // for this year value
    var value_this_year = data.node.data.value;

    if (value_this_year.includes("-") || value_this_year.includes("(") || value_this_year.includes(")"))
    {
        value_this_year = value_this_year.replace(/,/g, "");
        value_this_year = value_this_year.replace(/-/g, "");      // replace (-) sign
        value_this_year = value_this_year.replace(/[()]/g, "");   // replace "()" brackets
        value_this_year = parseFloat(value_this_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_this_year = numberWithCommas(value_this_year);      // thousand separator 
        value_this_year = '(' + value_this_year.toString() + ')'; // add negative brackets
    }
    else
    {
        value_this_year = value_this_year.replace(/,/g, "");
        value_this_year = parseFloat(value_this_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_this_year = numberWithCommas(value_this_year);      // thousand separator
        value_this_year = value_this_year.toString();             // add negative brackets
    }

    data.node.data.value = value_this_year;   // update this year value

    // for this year value
    var value_last_year = data.node.data.company_end_prev_ye_value;

    if (value_last_year.includes("-") || value_last_year.includes("(") || value_last_year.includes(")"))
    {
        value_last_year = value_last_year.replace(/,/g, "");
        value_last_year = value_last_year.replace(/-/g, "");
        value_last_year = value_last_year.replace(/[()]/g, "");
        value_last_year = parseFloat(value_last_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_last_year = numberWithCommas(value_last_year);      // thousand separator 
        value_last_year = '(' + value_last_year.toString() + ')'; // add negative brackets
    }
    else
    {
        value_last_year = value_last_year.replace(/,/g, "");
        value_last_year = parseFloat(value_last_year).toFixed(2); // convert to float and set decimal with minimum 2 decimals
        value_last_year = numberWithCommas(value_last_year);      // thousand separator
        value_last_year = value_last_year.toString();             // add negative brackets
    }

    data.node.data.company_end_prev_ye_value = value_last_year;   // update last year value
});

$('#CreateAccount').click(function () {
    $("#create_account_form").modal("show"); 
    $("#create_account_form #uncategorised_new_account").val("");

    // $('#Categoried_Treeview').jstree("create_node", null, {
    //     "text": "New Category", "type": "Branch", "data": { "Type": "#" }
    // }, "first", function (node) {
    //     this.edit(node);
    // });
});

function create_uncategorized_account()
{
    // var main_account_code = $("#tbl_main_account_list tr.selected td:first").html();
    // var description  = $("#tbl_main_account_list tr.selected td:last").html();

    var ref = $("#Uncategoried_Treeview").jstree(true);

    var new_account = $('#uncategorised_new_account').val();

    // console.log(new_account);
    // if(main_account_code) 
    // {
        $('#Uncategoried_Treeview').jstree("create_node", null, {
            "id": '', "parent": '#', "text": new_account, "type": "Leaf", "data": { "value": '0.00', "company_end_prev_ye_value": '0.00' }
        });

        // ref.deselect_all();
        // ref.select_node(main_account_code);
    // }

    $("#create_account_form").modal("hide");
    $("#Categoried_Treeview").jstree(true).sort();
    $("#Categoried_Treeview").jstree(true).redraw_node();
};


function load_sub_account_list()
{
    var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
    var CategoriedTree = JSON.parse(JSON.stringify(v));

    $.ajax({ //Upload common input
        url: partial_sub_account_list_url,
        type: "POST",
        data: {current_categorized_tree: CategoriedTree},
        dataType: 'html',
        success: function (response,data) {
            // console.log(response);
            $('#sub_account_list .modal-body').html(response);
        }
    });
}

function load_sub_account_list_without_input_desc_name(this_node)
{
    var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
    var CategoriedTree = JSON.parse(JSON.stringify(v));

    CategoriedTree = rearrange_cu_accounts_arr(CategoriedTree, 'current_categorized_tree');

    var selected_acc_code = this_node['data']['account_code'];

    $.ajax({ //Upload common input
        type: "post",
        url: partial_edit_account_code_list_url,
        dataType: 'html',
        data: {current_categorized_tree: CategoriedTree, selected_acc_code: selected_acc_code},
        success: function (response,data) 
        {
            $('#edit_account_code_list .modal-body').html(response);
        }
    });
}

var categoried_treeview_dynamic_temp_id = 1;

$('#sub_account_list #btn_insert_sub').on('click', function(e)
{
    var description  = $("#partial_sub_account_list #input_new_description").val();

    if(description)
    {
        var link_with_ref_id = $("#link_with_ref_id_val").val();
        var sub_account_code = '';
        var node_id = 'NS' + categoried_treeview_dynamic_temp_id;
        var fs_default_acc_category_id = '';

        if(link_with_ref_id !== '0')
        {
            node_id = $("#tbl_sub_account_list tr.selected td:first").html();
            sub_account_code = $("#tbl_sub_account_list tr.selected td:first").html();
            fs_default_acc_category_id = $("#tbl_sub_account_list tr.selected .fs_default_acc_category_id").val();
        }
        else
        {
            categoried_treeview_dynamic_temp_id++;
        }

        // console.log(node_id, description, sub_account_code, categoried_treeview_dynamic_temp_id);

        var ref = $("#Categoried_Treeview").jstree(true);
        var sel = $("#Categoried_Treeview").jstree(true).get_selected();

        if (!sel.length) { return false; }

        // if(sub_account_code) 
        // {
            sel = sel[0];
            // sel = ref.create_node(sel, { "id": sub_account_code, "text": description, "type": "Branch", 'data': { 'account_code': sub_account_code, 'Type': 'Branch' } });
            sel = ref.create_node(sel, { "id": node_id, "text": description, "type": "Branch", 'data': { 'id': '', 'account_code': sub_account_code, 'Type': 'Branch', 'fs_default_acc_category_id': fs_default_acc_category_id } });

            ref.deselect_all();
            ref.select_node(sub_account_code);
        // }

        $("#sub_account_list").modal("hide");
    }
    else
    {
        alert("Description cannot be empty!");
    }
});

$('#edit_account_code_list #btn_edit_sub').on('click', function(e)
{
    var edit_s_account_code = $("#tbl_edit_sub_account_list tr.selected .account_code").text();
    var edit_s_fs_default_acc_category_id = $("#tbl_edit_sub_account_list tr.selected .fs_default_acc_category_id").val();
    // console.log(edit_s_fs_default_acc_category_id);

    if(edit_s_fs_default_acc_category_id == undefined)
    {
        toastr.error("No selected account code.")
    }
    else
    {
        edit_account_code(edit_s_account_code,edit_s_fs_default_acc_category_id);
    }

    
});

function edit_account_code(account_code,fs_default_acc_category_id)
{
    // console.log(fs_default_acc_category_id);
    if(account_code == undefined)
    {
        grid_obj.data.account_code = '';
        grid_obj.data.fs_default_acc_category_id = '';
        $(grid_target).text('');
    }
    else
    {
        grid_obj.data.account_code = account_code;
        grid_obj.data.fs_default_acc_category_id = fs_default_acc_category_id;
        $(grid_target).text(account_code);
    }

    $("#edit_account_code_list").modal("hide");
}

$(document).on('click',".save_icon",function(e) 
{
    $('#loadingMessage').show();

    var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
    var CategoriedTree = JSON.parse(JSON.stringify(v));
    var temp_CategoriedTree = JSON.parse(JSON.stringify(v));

    CategoriedTree = rearrange_cu_accounts_arr(CategoriedTree, 'categorized');

    var u = $('#Uncategoried_Treeview').jstree(true).get_json('#', { flat: true });
    var UncategoriedTree = JSON.parse(JSON.stringify(u));

    UncategoriedTree = rearrange_cu_accounts_arr(UncategoriedTree, 'uncategorized');

    // console.log(CategoriedTree);
    // console.log(UncategoriedTree);

    $.ajax({
        type: 'post',
        url: check_previous_update_url,
        dataType: 'json',
        data: { assignment_id: $('#assignment_id').val(), InitialCategoriedTree : initial_categorized},
        success: function (response) 
        {
            // console.log(response)
            if(response.status == "overwrite")
            {
                $("#loadingMessage").hide();
                bootbox.confirm({
                    message: "Someone updated the mapping before you. Do you want to overwrite the record?",
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
                            

                            $("#loadingMessage").show();
                            $.ajax({
                                type: 'post',
                                url: save_categorized_uncategorized_account_url,
                                dataType: 'json',
                                data: { assignment_id: $('#assignment_id').val(), CategoriedTree: JSON.stringify(CategoriedTree), UncategoriedTree: JSON.stringify(UncategoriedTree) , InitialCategoriedTree : initial_categorized},
                                success: function (response) 
                                {
                                    alert(response.message);

                                    if(response.result)
                                    {
                                        // $('#Categoried_Treeview').jstree(true).settings.core.data = temp_CategoriedTree;

                                        /* Calculate parent after save */
                                        initial_categorized = response.categoried_tree;
                                        var CategoriedTree = JSON.parse(response.categoried_tree);

                                        var total = '';

                                        for (var i = 0; i < CategoriedTree.length; i++) 
                                        {
                                            if(CategoriedTree[i].parent == '#')
                                            {
                                                CategoriedTree = recursive_child(CategoriedTree, CategoriedTree[i]['id']);
                                                total = calculate_total_ids(CategoriedTree, CategoriedTree[i]['id']);

                                                CategoriedTree[i]['data']['value'] = total['total_c'];

                                                if(allow_ly_auto_calculation())
                                                {
                                                    CategoriedTree[i]['data']['company_end_prev_ye_value'] = total['total_c_lye'];
                                                }
                                            }
                                        }
                                        /* End calculate parent after save */

                                        $('#Categoried_Treeview').jstree(true).settings.core.data = CategoriedTree;
                                        $('#Categoried_Treeview').jstree(true).refresh();
                                        $('#Uncategoried_Treeview').jstree(true).refresh();

                                        $("input#categorized_account_search").val('');
                                        $("input#uncategorized_account_search").val('');
                                        $("input#categorized_account_search").keyup();
                                        
                                    }

                                    $('#loadingMessage').hide();
                                }
                            });
                        }
                        else
                        {
                            // location.reload();
                        }
                    }
                })
            }
            else
            {

                // console.log(CategoriedTree);
                // console.log(UncategoriedTree);

                $.ajax({
                    type: 'post',
                    url: save_categorized_uncategorized_account_url,
                    dataType: 'json',
                    data: { assignment_id: $('#assignment_id').val(), CategoriedTree: JSON.stringify(CategoriedTree), UncategoriedTree: JSON.stringify(UncategoriedTree) , InitialCategoriedTree : initial_categorized},
                    success: function (response) 
                    {
                        alert(response.message);

                        if(response.result)
                        {
                            // $('#Categoried_Treeview').jstree(true).settings.core.data = temp_CategoriedTree;

                            /* Calculate parent after save */
                            initial_categorized = response.categoried_tree;
                            var CategoriedTree = JSON.parse(response.categoried_tree);

                            var total = '';

                            for (var i = 0; i < CategoriedTree.length; i++) 
                            {
                                if(CategoriedTree[i].parent == '#')
                                {
                                    CategoriedTree = recursive_child(CategoriedTree, CategoriedTree[i]['id']);
                                    total = calculate_total_ids(CategoriedTree, CategoriedTree[i]['id']);

                                    CategoriedTree[i]['data']['value'] = total['total_c'];

                                    if(allow_ly_auto_calculation())
                                    {
                                        CategoriedTree[i]['data']['company_end_prev_ye_value'] = total['total_c_lye'];
                                    }
                                }
                            }
                            /* End calculate parent after save */

                            $('#Categoried_Treeview').jstree(true).settings.core.data = CategoriedTree;
                            $('#Categoried_Treeview').jstree(true).refresh();
                            $('#Uncategoried_Treeview').jstree(true).refresh();

                            $("input#categorized_account_search").val('');
                            $("input#uncategorized_account_search").val('');
                            $("input#categorized_account_search").keyup();
                            
                        }

                        $('#loadingMessage').hide();
                    }
                });
            }
            
        }
    });

    
});

function rearrange_cu_accounts_arr(tree, type)
{

  var output = [];

  for (var i = 0; i < tree.length; i++) 
  {
    // to exclude parent value save to db 
    if(type == 'categorized' && tree[i]['type'] == 'Branch')
    {
        // current year values
        tree[i]['data']['value'] = 0.00;

        if(allow_ly_auto_calculation()) // if last year child have values, exlcude parent values save to db
        {
            tree[i]['data']['company_end_prev_ye_value'] = 0.00;
        }
    }

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

function download_trial_b_template()
{ 
  $('#loadingMessage').show();
  $.ajax({
    url: get_trial_balance_template_excel_url,
    type: 'POST',
      processData: false, // important
      contentType: false, // important
      dataType : 'json',
      success: function (response) 
      {
        // console.log(response);

        for(var b = 0; b < response.link.length; b++) 
        {
          // console.log(response);
          
          // window.location.href = 'http://localhost/dot/pdf/invoice/INV - 1521254993.pdf';
          $('#loadingMessage').hide();
          window.open(
              response.link[b],
              '_blank' // <- This is what makes it open in a new window.
          );
        }
      }
  });
}

function allow_ly_auto_calculation()
{
    var result = false;
    var hasLeaf = false;
    var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
    var CategoriedTree = JSON.parse(JSON.stringify(v));

    for (var i = 0; i < CategoriedTree.length; i++) 
    {
        if(CategoriedTree[i].type == 'Leaf')
        {
            hasLeaf = true;
        }

        if(CategoriedTree[i].type == 'Leaf' && CategoriedTree[i]['data']['company_end_prev_ye_value'] != 0.00)
        {
            result = true;
        }
    }

    if(!hasLeaf) // to avoid "custom input clear LY value' message when no leaf
    {
        result = true;
    }

    return result;
}

function recursive_child(CategoriedTree, parent_id)
{
    var total = '';

    for (var i = 0; i < CategoriedTree.length; i++) 
    {
        if(CategoriedTree[i].parent == parent_id)
        {
            CategoriedTree = recursive_child(CategoriedTree, CategoriedTree[i]['id']);
            total = calculate_total_ids(CategoriedTree, CategoriedTree[i]['id']);

            if(CategoriedTree[i].type == "Branch")
            {
                CategoriedTree[i]['data']['value'] = total['total_c'];

                if(allow_ly_auto_calculation())
                {
                    CategoriedTree[i]['data']['company_end_prev_ye_value'] = total['total_c_lye'];
                }
            }
        }
    }

    return CategoriedTree;
}

function calculate_parents_val()
{
    $('#loadingMessage').show();
    setTimeout(function() {
      //your code to be executed after 1 second
        var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
        var CategoriedTree = JSON.parse(JSON.stringify(v));

        var total = '';

        for (var i = 0; i < CategoriedTree.length; i++) 
        {
            if(CategoriedTree[i].parent == '#')
            {
                CategoriedTree = recursive_child(CategoriedTree, CategoriedTree[i]['id']);
                total = calculate_total_ids(CategoriedTree, CategoriedTree[i]['id']);

                CategoriedTree[i]['data']['value'] = total['total_c'];

                if(allow_ly_auto_calculation())
                {
                    CategoriedTree[i]['data']['company_end_prev_ye_value'] = total['total_c_lye'];
                }
            }
        }

        $('#Categoried_Treeview').jstree(true).settings.core.data = CategoriedTree;
        $('#Categoried_Treeview').jstree(true).refresh();

         $('#loadingMessage').hide();

    }, 100);
    
}

function calculate_total_ids(CategoriedTree, parent_id)
{
    var total = {
                    total_c: 0.00,
                    total_c_lye: 0.00
                }

    var temp_CategoriedTree = CategoriedTree;
    var temp_account_id = [];

    temp_account_id.push(parent_id);

    do{
        for (var i = 0; i < CategoriedTree.length; i++) 
        {
            if(CategoriedTree[i].parent == temp_account_id[0])
            {
                if(CategoriedTree[i].type == 'Leaf')
                {
                    total['total_c'] += negative_bracket_to_number(CategoriedTree[i]['data']['value']);
                }

                if((allow_ly_auto_calculation() && CategoriedTree[i].type == 'Leaf') || (!allow_ly_auto_calculation() && CategoriedTree[i].type == 'Branch'))
                {
                    total['total_c_lye'] += negative_bracket_to_number(CategoriedTree[i]['data']['company_end_prev_ye_value']); 
                }

                temp_account_id.push(CategoriedTree[i].id);
            }
        }

        temp_account_id.splice(0, 1);
    }
    while(temp_account_id.length > 0)

    total['total_c']     = numberWithCommas(parseFloat(total['total_c'] ).toFixed(2));

    if (total['total_c'].includes("-"))
    {   
        total['total_c'] = total['total_c'].replace(/-/g, "");  
        total['total_c'] = '(' + total['total_c'].toString() + ')'; // add negative brackets
    }

    if(allow_ly_auto_calculation())
    {
        total['total_c_lye'] = numberWithCommas(parseFloat(total['total_c_lye'] ).toFixed(2));

        if (total['total_c_lye'].includes("-"))
        {
            total['total_c_lye'] = total['total_c_lye'].replace(/-/g, "");  
            total['total_c_lye'] = '(' + total['total_c_lye'].toString() + ')'; // add negative brackets
        }
    }

    return total;
}

function clear_ly_child_val(obj_type)
{
    var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
    var CategoriedTree = JSON.parse(JSON.stringify(v));

    var notEmptyChildVal = 0;
    var node_type = '';

    if(obj_type == 'Branch')
    {
        node_type = 'Leaf';
    }
    else if(obj_type == 'Leaf')
    {
        node_type = 'Branch';
    }

    for (var i = 0; i < CategoriedTree.length; i++) 
    {
        if(CategoriedTree[i]['type'] == node_type && CategoriedTree[i]['data']['company_end_prev_ye_value'] != 0.00)
        {
            notEmptyChildVal = 1;
        }
    }

    if(notEmptyChildVal == 1)
    {
        var msg = '';

        if(obj_type == 'Branch')
        {
            msg = 'All "child" values will be cleared as 0.00. Are you sure to clear "child" values?';
        }
        else if(obj_type == 'Leaf')
        {
            msg = 'All "parent" values will be cleared as 0.00. Are you sure to clear "parent" values?';
        }

        bootbox.confirm({
            message: msg,
            buttons: {
                confirm:
                {
                    label: 'Yes',
                    className: 'btn-primary'
                },
                cancel: {
                    label: 'No'
                }
            },
            callback: function(result)
            {
                if(result)
                {
                    for (var i = 0; i < CategoriedTree.length; i++) 
                    {
                        if(CategoriedTree[i]['type'] == node_type && CategoriedTree[i]['data']['company_end_prev_ye_value'] != 0.00)
                        {
                            CategoriedTree[i]['data']['company_end_prev_ye_value'] = '0.00';
                        }
                    }

                    $('#Categoried_Treeview').jstree(true).settings.core.data = CategoriedTree;
                    $('#Categoried_Treeview').jstree(true).refresh();
                }
            }
        });
    }
}

function clear_this_year_val()
{
    bootbox.confirm({
        message: 'Are you sure to clear "all values" in this year column?',
        buttons: {
            confirm:
            {
                label: 'Yes',
                className: 'btn-primary'
            },
            cancel: {
                label: 'No'
            }
        },
        callback: function(result)
        {
            if (result) 
            {
                var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
                var CategoriedTree = JSON.parse(JSON.stringify(v));

                for (var i = 0; i < CategoriedTree.length; i++) 
                {
                    CategoriedTree[i]['data']['value'] = 0.00;
                }

                $('#Categoried_Treeview').jstree(true).settings.core.data = CategoriedTree;
                $('#Categoried_Treeview').jstree(true).refresh();
            }
        }
    });
}

function clear_last_year_parent_val()
{
    // bootbox.confirm({
    //     message: 'Are you sure to clear all "parents values" in last year column?',
    //     buttons: {
    //         confirm:
    //         {
    //             label: 'Yes',
    //             className: 'btn-primary'
    //         },
    //         cancel: {
    //             label: 'No'
    //         }
    //     },
    //     callback: function(result)
    //     {
    //         var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
    //         var CategoriedTree = JSON.parse(JSON.stringify(v));

    //         for (var i = 0; i < CategoriedTree.length; i++) 
    //         {
    //             CategoriedTree[i]['data']['company_end_prev_ye_value'] = 0.00;
    //         }

    //         $('#Categoried_Treeview').jstree(true).settings.core.data = CategoriedTree;
    //         $('#Categoried_Treeview').jstree(true).refresh();
    //     }
    // });

    bootbox.dialog({ 
    title: 'Clear Last Year Value',
    message: '<p>How you wish to remove your last year values?</p>',
    size: 'large',
    onEscape: true,
    backdrop: true,
    buttons: {
        all: {
            label: 'All',
            className: 'btn-primary',
            callback: function(){
                bootbox.confirm("Are you sure to clear all values?", function(result){
                    var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
                    var CategoriedTree = JSON.parse(JSON.stringify(v));

                    for (var i = 0; i < CategoriedTree.length; i++) 
                    {
                        CategoriedTree[i]['data']['company_end_prev_ye_value'] = 0.00;
                    }

                    $('#Categoried_Treeview').jstree(true).settings.core.data = CategoriedTree;
                    $('#Categoried_Treeview').jstree(true).refresh();
                });
            }
        },
        parent: {
            label: 'Parent',
            className: 'btn-info',
            callback: function(){
                bootbox.confirm("Are you sure to clear all parent values?", function(result){
                    var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
                    var CategoriedTree = JSON.parse(JSON.stringify(v));

                    for (var i = 0; i < CategoriedTree.length; i++) 
                    {
                        if(CategoriedTree[i]['type'] == "Branch")
                        {
                            CategoriedTree[i]['data']['company_end_prev_ye_value'] = 0.00;
                        }
                    }

                    $('#Categoried_Treeview').jstree(true).settings.core.data = CategoriedTree;
                    $('#Categoried_Treeview').jstree(true).refresh();
                });
            }
        },
        child: {
            label: 'Child',
            className: 'btn-success',
            callback: function(){
                 bootbox.confirm("Are you sure to clear all child values?", function(result){
                    var v = $('#Categoried_Treeview').jstree(true).get_json('#', { flat: true });
                    var CategoriedTree = JSON.parse(JSON.stringify(v));

                    for (var i = 0; i < CategoriedTree.length; i++) 
                    {
                        if(CategoriedTree[i]['type'] == "Leaf")
                        {
                            CategoriedTree[i]['data']['company_end_prev_ye_value'] = 0.00;
                        }
                    }

                    $('#Categoried_Treeview').jstree(true).settings.core.dataType = CategoriedTree;
                    $('#Categoried_Treeview').jstree(true).refresh();
                });               
            }
        }
    }
})
}



$(document).on('click',".used_account",function(e) 
{
    $(this).removeClass( "selected" );
});
