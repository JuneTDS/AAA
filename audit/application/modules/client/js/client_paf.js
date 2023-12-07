
// $(".parent").html(function(index, html) {
//   return "PAF " + (index + 1) + html;
// });                                                                   

// $(".child").html(function(index, html) {
//   return "PAF " + (index + 1) + html;
// });
var initialPreviewArray = []; 
var initialPreviewConfigArray = [];
var indexno_asc = true, point_asc = true, response_asc = true, raisedby_asc = true;

$.ajax({
    type: "POST",
    url: get_paf_index_url,
    data: '&company_code=' + company_code,
    async: false,
    dataType: "json",
    success: function(data){

        $("#loadingmessage").hide();
        $.each(data, function(key, val) {
            var option = $('<option />');
            option.attr('value', val.id).text(val.index_no);
            $("#search_indexno").append(option);
            $("#search_indexno").select2();
      
        });

        //$('#form'+$count_family_info).find(".relationship"+$count_family_info).select2();
    }
});

$("#search_cleared").select2();



function add_main_category(){
	$("#main_category_modal #input_main_desc").val("");
	$("#main_category_modal").modal("show"); 
}

function add_sub_category(element){
	$("#sub_category_modal #input_sub_desc").val("");
	var tr = $(element).parent().parent();
	$("#sub_category_modal").modal("show"); 
	var parent_id = $(tr).attr('id');
	$("#selected_parent_id").val(parent_id);

	// var paf_number = window.getComputedStyle(
 //                    tr,':after').content;

	console.log(tr);
}

function rename_node(element)
{
	$("#new_name").val("");
	$("#rename_modal").modal("show");
	rename_tr = $(element).parent().parent();

	$('#new_name').attr('placeholder',$(rename_tr).find('.desc').text());
}

function delete_paf(element){
	var tr 		= $(element).parent().parent();
	var parent_flag = false;
	var node_id = "";


	if(tr.find('.child').length > 0)
	{
		node_id	= tr.find('[name ="c_id[]"]').val();
	}
	else if(tr.find('.parent').length > 0)
	{
		node_id	= tr.find('[name ="p_id[]"]').val();
		parent_flag = true;
	}

	if(node_id != ""){
        bootbox.confirm({
            message: "Do you want to delete this selected info? <br> If you have any unsaved changes, please save before proceed.",
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
                	if(parent_flag)
                	{
                		$.post(delete_paf_parent_url+'/active_paf_list', { 'p_id': node_id }, function(data, status){
                        if(data){
                        		delete_paf_interface(element);
	                            $("#create_paf_form").submit();
	                        }
	                    });
                	}
                	else
                	{
                		$.post(delete_paf_child_url+'/active_paf_list', { 'c_id': node_id }, function(data, status){
                        if(data){
                        		delete_paf_interface(element);
	                            $("#create_paf_form").submit();
	                        }
	                    });
                	}
                    
                }
            }
        })
    }
    else
    {
        delete_paf_interface(element);
    }
	
}


function delete_paf_interface(element) 
{
    var p_tr = $(element).parent().parent();
        // payment_receipt_service_id = tr.find('input[name="payment_receipt_service_id[]"]').val();
    var parent_form_id = $(p_tr).attr('id');

    p_tr.closest("tr").remove();
    $('#datatable-paf > tbody  > tr.child-tr').each(function(index, tr) { 
    	if($(tr).find('[name="c_temp_parent_id[]"]').val() == parent_form_id){
    		tr.closest("tr").remove();
    	}
    });
    load_index();


}

function delete_archived_paf_interface(element) 
{
    var p_tr = $(element).parent().parent();
        // payment_receipt_service_id = tr.find('input[name="payment_receipt_service_id[]"]').val();
    var parent_form_id = $(p_tr).attr('id');

    p_tr.closest("tr").remove();
    $('#datatable-archived-paf > tbody  > tr.child-tr').each(function(index, tr) { 
    	if($(tr).find('[name="c_temp_parent_id[]"]').val() == parent_form_id){
    		tr.closest("tr").remove();
    	}
    });

}


function archive_child(element){
	var tr 		= $(element).parent().parent();
	var child_id = tr.find('[name ="c_id[]"]').val();

	if(child_id != ""){
        bootbox.confirm({
            message: "Do you want to archive this selected info?",
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
           			$.post(archive_paf_child_url+'/active_paf_list', { 'c_id': child_id }, function(data, status){
                    if(data){
                    		delete_paf_interface(element);
                            $("#create_paf_form").submit();
                        }
                    });
            	
                    
                }
            }
        })
    }
	
}


function archive_parent(element){
	var tr 		= $(element).parent().parent();
	var parent_id = tr.find('[name ="p_id[]"]').val();

	if(parent_id != ""){
        bootbox.confirm({
            message: "Do you want to archive this selected info?",
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
           			$.post(archive_paf_parent_url+'/active_paf_list', { 'p_id': parent_id }, function(data, status){
                    if(data){
                    		delete_paf_interface(element);
                            $("#create_paf_form").submit();
                        }
                    });
            	
                    
                }
            }
        })
    }
	
}

function restore_paf(element){
	var tr 		= $(element).parent().parent();
	var child_id = tr.find('[name ="c_id[]"]').val();
    var child_type = tr.find('[name ="c_type[]"]').val();

	if(child_id != ""){
        bootbox.confirm({
            message: "Do you want to restore this selected info?",
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
           			$.post(restore_paf_url+'/archive_paf_list', { 'c_id': child_id , 'c_type':child_type }, function(data, status){
                    if(data){
                    		delete_archived_paf_interface(element);
                            location.reload();
                            $("#create_paf_form").submit();
                        }
                    });
            	
                    
                }
            }
        })
    }
	
}

function open_paf_doc_modal(element)
{
    $("#upload_paf_doc_modal").modal("show"); 
    var tr = $(element).parent().parent();
    var current_path = $(tr).find('.desc').text();
    current_index = $(tr).find('[name ="c_index_no[]"]').val();

    console.log(current_index);

    $('#current_path').val(current_path);

    $('#multiple_paf_doc').fileinput('destroy');

    $("#multiple_paf_doc").fileinput({
        theme: 'fa',
        uploadUrl: '/audit/paf_upload/uploadPafDoc', // you must set a valid URL here else you will get an error
        // uploadUrl: '/test_audit/paf_upload/uploadPafDoc', // you must set a valid URL here else you will get an error
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
        // initialPreviewDownloadUrl: base_url + '/audit/uploads/bank_images_or_pdf/{filename}',
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
        //         index_no: current_index
        //         // username: $("#username").val()
        //     };
        // }

    });
}

