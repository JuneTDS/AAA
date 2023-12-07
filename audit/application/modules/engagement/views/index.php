<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" />
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<script src="<?= base_url() ?>application/js/fileinput.js"></script>
<link rel="stylesheet" href="<?=base_url()?>node_modules/bootstrap-fileinput/css/fileinput.min.css" />

<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>node_modules/sweetalert2/dist/sweetalert2.min.css">
<script src="<?= base_url() ?>node_modules/sweetalert2/dist/sweetalert2.min.js"></script>

<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<script src="<?= base_url() ?>node_modules/moment/moment.js"></script>
<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootbox/bootbox.min.css" /> -->
<style type="text/css">
	#clearance_attachment_table th, #clearance_attachment_table td
	{
		text-align: center !important;
		/*font-weight: normal !important;*/
	}

</style>

<section role="main" class="content_section" style="margin-left:0;">
	<header class="panel-heading">
		<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
			<div class="panel-actions">
				<a class="create_engagement_letter amber" href="<?= base_url();?>engagement/add_engagement_letter" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create letter" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add engagement</a>
				<a class="create_subsequent_el amber" href="<?= base_url();?>engagement/add_subsequent_el" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create subsequent letter" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add subsequent engagement</a>
			</div>
			<h2></h2>
		<?php } ?>
	</header>
	<section class="panel" style="margin-top: 0px;">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="tabs">				
					<ul class="nav nav-tabs nav-justify">
						<li class="active check_state" data-information="engagement">
							<a href="#w2-engagement" data-toggle="tab" class="text-center">
								Initial
							</a>
						</li>
						<li class="check_state" data-information="subsequent">
							<a href="#w2-subsequent" data-toggle="tab" class="text-center">
								Subsequent
							</a>
						</li>
						<li class="check_state" data-information="moved_el">
							<a href="#w2-moved_el" data-toggle="tab" class="text-center">
								Moved letter
							</a>
						</li>
			<!-- 			<li class="check_state" data-information="auditor_list">
							<a href="#w2-auditor_list" data-toggle="tab" class="text-center">
								Auditors
							</a>
						</li> -->
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
						
						<div id="w2-engagement" class="tab-pane active">
							<table class="table table-bordered table-striped mb-none" id="datatable-engagement" style="width:100%">
								<thead style="width:100%">
									<tr>
									<!-- <th><input type="checkbox" name="selectall" class="selectall"/></th> -->
									<!-- <th style="text-align:center;">No</th> -->
									<th style="text-align:center;">Client</th>
									<th style="text-align:center;">Status</th>
									<th style="text-align:center;">Last update</th>
									<th style="text-align:center;"></th>
								</tr>
								</thead>
									<tbody id="engagement_body">
										<?php 
											if($el_list)
											{
												foreach($el_list as $el)
									  			{
									  				if($el->status == 13)
													{
														$disable_paf = "";
													}
													else
													{
														$disable_paf = "disabled";
								
													}

													if (array_key_exists($el->id, $initial_el_logs))
													{
														$update_data = "<p style='font-size:11px;white-space: pre-line;margin:0;'>".$initial_el_logs[$el->id]['user_name']."\n<span style:font-size:9px;>".str_replace(" ", " | ", $initial_el_logs[$el->id]['date_time'])."</span></p>";
													}
													else
													{
														$update_data = "-";
													}
									  				echo '<tr>';
									  				echo '<td style="width:55%; class="company_name"><a href="'.base_url().'/engagement/edit_engagement_letter/'.$el->id.'" class="edit_el" data-id="'.$el->id.'">'.$el->company_name.'</a></td>';
									  				echo '<td style="width:12%;" ><div class="input-group" style="width: 100%;">'.form_dropdown('initial_el_status', $status_dropdown, isset($el->status)?$el->status:'', 'class="initial_el_status" style="width:100%;" onchange=change_initial_el_status(this)').'<input type="hidden" class="el_id" value="'. $el->id .'" /></div></td>';
									  				echo '<td align="center" style="width:15%;vertical-align:middle;">'.$update_data.'</td>';
									  				echo '<td style="text-align:left;width:18%;">
									  						<input type="hidden" class="el_id" value="'. $el->id .'" />
									  						<input type="hidden" class="company_name" value="'. $el->company_name .'" />
									  						<button type="button" class="btn btn_blue" style="margin:4px;" onclick="generate_engagement_letter('.$el->id.')">PDF</button>
									  						<button type="button" class="btn btn_blue el_upload_btn" onclick=open_el_doc_modal(this) style="margin:4px;">Upload</button>
									  						<button '.$disable_paf.' type="button" class="btn btn_blue" style="margin:4px;" onclick="open_initial_el_paf(this)">PAF</button>
									  						<button type="button" class="btn btn_blue" style="margin:4px;" onclick=delete_initial_el(this)>Delete</button>
									  					  </td>';
									  				echo '</tr>';
									  			}
											}
											
										?>
								</tbody>
							</table>
						</div>

						<div id="w2-subsequent" class="tab-pane">
							<table class="table table-bordered table-striped mb-none" id="datatable-subsequent" style="width:100%">
								<thead style="width:100%">
									<tr>
									<!-- <th><input type="checkbox" name="selectall" class="selectall"/></th> -->
									<!-- <th style="text-align:center;">No</th> -->
									<th style="text-align:center;">Client</th>
									<th style="text-align:center;">Letter date</th>
									<th style="text-align:center;">Status</th>
									<th style="text-align:center;">Last update</th>
									<th style="text-align:center;"></th>
								</tr>
								</thead>
								<tbody id="subsequent_body">
									<?php 
											if($subsequent_el_list)
											{
												foreach($subsequent_el_list as $el)
									  			{
									  				if($el->status == 13)
													{
														$disable_paf = "";
													}
													else
													{
														$disable_paf = "disabled";
								
													}

													if (array_key_exists($el->id, $subsequent_el_logs))
													{
														$update_data = "<p style='font-size:11px;white-space: pre-line;margin:0;'>".$subsequent_el_logs[$el->id]['user_name']."\n<span style:font-size:9px;>".str_replace(" ", " | ", $subsequent_el_logs[$el->id]['date_time'])."</span></p>";
													}
													else
													{
														$update_data = "-";
													}
									  				echo '<tr>';
									  				echo '<td style="width:45%; class="company_name"><a href="'.base_url().'engagement/edit_subsequent_el/'.$el->id.'" class="edit_el" data-id="'.$el->id.'">'.$el->company_name.'</a></td>';
									  				echo '<td style="width:10%; class="company_name">'.$el->new_date.'</td>';
									  				echo '<td style="width:12%;" ><div class="input-group" style="width: 100%;">'.form_dropdown('subsequent_el_status', $status_dropdown, isset($el->status)?$el->status:'', 'class="subsequent_el_status" style="width:100%;" onchange=change_subsequent_el_status(this)').'<input type="hidden" class="sub_el_id" value="'. $el->id .'" /></div></td>';
									  				echo '<td align="center" style="width:15%;vertical-align:middle;">'.$update_data.'</td>';
									  				echo '<td style="text-align:left;width:18%;">
									  						<input type="hidden" class="sub_el_id" value="'. $el->id .'" />
									  						<input type="hidden" class="company_name" value="'. $el->company_name .'" />
									  						<button type="button" class="btn btn_blue" style="margin:4px;" onclick="generate_subsequent_el('.$el->id.')">PDF</button>
									  						<button type="button" class="btn btn_blue sub_el_upload_btn" onclick=open_sub_el_doc_modal(this) style="margin:4px;">Upload</button>
									  						<button '.$disable_paf.' type="button" class="btn btn_blue" style="margin:4px;" onclick="open_subsequent_el_paf(this)">PAF</button>
									  						<button type="button" class="btn btn_blue" style="margin:4px;" onclick=delete_subsequent_el(this)>Delete</button>
									  					  </td>';
									  				echo '</tr>';
									  			}
											}
											
										?>
								</tbody>
							</table>
						</div>

						<div id="w2-moved_el" class="tab-pane">
