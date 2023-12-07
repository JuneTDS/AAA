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
<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootbox/bootbox.min.css" /> -->

<link rel="stylesheet" href="<?= base_url() ?>node_modules/jstree/dist/themes/default/style.min.css" />
<script type="text/javascript" src="<?= base_url() ?>node_modules/jstree/dist/jstree.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>node_modules/jstree/src/misc.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/vendor/jstree-grid-master/jstreegrid.js"></script>

<style type="text/css">
	#addprogramme_popup .select2-container {
	    display: inline-block !important;
	}

	.jstree-grid-wrapper a
	{
		color: black !important;
	}

	.vakata-context { 
	    z-index:999 !important; 
	}

	.acenter
	{
	    /*text-align: right;*/
	    color: green;
	}
</style>

<section role="main" class="content_section" style="margin-left:0;">
	<header class="panel-heading">
		<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?> 
			<div class="panel-actions">
				<!-- <a class="create_audit_programme amber" href="<?= base_url();?>setting/add_audit_programme" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create New Audit Programme" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add New Audit Programme</a> -->
				<a class="create_audit_programme amber" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create New Audit Programme" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add New Audit Programme</a>
			</div>
			<h2></h2>
		
		<?php } ?> 
	</header>
	<section class="panel">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="tabs">				
					<ul class="nav nav-tabs nav-justify">
						<li class="active check_state" data-information="type_of_leave">
							<a href="#w2-audit_programme" data-toggle="tab" class="text-center">
								Audit Programme
							</a>
						</li>
						<li class="check_state" data-information="">
							<a href="#w2-archived_programme" data-toggle="tab" class="text-center">
								Archived Programme
							</a>
						</li>
						<li class="check_state" data-information="">
							<a href="#w2-new_acc_ref_id_mapping" data-toggle="tab" class="text-center">
								Mapping
							</a>
						</li>
					</ul>
					<div class="tab-content clearfix">
						<div id="w2-audit_programme" class="tab-pane active">
							<form id="type_of_leave_submit">
						            <table class="table table-bordered table-striped mb-none datatable-programme" id="datatable-programme" style="width:100%">
										<thead>
											<tr style="background-color:white;">
												<th class="text-left" style="width:20%;">Audit programme index</th>
												<th class="text-left" style="width:40%;">Audit programme title</th>
												<th class="text-center" style="width:20%;">Last updated by</th>
												<th class="text-center" style="width:20%;"></th>
												
											</tr>
										</thead>
										<tbody>
											<?php 
												if($programme_list)
												{
													foreach($programme_list as $programme)
										  			{
										  				// print_r($programme);
										  				if($programme->programme_type == 1)
										  				{
										  					$audit_programme_link = "add_audit_programme";
										  				}
										  				else if($programme->programme_type == 2)
										  				{
										  					$audit_programme_link = "add_audit_programme_yn";
										  				}
										  				else if($programme->programme_type == 3)
										  				{
										  					$audit_programme_link = "add_audit_programme_qa";
										  				}
										  				else if($programme->programme_type == 4 || $programme->programme_type == 5 || $programme->programme_type == 6 || $programme->programme_type == 7 || $programme->programme_type == 8 || $programme->programme_type == 9)
										  				{
										  					$audit_programme_link = "add_audit_programme_only_master/".$programme->programme_type;
										  				}


										  				echo '<tr>';
										  				echo '<td>'.$programme->programme_index.'</td>';
										  				echo '<td>'.$programme->title.'</td>';
										  				echo '<td style="text-align:center;">'.$programme->user_name.'</td>';
										  				echo '<td style="text-align:center;">
										  						<input type="hidden" class="programme_id" value="'. $programme->id .'" />
										  						<a type="button" class="btn btn_blue" href="'.base_url().'setting/'.$audit_programme_link.'/'. $programme->id .'">Edit</a>
										  						<button type="button" class="btn btn_blue" onclick=archive_programme(this)>Archive</button>
										  					  </td>';
										  				echo '</tr>';
										  			}
												}
												
											?>
										</tbody>
									</table>
								</form>
							</div>

							<div id="w2-archived_programme" class="tab-pane">
					            <table class="table table-bordered table-striped mb-none datatable-archived-programme" id="datatable-archived-programme" style="width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-left" style="width:40%;">Audit programme index</th>
											<th class="text-center" style="width:25%;">Archived at</th>
											<th class="text-center" style="width:25%;">Last updated by</th>
											<th class="text-center" style="width:10%;"></th>
											
										</tr>
									</thead>
									<tbody>
										<?php 
											if($archived_programme_list)
											{

												foreach($archived_programme_list as $archived_programme)
									  			{
									  				if($archived_programme->programme_type == "1")
									  				{
									  					$audit_programme_link = "add_audit_programme";
									  				}
									  				else if($archived_programme->programme_type == "2")
									  				{
									  					$audit_programme_link = "add_audit_programme_yn";
									  				}
									  				else if($archived_programme->programme_type == "3")
									  				{
									  					$audit_programme_link = "add_audit_programme_qa";
									  				}
									  				else if($archived_programme->programme_type == "4" || $archived_programme->programme_type == "5" || $archived_programme->programme_type == "6" || $archived_programme->programme_type == "7" || $archived_programme->programme_type == "8" || $archived_programme->programme_type == "9")
									  				{
									  					$audit_programme_link = "add_audit_programme_only_master/".$archived_programme->programme_type;
									  				}
									  				echo '<tr>';
									  				echo '<td>'.$archived_programme->programme_index.'</td>';
									  				echo '<td style="text-align:center;">'.$archived_programme->archived_at.'</td>';
									  				echo '<td style="text-align:center;">'.$archived_programme->user_name.'</td>';
									  				echo '<td style="text-align:center;">
									  						<input type="hidden" class="programme_id" value="'. $archived_programme->id .'" />
									  						<a type="button" class="btn btn_blue" href="'.base_url().'setting/'.$audit_programme_link.'/'. $archived_programme->id .'">Edit</a>
									  					  </td>';
									  				echo '</tr>';
									  			}
											}
										?>
									</tbody>
								</table>
							</div>

							<div id="w2-new_acc_ref_id_mapping" class="tab-pane">
								<div class="col-xs-12">
									<!-- <div style="display: inline-block;"> -->
									<div class="col-xs-3">
							       		<h3>Mapping Default List</h3>
							      	</div>

							      	<!-- <div style="display: inline-block; padding: 10px;"> -->
							      	<div class="col-xs-2" style="padding: 10px;">

							       		<button class="btn btn-primary" onclick="save_fs_default_acc_list()">Save</button>
							      	</div>
								</div>

								<hr />

								<div class="col-xs-12">
									<div id="mapping_default_Treeview" style="padding-right: 20px;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</section>

