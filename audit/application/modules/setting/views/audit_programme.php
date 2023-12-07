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

	#audit_objective_table {
	  /* Set "section" to 0 */
	  counter-reset: number;
	}


	.objective_text::before {
	  counter-increment: number;
	  content: counter(number) ". ";
	  font-weight: bold;
	  padding: 0 5px 0 0;
	  vertical-align: 50%;
	}

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
						<div class="wizard-progress wizard-progress-lg">
							<div class="steps-progress">
								<div class="progress-indicator"></div>
							</div>
							<ul class="wizard-steps" id="fs_tab">
								<li>
									<a href="#programme_info" data-toggle="tab"><span>1</span>Programme <br/> information</a>
								</li>
								<li>
									<a href="#audit_objectives" data-toggle="tab"><span>2</span>Audit <br/> Objectives</a>
								</li>
								<li>
									<a href="#risk_assessment" data-toggle="tab"><span>3</span>Table of risk <br/>assessment <br/> factor</a>
								</li>
								<li>
									<a href="#procedure_design" data-toggle="tab"><span>4</span>Audit Procedure <br/>  Design <br/> Considerations</a>
								</li>
								<li>
									<a href="#programme_content" data-toggle="tab"><span>5</span>Programme <br/> content</a>
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

									<div class="form-group uen_section">
										<label class="col-sm-5 control-label" for="UEN">Programme type</label>
										<div class="col-sm-3">
											<?php

												echo form_dropdown('programme_type', $programme_type_dropdown, isset($edit_programme)?$edit_programme->type:'', 'class="programme_type select2" style="width:100%;"');

											?>
										</div>
									</div>
								</form>
							</div>

							<div id="audit_objectives" class="tab-pane">
								<form id="audit_objectives_form">
									<a class="amber" id="add_objective_line" href="javascript:void(0);" onclick=add_objective_line(this) data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;float:right;" data-original-title="Add objective" >
                                    	<i class="fa fa-plus-circle amber" style="font-size:16px;"></i> Add Line
                                    </a>
									<table class="table table-stripped" style="display: table;width: 100%;" id="audit_objective_table">

			                            <thead>
			                            	
			                                <tr style="width:100%">
			                                	<th class="borderless" style="width:4%;"></th>
			                                    <th class="borderless" id="id_index" style="text-align: center;width:76%"></th>
			                              <!--       <div class="th" style="text-align: center;width:8%">No</div> -->
			                                    <th style="text-align: center;width:20%">Assertions</th>
			                                  
			                                    
			                                </tr>
			                                
			                                
			                            </thead>
			                            <tbody id="objectives_line">

			                            	<?php
			                        			if(!isset($objective_lines) || !$objective_lines)
			                        			{

			                        		?>
					                            	<tr style="width:100%">
					                            		<input type="hidden" name="objective_id[]" class="objective_id">
					                            		<td class="borderless" style="vertical-align: middle;">
									    					<a class="amber remove_objective_line" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;float: right;top:50px;display: none;" data-original-title="Remove this line"  onclick=remove_objective_line(this) ><i class="fa fa-minus-circle amber" style="font-size:13px;"></i></a>
									    				</td>
					                                    <td class="borderless text-left objective_text" id="id_index" style="width:80%;vertical-align: middle;">
					                                    	<textarea class="form-control" name="objective_text[]"  rows="1" style="width:95%;display: inline-block;">Edit text. </textarea>
						                                </td>
					                                    <td style="text-align: center;width:20%">
					                <!--                     	<?php
																echo form_dropdown('programme_assertion', $programme_assertion_dropdown, '', 'class="programme_assertion select2" style="width:100%;"');
															?> -->
															<select class="form-control programme_assertion" style="width: 100%;" name="programme_assertion[]" required>
												                    <option value="">Select assertion</option>
												            </select>
					                                    </td>
					                                </tr>
			                                <?php
					                            }
					                        ?>

			                            </tbody>
			                            
			                        </table>
			                        <div class="form-group">
										<div class="col-sm-12">
											<button type="button" class="btn btn-primary save_objective_line" id="save_objective_line" style="float: right">Save</button>
										</div>
									</div>

								</form>
							</div>

							<div id="risk_assessment" class="tab-pane">
								<form id="ra_factor_form">
									<table class="table table-bordered table-striped mb-none">
										<tr>
											<th rowspan="2" colspan="2" style="vertical-align: middle;width: 46%">Risk Assessment Factor(R Factor)</th>
										<!-- 	<td>test</td> -->
											<th colspan="3" class="text-center" style="vertical-align: middle;width: 54%;">Assertion level risk</th>
											<!-- <td>test</td>
											<td>test</td> -->
										</tr>
										<tr>
											<!-- <td>test</td> -->
											<!-- <td>test</td> -->
											<th class="text-center" style="width: 18%;">L</th>
											<th class="text-center" style="width: 18%;">M</th>
											<th class="text-center" style="width: 18%;">H</th>
										</tr>
										<tr>
											<th rowspan="3" style="vertical-align: middle;width: 28%;">Financial Statement level risk</th>
											<th class="text-center">L</th>
											<td>
												<input type="hidden" name="cell_id[]" value="<?=isset($ra_factors['LL'])?$ra_factors['LL']['id']:''?>">
												<input class="form-control" style="text-align:center;" type="number" name="cell_value[]" value="<?=isset($ra_factors['LL'])?$ra_factors['LL']['value']:''?>">
											</td>
											<td>
												<input type="hidden" name="cell_id[]" value="<?=isset($ra_factors['LM'])?$ra_factors['LM']['id']:''?>">
												<input class="form-control" style="text-align:center;" type="number" name="cell_value[]" value="<?=isset($ra_factors['LM'])?$ra_factors['LM']['value']:''?>">
											</td>
											<td>
												<input type="hidden" name="cell_id[]" value="<?=isset($ra_factors['LH'])?$ra_factors['LH']['id']:''?>">
												<input class="form-control" style="text-align:center;" type="number" name="cell_value[]" value="<?=isset($ra_factors['LH'])?$ra_factors['LH']['value']:''?>">
											</td>
										</tr>
										<tr>
											<!-- <td>test</td> -->
											<th class="text-center">M</th>
											<td>
												<input type="hidden" name="cell_id[]" value="<?=isset($ra_factors['ML'])?$ra_factors['ML']['id']:''?>">
												<input class="form-control" style="text-align:center;" type="number" name="cell_value[]" value="<?=isset($ra_factors['ML'])?$ra_factors['ML']['value']:''?>">
											</td>
											<td>
												<input type="hidden" name="cell_id[]" value="<?=isset($ra_factors['MM'])?$ra_factors['MM']['id']:''?>">
												<input class="form-control" style="text-align:center;" type="number" name="cell_value[]" value="<?=isset($ra_factors['MM'])?$ra_factors['MM']['value']:''?>">
											</td>
											<td>
												<input type="hidden" name="cell_id[]" value="<?=isset($ra_factors['MH'])?$ra_factors['MH']['id']:''?>">
												<input class="form-control" style="text-align:center;" type="number" name="cell_value[]" value="<?=isset($ra_factors['MH'])?$ra_factors['MH']['value']:''?>">
											</td>
										</tr>
										<tr>
											<!-- <td>test</td> -->
											<th class="text-center">H</th>
											<td>
												<input type="hidden" name="cell_id[]" value="<?=isset($ra_factors['HL'])?$ra_factors['HL']['id']:''?>">
												<input class="form-control" style="text-align:center;" type="number" name="cell_value[]" value="<?=isset($ra_factors['HL'])?$ra_factors['HL']['value']:''?>">
											</td>
											<td>
												<input type="hidden" name="cell_id[]" value="<?=isset($ra_factors['HM'])?$ra_factors['HM']['id']:''?>">
												<input class="form-control" style="text-align:center;" type="number" name="cell_value[]" value="<?=isset($ra_factors['HM'])?$ra_factors['HM']['value']:''?>">
											</td>
											<td>
												<input type="hidden" name="cell_id[]" value="<?=isset($ra_factors['HH'])?$ra_factors['HH']['id']:''?>">
												<input class="form-control" style="text-align:center;" type="number" name="cell_value[]" value="<?=isset($ra_factors['HH'])?$ra_factors['HH']['value']:''?>">
										</tr>
									</table>

									<br/>
									<div class="form-group">
										<div class="col-sm-12">
											<button type="button" class="btn btn-primary save_ra_factor" id="save_ra_factor" style="float: right">Save</button>
										</div>
									</div>

								</form>
							</div>

							<div id="procedure_design" class="tab-pane">
								<form id="procedure_design_form">
									<a class="amber" id="add_objective_line" href="javascript:void(0);" onclick=add_step_line(this) data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;float:right;" data-original-title="Add procedure step" >
                                    	<i class="fa fa-plus-circle amber" style="font-size:16px;"></i> Add Step
                                    </a>
									<table class="table table-borderless" style="display: table;width: 100%;" id="audit_procedure_table">
										<?php
		                        			if(!isset($procedure_lines) || !$procedure_lines)
		                        			{
		                        		?>
		                           	
			                            	<tr style="width:100%">
			                            		<input type="hidden" class="procedure_id" name="procedure[0][id]">
			                            		<td class="borderless" style="width:5%;">
							    					<a class="amber remove_step_line" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;float: right;top:50px;display: none;" data-original-title="Remove this line"  onclick=remove_step_line(this) ><i class="fa fa-minus-circle amber" style="font-size:13px;"></i></a>
							    				</td>
							    				<td style="width:95%;">
							    					<input class="form-control parent_line" style="text-align:left;width: 10%;" type="text" name="procedure[0][step_text]" value="Step 1">						    		
							    				</td>
			                                </tr>

			                                <tr style="width:100%;">
			                                	<td style="width:5%" class="borderless">
							    				</td>
							    				<td style="width:95%">
				                                	<table class="table table-borderless nested_audit_procedure_table" style="width:100%;" >  
				                                		<tr>
				                                			<input type="hidden" class="temp_parent_id" value='0'>
						                            		<td class="borderless text-center" style="width:3%;">
										    					<a class="amber add_procedure_child" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;top:50px;" data-original-title="Remove this line"  onclick=add_procedure_child(this) ><i class="fa fa-plus-circle amber" style="font-size:13px;"></i></a>
										    				</td>
						                                    <td class="borderless text-left" style="width:97%;">
						                                    	<input class="form-control child_line" type="text" name="procedure[0][child_line][]">
						                                    </td>
						                                </tr>
						                            </table>
						                        </td>
			                                </tr>
		                                <?php
				                            }
				                        ?>

			                            
			                        </table>
			                        <div class="form-group">
										<div class="col-sm-12">
											<button type="button" class="btn btn-primary save_procedure_design" id="save_procedure_design" style="float: right">Save</button>
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

