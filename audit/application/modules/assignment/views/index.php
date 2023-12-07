<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>node_modules/fullcalendar/dist/fullcalendar.css" />
<script src="<?= base_url() ?>node_modules/fullcalendar/dist/fullcalendar.js"></script>


<style>
#sameRow{
    display: flex;
    justify-content: space-between;  
}
</style>

<section role="main" class="content_section" style="margin-left:0;">
<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions" style="height:80px">
			<?php if($Admin || $Manager) { ?>
				<a href="javascript:void(0)" data-toggle="modal" class="add_new_assignment amber" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Assignment" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i> New Assignment </a>
			<?php } ?>
		</div>
		<h2></h2>
	</header>

	<div class="panel-body">
		<div class="col-md-12">
			<div class="tabs">

				<!-- Tab -->
					<ul class="nav nav-tabs nav-justify">
						<li class="check_state active" data-information="in_progress">
							<a href="#w2-in_progress" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">1</span>
									In Progress
							</a>
						</li>
				
						<li class="check_state" data-information="completed">
							<a href="#w2-completed" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">2</span>
									Completed
							</a>
						</li>

						<?php if($Admin || $Manager) { ?>
							<li class="check_state" data-information="signed">
								<a href="#w2-signed" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">3</span>
										Signed
								</a>
							</li>
							<li class="check_state" data-information="calendar">
								<a href="#w2-calendar" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">4</span>
										Calendar
								</a>
							</li>
							<!-- <li class="check_state" data-information="logs">
								<a href="#w2-logs" data-toggle="tab" class="text-center">
									<span class="badge hidden-xs">5</span>
										Logs
								</a>
							</li> -->
						<?php } ?>
					</ul>
				<!-- In Progress Tab -->
				<div class="tab-content clearfix">
					<div id="w2-in_progress" class="tab-pane active">
						<div style="float: left;padding-bottom:20px;">
							<div style="width: 200px; float: left;padding-left:10px;">
								<label class="control-label">Partner</label>
								<?php
									echo form_dropdown('A_partner_filter', $partner_list, isset($partner_list[0]->partner_name)?$partner_list[0]->partner_name:'', ' id="A_partner_filter" class="A_partner_filter" style="width:85%;" required');
								?>
							</div>
							<div style="float: left;padding-top:23px;padding-left:10px;">
								<button type="submit" id ="filter1" class="btn btn_purple" >Search</button>
							</div>
							<div style="float: right;padding-top:23px;padding-left:10px;">
								<button type="submit" id ="generate_A_Excel" class="btn btn_purple" >Generate Excel</button>
							</div>
						</div>
						<div class="col-sm-12 col-md-12">
							<table class="table table-bordered table-striped mb-none datatable-inprogress" id="datatable-inprogress" style="width:100%">
								<thead>
									<tr style="background-color:white;">
										<th class="text-left">No.</th>
										<th class="text-left">Clients</th>
										<th class="text-left">Firm</th>
										<th class="text-left">Job Type</th>
										<th class="text-left">PIC</th>
										<th class="text-left">FYE</th>
										<th class="text-left">Account received</th>
										<th class="text-left">Due date</th>
										<th class="text-left">Status</th>
										<th class="text-left">Remark</th>
										<th class="text-left">Expected Completion Date</th>
										<?php
										if($Admin || $Manager) 
											{
												echo '<th class="text-left">Log</th>';
												echo'<th class="text-left"></th>';
											}
										?>	
									</tr>
								</thead>
								<tbody class="testing321">
									
								</tbody>
							</table>
						</div>
					</div>

					<!-- Completed Tab -->
					<div id="w2-completed" class="tab-pane">
						<div style="float: left;padding-bottom:20px;">
							<div style="float: left;padding-top:1px;">
								<label class="control-label">Date range</label>
								<div class="input-daterange input-group block_leave_date" data-plugin-datepicker data-date-format="dd/mm/yyyy">
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control CA_date_from" name="from" value="" placeholder="From">
									<span class="input-group-addon">to</span>
									<input type="text" class="form-control CA_date_to" name="to" value="" placeholder="To">
								</div>
							</div>
							<div style="width: 200px; float: left;padding-left:10px;">
								<label class="control-label">Partner</label>
								<?php
									echo form_dropdown('CA_partner_filter', $partner_list, isset($partner_list[0]->partner_name)?$partner_list[0]->partner_name:'', ' id="CA_partner_filter" class="CA_partner_filter" style="width:85%;" required');
								?>
							</div>
							<div style="float: left;padding-top:23px;padding-left:10px;">
								<button type="submit" id ="filter2" class="btn btn_purple" >Search</button>
							</div>
							<div style="float: right;padding-top:23px;padding-left:10px;">
								<button type="submit" id ="generate_CA_Excel" class="btn btn_purple" >Generate Excel</button>
							</div>
						</div>

						<div class="col-sm-12 col-md-12">
							<table class="table table-bordered table-striped mb-none datatable-completed" id="datatable-completed" style="width:100%">
							<thead>
								<tr style="background-color:white;">
									<th class="text-left">No.</th>
									<th class="text-left">Clients</th>
									<th class="text-left">Firm</th>
									<th class="text-left">Job Type</th>
									<th class="text-left">PIC</th>
									<th class="text-left">FYE</th>
									<th class="text-left">Account received</th>
									<th class="text-left">Due date</th>
									<th class="text-left">Status</th>
									<th class="text-left">Remark</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($completed_list as $completed){

										  echo '<tr>';
										  echo '<td>'.$completed->assignment_id.'</td>';
										  echo '<td><a href="javascript:void(0)" class="assignment" data-id="'.$completed->id.'" id="edit_completed_assignment">'.$completed->client_name.'</a></td>';
										  echo '<td>'.$completed->name.'</td>';

										  if($completed->job!=null){
										  	echo '<td>'.$completed->job.'</td>';
										  }else{
										  	echo '<td>  </td>';
										  }

										  echo '<td style="width:15%;">';
										  echo '<table class="table table-bordered table-striped mb-none">';

										  echo '<tr>';
										  echo '<th>Partner</th>';
										  echo '<td>';
										  echo strtoupper(json_decode($completed->PIC)->partner);
										  echo '</td>';
										  echo '</tr>';

										  echo '<tr>';
										  echo '<th>Manager</th>';
										  echo '<td>';
										  echo strtoupper(json_decode($completed->PIC)->manager);
										  echo '</td>';
										  echo '</tr>';

										  echo '<tr>';
										  echo '<th>Leader</th>';
										  echo '<td>';
										  echo strtoupper(json_decode($completed->PIC)->leader);
										  echo '</td>';
										  echo '</tr>';

										foreach (json_decode($completed->PIC)->assistant as $key => $assistant) {
										  echo '<tr>';
										  echo '<th>Assistant</th>';
										  echo '<td>';
										  echo strtoupper($assistant);
										  echo '</td>';
										  echo '</tr>';
										}

										  echo '</table>';
										  echo '</td>';
										  if($completed->FYE!=null){
										  	echo '<td>'.date('d F Y', strtotime($completed->FYE)).'</td>';
										  }else{
										  	echo '<td>  </td>';
										  }
										  if($completed->account_received!=null){
										  	echo '<td>'.date('d F Y', strtotime($completed->account_received)).'</td>';
										  }else{
										  	echo '<td>  </td>';
										  }
										  if($completed->due_date!=null){
										  	echo '<td>'.date('d F Y', strtotime($completed->due_date)).'</td>';
										  }else{
										  	echo '<td>  </td>';
										  }
										  echo '<td>'.$completed->assignment_status.'</td>';
										  // echo '<td>'.$completed->remark.'</td>';
										  if($completed->remark == ""){
											  	echo '<td align="center">
											  			<a href="javascript:void(0)" style="font-weight:bold;" data-toggle="tooltip" data-placement="top" data-original-title="No Remark"><i class="fas fa-info-circle" style="font-size:16px;"></i></a>
											  		</td>';
											}else{
											  	echo '<td align="center">
											  			<a href="javascript:void(0)" style="font-weight:bold;" data-toggle="tooltip" data-placement="top" data-original-title="'.$completed->remark.'"><i class="fas fa-info-circle" style="font-size:16px;"></i></a>
											  		</td>';
											}
										  echo '</tr>';
									}
								?>
							</tbody>
							</table>
						</div>
					</div>

					<!-- Signed Tab -->
					<div id="w2-signed" class="tab-pane">
						<div style="float: left;padding-bottom:20px;">
							<div style="float: left;padding-top:1px;">
								<label class="control-label">Date range</label>
								<div class="input-daterange input-group block_leave_date" data-plugin-datepicker data-date-format="dd/mm/yyyy">
									<span class="input-group-addon">
										<i class="far fa-calendar-alt"></i>
									</span>
									<input type="text" class="form-control SA_date_from" name="from" value="" placeholder="From">
									<span class="input-group-addon">to</span>
									<input type="text" class="form-control SA_date_to" name="to" value="" placeholder="To">
								</div>
							</div>
							<div style="width: 200px; float: left;padding-left:10px;">
								<label class="control-label">Partner</label>
								<?php
									echo form_dropdown('SA_partner_filter', $partner_list, isset($partner_list[0]->partner_name)?$partner_list[0]->partner_name:'', ' id="SA_partner_filter" class="SA_partner_filter" style="width:85%;" required');
								?>
							</div>
							<div style="float: left;padding-top:23px;padding-left:10px;">
								<button type="submit" id ="filter3" class="btn btn_purple" >Search</button>
							</div>
							<div style="float: right;padding-top:23px;padding-left:10px;">
								<button type="submit" id ="generate_SA_Excel" class="btn btn_purple" >Generate Excel</button>
							</div>
						</div>

						<div class="col-sm-12 col-md-12">
							<table class="table table-bordered table-striped mb-none datatable-signed" id="datatable-signed" style="width:100%">
							<thead>
								<tr style="background-color:white;">
									<th class="text-left">No.</th>
									<th class="text-left">Clients</th>
									<th class="text-left">Firm</th>
									<th class="text-left">Job Type</th>
									<th class="text-left">PIC</th>
									<th class="text-left">FYE</th>
									<th class="text-left">Account received</th>
									<th class="text-left">Due date</th>
									<th class="text-left">Status</th>
									<th class="text-left">Remark</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($signed_list as $signed){

										  echo '<tr>';
										  echo '<td>'.$signed->assignment_id.'</td>';
										  echo '<td><a href="javascript:void(0)" class="assignment" data-id="'.$signed->id.'" id="edit_signed_assignment">'.$signed->client_name.'</a></td>';
										  echo '<td>'.$signed->name.'</td>';

										  if($signed->job!=null){
										  	echo '<td>'.$signed->job.'</td>';
										  }else{
										  	echo '<td>  </td>';
										  }

										  echo '<td style="width:15%;">';
										  echo '<table class="table table-bordered table-striped mb-none">';

										  echo '<tr>';
										  echo '<th>Partner</th>';
										  echo '<td>';
										  echo strtoupper(json_decode($signed->PIC)->partner);
										  echo '</td>';
										  echo '</tr>';

										  echo '<tr>';
										  echo '<th>Manager</th>';
										  echo '<td>';
										  echo strtoupper(json_decode($signed->PIC)->manager);
										  echo '</td>';
										  echo '</tr>';

										  echo '<tr>';
										  echo '<th>Leader</th>';
										  echo '<td>';
										  echo strtoupper(json_decode($signed->PIC)->leader);
										  echo '</td>';
										  echo '</tr>';

										foreach (json_decode($signed->PIC)->assistant as $key => $assistant) {
										  echo '<tr>';
										  echo '<th>Assistant</th>';
										  echo '<td>';
										  echo strtoupper($assistant);
										  echo '</td>';
										  echo '</tr>';
										}

										  echo '</table>';
										  echo '</td>';
										  if($signed->FYE!=null){
										  	echo '<td>'.date('d F Y', strtotime($signed->FYE)).'</td>';
										  }else{
										  	echo '<td>  </td>';
										  }
										  if($signed->account_received!=null){
										  	echo '<td>'.date('d F Y', strtotime($signed->account_received)).'</td>';
										  }else{
										  	echo '<td>  </td>';
										  }
										  if($signed->due_date!=null){
										  	echo '<td>'.date('d F Y', strtotime($signed->due_date)).'</td>';
										  }else{
										  	echo '<td>  </td>';
										  }
										  echo '<td>'.$signed->assignment_status.'</td>';
										  // echo '<td>'.$signed->remark.'</td>';
										  if($signed->remark == ""){
											  	echo '<td align="center">
											  			<a href="javascript:void(0)" style="font-weight:bold;" data-toggle="tooltip" data-placement="top" data-original-title="No Remark"><i class="fas fa-info-circle" style="font-size:16px;"></i></a>
											  		</td>';
											}else{
											  	echo '<td align="center">
											  			<a href="javascript:void(0)" style="font-weight:bold;" data-toggle="tooltip" data-placement="top" data-original-title="'.$signed->remark.'"><i class="fas fa-info-circle" style="font-size:16px;"></i></a>
											  		</td>';
											}
										  echo '</tr>';
									}
								?>
							</tbody>
							</table>
						</div>
					</div>

					<!-- Calendar Tab -->
					<div id="w2-calendar" class="tab-pane">
						<div style="padding-left: 92%;">
							<a href="javascript:void(0)" style="font-weight:bold;" data-toggle="tooltip" data-placement="left" data-original-title="<div style='text-align:left;'>
						  <li style='color:#FA8072; font-size: 13px; font-weight:bold;'>NO ACCOUNT</li>
						  <li style='color:#4caf50; font-size: 13px; font-weight:bold;'>WIP</li>
						  <li style='color:#f44336; font-size: 13px; font-weight:bold;'>KIV</li>
						  <li style='color:#ffa726; font-size: 13px; font-weight:bold;'>REVIEWING & PARTNER REVIEW</li>
						  <li style='color:#9c27b0; font-size: 13px; font-weight:bold;'>ADOPTION & SIGNED</li>
						  <li style='color:#90a4ae; font-size: 13px; font-weight:bold;'>PENDING DOCS & PENDING PAYMENT</li>
						  <li style='color:#3f51b5; font-size: 13px; font-weight:bold;'>COMPLETED</li></div>"> 
						  Status Color <i class="far fa-question-circle" style="font-size:16px;"></i></a>
						</div>
						<div>
							<div style="font-size: 3rem;padding: 0; margin: 7px 0 14px 0;">Calendar</div>
							<div id="calender_container"></div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>