// function open_doc(element)
// {
// 	doc_url = base_url + '/audit/uploads/paf_client_documents/'+ $(element).text();
// 	window.open(
//       doc_url,
//       '_blank' // <- This is what makes it open in a new window.
//     );
// }

function view_docs(element)
{
	$("#paf_doc_table tr").remove()
    var tr = $(element).parent().parent();

    current_id = $(tr).find('[name ="c_id[]"]').val();

    var modal = $("#paf_doc_modal");
	modal.find('.panel-title').text($(tr).find('.desc').text() + ' - Document(s)');


    var table = "";
	console.log(paf_doc_list);
    console.log(active_tab);
	for(var i = 0; i < paf_doc_list.length; i++)
	{
		if(paf_doc_list[i]["id"] == current_id)
        {   
            if(active_tab == "active_paf_list")
            {
                var count_non_archived = 0;

                
                for(var a = 0; a < paf_doc_list[i].doc.length; a++)
                {   
                    if(paf_doc_list[i].doc[a].archived == 0)
                    {
                        table += '<tr class="tr_paf_doc">'+
                                '<input type="hidden" class="doc_id" value="'+paf_doc_list[i].doc[a].id+'">'+
                                '<input type="hidden" class="file_name" value="'+paf_doc_list[i].doc[a].file_name+'">'+
                                '<th style="width:65%;"><a href="'+ base_url + '/audit/document/'+paf_doc_list[i].doc[a].file_path+'/'+paf_doc_list[i].doc[a].file_name+'" target="_blank" >'+paf_doc_list[i].doc[a].file_name+'</a></th>'+
                                // '<th style="width:65%;"><a href="'+ base_url + '/test_audit/document/'+paf_doc_list[i].doc[a].file_path+'/'+paf_doc_list[i].doc[a].file_name+'" target="_blank" >'+paf_doc_list[i].doc[a].file_name+'</a></th>'+
                                '<td style="width:35%"><button style="margin-right:5px;margin-left:5px;" type="button" class="btn btn_blue" onclick=delete_doc(this)>Delete</button><button type="button" class="btn btn_blue" onclick=download_doc(\''+paf_doc_list[i].doc[a].file_path+'\',\''+paf_doc_list[i].doc[a].file_name+'\')>Download</button></td>'+
                             '</tr>';

                        count_non_archived ++;
                    }
                    
                }
                
                if(count_non_archived == 0)
                {
                    table = '<tr><td align="center"><p style="margin-top:10px;">No document(s) to display</p></td></tr>'

                }
            }
            else
            {
                if(paf_doc_list[i].doc != "")
                {
                    var count_archived = 0;

                    for(var a = 0; a < paf_doc_list[i].doc.length; a++)
                    {
                        if(paf_doc_list[i].doc[a].archived == 1)
                        {
                            table += '<tr class="tr_paf_doc">'+
                                        '<input type="hidden" class="doc_id" value="'+paf_doc_list[i].doc[a].id+'">'+
                                        '<input type="hidden" class="file_name" value="'+paf_doc_list[i].doc[a].file_name+'">'+
                                        '<th style="width:65%;"><a href="'+ base_url + '/audit/document/'+paf_doc_list[i].doc[a].file_path+'/'+paf_doc_list[i].doc[a].file_name+'" target="_blank" >'+paf_doc_list[i].doc[a].file_name+'</a></th>'+
                                        // '<th style="width:65%;"><a href="'+ base_url + '/test_audit/document/'+paf_doc_list[i].doc[a].file_path+'/'+paf_doc_list[i].doc[a].file_name+'" target="_blank" >'+paf_doc_list[i].doc[a].file_name+'</a></th>'+
                                        '<td style="width:35%"><button style="margin-right:5px;margin-left:5px;" type="button" class="btn btn_blue" onclick=delete_doc(this)>Delete</button><button type="button" class="btn btn_blue" onclick=download_doc(\''+paf_doc_list[i].doc[a].file_path+'\',\''+paf_doc_list[i].doc[a].file_name+'\')>Download</button></td>'+
                                     '</tr>';

                            count_archived ++;
                        }
                    }
                }
                
                if(count_archived == 0)
                {
                    table = '<tr><td align="center"><p style="margin-top:10px;">No document(s) to display</p></td></tr>'
                }

                

                // console.log(count_archived);
            }

            // if(paf_doc_list[i]["type"] == "fixed" && paf_doc_list[i]["archived"] == 0 && active_tab == "active_paf_list")
            // {
            //     var count_fixed_non_archived = 0;

                
            //     for(var a = 0; a < paf_doc_list[i].doc.length; a++)
            //     {   
            //         if(paf_doc_list[i].doc[a].archived == 0)
            //         {
            //             table += '<tr class="tr_paf_doc">'+
            //                     '<input type="hidden" class="doc_id" value="'+paf_doc_list[i].doc[a].id+'">'+
            //                     '<input type="hidden" class="file_name" value="'+paf_doc_list[i].doc[a].file_name+'">'+
            //                     '<th style="width:65%;"><a href="'+ base_url + '/audit/document/'+paf_doc_list[i].doc[a].file_path+'/'+paf_doc_list[i].doc[a].file_name+'" target="_blank" >'+paf_doc_list[i].doc[a].file_name+'</a></th>'+
            //                     // '<th style="width:65%;"><a href="'+ base_url + '/test_audit/document/'+paf_doc_list[i].doc[a].file_path+'/'+paf_doc_list[i].doc[a].file_name+'" target="_blank" >'+paf_doc_list[i].doc[a].file_name+'</a></th>'+
            //                     '<td style="width:35%"><button style="margin-right:5px;margin-left:5px;" type="button" class="btn btn_blue" onclick=delete_doc(this)>Delete</button><button type="button" class="btn btn_blue" onclick=download_doc(\''+paf_doc_list[i].doc[a].file_path+'\',\''+paf_doc_list[i].doc[a].file_name+'\')>Download</button></td>'+
            //                  '</tr>';

            //             count_fixed_non_archived ++;
            //         }
                    
            //     }
                
            //     if(count_fixed_non_archived == 0)
            //     {
            //         table = '<tr><td align="center"><p style="margin-top:10px;">No document(s) to display</p></td></tr>'

            //     }
            // }
            // else
            // {
            //     if(paf_doc_list[i].doc != "")
            //     {
            //         var count_fixed_archived = 0;

            //         for(var a = 0; a < paf_doc_list[i].doc.length; a++)
            //         {
            //             if(paf_doc_list[i].doc[a].archived == 1)
            //             {
            //                 table += '<tr class="tr_paf_doc">'+
            //                             '<input type="hidden" class="doc_id" value="'+paf_doc_list[i].doc[a].id+'">'+
            //                             '<input type="hidden" class="file_name" value="'+paf_doc_list[i].doc[a].file_name+'">'+
            //                             '<th style="width:65%;"><a href="'+ base_url + '/audit/document/'+paf_doc_list[i].doc[a].file_path+'/'+paf_doc_list[i].doc[a].file_name+'" target="_blank" >'+paf_doc_list[i].doc[a].file_name+'</a></th>'+
            //                             // '<th style="width:65%;"><a href="'+ base_url + '/test_audit/document/'+paf_doc_list[i].doc[a].file_path+'/'+paf_doc_list[i].doc[a].file_name+'" target="_blank" >'+paf_doc_list[i].doc[a].file_name+'</a></th>'+
            //                             '<td style="width:35%"><button style="margin-right:5px;margin-left:5px;" type="button" class="btn btn_blue" onclick=delete_doc(this)>Delete</button><button type="button" class="btn btn_blue" onclick=download_doc(\''+paf_doc_list[i].doc[a].file_path+'\',\''+paf_doc_list[i].doc[a].file_name+'\')>Download</button></td>'+
            //                          '</tr>';

            //                 count_fixed_archived ++;
            //             }
            //         }
            //     }
            //     else
            //     {
            //         if(count_fixed_archived == 0)
            //         {
            //             table = '<tr><td align="center"><p style="margin-top:10px;">No document(s) to display</p></td></tr>'
            //         }

            //     }
            // }


			
		}
	}
	// var partner, manager, lead, assistant;

	// partner = caf_pic.partner;
	// manager = caf_pic.manager;
	// lead = caf_pic.leader;
	// assistant = caf_pic.assistant.toString();

	// table += '<tr class="tr_pic"><th style="width: 40%;"><p id="role">Partner</p></th><td style="width:60%"><p id="name">'+partner+'</p></td></tr>';
	// table += '<tr class="tr_pic"><th style="width: 40%;"><p id="role">Manager/Supervisor</p></th><td style="width:60%"><p id="name">'+manager+'</p></td></tr>';
	// table += '<tr class="tr_pic"><th style="width: 40%;"><p id="role">Team Lead</p></th><td style="width:60%"><p id="name">'+lead+'</p></td></tr>';
	// table += '<tr class="tr_pic"><th style="width: 40%;"><p id="role">Assistant(s)</p></th><td style="width:60%"><p id="name">'+assistant+'</p></td></tr>';
	// // console.log(caf_pic);
	$(table).appendTo('#paf_doc_table');

	// var modal = $("#more_pic_modal");
	// modal.find('.panel-title').text(client_name.toUpperCase() + ' - Team');


	$("#paf_doc_modal").modal("show");
}

