<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" />
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>

<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<script src="<?= base_url() ?>node_modules/moment/moment.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.21/sorting/natural.js"></script>



<!-- context menu -->
<script src="<?= base_url() ?>node_modules/jquery-contextmenu/dist/jquery.contextMenu.js"></script>
<script src="<?= base_url() ?>node_modules/jquery-contextmenu/dist/jquery.ui.position.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/jquery-contextmenu/dist/jquery.contextMenu.min.css">

<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootbox/bootbox.min.css" /> -->
<script src="<?= base_url() ?>node_modules/jquery-validation/dist/jquery.validate.js"></script>

<style type="text/css">
	.panel-heading.collapsed .fa-chevron-down,
	.panel-heading .fa-chevron-right {
	  display: none;
	}

	.panel-heading.collapsed .fa-chevron-right,
	.panel-heading .fa-chevron-down {
	  display: inline-block;
	}

	.collapsed ~ .panel-body {
	  padding: 0 18px 0 18px;
	}

	.panel-heading {
		background-color: white;
		color: #154069;
		font-weight: bold;
		font-size: 14px;
		cursor: pointer;
	}

	.panel-heading span{
		/*border-bottom: 2px solid #154069;*/
		display: 	inline-block;	
		width: 	180px;

	}

	.table-borderless>tbody>tr>td
	{
		border: none;
	}

	.panel-body table 
	{
		/*color: steelblue;*/
		font-size: 13px;
	}

	.content_section thead {
         position: absolute !important;
         top: -9999px !important;
         left: -9999px !important;
	}

	#addline_popup .select2-container {
	    display: inline-block !important;
	}

	#addline_popup .modal-dialog {
		color: #154069;
		max-height: 450px;
		overflow-y: auto;
		overflow-x: hidden;
	}

</style>
<link href="<?= base_url() ?>application/modules/caf/css/adjustment_popup.css" rel="stylesheet" type="text/css">

<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 0px;">

		<div class="panel-heading collapsed" data-toggle="collapse" data-target="#client_doc">
			<i class="fa fa-fw fa-chevron-down"></i>
			<i class="fa fa-fw fa-chevron-right"></i><span>CLIENT’S DOCUMENTS</span>
		</div>
		<div class="panel-body">
		<!-- The inside div eliminates the 'jumping' animation. -->
			<div class="collapse" id="client_doc">
			  	
			</div>
		</div>

		<div class="panel-heading collapsed" data-toggle="collapse" data-target="#confirmation">
			<i class="fa fa-fw fa-chevron-down"></i>
			<i class="fa fa-fw fa-chevron-right"></i><span>CONFIRMATIONS</span>
		</div>
		<div class="panel-body">
		<!-- The inside div eliminates the 'jumping' animation. -->
			<div class="collapse" id="confirmation">
			  	
			</div>
		</div>


<!-- 		<div class="panel-heading collapsed" data-toggle="collapse" data-target="#completion">
			<i class="fa fa-fw fa-chevron-down"></i>
			<i class="fa fa-fw fa-chevron-right"></i><span>COMPLETION</span>
		</div>
		<div class="panel-body"> -->
		<!-- The inside div eliminates the 'jumping' animation. -->
<!-- 			<div class="collapse" id="completion">
			  	
			</div>
		</div>
 -->

<!-- 		<div class="panel-heading collapsed" data-toggle="collapse" data-target="#final">
			<i class="fa fa-fw fa-chevron-down"></i>
			<i class="fa fa-fw fa-chevron-right"></i><span>AUDIT FINALIZATION</span>
		</div>
		<div class="panel-body"> -->
		<!-- The inside div eliminates the 'jumping' animation. -->
