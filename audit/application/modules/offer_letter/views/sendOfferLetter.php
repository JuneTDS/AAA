<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-switch/dist/js/bootstrap-switch.js"></script>
<script src="<?= base_url() ?>plugin/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<style>
	.toggle {
		margin:0px;
	}
</style>

<?php if(!empty($employee_data[0]->id)){ ?>
<div style="text-align: right; display:none" id="btn_action">
	<a class="btn btn_blue" style="cursor:pointer; margin-bottom: 10px;" onclick=preview_offer_letter("<?=isset($employee_data[0]->employee_applicant_id)?$employee_data[0]->employee_applicant_id:'' ?>")>Preview</a>
	<!-- <a class="btn btn-danger" style="cursor:pointer;">Send Now</a> -->
</div>
<?php } ?>

<div class="box">
	<div class="box-content">
	    <div class="row">
	        <div class="col-lg-12">
	            <!-- <?php echo form_open_multipart('Interview/create_applicant', array('id' => 'firm_form', 'enctype' => "multipart/form-data")); ?> -->
	            
	            	<input type="hidden" name="offer_letter_id" value="<?=isset($employee_data[0]->id)?$employee_data[0]->id:'' ?>">
	            	<input type="hidden" name="applicant_id" value="<?=isset($employee_data[0]->applicant_id)?$employee_data[0]->applicant_id:'' ?>">
	            	<input type="hidden" name="employee_id" value="<?=isset($employee_data[0]->employee_id)?$employee_data[0]->employee_id:'' ?>">

		            <div class="row">
		                <div class="col-md-12">
		                    <div class="col-md-12">
		                    	<div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Employee name :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div class="input-group">
	                                        	<label style="width:100px;"><?php echo isset($employee_data[0]->name)?$employee_data[0]->name:'' ?></label>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="form-group">
			                    	<div style="width: 100%;">
				                    	<div style="width: 25%;float:left;margin-right: 20px;">
				                            <label>Company name :</label>
				                        </div>
				                    	<div style="width: 65%;float: left;">
				                    		<label>
				                    			<?php echo isset($employee_data[0]->company_name)?$employee_data[0]->company_name :'' ?>
				                    		</label>
                							<!-- <input type="text" class="form-control" name="ol_company"> -->
				                        </div>
				                    </div>
			                    </div>

			                    <?php 
	                            	if(!$new_employee){
	                            		echo 
	                            		'<div class="form-group">
					                    	<div style="width: 100%;">
						                    	<div style="width: 25%;float:left;margin-right: 20px;">
						                            <label>Pass given :</label>
						                        </div>
						                    	<div style="width: 65%;float: left;">'.
						                    		form_dropdown('ol_employee_pass', $employment_type, isset($employee_data[0]->workpass)?$employee_data[0]->workpass:'', 'class="form-control" disabled')
						                        .'</div>
						                    </div>
					                    </div>';
	                            	} else{
	                            		echo
	                            		'<div class="form-group">
					                    	<div style="width: 100%;">
						                    	<div style="width: 25%;float:left;margin-right: 20px;">
						                            <label>PR/Singaporean :</label>
						                        </div>
						                    	<div style="width: 65%;float: left;">
						                    		<div>
												        <input type="checkbox" name="ol_is_pr_singaporean" disabled />
												        <input type="hidden" name="hidden_ol_is_pr_singaporean" value="'. $is_pr_singaporean .'"/>
												    </div>
						                    	</div>
						                    </div>
					                    </div>';
	                            	}
	                            ?>

	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Effective from :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
							                <div class="input-group date datepicker">
							                	<div class="input-group-addon">
    										        <span class="far fa-calendar-alt"></span>
    										    </div>
							                	<input type="text" class="form-control" id="ol_effective_from" name="ol_effective_from" data-date-format="dd-mm-yyyy" value="<?=isset($employee_data[0]->effective_from)?date('d F Y', strtotime($employee_data[0]->effective_from)):'' ?>" required >
    										</div>
	                                    </div>
	                                </div>
	                            </div>
	                            <?php if($new_employee){ ?>
		                            <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>Probationary period :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                      	<input type="number" class="form-control" style="width:50%; display:inline-block;" name="ol_probationary_period" value="<?=isset($employee_data[0]->probationary_period)?$employee_data[0]->probationary_period:'' ?>" required />
		                                      	<span style="display:inline-block;">day(s)</span>
		                                    </div>
		                                </div>
		                            </div>
	                        	<?php } 
	                        		else {
	                        			echo '<input type="hidden" name="ol_probationary_period" value=""/>';
	                        		}
	                        	?>

	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Termination notice :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                      	<input type="number" class="form-control" style="width:50%; display:inline-block;" name="ol_termination_notice" value="<?=isset($employee_data[0]->termination_notice)?$employee_data[0]->termination_notice:'' ?>" required />
	                                      	<span style="display:inline-block;">day(s)</span>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Working hours (day):</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                    	<?php 
	                                    		echo form_dropdown('ol_working_hour_day_start', $time_in_day, 'Monday', 'style="height: 34px; padding: 6px 12px;"'); 
	                                    	?>
	                                        <!-- <input type="text" class="form-control" style="width:30%; display:inline-block;" name="ol_working_hour_day_start" value="<?=isset($employee_data[0]->working_hour_day_start)?$employee_data[0]->working_hour_day_start:'' ?>" placeholder="Start" required /> -->
	                                        <label> to </label>
	                                        <!-- <input type="text" class="form-control" style="width:30%; display:inline-block;" name="ol_working_hour_day_end" value="<?=isset($employee_data[0]->working_hour_day_end)?$employee_data[0]->working_hour_day_end:'' ?>" placeholder="End" required /> -->
	                                        <?php 
	                                    		echo form_dropdown('ol_working_hour_day_end', $time_in_day, 'Friday', 'style="height: 34px; padding: 6px 12px;"'); 
	                                    	?>
	                                        <span style="display: inline-block; color:lightgrey;"> Eg. Monday - Friday</span>
							               <!--  <div class='input-group date working_hours' style="display:inline-block;">
							                    <input type='text' class="form-control" />
							                    <span class="input-group-addon">
							                        <span class="glyphicon glyphicon-time"></span>
							                    </span>
							                </div> -->
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Working hours (time):</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <input type="text" class="form-control" style="width:20%; display:inline-block;" name="ol_working_hour_time_start" value="<?=isset($employee_data[0]->working_hour_time_start)? substr(trim($employee_data[0]->working_hour_time_start), 0, -2) :'' ?>" placeholder="Start" required />
	                                        <?php
	                                        	echo form_dropdown('ol_am_pm_start', $time_in_am_pm, isset($employee_data[0]->working_hour_time_start)? substr(trim($employee_data[0]->working_hour_time_start), -2) : 'am', 'style="height: 34px; padding: 6px 12px;"'); 
	                                        ?>
	                                        <label> to </label>
	                                        <input type="text" class="form-control" style="width:20%; display:inline-block;" name="ol_working_hour_time_end" value="<?=isset($employee_data[0]->working_hour_time_end)? substr(trim($employee_data[0]->working_hour_time_end), 0, -2):'' ?>" placeholder="End" required />
	                                        <?php
	                                        	echo form_dropdown('ol_am_pm_end', $time_in_am_pm, isset($employee_data[0]->working_hour_time_end)? substr(trim($employee_data[0]->working_hour_time_end), -2) : 'pm', 'style="height: 34px; padding: 6px 12px;"'); 
	                                        ?>
	                                        <span style="display: inline-block; color:lightgrey;">Eg. 9:00 am - 6:00 pm</span>
	                                    </div>
	                                </div>
	                            </div>
	                            <!-- <input type="number" class="number-field" id="number-field"> -->
	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Given salary</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                    	<label style="display:inline-block;">SGD</label>
	                                        
	                                        <?php if($new_employee){ ?>
				                            	<input type="text" id="number" class="form-control input_num_val" style="width:50%; display:inline-block; text-align: right;" name="ol_given_salary" value="<?=isset($employee_data[0]->given_salary)?$employee_data[0]->given_salary: $employee_data[0]->expected_salary ?>" />

					                            <?php if(!empty($employee_data[0]->expected_salary)){ ?>
					                            	<label style="color:lightgrey; width:100px;">Expected salary: $ <?=$employee_data[0]->expected_salary?></label>
					                            <?php } else{ ?>
					                            	<label style="color:lightgrey; width:100px;">Expected salary: $0 </label>
					                            <?php } ?>
					                            	
					                        <?php } else { ?>
					                      		<input type="text" class="form-control input_num_val" style="width:50%; display:inline-block; text-align: right;" name="ol_given_salary" value="<?=isset($employee_data[0]->given_salary)?$employee_data[0]->given_salary: $employee_data[0]->salary ?>" />
					                         	<label style="color:lightgrey; width:100px;">Current salary: $ <?=$employee_data[0]->salary?></label>
					                        <?php } ?>
	                                    </div>
	                                </div>
	                            </div>
	                            <!-- <input type="checkbox" checked data-on="Yes" data-off="No" data-toggle="toggle" data-onstyle="default"> -->
	                            
			                    <div class="form-group">
			                    	<div style="width: 100%;">
				                    	<div style="width: 25%;float:left;margin-right: 20px;">
				                            <label>On behave of the Employer:</label>
				                        </div>
				                    	<div style="width: 65%;float: left;">
                							<input type="text" class="form-control" name="ol_employer" value="<?=isset($employee_data[0]->employer)?$employee_data[0]->employer:'' ?>" required >
				                        </div>
				                    </div>
			                    </div>
			                </div>
			            </div>
			        </div>
		    </div>
		</div>
	</div>
