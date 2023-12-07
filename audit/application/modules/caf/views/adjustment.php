<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrapvalidator/dist/css/bootstrapValidator.min.css"/> -->

<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<!-- <script src="<?= base_url()?>application/js/bootstrapValidator.min.js" /></script> -->

<!-- <script src="<?= base_url() ?>node_modules/jquery.js"></script> -->
<script src="<?= base_url() ?>node_modules/jquery-validation/dist/jquery.validate.js"></script>

<!-- jquery-ui -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

<script src="<?= base_url() ?>node_modules/bootstrap/dist/js/bootstrap.min.js"></script> -->

<style type="text/css">
	#adjustment {
	  position: relative;

	}

	#adjustment .modal-dialog {
	  position: fixed;
	  width: 60%;
	  margin: 0;
	  padding: 10px;
	  color: #154069;
	  max-height: 450px;
	  overflow-y: auto;
	  overflow-x: hidden;
	}

	.adjustment_form .select2-container
	{
		display: inline-block !important;
		margin-bottom: 10px;

	}

	.modal-open 
	{ 
		overflow-y: auto; 
	}

	.bordered 
	{
		border-bottom:1px solid grey !important;
	}

	.custom-header
	{
		background-color: #446687;
		color: white;
		text-align: center;
	}

/*	.tooltip {
	    z-index: 100000000; 
	}*/
	#adjustment_display_body td
	{
		border-top: none !important;
	}

	

	.no_bottom 
	{
		border-bottom: none !important;
		
	}

	.type-form .select2-container
	{
		margin-right: 10px;
	}

	#body_add_adjustment .select2 {
		width:100%!important;
	}

	#adjustment_tbl
	{
		table-layout: fixed;
	}

/*	#adjustment_tbl .select2-container
	{
		white-space: nowrap;
		width: 100% !important;
	}

*/

</style>


<section role="main"  class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 0px;">
		<?php
			if($show_data_content)
			{
				echo form_dropdown('account_id', $accounts_dropdown, '', 'id="account_dropdown_clone" style="display:none;width:100%;"');
		?>
		<div class="row form-inline" style="margin:15px; padding-left: 15px;padding-right: 15px;">
			<div class="type_filter_div">
        		
        		Type: 
				<?php
					echo form_dropdown('type_filter', $types_dropdown, '', 'class="form-control type_filter" ');
				?> 
			</div>
		</div>
				

		<table class="table table-borderless adjustment_display" style="border-collapse: collapse; margin: 2%; width: 70%; color:black">
			<thead>
				<tr >
					<th class="bordered custom-header" width="55%" style="vertical-align: middle !important;" colspan="2">Description</th>
					<th class="bordered custom-header" width="15%">DR<br>$</th>
					<th class="bordered custom-header" width="15%">CR<br>$</th>
					<th class="bordered custom-header" width="15%" style="vertical-align: middle !important;">Reference</th>
				</tr>
			</thead>
			<tbody id="adjustment_display_body">
				
			</tbody>

		
		</table>
		<?php 
				}
				else
				{
					echo '<p style="text-align:center; font-weight: bold; font-size: 20px; margin-top:50px;">Account Category is not managed!</p>' .
						 '<p style="text-align:center; font-weight: bold; font-size: 14px; margin-top:10px;">Please complete Account Category before preview this document!</p>';
				}
			?>
		
		<!-- </form> -->
	</section>
</section>