</section>

<!-- Log Pop up -->
<div id="log" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Assignment Log</h2>
			</header>
			<form id="log">
				<div class="panel-body">
					<div class="col-md-12">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<input type="hidden" name="assignment_log_id" class="assignment_log_id" value="">
							<thead>
								<tr style="background-color:white;">
									<th class="text-left" style="width: 10%">ID</th>
									<th class="text-left" style="width: 15%">Date</th>
									<th class="text-left">Log</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id ="generate_log_Excel" class="btn btn_purple" >Generate Excel</button>
					<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- New Assignment Pop up -->
<div id="new_assignment" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
		<div class="modal-dialog" style="width: 1000px !important;">
			<div class="modal-content">
				<header class="panel-heading">
					<h2 class="panel-title">Assignment</h2>
				</header>
				<form id="addAssignment">
					<div class="panel-body">
						<div class="col-md-12">
							<table class="table table-bordered table-striped table-condensed mb-none">
								<input type="hidden" name="assignment_id" class="assignment_id" value="">
								<input type="hidden" name="assignment_code" class="assignment_code" value="">

								<tr>
									<th>Client</th>
									<td>
								        <div id="new_client" class="tab-pane" style="width: 80%;">
											<?php
	    										echo form_dropdown('client_id', $client_list, isset($client_list[0]->client_id)?$client_list[0]->client_id:'', 'class="assignment_client" style="width:100%;" required');
	    									?>
								        </div>
								        <div id="edit_client" class="tab-pane" style="width: 80%;">
											<input disabled type="text" class="form-control client_name assignment" name="client_name" value="<?=isset($client_list[0]->client_name)?$client_list[0]->client_name:'' ?>" style="width: 100%;"/>
								        </div>
									</td>
								</tr>

		                       	<tr>
									<th>Firm</th>
									<td>
								        <div class="input_firm" style="width: 80%;">
											<?php
	    										echo form_dropdown('firm_id', $firm_list, isset($firm_list[0]->firm_id)?$firm_list[0]->firm_id:'', 'class="assignment_firm" id="firm" style="width:100%;" required');
	    									?>
								        </div>
									</td>
								</tr>

								<tr>
									<th>Final Year End</th>
									<td>
								        <div class="input_FYE" style="width: 50%;">
	    			                        <div class="input-group date datepicker" data-provide="datepicker">
	    			                           	<span class="input-group-addon">
													<i class="far fa-calendar-alt"></i>
												</span>
							                    <input type='text' class="form-control assignment_fye" name="assignment_fye" id='assignment_fye' />
	    									</div>
								        </div>
									</td>
								</tr>

								<tr>
									<th>Account Received</th>
									<td>
								        <div class="input_account_received" style="width: 50%;">
	    			                        <div class="input-group date datepicker" data-provide="datepicker">
	    			                           	<span class="input-group-addon">
													<i class="far fa-calendar-alt"></i>
												</span>
							                    <input type='text' class="form-control assignment_account_received" name="assignment_account_received" id='assignment_account_received' />
	    									</div>
								        </div>
									</td>
								</tr>

								<tr>
									<th>Due Date</th>
									<td>
								        <div class="input_due_date" style="width: 50%;">
	    			                        <div class="input-group date datepicker" data-provide="datepicker">
	    			                           	<span class="input-group-addon">
													<i class="far fa-calendar-alt"></i>
												</span>
							                    <input type='text' class="form-control assignment_due_date" name="assignment_due_date" id='assignment_due_date' />
	    									</div>
								        </div>
									</td>
								</tr>

								<tr>
									<th>Type Of Job</th>
									<td>
								        <div class="input_firm" style="width: 50%;">
											<?php
	    										echo form_dropdown('jobs_list', $jobs_list, isset($jobs_list[0]->id)?$jobs_list[0]->id:'', 'class="type_of_job" id="type_of_job" style="width:100%;" required');
	    									?>
								        </div>
									</td>
								</tr>

								<tr>
									<th>Person In Charge</th>
									<td>
								        <div class="input_PIC" style="width: 100%;">
											<table class="table table-bordered table-striped table-condensed mb-none">
												<tr>
													<th>Partner</th>
													<td >
														<?php
	    													echo form_dropdown('A_partner', $partner_list2, isset($partner_list2[0]->partner_name)?$partner_list2[0]->partner_name:'', 'id="A_partner" class="A_partner" style="width:85%;" required');
	    												?>
													</td>
												</tr>
												<tr>
													<th>Manager</th>
													<td>
														<?php
	    													echo form_dropdown('manager', $manager_list, isset($manager_list[0]->first_name)?$manager_list[0]->first_name:'', 'id="manager" class="manager" style="width:85%;" required');
	    												?>
													</td>
												</tr>
												<tr>
													<th>Leader</th>
													<td>
														<?php
	    													echo form_dropdown('leader', $users_list, isset($users_list[0]->first_name)?$users_list[0]->first_name:'', 'id="leader" class="leader" style="width:85%;" required');
	    												?>
													</td>
												</tr>
												<tr>
													<th>Assistant</th>
													<td>
														<table style="width: 100%;">
															<tr>
																<div id="sameRow">
																	<?php
			    														echo form_dropdown('assistant[]', $users_list, isset($users_list[0]->first_name)?$users_list[0]->first_name:'', 'id="assistant" class="assistant first" style="width:85%;" required');
			    													?>
			    													<?php if($Admin || $Manager) { 
			    													echo '<a href="javascript: void(0);" calss="before" rowspan=2; style="color: #D9A200; outline: none !important;text-decoration: none; padding-left:4px; padding-top:8px;">
			    														<span id="assistant_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Add Assistant" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add </span></a>';
			    													 } ?>
			    												</div>
															</tr>
															<tr id="assistant_list">
															</tr>
	    												</table>
													</td>
												</tr>
											</table>
								        </div>
									</td>
								</tr>

								<tr>
									<th>Remark</th>
									<td>
								        <div class="input_remark" style="width: 100%;">
											<textarea class="form-control assignment_remark" id="assignment_remark" name="assignment_remark" value="<?=isset($staff[0]->remark)? $staff[0]->remark:'' ?>" style="width: 100%;"></textarea>
								        </div>
									</td>
								</tr>

								<tr id="tr_input_EC_date" class="hidden">
									<th>Expected Completion Date</th>
									<td>
										<div class="input_EC_date" style="width:50%;">
									        <div class="input-group date datepicker" data-provide="datepicker">
					                           	<span class="input-group-addon">
													<i class="far fa-calendar-alt"></i>
												</span>
							                    <input type='text' class="form-control Expected_Completion" name="Expected_Completion" id='Expected_Completion' required disabled />
											</div>
										</div>
									</td>
								</tr> 

							</table>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn_purple">Submit</button>
						<input type="button" class="btn btn-default cancel" data-dismiss="modal" name="" value="Cancel">
					</div>
				</form>
			</div>
		</div>
	</div>

