<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" />
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<!-- <script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script> -->
<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>


<script src="<?= base_url()?>assets/vendor/dataTables.rowsGroup.js"></script>


<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<script src="<?= base_url() ?>node_modules/moment/moment.js"></script>
<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootbox/bootbox.min.css" /> -->

<section role="main" class="content_section" style="margin-left:0;">
	<header class="panel-heading">
		<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
			<!-- <div class="panel-actions" id="create_paf">
				<a class="create_first_clearance_letter amber" href="<?= base_url();?>paf_upload/add_paf" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create PAF" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add PAF</a>
			</div> -->
			<h2></h2>
		<?php } ?>
	</header>
	<section class="panel" style="margin-top: 0px;">
		<div class="panel-body">
			<div class="col-md-12">
				<div id="w2-auditor_list" class="tab-pane active">	
		            <table class="table table-bordered table-striped mb-none datatable-paf" id="datatable-paf" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left">Company</th>
							</tr>
						</thead>
						<tbody>
							<?php 
									
										if($paf_list)
										{
											foreach($paf_list as $key=>$each)
								  			{	
								  			
								  				echo '<tr class="paf_tr">';
								  				// echo '<td></td>';
								  				echo '<td style="width:60%;"><a href="paf_upload/edit_paf/'.$each->id.'" class="pointer mb-sm mt-sm mr-sm">'.$each->company_name.'</a></td>';
								  				// echo '<td style="width:30%;">'.$each->fye_date.'</td>';
								  				// echo '<td style="width:15%;">'.($each->auditor_id?"<table class='table table-bordered table-striped mb-none'>".$rowAuditorName."</table>":"<b>Not Arranged</b>").'</td>';
								  				// echo '<td style="width:8%;">'.($each->stocktake_date != "0000-00-00"?$each->stocktake_date:"<b>Not Arranged</b>").'</td>';
								  				// echo '<td style="width:8%;">'.($each->stocktake_time != "00:00:00"?$each->stocktake_time:"<b>Not Arranged</b>").'</td>';
								  				// echo '<td style="width:20%;">'.($each->stocktake_address != ""?$each->stocktake_address:"<b>Not Arranged</b>").'</td>';
								  				// echo '<td style="width:14%;">'.($each->client_pic != ""?$each->client_pic:"<b>Not Arranged</b>").'</td>';
								  				// echo '<td style="width:10%;text-align:center;">
								  				// 		<input type="hidden" class="paf_id" value="'. $each->id .'" />
														// <button type="button" class="btn btn_blue" onclick=delete_paf(this) style="margin-bottom:5px;margin-right:5px;margin-left:5px;">Delete</button></td>';
								  				echo '</tr>';
								  			}
										}
										
									?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
</section>
<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>
<style type="text/css">
	#create_paf {width: 100px; }
</style>

<script type="text/javascript">
	var pathArray = location.href.split( '/' );
	var protocol = pathArray[0];
	var host = pathArray[2];
	var folder = pathArray[3];

	var active_tab = <?php echo json_encode(isset($active_tab)?$active_tab:"auditor_list") ?>;
	var auditor_list = <?php echo json_encode(isset($auditor_list)?$auditor_list:"") ?>;
	var bank_confirm_setting = <?php echo json_encode(isset($bank_confirm_setting)?$bank_confirm_setting:"") ?>;
	var delete_paf_url = "<?php echo site_url('paf_upload/delete_paf'); ?>";
	// var type_of_leave_list = <?php echo json_encode(isset($type_of_leave_list)?$type_of_leave_list:"") ?>;
	// var block_leave_list = <?php echo json_encode(isset($block_leave_list)?$block_leave_list:"") ?>;
	// var holiday_list = <?php echo json_encode(isset($holiday_list)?$holiday_list:"") ?>;
	var pv_index_tab_aktif;
	var startDate = new Date();

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

	    this.getPicName = function(selected_pic_name=null) {
	        var url = base_url+"/"+folder+"/"+'bank/getPicName';
	        //console.log(url);
	        var method = "get";
	        var data = {};
	        $('.pic_name').find("option:eq(0)").html("Please wait..");
	        call.send(data, url, method, function(data) {
	            //console.log(data);
	            $('.pic_name').find("option:eq(0)").html("Select PIC Name");
	            // console.log(data);
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);
	                    if(selected_pic_name != null && key == selected_pic_name)
	                    {

	                        option.attr('selected', 'selected');
	                        // $('.pic_name').attr('disabled', true);
	                    }
	                    // console.log(option);
	                    $('.pic_name').append(option);
	                });
	                $('#pic_name').select2();
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
	    cn.getPicName();
	});


    function delete_paf(element){
    	var div         = $(element).parent();
	    var paf_id     = div.find('.paf_id').val();
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
	                $.post(delete_paf_url, { 'paf_id': paf_id }, function(data, status){
	                    if(data){
	                        location.reload();
	                    }
	                });
	            }
	        }
	    })
    }

	$(document).ready(function () {
	    // $('#datatable-confirmation').DataTable( {
	    // 	"order": []
	    // });
	    var t_paf = $('#datatable-paf').DataTable({
			//orderFixed: [0, 'asc'],
			// 'data': stocktake_arrangement_data
		
				  
			// 'rowsGroup': [0]
			// orderFixed: [1, 'asc'],
	  //       rowGroup: {
	  //           dataSrc: 1
	  //       }
	    });

	    
	});

	// $(".auth_status").select2();

	// $('.nav li').not('.active').addClass('disabled');

	// if(auditor_list)
	// {
	// 	$('.nav li').removeClass('disabled');
	// }
	// else
	// {
	// 	$('.disabled').click(function (e) {
	//         e.preventDefault();

	//         if($(this).hasClass("disabled"))
	//         {
	//         	return false;
	//         }
	//         else
	//         {
	//         	return true;
	//         }
	        
	// 	});
	// }


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

	    if(active_tab != "auditor_list")
	    {
	        $('li[data-information="'+active_tab+'"]').addClass("active");
	        $('#w2-'+active_tab+'').addClass("active");
	        $('li[data-information="auditor_list"]').removeClass("active");
	        $('#w2-auditor_list').removeClass("active");
	    }
	    // console.log(active_tab);
	    

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


</script>