</div>

<!-- <style>
	.bootstrap-switch .bootstrap-switch-handle-on.bootstrap-switch-blue,
	.bootstrap-switch .bootstrap-switch-handle-off.bootstrap-switch-blue {
	  color: #fff;
	  background: #9349BD;
	}
</style> -->
<script type="text/javascript" src="<?php echo base_url() . 'application/js/number_input_format.js' ?>"></script>

<script type="text/javascript">

	var offer_letter_id = <?php echo isset($employee_data[0]->id)?$employee_data[0]->id: 'null'; ?>;

	if(offer_letter_id != null){
		$("#btn_action").show();
	}

	$('.datepicker').datepicker({
		format: 'dd MM yyyy',
	});

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	$("[name='ol_is_pr_singaporean']").bootstrapSwitch({
	    state: <?php echo isset($is_pr_singaporean)?$is_pr_singaporean: 0; ?>,
	    size: 'normal',
	    onColor: 'blue',
	    onText: 'Yes',
	    offText: 'No',
	    // Text of the center handle of the switch
	    labelText: '&nbsp',
	    // Width of the left and right sides in pixels
	    handleWidth: '75px',
	    // Width of the center handle in pixels
	    labelWidth: 'auto',
	    baseClass: 'bootstrap-switch',
	    wrapperClass: 'wrapper'
	});

	$("[name='ol_is_pr_singaporean']").on('switchChange.bootstrapSwitch', function(event, state) {
		if(state == true)
	    {
			$("[name='hidden_ol_is_pr_singaporean']").val(1);
		}
		else
		{
			$("[name='hidden_ol_is_pr_singaporean']").val(0);
		}
	})

	$('form#offer_letter').submit(function(e) {
	    var form = $(this);

	    e.preventDefault();

	    $.ajax({
	        type: "POST",
	        url: "<?php echo site_url('offer_letter/save_offer_letter'); ?>",
	        data: form.serialize(),
	        dataType: "html",
	        success: function(data){
	        	console.log(data);
	            // $('#feed-container').prepend(data);
	            $('input[name=offer_letter_id]').val(data);
	            $('#offer_letter_modal').modal('hide');

	            toastr.success('Information Updated', 'Updated');
	        },
	        // error: function() { alert("Error posting feed."); }
	   });

	});
</script>  