<!-- Completed Assignment Pop up -->
	<div id="completed_assignment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
		<div class="modal-dialog" style="width: 1000px !important;">
			<div class="modal-content">
				<header class="panel-heading">
					<h2 class="panel-title Completed" id="Completed"> Completed Assignment </h2>
					<h2 class="panel-title Signed" id="Signed"> Signed Assignment </h2>
				</header>
				<form id="completed_assignment_form">
					<div class="panel-body">
						<div class="col-md-12">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<input type="hidden"  id="id" name="id" class="id" value="">
							<input type="hidden"  id="payroll_assignment_id" name="payroll_assignment_id" class="complete_assignment_id" value="">
							<input type="hidden"  id="status_id" name="status_id" class="status_id" value="">

							<tr>
								<th>Firm</th>
								<td>
							        <div class="completed_firm" style="width: 90%;">
							        	<input type="hidden" id="firm_id" name="firm_id" class="completed_assignment_firm_id" value="">
										<input type="text" class="form-control completed_assignment_firm" value="<?=isset($assignment_list[0]->name)?$assignment_list[0]->name:''?>" disabled/>
							        </div>
								</td>
							</tr>

							<tr>
								<th>Client</th>
								<td>
							        <div class="completed_client" style="width: 90%;">
							        	<input type="hidden" id="client_id" name="client_id" class="completed_assignment_client_id" value="">
							        	<input type="hidden" id="client_name" name="client_name" class="completed_assignment_client_name" value="">
										<input type="text" class="form-control completed_assignment_client assignment" value="<?=isset($assignment_list[0]->client_name)?$assignment_list[0]->client_name:''?>" disabled/>
							        </div>
								</td>
							</tr>

							<tr>
								<th>Final Year End</th>
								<td>
							        <div class="completed_fye" style="width: 60%;">
							        	<input type="hidden" id="FYE" name="FYE" class="completed_assignment_fye" value="">
										<input type="text" name="completed_assignment_fye" class="form-control completed_assignment_fye" value="<?=isset($assignment_list[0]->FYE)?$assignment_list[0]->FYE:''?>" disabled/>
							        </div>
								</td>
							</tr>

							<tr>
								<th>Report Date</th>
								<td>
							        <div class="completed_report_date" style="width: 50%;">
    			                        <div class="input-group date datepicker" data-provide="datepicker">
    			                           	<span class="input-group-addon">
												<i class="far fa-calendar-alt"></i>
											</span>
						                    <input type='text' id="report_date" name="report_date" class="form-control completed_assignment_report_date" required/>
    									</div>
							        </div>
								</td>
							</tr>
							
							<tr>
								<th>Partner</th>
								<td>
									<input type="hidden" id="CA_partner_name" name="CA_partner_name" class="CA_partner_name" value="">
							        <div class="completed_partner" style="width: 60%;">
										<!-- <input type="text" id="partner" name="partner" class="form-control completed_assignment_partner" required/> -->
										<?php
    										echo form_dropdown('CA_partner', $partner_list, isset($partner_list[0]->partner_name)?$partner_list[0]->partner_name:'', ' id="CA_partner" class="CA_partner" style="width:85%;" disabled');
    									?>
							        </div>
								</td>
							</tr>

							<tr>
								<th>Revenue</th>
								<td>
							        <div class="completed_revenue" style="width: 60%;">
										<input type="text" id="revenue" name="revenue" class="form-control completed_assignment_revenue" style="text-align:right;" onkeypress="validate(event)" required/>
							        </div>
								</td>
							</tr>

							<tr>
								<th>Asset</th>
								<td>
							        <div class="completed_asset" style="width: 60%;">
										<input type="text" id="asset" name="asset" class="form-control completed_assignment_asset" style="text-align:right;" onkeypress="validate(event)" required/>
							        </div>
								</td>
							</tr>

							<tr>
								<th>PBT/LBT</th>
								<td>
							        <div class="completed_pbt_lbt" style="width: 60%;">
										<input type="text" id="PBT_LBT" name="PBT_LBT" class="form-control completed_assignment_pbt_lbt" style="text-align:right;" onkeypress="validatePBT(event)" required/>
							        </div>
								</td>
							</tr>

							<tr>
								<th>Functional Currency</th>
								<td>
							        <div class="completed_functional_currency" style="width: 60%;">
										<input type="text" id="functional_currency" name="functional_currency" class="form-control completed_assignment_functional_currency" required/>
							        </div>
								</td>
							</tr>

							<tr>
								<th>Does it have subsidiary</th>
								<td>
							        <div class="completed_subsidiary" style="width: 30%;">
										<?php 
			                                 echo form_dropdown('subsidiary', $yes_no_list, isset($choose_carry_forward_id_data['choose_carry_forward_id'])?$choose_carry_forward_id_data['choose_carry_forward_id']:'', 'id="subsidiary" class="form-control completed_assignment_subsidiary" required');
			                            ?>
							        </div>
								</td>
							</tr>

							<tr>
								<th>Does it have holding company</th>
								<td>
							        <div class="completed_holding_company" style="width: 30%;">
										<?php 
			                                 echo form_dropdown('holding_company', $yes_no_list, isset($choose_carry_forward_id_data['choose_carry_forward_id'])?$choose_carry_forward_id_data['choose_carry_forward_id']:'', 'id="holding_company" class="form-control completed_assignment_holding_company" required');
			                            ?>
							        </div>
								</td>
							</tr>

							<tr>
								<th>Normal Audit</th>
								<td>
							        <div class="completed_normal_audit" style="width: 30%;">
										<?php 
			                                 echo form_dropdown('normal_audit', $yes_no_list, isset($choose_carry_forward_id_data['choose_carry_forward_id'])?$choose_carry_forward_id_data['choose_carry_forward_id']:'', 'id="normal_audit" class="form-control completed_assignment_normal_audit" required');
			                            ?>
							        </div>
								</td>
							</tr>

							<tr>
								<th>Principal Activity</th>
								<td>
							        <div class="completed_principal_activity" style="width: 100%;">
										<input type="text" id="principal_activity" name="principal_activity" class="form-control completed_assignment_principal_activity" required/>
							        </div>
								</td>
							</tr>

							<tr>
								<th>Audit Fee</th>
								<td>
							        <div class="input-group" style="width: 60%;">
							        	<span class="input-group-addon">SGD</span>
											<input type="text" id="audit_fee" name="audit_fee" class="form-control completed_assignment_audit_fee" style="text-align:right;" onkeypress="validate(event)" required/>
									</div>
								</td>
							</tr>

						</table>
					</div>
					</div>
						<div class="modal-footer">
							<div id="w2-footer_in_progress" class="tab-pane">
								<input type="button" id ="cancel" class="btn btn-default cancel_assignment" data-dismiss="modal" name="cancel_assignment" value="Cancel">
								<button type="submit" id ="save" class="btn btn_purple">Save</button>
							</div>
							<div id="w2-footer_completed" class="tab-pane">								
								<input type="button" id ="Close" class="btn btn-default " data-dismiss="modal" name="" value="Close">
							</div>
						</div>
				</form>
			</div>
		</div>
	</div>

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script>

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	var assignment_list_data = <?php echo json_encode(isset($assignment_list)?$assignment_list:"") ?>;
	var completed_assignment_list_data = <?php echo json_encode(isset($completed_assignment_list)?$completed_assignment_list:"") ?>;

	//Reload
	$(".cancel_assignment").click(function(){
    	location.reload();
	});

	//Show Popout (Add Assignment)
	$(".add_new_assignment").click(function(){
    	add_new_assignment();
	});

	$(".cancel").click(function(){
		location.reload();
	});

	$(document).on('click',".check_state",function(){
		var index_tab_aktif = $(this).data("information");
		if(index_tab_aktif == "in_progress")
		{
			$(".add_new_assignment").show();
			location.reload();
		}else{

			$(".add_new_assignment").hide();
			if(index_tab_aktif == "calendar")
			{
				$('#calender_container').fullCalendar( 'rerenderEvents' );
			}
		}
		
	});
	var calender_list = <?php echo json_encode(isset($calender_list)?$calender_list:"") ?>;
	if(calender_list.length > 0)
	{	
		var calender_event = [];
		
		for(var i = 0; i < calender_list.length; i++)
		{
			if(calender_list[i]["status"]==1){
				var color = '#FA8072'; // PINK
			}
			else if(calender_list[i]["status"]==2){
				var color = '#4caf50'; // GREEN
			}
			else if(calender_list[i]["status"]==3){
				var color = '#f44336'; // RED
			}
			else if(calender_list[i]["status"]==4 || calender_list[i]["status"]==5){
				var color = '#ffa726'; // ORANGE
			}
			else if(calender_list[i]["status"]==6 || calender_list[i]["status"]==11){
				var color = '#9c27b0'; // PURPLE
			}
			else if(calender_list[i]["status"]==7 ||calender_list[i]["status"]==8 || calender_list[i]["status"]==9){
				var color = '#90a4ae'; // GREY
			}
			else if(calender_list[i]["status"]==10){
				var color = '#3f51b5'; // BLUE
			}

			var today = new Date();
			var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

			if(calender_list[i]["expected_completion_date"]!=null){

				var expected_completion_date = new Date(calender_list[i]["expected_completion_date"])
				var today_date = new Date(date)

				if(expected_completion_date>=today_date){
					calender_event.push({
						title: JSON.parse(calender_list[i]["PIC"])["leader"]+'-'+calender_list[i]['client_name'], 
						start: moment(calender_list[i]["create_on"], 'YYYY-MM-DD'), 
						end: moment(calender_list[i]["expected_completion_date"], 'YYYY-MM-DD').add(1, 'days'),
						color: color+'!important' 
					});
				}
			}
		}
	}

	(function( $ ) {

		'use strict';
		var initCalendar = function() {
		var $calendar = $('#calender_container');
		$calendar.fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay,listWeek'
			},
			themeButtonIcons: {
				prev: 'fa fa-caret-left',
				next: 'fa fa-caret-right',
			},

			editable: false,
			droppable: false,
			displayEventTime: false,
			navLinks: true, // can click day/week names to navigate views
			eventLimit: true, // allow "more" link when too many events
			events: calender_event
		});

		// FIX INPUTS TO BOOTSTRAP VERSIONS
		var $calendarButtons = $calendar.find('.fc-toolbar > .fc-right > button');
		//console.log($calendarButtons);
		// $calendarButtons
		// 	.filter('.fc-button-prev, .fc-button-today, .fc-button-next')
		// 		.wrapAll('<div class="btn-group mt-sm mr-md mb-sm ml-sm"></div>')
		// 		.parent()
		// 		.after('<br class="hidden"/>');

		$calendarButtons
			.not('.fc-button-prev, .fc-button-today, .fc-button-next')
				.wrapAll('<div class="btn-group mb-sm mt-sm"></div>');

		$calendarButtons
			.attr({ 'class': 'btn btn-sm btn-default' });
		};

		$(function() {
			initCalendar();
		});

	}).apply(this, [ jQuery ]);	

	window.onbeforeunload = function() {
		localStorage.setItem("partner", $('#A_partner_filter').val());
	}

	window.onload = function() {
	    $partner = localStorage.getItem("partner");

	    if( $partner == null ){

			$('#A_partner_filter').val(0).trigger('change');
			$("#filter1").trigger('click');
	    }
	    else if($partner != null || $partner != "")
	    {
	    	$('#A_partner_filter').val($partner).trigger('change');
			$("#filter1").trigger('click');
	    }
	}

    function add_new_assignment(){
    	$("#new_client").css("display","inline-block !important");
	    $("#edit_client").css("display","none");

	    $("#tr_input_EC_date").removeClass("hidden");
	    document.getElementById('Expected_Completion').removeAttribute('disabled');

    	$("#new_assignment").modal("show"); 
    	$(".assignment_id").val('');
    	$(".assignment_client").select2("val", '');
    	$(".client_name").val('');
    	$(".assignment_firm").select2("val", '');
	}

	// select2 initialize 
	$(".assignment_client").select2();
	$(".assignment_firm").select2();
	$(".type_of_job").select2();
	$(".status-select").select2();
	$(".A_partner_filter").select2();
	$(".CA_partner_filter").select2()
	$(".SA_partner_filter").select2()
	$(".A_partner").select2();
	$(".CA_partner").select2();
	$(".manager").select2();
	$(".leader").select2();
	$(".assistant").select2();

	$('.datepicker').datepicker({
	    format: 'dd MM yyyy',
	});

	var userTarget = "";
	var exit = false;
	$('.input-daterange').datepicker({
	  format: "dd MM yyyy",
	  weekStart: 1,
	  language: "en",
	  startDate: "01/01/1957",
	  orientation: "bottom auto",
	  autoclose: true,
	  showOnFocus: true,
	  keepEmptyValues: true,
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

	// // datatable initialize
	// $(document).ready(function () {
 //    	// console.log("uuuuuuuuuuuuuuu");
 //    	function getData(handleData){
 //    		$.ajax({
	// 	           type: "POST",
	// 	           url: "assignment/A_filter",
	// 	           data: $('form#share_transfer_form').serialize()+ '&partner=' + $(".A_partner_filter").val(),
	// 	        success: function(data)
	// 	        {
	// 	        	handleData(data);
	// 	        }
	// 	    });
 //    	}
 //    	getData(function(output){
	// 	  var data = JSON.parse(output);
	// 	  table1 = $('#datatable-inprogress');
	// 	  //console.log(table1);
	// 		$('#datatable-inprogress').DataTable({
	// 	    	"order": [],
	// 	    	"bStateSave": true,
	// 	        pageLength: 10,
	// 	        drawCallback: function (){
	// 		      	// $('#datatable-inprogress').on('preDraw', function(){
	// 		            for(var i = 0; i < data.length; i++){
	// 						// $("#status-select"+i).val(data[i]["status"]);
	// 						// $("#status-select"+i).select2();
	// 						$( ".status-select", $('#datatable-inprogress').rows().nodes() ).select2();
	// 		          	}
	// 			        $('[data-toggle="tooltip"]').tooltip({html:true});

	// 			        $('.datepicker').datepicker({
	// 					    format: 'dd MM yyyy',
	// 					});
	// 		        // });

	// 		        $("select[name='datatable-inprogress_length']").select2();
	// 		        $('div#datatable-inprogress_filter.dataTables_filter input').addClass('form-control');
	// 		        $('div#datatable-inprogress_filter.dataTables_filter input').attr("placeholder", "Search");
	// 		        console.log("uuuuuuuuuuuuuuu");
	// 		  	}
	// 	    //     initComplete: function (){
	// 		   //    	// $('#datatable-inprogress').on('draw.dt', function(){
	// 		   //          for(var i = 0; i < data.length; i++){
	// 					// 	// $("#status-select"+i).val(data[i]["status"]);
	// 					// 	$("#status-select"+i).select2();
	// 		   //        	}
	// 			  //       $('[data-toggle="tooltip"]').tooltip({html:true});

	// 			  //       $('.datepicker').datepicker({
	// 					//     format: 'dd MM yyyy',
	// 					// });
	// 		   //      // });

	// 		   //      $("select[name='datatable-inprogress_length']").select2();
	// 		   //      $('div#datatable-inprogress_filter.dataTables_filter input').addClass('form-control');
	// 		   //      $('div#datatable-inprogress_filter.dataTables_filter input').attr("placeholder", "Search");
	// 		  	// }
	// 	    });
	// 	});
	// });

	// datatable initialize
	$(document).ready(function () {
	    $('#datatable-completed').DataTable( {
	    	"order": [],
	    	initComplete: function (){
		      	$('#datatable-completed').on('draw.dt', function(){
			        $('[data-toggle="tooltip"]').tooltip({html:true});
		        });

		        $("select[name='datatable-completed_length']").select2();
		        $('div#datatable-completed_filter.dataTables_filter input').addClass('form-control');
		        $('div#datatable-completed_filter.dataTables_filter input').attr("placeholder", "Search");
		  	}
	    });
	});

	// datatable initialize
	$(document).ready(function () {
	    $('#datatable-signed').DataTable( {
	    	"order": [],
	    	 initComplete: function (){
		      	$('#datatable-signed').on('draw.dt', function(){
			        $('[data-toggle="tooltip"]').tooltip({html:true});
		        });

		        $("select[name='datatable-signed_length']").select2();
		        $('div#datatable-signed_filter.dataTables_filter input').addClass('form-control');
		        $('div#datatable-signed_filter.dataTables_filter input').attr("placeholder", "Search");
		  	}
	    } );
	});

	// CHECK MISSED ASSIDGNMENT
	$(document).ready(function (){
		<?php if(!($Admin || $Manager)) { ?>
			var today = new Date();
			var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
			var object;

		    $.post("assignment/check_assignment_deadline", { 'date': date }, function(data, status){
		    	if(data){
		    		if(data!='[]'){
		    			object = (JSON.parse(data));
		    			var id = new Array();
		    			var client_name = new Array();

		    			for(var i = 0; i < object.length; i++)
		    			{
		    				$.ajax({
					           type: "POST",
					           url: "assignment/check_missed_reason",
					           data: '&id=' + object[i]["assignment_id"],
					           success: function(data){
					           		var j;
					           		if(data==='[]'){
					           			var object2 = object;
					           			for(var j = 0; j < object2.length; j++)
						    			{
					           				bootbox.prompt({
								                closeButton: false,
										        title: "<p style='text-align:center'>Why Is The Expected Completion Date Missed?</p><p style='text-align:center' class='assignment_id'>("+object2[j]["assignment_id"]+")  "+object2[j]["client_name"]+"</p>",
										        message: "<p>Reason?</p>",
										        inputType:'textarea',
										        buttons: {
										            confirm: {
										                label: 'Submit',
										                className: 'btn_purple'
										            },
										            cancel: {
										                label: 'No',
										                className: 'hidden'
										            }
										        },
										        callback: function (result){
										        	if(result == '' || result == null){
										        		toastr.error('Please Provide Your REASON', 'Error');
										        		return false;
													}else{
														// SAVE DATA
														var str = this.find(".assignment_id").text();
														var id = str.substring(1, 12);

														$.post("assignment/missed_ExpectedCompletionDate", {'assignment_id': id, 'reason': result}, function(data, status){
															if(data){
											    	 			toastr.success('Information Updated', 'Updated');
											    	 		}
														});
						    						}

										        }
										    })
					           			}

					           		}
					        	}
					       	});
					       	break;
			    		}
	           		}
		    	}
		    });
	    <?php } ?>
	});

	//Email Notification [if assignment completion date less than 3 days]
	$(document).ready(function () {
		var today = new Date();
		var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+(today.getDate()+3);
		var object;

	    $.post("assignment/check_assignment_remain_day", { 'date': date }, function(data, status){
	    	if(data){
	    		if(data!='[]'){
	    			object = (JSON.parse(data));
	    			var id = new Array();
	    			var client_name = new Array();

	    			for(var i = 0; i < object.length; i++)
	    			{
	    				$.ajax({
				           type: "POST",
				           url: "assignment/check_assignment_remain_day_email",
				           data: '&id=' + object[i]["assignment_id"],
				           success: function(data){
				           		var j;
				           		if(data==='[]'){
				           			var object2 = object;
				           			for(var j = 0; j < object2.length; j++)
					    			{
					    				var id = object2[j]["assignment_id"];
					    				var pic = object2[j]["PIC"];
					    				var client_name = object2[j]["client_name"];

					    				$.post("assignment/email_notification_email", {'assignment_id': id, 'client_name': client_name, 'pic': pic}, function(data, status){
											if(data){
							    	 			// toastr.success('Information Updated', 'Updated');
							    	 		}
										});

										$.post("assignment/email_notification_log", {'assignment_id': id}, function(data, status){
											if(data){
							    	 			// toastr.success('Information Updated', 'Updated');
							    	 		}
										});
				           			}
				           		}
				        	}
				       	});
				       	break;
		    		}
           		}
	    	}
	    });
	});

	//Submit Assignment
	$("#addAssignment").submit(function(e) {

		document.getElementById('firm').removeAttribute('disabled');
		document.getElementById('assignment_fye').removeAttribute('disabled');
		document.getElementById('assignment_account_received').removeAttribute('disabled');
		document.getElementById('assignment_due_date').removeAttribute('disabled');
		document.getElementById('A_partner').removeAttribute('disabled');
		document.getElementById('manager').removeAttribute('disabled');
		document.getElementById('leader').removeAttribute('disabled');
		document.getElementById('assistant').removeAttribute('disabled');
		document.getElementById('type_of_job').removeAttribute('disabled');

		var date = new Date();
		date.addDays(21); // ADD 21 Day
		var after3week = new Date(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate());
		var expected_date = new Date(moment($("#Expected_Completion").val()).format('DD MMMM YYYY'));

        var form = $(this);

       	if(expected_date > after3week)
       	{
       		bootbox.prompt({
				closeButton: false,
				title: "Reason For Long Completion Date?",
				message: "<p>Reason?</p>",
				inputType:'textarea',
				buttons: {
					confirm: {
						label: 'Submit',
						className: 'btn_purple'
					},
					cancel: {
						label: 'No',
						className: 'hidden'
					}
				},
				callback: function (result) {
					if(result == '' || result == null)
					{
						toastr.error('Please Provide Your REASON', 'Error');
						return false;
					}
					else
					{
						$.ajax({
				           type: "POST",
				           url: "assignment/get_new_assignment_code",
				           data: form.serialize(),
				           success: function(data)
				           {	
				           		if(data){
				           			var new_assignment_code = data;
				           		}
				           		
				           		// SAVE REASON TO LOG TABLE
								$.post("assignment/set_ExpectedCompletionDate", { 'assignment_id': new_assignment_code, 'expected_completion_date': $("#Expected_Completion").val(), 'reason': result}, function(data, status){
									if(data){
										// toastr.success('Information Updated', 'Updated');
									}
								});

								$.ajax({
						           type: "POST",
						           url: "assignment/submit_assignment",
						           data: form.serialize(),
						           success: function(data)
						           {	
						           		if(data){
						           			toastr.success('Information Updated', 'Updated');
						           			location.reload();
						           		}
						           }
						       	});
				            }
				       	});
					}
				}
			})
		}
		else
		{
			$.ajax({
	           type: "POST",
	           url: "assignment/submit_assignment",
	           data: form.serialize(),
	           success: function(data)
	           {	
	           		if(data){
	           			toastr.success('Information Updated', 'Updated');
	           			location.reload();
	           		}
	           }
	       	});
		}

    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });

	// Get Final Year End While Client Selected
    $(".assignment_client").change(function (e){
		var form = $(this);
		var assignment_id =  $(".assignment_id").val();

		if(assignment_id == ''){
			$('.assignment_fye').val('');

        	$.ajax({
           		type: "POST",
           		url: "assignment/get_final_year_end",
           		data: form.serialize(), // serializes the form's elements.
           		success: function(data)
           		{	
           			var date = (JSON.parse(data));

           			if(date!=0){
           				$('.assignment_fye').val(date);
           			}
           			else{
           				$('.assignment_fye').val('');
           			}
           		}
       		});
		}
		
    	e.preventDefault();
	});

	// Thousand Separator
    $("#revenue").change(function (e){
		var form = $(this);

		$("#revenue").val(numberWithCommas($("#revenue").val()));
    	e.preventDefault();
	});
	$("#asset").change(function (e){
		var form = $(this);

		$("#asset").val(numberWithCommas($("#asset").val()));
    	e.preventDefault();
	});
	$("#PBT_LBT").change(function (e){
		var form = $(this);

		$("#PBT_LBT").val(changePBT($("#PBT_LBT").val()));
    	e.preventDefault();
	});
	$("#audit_fee").change(function (e){
		var form = $(this);

		$("#audit_fee").val(numberWithCommas($("#audit_fee").val()));
    	e.preventDefault();
	});

	// Thousand Separator
	function numberWithCommas(x) {
   		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	function changePBT(x) {
		x = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

		if(x.search('-')!= -1){
			x = x.replace('-','');
			x = '('+x+')';
			return x;
		}else{
			return x;
		}
	 
	}

	// validation for PBT
	function validatePBT(evt) {
  		var theEvent = evt || window.event;

  		// Handle paste
  		if (theEvent.type === 'paste') {
      		key = event.clipboardData.getData('text/plain');
  		} else {
  			// Handle key press
      		var key = theEvent.keyCode || theEvent.which;
      		key = String.fromCharCode(key);
  		}
  		var regex = /[0-9]|\-/;
  		if( !regex.test(key) ) {
    		theEvent.returnValue = false;
    		if(theEvent.preventDefault) theEvent.preventDefault();
  		}
	}

	// validation for number only
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
  		var regex = /[0-9]/;
  		if( !regex.test(key) ) {
    		theEvent.returnValue = false;
    		if(theEvent.preventDefault) theEvent.preventDefault();
  		}
	}

	// CHANGE EXPECTED_COMPLETION_DATE
	function change_EC_date(element){
    	var div 				      = $(element).parent();
    	var assignment_id 		      = div.parent().find('.ECD_assignment_id').val();
    	var expected_completion_date  = div.find('.EC_date').val();

    	bootbox.confirm({
	        message: "Do you want to change this Expected Complete Date info?",
	        closeButton: false,
	        buttons: {
	            confirm: {
	                label: 'Yes',
	                className: 'btn_purple'
	            },
	            cancel: {
	                label: 'No',
	                className: 'btn_cancel'
	            }
	        },
	        callback: function (result) {
	        	if(result == true){

	        		$.post("assignment/check_expected_completion_date", { 'assignment_id': assignment_id , 'expected_completion_date': expected_completion_date}, function(data, status){

	        			if(data){
	        				var object = (JSON.parse(data));

	        				if(object[0]["expected_completion_date"]==null){

	        					var date = new Date();
								date.addDays(21); // ADD 21 Day
								var after3week = new Date(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate());
								var expected_date = new Date(expected_completion_date);

								if(expected_date > after3week){

									bootbox.prompt({
						        		closeButton: false,
								        title: "Reason For Long Completion Date?",
								        message: "<p>Reason?</p>",
								        inputType:'textarea',
								        buttons: {
								            confirm: {
								                label: 'Submit',
								                className: 'btn_purple'
								            },
								            cancel: {
								                label: 'No',
								                className: 'hidden'
								            }
								        },
								        callback: function (result) {
								        	if(result == '' || result == null){
								        		toastr.error('Please Provide Your REASON', 'Error');
								        		return false;
											}else{
												// SAVE Expected Completion Date
												$.post("assignment/submit_expected_completion_date", { 'assignment_id': assignment_id, 'expected_completion_date': expected_completion_date}, function(data, status){
													if(data){
									    	 			toastr.success('Information Updated', 'Updated');
									    	 		}
												});

												// SAVE REASON TO LOG TABLE
												$.post("assignment/set_ExpectedCompletionDate", { 'assignment_id': object[0]["assignment_id"]  , 'expected_completion_date': expected_completion_date, 'reason': result}, function(data, status){
													if(data){
									    	 			toastr.success('Information Updated', 'Updated');
									    	 		}
												});

												location.reload();
											}
								        }
								    })

								}else{
									
									// SAVE Expected Completion Date
									$.post("assignment/submit_expected_completion_date", { 'assignment_id': assignment_id , 'expected_completion_date': expected_completion_date}, function(data, status){
										if(data)
										{
						    	 			toastr.success('Information Updated', 'Updated');
										    location.reload();
						    	 		}
									});

									$.post("assignment/add_ExpectedCompletionDate", { 'assignment_id':  object[0]["assignment_id"], 'expected_completion_date': expected_completion_date}, function(data, status){
										if(data){
						    	 			toastr.success('Information Updated', 'Updated');
						    	 		}
									});

									location.reload();
								}
	        				}
	        				else if(object[0]["expected_completion_date"]!=null)
	        				{
	        					var expected_date2 = new Date(expected_completion_date);
	        					var expected_date   = new Date(object[0]["expected_completion_date"]);

	        					if(expected_date2 > expected_date){

	        						bootbox.prompt({
						        		closeButton: false,
								        title: "Why Do You Need Extension?",
								        message: "<p>Reason?</p>",
								        inputType:'textarea',
								        buttons: {
								            confirm: {
								                label: 'Submit',
								                className: 'btn_purple'
								            },
								            cancel: {
								                label: 'No',
								                className: 'hidden'
								            }
								        },
								        callback: function (result) {
								        	if(result == '' || result == null){
								        		toastr.error('Please Provide Your REASON', 'Error');
								        		return false;
											}else{
												// SAVE Expected Completion Date
												$.post("assignment/submit_expected_completion_date", { 'assignment_id': assignment_id , 'expected_completion_date': expected_completion_date}, function(data, status){
													if(data)
													{
									    	 			toastr.success('Information Updated', 'Updated');
													    location.reload();
									    	 		}
												});

												// SAVE REASON TO LOG TABLE
												$.post("assignment/change_ExpectedCompletionDate", { 'assignment_id': object[0]["assignment_id"]  , 'expected_completion_date': expected_completion_date, 'reason': result}, function(data, status){
													if(data){
									    	 			toastr.success('Information Updated', 'Updated');
									    	 		}
												});

												location.reload();
											}
								        }
								    })
	        					}else{
	        						toastr.error('Not allow to change date before Expected Completion Date ', 'Error');
	        						location.reload();
	        					}
	        				}
	        			}
	        		});

	        	}else{
	        		location.reload();
	        	}

	        }
	    })
    }

    Date.prototype.addDays = function(days) {
	    this.setDate(this.getDate() + parseInt(days));
	    return this;
	};
    
	// Update Status
	function change_status(element){
    	var div 				= $(element).parent();
    	var assignment_id 		= div.find('.assignment_id').val();
    	var status_id 			= div.find('.status-select').val();

    	for(var i = 0; i < assignment_list_data.length; i++){
			if(assignment_list_data[i]["id"] == assignment_id){
				var assignment_log_id = assignment_list_data[i]["assignment_id"]
			}
		}

    	bootbox.confirm({
	        message: "Do you want to change this status info?",
	        closeButton: false,
	        buttons: {
	            confirm: {
	                label: 'Yes',
	                className: 'btn_purple'
	            },
	            cancel: {
	                label: 'No',
	                className: 'btn_cancel'
	            }
	        },
	        callback: function (result) {
	        	if(result == true)
	        	{
	        		if(status_id == '11'){

	        			$.post("assignment/check_signed_assignment", { 'assignment_id': assignment_id , 'status_id': status_id}, function(data, status){
			    	 		if(data){
			    	 			// if != empty
			    	 			if(data!='[]'){

				    	 			var object = (JSON.parse(data));
				    	 			$("#w2-footer_completed").css("display","none");
		    						$("#w2-footer_in_progress").css("display","inline-block !important");

		    						$(".Completed").css("display","none");
		    						$('.Signed').show();
		    						$(".Signed").css("display","inline-block !important");

		    						$("#completed_assignment").modal("show");
		    						document.getElementById('cancel').style.visibility = 'visible';
		    						document.getElementById('save').style.visibility = 'visible';

		    						$(".id").val(object[0]["id"]);
		    						$(".complete_assignment_id").val(object[0]["payroll_assignment_id"]);
		    						$(".status_id").val(status_id);
						    		$(".completed_assignment_firm").val(object[0]["name"]);
						    		$(".completed_assignment_firm_id").val(object[0]["firm_id"]);
						    		$(".completed_assignment_client").val(object[0]["client_name"]);
						    		$(".completed_assignment_client_name").val(object[0]["client_name"]);
						    		$(".completed_assignment_client_id").val(object[0]["client_id"]);
						    		$(".completed_assignment_fye").val(moment(object[0]["FYE"]).format('DD MMMM YYYY'));
						    		$(".completed_assignment_report_date").val(moment(object[0]["report_date"]).format('DD MMMM YYYY'));
						    		$(".CA_partner").val(object[0]["partner"]).trigger('change');
						    		$(".CA_partner_name").val(object[0]["partner"]);
						    		$(".completed_assignment_revenue").val(numberWithCommas(object[0]["revenue"]));
						    		$(".completed_assignment_asset").val(numberWithCommas(object[0]["asset"]));
						    		$(".completed_assignment_pbt_lbt").val(changePBT(object[0]["PBT_LBT"]));
						    		$(".completed_assignment_functional_currency").val(object[0]["functional_currency"]);
						    		$(".completed_assignment_subsidiary").val(object[0]["subsidiary"]);
						    		$(".completed_assignment_holding_company").val(object[0]["holding_company"]);
						    		$(".completed_assignment_normal_audit").val(object[0]["normal_audit"]);
						    		$(".completed_assignment_principal_activity").val(object[0]["principal_activity"]);
						    		$(".completed_assignment_audit_fee").val(numberWithCommas(object[0]["audit_fee"]));
						    	}
						    	else{
				    	 			for(var i = 0; i < assignment_list_data.length; i++)
				    				{
				    					if(assignment_list_data[i]["id"] == assignment_id)
				    					{
				    						$("#w2-footer_completed").css("display","none");
				    						$("#w2-footer_in_progress").css("display","inline-block !important");
				    						$(".Completed").css("display","none");
		    								$('.Signed').show();
		    								$(".Signed").css("display","inline-block !important");
				    						$("#completed_assignment").modal("show");
				    						document.getElementById('cancel').style.visibility = 'visible';
				    						document.getElementById('save').style.visibility = 'visible';

				    							$(".complete_assignment_id").val(assignment_list_data[i]["id"]);
				    							$(".status_id").val(status_id);
				    							$(".completed_assignment_firm").val(assignment_list_data[i]["name"]);
				    							$(".completed_assignment_firm_id").val(assignment_list_data[i]["firm_id"]);
				    							$(".completed_assignment_client_name").val(assignment_list_data[i]["client_name"]);
			           							$(".completed_assignment_client").val(assignment_list_data[i]["client_name"]);
			           							$(".completed_assignment_client_id").val(assignment_list_data[i]["client_id"]);
			           							$(".completed_assignment_fye").val(moment(assignment_list_data[i]["FYE"]).format('DD MMMM YYYY'));
			           							$(".CA_partner_name").val(JSON.parse(assignment_list_data[i]["PIC"])["partner"]).trigger('change');
			           							$(".CA_partner").val(JSON.parse(assignment_list_data[i]["PIC"])["partner"]).trigger('change');
			           							
				    					}
				    				}
				    	 		}

			    	 		}
			    		});
	        		}

	        		if(status_id == '10'){
	        			$.post("assignment/check_signed_assignment", { 'assignment_id': assignment_id , 'status_id': status_id}, function(data, status){
			    	 		if(data){
			    	 			// if != empty
			    	 			if(data!='[]'){

				    	 			var object = (JSON.parse(data));
				    	 			$("#w2-footer_completed").css("display","none");
		    						$("#w2-footer_in_progress").css("display","inline-block !important");

		    						$(".Signed").css("display","none");
		    						$('.Completed').show();
		    						$(".Completed").css("display","inline-block !important");

		    						$("#completed_assignment").modal("show");
		    						document.getElementById('cancel').style.visibility = 'visible';
		    						document.getElementById('save').style.visibility = 'visible';

		    						$(".id").val(object[0]["id"]);
		    						$(".complete_assignment_id").val(object[0]["payroll_assignment_id"]);
		    						$(".status_id").val(status_id);
						    		$(".completed_assignment_firm").val(object[0]["name"]);
						    		$(".completed_assignment_firm_id").val(object[0]["firm_id"]);
						    		$(".completed_assignment_client").val(object[0]["client_name"]);
						    		$(".completed_assignment_client_name").val(object[0]["client_name"]);
						    		$(".completed_assignment_client_id").val(object[0]["client_id"]);
						    		$(".completed_assignment_fye").val(moment(object[0]["FYE"]).format('DD MMMM YYYY'));
						    		$(".completed_assignment_report_date").val(moment(object[0]["report_date"]).format('DD MMMM YYYY'));
						    		$(".CA_partner").val(object[0]["partner"]).trigger('change');
						    		$(".CA_partner_name").val(object[0]["partner"]);
						    		$(".completed_assignment_revenue").val(numberWithCommas(object[0]["revenue"]));
						    		$(".completed_assignment_asset").val(numberWithCommas(object[0]["asset"]));
						    		$(".completed_assignment_pbt_lbt").val(changePBT(object[0]["PBT_LBT"]));
						    		$(".completed_assignment_functional_currency").val(object[0]["functional_currency"]);
						    		$(".completed_assignment_subsidiary").val(object[0]["subsidiary"]);
						    		$(".completed_assignment_holding_company").val(object[0]["holding_company"]);
						    		$(".completed_assignment_normal_audit").val(object[0]["normal_audit"]);
						    		$(".completed_assignment_principal_activity").val(object[0]["principal_activity"]);
						    		$(".completed_assignment_audit_fee").val(numberWithCommas(object[0]["audit_fee"]));
						    	}
						    	else{
				    	 			for(var i = 0; i < assignment_list_data.length; i++)
				    				{
				    					if(assignment_list_data[i]["id"] == assignment_id)
				    					{
				    						$("#w2-footer_completed").css("display","none");
				    						$("#w2-footer_in_progress").css("display","inline-block !important");
				    						$(".Signed").css("display","none");
				    						$('.Completed').show();
				    						$(".Completed").css("display","inline-block !important");
				    						$("#completed_assignment").modal("show");
				    						document.getElementById('cancel').style.visibility = 'visible';
				    						document.getElementById('save').style.visibility = 'visible';

				    							$(".complete_assignment_id").val(assignment_list_data[i]["id"]);
				    							$(".status_id").val(status_id);
				    							$(".completed_assignment_firm").val(assignment_list_data[i]["name"]);
				    							$(".completed_assignment_firm_id").val(assignment_list_data[i]["firm_id"]);
				    							$(".completed_assignment_client_name").val(assignment_list_data[i]["client_name"]);
			           							$(".completed_assignment_client").val(assignment_list_data[i]["client_name"]);
			           							$(".completed_assignment_client_id").val(assignment_list_data[i]["client_id"]);
			           							$(".completed_assignment_fye").val(moment(assignment_list_data[i]["FYE"]).format('DD MMMM YYYY'));
			           							$(".CA_partner_name").val(JSON.parse(assignment_list_data[i]["PIC"])["partner"]).trigger('change');
			           							$(".CA_partner").val(JSON.parse(assignment_list_data[i]["PIC"])["partner"]).trigger('change');
			           							
				    					}
				    				}
				    	 		}

			    	 		}
			    		});
	        		}

	        		if(status_id != '11' && status_id != '10'){
	        			$.post("assignment/updt_status", { 'assignment_id': assignment_id , 'status_id': status_id}, function(data, status){
			    	 		if(data){
			    	 			location.reload();
			    	 		}
			    		});

			    		$.post("assignment/change_status_log", { 'assignment_id': assignment_log_id, 'status_id': status_id}, function(data, status){
							if(data){
			    	 			toastr.success('Information Updated', 'Updated');
			    	 		}
						});
	        		}

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

	//Delete Assignment
	function delete_assignment(element){
    	var div 				= $(element).parent();
    	var assignment_id 		= div.find('.assignment_id').val();
    	bootbox.confirm({
	        message: "Do you want to delete this selected info?",
	        closeButton: false,
	        buttons: {
	            confirm: {
	                label: 'Yes',
	                className: 'btn_purple'
	            },
	            cancel: {
	                label: 'No',
	                className: 'btn_cancel'
	            }
	        },
	        callback: function (result){
	        	if(result == true)
	        	{
	        		$.post("assignment/delete_assignment", { 'assignment_id': assignment_id }, function(data, status){
			    	 	if(data)
			    	 	{
			    	 		location.reload();
			    	 	}
			    	});
	        	}
	        }
	    })
    }

//Show Log
$(document).on('click',"#show_log",function(e){
	var assignment_id =  $(this).data("id");
	$("#log").modal("show");

	$.ajax({
       type: "POST",
       url: "assignment/show_log",
       data: '&assignment_id=' + assignment_id,
       success: function(data)
       {
       		if(JSON.parse(data)!=null || JSON.parse(data)!=""){
           		var object = (JSON.parse(data));
           		for(var i=0; i<object.length; i++){
           			$('.assignment_log_id').val(object[i]["assignment_id"]);

           			var tableRef = document.getElementById('log').getElementsByTagName('tbody')[0];

					// Insert a row in the table at the last row
					var newRow   = tableRef.insertRow();

					// Insert a cell in the row at index 0
					var newCell  = newRow.insertCell(0);
					var newCell2  = newRow.insertCell(1);
					var newCell3  = newRow.insertCell(2);

					// Append a text node to the cell
					var newText  = document.createTextNode(object[i]["assignment_id"]);
					newCell.appendChild(newText);
					var newText2  = document.createTextNode(object[i]["date"]);
					newCell2.appendChild(newText2);
					var newText3  = document.createTextNode(object[i]["assignment_log"]);
					newCell3.appendChild(newText3);

           		}
       		}
       }
   	});

});

    //Show Selected Assignment
	 $(document).on('click',"#edit_assignment",function(e){

	 	e.preventDefault();
	    var assignment_id =  $(this).data("id");

	    for(var i = 0; i < assignment_list_data.length; i++)
	    {
	    	if(assignment_list_data[i]["id"] == assignment_id)
	    	{
	    		<?php if(!($Admin || $Manager)) { ?>
	    			document.getElementById('firm').disabled = "true";
	    			document.getElementById('assignment_fye').disabled = "true";
	    			document.getElementById('assignment_account_received').disabled = "true";
	    			document.getElementById('assignment_due_date').disabled = "true";
	    			document.getElementById('A_partner').disabled = "true";
	    			document.getElementById('manager').disabled = "true";
	    			document.getElementById('leader').disabled = "true";
	    			document.getElementById('assistant').disabled = "true";
	    			document.getElementById('type_of_job').disabled = "true";
	    		<?php } ?>

	    		$("#edit_client").css("display","inline-block !important");
	    		$("#new_client").css("display","none");

	    		$("#new_assignment").modal("show");
	    			$(".assignment_id").val(assignment_list_data[i]["id"]);
	    			$(".assignment_code").val(assignment_list_data[i]["assignment_id"]);
           			$(".assignment_client").val(assignment_list_data[i]["client_id"]).trigger('change');
           			$(".client_name").val(assignment_list_data[i]["client_name"]);
	     			$(".assignment_firm").val(assignment_list_data[i]["firm_id"]).trigger('change');
	     			if(assignment_list_data[i]["FYE"]!=null){
	     				$(".assignment_fye").val(moment(assignment_list_data[i]["FYE"]).format('DD MMMM YYYY'));
	     			}

	     			if(assignment_list_data[i]["account_received"]!=null){
	     				$(".assignment_account_received").val(moment(assignment_list_data[i]["account_received"]).format('DD MMMM YYYY'));
	     			}

	     			if(assignment_list_data[i]["due_date"]!=null){
	     				$(".assignment_due_date").val(moment(assignment_list_data[i]["due_date"]).format('DD MMMM YYYY'));
	     			}
	     			$(".A_partner").val(JSON.parse(assignment_list_data[i]["PIC"])["partner"]).trigger('change');
	     			$(".manager").val(JSON.parse(assignment_list_data[i]["PIC"])["manager"]).trigger('change');
	     			$(".leader").val(JSON.parse(assignment_list_data[i]["PIC"])["leader"]).trigger('change');
	     			$(".assistant").val(JSON.parse(assignment_list_data[i]["PIC"])["assistant"][0]).trigger('change');
	     			
	     			if(assignment_list_data[i]["type_of_job"]!=null){
	     				$(".type_of_job").val(assignment_list_data[i]["type_of_job"]).trigger('change');
	     			}else{
	     				$(".type_of_job").val(0).trigger('change');
	     			}

	     			for (var n = 1; n < JSON.parse(assignment_list_data[i]["PIC"])["assistant"].length; n++){

	     				<?php 
	     					$form_dropdown = form_dropdown('assistant[]', $users_list, isset($users_list[0]->first_name)?$users_list[0]->first_name:'', 'id="assistant" class="assistant" style="width:85%;" id="assistant" required');
	     				?>
						var assistant = <?php echo json_encode($form_dropdown); ?>;
						var addAssistant = assistant.replace('id="assistant"','id="assistant'+ n +'"');
						var tt = $('<div id="sameRow">'+addAssistant+'<?php if($Admin || $Manager){ echo '<a href="javascript: void(0);" rowspan=2; style="color: #D9A200; outline: none !important;text-decoration: none; padding-left:4px; padding-top:8px;"><span id="assistant_Delete" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Family Info" style="font-size:14px;"><i class="fa fa-minus-circle"></i> Drop</span></a>';} ?></div>');
						$("#assistant_list").append(tt);
	     				$("#assistant"+n).val(JSON.parse(assignment_list_data[i]["PIC"])["assistant"][n]).trigger('change');
	     				$("#assistant"+n).select2();

	     				<?php if(!($Admin || $Manager)) { ?>
	    					document.getElementById('assistant'+ n).disabled = "true";
	    				<?php } ?>
	     				
	     			}

	     			$(".assignment_remark").val(assignment_list_data[i]["remark"]);
	    	}
	    }
	});	 

	 //Show Selected Complete Assignment
	 $(document).on('click',"#edit_completed_assignment",function(){
	    var completed_assignment =  $(this).data("id");

	    $("#w2-footer_completed").css("display","inline-block !important");
	    $("#w2-footer_in_progress").css("display","none");

	    $(".Signed").css("display","none");
	    $('.Completed').show();
	    $(".Completed").css("display","inline-block !important");

	    for(var i = 0; i < completed_assignment_list_data.length; i++)
	    {
	    	if(completed_assignment_list_data[i]["payroll_assignment_id"] == completed_assignment)
	    	{
	    		$("#completed_assignment").modal("show");

	    		document.getElementById('cancel').style.visibility = 'hidden';
	    		document.getElementById('save').style.visibility   = 'hidden';

	    		$(".completed_assignment_firm").val(completed_assignment_list_data[i]["name"]);
	    		$(".completed_assignment_client").val(completed_assignment_list_data[i]["client_name"]);
	    		$(".completed_assignment_fye").val(moment(completed_assignment_list_data[i]["FYE"]).format('DD MMMM YYYY'));
	    		$(".completed_assignment_report_date").val(moment(completed_assignment_list_data[i]["report_date"]).format('DD MMMM YYYY'));
	    		$(".CA_partner").val(completed_assignment_list_data[i]["partner"]).trigger('change');
	    		$(".completed_assignment_revenue").val(numberWithCommas(completed_assignment_list_data[i]["revenue"]));
	    		$(".completed_assignment_asset").val(numberWithCommas(completed_assignment_list_data[i]["asset"]));
	    		$(".completed_assignment_pbt_lbt").val(changePBT(completed_assignment_list_data[i]["PBT_LBT"]));
	    		$(".completed_assignment_functional_currency").val(completed_assignment_list_data[i]["functional_currency"]);
	    		$(".completed_assignment_subsidiary").val(completed_assignment_list_data[i]["subsidiary"]);
	    		$(".completed_assignment_holding_company").val(completed_assignment_list_data[i]["holding_company"]);
	    		$(".completed_assignment_normal_audit").val(completed_assignment_list_data[i]["normal_audit"]);
	    		$(".completed_assignment_principal_activity").val(completed_assignment_list_data[i]["principal_activity"]);
	    		$(".completed_assignment_audit_fee").val(numberWithCommas(completed_assignment_list_data[i]["audit_fee"]));

	    		document.getElementById('report_date').disabled = "true";
	    		document.getElementById('CA_partner').disabled = "true";
	    		document.getElementById('revenue').disabled = "true";
	    		document.getElementById('asset').disabled = "true";
	    		document.getElementById('PBT_LBT').disabled = "true";
	    		document.getElementById('functional_currency').disabled = "true";
	    		document.getElementById('subsidiary').disabled = "true";
	    		document.getElementById('holding_company').disabled = "true";
	    		document.getElementById('normal_audit').disabled = "true";
	    		document.getElementById('principal_activity').disabled = "true";
	    		document.getElementById('audit_fee').disabled = "true";
	    	}
	    }
	});

	 //Show Selected Complete Assignment
	 $(document).on('click',"#edit_signed_assignment",function(){
	    var completed_assignment =  $(this).data("id");

	    $("#w2-footer_completed").css("display","inline-block !important");
	    $("#w2-footer_in_progress").css("display","none");

	    $(".Completed").css("display","none");
	    $('.Signed').show();
	    $(".Signed").css("display","inline-block !important");

	    for(var i = 0; i < completed_assignment_list_data.length; i++)
	    {
	    	if(completed_assignment_list_data[i]["payroll_assignment_id"] == completed_assignment)
	    	{
	    		$("#completed_assignment").modal("show");

	    		document.getElementById('cancel').style.visibility = 'hidden';
	    		document.getElementById('save').style.visibility   = 'hidden';

	    		$(".completed_assignment_firm").val(completed_assignment_list_data[i]["name"]);
	    		$(".completed_assignment_client").val(completed_assignment_list_data[i]["client_name"]);
	    		$(".completed_assignment_fye").val(moment(completed_assignment_list_data[i]["FYE"]).format('DD MMMM YYYY'));
	    		$(".completed_assignment_report_date").val(moment(completed_assignment_list_data[i]["report_date"]).format('DD MMMM YYYY'));
	    		$(".CA_partner").val(completed_assignment_list_data[i]["partner"]).trigger('change');
	    		$(".completed_assignment_revenue").val(numberWithCommas(completed_assignment_list_data[i]["revenue"]));
	    		$(".completed_assignment_asset").val(numberWithCommas(completed_assignment_list_data[i]["asset"]));
	    		$(".completed_assignment_pbt_lbt").val(changePBT(completed_assignment_list_data[i]["PBT_LBT"]));
	    		$(".completed_assignment_functional_currency").val(completed_assignment_list_data[i]["functional_currency"]);
	    		$(".completed_assignment_subsidiary").val(completed_assignment_list_data[i]["subsidiary"]);
	    		$(".completed_assignment_holding_company").val(completed_assignment_list_data[i]["holding_company"]);
	    		$(".completed_assignment_normal_audit").val(completed_assignment_list_data[i]["normal_audit"]);
	    		$(".completed_assignment_principal_activity").val(completed_assignment_list_data[i]["principal_activity"]);
	    		$(".completed_assignment_audit_fee").val(numberWithCommas(completed_assignment_list_data[i]["audit_fee"]));

	    		document.getElementById('report_date').disabled = "true";
	    		document.getElementById('CA_partner').disabled = "true";
	    		document.getElementById('revenue').disabled = "true";
	    		document.getElementById('asset').disabled = "true";
	    		document.getElementById('PBT_LBT').disabled = "true";
	    		document.getElementById('functional_currency').disabled = "true";
	    		document.getElementById('subsidiary').disabled = "true";
	    		document.getElementById('holding_company').disabled = "true";
	    		document.getElementById('normal_audit').disabled = "true";
	    		document.getElementById('principal_activity').disabled = "true";
	    		document.getElementById('audit_fee').disabled = "true";
	    	}
	    }
	});

	//Save completed assignment
    $("#completed_assignment_form").submit(function (e){
		var form = $(this);
		var status_id =  $(".status_id").val();
		for(var i = 0; i < assignment_list_data.length; i++){
			if(assignment_list_data[i]["id"] == $(".complete_assignment_id").val()){
				var assignment_log_id = assignment_list_data[i]["assignment_id"]

			}
		}
		$.post("assignment/change_status_log", { 'assignment_id': assignment_log_id, 'status_id': status_id}, function(data, status){
			if(data){
	 			toastr.success('Information Updated', 'Updated');
	 		}
		});

        $.ajax({
           type: "POST",
           url: "assignment/save_completed_assignment",
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {	
           		toastr.success('Information Updated', 'Updated');
			    location.reload();

           }
       	});

    	e.preventDefault();
	});

    // Add Assistant
    var n = 0;
	$(document).on('click',"#assistant_Add",function() {
		var first_assistant = $(".assistant").val();
		<?php

			$form_dropdown = form_dropdown('assistant[]', $users_list, isset($users_list[0]->first_name)?$users_list[0]->first_name:'', ' class="assistant new_assistant" style="width:85%;" required');
		?>
		var assistant = <?php echo json_encode($form_dropdown); ?>;
		assistant = assistant.replace('class="assistant new_assistant"','class="assistant new_assistant'+n+'"');
		var tt = '<div id="sameRow">'+assistant+'<a href="javascript: void(0);" rowspan=2; style="color: #D9A200; outline: none !important;text-decoration: none; padding-left:4px; padding-top:8px;"><span id="assistant_Delete" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Family Info" style="font-size:14px;"><i class="fa fa-minus-circle"></i> Drop</span></div>';

		$("#assistant_list").prepend(tt);
		$(".first").val('').trigger('change');

		$(".assistant").select2();
		$(".new_assistant"+n).val(first_assistant).trigger('change');
		n++;
	});

	// Remove Assistant
	$(document).on('click',"#assistant_Delete",function() {
		var tr = jQuery(this).parent().parent();
		tr.remove();
	});

	//Generate Excel
	$("#generate_log_Excel").click(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "assignment/generateLogExcel",
           data: $('form#share_transfer_form').serialize()+ '&assignment_id=' + $(".assignment_log_id").val(),
           success: function(response,data)
           {	
           		toastr.success('Excel File Generated', 'Successful');
           		window.open(
                  response,
                  '_blank' // <- This is what makes it open in a new window.
                );
           }
       	});

    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });

	//Generate Excel
	$("#generate_A_Excel").click(function(e) {
        var form = $(this);

        <?php if($Admin) { ?>

	       	$.ajax({
	           type: "POST",
	           url: "assignment/generateAExcel",
	           data: $('form#share_transfer_form').serialize()+ '&partner=' + $(".A_partner_filter").val(),
	           success: function(response,data)
	           {	
	           		toastr.success('Excel File Generated', 'Successful');
	           		window.open(
	                  response,
	                  '_blank' // <- This is what makes it open in a new window.
	                );
	           }
	       	});

        <?php }else{ ?>

        	$.ajax({
	           type: "POST",
	           url: "assignment/generateAExcel2",
	           data: $('form#share_transfer_form').serialize()+ '&partner=' + $(".A_partner_filter").val(),
	           success: function(response,data)
	           {	
	           		toastr.success('Excel File Generated', 'Successful');
	           		window.open(
	                  response,
	                  '_blank' // <- This is what makes it open in a new window.
	                );
	           }
	       	});

        <?php } ?>

    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    //Generate Excel
	$("#generate_CA_Excel").click(function(e) {
        var form = $(this);

        <?php if($Admin) { ?>

	        $.ajax({
	           type: "POST",
	           url: "assignment/generateCAExcel",
	           data: $('form#share_transfer_form').serialize()+ '&partner=' + $(".CA_partner_filter").val() + '&from=' + $(".CA_date_from").val() + '&to=' + $(".CA_date_to").val(),
	           success: function(response,data)
	           {	
	           		toastr.success('Excel File Generated', 'Successful');
	           		window.open(
	                  response,
	                  '_blank' // <- This is what makes it open in a new window.
	                );
	           }
	       	});

		<?php }else{ ?>

			$.ajax({
	           type: "POST",
	           url: "assignment/generateCAExcel2",
	           data: $('form#share_transfer_form').serialize()+ '&partner=' + $(".CA_partner_filter").val() + '&from=' + $(".CA_date_from").val() + '&to=' + $(".CA_date_to").val(),
	           success: function(response,data)
	           {	
	           		toastr.success('Excel File Generated', 'Successful');
	           		window.open(
	                  response,
	                  '_blank' // <- This is what makes it open in a new window.
	                );
	           }
	       	});

		<?php } ?>

    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    //Generate Excel
	$("#generate_SA_Excel").click(function(e) {
        var form = $(this);

        <?php if($Admin) { ?>

	        $.ajax({
	           type: "POST",
	           url: "assignment/generateSAExcel",
	           data: $('form#share_transfer_form').serialize()+ '&partner=' + $(".SA_partner_filter").val() + '&from=' + $(".SA_date_from").val() + '&to=' + $(".SA_date_to").val(),
	           success: function(response,data)
	           {	
	           		toastr.success('Excel File Generated', 'Successful');
	           		window.open(
	                  response,
	                  '_blank' // <- This is what makes it open in a new window.
	                );
	           }
	       	});

		<?php }else{ ?>

			$.ajax({
	           type: "POST",
	           url: "assignment/generateSAExcel2",
	           data: $('form#share_transfer_form').serialize()+ '&partner=' + $(".SA_partner_filter").val() + '&from=' + $(".SA_date_from").val() + '&to=' + $(".SA_date_to").val(),
	           success: function(response,data)
	           {	
	           		toastr.success('Excel File Generated', 'Successful');
	           		window.open(
	                  response,
	                  '_blank' // <- This is what makes it open in a new window.
	                );
	           }
	       	});

		<?php } ?>

    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    // Date Filter
    var A_list;
	$("#filter1").click(function(e) {
        var form = $(this);
        $("#loadingmessage").show();
        $.ajax({
           type: "POST",
           url: "assignment/A_filter",
           // async: false,
           data: $('form#share_transfer_form').serialize()+ '&partner=' + $(".A_partner_filter").val(),
           success: function(data)
           {
           		if(JSON.parse(data)==null || JSON.parse(data)==""){
           			$("#datatable-inprogress").DataTable().destroy();
           			var table  = $("#datatable-inprogress").DataTable();
	           		var object = (JSON.parse(data));
					table.clear().draw();
           		}

				if(JSON.parse(data)!=null){

					$("#datatable-inprogress").DataTable().destroy();

	           		var object = (JSON.parse(data));
					$(".testing123").remove();

					for(var i = 0; i < object.length; i++){
						var assistant_list = "";
						var rowHtml1 = "";
						var assistant_list = JSON.parse(object[i]["PIC"])["assistant"].length;

						for(var n = 0; n < assistant_list; n++){
							var rowHtml1 = rowHtml1+"<tr><th>Assistant</th><td>"+JSON.parse(object[i]["PIC"])["assistant"][n]+"</td></tr>";
						}

						if(object[i]["FYE"]!=null){
							var rowHtmlfye = ""+moment(object[i]["FYE"]).format('DD/MM/YYYY')+"";
						}else{
							var rowHtmlfye = " ";
						}

						if(object[i]["account_received"]!=null){
							var rowHtmlac = ""+moment(object[i]["account_received"]).format('DD/MM/YYYY')+"";
						}else{
							var rowHtmlac = "";
						}

						if(object[i]["due_date"]!=null){
							var rowHtmldd = ""+moment(object[i]["due_date"]).format('DD/MM/YYYY')+"";
						}else{
							var rowHtmldd = "";
						}

						if(object[i]["job"]!=null){
							var job = ""+object[i]["job"]+"";
						}else{
							var job = "";
						}

						<?php if($Admin || $Manager) { ?>
							var rowHtmldelete = "<td><input type='hidden' class='assignment_id' value="+object[i]["id"]+" /><button type='button' class='btn btn_purple' onclick=delete_assignment(this) title='Delete'><i class='fas fa-trash-alt'></i></button></td></tr>";
						<?php }else{ ?>
							var rowHtmldelete = "</tr>";
						<?php } ?>

						<?php if($Admin || $Manager) { ?>
							var rowHtmllog = "<td><button  class='btn btn_purple' title='Log' data-id='"+object[i]["assignment_id"]+"' id='show_log'><i class='fas fa-search'></i></button></td>";
						<?php }else{ ?>
							var rowHtmllog= "";
						<?php } ?>

						if(object[i]["signed"]=='0'){
							<?php 
								$form_dropdown = form_dropdown('status_id', $status_list, isset($assignment->status)?$assignment->status:'', 'class="status-select" id="status-select" style="width:100%;" onchange=change_status(this) required');
		     				?>
		     				var status = <?php echo json_encode($form_dropdown); ?>;
							var statuslist = status.replace('id="status-select"','id="status-select'+i+'"');

						}else if(object[i]["signed"]=='1'){
							<?php 
								$form_dropdown = form_dropdown('status_id', $status_list2, isset($assignment->status)?$assignment->status:'', 'class="status-select" id="status-select" style="width:100%;" onchange=change_status(this) required');
		     				?>
		     				var status = <?php echo json_encode($form_dropdown); ?>;
							var statuslist = status.replace('id="status-select"','id="status-select'+i+'"');

						}

						if(object[i]["remark"]!=""){
							var remark = "<td align='center'><a href='javascript:void(0)' style='font-weight:bold;' data-toggle='tooltip' data-placement='top' data-original-title='"+object[i]["remark"]+"'><i class='fas fa-info-circle' style='font-size:16px;'></i></a></td>";
						}else{
							var remark = "<td align='center'><a href='javascript:void(0)' style='font-weight:bold;' data-toggle='tooltip' data-placement='top' data-original-title='No Remark'><i class='fas fa-info-circle' style='font-size:16px;'></i></a></td>";
						}

						if(object[i]["expected_completion_date"]!=null){

							var date = new Date();
							var today = new Date(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate());
							var ECD = new Date(object[i]["expected_completion_date"]);

							date.addDays(3);
							var threeDaysBefore = new Date(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate());

							if(ECD<=threeDaysBefore){
								var expected_completion_date = "<div class='input-group date datepicker' data-provide='datepicker'><span class='input-group-addon'><i class='far fa-calendar-alt'></i></span><input onchange=change_EC_date(this) type='text' class='form-control EC_date' name='EC_date'  value='"+moment(object[i]["expected_completion_date"]).format('DD MMMM YYYY')+"' style='color:#FF0000; font-weight:bold'/></div></td>";
							}else{
								var expected_completion_date = "<div class='input-group date datepicker' data-provide='datepicker'><span class='input-group-addon'><i class='far fa-calendar-alt'></i></span><input onchange=change_EC_date(this) type='text' class='form-control EC_date' name='EC_date'  value='"+moment(object[i]["expected_completion_date"]).format('DD MMMM YYYY')+"'/></div></td>";
							}

						}else{
							var ECD = null;
							var expected_completion_date = "<div class='input-group date datepicker' data-provide='datepicker'><span class='input-group-addon'><i class='far fa-calendar-alt'></i></span><input onchange=change_EC_date(this) type='text' class='form-control EC_date' name='EC_date'  value=''/></div></td>";
						}
						
						if(ECD != null){
							
							if(ECD<=threeDaysBefore){
								var rowHtml2 =  "<tr style='background-color:#FAEBD7; font-weight:bold;' class='testing123'><td>"+object[i]["assignment_id"]+"</td><td><a href='javascript:void(0)' class='assignment' data-id='"+object[i]["id"]+"' id='edit_assignment'>"+object[i]["client_name"]+"</a></td><td>"+object[i]["name"]+"</td><td>"+ job +"</td><td style='width:15%;'><table class='table table-bordered table-striped mb-none'><tr><th>Partner</th><td>"+JSON.parse(object[i]["PIC"])["partner"] +"</td></tr><tr><th>Manager</th><td>"+ JSON.parse(object[i]["PIC"])["manager"] +"</td></tr><tr><th>Leader</th><td>"+ JSON.parse(object[i]["PIC"])["leader"] +"</td></tr> A </table></td><td> "+rowHtmlfye+" </td><td> "+rowHtmlac+" </td><td> "+rowHtmldd+" </td><td style='width:16%;'><div class='status-select'><input type='hidden'  class='assignment_id' value='"+object[i]["id"]+"' />"+statuslist+"</div></td> "+remark+"<td style='width:16%;'><input type='hidden' class='ECD_assignment_id' value='"+object[i]["id"]+"' />"+expected_completion_date+""+rowHtmllog+""+rowHtmldelete+"";
							} else
							{
								var rowHtml2 =  "<tr id='"+object[i]["id"]+"' class='testing123'><td>"+object[i]["assignment_id"]+"</td><td><a href='javascript:void(0)' class='assignment' data-id='"+object[i]["id"]+"' id='edit_assignment'>"+object[i]["client_name"]+"</a></td><td>"+object[i]["name"]+"</td><td>"+ job +"</td><td style='width:15%;'><table class='table table-bordered table-striped mb-none'><tr><th>Partner</th><td>"+JSON.parse(object[i]["PIC"])["partner"] +"</td></tr><tr><th>Manager</th><td>"+ JSON.parse(object[i]["PIC"])["manager"] +"</td></tr><tr><th>Leader</th><td>"+ JSON.parse(object[i]["PIC"])["leader"] +"</td></tr> A </table></td><td> "+rowHtmlfye+" </td><td> "+rowHtmlac+" </td><td> "+rowHtmldd+" </td><td style='width:16%;'><div class='status-select'><input type='hidden'  class='assignment_id' value='"+object[i]["id"]+"' /> "+statuslist+" </div></td> "+remark+"<td style='width:16%;'><input type='hidden' class='ECD_assignment_id' value='"+object[i]["id"]+"' />"+expected_completion_date+""+rowHtmllog+""+rowHtmldelete+"";
							}
						} else
						{
							var rowHtml2 =  "<tr id='"+object[i]["id"]+"' class='testing123'><td>"+object[i]["assignment_id"]+"</td><td><a href='javascript:void(0)' class='assignment' data-id='"+object[i]["id"]+"' id='edit_assignment'>"+object[i]["client_name"]+"</a></td><td>"+object[i]["name"]+"</td><td>"+ job +"</td><td style='width:15%;'><table class='table table-bordered table-striped mb-none'><tr><th>Partner</th><td>"+JSON.parse(object[i]["PIC"])["partner"] +"</td></tr><tr><th>Manager</th><td>"+ JSON.parse(object[i]["PIC"])["manager"] +"</td></tr><tr><th>Leader</th><td>"+ JSON.parse(object[i]["PIC"])["leader"] +"</td></tr> A </table></td><td> "+rowHtmlfye+" </td><td> "+rowHtmlac+" </td><td> "+rowHtmldd+" </td><td style='width:16%;'><div class='status-select'><input type='hidden'  class='assignment_id' value='"+object[i]["id"]+"' /> "+statuslist+" </div></td> "+remark+"<td style='width:16%;'><input type='hidden' class='ECD_assignment_id' value='"+object[i]["id"]+"' />"+expected_completion_date+""+rowHtmllog+""+rowHtmldelete+"";
						}

						var rowHtml4 = rowHtml1.replace('undefined','');
						var rowHtml3 = rowHtml2.replace('</tr> A </table>','</tr>'+rowHtml4+'</table>');
						$(".testing321").append(rowHtml3);

						$("#status-select"+i).val(object[i]["status"]);
						$("#status-select"+i).select2();
						$('[data-toggle="tooltip"]').tooltip({html:true});
		        		$('.datepicker').datepicker({ format: 'dd MM yyyy' });
					}
					$("select[name='datatable-inprogress_length']").select2();
			        $('div#datatable-inprogress_filter.dataTables_filter input').addClass('form-control');
			        $('div#datatable-inprogress_filter.dataTables_filter input').attr("placeholder", "Search");

			        $('#datatable-inprogress').DataTable({
				    	"order": [],
				    	"bStateSave": true,
				        pageLength: 10
				    });
				}
				A_list = object;
				$("#loadingmessage").hide();
           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    var CA_list;
	$("#filter2").click(function(e) {
        var form = $(this);

        $.ajax({
           type: "POST",
           url: "assignment/CA_filter",
           data: $('form#share_transfer_form').serialize()+ '&partner=' + $(".CA_partner_filter").val() + '&from=' + $(".CA_date_from").val() + '&to=' + $(".CA_date_to").val(),
           success: function(data)
           {
           		if(JSON.parse(data)==null || JSON.parse(data)==""){
           			var table = $(".datatable-completed").DataTable();
	           		var object = (JSON.parse(data));
					table.clear().draw();
           		}

				if(JSON.parse(data)!=null){

					var table = $(".datatable-completed").DataTable();
	           		var object = (JSON.parse(data));
					table.clear();

					for(var i = 0; i < object.length; i++){
						var assistant_list = "";
						var rowHtml1 = "";
						assistant_list = JSON.parse(object[i]["PIC"])["assistant"].length;

						for(var n = 0; n < assistant_list; n++){
							rowHtml1 = rowHtml1+"<tr><th>Assistant</th><td>"+JSON.parse(object[i]["PIC"])["assistant"][n]+"</td></tr>";
						}

						if(object[i]["FYE"]!=null){
							var rowHtmlfye = ""+moment(object[i]["FYE"]).format('DD MMMM YYYY')+"";
						}else{
							var rowHtmlfye = " ";
						}

						if(object[i]["account_received"]!=null){
							var rowHtmlac = ""+moment(object[i]["account_received"]).format('DD MMMM YYYY')+"";
						}else{
							var rowHtmlac = "";
						}

						if(object[i]["due_date"]!=null){
							var rowHtmldd = ""+moment(object[i]["due_date"]).format('DD MMMM YYYY')+"";
						}else{
							var rowHtmldd = "";
						}

						if(object[i]["job"]!=null){
							var job = ""+object[i]["job"]+"";
						}else{
							var job = "";
						}

						if(object[i]["remark"]!=""){
							var remark = "<td align='center'><a href='javascript:void(0)' style='font-weight:bold;' data-toggle='tooltip' data-placement='top' data-original-title='"+object[i]["remark"]+"'><i class='fas fa-info-circle' style='font-size:16px;'></i></a></td>";
						}else{
							var remark = "<td align='center'><a href='javascript:void(0)' style='font-weight:bold;' data-toggle='tooltip' data-placement='top' data-original-title='No Remark'><i class='fas fa-info-circle' style='font-size:16px;'></i></a></td>";
						}

						var rowHtml2 =  "<tr><td>"+object[i]["assignment_id"]+"</td><td><a href='javascript:void(0)'' class='assignment' data-id='"+object[i]["id"]+"' id='edit_completed_assignment'>"+object[i]["client_name"]+"</a></td><td>"+ object[i]["name"] +"</td><td>"+ job +"</td>"+"<td style='width:15%;'><table class='table table-bordered table-striped mb-none'><th>Partner</th><td>"+ JSON.parse(object[i]["PIC"])["partner"] +"</td></tr><tr><th>Manager</th><td>"+ JSON.parse(object[i]["PIC"])["manager"] +"</td></tr><tr><th>Leader</th><td>"+ JSON.parse(object[i]["PIC"])["leader"] +"</td></tr> A </table></td><td>"+ rowHtmlfye +"</td><td>"+ rowHtmlac +"</td><td>"+ rowHtmldd +"</td><td>"+object[i]["assignment_status"]+"</td>"+remark+"</tr>";

						var rowHtml1 = rowHtml1.replace('undefined','');
						var rowHtml3 = rowHtml2.replace('</tr> A </table>','</tr>'+rowHtml1+'</table>');

						table.row.add($(rowHtml3)).draw();
					}
				}

				CA_list = object;

           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });


    var SA_list;
	$("#filter3").click(function(e) {
        var form = $(this);
        $.ajax({
           type: "POST",
           url: "assignment/SA_filter",
           data: $('form#share_transfer_form').serialize()+ '&partner=' + $(".SA_partner_filter").val() + '&from=' + $(".SA_date_from").val() + '&to=' + $(".SA_date_to").val(),
           success: function(data)
           {
           		if(JSON.parse(data)==null || JSON.parse(data)==""){
           			var table = $(".datatable-signed").DataTable();
	           		var object = (JSON.parse(data));
					table.clear().draw();
           		}

				if(JSON.parse(data)!=null){

					var table = $(".datatable-signed").DataTable();
	           		var object = (JSON.parse(data));
					table.clear();

					for(var i = 0; i < object.length; i++){
						var assistant_list = "";
						var rowHtml1 = "";
						assistant_list = JSON.parse(object[i]["PIC"])["assistant"].length;

						for(var n = 0; n < assistant_list; n++){
							rowHtml1 = rowHtml1+"<tr><th>Assistant</th><td>"+JSON.parse(object[i]["PIC"])["assistant"][n]+"</td></tr>";
						}

						if(object[i]["FYE"]!=null){
							var rowHtmlfye = ""+moment(object[i]["FYE"]).format('DD MMMM YYYY')+"";
						}else{
							var rowHtmlfye = " ";
						}

						if(object[i]["account_received"]!=null){
							var rowHtmlac = ""+moment(object[i]["account_received"]).format('DD MMMM YYYY')+"";
						}else{
							var rowHtmlac = "";
						}

						if(object[i]["due_date"]!=null){
							var rowHtmldd = ""+moment(object[i]["due_date"]).format('DD MMMM YYYY')+"";
						}else{
							var rowHtmldd = "";
						}

						if(object[i]["job"]!=null){
							var job = ""+object[i]["job"]+"";
						}else{
							var job = "";
						}

						if(object[i]["remark"]!=""){
							var remark = "<td align='center'><a href='javascript:void(0)' style='font-weight:bold;' data-toggle='tooltip' data-placement='top' data-original-title='"+object[i]["remark"]+"'><i class='fas fa-info-circle' style='font-size:16px;'></i></a></td>";
						}else{
							var remark = "<td align='center'><a href='javascript:void(0)' style='font-weight:bold;' data-toggle='tooltip' data-placement='top' data-original-title='No Remark'><i class='fas fa-info-circle' style='font-size:16px;'></i></a></td>";
						}

						var rowHtml2 =  "<tr><td>"+object[i]["assignment_id"]+"</td><td><a href='javascript:void(0)'' class='assignment' data-id='"+object[i]["id"]+"' id='edit_signed_assignment'>"+object[i]["client_name"]+"</a></td><td>"+ object[i]["name"] +"</td><td>"+ job +"</td>"+"<td style='width:15%;'><table class='table table-bordered table-striped mb-none'><th>Partner</th><td>"+ JSON.parse(object[i]["PIC"])["partner"] +"</td></tr><tr><th>Manager</th><td>"+ JSON.parse(object[i]["PIC"])["manager"] +"</td></tr><tr><th>Leader</th><td>"+ JSON.parse(object[i]["PIC"])["leader"] +"</td></tr> A </table></td><td>"+ rowHtmlfye +"</td><td>"+ rowHtmlac +"</td><td>"+ rowHtmldd +"</td><td>"+object[i]["assignment_status"]+"</td>"+remark+"</tr>";

						var rowHtml1 = rowHtml1.replace('undefined','');
						var rowHtml3 = rowHtml2.replace('</tr> A </table>','</tr>'+rowHtml1+'</table>');

						table.row.add($(rowHtml3)).draw();
					}
				}

				SA_list = object;

           }
       	});
    	e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $(function (){
	  $('[data-toggle="tooltip"]').tooltip({html:true})
	})

</script>