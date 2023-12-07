$('.initial_el_status').select2();
$('.subsequent_el_status').select2();



$('.leave_cycle_daterange').datepicker({
  format: "dd MM",
  //weekStart: 1,
  language: "en",
  //daysOfWeekHighlighted: "0,6",
  //startDate: "01/01/2017",
  orientation: "bottom auto",
  autoclose: true,
  showOnFocus: true,
  //maxViewMode: 'days',
  keepEmptyValues: true,
  // templates: {
  //   leftArrow: '&lt;',
  //   rightArrow: '&gt;'
  // }
}).on('show', function() {
    // remove the year from the date title before the datepicker show
    var dateText  = $(".datepicker-days .datepicker-switch").text();
    var dateTitle = dateText.substr(0, dateText.length - 5);
    $(".datepicker-days .datepicker-switch").text(dateTitle);
});

$('.leave_cycle_daterange').focusin(function(e) {
  userTarget1 = e.target.name;
});
$('.leave_cycle_daterange').on('changeDate', function(e) {
  if (exit1) return;
  if (e.target.name != userTarget1) {
    exit1 = true;
    $(e.target).datepicker('clearDates');
  }
  exit1 = false;
});


var userTarget = "";
var exit = false;
$('.input-daterange').datepicker({
  format: "dd MM yyyy",
  weekStart: 1,
  language: "en",
  //daysOfWeekHighlighted: "0,6",
  startDate: "01/01/1957",
  orientation: "bottom auto",
  autoclose: true,
  showOnFocus: true,
  //maxViewMode: 'days',
  keepEmptyValues: true,
  // templates: {
  //   leftArrow: '&lt;',
  //   rightArrow: '&gt;'
  // }
});
$('.input-daterange').focusin(function(e) {
  userTarget = e.target.name;
});
$('.input-daterange').on('changeDate', function(e) {
  if (exit) return;
  if (e.target.name != userTarget) {
    exit = true;
    $(e.target).datepicker('clearDates');
  }
  exit = false;
});

function validate(evt) {
  var theEvent = evt || window.event;

  // Handle paste
  if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
  } else {
  // Handle key press
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
  }
  var regex = /[0-9]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

if(active_tab != null)
{  
	pv_index_tab_aktif = active_tab;

    if(active_tab != "engagement")
    {
        $('li[data-information="'+active_tab+'"]').addClass("active");
        $('#w2-'+active_tab+'').addClass("active");
        $('li[data-information="engagement"]').removeClass("active");
        $('#w2-engagement').removeClass("active");
    }
    // console.log(active_tab);
    if(active_tab =="engagement")
    {
    	$(".create_engagement_letter").show();
    }
    else{
    	$(".create_engagement_letter").hide();
    }

    if(active_tab =="subsequent")
    {
        $(".create_subsequent_el").show();
    }
    else{
        $(".create_subsequent_el").hide();
    }


    // if(active_tab =="bank_confirm")
    // {
    // 	$(".create_bank_confirmation").show();
    // }
    // else{
    // 	$(".create_bank_confirmation").hide();
    // }
}

$(document).on('click',".check_state",function(){
	pv_index_tab_aktif = $(this).data("information")

    if(pv_index_tab_aktif =="engagement")
    {
        $(".create_engagement_letter").show();
    }
    else{
        $(".create_engagement_letter").hide();
    }

    if(pv_index_tab_aktif =="subsequent")
    {
        $(".create_subsequent_el").show();
    }
    else{
        $(".create_subsequent_el").hide();
    }



    // if(pv_index_tab_aktif =="bank_confirm")
    // {
    // 	$(".create_bank_confirmation").show();
    // }
    // else{
    // 	$(".create_bank_confirmation").hide();
    // }
});

$('.initial_el_status').on('select2:selecting', function (evt) {
  	previous_initial_el = $(this).val();

  	// console.log("selecting"+previous_confirm);
});


$('.subsequent_el_status').on('select2:selecting', function (evt) {
    previous_subsequent_el = $(this).val();

    // console.log("selecting"+previous_confirm);
});

