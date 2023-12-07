<link rel="stylesheet" href="<?= base_url() ?>node_modules/chosen-js/chosen.css" />
<script src="<?= base_url() ?>node_modules/chosen-js/chosen.jquery.js"></script>
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />

<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-switch/dist/js/bootstrap-switch.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>

<section role="main" class="content_section" style="margin-left:0;">
	<?php echo $breadcrumbs;?>
	<section class="panel" style="margin-top: 30px;">
		<form id="employee" method="POST" enctype="multipart/form-data"> 
		<div class="panel-body">
			<div class="col-md-12">
				<div class="tabs">				
					<ul class="nav nav-tabs nav-justify">
						<?php 
							$staff_data = isset($staff)?$staff:FALSE;
							//echo json_encode($staff_data);
							if($staff_data == FALSE)
							{
						?>
							<li class="active check_state" data-information="particulars">
								<a href="#w2-particulars" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">1</span>
									Particulars
								</a>
							</li>
							<li class="check_state" data-information="settings">
								<a href="#w2-settings" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">2</span>
									General
								</a>
							</li>
							
						<?php 
							}else{
						?>
							<li class="active check_state" data-information="settings">
								<a href="#w2-settings" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">1</span>
									General
								</a>
							</li>
							<li class="check_state" data-information="particulars">
								<a href="#w2-particulars" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">2</span>
									Particulars
								</a>
							</li>
						<?php 
							}
						?>
						<li class="check_state" data-information="family">
							<a href="#w2-family" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">3</span>
								Family
							</a>
						</li>
						<!-- <li class="check_state" data-information="settings">
							<a href="#w2-settings" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">4</span>
								History
							</a>
						</li> -->
					</ul>
					
					<div class="tab-content clearfix">
							<?php 
								$staff_data = isset($staff)?$staff:FALSE;
								if($staff_data == FALSE)
								{
							?>
								<div id="w2-settings" class="tab-pane">
							<?php 
								}else{
							?>
								<div id="w2-settings" class="tab-pane active">
							<?php 
								}
							?>
								<input type="hidden" name="previous_staff_status" class="previous_staff_status" value="<?php echo isset($staff[0]->employee_status_id)?$staff[0]->employee_status_id:''?>">
								<input type="hidden" name="previous_status_date" class="previous_status_date" value="<?php echo isset($staff[0]->status_date)?$staff[0]->status_date:''?>">
								<div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Status :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 30%;">
	                                        	<?php 
	                                        		echo form_dropdown('staff_status', $status_list, isset($staff[0]->employee_status_id)?$staff[0]->employee_status_id:'', 'class="form-control employee_status"');
	                                        	?>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group date_of_letter_div" style="display: none;">
    		                    	<div style="width: 100%;">
    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
    			                            <label>Date of letter :</label>
    			                        </div>
    			                    	<div style="width: 30%;float: left;">
    			                            <div class="input-group date datepicker" data-provide="datepicker">
    			                            	<div class="input-group-addon">
											        <span class="far fa-calendar-alt"></span>
											    </div>
    			                            	<input type="text" class="form-control" id="date_of_letter" name="date_of_letter" data-date-format="d F Y" value="<?=isset($staff[0]->date_of_letter)?date("d F Y",strtotime($staff[0]->date_of_letter)):''?>">
    										</div>
    			                        </div>
    			                    </div>
    		                    </div>
	                            <div class="form-group effective_date_div" style="display: none;">
    		                    	<div style="width: 100%;">
    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
    			                            <label>Effective Date :</label>
    			                        </div>
    			                    	<div style="width: 30%;float: left;">
    			                            <div class="input-group date datepicker" data-provide="datepicker">
    			                            	<div class="input-group-addon">
											        <span class="far fa-calendar-alt"></span>
											    </div>
    			                            	<input type="text" class="form-control" id="status_date" name="status_date" data-date-format="d F Y" value="<?=isset($staff[0]->status_date)?date("d F Y",strtotime($staff[0]->status_date)):''?>">
    										</div>
    			                        </div>
    			                    </div>
    		                    </div>
    		                    <?php 
	                            	echo
	                            		'<div class="form-group">
					                    	<div style="width: 100%;">
						                    	<div style="width: 25%;float:left;margin-right: 20px;">
						                            <label>AWS eligibility :</label>
						                        </div>
						                    	<div style="width: 200px;float:left;margin-bottom:5px;">
						                    		<div>
												        <input type="checkbox" name="staff_aws_given" />
												        <input type="hidden" name="hidden_staff_aws_given" value="'. (isset($staff[0]->aws_given)? $staff[0]->aws_given : 0) .'"/>
												    </div>
						                    	</div>
						                    </div>
					                    </div>';
	                            ?>
	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>CPF (Employee) :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div class="input-group" style="width: 40%;">
	                                        	<input type="number" step="0.01" class="form-control" id="staff_cpf_employee" name="staff_cpf_employee" value="<?=isset($staff[0]->cpf_employee)?$staff[0]->cpf_employee:'' ?>" style="width: 100px;border-radius: 4px;"/>
	                                        	<label style="margin:3%">%</label>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>CPF (Employer) :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div class="input-group" style="width: 40%;">
	                                        	<input type="number" step="0.01" class="form-control" id="staff_cpf_employer" name="staff_cpf_employer" value="<?=isset($staff[0]->cpf_employer)?$staff[0]->cpf_employer:'' ?>" style="width: 100px;border-radius: 4px;"/>
	                                        	<label style="margin:3%">%</label>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Workpass :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 30%;">
	                                        	<?php 
	                                        		echo form_dropdown('staff_workpass', $workpass_list, isset($staff[0]->workpass)?$staff[0]->workpass:'', 'class="form-control staff_workpass"');
	                                        	?>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group">
    		                    	<div style="width: 100%;">
    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
    			                            <label>Expire on :</label>
    			                        </div>
    			                    	<div style="width: 30%;float: left;">
    			                            <div class="input-group date datepicker" data-provide="datepicker">
    			                            	<div class="input-group-addon">
											        <span class="far fa-calendar-alt"></span>
											    </div>
    			                            	<input type="text" class="form-control" id="staff_pass_expire" name="staff_pass_expire" data-date-format="d F Y" value="<?=isset($staff[0]->pass_expire)?date("d F Y",strtotime($staff[0]->pass_expire)):''?>">
    										</div>
    			                        </div>
    			                        <div style="width: 30%;float: left;">
    			                        	<button  type="button" class="btn btn_blue renewed_btn" style="margin-left:5px;">Renewed</button>
    			                        </div>
    			                    </div>
    		                    </div>
								<div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Salary ($) :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                        	<input type="number" class="form-control" id="staff_salary" name="staff_salary" value="<?=isset($staff[0]->salary)?$staff[0]->salary:'' ?>" style="width: 400px;"/>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Firm :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                        	<?php
    												echo form_dropdown('firm_id', $firm_list, isset($staff[0]->firm_id)?$staff[0]->firm_id: '', 'class="firm-select" style="width:150%;" required');
    											?>
	                                        	<!-- <input type="text" class="form-control" id="staff_nric_finno" name="staff_nric_finno" value="<?=isset($staff[0]->nric_fin_no)?$staff[0]->nric_fin_no:'' ?>" style="width: 400px;" required/> -->
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
    		                    <div class="form-group">
    		                    	<div style="width: 100%;">
    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
    			                            <label>Date joined :</label>
    			                        </div>
    			                    	<div style="width: 30%;float: left;">
    			                    		<div class="input-group date datepicker" data-provide="datepicker">
    			                    			<div class="input-group-addon">
											        <span class="far fa-calendar-alt"></span>
											    </div>
											    <input type="text" class="form-control" id="staff_joined" name="staff_joined" data-date-format="d F Y" value="<?=isset($staff[0]->date_joined)?date("d F Y",strtotime($staff[0]->date_joined)):''?>" required>
											</div>
    			                        </div>
    			                    </div>
    		                    </div>
    		                    <div class="form-group">
    		                    	<div style="width: 100%;">
    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
    			                            <label>Date of cessation :</label>
    			                        </div>
    			                    	<div style="width: 30%;float: left;">
    			                            <div class="input-group date datepicker" data-provide="datepicker">
    			                            	<div class="input-group-addon">
											        <span class="far fa-calendar-alt"></span>
											    </div>
    			                            	<input type="text" class="form-control" id="staff_cessation" name="staff_cessation" data-date-format="d F Y" value="<?=isset($staff[0]->date_cessation)?date("d F Y",strtotime($staff[0]->date_cessation)):''?>">
    										</div>
    			                        </div>
    			                    </div>
    		                    </div>
    		                    <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Designation :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                        	<input type="text" class="form-control" id="staff_designation" name="staff_designation" value="<?=isset($staff[0]->designation)?$staff[0]->designation:'' ?>" style="width: 400px;"/>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Department :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 30%;">
	                                        	<?php 
	                                        		echo form_dropdown('staff_department', $department_list, isset($staff[0]->department)?$staff[0]->department:'', 'class="form-control"');
	                                        	?>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
								<!-- <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Annual leave/year :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div class="input-group" style="width: 40%;">
	                                        	<input type="number" class="form-control" id="staff_annual_leave" name="staff_annual_leave" value="<?=isset($staff[0]->annual_leave_year)?$staff[0]->annual_leave_year:'' ?>" style="width: 50%; border-radius: 4px;"/>
	                                        	<label style="margin:3%">days</label>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div> -->
	                            
	                            
	                            
	                            
	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>CDAC/MENDAKI/SINDA ($) :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                        	<input type="number" step="0.01" class="form-control" id="staff_cdac" name="staff_cdac" value="<?=isset($staff[0]->cdac)?$staff[0]->cdac:'' ?>" style="width: 100px;"/>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Remark :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                        	<textarea class="form-control" id="staff_remark" name="staff_remark" style="width: 400px;"><?php echo isset($staff[0]->remark)?$staff[0]->remark:'' ?></textarea>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <!-- <div class="form-group">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Username :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                        	<input type="text" class="form-control" id="staff_username" name="staff_username" value="<?=isset($staff[0]->username)?$staff[0]->username:'' ?>" style="width: 400px;"/>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div> -->
	                            <div class="form-group" style="display: none">
	                                <div style="width: 100%;">
	                                    <div style="width: 25%;float:left;margin-right: 20px;">
	                                        <label>Supervisor :</label>
	                                    </div>
	                                    <div style="width: 65%;float:left;margin-bottom:5px;">
	                                        <div style="width: 20%;">
	                                        	<input type="text" class="form-control" id="staff_supervisor" name="staff_supervisor" value="<?=isset($staff[0]->supervisor)?$staff[0]->supervisor:'' ?>" style="width: 400px;"/>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <h3>Leave</h3>
	                            <table class="table table-bordered table-striped mb-none" style="width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left" style="width:50px;">Active</th>
											<th class="text-left">Leave Name</th>
											<th class="text-left">Days</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$active_type_of_leave = isset($active_type_of_leave)?$active_type_of_leave:FALSE;
											foreach($type_of_leave_list as $leave)
								  			{
								  				echo '<tr>';

								  				if($active_type_of_leave)
								  				{
								  					echo '<td style="text-align: center;vertical-align: middle;"><input type="checkbox" class="checkbox'.$leave->id.'" name="active[]" value="'.$leave->id.'"></td>';
								  				}
								  				else
								  				{
								  					echo '<td style="text-align: center;vertical-align: middle;"><input type="checkbox" class="checkbox'.$leave->id.'" name="active[]" value="'.$leave->id.'" checked></td>';
								  				}

								  				echo '<td>'.$leave->leave_name.'</td>';
								  				echo '<td class="text-right"><input type="text" class="form-control leave_days'.$leave->id.'" name="leave_days[]" value="'.$leave->days.'" style="width: 70px;"></td>';
								  				echo '</tr>';
								  			}
										?>
									</tbody>
								</table>
							</div>
							<?php 
								$staff_data = isset($staff)?$staff:FALSE;
								if($staff_data == FALSE)
								{
							?>
								<div id="w2-particulars" class="tab-pane active">
							<?php 
								}else{
							?>
								<div id="w2-particulars" class="tab-pane">
							<?php 
								}
							?>
							
							<div class="form-group">
	                    		<input type="hidden" class="form-control" id="staff_id" name="staff_id" value="<?= isset($staff[0]->id)? $staff[0]->id:'' ?>" style="width: 400px;"/>
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Full Name :</label>
                                    </div>
                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                        <div style="width: 20%;">
                                        	<input type="text" class="form-control" id="staff_name" name="staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;" required/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Nationality :</label>
                                    </div>
                                    <div style="width: 15%;float:left;margin-bottom:5px;">
                                        <div>
                                        	<?php
												echo form_dropdown('staff_nationality', $nationality_list, isset($staff[0]->nationality_id)?$staff[0]->nationality_id: '', 'class="nationality-select" style="width:150%;" required');
											?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group singapore_pr_checkbox" style="display: none">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Singapore PR :</label>
                                    </div>
                                    <div style="width: 190px;float:left;margin-bottom:5px;">
                                    	<div>
									        <input type="checkbox" name="singapore_pr" />
									        <input type="hidden" name="hidden_singapore_pr" value="<?php echo (isset($staff[0]->singapore_pr)? $staff[0]->singapore_pr : 0) ?>"/>
									    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group attachment_singapore_pr_button" style="display: none">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Attach Singapore PR :</label>
                                    </div>
                                    <div style="width: 55%;float:left;margin-bottom:5px;">
                                    	<div class='input-group'>
		                                	<input type='file' style='display:none' id='attachment_singapore_pr' multiple='' name='attachment_singapore_pr[]'>
		                                	<label for='attachment_singapore_pr' class='btn btn_blue attachment_singapore_pr'>Select Attachment</label><br/>
		                                	<span class='file_name_singapore_pr' id='file_name_singapore_pr'></span>
		                                	<input type='hidden' class='hidden_attachment_singapore_pr' name='hidden_attachment_singapore_pr' value='<?php echo isset($staff[0]->attachment_singapore_pr)?$staff[0]->attachment_singapore_pr: ''?>'/>
		                                </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>NRIC/Passport No :</label>
                                    </div>
                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                        <div style="width: 20%;">
                                        	<input type="text" class="form-control" id="staff_nric_finno" name="staff_nric_finno" value="<?=isset($staff[0]->nric_fin_no)?$staff[0]->nric_fin_no:'' ?>" style="width: 400px;" required/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group attachment_nric_button">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Attach NRIC/Passport No :</label>
                                    </div>
                                    <div style="width: 55%;float:left;margin-bottom:5px;">
                                    	<div class='input-group'>
		                                	<input type='file' style='display:none' id='attachment_nric' multiple='' name='attachment_nric[]'>
		                                	<label for='attachment_nric' class='btn btn_blue attachment_nric'>Select Attachment</label><br/>
		                                	<span class='file_name_nric' id='file_name_nric'></span>
		                                	<input type='hidden' class='hidden_attachment_nric' name='hidden_attachment_nric' value='<?php echo isset($staff[0]->attachment_nric)?$staff[0]->attachment_nric: ''?>'/>
		                                </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Address :</label>
                                    </div>
                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                        <div style="width: 20%;">
                                        	<textarea class="form-control" id="staff_address" name="staff_address" style="width: 400px;" required><?php echo (isset($staff[0]->address))?$staff[0]->address:'';?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Mobile Phone :</label>
                                    </div>
                                    <div style="width: 65%;float:left;margin-bottom:5px;">
                                        <div style="width: 20%;">
                                        	<input type="text" class="form-control" id="staff_phoneno" name="staff_phoneno" value="<?= isset($staff[0]->phoneno)?$staff[0]->phoneno:'' ?>" style="width: 400px;" required/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
		                    	<div style="width: 100%;">
			                    	<div style="width: 25%;float:left;margin-right: 20px;">
			                            <label>D.O.B :</label>
			                        </div>
			                    	<div style="width: 30%;float: left;">
			                    		<div class="input-group date datepicker" data-provide="datepicker">
			                    			<div class="input-group-addon">
										        <span class="far fa-calendar-alt"></span>
										    </div>
										    <input type="text" class="form-control" id="staff_DOB" name="staff_DOB" value="<?=isset($staff[0]->dob)?date("d F Y",strtotime($staff[0]->dob)):''?>" required>
										    
										</div>
			                        </div>
			                    </div>
		                    </div>
		                    <div class="form-group">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Marital Status :</label>
                                    </div>
                                    <div style="width: 190px;float:left;margin-bottom:5px;">
                                    	<div>
									        <input type="checkbox" name="marital_status" />
									        <input type="hidden" name="hidden_marital_status" value="<?php echo (isset($staff[0]->marital_status)? $staff[0]->marital_status : 0) ?>"/>
									    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group attachment_marital_status_button" style="display: none">
                                <div style="width: 100%;">
                                    <div style="width: 25%;float:left;margin-right: 20px;">
                                        <label>Attach Marital Status :</label>
                                    </div>
                                    <div style="width: 55%;float:left;margin-bottom:5px;">
                                    	<div class='input-group'>
		                                	<input type='file' style='display:none' id='attachment_marital_status' multiple='' name='attachment_marital_status[]'>
		                                	<label for='attachment_marital_status' class='btn btn_blue attachment_marital_status'>Select Attachment</label><br/>
		                                	<span class='file_name_marital_status' id='file_name_marital_status'></span>
		                                	<input type='hidden' class='hidden_attachment_marital_status' name='hidden_attachment_marital_status' value='<?php echo isset($staff[0]->attachment_marital_status)?$staff[0]->attachment_marital_status: ''?>'/>
		                                </div>
                                    </div>
                                </div>
                            </div>
						</div>

						<div id="w2-family" class="tab-pane">
							<div>
                                <table class="table table-bordered table-striped table-condensed mb-none" id="family_info_table">
                                    <thead>
                                        <div class="tr">
                                            <div class="th" id="family_name" style="text-align: center;width:150px">Name</div>
                                            <div class="th" id="nric" style="text-align: center;width:170px">NRIC/Passport</div>
                                            <div class="th" style="text-align: center;width:190px" id="dob">D.O.B</div>
                                            <div class="th" id="nationality " style="text-align: center;width:160px">Nationality </div>
                                            <div class="th" id="relationship" style="text-align: center;width:150px">Relationship</div>
                                            <div class="th" id="contact" style="text-align: center;width:150px">Contact</div>
                                            <div class="th" id="document" style="text-align: center;width:150px">Proof of Document</div>
                                            <a href="javascript: void(0);" class="th" rowspan=2 style="color: #D9A200;width:80px; outline: none !important;text-decoration: none;"><span id="family_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Family Info" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add </span></a>
                                            <div class="th" id="in_use" style="text-align: center;width:80px">Verify</div>
                                        </div>
                                        
                                    </thead>
                                    <div class="tbody" id="body_family_info">
                                        

                                    </div>
                                    
                                </table>
                            </div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 number text-right" id="client_footer_button">
					<!--input type="button" value="Save As Draft" id="save_draft" class="btn btn-default"-->
					<!-- <input type="button" value="Save" id="save" class="btn btn-primary ">
					<a href="<?= base_url();?>masterclient/" class="btn btn-default">Cancel</a>

					<div style="width: 65%;float:left;margin-bottom:5px;"> -->
                    	<?php 
                    		echo '<a href="'.base_url().'employee" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

                    		if($create){
                    			echo '<button class="btn btn_blue pull-right" style="margin:0.5%">Create</button>';
                    		}
                    		else{
                    			echo '<button class="btn btn_blue pull-right" style="margin:0.5%">Save</button>';
                    		}
                    	?>
                    <!-- </div> -->
				</div>
				<div class="col-md-12 number text-right" id="client_footer_cancel_button" style="display: none;">
                    	<?php 
                    		echo '<a href="'.base_url().'employee" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';
                    	?>
				</div>
			</div>
		</footer>
		</form>
	</section>
