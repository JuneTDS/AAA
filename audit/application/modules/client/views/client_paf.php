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

<!-- own script -->
<script type="text/javascript">
	function load_index(){
	
		var parent_index = 1;
		var child_index = 1;

		$('#datatable-paf > tbody  > tr').each(function(index, tr) { 
		   // console.log(tr);
		   // console.log(tr.firstElementChild);

		   if($(tr.firstElementChild).hasClass('child'))
		   {
		   		$(this).find('.child').html("PAF " + (parent_index-1) + "." + child_index);
		   		$(tr).find('[name ="c_index_no[]"]').val((parent_index-1) + "." + child_index);
		   		child_index++;
		   }

		   if($(tr.firstElementChild).hasClass('parent'))
		   {
		   		$(tr).attr('id', 'paf'+parent_index);
		   		// console.log($(tr).find('[name ="p_form_id[]"]').val('paf'+parent_index));
		   		$(tr).find('[name ="p_form_id[]"]').val('paf'+parent_index);
		   		$(tr).find('[name ="p_index_no[]"]').val(parent_index);
		   		// console.log(this);
		   		$(this).find('.parent').html("PAF " + parent_index);

		   		

		   		parent_index++;
		   		child_index = 1;
		   		// return false;
		   }


		});
	}

	function submit_paf_no_reload()
    {
        var form = $("#create_paf_form");
        // console.log(JSON.stringify(uploadedFiles));

        $.ajax({
           type: "POST",
           url: "<?= base_url();?>client/submit_paf_list",
           data: form.serialize(), // serializes the form's elements.
           dataType: "JSON",
           // data : { 'uploaded_docs' : JSON.stringify(uploadedFiles)},
           success: function(data)
           {    
                if(data){
                    // toastr.success('Information Updated', 'Updated');
                    // location.reload();
                }
               // alert(data); // show response from the php script.
               // $('#applicant_resume').fileinput('upload');
           }
        });
        // e.preventDefault(); // avoid to execute the actual submit of the form.
    }

	</script>
<!-- <script src="<?= base_url()?>application/modules/client/js/client_paf.js" charset="utf-8"></script> -->

<style>
	.select2-container
	{
		display: inline-block !important;
		min-width: 112px !important;  

	}

	.desc ,.action{
		color: #154069;
	}

	.parent-tr .desc{
	  /* Set "subsection" to 0 */
	  counter-reset: subsection;
	  font-weight: bold;

	}

	.parent-fixed > .delete-main, .parent-fixed > .rename-main, .parent-fixed > .archive-main
	{
		display: none;
	}

	.child-fixed> .delete-sub, .child-fixed > .upload, .child-fixed > .rename-sub
	{
		display: none;
	}

	.action > a 
	{
		margin-left: 5px;
		margin-right: 5px;
	}

	#paf_doc_modal a:visited
	{
		color: purple !important; 
	}

	#datatable-paf td, #datatable-paf-archived td
	{
		height: 45px;
		vertical-align: middle;
	}

	.review_point_icon
	{
		float: right;
		margin-right: 50px;
		display: inline-block;
	}

	.review_point_icon > a
	{
		display: none;
	}

	.rp_cleared
	{
		text-decoration: line-through;
	}

	.disable {
	    cursor: not-allowed;
	    color: #A9A9A9 !important;
	}

	.disable:active {
	    pointer-events: none;
	}





</style>


<section role="main" class="content_section" style="margin-left:0;">
	<?php echo $breadcrumbs;?>