function delete_doc(element){
	var tr 		= $(element).parent().parent();
	var doc_id = tr.find('.doc_id').val();


    bootbox.confirm({
        message: "Do you want to delete this selected info?",
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

            	
        		$.post(delete_paf_doc_url, { 'doc_id': doc_id }, function(data, status){
                if(data){
                		
    					tr.closest("tr").remove();
    					if($('#paf_doc_table tr').length < 1)
    					{
    						$('<tr><td align="center"><p style="margin-top:10px;">No document(s) to display</p></td></tr>').appendTo('#paf_doc_table');
    					}
                        location.reload();
         
                    }
                });
            
                
            }
        }
    })
}

function download_doc(file_path,file_name){


    bootbox.confirm({
        message: "Do you want to download this selected document?",
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
           	
        	 	var url = base_url + '/audit/document/'+file_path+'/' + file_name;
                // var url = base_url + '/test_audit/document/'+file_path+'/' + file_name;
                console.log(url);

                var link = document.createElement('a');
                link.href = url;
                link.download = file_name;
                link.dispatchEvent(new MouseEvent('click'));
                
            }
        }
    })
   
	
}

function show_review_point(){
    $("#review_point_section").show();
    // $("#create_paf_form").submit();
}


function edit_point(element)
{
    var tr = jQuery(element).parent().parent();
    console.log(tr);
    if(!tr.hasClass("p_editing")) 
    {
        tr.addClass("p_editing");
        tr.find('.point').removeAttr("readonly");
        tr.find($(".point_action_icon")).removeClass('fa-edit').addClass('fa-save');
        // tr.find("DIV.td").each(function()
        // {
        //     if(!jQuery(this).hasClass("family_action"))
        //     {
        //         jQuery(this).find('input[name="family_name[]"]').attr('disabled', false);
        //         jQuery(this).find('input[name="nric[]"]').attr('disabled', false);
        //         jQuery(this).find('input[name="dob[]"]').attr('disabled', false);
        //         jQuery(this).find('input[name="contact[]"]').attr('disabled', false);
        //         jQuery(this).find('input[class="attachment_proof_of_document"]').attr('disabled', false);
        //         jQuery(this).find('.attachment_proof_of_document').attr('disabled', false);
        //         jQuery(this).find("select").attr('disabled', false);
        //     } 
        //     else 
        //     {
        //         jQuery(this).find(".submit_family_info_button").text("Save");
        //     }
        // });
    } 
    else 
    {
        // var frm = $(element).closest('form');

        // var frm_serialized = frm.serialize();
        tr.find("select").attr('disabled', false);

        var formData = new FormData($(element).closest('form')[0]);
          formData.append('save_type', 'point');


        rp_info_submit(formData, tr, 'point');

    }
}


