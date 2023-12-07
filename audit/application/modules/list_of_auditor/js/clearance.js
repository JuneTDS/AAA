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

    if(active_tab != "first_clearance_letter")
    {
        $('li[data-information="'+active_tab+'"]').addClass("active");
        $('#w2-'+active_tab+'').addClass("active");
        $('li[data-information="first_clearance_letter"]').removeClass("active");
        $('#w2-first_clearance_letter').removeClass("active");
    }
    // console.log(active_tab);
    if(active_tab =="first_clearance_letter")
    {
    	$(".create_first_clearance_letter").show();
    }
    else{
    	$(".create_first_clearance_letter").hide();
    }

    if(active_tab =="resignation")
    {
        $(".create_resignation").show();
    }
    else{
        $(".create_resignation").hide();
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

	if(pv_index_tab_aktif == "first_clearance_letter")
    {
    	$(".create_first_clearance_letter").show();
    }
    else{
    	$(".create_first_clearance_letter").hide();
    }

    if(pv_index_tab_aktif == "resignation")
    {
        $(".create_resignation").show();
    }
    else{
        $(".create_resignation").hide();
    }



    // if(pv_index_tab_aktif =="bank_confirm")
    // {
    // 	$(".create_bank_confirmation").show();
    // }
    // else{
    // 	$(".create_bank_confirmation").hide();
    // }
});

$('.clearance_status').on('select2:selecting', function (evt) {
  	previous_clearance = $(this).val();

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

function delete_first_letter(element){
	var div 		= $(element).parent();
	var letter_id 	= div.find('.letter_id').val();
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
        		$.post("list_of_auditor/delete_first_letter", { 'letter_id': letter_id }, function(data, status){
		    	 	if(data){
		    	 		location.reload();
		    	 	}
		    	});
        	}
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
		    	 		location.reload();
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
		    	 		location.reload();
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
		    	 		location.reload();
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




// Update Status
function change_auth_status(element){
	var div 				= $(element).parent();
	var bank_auth_id   = div.find('.auth_id').val();


	// var assignment_id 		= div.find('.assignment_id').val();
	var status_id 			= div.find('.auth_status').val();
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

    			$.post("bank/updt_auth_status", { 'bank_auth_id': bank_auth_id , 'status_id': status_id}, function(data, status){
	    	 		if(data){
	    	 			location.reload();
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
        		location.reload();

        	}
        }
    })
}

function change_confirm_status(element){
	var div 				= $(element).parent();
	var bank_confirm_id   = div.find('.confirm_id').val();


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

    			$.post("bank/updt_confirm_status", { 'bank_confirm_id': bank_confirm_id , 'status_id': status_id}, function(data, status){
	    	 		if(data){
	    	 			location.reload();
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
        		location.reload();

        	}
        }
    })
}

// Edit bank confirmation send date
function change_sent_date(element){
	var div 				= $(element).parent();
	var bank_confirm_id   = div.parent().find('.confirm_id').val();


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

    			$.post("bank/updt_confirm_sent_date", { 'bank_confirm_id': bank_confirm_id , 'sent_on_date': date_value}, function(data, status){
	    	 		if(data){
	    	 			location.reload();
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
        		location.reload();

        	}
        }
    })
}

function change_clearance_status(element){
	var dropdown = $(element);
	var div 				= $(element).parent();
	var tr 					= div.parent().parent();
	var letter_id   = div.find('.letter_id').val();
	var upload_btn = tr.find('.clearance_upload_btn');
	// console.log(tr);

	// var assignment_id 		= div.find('.assignment_id').val();
	var status_id 			= div.find('.clearance_status').val();
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
        		if(status_id != 11){
        			$.post("list_of_auditor/updt_clearance_status", { 'letter_id': letter_id , 'status_id': status_id}, function(data, status){
		    	 		if(data){
		    	 			location.reload();
		    	 		}
		    		});
        		}
        		else
        		{
        			$("#upload_cl_doc_modal").find('.cl_upload_cancel').hide();
        			open_cl_doc_modal(upload_btn);
        			change_cl_status_flag = true;


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
				dropdown.val(previous_clearance);
				dropdown.select2();


        	}
        }
    })
}

