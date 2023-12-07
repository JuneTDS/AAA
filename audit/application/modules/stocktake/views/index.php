<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" />
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url()?>node_modules/toastr/build/toastr.min.js" /></script>

<!-- <script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script> -->
<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>

<!-- <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script> -->
<!-- <script src="http://cdn.datatables.net/1.10.7/js/jquery.dataTables.js"></script> -->
<script src="<?= base_url()?>assets/vendor/dataTables.rowsGroup.js"></script>

<!-- <script src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.1/css/rowGroup.dataTables.min.css" />
 -->
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<script src="<?= base_url() ?>node_modules/moment/moment.js"></script>
<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootbox/bootbox.min.css" /> -->
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>

<style type="text/css">
	.table_filter_div .select2-container
	{
		display: inline-block !important;
		width: auto !important;

	}

	.select2-container .select2-selection--single .select2-selection__rendered
	{
		padding-right: 40px !important;
	}

	.review_point_icon
	{
		float: right;
		display: inline-block;
	}

	.review_point_icon 
	{
		float: right;
		display: inline-block;
	}

	.bootbox .modal-dialog
	{
		margin:60px auto;
	}


	#edit_subsequent_modal > .modal-dialog
	{
		width: 90%;

	}

	#subsequent_edit_table
	{
		width: 100%;
		margin: auto;
	}

	#attachment_table_wrapper
	{
		max-height: 165px;
		overflow: auto;
		display:inline-block;
		width:100%;
	}

	#edit_attachment_table th
	{
		vertical-align: middle !important;
		font-weight: normal !important;
	}

	.rp_cleared
	{
		text-decoration: line-through;
	}

	.disable {
	    cursor: not-allowed;
	    color: #A9A9A9 !important;
	}

	.disable:active {
	    pointer-events: none;
	}

	button:disabled,
	button[disabled]{
	 
	  background-color: #A9A9A9;

	}



</style>