$('#postal_code').keyup(function(){
	if($(this).val().length == 6)
	{
  		var zip = $(this).val();
		//var address = "068914";
		$.ajax({
		  url:    'https://gothere.sg/maps/geo',
		  dataType: 'jsonp',
		  data:   {
		    'output'  : 'json',
		    'q'     : zip,
		    'client'  : '',
		    'sensor'  : false
		  },
		  type: 'GET',
		  success: function(data) {
		    //console.log(data);
		    //var field = $("textarea");
		    var myString = "";
		    
		    var status = data.Status;
		    /*myString += "Status.code: " + status.code + "\n";
		    myString += "Status.request: " + status.request + "\n";
		    myString += "Status.name: " + status.name + "\n";*/
		    
		    if (status.code == 200) {         
		      for (var i = 0; i < data.Placemark.length; i++) {
		        var placemark = data.Placemark[i];
		        var status = data.Status[i];
		        //console.log(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);
		        $("#street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

		        if(placemark.AddressDetails.Country.AddressLine == "undefined")
		        {
		        	$("#building_name").val("");
		        }
		        else
		        {
		        	$("#building_name").val(placemark.AddressDetails.Country.AddressLine);
		        }

		      }
		      $( '#form_postal_code' ).html('');
		      $( '#form_street_name' ).html('');
		      //field.val(myString);
		    } else if (status.code == 603) {
		    	$( '#form_postal_code' ).html('<span class="help-block">*No Record Found</span>');
		      //field.val("No Record Found");
		    }

		  },
		  statusCode: {
		    404: function() {
		      alert('Page not found');
		    }
		  },
	    });
	}
});

(function( $ ) {
	'use strict';

	var datatableInit = function() {

		var table1 = $('.datatable-setting').DataTable({
			
            "order": [[ 0, 'asc' ]]
		});
	};

	$(function() {
		datatableInit();
	});

}).apply( this, [ jQuery ]);

// $("#block_holiday_submit").submit(function(e) {
//        var form = $(this);

//        $.ajax({
//           type: "POST",
//           url: "setting/submit_holiday",
//           data: form.serialize(), // serializes the form's elements.
//           success: function(data)
//           {	
//           		if(data){
//           			toastr.success('Information Updated', 'Updated');
//           			location.reload();
//           		}
//               // alert(data); // show response from the php script.
//               // $('#applicant_resume').fileinput('upload');
//           }
//       	});
//    	e.preventDefault(); // avoid to execute the actual submit of the form.
//    });

$("#auditor_list_submit").submit(function(e) {
    var form = $(this);

    $.ajax({
       type: "POST",
       url: "list_of_auditor/submit_auditor_list",
       data: form.serialize(), // serializes the form's elements.
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
       			location.reload();
       		}
           // alert(data); // show response from the php script.
           // $('#applicant_resume').fileinput('upload');
       }
   	});
	e.preventDefault(); // avoid to execute the actual submit of the form.
});

function generate_first_clearance_letter(element)
{
	var div 		= $(element).parent();
	var letter_id 	= div.find('.letter_id').val();
	// console.log(auth_id);

	$.ajax({ //Upload common input
      url		: "list_of_auditor/generate_first_letter",
      type 		: "POST",
      dataType	: 'json',
      data 	: {"letter_id": letter_id},
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

function get_first_clearance_letter(element)
{
    var div = $(element).parent();
    var letter_id = div.find('.letter_id').val();

    $.ajax({
        url     : "list_of_auditor/get_latest_first_letter",
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



function generate_confirm(element)
{
	var div 		= $(element).parent();
	var confirm_id 	= div.find('.confirm_id').val();
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

            setTimeout(function(){ deletePDF(response.path); }, 5000);
        }
    })
}

function delete_auditor(element){
	var div 		= $(element).parent();
	var auditor_id 	= div.find('.auditor_id').val();
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
        		$.post("list_of_auditor/delete_auditor", { 'auditor_id': auditor_id }, function(data, status){
		    	 	if(data){
		    	 		location.reload();
		    	 	}
		    	});
        	}
        }
    })
}

function delete_initial_el(element){
	var div 		= $(element).parent();
	var letter_id 	= div.find('.el_id').val();
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
        		$.post("engagement/delete_initial_el", { 'letter_id': letter_id }, function(data, status){
		    	 	if(data){
		    	 		location.reload();
		    	 	}
		    	});
        	}
        }
    })
}

