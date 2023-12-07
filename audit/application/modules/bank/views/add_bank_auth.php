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
			
			<section class="panel">
				<form id="create_bank_auth_form">
				<div class="panel-body">
					<input type="hidden" name="bank_auth_id" class="form-control" id="bank_auth_id" value=""/>
					<table class="table table-bordered table-striped table-condensed mb-none">
						<tr>
							<th>Client</th>
							<td style="width: 78%;">
								<div class="form-group input-group dropdown_client_name" style="width: 100%;">
									<select id="client_name" class="form-control client_name" style="width: 100%;" name="client_name">
						                    <option value="">Select Client Name</option>
						            </select>
						        </div>
						        <div class="input_client_name" style="width: 100%; display: none">
									<input type="text" id="text_client_name" class="form-control text_client_name" style="width: 100%;" name="text_client_name">
						        </div>
							</td><!-- disabled -->
						</tr>
						<tr>
							<th>Bank Name</th>
							<td>
								<div class="form-group input-group dropdown_bank_name" style="width: 100%;">
									<select id="bank_name" class="form-control bank_name" style="width: 100%;" name="bank_name">
						                    <option value="">Select Bank Name</option>
						            </select>
						        </div>
						        <div class="input_bank_name" style="width: 100%; display: none">
									<input type="text" id="text_bank_name" class="form-control text_bank_name" style="width: 100%;" name="text_bank_name">
						        </div>
							</td>
						</tr>
						<tr>
							<th>Authorization Date</th>
							<td>
								<div class="auth_date_div">
								<div class="form-group input-group" id="auth_date" style="width: 30%;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="auth_date form-control" name="auth_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="">
									<!-- <?php $now = getDate();echo $now['mday'].'/'.$now['mon']."/".$now['year'];?> -->
								</div>
							</td>
						</tr>
	<!-- 					<tr>
							<th>Status</th>
							<td>
								<div class="form-group input-group" style="width: 30%;">
									<select id="auth_status" class="form-control auth_status" style="width: 100%;" name="auth_status">
						                    <option value="0">Select Status</option>
						            </select>
						        </div>
							</td>
						</tr> -->
					<!-- 	<tr>
							<th>Rate</th>
							<td>
								<div class="rate-input-group">
									<div class="validate" style="width: 50%;"><input type="text" name="rate" class="form-control rate" id="rate" value="" style="text-align: right"/></div>
								</div>
							</td>
						</tr>
						<tr>
							<th>Address</th>
							<td>
								<div class="input-group">
									<textarea class="form-control" name="address" id="address" style="width:400px;height:120px;">
									</textarea>
									<input type="hidden" name="hidden_postal_code">
									<input type="hidden" name="hidden_street_name">
									<input type="hidden" name="hidden_building_name">
									<input type="hidden" name="hidden_unit_no1">
									<input type="hidden" name="hidden_unit_no2">
								</div>
							</td>
						</tr> -->
						
					</table>
					<br/>
		
					<!-- <?= form_close(); ?> -->
				</div>
				</form>	
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 number text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button type="submit" class="btn btn-primary" name="saveAuth" id="saveAuth">Save & Generate</button>
							<a href="<?= base_url();?>bank" class="btn btn-default">Back</a>
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

	var edit_bank_auth = <?php echo json_encode(isset($edit_bank_auth)?$edit_bank_auth:"") ?>;
	var save_bank_auth_url = "<?php echo site_url('bank/save_bank_auth'); ?>";
	var generate_auth_document_url = "<?php echo site_url('bank/generate_auth_document'); ?>";
	var bank_url = "<?= base_url();?>bank";
	var bank_list_url = "<?= base_url();?>bank/set_banklist_active";



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

<script src="<?= base_url()?>application/modules/bank/js/add_bank_auth.js" charset="utf-8"></script>