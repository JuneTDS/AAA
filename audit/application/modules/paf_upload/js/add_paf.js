var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];

var latest_gst_rate = 0, count_billing_service_info_num = 0, tmp = [], total_claim_amount = 0;
var state_own_letterhead_checkbox = true;

var initialPreviewArray = []; 
var initialPreviewConfigArray = [];

$('.fye_date').attr('disabled', true);

function ajaxCall() {
    this.send = function(data, url, method, success, type) {
        type = type||'json';
        //console.log(data);
        var successRes = function(data) {
            success(data);
        };

        var errorRes = function(e) {
          //console.log(e);
          alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
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
    var base_url = window.location.origin;  
    var call = new ajaxCall();
    var client = this;

    this.getClientName = function() {
        var url = base_url+"/"+folder+"/"+'paf_upload/getClientName';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.client_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            // console.log(data);
            $('.client_name').find("option:eq(0)").html("Select Client Name");
            // console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_client_name != null && key == data.selected_client_name)
                    {
                        option.attr('selected', 'selected');
                        // $('.client_name').trigger("change");
                        $('.client_name').attr('disabled', true);
                        client.getFyeDate(key);

                    }
                    // console.log(option);
                    $('.client_name').append(option);
                });
                $('#client_name').select2();
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getFyeDate = function(company_code=null) {
        var url = base_url+"/"+folder+"/"+'paf_upload/getFyeDate';
        //console.log(url);
        var method = "get";
        var data = {"company_code": company_code};


        call.send(data, url, method, function(data) {
            console.log(data);
            // $('.bank_name').find("option:eq(0)").html("Select Bank Name");
            // console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    if(data.selected_fye != null && key == data.selected_fye)
                    {
                        option.attr('selected', 'selected');
                        $('.fye_date').attr('disabled', true);

                    }
                    $('.fye_date').append(option);
                    

                });
                // $('#bank_name').select2({
                //     escapeMarkup: function (markup) { return markup; },
                //     language: {
                //         noResults: function () {
                //              return "<a href='"+bank_url+"'>Bank not exist. Add bank here</a>";
                //         }
                //     }
                // });
                //$(".nationality").prop("disabled",false);
                // if(data['result'])
                // {
                //     $(".fye_date").val(data['result']);
                // }

                

                // $('#create_stocktake_arrangement_form').bootstrapValidator('revalidateField', 'fye_date');


            }
            else{
                // $('#create_stocktake_arrangement_form').bootstrapValidator('revalidateField', 'fye_date');
                alert(data.msg);
            }
        }); 
    };

    // this.getAuthStatus = function() {
    //     var url = base_url+"/"+folder+"/"+'bank/getAuthStatus';
    //     //console.log(url);
    //     var method = "get";
    //     var data = {};
    //     $('.auth_status').find("option:eq(0)").html("Please wait..");
    //     call.send(data, url, method, function(data) {
    //         //console.log(data);
    //         $('.auth_status').find("option:eq(0)").html("Select Status:");
    //         //console.log(data);
    //         if(data.tp == 1){
    //             $.each(data['result'], function(key, val) {
    //                 var option = $('<option />');
    //                 console.log(data.select_auth_status);
    //                 option.attr('value', key).text(val);
    //                 if(data.select_auth_status != null && key == data.select_auth_status)
    //                 {
    //                     option.attr('selected', 'selected');
    //                 }
    //                 $('.auth_status').append(option);
    //             });
    //             //$(".nationality").prop("disabled",false);
    //         }
    //         else{
    //             alert(data.msg);
    //         }
    //     }); 
    // };

}

$(function() {
    var cn = new Client();
    cn.getClientName();
    // cn.getAuditorName();
    // cn.getBankName();
    // cn.getAuthStatus();
    //cn.frequency();
    //cn.type_of_day();
    //cm.getDirectorSignature1();
});

$('#client_name').on('change', function() {
    var data = $(".client_name option:selected").val();

    // console.log(data);
    $(".fye_date").val("");
    if(data == "")
    {
        $('.fye_date').attr('disabled', true);
    }
    else{
        // $(".bank_name option").remove();
        var cn = new Client();
        // cn.getBankName(data);
        cn.getFyeDate(data);
        $('.fye_date').attr('disabled', false);
    }

  

})


toastr.options = {
    "positionClass": "toast-bottom-right"
}