function delete_subsequent_el(element){
    var div         = $(element).parent();
    var letter_id   = div.find('.sub_el_id').val();
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
                $.post("engagement/delete_subsequent_el", { 'letter_id': letter_id }, function(data, status){
                    if(data){
                        location.reload();
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
		    	 		location.reload();
		    	 	}
		    	});
        	}
        }
    })
}



function change_initial_el_status(element){
	var dropdown = $(element);
	var div 				= $(element).parent();
	var tr 					= div.parent().parent();
	var letter_id   = div.find('.el_id').val();
	var upload_btn = tr.find('.el_upload_btn');
	// console.log(tr);

	// var assignment_id 		= div.find('.assignment_id').val();
	var status_id 			= div.find('.initial_el_status').val();
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
        		if(status_id != 13){
        			$.post("engagement/updt_initial_el_status", { 'letter_id': letter_id , 'status_id': status_id}, function(data, status){
		    	 		if(data){
		    	 			location.reload();
		    	 		}
		    		});
        		}
        		else
        		{
        			$("#upload_el_doc_modal").find('.el_upload_cancel').hide();
        			open_el_doc_modal(upload_btn);
        			change_el_status_flag = true;


        		}

    			
       
        		$('.modal').on("hidden.bs.modal", function (e) { 
				    if ($('.modal:visible').length) { 
				        $('body').addClass('modal-open');
				    }
				});
        	}
        	else
        	{
        		// location.reload();
        		dropdown.select2('destroy');
				dropdown.val(previous_initial_el);
				dropdown.select2();


        	}
        }
    })
}

function change_subsequent_el_status(element){
    var dropdown = $(element);
    var div                 = $(element).parent();
    var tr                  = div.parent().parent();
    var letter_id   = div.find('.sub_el_id').val();
    var upload_btn = tr.find('.sub_el_upload_btn');
    // console.log(tr);

    // var assignment_id        = div.find('.assignment_id').val();
    var status_id           = div.find('.subsequent_el_status').val();
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
                if(status_id != 13){
                    $.post("engagement/updt_subsequent_el_status", { 'letter_id': letter_id , 'status_id': status_id}, function(data, status){
                        if(data){
                            location.reload()
                            // lodge_engagement(letter_id);
                        }
                    });
                }
                else
                {
                    $("#upload_sub_el_doc_modal").find('.sub_el_upload_cancel').hide();
                    open_sub_el_doc_modal(upload_btn);
                    change_sub_el_status_flag = true;


                }

                
       
                $('.modal').on("hidden.bs.modal", function (e) { 
                    if ($('.modal:visible').length) { 
                        $('body').addClass('modal-open');
                    }
                });
            }
            else
            {
                // location.reload();
                dropdown.select2('destroy');
                dropdown.val(previous_subsequent_el);
                dropdown.select2();


            }
        }
    })
}