<!-- 	<header class="panel-heading">
		<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
			<div class="panel-actions">
				<a class="create_main_category amber" href="<?= base_url();?>bank/add_bank_auth" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;display:none;" data-original-title="Add Main Category" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add Main Category</a>
			</div>
			<h2></h2>
		<?php } ?>
	</header>
 -->
	<section class="panel" style="margin-top: 0px;">

		<div class="panel-body">
			<div class="col-md-12">
				<div class="tabs">				
					<ul class="nav nav-tabs nav-justify">
						<li class="active check_state" data-information="active_paf_list">
							<a href="#w2-active_paf_list" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">1</span>
								Active
							</a>
						</li>
						<li class="check_state" data-information="archive_paf_list">
							<a href="#w2-archive_paf_list" data-toggle="tab" class="text-center">
								<span class="badge hidden-xs">2</span>
								Archive
							</a>
						</li>
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
						<div id="w2-active_paf_list" class="tab-pane active">
							<div class="panel-actions" style="position: relative;float: right;top:0;">
								<a class="create_review_point amber" href="javascript:void(0);" onclick="show_review_point()" data-toggle="tooltip" data-trigger="hover" style="height:32px;font-weight:bold;display:none;" data-original-title="Add Review Point" ><i class="fa fa-sticky-note amber" style="font-size:16px;height:32px;"></i> Add Review Point</a>
								<span style="padding-left: 15px;"></span>
								<a class="create_main_category amber" href="javascript:void(0);" onclick="add_main_category()" data-toggle="tooltip" data-trigger="hover" style="height:32px;font-weight:bold;display:none;" data-original-title="Add Main Category" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:32px;"></i> Add Main Category</a>
								
							</div>
							<form id="create_paf_form" autocomplete="off">
								<input type="hidden" name="company_code" id="company_code" value="<?=$client->company_code ?>">
								<table class="table table-bordered table-striped mb-none datatable-paf" id="datatable-paf" style="width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-center">Index</th>
											<th class="text-center">Description</th>
											<th class="text-center">Last update</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php 
									
											if($paf_data)
											{
												foreach($paf_data as $key=>$each)
									  			{	
									  			
									  				echo '<tr class="parent-tr" id="'.$each->form_id.'">';
									  				// echo '<td></td>';
									  				echo '<td style="width:7%;" class="parent"></td>';
									  				echo '<td style="width:53%;" class="desc"><input type="hidden" name="p_text[]" value="'.$each->text.'">'.$each->text.'</td>';
									  				
									  				echo '<td align="center" style="width:15%;"></td>';
									  				echo '<td align="right" style="width:25%;" class="action parent-'.$each->type.'">
									  						<input type="hidden" name="p_type[]" value="'.$each->type.'">
															<a class="add-sub" href="javascript:void(0);" onclick="add_sub_category(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Add Sub" >
																<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
															</a>
															<a class="delete-main" href="javascript:void(0);" onclick="delete_paf(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Delete" >
																<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
															</a>
															<a class="rename-main" href="javascript:void(0);" onclick="rename_node(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Rename" >
																<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
															</a>
															<a class="archive-main" href="javascript:void(0);" onclick="archive_parent(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Archive" >
																<span class="glyphicon glyphicon-inbox" aria-hidden="true"></span>
															</a>
														</td>';
									  				echo '<input type="hidden" name="p_id[]" value="'.$each->id.'"><input type="hidden" name="p_form_id[]" value="'.$each->form_id.'"><input type="hidden" name="p_index_no[]" value="'.$each->index_no.'">';
									  				echo '</tr>';

									  				foreach($each->child as $ckey => $c_each)
									  				{
									  					if (array_key_exists($c_each['id'], $paf_logs))
														{
															$update_data = "<p style='font-size:11px;white-space: pre-line;margin:0;'>".$paf_logs[$c_each['id']]['user_name']."\n<span style:font-size:9px;>".str_replace(" ", " | ", $paf_logs[$c_each['id']]['date_time'])."</span></p>";
														}
														else
														{
															$update_data = "-";
														}
									  					echo '<tr class="child-tr">';
									  					echo '<td style="width:7%;" class="child"></td>';
									  					echo '<td style="width:53%;" class="desc" id="desc'.$c_each['id'].'"><input type="hidden" name="c_text[]" value="'.$c_each['text'].'">'.$c_each['text'].
									  					       '<div class="review_point_icon">
									  					       	  <a href="javascript:void(0)" class="no_doc"><img src="'.base_url().'img/X_icon.png"  style="width:25px;height:25px;"></a>
									  					          <a href="#review_point_section" class="rp view_rp"><img src="'.base_url().'img/R_icon2.png"  style="width:25px;height:25px;"></a>
									  					          <a href="#review_point_section" class="cleared_rp view_rp"><img src="'.base_url().'img/R_strike_icon2.png"  style="width:25px;height:25px;"></a>
									  					          <input type="hidden" class="c_id" value="'.$c_each['id'].'">
									  					        </div>
									  					      </td>';
									  					echo '<td align="center" style="width:15%;">'.$update_data.'</td>';
									  					echo '<td align="right" style="width:25%;" class="action child-'.$c_each['type'].'">
									  							<input type="hidden" name="c_type[]" value="'.$c_each['type'].'">
										  						<a class="view" href="javascript:void(0);" onclick="view_docs(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="View" >
																	<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
																</a>
																<a class="upload" href="javascript:void(0);" onclick="open_paf_doc_modal(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Upload" >
																	<span class="glyphicon glyphicon-upload" aria-hidden="true"></span>
																</a>
																<a class="delete-sub" href="javascript:void(0);" onclick="delete_paf(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Delete" >
																	<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
																</a>
																<a class="rename-sub" href="javascript:void(0);" onclick="rename_node(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Rename" >
																	<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
																</a>
																<a class="archive-sub" href="javascript:void(0);" onclick="archive_child(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Archive" >
																	<span class="glyphicon glyphicon-inbox" aria-hidden="true"></span>
																</a>
															</td>';
														echo '<input type="hidden" name="c_id[]" value="'.$c_each['id'].'"><input type="hidden" name="c_parent_id[]" value="'.$each->id.'"><input type="hidden" name="c_temp_parent_id[]" value="'.$each->form_id.'"><input type="hidden" name="c_index_no[]" value="'.$c_each['index_no'].'"><input type="hidden" name="rename_flag[]" value=0>';
														echo '</tr>';



									  				}
									  			}
											}
											
										?>
										<script type="text/javascript">
										    load_index();
										    submit_paf_no_reload();
										</script>
										<!-- <tr class="parent-tr" id="paf1">
											<input type="hidden" name="id[]">
											<input type="hidden" name="form_id[]">
											<td style="width:5%;" class="parent"><input type="hidden" name="index_no[]"></td>
											<td style="width:40%;" class="desc"><input type="hidden" name="text[]">Audit Administration</td>
											<td align="center" style="width:15%;"></td>
											<td align="center" style="width:40%;" class="action">
												<a class="" href="javascript:void(0);" onclick="add_sub_category(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Add" >
													<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
												</a> &nbsp;
												<span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span> &nbsp;
												<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> &nbsp;
												<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> &nbsp;
												<span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> 
											</td>
										</tr>
										<tr>
											<td style="width:5%;" class="child"></td>
											<td style="width:40%;" class="desc">Test child 1</td>
											<td align="center" style="width:15%;">Read Only</td>
											<td align="center" style="width:40%;">N/A</td>
										</tr>
										<tr>
											<td style="width:5%;" class="child"></td>
											<td style="width:40%;" class="desc">Test child 2</td>
											<td align="center" style="width:15%;">Read Only</td>
											<td align="center" style="width:40%;">N/A</td>
										</tr>
										<tr class="parent-tr">
											<td style="width:5%;" class="parent"></td>
											<td style="width:40%;" class="desc">Test parent 2</td>
											<td align="center" style="width:15%;">Read Only</td>
											<td align="center" style="width:40%;">N/A</td>
										</tr>
											<tr>
											<td style="width:5%;" class="child"></td>
											<td  style="width:40%;" class="desc">Test child 2.1</td>
											<td align="center" style="width:15%;">Read Only</td>
											<td align="center" style="width:40%;">N/A</td>
										</tr>
										<tr>
											<td style="width:5%;" class="child"></td>
											<td style="width:40%;" class="desc">Test child 2.2</td>
											<td align="center" style="width:15%;">Read Only</td>
											<td align="center" style="width:40%;">N/A</td>
										</tr> -->
										<!-- <tr>
											<td style="width:25%;"><a href="<?=site_url('client/paf/'.$client->id);?>" class="pointer mb-sm mt-sm mr-sm">Permanent Audit File</a></td>
											<td align="center" style="width:15%;">N/A</td>
											<?php if($Admin || $Manager) { ?>
												<td align="center" style="width:15%;">
													<?php echo form_dropdown('paf_rights', $rights_dropdown, 3, 'class="paf_rights" style="width:100%;text-align:center;" onchange=change_rights(this) required');?>
												</td>
											<?php } else{ ?>
												<td align="center" style="width:15%;">Read Only</td>
											<?php } ?>
											<td align="center" style="width:30%;">N/A</td>
											<td align="center" style="width:15%;">N/A</td>
										</tr> -->
									</tbody>
								</table>
							</form>
							<div class="number text-right" style="margin-top:14px;">
								<!--button class="btn btn-primary modal-confirm">Confirm</button-->
								<button type="button" class="btn btn-primary" name="savePaf" id="savePaf">Save</button>
								<a href="<?= base_url();?>client/audit_client/<?= $client->id ?>" class="btn btn-default">Back</a>
							</div>
						</div>
						<div id="w2-archive_paf_list" class="tab-pane">
							<form id="archived_paf_form" autocomplete="off">
								<input type="hidden" name="company_code" value="<?=$client->company_code ?>">
								<table class="table table-bordered table-striped mb-none datatable-paf-archived" id="datatable-paf-archived" style="width:100%">
									<thead>
										<tr style="background-color:white;">
											<th class="text-center">Index</th>
											<th class="text-center">Description</th>
											<th class="text-center">Date archived</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php 
									
											if($paf_archived_data)
											{
												foreach($paf_archived_data as $key=>$each)
									  			{	


									  				echo '<tr class="parent-tr" >';
									  				// echo '<td></td>';
									  				echo '<td style="width:7%;" class="parent"></td>';
									  				echo '<td style="width:53%;" class="desc"><input type="hidden" name="p_text[]" value="'.$each->text.'">'.$each->text.'</td>';
									  				
									  				echo '<td align="center" style="width:15%;"></td>';
									  				echo '<td align="right" style="width:25%;" class="action parent-'.$each->type.'"></td>';
									  				echo '<input type="hidden" name="p_id[]" value="'.$each->id.'"><input type="hidden" name="p_form_id[]" value="'.$each->form_id.'"><input type="hidden" name="p_index_no[]" value="'.$each->index_no.'">';
									  				echo '</tr>';

									  				foreach($each->child as $ckey => $c_each)
									  				{
									  					if (array_key_exists($c_each['id'], $paf_logs))
														{
															$update_data = "<p style='font-size:11px;white-space: pre-line;margin:0;'>".$paf_logs[$c_each['id']]['user_name']."\n<span style:font-size:9px;>".str_replace(" ", " | ", $paf_logs[$c_each['id']]['date_time'])."</span></p>";
														}
														else
														{
															$update_data = "-";
														}

									  					echo '<tr class="child-tr">';
									  					echo '<td style="width:7%;" class="child"></td>';
									  					echo '<td style="width:53%;" class="desc"><input type="hidden" name="c_text[]" value="'.$c_each['text'].'">'.$c_each['text'].'</td>';
									  					echo '<td align="center" style="width:15%;">'.$update_data.'</td>';
									  					echo '<td align="right" style="width:25%;" class="action child-'.$c_each['type'].'">
									  							<input type="hidden" name="c_type[]" value="'.$c_each['type'].'">
										  						<a class="view" href="javascript:void(0);" onclick="view_docs(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="View" >
																	<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
																</a>
																<a class="restore" href="javascript:void(0);" onclick="restore_paf(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Restore" >
																	<span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>
																</a>
																<a class="delete-sub" href="javascript:void(0);" onclick="delete_paf(this)" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Delete" >
																	<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
																</a>
																
															</td>';
														echo '<input type="hidden" name="c_id[]" value="'.$c_each['id'].'"><input type="hidden" name="c_parent_id[]" value="'.$each->id.'"><input type="hidden" name="c_temp_parent_id[]" value="'.$each->form_id.'"><input type="hidden" name="c_index_no[]" value="'.$c_each['index_no'].'">';
														echo '</tr>';

														

									  				}
									  			}
											}
											
										?>
										
									</tbody>
								</table> 
							</form>
						</div>
					
					</div>
				</div>

					
				
			</div>

			

			<div class="col-md-12" id="review_point_section" style="display: none;">
				<div class="panel panel-default" style="padding:0px;">
			        
			        <div class="panel-body">
			        	<div style="margin-bottom:15px;height: 32px;display: table;width: 100%;">
			        		<span style="vertical-align: middle;display: table-cell;"><b>REVIEW POINTS</b></span>
			        		<span style="vertical-align: middle;display: table-cell;"><a href="javascript:void(0)" id="hide_rp_btn" class="btn btn-default pull-right">Hide review points</a></span>
			        	</div>
			        	<div class="form-inline" style="width:100%;margin-bottom:20px;" colspan=5>

			        		Index :
			        		<select class="form-control search_indexno" name="search_indexno" id="search_indexno"><option value="0">All</option></select>

			   
							&nbsp;&nbsp;&nbsp;

			        		Cleared :
			        		<select id="search_cleared" class="form-control sta_arranged_filter">
			        		  <option value="">All</option>
							  <option value="1">Cleared</option>
							  <option value="0">Uncleared</option>
							</select>

		        		</div>
			        	
                        <div class="table" style="display: table;width: 100%;" id="paf_rp_table">
                            <thead>
                            	
                                <div class="tr" style="width:100%">
                                    <div class="th" id="id_index" style="text-align: center;width:12%">Index</div>
                              <!--       <div class="th" style="text-align: center;width:8%">No</div> -->
                                    <div class="th" id="id_point" style="text-align: center;width:29%">Points</div>
                                    <div class="th" id="id_response" style="text-align: center;width:29%">Response</div>
                                    <div class="th" id="id_raisedby" style="text-align: center;width:18%">Points raised by</div>
                                    <div class="th" style="text-align: right;width:18%">
                                    	<a class="create_main_category amber" id="add_rp_line" href="javascript:void(0);" onclick="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add Main Category" >
	                                    	<i class="fa fa-plus-circle amber" style="font-size:16px;"></i> Add Line
	                                    </a>
	                                </div>
                                    
                                </div>
                                
                                
                            </thead>
                            <div class="tbody" id="review_point_info">
                                

                            </div>
                            
                        </div>
                           
			        </div>
		      	</div>
			</div>
		</div>

	
	

		<!-- <footer class="panel-footer">
			<div class="col-md-12 number text-right">
				<button class="btn btn-primary modal-confirm">Confirm</button>
				<button type="button" class="btn btn-primary" name="savePaf" id="savePaf">Save</button>
				<a href="<?= base_url();?>client/audit_client/<?= $client->id ?>" class="btn btn-default">Back</a>
			</div>
		</footer>  -->
	</section>
	<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>