// function optionCheckService(service_element) 
// {
//     var tr = jQuery(service_element).parent().parent();
//     console.log(jQuery(service_element).val());
//     if(jQuery(service_element).val() == "1" || jQuery(service_element).val() == "0")
//     {
//         $(".recurring_part").hide();
//         $("#type_of_day").prop("disabled", true);
//         $("#days").prop("disabled", true);
//         $("#from").prop("disabled", true);
//         $("#to").prop("disabled", true);
//     }
//     else
//     {
//         $(".recurring_part").show();
//         $("#type_of_day").prop("disabled", false);
//         $("#days").prop("disabled", false);
//         $("#from").prop("disabled", false);
//         $("#to").prop("disabled", false);
//     }
// }

// function formatDateFunc(date) {
//     //console.log(date);
//   var monthNames = [
//     "01", "02", "03",
//     "04", "05", "06", "07",
//     "08", "09", "10",
//     "11", "12"
//   ];

//   var day = date.getDate();
//   //console.log(day.length);
//   if(day.toString().length==1)  
//   {
//     day="0"+day;
//   }
    
//   var monthIndex = date.getMonth();
//   var year = date.getFullYear();

//   return day + '/' + monthNames[monthIndex] + '/' + year;
// }

$(document).ready(function() {
    // $('#loadingBilling').hide();
    
    // console.log("nani");

    //  $('#create_first_clearance_letter_form').bootstrapValidator({
    //     // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
    //     excluded: ':disabled',
    //     fields: {
    //         client_name: {
    //             row: '.input-group',
    //             validators: {
    //                 notEmpty: {
    //                     message: 'Client name is required and cannot be empty'
    //                 }
    //             }
    //         },
    //         audit_firm_name: {
    //             row: '.input-group',
    //             validators: {
    //                 notEmpty: {
    //                     message: 'Audit firm name is required and cannot be empty'
    //                 }
    //             }
    //         },
    //         fye_date: {
    //             row: '.input-group',
    //             validators: {
    //                 notEmpty: {
    //                     message: 'Financial year end date is required and cannot be empty'
    //                 }
    //             }
    //         },
    //         send_date: {
    //             validators: {
    //                 notEmpty: {
    //                     message: 'Send date is required and cannot be empty'
    //                 }
    //             }
    //         }
    //     }
    // });

    $('#paf_tree').jstree({
        "core": {
            'data': {
                // 'url': "paf_upload/categoriedDefaultData/" + $('#fs_company_info_id').val(),
                'url': paf_all_url +"/"+ $('#paf_id').val() ,
                'dataType': 'json'
                // 'data': function (node) {
                //     console.log(node);
                //     return { 'id': node.id, 'parent': node.parent, 'type': node.type };
                // }
            },
            "check_callback" : true
        },
        "types" : {
            "fixed" : {
                "icon" : false,
                "a_attr" : { "style" : "font-weight:bold;"}
            },
            "root" : {
                "icon" : false,
                "a_attr" : { "style" : "font-weight:bold;"}
            },
            "tree" : {
                "icon" : false
            },
            "doc" : {
                "icon" : "glyphicon glyphicon-file",
                "a_attr" : { "style" : "color:#154069 !important" }
            }
        },

        'contextmenu' : {
            'items' : customMenu
        },
        "plugins": [
            "themes",
            "types",
            "contextmenu",
            "numbering"
        ]
    }).bind("loaded.jstree", function (event, data) {
        $(this).jstree("open_all");
    });
});

function add_index()
{
     $("#paf_tree").jstree("create_node", "#", {type: "root" ,data:{"id":""}}, "last", function (node) {
        this.edit(node);
    });
}