function open_el_doc_modal(element){
    initialPreviewArray = []; 
	initialPreviewConfigArray = [];
	engagement_files = "";

    var div 	= $(element).parent();
    var el_id = div.find('.el_id').val();
    var company_name = div.find('.company_name').val();
 
	$("#upload_el_doc_modal").modal("show"); 
	// $('#loadingMessage').show();
	// console.log(auth_id);

	$.ajax({ //Upload common input
		url		: "engagement/get_engagement_doc_list",
		type 		: "POST",
		dataType	: 'json',
		data 	: {"letter_id": el_id},
		beforeSend: function()
	    {
	        // $('#loadingMessage').show();
	    },
        success	: function (response) {
	        $('#loadingMessage').hide();
	        //console.log(response.pdf_link);
	        engagement_files = response[0].file_names;
	        // console.log(response);
            $('#el_client').val(company_name);
            $('.doc_el_id').val(el_id);

	     //    for(var i = 0; i < engagement_list.length; i++)
		    // {
		    // 	if(engagement_list[i]["id"] == el_id)
		    // 	{
		    		
		    		
		    		// console.log(bank_auth_list[i]);

		 //    	}

			// }


		    if(engagement_files != null)
			{
				for (var i = 0; i < engagement_files.length; i++) {
					
				  var url = base_url + "/audit/document/engagement/";
				  // var url = base_url + "/test_audit/uploads/bank_images_or_pdf/";
				  var fileArray = engagement_files[i].split(',');
				  //console.log(fileArray[0]);
				  initialPreviewArray.push( url + fileArray[1] );
				  var file_type = fileArray[1].substring(fileArray[1].lastIndexOf('.'));
				  //console.log(file_type);
				  	if(file_type == ".pdf" || file_type == ".PDF")
				  	{
					  initialPreviewConfigArray.push({
						  type: "pdf",
					      caption: fileArray[1],
					      url: "/audit/engagement/deleteElFile/" + fileArray[0],
					      // url: "/test_audit/bank/deleteBcFile/" + fileArray[0],
					      width: "120px",
					      key: i+1
					  });
					}
					else
					{
						initialPreviewConfigArray.push({
					      caption: fileArray[1],
					      url: "/audit/engagement/deleteElFile/" + fileArray[0],
					      // url: "/test_audit/bank/deleteBcFile/" + fileArray[0],
					      width: "120px",
					      key: i+1
					  });
					}
				}

				console.log(initialPreviewArray);
			}
			$('#multiple_el_doc').fileinput('destroy');

			$("#multiple_el_doc").fileinput({
				'async' : false,
			    theme: 'fa',
			    uploadUrl: '/audit/engagement/uploadElDoc', // you must set a valid URL here else you will get an error
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
			    initialPreviewDownloadUrl: base_url + '/audit/document/engagement/{filename}',
			    // initialPreviewDownloadUrl: base_url + '/test_audit/uploads/bank_images_or_pdf/{filename}',
			    initialPreview: initialPreviewArray,
			 	initialPreviewConfig: initialPreviewConfigArray,
			    uploadExtraData: function() {
		            return {
		                letter_id: $(".doc_el_id").val()
		                // username: $("#username").val()
		            };
		        }

			});
        }
    })

}


function open_sub_el_doc_modal(element){
    initialPreviewArray = []; 
    initialPreviewConfigArray = [];
    subsequent_engagement_files = "";

    var div     = $(element).parent();
    var sub_el_id = div.find('.sub_el_id').val();
    var company_name = div.find('.company_name').val();
 
 
    $("#upload_sub_el_doc_modal").modal("show"); 

    $.ajax({ //Upload common input
        url     : "engagement/get_sub_engagement_doc_list",
        type        : "POST",
        dataType    : 'json',
        data    : {"letter_id": sub_el_id},
        beforeSend: function()
        {
            // $('#loadingMessage').show();
        },
        success : function (response) {
            $('#loadingMessage').hide();
            //console.log(response.pdf_link);
            subsequent_engagement_files = response[0].file_names;
            // console.log(response);
            $('#sub_el_client').val(company_name);
            $('.doc_sub_el_id').val(sub_el_id);

            // for(var i = 0; i < subsequent_engagement_list.length; i++)
            // {
            //     if(subsequent_engagement_list[i]["id"] == sub_el_id)
            //     {
                    
                    
            //         // console.log(company_name);

            //     }

            // }

            if(subsequent_engagement_files != null)
            {
                for (var i = 0; i < subsequent_engagement_files.length; i++) {
                    
                  var url = base_url + "/audit/document/engagement/";
                  // var url = base_url + "/test_audit/uploads/bank_images_or_pdf/";
                  var fileArray = subsequent_engagement_files[i].split(',');
                  //console.log(fileArray[0]);
                  initialPreviewArray.push( url + fileArray[1] );
                  var file_type = fileArray[1].substring(fileArray[1].lastIndexOf('.'));
                  //console.log(file_type);
                    if(file_type == ".pdf" || file_type == ".PDF")
                    {
                      initialPreviewConfigArray.push({
                          type: "pdf",
                          caption: fileArray[1],
                          url: "/audit/engagement/deleteSubElFile/" + fileArray[0],
                          // url: "/test_audit/bank/deleteBcFile/" + fileArray[0],
                          width: "120px",
                          key: i+1
                      });
                    }
                    else
                    {
                        initialPreviewConfigArray.push({
                          caption: fileArray[1],
                          url: "/audit/engagement/deleteSubElFile/" + fileArray[0],
                          // url: "/test_audit/bank/deleteBcFile/" + fileArray[0],
                          width: "120px",
                          key: i+1
                      });
                    }
                }

                // console.log(initialPreviewArray);
            }


            $('#multiple_sub_el_doc').fileinput('destroy');

            $("#multiple_sub_el_doc").fileinput({
                'async' : false,
                theme: 'fa',
                uploadUrl: '/audit/engagement/uploadSubElDoc', // you must set a valid URL here else you will get an error
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
                initialPreviewDownloadUrl: base_url + '/audit/document/engagement/{filename}',
                // initialPreviewDownloadUrl: base_url + '/test_audit/uploads/bank_images_or_pdf/{filename}',
                initialPreview: initialPreviewArray,
                initialPreviewConfig: initialPreviewConfigArray,
                uploadExtraData: function() {
                    return {
                        letter_id: $(".doc_sub_el_id").val()
                        // username: $("#username").val()
                    };
                }

            });
        }
    })

    
}