</section>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<!-- <section role="main" class="content_section" style="margin-left:0;">
	<?php echo $breadcrumbs;?>
	<section class="panel" style="margin-top: 30px;">
	<div class="panel-body">
		<div class="box-content">
		    <div class="row">
		        <div class="col-lg-12">
		            <form id="employee" method="POST"> 
			            <div class="row">
			                <div class="col-md-12">
			                    <div class="col-md-12">
			                    	<div class="form-group">
			                    		<input type="hidden" class="form-control" id="staff_id" name="staff_id" value="<?= isset($staff[0]->id)? $staff[0]->id:'' ?>" style="width: 400px;"/>
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>Staff Name :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div style="width: 20%;">
		                                        	<input type="text" class="form-control" id="staff_name" name="staff_name" value="<?=isset($staff[0]->name)? $staff[0]->name:'' ?>" style="width: 400px;" required/>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>NRIC/FIN No :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div style="width: 20%;">
		                                        	<input type="text" class="form-control" id="staff_nric_finno" name="staff_nric_finno" value="<?=isset($staff[0]->nric_fin_no)?$staff[0]->nric_fin_no:'' ?>" style="width: 400px;" required/>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>Address :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div style="width: 20%;">
		                                        	<textarea class="form-control" id="staff_address" name="staff_address" style="width: 400px;" required><?php echo (isset($staff[0]->address))?$staff[0]->address:'';?></textarea>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>Phone /Mobile Phone :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div style="width: 20%;">
		                                        	<input type="text" class="form-control" id="staff_phoneno" name="staff_phoneno" value="<?= isset($staff[0]->phoneno)?$staff[0]->phoneno:'' ?>" style="width: 400px;" required/>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>

		                            <div class="form-group">
	                                    <div style="width: 100%;">
	                                        <div style="width: 25%;float:left;margin-right: 20px;">
	                                            <label>Nationality :</label>
	                                        </div>
	                                        <div style="width: 65%;float:left;margin-bottom:5px;">
	                                            <div style="width: 20%;" >
	                                            	<?php
	    												echo form_dropdown('staff_nationality', $nationality_list, isset($staff[0]->nationality_id)?$staff[0]->nationality_id: '', 'class="nationality-select" style="width:150%;" required');
	    											?>
	                                            </div>
	                                            <div id="form_url"></div>
	                                        </div>
	                                    </div>
	                                </div>
		                            <div class="form-group">
	    		                    	<div style="width: 100%;">
	    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
	    			                            <label>D.O.B :</label>
	    			                        </div>
	    			                    	<div style="width: 30%;float: left;">
	    			                    		<div class="input-group date datepicker" data-provide="datepicker">
	    			                    			<div class="input-group-addon">
												        <span class="far fa-calendar-alt"></span>
												    </div>
												    <input type="text" class="form-control" id="staff_DOB" name="staff_DOB" value="<?=isset($staff[0]->dob)?date("d F Y",strtotime($staff[0]->dob)):''?>" required>
												    
												</div>
	    			                        </div>
	    			                    </div>
	    		                    </div>

	    		                    <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>Firm :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div style="width: 20%;">
		                                        	<?php
	    												echo form_dropdown('firm_id', $firm_list, isset($staff[0]->firm_id)?$staff[0]->firm_id: '', 'class="firm-select" style="width:150%;" required');
	    											?>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
	    		                    <div class="form-group">
	    		                    	<div style="width: 100%;">
	    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
	    			                            <label>Date joined :</label>
	    			                        </div>
	    			                    	<div style="width: 30%;float: left;">
	    			                    		<div class="input-group date datepicker" data-provide="datepicker">
	    			                    			<div class="input-group-addon">
												        <span class="far fa-calendar-alt"></span>
												    </div>
												    <input type="text" class="form-control" id="staff_joined" name="staff_joined" data-date-format="d F Y" value="<?=isset($staff[0]->date_joined)?date("d F Y",strtotime($staff[0]->date_joined)):''?>" required>
												</div>
	    			                        </div>
	    			                    </div>
	    		                    </div>
	    		                    <div class="form-group">
	    		                    	<div style="width: 100%;">
	    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
	    			                            <label>Date of cessation :</label>
	    			                        </div>
	    			                    	<div style="width: 30%;float: left;">
	    			                            <div class="input-group date datepicker" data-provide="datepicker">
	    			                            	<div class="input-group-addon">
												        <span class="far fa-calendar-alt"></span>
												    </div>
	    			                            	<input type="text" class="form-control" id="staff_cessation" name="staff_cessation" data-date-format="d F Y" value="<?=isset($staff[0]->date_cessation)?date("d F Y",strtotime($staff[0]->date_cessation)):''?>">
	    										</div>
	    			                        </div>
	    			                    </div>
	    		                    </div>
	    		                    <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>Designation :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div style="width: 20%;">
		                                        	<input type="text" class="form-control" id="staff_designation" name="staff_designation" value="<?=isset($staff[0]->designation)?$staff[0]->designation:'' ?>" style="width: 400px;"/>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>Department :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div style="width: 30%;">
		                                        	<?php 
		                                        		echo form_dropdown('staff_department', $department_list, isset($staff[0]->department)?$staff[0]->department:'', 'class="form-control"');
		                                        	?>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>Salary ($) :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div style="width: 20%;">
		                                        	<input type="number" class="form-control" id="staff_salary" name="staff_salary" value="<?=isset($staff[0]->salary)?$staff[0]->salary:'' ?>" style="width: 400px;"/>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>Workpass :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div style="width: 30%;">
		                                        	<?php 
		                                        		echo form_dropdown('staff_workpass', $workpass_list, isset($staff[0]->workpass)?$staff[0]->workpass:'', 'class="form-control"');
		                                        	?>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="form-group">
	    		                    	<div style="width: 100%;">
	    			                    	<div style="width: 25%;float:left;margin-right: 20px;">
	    			                            <label>Expire on :</label>
	    			                        </div>
	    			                    	<div style="width: 30%;float: left;">
	    			                            <div class="input-group date datepicker" data-provide="datepicker">
	    			                            	<div class="input-group-addon">
												        <span class="far fa-calendar-alt"></span>
												    </div>
	    			                            	<input type="text" class="form-control" id="staff_pass_expire" name="staff_pass_expire" data-date-format="d F Y" value="<?=isset($staff[0]->pass_expire)?date("d F Y",strtotime($staff[0]->pass_expire)):''?>">
	    										</div>
	    			                        </div>
	    			                    </div>
	    		                    </div>
	    		                    <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>Annual leave/year :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div class="input-group" style="width: 40%;">
		                                        	<input type="number" class="form-control" id="staff_annual_leave" name="staff_annual_leave" value="<?=isset($staff[0]->annual_leave_year)?$staff[0]->annual_leave_year:'' ?>" style="width: 50%; border-radius: 4px;"/>
		                                        	<label style="margin:3%">days</label>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <?php 
		                            	echo
		                            		'<div class="form-group">
						                    	<div style="width: 100%;">
							                    	<div style="width: 25%;float:left;margin-right: 20px;">
							                            <label>AWS given :</label>
							                        </div>
							                    	<div style="width: 65%;float:left;margin-bottom:5px;">
							                    		<div>
													        <input type="checkbox" name="staff_aws_given" />
													        <input type="hidden" name="hidden_staff_aws_given" value="'. (isset($staff[0]->aws_given)? $staff[0]->aws_given : 0) .'"/>
													    </div>
							                    	</div>
							                    </div>
						                    </div>';
		                            ?>
		                            <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>CPF (Employee) :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div class="input-group" style="width: 20%;">
		                                        	<input type="number" step="0.01" class="form-control" id="staff_cpf_employee" name="staff_cpf_employee" value="<?=isset($staff[0]->cpf_employee)?$staff[0]->cpf_employee:'' ?>" style="width: 100px;border-radius: 4px;"/>
		                                        	<label style="margin:3%">%</label>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>CPF (Employer) :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div class="input-group" style="width: 20%;">
		                                        	<input type="number" step="0.01" class="form-control" id="staff_cpf_employer" name="staff_cpf_employer" value="<?=isset($staff[0]->cpf_employer)?$staff[0]->cpf_employer:'' ?>" style="width: 100px;border-radius: 4px;"/>
		                                        	<label style="margin:3%">%</label>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>CDAC/MENDAKI/SINDA ($) :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div style="width: 20%;">
		                                        	<input type="number" step="0.01" class="form-control" id="staff_cdac" name="staff_cdac" value="<?=isset($staff[0]->cdac)?$staff[0]->cdac:'' ?>" style="width: 100px;"/>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>Remark :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div style="width: 20%;">
		                                        	<textarea class="form-control" id="staff_remark" name="staff_remark" style="width: 400px;"><?php echo isset($staff[0]->remark)?$staff[0]->remark:'' ?></textarea>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>Supervisor :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div style="width: 20%;">
		                                        	<input type="text" class="form-control" id="staff_supervisor" name="staff_supervisor" value="<?=isset($staff[0]->supervisor)?$staff[0]->supervisor:'' ?>" style="width: 400px;"/>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <div style="width: 100%;">
		                                    <div style="width: 25%;float:left;margin-right: 20px;">
		                                        <label>Status :</label>
		                                    </div>
		                                    <div style="width: 65%;float:left;margin-bottom:5px;">
		                                        <div style="width: 30%;">
		                                        	<?php 
		                                        		echo form_dropdown('staff_status', $status_list, isset($staff[0]->employee_status_id)?$staff[0]->employee_status_id:'', 'class="form-control"');
		                                        	?>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
				                    <div class="form-group">
				                        <div style="width: 65%;float:left;margin-bottom:5px;">
				                        	<?php 
				                        		echo '<a href="'.base_url().'employee" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

				                        		if($create){
				                        			echo '<button class="btn btn_blue pull-right" style="margin:0.5%">Create</button>';
				                        		}
				                        		else{
				                        			echo '<button class="btn btn_blue pull-right" style="margin:0.5%">Save</button>';
				                        		}
				                        	?>
				                        </div>
				                    </div>
				                </div>
				            </div>
				        </div>
				    </form>
			    </div>
			</div>
		</div>
	</div>
	</section>
