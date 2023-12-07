<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" />
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/jstree/dist/themes/default/style.min.css" />
<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/jstree/dist/themes/default/custom-style.css" /> -->

<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>

<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<script src="<?= base_url() ?>node_modules/moment/moment.js"></script>
<script type="text/javascript" src="<?= base_url() ?>node_modules/jstree/dist/jstree.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>node_modules/jstree/src/misc.js"></script>
<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootbox/bootbox.min.css" /> -->

<script src="<?= base_url() ?>node_modules/jquery-validation/dist/jquery.validate.js"></script>
<style type="text/css">
	.table-borderless > tbody > tr > td,
	.table-borderless > tbody > tr > th,
	.table-borderless > tfoot > tr > td,
	.table-borderless > tfoot > tr > th,
	.table-borderless > thead > tr > td,
	.table-borderless > thead > tr > th 
	{
	    border: none;
	}

	.control-label
	{
		text-align: right;
	}

	.borderless
	{
		border: none !important;
	}

/*	#audit_objective_table {
	   Set "section" to 0 
	  counter-reset: number;
	}
*/

/*	.objective_text::before {
	  counter-increment: number;
	  content: counter(number) ". ";
	  font-weight: bold;
	  padding: 0 5px 0 0;
	  vertical-align: 50%;
	}*/

	.vakata-context { z-index:10001; }

	.jstree-default a { 
	    white-space:normal !important; height: auto; 
	}
	.jstree-anchor {
	    height: auto !important;
	}
	.jstree-default li > ins { 
	    vertical-align:top; 
	}
	.jstree-leaf {
	    height: auto;
	}
	.jstree-leaf a{
	    height: auto !important;
	}

</style>



<div class="header_between_all_section">
	<section class="content_section">
		<?php echo $breadcrumbs;?> 
		<div class="panel-body">
			<div class="col-md-12">
				<div id="modal_fs" class="">
					<!-- <?php $attrib = array('class' => 'form-horizontal transaction_form', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'transaction_form');
									echo form_open_multipart("financial_statement/lodge_transaction", $attrib);
								?> -->
					<!-- <form> -->
					<section id="rootwizard">
						<div class="panel-body">
							<div style="width:30%;" class="wizard-progress wizard-progress-lg">
								<div class="steps-progress">
									<div class="progress-indicator"></div>
								</div>
								<ul class="wizard-steps" id="fs_tab">
									<li>
										<a href="#programme_info" data-toggle="tab"><span>1</span>Programme <br/> information</a>
									</li>
									<li>
										<a href="#programme_content" data-toggle="tab"><span>2</span>Programme <br/> content</a>
									</li>
								</ul>
							</div>

							<div class="tab-content">

								<div id="programme_info" class="tab-pane active">
									<form id="audit_programme_info_form">
										<div class="form-group">
											<input type="hidden" id="master_id" name="master_id" value="<?=isset($edit_programme)?$edit_programme->id:''?>">
											<label class="col-sm-5 control-label" for="Transaction">Index</label>
											<div class="col-sm-3">
												<input type="text" class="index_alpha form-control" name="index_alpha" style="text-transform:uppercase;" value="<?=isset($edit_programme)?$edit_programme->programme_index:''?>">
												<!-- <?php

													echo form_dropdown('index_alpha', $alphas_dropdown, isset($edit_programme)?$edit_programme->programme_index:'', 'class="index_alpha select2" style="width:100%;"');

												?> -->
												<!-- <select class="transaction_task" style="width: 100%;" name="transaction_task" id="transaction_task">
													<option value="0">Select Index</option>
												</select> -->
											</div>
										</div>

										<div class="form-group">
											<label class="col-sm-5 control-label" for="Transaction">Programme title</label>
											<div class="col-sm-3">
												<input type="text" class="programme_title form-control" name="programme_title" style="text-transform:uppercase;" value="<?=isset($edit_programme)?$edit_programme->title:''?>">
												<!-- <select class="transaction_task" style="width: 100%;" name="transaction_task" id="transaction_task">
													<option value="0">Select Index</option>
												</select> -->
											</div>
										</div>

									</form>
								</div>

								<div id="programme_content" class="tab-pane">
									<form>
				                        <a class="add_index amber" href="javascript:add_index()" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;"><i class="fa fa-plus-circle amber" style="font-size:16px;margin-top: 10px;"></i> Add Main Title</a>
										<div id="programme_content_tree" class="programme_content_tree jstree-numbering" ></div>

				                        <div class="form-group">
											<div class="col-sm-12">
												<button type="button" class="btn btn-primary save_programme_content" id="save_programme_content" style="float: right">Save</button>
											</div>
										</div>

									</form>
								</div>

							</div>
						</div>
						
						<div class="panel-footer">
							<ul class="pager wizard">
					            <li class="previous"><a href="javascript: void(0);">Go To Previous Page</a></li>
					            <li class="next" style="float: right"><a href="javascript: void(0);">Go To Next Page</a></li>
					           <!--  <li class="other_next" style="float: right"><a href="<?= base_url();?>masterclient/view_transfer/<?=$company_code?>">Done</a></li> -->
					            <li class="cancel_transaction" style="float: right; margin-right: 10px;"><a href="<?= base_url();?>setting" id="cancel_buyback">Cancel</a></li>
					        </ul>
						</div>
					</section>
					<!-- <?= form_close(); ?> -->
				<!-- </form> -->
				</div>
										
			</div>
		</div>
	</section>
</div>


<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<script type="text/javascript">
	var pathArray = location.href.split( '/' );
	var protocol = pathArray[0];
	var host = pathArray[2];
	var folder = pathArray[3];
	// var temp_parent_id_count = 1;

	var base_url = window.location.origin;  
	var arr_deleted_objtv = [];
	var arr_deleted_step = [];
	var contentAllData_yn_url = "<?php echo site_url('setting/contentAllData'); ?>";
	var save_all_yn_programme_setting_url = "<?php echo site_url('setting/save_all_yn_programme_setting'); ?>";
	var check_avail_index_url = "<?php echo site_url('setting/check_avail_index'); ?>";

	var editing_flag = <?php echo json_encode(isset($editing_flag)?$editing_flag:"") ?>;
	var objective_lines = <?php echo json_encode(isset($objective_lines)?$objective_lines:"") ?>;
	var procedure_lines = <?php echo json_encode(isset($procedure_lines)?$procedure_lines:"") ?>;

	var form_is_valid = true;


</script>

<script src="<?= base_url()?>application/modules/setting/js/audit_programme_yn.js" charset="utf-8"></script>