function open_initial_el_paf(element, replace=false)
{
    $("#initial_el_paf_modal").modal("show"); 
    $("#initial_el_paf_modal .attachment_tr").remove()
    var tr = $(element).parent().parent();
    var div     = $(element).parent();
    var el_id = div.find('.el_id').val();
    var modal = $("#initial_el_paf_modal");
    var table = "";

    if(replace)
    {
        $("#move_paf_btn").hide();
        $("#replace_paf_btn").show();
    }
    else
    {
        $("#move_paf_btn").show();
        $("#replace_paf_btn").hide();
    }

    // console.log(tr.find('.company_name'));

    modal.find('.panel-title').html(tr.find('.company_name').val()+'<br/><label class="control-label" style="font-size:13px;"><i>File engagement letter to Permanent Audit File</i></label>');

    $("#loadingMessage").show();

    $.ajax({
        type: "POST",
        url: get_initial_el_attachment_url,
        data: '&el_id=' + el_id,
        async: false,
        dataType: "json",
        success: function(data){
           
            selected_move_el = data.attachment;

            if(data.attachment != "")
            {
                for(var a = 0; a <data.attachment.length; a++)
                {
                    table += '<tr class="attachment_tr">'+
                                '<td style="width:100%;"><a href="'+ base_url + '/'+folder+'/document/'+data.attachment[a].file_path+'/'+data.attachment[a].file_name+'" target="_blank" >'+data.attachment[a].file_name+'</a></td>'+
                             '</tr>';
                }
            }
            else
            {
                table = '<tr><td align="center"><p style="margin-top:10px;">No document(s) to display</p></td></tr>'

            }
            $(table).appendTo('#initial_el_attachment_table');  
            $("#loadingMessage").hide();
        }
    });    


}

$(document).on('click',"#move_paf_btn",function(){


    $.post( "engagement/move_initial_el_to_paf", { 'selected_move_el': selected_move_el }, function() {
        alert( "success" );
        window.location.href = engagement_url;
    })
});


function open_subsequent_el_paf(element, replace=false)
{
    $("#subsequent_el_paf_modal").modal("show"); 
    $("#subsequent_el_paf_modal .attachment_tr").remove()
    var tr = $(element).parent().parent();
    var div     = $(element).parent();
    var sub_el_id = div.find('.sub_el_id').val();
    var modal = $("#subsequent_el_paf_modal");
    var table = "";

    if(replace)
    {
        $("#move_sub_paf_btn").hide();
        $("#replace_sub_paf_btn").show();
    }
    else
    {
        $("#move_sub_paf_btn").show();
        $("#replace_sub_paf_btn").hide();
    }
    // console.log(tr.find('.company_name'));

    modal.find('.panel-title').html(tr.find('.company_name').val()+'<br/><label class="control-label" style="font-size:13px;"><i>File subsequent engagement letter to Permanent Audit File</i></label>');

    $("#loadingMessage").show();

    $.ajax({
        type: "POST",
        url: get_subsequent_el_attachment_url,
        data: '&sub_el_id=' + sub_el_id,
        async: false,
        dataType: "json",
        success: function(data){
           
            selected_move_sub_el = data.attachment;

            if(data.attachment != "")
            {
                for(var a = 0; a <data.attachment.length; a++)
                {
                    table += '<tr class="attachment_tr">'+
                                '<td style="width:100%;"><a href="'+ base_url + '/'+folder+'/document/'+data.attachment[a].file_path+'/'+data.attachment[a].file_name+'" target="_blank" >'+data.attachment[a].file_name+'</a></td>'+
                             '</tr>';
                }
            }
            else
            {
                table = '<tr><td align="center"><p style="margin-top:10px;">No document(s) to display</p></td></tr>'

            }
            $(table).appendTo('#subsequent_el_attachment_table');  
            $("#loadingMessage").hide();
        }
    });    
}

