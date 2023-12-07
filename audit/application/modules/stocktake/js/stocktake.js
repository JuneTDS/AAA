	
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
	    	}
	    }
	});

	$(document).on('click',".edit_auditor",function(){
	    var edit_auditor_id =  $(this).data("id");
	 
	    for(var i = 0; i < auditor_list.length; i++)
	    {
	    	if(auditor_list[i]["id"] == edit_auditor_id)
	    	{
	    		$(".auditor_list_id").val(auditor_list[i]["id"]);
    			$("#audit_firm_name").val(auditor_list[i]["audit_firm_name"]);
    			$("#audit_firm_email").val(auditor_list[i]["audit_firm_email"]);
    			$("#postal_code").val(auditor_list[i]["postal_code"]);
    			$("#street_name").val(auditor_list[i]["street_name"]);
    			$("#building_name").val(auditor_list[i]["building_name"]);
    			$("#unit_no1").val(auditor_list[i]["unit_no1"]);
    			$("#unit_no2").val(auditor_list[i]["unit_no2"]);
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
	    		console.log("match");
	    		$(".bank_confirm_setting_list_id").val(bank_confirm_setting[i]["setting_id"]);
    			$(".confirm_month").val(bank_confirm_setting[i]["confirm_month"]);
    			var cn = new Client();
	    		cn.getPicName(bank_confirm_setting[i]["pic_id"]);
    			
	    	}
	    }

	
	});
	//$('.edit_type_of_leave').live("click",function(){

	$('[data-toggle="tooltip"]').tooltip();

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	$('.confirm_month').datepicker({
	    autoclose: true,
	    minViewMode: 1,
	    format: 'MM yyyy'
	});

	

	$('.datepicker').datepicker({
		format: 'dd MM yyyy',
	});

	$('.carry_forward_period_datepicker').datepicker({
		format: 'dd MM',
		//viewMode: 'months'
		// minViewMode: 'months',
		// maxViewMode: 'months',
	}).on('show', function() {
	    // remove the year from the date title before the datepicker show
	    var dateText  = $(".datepicker-days .datepicker-switch").text();
	    var dateTitle = dateText.substr(0, dateText.length - 5);
	    $(".datepicker-days .datepicker-switch").text(dateTitle);
	});

	var userTarget1 = "";
	var exit1 = false;
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

	$(document).on('change','[type=file]',function(){
	    var filename = "";
	    //console.log(this.files[0]);
	    for(var i = 0; i < this.files.length; i++)
	    {
	    if(i == 0)
	    {
	        filename = this.files[i].name;
	    }
	    else
	    {
	        filename = filename + ", " + this.files[i].name;
	    }
	    }
	    $(this).parent().find(".file_name").html(filename);
	    $(this).parent().find(".hidden_attachment").val("");
	});

	$("#subsequent_submit").submit(function(e) {
        var form = $(this);
        $("#loadingMessage").show();

        var formData = new FormData($('form')[0]);
        // console.log(formData);
        $.ajax({
           type: "POST",
           url: "stocktake/submit_subsequent",
           data: formData, // serializes the form's elements.
           dataType: 'json',
           cache: false,
	        contentType: false,
	        processData: false,
           success: function(data)
           {	
           		if(data){
           			$("#loadingMessage").hide();
           			toastr.success('Information Updated', 'Updated');
           			location.reload();
           		}
               // alert(data); // show response from the php script.
               // $('#applicant_resume').fileinput('upload');
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $(document).on('click',"#saveEditSubsequent",function(){
    	var selected_status = $(".edit_sts_status").val();

		if(selected_status == 1 && !$('.edit_remark').val())
		{
			toastr.error("Remark is required for KIV status.", "Error");
			$('.remark').focus();
		}
		else if(selected_status == 3 && ($('#edit_attachment_btn').get(0).files.length === 0 && $("#edit_attachment_table").find(".tr_subsequent_doc").length === 0 ))
		{
			// console.log();
			toastr.error("Attachment is required for Completed status.", "Error");
			$('#edit_attachment_btn').focus();
		}
		else
		{
			$("#edit_subsequent_submit").submit();
		}
	    

	    // $("#edit_subsequent_submit").submit();
	    // console.log("submitting");
	});

	$(document).on('click',"#saveSubsequent",function(){
		var selected_status = $(".sts_status").val();

		if(selected_status == 1 && !$('.remark').val())
		{
			toastr.error("Remark is required for KIV status.", "Error");
			$('.remark').focus();
		}
		else if(selected_status == 3 && $('#attachment').get(0).files.length === 0)
		{
			toastr.error("Attachment is required for Completed status.", "Error");
			$('#attachment').focus();
		}
		else
		{
			$("#subsequent_submit").submit();
		}
	    
	    // console.log("submitting");
	});

	$(document).on('click',"#edit_last_update",function(){
		var cur_datetime = moment().format('YYYY-MM-DD hh:mm:ss');
	    $(".edit_last_update").val(cur_datetime);
	    $(".edit_last_update_span").html(cur_datetime);
	    // console.log("submitting");
	});

    $("#edit_subsequent_submit").submit(function(e) {
    	// console.log($(this));
        var form = $(this);
        $("#loadingMessage").show();
        // $("#loadingMessage").hide();

        var formData = new FormData(this);
        // console.log(formData);
        $.ajax({
           type: "POST",
           url: "stocktake/submit_edit_subsequent",
           data: formData, // serializes the form's elements.
           dataType: 'json',
           cache: false,
           async:false,
	        contentType: false,
	        processData: false,
           success: function(response)
           {	
           
           
	                //var errorsDateOfCessation = ' ';
                toastr.success(response.message, response.title);
                
                $("#edit_subsequent_modal").modal("hide");
       			$("#loadingMessage").hide();
       			location.reload();
	                
	    
           		// if(data){
           			
           		// }
               // alert(data); // show response from the php script.
               // $('#applicant_resume').fileinput('upload');
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
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

	    if(active_tab != "st_arrangement")
	    {
	        $('li[data-information="'+active_tab+'"]').addClass("active");
	        $('#w2-'+active_tab+'').addClass("active");
	        $('li[data-information="st_arrangement"]').removeClass("active");
	        $('#w2-st_arrangement').removeClass("active");
	    }
	    // console.log(active_tab);
	    if(active_tab =="st_arrangement")
	    {
	    	$(".create_st_arrangement").show();
	    }
	    else{
	    	$(".create_st_arrangement").hide();
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

		if(pv_index_tab_aktif == "st_arrangement")
	    {
	    	$(".create_st_arrangement").show();
	    }
	    else{
	    	$(".create_st_arrangement").hide();
	    }


	    // if(pv_index_tab_aktif =="bank_confirm")
	    // {
	    // 	$(".create_bank_confirmation").show();
	    // }
	    // else{
	    // 	$(".create_bank_confirmation").hide();
	    // }
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

    function generate_reminder(element)
    {
    	var div 		= $(element).parent();
    	var reminder_id 	= div.find('.reminder_id').val();
    	// console.log(auth_id);

    	$.ajax({ //Upload common input
	      url		: "stocktake/generate_reminder_document",
	      type 		: "POST",
	      dataType	: 'json',
	      data 	: {"reminder_id": reminder_id},
	      beforeSend: function()
		    {
		        $('#loadingMessage').show();
		    },
	      success	: function (response,data) {
	        $('#loadingMessage').hide();
	        //console.log(response.pdf_link);
	        // console.log("done");
	            window.open(
	              response.pdf_link,
	              '_blank' // <- This is what makes it open in a new window.
	            );

	            setTimeout(function(){ deletePDF(response.path); }, 5000);
	        }
	    })
    }


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

	            setTimeout(function(){ deletePDF(response.path); }, 5000);
	        }
	    })
    }

    function done_sta(element)
	{
		$("#subsequent_modal").modal("show"); 
		var tr = $(element).parent().parent();
		var modal = $("#subsequent_modal");

		// $('#fin_period').find('option').not(':nth-child(1)').remove();

		// var cn = new Client();
	 //    cn.getFinPeriod(tr.find('.company_code').val());

		console.log(tr);
		modal.find('.arrangement_id').val(tr.find('.sta_id').val());
		modal.find('.panel-title').html(tr.find('.company_name').val()+'<br/><label class="control-label" style="font-size:13px;"><i>Stock take completion</i></label>');
	}

	function edit_subsequent(sta_id)
	{
		console.log(stocktake_subsequent_list);
		// console.log("this"+sta_id);
		$('#loadingmessage').css('display', 'block');
		$("#edit_attachment_table tr").remove()
		$("#edit_subsequent_modal").modal("show");

		var table = "";
		var modal = $("#edit_subsequent_modal");
		$("#search_cleared").val("");

	
		
		for(var i = 0; i < stocktake_subsequent_list.length; i++)
	    {
	    	if(stocktake_subsequent_list[i]["id"] == sta_id)
	    	{
	    		$(".edit_sts_status").val(stocktake_subsequent_list[i]["status"]);
	    		$(".edit_remark").val(stocktake_subsequent_list[i]["remark"])
	    		$('.edit_last_update').val(stocktake_subsequent_list[i]["updated_at"]);
	    		$('.edit_last_update_span').html(stocktake_subsequent_list[i]["updated_at"]);
	    		$('.arrangement_id').val(sta_id);
	    		modal.find('.panel-title').html(stocktake_subsequent_list[i]["company_name"] + '<br/>Edit Stock take completion ');

			    var count_attachment = 0;

		     
		        if(stocktake_subsequent_list[i].attachment != "")
		        {
		            for(var a = 0; a <stocktake_subsequent_list[i].attachment.length; a++)
		            {
		                table += '<tr class="tr_subsequent_doc">'+
		                            '<input type="hidden" class="doc_id" value="'+stocktake_subsequent_list[i].attachment[a].id+'">'+
		                            '<input type="hidden" class="file_name" value="'+stocktake_subsequent_list[i].attachment[a].file_name+'">'+
		                            '<th style="width:75%;"><a href="'+ base_url + '/'+folder+'/document/'+stocktake_subsequent_list[i].attachment[a].file_path+'/'+stocktake_subsequent_list[i].attachment[a].file_name+'" target="_blank" >'+stocktake_subsequent_list[i].attachment[a].file_name+'</a></th>'+
		                            '<td style="width:25%"><button style="margin-right:5px;margin-left:5px;" type="button" class="btn btn_blue" onclick=delete_doc(this)>Delete</button></td>'+
		                         '</tr>';
		            }
		        }
		        else
		        {
		            table = '<tr><td align="center"><p style="margin-top:10px;">No document(s) to display</p></td></tr>'

		        }
		    }

		    
	    }            
        
        $(table).appendTo('#edit_attachment_table');
    

	    $(".edit_sts_status").select2();


	    //retrieve review point from DB each time user open the modal
        $.ajax({
            type: "POST",
            url: filter_review_points_url,
            data: '&cleared=' + "" + '&sta_id=' + sta_id, // <--- THIS IS THE CHANGE
            async: false,
            dataType: "json",
            success: function(response){
                $('#loadingmessage').hide();
                $(".sort_id").remove();

                review_point_info = response;
                // get_review_points();
            }
        });

	    // Review point table
	    get_review_points(review_point_info, sta_id);
	    
	}



    function generate_auth(element)
    {
    	var div 		= $(element).parent();
    	var auth_id 	= div.find('.auth_id').val();
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

	            setTimeout(function(){ deletePDF(response.path); }, 5000);
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

    //Activate stocktake setting
    function change_stocktake_setting(element, active){
    	var div 		= $(element).parent();
    	var company_code 	= div.parent().find('.company_code').val();
    	// $("#loadingMessage").show();
    	// console.log(company_code);

		var msg = '';

		if(element.checked){
			var active = 1;
			msg = "Do you want to activate this selected info?"
		}
		else
		{
			var active = 0;
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
	        		$("#loadingMessage").show();
	        		$.post("stocktake/update_stocktake_reminder", { 'company_code': company_code , 'active': active}, function(data, status){
			    	 	if(data){
			    	 		// location.reload();
			    	 		
			    	 		data = JSON.parse(data);
			    	 		// console.log(data);
							toastr.success(data.message, data.title);
							$("#loadingMessage").hide();			    	 	
						}
			    	});
	        	}
	        	else
	        	{
	        		if (active == 1)
	        		{
	        			$(element).prop("checked", false);
	        		}
	        		else
	        		{
	        			$(element).prop("checked", true);
	        			// console.log($(element));
	        		}
	        		// console.log(element);
	       		
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

    function delete_sta(element){
    	var div         = $(element).parent();
	    var sta_id     = div.find('.sta_id').val();
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
	                $.post(delete_sta_url, { 'sta_id': sta_id }, function(data, status){
	                    if(data){
	                        location.reload();
	                    }
	                });
	            }
	        }
	    })
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
	    var sta_id = tr.find(".sta_id").val();
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
	                check_cleared_points(sta_id);

	                
	            }

	            if (response.Status === 0)
	            {
	                toastr.error(response.message, response.title);
	            } 
	        }
	    });
	}




    // FILTER ------------------------------------------------------------------------------------------------------------------------------------------//
	// MONTH FILTER
	$(document).on('change',".sta_month_filter",function(){
		// var employee  = $(".timesheet_employee_filter").val();
		// var year      = $(".timesheet_year_filter").val();
		var month     = $(".sta_month_filter").val();
		var auditor    = $(".sta_auditor_filter").val();
		var arranged =  $(".sta_arranged_filter").val();
		var office = $(".sta_office_filter").val();
		var department = $(".sta_department_filter").val();

		filter_arranged_table(month, auditor, arranged, office, department);

	   	
	});

	// AUDITOR FILTER
	$(document).on('change',".sta_auditor_filter",function(){
		// var employee  = $(".timesheet_employee_filter").val();
		// var year      = $(".timesheet_year_filter").val();
		var month     = $(".sta_month_filter").val();
		var auditor    = $(".sta_auditor_filter").val();
		var arranged =  $(".sta_arranged_filter").val();
		var office = $(".sta_office_filter").val();
		var department = $(".sta_department_filter").val();

		filter_arranged_table(month, auditor, arranged, office, department);
	   
	});

	// Arranged FILTER
	$(document).on('change',".sta_arranged_filter",function(){
		// var employee  = $(".timesheet_employee_filter").val();
		// var year      = $(".timesheet_year_filter").val();
		var month     = $(".sta_month_filter").val();
		var auditor    = $(".sta_auditor_filter").val();
		var arranged =  $(".sta_arranged_filter").val();
		var office = $(".sta_office_filter").val();
		var department = $(".sta_department_filter").val();

		filter_arranged_table(month, auditor, arranged, office, department);

	});

	//office filter
	$(document).on('change',".sta_office_filter",function(){
		// var employee  = $(".timesheet_employee_filter").val();
		// var year      = $(".timesheet_year_filter").val();
		var month     = $(".sta_month_filter").val();
		var auditor    = $(".sta_auditor_filter").val();
		var arranged =  $(".sta_arranged_filter").val();
		var office = $(".sta_office_filter").val();
		var department = $(".sta_department_filter").val();

		filter_arranged_table(month, auditor, arranged, office, department);

	});

	//department filter
	$(document).on('change',".sta_department_filter",function(){
		// var employee  = $(".timesheet_employee_filter").val();
		// var year      = $(".timesheet_year_filter").val();
		var month     = $(".sta_month_filter").val();
		var auditor    = $(".sta_auditor_filter").val();
		var arranged =  $(".sta_arranged_filter").val();
		var office = $(".sta_office_filter").val();
		var department = $(".sta_department_filter").val();

		filter_arranged_table(month, auditor, arranged, office, department);

	});


	// SUBSEQUENT MONTH FILTER
	$(document).on('change',".sts_month_filter",function(){
		// var employee  = $(".timesheet_employee_filter").val();
		// var year      = $(".timesheet_year_filter").val();
		var month     = $(".sts_month_filter").val();
		var auditor    = $(".sts_auditor_filter").val();
		var office    = $(".sts_office_filter").val();
		var department    = $(".sts_department_filter").val();

		filter_subsequent_table(month, auditor, officer, department);

	   	
	});

	// SUBSEQUENT AUDITOR FILTER
	$(document).on('change',".sts_auditor_filter",function(){
		// var employee  = $(".timesheet_employee_filter").val();
		// var year      = $(".timesheet_year_filter").val();
		var month     = $(".sts_month_filter").val();
		var auditor    = $(".sts_auditor_filter").val();
		var office    = $(".sts_office_filter").val();
		var department    = $(".sts_department_filter").val();

		filter_subsequent_table(month, auditor, office, department);

	   
	});

	$(document).on('change',".sts_office_filter",function(){
		// var employee  = $(".timesheet_employee_filter").val();
		// var year      = $(".timesheet_year_filter").val();
		var month     = $(".sts_month_filter").val();
		var auditor    = $(".sts_auditor_filter").val();
		var office    = $(".sts_office_filter").val();
		var department    = $(".sts_department_filter").val();

		filter_subsequent_table(month, auditor, office, department);

	});

	//department filter
	$(document).on('change',".sts_department_filter",function(){
		// var employee  = $(".timesheet_employee_filter").val();
		// var year      = $(".timesheet_year_filter").val();
		var month     = $(".sts_month_filter").val();
		var auditor    = $(".sts_auditor_filter").val();
		var office    = $(".sts_office_filter").val();
		var department    = $(".sts_department_filter").val();

		filter_subsequent_table(month, auditor, office, department);

	});



	function filter_arranged_table(month, auditor, arranged, office, department)
	{
		$.ajax({
	       type: "POST",
	       url:  "stocktake/stocktake_arrangement_filter",
	       data: '&month=' + month + '&auditor=' + auditor + '&arranged=' + arranged + '&office=' + office+ '&department=' + department,
	       success: function(data)
	       {

	       		var auditor_name;
       			var stocktake_date;
       			var stocktake_time;
       			var stocktake_address;
       			var client_pic;

	       		if(JSON.parse(data)==null || JSON.parse(data)==""){
	    //    			$("#datatable-stocktake_arrangement").DataTable().destroy();
	    //    			var table  = $("#datatable-stocktake_arrangement").DataTable();
	    //         		var object = [{}];
	    //         		console.log(object);
					// t_sa.clear().draw();
					// $(".sta_tr").remove();
					// $("#datatable-stocktake_arrangement").DataTable().clear();
					// $("#datatable-stocktake_arrangement").DataTable().reload();

					$("#datatable-stocktake_arrangement").DataTable().destroy();
	       			var table  = $("#datatable-stocktake_arrangement").DataTable();
	           		var object = (JSON.parse(data));
					table.clear().draw();
	       		}

	       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){

	       			$("#datatable-stocktake_arrangement").DataTable().destroy();

	       			// t_sa.clear().draw();
	           		var object = (JSON.parse(data));
	           		var rowAuditorName = "";
	           		console.log(object);
	           		console.log(object.length);
	           		$(".sta_tr").remove();
	           		// var http = '<?php echo base_url() ?>';

	           		for(var i=0; i<object.length; i++){
	           			rowAuditorName = "";
	           			// console.log(object[i]);
	       //     			foreach ($each->auditor_name as $value) {
		  				// 	$rowAuditorName .= '<tr><td>'.$value.'</td></tr>';
		  				// }
		  				for(var a=0; a<object[i]['auditor_name'].length; a++)
		  				{
		  					rowAuditorName += '<tr><td>'+object[i]['auditor_name'][a]+'</td></tr>';
		  				}

		  				// '<td style="width:15%;">'.($each->auditor_id?"<table class='table table-bordered table-striped mb-none'>".$rowAuditorName."</table>":"<b>Not Arranged</b>").'</td>';



	           			if(object[i]['auditor_id'] != 0)
	           			{
	           				auditor_name = "<table class='table table-bordered table-striped mb-none'>"+rowAuditorName+"</table>";
	           			}
	           			else
	           			{
	           				auditor_name = "<b>Not Arranged</b>";
	           			}

	           			if(object[i]['stocktake_date'] != "0000-00-00")
	           			{
	           				stocktake_date = object[i]['stocktake_date'];
	           			}
	           			else
	           			{
	           				stocktake_date = "<b>Not Arranged</b>";
	           			}

	           			if(object[i]['stocktake_time'] != "00:00:00")
	           			{
	           				stocktake_time = object[i]['stocktake_time'];
	           			}
	           			else
	           			{
	           				stocktake_time = "<b>Not Arranged</b>";
	           			}

	           			if(object[i]['stocktake_address'] != "")
	           			{
	           				stocktake_address = object[i]['stocktake_address'];
	           			}
	           			else
	           			{
	           				stocktake_address = "<b>Not Arranged</b>";
	           			}

	           			if(object[i]['client_pic'] != "")
	           			{
	           				client_pic = object[i]['client_pic'];
	           			}
	           			else
	           			{
	           				client_pic = "<b>Not Arranged</b>";
	           			}

	           			var rowHtml = 	"<tr class='sta_tr'>"+
	           							  '<input type="hidden" class="sta_id" value="'+object[i]['sta_id']+'" />'+
								  		  "<input type='hidden' class='company_name' value='"+object[i]['company_name']+"' />"+
								  		  "<input type='hidden' class='reminder_id' value='"+object[i]['reminder_id']+"' />"+
		           						  "<td style='width:25%; id=desc'"+ object[i]['sta_id'] +"'>"+
		           						  		"<a href='stocktake/edit_stocktake_arrangement/"+object[i]['sta_id']+"' class='pointer mb-sm mt-sm mr-sm'>"+object[i]['company_name']+"</a>"+
		           						  "</td>"+
		           						  "<td style='width:8%;'>"+object[i]['fye_date']+"</td>"+
		           						  "<td style='width:15%;'>"+auditor_name+"</td>"+
		           						  "<td style='width:8%;'>"+stocktake_date+"</td>"+
		           						  "<td style='width:8%;'>"+stocktake_time+"</td>"+
		           						  "<td style='width:20%;'>"+stocktake_address+"</td>"+
		           						  "<td style='width:14%;'>"+client_pic+"</td>"+
		           						  "<td style='width:8%;'>"+
		           						    "<button type='button' class='btn btn_blue' onclick=done_sta(this) style='margin-bottom:5px;margin-right:5px;margin-left:5px;'>Done</button>"+
		           						  	"<input type='hidden' class='sta_id' value='"+ object[i]['sta_id'] +"' /><button type='button' class='btn btn_blue' onclick=delete_first_letter(this) style='margin-bottom:5px;'>Delete</button>"+
		           						  "</td>"+
		           						"</tr>";

		           		// console.log(rowHtml);
	           			$(".stocktake_arrangement_body").append(rowHtml);

	           			


	           		}
	           		

	       		}




	           	var t_c = $('#datatable-stocktake_arrangement').DataTable( {
	           		'rowsGroup': [0,1,7]
			    	// "order": [[ 5, 'desc' ]]
			    	// "order": [[ 3, 'asc' ]]
			    });


		
	       }
	   	});

	}

	function filter_subsequent_table(month, auditor, office, department)
	{
		$.ajax({
	       type: "POST",
	       url:  "stocktake/stocktake_subsequent_filter",
	       data: '&month=' + month + '&auditor=' + auditor + '&office=' + office+ '&department=' + department,
	       success: function(data)
	       {

	       		var auditor_name;
       			var stocktake_date;
       			var stocktake_time;
       			var stocktake_address;
       			var client_pic;

	       		if(JSON.parse(data)==null || JSON.parse(data)==""){
	    //    			$("#datatable-stocktake_arrangement").DataTable().destroy();
	    //    			var table  = $("#datatable-stocktake_arrangement").DataTable();
	    //         		var object = [{}];
	    //         		console.log(object);
					// t_sa.clear().draw();
					// $(".sta_tr").remove();
					// $("#datatable-stocktake_arrangement").DataTable().clear();
					// $("#datatable-stocktake_arrangement").DataTable().reload();

					$("#datatable-stocktake_subsequent").DataTable().destroy();
	       			var table  = $("#datatable-stocktake_subsequent").DataTable();
	           		var object = (JSON.parse(data));
					table.clear().draw();
	       		}

	       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){

	       			$("#datatable-stocktake_subsequent").DataTable().destroy();

	       			// t_sa.clear().draw();
	           		var object = (JSON.parse(data));
	           		var rowAuditorName = "";
	           		console.log(object);
	           		console.log(object.length);
	           		$(".sts_tr").remove();
	           		// var http = '<?php echo base_url() ?>';
	           		// console.log(object);


	           		for(var i=0; i<object.length; i++){
	           			rowAuditorName = "";
	           			// console.log(object[i]);
	       //     			foreach ($each->auditor_name as $value) {
		  				// 	$rowAuditorName .= '<tr><td>'.$value.'</td></tr>';
		  				// }
		  				for(var a=0; a<object[i]['auditor_name'].length; a++)
		  				{
		  					rowAuditorName += '<tr><td>'+object[i]['auditor_name'][a]+'</td></tr>';
		  				}

		  				// '<td style="width:15%;">'.($each->auditor_id?"<table class='table table-bordered table-striped mb-none'>".$rowAuditorName."</table>":"<b>Not Arranged</b>").'</td>';



	           			if(object[i]['auditor_id'] != 0)
	           			{
	           				auditor_name = "<table class='table table-bordered table-striped mb-none'>"+rowAuditorName+"</table>";
	           			}
	           			else
	           			{
	           				auditor_name = "<b>Not Arranged</b>";
	           			}

	           			if(object[i]['stocktake_date'] != "0000-00-00")
	           			{
	           				stocktake_date = object[i]['stocktake_date'];
	           			}
	           			else
	           			{
	           				stocktake_date = "<b>Not Arranged</b>";
	           			}

	           			if(object[i]['stocktake_time'] != "00:00:00")
	           			{
	           				stocktake_time = object[i]['stocktake_time'];
	           			}
	           			else
	           			{
	           				stocktake_time = "<b>Not Arranged</b>";
	           			}

	           			if(object[i]['stocktake_address'] != "")
	           			{
	           				stocktake_address = object[i]['stocktake_address'];
	           			}
	           			else
	           			{
	           				stocktake_address = "<b>Not Arranged</b>";
	           			}

	           			if(object[i]['client_pic'] != "")
	           			{
	           				client_pic = object[i]['client_pic'];
	           			}
	           			else
	           			{
	           				client_pic = "<b>Not Arranged</b>";
	           			}

	           			var rowHtml = 	"<tr class='sts_tr'>"+
	           							  '<input type="hidden" class="sta_id" value="'+object[i]['id']+'" />'+
								  		  "<input type='hidden' class='company_name' value='"+object[i]['company_name']+"' />"+
		           						  "<td style='width:25%;' id='desc"+ object[i]['id'] +"'><a href='stocktake/edit_stocktake_arrangement/"+object[i]['id']+"' class='pointer mb-sm mt-sm mr-sm'>"+object[i]['company_name']+"</a>"+
		           						  	"<div class='review_point_icon'><a href='javascript:void(0)' onclick='edit_subsequent('"+ object[i]['id'] +")' class='rp view_rp'><img src='" + php_base_url + "/img/R_icon2.png' style='width:25px;height:25px;'></a>"+
						  					    "<a href='javascript:void(0)'' onclick='edit_subsequent("+ object[i]['id'] +")' class='cleared_rp view_rp'><img src='" + php_base_url + "/img/R_strike_icon2.png'  style='width:25px;height:25px;'></a>"+
						  					"</div>"+
		           						  "</td>"+
		           						  "<td style='width:8%;'>"+object[i]['fye_date']+"</td>"+
		           						  "<td style='width:15%;'>"+auditor_name+"</td>"+
		           						  "<td style='width:10%;'>"+object[i]['updated_at']+"</td>"+
		           						  "<td style='width:26%;'>"+object[i]['remark']+"</td>"+
		           						  "<td style='width:8%;'>"+object[i]['status_text']+"</td>"+		           						
		           						  "<td style='width:8%;'>"+
		           						  	"<input type='hidden' class='sta_id' value='"+ object[i]['id'] +"' /><input type='hidden' class='reminder_id' value='"+object[i]['reminder_id']+"' /><button type='button' class='btn btn_blue caf_btn' onclick=subsequent_caf(this) style='margin-bottom:5px;margin-right:5px;margin-left:5px;'>CAF</button>"+
		           						  "</td>"+
		           						"</tr>";
	           			$(".stocktake_subsequent_body").append(rowHtml);

	           			check_cleared_points(object[i]['id']);
	           		}
	           		

	       		}




	           	var t_c = $('#datatable-stocktake_subsequent').DataTable( {
	           		
			    });
		
	       }
	   	});

	}
	// FILTER ------------------------------------------------------------------------------------------------------------------------------------------//
	
	// fix maximum call stack for rowsgroup
	$(document).on( 'destroy.dt', function ( e, settings ) {
		var api = new $.fn.dataTable.Api( settings );
		api.off('order.dt');
		api.off('preDraw.dt');
		api.off('column-visibility.dt');
		api.off('search.dt');
		api.off('page.dt');
		api.off('length.dt');
		api.off('xhr.dt');
	});


	

    function edit_point(element)
	{
	    var tr = jQuery(element).parent().parent();
	    console.log(tr);
	    if(!tr.hasClass("p_editing")) 
	    {
	        tr.addClass("p_editing");
	        tr.find('.point').removeAttr("readonly");
	        tr.find($(".point_action_icon")).removeClass('fa-edit').addClass('fa-save');
	 
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
	                var sta_id = tr.find(".sta_id").val();
                	check_cleared_points(sta_id);
	                
	            }
	        }
	    });
	}

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
        var sta_id = $('.arrangement_id').val();

        $count_review_point_info++;
        $a=""; 
        $a += '<form class="tr r_editing p_editing sort_id" method="post" name="form'+$count_review_point_info+'" id="form'+$count_review_point_info+'" >';

        $a += '<div class="hidden"><input type="text" class="form-control sta_id" name="sta_id" value="'+sta_id+'"/></div>';
        $a += '<div class="hidden"><input type="text" class="form-control review_point_id" name="review_point_id" value=""/></div>';

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

        // $('#form'+$count_event_info).find('.datepicker').datepicker({
        //     format: 'dd MM yyyy',
        // });
    });

	function get_review_points(review_point_info, sta_id)
    {

        if(review_point_info)
        {
            // var sta_id = $('#company_code').val();

            // console.log(review_point_info);
            for(var i = 0; i < review_point_info.length; i++)
            {
                if(!$("#desc"+ review_point_info[i]["stocktake_arrangement_id"]).find(".cleared_rp:visible").length > 0)
                {
                    $("#desc"+ review_point_info[i]["stocktake_arrangement_id"]).find(".rp").show();
                }
                
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

                $a += '<div class="hidden"><input type="text" class="form-control sta_id" name="sta_id" value="'+sta_id+'"/></div>';
                $a += '<div class="hidden"><input type="text" class="form-control review_point_id" name="review_point_id" value="'+review_point_info[i]["id"]+'"/></div>';
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

            }
        }
    }

    $('#search_cleared').change(function() {
        $('#loadingmessage').css('display', 'block');
        var sta_id     = $("#edit_subsequent_modal").find('.arrangement_id').val();
        var cleared    = $("#search_cleared").val();

        //console.log($('#loadingmessage'));
        $.ajax({
            type: "POST",
            url: filter_review_points_url,
            data: '&cleared=' + cleared + '&sta_id=' + sta_id, // <--- THIS IS THE CHANGE
            async: false,
            dataType: "json",
            success: function(response){
                $('#loadingmessage').hide();
                $(".sort_id").remove();

                review_point_info = response;
                // get_review_points();
            }
        });

	    // Review point table
	    get_review_points(review_point_info, sta_id);
	    
    });



    function delete_review_point(element)
    {
        var tr = jQuery(element).parent().parent().parent();

        var rp_id = tr.find('.review_point_id').val();
        var sta_id = tr.find(".sta_id").val();

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
                                    check_cleared_points(sta_id);

                                }
                            }
                        });
                    }
                }
            }
        })
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

	            	
	        		$.post(delete_subsequent_doc_url, { 'doc_id': doc_id }, function(data, status){
	                if(data){
	                		
	    					tr.closest("tr").remove();
	    					if($('#edit_attachment_table tr').length < 1)
	    					{
	    						$('<tr><td align="center"><p style="margin-top:10px;">No document(s) to display</p></td></tr>').appendTo('#edit_attachment_table');
	    					}
	                        // location.reload();
	         
	                    }
	                });
	            
	                
	            }
	        }
	    })
	}

	function subsequent_caf(element)
	{
		$("#subsequent_caf_modal").modal("show"); 
		var tr = $(element).parent().parent();
		var modal = $("#subsequent_caf_modal");

		$('#fin_period').find('option').not(':nth-child(1)').remove();

		var cn = new Client();
	    cn.getFinPeriod(tr.find('.company_code').val());

		console.log(tr);
		modal.find('.panel-title').html(tr.find('.company_name').val()+'<br/><label class="control-label" style="font-size:13px;"><i>File stock take file to Current Audit File</i></label>');
	}

	