function open_cl_doc_modal(element){
    initialPreviewArray = []; 
	initialPreviewConfigArray = [];
	clearance_files = "";

    var div 	= $(element).parent();
    var cl_id = div.find('.letter_id').val();
 
	$("#upload_cl_doc_modal").modal("show"); 
	// $('#loadingMessage').show();
	// console.log(auth_id);

	$.ajax({ //Upload common input
		url		: "list_of_auditor/get_clearance_doc_list",
		type 		: "POST",
		dataType	: 'json',
		data 	: {"letter_id": cl_id},
		beforeSend: function()
	    {
	        // $('#loadingMessage').show();
	    },
        success	: function (response) {
	        $('#loadingMessage').hide();
	        //console.log(response.pdf_link);
	        clearance_files = response[0].file_names;
	        // console.log(response);

	        for(var i = 0; i < clearance_list.length; i++)
		    {
		    	if(clearance_list[i]["id"] == cl_id)
		    	{
		    		$('.doc_cl_id').val(cl_id);
		    		$('#cl_client').val(clearance_list[i]["company_name"]);
		    		// console.log(bank_auth_list[i]);

		    	}

			}


		  if(clearance_files != null)
			{
				for (var i = 0; i < clearance_files.length; i++) {
					
				  var url = base_url + "/audit/document/clearance/";
				  // var url = base_url + "/test_audit/uploads/bank_images_or_pdf/";
				  var fileArray = clearance_files[i].split(',');
				  //console.log(fileArray[0]);
				  initialPreviewArray.push( url + fileArray[1] );
				  var file_type = fileArray[1].substring(fileArray[1].lastIndexOf('.'));
				  //console.log(file_type);
				  	if(file_type == ".pdf" || file_type == ".PDF")
				  	{
					  initialPreviewConfigArray.push({
						  type: "pdf",
					      caption: fileArray[1],
					      url: "/audit/list_of_auditor/deleteClFile/" + fileArray[0],
					      // url: "/test_audit/bank/deleteBcFile/" + fileArray[0],
					      width: "120px",
					      key: i+1
					  });
					}
					else
					{
						initialPreviewConfigArray.push({
					      caption: fileArray[1],
					      url: "/audit/list_of_auditor/deleteClFile/" + fileArray[0],
					      // url: "/test_audit/bank/deleteBcFile/" + fileArray[0],
					      width: "120px",
					      key: i+1
					  });
					}
				}

				console.log(initialPreviewArray);
			}
			$('#multiple_cl_doc').fileinput('destroy');

			$("#multiple_cl_doc").fileinput({
				'async' : false,
			    theme: 'fa',
			    uploadUrl: '/audit/list_of_auditor/uploadClDoc', // you must set a valid URL here else you will get an error
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
			    initialPreviewDownloadUrl: base_url + '/audit/document/clearance/{filename}',
			    // initialPreviewDownloadUrl: base_url + '/test_audit/uploads/bank_images_or_pdf/{filename}',
			    initialPreview: initialPreviewArray,
			 	initialPreviewConfig: initialPreviewConfigArray,
			    uploadExtraData: function() {
		            return {
		                letter_id: $(".doc_cl_id").val()
		                // username: $("#username").val()
		            };
		        }

			});
        }
    })

}

function open_clearance_paf(element)
{
    $("#clearance_paf_modal").modal("show"); 
    $("#clearance_paf_modal .attachment_tr").remove()
    var tr = $(element).parent().parent();
    var div     = $(element).parent();
    var cl_id = div.find('.letter_id').val();
    var modal = $("#clearance_paf_modal");
    var table = "";

    modal.find('.panel-title').html(tr.find('.company_name').text()+'<br/><label class="control-label" style="font-size:13px;"><i>File clearance letter to Permanent Audit File</i></label>');

    $("#loadingMessage").show();

    $.ajax({
        type: "POST",
        url: get_clearance_attachment_url,
        data: '&cl_id=' + cl_id,
        async: false,
        dataType: "json",
        success: function(data){
           
            selected_move_cl = data.attachment;

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
            $(table).appendTo('#clearance_attachment_table');  
            $("#loadingMessage").hide();
        }
    });    


}

$(document).on('click',"#move_paf_btn",function(){


    $.post( "list_of_auditor/move_clearance_to_paf", { 'selected_move_clearance': selected_move_cl }, function() {
        alert( "success" );
        window.location.href = clearance_url;
    })
});


$( "#upload_cl_doc_btn" ).click(function() {
  	$("#multiple_cl_doc").fileinput('upload');
});

$(document).on('click',".cl_upload_cancel",function(){
    $.post("list_of_auditor/clear_delete_clearance_session");
});

$("#multiple_cl_doc").fileinput({
    'async' : false,
    theme: 'fa',
    uploadUrl: '/audit/list_of_auditor/uploadClDoc', // you must set a valid URL here else you will get an error
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
    initialPreviewDownloadUrl: base_url + '/audit/document/clearance/{filename}',
    // initialPreviewDownloadUrl: base_url + '/test_audit/uploads/bank_images_or_pdf/{filename}',
    initialPreview: initialPreviewArray,
    initialPreviewConfig: initialPreviewConfigArray,

    uploadExtraData: function() {
        return {
            letter_id: $(".doc_cl_id").val()
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
        if(change_cl_status_flag)
        {
            if(data.response != "empty")
            {
                $.post("list_of_auditor/updt_clearance_status", { 'letter_id': data.extra.letter_id , 'status_id': 11}, function(data, status){
                    if(data){
                        location.reload();
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
        $("#upload_cl_doc_modal").modal("hide");
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