function edit_response(element)
{
    var tr = jQuery(element).parent().parent();
    if(!tr.hasClass("r_editing")) 
    {
        tr.addClass("r_editing");
        tr.find('.response').removeAttr("readonly");
        tr.find($(".response_action_icon")).removeClass('fa-edit').addClass('fa-save');
        // tr.find("DIV.td").each(function()
        // {   console.log(this);
        //     if(!jQuery(this).hasClass("family_action"))
        //     {
        //         // jQuery(this).find('input[name="point"]').attr('readonly', false);
        //         // jQuery(this).find('input[name="response"]').removeAttr("readonly");
        //         console.log(jQuery(this).find('.response'));
        //         // jQuery(this).find("select").attr('disabled', false);
        //     } 
        //     else 
        //     {
        //         // jQuery(this).find(".submit_family_info_button").text("Save");
        //     }
        // });
    } 
    else 
    {
        // var frm = $(element).closest('form');

        // var frm_serialized = frm.serialize();
        tr.find("select").attr('disabled', false);
        var formData = new FormData($(element).closest('form')[0]);
        formData.append('save_type', 'response');

        rp_info_submit(formData, tr, 'response');

    }
}


function rp_info_submit(frm_serialized, tr, type)
{

    //console.log(tr);
    $('#loadingmessage').show();
    
    $.ajax({ //Upload common input
        url: add_rp_info_url,
        type: "POST",
        data: frm_serialized,
        dataType: 'json',
        // Tell jQuery not to process data or worry about content-type
        // You *must* include these options!
        // + '&vendor_name_text=' + $(".vendor_name option:selected").text()
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            tr.find("select").attr('disabled', true);
            $('#loadingmessage').hide();
            //console.log(response.Status);
            if (response.Status === 1) {
                //var errorsDateOfCessation = ' ';
                toastr.success(response.message, response.title);
                if(response.insert_review_point_id != null)
                {
                    tr.find('input[name="review_point_id"]').attr('value', response.insert_review_point_id);
                }
                if(response.point_raise_detail != null)
                {
                    console.log(response.point_raise_detail);
                    var point_raised_data = "<p style='font-size:11px;white-space: pre-line;margin:0;'>"+response.point_raise_detail['user_name']+"\n<span style:font-size:9px;>"+response.point_raise_detail['point_raised_at'].replace(" ", " | ")+"</span></p>";
                    tr.find('.point_raised_by').html(point_raised_data);
                }
                

                if (type == "response")
                {
                    tr.find('.response').attr("readonly", true);
                    tr.find($(".response_action_icon")).removeClass('fa-save').addClass('fa-edit');
                    tr.removeClass("r_editing");
                }

                if(type == "point")
                {
                    tr.find('.point').attr("readonly", true);
                    tr.find($(".point_action_icon")).removeClass('fa-save').addClass('fa-edit');
                    tr.removeClass("p_editing");
                }
                var tr_paf_child_id = tr.find('.paf_index').val();
                // console.log(tr.find('.paf_index'));
                // $("#desc"+ tr_paf_child_id).find(".rp").show();
                check_cleared_points(tr_paf_child_id);

                // tr.find("DIV.td").each(function(){
                //     // console.log(this);
                //     if(!jQuery(this).hasClass("family_action"))
                //     {
                //         jQuery(this).find('input[name="family_id[]"]').attr('readonly', true);
                //         jQuery(this).find('input[name="family_name[]"]').attr('disabled', true);
                //         jQuery(this).find('input[name="nric[]"]').attr('disabled', true);
                //         jQuery(this).find('input[name="dob[]"]').attr('disabled', true);
                //         jQuery(this).find('input[name="contact[]"]').attr('disabled', true);
                //         jQuery(this).find('input[class="attachment_proof_of_document"]').attr('disabled', true);
                //         jQuery(this).find('.attachment_proof_of_document').attr('disabled', true);
                //         jQuery(this).find("select").attr('disabled', true);
                        
                //     } 
                //     else 
                //     {
                //         jQuery(this).find(".submit_family_info_button").text("Edit");
                //     }
                // });
                
            }
        }
    });
}

function clear_rp(element)
{
    var tr = jQuery(element).parent().parent().parent();
    // console.log($(tr).find('textarea'));
   
    var textarea = tr.find("textarea");

    if(!textarea.hasClass("rp_cleared")) 
    {
        
        // tr.find("DIV.td").each(function()

        var formData = new FormData($(element).closest('form')[0]);
        formData.append('cleared', 1);

        clear_rp_submit(formData, tr, 1, element);
    } 
    else 
    {
        // var frm = $(element).closest('form');

        // var frm_serialized = frm.serialize();
        
        var formData = new FormData($(element).closest('form')[0]);
        formData.append('cleared', 0);

        clear_rp_submit(formData, tr, 0, element);
        

    }
}