<section role="main" class="content_section" style="margin-left:0;">
	<header class="panel-heading">
		<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
			<div class="panel-actions">
				<a class="create_st_arrangement amber" href="<?= base_url();?>stocktake/add_stocktake_arrangement" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;display:none;" data-original-title="Create Stocktake Arrangement" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add Stocktake Arrangement</a>
			</div>
			<h2></h2>
		<?php } ?>
	</header>
	<section class="panel" style="margin-top: 0px;">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="tabs">				
					<ul class="nav nav-tabs nav-justify">
						
						<li class="active check_state" data-information="st_arrangement">
							<a href="#w2-st_arrangement" data-toggle="tab" class="text-center">
								<!-- <span class="badge hidden-xs">2</span> -->
								Arrangement
							</a>
						</li>
						<li class="check_state" data-information="st_subsequent">
							<a href="#w2-st_subsequent" data-toggle="tab" class="text-center">
								<!-- <span class="badge hidden-xs">2</span> -->
								Subsequent
							</a>
						</li>
						<li class="check_state" data-information="st_setting_list">
							<a href="#w2-st_setting_list" data-toggle="tab" class="text-center">
								<!-- <span class="badge hidden-xs">1</span> -->
								Setup
							</a>
						</li>
					<!-- 	<li class="check_state" data-information="bank_auth_deactive">
							<a href="#w2-bank_auth_deactive" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">3</span>
								Bank Authorization - Deactivated
							</a>
						</li>
						<li class="check_state" data-information="bank_confirm_setting">
							<a href="#w2-bank_confirm_setting" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">4</span>
								Bank Confirmation Setting
							</a>
						</li>
						<li class="check_state" data-information="bank_confirm">
							<a href="#w2-bank_confirm" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">5</span>
								Bank Confirmation
							</a>
						</li> -->
					</ul>
					<div class="tab-content clearfix">
						<div id="w2-st_setting_list" class="tab-pane">
							<table class="table table-bordered table-striped mb-none" id="datatable-stocktake_setting" style="width: 100%">
								<thead>
									<tr>
										<th>No</th>
										<th>Company Name</th>
										<th>Company Email</th>
										<th>Active</th>
									</tr>
								</thead>
								<tbody>
									<?php
										// $i = 1;
										if($stocktake_setting_list){
											// print_r($stocktake_setting_list);
											foreach($stocktake_setting_list as $st)
											{
												//echo '';
												echo '<tr>';
												echo '<td></td>';
												echo '<td>'.$st->company_name.'</td>';
												echo '<td>'.$st->email.'</td>';
												echo '<td width="10%" align="center" data-order='.$st->reminder_flag.'><input type="hidden" class="company_code" value="'.$st->company_code.'" /><label class="verify_switch"><input name="active_switch" onchange=change_stocktake_setting(this,'.(($st->reminder_flag)?"1":"0").') class="active_switch" type="checkbox" '.(($st->reminder_flag)?"checked":"").'><span class="slider round"></span></label></td>';
												echo '</tr>';

												// $i++;
											}
										}
										
									?>

								</tbody>
							</table>
						</div>
						<div id="w2-st_arrangement" class="tab-pane active">
							<div class="row form-inline" style="padding-left: 15px;padding-right: 15px;">
								<div class="table_filter_div">
					        		
					        		Offices: 
									<?php
										echo form_dropdown('office', $office, isset($office)?$office[0]:'', ' id="sta_office_filter" class="form-control office sta_office_filter" ');
									?> 
									
									&nbsp;&nbsp;&nbsp;
								
									Departments: 
									<?php
										echo form_dropdown('department', $department, isset($department)?$department[0]:'', ' id="sta_department_filter" class="form-control department sta_department_filter" ');
									?>

									&nbsp;&nbsp;&nbsp;

					        		Auditor :
					        		<?php 
					           			echo form_dropdown('auditor', $auditor_filter_dropdown, 0, 'multiple="multiple" class="form-control sta_auditor_filter" ');
					        		?>

					        		&nbsp;&nbsp;&nbsp;

					        		FYE Month :
					        		<div class="input-group" style="width: 15%;">
				                    	<span class="input-group-addon">
				                    		<i class="far fa-calendar-alt"></i>
				                    	</span>
				                    	<input type="text" class="sta_month_filter form-control" name="confirm_month" data-date-format="mm/yyyy" value="" placeholder="Select Month" autocomplete="off">
									</div>

									&nbsp;&nbsp;&nbsp;

					        		Arranged :
					        		<select id="arranged" class="form-control sta_arranged_filter">
					        		  <option value="">All</option>
									  <option value="1">Arranged</option>
									  <option value="0">Not Arranged</option>
									</select>

				        		</div>
			        		</div>

				        	<hr>

							<table class="table table-bordered table-striped mb-none datatable-stocktake_arrangement" id="datatable-stocktake_arrangement" style="width:100%;">
								<thead style="width:100%">
									<tr>
									<!-- <th><input type="checkbox" name="selectall" class="selectall"/></th> -->
										<!-- <th style="text-align:center;">No</th> -->
										<th style="height:35px;width:19%;">Client</th>
										<th style="height:35px;width:8%;">FYE Date</th>
										<th style="height:35px;width:15%;">Our PIC</th>
										<th style="height:35px;width:8%;">Date</th>
										<th style="height:35px;width:8%;">Time</th>
										<th style="height:35px;width:20%;">Address</th>
										<th style="height:35px;width:14%;">Client PIC</th>
										<th style="height:35px;width:8%;"></th>
									</tr>
								</thead>
								<tbody class="stocktake_arrangement_body">
									<?php 
									
										if($stocktake_arrangement_list)
										{
											foreach($stocktake_arrangement_list as $key=>$each)
								  			{	
								  				$rowAuditorName = "";
								  				foreach ($each->auditor_name as $value) {
								  					$rowAuditorName .= '<tr><td>'.$value.'</td></tr>';
								  				}

								  				echo '<tr class="sta_tr">';
								  				// echo '<td></td>';
								  				echo '<input type="hidden" class="sta_id" value="'.$each->sta_id.'" />';
								  				echo '<input type="hidden" class="company_name" value="'.$each->company_name.'" />';
								  				echo '<td style="width:19%;"><a href="stocktake/edit_stocktake_arrangement/'.$each->sta_id.'" class="pointer mb-sm mt-sm mr-sm">'.$each->company_name.'</a></td>';
								  				echo '<td style="width:8%;">'.$each->fye_date.'</td>';
								  				echo '<td style="width:15%;">'.($each->auditor_id?"<table class='table table-bordered table-striped mb-none'>".$rowAuditorName."</table>":"<b>Not Arranged</b>").'</td>';
								  				echo '<td style="width:8%;">'.($each->stocktake_date != "0000-00-00"?$each->stocktake_date:"<b>Not Arranged</b>").'</td>';
								  				echo '<td style="width:8%;">'.($each->stocktake_time != "00:00:00"?$each->stocktake_time:"<b>Not Arranged</b>").'</td>';
								  				echo '<td style="width:20%;">'.($each->stocktake_address != ""?$each->stocktake_address:"<b>Not Arranged</b>").'</td>';
								  				echo '<td style="width:14%;">'.($each->client_pic != ""?$each->client_pic:"<b>Not Arranged</b>").'</td>';
								  				echo '<td style="width:8%;">
								  						<input type="hidden" class="sta_id" value="'. $each->sta_id .'" />
								  						<input type="hidden" class="reminder_id" value="'. $each->reminder_id .'" />
								  						<button type="button" class="btn btn_blue" onclick=generate_reminder(this) style="margin:4px">PDF</button>
								  						<button type="button" class="btn btn_blue" onclick=done_sta(this) style="margin-bottom:5px;margin-right:5px;margin-left:5px;">Done</button>
														<button type="button" class="btn btn_blue" onclick=delete_sta(this) style="margin-bottom:5px;margin-right:5px;margin-left:5px;">Delete</button></td>';
								  				echo '</tr>';
								  			}
										}
										
									?>
								</tbody>
							</table>
						</div>
						<di
						v id="w2-st_subsequent" class="tab-pane">
							<div class="row form-inline" style="padding-left: 15px;padding-right: 15px;">
								<div class="table_filter_div">
					        		
									Offices: 
									<?php
										echo form_dropdown('office', $office, isset($office)?$office[0]:'', ' id="sts_office_filter" class="form-control office sts_office_filter" ');
									?> 
									
									&nbsp;&nbsp;&nbsp;
								
									Departments: 
									<?php
										echo form_dropdown('department', $department, isset($department)?$department[0]:'', ' id="sts_department_filter" class="form-control department sts_department_filter" ');
									?>
 
									&nbsp;&nbsp;&nbsp;

					        		Auditor :
					        		<?php 
					           			echo form_dropdown('auditor', $auditor_filter_dropdown, 0, 'multiple="multiple" class="form-control sts_auditor_filter" ');
					        		?>

					        		&nbsp;&nbsp;&nbsp;

					        		FYE Month :
					        		<div class="input-group" style="width: 15%;">
				                    	<span class="input-group-addon">
				                    		<i class="far fa-calendar-alt"></i>
				                    	</span>
				                    	<input type="text" class="sts_month_filter form-control" name="confirm_month" data-date-format="mm/yyyy" value="" placeholder="Select Month" autocomplete="off">
									</div>
								</div>

			        		</div>

				        	<hr>

							<table class="table table-bordered table-striped mb-none datatable-stocktake_subsequent" id="datatable-stocktake_subsequent" style="width:100%;">
								<thead style="width:100%">
									<tr>
									<!-- <th><input type="checkbox" name="selectall" class="selectall"/></th> -->
										<!-- <th style="text-align:center;">No</th> -->
										<th style="height:35px;width:25%;">Client</th>
										<th style="height:35px;width:8%;">FYE Date</th>
										<th style="height:35px;width:15%;">Our PIC</th>
										<th style="height:35px;width:10%;">Last Update</th>
										<th style="height:35px;width:26%;">Remark</th>
										<th style="height:35px;width:8%;">Status</th>
										<th style="height:35px;width:8%;"></th>
									</tr>
								</thead>
								<tbody class="stocktake_subsequent_body">
									<?php 
									
										if($stocktake_subsequent_list)
										{
											foreach($stocktake_subsequent_list as $key=>$each)
								  			{	
								  				$rowAuditorName = "";
								  				foreach ($each->auditor_name as $value) {
								  					$rowAuditorName .= '<tr><td>'.$value.'</td></tr>';
								  				}


								  				// if(count($each->review_point) == 0)
								  				// {
								  				// 	$icon_display = "display:none;";
								  				// }
								  				// else
								  				// {
								  				// 	$icon_display = "";
								  				// }


								  				echo '<tr class="sts_tr">';
								  				// echo '<td></td>';
								  				echo '<input type="hidden" class="sta_id" value="'.$each->id.'" />';
								  				echo '<input type="hidden" class="company_name" value="'.$each->company_name.'" />';
								  				echo '<input type="hidden" class="company_code" value="'. $each->company_code .'" />';
								  				echo '<td style="width:25%;" id="desc'.$each->id.'">
										  				<a href="javascript:void(0)" onclick="edit_subsequent('.$each->id.')" class="pointer mb-sm mt-sm mr-sm">'.$each->company_name.'</a>
										  				<div class="review_point_icon">
							  					          <a href="javascript:void(0)" onclick="edit_subsequent('.$each->id.')" class="rp view_rp"><img src="'.base_url().'img/R_icon2.png"  style="width:25px;height:25px;"></a>
							  					          <a href="javascript:void(0)" onclick="edit_subsequent('.$each->id.')" class="cleared_rp view_rp"><img src="'.base_url().'img/R_strike_icon2.png"  style="width:25px;height:25px;"></a>
							  					        </div>
										  			  </td>';
								  				echo '<td style="width:8%;">'.$each->fye_date.'</td>';
								  				echo '<td style="width:15%;">'.($each->all_auditor_id?"<table class='table table-bordered table-striped mb-none'>".$rowAuditorName."</table>":"<b>Not Arranged</b>").'</td>';
								  				echo '<td style="width:10%;">'.$each->updated_at .'</td>';
								  				echo '<td style="width:26%;">'.$each->remark.'</td>';
								  				echo '<td style="width:8%;">'.$each->status_text.'</td>';
								  				echo '<td style="width:8%;">
								  						<input type="hidden" class="sta_id" value="'. $each->id .'" />';
								  						if($each->status == 3)
										  				{
										  					echo '<button type="button" class="caf_btn btn btn_blue" onclick=subsequent_caf(this) style="margin:4px;">CAF</button>';
										  				}
										  				else
										  				{
										  					echo '<button disabled type="button" class="caf_btn btn btn_blue" onclick=subsequent_caf(this) style="margin:4px;">CAF</button>';
										  				}
								  						// <button type="button" class="btn btn_blue" onclick=subsequent_caf(this) style="margin-bottom:5px;margin-right:5px;margin-left:5px;">CAF</button>
								  				echo '</td>';
												echo '</tr>';
								  			}
										}
										
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</section>