<!-- 							<table class="table table-bordered table-striped mb-none" id="datatable-subsequent" style="width:100%">
								<thead style="width:100%">
									<tr>
									<th style="text-align:center;">Client</th>
									<th style="text-align:center;">Letter date</th>
									<th style="text-align:center;">Status</th>
									<th style="text-align:center;">Last update</th>
									<th style="text-align:center;"></th>
								</tr>
								</thead>
								<tbody id="subsequent_body">
									<?php 
											if($subsequent_el_list)
											{
												foreach($subsequent_el_list as $el)
									  			{
									  				if($el->status == 13)
													{
														$disable_paf = "";
													}
													else
													{
														$disable_paf = "disabled";
								
													}

													if (array_key_exists($el->id, $subsequent_el_logs))
													{
														$update_data = "<p style='font-size:11px;white-space: pre-line;margin:0;'>".$subsequent_el_logs[$el->id]['user_name']."\n<span style:font-size:9px;>".str_replace(" ", " | ", $subsequent_el_logs[$el->id]['date_time'])."</span></p>";
													}
													else
													{
														$update_data = "-";
													}
									  				echo '<tr>';
									  				echo '<td style="width:45%; class="company_name"><a href="'.base_url().'engagement/edit_subsequent_el/'.$el->id.'" class="edit_el" data-id="'.$el->id.'">'.$el->company_name.'</a></td>';
									  				echo '<td style="width:10%; class="company_name">'.$el->new_date.'</td>';
									  				echo '<td style="width:12%;" ><div class="input-group" style="width: 100%;">'.form_dropdown('subsequent_el_status', $status_dropdown, isset($el->status)?$el->status:'', 'class="subsequent_el_status" style="width:100%;" onchange=change_subsequent_el_status(this)').'<input type="hidden" class="sub_el_id" value="'. $el->id .'" /></div></td>';
									  				echo '<td align="center" style="width:15%;vertical-align:middle;">'.$update_data.'</td>';
									  				echo '<td style="text-align:left;width:18%;">
									  						<input type="hidden" class="sub_el_id" value="'. $el->id .'" />
									  						<input type="hidden" class="company_name" value="'. $el->company_name .'" />
									  						<button type="button" class="btn btn_blue" style="margin:4px;" onclick="generate_subsequent_el('.$el->id.')">PDF</button>
									  						<button type="button" class="btn btn_blue sub_el_upload_btn" onclick=open_sub_el_doc_modal(this) style="margin:4px;">Upload</button>
									  						<button '.$disable_paf.' type="button" class="btn btn_blue" style="margin:4px;" onclick="open_subsequent_el_paf(this)">PAF</button>
									  						<button type="button" class="btn btn_blue" style="margin:4px;" onclick=delete_subsequent_el(this)>Delete</button>
									  					  </td>';
									  				echo '</tr>';
									  			}
											}
											
										?>
								</tbody>
							</table> -->
							<form id="el_record_form">
								<input type="button" class="btn btn-primary search_report" id="searchRegister" name="searchRegister" value="Search" style="float: right;margin-right: 10px;"/>											
								<div class="form-group">
									<label class="col-xs-3">Client name: </label>
									<div class="col-xs-7 form-inline" style="width:40%">
										<div class="input-group" style="width: 500px;">
											<select id="client_name" class="form-control client_name" style="width: 100%;" name="company_code">
								                <option value="">Select Client Name</option>
								            </select>
										</div>
									</div>
								</div>
								<HR SIZE=10></HR>
								<div class="printablereport" style="width: 100%">
									<table class="table table-bordered table-striped mb-none" id="result_table" style="width:100%">

										<thead style="width:100%">
											<tr>
												<!-- <th><input type="checkbox" name="selectall" class="selectall"/></th> -->
												<!-- <th style="text-align:center;">No</th> -->
												<th style="text-align:center;">Latest letter date</th>
												<!-- <th style="text-align:center;">Status</th> -->
												<th style="text-align:center;">Last update</th>
												<th style="text-align:center;"></th>
											</tr>
										</thead>
										<tbody id="moved_el_body">
										</tbody>												
									</table>
									
								</div>
							</form>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</section>