$(document).on('click',"#move_sub_paf_btn",function(){

    $.post( "engagement/move_subsequent_el_to_paf", { 'selected_move_sub_el': selected_move_sub_el }, function() {
        alert( "success" );
        window.location.href = engagement_url;
    })
});



$( "#upload_el_doc_btn" ).click(function() {
  	$("#multiple_el_doc").fileinput('upload');
});

$(document).on('click',".el_upload_cancel",function(){
    $.post("engagement/clear_delete_engagement_session");
});

$( "#upload_sub_el_doc_btn" ).click(function() {
    $("#multiple_sub_el_doc").fileinput('upload');
});

$(document).on('click',".sub_el_upload_cancel",function(){
    $.post("engagement/clear_delete_subsequent_engagement_session");
});


$("#multiple_el_doc").fileinput({
    'async' : false,
    theme: 'fa',
    uploadUrl: '/audit/engagement/uploadElDoc', // you must set a valid URL here else you will get an error
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
    initialPreviewDownloadUrl: base_url + '/audit/document/engagement/{filename}',
    // initialPreviewDownloadUrl: base_url + '/test_audit/uploads/bank_images_or_pdf/{filename}',
    initialPreview: initialPreviewArray,
    initialPreviewConfig: initialPreviewConfigArray,

    uploadExtraData: function() {
        return {
            letter_id: $(".doc_el_id").val()
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
        if(change_el_status_flag)
        {
            if(data.response != "empty")
            {
                var letter_id = data.extra.letter_id;
                $.post("engagement/updt_initial_el_status", { 'letter_id': data.extra.letter_id , 'status_id': 13}, function(data, status){
                    if(data){
                        // location.reload();
                        lodge_engagement(letter_id);
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
                    location.reload();
                })
            }
        }
            
        console.log(data);
        $("#upload_el_doc_modal").modal("hide");
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
        $("#upload_el_doc_modal").modal("hide");
        toastr.success("Information Updated", "Success");
    }
    //toastr.error("Error", "Error");
});


$("#multiple_sub_el_doc").fileinput({
    'async' : false,
    theme: 'fa',
    uploadUrl: '/audit/engagement/uploadSubElDoc', // you must set a valid URL here else you will get an error
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
    initialPreviewDownloadUrl: base_url + '/audit/document/engagement/{filename}',
    // initialPreviewDownloadUrl: base_url + '/test_audit/uploads/bank_images_or_pdf/{filename}',
    initialPreview: initialPreviewArray,
    initialPreviewConfig: initialPreviewConfigArray,

    uploadExtraData: function() {
        return {
            letter_id: $(".doc_sub_el_id").val()
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
        if(change_sub_el_status_flag)
        {
            if(data.response != "empty")
            {
                var letter_id = data.extra.letter_id;
                $.post("engagement/updt_subsequent_el_status", { 'letter_id': data.extra.letter_id , 'status_id': 13}, function(data, status){
                    if(data){
                        // location.reload();
                        lodge_subsequent_engagement(letter_id);
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
                    location.reload();
                })
            }
        }
            
        console.log(data);
        $("#upload_sub_el_doc_modal").modal("hide");
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
        $("#upload_sub_el_doc_modal").modal("hide");
        toastr.success("Information Updated", "Success");
    }
    //toastr.error("Error", "Error");
});

function generate_engagement_letter(el_id)
{
    $('#loadingMessage').show();
    // console.log(auth_id);

    $.ajax({ //Upload common input
      url       : generate_engagement_letter_url,
      type      : "POST",
      dataType  : 'json',
      data  : {"el_id": el_id},
      beforeSend: function()
        {
            $('#loadingMessage').show();
        },
      success   : function (response) {
        $('#loadingMessage').hide();
        //console.log(response.pdf_link);
        console.log(response.file_link);
        window.open(
          response.file_link,
          '_blank' // <- This is what makes it open in a new window.
        );
            // location.href = response.file_name;

            // setTimeout(function(){ deletePDF(response.path); }, 5000);
        }
    })
}

function generate_subsequent_el(sub_el_id)
{
    $('#loadingMessage').show();
    // console.log(auth_id);

    $.ajax({ //Upload common input
      url       : generate_engagement_letter_url,
      type      : "POST",
      dataType  : 'json',
      data  : {"sub_el_id": sub_el_id},
      beforeSend: function()
        {
            $('#loadingMessage').show();
        },
      success   : function (response) {
        $('#loadingMessage').hide();
        //console.log(response.pdf_link);
        console.log(response.file_link);
        window.open(
          response.file_link,
          '_blank' // <- This is what makes it open in a new window.
        );
            // location.href = response.file_name;

            // setTimeout(function(){ deletePDF(response.path); }, 5000);
        }
    })
}



function lodge_engagement(el_id)
{
    $('#loadingMessage').show();
    // console.log(auth_id);

    $.ajax({ //Upload common input
        url       : lodge_engagement_url,
        type      : "POST",
        dataType  : 'json',
        data  : {"el_id": el_id},
        beforeSend: function()
        {
            $('#loadingMessage').show();
        },
        success   : function (response) {
            $('#loadingMessage').hide();
            location.reload();

        }
    })
}

function lodge_subsequent_engagement(sub_el_id)
{
    $('#loadingMessage').show();
    console.log(sub_el_id);

    $.ajax({ //Upload common input
        url       : lodge_subsequent_engagement_url,
        type      : "POST",
        dataType  : 'json',
        data  : {"sub_el_id": sub_el_id},
        beforeSend: function()
        {
            $('#loadingMessage').show();
        },
        success   : function (response) {
            $('#loadingMessage').hide();
            location.reload();

        }
    })
}

function Client() {
    var base_url = window.location.origin;  
    var call = new ajaxCall();

    this.getClientName = function() {
        var url = base_url+"/"+folder+"/"+'engagement/getMovedEngagementClient';
        //console.log(url);
        var method = "get";
        var data = {};
        $('.client_name').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            //console.log(data);
            $('.client_name').find("option:eq(0)").html("Select Client Name");
            // console.log(data);
            if(data.tp == 1){
                $.each(data['result'], function(key, val) {
                    var option = $('<option />');
                    option.attr('value', key).text(val);
                    // if(selected_sub_el.company_code != null && key == selected_sub_el.company_code)
                    // {
                    //     option.attr('selected', 'selected');
                    //     $('.client_name').attr('disabled', true);
                        
                        
                    // }
                    // console.log(option);
                    $('.client_name').append(option);
                });
                $('#client_name').select2();
                // if(selected_sub_el.company_code != null)
                // {
                //     $('.client_name').change();
                // }
                
                //$(".nationality").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };
}

$(function() {
    var cn = new Client();
    cn.getClientName();

});

$('.search_report').click(function(e){
    e.preventDefault();
    $('#loadingMessage').show();
    $.ajax({
        type: 'POST',
        url: "engagement/search_moved_al",
        data: $('#el_record_form').serialize(),
        dataType: 'json',
        success: function(response){
            $('#loadingMessage').hide();
            $("#moved_el_body tr").remove();
            var letter_date, last_update, subsequent_flag, generate_pdf_function, hidden_id_input, upload_function, replace_paf_function ="";
            
            if(response.hasOwnProperty('new_date'))
            {
                letter_date = response.new_date;
                subsequent_flag = 1;
                generate_pdf_function = "generate_subsequent_el";
                upload_function = "open_sub_el_doc_modal";
                replace_paf_function = "open_subsequent_el_paf";
                hidden_id_input = '<input type="hidden" class="sub_el_id" value="'+response.id+'" />';
                if (subsequent_el_logs.hasOwnProperty(response.id))
                {
                    last_update = "<p style='font-size:11px;white-space: pre-line;margin:0;'>"+subsequent_el_logs[response.id]['user_name']+"\n<span style:font-size:9px;>"+subsequent_el_logs[response.id]['date_time'].replace(" ", " | ")+"</span></p>";
                }
                else
                {
                    // console.log(subsequent_el_logs);
                    last_update = "-";
                }
            }
            else 
            {
                letter_date = response.engagement_letter_date;
                subsequent_flag = 0;
                generate_pdf_function = "generate_engagement_letter";
                upload_function = "open_el_doc_modal";
                replace_paf_function = "open_initial_el_paf";
                hidden_id_input = '<input type="hidden" class="el_id" value="'+response.id+'" />';
                if (initial_el_logs.hasOwnProperty(response.id))
                {
                    last_update = "<p style='font-size:11px;white-space: pre-line;margin:0;'>"+initial_el_logs[response.id]['user_name']+"\n<span style:font-size:9px;>"+initial_el_logs[response.id]['date_time'].replace(" ", " | ")+"</span></p>";
                }
                else
                {
                    last_update = "-";
                }
            }



            $b =""; 
            $b += '<tr class="moved_letter">';
            $b += '<input type="hidden" name="subsequent_flag" value="'+subsequent_flag+'">';
            $b += '<td style="text-align:center;width:35%;"><div style="width:50%;display:inline-block;vertical-align:bottom;"><div class="input-group" style="width: 200px;margin:auto;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>'+
                    '<input disabled style="text-align:center;" autocomplete="off" type="text" class="form-control valid moved_letter_date" name="moved_letter_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="'+letter_date+'" placeholder="DD/MM/YYYY" onchange="change_letter_date(this)"></div></div>'+
                    '<button type="button" class="btn btn_blue" onclick="close_disable()">Change</button>'+
                        '<input class="form-control hidden_letter_id" id="hidden_letter_id" type="hidden" name="hidden_letter_id" value="'+response.id+'"></td>';
            $b += '<td style="text-align:center;width:35%;">'+last_update+'</td>';
            $b += '<td style="text-align:center;width:30%;">'+ hidden_id_input +
                  '<input type="hidden" class="company_name" value="'+response.company_name+'" />'+
                  '<button type="button" class="btn btn_blue" style="margin:4px;" onclick="'+generate_pdf_function+'('+response.id+')">PDF</button>'+
                  '<button type="button" class="btn btn_blue sub_el_upload_btn" onclick='+upload_function+'(this) style="margin:4px;">Upload</button>'+
                  '<button type="button" class="btn btn_blue" style="margin:4px;" onclick="'+replace_paf_function+'(this,true)">PAF</button></td>';
            $b += '</tr>';

            $("#moved_el_body").append($b);

            $('.moved_letter_date').datepicker({ 
                format: 'dd MM yyyy'
            });
        }
    });


})

function close_disable()
{
    $(".moved_letter_date").prop('disabled', false);
}

function change_letter_date(element){

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
                $('#loadingMessage').show();
                $.ajax({
                    type: 'POST',
                    url: "engagement/change_letter_date",
                    data: $('#el_record_form').serialize(),
                    dataType: 'json',
                    success: function(response){
                         $('#loadingMessage').hide();
                        if (response.status == 1) {
                            toastr.success(response.message, response.title);
                            $(".moved_letter_date").prop('disabled', true);
                        }
                        else
                        {
                            toastr.error(response.message, response.title);
                        }
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

$(document).on('click',"#replace_paf_btn",function(){

    $.post( "engagement/replace_initial_el_paf", { 'selected_move_el': selected_move_el }, function() {
        alert( "success" );
        // window.location.href = engagement_url;
    })
});

$(document).on('click',"#replace_sub_paf_btn",function(){

    $.post( "engagement/replace_subsequent_el_paf", { 'selected_move_sub_el': selected_move_sub_el }, function() {
        alert( "success" );
        // window.location.href = engagement_url;
    })
});
