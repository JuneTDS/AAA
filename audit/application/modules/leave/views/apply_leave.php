<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script> -->

<link rel="stylesheet" href="<?= base_url() ?>node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript" src="<?= base_url()?>application/js/custom/time_format.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>





<section class="body">
	<div class="inner-wrapper">
		<section role="main" class="content_section" style="margin-left:0;">
			<?php echo $breadcrumbs;?>
			<div class="box" style="margin-bottom: 30px; margin-top: 30px;">
				<div class="box-content">
				    <div class="row">
				        <div class="col-lg-12">
				            <form id="appy_leave" method="POST">
				            	<input name="employee_id" class="form-control" type="hidden" value="<?=isset($leave_data['employee_id'])?$leave_data['employee_id']:''?>">
				            	<input name="leave_id" class="form-control" type="hidden" value="<?=isset($leave_data['leave_id'])?$leave_data['leave_id']:''?>">

	                            <div class="form-group row">
								    <label for="active_type_of_leave" class="col-sm-3 col-form-label">Type of leave: </label>
							    	<div class="col-sm-5">
							    		<?php 
							    			//echo json_encode($block_leave_list);
                                    		echo form_dropdown('type_of_leave_id', $active_type_of_leave, isset($leave_data['type_of_leave_id'])?$leave_data['type_of_leave_id']:'', 'class="form-control type_of_leave" required');
                                    	?>
						            </div>
								</div>

								<div class="form-group row">
								    <label for="balance" class="col-sm-3 col-form-label">Balance (Before Approve): </label>
							    	<div class="col-sm-1">
							    		<input type='text' class="form-control" name="balance" id='balance' readonly="true" value="<?php echo (isset($leave_data['balance_before_approve'])?$leave_data['balance_before_approve']:'')?>"/>
							    		
						            </div>
						            <div class="col-sm-1" style="padding-top: 6px; padding-left: 0px;"><span>day(s)</span></div>
						            <!--  -->
								</div>

				            	<div class="form-group row">
								    <label for="leave_start_date" class="col-sm-3 col-form-label">Start Date: </label>
								    <div class="col-sm-3">
								    	<div class='input-group date'>
								    		<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
						                    <input type='text' class="form-control leave_start_date" name="leave_start_date" id='start_date'/>
						                </div>
								    </div>
								    <div class="col-sm-2">
								    	<!-- <div class="col-sm-6 addBtn">
								    		<div style="padding:5%">
									    		<a style="cursor: pointer;" onclick="hide_addBtn(this)"><span class="glyphicon glyphicon-plus"></span> Add Time</a>
									    	</div>
								    	</div> -->
								    	<div class="col-sm-10">
								    		<?php
                                                echo form_dropdown('leave_start_time', $start_time_list, isset($leave_data['start_time'])?$leave_data['start_time']:'', 'onchange="remove_time_option(1)" style="height: 34px; padding: 6px 12px;" class="form-control leave_start_time"');
                                            ?>
									    	<!-- <div class='input-group date' id='start_date_time' style="display:none;">
							                    <input type='text' class="form-control" name="leave_start_time" />
							                    <span class="input-group-addon">
							                        <span class="glyphicon glyphicon-time"></span>
							                    </span>
							                </div> -->
							            </div>
						               <!--  <div class="col-sm-1 removeBtn" style="display: none; padding: 3%;">
							                <a style="cursor: pointer;" onclick="remove_time(this)"><span class="glyphicon glyphicon-remove"></span></a>
							            </div> -->
								    </div>
								</div>
								<div class="form-group row">
								    <label for="leave_end_date" class="col-sm-3 col-form-label">End Date: </label>
								    <div class="col-sm-3">
								    	<div class='input-group date'>
								    		<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
						                    <input type='text' class="form-control leave_end_date" name="leave_end_date" id='end_date' />
						                </div>
								    </div>
								    <div class="col-sm-2">
								    	<!-- <div class="col-sm-6 addBtn">
								    		<div style="padding:5%">
										    	<a style="cursor: pointer" onclick="hide_addBtn(this)"><span class="glyphicon glyphicon-plus"></span> Add Time</a>
										    </div>
									    </div> -->
								    	<div class="col-sm-10">
								    		<?php
                                                echo form_dropdown('leave_end_time', $end_time_list, isset($leave_data['end_time'])?$leave_data['end_time']?$leave_data['end_time']:end($end_time_list):end($end_time_list), 'onchange="remove_time_option(0)" style="height: 34px; padding: 6px 12px;" class="form-control leave_end_time"');
                                            ?>
									    	<!-- <div class='input-group date' id='end_date_time' style="display:none;">
							                    <input type='text' class="form-control" name="leave_end_time"/>
							                    <span class="input-group-addon">
							                        <span class="glyphicon glyphicon-time"></span>
							                    </span>
							                </div> -->
							            </div>
							            <!-- <div class="col-sm-1 removeBtn" style="display: none; padding: 3%;">
							                <a style="cursor: pointer;" onclick="remove_time(this)"><span class="glyphicon glyphicon-remove"></span></a>
							            </div> -->
								    </div>
								    <!-- <div class="col-sm-5">
								      	<div id="leave_end_date" class="input-group date form_datetime"  data-date-format="dd MM yyyy - HH:ii:ss p" data-link-field="dtp_input1">
						                    <input name="leave_end_date" class="form-control" size="16" type="text" value="<?=isset($mc_data[0]->end_date)?$mc_data[0]->end_date:''?>" onchange="check_date('end_date')">
						                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
											<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
						                </div>
								    </div>
								    <div class="col-sm-3">
								    	<div class='input-group date' id='end_date_time'>
						                    <input type='text' class="form-control" />
						                    <span class="input-group-addon">
						                        <span class="glyphicon glyphicon-time"></span>
						                    </span>
						                </div>
								    </div> -->
								</div>
								<div class="form-group row">
								    <label for="mc_reason" class="col-sm-3 col-form-label">Total Leave Apply: </label>
								    <div class="col-sm-5">
								    	<label id="total_leave_apply" style="display:inline-block;">
								    		<?php echo isset($leave_data['total_leave_apply'])?$leave_data['total_leave_apply']:0 ?>
								    	</label>
								    	<input name="leave_total_days" type="hidden" value="<?=isset($leave_data['total_leave_apply'])?$leave_data['total_leave_apply']:0?>">
								    	<label style="display:inline-block;">day(s)</label>
								    </div>
								</div>
								<hr>
								<!-- <div class="form-group row">
								    <label for="mc_reason" class="col-sm-2 col-form-label">Remaining leave: </label>
								    <div class="col-sm-5">
								    	<input name="days_left_before" type="hidden" value="<?=isset($leave_data['days_left_before'])?$leave_data['days_left_before']:0?>">
								    	<label id="total_remaining_al" style="display:inline-block;">
								    		<?php echo isset($leave_data['total_remaining_al'])?$leave_data['total_remaining_al']:0 ?>
								    	</label>
								    	<input name="total_remaining_al" type="hidden" value="<?=isset($leave_data['total_remaining_al'])?$leave_data['total_remaining_al']:0?>">	
								    	<label style="display:inline-block;">day(s)</label>
								    </div>
								</div> -->
								<!-- <div class="row">
							        <div class='col-sm-6'>
							            <div class="form-group">
							                
							            </div>
							        </div>
							    </div> -->
								<div class="form-group row">
									<div class="col-sm-12">
								    	<?php 
								    		//$status = isset($status)?$status:'';
								    		echo '<a href="'.base_url().'leave" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

								    		if($status == NULL)
								    		{
								    			if(!$Admin && !$Manager && $leave_data['user_id'] == $this->user_id)
								    			{
								    				if($leave_data['status'] == 1 || $leave_data['status'] == '')
								    				{
								    					echo '<button class="btn btn_blue pull-right" style="margin:0.5%">Apply</button>';
								    				}
								    			}
								    			else
								    			{
								    				echo '<button class="btn btn_blue pull-right" style="margin:0.5%">Apply</button>';
								    			}
								    		}

								    	?>
			                      		
								    </div>
			                    </div>
						    </form>
					    </div>
					</div>
				</div>
			</div>
		</section>
	</div>