<!-- Add audit programme pop up -->
<div class="modal fade" id="addprogramme_popup" tab-index="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel"><b>Add audit programme</b></h5>
      </div>
      
      	<form id="add_programme_form">
	      	<div class="modal-body">
	      		<div style="width: 100%;display: inline-block !important;" class="form-inline type-form">
	     
		        	Types: 
		        	<!-- <select class="select2" style="display: inline-block;width: 60%;"></select> -->
		        	
					<?php
						echo form_dropdown('programme_type', $programme_type_dropdown, '', 'class="programme_type select2" style="display: inline-block !important;width: 88%;" required')
					?>

							
	        	</div>
	   	  	</div>
	   	</form>

	  <!-- </form> -->
	    
      <div class="modal-footer">
        	<button type="button" class="btn btn-primary" name="addProgramme" id="addProgramme">Add</button>
			<a href="javascript:void(0)" class="btn btn-default" id="cancel_addline" data-dismiss="modal">Cancel</a>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">

	var active_tab 		   = <?php echo json_encode(isset($active_tab)?$active_tab:"type_of_leave") ?>;
	var type_of_leave_list = <?php echo json_encode(isset($type_of_leave_list)?$type_of_leave_list:"") ?>;
	var block_leave_list = <?php echo json_encode(isset($block_leave_list)?$block_leave_list:"") ?>;
	var holiday_list 	 = <?php echo json_encode(isset($holiday_list)?$holiday_list:"") ?>;
	var pv_index_tab_aktif;
	var setting_url = "<?php echo site_url('setting'); ?>";
	var audit_programme_url = "<?php echo site_url('setting/add_audit_programme'); ?>";
	var audit_programme_yn_url = "<?php echo site_url('setting/add_audit_programme_yn'); ?>";
	var audit_programme_qa_url = "<?php echo site_url('setting/add_audit_programme_qa'); ?>";
	var audit_programme_only_master_url = "<?php echo site_url('setting/add_audit_programme_only_master'); ?>";

	// Mapping tab
	var setting_load_default_mapping = "<?php echo site_url('setting/load_fs_default_acc_category'); ?>";
	var save_fs_default_acc_list_url = "<?php echo site_url('setting/save_fs_default_acc_list'); ?>";
</script>

<script src="<?= base_url()?>application/modules/setting/js/setting.js" charset="utf-8"></script>
