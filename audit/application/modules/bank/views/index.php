<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" />
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<!-- <script src="<?= base_url() ?>node_modules/bootstrap-fileinput/js/fileinput.min.js"></script> -->
<script src="<?= base_url() ?>application/js/fileinput.js"></script>
<link rel="stylesheet" href="<?=base_url()?>node_modules/bootstrap-fileinput/css/fileinput.min.css" />

<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>node_modules/sweetalert2/dist/sweetalert2.min.css">
<script src="<?= base_url() ?>node_modules/sweetalert2/dist/sweetalert2.min.js"></script>

<!-- jquery-ui -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css"> -->

<!-- datetime picker -->
<link rel="stylesheet" href="<?= base_url() ?>node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<script src="<?= base_url() ?>node_modules/moment/moment.js"></script>
<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootbox/bootbox.min.css" /> -->

<style type="text/css">
	button:disabled,
	button[disabled]{
	 
	  background-color: #A9A9A9;

	}
</style>

<section role="main" class="content_section" style="margin-left:0;">
	<header class="panel-heading">
		<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
			<div class="panel-actions">
				<a class="create_bank_authorisation amber" href="<?= base_url();?>bank/add_bank_auth" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;display:none;" data-original-title="Create Bank Authorisation" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add Bank Authorisation</a>
				<a class="create_bank_confirmation amber" href="<?= base_url();?>bank/add_bank_confirm" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;display:none;" data-original-title="Create Bank Confirmation" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add Bank Confirmation</a>
			</div>
			<h2></h2>
		<?php } ?>
	</header>
	<section class="panel" style="margin-top: 0px;">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="tabs">				
					<ul class="nav nav-tabs nav-justify">
						<li class="active check_state" data-information="bank_confirm">
							<a href="#w2-bank_confirm" data-toggle="tab" class="text-center">
								<!-- <span class="badge hidden-xs">5</span> -->
								Confirmation
							</a>
						</li>
						<?php if($Admin || $Manager) { ?>
							<li class="check_state" data-information="bank_confirm_setting">
								<a href="#w2-bank_confirm_setting" data-toggle="tab" class="text-center">
									<!-- <span class="badge hidden-xs">4</span> -->
									Scheduling
								</a>
							</li>
						<?php } ?>
					
						<li class="check_state" data-information="bank_auth">
							<a href="#w2-bank_auth" data-toggle="tab" class="text-center">
								<!-- <span class="badge hidden-xs">2</span> -->
								Authorization - Active
							</a>
						</li>
						<li class="check_state" data-information="bank_auth_deactive">
							<a href="#w2-bank_auth_deactive" data-toggle="tab" class="text-center">
								<!-- <span class="badge hidden-xs">3</span> -->
								Authorization - Deactivated
							</a>
						</li>
						<li class="check_state" data-information="bank_list">
							<a href="#w2-bank_list" data-toggle="tab" class="text-center">
								<!-- <span class="badge hidden-xs">1</span> -->
								Banks
							</a>
						</li>
					</ul>
					<div class="tab-content clearfix">
						<div id="w2-bank_list" class="tab-pane">
							<form id="bank_list_submit">
								<input type="hidden" name="bank_list_id" class="bank_list_id" value="">
								<div class="col-md-12">
									<div class="form-group">
						                <div style="width: 100%;">
						                    <div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Bank Name for user: </label>
						                    </div>
						                    <div style="width: 45%;float: left;">
						                        <div style="width:100%">
						                        	<input type="text" id="bank_name_for_user" class="form-control" name="bank_name_for_user" required>
						                        </div>
						                    </div>
						                </div>
						            </div>
									<div class="form-group">
										<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Bank Name:</label>
						                    </div>
						                	<div style="width: 45%;float: left;">
						                        <div style="width:100%">
						                        	<input type="text" id="bank_name" class="form-control" name="bank_name" required>
						                        </div>
						                    </div>
						                </div>
									</div>
									<div class="form-group">
										<div style="width: 100%;">
											<div style="width: 25%;float:left;margin-right: 20px;">
							                        <label>Bank Address: </label>
							                </div>
							                <div style="width: 50%;float: left;">
					                        	<div style="width: 18%;float:left;">
													<label>Address Line 1 :</label>
												</div>
												<div style="width: 72%;float:left;">
													<div class="" style="width: 100%;" >
														<input type="text" class="form-control" id="add_line1" name="add_line1" value="" required>
													</div>
												</div>
						                    </div>
										</div>
									</div>
									<div class="form-group">
										<div style="width: 100%;">
											<div style="width: 25%;float:left;margin-right: 20px;">
							                        <label></label>
							                </div>
							                <div style="width: 50%;float: left;">
					                        	<div style="width: 18%;float:left;">
													<label>Address Line 2 :</label>
												</div>
												<div style="width: 72%;float:left;">
													<div class="" style="width: 100%;" >
														<input type="text" class="form-control" id="add_line2" name="add_line2" value="" required>
													</div>
												</div>
						                    </div>
										</div>
									</div>
									<div class="form-group">
										<div style="width: 100%;">
											<div style="width: 25%;float:left;margin-right: 20px;">
							                        <label></label>
							                </div>
							                <div style="width: 50%;float: left;">
					                        	<div style="width: 18%;float:left;">
													<label>Address Line 3 :</label>
												</div>
												<div style="width: 72%;float:left;">
													<div class="" style="width: 100%;" >
														<input type="text" class="form-control" id="add_line3" name="add_line3" value="" required>
													</div>
												</div>
						                    </div>
										</div>
									</div>

									<!-- <div class="form-group">
										<div style="width: 100%;">
											<div style="width: 25%;float:left;margin-right: 20px;">
							                        <label></label>
							                </div>
							                <div style="width: 50%;float: left;">
					                        	<div style="width: 25%;float:left;">
													<label>Building Name :</label>
												</div>
												<div style="width: 65%;float:left;">
													<div class="" style="width: 100%;" >
														<input type="text" style="text-transform:uppercase" class="form-control" id="building_name" name="building_name" value="<?=isset($client->building_name)?$client->building_name:"" ?>">
													</div>
													<div id="form_postal_code"></div>
												</div>
						                    </div>
										</div>
									</div>
									<div class="form-group">
										<div style="width: 100%;">
											<div style="width: 25%;float:left;margin-right: 20px;">
							                        <label></label>
							                </div>
							                <div style="width: 50%;float: left;">
					                        	<div style="width: 25%;float:left;">
													<label>Unit No :</label>
												</div>
												<div style="width: 65%;float:left;">
													<div class="" style="width: 100%;" >
														<input style="width: 15%; float: left; margin-right: 10px; text-transform:uppercase;" type="text" class="form-control" id="unit_no1" name="unit_no1" value="<?=isset($client->unit_no1)?$client->unit_no1:"" ?>" maxlength="3">
														<label style="float: left; margin-right: 10px;" >-</label>
														<input style="width: 25%; text-transform:uppercase;" type="text" class="form-control" id="unit_no2" name="unit_no2" value="<?=isset($client->unit_no2)?$client->unit_no2:"" ?>" maxlength="10">
													</div>
													<div id="form_postal_code"></div>
												</div>
						                    </div>
										</div>
									</div>-->
									
									<div class="form-group">
						                <div style="width: 100%;">
						                    <div style="width: 25%;float:left;margin-right: 20px;">
						                        <label></label>
						                    </div>
						                    <div style="float:right;margin-bottom:5px;">
						                        <div class="input-group">
						                        	<button class="btn btn_blue" type="submit">Save</button>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <hr>
						            <table class="table table-bordered table-striped mb-none datatable-bank" id="datatable-bank" style="width:100%">
										<thead>
											<tr style="background-color:white;">
												<th class="text-left">Bank Name(for user)</th>
												<th class="text-left">Bank Name</th>
												<th class="text-left">Bank Address</th>
												<th class="text-left"></th>
											</tr>
										</thead>
										<tbody>
											<?php 
												if($bank_list)
												{
													foreach($bank_list as $bank)
										  			{
										  				echo '<tr>';
										  				echo '<td><a href="javascript:void(0)" class="edit_bank" data-id="'.$bank->id.'">'.$bank->bank_name_for_user.'</a></td>';
										  				echo '<td>'.$bank->bank_name.'</td>';
										  				echo '<td>'.$bank->add_line1.'<br/>'.$bank->add_line2.'<br/>'.$bank->add_line3.'</td>';
										  				echo '<td style="text-align:center;"><input type="hidden" class="bank_id" value="'. $bank->id .'" /><button type="button" class="btn btn_blue" onclick=delete_bank(this)>Delete</button></td>';
										  				echo '</tr>';
										  			}
												}
												
											?>
										</tbody>
									</table>
								</div>
							</form>
						</div>
						<div id="w2-bank_auth" class="tab-pane">
							<table class="table table-bordered table-striped mb-none" id="datatable-auth" style="width:100%">
								<thead style="width:100%">
									<tr>
									<!-- <th><input type="checkbox" name="selectall" class="selectall"/></th> -->
									<th style="text-align:center;">No</th>
									<th style="text-align:center;">Client</th>
									<th style="text-align:center;">Bank Name</th>
									<th style="text-align:center;">Authorization Date</th>
									<th style="text-align:center;">Status</th>
									<th style="text-align:center;"></th>
								</tr>
								</thead>
									<tbody id="bank_auth_body">
										<?php 
												if($bank_auth)
												{
													foreach($bank_auth as $key=>$auth)
										  			{
										  				echo '<tr>';
										  				echo '<td style="width:2.5%;>" "</td>';
										  				echo '<td style="width:22.5%;" class="company_name"><a href="bank/edit_bank_auth/'.$auth->id.'" class="pointer mb-sm mt-sm mr-sm">'.$auth->company_name.'</a></td>';
										  				echo '<td style="width:28%;" class="bank_name">'.$auth->bank_name.'</td>';
										  				echo '<td style="width:14%;">'.$auth->auth_date.'</td>';
										  				// echo '<td style="width:15%;"><div class="input-group" style="width: 100%;">'.form_dropdown('auth_status', $status_dropdown, isset($auth->auth_status)?$auth->auth_status:'', 'class="auth_status" style="width:100%;" onchange=change_auth_status(this)').'<input type="hidden" class="auth_id" value="'. $auth->id .'" /></div></td>';
										  				echo '<td style="width:15;"><div class="input-group" style="width: 100%;">'.form_dropdown('auth_status', $status_dropdown, isset($auth->auth_status)?$auth->auth_status:'', 'class="auth_status" style="width:100%;"').'<input type="hidden" class="auth_id" value="'. $auth->id .'" /></div></td>';
										  				echo '<td style="text-align:left;width:18%;"><input type="hidden" class="auth_id" value="'. $auth->id .'" />
										  						<input type="hidden" class="auth_id" value="'. $auth->id .'" />
										  						<button type="button" class="btn btn_blue ba_upload_btn ba_upload_btn" onclick=open_ba_doc_modal(this) style="margin:4px;">Upload</button>
										  						<button type="button" class="btn btn_blue" onclick=generate_auth(this) style="margin:4px">PDF</button>';
										  				if($auth->auth_status == 2)
										  				{
										  					echo '<button type="button" class="btn btn_blue" onclick=open_paf(this) style="margin:4px;">PAF</button>';
										  				}
										  				else
										  				{
										  					echo '<button disabled type="button" class="btn btn_blue" style="margin:4px;">PAF</button>';
										  				}
										  				echo	'<button type="button" class="btn btn_blue" onclick=update_auth(this,0) style="margin:4px;">Deactivate</button>
										  						<button type="button" class="btn btn_blue" style="margin:4px;" onclick=delete_bank_auth(this)>Delete</button></td>';
										  				echo '</tr>';
										  			}
												}
												
											?>
									</tbody>
								</table>
						</div>
						<div id="w2-bank_auth_deactive" class="tab-pane">
							<table class="table table-bordered table-striped mb-none" id="datatable-auth-deactive" style="width:100%">
								<thead style="width:100%">
									<tr>
									<!-- <th><input type="checkbox" name="selectall" class="selectall"/></th> -->
									<th style="text-align:center;">No</th>
									<th style="text-align:center;">Client</th>
									<th style="text-align:center;">Bank Name</th>
									<th style="text-align:center;">Authorization Date</th>
									<!-- <th style="text-align:center;">Status</th> -->
									<th style="text-align:center;"></th>
								</tr>
								</thead>
									<tbody id="bank_auth_deactivate_body">
										<?php 
												if($bank_auth_deactivate)
												{
													foreach($bank_auth_deactivate as $key=>$auth)
										  			{
										  				echo '<tr>';
										  				echo '<td style="width:2.5%;>" "</td>';
										  				echo '<td style="width:30%;">'.$auth->company_name.'</td>';
										  				echo '<td style="width:30%;">'.$auth->bank_name.'</td>';
										  				echo '<td style="width:15%;">'.$auth->auth_date.'</td>';
										  				// echo '<td style="width:15%;"><div class="input-group" style="width: 100%;">'.form_dropdown('auth_status', $status_dropdown, isset($auth->auth_status)?$auth->auth_status:'', 'class="auth_status" style="width:100%;" onchange=change_auth_status(this)').'<input type="hidden" class="auth_id" value="'. $auth->id .'" /></div></td>';
										  				echo '<td style="text-align:center;width:7.5%;"><input type="hidden" class="auth_id" value="'. $auth->id .'" /><button type="button" class="btn btn_blue" onclick=update_auth(this,1) style="margin-bottom:5px;">Activate</button><button type="button" class="btn btn_blue" onclick=delete_bank_auth(this)>Delete</button></td>';
										  				echo '</tr>';
										  			}
												}
												
											?>
									</tbody>
								</table>
						</div>
						<div id="w2-bank_confirm_setting" class="tab-pane">
							<form id="bank_confirm_setting_submit" autocomplete="off">
								<input type="hidden" name="bank_confirm_setting_list_id" class="bank_confirm_setting_list_id" value="">
								<div class="col-md-12">
									<div class="form-group">
						                <div style="width: 100%;">
						                    <div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>FYE Month: </label>
						                    </div>
						                    <div class="input-group" id="confirm_month" style="width: 40%;">
						                    	<span class="input-group-addon">
						                    		<i class="far fa-calendar-alt"></i>
						                    	</span>
						                    	<input readonly style="background-color:white;" type="text" class="confirm_month form-control" name="confirm_month" data-date-format="MMMM YYYY" required value="">
											</div>
						        
						                </div>
						            </div>
									<div class="form-group">
										<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Person in charge:</label>
						                    </div>
						                	<div style="width: 40%;float: left;">
						                        <div class="input-group dropdown_pic_name" style="width: 100%;">
													<select id="pic_name" class="form-control pic_name" style="width: 100%;" name="pic_name" required>
										                    <option value="">Select PIC Name</option>
										            </select>
										        </div>
										        <!-- <div class="input_pic_name" style="width: 100%; display: none">
													<input type="text" id="text_pic_name" class="form-control text_pic_name" style="width: 100%;" name="pic_bank_name">
										        </div> -->
										    </div>
						                </div>
									</div>
		
			
									
									<div class="form-group">
						                <div style="width: 100%;">
						                    <div style="width: 25%;float:left;margin-right: 20px;">
						                        <label></label>
						                    </div>
						                    <div style="float:right;margin-bottom:5px;">
						                        <div class="input-group">
						                        	<a class="btn btn-default" onclick="reset_setting()" type="button">Cancel</a>
						                       		<span style="padding-left: 10px;"></span>
						                        	<button class="btn btn_blue" type="submit">Save</button>
						                        </div>
						                    </div>
						                </div>
						            </div>
						            <hr>
						            <table class="table table-bordered table-striped mb-none datatable-confirm-setting" id="datatable-confirm-setting" style="width:100%">
										<thead>
											<tr style="background-color:white;">
												<th class="text-left">FYE Month</th>
												<th class="text-left">PIC</th>
												<td class="text-left">
													
												</td>
											</tr>
										</thead>
										<tbody>
											<?php 
												if($bank_confirm_setting)
												{
													foreach($bank_confirm_setting as $bank)
										  			{
										  				$mydate = strtotime($bank->confirm_month);
														$newformat = date('MM yyyy',$mydate);
										  				echo '<tr>';
										  				echo '<td data-sort="'. $mydate .'"><a href="javascript:void(0)" class="edit_bank_confirm_setting" data-id="'.$bank->setting_id.'" width="30%">'.$bank->confirm_month.'</a></td>';
										  				echo '<td class="" width="60%">'.$bank->name.'</td>';
										  				echo '<td style="text-align:center;" width="10%"><input type="hidden" class="bank_confirm_setting_id" value="'. $bank->setting_id .'" /><button type="button" style="margin:4px;" class="btn btn_blue" onclick=delete_confirm_setting(this)>Delete</button></td>';
										  				echo '</tr>';
										  			}
												}
												
											?>
										</tbody>
									</table>
								</div>
							</form>
						</div>
						<div id="w2-bank_confirm" class="tab-pane active">
							<div class="row form-inline" style="text-align: left;padding-left: 15px;padding-right: 15px;">
								<div style="text-align: left;">
									<div style="width: 200px; float: left;padding-left:10px;">
						        		<label class="control-label">FYE Month </label>
						        		<div class="input-group" style="width: 90%;">
					                    	<span class="input-group-addon">
					                    		<i class="far fa-calendar-alt"></i>
					                    	</span>
					                    	<input type="text" class="bc_month_filter form-control" name="confirm_month" data-date-format="mm/yyyy" value="" placeholder="Select Month" autocomplete="off">
										</div>
									</div>
					        		
					        		&nbsp;&nbsp;&nbsp;
					        		<div style="width: 200px; float: left;padding-left:10px;">
						        		<label class="control-label">Status </label>
						        		<?php 
						        			echo form_dropdown('status', $status_dropdown_filter, isset($status_dropdown)?0:'', 'class="form-control bc_status_filter" style="width:85%;" ');
						        		?>
					        		</div>
				        		</div>
			        		</div>

				        	<br>
										
							<div class="col-sm-12 col-md-12">
								<table class="table table-bordered table-striped mb-none datatable-confirmation" id="datatable-confirmation" style="width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left">No.</th>
											<th class="text-left">Client Name</th>
											<th class="text-left">Bank Name</th>
											<th class="text-left">Firm Name</th>
											<th class="text-left">Financial Year End</th>
											<th class="text-left">Sent On</th>
											<th class="text-left">Status</th>
											<th class="text-left"></th>
										
						
										</tr>
									</thead>
									<tbody class="bank_confirm_body">
										<?php 
												if($bank_confirm)
												{
													foreach($bank_confirm as $key=>$confirm)
										  			{
										  				echo '<tr class="bc_tr">';
										  				echo '<input type="hidden" class="company_code" value="'. $confirm->company_code .'" />';
										  				echo '<td style="width:2.5%;>" "</td>';
										  				echo '<td style="width:15%;" class="company_name">'.$confirm->company_name.'</td>';
										  				echo '<td style="width:15%;">'.$confirm->bank_name.'</td>';
										  				echo '<td style="width:15%;">'.$confirm->firm_name.'</td>';
										  				echo '<td style="width:10%;">'.$confirm->fye_date.'</td>';
										  				echo '<td style="width:16%;" data-order="'.$confirm->sent_on_date.'"><div class="input_sent_date" style="width: 100%;">
						    			                        <div class="input-group date datepicker" data-provide="datepicker">
						    			                           	<span class="input-group-addon">
																		<i class="far fa-calendar-alt"></i>
																	</span>
												                    <input type="text" class="form-control confirm_sent_date" name="confirm_sent_date" data-prevdate="'.DateTime::createFromFormat('Y-m-d', $confirm->sent_on_date)->format('d F Y').'" onchange=change_sent_date(this) value="'.DateTime::createFromFormat('Y-m-d', $confirm->sent_on_date)->format('d F Y').'" autocomplete="off" />

												                </div>
												                <input type="hidden" class="confirm_id" value="'. $confirm->id .'" />
													        </div></td>';
										  				echo '<td style="width:14%;"><div class="input-group" style="width: 100%;">'.form_dropdown('confirm_status', $status_dropdown, isset($confirm->confirm_status)?$confirm->confirm_status:'', 'class="confirm_status" style="width:100%;" onchange=change_confirm_status(this)').'<input type="hidden" class="confirm_id" value="'. $confirm->id .'" /></div></td>';
										  				
										  				echo '<td style="text-align:left;width:12.5%;"><input type="hidden" class="confirm_id" value="'. $confirm->id .'" /><button type="button" class="btn btn_blue bc_upload_btn" onclick=open_bc_doc_modal(this) style="margin:4px;">Upload</button><button type="button" class="btn btn_blue" onclick=generate_confirm(this) style="margin:4px;">PDF</button>';
										  				if($confirm->confirm_status == 2)
										  				{
										  					echo '<button type="button" class="btn btn_blue" onclick=open_caf(this) style="margin:4px;">CAF</button>';
										  				}
										  				else
										  				{
										  					echo '<button disabled type="button" class="btn btn_blue" onclick=open_caf(this) style="margin:4px;">CAF</button>';
										  				}
										  				
										  				echo '<button type="button" class="btn btn_blue" onclick=delete_bank_confirm(this) style="margin:4px;">Delete</button></td>';
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
		</div>
	</section>
</section>

<!-- Upload Bank confirm Doc Pop up -->
<div id="upload_bc_doc_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Upload</h2>
			</header>
			<form id="upload_bc_doc">
				<div class="panel-body">
					<div class="col-md-12">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<input type="hidden" name="doc_bc_id" class="doc_bc_id" value="">

							<tr>
								<th>Client</th>
								<td>
							        <div style="width: 80%;">
										<input disabled type="text" class="form-control client_name" name="client_name" value="" style="width: 100%;" id="bc_client" />
							        </div>
								</td>
							</tr>

	                       	<tr>
								<th>Bank Name</th>
								<td>
							        <div style="width: 80%;">
										<input disabled type="text" class="form-control bank_name" name="bank_name" value="" style="width: 100%;" id="bc_bank" />
							        </div>
								</td>
							</tr>

							<tr>
								<th>Document(s)</th>
								<td>
							        <div class="">
    			                        <input id="multiple_bc_doc" name="bc_docs[]" type="file" data-browse-on-zone-click="false" multiple="true">
							        </div>
								</td>
							</tr>

						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn_blue" id="upload_bc_doc_btn">Submit</button>
					<input type="button" class="btn btn-default bc_upload_cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
			</form>
		</div>
	</div>
</div>


<!-- Upload Bank Auth Doc Pop up -->
<div id="upload_ba_doc_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Upload</h2>
			</header>
			<form id="upload_ba_doc">
				<div class="panel-body">
					<div class="col-md-12">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<input type="hidden" name="doc_ba_id" class="doc_ba_id" value="">

							<tr>
								<th>Client</th>
								<td>
							        <div style="width: 80%;">
										<input disabled type="text" class="form-control client_name" name="client_name" value="" style="width: 100%;" id="ba_client" />
							        </div>
								</td>
							</tr>

	                       	<tr>
								<th>Bank Name</th>
								<td>
							        <div style="width: 80%;">
										<input disabled type="text" class="form-control bank_name" name="bank_name" value="" style="width: 100%;" id="ba_bank" />
							        </div>
								</td>
							</tr>

							<tr>
								<th>Document(s)</th>
								<td>
							        <div class="">
    			                        <input id="multiple_ba_doc" name="ba_docs[]" type="file" data-browse-on-zone-click="false" multiple="true">
							        </div>
								</td>
							</tr>

						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn_blue" id="upload_ba_doc_btn">Submit</button>
					<input type="button" class="btn btn-default ba_upload_cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
			</form>
		</div>
	</div>
</div>


<!-- File to CAF Pop up -->
<div id="confirm_caf_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  data-backdrop="static" style="margin-top:150px;">
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
						<tr class="tr_pic">
							<th style="width:30%;"><p id="role">Name</p></th>
							<td style="width:70%">
								<input type="text" name="name" class='name form-control' />
							</td>
						</tr>
					</table>

				</div>
			</div>
			<div class="modal-footer">
				
				<input type="button" class="btn btn-default ba_upload_cancel" data-dismiss="modal" name="" value="Close">
			</div>
		
		</div>
	</div>
</div>

<!-- File to PAF Pop up -->
<div id="auth_paf_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  data-backdrop="static" style="margin-top:150px;">
	<div class="modal-dialog" >
		<div class="modal-content">
			<header class="panel-heading">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h2 class="panel-title">Upload</h2>
			</header>
			<form id="auth_paf_form">
				<input type="hidden" class="bank_auth_id" name="bank_auth_id">
	
				<div class="panel-body">
					<div class="col-md-12">
						<p style='margin:0;'>Are you sure you want to move the authorization to permanent file?</p>
						<p class='bank_name'></p>
					</div>
				</div>
			</form>
			<div class="modal-footer">
				
				<input type="button" class="btn btn-default move_paf_cancel" data-dismiss="modal" name="" value="Cancel">
				<button type="button" class="btn btn_blue" id="move_paf_btn">Move</button>
			</div>
		
		</div>
	</div>
</div>

<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>

<script type="text/javascript">
	var pathArray = location.href.split( '/' );
	var protocol = pathArray[0];
	var host = pathArray[2];
	var folder = pathArray[3];

	var base_url = window.location.origin;  


	var previous_auth = "";
	var previous_auth_text = "";

	var previous_confirm = "";
	var previous_confirm_text = "";

	var initialPreviewArray = []; 
	var initialPreviewConfigArray = [];
	var bank_auth_files = "";
	var selected_move_auth = "";

	var change_bc_status_flag = false;
	var change_ba_status_flag = false;

	var active_tab = <?php echo json_encode(isset($active_tab)?$active_tab:"bank_confirm") ?>;
	var bank_list = <?php echo json_encode(isset($bank_list)?$bank_list:"") ?>;
	var bank_confirm_setting = <?php echo json_encode(isset($bank_confirm_setting)?$bank_confirm_setting:"") ?>;
	var bank_auth_list = <?php echo json_encode(isset($bank_auth)?$bank_auth:"") ?>;
	var bank_confirm_list = <?php echo json_encode(isset($bank_confirm)?$bank_confirm:"") ?>;
	var status_dropdown = <?php echo json_encode(isset($status_dropdown)?$status_dropdown:"") ?>;
	var all_bank_auth_files = <?php echo json_encode(isset($all_bank_auth_files)?$all_bank_auth_files:"") ?>;
	var monthToDisable = <?php echo json_encode(isset($disable_month)?$disable_month:"") ?>;
	
	
	// var type_of_leave_list = <?php echo json_encode(isset($type_of_leave_list)?$type_of_leave_list:"") ?>;
	// var block_leave_list = <?php echo json_encode(isset($block_leave_list)?$block_leave_list:"") ?>;
	// var holiday_list = <?php echo json_encode(isset($holiday_list)?$holiday_list:"") ?>;
	var bank_url = "<?= base_url();?>bank";
	var pv_index_tab_aktif;
	var startDate = new Date();
	// console.log(active_tab);

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
	    
	    var call = new ajaxCall();

	    this.getPicName = function(selected_pic_name=null) {
	    	$('#pic_name').find('option').not(':nth-child(1)').remove();
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

	$(function() {
	    var cn = new Client();
	    cn.getPicName();
	});

	$(document).ready(function () {
	    // $('#datatable-confirmation').DataTable( {
	    // 	"order": []
	    // });

	    var t = $('#datatable-auth').DataTable( {
	    	// "order": []
	    	"order": [[ 3, 'desc' ]]
	    });

	    t.on( 'order.dt search.dt', function () {
	        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	            cell.innerHTML = i+1;
	        } );
	    } ).draw();

	    var t_d = $('#datatable-auth-deactive').DataTable( {
	    	"order": [[ 3, 'desc' ]]
	    	// "order": [[ 3, 'asc' ]]
	    });

	    t_d.on( 'order.dt search.dt', function () {
	        t_d.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	            cell.innerHTML = i+1;
	        } );
	    } ).draw();

	    var t_c = $('#datatable-confirmation').DataTable( {
	    	"order": [[ 5, 'desc' ]]
	    	// "order": [[ 3, 'asc' ]]
	    });

	    t_c.on( 'order.dt search.dt', function () {
	        t_c.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	            cell.innerHTML = i+1;
	        } );
	    } ).draw();

	    
	});

	
	$(".auth_status").select2();

	$(".confirm_status").select2();

	$('.nav li').not('.active').addClass('disabled');

	if(bank_list)
	{
		$('.nav li').removeClass('disabled');
		// active_tab = "bank_confirm";
	}
	else
	{
		active_tab = "bank_list";
		 
		// $('#w2-bank_list').addClass("active");
		// $('li[data-information="bank_list"]').addClass("active");

		// $('#w2-bank_confirm').addClass("disabled");
		// $('li[data-information="bank_confirm"]').addClass("disabled");

		// $('#w2-bank_confirm').removeClass("active");
		// $('li[data-information="bank_confirm"]').removeClass("active");

		$('.disabled').click(function (e) {
	        e.preventDefault();

	        if($(this).hasClass("disabled"))
	        {
	        	return false;
	        }
	        else
	        {
	        	return true;
	        }
	        
		});
	}


</script>

<!-- own script -->
<script src="<?= base_url()?>application/modules/bank/js/bank.js" charset="utf-8"></script>