<div class="modal fade" id="adjustment" tab-index="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false">
  <div class="modal-dialog" role="document">
  	<form id="adjustment_form">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel"><b>ADJUSTMENTS</b></h5>
      </div>
      
	      <div class="modal-body">
	      	<input type="hidden" name="adjustment_master_id" id="adjustment_master_id" value="">
	      	<input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">

	      	<div style="width: 100%;display: inline-block !important;" class="adjustment_form form-inline type-form">
	     
		        	Types: 
		        	<!-- <select class="select2" style="display: inline-block;width: 60%;"></select> -->
		        	
					<?php
						echo form_dropdown('adjustment_type', $types_dropdown, '', 'class="adjustment_type select2" style="display: inline-block;width: 60%;" id="special_select" required')
					?>

							
	        </div>
	        <div style="width: 100%;" class="form-inline">
	       
		        	JE No:
		        	<input type="number" min="0" name="je_no" class="je_no form-control" val="" style="display: inline-block; width: 60%;margin-right: 10px;" required="" /> 
		        	<input type="hidden" min="0" name="je_no_hidden" class="je_no_hidden form" value="" />

	        </div>
	        <div style="width: 100%;">
	        	<a class="amber" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;float: right;top:50px;" data-original-title="Add adjustment line"  onclick=add_adjustment_line() ><i class="fa fa-plus-circle amber" style="font-size:13px;"></i> Add line</a>

	        	<table class="table table-bordered table-striped table-condensed mb-none" id="adjustment_tbl">
	        		<thead>
	        			<tr>
	        				<th style="width: 56%;" class="text-center">Account</th>
	        				<th style="width: 20%;" class="text-center">Dr/(Cr)</th>
	        				<th style="width: 20%;" class="text-center">Reference</th>
	        				<th style="width: 4%;" class="text-center"></th>
	        			</tr>
	        		</thead>
	        		<tbody id="body_add_adjustment">
	        			<tr>
	        				<!-- <td><select class="select2" style="width: 100%;"></select></td> -->
	        				<input type="hidden" class="adjustment_info_id" name="adjustment_info_id[]"  />
	        				<td>
	        				<?php
	        					// print_r($accounts_dropdown);
								echo form_dropdown('account_id[]', $accounts_dropdown, '', 'class="account_dropdown form-control select2" style="width: 100%;"');
							?> 
							</td>
	        				<td><input type="number" name="adjustment_value[]" class="form-control adjustment_value" style="width: 100%;" /></td>
	        				<td><input type="text" name="reference[]" class="form-control reference" style="width: 100%;" /></td>
	        				<td>
		    					<a class="amber remove_adjustment_line" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;float: right;top:50px;" data-original-title="Remove this line"  onclick=remove_adjustment_line(this) ><i class="fa fa-minus-circle amber" style="font-size:13px;"></i></a>
		    				</td>
	        			</tr>

	        			
	        		</tbody>
	        	</table>


	        </div>
	        <br>
	        <div style="width: 100%;">
		        	Narration
		        	<textarea name="narration" class="form-control" rows="1" required></textarea>
	
	        </div>

	      </div>
	  <!-- </form> -->
	    
      <div class="modal-footer">
        	<button type="button" class="btn btn-primary" name="saveAdjustment" id="saveAdjustment">Save</button>
			<a href="javascript:void(0)" class="btn btn-default" id="cancel_adjustment">Cancel</a>
      </div>
    </div>
	</form>
  </div>
</div>

<table id="clone_model" style="display: none;" >
	<tr>
		<input type="hidden" name="adjustment_info_id[]">
		<td>
			<?php
				// print_r($accounts_dropdown);
				echo form_dropdown('account_id[]', $accounts_dropdown, '', 'class="account_dropdown form-control" style="width: 100%;"');
			?> 
		</td>
		<td><input type="number" name="adjustment_value[]" class="form-control adjustment_value" style="width: 100%;" /></td>
		<td><input type="text" name="reference[]" class="form-control" style="width: 100%;" /></td>
		<td>
			<a class="amber remove_adjustment_line" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;float: right;top:50px;" data-original-title="Remove this line"  onclick=remove_adjustment_line(this) ><i class="fa fa-minus-circle amber" style="font-size:13px;"></i></a>
		</td>
	</tr>
</table>

<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>


<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';

	var save_adjustment_url = "<?php echo site_url('caf/save_adjustment'); ?>";
	var get_adjustment_data_url = "<?php echo site_url('caf/get_adjustment_data'); ?>";
	var get_je_no_url  = "<?php echo site_url('caf/get_je_no'); ?>";
	var delete_adjustment_master_url = "<?php echo site_url('caf/delete_adjustment_master'); ?>";
	var export_adjustment_pdf_url = "<?php echo (site_url('caf/export_adjustment_pdf')).'/'.$caf_id .'/'.$assignment_id; ?>";

	var caf_id = '<?php echo $caf_id?>';
	var account_dropdown_arr = "<?php echo htmlspecialchars(json_encode($accounts_dropdown))?>";
	var show_data_content = '<?php echo $show_data_content ?>';

	var reserved_je_no = [];
	var adjustment_data = [];
	var arr_deleted_info = [];

	console.log("update");

	// console.log(adjustment_data);

</script>

<script src="<?= base_url()?>application/modules/caf/js/adjustment.js" charset="utf-8"></script>