<!-- Shift to subsequent Pop up -->
<div id="subsequent_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  data-backdrop="static" style="margin-top:150px;">
	<div class="modal-dialog" >
		<div class="modal-content">
			<header class="panel-heading">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h2 class="panel-title">Upload</h2>
			</header>

	
			<form id="subsequent_submit" enctype="multipart/form-data">
				<input type="hidden" class="arrangement_id" name="arrangement_id" value="" />
				<div class="panel-body">
					<div class="col-md-12">
						<table class="table table-bordered table-striped table-condensed mb-none" id="pic_table">
							<tr>
								<th style="width: 40%;"><p id="role">Stock take completed?</p></th>
								<td style="width:60%">
									<div class="input-group dropdown_fin_period" style="width: 100%;">
										<?php
											echo form_dropdown('sts_status', $sts_status_dropdown, '', 'class="sts_status" style="width:100%;"');
										?>
							        </div>
								</td>
							</tr>
							<tr>
								<th style="width: 40%;"><p id="role">Remark: </p></th>
								<td style="width:60%">
									<div style="width: 100%;">
										<input type="text" name="remark" class='form-control remark' />
							        </div>
								</td>
							</tr>
							<tr>
								<th style="width: 40%;"><p id="role">Upload</p></th>
								<td style="width:60%">
									<input type="file" id="attachment" name="attachment[]" class='upload_file form-control' style="display:none;" multiple />
									<label for='attachment' class='btn btn-primary'>Browse</label>
									<br/><span class="file_name"></span>
								</td>
							</tr>
						</table>

					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" name="saveSubsequent" id="saveSubsequent">Save</button>
					<input type="button" class="btn btn-default ba_upload_cancel" data-dismiss="modal" name="" value="Close">
				</div>
			</form>
		
		</div>
	</div>
