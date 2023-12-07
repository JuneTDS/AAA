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
			
		<section class="panel" style="margin-top: 0px;">
			<div class="panel-body">
				<div class="col-md-12">
					<div id="w2-auditor_list" class="tab-pane active">	
			            <table class="table table-bordered table-striped mb-none datatable-paf" id="datatable-paf" style="width:100%">
							<thead>
								<tr style="background-color:white;">
									<th class="text-center">Description</th>
									<th class="text-center">Status</th>
									<th class="text-center">Access rights</th>
									<th class="text-center">PIC</th>
									<th class="text-center">Signed by | on</th>
									<th class="text-center">Job Type</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="width:22.5%;"><a href="<?=site_url('client/edit/'.$client->id);?>" class="pointer mb-sm mt-sm mr-sm">Profile</a></td>
									<td align="center" style="width:10%;">N/A</td>
									<td align="center" style="width:15%;">Read Only</td>
									<td align="center" style="width:15%;">N/A</td>
									<td align="center" style="width:22.5%;">N/A</td>
									<td align="center" style="width:15%;">N/A</td>
								</tr>
								<tr>
									<td style="width:22.5%;"><a href="<?=site_url('client/paf/'.$client->id);?>" class="pointer mb-sm mt-sm mr-sm">Permanent Audit File</a></td>
									<td align="center" style="width:10%;">N/A</td>
									<?php if($Admin || $Manager) { ?>
										<td align="center" style="width:15%;">
											<?php echo form_dropdown('paf_rights', $rights_dropdown, 3, 'class="paf_rights" style="width:100%;text-align:center;" onchange=change_rights(this) required');?>
										</td>
									<?php } else{ ?>
										<td align="center" style="width:15%;">Read Only</td>
									<?php } ?>
									<td align="center" style="width:15%;">N/A</td>
									<td align="center" style="width:22.5%;">N/A</td>
									<td align="center" style="width:15%;">N/A</td>
								</tr>
								<?php 
									
										if($caf_list)
										{
											// print_r($caf_list);
											foreach($caf_list as $key=>$each)
								  			{	
								  			
								  				echo '<tr>';
								  				// echo '<td></td>';
								  				// if($each->status != 10)
								  				// {
									  				echo '<td style="width:22.5%;"><a href="'.base_url().'caf/index/'.$each->id.'" class="pointer mb-sm mt-sm mr-sm">Current File - '.date("d F Y", strtotime($each->FYE)).'</a></td>';
									  			// }
									  			// else
									  			// {
									  			// 	echo '<td style="width:22.5%;">Current File - '.date("d F Y", strtotime($each->FYE)).'</td>';
									  			// }
								  				echo '<td align="center" style="width:10%;">'.$each->assignment_status.'</td>';
								  				if($Admin || $Manager) 
								  				{
								  					echo '<td align="center" style="width:15%;">';
													echo form_dropdown('paf_rights', $rights_dropdown, 3, 'class="paf_rights" style="width:100%;text-align:center;" onchange=change_rights(this) required');
													echo '</td>';
								  				}
								  				else
								  				{
								  					echo '<td align="center" style="width:15%;">Read Only</td>';
								  				}
								  				echo '<td align="center" style="width:15%;">'.strtoupper(json_decode($each->PIC)->leader).'<a class="toggle_morepic" data-id="'.$each->id.'">&nbsp;<i>more<i></a></td>';
								  				if($each->signed == 1) 
								  				{
								  					echo '<td align="center" style="width:22.5%;">'.$each->partner .' | '.date("d-m-Y", strtotime($each->report_date)).'</td>';
								  				}
								  				else
								  				{
								  					echo '<td align="center" style="width:22.5%;"></td>';
								  				}
								  				echo '<td align="center" style="width:15%;">'.$each->job.'</td>';
								  				

								  				// echo '<td style="width:30%;">'.$each->fye_date.'</td>';
								  				// echo '<td style="width:15%;">'.($each->auditor_id?"<table class='table table-bordered table-striped mb-none'>".$rowAuditorName."</table>":"<b>Not Arranged</b>").'</td>';
								  				// echo '<td style="width:8%;">'.($each->stocktake_date != "0000-00-00"?$each->stocktake_date:"<b>Not Arranged</b>").'</td>';
								  				// echo '<td style="width:8%;">'.($each->stocktake_time != "00:00:00"?$each->stocktake_time:"<b>Not Arranged</b>").'</td>';
								  				// echo '<td style="width:20%;">'.($each->stocktake_address != ""?$each->stocktake_address:"<b>Not Arranged</b>").'</td>';
								  				// echo '<td style="width:14%;">'.($each->client_pic != ""?$each->client_pic:"<b>Not Arranged</b>").'</td>';
								  				// echo '<td style="width:10%;text-align:center;">
								  				// 		<input type="hidden" class="paf_id" value="'. $each->id .'" />
														// <button type="button" class="btn btn_blue" onclick=delete_paf(this) style="margin-bottom:5px;margin-right:5px;margin-left:5px;">Delete</button></td>';
								  				echo '</tr>';
								  			}
										}
										
									?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</section>
						
		</div>
	</div>


	
	<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>
<!-- end: page -->
</section>

<!-- Show More PIC Pop up -->
<div id="more_pic_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  data-backdrop="static" style="margin-top:150px;">
	<div class="modal-dialog" >
		<div class="modal-content">
			<header class="panel-heading">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h2 class="panel-title">Upload</h2>
			</header>
	
			<div class="panel-body">
				<div class="col-md-12">
					<table class="table table-bordered table-striped table-condensed mb-none" id="pic_table">
						
					</table>

				<!-- 	<table id="clone_model" >
						<tr class="tr_pic">
	 						<th style="width: 40%;">
	    						<p id="role"></p>
	   						</td>
	   						<td style="width:60%">
	   							<p id="name"></p>
	   						</td>
	    				</tr>
						
					</table> -->
				</div>
			</div>
			<div class="modal-footer">
				
				<input type="button" class="btn btn-default ba_upload_cancel" data-dismiss="modal" name="" value="Close">
			</div>
		
		</div>
	</div>
</div>




<script type="text/javascript">

	var caf_list = <?php echo json_encode(isset($caf_list)?$caf_list:"") ?>;
	var save_first_letter_url = "<?php echo site_url('list_of_auditor/save_first_letter'); ?>";
	var save_paf_url = "<?php echo site_url('paf_upload/save_paf'); ?>";
	var paf_all_url = "<?php echo site_url('paf_upload/pafAllData'); ?>";
	var generate_first_letter_url = "<?php echo site_url('list_of_auditor/generate_first_letter'); ?>";
	var generate_auth_document_url = "<?php echo site_url('bank/generate_auth_document'); ?>";
	var auditor_url = "<?= base_url();?>list_of_auditor";

	var base_url = window.location.origin;  

	$(document).keydown(function(event) { 
		if (event.keyCode == 27) { 
			$('.modal').modal("hide");
		}
	});


	// console.log(edit_bank_auth[0]["auth_date"]);

</script>

<script src="<?= base_url()?>application/modules/client/js/audit_client.js" charset="utf-8"></script>