<!-- 			<div class="collapse" id="final">
			  	<table class="table table-borderless mb-one main-category" id="final-table" style="width:100%;">
			  		<thead></thead>
					<tbody>
						<?php 
									
							if($caf_data)
							{
								foreach($caf_data as $key=>$each)
					  			{	
					  				if(substr($each->index_no, 0,1) == "A" || substr($each->index_no, 0,1) == "B")
					  				{
					  			

						  				echo '<tr class="caf_row initial_caf">';
						  				// echo '<td></td>';
						  				echo '<input type="hidden" class="caf_id" value="'.$each->id.'">';
						  				echo '<input type="hidden" class="caf_name" value="'.$each->name.'">';
						  				echo '<td style="width: 2%;"><input type="checkbox" class="cbx" name="" value="'.$each->id.'"></td>';
						  				echo '<td style="width: 4%;" class="index_no">'.$each->index_no.'</td>';
						  				
						  				echo '<td style="width: 2%;"></td>';
						  				echo '<td style="width: 37%;"><a target="_blank" href="'.base_url().'caf/'.$each->worksheet_url.'/'.$each->id.'/'.$assignment_id.'">'.$each->name.'</td>';
						  				echo '<td style="width: 55%;"></td>';

						  				echo '</tr>';
					  				}
					  			}
					  		}
						?>
					</tbody>
				</table>
			</div>
		</div> -->


<!-- 		<div class="panel-heading collapsed" data-toggle="collapse" data-target="#planning">
			<i class="fa fa-fw fa-chevron-down"></i>
			<i class="fa fa-fw fa-chevron-right"></i><span>AUDIT PLANNING</span>
		</div>
		<div class="panel-body"> -->
		<!-- The inside div eliminates the 'jumping' animation. -->
<!-- 			<div class="collapse" id="planning">
				<table class="table table-borderless mb-one" style="width:100%; margin-bottom: 0;">
					<tbody>
						<tr>
							<td style="width: 2%;"><input type="hidden" class="cbx" name="" value="1"></td>
							<td style="width: 4%;" >-</td>
							<td style="width: 2%;"></td>
							<td style="width: 37%;"><a target="_blank" href="<?= base_url();?>caf/fs_company_info/<?= $fs_company_info_id; ?>">Company Info</a></td>
							<td style="width: 55%;"></td>
						</tr>

						<tr>
							<td style="width: 2%;"><input type="hidden" class="cbx" name="" value="1"></td>
							<td style="width: 4%;" >-</td>
							<td style="width: 2%;"></td>
							<td style="width: 37%;"><a target="_blank" href="<?= base_url();?>caf/categorization_view/<?= $assignment_id; ?>">Mapping</a></td>
							<td style="width: 55%;"></td>
						</tr>

						<tr>
							<td style="width: 2%;"><input type="hidden" class="cbx" name="" value="1"></td>
							<td style="width: 4%;" >-</td>
							<td style="width: 2%;"></td>
							<td style="width: 37%;"><a target="_blank" href="<?= base_url();?>caf/levelling_table/<?= $assignment_id; ?>">Levelling Table (Balance Sheet)</a></td>
							<td style="width: 55%;"></td>
						</tr>
					</tbody>
				</table>
			  	<table class="table table-borderless mb-one main-category" id="planning-table" style="width:100%;">
			  		<thead></thead>
					<tbody>

						<?php 
									
							if($caf_data)
							{
								foreach($caf_data as $key=>$each)
					  			{	
					  				if(substr($each->index_no, 0,1) == 'C' || substr($each->index_no, 0,1) == 'D')
					  				{
						  				echo '<tr class="caf_row initial_caf">';
						  				// echo '<td></td>';
						  				echo '<input type="hidden" class="caf_id" value="'.$each->id.'">';
						  				echo '<input type="hidden" class="caf_name" value="'.$each->name.'">';
						  				echo '<td style="width: 2%;"><input type="checkbox" class="cbx" name="" value="'.$each->id.'"></td>';
						  				echo '<td style="width: 4%;" class="index_no">'.$each->index_no.'</td>';
						  				
						  				echo '<td style="width: 2%;"></td>';
						  				echo '<td style="width: 37%;"><a target="_blank" href="'.base_url().'caf/'.$each->worksheet_url.'/'.$each->id.'/'.$assignment_id.'">'.$each->name.'</td>';
						  				echo '<td style="width: 55%;"></td>';

						  				echo '</tr>';
						  			}
					  			}
					  		}
						?>
					</tbody>
				</table>
			</div>
		</div> -->


		<div class="panel-heading" data-toggle="collapse" data-target="#working">
			<i class="fa fa-fw fa-chevron-down"></i>
			<i class="fa fa-fw fa-chevron-right"></i><span>AUDIT WORKINGS</span>
		</div>
		<div class="panel-body">
		<!-- The inside div eliminates the 'jumping' animation. -->

			<div class="collapse in" id="working">
				<table class="table table-borderless mb-one" style="width:100%; margin-bottom: 0;">
					<tbody>
						<tr>
							<td style="width: 2%;"><input type="hidden" class="cbx" name="" value="1"></td>
							<td style="width: 9%;" >-</td>
							<td style="width: 2%;"></td>
							<td style="width: 37%;"><a target="_blank" href="<?= base_url();?>caf/fs_company_info/<?= $fs_company_info_id; ?>">Company Info</a></td>
							<td style="width: 50%;"></td>
						</tr>

						<tr>
							<td style="width: 2%;"><input type="hidden" class="cbx" name="" value="1"></td>
							<td style="width: 9%;" >-</td>
							<td style="width: 2%;"></td>
							<td style="width: 37%;"><a target="_blank" href="<?= base_url();?>caf/categorization_view/<?= $assignment_id; ?>">Mapping</a></td>
							<td style="width: 50%;"></td>
						</tr>

						<tr>
							<td style="width: 2%;"><input type="hidden" class="cbx" name="" value="1"></td>
							<td style="width: 9%;" >-</td>
							<td style="width: 2%;"></td>
							<td style="width: 37%;"><a target="_blank" href="<?= base_url();?>caf/levelling_table/<?= $assignment_id; ?>">Levelling Table (Balance Sheet)</a></td>
							<td style="width: 50%;"></td>
						</tr>
					</tbody>
				</table>
			  	<table class="table table-borderless mb-one main-category" id="workings-table" style="width:100%;">
			  		<thead></thead>
					<tbody>