</div>

<!-- Edit subsequent Pop up -->
<div id="edit_subsequent_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  data-backdrop="static" >
	<div class="modal-dialog" >
		<div class="modal-content">
			<header class="panel-heading">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h2 class="panel-title">Upload</h2>
			</header>

	
			<form id="edit_subsequent_submit" enctype="multipart/form-data">
				<input type="hidden" class="arrangement_id" name="arrangement_id" value="" />
				<div class="panel-body">
					<div class="col-md-12">
						<table id="subsequent_edit_table">
							<tr valign="top">
								<td style="width:65%;">
									<table class="table table-bordered table-striped table-condensed mb-none" id="pic_table">
										<tr>
											<th style="width: 50%;"><p id="role">Stock take completed?</p></th>
											<td style="width:50%">
												<div class="input-group dropdown_fin_period" style="width: 100%;">
													<?php
														echo form_dropdown('edit_sts_status', $sts_status_dropdown, '', 'class="edit_sts_status" style="width:100%;"');
													?>
										        </div>
											</td>
										</tr>
										<tr>
											<th style="width: 50%;"><p id="role">Remark: </p></th>
											<td style="width:50%">
												<div style="width: 100%;">
													<input type="text" name="edit_remark" class='form-control edit_remark' />
										        </div>
											</td>
										</tr>
										<tr>
											<th style="width: 50%;"><p id="role">Upload</p></th>
											<td style="width:50%">
												<input type="file" id="edit_attachment_btn" name="edit_attachment[]" class='upload_file form-control' style="display:none;" multiple />
												<label for='edit_attachment_btn' class='btn btn-primary'>Browse</label>
												<br/><span class="file_name"></span>
											</td>
										</tr>
										<tr>
										<th style="width:50%;"><p id="role">Last Update</p></th>
											<td style="width:50%">
												<button type="button" class="btn btn-primary edit_last_update" name="edit_last_update" id="edit_last_update" value="">Update</button>
												<input type="hidden" class="edit_last_update" name="edit_last_update">
												<br/><span class="edit_last_update_span"></span>
											</td>
										</tr>
									</table>
								</td>
								<td style="width:35%;">
									<div style="margin-left:20px">
										<h5>Attachment</h5>
										<div id="attachment_table_wrapper">
											<table class="table table-bordered" id="edit_attachment_table">
												<tr>
													<th style="width: 80%;">Example</th>
													<td style="width:20%">
														<button type="button" class="btn btn-primary" name="edit_delete_attachment" id="edit_delete_attachment">Delete</button>
													</td>
												</tr>
											</table>
										</div>
									</div>
								</td>

							</tr>
						

						</table>

					</div>
					<hr/>

					<div class="col-md-12" id="review_point_section" style="margin-top:10px;">
						<div class="panel panel-default" style="padding:0px;">
					        
					        <div class="panel-body">
					        	<div style="margin-bottom:15px;height: 32px;display: table;width: 100%;">
					        		<span style="vertical-align: middle;display: table-cell;"><b>REVIEW POINTS</b></span>
					        		<!-- <span style="vertical-align: middle;display: table-cell;"><a href="javascript:void(0)" id="hide_rp_btn" class="btn btn-default pull-right">Hide review points</a></span> -->
					        	</div>
					        	<div class="form-inline" style="width:100%;margin-bottom:20px;" colspan=5>

					           		Cleared :
					        		<select id="search_cleared" class="form-control sts_rp_cleared_filter">
					        		  <option value="">All</option>
									  <option value="1">Cleared</option>
									  <option value="0">Uncleared</option>
									</select>

				        		</div>
					        	
		                        <div class="table" style="display: table;width: 100%;" id="st_rp_table">
		                            <thead>
		                            	
		                                <div class="tr" style="width:100%">
		                                    <!--       <div class="th" style="text-align: center;width:8%">No</div> -->
		                                    <div class="th" id="id_point" style="text-align: center;width:32%">Points</div>
		                                    <div class="th" id="id_response" style="text-align: center;width:32%">Response</div>
		                                    <div class="th" id="id_raisedby" style="text-align: center;width:20%">Points raised by</div>
		                                    <div class="th" style="text-align: right;width:16%">
		                                    	<a class="amber" id="add_rp_line" href="javascript:void(0);" onclick="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add Review Point" >
			                                    	<i class="fa fa-plus-circle amber" style="font-size:16px;"></i> Add Line
			                                    </a>
			                                </div>
		                                    
		                                </div>
		                                
		                                
		                            </thead>
		                            <div class="tbody" id="review_point_info">
		                                

		                            </div>
		                            
		                        </div>
		                           
					        </div>
				      	</div>
					</div>
				</div>


				<div class="modal-footer">
					<button type="button" class="btn btn-primary" name="saveSubsequent" id="saveEditSubsequent">Save</button>
					<input type="button" class="btn btn-default ba_upload_cancel" data-dismiss="modal" name="" value="Close">
				</div>
			</form>
		
		</div>
	</div>