</section>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
	    	<div class="modal-header">
	      		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		  	    </button>
		  	    <?php 
		  	    	if(is_null(isset($mc_data[0]->id)?$mc_data[0]->id:null)){
		  	    		echo '<h4 class="modal-title">Success apply</h4>';
		  	    	} else {
		  	    		echo '<h4 class="modal-title">Success update</h4>';
		  	    	}
		  	    ?>
	      		
	      	</div>
	      	<div class="modal-body">
	      		<?php 
	      			if(is_null(isset($mc_data[0]->id)?$mc_data[0]->id:null)){
		  	    		echo '<p>Your MC has been successfully applied.</p>';
		  	    	} else {
		  	    		echo '<p>MC has been successfully updated.</p>';
		  	    	}
	      		?>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-default cancelBtn" data-dismiss="modal">Close</button>
	      	</div>
    	</div>
  	</div>
</div> -->
<!-- <script src="<?= base_url() ?>application/views/modal/modal_template.php"></script> -->

<script type="text/javascript">
	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}
	//console.log(<?php echo json_encode($block_leave_list) ?>);
	var page_status = <?php echo json_encode($status) ?>;
	var leave_data = JSON.parse('<?php echo json_encode($leave_data) ?>');
	var initial_start_date = '<?php echo isset($leave_data['start_date'])?$leave_data['start_date']:''; ?>';
	var initial_end_date   = '<?php echo isset($leave_data['end_date'])?$leave_data['end_date']:''; ?>';
	var employee_id = '<?php echo isset($leave_data['employee_id'])?$leave_data['employee_id']:''; ?>';
	var disabledArr = JSON.parse('<?php echo json_encode($block_leave_list) ?>');
	var base_url = '<?php echo base_url() ?>';
	var Admin = <?php echo json_encode($Admin) ?>;
	var Manager = <?php echo json_encode($Manager) ?>;
	var user_id = <?php echo json_encode($this->user_id) ?>;
	var daysOfYear = [];

	if(page_status == "view")
	{
		$(".type_of_leave").attr("disabled", true);
		$(".leave_start_date").attr("disabled", true);
		$(".leave_end_date").attr("disabled", true);
		$(".leave_start_time").attr("disabled", true);
		$(".leave_end_time").attr("disabled", true);
	}
	console.log(leave_data['status']);
	if(!Admin && !Manager || leave_data['user_id'] == user_id)
	{
		
		if(leave_data['status'] != 1 && leave_data['status'] != '')
		{
			$(".type_of_leave").attr("disabled", true);
			$(".leave_start_date").attr("disabled", true);
			$(".leave_end_date").attr("disabled", true);
			$(".leave_start_time").attr("disabled", true);
			$(".leave_end_time").attr("disabled", true);
		}
	}

	for(i=0;i<disabledArr.length;i++){
		var From = new Date(disabledArr[i].block_leave_date_from);
	    var To = new Date(disabledArr[i].block_leave_date_to);
	    
	    var loop = new Date(disabledArr[i].block_leave_date_from);

	    for (var d = From; d <= To; d.setDate(d.getDate() + 1)) {
		    daysOfYear.push(new Date(d));
		}
	}

	// start and end (DATE)
	$('#start_date').datetimepicker({
        format: 'DD/MM/YYYY',
        date: new Date(initial_start_date),
        useCurrent: false,
        widgetPositioning: {
        	vertical: 'bottom',
        },
        disabledDates: daysOfYear
    });

	$('#end_date').datetimepicker({
        format: 'DD/MM/YYYY',
        date: new Date(initial_end_date),
        useCurrent: false,
        widgetPositioning: {
        	vertical: 'bottom',
        },
        disabledDates: daysOfYear
    });