function clear_rp_submit(frm_serialized, tr, cleared, element)
{

    //console.log(tr);
    var textarea = tr.find("textarea");
    var paf_child_id = tr.find(".paf_index").val();
    $('#loadingmessage').show();
    
    $.ajax({ //Upload common input
        url: clear_rp_url,
        type: "POST",
        data: frm_serialized,
        dataType: 'json',
        // Tell jQuery not to process data or worry about content-type
        // You *must* include these options!
        // + '&vendor_name_text=' + $(".vendor_name option:selected").text()
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            // tr.find("select").attr('disabled', true);
            $('#loadingmessage').hide();
            //console.log(response.Status);
            if (response.Status === 1) {
                //var errorsDateOfCessation = ' ';
                toastr.success(response.message, response.title);
                if(cleared)
                {
                    textarea.addClass("rp_cleared");
                    jQuery(element).text("Undo");
                    // console.log(tr.find('.edit-point'));
                    var edit_icon = tr.find('.edit-point');
                    edit_icon.each(function() {
                        $( this ).addClass( "disable" );
      
                    });


                    // edit_icon.removeClass("disable");

                    // textarea.css("text-decoration" ,"line-through" );
                }
                else 
                {
                    textarea.removeClass("rp_cleared");
                    jQuery(element).text("Clear");
                    // console.log(tr.find('.edit-point'));
                    var edit_icon = tr.find('.edit-point');
                    edit_icon.each(function() {
                        $( this ).removeClass( "disable" );   
                    });

                    // edit_icon.addClass("disable");
                    // textarea.css("text-decoration" ,"none" );
                }
                check_cleared_points(paf_child_id);

                
            }

            if (response.Status === 0)
            {
                toastr.error(response.message, response.title);
            } 
        }
    });
}

    function delete_review_point(element)
    {
        var tr = jQuery(element).parent().parent().parent();

        var rp_id = tr.find('.review_point_id').val();
        var paf_child_id = tr.find(".paf_index").val();

        bootbox.confirm({
            message: "Do you wanna delete this selected info?",
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
                if (result) 
                {
                    $('#loadingmessage').show();
                    if(rp_id != undefined)
                    {
                        $.ajax({ //Upload common input
                            url: delete_review_point_url,
                            type: "POST",
                            data: {"rp_id": rp_id},
                            dataType: 'json',
                            success: function (response) {
                                $('#loadingmessage').hide();
                                if(response.Status == 1)
                                {
                                    tr.remove();
                                    toastr.success("Updated Information.", "Updated");
                                    check_cleared_points(paf_child_id);

                                }
                            }
                        });
                    }
                }
            }
        })
    }