</div>

<!-- File to CAF Pop up -->
<div id="subsequent_caf_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  data-backdrop="static" style="margin-top:150px;">
	<div class="modal-dialog" >
		<div class="modal-content">
			<header class="panel-heading">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h2 class="panel-title">Upload</h2>
			</header>
	
			<div class="panel-body">
				<div class="col-md-12">
					<table class="table table-bordered table-striped table-condensed mb-none" id="pic_table">
						<tr class="tr_pic">
							<th style="width: 30%;"><p id="role">Financial period</p></th>
							<td style="width:70%">
								<div class="input-group dropdown_fin_period" style="width: 100%;">
									<select id="fin_period" class="form-control fin_period" style="width: 100%;" name="fin_period" required>
						                <option value="">Select Financial Period</option>
						            </select>
						        </div>
							</td>
						</tr>
						<tr class="tr_pic">
							<th style="width: 30%;"><p id="role">Index section</p></th>
							<td style="width:70%">
								<div class="input-group dropdown_index" style="width: 100%;">
									<select id="index_sec" class="form-control index_sec" style="width: 100%;" name="index_sec" required>
						                    <option value="">Select Index Section</option>
						            </select>
						        </div>
							</td>
						</tr>
						<tr class="tr_pic">
							<th style="width: 30%;"><p id="role">Index No.</p></th>
							<td style="width:70%">
								<input type="number" name="index_no" class='index_no form-control' />
							</td>
						</tr>