</section>

<!-- Upload Engagaement Doc Pop up -->
<div id="upload_el_doc_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Upload</h2>
			</header>
			<form id="upload_el_doc_form">
				<div class="panel-body">
					<div class="col-md-12">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<input type="hidden" name="doc_el_id" class="doc_el_id" value="">

							<tr>
								<th>Client</th>
								<td>
							        <div style="width: 80%;">
										<input disabled type="text" class="form-control client_name" name="client_name" value="" style="width: 100%;" id="el_client" />
							        </div>
								</td>
							</tr>

							<tr>
								<th>Document(s)</th>
								<td>
							        <div class="">
    			                        <input id="multiple_el_doc" name="el_docs[]" type="file" data-browse-on-zone-click="false" multiple="true">
							        </div>
								</td>
							</tr>

						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn_blue" id="upload_el_doc_btn">Submit</button>
					<input type="button" class="btn btn-default el_upload_cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- File to PAF Pop up -->
<div id="initial_el_paf_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  data-backdrop="static" style="margin-top:150px;">
	<div class="modal-dialog" style="width: 750px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h2 class="panel-title">File to PAF</h2>
			</header>
			<form id="initial_el_paf_form">
				<input type="hidden" class="paf_el_id" name="paf_el_id">
	
				<div class="panel-body">
					<div class="col-md-12">
				
						<div id="attachment_table_wrapper">
							<table class="table table-bordered" id="initial_el_attachment_table">
								<tr>
									<th style="width: 100%;">Attachment</th>
								</tr>
							</table>
						</div>

							
					</div>
				</div>
			</form>
			<div class="modal-footer">
				
				<input type="button" class="btn btn-default move_paf_cancel" data-dismiss="modal" name="" value="Cancel">
				<button type="button" class="btn btn_blue" id="move_paf_btn">Move</button>
				<button type="button" style="display: none;" class="btn btn_blue" id="replace_paf_btn">Replace</button>
			</div>
		
		</div>
	</div>