<!-- end: page -->
</section>

<!-- Modal to add main category -->
<div class="modal fade" id="main_category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>ADD MAIN CATEGORY</strong>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
      </div>
      <div class="modal-body">
      	<input type="text" class="form-control" id="input_main_desc" name="main_desc" placeholder="Main Category Description">
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="btn_insert_main_category" type="button" class="btn btn-primary">Insert</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF add main category modal -->

<!-- Modal to add sub category -->
<div class="modal fade" id="sub_category_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>ADD SUB</strong>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
      </div>
      <div class="modal-body">
      	<input type="hidden" id="selected_parent_id"  name="selected_parent_id">
      	<input type="text" class="form-control" id="input_sub_desc" name="sub_desc" placeholder="Sub Category Description">
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="btn_insert_sub_category" type="button" class="btn btn-primary">Insert</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF add main category modal -->

<!-- Modal to rename -->
<div class="modal fade" id="rename_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>RENAME</strong>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
      </div>
      <div class="modal-body">
      	<input type="hidden" id="selected_parent_id"  name="selected_parent_id">
      	<input type="text" class="form-control" id="new_name" name="new_name" placeholder="New name">
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="btn_save_new_name" type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF rename modal -->

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
			

							<tr>
								<th>Uploading document(s) to</th>
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

