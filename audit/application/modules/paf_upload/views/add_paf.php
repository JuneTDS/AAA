<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<link rel="stylesheet" href="<?=base_url()?>application/css/theme-custom.css" />
<link rel="stylesheet" href="<?= base_url()?>application/css/plugin/intlTelInput.css" />
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrapvalidator/dist/css/bootstrapValidator.min.css"/>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/toastr/build/toastr.min.css" />
<link rel="stylesheet" href="<?= base_url() ?>node_modules/jstree/dist/themes/default/style.min.css" />
<link rel="stylesheet" href="<?= base_url() ?>node_modules/jstree/dist/themes/default/custom-style.css" />
<link href="<?= base_url() ?>node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="<?=base_url()?>node_modules/bootstrap-fileinput/css/fileinput.min.css" />
<!-- <link rel="stylesheet" href="<?=base_url()?>application/css/styles/formValidation.css" /> -->
<script src="<?= base_url() ?>application/js/fileinput.js"></script>
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
<script type="text/javascript" src="<?= base_url() ?>node_modules/jstree/dist/jstree.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>node_modules/jstree/src/misc.js"></script>


<section role="main" class="content_section" style="margin-left:0;">
	<?php echo $breadcrumbs;?>
	<div class="panel-body" style="z-index: -1;">
		<div class="col-md-12">
			
			<section class="panel">
				<form id="create_paf_form" autocomplete="off">
				<div class="panel-body" >
					<input type="hidden" name="paf_id" class="form-control" id="paf_id" value="<?=isset($edit_paf)?$edit_paf[0]->id: ''?>"/>
					<table class="table table-bordered table-striped table-condensed mb-none" >
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
							<td colspan="2">
								&nbsp;
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<b>PERMANENT AUDIT FILE INDEX</b>
								<br />
								<a class="add_index amber" href="javascript:add_index()" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;"><i class="fa fa-plus-circle amber" style="font-size:16px;margin-top: 10px;"></i> Add Index</a>
								<div id="paf_tree" class="paf_tree jstree-numbering" ></div>
							</td>
						</tr>
						
					</table>
					<br/>

			
				</div>
				</form>	


				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 number text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button type="submit" class="btn btn-primary" name="savePaf" id="savePaf">Save</button>
							<a href="<?= base_url();?>paf_upload" class="btn btn-default">Back</a>
						</div>
					</div>
				</footer>
			</section>
						
		</div>
	</div>

	
	<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>
<!-- end: page -->
</section>

<!-- Upload PAF Doc Pop up -->
<div id="upload_paf_doc_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Upload</h2>
			</header>
			<form id="upload_paf_doc">
				<div class="panel-body">
					<div class="col-md-12">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<!-- <input type="hidden" name="doc_ba_id" class="doc_ba_id" value=""> -->

							<tr>
								<th>Current Path</th>
								<td>
							        <div style="width: 80%;">
										<input disabled type="text" class="form-control current_path" name="current_path" value="" style="width: 100%;" id="current_path" />
							        </div>
								</td>
							</tr>


							<tr>
								<th>Document(s)</th>
								<td>
							        <div class="">
    			                        <input id="multiple_paf_doc" name="paf_docs[]" type="file" data-browse-on-zone-click="false" multiple="true">
							        </div>
								</td>
							</tr>

						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn_blue" id="upload_paf_doc_btn">Submit</button>
					<input type="button" class="btn btn-default paf_upload_cancel" data-dismiss="modal" name="" value="Cancel" >
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">

	var edit_first_letter = <?php echo json_encode(isset($edit_first_letter)?$edit_first_letter:"") ?>;
	var save_first_letter_url = "<?php echo site_url('list_of_auditor/save_first_letter'); ?>";
	var save_paf_url = "<?php echo site_url('paf_upload/save_paf'); ?>";
	var paf_all_url = "<?php echo site_url('paf_upload/pafAllData'); ?>";
	var generate_first_letter_url = "<?php echo site_url('list_of_auditor/generate_first_letter'); ?>";
	var generate_auth_document_url = "<?php echo site_url('bank/generate_auth_document'); ?>";
	var auditor_url = "<?= base_url();?>list_of_auditor";

	var base_url = window.location.origin;  



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

<script src="<?= base_url()?>application/modules/paf_upload/js/add_paf.js" charset="utf-8"></script>