<table id="clone_model" style="display: none;" >
	<tr style="width:100%">
		<input type="hidden" name="objective_id[]" class="objective_id">
		<td class="borderless" style="vertical-align: middle;">
			<a class="amber remove_objective_line" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;float: right;top:50px;display: none;" data-original-title="Remove this line"  onclick=remove_objective_line(this) ><i class="fa fa-minus-circle amber" style="font-size:13px;"></i></a>
		</td>
	    <td class="borderless text-left objective_text" id="id_index" style="width:80%;vertical-align: middle;">
	    	<textarea class="form-control" name="objective_text[]"  rows="1" style="width:95%;display: inline-block;">Edit text. </textarea>
	    </td>
	    <td style="text-align: center;width:20%">
			<select class="form-control programme_assertion" style="width: 100%;" name="programme_assertion[]" required>
                    <option value="">Select assertion</option>
            </select>
        </td>
	</tr>
</table>

<table id="clone_model_procedure" style="display: none;" >
	<tbody>
		<tr style="width:100%">
			<input type="hidden" class="procedure_id" name="procedure[][id]">
			<td class="borderless" style="width:5%;">
				<a class="amber remove_step_line" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;float: right;top:50px;display: none;" data-original-title="Remove this line"  onclick=remove_step_line(this) ><i class="fa fa-minus-circle amber" style="font-size:13px;"></i></a>
			</td>
			<td style="width:95%;">
				<input class="form-control parent_line" style="text-align:left;width: 10%;" type="text" name="procedure[][step_text]" value="Step 1">						    		
			</td>
		</tr>

		<tr style="width:100%;">
			<td class="borderless">
			
			</td>
			<td>
		    	<table class="table table-borderless nested_audit_procedure_table" style="width:100%;">  
		    		<tr>
    					<input type="hidden" class="temp_parent_id" value='0'>
		        		<td class="borderless text-center" style="width:3%;">
	    					<a class="amber add_procedure_child" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;top:50px;" data-original-title="Remove this line"  onclick=add_procedure_child(this) ><i class="fa fa-plus-circle amber" style="font-size:13px;"></i></a>
	    				</td>
                        <td class="borderless text-left" style="width:97%;">
                        	<input class="form-control child_line" type="text" name="procedure[][child_line][]">
                        </td>
		            </tr>
		        </table>
		    </td>
		</tr>
	</tbody>