</div>

<!-- Upload Subsequent Engagaement Doc Pop up -->
<div id="upload_sub_el_doc_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Upload</h2>
			</header>
			<form id="upload_sub_el_doc_form">
				<div class="panel-body">
					<div class="col-md-12">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<input type="hidden" name="doc_sub_el_id" class="doc_sub_el_id" value="">

							<tr>
								<th>Client</th>
								<td>
							        <div style="width: 80%;">
										<input disabled type="text" class="form-control client_name" name="client_name" value="" style="width: 100%;" id="sub_el_client" />
							        </div>
								</td>
							</tr>

							<tr>
								<th>Document(s)</th>
								<td>
							        <div class="">
    			                        <input id="multiple_sub_el_doc" name="sub_el_docs[]" type="file" data-browse-on-zone-click="false" multiple="true">
							        </div>
								</td>
							</tr>

						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn_blue" id="upload_sub_el_doc_btn">Submit</button>
					<input type="button" class="btn btn-default sub_el_upload_cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- File subsequent el to PAF Pop up -->
<div id="subsequent_el_paf_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  data-backdrop="static" style="margin-top:150px;">
	<div class="modal-dialog" style="width: 750px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h2 class="panel-title">File to PAF</h2>
			</header>
			<form id="subsequent_el_paf_form">
				<input type="hidden" class="paf_sub_el_id" name="paf_sub_el_id">
	
				<div class="panel-body">
					<div class="col-md-12">
				
						<div id="attachment_table_wrapper">
							<table class="table table-bordered" id="subsequent_el_attachment_table">
								<tr>
									<th style="width: 100%;">Attachment</th>
								</tr>
							</table>
						</div>

							
					</div>
				</div>
			</form>
			<div class="modal-footer">
				
				<input type="button" class="btn btn-default move_sub_paf_cancel" data-dismiss="modal" name="" value="Cancel">
				<button type="button" class="btn btn_blue" id="move_sub_paf_btn">Move</button>
				<button type="button" style="display: none;" class="btn btn_blue" id="replace_sub_paf_btn">Replace</button>
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

	var active_tab = <?php echo json_encode(isset($active_tab)?$active_tab:"engagement") ?>;
	var engagement_list = <?php echo json_encode(isset($el_list)?$el_list:"") ?>;
	var subsequent_engagement_list = <?php echo json_encode(isset($subsequent_el_list)?$subsequent_el_list:"") ?>;

	var subsequent_el_logs = <?php echo json_encode(isset($subsequent_el_logs)?$subsequent_el_logs:"") ?>;
	var initial_el_logs = <?php echo json_encode(isset($initial_el_logs)?$initial_el_logs:"") ?>;
	
	// var bank_confirm_setting = <?php echo json_encode(isset($bank_confirm_setting)?$bank_confirm_setting:"") ?>;

	var generate_engagement_letter_url = "<?php echo site_url('engagement/generate_engagement_letter'); ?>";
	var get_initial_el_attachment_url = "<?php echo site_url('engagement/get_initial_el_attachment'); ?>";
	var get_subsequent_el_attachment_url = "<?php echo site_url('engagement/get_subsequent_el_attachment'); ?>";
	var lodge_engagement_url = "<?php echo site_url('engagement/lodge_engagement'); ?>";
	var lodge_subsequent_engagement_url = "<?php echo site_url('engagement/lodge_subsequent_engagement'); ?>";

	var engagement_url = "<?php echo site_url('engagement'); ?>";
	var initialPreviewArray = []; 
	var initialPreviewConfigArray = [];
	var selected_move_el, selected_move_sub_el;
	var change_el_status_flag = false;
	var change_sub_el_status_flag = false;
	
	// var type_of_leave_list = <?php echo json_encode(isset($type_of_leave_list)?$type_of_leave_list:"") ?>;
	// var block_leave_list = <?php echo json_encode(isset($block_leave_list)?$block_leave_list:"") ?>;
	// var holiday_list = <?php echo json_encode(isset($holiday_list)?$holiday_list:"") ?>;
	var pv_index_tab_aktif;
	var previous_initial_el = "";
	var previous_subsequent_el = "";
	var base_url = window.location.origin;
	var startDate = new Date();

	console.log(active_tab);

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

	$(document).ready(function () {
	    $('#datatable-engagement').DataTable( {
	    	"bStateSave": true,
	    	"order": [],
	    	"columnDefs": [
			  { "searchable": false, "targets": [2] }  // Disable search on first and last columns
			]
	    });

	    $('#datatable-subsequent').DataTable( {
	    	"bStateSave": true,
	    	"order": []
	    });
	});


	$(".clearance_status").select2();

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


	//$('.edit_type_of_leave').live("click",function(){

	$('[data-toggle="tooltip"]').tooltip();

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	$('.confirm_month').datepicker({
	    autoclose: true,
	    minViewMode: 1,
	    format: 'MM yyyy'
	});

	

	$('.datepicker').datepicker({
		format: 'dd MM yyyy',
	});

	$('.carry_forward_period_datepicker').datepicker({
		format: 'dd MM',
		//viewMode: 'months'
		// minViewMode: 'months',
		// maxViewMode: 'months',
	}).on('show', function() {
	    // remove the year from the date title before the datepicker show
	    var dateText  = $(".datepicker-days .datepicker-switch").text();
	    var dateTitle = dateText.substr(0, dateText.length - 5);
	    $(".datepicker-days .datepicker-switch").text(dateTitle);
	});

	var userTarget1 = "";
	var exit1 = false;



</script>

<script src="<?= base_url()?>application/modules/engagement/js/engagement.js" charset="utf-8"></script>