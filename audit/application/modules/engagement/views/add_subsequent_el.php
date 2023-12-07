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

<section role="main" class="content_section" style="margin-left:0;">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			
			<div id="w2-engagement_letter" class="panel">
				<form id="subsequent_el_form" style="margin-top: 20px;">
					<input type="hidden" class="form-control company_code" id="company_code" name="company_code" value=""/>
					<input type="hidden" class="form-control subsequent_el_id" id="subsequent_el_id" name="subsequent_el_id" value=""/>
					<!-- <span style="font-size: 1.7rem;padding: 0; margin: 7px 0px 4px 0;">Services</span> -->
					<div class="form-group">
						<label class="col-sm-2" for="w2-DS2">Client Name:</label>
						<div class="col-sm-8">
							<div class="input-group" style="width: 500px;">
								<select id="client_name" class="form-control client_name" style="width: 100%;" name="company_code">
					                <option value="">Select Client Name</option>
					            </select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2" for="w2-DS2">Our previous letter:</label>
						<div class="col-sm-8">
							<div class="input-group" style="width: 200px;">
								<span class="input-group-addon">
									<i class="far fa-calendar-alt"></i>
								</span>
								<input autocomplete="off" type="text" class="form-control valid previous_letter_date" name="previous_letter_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2" for="w2-DS2">Date of new letter:</label>
						<div class="col-sm-8">
							<div class="input-group" style="width: 200px;">
								<span class="input-group-addon">
									<i class="far fa-calendar-alt"></i>
								</span>
								<input autocomplete="off" type="text" class="form-control valid new_letter_date" name="new_letter_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2" for="w2-DS2">FYE Period Start Date: <i class="fas fa-info-circle" style="font-size: 16px;margin-top: 8px;" data-toggle="tooltip" data-trigger="hover" data-original-title="Neeed for ML Quarterly Statement"></i></label>
						<div class="col-sm-2">
							<div class="input-group" style="width: 200px;">
								<span class="input-group-addon">
									<i class="far fa-calendar-alt"></i>
								</span>
								<input autocomplete="off" type="text" class="form-control valid start_fye_date" name="start_fye_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="DD/MM/YYYY">
							</div>
						</div>
					
					</div>
					<div class="form-group">
						<label class="col-sm-2" for="w2-DS2">FYE Date:</label>
						<div class="col-sm-8">
							<div class="input-group" style="width: 200px;">
								<span class="input-group-addon">
									<i class="far fa-calendar-alt"></i>
								</span>
								<input autocomplete="off" type="text" class="form-control valid fye_date" name="fye_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="" placeholder="DD/MM/YYYY">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2" for="w2-username">Director Signing: </label>
						<div class="col-sm-3">
							<input type="text" style="text-transform:uppercase" class="form-control" id="director_signing" name="director_signing" value="" >
						</div>
					</div>
					<table class="table table-bordered table-striped table-condensed mb-none" id="subsequent_el_table" style="width: 1010px; margin-top: 20px">
						<thead>
							<tr>
								<th style="text-align: center;width:70px"></th>
								<th style="text-align: center;width:200px">Engagement Letter Name</th>
								<th style="text-align: center;width:170px">Currency</th>
								<th style="text-align: center;width:170px">Fee</th>
								<th style="text-align: center;width:220px">Unit Pricing (Per)</th>
								<th style="text-align: center;width:220px">Servicing Firm</th>
							</tr>
							
						</thead>
						<tbody id="body_subsequent_el">
						</tbody>
						
					</table>
					<div class="form-group">
						<div class="col-sm-12">
							<input type="button" class="btn btn-primary submitSubsequentElInfo" id="submitSubsequentElInfo" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>
<!-- end: page -->
</section>


<script type="text/javascript">


	var clearance_history_list = <?php echo json_encode(isset($clearance_history_list)?$clearance_history_list:"") ?>;
	var engagement_letter_type_list = <?php echo json_encode(isset($transaction_engagement_letter_list)?$transaction_engagement_letter_list:"") ?>;
	var selected_el = <?php echo json_encode(isset($selected_el)?$selected_el[0]:"") ?>;
	var selected_sub_el = <?php echo json_encode(isset($selected_sub_el)?$selected_sub_el[0]:"") ?>;

	var save_first_letter_url = "<?php echo site_url('list_of_auditor/save_first_letter'); ?>";
	var generate_first_letter_url = "<?php echo site_url('list_of_auditor/generate_first_letter'); ?>";
	var generate_auth_document_url = "<?php echo site_url('bank/generate_auth_document'); ?>";
	var get_subsequent_el_detail_url = "<?php echo site_url('engagement/get_subsequent_el_detail'); ?>";
	var generate_engagement_letter_url = "<?php echo site_url('engagement/generate_engagement_letter'); ?>";
	var engagement_url = "<?= base_url();?>engagement";
	var save_subsequent_el_url = "<?php echo site_url('engagement/save_subsequent_el'); ?>";

	var base_url = window.location.origin;

	console.log(selected_el);


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

<script src="<?= base_url()?>application/modules/engagement/js/add_subsequent_el.js" charset="utf-8"></script>