</table>

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>

<script type="text/javascript">
	var pathArray = location.href.split( '/' );
	var protocol = pathArray[0];
	var host = pathArray[2];
	var folder = pathArray[3];
	var temp_parent_id_count = 1;

	var base_url = window.location.origin;  
	var arr_deleted_objtv = [];
	var arr_deleted_step = [];
	var contentAllData_url = "<?php echo site_url('setting/contentAllData'); ?>";
	var save_all_programme_setting_url = "<?php echo site_url('setting/save_all_programme_setting'); ?>";
	// var save_ra_factor_url = "<?php echo site_url('setting/save_ra_factor'); ?>";
	// var save_procedure_design_url = "<?php echo site_url('setting/save_procedure_design'); ?>";
	// var save_programme_content_url = "<?php echo site_url('setting/save_programme_content'); ?>";
	var check_avail_index_url = "<?php echo site_url('setting/check_avail_index'); ?>";

	var editing_flag = <?php echo json_encode(isset($editing_flag)?$editing_flag:"") ?>;
	var objective_lines = <?php echo json_encode(isset($objective_lines)?$objective_lines:"") ?>;
	var procedure_lines = <?php echo json_encode(isset($procedure_lines)?$procedure_lines:"") ?>;

	var form_is_valid = true;


</script>

<script src="<?= base_url()?>application/modules/setting/js/audit_programme.js" charset="utf-8"></script>