<!-- View paf doc Pop up -->
<div id="paf_doc_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  data-backdrop="static" style="margin-top:150px;">
	<div class="modal-dialog" >
		<div class="modal-content">
			<header class="panel-heading">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="panel-title">Upload</h2>
			</header>
	
			<div class="panel-body">
				<div class="col-md-12">
					<table class="table table-bordered table-striped table-condensed mb-none" id="paf_doc_table">
						
					</table>

				</div>
			</div>

		
		</div>
	</div>
</div>


<script type="text/javascript">

	var company_code = <?php echo json_encode(isset($client)?$client->company_code:"" ) ?>;
	var paf_doc_list = <?php echo json_encode(isset($paf_documents)?$paf_documents:"" ) ?>;
	var paf_data = <?php echo json_encode(isset($paf_data)?$paf_data:"" ) ?>;
	var active_tab = <?php echo json_encode(isset($active_tab)?$active_tab:"active_paf_list") ?>;
	var edit_first_letter = <?php echo json_encode(isset($edit_first_letter)?$edit_first_letter:"") ?>;
	var review_point_info = <?php echo json_encode(isset($review_point_info)?$review_point_info:FALSE) ?>;
	var save_first_letter_url = "<?php echo site_url('list_of_auditor/save_first_letter'); ?>";
	var save_paf_url = "<?php echo site_url('paf_upload/save_paf'); ?>";
	var paf_all_url = "<?php echo site_url('paf_upload/pafAllData'); ?>";
	var generate_first_letter_url = "<?php echo site_url('list_of_auditor/generate_first_letter'); ?>";
	var generate_auth_document_url = "<?php echo site_url('bank/generate_auth_document'); ?>";
	var auditor_url = "<?= base_url();?>list_of_auditor";
	var submit_paf_list_url = "<?= base_url();?>client/submit_paf_list";
	var delete_paf_parent_url = "<?= base_url();?>client/delete_paf_parent";
	var delete_paf_child_url = "<?= base_url();?>client/delete_paf_child";
	var delete_paf_doc_url = "<?= base_url();?>client/delete_paf_doc";
	var archive_paf_child_url = "<?= base_url();?>client/archive_paf_child";
	var archive_paf_parent_url = "<?= base_url();?>client/archive_paf_parent";
	var restore_paf_url = "<?= base_url();?>client/restore_paf";
	var get_paf_index_url = "<?= base_url();?>client/get_paf_index";
	var add_rp_info_url = "<?= base_url();?>client/add_rp_info";
	var clear_rp_url = "<?= base_url();?>client/clear_rp";
	var check_cleared_points_url = "<?= base_url();?>client/check_cleared_points";
	var filter_review_points_url = "<?= base_url();?>client/filter_review_points";
	var delete_review_point_url = "<?= base_url();?>client/delete_review_point";
	var php_base_url = "<?= base_url();?>";
	var base_url = window.location.origin;  
	var pv_index_tab_aktif;

	var uploaded_paf_doc = [];
	var current_index = "";
	var uploadedFiles = {};
	var paf_list_id = [];

	//global variable for table row (tr)
	var rename_tr = "";
	var paf_doc_list_id = paf_doc_list.map(function(x) {
							if(x.doc.length > 0)
							{
								for (var i = x.doc.length - 1; i >= 0; i--) {
									if(x.doc[i].archived == 0)
									{
										return x.id;
									}
								}
								// console.log(x);
								
							}
							else
							{
								return;
							}
						})
	// console.log(paf_doc_list);
	var temp_paf_list_id = paf_data.map(x => x.child);
	for (var i = temp_paf_list_id.length - 1; i >= 0; i--) {
		for (var a = temp_paf_list_id[i].length - 1; a >= 0; a--) {
			paf_list_id.push(temp_paf_list_id[i][a].id);
		}
		
	}
	paf_doc_list_id = paf_doc_list_id.filter(n => n);
	var paf_no_doc_list = paf_list_id.filter(function(x) {
						  	return !paf_doc_list_id.includes(x); 
						}) 

	// console.log(paf_no_doc_list);
	for (var k = 0; k < paf_no_doc_list.length; k++) {
		$("#desc" + paf_no_doc_list[k]).find(".no_doc").show();
	}

	function check_cleared_points(paf_child_id)
	{
	    $.ajax({
	        type: "POST",
	        url: check_cleared_points_url,
	        data: '&paf_child_id=' + paf_child_id,
	        async: false,
	        dataType: "json",
	        success: function(data){
	            if (data.Status === 1) {
	                if(data.cleared == "cleared")
	                {
	                    $("#desc"+ paf_child_id).find(".cleared_rp").show();
	                    $("#desc"+ paf_child_id).find(".rp").hide();
	                }
	                else if(data.cleared == "uncleared")
	                {
	                	$("#desc"+ paf_child_id).find(".rp").show();
	                    $("#desc"+ paf_child_id).find(".cleared_rp").hide();
	                }
	                else if(data.cleared == "no_rp")
	                {
	                	$("#desc"+ paf_child_id).find(".rp").hide();
	                    $("#desc"+ paf_child_id).find(".cleared_rp").hide();
	                }
	            }


	            // $.each(data, function(key, val) {
	            //     var option = $('<option />');
	            //     option.attr('value', key).text(val);
	            //     $('#form'+$count_review_point_info).find(".paf_index").append(option);
	            //     $('#paf_index'+$count_review_point_info).select2();
	          
	            // });

	            //$('#form'+$count_family_info).find(".relationship"+$count_family_info).select2();
	        }
	    });
	}


	for (var j = 0; j < paf_list_id.length; j++){
		check_cleared_points(paf_list_id[j]);
	}



	// console.log(edit_bank_auth[0]["auth_date"]);
	$('[data-toggle="tooltip"]').tooltip();


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

	if(active_tab != null)
	{  
		pv_index_tab_aktif = active_tab;

	    if(active_tab != "active_paf_list")
	    {
	        $('li[data-information="'+active_tab+'"]').addClass("active");
	        $('#w2-'+active_tab+'').addClass("active");
	        $('li[data-information="active_paf_list"]').removeClass("active");
	        $('#w2-active_paf_list').removeClass("active");
	    }
	    // console.log(active_tab);
	    if(active_tab =="active_paf_list")
	    {
	    	$(".create_main_category").show();
	    	$(".create_review_point").show();
	    	$("#savePaf").show();
	    }
	    else{
	    	$(".create_main_category").hide();
	    	$(".create_review_point").show();
	    	$("#review_point_section").hide();
	    	$("#savePaf").hide();
	    }

	}


	$(document).on('click',".check_state",function(){
		pv_index_tab_aktif = $(this).data("information")
		active_tab = pv_index_tab_aktif;

		if(pv_index_tab_aktif =="active_paf_list")
	    {
	    	$(".create_main_category").show();
	    	$(".create_review_point").show();
	    	$("#review_point_section").show();
	    	$("#savePaf").show();
	    }
	    else{
	    	$(".create_main_category").hide();
	    	$(".create_review_point").show();
	    	$("#review_point_section").hide();
	    	$("#savePaf").hide();
	    }

	});

	$(document).keydown(function(event) { 
		if (event.keyCode == 27) { 
			$('.modal').modal("hide");
		}

		if($("#main_category_modal").hasClass('in') && (event.keycode == 13 || event.which == 13)) {
			$('#btn_insert_main_category').click();
		}

		if($("#sub_category_modal").hasClass('in') && (event.keycode == 13 || event.which == 13)) {
			$('#btn_insert_sub_category').click();
		}

		if($("#rename_modal").hasClass('in') && (event.keycode == 13 || event.which == 13)) {
			$('#btn_save_new_name').click();
		}
	});

</script>

<!-- own script -->
<script src="<?= base_url()?>application/modules/client/js/client_paf.js" charset="utf-8"></script>