<!-- 						<tr class="tr_pic">
							<th style="width:30%;"><p id="role">Name</p></th>
							<td style="width:70%">
								<input type="text" name="name" class='name form-control' />
							</td>
						</tr> -->
					</table>

				</div>
			</div>
			<div class="modal-footer">
				
				<input type="button" class="btn btn-default ba_upload_cancel" data-dismiss="modal" name="" value="Close">
			</div>
		
		</div>
	</div>
</div>


<div class="loading" id='loadingMessage' style='display:none;'>Loading&#8230;</div>

<script type="text/javascript">
	var pathArray = location.href.split( '/' );
	var protocol = pathArray[0];
	var host = pathArray[2];
	var folder = pathArray[3];
	var review_point_info = "";
	var active_tab = <?php echo json_encode(isset($active_tab)?$active_tab:"st_arrangement") ?>;
	// var auditor_list = <?php echo json_encode(isset($auditor_list)?$auditor_list:"") ?>;
	var bank_confirm_setting = <?php echo json_encode(isset($bank_confirm_setting)?$bank_confirm_setting:"") ?>;
	var stocktake_subsequent_list = <?php echo json_encode(isset($stocktake_subsequent_list)?$stocktake_subsequent_list:"") ?>;
	var delete_sta_url = "<?php echo site_url('stocktake/delete_sta'); ?>";
	var add_rp_info_url = "<?= base_url();?>stocktake/add_rp_info";
	var filter_review_points_url = "<?= base_url();?>stocktake/filter_review_points";
	var check_cleared_points_url = "<?= base_url();?>stocktake/check_cleared_points";
	var clear_rp_url = "<?= base_url();?>stocktake/clear_rp";
	var delete_review_point_url = "<?= base_url();?>stocktake/delete_review_point";
	var delete_subsequent_doc_url = "<?= base_url();?>stocktake/delete_subsequent_doc";
	var php_base_url = "<?= base_url();?>";


	// var stocktake_arrangement_list = <?php echo json_encode(isset($stocktake_arrangement_list)?$stocktake_arrangement_list:"") ?>;
	
	// console.log(edit_stocktake_arrangement);
	
	// var type_of_leave_list = <?php echo json_encode(isset($type_of_leave_list)?$type_of_leave_list:"") ?>;
	// var block_leave_list = <?php echo json_encode(isset($block_leave_list)?$block_leave_list:"") ?>;
	// var holiday_list = <?php echo json_encode(isset($holiday_list)?$holiday_list:"") ?>;
	var base_url = window.location.origin;  
	var pv_index_tab_aktif;
	var startDate = new Date();
	

	$('.sta_month_filter').datepicker({
	    autoclose: true,
	    minViewMode: 1,
	    format: 'MM yyyy'
	});

	$('.sta_auditor_filter').multiselect({
	    allSelectedText: 'All',
	    enableFiltering: true,
	    enableCaseInsensitiveFiltering: true,
	    maxHeight: 200,
	    includeSelectAllOption: true,
	    buttonWidth: 200
	});

	$(".sta_auditor_filter").multiselect('selectAll', false);
    $(".sta_auditor_filter").multiselect('updateButtonText');
    $('.sta_auditor_filter').val('multiselect-all');
	$(".sta_auditor_filter").multiselect("refresh");

	$('.sts_month_filter').datepicker({
	    autoclose: true,
	    minViewMode: 1,
	    format: 'MM yyyy'
	});

	$('.sts_auditor_filter').multiselect({
	    allSelectedText: 'All',
	    enableFiltering: true,
	    enableCaseInsensitiveFiltering: true,
	    maxHeight: 200,
	    includeSelectAllOption: true,
	    buttonWidth: 200
	});

	$(".sts_auditor_filter").multiselect('selectAll', false);
    $(".sts_auditor_filter").multiselect('updateButtonText');
    $('.sts_auditor_filter').val('multiselect-all');
	$(".sts_auditor_filter").multiselect("refresh");

	
	

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

	    this.getFinPeriod = function(company_code=null) {
	        var url = base_url+"/"+folder+"/"+'bank/getFinPeriod';
	        //console.log(url);
	        var method = "get";
	        var data = {"company_code": company_code};
	        $('.fin_period').find("option:eq(0)").html("Please wait..");
	        call.send(data, url, method, function(data) {
	            //console.log(data);
	            $('.fin_period').find("option:eq(0)").html("Select Financial Period");
	            // console.log(data);
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);
	                    if(data.selected_pic_name != null && key == data.selected_pic_name)
	                    {

	                        option.attr('selected', 'selected');
	                        // $('.pic_name').attr('disabled', true);
	                    }
	                    // console.log(option);
	                    $('.fin_period').append(option);
	                });
	                $('#fin_period').select2();
	                //$(".nationality").prop("disabled",false);
	            }
	            else{
	                alert(data.msg);
	            }
	        }); 
	    };

	}

	// $(function() {
	//     var cn = new Client();
	//     cn.getPicName();
	// });
	var t_sa;
	$(document).ready(function () {
	    // $('#datatable-confirmation').DataTable( {
	    // 	"order": []
	    // });


	    var t_d = $('#datatable-stocktake_setting').DataTable( {
	    	"order": [3, 'desc']
	    });

	    t_d.on( 'order.dt search.dt', function () {
	        t_d.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	            cell.innerHTML = i+1;
	        } );
	    } ).draw();

	    // var t_sa = $('#datatable-stocktake_arrangement').DataTable( {
	    // 	order: [[1, 'desc']],
	    //     rowGroup: {
	    //         dataSrc: 2
	    //     }
	    // });

	    t_sa = $('#datatable-stocktake_arrangement').DataTable({
			//orderFixed: [0, 'asc'],
			// 'data': stocktake_arrangement_data
		
				  
			'rowsGroup': [0,1,7]
			// orderFixed: [1, 'asc'],
	  //       rowGroup: {
	  //           dataSrc: 1
	  //       }
	    });

	    t_ss = $('#datatable-stocktake_subsequent').DataTable({
		
	    });
	    
	    

	    // t_sa.on( 'order.dt search.dt', function () {
	    //     t_sa.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	    //         cell.innerHTML = i+1;
	    //     } );
	    // } ).draw();


	    
	});

	$(".sts_status").select2();
	$(".office").select2();
	$(".department").select2();

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

	function check_cleared_points(sta_id)
	{
	    $.ajax({
	        type: "POST",
	        url: check_cleared_points_url,
	        data: '&sta_id=' + sta_id,
	        async: false,
	        dataType: "json",
	        success: function(data){
	            if (data.Status === 1) {
	                if(data.cleared == "cleared")
	                {
	                    $("#desc"+ sta_id).find(".cleared_rp").show();
	                    $("#desc"+ sta_id).find(".rp").hide();
	                }
	                else if(data.cleared == "uncleared")
	                {
	                	$("#desc"+ sta_id).find(".rp").show();
	                    $("#desc"+ sta_id).find(".cleared_rp").hide();
	                }
	                else if(data.cleared == "no_rp")
	                {
	                	$("#desc"+ sta_id).find(".rp").hide();
	                    $("#desc"+ sta_id).find(".cleared_rp").hide();
	                }
	            }


	            // $.each(data, function(key, val) {
	            //     var option = $('<option />');
	            //     option.attr('value', key).text(val);
	            //     $('#form'+$count_review_point_info).find(".paf_index").append(option);
	            //     $('#paf_index'+$count_review_point_info).select2();
	          
	            // });

	            //$('#form'+$count_family_info).find(".relationship"+$count_family_info).select2();
	        }
	    });
	}


	for (var j = 0; j < stocktake_subsequent_list.length; j++){
		check_cleared_points(stocktake_subsequent_list[j]['id']);
	}


	
</script>

<!-- own script -->
<script src="<?= base_url()?>application/modules/stocktake/js/stocktake.js" charset="utf-8"></script>