<!-- 						<tr>
							<td style="width: 2%;"><input type="hidden" class="cbx" name="" value="1"></td>
							<td style="width: 4%;" >-</td>
							<td style="width: 2%;"></td>
							<td style="width: 37%;"><a href="<?= base_url();?>caf/categorization_view/<?= $assignment_id; ?>">Categorization</a></td>
							<td style="width: 55%;"></td>
						</tr> -->
						<?php 
									
							if($caf_data)
							{
								foreach($caf_data as $key=>$each)
					  			{	
					  				// if($each->type_id != '16')
					  				// {
						  				echo '<tr class="caf_row initial_caf">';
						  				// echo '<td></td>';
						  				echo '<input type="hidden" class="caf_id" value="'.$each->id.'">';
						  				echo '<input type="hidden" class="caf_name" value="'.$each->name.'">';
						  				echo '<td style="width: 2%;"><input type="checkbox" class="cbx" name="" value="'.$each->id.'"></td>';
						  				echo '<td style="width: 9%;" class="index_no">'.$each->index_no.'</td>';
						  				
						  				echo '<td style="width: 2%;"></td>';
						  				echo '<td style="width: 37%;"><a target="_blank" href="'.base_url().'caf/'.$each->worksheet_url.'/'.$each->id.'/'.$assignment_id.'">'.$each->name.'</td>';
						  				echo '<td style="width: 50%;"></td>';

						  				echo '</tr>';
						  			// }
						  			// else
						  			// {
						  			// 	echo '<tr class="caf_row initial_caf">';
						  			// 	// echo '<td></td>';
						  			// 	echo '<input type="hidden" class="caf_id" value="'.$each->id.'">';
						  			// 	echo '<input type="hidden" class="caf_name" value="'.$each->name.'">';
						  			// 	echo '<td style="width: 2%;"><input type="checkbox" class="cbx" name="" value="'.$each->id.'"></td>';
						  			// 	echo '<td style="width: 4%;" class="index_no">'.$each->index_no.'</td>';
						  				
						  			// 	echo '<td style="width: 2%;"></td>';
						  			// 	echo '<td style="width: 37%;"><a href="javascript:void(0)" onclick="get_sheet_id()">'.$each->name.'</td>';
						  			// 	echo '<td style="width: 55%;"></td>';

						  			// 	echo '</tr>';
						  			// }
						  			
					  			}
					  		}
						?>
					</tbody>
				</table>
			</div>
		</div>
		
	</section>
</section>
<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>