$( document ).ready(function() {


	$('#btn_insert_sub_category').on('click', function(e)
	{
		
		var description  = $("#sub_category_modal #input_sub_desc").val();
	    var parent_id = $("#selected_parent_id").val();

	    var tr = document.getElementById(parent_id);

	    var new_sub = "";

        new_sub += '<tr class="child-tr">'+
					'<td style="width:5%;" class="child"></td>'+
					'<td style="width:40%;" class="desc"><input type="hidden" name="c_text[]" value="'+description+'">'+description+'</td>'+
					'<td align="center" style="width:15%;"> - </td>'+
					'<td align="right" style="width:40%;" class="action child-dynmc">'+
						'<input type="hidden" name="c_type[]" value="dynmc">'+
						'<a class="upload" href="javascript:void(0);" onclick="open_paf_doc_modal(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Upload" >'+
							'<span class="glyphicon glyphicon-upload" aria-hidden="true"></span>'+
						'</a>'+
						'<a class="delete-sub" href="javascript:void(0);" onclick="delete_paf(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Delete" >'+
							'<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'+
						'</a>'+
						'<a class="rename-sub" href="javascript:void(0);" onclick="rename_node(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Rename" >'+
							'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>'+
						'</a>'+						
					'</td>'+
					'<input type="hidden" name="c_id[]" value=""><input type="hidden" name="c_parent_id[]" value="'+ $(tr).find("[name ='p_id[]']").val() +'"><input type="hidden" name="c_temp_parent_id[]" value="'+ $(tr).attr('id') +'"><input type="hidden" name="c_index_no[]" value=""><input type="hidden" name="rename_flag[]" value=0>'+
				'</tr>';


	    console.log($('.parent-tr').length);
	    console.log($(".parent-tr").index( $(tr) ) + 1);

	     if(description)
	    {

		    if($('#datatable-paf .parent-tr').length == ($("#datatable-paf .parent-tr").index( $(tr) ) + 1))
		    {
		    	$(new_sub).appendTo('#datatable-paf');
		    	console.log("same");
		    }
		    else
		    {
		    	var next_parent = $("#datatable-paf .parent-tr").eq( $("#datatable-paf .parent-tr").index( $(tr) ) + 1 );
			    // var next_parent = $(tr).closest('tr').nextAll();

			    $(next_parent).before(new_sub);


			    console.log('hahaha');
			    // console.log(next_parent);
		    }
		    load_index();
		    $("#sub_category_modal").modal("hide");
		}
		else
	    {
	        alert("Description cannot be empty!");
	    }

	    
	});



	$('#btn_insert_main_category').on('click', function(e)
	{

	    var description  = $("#main_category_modal #input_main_desc").val();
	    var parent_id = $("#selected_parent_id").val();

	    var tr = document.getElementById(parent_id);


	    if(description)
	    {
	        var table = "";

	        table += '<tr class="parent-tr" >'+
						'<td style="width:5%;" class="parent"></td>'+
						'<td style="width:40%;" class="desc"><input type="hidden" name="p_text[]" value="'+description+'">'+description+'</td>'+
						'<td align="center" style="width:15%;"></td>'+
						'<td align="right" style="width:40%;" class="action parent-dynmc">'+
							'<input type="hidden" name="p_type[]" value="dynmc">'+
							'<a class="add-sub" href="javascript:void(0);" onclick="add_sub_category(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Add Sub" >'+
								'<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>'+
							'</a>'+
							'<a class="delete-main" href="javascript:void(0);" onclick="delete_paf(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Delete" >'+
								'<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'+
							'</a>'+
							'<a class="rename-main" href="javascript:void(0);" onclick="rename_node(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Rename" >'+
								'<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>'+
							'</a>'+
						'</td>'+
						'<input type="hidden" name="p_id[]" value=""><input type="hidden" name="p_form_id[]"><input type="hidden" name="p_index_no[]" >'+
					'</tr>';


	    	$(table).appendTo('#datatable-paf');
	    	load_index();

	    	$("#main_category_modal").modal("hide");
	    }
	    else
	    {
	        alert("Description cannot be empty!");
	    }


	});

	$('#btn_save_new_name').on('click', function(e)
	{

	    var new_name  = $("#rename_modal #new_name").val();
	    // var parent_id = $("#selected_parent_id").val();

	    var desc_td = $(rename_tr).find('.desc');
	    // console.log(rename_tr);
        var c_id = desc_td.find('.c_id').val();
        console.log(c_id);

	    if(new_name)
	    {
	   
	    	$(desc_td).text(new_name);
	    	// console.log($(rename_tr).find('.child'));
	    	// console.log($(rename_tr).find('.parent'));

	    	if($(rename_tr).find('.child').length > 0)
		   	{
	    		$(desc_td).append('<input type="hidden" name="c_text[]" value="'+new_name+'">');
	    		$(rename_tr).find('[name ="rename_flag[]"]').val(1);
                
                $(desc_td).append('<div class="review_point_icon">'+
                                    '<a href="javascript:void(0)" class="no_doc"><img src="'+php_base_url+'img/X_icon.png"  style="width:25px;height:25px;"></a> '+
                                    '<a href="#review_point_section" class="rp view_rp"><img src="'+php_base_url+'img/R_icon2.png"  style="width:25px;height:25px;"></a> '+
                                    '<a href="#review_point_section" class="cleared_rp view_rp"><img src="'+php_base_url+'img/R_strike_icon2.png"  style="width:25px;height:25px;"></a> '+
                                    '<input type="hidden" class="c_id" value="'+ c_id +'">'+
                                  '</div>');
                                                                  
	    		console.log("child_tr");
                for (var k = 0; k < paf_no_doc_list.length; k++) {
                    $("#desc" + paf_no_doc_list[k]).find(".no_doc").show();
                }

                check_cleared_points(c_id);
	    	}

	    	if($(rename_tr).find('.parent').length > 0)
		   	{
	    		$(desc_td).append('<input type="hidden" name="p_text[]" value="'+new_name+'">');
	    		console.log("parent_tr");
	    	}


	    	$("#rename_modal").modal("hide");
	    }
	    else
	    {
	        alert("New name cannot be empty!");
	    }


	});

	$('#savePaf').on('click', function(e)
	{
		$("#create_paf_form").submit();
		console.log('submit');
	});

    $('#hide_rp_btn').on('click', function(e)
    {
        $("#review_point_section").hide();
        // console.log('submit');
    });



	$( "#upload_paf_doc_btn" ).click(function() {
	    $("#multiple_paf_doc").fileinput('upload');
	});

	$("#multiple_paf_doc").fileinput({
	    theme: 'fa',
	    uploadUrl: '/audit/paf_upload/uploadPafDoc', // you must set a valid URL here else you will get an error
        // uploadUrl: '/test_audit/paf_upload/uploadPafDoc', // you must set a valid URL here else you will get an error
	    uploadAsync: false,
	    'async' : false,
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
	    // initialPreviewDownloadUrl: base_url + '/audit/uploads/bank_images_or_pdf/{filename}',
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

	        
	        var fileNames = data.response;

	        // uploadedFiles.id = current_index;
	        // uploadedFiles.files = fileNames;
	        if (uploadedFiles.hasOwnProperty(current_index)) {
		        uploadedFiles[current_index] = uploadedFiles[current_index].concat(fileNames);
		    } else {
		        uploadedFiles[current_index] = fileNames;
		    }

	        // uploadedFiles[current_index] = fileNames;
	        // ref.create_node(sel, { text: "fileNames[i]", type: 'doc', data:{"id":""}, icon: 'glyphicon glyphicon-file' });


	        console.log(uploadedFiles);
	        // console.log(fileNames);
	        console.log("success");
            $("#upload_paf_doc_modal").modal("hide")
	        toastr.success("Information Updated", "Success");
	    
	    
	    //console.log(data);
	});


	// console.log(uploadedFiles);

	$("#create_paf_form").submit(function(e) {
        var form = $(this);
        console.log(JSON.stringify(uploadedFiles));

        $.ajax({
           type: "POST",
           url: submit_paf_list_url,
           data: form.serialize() + '&uploaded_docs=' + JSON.stringify(uploadedFiles), // serializes the form's elements.
           dataType: "JSON",
           // data : { 'uploaded_docs' : JSON.stringify(uploadedFiles)},
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			location.reload();
           		}
               // alert(data); // show response from the php script.
               // $('#applicant_resume').fileinput('upload');
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    

    // Review point table
    if(review_point_info)
    {
        $count_review_point_info = review_point_info.length - 1;
    }
    else
    {
        $count_review_point_info = 0;
    }
    $(document).on('click',"#add_rp_line",function() 
    {
        var company_code = $('#company_code').val();

        $count_review_point_info++;
        $a=""; 
        $a += '<form class="tr r_editing p_editing sort_id" method="post" name="form'+$count_review_point_info+'" id="form'+$count_review_point_info+'" >';

        $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control review_point_id" name="review_point_id" value=""/></div>';

        $a += '<div class="td">';
        $a += '<select class="form-control paf_index" style="width: 100%;" name="paf_index" id="paf_index'+$count_review_point_info+'"><option value="0">Select Index</option></select>';
        // $a += '<input type="hidden" class="form-control" name="paf_index" value="'+review_point_info[i]["paf_child_id"]+'"/>';
        $a += '</div>';

        // $a += '<div class="td">';
        // $a += '<select class="form-control paf_no" style="width: 100%;" name="paf_no" id="event"><option value="0"></option></select>';
        // $a += '</div>';

        $a += '<div class="td">';
        $a += '<textarea style="width:90%;display:inline-block;" name="point" class="form-control point" value="" />';
        $a += '<a class="edit-point pull-right " href="javascript:void(0);" onclick="edit_point(this)" data-toggle="tooltip" data-trigger="hover" style="height:30px;font-weight:bold;width:10%;text-align: center;" data-original-title="Save" tabindex="-1" onfocus="this.blur()">'+
                '<i class="far fa-save point_action_icon" style="font-size:20px;"></i>'+
              '</a>';
        $a += '</div>';

        $a += '<div class="td">';
        $a += '<textarea style="width:90%;display:inline-block;" name="response" class="form-control response" value="" />';
        $a += '<a class="edit-point pull-right" href="javascript:void(0);" onclick="edit_response(this)" data-toggle="tooltip" data-trigger="hover" style="height:30px;font-weight:bold;width:10%;text-align: center;" data-original-title="Save" tabindex="-1" onfocus="this.blur()">'+
                '<i class="far fa-save response_action_icon" style="font-size:20px;"></i>'+
              '</a>';
        $a += '</div>';

        $a += '<div class="td">';
        $a += '<div class="point_raised_by text-center"></div>'
        $a += '</div>';

        $a += '<div class="td text-center">';
        $a += '<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn_blue submit_event_info_button" onclick="delete_review_point(this);">Delete</button></div><div style="display: inline-block;"><button type="button" class="btn btn_blue" onclick="clear_rp(this);">Clear</button></div>';
        $a += '</div>';

        // if(Admin || Manager)
        // {
        //     $a += '<div class="td event_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn_blue submit_event_info_button" onclick="edit_event(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn_blue" onclick="delete_event_info(this);">Delete</button></div></div>';
        // } 

        $a += '</form>';
        
        $("#review_point_info").prepend($a); 

        // $('#form'+$count_event_info).find(".employee_id").val($("#staff_id").val());

        // var date = new Date();
        // var today = new Date(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate());
        // $('#form'+$count_event_info).find(".eventDate").val(moment(today).format('DD MMMM YYYY'));


        $("#loadingmessage").show();
        $.ajax({
            type: "POST",
            url: get_paf_index_url,
            data: '&company_code=' + company_code,
            async: false,
            dataType: "json",
            success: function(data){
                // console.log(data);
                $("#loadingmessage").hide();
                $.each(data, function(key, val) {
                    var option = $('<option />');
                    option.attr('value', val.id).text(val.index_no);
                    $('#form'+$count_review_point_info).find(".paf_index").append(option);
                    $('#paf_index'+$count_review_point_info).select2();
              
                });

                //$('#form'+$count_family_info).find(".relationship"+$count_family_info).select2();
            }
        });

        // $('#form'+$count_event_info).find('.datepicker').datepicker({
        //     format: 'dd MM yyyy',
        // });
    });

    $('#search_indexno').change(function() {
        // console.log("changeeee");
        $('#loadingmessage').css('display', 'block');
        var paf_child_id     = $("#search_indexno").val();
        var cleared    = $("#search_cleared").val();
        
        // console.log(company_code);
        $.ajax({
            type: "POST",
            url: filter_review_points_url,
            data: '&paf_child_id=' + paf_child_id + '&cleared=' + cleared + '&company_code=' + company_code, // <--- THIS IS THE CHANGE
            async: false,
            dataType: "json",
            success: function(response){
                $('#loadingmessage').hide();
                $(".sort_id").remove();

                review_point_info = response;
                get_review_points();
            }
        });
    });

    $('#search_cleared').change(function() {
        $('#loadingmessage').css('display', 'block');
        var paf_child_id     = $("#search_indexno").val();
        var cleared    = $("#search_cleared").val();

        //console.log($('#loadingmessage'));
        $.ajax({
            type: "POST",
            url: filter_review_points_url,
            data: '&paf_child_id=' + paf_child_id + '&cleared=' + cleared + '&company_code=' + company_code, // <--- THIS IS THE CHANGE
            async: false,
            dataType: "json",
            success: function(response){
                $('#loadingmessage').hide();
                $(".sort_id").remove();

                review_point_info = response;
                get_review_points();
            }
        });
    });

    get_review_points();

    function get_review_points()
    {

        if(review_point_info)
        {
            var company_code = $('#company_code').val();

            // console.log(review_point_info);
            for(var i = 0; i < review_point_info.length; i++)
            {
                // if(!$("#desc"+ review_point_info[i]["paf_child_id"]).find(".cleared_rp:visible").length > 0)
                // {
                //     $("#desc"+ review_point_info[i]["paf_child_id"]).find(".rp").show();
                // }
                
                var point_raised_data = "<p style='font-size:11px;white-space: pre-line;margin:0;'>"+review_point_info[i]['raised_user_name']+"\n<span style:font-size:9px;>"+review_point_info[i]['point_raised_at'].replace(" ", " | ")+"</span></p>";
                var clear_btn_text = "Clear";
                var rp_cleared_class = "";
                var rp_disable_edit = "";
                if(review_point_info[i]["cleared"] == 1)
                {
                    clear_btn_text = "Undo";
                    rp_cleared_class = "rp_cleared";
                    rp_disable_edit = "disable";
                }

                $a=""; 
                $a += '<form class="tr sort_id" method="post" name="form-rp'+i+'" id="form-rp'+i+'" data-indexno="'+review_point_info[i]["index_no"]+'" data-point="'+review_point_info[i]["cleared"]+review_point_info[i]["point"]+'" data-response="'+review_point_info[i]["cleared"]+' '+review_point_info[i]["response"]+'" data-raisedby="'+review_point_info[i]['raised_user_name']+' '+review_point_info[i]['point_raised_at']+'" >';

                $a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
                $a += '<div class="hidden"><input type="text" class="form-control review_point_id" name="review_point_id" value="'+review_point_info[i]["id"]+'"/></div>';

                $a += '<div class="td">';
                // $a += review_point_info[i]["index_no"]
                $a += '<select class="form-control paf_index" style="width: 100%;" name="paf_index" id="paf_index'+i+'" disabled><option value="'+review_point_info[i]["paf_child_id"]+'">'+review_point_info[i]["index_no"]+'</option></select>';
                // $a += '<input type="hidden" class="form-control" name="paf_index" value="'+review_point_info[i]["paf_child_id"]+'"/>';
                $a += '</div>'; 

                // $a += '<div class="td">';
                // $a += '<select class="form-control paf_no" style="width: 100%;" name="paf_no" id="event"><option value="0"></option></select>';
                // $a += '</div>';

                $a += '<div class="td">';
                $a += '<textarea style="width:90%;display:inline-block;" name="point" class="form-control point '+rp_cleared_class+'" value="" readonly>'+review_point_info[i]["point"]+'</textarea>';
                $a += '<a class="edit-point pull-right '+rp_disable_edit+'" href="javascript:void(0);" onclick="edit_point(this)" data-toggle="tooltip" data-trigger="hover" style="height:30px;font-weight:bold;width:10%;text-align: center;" data-original-title="Save" tabindex="-1" onfocus="this.blur()">'+
                        '<i class="far fa-edit point_action_icon" style="font-size:20px;"></i>'+
                      '</a>';
                $a += '</div>';

                $a += '<div class="td">';
                $a += '<textarea style="width:90%;display:inline-block;" name="response" class="form-control response '+rp_cleared_class+'" value="" readonly>'+review_point_info[i]["response"]+'</textarea>';
                $a += '<a class="edit-point pull-right '+rp_disable_edit+'" href="javascript:void(0);" onclick="edit_response(this)" data-toggle="tooltip" data-trigger="hover" style="height:30px;font-weight:bold;width:10%;text-align: center;" data-original-title="Save" tabindex="-1" onfocus="this.blur()">'+
                        '<i class="far fa-edit response_action_icon" style="font-size:20px;"></i>'+
                      '</a>';
                $a += '</div>';

                $a += '<div class="td">';
                $a += '<div class="point_raised_by text-center">'+point_raised_data+'</div>'
                $a += '</div>';

                $a += '<div class="td text-center">';
                $a += '<div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn_blue submit_event_info_button" onclick="delete_review_point(this);">Delete</button></div><div style="display: inline-block;"><button type="button" class="btn btn_blue" onclick="clear_rp(this);">'+clear_btn_text+'</button></div>';
                $a += '</div>';

                // if(Admin || Manager)
                // {
                //     $a += '<div class="td event_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn_blue submit_event_info_button" onclick="edit_event(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn_blue" onclick="delete_event_info(this);">Delete</button></div></div>';
                // } 

                $a += '</form>';

                $("#review_point_info").prepend($a); 
                $('#paf_index'+i).select2();
            }
        }
    }

    /////////////////////////////////////REVIEW POINT TABLE SORTING/////////////////////////////////////
    $('#id_index')
        .wrapInner('<span title="sort this column"/>')
        .each(function(){
            
            var th = $(this),
                thIndex = th.index(),
                inverse = false;
            
            th.click(function(){
                //console.log($("#body_officer").find('.sort_id'));
                if(indexno_asc)
                {
                    $("#review_point_info").each(function(){
                        $(this).html($(this).find('.sort_id').sort(function(a, b){
                            return ($(b).data('indexno').toString().toLowerCase()) < ($(a).data('indexno').toString().toLowerCase()) ? 1 : -1;
                        }));
                    });

                    indexno_asc = false;
                }
                else
                {
                    $("#review_point_info").each(function(){
                        $(this).html($(this).find('.sort_id').sort(function(a, b){
                            return ($(b).data('indexno').toString().toLowerCase()) < ($(a).data('indexno').toString().toLowerCase()) ? -1 : 1;
                        }));
                    });

                    indexno_asc = true;
                }
                // $('#officer_date_of_appointment').datepicker({ 
                //     dateFormat:'dd/mm/yyyy',
                // }).datepicker('setStartDate', latest_incorporation_date); 

                // $('#date_of_cessation').datepicker({ 
                //     dateFormat:'dd/mm/yyyy',
                // }).datepicker('setStartDate', latest_incorporation_date);
            });
                
        });

        $('#id_point')
        .wrapInner('<span title="sort this column"/>')
        .each(function(){
            
            var th = $(this),
                thIndex = th.index(),
                inverse = false;
            
            th.click(function(){
                //console.log($("#body_officer").find('.sort_id'));
                if(point_asc)
                {
                    $("#review_point_info").each(function(){
                        $(this).html($(this).find('.sort_id').sort(function(a, b){
                            return ($(b).data('point').toString().toLowerCase()) < ($(a).data('point').toString().toLowerCase()) ? 1 : -1;
                        }));
                    });

                    point_asc = false;
                }
                else
                {
                    $("#review_point_info").each(function(){
                        $(this).html($(this).find('.sort_id').sort(function(a, b){
                            return ($(b).data('point').toString().toLowerCase()) < ($(a).data('point').toString().toLowerCase()) ? -1 : 1;
                        }));
                    });

                    point_asc = true;
                }
                // $('#officer_date_of_appointment').datepicker({ 
                //     dateFormat:'dd/mm/yyyy',
                // }).datepicker('setStartDate', latest_incorporation_date); 

                // $('#date_of_cessation').datepicker({ 
                //     dateFormat:'dd/mm/yyyy',
                // }).datepicker('setStartDate', latest_incorporation_date);
            });
                
        });

        $('#id_response')
        .wrapInner('<span title="sort this column"/>')
        .each(function(){
            
            var th = $(this),
                thIndex = th.index(),
                inverse = false;
            
            th.click(function(){
                //console.log($("#body_officer").find('.sort_id'));
                if(response_asc)
                {
                    $("#review_point_info").each(function(){
                        $(this).html($(this).find('.sort_id').sort(function(a, b){
                            return ($(b).data('response').toLowerCase()) < ($(a).data('response').toLowerCase()) ? 1 : -1;
                        }));
                    });

                    response_asc = false;
                }
                else
                {
                    $("#review_point_info").each(function(){
                        $(this).html($(this).find('.sort_id').sort(function(a, b){
                            return ($(b).data('response').toLowerCase()) < ($(a).data('response').toLowerCase()) ? -1 : 1;
                        }));
                    });

                    response_asc = true;
                }
                // $('#officer_date_of_appointment').datepicker({ 
                //     dateFormat:'dd/mm/yyyy',
                // }).datepicker('setStartDate', latest_incorporation_date); 

                // $('#date_of_cessation').datepicker({ 
                //     dateFormat:'dd/mm/yyyy',
                // }).datepicker('setStartDate', latest_incorporation_date);
            });
                
        });

        $('#id_raisedby')
        .wrapInner('<span title="sort this column"/>')
        .each(function(){
            
            var th = $(this),
                thIndex = th.index(),
                inverse = false;
            
            th.click(function(){
                //console.log($("#body_officer").find('.sort_id'));
                if(raisedby_asc)
                {
                    $("#review_point_info").each(function(){
                        $(this).html($(this).find('.sort_id').sort(function(a, b){
                            return ($(b).data('raisedby')) < ($(a).data('raisedby')) ? 1 : -1;
                        }));
                    });

                    raisedby_asc = false;
                }
                else
                {
                    $("#review_point_info").each(function(){
                        $(this).html($(this).find('.sort_id').sort(function(a, b){
                            return ($(b).data('raisedby')) < ($(a).data('raisedby')) ? -1 : 1;
                        }));
                    });

                    raisedby_asc = true;
                }

            });
                
        });


        $('.view_rp').on('click', function(e)
        {
            $("#review_point_section").show();
            
            var div = $(this).parent();
            var paf_child_id = div.find('.c_id').val();
            
            $("#search_indexno").val(paf_child_id);

            $("#search_indexno").trigger("change");
        });





});


