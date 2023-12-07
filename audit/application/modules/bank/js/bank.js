
	$(document).on('click',".edit_bank",function(){
	    var edit_bank_id =  $(this).data("id");
	 
	    for(var i = 0; i < bank_list.length; i++)
	    {
	    	if(bank_list[i]["id"] == edit_bank_id)
	    	{
	    		$(".bank_list_id").val(bank_list[i]["id"]);
    			$("#bank_name_for_user").val(bank_list[i]["bank_name_for_user"]);
    			$("#bank_name").val(bank_list[i]["bank_name"]);
    			$("#add_line1").val(bank_list[i]["add_line1"]);
    			$("#add_line2").val(bank_list[i]["add_line2"]);
    			$("#add_line3").val(bank_list[i]["add_line3"]);
	    	}
	    }
	});

	$(document).on('click',".edit_bank_confirm_setting",function(){
	    var edit_bank_confirm_setting_id =  $(this).data("id");
	    
	    // console.log(bank_confirm_setting);
	 
	    for(var i = 0; i < bank_confirm_setting.length; i++)
	    {
	    	if(bank_confirm_setting[i]["setting_id"] == edit_bank_confirm_setting_id)
	    	{
	    		// console.log("match");

	    		$(".bank_confirm_setting_list_id").val(bank_confirm_setting[i]["setting_id"]);
    			$(".confirm_month").val(bank_confirm_setting[i]["confirm_month"]);
    			var cn = new Client();
	    		cn.getPicName(bank_confirm_setting[i]["pic_id"]);
	    		$('.confirm_month').prop("readonly", true);
	    		$('.confirm_month').prop('style', '');
	    		$('.confirm_month').datepicker('remove');
	    	}
	    }
	
	});

	$(document).on('click',"#move_paf_btn",function(){
	    console.log(selected_move_auth);
	    $.post( "bank/move_auth_to_paf", { 'selected_move_auth': selected_move_auth }, function() {
		  	alert( "success" );
		  	window.location.href = bank_url;
		})
	});

	//clearing delete session when user press cancel 
	$(document).on('click',".ba_upload_cancel",function(){
	    $.post("bank/clear_delete_auth_session");
	});

	$(document).on('click',".bc_upload_cancel",function(){
	    $.post("bank/clear_delete_confirm_session");
	});
	//$('.edit_type_of_leave').live("click",function(){

	$('[data-toggle="tooltip"]').tooltip();

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	$( document ).ready(function() {
		$('.confirm_month').datepicker({
		    autoclose: true,
		    minViewMode: 1,
		    format: 'MM yyyy'
		}).on("show", function(event) {

		  	var year = $("th.datepicker-switch").eq(1).text();  // there are 3 matches

		  	$(".month").each(function(index, element) {
			    var el = $(element);
			    
			    var hideMonth = $.grep(monthToDisable, function( n, i ) {
				                  return n.substr(4, 4) == year && n.substr(0, 3) == el.text();
				                });

			    if (hideMonth.length)
			      el.addClass('disabled');

		    
			});
		});
	});
    


	$('.bc_month_filter').datepicker({
	    autoclose: true,
	    minViewMode: 1,
	    format: 'MM yyyy'
	});

	

	$('.datepicker').datepicker({
		format: 'dd MM yyyy',
	});


	function disableSpecificWeekDays(date) {

	    var month = date.getMonth();
	    if ($.inArray(month, monthsToDisable) != -1) {
	        return [false];
	    }
	    return [true];
	}

	// $('.carry_forward_period_datepicker').datepicker({
	// 	format: 'dd MM',
	// 	//viewMode: 'months'
	// 	// minViewMode: 'months',
	// 	// maxViewMode: 'months',
	// }).on('show', function() {
	//     // remove the year from the date title before the datepicker show
	//     var dateText  = $(".datepicker-days .datepicker-switch").text();
	//     var dateTitle = dateText.substr(0, dateText.length - 5);
	//     $(".datepicker-days .datepicker-switch").text(dateTitle);
	// });

	// var userTarget1 = "";
	// var exit1 = false;
	// $('.leave_cycle_daterange').datepicker({
	//   format: "dd MM",
	//   //weekStart: 1,
	//   language: "en",
	//   //daysOfWeekHighlighted: "0,6",
	//   //startDate: "01/01/2017",
	//   orientation: "bottom auto",
	//   autoclose: true,
	//   showOnFocus: true,
	//   //maxViewMode: 'days',
	//   keepEmptyValues: true,
	//   // templates: {
	//   //   leftArrow: '&lt;',
	//   //   rightArrow: '&gt;'
	//   // }
	// }).on('show', function() {
	//     // remove the year from the date title before the datepicker show
	//     var dateText  = $(".datepicker-days .datepicker-switch").text();
	//     var dateTitle = dateText.substr(0, dateText.length - 5);
	//     $(".datepicker-days .datepicker-switch").text(dateTitle);
	// });


	if(active_tab != null)
	{  
		pv_index_tab_aktif = active_tab;

	    if(active_tab != "bank_confirm")
	    {
	        $('li[data-information="'+active_tab+'"]').addClass("active");
	        $('#w2-'+active_tab+'').addClass("active");
	        $('li[data-information="bank_confirm"]').removeClass("active");
	        $('#w2-bank_confirm').removeClass("active");
	    }
	    // console.log(active_tab);
	    if(active_tab =="bank_auth")
	    {
	    	$(".create_bank_authorisation").show();
	    }
	    else{
	    	$(".create_bank_authorisation").hide();
	    }

	    if(active_tab =="bank_confirm")
	    {
	    	$(".create_bank_confirmation").show();
	    }
	    else{
	    	$(".create_bank_confirmation").hide();
	    }
	}

	$(document).on('click',".check_state",function(){
		pv_index_tab_aktif = $(this).data("information")

		if(pv_index_tab_aktif =="bank_auth")
	    {
	    	$(".create_bank_authorisation").show();
	    }
	    else{
	    	$(".create_bank_authorisation").hide();
	    }

	    if(pv_index_tab_aktif =="bank_confirm")
	    {
	    	$(".create_bank_confirmation").show();
	    }
	    else{
	    	$(".create_bank_confirmation").hide();
	    }
	});

	(function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('.datatable-bank').DataTable({
				
	            "order": [[ 0, 'asc' ]]
			});

			var table2 = $('.datatable-confirm-setting').DataTable({
				
	            "order": [[ 0, 'desc' ]]
			});

		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);


	$("#bank_list_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "bank/submit_bank_list",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			window.location.href = bank_url;
           		}
               // alert(data); // show response from the php script.
               // $('#applicant_resume').fileinput('upload');
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });

	$("#bank_confirm_setting_submit").submit(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "bank/submit_bank_confirm_setting",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		if(data){
           			toastr.success('Information Updated', 'Updated');
           			window.location.href = bank_url;
           		}
               // alert(data); // show response from the php script.
               // $('#applicant_resume').fileinput('upload');
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    function generate_auth(element)
    {
    	var div 		= $(element).parent();
    	var auth_id 	= div.find('.auth_id').val();

    	$('#loadingMessage').show();
    	// console.log(auth_id);

    	$.ajax({ //Upload common input
	      url		: "bank/generate_auth_document",
	      type 		: "POST",
	      dataType	: 'json',
	      data 	: {"auth_id": auth_id},
	      beforeSend: function()
		    {
		        $('#loadingMessage').show();
		    },
	      success	: function (response,data) {
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

    function generate_confirm(element)
    {
    	var div 		= $(element).parent();
    	var confirm_id 	= div.find('.confirm_id').val();

    	$('#loadingMessage').show();
    	// console.log(auth_id);

    	$.ajax({ //Upload common input
	      url		: "bank/generate_confirm_document",
	      type 		: "POST",
	      dataType	: 'json',
	      data 	: {"confirm_id": confirm_id},
	      beforeSend: function()
		    {
		        $('#loadingMessage').show();
		    },
	      success	: function (response,data) {
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


    function delete_bank(element){
    	var div 		= $(element).parent();
    	var bank_id 	= div.find('.bank_id').val();
    	//console.log(holiday_id);
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
	        		$.post("bank/delete_bank", { 'bank_id': bank_id }, function(data, status){
			    	 	if(data){
			    	 		window.location.href = bank_url;
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    function delete_bank_auth(element){
    	var div 		= $(element).parent();
    	var bank_auth_id 	= div.find('.auth_id').val();
    	//console.log(holiday_id);
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
	        		$.post("bank/delete_bank_auth", { 'bank_auth_id': bank_auth_id }, function(data, status){
			    	 	if(data){
			    	 		window.location.href = bank_url;
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    function delete_confirm_setting(element){
    	var div 		= $(element).parent();
    	var bank_confirm_setting_id 	= div.find('.bank_confirm_setting_id').val();
    	//console.log(holiday_id);
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
	        		$.post("bank/delete_bank_confirm_setting", { 'bank_confirm_setting_id': bank_confirm_setting_id }, function(data, status){
			    	 	if(data){
			    	 		window.location.href = bank_url;
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    function delete_bank_confirm(element){
    	var div 		= $(element).parent();
    	var bank_confirm_id 	= div.find('.confirm_id').val();
    	//console.log(holiday_id);
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
	        		$.post("bank/delete_bank_confirm", { 'bank_confirm_id': bank_confirm_id }, function(data, status){
			    	 	if(data){
			    	 		window.location.href = bank_url;
			    	 	}
			    	});
	        	}
	        }
	    })
    }

    //Activate bank authorization
    function update_auth(element, active){
    	var div 		= $(element).parent();
    	var bank_auth_id 	= div.find('.auth_id').val();
    	//console.log(holiday_id);

		if(active){
			msg = "Do you want to activate this selected info?"
		}
		else
		{
			msg = "Do you want to deactivate this selected info?"
		}
    	bootbox.confirm({
	        message: msg,
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
	        		$.post("bank/update_bank_auth", { 'bank_auth_id': bank_auth_id , 'active': active, 'is_bank': true}, function(data, status){
			    	 	if(data){
			    	 		window.location.href = bank_url;
			    	 	}
			    	});
	        	}
	        }
	    })
    }


    $('.auth_status').on('select2:selecting', function (evt) {
	  	previous_auth = $(this).val();
	  	// previous_auth = evt.params.data.;
	  	previous_auth_text = $(this).select2('data');

	  	// console.log("previous val "+previous_auth_text[0].text);
	});

	$('.confirm_status').on('select2:selecting', function (evt) {
	  	previous_confirm = $(this).val();
	  	previous_confirm_text = $(this).select2('data');

	  	// console.log("selecting"+previous_confirm);
	});

    // Update Status
	// function change_auth_status(element,e){
	// 	if(e.originalEvent)
	// 	{
	$(document).on('change',".auth_status",function(e){
		//console.log($(this).parent());
		var dropdown = $(this);
		var div 		   = $(this).parent();
		var tr = div.parent().parent();
    	var bank_auth_id   = div.find('.auth_id').val();
    	var upload_btn = tr.find('.ba_upload_btn');


    	// var assignment_id 		= div.find('.assignment_id').val();
    	var status_id 			= div.find('.auth_status').val();
    	// console.log($(element));

    	bootbox.confirm({
	        message: "Do you want to change this status info?",
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
	        	if(result == true)
	        	{

        			

		    		if(status_id != 2){
	        			$.post("bank/updt_auth_status", { 'bank_auth_id': bank_auth_id , 'status_id': status_id}, function(data, status){
			    	 		if(data){
			    	 			window.location.href = bank_url;
			    	 		}
			    		});
	        		}
	        		else
	        		{
	        			$("#upload_ba_doc_modal").find('.ba_upload_cancel').hide();
	        			open_ba_doc_modal(upload_btn);
	        			change_ba_status_flag = true;


	        		}



	       
	        		$('.modal').on("hidden.bs.modal", function (e) { 
					    if ($('.modal:visible').length) { 
					        $('body').addClass('modal-open');
					    }
					});
	        	}
	        	else
	        	{

	      
					dropdown.select2('destroy');
					dropdown.val(previous_auth);
					dropdown.select2();

	        	}
	        }
	    })
	});
			

		//}
    	
    //}
    function open_ba_doc_modal(element){
    	initialPreviewArray = []; 
		initialPreviewConfigArray = [];
		bank_auth_files = "";

    	// $("#new_client").css("display","inline-block !important");
	    // $("#edit_client").css("display","none");

	    // $("#tr_input_EC_date").removeClass("hidden");
	    // document.getElementById('Expected_Completion').removeAttribute('disabled');
	    var div 	= $(element).parent();
	    var ba_id = div.find('.auth_id').val();
	 
    	$("#upload_ba_doc_modal").modal("show"); 
    	// $('#loadingMessage').show();
    	// console.log(auth_id);

    	$.ajax({ //Upload common input
			url		: "bank/get_bank_auth_doc_list",
			type 		: "POST",
			dataType	: 'json',
			data 	: {"auth_id": ba_id},
			beforeSend: function()
		    {
		        $('#loadingMessage').show();
		    },
	        success	: function (response) {
		        $('#loadingMessage').hide();
		        //console.log(response.pdf_link);
		        bank_auth_files = response[0].file_names;
		        // console.log(response);

		        for(var i = 0; i < bank_auth_list.length; i++)
			    {
			    	if(bank_auth_list[i]["id"] == ba_id)
			    	{
			    		$('.doc_ba_id').val(ba_id);
			    		$('#ba_client').val(bank_auth_list[i]["company_name"]);
			    		$('#ba_bank').val(bank_auth_list[i]["bank_name"]);
			    		// console.log(bank_auth_list[i]);

			    	}

				}

				// console.log(all_bank_auth_files);

				// for(var a = 0; a < all_bank_auth_files.length; a++)
			 //    {
			 //    	// console.log(all_bank_auth_files[a].bank_auth_id + "yoooooooo" + ba_id);
			 //    	if(all_bank_auth_files[a].bank_auth_id == ba_id)
			 //    	{
			 //    		bank_auth_files = all_bank_auth_files[a].file_names;
			    		
			 //    	}

			 //    }

			    // console.log(bank_auth_files_1);
			    // console.log(bank_auth_files);

			    if(bank_auth_files != null)
				{
					for (var i = 0; i < bank_auth_files.length; i++) {
						
					  var url = base_url + "/audit/document/bank_auth/";
					  // var url = base_url + "/test_audit/document/bank_auth/";
					  var fileArray = bank_auth_files[i].split(',');
					  //console.log(fileArray[0]);
					  initialPreviewArray.push( url + fileArray[1] );
					  var file_type = fileArray[1].substring(fileArray[1].lastIndexOf('.'));
					  //console.log(file_type);
					  	if(file_type == ".pdf" || file_type == ".PDF")
					  	{
						  initialPreviewConfigArray.push({
							  type: "pdf",
						      caption: fileArray[1],
						      url: "/audit/bank/deleteBaFile/" + fileArray[0],
						      // url: "/test_audit/bank/deleteBaFile/" + fileArray[0],
						      width: "120px",
						      key: i+1
						  });
						}
						else
						{
							initialPreviewConfigArray.push({
						      caption: fileArray[1],
						      url: "/audit/bank/deleteBaFile/" + fileArray[0],
						      // url: "/test_audit/bank/deleteBaFile/" + fileArray[0],
						      width: "120px",
						      key: i+1
						  });
						}
					}

					console.log(initialPreviewArray);
				}
				$('#multiple_ba_doc').fileinput('destroy');

				$("#multiple_ba_doc").fileinput({
					'async' : false,
				    theme: 'fa',
				    uploadUrl: '/audit/bank/uploadBaDoc', // you must set a valid URL here else you will get an error
				    // uploadUrl: '/test_audit/bank/uploadBaDoc',
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
				    initialPreviewDownloadUrl: base_url + '/audit/document/bank_auth/{filename}',
				     // initialPreviewDownloadUrl: base_url + '/test_audit/document/bank_auth/{filename}',
				    initialPreview: initialPreviewArray,
				 	initialPreviewConfig: initialPreviewConfigArray,
				 	// deleteUrl: "/audit/bank/deleteBaFile",
				 	// maxFileSize: 20000048,
				 	// maxImageWidth: 1000,
				  //   maxImageHeight: 1500,
				  //   resizePreference: 'height',
				  //   resizeImage: true,
				 	// purifyHtml: true,// this by default purifies HTML data for preview
				    /*uploadExtraData: { 
				    	officer_id: $('input[name="officer_id"]').val() 
				    }*/
				    /*width:auto;height:auto;max-width:100%;max-height:100%;*/
				    uploadExtraData: function() {
			            return {
			                ba_id: $(".doc_ba_id").val()
			                // username: $("#username").val()
			            };
			        }

				});
	        }
	    })

	}

	function open_bc_doc_modal(element){
    	initialPreviewArray = []; 
		initialPreviewConfigArray = [];
		bank_confirm_files = "";

    	// $("#new_client").css("display","inline-block !important");
	    // $("#edit_client").css("display","none");

	    // $("#tr_input_EC_date").removeClass("hidden");
	    // document.getElementById('Expected_Completion').removeAttribute('disabled');
	    var div 	= $(element).parent();
	    var bc_id = div.find('.confirm_id').val();
	 
    	$("#upload_bc_doc_modal").modal("show"); 
    	// $('#loadingMessage').show();
    	// console.log(auth_id);

    	$.ajax({ //Upload common input
			url		: "bank/get_bank_confirm_doc_list",
			type 		: "POST",
			dataType	: 'json',
			data 	: {"confirm_id": bc_id},
			beforeSend: function()
		    {
		        $('#loadingMessage').show();
		    },
	        success	: function (response) {
		        $('#loadingMessage').hide();
		        //console.log(response.pdf_link);
		        bank_confirm_files = response[0].file_names;
		        // console.log(response);

		        for(var i = 0; i < bank_confirm_list.length; i++)
			    {
			    	if(bank_confirm_list[i]["id"] == bc_id)
			    	{
			    		$('.doc_bc_id').val(bc_id);
			    		$('#bc_client').val(bank_confirm_list[i]["company_name"]);
			    		$('#bc_bank').val(bank_confirm_list[i]["bank_name"]);
			    		// console.log(bank_auth_list[i]);

			    	}

				}

				// console.log(all_bank_auth_files);

				// for(var a = 0; a < all_bank_auth_files.length; a++)
			 //    {
			 //    	// console.log(all_bank_auth_files[a].bank_auth_id + "yoooooooo" + ba_id);
			 //    	if(all_bank_auth_files[a].bank_auth_id == ba_id)
			 //    	{
			 //    		bank_auth_files = all_bank_auth_files[a].file_names;
			    		
			 //    	}

			 //    }

			    // console.log(bank_auth_files_1);
			    // console.log(bank_auth_files);

			    if(bank_confirm_files != null)
				{
					for (var i = 0; i < bank_confirm_files.length; i++) {
						
					  var url = base_url + "/audit/uploads/bank_images_or_pdf/";
					  // var url = base_url + "/test_audit/uploads/bank_images_or_pdf/";
					  var fileArray = bank_confirm_files[i].split(',');
					  //console.log(fileArray[0]);
					  initialPreviewArray.push( url + fileArray[1] );
					  var file_type = fileArray[1].substring(fileArray[1].lastIndexOf('.'));
					  //console.log(file_type);
					  	if(file_type == ".pdf" || file_type == ".PDF")
					  	{
						  initialPreviewConfigArray.push({
							  type: "pdf",
						      caption: fileArray[1],
						      url: "/audit/bank/deleteBcFile/" + fileArray[0],
						      // url: "/test_audit/bank/deleteBcFile/" + fileArray[0],
						      width: "120px",
						      key: i+1
						  });
						}
						else
						{
							initialPreviewConfigArray.push({
						      caption: fileArray[1],
						      url: "/audit/bank/deleteBcFile/" + fileArray[0],
						      // url: "/test_audit/bank/deleteBcFile/" + fileArray[0],
						      width: "120px",
						      key: i+1
						  });
						}
					}

					console.log(initialPreviewArray);
				}
				$('#multiple_bc_doc').fileinput('destroy');

				$("#multiple_bc_doc").fileinput({
					'async' : false,
				    theme: 'fa',
				    uploadUrl: '/audit/bank/uploadBcDoc', // you must set a valid URL here else you will get an error
				    // uploadUrl: '/test_audit/bank/uploadBcDoc', // you must set a valid URL here else you will get an error
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
				    // initialPreviewDownloadUrl: base_url + '/test_audit/uploads/bank_images_or_pdf/{filename}',
				    initialPreview: initialPreviewArray,
				 	initialPreviewConfig: initialPreviewConfigArray,
				 	// deleteUrl: "/audit/bank/deleteBaFile",
				 	// maxFileSize: 20000048,
				 	// maxImageWidth: 1000,
				  //   maxImageHeight: 1500,
				  //   resizePreference: 'height',
				  //   resizeImage: true,
				 	// purifyHtml: true,// this by default purifies HTML data for preview
				    /*uploadExtraData: { 
				    	officer_id: $('input[name="officer_id"]').val() 
				    }*/
				    /*width:auto;height:auto;max-width:100%;max-height:100%;*/
				    uploadExtraData: function() {
			            return {
			                bc_id: $(".doc_bc_id").val()
			                // username: $("#username").val()
			            };
			        }

				});
	        }
	    })

	}

	$("#multiple_ba_doc").fileinput({
		'async' : false,
	    theme: 'fa',
	    uploadUrl: '/audit/bank/uploadBaDoc', // you must set a valid URL here else you will get an error
	    // uploadUrl: '/test_audit/bank/uploadBaDoc', // you must set a valid URL here else you will get an error
	    uploadAsync: false,
	    browseClass: "btn btn-primary",
	    fileType: "any",
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
	    // initialPreviewDownloadUrl: base_url + '/test_audit/uploads/bank_images_or_pdf/{filename}',
	    initialPreview: initialPreviewArray,
	 	initialPreviewConfig: initialPreviewConfigArray,
	 	maxFileCount: 1,
	 	//deleteUrl: "/dot/personprofile/deleteFile",
	 	/*maxFileSize: 20000048,
	 	maxImageWidth: 1000,
	    maxImageHeight: 1500,
	    resizePreference: 'height',
	    resizeImage: true,
	 	purifyHtml: true,// this by default purifies HTML data for preview
	    /*uploadExtraData: { 
	    	officer_id: $('input[name="officer_id"]').val() 
	    }*/
	    /*width:auto;height:auto;max-width:100%;max-height:100%;*/
	    uploadExtraData: function() {
            return {
                ba_id: $(".doc_ba_id").val()
                // username: $("#username").val()
            };
        }

	}).on('filesorted', function(e, params) {
	    //console.log('File sorted params', params);
	}).on('filebatchuploadsuccess', function(event, data, previewId, index) {
		if($("#close_page").val() == 1)
		{
			// window.close();
		}
		else
		{
			// console.log(change_ba_status_flag);
			if(change_ba_status_flag)
			{
				if(data.response != "empty")
				{
					$.post("bank/updt_auth_status", { 'bank_auth_id': data.extra.ba_id , 'status_id': 2}, function(data, status){
		    	 		if(data){
		    	 			window.location.href = bank_url;
		    	 		}
		    		});
				}
				else
				{
					Swal.fire({
			            icon: 'error',
			            title: 'Error',
			            text: 'Change status failed. No uploaded document(s).',
		            }).then((result) => {
							window.location.href = bank_url;
						})
				}
			}

			console.log("in1");
			$("#upload_ba_doc_modal").modal("hide");
			toastr.success("Information Updated", "Success");
		}
		
	    //console.log(data);
	}).on('fileuploaderror', function(event, data, msg) {
		// break;
		if($("#close_page").val() == 1)
		{
			// window.close();
		}
		else
		{
			//window.location.href = base_url + "personprofile";
			// if(corporate_reload_link != false)
			// {
				// window.location.href = corporate_reload_link;
			// }
			console.log("in2");
			$("#upload_ba_doc_modal").modal("hide");
			toastr.success("Information Updated", "Success");
		}
		//toastr.error("Error", "Error");
	});


	$("#multiple_bc_doc").fileinput({
		'async' : false,
	    theme: 'fa',
	    uploadUrl: '/audit/bank/uploadBcDoc', // you must set a valid URL here else you will get an error
	    // uploadUrl: '/test_audit/bank/uploadBcDoc', // you must set a valid URL here else you will get an error
	    uploadAsync: false,
	    browseClass: "btn btn-primary",
	    fileType: "any",
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
	    // initialPreviewDownloadUrl: base_url + '/test_audit/uploads/bank_images_or_pdf/{filename}',
	    initialPreview: initialPreviewArray,
	 	initialPreviewConfig: initialPreviewConfigArray,
	 	maxFileCount: 1,
	    uploadExtraData: function() {
            return {
                bc_id: $(".doc_bc_id").val()
                // username: $("#username").val()
            };
        }

	}).on('filesorted', function(e, params) {
	    //console.log('File sorted params', params);
	}).on('filebatchuploadsuccess', function(event, data, previewId, index) {
		if($("#close_page").val() == 1)
		{
			// window.close();
		}
		else
		{
			if(change_bc_status_flag)
			{
				if(data.response != "empty")
				{
					$.post("bank/updt_confirm_status", { 'bank_confirm_id': data.extra.bc_id , 'status_id': 2}, function(data, status){
		    	 		if(data){
		    	 			window.location.href = bank_url;
		    	 		}
		    		});
				}
				else
				{

					Swal.fire({
			            icon: 'error',
			            title: 'Error',
			            text: 'Change status failed. No uploaded document(s).',
		            }).then((result) => {
							window.location.href = bank_url;
						})
				}
			}
				
			console.log(data);
			$("#upload_bc_doc_modal").modal("hide");
			toastr.success("Information Updated", "Success");
		}
		
	    //console.log(data);
	}).on('fileuploaderror', function(event, data, msg) {
		// break;
		if($("#close_page").val() == 1)
		{
			// window.close();
		}
		else
		{
			//window.location.href = base_url + "personprofile";
			// if(corporate_reload_link != false)
			// {
				// window.location.href = corporate_reload_link;
			// }
			console.log("in2");
			$("#upload_ba_doc_modal").modal("hide");
			toastr.success("Information Updated", "Success");
		}
		//toastr.error("Error", "Error");
	});





    function change_confirm_status(element){
    	var dropdown = $(element);
    	var div 				= $(element).parent();
    	var tr 					= div.parent().parent();
    	var bank_confirm_id   = div.find('.confirm_id').val();
    	var upload_btn = tr.find('.bc_upload_btn');
    	// console.log(tr);

    	// var assignment_id 		= div.find('.assignment_id').val();
    	var status_id 			= div.find('.confirm_status').val();
    	// console.log(bank_auth_id);

    	bootbox.confirm({
	        message: "Do you want to change this status info?",
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
	        	if(result == true)
	        	{	
	        		if(status_id != 2){
	        			$.post("bank/updt_confirm_status", { 'bank_confirm_id': bank_confirm_id , 'status_id': status_id}, function(data, status){
			    	 		if(data){
			    	 			window.location.href = bank_url;
			    	 		}
			    		});
	        		}
	        		else
	        		{
	        			$("#upload_bc_doc_modal").find('.bc_upload_cancel').hide();
	        			open_bc_doc_modal(upload_btn);
	        			change_bc_status_flag = true;


	        		}

        			
	       
	        		$('.modal').on("hidden.bs.modal", function (e) { 
					    if ($('.modal:visible').length) { 
					        $('body').addClass('modal-open');
					    }
					});
	        	}
	        	else
	        	{
	        		// window.location.href = bank_url;
	        		dropdown.select2('destroy');
					dropdown.val(previous_confirm);
					dropdown.select2();


	        	}
	        }
	    })
    }

    // Edit bank confirmation send date
	function change_sent_date(element){
    	var div 				= $(element).parent();
    	var bank_confirm_id   = div.parent().find('.confirm_id').val();
    	var previous_sent_date =  $(element).data("prevdate");


    	// var assignment_id 		= div.find('.assignment_id').val();
    	var date_value 			= div.find('.confirm_sent_date').val();
    	console.log(bank_confirm_id);

    	bootbox.confirm({
	        message: "Do you want to change this status info?",
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
	        	if(result == true)
	        	{
	        		$(element).data("prevdate", date_value);

        			$.post("bank/updt_confirm_sent_date", { 'bank_confirm_id': bank_confirm_id , 'sent_on_date': date_value}, function(data, status){
		    	 		if(data){
		    	 			window.location.href = bank_url;
		    	 		}
		    		});
	       
	        		$('.modal').on("hidden.bs.modal", function (e) { 
					    if ($('.modal:visible').length) { 
					        $('body').addClass('modal-open');
					    }
					});
	        	}
	        	else
	        	{
	        		// window.location.href = bank_url;
	        		$(element).val(previous_sent_date);

	        	}
	        }
	    })
    }

    // $("#upload_ba_doc").submit(function(e) {
    // 	$("#multiple_ba_doc").fileinput('upload');
    // });

    $( "#upload_ba_doc_btn" ).click(function() {
	  	$("#multiple_ba_doc").fileinput('upload');
	});

	$( "#upload_bc_doc_btn" ).click(function() {
	  	$("#multiple_bc_doc").fileinput('upload');
	});


    // FILTER ------------------------------------------------------------------------------------------------------------------------------------------//
	// MONTH FILTER
	$(document).on('change',".bc_month_filter",function(){
		// var employee  = $(".timesheet_employee_filter").val();
		// var year      = $(".timesheet_year_filter").val();
		var month     = $(".bc_month_filter").val();
		var status    = $(".bc_status_filter").val();

		$.ajax({
	       type: "POST",
	       url:  "bank/bank_confirm_filter",
	       data: '&month=' + month + '&status=' + status,
	       success: function(data)
	       {

	       		if(JSON.parse(data)==null || JSON.parse(data)==""){
	       			$("#datatable-confirmation").DataTable().destroy();
	       			var table  = $("#datatable-confirmation").DataTable();
	           		var object = (JSON.parse(data));
					table.clear().draw();
	       		}

	       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
	       			$("#datatable-confirmation").DataTable().destroy();
	           		var object = (JSON.parse(data));
	           		$(".bc_tr").remove();
	           		var http = '<?php echo base_url() ?>';

	           		for(var i=0; i<object.length; i++){
	           			var rowHtml = 	"<tr class='bc_tr' >"+
	           							  "<input type='hidden' class='company_code' value='"+object[i]['company_code']+"' />"+
		           						  "<td style='width:2.5%;'>' '</td>"+
		           						  "<td style='width:15%;'>"+ object[i]['company_name'] +"</td>"+
		           						  "<td style='width:15%;'>"+ object[i]['bank_name'] +"</td>"+
		           						  "<td style='width:15%;'>"+ object[i]['firm_name'] +"</td>"+
		           						  "<td style='width:10%;'>"+ object[i]['fye_date'] +"</td>"+
		           						  "<td style='width:16%;' data-order='"+object[i]['sent_on_date']+"'><div class='input_sent_date' style='width: 100%;'>"+
		           						  	"<div class='input-group date datepicker' data-provide='datepicker'>"+
		           						  		"<span class='input-group-addon'><i class='far fa-calendar-alt'></i></span>"+
		           						  		"<input type='text' class='form-control confirm_sent_date' name='confirm_sent_date' data-prevdate='"+moment(object[i]['sent_on_date']).format('DD MMMM YYYY')+"' onchange=change_sent_date(this) value='"+moment(object[i]['sent_on_date']).format('DD MMMM YYYY')+"' autocomplete='off' />"+
		           						  	"</div>"+
		           						  	"<input type='hidden' class='confirm_id' value='"+ object[i]['id']+"' />"+
		           						  "</div></td>"+
		           						  "<td style='width:14%;'><div class='input-group' style='width: 100%'>"+
		           						  	"<select class='confirm_status' style='width: 100%;' name='confirm_status' onchange=change_confirm_status(this)></select>"+
		           						  	"<input type='hidden' class='confirm_id' value='"+ object[i]['id']+"' />"+
		           						  "</div></td>"+
		           						  "<td style='text-align:left;width:12.5%;'>"+
		           						  	"<input type='hidden' class='confirm_id' value='"+ object[i]['id'] +"' />"+
		           						  	"<button type='button' class='btn btn_blue bc_upload_btn' onclick=open_bc_doc_modal(this) style='margin:4px;'>Upload</button>"+
		           						  	"<button type='button' class='btn btn_blue' onclick=generate_confirm(this) style='margin-bottom:5px;'>PDF</button>";
	           				if(object[i]['confirm_status'] == 2)
	           				{
	           					rowHtml += "<button type='button' class='btn btn_blue' onclick=open_caf(this) style='margin:4px;'>CAF</button>";
	           				}
	           				else
	           				{
	           					rowHtml += "<button disabled type='button' class='btn btn_blue' onclick=open_caf(this) style='margin:4px;'>CAF</button>";
	           				}

		           				rowHtml += 	"<button type='button' class='btn btn_blue' onclick=delete_bank_confirm(this)>Delete</button>"+

		           						  "</td>"
		           						 "</tr>";
	           			$(".bank_confirm_body").append(rowHtml);

	           			//append select option below here

	           			$.each(status_dropdown, function(key, val) {
		                    var option = $('<option />');
		                    option.attr('value', key).text(val);
		                    
		                    if(object[i]['confirm_status'] != null && object[i]['confirm_status'] == key)
		                    {
		                    	option.attr('selected', 'selected');
		                    }
		                    $(".confirm_status").last().append(option);
		                });
	           		}

	           		$(".confirm_status").select2();

	           		$('.datepicker').datepicker({
						format: 'dd MM yyyy',
					});

	       		}

	       		var t_c = $('#datatable-confirmation').DataTable( {
			    	"order": [[ 5, 'desc' ]]
			    	// "order": [[ 3, 'asc' ]]
			    });

			    t_c.on( 'order.dt search.dt', function () {
			        t_c.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
			            cell.innerHTML = i+1;
			        } );
			    } ).draw();
	       }
	   	});

	   	
	});
	// STATUS FILTER
	$(document).on('change',".bc_status_filter",function(){
		// var employee  = $(".timesheet_employee_filter").val();
		// var year      = $(".timesheet_year_filter").val();
		var month     = $(".bc_month_filter").val();
		var status    = $(".bc_status_filter").val();

		$.ajax({
	       type: "POST",
	       url:  "bank/bank_confirm_filter",
	       data: '&month=' + month + '&status=' + status,
	       success: function(data)
	       {



	       		if(JSON.parse(data)==null || JSON.parse(data)==""){
	       			$("#datatable-confirmation").DataTable().destroy();
	       			var table  = $("#datatable-confirmation").DataTable();
	           		var object = (JSON.parse(data));
					table.clear().draw();
	       		}

	       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
	       			$("#datatable-confirmation").DataTable().destroy();
	           		var object = (JSON.parse(data));
	           		$(".bc_tr").remove();
	           		var http = '<?php echo base_url() ?>';
	           		for(var i=0; i<object.length; i++){
	           			var rowHtml = 	"<tr class='bc_tr' >"+
	           							  "<input type='hidden' class='company_code' value='"+object[i]['company_code']+"' />"+
		           						  "<td style='width:2.5%;'>' '</td>"+
		           						  "<td style='width:15%;'>"+ object[i]['company_name'] +"</td>"+
		           						  "<td style='width:15%;'>"+ object[i]['bank_name'] +"</td>"+
		           						  "<td style='width:15%;'>"+ object[i]['firm_name'] +"</td>"+
		           						  "<td style='width:10%;'>"+ object[i]['fye_date'] +"</td>"+
		           						  "<td style='width:16%;' data-order='"+object[i]['sent_on_date']+"'><div class='input_sent_date' style='width: 100%;'>"+
		           						  	"<div class='input-group date datepicker' data-provide='datepicker'>"+
		           						  		"<span class='input-group-addon'><i class='far fa-calendar-alt'></i></span>"+
		           						  		"<input type='text' class='form-control confirm_sent_date' name='confirm_sent_date' data-prevdate='"+moment(object[i]['sent_on_date']).format('DD MMMM YYYY')+"' onchange=change_sent_date(this) value='"+moment(object[i]['sent_on_date']).format('DD MMMM YYYY')+"' autocomplete='off' />"+
		           						  	"</div>"+
		           						  	"<input type='hidden' class='confirm_id' value='"+ object[i]['id']+"' />"+
		           						  "</div></td>"+
		           						  "<td style='width:14%;'><div class='input-group' style='width: 100%'>"+
		           						  	"<select class='confirm_status' style='width: 100%;' name='confirm_status' onchange=change_confirm_status(this)></select>"+
		           						  	"<input type='hidden' class='confirm_id' value='"+ object[i]['id']+"' />"+
		           						  "</div></td>"+
		           						  "<td style='text-align:left;width:12.5%;'>"+
		           						  	"<input type='hidden' class='confirm_id' value='"+ object[i]['id'] +"' />"+
		           						  	"<button type='button' class='btn btn_blue bc_upload_btn' onclick=open_bc_doc_modal(this) style='margin:4px;'>Upload</button>"+
		           						  	"<button type='button' class='btn btn_blue' onclick=generate_confirm(this) style='margin-bottom:5px;'>PDF</button>";
	           				if(object[i]['confirm_status'] == 2)
	           				{
	           					rowHtml += "<button type='button' class='btn btn_blue' onclick=open_caf(this) style='margin:4px;'>CAF</button>";
	           				}
	           				else
	           				{
	           					rowHtml += "<button disabled type='button' class='btn btn_blue' onclick=open_caf(this) style='margin:4px;'>CAF</button>";
	           				}

		           				rowHtml += 	"<button type='button' class='btn btn_blue' onclick=delete_bank_confirm(this)>Delete</button>"+

		           						  "</td>"
		           						 "</tr>";
	           			$(".bank_confirm_body").append(rowHtml);

	           			//append select option below here

	           			$.each(status_dropdown, function(key, val) {
		                    var option = $('<option />');
		                    option.attr('value', key).text(val);
		                    
		                    if(object[i]['confirm_status'] != null && object[i]['confirm_status'] == key)
		                    {
		                    	option.attr('selected', 'selected');
		                    }
		                    $(".confirm_status").last().append(option);
		                });
	           		}

	           		$(".confirm_status").select2();

	           			$('.datepicker').datepicker({
							format: 'dd MM yyyy',
						});

	       		}

	       		var t_c = $('#datatable-confirmation').DataTable( {
			    	"order": [[ 5, 'desc' ]]
			    	// "order": [[ 3, 'asc' ]]
			    });

			    t_c.on( 'order.dt search.dt', function () {
			        t_c.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
			            cell.innerHTML = i+1;
			        } );
			    } ).draw();
	       }
	   	});

	   
	});
	// FILTER ------------------------------------------------------------------------------------------------------------------------------------------//

	function open_caf(element)
	{
		$("#confirm_caf_modal").modal("show"); 
		var tr = $(element).parent().parent();
		var modal = $("#confirm_caf_modal");

		$('#fin_period').find('option').not(':nth-child(1)').remove();

		var cn = new Client();
	    cn.getFinPeriod(tr.find('.company_code').val());

		console.log(tr);
		modal.find('.panel-title').html(tr.find('.company_name').text()+'<br/><label class="control-label" style="font-size:13px;"><i>File bank confirmation to current audit file</i></label>');
	}

	function open_paf(element)
	{
		$("#auth_paf_modal").modal("show"); 
		var tr = $(element).parent().parent();
		var modal = $("#auth_paf_modal");

		var move_auth_id =  tr.find('.auth_id').val();
		console.log(move_auth_id);
	 
	    for(var i = 0; i < bank_auth_list.length; i++)
	    {
	    	if(bank_auth_list[i]["id"] == move_auth_id)
	    	{
	    		selected_move_auth = bank_auth_list[i];
	    	}

	    }
	    // console.log(bank_lost);

		// $('#fin_period').find('option').not(':nth-child(1)').remove();

		// var cn = new Client();
	 //    cn.getFinPeriod(tr.find('.company_code').val());

		console.log(tr);
		modal.find('.panel-title').html(tr.find('.company_name').text()+'<br/><label class="control-label" style="font-size:13px;"><i>File bank authorization to permanent audit file</i></label>');
		modal.find('.bank_name').html('Bank: '+tr.find('.bank_name').text());
	}


	function reset_setting()
	{
		var cn = new Client();
		cn.getPicName();
		$('.confirm_month').prop('style', 'background-color:white;');
		$('.confirm_month').val("");
		$('.confirm_month').datepicker({
		    autoclose: true,
		    minViewMode: 1,
		    format: 'MM yyyy'
		}).on("show", function(event) {

		  	var year = $("th.datepicker-switch").eq(1).text();  // there are 3 matches

		  	$(".month").each(function(index, element) {
			    var el = $(element);
			    
			    var hideMonth = $.grep(monthToDisable, function( n, i ) {
				                  return n.substr(4, 4) == year && n.substr(0, 3) == el.text();
				                });

			    if (hideMonth.length)
			      el.addClass('disabled');

		    
			});
		});
	    	
	}

	// function add_setting_line()
	// {
	// 	var new_row = '<tr>'+
	// 					'<td>'+
	// 					 	'<div class="input-group" id="confirm_month" style="width: 80%;">'+
	// 	                    	'<span class="input-group-addon">'+
	// 	                    		'<i class="far fa-calendar-alt"></i>'+
	// 	                    	'</span>'+
	// 	                    	'<input type="text" class="confirm_month form-control" name="confirm_month" data-date-format="mm/yyyy" required value="">'+
	// 						'</div>'+
	// 					'</td>'+
	// 					'<td>'+
	// 						'<div class="input-group dropdown_pic_name" style="width: 80%;">'+
	// 							'<select id="pic_name" class="form-control pic_name" style="width: 100%;" name="pic_name" required>'+
	// 				                    '<option value="">Select PIC Name</option>'+
	// 				            '</select>'+
	// 				        '</div>'+
	// 					'</td>'+
	// 					'<td style="text-align:center;" width="15%"><button type="button" style="margin:4px;" class="btn btn_blue"  onclick=delete_confirm_setting(this)>Save</button></td>'+
	// 				   '</tr>';

	// 	$('#datatable-confirm-setting').prepend($(new_row));


	// $('.confirm_month').datepicker({
	//     autoclose: true,
	//     minViewMode: 1,
	//     format: 'MM yyyy'
	// });

	// $(function() {
	//     var cn = new Client();
	//     cn.getPicName();
	// });


	// }