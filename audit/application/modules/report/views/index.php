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
					<div class="col-sm-12 col-md-12">
						<table class="table table-bordered table-striped mb-none" id="datatable-report" style="width:100%">
							<thead>
								<tr style="background-color:white;">
									<th class="text-left">No.</th>
									<th class="text-left">Client Name</th>
									<!-- <th class="text-left">Report</th>	 -->
								</tr>
							</thead>
							<tbody class="bank_confirm_body">
								<?php 
									if($ml_client)
									{
										foreach($ml_client as $key=>$each_client)
							  			{

							  				// print_r($each_client);

							  				echo '<tr>';
								  				echo '<input type="hidden" class="company_code" value="'. $each_client['company_code'] .'" />';
								  				echo '<input type="hidden" class="company_name" value="'. $each_client['company_name'] .'" />';
								  				echo '<td style="width:2.5%;>" "</td>';
								  				echo '<td style="width:97.5%;" class="company_name"><a href="javascript:void(0)" class="open_caf_report_list">'.$each_client['company_name'].'</a></td>';
								  				// echo '<td style="width:15%;"></td>';
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
	</section>
</section>

<!-- File to CAF Pop up -->
<div id="caf_report_list" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  data-backdrop="static" style="margin-top:150px;">
	<div class="modal-dialog" >
		<div class="modal-content">
			<header class="panel-heading">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h2 class="panel-title">Current files</h2>
			</header>
	
			<div class="panel-body">
				<div class="col-md-12">
					<table class="table table-bordered table-striped table-condensed mb-none" id="pic_table">
						<thead>
							<tr style="background-color:white;">
								<th class="text-center">Description</th>
								<th class="text-center">Job Type</th>
							</tr>
						</thead>
						<tbody id="caf_report_list_body">
						</tbody>
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

	$(document).ready(function () {

	    var t_c = $('#datatable-report').DataTable({
				
	            "order": [[ 1, 'asc' ]]
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
<script src="<?= base_url()?>application/modules/report/js/report.js" charset="utf-8"></script>