/* --------------------------------------------------------------------------------------------------------- */
	$(".type_of_leave").change(function (){
		$('#loadingmessage').show();
	    $.post("<?php echo site_url('leave/get_the_balance'); ?>", {employee_id: employee_id ,type_of_leave_id: $(this).val()}, function(data){
	    	if(data != false)
	    	{
	    		$("#balance").val(JSON.parse(data)[0]["annual_leave_days"]);
	    	}
	    	else
	    	{
	    		toastr.error('Something went wrong. Please try again later.', 'Error');
	    	}
	    	$('#loadingmessage').hide();
		});
	});

	// disable selection on date before and after.
 	$("#start_date").on("dp.change", function (e) {
        $('#end_date').data("DateTimePicker").minDate(e.date);

        var end_date = $('#end_date').data('DateTimePicker').date();

        if(end_date != null){
        	if(!(e.date.format('YYYY-MM-DD') == end_date.format('YYYY-MM-DD'))){
	        	$('select[name=leave_start_time] option').last().show();
		    	$('select[name=leave_end_time] option').first().show();
	        }
        	calculate_totalDays();	// excluding weekend.
        }
    });

    $("#end_date").on("dp.change", function (e) {
        $('#start_date').data("DateTimePicker").maxDate(e.date);

        var start_date = $('#start_date').data('DateTimePicker').date();

        // console.log("start date == end date", e.date.format('YYYY-MM-DD') == start_date.format('YYYY-MM-DD'));

        if(start_date != null){
        	if(!(e.date.format('YYYY-MM-DD') == start_date.format('YYYY-MM-DD'))){
	        	$('select[name=leave_start_time] option').last().show();
		    	$('select[name=leave_end_time] option').first().show();
	        }
        	calculate_totalDays();	// excluding weekend.
        }
    });

    // start and end (TIME)
	$('#start_date_time').datetimepicker({
        format: 'LT'
    });

    $('#end_date_time').datetimepicker({
        format: 'LT'
    });

    $('form#appy_leave').submit(function(e) {
	    var form = $(this);

	    e.preventDefault();
	    $('#loadingmessage').show();
	    $.ajax({
	        type: "POST",
	        url: "<?php echo site_url('leave/submit_leave'); ?>",
	        data: form.serialize(),
	        dataType: "html",
	        success: function(data){
	        	$('#loadingmessage').hide();
	        	var response = JSON.parse(data);

	        	// console.log(response);

	        	if(response.result){
	        		$('input[name=leave_id]').val(response.data.id);
	        		toastr.success('Information Updated', 'Updated');
	        		//alert("Successfully applied leave!");
	        	}else{
	        		toastr.error('Something went wrong. Please try again later.', 'Error');
	        		//alert("Something went wrong. Please try again later.");
	        	}

	        	window.location = base_url + "leave";
	        },
	        // error: function() { alert("Error posting feed."); }
	   });
	});

    // function check_date(startOrEnd){
    // 	console.log("Change");
    	// var start_date 	= $("#leave_start_date").data("datetimepicker").getDate();
    	// var end_date 	= $("#leave_end_date").data("datetimepicker").getDate();

    	// // formatted = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + " " + date.getHours + ":" + date.getMinutes() + ":" + date.getSeconds();
    	// var year 	= end_date.getFullYear() - start_date.getFullYear();
    	// var day  	= end_date.getDate()     - start_date.getDate();
    	// var hour  	= end_date.getHours()    - start_date.getHours();
    	// var minute  = end_date.getMinutes()  - start_date.getMinutes();

    	// console.log(day);
    // }

    function remove_time_option(isStart){
    	var start_date 	= $('input[name=leave_start_date]');
    	var end_date 	= $('input[name=leave_end_date]');
    	var start_time 	= $('select[name=leave_start_time] option');
    	var end_time 	= $('select[name=leave_end_time] option');

    	var start_time_val 	= $('select[name=leave_start_time]').val();
    	var end_time_val 	= $('select[name=leave_end_time]').val();

    	if(start_date.val() == end_date.val()){
	    	if(isStart){
	    		if(start_time_val == start_time.last().val()){
	    			end_time.first().hide();
	    		}else{
	    			end_time.first().show();
	    		}
	    	}else{
	    		if(end_time_val == end_time.first().val()){
	    			start_time.last().hide();
	    		}else{
	    			start_time.last().show();
	    		}
	    	}
	    }else{
	    	start_time.last().show();
	    	end_time.first().show();
	    }

	    calculate_totalDays();
    }

    function calculate_totalDays(){
    	$('#loadingmessage').show();
		var dates = new Object();

        dates.start_date = $('#start_date').data('DateTimePicker').date().format('YYYY-MM-DD');
        dates.end_date 	 = $('#end_date').data('DateTimePicker').date().format('YYYY-MM-DD');

        // dates.start_time 	 = ConvertTimeformat("24", $("select[name=start_time]").val());
        // dates.end_time 		 = ConvertTimeformat("24", $("select[name=end_time]").val());
        dates.start_time 	 = moment($("select[name=leave_start_time]").val(), "h:mm A").format("HH:mm");
        dates.end_time 		 = moment($("select[name=leave_end_time]").val(), "h:mm A").format("HH:mm");

        $.ajax({
	        type: "POST",
	        url: "<?php echo site_url('leave/calculate_working_days'); ?>",
	        data: dates,
	        dataType: "json",
	        success: function(data){
	        	$("#total_leave_apply").text(data.total_working_days);
	        	$("input[name=leave_total_days]").val(data.total_working_days);
	        	$('#loadingmessage').hide();
	        	// $('#total_remaining_al').text(data.total_remaining_al);
	        	// $("input[name=total_remaining_al]").val(data.total_working_days);
	        },
	        // error: function() { alert("Error posting feed."); }

	   	});
    }

    // function hide_addBtn(element){
    // 	$(element).parent().parent().hide();
    // 	var date_element = $(element).parent().parent().parent().find('.date');
    // 	var remove_btn	 = $(element).parent().parent().parent().find('.removeBtn');

    // 	date_element.show();
    // 	remove_btn.show();
    // }

    // function remove_time(element){
    // 	var remove_btn = $(element).parent().hide();
    // 	var addBtn 	   = $(element).parent().parent().find('.addBtn');
    // 	var timeInput  = $(element).parent().parent().find('.date');

    // 	remove_btn.hide();
    // 	addBtn.show();
    // 	timeInput.hide();

    // 	timeInput.find("input").val('');
    // }

</script>  