<!-- Modal to EDIT -->
<div class="modal fade" id="edit_modal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>EDIT</strong>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
      </div>
      <div class="modal-body">
      	<form id="edit_caf_line">
	      	<input type="hidden" id="selected_caf_id"  name="selected_caf_id">
	      	<div style="width: 100%;" class="form-inline">
	       
		        	<label style="width: 30%;">Index:</label>
		        	<input type="text" name="edited_index" class="edit_index form-control" val="" style="display: inline-block; width: 60%;margin-right: 10px;" required="" /> 

	        </div>
	        <br>
	        <div style="width: 100%;" class="form-inline">
	       
		        	<label style="width: 30%;">CAF Name:</label>
		        	<input type="text" name="edited_name" class="edit_name form-control" val="" style="display: inline-block; width: 60%;margin-right: 10px;" required="" /> 

	        </div>
	    </form>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="btn_save_new_caf" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF rename modal -->


<!-- Modal to add AWP -->
<!-- <div class="modal fade" id="edit_modal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>AUDIT WORKING PAPER</strong>
      </div>
      <div class="modal-body">
      	<form id="audit_awp_info_form">
	      	<div style="width: 100%;" class="form-inline">
	       
		        	<label style="width: 30%;">Index:</label>
		        	<input type="text" name="caf_index" class="caf_index form-control" val="" style="display: inline-block; width: 60%;margin-right: 10px;" required="" /> 

	        </div>
	        <br>
	        <div style="width: 100%;" class="form-inline">
	       
		        	<label style="width: 30%;">CAF Name:</label>
		        	<input type="text" name="caf_name" class="caf_name form-control" val="" style="display: inline-block; width: 60%;margin-right: 10px;" required="" /> 

	        </div>
	    </form>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="btn_save_new_caf" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div> -->
<!-- END OF rename modal -->


<?php include('application/modules/caf/template/adjustment_popup.html'); ?>
<?php include('application/modules/caf/template/addline_popup.html'); ?>


<script type="text/javascript">
	var pathArray = location.href.split( '/' );
	var protocol = pathArray[0];
	var host = pathArray[2];
	var folder = pathArray[3];
	var priority_row=[], priority_row_html=[], priority_row_first_alphabet =[];

	var active_tab = <?php echo json_encode(isset($active_tab)?$active_tab:"auditor_list") ?>;
	var auditor_list = <?php echo json_encode(isset($auditor_list)?$auditor_list:"") ?>;
	var bank_confirm_setting = <?php echo json_encode(isset($bank_confirm_setting)?$bank_confirm_setting:"") ?>;

	var adjustment_caf_id = '<?php echo $adjustment_caf_id?>';
	var assignment_id 	  = '<?php echo $assignment_id?>';
	var reserved_je_no = [];

	var arr_deleted_info = [];

	var save_adjustment_url   = "<?php echo site_url('caf/save_adjustment'); ?>";
	var add_programme_caf_url = "<?php echo site_url('caf/add_programme_caf'); ?>";
	var add_awp_caf_url 	  = "<?php echo site_url('caf/add_awp_caf'); ?>";
	var add_leadsheet_caf_url 	  = "<?php echo site_url('caf/add_leadsheet_caf'); ?>";
	var get_je_no_url  		  = "<?php echo site_url('caf/get_je_no'); ?>";
	var delete_caf_line_url   = "<?php echo site_url('caf/delete_caf_line'); ?>";
	var update_caf_line_url   = "<?php echo site_url('caf/update_caf_line'); ?>";
	var check_avail_caf_index_url = "<?php echo site_url('caf/check_avail_caf_index'); ?>";
	var get_sheet_id_url 	  = "<?php echo site_url('caf/get_sheet_id'); ?>";
	var get_leadsheet_list_url 	  = "<?php echo site_url('caf/get_leadsheet_list'); ?>";
	
	// var type_of_leave_list = <?php echo json_encode(isset($type_of_leave_list)?$type_of_leave_list:"") ?>;
	// var block_leave_list = <?php echo json_encode(isset($block_leave_list)?$block_leave_list:"") ?>;
	// var holiday_list = <?php echo json_encode(isset($holiday_list)?$holiday_list:"") ?>;
	var pv_index_tab_aktif;
	var startDate = new Date();

	var awp_form_is_valid = true;

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



</script>

<script src="<?= base_url()?>application/modules/caf/js/caf.js" charset="utf-8"></script>
<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>