function customMenu(node)
{
    // console.log(node.type);

    var tree = $("#paf_tree").jstree(true);
    var items = {
        createSub : {
            "separator_before": false,
            "separator_after": false,
            "label": "Create Sub Index",
            action: function (obj) {
                node = tree.create_node(node, { text: 'New Sub Index', type: 'tree',data:{"id":""} });
                tree.edit(node);
                tree.deselect_all();
                tree.select_node(node);
            }
        },
        createFile : {
            "separator_before": false,
            "separator_after": false,
            "label": "Add Document",
            action: function (obj) {
                var node=$('#paf_tree').jstree("get_selected", true);
                var path = $('#paf_tree').jstree().get_path(node[0], ' > ');
                
                open_paf_doc_modal(path);
                // $("#upload_paf_doc_modal").modal("show"); 
                // node = tree.create_node(node, { text: 'New File', type: 'doc', data:{"id":""}, icon: 'glyphicon glyphicon-file' });
                // tree.deselect_all();
                // tree.select_node(node);
            }
        },
        renameItem : {
            "separator_before": false,
            "separator_after": false,
            "label": "Rename",
            action: function (obj) {
                tree.edit(node);                                    
            }
        },
        downloadItem : {
            "separator_before": false,
            "separator_after": false,
            "label": "Download",
            action: function (obj) {
                // window.open(
                //     base_url + '/audit/uploads/paf_client_documents/' + node.text ,
                //     ' _blank' // <- This is what makes it open in a new window.
                // );
                var url = base_url + '/audit/uploads/paf_client_documents/' + node.text;


                var link = document.createElement('a');
                link.href = url;
                link.download = node.text;
                link.dispatchEvent(new MouseEvent('click'));
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

    if (node.type === 'fixed') {
        delete items.createFile;
        delete items.removeItem;
        delete items.renameItem;
        delete items.downloadItem;
    }
    else if (node.type === 'root') {
        delete items.createFile;
        delete items.downloadItem;
    }
    else if (node.type === 'tree') {
        delete items.createSub;
        delete items.downloadItem;
    }
    else if (node.type === 'doc') {
        delete items.createSub;
        delete items.createFile;
        delete items.renameItem;

    }

    return items;
}

function open_paf_doc_modal(path, tree, node)
{
    $("#upload_paf_doc_modal").modal("show"); 
    $('#current_path').val(path);

    $('#multiple_paf_doc').fileinput('destroy');

    $("#multiple_paf_doc").fileinput({
        theme: 'fa',
        uploadUrl: '/audit/paf_upload/uploadPafDoc', // you must set a valid URL here else you will get an error
        uploadAsync: false,
        browseClass: "btn btn-primary",
        fileType: "any",
        required: false,
        showCaption: false,
        showUpload: false,
        showRemove: false,
        showClose: false,
        fileActionSettings: {
                        showRemove: true,
                        showUpload: false,
                        showZoom: true,
                        showDrag: true,
                    },
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
        overwriteInitial: false,
        initialPreviewAsData: true,
        initialPreviewDownloadUrl: base_url + '/audit/uploads/bank_images_or_pdf/{filename}',
        initialPreview: initialPreviewArray,
        initialPreviewConfig: initialPreviewConfigArray,
        // deleteUrl: "/audit/bank/deleteBaFile",
        /*maxFileSize: 20000048,
        maxImageWidth: 1000,
        maxImageHeight: 1500,
        resizePreference: 'height',
        resizeImage: true,*/
        purifyHtml: true,// this by default purifies HTML data for preview
        /*uploadExtraData: { 
            officer_id: $('input[name="officer_id"]').val() 
        }*/
        /*width:auto;height:auto;max-width:100%;max-height:100%;*/
        // uploadExtraData: function() {
        //     return {
        //         ba_id: $(".doc_ba_id").val()
        //         // username: $("#username").val()
        //     };
        // }

    });
}

$( "#upload_paf_doc_btn" ).click(function() {
    $("#multiple_paf_doc").fileinput('upload');
});

$("#multiple_paf_doc").fileinput({
    theme: 'fa',
    uploadUrl: '/audit/paf_upload/uploadPafDoc', // you must set a valid URL here else you will get an error
    uploadAsync: false,
    browseClass: "btn btn-primary",
    fileType: "any",
    required: false,
    showCaption: false,
    showUpload: false,
    showRemove: false,
    showClose: false,
    fileActionSettings: {
                    showRemove: true,
                    showUpload: false,
                    showZoom: true,
                    showDrag: true,
                },
    previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
    overwriteInitial: false,
    initialPreviewAsData: true,
    initialPreviewDownloadUrl: base_url + '/audit/uploads/bank_images_or_pdf/{filename}',
    initialPreview: initialPreviewArray,
    initialPreviewConfig: initialPreviewConfigArray,
    // deleteUrl: "/audit/bank/deleteBaFile",
    /*maxFileSize: 20000048,
    maxImageWidth: 1000,
    maxImageHeight: 1500,
    resizePreference: 'height',
    resizeImage: true,*/
    purifyHtml: true,// this by default purifies HTML data for preview
    /*uploadExtraData: { 
        officer_id: $('input[name="officer_id"]').val() 
    }*/
    /*width:auto;height:auto;max-width:100%;max-height:100%;*/
    // uploadExtraData: function() {
    //     return {
    //         ba_id: $(".doc_ba_id").val()
    //         // username: $("#username").val()
    //     };
    // }

}).on('filebatchuploadsuccess', function(event, data, previewId, index) {

        //window.location.href = base_url + "personprofile";
        // if(corporate_reload_link != false)
        // {
            // window.location.href = corporate_reload_link;
        // }

        var ref = $("#paf_tree").jstree(true);
        var sel = $("#paf_tree").jstree(true).get_selected();

        $("#upload_paf_doc_modal").modal("hide"); 
        var fileNames = data.response;
        for (i = 0; i < fileNames.length; i++) {
            // console.log(tree);
            // console.log(node);
            var node = ref.create_node(sel, { text: fileNames[i], type: 'doc', data:{"id":""}, icon: 'glyphicon glyphicon-file' });
            
        }

        ref.deselect_all();
        ref.select_node(node);

        // ref.create_node(sel, { text: "fileNames[i]", type: 'doc', data:{"id":""}, icon: 'glyphicon glyphicon-file' });

        console.log(fileNames);
        console.log("success");
        toastr.success("Information Updated", "Success");
    
    
    //console.log(data);
});

//console.log(billing_top_info);
// new Date('2015-1-1').to('dd.MM.yy')    
// $('[name="auth_date"]').val(edit_bank_auth[0]["auth_date"]);
// console.log(edit_bank_auth);
// if(edit_bank_auth != "")
// {
//    $('[name="auth_date1"]').val(edit_bank_auth[0]["auth_date"]); 
// }

if(edit_first_letter != "")
{
   $('[name="fye_date"]').val(edit_first_letter[0]["fye_date"]); 
}

if(edit_first_letter != "")
{
   $('[name="send_date"]').val(edit_first_letter[0]["send_date"]); 
}



// $("[name='own_letterhead_checkbox']").bootstrapSwitch({
//     state: state_own_letterhead_checkbox,
//     size: 'normal',
//     onColor: 'primary',
//     onText: 'YES',
//     offText: 'NO',
//     // Text of the center handle of the switch
//     labelText: '&nbsp',
//     // Width of the left and right sides in pixels
//     handleWidth: '75px',
//     // Width of the center handle in pixels
//     labelWidth: 'auto',
//     baseClass: 'bootstrap-switch',
//     wrapperClass: 'wrapper'


// });

// Triggered on switch state change.
// $("[name='own_letterhead_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
//     console.log(state); // true | false

//     if(state == true)
//     {
//         $("[name='hidden_own_letterhead_checkbox']").val(1);
//     }
//     else
//     {
//         $("[name='hidden_own_letterhead_checkbox']").val(0);
//     }
// });


// var service_array = "";
// var service_category_array = "";
// var unit_pricing = "";



// $(".amount").on('change',function(){
//     sum_total();
// });
// $(".rate").on('change',function(){
//     sum_total();
// });
// $(".currency").on('change',function(){
//     sum_total();
// });




// function addCommas(nStr) {
//     nStr += '';
//     var x = nStr.split('.');
//     var x1 = x[0];
//     var x2 = x.length > 1 ? '.' + x[1] : '';
//     var rgx = /(\d+)(\d{3})/;
//     while (rgx.test(x1)) {
//         x1 = x1.replace(rgx, '$1' + ',' + '$2');
//     }
//     return x1 + x2;
// }

// function delete_billing(element) {
//     /*if(confirm("Delete This Record?"))
//     {*/
//         var tr = jQuery(element).parent().parent(),
//             billing_service_id = tr.find('input[name="billing_service_id[]"]').val();

//         tr.closest("DIV.tr").remove();
//         //console.log($("#allotment_add > div").length);
//         if($("#body_create_billing > div").length == 1)
//         {
//             if($('.delete_billing_button').css('display') == 'block')
//             {
//                 $('.delete_billing_button').css('display','none');
//             }
//         }
//         sum_total();
//     //}
// }



// $(document).on('change','#service',function(e){
//     var num = $(this).parent().parent().parent().attr("num");
    
//     var descriptionValue = $(this).find(':selected').data('description');
//     var amountValue = $(this).find(':selected').data('amount');
//     // var currencyValue = $(this).find(':selected').data('currency');
//     var unit_pricingValue = $(this).find(':selected').data('unit_pricing');

//     $(this).parent().parent().parent().find('#invoice_description').text(descriptionValue);
//     $(this).parent().parent().parent().find('#amount').val(addCommas(amountValue));
//     //$(this).parent().parent().parent().find('#currency').val(currencyValue);
//     $(this).parent().parent().parent().find('#unit_pricing').val(addCommas(unit_pricingValue));

//     $('#create_billing_form').formValidation('revalidateField', 'service['+num+']');
//     $('#create_billing_form').formValidation('revalidateField', 'invoice_description['+num+']');
//     $('#create_billing_form').formValidation('revalidateField', 'amount['+num+']');
//     $('#create_billing_form').formValidation('revalidateField', 'unit_pricing['+num+']');
//     sum_total();
// });



//$count_billing_service_info = 1;
// $(document).on('click',"#billing_service_info_Add",function() {
    
//     $a=""; 
//     /*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
//     $a += '<div class="tr editing tr_billing" method="post" name="form'+$count_billing_service_info+'" id="form'+$count_billing_service_info+'" num="'+$count_billing_service_info+'">';
//     $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
//     $a += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value=""/></div>';
//     $a += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+$count_billing_service_info+']" id="client_billing_info_id" value=""/></div>';
//     $a += '<div class="hidden"><input type="text" class="form-control" name="claim_service_id['+$count_billing_service_info+']" id="claim_service_id" value=""/></div>';
//     $a += '<div class="td"><div class="select-input-group"><select class="input-sm form-control" name="service['+$count_billing_service_info+']" id="service" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+$count_billing_service_info+']" id="payment_voucher_type" value=""/><div id="form_service"></div></div></div>';
//     $a += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+$count_billing_service_info+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+$count_billing_service_info+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div></div>';
//     $a += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+$count_billing_service_info+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
//     $a += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+$count_billing_service_info+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select><div id="form_unit_pricing"></div></div></div>';
//     /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_billing_info(this);">Save</button></div></div>';*/
//     $a += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: block;">Delete</button></div>';
//     $a += '</div>';

//     /*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
//     $("#body_create_billing").append($a); 

//     if($("#body_create_billing > div").length > 1)
//     {
//         $('.delete_billing_button').css('display','block');
//     }

//     $('.period_start_date').datepicker({ 
//         dateFormat:'dd/mm/yyyy',
//     }).datepicker('setStartDate', "01/01/1920");

//     $('.period_end_date').datepicker({ 
//         dateFormat:'dd/mm/yyyy',
//     }).datepicker('setStartDate', "01/01/1920");

//     $.each(unit_pricing, function(key, val) {
//         //console.log(val['unit_pricing_name']);
//         var option = $('<option />');
//         option.attr('value', val['id']).text(val['unit_pricing_name']);
        
//         $("#form"+$count_billing_service_info+" #unit_pricing").append(option);
//     });

//     var category_description = '';
//     var optgroup = '';

//     for(var t = 0; t < service_category_array.length; t++)
//     {
//         // if(category_description != service_category_array[t]['category_description'])
//         // {
//             if(category_description != service_category_array[t]['category_description'])
//             {
//                 if(optgroup != '')
//                 {
//                     $("#form"+$count_billing_service_info+" #service").append(optgroup);
//                 }
//                 optgroup = $('<optgroup label="' + service_category_array[t]['category_description'] + '" />');
//                 //console.log(service_category_array[t]['category_description']);
//             }

//             category_description = service_category_array[t]['category_description'];
            
//             for(var h = 0; h < service_array.length; h++)
//             {
//                 if(category_description == service_array[h]['category_description'])
//                 {
//                     //console.log(service_array[h]['service_name']);
//                     var option = $('<option />');
//                     option.attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']).appendTo(optgroup);
//                 }
//             }
//             //}
        

        
//     }
//     $("#form"+$count_billing_service_info+" #service").append(optgroup);   

//     $("#form"+$count_billing_service_info+" #service").select2();
//     $('#create_billing_form').formValidation('addField', 'service['+$count_billing_service_info+']', service);
//     $('#create_billing_form').formValidation('addField', 'invoice_description['+$count_billing_service_info+']', invoice_description);
//     $('#create_billing_form').formValidation('addField', 'amount['+$count_billing_service_info+']', amount);
//     $('#create_billing_form').formValidation('addField', 'unit_pricing['+$count_billing_service_info+']', validate_unit_pricing);

//     $count_billing_service_info++;
// });


// $(document).on('change','#create_billing_form #client_name',function(e){
//     showRow();
// });

// $(document).on('change','#create_billing_form #currency',function(e){
//     showRow();
// });

// function showRow(){
//     var company_code = $('#client_name option:selected').val();

//     //console.log(company_code);
//     if($("#currency option:selected").val() == 0)
//     {
//         toastr.error("Please select a currency.", "Error");
//     }
//     else
//     {
//         $.ajax({
//             type: "POST",
//             url: "billings/get_company_service",
//             data: {"company_code":company_code, "currency": $("#currency option:selected").val()}, // <--- THIS IS THE CHANGE
//             dataType: "json",
//             success: function(response){
//                 $(".tr_billing").remove();
//                 console.log(response);
//                 if(response.Status == 1)
//                 {
//                     if(company_code == 0)
//                     {
//                         $('[name="address"]').val("");
//                         $('[name="hidden_postal_code"]').val("");
//                         $('[name="hidden_street_name"]').val("");
//                         $('[name="hidden_building_name"]').val("");
//                         $('[name="hidden_unit_no1"]').val("");
//                         $('[name="hidden_unit_no2"]').val("");
//                         $('#create_billing_form').formValidation('revalidateField', 'address');
//                         $("#address").attr('readOnly', false);
//                     }
//                     else
//                     {
//                         $('[name="address"]').val(response.address);
//                         $('[name="hidden_postal_code"]').val(response.postal_code);
//                         $('[name="hidden_street_name"]').val(response.street_name);
//                         $('[name="hidden_building_name"]').val(response.building_name);
//                         $('[name="hidden_unit_no1"]').val(response.unit_no1);
//                         $('[name="hidden_unit_no2"]').val(response.unit_no2);
//                         $('#create_billing_form').formValidation('revalidateField', 'address');
//                         $("#address").attr('readOnly', true);
//                     }
                    

//                     service_array = response.service;
//                     service_category_array = response.selected_billing_info_service_category;
//                     unit_pricing = response.unit_pricing;
//                     claim_result = response.claim_result;

//                     if(response.service.length != 0)
//                     {
//                         $('#create_billing_service').show();
//                         $('#body_create_billing').show();
//                         $('#sub_total_create_billing').show();
//                         $('#gst_create_billing').show();
//                         $('#grand_total_create_billing').show();
//                     }
//                     else
//                     {
//                         $('#create_billing_service').hide();
//                         $('#body_create_billing').hide();
//                         $('#sub_total_create_billing').hide();
//                         $('#gst_create_billing').hide();
//                         $('#grand_total_create_billing').hide();
//                     }
//                     //console.log(claim_result.length);
//                     // if(claim_result.length == 0)
//                     // {
//                         count_billing_service_info_num = 0;

//                         $a0=""; 

//                         $a0 += '<div class="tr editing tr_billing" method="post" name="form'+0+'" id="form'+0+'" num="'+0+'">';
//                         $a0 += '<div class="hidden"><input type="text" class="form-control" name="company_code" value=""/></div>';
//                         $a0 += '<div class="hidden"><input type="text" class="form-control" name="billing_service_id" value=""/></div>';
//                         $a0 += '<div class="hidden"><input type="text" class="form-control" name="client_billing_info_id['+0+']" id="client_billing_info_id" value=""/></div>';
//                         $a0 += '<div class="hidden"><input type="text" class="form-control" name="claim_service_id['+0+']" id="claim_service_id" value=""/></div>';
//                         $a0 += '<div class="td"><div class="select-input-group"><select class="input-sm form-control" name="service['+0+']" id="service" style="width:200px;"><option value="0" data-invoice_description="" data-amount="">Select Service</option></select><input type="hidden" class="form-control" name="payment_voucher_type['+0+']" id="payment_voucher_type" value=""/><div id="form_service"></div></div></div>';
//                         $a0 += '<div class="td"><div class="input-group mb-md"><textarea class="form-control" name="invoice_description['+0+']"  id="invoice_description" rows="3" style="width:420px"></textarea></div><div style="width: 200px;display: inline-block; margin-right:10px;"><div style="font-weight: bold;">Period Start Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_start_date" id="period_start_date" name="period_start_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div><div style="width: 200px;display: inline-block"><div style="font-weight: bold;">Period End Date</div><div class="input-group" style="width: 100%" id="period_end_date_datepicker"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker period_end_date" id="period_end_date" name="period_end_date['+0+']" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div></div>';
//                         $a0 += '<div class="td" style="width:150px"><div class="input-group"><input type="text" name="amount['+0+']" class="numberdes form-control text-right amount" value="" id="amount" style="width:150px"/><div id="form_amount"></div></div></div>';
//                         /*$a += '<div class="td action"><button type="button" class="btn btn-primary" onclick="edit_billing_info(this);">Save</button></div></div>';*/
//                         $a0 += '<div class="td" style="width:150px"><div class="select-input-group"><select class="form-control" style="text-align:right;width: 165px;" name="unit_pricing['+0+']" id="unit_pricing"><option value="0" >Select Unit Pricing</option></select></div></div>';
//                         $a0 += '<div class="td action"><button type="button" class="btn btn-primary delete_billing_button" onclick="delete_billing(this)" style="display: none;">Delete</button></div>';
//                         $a0 += '</div>';

//                         $("#body_create_billing").append($a0); 

//                         if($("#body_create_billing > div").length > 1)
//                         {
//                             $('.delete_billing_button').css('display','block');
//                         }

//                         $('.period_start_date').datepicker({ 
//                             dateFormat:'dd/mm/yyyy',
//                         }).datepicker('setStartDate', "01/01/1920");

//                         $('.period_end_date').datepicker({ 
//                             dateFormat:'dd/mm/yyyy',
//                         }).datepicker('setStartDate', "01/01/1920");

//                         $.each(unit_pricing, function(key, val) {
//                             //console.log(val['unit_pricing_name']);
//                             var option = $('<option />');
//                             option.attr('value', val['id']).text(val['unit_pricing_name']);
                            
//                             $("#form"+0+" #unit_pricing").append(option);
//                         });

//                         var category_description = '';
//                         var optgroup = '';

//                         for(var t = 0; t < service_category_array.length; t++)
//                         {
//                             if(category_description != service_category_array[t]['category_description'])
//                             {
//                                 if(optgroup != '')
//                                 {
//                                     $("#form"+0+" #service").append(optgroup);
//                                 }
//                                 optgroup = $('<optgroup label="' + service_category_array[t]['category_description'] + '" />');
//                             }

//                             category_description = service_category_array[t]['category_description'];
                            
//                             for(var h = 0; h < service_array.length; h++)
//                             {
//                                 if(category_description == service_array[h]['category_description'])
//                                 {
//                                     var option = $('<option />');
//                                     option.attr('data-description', service_array[h]['invoice_description']).attr('data-currency', service_array[h]['currency']).attr('data-unit_pricing', service_array[h]['unit_pricing']).attr('data-amount', service_array[h]['amount']).attr('value', service_array[h]['id']).text(service_array[h]['service_name']).appendTo(optgroup);
//                                 }
//                             }
//                         }
//                         $("#form"+0+" #service").append(optgroup);

//                         $("#form"+0+" #service").select2();

//                         $('#create_billing_form').formValidation('addField', 'service['+0+']', service);
//                         $('#create_billing_form').formValidation('addField', 'invoice_description['+0+']', invoice_description);
//                         $('#create_billing_form').formValidation('addField', 'amount['+0+']', amount);  
//                         $('#create_billing_form').formValidation('addField', 'unit_pricing['+0+']', validate_unit_pricing);  
//                     //}
//                     if(claim_result.length > 0)
//                     { 
//                         $("#modal_claim_list").modal("show");
//                         //toastr.error("This Client have "+claim_result.length+" not paying claim.", "Claim Info");
//                         $(".claim_list_tr").remove();
//                         for(var f = 0; f < claim_result.length; f++)
//                         {
//                             $b = "";
//                             $b = '<tr class="claim_list_tr"><td style="text-align: center; vertical-align: middle;"><input type="checkbox" class="claim_id" id="claim_id" value="'+claim_result[f]["id"]+'" data-amount="'+claim_result[f]["amount"]+'"></td><td><div class="type">'+claim_result[f]["type_name"]+'</div></td><td><div class="desciption">'+claim_result[f]["claim_description"]+'</div></td><td><div class="desciption">'+claim_result[f]["currency_name"]+'</div></td><td style="text-align: right"><div class="amount">'+addCommas(claim_result[f]["amount"])+'</div></td></tr>';
//                             $("#claim_info").append($b); 
//                         }
//                     }
//                 }

//             }               
//         });
//         $('#create_billing_form').formValidation('revalidateField', 'client_name');
//     }
// }

// $(document).on('change','.claim_id',function(e){
//     var checked = $(this).val();

//     if ($(this).is(':checked')) {
//         tmp.push(checked);
//         total_claim_amount = total_claim_amount + parseFloat($(this).data('amount'));
//     } else {
//         tmp.splice($.inArray(checked, tmp),1);
//         total_claim_amount = total_claim_amount - parseFloat($(this).data('amount'));
//     }

//     $(".total_selected").html(addCommas(total_claim_amount.toFixed(2)));
// });

// $(document).on('click',"#selectClaimList",function(e){
//     $("#modal_claim_list").modal("hide");
//     //console.log($("#service").includes("DISBURSEMENT - Printing"));
//     var opt = 'DISBURSEMENTS';
//     if ($('#service option:contains('+ opt +')').length) {
//         $("select[name='service[0]']").select2("val", $("#service option:contains('DISBURSEMENTS')").val()).trigger('change');
//         $("#amount").val(addCommas(total_claim_amount.toFixed(2)));
//         //console.log($("#claim_service_id"));
//         $("#claim_service_id").attr('value', JSON.stringify(tmp));
//     }
//     else
//     {
//         toastr.error("Please set the DISBURSEMENTS in Service Engagement inside Client module.", "Error");
//     }
    
//     //$("select[name='service[0]'] option:contains(DISBURSEMENT - Printing)").attr('selected', 'selected');
// });

// $(document).on('change','#create_billing_form #currency',function(e){
//     //console.log($(this).val());
//     if($(this).val() == "1")
//     {
//         $("#rate").val("1.0000");
//     }
//     $('#create_billing_form').formValidation('revalidateField', 'currency');
// });
$(document).on('click',"#savePaf",function(e){
    $('.fye_date').prop('disabled',false);
    $('.client_name').prop('disabled',false);

   
    $("#create_paf_form").submit();

      
});





$(document).on("submit",function(e){
    
        e.preventDefault();
        var $form = $(e.target);
        

        if($('#create_paf_form').css('display') != 'none')
        {
            //$("#create_billing_form").formValidation('destroy');
            //$('[name="invoice_no"]').attr('disabled', false);
            // $('[name="billing_date"]').attr('disabled', false);
            // $('[name="address"]').attr('disabled', false);
            // $('.currency').attr('disabled', false);

            // $('.fye_date').attr('disabled', false);
            // $('.client_name').attr('disabled', false);
            // console.log($(".client_name option:selected").val());
            var selected_company_name = $(".client_name option:selected").val();
            var v = $('#paf_tree').jstree(true).get_json('#', { flat: true });
            var paf_tree = JSON.parse(JSON.stringify(v));
            // var paf_tree = JSON.stringify(v);

            console.log(paf_tree);

            $.ajax({
                type: 'POST',
                url: save_paf_url,
                // data: $form.serialize() + '&paf_tree=' + paf_tree,
                data: $form.serialize() + '&' + $.param({ 'paf_tree': paf_tree }),
                dataType: 'json',
                success: function(response){
                    // console.log("fffffffffffffffffffffffffffffff");
                    //console.log(response.Status);

                    // if (response.Status === 1) 
                    // {
                    //     toastr.success(response.message, response.title);
                    //     //console.log(response);
                    //     var getUrl = window.location;
                    //     var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/billings";
                    //     //console.log(baseUrl);
                    //     window.location.href = baseUrl;

                    // }
                    // if(response.status == "success")
                    // {
                    $('.fye_date').attr('disabled', true);
                    $('.client_name').attr('disabled', true);
                        // generate_first_clearance_letter(response.letter_id);
                        // console.log(bank_url);
                        // window.location.href = stocktake_url;
                    toastr.success("Information Updated", "Success");
                        
                    // }
                    // else
                    // {
                        // alert("Selected company may not assigned to any service or servicing firm. Failed to add bank authorization.");
                    //}
                 
                    // window.location.href = auditor_url;
                    
                }
            });
        }
        else
        {
            toastr.error("Please set service engagement in this client.", "error");
        }
        

});


function generate_first_clearance_letter(letter_id)
{


    $.ajax({ //Upload common input
      url       : generate_first_letter_url,
      type      : "POST",
      dataType  : 'json',
      data  : {"letter_id": letter_id},
      beforeSend: function()
        {
            $('#loadingMessage').show();
        },
      success   : function (response,data) {
        $('#loadingMessage').hide();
        //console.log(response.pdf_link);
        console.log("done");
            window.open(
              response.pdf_link,
              '_blank' // <- This is what makes it open in a new window.
            );

            // setTimeout(function(){ deletePDF(response.path); }, 5000);
        }
    })
}

function generate_auth(auth_id)
{
    // var auth_id     = div.find('.auth_id').val();
    // console.log(auth_id);

    $.ajax({ //Upload common input
      url       : generate_auth_document_url,
      type      : "POST",
      dataType  : 'json',
      data  : {"auth_id": auth_id},
      beforeSend: function()
        {
            $('#loadingMessage').show();
        },
      success   : function (response,data) {
        $('#loadingMessage').hide();
        //console.log(response.pdf_link);
        console.log("done");
            window.open(
              response.pdf_link,
              '_blank' // <- This is what makes it open in a new window.
            );

            setTimeout(function(){ deletePDF(response.path); }, 5000);
        }
    })
}

// $(document).on('click',"#saveLetter",function(e){
//     var bootstrapValidator = $("#create_first_clearance_letter_form").data('bootstrapValidator');
//     bootstrapValidator.validate();
//     if(bootstrapValidator.isValid())
//         $("#create_first_clearance_letter_form").submit();
//     else return;
//     // $("#create_bank_auth_form").submit();
// });
