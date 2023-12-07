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

<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>node_modules/sweetalert2/dist/sweetalert2.min.css">
<script src="<?= base_url() ?>node_modules/sweetalert2/dist/sweetalert2.min.js"></script>


<section role="main" class="content_section" style="margin-left:0;">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			
			<section class="panel">
				<form id="create_stocktake_arrangement_form" autocomplete="off">
				<div class="panel-body">
					<input type="hidden" name="arrangement_id" class="form-control" id="arrangement_id" value=""/>
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
							<th>Financial Year End Date</th>
							<td>
								<div class="fye_date_div">
									<div class="form-group">
										<div class="input-group" id="fye_date" style="width: 30%;">
											<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="fye_date form-control" name="fye_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="" readonly>
											<!-- <?php $now = getDate();echo $now['mday'].'/'.$now['mon']."/".$now['year'];?> -->
										</div>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th>Person in charge</th>
							<td>
								<div class="pic_name_div">
									<div class="input-group dropdown_pic_name" style="width: 100%;">
										<select id="pic_name" class="form-control pic_name" style="width: 100%;" name="pic_name">
							                    <option value="">Select PIC Name</option>
							            </select>
							        </div>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								&nbsp;
							</td>
						</tr>
						<tr>
							<table class="table table-bordered table-striped mb-none" style="width:100%;table-layout: fixed;" id="arrangement_tbl">
								<thead style="width:100%;">
									<tr>
										<th style="height:35px;width: 20%;" valign=middle>Our Auditor</th>
										<th style="height:35px;width:17.5%" valign=middle>Date</th>
										<th style="height:35px;width:12.5%" valign=middle>Time</th>
										<th style="height:35px;width:25%" valign=middle>Address</th>
										<th style="height:35px;width:18%" valign=middle>Client PIC</th>
										<th style="height:35px;width:7%" valign=middle>
											<!-- <a class="create_st_arrangement amber" href="<?= base_url();?>stocktake/add_stocktake_arrangement" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Create arragement" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add Line</a> 

											<a href="javascript: void(0);" class="th" style="color: #D9A200; outline: none !important;text-decoration: none;"><span id="payment_receipt_service_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Payment Voucher" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Line</span></a>-->
											<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Create arragement" id="stocktake_arrangement_Add" ><i class="fa fa-plus-circle amber" style="font-size:16px;"></i> Add Line</a>
										</th>
									</tr>
								</thead>

								<tbody id="body_add_stocktake_arrangement">
							
								</tbody>
					
							</table>
						</tr>
						
					</table>
					<br/>

					
					<!-- <table class="table table-bordered table-striped table-condensed mb-none" style="width:f100%;">
						<thead>
							<div class="tr" id="create_payment_receipt_service">
								<div class="th" valign=middle >Type</div>
								<div class="th" valign=middle >Payment Description</div>
								<div class="th">Amount</div>
								<div class="th">Attachment</div>
								<a href="javascript: void(0);" class="th" style="color: #D9A200; outline: none !important;text-decoration: none;"><span id="payment_receipt_service_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Payment Voucher" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Line</span></a>
							</div>
							
						</thead>
						

						<div class="tbody" id="body_create_payment_receipt">
							

						</div>
						<div class="tr" id="grand_total_create_payment_receipt">
							<div class="th">Grand Total</div>
							<div class="th"></div>
							<div class="th" style="text-align: right;" id="grand_total">0</div>
							<div class="th"></div>
							<div class="th"></div>
							<input type="hidden" name="grand_total" class="form-control" id="hidden_grand_total" value="0"/>
						</div>
					</table> -->
					<!-- <?= form_close(); ?> -->
				</div>
				</form>	

				<table id="clone_model" style="display: none;" >
					<tr class="editing tr_payment_receipt" method="post" name="form" id="form" num="">
 						<td id="auditor_td" style="width: 20%;">
    						<div class="hidden"><input type="text" class="form-control arrangement_info_id" name="arrangement_info_id[]" value=""/></div>
   							<div class="select-input-group" style="width: 100%;">
   								<?php  
   									echo form_dropdown('our_auditor[]', $auditor_name_dropdown, '' ,'class="our_auditor" multiple="multiple" onchange="assign_hidden_val(this)" ');
   								?>
   								<input type="hidden" name=hidden_our_auditor[] class="form-control hidden_our_auditor" value=""/>   					
   								<!-- <select class="input-sm form-control our_auditor" name="our_auditor[]" >
   									<option value="0" data-invoice_description="" data-amount="">Select Auditor</option>
   								</select> -->
   							</div>
   						</td>
   						<td style="width:17.5%">
   							<!-- <div>
   								<input type="text" name="stocktake_date[]" class="form-control" value="" />
   							</div> -->
   							<div class="input-group" id="stocktake_date" >
								<span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control stocktake_date" name="stocktake_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker value="">
								<!-- <?php $now = getDate();echo $now['mday'].'/'.$now['mon']."/".$now['year'];?> -->
							</div>
   						</td>
   						<td style="width:12.5%">
   							<div>
   								<input type="time" name="stocktake_time[]" class="form-control stocktake_time" value=""/>
   							</div>
   						</td>
   						<td  style="width:25%">
   							<div style="display: block;">
   								<textarea class="form-control stocktake_address" name="stocktake_address[]" rows="3" style="width:100%"></textarea>
   							</div>
   						</td>
   						<td style='width:18%'>
   							<div>
   								<input type="text" name="client_pic[]" class="form-control" value=""  />
   							</div>
   						</td>
    					<td class="action" style='width:7%'>
    						<input type="hidden" class="form-control arrangement_info_id" value=""/>
    						<button type="button" class="btn btn-primary delete_arrangement_button" onclick="delete_arrangement_info(this)" style="display: none;">Delete</button>
    					</td>
    				</tr>
					
				</table>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 number text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button type="submit" class="btn btn-primary" name="saveArrangement" id="saveArrangement">Save</button>
							<a href="<?= base_url();?>stocktake" class="btn btn-default">Back</a>
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
	var stocktake_arrangement_list = <?php echo json_encode(isset($stocktake_arrangement_list)?$stocktake_arrangement_list:"") ?>;
	var save_stocktake_arrangement_url = "<?php echo site_url('stocktake/save_stocktake_arrangement'); ?>";
	var delete_arrangement_info_url = "<?php echo site_url('stocktake/delete_arrangement_info'); ?>";
	var stocktake_url = "<?php echo site_url('stocktake'); ?>";
	var edit_stocktake_arrangement = <?php echo json_encode(isset($edit_stocktake_arrangement)?$edit_stocktake_arrangement:"") ?>;

	// var generate_first_letter_url = "<?php echo site_url('list_of_auditor/generate_first_letter'); ?>";
	var generate_auth_document_url = "<?php echo site_url('bank/generate_auth_document'); ?>";
	var auditor_url = "<?= base_url();?>list_of_auditor";
	var check_duplicate_flag = true;

	// console.log(edit_stocktake_arrangement);


	// console.log(edit_bank_auth[0]["auth_date"]);
	// $("#our_auditor").multiselect({
 //        allSelectedText: 'All',
 //        enableFiltering: true,
 //        enableCaseInsensitiveFiltering: true,
 //        maxHeight: 200,
 //        includeSelectAllOption: true,
 //        buttonWidth: '100%'
 //    });

 //    $("#our_auditor").multiselect('selectAll', false);
 //    $("#our_auditor").multiselect('updateButtonText');
    

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

<!-- <script src="<?= base_url()?>application/modules/list_of_auditor/js/add_first_clearance_letter.js" charset="utf-8"></script> -->
<script src="<?= base_url()?>application/modules/stocktake/js/add_stocktake_arrangement.js" charset="utf-8"></script>