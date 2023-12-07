<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<link rel="stylesheet" href="<?=base_url()?>application/css/theme-custom.css" />
<link rel="stylesheet" href="<?= base_url()?>application/css/plugin/intlTelInput.css" />
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrapvalidator/dist/css/bootstrapValidator.min.css"/>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/toastr/build/toastr.min.css" />
<link href="<?= base_url() ?>node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" media="all" rel="stylesheet" type="text/css"/>
<!-- <link rel="stylesheet" href="<?=base_url()?>application/css/styles/formValidation.css" /> -->
<script src="<?= base_url()?>node_modules/toastr/build/toastr.min.js" /></script>
<script src="<?= base_url()?>application/js/intlTelInput.js" /></script>
<script src="<?= base_url()?>application/js/bootstrapValidator.min.js" /></script>
<script src="<?= base_url()?>application/js/defaultCountryIp.js" /></script>
<script src="<?= base_url()?>application/js/utils.js" /></script>
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>	
<script src="<?= base_url() ?>node_modules/bootstrap-switch/dist/js/bootstrap-switch.min.js" type="text/javascript"></script>

<script src="<?= base_url() ?>application/js/fileinput.js"></script>
<link rel="stylesheet" href="<?=base_url()?>node_modules/bootstrap-fileinput/css/fileinput.min.css" />

<section role="main" class="content_section" style="margin-left:0;">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			
			<section class="panel">
				<form id="create_resignation_letter_form" autocomplete="off">
					<div class="panel-body">
						<input type="hidden" name="resignation_letter_id"  value="<?=isset($edit_resignation_letter[0]->id)?$edit_resignation_letter[0]->id:''?>">
							<div class="col-md-12">
								<div class="form-group">
					                <div style="width: 100%;">
					                    <div style="width: 20%;float:left;margin-right: 20px;">
					                        <label>Outgoing client: </label>
					                    </div>
					                    <div class="form-group input-group dropdown_client_name" style="width: 45%;">
											<select id="client_name" class="form-control client_name" style="width: 100%;" name="client_name">
								                <option value="">Select Client Name</option>
								                <?php

								                	if(isset($edit_resignation_letter))
								                	{
								                		echo '<option selected value="'.$edit_resignation_letter[0]->company_code.'">'.$edit_resignation_letter[0]->company_name.'</option>';

								                	}
								                ?>
								            </select>
								        </div>
					                </div>
					            </div>
								<div class="form-group">
									<div style="width: 100%;">
					                	<div style="width: 20%;float:left;margin-right: 20px;">
					                        <label>New auditor:</label>
					                    </div>
					                	<div class="form-group input-group dropdown_auditor_name" style="width: 45%;">
											<select id="audit_firm_name" class="form-control audit_firm_name" style="width: 100%;" name="audit_firm_name">
								                <option value="">Select Auditor Name</option>
								            </select>
								        </div>
					                </div>
								</div>
								<div class="form-group">
									<div style="width: 100%;">
					                	<div style="width: 20%;float:left;margin-right: 20px;">
					                        <label>Our firm:</label>
					                    </div>
					                	<div class="form-group input-group dropdown_our_firm_name" style="width: 45%;">
											<select id="our_firm_name" class="form-control our_firm_name" style="width: 100%;" name="our_firm_name">
								                <option value="">Select Our Firm</option>
								            </select>
								        </div>
					                </div>
								</div>
								<div class="form-group">
									<div style="width: 100%;">
					                	<div style="width: 20%;float:left;margin-right: 20px;">
					                        <label>Date of their letter:</label>
					                    </div>
					                	<div class="form-group input-group" style="width: 45%;">
											<div class="input-group" id="fye_date" style="width: 40%;">
												<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="their_date form-control" name="their_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="">
											</div>
								        </div>
					                </div>
								</div>
								<div class="form-group">
									<div style="width: 100%;">
					                	<div style="width: 20%;float:left;margin-right: 20px;">
					                        <label>Date of our letter:</label>
					                    </div>
					                	<div class="form-group input-group" style="width: 45%;">
											<div class="input-group" id="fye_date" style="width: 40%;">
												<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="our_date form-control" name="our_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="">
											</div>
								        </div>
					                </div>
								</div>
								<div class="form-group">
									<div style="width: 100%;">
					                	<div style="width: 20%;float:left;margin-right: 20px;">
					                        <label>Letter from auditor:</label>
					                    </div>
					                	<div class="form-group input-group dropdown_auditor_name" style="width: 45%;">
											<div class="input-group" style="width: 100%;" >
		                                        <input id="resignation_doc" name="resignation_doc" type="file" data-min-file-count="0" data-browse-on-zone-click="false">
		                                    </div>
								        </div>
					                </div>
								</div>
			<!-- 					<div class="form-group">
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
					            </div> -->
					        </div>
					    </div>
				</form>	
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 number text-right">
							<button type="submit" class="btn btn-primary" name="saveLetter" id="saveLetter">Save</button>
							<a href="<?= base_url();?>list_of_auditor" class="btn btn-default">Back</a>
						</div>
					</div>
				</footer>
			</section>
						
		</div>
	</div>
	<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>
<!-- end: page -->
</section>


<script type="text/javascript">

	// var edit_first_letter = <?php echo json_encode(isset($edit_first_letter)?$edit_first_letter:"") ?>;
	var clearance_history_list = <?php echo json_encode(isset($clearance_history_list)?$clearance_history_list:"") ?>;
	var save_resignation_url = "<?php echo site_url('list_of_auditor/save_resignation'); ?>";
	var generate_first_letter_url = "<?php echo site_url('list_of_auditor/generate_first_letter'); ?>";
	var generate_resignation_letter_url = "<?php echo site_url('list_of_auditor/generate_resignation_letter'); ?>";
	var edit_resignation_letter = <?php echo json_encode(isset($edit_resignation_letter)?$edit_resignation_letter:"") ?>;
	var auditor_url = "<?= base_url();?>list_of_auditor";

	var initialPreviewArray = []; 
	var initialPreviewConfigArray = [];

	var base_url = window.location.origin;

	console.log(clearance_history_list);


	// console.log(edit_bank_auth[0]["auth_date"]);


	$("#header_our_firm").removeClass("header_disabled");
	$("#header_manage_user").removeClass("header_disabled");
	$("#header_access_right").removeClass("header_disabled");
	$("#header_user_profile").removeClass("header_disabled");
	$("#header_setting").removeClass("header_disabled");
	$("#header_dashboard").removeClass("header_disabled");
	$("#header_client").removeClass("header_disabled");
	$("#header_person").removeClass("header_disabled");
	$("#header_document").removeClass("header_disabled");
	$("#header_report").removeClass("header_disabled");
	$("#header_billings").addClass("header_disabled");
</script>

<script src="<?= base_url()?>application/modules/list_of_auditor/js/add_resignation.js" charset="utf-8"></script>