</section> -->
<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	var active_type_of_leave = <?php echo json_encode(isset($active_type_of_leave)?$active_type_of_leave:FALSE) ?>;
	var staff = <?php echo json_encode(isset($staff)?$staff:FALSE) ?>;
	var family_info = <?php echo json_encode(isset($family_info)?$family_info:FALSE) ?>;
	var Admin = <?php echo json_encode($Admin) ?>;
	var Manager = <?php echo json_encode($Manager) ?>;
	var user_id = <?php echo json_encode($this->user_id) ?>;
	var renewed_value = 0;
	var index_tab_aktif;
	//console.log(staff);
	if(active_type_of_leave)
	{
		for(var t = 0; t < active_type_of_leave.length; t++)
		{
			$('.checkbox'+active_type_of_leave[t]["type_of_leave_id"]).prop("checked", true);
		}
	}

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	$(".nationality-select").select2();
	$(".firm-select").select2();

	$('.datepicker').datepicker({
	    format: 'dd MM yyyy',
	});

	$(document).on('click',".check_state",function(){
		index_tab_aktif = $(this).data("information");
		if(index_tab_aktif == "family")
		{
			$("#client_footer_cancel_button").show();
			$("#client_footer_button").hide();
		}
		else
		{
			$("#client_footer_button").show();
			$("#client_footer_cancel_button").hide();
		}
	});

	$(".nationality-select").change(function (){
		var selected_nationality = $(".nationality-select option:selected").text();
		if(selected_nationality == "SINGAPORE CITIZEN")
		{
			$(".singapore_pr_checkbox").hide();
			$("[name='singapore_pr']").bootstrapSwitch('state', false);
			$("[name='hidden_singapore_pr']").val(0);
			$(".attachment_singapore_pr_button").hide();
			$(".file_name_singapore_pr").html("");
	    	$(".hidden_attachment_singapore_pr").val("");
	    	$(".staff_workpass").val("Not Available");
	    	//$(".staff_workpass").attr("disabled", true);
	    	$("#staff_pass_expire").val("");
	    	//$("#staff_pass_expire").attr("disabled", true);
		}
		else
		{
			$(".singapore_pr_checkbox").show();
			$(".staff_workpass").val("");
			//$(".staff_workpass").attr("disabled", false);
			$("#staff_pass_expire").val("");
			//$("#staff_pass_expire").attr("disabled", false);
		}
	});

	//renewed_btn
	$(".renewed_btn").click(function (){
		//console.log("in");
		if(renewed_value == 1)
		{
			$("#staff_pass_expire").attr("disabled", false);
		}
	});

	if(staff != false)
	{
		renewed_value = 1;
		$("#staff_pass_expire").attr("disabled", true);
	}
	//Employee Status
	$(".employee_status").change(function (){
		var selected_employee_status = $(".employee_status option:selected").text();
		//console.log(selected_employee_status);
		if(selected_employee_status == "Confirmed")
		{
			$(".date_of_letter_div").show();
			$(".effective_date_div").show();
		}
		else
		{
			$(".date_of_letter_div").hide();
			$(".effective_date_div").hide();
		}
	});

	if(staff != false)
	{
		if(staff[0]['employee_status_id'] == 2)
		{
			$(".date_of_letter_div").show();
			$(".effective_date_div").show();
		}
		else
		{
			$(".date_of_letter_div").hide();
			$(".effective_date_div").hide();
		}
	}

	//Attach Singapore PR
	if(staff != false)
	{
		if(staff[0]["singapore_pr"] == 1)
		{
			$(".singapore_pr_checkbox").show();
			$(".attachment_singapore_pr_button").show();

			var file_result_singapore_pr = JSON.parse(staff[0]["attachment_singapore_pr"]);
	        var filename_singapore_pr = "";
	        //console.log(file_result_singapore_pr.length);
	        for(var i = 0; i < file_result_singapore_pr.length; i++)
	        {
		        if(i == 0)
		        {
		            filename_singapore_pr = '<a href="'+base_url+'uploads/singapore_pr/'+file_result_singapore_pr[i]+'" target="_blank">'+file_result_singapore_pr[i]+'</a>';
		        }
		        else
		        {
		            filename_singapore_pr = filename_singapore_pr + ", " + '<a href="'+base_url+'uploads/singapore_pr/'+file_result_singapore_pr[i]+'" target="_blank">'+file_result_singapore_pr[i]+'</a>';
		        }
	        }
	        $("#file_name_singapore_pr").html(filename_singapore_pr);
		}
	}

	$(document).on('change','[id=attachment_singapore_pr]',function(){
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
	    //console.log(filename);
	    $(this).parent().find(".file_name_singapore_pr").html(filename);
	    $(this).parent().find(".hidden_attachment_singapore_pr").val("");
	});

  	$("[name='singapore_pr']").bootstrapSwitch({
	    state: <?php echo isset($staff[0]->singapore_pr)? $staff[0]->singapore_pr : 0 ?>,
	    size: 'normal',
	    onColor: 'blue',
	    onText: 'Yes',
	    offText: 'No',
	    // Text of the center handle of the switch
	    labelText: '&nbsp',
	    // Width of the left and right sides in pixels
	    handleWidth: '55px',
	    // Width of the center handle in pixels
	    labelWidth: 'auto',
	    baseClass: 'bootstrap-switch',
	    wrapperClass: 'wrapper'
	});

	$("[name='singapore_pr']").on('switchChange.bootstrapSwitch', function(event, state) {
		if(state == true)
	    {
			$("[name='hidden_singapore_pr']").val(1);
			$(".attachment_singapore_pr_button").show();
			$(".staff_workpass").val("Not Available");
	    	//$(".staff_workpass").attr("disabled", true);
	    	$("#staff_pass_expire").val("");
	    	//$("#staff_pass_expire").attr("disabled", true);
		}
		else
		{
			$("[name='hidden_singapore_pr']").val(0);
			$(".attachment_singapore_pr_button").hide();
			$(".file_name_singapore_pr").html("");
	    	$(".hidden_attachment_singapore_pr").val("");

			$(".staff_workpass").val("");
			//$(".staff_workpass").attr("disabled", false);
			$("#staff_pass_expire").val("");
			//$("#staff_pass_expire").attr("disabled", false);
		}
	});

	//Attach NRIC/Passport No
	if(staff != false)
	{
		if(0 < JSON.parse(staff[0]["attachment_nric"]).length)
		{
			var file_result_nric = JSON.parse(staff[0]["attachment_nric"]);
	        var filename_nric = "";
	        //console.log(file_result_nric.length);
	        for(var i = 0; i < file_result_nric.length; i++)
	        {
		        if(i == 0)
		        {
		            filename_nric = '<a href="'+base_url+'uploads/nric/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
		        }
		        else
		        {
		            filename_nric = filename_nric + ", " + '<a href="'+base_url+'uploads/nric/'+file_result_nric[i]+'" target="_blank">'+file_result_nric[i]+'</a>';
		        }
	        }
	        $("#file_name_nric").html(filename_nric);
		}
	}

  	$(document).on('change','[id=attachment_nric]',function(){
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
	    //console.log(filename);
	    $(this).parent().find(".file_name_nric").html(filename);
	    $(this).parent().find(".hidden_attachment_nric").val("");
	});
	//Attach Marital Status
	if(staff != false)
	{
		if(staff[0]["marital_status"] == 1)
		{
			$(".attachment_marital_status_button").show();

			var file_result_marital_status = JSON.parse(staff[0]["attachment_marital_status"]);
	        var filename_marital_status = "";
	        //console.log(file_result_marital_status.length);
	        for(var i = 0; i < file_result_marital_status.length; i++)
	        {
		        if(i == 0)
		        {
		            filename_marital_status = '<a href="'+base_url+'uploads/marital_status/'+file_result_marital_status[i]+'" target="_blank">'+file_result_marital_status[i]+'</a>';
		        }
		        else
		        {
		            filename_marital_status = filename_marital_status + ", " + '<a href="'+base_url+'uploads/marital_status/'+file_result_marital_status[i]+'" target="_blank">'+file_result_marital_status[i]+'</a>';
		        }
	        }
	        $("#file_name_marital_status").html(filename_marital_status);
		}
	}

	$(document).on('change','[id=attachment_marital_status]',function(){
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
	    //console.log(filename);
	    $(this).parent().find(".file_name_marital_status").html(filename);
	    $(this).parent().find(".hidden_attachment_marital_status").val("");
	});

  	$("[name='marital_status']").bootstrapSwitch({
	    state: <?php echo isset($staff[0]->marital_status)? $staff[0]->marital_status : 0 ?>,
	    size: 'normal',
	    onColor: 'blue',
	    onText: 'Yes',
	    offText: 'No',
	    // Text of the center handle of the switch
	    labelText: '&nbsp',
	    // Width of the left and right sides in pixels
	    handleWidth: '55px',
	    // Width of the center handle in pixels
	    labelWidth: 'auto',
	    baseClass: 'bootstrap-switch',
	    wrapperClass: 'wrapper'
	});

	$("[name='marital_status']").on('switchChange.bootstrapSwitch', function(event, state) {
		if(state == true)
	    {
			$("[name='hidden_marital_status']").val(1);
			$(".attachment_marital_status_button").show();
		}
		else
		{
			$("[name='hidden_marital_status']").val(0);
			$(".attachment_marital_status_button").hide();
			$(".file_name_marital_status").html("");
	    	$(".hidden_attachment_marital_status").val("");
		}
	});

  	$("[name='staff_aws_given']").bootstrapSwitch({
	    state: <?php echo isset($staff[0]->aws_given)? $staff[0]->aws_given : 0 ?>,
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

	$("[name='staff_aws_given']").on('switchChange.bootstrapSwitch', function(event, state) {
		if(state == true)
	    {
			$("[name='hidden_staff_aws_given']").val(1);
		}
		else
		{
			$("[name='hidden_staff_aws_given']").val(0);
		}
	});

    $('form#employee').submit(function(e) {
	    //var form = $(this);

	    e.preventDefault();
		//$(".staff_workpass").attr("disabled", false);
		$("#staff_pass_expire").attr("disabled", false);
	    var formData = new FormData($('form')[0]);
	    $('#loadingmessage').show();
	    $.ajax({
	        type: "POST",
	        url: "<?php echo site_url('employee/create_employee'); ?>",
	        data: formData,
	        dataType: 'json',
            // Tell jQuery not to process data or worry about content-type
            // You *must* include these options!
            // + '&vendor_name_text=' + $(".vendor_name option:selected").text()
            cache: false,
            contentType: false,
            processData: false,
	        success: function(data){
	            // $('#feed-container').prepend(data);
	            // $('input[name=interview_code]').val(data);
	            //console.log(data);
	            //var data = JSON.parse(data);
	            $('#loadingmessage').hide();
	            //$(".staff_workpass").attr("disabled", true);
				$("#staff_pass_expire").attr("disabled", true);
				$(".previous_staff_status").val($(".employee_status").val());
				$(".previous_status_date").val($("#status_date").val());

				renewed_value = 1;
	            if(data[0]["status"] == "created"){
	            	//alert("Data is successfully " + data + ".");
	            	$("#staff_id").val(data[0]["employee_id"]);
	            	toastr.success('Information Updated', 'Updated');
	            	// window.location = '<?php echo base_url(); ?>' + "employee/index";
	            }else if(data[0]["status"] == "updated"){
	            	toastr.success('Information Updated', 'Updated');
	            }else{
	            	toastr.error('Something went wrong. Data is failed to create/save. Please try again later.', 'Error');
	            	//alert("Something went wrong. Data is failed to create/save. Please try again later.");
	            }
	        },
	        // error: function() { alert("Error posting feed."); }
	   });
	});

	//Family Tab
	if(family_info)
	{
		$count_family_info = family_info.length - 1;
	}
	else
	{
		$count_family_info = 0;
	}
	$(document).on('click',"#family_Add",function() {

		//console.log($("#body_family_info form").index());
		$count_family_info++;
	 	$a=""; 
		$a += '<form class="tr family_editing sort_id" method="post" enctype="multipart/form-data" name="form'+$count_family_info+'" id="form'+$count_family_info+'">';
		$a += '<div class="hidden"><input type="text" class="form-control employee_id" name="employee_id[]" id="employee_id" value=""/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="family_info_id[]" id="family_info_id" value=""/></div>';
		$a += '<div class="td"><input type="text" name="family_name[]" id="family_name" class="form-control" value=""/><div id="form_family_name"></div></div>';
		$a += '<div class="td"><input type="text" name="nric[]" class="form-control" value="" id="nric"/><div id="form_nric"></div></div>';
		$a += '<div class="td"><div class="input-group date datepicker" data-provide="datepicker"><div class="input-group-addon"><span class="far fa-calendar-alt"></span></div><input type="text" class="form-control dob" id="dob" name="dob[]" value=""></div><div id="form_dob"></div></div>';
		$a += '<div class="td">';
		$a += '<select class="form-control nationality" style="width: 100%;" name="nationality[]" id="nationality"><option value="0">Select Nationality</option></select><div id="form_nationality"></div>';
		$a += '</div>';
		$a += '<div class="td">';
		$a += '<select class="form-control relationship" style="width: 100%;" name="relationship[]" id="relationship"><option value="0">Select Relationship</option></select><div id="form_relationship"></div>';
		$a += '</div>';
		$a += '<div class="td">';
		$a += '<input type="text" name="contact[]" id="contact" class="form-control" value=""/><div id="form_contact"></div>';
		$a += '</div>';
		$a += '<div class="td">';
		$a += "<div class='input-group'><input type='file' style='display:none' id='attachment_proof_of_document' multiple='' name='attachment_proof_of_document[]'><label for='attachment_proof_of_document' class='btn btn_blue attachment_proof_of_document'>Select Attachment</label><br/><span class='file_name_proof_of_document' id='file_name_proof_of_document'></span><input type='hidden' class='hidden_attachment_proof_of_document' name='hidden_attachment_proof_of_document' value=''/></div>";
		$a += '</div>';
		$a += '<div class="td family_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn_blue submit_family_info_button" onclick="edit_family(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn_blue" onclick="delete_family_info(this);">Delete</button></div></div>';
		if(Admin || Manager)
		{
			$a += '<div class="td"><label class="switch"><input name="verify_family_switch" class="verify_family_switch" type="checkbox"><span class="slider round"></span></label></div>';
		}
		else
		{
			$a += '<div class="td"></div>';
		}

		$a += '</form>';
		
		$("#body_family_info").prepend($a); 

		$('#form'+$count_family_info).find(".employee_id").val($("#staff_id").val());


		$("#loadingmessage").show();
		$.ajax({
	        type: "GET",
	        url: "<?php echo site_url('employee/get_nationality'); ?>",
	        async: false,
	        dataType: "json",
	        success: function(data){

	            $("#loadingmessage").hide();
	            $.each(data, function(key, val) {
	                var option = $('<option />');
	                option.attr('value', key).text(val);
	                $('#form'+$count_family_info).find(".nationality").append(option);
	            });

	            //$('#form'+$count_family_info).find(".nationality"+$count_family_info).select2();
	        }
	    });

	    $("#loadingmessage").show();
		$.ajax({
	        type: "GET",
	        url: "<?php echo site_url('employee/get_family_relationship'); ?>",
	        async: false,
	        dataType: "json",
	        success: function(data){

	            $("#loadingmessage").hide();
	            $.each(data, function(key, val) {
	                var option = $('<option />');
	                option.attr('value', key).text(val);
	                $('#form'+$count_family_info).find(".relationship").append(option);
	            });

	            //$('#form'+$count_family_info).find(".relationship"+$count_family_info).select2();
	        }
	    });

		$('#form'+$count_family_info).find('.datepicker').datepicker({
		    format: 'dd MM yyyy',
		});
	});

	if(family_info)
	{
		//console.log(family_info);
		for(var i = 0; i < family_info.length; i++)
		{
			$a=""; 
			$a += '<form class="tr sort_id" method="post" enctype="multipart/form-data" name="form'+i+'" id="form'+i+'">';
			$a += '<div class="hidden"><input type="text" class="form-control" name="employee_id[]" id="employee_id" value="'+family_info[i]["employee_id"]+'"/></div>';
			$a += '<div class="hidden"><input type="text" class="form-control" name="family_info_id[]" id="family_info_id" value="'+family_info[i]["id"]+'"/></div>';
			$a += '<div class="td"><input type="text" name="family_name[]" id="family_name" class="form-control" value="'+family_info[i]["family_name"]+'" disabled="disabled"/><div id="form_family_name"></div></div>';
			$a += '<div class="td"><input type="text" name="nric[]" class="form-control" value="'+family_info[i]["nric"]+'" id="nric" disabled="disabled"/><div id="form_nric"></div></div>';
			$a += '<div class="td"><div class="input-group date datepicker" data-provide="datepicker"><div class="input-group-addon"><span class="far fa-calendar-alt"></span></div><input type="text" class="form-control dob" id="dob" name="dob[]" value="" disabled="disabled"></div><div id="form_dob"></div></div>';
			$a += '<div class="td">';
			$a += '<select class="form-control nationality" style="width: 100%;" name="nationality[]" id="nationality" disabled="disabled"><option value="0">Select Nationality</option></select><div id="form_nationality"></div>';
			$a += '</div>';
			$a += '<div class="td">';
			$a += '<select class="form-control relationship" style="width: 100%;" name="relationship[]" id="relationship" disabled="disabled"><option value="0">Select Relationship</option></select><div id="form_relationship"></div>';
			$a += '</div>';
			$a += '<div class="td">';
			$a += '<input type="text" name="contact[]" id="contact" class="form-control" value="'+family_info[i]["contact"]+'" disabled="disabled"/><div id="form_contact"></div>';
			$a += '</div>';
			$a += '<div class="td">';
			$a += "<div class='input-group'><input type='file' style='display:none' id='attachment_proof_of_document' class='attachment_proof_of_document' multiple='' name='attachment_proof_of_document[]' disabled='disabled'><label for='attachment_proof_of_document' class='btn btn_blue attachment_proof_of_document' disabled='disabled'>Select Attachment</label><br/><span class='file_name_proof_of_document' id='file_name_proof_of_document'></span><input type='hidden' class='hidden_attachment_proof_of_document' name='hidden_attachment_proof_of_document' value=''/></div>";
			$a += '</div>';
			$a += '<div class="td family_action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn_blue submit_family_info_button" onclick="edit_family(this);">Edit</button></div><div style="display: inline-block;"><button type="button" class="btn btn_blue" onclick="delete_family_info(this);">Delete</button></div></div>';
			if(Admin || Manager)
			{
				if(family_info[i]["user_id"] != user_id)
				{
					$a += '<div class="td"><label class="switch"><input name="verify_family_switch" class="verify_family_switch" type="checkbox" '+((family_info[i]["verify"] == 1)?"checked":"")+'><span class="slider round"></span></label></div>';
				}
				else
				{
					$a += '<div class="td"></div>';
				}
			}
			else
			{
				$a += '<div class="td"></div>';
			}

			$a += '</form>';
			//console.log($a);
			$("#body_family_info").prepend($a); 

			$("#loadingmessage").show();
			$.ajax({
		        type: "GET",
		        url: "<?php echo site_url('employee/get_nationality'); ?>",
		        async: false,
		        dataType: "json",
		        success: function(data){

		            $("#loadingmessage").hide();
		            $.each(data, function(key, val) {
		                var option = $('<option />');
		                option.attr('value', key).text(val);
		                if(family_info[i]["nationality"] != undefined && key == family_info[i]["nationality"])
		                {
		                    option.attr('selected', 'selected');
		                }
		                $('#form'+i).find(".nationality").append(option);
		            });

		            //$('#form'+i).find(".nationality"+i).select2();
		        }
		    });

		    $("#loadingmessage").show();
			$.ajax({
		        type: "GET",
		        url: "<?php echo site_url('employee/get_family_relationship'); ?>",
		        async: false,
		        dataType: "json",
		        success: function(data){

		            $("#loadingmessage").hide();
		            $.each(data, function(key, val) {
		                var option = $('<option />');
		                option.attr('value', key).text(val);
		                if(family_info[i]["relationship"] != undefined && key == family_info[i]["relationship"])
		                {
		                    option.attr('selected', 'selected');
		                }
		                $('#form'+i).find(".relationship").append(option);
		            });

		            //$('#form'+i).find(".relationship"+i).select2();
		        }
		    });

			$('#form'+i).find(".dob").val(moment(family_info[i]["dob"]).format('DD MMMM YYYY'));

			$('#form'+i).find('.datepicker').datepicker({
			    format: 'dd MM yyyy',
			});

			if(0 < JSON.parse(family_info[i]["proof_of_document"]).length)
			{
				var file_result_proof_of_document = JSON.parse(family_info[i]["proof_of_document"]);
		        var filename_proof_of_document = "";
		        //console.log(file_result_proof_of_document.length);
		        for(var p = 0; p < file_result_proof_of_document.length; p++)
		        {
			        if(i == 0)
			        {
			            filename_proof_of_document = '<a href="'+base_url+'uploads/proof_of_document/'+file_result_proof_of_document[p]+'" target="_blank">'+file_result_proof_of_document[p]+'</a>';
			        }
			        else
			        {
			            filename_proof_of_document = filename_proof_of_document + ", " + '<a href="'+base_url+'uploads/proof_of_document/'+file_result_proof_of_document[p]+'" target="_blank">'+file_result_proof_of_document[p]+'</a>';
			        }
		        }
		        $('#form'+i).find("#file_name_proof_of_document").html(filename_proof_of_document);
			}
		}
	}

	$(document).on('change','[id=attachment_proof_of_document]',function(){
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
	    //console.log(filename);
	    $(this).parent().find(".file_name_proof_of_document").html(filename);
	    $(this).parent().find(".hidden_attachment_proof_of_document").val("");
	});


	$(document).on("change","[name='verify_family_switch']",function(element) {

		var checkbox = $(this);
		var checked = this.checked;
		var family_info_id = $(this).parent().parent().parent().find("#family_info_id").val();

		if(family_info_id == "")
		{
			checkbox.prop('checked', false);
			toastr.error("Please save the information before you verify family.", "Error");
		}
		else
		{
			bootbox.confirm({
			    message: "Do you wanna change the verification?",
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
			        	$("#loadingmessage").show();
						$.ajax({
							type: "POST",
							url: "<?php echo site_url('employee/check_verify_family'); ?>",
							data: {"checked":checked, "staff_id": $("#staff_id").val(), "family_info_id": family_info_id}, // <--- THIS IS THE CHANGE
							dataType: "json",
							success: function(response){
								$("#loadingmessage").hide();
								if(response.Status == 1)
								{
									//$('input[name="verify_family_switch"]:checked').not(checkbox).prop('checked', false);
									toastr.success(response.message, response.title);
								}
							}
						});
					}
					else
					{
						if(checked)
						{
							//console.log(checkbox);
							checkbox.prop('checked', false);
						}
						else
						{
							checkbox.prop('checked', true);
						}
					}
			    }
			})
		}
	});

	function edit_family(element)
	{
		var tr = jQuery(element).parent().parent().parent();
		if(!tr.hasClass("family_editing")) 
		{
			tr.addClass("family_editing");
			tr.find("DIV.td").each(function()
			{
				if(!jQuery(this).hasClass("family_action"))
				{
					jQuery(this).find('input[name="family_name[]"]').attr('disabled', false);
					jQuery(this).find('input[name="nric[]"]').attr('disabled', false);
					jQuery(this).find('input[name="dob[]"]').attr('disabled', false);
					jQuery(this).find('input[name="contact[]"]').attr('disabled', false);
					jQuery(this).find('input[class="attachment_proof_of_document"]').attr('disabled', false);
					jQuery(this).find('.attachment_proof_of_document').attr('disabled', false);
					jQuery(this).find("select").attr('disabled', false);
				} 
				else 
				{
					jQuery(this).find(".submit_family_info_button").text("Save");
				}
			});
		} 
		else 
		{
			// var frm = $(element).closest('form');

			// var frm_serialized = frm.serialize();

			var formData = new FormData($(element).closest('form')[0]);

			family_info_submit(formData, tr);

		}
	}

	function family_info_submit(frm_serialized, tr)
	{
		//console.log(tr);
		$('#loadingmessage').show();
		$.ajax({ //Upload common input
	        url: "<?php echo site_url('employee/add_family_info'); ?>",
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
	        	$('#loadingmessage').hide();
	        	//console.log(response.Status);
	            if (response.Status === 1) {
	            	//var errorsDateOfCessation = ' ';
	            	toastr.success(response.message, response.title);
	            	if(response.insert_family_info_id != null)
	            	{
	            		tr.find('input[name="family_info_id[]"]').attr('value', response.insert_family_info_id);
	            	}
	            	tr.removeClass("family_editing");

					tr.find("DIV.td").each(function(){
						if(!jQuery(this).hasClass("family_action"))
						{
							jQuery(this).find('input[name="family_id[]"]').attr('readonly', true);
							jQuery(this).find('input[name="family_name[]"]').attr('disabled', true);
							jQuery(this).find('input[name="nric[]"]').attr('disabled', true);
							jQuery(this).find('input[name="dob[]"]').attr('disabled', true);
							jQuery(this).find('input[name="contact[]"]').attr('disabled', true);
							jQuery(this).find('input[class="attachment_proof_of_document"]').attr('disabled', true);
							jQuery(this).find('.attachment_proof_of_document').attr('disabled', true);
							jQuery(this).find("select").attr('disabled', true);
							
						} 
						else 
						{
							jQuery(this).find(".submit_family_info_button").text("Edit");
						}
					});
				    
	            }
	        }
	    });
	}

	function delete_family_info(element)
	{
		var tr = jQuery(element).parent().parent().parent();

		var family_info_id = tr.find('input[name="family_info_id[]"]').val();

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
					if(family_info_id != undefined)
					{
						$.ajax({ //Upload common input
				            url: "<?php echo site_url('employee/delete_family_info'); ?>",
				            type: "POST",
				            data: {"family_info_id": family_info_id},
				            dataType: 'json',
				            success: function (response) {
				            	$('#loadingmessage').hide();
				            	if(response.Status == 1)
				            	{
				            		tr.remove();
				            		toastr.success("Updated Information.", "Updated");

				            	}
				            }
				        });
					}
		        }
		    }
		})
	}

</script>  