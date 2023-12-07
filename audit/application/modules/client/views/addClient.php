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


<?php
	$now = getDate();
	// $this->sma->print_arrays($client);
	//echo json_encode($now[0]);
	// 	$this->session->set_userdata('unique_code', $client->unique_code);

	// if ($this->session->userdata('unique_code') && $this->session->userdata('unique_code') != '')
	// {
	// 	$unique_code =$this->session->userdata('unique_code');
	// } else {
	// 	$unique_code = $this->session->userdata('username').'_'.$now[0];
	// 	$this->session->set_userdata('unique_code', $client->unique_code);
	// }
	// $ndate = $now['mday']."/".$this->sma->addzero($now['mon'],2)."/".$now['year'];
	// // $this->sma->print_arrays($ndate);
	// // echo $ndate;
	// $type_of_doc[""] = [];
	// foreach ($typeofdoc as $cs) {
	// 	$type_of_doc[$cs->id] = $cs->typeofdoc;
	// }
	// $doc_category[""] = [];
	// foreach ($doccategory as $cs) {
	// 	$doc_category[$cs->id] = $cs->doccategory;
	// }
	// $svc[""] = [];
	// foreach ($service as $cs) {
	// 	$svc[$cs->id] = $cs->service_name;
	// }

	if ($this->session->userdata('company_code') && $this->session->userdata('company_code') != '')
	{
		$company_code =$this->session->userdata('company_code');
		// echo json_encode($company_code);
	} 
	else 
	{
		$company_code = 'company_'.$now[0];
		$this->session->set_userdata('company_code', $company_code);
	}
	
?>		

<section role="main" class="content_section" style="margin-left:0;">
	<?php echo $breadcrumbs;?>
	<div class="panel-body">
		<div class="col-md-12">
			<div id="modalLG" class="modal-block modal-block-lg" style="max-width: 100%	;margin: 0px auto;">
				<section class="panel" style="margin-bottom: 0px;">
					<div class="panel-body">
						<div class="modal-wrapper">
							<div class="modal-text">
								<div class="tabs">
									<ul class="nav nav-tabs nav-justify" id="myTab">
										<!-- <?php if ($company_info_module != 'none') { ?> -->
										<!-- <?php
											}
										?> -->
										<li class="active check_stat" id="li-companyInfo" data-information="companyInfo">
											<a href="#w2-companyInfo" data-toggle="tab" class="text-center">
												<!-- <span class="badge hidden-xs">1</span> -->
												<b>1.</b> Company Info
											</a>
										</li>											 
										<li class="check_stat" id="li-filing" data-information="filing">
											<a href="#w2-filing" data-toggle="tab" class="text-center">
												<!-- <span class="badge hidden-xs">6</span> -->
												<b>2.</b> Filing
											</a>
										</li>																						
										<li class="check_stat" id="li-billing" data-information="billing">
											<a href="#w2-billing" data-toggle="tab" class="text-center">
												<!-- <span class="badge hidden-xs">8</span> -->
												<b>3.</b> Service Engagement
											</a>
										</li>
										<!-- Service Engagement -->
										<li class="check_stat" id="li-setup" data-information="setup">
											<a href="#w2-setup" data-toggle="tab" class="text-center">
												<!-- <span class="badge hidden-xs">9</span> -->
												<b>4.</b> Setup
											</a>
										</li>
										<li class="check_stat" id="li-bank_detail" data-information="bank_detail">
											<a href="#w2-bank_detail" data-toggle="tab" class="text-center">
												<!-- <span class="badge hidden-xs">6</span> -->
												<b>5.</b> Bank Detail
											</a>
										</li>
										<li class="check_stat" id="li-bank" data-information="bank">
											<a href="#w2-bank" data-toggle="tab" class="text-center">
												<!-- <span class="badge hidden-xs">6</span> -->
												<b>6.</b> Bank Summary
											</a>
										</li>
										<!--  <li class="check_stat" id="li-paf" data-information="paf">
											<a href="#w2-paf" data-toggle="tab" class="text-center">
										
												<b>7.</b> PAF Uploads
											</a>
										</li>	 -->				
									</ul>			
									<div class="tab-content">	
										<div id="w2-companyInfo" class="tab-pane active">
										
											<?php echo form_open_multipart('', array('id' => 'upload_company_info', 'enctype' => "multipart/form-data")); ?> 

												<div class="hidden">
													<input type="text" class="form-control" name="company_code" value="<?=isset($company_code)?$company_code:"" ?>"/>
												</div>
												
												<span style="font-size: 2.4rem;padding: 0; margin: 7px 0 4px 0;">Company Profile</span>
												<div class="form-group" style="margin-top: 20px;">
													<label class="col-sm-4 control-label" for="w2-username">Client Code: </label>
													<div class="col-sm-4">
														<input type="text" style="text-transform:uppercase" class="form-control" maxlength="20" id="client_code" name="client_code" value="<?=isset($client->client_code)?$client->client_code:"" ?>" >
														<div id="form_client_code"></div>
														<!-- <?php echo form_error('client_code','<span class="help-block">*','</span>'); ?> -->
													</div>
													<div class="col-sm-4">
														<div style="float: right;">
															<select id="acquried_by" class="form-control acquried_by" style="text-align:right; text-transform:uppercase;" name="acquried_by">
											                    <option value="0">client status:</option>
											                </select>
											                <div id="form_acquried_by"></div>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username">Registration No: </label>
													<div class="col-sm-4">
														<input type="text" style="text-transform:uppercase" class="form-control" id="registration_no" name="registration_no" value="<?=isset($client->registration_no)?$client->registration_no:"" ?>" >
														<div id="form_registration_no"></div>
														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username">Company Name: </label>
													<div class="col-sm-8">
														<input type="text" style="text-transform:uppercase" class="form-control" id="edit_company_name" name="company_name"  value="">
														<div id="form_company_name"></div>
														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username">Former Name (if any): </label>
													<div class="col-sm-8">
														<textarea class="form-control" style="text-transform:uppercase" id="former_name" name="former_name"><?=isset($client->former_name)?$client->former_name:"" ?></textarea>
														<div id="form_former_name"></div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username">Incorporation Date: </label>
													<div class="col-sm-8">
														<div class="input-group mb-md" style="width: 200px;">
															<span class="input-group-addon">
																<i class="far fa-calendar-alt"></i>
															</span>
															<input type="text" class="form-control valid datepicker" id="date_todolist" name="incorporation_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" required="" value="<?=isset($client->incorporation_date)?$client->incorporation_date:"" ?>" placeholder="DD/MM/YYYY" autocomplete="off">	
														</div>
														<!-- <div style="width: 30%;float: left;">
				    			                            <div class="input-group date datepicker" data-provide="datepicker">
				    			                            	<div class="input-group-addon">
															        <span class="far fa-calendar-alt"></span>
															    </div>
				    			                            	<input type="text" class="form-control" id="status_date" name="incorporation_date" data-date-format="d F Y" value="<?=isset($staff[0]->status_date)?date("d F Y",strtotime($staff[0]->status_date)):''?>">
				    										</div>
				    			                        </div> -->
														<div id="form_incorporation_date"></div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username">Company Type: </label>
													<div class="col-sm-8">
														<select id="company_type" class="form-control company_type" style="text-align:right; width: 400px;" name="company_type">
										                    <option value="0">Select Company Type</option>
										                </select>
										                <div id="form_company_type"></div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username">Status: </label>
													<div class="col-sm-8">
														<select id="status" class="form-control status" style="text-align:right;" name="status">
										                    <option value="0">Select Status</option>
										                </select>
										                <div id="form_status"></div>
													</div>
												</div>
												<span style="font-size: 2.4rem;padding: 0; margin: 7px 0px 4px 0;">Principal Activities</span>
												<div class="form-group" style="margin-top: 20px">
													<label class="col-sm-4 control-label" for="w2-username">Activity 1: </label>
													<div class="col-sm-8">
														<input type="text" style="text-transform:uppercase" class="form-control" id="activity1" name="activity1" value="<?=isset($client->activity1)?$client->activity1:"" ?>" >
														<div id="form_activity1"></div>
														<!-- <?php echo form_error('activity1','<span class="help-block">*','</span>'); ?> -->
													</div>
												</div>
												<div class="form-group" style="margin-top: 20px">
													<label class="col-sm-4 control-label" for="w2-username">Description 1: </label>
													<div class="col-sm-8">
														<textarea rows="4" cols="50" style="text-transform:uppercase" class="form-control" id="description1" name="description1"><?=isset($client->description1)?$client->description1:"" ?>
														</textarea>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username">Activity 2: </label>
													<div class="col-sm-8">
														<input type="text" style="text-transform:uppercase" class="form-control" id="activity2" name="activity2" value="<?=isset($client->activity2)?$client->activity2:"" ?>" >
														<div id="form_activity2"></div>
													</div>
												</div>
												<div class="form-group" style="margin-top: 20px">
													<label class="col-sm-4 control-label" for="w2-username">Description 2: </label>
													<div class="col-sm-8">
														<textarea rows="4" cols="50" style="text-transform:uppercase" class="form-control" id="description2" name="description2"><?=isset($client->description2)?$client->description2:"" ?>
														</textarea>
													</div>
												</div>
												<span style="font-size: 2.4rem;padding: 0; margin: 7px 0px 4px 0;">Registered Office Address</span>
												<div class="form-group" style="margin-top: 20px">
													<label class="col-xs-4 control-label" for="w2-username">Use Our Registered Address: </label>
													<div class="col-xs-8">
														<div class="col-xs-1">
															<input type="checkbox" class="" id="" name="use_registered_address" <?=isset($client->registered_address)?$client->registered_address?'checked':'':'';?>  onclick="fillRegisteredAddressInput(this);">
														</div>
														<div class="col-xs-6 service_reg_off_area" style="display: none;">
															<select id="service_reg_off" class="form-control service_reg_off" style="text-align:right;" name="service_reg_off">
											                    <option value="0">Select Service Name</option>
											                </select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username">Registered Office Address: </label>
													<div class="col-sm-8">
														<div style="width: 100%;">
															<div style="width: 15%;float:left;margin-right: 20px;">
																<label>Postal Code :</label>
															</div>
															<div style="width: 65%;float:left;margin-bottom:5px;">
																<div class="" style="width: 20%;" >
																	<input type="text" style="text-transform:uppercase" class="form-control" id="postal_code" name="postal_code" value="<?=isset($client->postal_code)?$client->postal_code:"" ?>" maxlength="6">
																</div>
																<div id="form_postal_code"></div>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username"></label>
													<div class="col-sm-8">
														<div style="width: 100%;">
															<div style="width: 15%;float:left;margin-right: 20px;">
																<label>Street Name :</label>
															</div>
															<div style="width: 71%;float:left;margin-bottom:5px;">
																<div class="" style="width: 100%;" >
																	<input type="text" style="text-transform:uppercase" class="form-control" id="street_name" name="street_name" value="<?=isset($client->street_name)?$client->street_name:"" ?>">
																</div>
																<div id="form_street_name"></div>
											
															</div>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username"></label>
													<div class="col-sm-8">
														<div style="width: 100%;">
															<div style="width: 15%;float:left;margin-right: 20px;">
																<label>Building Name :</label>
															</div>
															<div style="width: 71%;float:left;margin-bottom:5px;">
																<div class="" style="width: 100%;" >
																	<input type="text" style="text-transform:uppercase" class="form-control" id="building_name" name="building_name" value="<?=isset($client->building_name)?$client->building_name:"" ?>">
																</div>
																<div id="form_street_name"></div>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label" for="w2-username"></label>
													<div class="col-sm-8">
														<label style="width: 15%;float:left;margin-right: 20px;">Unit No :</label>
														<input style="width: 8%; float: left; margin-right: 10px; text-transform:uppercase;" type="text" class="form-control" id="unit_no1" name="unit_no1" value="<?=isset($client->unit_no1)?$client->unit_no1:"" ?>" maxlength="3">
														<label style="float: left; margin-right: 10px;" >-</label>
														<input style="width: 14%; text-transform:uppercase;" type="text" class="form-control" id="unit_no2" name="unit_no2" value="<?=isset($client->unit_no2)?$client->unit_no2:"" ?>" maxlength="10">
													</div>
												</div>
												<div class="form-group hidden">
													<label class="col-sm-4 control-label" for="w2-foreign_add_1">Foreign Address: </label>
													<div class="col-sm-8">
														<div style="width: 100%;">
															<div style="width: 85%;float:left;margin-bottom:5px;">
																<div class="" >
																	<input type="text" style="text-transform:uppercase" class="form-control" id="foreign_add_1" name="foreign_add_1" value="<?=isset($client->foreign_add_1)?$client->foreign_add_1:"" ?>">
																</div>
																<div id="form_foreign_add_1"></div>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group hidden">
													<label class="col-sm-4 control-label" for="w2-foreign_add_2"></label>
													<div class="col-sm-8">
														<div style="width: 100%;">
															<div style="width: 85%;float:left;margin-bottom:5px;">
																<div class="" style="width: 100%;" >
																	<input type="text" style="text-transform:uppercase" class="form-control" id="foreign_add_2" name="foreign_add_2" value="<?=isset($client->foreign_add_2)?$client->foreign_add_2:"" ?>">
																</div>
																<div id="form_foreign_add_2"></div>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group hidden">
													<label class="col-sm-4 control-label" for="w2-foreign_add_3"></label>
													<div class="col-sm-8">
														<div style="width: 100%;">
															<div style="width: 85%;float:left;margin-bottom:5px;">
																<div class="" style="width: 100%;" >
																	<input type="text" style="text-transform:uppercase" class="form-control" id="foreign_add_3" name="foreign_add_3" value="<?=isset($client->foreign_add_3)?$client->foreign_add_3:"" ?>">
																</div>
																<div id="form_foreign_add_3"></div>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group hidden">
													<label class="col-sm-4 control-label" for="w2-use_foreign_add_as_billing_add">Use Foreign Address as Billing Address: </label>
													<div class="col-sm-8">
														<input type="checkbox" class="" id="use_foreign_add_as_billing_add" <?=isset($client->use_foreign_add_as_billing_add)?$client->use_foreign_add_as_billing_add?'checked':'':'';?> name="use_foreign_add_as_billing_add">
													</div>
												</div>
												<div class="form-group hidden">
													<label class="col-sm-4 control-label" for="w2-username">Listed Company: </label>
													<div class="col-sm-8">
														<input type="checkbox" class="" id="listedcompany" <?=isset($client->listed_company)?$client->listed_company?'checked':'':'';?> name="listedcompany"  />
													</div>
												</div>
											<?= form_close(); ?> 
										</div>
												<!-- <div class="row">
													<div class="col-md-12 number text-right client_footer_button" id="client_footer_button">
														input type="button" value="Save As Draft" id="save_draft" class="btn btn-default"
														<input type="button" value="Save" id="save" class="btn btn-primary btn_blue">
														<button id="save" class="btn btn-primary btn_blue" onclick="save_client()">Save</button>
														<a href="<?= base_url();?>masterclient/" class="btn btn-default">Cancel</a>
													</div>
												</div> -->

											<!-- </div> -->

										<!-- Setup -->
										<div id="w2-setup" class="tab-pane">	
											<form id="contact_information_form">
												<div class="hidden"><input type="text" class="form-control" name="company_code" value="<?=isset($company_code)?$company_code:"" ?>"/></div>
												
												<div class="hidden"><input type="text" class="form-control" name="client_contact_info_id" value="<?=isset($client_contact_info[0]->id)?$client_contact_info[0]->id:"" ?>"/></div>

												<button type="button" class="setup_collapsible" style="margin-top: 10px;">
														<span style="font-size: 2.4rem;">Contact Information</span>
												</button>

												<div class="setup_content">
													<div style="margin-top: 20px; margin-bottom: 50px;">
														<div class="form-group">
															<label class="col-sm-3" for="w2-chairman">Name:</label>
															<div class="col-sm-9">
																<input type="text" style="width:400px;text-transform:uppercase;" class="form-control" name="contact_name" id="contact_name" value="<?=isset($client_contact_info[0])?$client_contact_info[0]->name:"" ?>"/>
												            </div>
														</div>
														<div class="form-group">
															<label class="col-sm-3" for="w2-chairman">Phone:</label>
															<div class="col-sm-9">
																<div class="input-group fieldGroup_contact_phone">
																	<input type="tel" class="form-control check_empty_contact_phone main_contact_phone hp" id="contact_phone" name="contact_phone[]" value="<?=isset($client_contact_info[0]->contact_phone)?$client_contact_info[0]->contact_phone:"" ?>"/>
																	<input type="hidden" class="form-control input-xs hidden_contact_phone main_hidden_contact_phone" id="hidden_contact_phone" name="hidden_contact_phone[]" value=""/>
																	<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="contact_phone_primary main_contact_phone_primary" name="contact_phone_primary" value="1" checked>
																		Primary
																	</label>
																<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
																<!-- 	<input class="btn btn-primary button_increment_contact_phone addMore_contact_phone" type="button" id="create_button" value="+" style="margin-left: 20px; margin-top: -26px; border-radius: 3px;visibility: hidden; width: 35px;"/> -->
																<!-- </span> -->
																	<button type="button" class="btn btn-default btn-sm show_contact_phone" style="margin-left: 20px; margin-top: -23px; visibility: hidden;">
																		  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
																	</button>
																</div>
																<div class="contact_phone_toggle"></div>
																<div class="input-group fieldGroupCopy_contact_phone contact_phone_disabled" style="display: none;">
																	<input type="tel" class="form-control check_empty_contact_phone second_contact_phone second_hp" id="contact_phone" name="contact_phone[]" value=""/>
																	<input type="hidden" class="form-control input-xs hidden_contact_phone" id="hidden_contact_phone" name="hidden_contact_phone[]" value=""/>
																	<label class="radio-inline control-label" style="margin-top: -30px; margin-left: 20px;"><input type="radio" class="contact_phone_primary" name="contact_phone_primary" value="1"/> 
																		Primary
																	</label>
																	<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
																	<!-- <input class="btn btn-primary button_decrease_contact_phone remove_contact_phone" type="button" id="create_button" value="-" style="margin-left: 20px; margin-top: -26px; border-radius: 3px; width: 35px;"/> -->
																	<!-- </span> -->
																</div>
																<div id="form_contact_phone"></div>
												       		</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3" for="w2-chairman">Email:</label>
															<div class="col-sm-9">
																<!-- <input type="email" style="width:400px;" class="form-control" name="contact_email" id="contact_email" value="<?=$client_contact_info[0]->email?>"/> -->
																<div class="input-group fieldGroup_contact_email" style="display: block !important;">
																	<input type="text" class="form-control input-xs check_empty_contact_email main_contact_email" id="contact_email" name="contact_email[]" value="<?=isset($client_contact_info[0]->contact_email)?$client_contact_info[0]->contact_emal:"" ?>" style="text-transform:uppercase; width:400px;"/>
																	<label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="contact_email_primary main_contact_email_primary" name="contact_email_primary" value="1" checked/> 
																		Primary
																	</label>
																	<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
														<!-- 			<input class="btn btn-primary button_increment_contact_email addMore_contact_email" type="button" id="create_button" value="+" style="margin-left: 20px; border-radius: 3px;visibility: hidden; width: 35px;"/> -->
																	<!-- </span> -->
																	<button type="button" class="btn btn-default btn-sm show_contact_email" style="margin-left: 20px; visibility: hidden;">
																		  <span class="fa fa-arrow-down" aria-hidden="true"></span>&nbsp<span class="toggle_word">Show more</span>
																	</button>
																</div>
																<div class="contact_email_toggle"></div>

																<div class="input-group fieldGroupCopy_contact_email contact_email_disabled" style="display: none;">
																	<input type="text" class="form-control input-xs check_empty_contact_email second_contact_email" id="contact_email" name="contact_email[]" value="" style="width:400px; text-transform:uppercase; "/>
																	<label class="radio-inline control-label" style="margin-left: 20px;"><input type="radio" class="contact_email_primary" name="contact_email_primary" value="1"> Primary</label>
																	
																	<!-- <span class="input-group-btn" style="vertical-align: top !important;"> -->
																		<!-- <input class="btn btn-primary button_decrease_contact_email remove_contact_email" type="button" id="create_button" value="-" style="margin-left: 20px; border-radius: 3px; width: 35px;"/> -->
																	<!-- </span> -->
																</div>

																<div id="form_contact_email"></div>
												            </div>
														</div>
													</div>
													<div class="form-group">
														<!-- <div class="col-sm-12">
															<button type="button" class="btn btn-primary save_contact_information" id="save_contact_information" style="float: right;margin-bottom: 10px;">Save</button>
														</div> -->
													</div>
												</div>

												
											</form>

						
											<form id="reminder_form">
												<div class="hidden">
													<input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/>
												</div>
															
												<div class="hidden"><input type="text" class="form-control" name="client_contact_info_id" value=""/></div>

												<button type="button" class="setup_collapsible" style="margin-top: 10px;">
													<span style="font-size: 2.4rem;">Reminder</span>
												</button>
												<div class="setup_content">
													<div style="margin-top: 20px; margin-bottom: 100px;">
														<div class="form_select_stocktake form-group">
															<label class="col-sm-3" for="w2-DS2">Stocktake Reminder:</label>
															<div class="col-xs-8">
													            <div class="input-group" style="width: 200px;" >
													                <input type="checkbox" id="stocktake_checkbox" name="stocktake_checkbox" <?=isset($client_reminder_info[0]->reminder_flag)?$client_reminder_info[0]->reminder_flag?'checked':'':'';?> />
													                <input type="hidden" name="hidden_stocktake_checkbox" value=""/>
													            </div>
													        </div>
														</div>
														<div class="stocktake_email form-group">
															<label class="col-sm-3" for="w2-DS2">Receiver E-mail Address:</label>
															<div class="col-xs-8">
													            <div style="width: 200px;" >
													                <input type="text" style="width:400px;" class="form-control" name="stocktake_email" value="<?=isset($client_reminder_info[0]->email)?$client_reminder_info[0]->email?$client_reminder_info[0]->email:'':'';?>"/>
													            </div>
													        </div>
														</div>
													</div>
													<div class="form-group">
														<!-- <div class="col-sm-12">
															<button type="button" class="btn btn-primary btn_blue save_reminder" id="save_stocktake_setting" style="float: right;margin-bottom: 10px;">Save</button>
														</div> -->
													</div>
												</div>
											</form>


										</div>
										
										<!-- Service engagement  -->
										<div id="w2-billing" class="tab-pane">
											<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'billing_form');
												echo form_open_multipart("client/add_client_billing_info", $attrib);
											?> 
												<div class="hidden">
													<input type="text" class="form-control company_code" name="company_code" value="<?=$company_code?>"/>
												</div>
												<div class="form-group">
													<div class="col-sm-6">
														<h3>Service Engagement Information</h3>
													</div>
													<div class="col-sm-6"></div>
												</div>
											<!-- 	<div style="display: table; border-collapse: collapse;">
													<thead>
														<div class="tr"> 
															<div class="th" valign=middle style="width:210px;text-align: center">
																Service
															</div>
															<div class="th" valign=middle style="width:250px;text-align: center">
																Invoice Description
															</div>
															<div class="th" style="width:180px;text-align: center">
																Currency
															</div>
															<div class="th" style="width:180px;text-align: center">
																Amount
															</div>
															<div class="th" style="width:180px;text-align: center">
																Unit Pricing
															</div>

															<a href="javascript: void(0);" class="th" rowspan =2 style="color: #D9A200;width:230px; outline: none !important;text-decoration: none;"><span id="billing_info_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Service Engagement Information" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Service Engagement</span></a>
														</div>
													</thead>
													<div class="tbody" id="body_billing_info"></div>
												</div> -->
											<?= form_close(); ?>
										</div>
								

										<div id="w2-filing" class="tab-pane">
											<h3>Key Filing</h3>
											<button type="button" class="collapsible" style="margin-top: 10px;">
												<span style="font-size: 2.4rem;">
													AGM and Annual Return
												</span>
											</button>
											<div class="incorp_content">
												<!-- <?php echo form_open_multipart('', array('id' => 'filing_form', 'enctype' => "multipart/form-data")); ?> -->
													<!-- <div class="hidden">
														<input type="text" class="form-control" name="company_code" value="<?=$company_code?>"/>
													</div>
													<div class="hidden">
														<input type="text" class="form-control filing_id" name="filing_id" value=""/>
													</div>
													<div class="form-group" style="margin-top: 20px; margin-bottom: 20px;">
														<label class="col-xs-3" for="w2-show_all">Year End: </label>
														<div class="col-sm-9 form-inline">
															<div class="input-bar">
															    <div class="input-bar-item">
															   
															         <div class="year_end_group">
															            <div class="input-group" style="width: 200px;">
																			<span class="input-group-addon">
																				<i class="far fa-calendar-alt"></i>
																			</span>
																			<input type="text" class="form-control year_end_date datepicker" id="year_end_date" autocomplete="off" name="year_end" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="" placeholder="dd MMMM yyyy "/>
																		</div>
																		<div id="validate_year_end_date"></div>
															        </div>
															     
															    </div>
															    <div class="input-bar-item input-bar-item-btn">
															      	<button type="button" class="btn btn-primary btn_blue change_year_end_button" onclick="change_year_end(this)" style="display: none">
															      		Change
															  		</button>
															    </div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3" for="w2-DS1">Financial Year Cycle:</label>
															<div class="col-sm-3">
																<select id="financial_year_period" class="form-control financial_year_period" id="financial_year_period" style="width:200px;" name="financial_year_period">
												                   
												                </select>
															</div>
													</div>
												<div class="form-group row_colunm_date_175">
													<label class="col-sm-3" for="w2-DS1">Due Date AGM (S.175):</label>
														<div class="col-sm-3">
															<input type="text" name="due_date_175" class="form-control" value="" id="due_date_175" readonly="true" style="width: 200px;"/>
														</div>
														
														<div class="col-sm-6">
															<label class="col-sm-3" for="extended_to">Extended to</label>
															<select id="extended_to_175" class="form-control extended_to_175" id="extended_to_175" style="width:200px;" name="extended_to_175">
											                    <option value="0">Please Select</option>
											                </select>
											            </div>
												</div>
													<div class="form-group">
														<label class="col-sm-3" for="w2-DS2">Due Date New AGM (S.175):</label>
														<div class="col-sm-3">
															<input type="text" name="due_date_201" class="form-control" value="" id="due_date_201" readonly="true" style="width: 200px;"/>
														</div>
														<div class="col-sm-6">
															<label class="col-sm-3" for="extended_to">Extended to</label>
															<select id="extended_to_201" class="form-control extended_to_201" id="extended_to_201" style="width:200px;" name="extended_to_201">
											                    <option value="0">Please Select</option>
											                </select>
											            </div>
													</div>
													<div class="form-group">
														<label class="col-sm-3" for="w2-DS3">Due Date AR (S.197):</label>
														<div class="col-sm-3">
															<input type="text" name="due_date_197" class="form-control" value="" id="due_date_197" readonly="true" style="width: 200px;"/>
														</div>
														<div class="col-sm-6">
															<label class="col-sm-3" for="extended_to">Extended to</label>
															<select id="extended_to_197" class="form-control extended_to_197" id="extended_to_197" style="width:200px;" name="extended_to_197">
											                    <option value="0">Please Select</option>
											                </select>
											            </div>
													</div>
													<div class="form-group">
														<label class="col-sm-3" for="w2-AGM">AGM:</label>
														<div class="col-sm-9 form-inline">
															<div class="input-bar">
															    <div class="input-bar-item">
															     
															        <div class="year_end_group">
															            <div class="input-group" style="width: 200px;">
																			<span class="input-group-addon">
																				<i class="far fa-calendar-alt"></i>
																			</span>
																			<input type="text" class="form-control" id="agm_date" name="agm" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" autocomplete="off" value="" placeholder="dd MMMM yyyy">
																		</div>
																		<div id="validate_year_end_date"></div>
															        </div>
															      
															    </div>
															    <div class="input-bar-item input-bar-item-btn">
															      <button type="button" class="btn btn-primary btn_blue dispense_agm_button" onclick="dispense_agm(this)" style="display: none">Dispense AGM</button>
															    </div>
															</div>
											            </div>
													</div>
													<div class="form-group">
														<label class="col-sm-3" for="w2-DS1">AR Filing Date:</label>
														<div class="col-sm-3">
															<div class="year_end_group">
														        <div class="input-group" style="width: 200px;">
																	<span class="input-group-addon">
																		<i class="far fa-calendar-alt"></i>
																	</span>
																	<input type="text" name="ar_filing_date" data-date-format="dd/mm/yyyy" class="form-control" data-plugin-datepicker="" value="" id="ar_filing_date" placeholder="dd MMMM yyyy"/>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group">
														<div class="col-sm-12">
															<button type="button" class="btn btn-primary btn_blue update_filling" id="update_filling" style="float: right">
																Update
															</button>
														</div>
													</div> -->
													<div style="margin-top: 20px;margin-bottom: 20px;">
														<h3>AGM and Annual Return Filing History</h3>
														<table style="border:1px solid black" class="allotment_table" id="filing_table">
															<tr> 
																<th style="width:50px !important;text-align: center">No</th>
																<th style="text-align: center">Year End</th> 
																<th style="text-align: center">Financial Year Period</th>
																<th style="text-align: center">Due Date AGM (S.175)</th> 
																<th style="text-align: center">Due Date New AGM (S.175)</th> 
																<th style="text-align: center">Due Date AR (S.197)</th>
																<th style="text-align: center">AGM</th> 
																<th style="text-align: center">AR Filing Date</th>
														
															</tr> 
														</table>
													</div>
											<!-- 	<?= form_close(); ?>	 -->
											</div>		
										</div>

										<div id="w2-bank_detail" class="tab-pane">
											<table class="table table-bordered table-striped mb-none datatable-default" id="datatable-bank_detail" style="width: 100%">
												<thead>
													<tr>
														<th>Bank Name</th>
														<th>Active</th>
													</tr>
												</thead>
												<tbody>
													<?php
														// $i = 1;
														if($client_bank_detail){
															foreach($client_bank_detail as $b)
															{
																//echo '';
																echo '<tr>';
																echo '<td>'.$b['bank_name'].'</td>';
																echo '<td width="10%" align="center" data-order='.$b['active'].'><input type="hidden" class="auth_id" value="'. $b['id'] .'" /><label class="verify_switch"><input name="active_switch" onchange=change_bank_auth_active(this) class="active_switch" type="checkbox" '.(($b["active"])?"checked":"").'><span class="slider round"></span></label></td>';
																echo '</tr>';

																// $i++;
															}
														}
														
													?>

												</tbody>
											</table>
										</div>
											
										<div id="w2-bank" class="tab-pane">
											<table class="table table-bordered table-striped mb-none datatable-default" id="datatable-bank" style="width:100%">
												<thead>
													<tr style="background-color:white;">
														<th class="text-left">Parties</th>
														<th class="text-left">Type</th>
														<th class="text-left">Status</th>
														<!-- <?php
														if($Admin || $Manager) 
															{
																echo'<th class="text-left"></th>';
															}
														?>	 -->
													</tr>
												</thead>
												<tbody>
													<?php
														// $i = 1;
														if($client_bank_summary){
															foreach($client_bank_summary as $s)
															{
																//echo '';
																echo '<tr>';
																echo '<td>'.$s['bank_name'].'</td>';
																echo '<td>'.$s['source'].'</td>';
																echo '<td>'.$s['status'].'</td>';
																// echo '<td width="10%" align="center" data-order='.$b['active'].'><input type="hidden" class="auth_id" value="'. $b['id'] .'" /><label class="switch"><input name="active_switch" onchange=change_bank_auth_active(this) class="active_switch" type="checkbox" '.(($b["active"])?"checked":"").'><span class="slider round"></span></label></td>';
																echo '</tr>';

																// $i++;
															}
														}
														
													?>
												</tbody>
											</table>
										</div>

						<!-- 				<div id="w2-paf" class="tab-pane">
											<table class="table table-bordered table-striped mb-none datatable-default" id="datatable-paf" style="width:100%">
												<thead>
													<tr style="background-color:white;">
														<th class="text-left">Final Year End</th>
														<th class="text-left"></th>
											
													</tr>
												</thead>
												<tbody>
													<?php
														// $i = 1;
														if($client_paf){
															foreach($client_paf as $p)
															{
																//echo '';
																echo '<tr>';
																echo '<td style="width:60%;"><a href="'.site_url("paf_upload/edit_paf_client").'/'.$p['id'].'" class="pointer mb-sm mt-sm mr-sm">'.$p['fye_date'].'</a></td>';
																echo '<td style="width:10%;text-align:center;">
											  						  <input type="hidden" class="paf_id" value="'. $p['id'] .'" />
																	  <button type="button" class="btn btn_blue" onclick=delete_paf(this) style="margin-bottom:5px;margin-right:5px;margin-left:5px;">Delete</button></td>';
																// echo '<td width="10%" align="center" data-order='.$b['active'].'><input type="hidden" class="auth_id" value="'. $b['id'] .'" /><label class="switch"><input name="active_switch" onchange=change_bank_auth_active(this) class="active_switch" type="checkbox" '.(($b["active"])?"checked":"").'><span class="slider round"></span></label></td>';
																echo '</tr>';

																// $i++;
															}
														}
														
													?>
												</tbody>
											</table>
										</div>
 -->									</div>
								</div>
							</div>
						</div>
					</div>
					<footer class="panel-footer">
				<!-- 		<div class="row">
							<div class="col-md-12 number text-right client_footer_button" id="client_footer_button"> -->
								<!-- input type="button" value="Save As Draft" id="save_draft" class="btn btn-default" -->
								<!-- <input type="button" value="Save" id="save" class="btn btn-primary btn_blue">
								<a href="<?= base_url();?>client/" class="btn btn-default">Cancel</a>
							</div> -->
						<!-- </div> -->
					</footer>
				</section>
			</div>
		</div>
	</div>
</section>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
										
								
<!-- <div class="tab-pane" id="jquerytab">jQuery content here </div>
  <div class="tab-pane" id="bootstab">Bootstrap Content here
  <ul>
  <li>Bootstrap forms</li>
  <li>Bootstrap buttons</li>
  <li>Bootstrap navbar</li>
  <li>Bootstrap footer</li>
  </ul>
  </div>
  <div class="tab-pane" id="htmltab">Hypertext Markup Language</div>  -->
										<!-- 	</div>
										</div> -->
									
		
			
	<!-- </form> -->
	
<!-- end: page -->

<script type="text/javascript">
	var client = <?php echo json_encode(isset($client)?$client:"");?>;
	var client_billing_info = <?php echo json_encode(isset($client_billing_info)?$client_billing_info:"");?>;
	var count_billing_info = "";
	var filing_data = <?php echo json_encode(isset($filing_data)?$filing_data:"");?>;
	var admin = <?php echo json_encode($Admin)?>;
	var company_code = "<?php echo ($company_code);?>";	
	var setup_section, submit_setup_link, submit_setup_data;
	var client_contact_info_email = <?php echo json_encode(isset($client_contact_info)?$client_contact_info[0]->client_contact_info_email:"");?>;
	var client_contact_info_phone = <?php echo json_encode(isset($client_contact_info)?$client_contact_info[0]->client_contact_info_phone:"");?>;
	var update_bank_auth_url = "<?php echo site_url('bank/update_bank_auth'); ?>"
	var get_financial_year_period_url = "<?php echo site_url('client/get_financial_year_period'); ?>";
	var check_incorporation_date_url = "<?php echo site_url('client/check_incorporation_date'); ?>";
	var get_gst_filing_cycle_url = "<?php echo site_url('client/get_gst_filing_cycle'); ?>";
	var check_filing_data_url = "<?php echo site_url('client/check_filing_data'); ?>";
	var add_filing_info_url = "<?php echo site_url('client/add_filing_info'); ?>";
	var delete_filing_url = "<?php echo site_url('client/delete_filing'); ?>";
	var calculate_new_filing_date_url = "<?php echo site_url('client/calculate_new_filing_date'); ?>";
	var search_client_billing_url = "<?php echo site_url('client/search_client_billing'); ?>";
	var get_billing_info_service_url = "<?php echo site_url('client/get_billing_info_service'); ?>";
	var get_currency_url = "<?php echo site_url('client/get_currency'); ?>";
	var get_servicing_firm_url = "<?php echo site_url('client/get_servicing_firm'); ?>";
	var get_unit_pricing_url = "<?php echo site_url('client/get_unit_pricing'); ?>";
	var get_billing_info_frequency_url = "<?php echo site_url('client/get_billing_info_frequency'); ?>";
	var get_type_of_day_url = "<?php echo site_url('client/get_type_of_day'); ?>";
	var delete_client_billing_info_url = "<?php echo site_url('client/delete_client_billing_info'); ?>";
	var check_next_recurring_date_url = "<?php echo site_url('client/check_next_recurring_date'); ?>";
	var edit_client_url = "<?php echo site_url('client/edit'); ?>";
	var tab = <?php echo json_encode(isset($tab)?$tab:"");?>;
	var tab_aktif = $('.check_stat.active').data("information");
	

</script>

<!--Company type js is for drop down list-->
<script src="<?= base_url()?>application/modules/client/js/companyType.js" charset="utf-8"></script>
<script src="<?= base_url()?>application/modules/client/js/filing.js" charset="utf-8"></script>
<script src="<?= base_url()?>application/modules/client/js/billing.js" charset="utf-8"></script>
<script src="<?= base_url()?>application/modules/client/js/setup.js" charset="utf-8"></script>

<script type="text/javascript">

	// if(client != null)
	// {
		
	// 	if(client["registered_address"] == 1)
	// 	{
	// 		$(".service_reg_off_area").show();
	// 		document.getElementById("postal_code").readOnly = true;
	//       	document.getElementById("street_name").readOnly = true;
	//       	document.getElementById("building_name").readOnly = true;
	//       	document.getElementById("unit_no1").readOnly = true;
	//       	document.getElementById("unit_no2").readOnly = true;
	// 	}
		

	// }
	$('input[type="text"],input[type="tel"], textarea').attr('readonly','readonly');
	$('select').attr('disabled',true);
	$('input[type="checkbox"]').attr('disabled',true);
	$('#w2-bank_detail input[type="checkbox"]').attr('disabled',false);
	$("[name='stocktake_checkbox']").bootstrapSwitch();
	$("[name='stocktake_checkbox']").bootstrapSwitch('disabled',true);
	
	$('.datepicker').datepicker({
	    format: 'dd/mm/yyyy',

	});
	
	console.log(tab+"tab");

	if(tab == "filing")
	{
		$('#myTab #li-companyInfo').removeClass("active");
		$('.tab-content #w2-companyInfo').removeClass("active");

		$('#myTab #li-filing').addClass("active");
		$('.tab-content #w2-filing').addClass("active");

		$(".client_footer_button").hide();
		tab_aktif = "filing";
	}
	else if(tab == "setup")
	{
		$('#myTab #li-companyInfo').removeClass("active");
		$('.tab-content #w2-companyInfo').removeClass("active");

		$('#myTab #li-setup').addClass("active");
		$('.tab-content #w2-setup').addClass("active");

		$(".client_footer_button").hide();
		tab_aktif = "setup";
	}
	else if(tab == "billing")
	{
		$('#myTab #li-companyInfo').removeClass("active");
		$('.tab-content #w2-companyInfo').removeClass("active");

		$('#myTab #li-billing').addClass("active");
		$('.tab-content #w2-billing').addClass("active");
		tab_aktif = "billing";
	}
	else if(tab == "bank_detail")
	{
		$('#myTab #li-companyInfo').removeClass("active");
		$('.tab-content #w2-companyInfo').removeClass("active");

		$('#myTab #li-bank_detail').addClass("active");
		$('.tab-content #w2-bank_detail').addClass("active");
		tab_aktif = "bank_detail";
	}
	$('.nav li').not('.active').addClass('disabled');

	if(client)
	{
		$('.nav li').removeClass('disabled');
	}
	else
	{
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


	incorp_coll = document.getElementsByClassName("collapsible");

	for (var g = 0; g < incorp_coll.length; g++) {
	incorp_coll[g].classList.toggle("incorp_active");
	incorp_coll[g].nextElementSibling.style.maxHeight = "100%";
	}

	for (var i = 0; i < incorp_coll.length; i++) {
	incorp_coll[i].addEventListener("click", function() {
	this.classList.toggle("incorp_active");
	var content = this.nextElementSibling;
	if (content.style.maxHeight){
	  content.style.maxHeight = null;
	} else {
	  content.style.maxHeight = "100%";
	} 
	});
	}
	setup_coll = document.getElementsByClassName("setup_collapsible");

	for (var g = 0; g < setup_coll.length; g++) {
	setup_coll[g].classList.toggle("setup_active");
	setup_coll[g].nextElementSibling.style.maxHeight = "100%";
	}

	for (var i = 0; i < setup_coll.length; i++) {
		setup_coll[i].addEventListener("click", function() {
		this.classList.toggle("setup_active");
		var content = this.nextElementSibling;
			if (content.style.maxHeight){
			  content.style.maxHeight = null;
			} else {
			  content.style.maxHeight = "100%";
			} 
		});
	}

	// $('#w2-setup .show_contact_phone').attr('disabled', false);
	// $('#w2-setup .show_contact_email').attr('disabled', false);

	// $(".check_empty_contact_phone").live('change',function(){
	// 	$( '#form_contact_phone' ).html("");
	// });

	// $(".check_empty_contact_email").live('change',function(){
	// 	$( '#form_contact_email' ).html("");
	// });

	$(document).on('blur', '.check_empty_contact_phone', function(){
	    $(this).parent().parent().find(".hidden_contact_phone").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
	    $(this).parent().parent().find(".contact_phone_primary").attr("value", $(this).intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164));
	});

	$(document).on('blur', '.check_empty_contact_email', function(){
	    $(this).parent().find(".contact_email_primary").attr("value", $(this).val());
	});

	$(document).ready(function() {

		$('#datatable-bank').DataTable( {
	    	"order": []
	    });

	    $('#datatable-bank_detail').DataTable( {
	    	"order": [1, 'desc']
	    });

	    $(document).on('click', '.contact_phone_primary', function(event){   
	        event.preventDefault();
	        var contact_phone_primary_radio_button = $(this);
	        bootbox.confirm("Are you comfirm set as primary for this contact_phone?", function (result) {
	            if (result) {
	                contact_phone_primary_radio_button.prop( "checked", true );
	                $( '#form_contact_phone' ).html("");
	            }
	        });
	    });


	    $(document).on('click', '.contact_email_primary', function(event){  
	        event.preventDefault();
	        var email_primary_radio_button = $(this);
	        bootbox.confirm("Are you comfirm set as primary for this Email?", function (result) {
	            if (result) {
	                email_primary_radio_button.prop( "checked", true );
	                $( '#form_contact_email' ).html("");
	            }
	        });
	    });

	    $(".check_empty_contact_phone").on({
	      keydown: function(e) {
	        if (e.which === 32)
	          return false;
	      },
	      change: function() {
	        this.value = this.value.replace(/\s/g, "");
	      }
	    });


	    $(".addMore_contact_phone").click(function(){
	        var number = $(".main_contact_phone").intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164);

	        var countryData = $(".main_contact_phone").intlTelInput("getSelectedCountryData");

	        $(".contact_phone_toggle").show();
	        $(".show_contact_phone").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
	        $(".show_contact_phone").find(".toggle_word").text('Show less');

	        $(".fieldGroupCopy_contact_phone").find('.second_contact_phone').attr("value", $(".main_contact_phone").val());
	        $(".fieldGroupCopy_contact_phone").find('.hidden_contact_phone').attr("value", number);
	        $(".fieldGroupCopy_contact_phone").find('.contact_phone_primary').attr("value", number);
	        //$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
	        //$(".fieldGroupCopy_contact_phone").find('.second_contact_phone').intlTelInput("setCountry", countryData.iso2);

	        var fieldHTML = '<div class="input-group fieldGroup_contact_phone" style="margin-top:10px;">'+$(".fieldGroupCopy_contact_phone").html()+'</div>';

	        //$('body').find('.fieldGroup_contact_phone:first').after(fieldHTML);
	        $( fieldHTML).prependTo(".contact_phone_toggle");

	        $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').intlTelInput({
	            preferredCountries: [ "sg", "my"],
	            formatOnDisplay: false,
	            nationalMode: true,
	            initialCountry: countryData.iso2,
	            geoIpLookup: function(callback) {
	                jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
	                    var countryCode = (resp && resp.country) ? resp.country : "";
	                    callback(countryCode);
	                });
	            },
	            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
	              return "" ;
	            },
	            utilsScript: "../themes/default/js/utils.js"
	        });

	        $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').on({
	          keydown: function(e) {
	            if (e.which === 32)
	              return false;
	          },
	          change: function() {
	            this.value = this.value.replace(/\s/g, "");
	          }
	        });

	        if ($(".main_contact_phone_primary").is(":checked")) 
	        {
	            $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.contact_phone_primary').prop( "checked", true );
	        }


	        $(".button_increment_contact_phone").css({"visibility": "hidden"});

	        if ($(".contact_phone_toggle").find(".second_contact_phone").length > 0) 
	        {
	            $(".show_contact_phone").css({"visibility": "visible"});

	        }
	        else {
	            $(".show_contact_phone").css({"visibility": "hidden"});
	            
	        }
	       
	        $(".main_contact_phone").val("");
	        $(".main_contact_phone").parent().parent().find(".hidden_contact_phone").val("");
	        $(".main_contact_phone").parent().parent().find(".contact_phone_primary").val("");
	        $(".fieldGroupCopy_contact_phone").find('.second_contact_phone').attr("value", "");
	        $(".fieldGroupCopy_contact_phone").find('.hidden_contact_phone').attr("value", "");
	        $(".fieldGroupCopy_contact_phone").find('.contact_phone_primary').attr("value", "");

	    });

	    $("body").on("click",".remove_contact_phone",function(){ 
	        var remove_contact_phone_button = $(this);
	        bootbox.confirm("Are you comfirm delete this contact_phone?", function (result) {
	            if (result) {

	                remove_contact_phone_button.parents(".fieldGroup_contact_phone").remove();

	                if (remove_contact_phone_button.parent().find(".contact_phone_primary").is(":checked")) 
	                {
	                    if ($(".contact_phone_toggle").find(".second_contact_phone").length > 0) 
	                    {
	                        $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.contact_phone_primary').prop( "checked", true );
	                    }
	                    else
	                    {
	                        $(".main_contact_phone_primary").prop( "checked", true );
	                    }
	                    
	                }

	                if ($(".contact_phone_toggle").find(".second_contact_phone").length > 0) 
	                {
	                    $(".show_contact_phone").css({"visibility": "visible"});

	                }
	                else {
	                    $(".show_contact_phone").css({"visibility": "hidden"});
	                    
	                }
	            }
	        });
	    });

	    $('.main_contact_phone').keyup(function(){

	        if ($(this).val()) {
	            $(".button_increment_contact_phone").css({"visibility": "visible"});

	        }
	        else {
	            $(".button_increment_contact_phone").css({"visibility": "hidden"});
	        }
	    });



	    $(".addMore_contact_email").click(function(){
	    	//console.log("1234");
	        $(".contact_email_toggle").show();
	        $(".show_contact_email").find(".fa").addClass("fa-arrow-up").removeClass("fa-arrow-down");
	        $(".show_contact_email").find(".toggle_word").text('Show less');

	        $(".fieldGroupCopy_contact_email").find('.second_contact_email').attr("value", $(".main_contact_email").val());
	        //$(".fieldGroupCopy").find('.second_local_fix_line').intlTelInput("setNumber", number);
	        //$(".fieldGroupCopy_email").find('.second_email').intlTelInput("setCountry", countryData.iso2);
	        $(".fieldGroupCopy_contact_email").find('.contact_email_primary').attr("value", $(".main_contact_email").val());

	        var fieldHTML = '<div class="input-group fieldGroup_contact_email" style="margin-top:10px; display: block !important;">'+$(".fieldGroupCopy_contact_email").html()+'</div>';

	        //$('body').find('.fieldGroup_email:first').after(fieldHTML);
	        $( fieldHTML).prependTo(".contact_email_toggle");

	        if ($(".main_contact_email_primary").is(":checked")) 
	        {
	            $(".contact_email_toggle .fieldGroup_contact_email").eq(0).find('.contact_email_primary').prop( "checked", true );
	        }
	        
	        $(".button_increment_contact_email").css({"visibility": "hidden"});
	       	
	       if ($(".contact_email_toggle").find(".second_contact_email").length > 0) 
	        {
	        	//console.log($(".show_contact_email"));
	             $(".show_contact_email").css({"visibility": "visible"});
	             $(".show_contact_email").show();

	        }
	        else {
	            $(".show_contact_email").css({"visibility": "hidden"});
	            $(".show_contact_email").hide();
	            
	        }

	        $(".main_contact_email").val("");
	        $(".main_contact_email").parent().find(".main_contact_email_primary").val("");
	        $(".fieldGroupCopy_contact_email").find('.second_contact_email').attr("value", "");
	        $(".fieldGroupCopy_contact_email").find('.contact_email_primary').attr("value", "");

	    });

	    $("body").on("click",".remove_contact_email",function(){ 
	        var remove_contact_email_button = $(this);
	        bootbox.confirm("Are you comfirm delete this Email?", function (result) {
	            if (result) {

	                remove_contact_email_button.parents(".fieldGroup_contact_email").remove();

	                if (remove_contact_email_button.parent().find(".contact_email_primary").is(":checked")) 
	                {
	                    if ($(".contact_email_toggle").find(".second_contact_email").length > 0) 
	                    {
	                        $(".contact_email_toggle .fieldGroup_contact_email").eq(0).find('.contact_email_primary').prop( "checked", true );
	                    }
	                    else
	                    {
	                        $(".main_contact_email_primary").prop( "checked", true );
	                    }
	                }

	                if ($(".contact_email_toggle").find(".second_contact_email").length > 0) 
	                {
	                    $(".show_contact_email").show();

	                }
	                else {
	                    $(".show_contact_email").hide();
	                    
	                }
	            }
	        });
	    });

	    $('.main_contact_email').keyup(function(){

	        if ($(this).val()) {
	            $(".button_increment_contact_email").css({"visibility": "visible"});

	        }
	        else {
	            $(".button_increment_contact_email").css({"visibility": "hidden"});
	        }
	    });

	    if ($(".contact_phone_toggle").find(".second_contact_phone").length > 0) 
	    {
	        $(".show_contact_phone").css({"visibility": "visible"});
	        $(".contact_phone_toggle").hide();

	    }
	    else {
	        $(".show_contact_phone").css({"visibility": "hidden"});
	        $(".contact_phone_toggle").hide();
	    }


	    if ($(".contact_email_toggle").find(".second_contact_email").length > 0) 
	    {
	        $(".show_contact_email").css({"visibility": "visible"});
	        $(".contact_email_toggle").hide();

	    }
	    else {
	        $(".show_contact_email").css({"visibility": "hidden"});
	        $(".contact_email_toggle").hide();
	    }

	});
	
	$('.show_contact_phone').click(function(e){
	    e.preventDefault();
	    $(this).parent().parent().find(".contact_phone_toggle").toggle();
	    console.log($(this).parent().parent());
	    var icon = $(this).find(".fa");
	    if(icon.hasClass("fa-arrow-down"))
	    {
	        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
	        $(this).find(".toggle_word").text('Show less');
	    }
	    else
	    {
	        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
	        $(this).find(".toggle_word").text('Show more');
	    }
	});


	$('.show_contact_email').click(function(e){
	    e.preventDefault();
	    $(this).parent().parent().find(".contact_email_toggle").toggle();
	    var icon = $(this).find(".fa");
	    if(icon.hasClass("fa-arrow-down"))
	    {
	        icon.addClass("fa-arrow-up").removeClass("fa-arrow-down");
	        $(this).find(".toggle_word").text('Show less');
	    }
	    else
	    {
	        icon.addClass("fa-arrow-down").removeClass("fa-arrow-up");
	        $(this).find(".toggle_word").text('Show more');
	    }
	});



	$('.hp').intlTelInput({
	    preferredCountries: [ "sg", "my"],
	    initialCountry: "auto",
	    formatOnDisplay: false,
	    nationalMode: true,
	    geoIpLookup: function(callback) {
	        jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
	            var countryCode = (resp && resp.country) ? resp.country : "";
	            callback(countryCode);
	        });
	    },
	    customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
	      return "" ;
	    },
	    utilsScript: "../themes/default/js/utils.js"
	});

	if(client_contact_info_phone != null)
	{
	    for (var h = 0; h < client_contact_info_phone.length; h++) 
	    {
	        var clientContactInfoPhoneArray = client_contact_info_phone[h].split(',');

	        if(clientContactInfoPhoneArray[2] == 1)
	        {
	            $(".fieldGroup_contact_phone").find('.main_contact_phone').intlTelInput("setNumber", clientContactInfoPhoneArray[1]);
	            $(".fieldGroup_contact_phone").find('.main_hidden_contact_phone').attr("value", clientContactInfoPhoneArray[1]);
	            $(".fieldGroup_contact_phone").find('.main_contact_phone_primary').attr("value", clientContactInfoPhoneArray[1]);
	            $(".fieldGroup_contact_phone").find(".button_increment_contact_phone").css({"visibility": "visible"});
	        }
	        else
	        {
	            
	            $(".fieldGroupCopy_contact_phone").find('.hidden_contact_phone').attr("value", clientContactInfoPhoneArray[1]);
	            $(".fieldGroupCopy_contact_phone").find('.contact_phone_primary').attr("value", clientContactInfoPhoneArray[1]);


	            var fieldHTML = '<div class="input-group fieldGroup_contact_phone" style="margin-top:10px;">'+$(".fieldGroupCopy_contact_phone").html()+'</div>';

	            //$('body').find('.fieldGroup_contact_phone:first').after(fieldHTML);
	            $( fieldHTML).prependTo(".contact_phone_toggle");

	            $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').intlTelInput({
	                preferredCountries: [ "sg", "my"],
	                formatOnDisplay: false,
	                nationalMode: true,
	                geoIpLookup: function(callback) {
	                    jQuery.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
	                        var countryCode = (resp && resp.country) ? resp.country : "";
	                        callback(countryCode);
	                    });
	                },
	                customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
	                  return "" ;
	                },
	                utilsScript: "../themes/default/js/utils.js"
	            });

	            $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').intlTelInput("setNumber", clientContactInfoPhoneArray[1]);

	            $('.contact_phone_toggle .fieldGroup_contact_phone').eq(0).find('.second_hp').on({
	              keydown: function(e) {
	                if (e.which === 32)
	                  return false;
	              },
	              change: function() {
	                this.value = this.value.replace(/\s/g, "");
	              }
	            });

	            $(".fieldGroupCopy_contact_phone").find('.hidden_contact_phone').attr("value", "");
	            $(".fieldGroupCopy_contact_phone").find('.contact_phone_primary').attr("value", "");
	        }
	    }
	}
	else
	{
	    $(".fieldGroup_contact_phone").find('.main_contact_phone').intlTelInput("setNumber", "");
	}

	if(client_contact_info_email != null)
	{
	    for (var h = 0; h < client_contact_info_email.length; h++) 
	    {
	        var clientContactInfoEmailArray = client_contact_info_email[h].split(',');

	        if(clientContactInfoEmailArray[2] == 1)
	        {
	            $(".fieldGroup_contact_email").find('.main_contact_email').attr("value", clientContactInfoEmailArray[1]);
	            $(".fieldGroup_contact_email").find('.main_contact_email_primary').attr("value", clientContactInfoEmailArray[1]);

	            $(".fieldGroup_contact_email").find(".button_increment_contact_email").css({"visibility": "visible"});
	        }
	        else
	        {
	            $(".fieldGroupCopy_contact_email").find('.second_contact_email').attr("value", clientContactInfoEmailArray[1]);

	            $(".fieldGroupCopy_contact_email").find('.contact_email_primary').attr("value", clientContactInfoEmailArray[1]);

	            var fieldHTML = '<div class="input-group fieldGroup_contact_email" style="margin-top:10px; display: block !important;">'+$(".fieldGroupCopy_contact_email").html()+'</div>';

	            //$('body').find('.fieldGroup_contact_email:first').after(fieldHTML);
	            $( fieldHTML).prependTo(".contact_email_toggle");

	            $(".fieldGroupCopy_contact_email").find('.second_contact_email').attr("value", "");
	            $(".fieldGroupCopy_contact_email").find('.contact_email_primary').attr("value", "");
	        }
	    }
	}


	var registered_address_info = <?php echo json_encode($registered_address_info);?>;
	// console.log(registered_address_info);


	$.each(registered_address_info, function(key, val) {
		var option = $('<option />');
		option.attr('data-postal_code', val['postal_code']).attr('data-street_name', val['street_name']).attr('data-building_name', val['building_name']).attr('data-unit_no1', val['unit_no1']).attr('data-unit_no2', val['unit_no2']).attr('value', val['id']).text(val['service_name']);
		if(client != null)
		{
		    if(client['our_service_regis_address_id'] != undefined && val['id'] == client['our_service_regis_address_id'])
		    {
		        option.attr('selected', 'selected');
		    }
		}
		// option.attr('selected', 'selected');
		// console.log(option);
		$("#service_reg_off").append(option);
	});

	$(document).on('change','#service_reg_off',function(e){
	// var num = $(this).parent().parent().parent().attr("num");
	//console.log(num);
	var postal_codeValue = $(this).find(':selected').data('postal_code');
	var street_nameValue = $(this).find(':selected').data('street_name');
	var building_nameValue = $(this).find(':selected').data('building_name');
	var unit_no1Value = $(this).find(':selected').data('unit_no1');
	var unit_no2Value = $(this).find(':selected').data('unit_no2');

	document.getElementById("postal_code").value = postal_codeValue;
		document.getElementById("street_name").value = street_nameValue;
		document.getElementById("building_name").value = building_nameValue;
		document.getElementById("unit_no1").value = unit_no1Value;
		document.getElementById("unit_no2").value = unit_no2Value;

	});

	function fillRegisteredAddressInput(cbox) {
		//console.log(cbox);
		if (cbox.checked) {
			//console.log(cbox);
			$(".service_reg_off_area").show();
			// document.getElementById("postal_code").value = registered_address_info[0].postal_code;
			// document.getElementById("street_name").value = registered_address_info[0].street_name;
			// document.getElementById("building_name").value = registered_address_info[0].building_name;
			// document.getElementById("unit_no1").value = registered_address_info[0].unit_no1;
			// document.getElementById("unit_no2").value = registered_address_info[0].unit_no2;

			document.getElementById("postal_code").readOnly = true;
			document.getElementById("street_name").readOnly = true;
			document.getElementById("building_name").readOnly = true;
			document.getElementById("unit_no1").readOnly = true;
			document.getElementById("unit_no2").readOnly = true;

			$( '#form_postal_code' ).html("");
			$( '#form_street_name' ).html("");
		}
		else
		{
			$(".service_reg_off_area").hide();
			$("#service_reg_off").val(0);

			document.getElementById("postal_code").value = "";
			document.getElementById("street_name").value = "";
			document.getElementById("building_name").value = "";
			document.getElementById("unit_no1").value = "";
			document.getElementById("unit_no2").value = "";

			document.getElementById("postal_code").readOnly = false;
			document.getElementById("street_name").readOnly = false;
			document.getElementById("building_name").readOnly = false;
			document.getElementById("unit_no1").readOnly = false;
			document.getElementById("unit_no2").readOnly = false;
		}
	}

	function change_bank_auth_active(element)
	{
		var div 		= $(element).parent();
    	var bank_auth_id 	= div.parent().find('.auth_id').val();
    	// console.log($(div));
    	var msg = '';

		if(element.checked){
			var active = 1;
			msg = "Do you want to activate this selected info?"
		}
		else
		{
			var active = 0;
			msg = "Do you want to deactivate this selected info?"
		}

		bootbox.confirm({
	        message: msg,
	        closeButton: false,
	        buttons: {
	            confirm: {
	                label: 'Yes',
	                className: 'btn_blue'
	            },
	            cancel: {
	                label: 'No',
	                className: 'btn_cancel'
	            }
	        },
	        callback: function (result) {
	        	//console.log(result);
	        	if(result == true)
	        	{
	        		$.post(update_bank_auth_url, { 'bank_auth_id': bank_auth_id , 'active': active}, function(data, status){
			    	 	if(data){
			    	 		window.location = edit_client_url+"/"+client['id']+"/bank_detail"
			    	 	}
			    	});
	        	}
	        	else
	        	{
	        		if (active == 1)
	        		{
	        			$(element).prop("checked", false);
	        		}
	        		else
	        		{
	        			$(element).prop("checked", true);
	        			// console.log($(element));
	        		}
	        		// console.log(element);
	       		
	       		}
	        }
	    })

	}

$(document).on('click',".check_stat",function() {
	var tab_aktif = $('.check_stat.active').data("information");

	if(tab_aktif == "controller" || tab_aktif == "capital" || tab_aktif == "officer" || tab_aktif == "filing"|| tab_aktif == "register" || tab_aktif == "charges" || tab_aktif == "billing" || tab_aktif == "setup" || tab_aktif == "billing")
	{
		$.ajax({
			type: "POST",
			url: check_incorporation_date_url,
			data: {"company_code": company_code}, // <--- THIS IS THE CHANGE
			dataType: "json",
			async: false,
			success: function(response)
			{
				//console.log("incorporation_date==="+response[0]["incorporation_date"]);
				var array = response[0]["incorporation_date"].split("/");
				var tmp = array[0];
				array[0] = array[1];
				array[1] = tmp;
				//unset($tmp);
				var date_2 = array[0]+"/"+array[1]+"/"+array[2];
				//console.log(new Date($date_2));

				latest_incorporation_date = new Date(date_2);

				//officer_change_date(latest_incorporation_date);
				// controller_change_date(latest_incorporation_date);
				// charge_change_date(latest_incorporation_date);
				// register_change_date(latest_incorporation_date);
				// setup_change_date(latest_incorporation_date);
				/*date.setDate(date.getDate()-1)
	*/
				//console.log(new Date());
				if(tab_aktif == "filing")
				{
					// change_incorporation_date(latest_incorporation_date);
					// if(!filing_data)
					// {
					// 	check_due_date_175();
					// }

					if(company_type == "3" || company_type == "6")
					{
						dispense_agm_button("public");
					}
					else
					{
						dispense_agm_button("private");
					}
					//dispense_agm_button
				}
			}
		});


	}

	if(tab_aktif == "billing")
	{
		search_billing_function();
	}


	 if(tab_aktif == "filing" || tab_aktif == "bank" || tab_aktif == "setup")
	{
		// console.log($("#client_footer_button"));
		$(".client_footer_button").hide();
	}
	else
	{
		$(".client_footer_button").show();
	}
});

$(document).on('click',"#save",function(e){
	var tab_aktif = $('.check_stat.active').data("information");
	if(tab_aktif == "companyInfo"){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('client/check_client_data'); ?>",
			data: $("#w2-companyInfo form").serialize(), // <--- THIS IS THE CHANGE
			dataType: "json",
			async: false,
			success: function(response)
			{
				// console.log("wtf?");
				if(response)
				{
					/*if (confirm('Do you want to submit?')) 
					{
			           $("#w2-"+$tab_aktif+" form").submit();
					} 
					else 
					{
					   return false;
					}*/
					bootbox.confirm("Do you want to submit? Did you want to overwrite previous document?", function (result) {
			            if (result) {
			            	$("#w2-"+tab_aktif+" form").submit();
			            }
			            else
			            {
			            	return false;
			            }
			        });
				}
				else
				{
					// console.log("??????");
					$("#w2-"+tab_aktif+" form").submit();
				}
			}
		});
	}
	else
	{
		// console.log(tab_aktif + "??????");	
		$("#w2-"+tab_aktif+" form").submit();
	}
	
});

// 	$('form#client').submit(function (e) {
// 	    e.preventDefault();
	    
// 	    var tab_aktif = $(this).data("information");
// 	    var link;
// 	    var form = $("#w2-"+tab_aktif+" form");
// 	    var form_data; 
	    
// console.log(tab_aktif);
// 	    if(tab_aktif == "companyInfo")
// 		{
// 			link = "client/save";
// 			form_data = form.serialize();
// 		}
		
// 	});

$(document).on('submit', function (e) {
	var tab_aktif = $('.check_stat.active').data("information");
    e.preventDefault();
    // console.log(setup_section);
    $('#loadingmessage').show();
    var link;
    var form = $("#w2-"+tab_aktif+" form");
    var form_data; 

    if(tab_aktif == "companyInfo")
	{
		link = "<?php echo site_url('client/save'); ?>";
		form_data = form.serialize();
	}
	else if(tab_aktif == "billing") 
	{
		// var $form = $(e.target);

  //       // and the FormValidation instance
  //       var fv = $form.data('formValidation');
  //       // Get the first invalid field
  //       var $invalidFields = fv.getInvalidFields().eq(0);
  //       // Get the tab that contains the first invalid field
  //       var $tabPane     = $invalidFields.parents('.tab-pane');
  //       var valid_setup = fv.isValidContainer($tabPane);

        var valid_setup = $("#billing_form").data('bootstrapValidator').validate();
        console.log(valid_setup.isValid());

        if(valid_setup.isValid())
        {
			link = "<?php echo site_url('client/add_client_billing_info'); ?>";

			form_data = form.serialize() + '&array_client_billing_info_id=' + JSON.stringify(array_client_billing_info_id);
        }
        else if(!valid_setup.isValid())
        {
        	toastr.error("Please complete all required field", "Error");
        	$('#loadingmessage').hide();
        }
        // var valid_setup = true;

		// link = "<?php echo site_url('client/add_client_billing_info'); ?>";

		// form_data = form.serialize() + '&array_client_billing_info_id=' + JSON.stringify(array_client_billing_info_id);

	}
	else if(tab_aktif == "setup") 
	{
		if(setup_section == "contact_information_form")
	    {
	    	link = "<?php echo site_url('client/submit_contact_information'); ?>";
	        form_data = $("#contact_information_form").serialize();
	    }
	    else if(setup_section == "reminder_form")
	    {
	    	link = "<?php echo site_url('client/submit_reminder'); ?>";
	        form_data = $("#reminder_form").serialize();
	    }
	    else if(setup_section == "corporate_representative_form")
	    {
	    	link = "masterclient/submit_corporate_representative";
	        form_data = $("#corporate_representative_form").serialize();
	    }

	}
	if(tab_aktif == "companyInfo" || setup_section == "contact_information_form" || setup_section == "reminder_form" || setup_section == "corporate_representative_form" || valid_setup)
	{		
		// if(client)
		// {
		// 	if (client["status"] == "1")
		// 	{
		// 		$(".acquried_by").attr('disabled', false);
		// 	}
		// }

		// if(tab_aktif == "billing")
		// {
		// 	var form_data = form.serialize() + '&array_client_billing_info_id=' + JSON.stringify(array_client_billing_info_id);
		// }
		// else
		// {
		// 	var form_data = form.serialize();
		// }
		
		
        $.ajax({ //Upload common input
                url: link,
                type: "POST",
                data: form_data,
                dataType: 'json',
                success: function (response,data) {
                	$('#loadingmessage').hide();
                	//console.log(response);
                    if (response.Status === 1) {
                    	toastr.success(response.message, response.title);
					    //$("#w2-setup").replaceWith($('#w2-setup', $(response)));
					    //$('#w2-setup').html(data);
					    if(tab_aktif == "companyInfo")
                    	{
						    var errors = '';
                    		$( '#form_client_code' ).html( errors );
                    		$( '#form_registration_no' ).html( errors );
                    		$( '#form_company_name' ).html( errors );
                    		$( '#form_former_name' ).html( errors );
                    		$( '#form_incorporation_date' ).html( errors );
                    		$( '#form_company_type' ).html( errors );
                    		$( '#form_activity1' ).html( errors );
                    		$( '#form_activity2' ).html( errors );
                    		$( '#form_postal_code' ).html( errors );

                    		$('.nav li').removeClass('disabled');

                    		/*$('.disabled').click(function (e) {
						        e.preventDefault();
						        console.log($('.disabled'));
						        return true;
							});*/

							// billing(response.client_billing["client_billing_data"]);
                    		/*if(response.client_billing)
                    		{
                    			
                    			if(response.client_billing['check_available_in_client_billing_info'] == 0)
                    			{
                    				template = response.client_billing["client_billing_data"];
                    				billing(response.client_billing["client_billing_data"]);
                    			}
                    			else
                    			{
                    				template = response.client_billing["template"];
                    			}
                    			
                    		}*/
                    		console.log(response.change_company_name);

                    		if($("select[name='status']").val() != 1)
                    		{
                    			location.reload();
                    			// console.log("name status");
                    		}

                    		if(response.change_company_name)
                    		{
                    			location.reload();
                    			// console.log("change company name");
                    		}

                    		if(!admin)
                    		{
                    			$(".acquried_by").attr('disabled', 'disabled');
                    		}

                    		company_type =  $("#company_type").val();
                    		
                    	}
                    
                    }
                    else if (response.Status === 2) {
                    	toastr.error(response.message, response.title);
                    }
                    else if (response.Status === 3) {
                    	toastr.success(response.message, response.title);
                    	location.reload();
                    }
                    else
                    {
                    	console.log("fail");
                    	toastr.error(response.message, response.title);
                    	//console.log(response.error);
                    	if(tab_aktif == "companyInfo")
                    	{
	                    	if (response.error["client_code"] != "")
	                    	{
	                    		var errorsClientCode = '<span class="help-block">*' + response.error["client_code"] + '</span>';
	                    		$( '#form_client_code' ).html( errorsClientCode );

	                    	}
	                    	else
	                    	{
	                    		var errorsClientCode = '';
	                    		$( '#form_client_code' ).html( errorsClientCode );
	                    	}
	                    	if (response.error["status"] != "")
	                    	{
	                    		var errorsStatus = '<span class="help-block">*' + response.error["status"] + '</span>';
	                    		$( '#form_status' ).html( errorsStatus );

	                    	}
	                    	else
	                    	{
	                    		var errorsStatus = '';
	                    		$( '#form_status' ).html( errorsStatus );
	                    	}
	                    	if (response.error["acquried_by"] != "")
	                    	{
	                    		var errorsAcquriedBy = '<span class="help-block">*' + response.error["acquried_by"] + '</span>';
	                    		$( '#form_acquried_by' ).html( errorsAcquriedBy );

	                    	}
	                    	else
	                    	{
	                    		var errorsAcquriedBy = '';
	                    		$( '#form_acquried_by' ).html( errorsAcquriedBy );
	                    	}
	                    	if (response.error["registration_no"] != "")
	                    	{
	                    		var errorsRegistrationNo = '<span class="help-block">*' + response.error["registration_no"] + '</span>';
	                    		$( '#form_registration_no' ).html( errorsRegistrationNo );

	                    	}
	                    	else
	                    	{
	                    		var errorsRegistrationNo = '';
	                    		$( '#form_registration_no' ).html( errorsRegistrationNo );
	                    	}
	                    	if (response.error["company_name"] != "")
	                    	{
	                    		var errorsCompanyName = '<span class="help-block">*' + response.error["company_name"] + '</span>';
	                    		$( '#form_company_name' ).html( errorsCompanyName );

	                    	}
	                    	else
	                    	{
	                    		var errorsCompanyName = '';
	                    		$( '#form_company_name' ).html( errorsCompanyName );
	                    	}
	                    	/*if (response.error["former_name"] != "")
	                    	{
	                    		var errorsFormerName = '<span class="help-block">*' + response.error["former_name"] + '</span>';
	                    		$( '#form_former_name' ).html( errorsFormerName );

	                    	}
	                    	else
	                    	{
	                    		var errorsFormerName = '';
	                    		$( '#form_former_name' ).html( errorsFormerName );
	                    	}*/
	                    	if (response.error["incorporation_date"] != "")
	                    	{
	                    		var errorsIncorporationDate = '<span class="help-block">*' + response.error["incorporation_date"] + '</span>';
	                    		$( '#form_incorporation_date' ).html( errorsIncorporationDate );

	                    	}
	                    	else
	                    	{
	                    		var errorsIncorporationDate = '';
	                    		$( '#form_incorporation_date' ).html( errorsIncorporationDate );
	                    	}
	                    	if (response.error["company_type"] != "")
	                    	{
	                    		var errorsCompanyType = '<span class="help-block">' + response.error["company_type"] + '</span>';
	                    		$( '#form_company_type' ).html( errorsCompanyType );

	                    	}
	                    	else
	                    	{
	                    		var errorsCompanyType = '';
	                    		$( '#form_company_type' ).html( errorsCompanyType );
	                    	}
	                    	if (response.error["activity1"] != "")
	                    	{
	                    		var errorsActivity1 = '<span class="help-block">*' + response.error["activity1"] + '</span>';
	                    		$( '#form_activity1' ).html( errorsActivity1 );

	                    	}
	                    	else
	                    	{
	                    		var errorsActivity1 = '';
	                    		$( '#form_activity1' ).html( errorsActivity1 );
	                    	}
	                    	/*if (response.error["activity2"] != "")
	                    	{
	                    		var errorsActivity2 = '<span class="help-block">*' + response.error["activity2"] + '</span>';
	                    		$( '#form_activity2' ).html( errorsActivity2 );

	                    	}
	                    	else
	                    	{
	                    		var errorsActivity2 = '';
	                    		$( '#form_activity2' ).html( errorsActivity2 );
	                    	}*/
	                    	if (response.error["postal_code"] != "")
	                    	{
	                    		var errorsPostalCode = '<span class="help-block">*' + response.error["postal_code"] + '</span>';
	                    		$( '#form_postal_code' ).html( errorsPostalCode );

	                    	}
	                    	else
	                    	{
	                    		var errorsPostalCode = '';
	                    		$( '#form_postal_code' ).html( errorsPostalCode );
	                    	}

	                    	if (response.error["street_name"] != "")
	                    	{
	                    		var errorsStreetName = '<span class="help-block">*' + response.error["street_name"] + '</span>';
	                    		$( '#form_street_name' ).html( errorsStreetName );

	                    	}
	                    	else
	                    	{
	                    		var errorsStreetName = '';
	                    		$( '#form_street_name' ).html( errorsStreetName );
	                    	}
	                    
	                    }

	                }
	            }
	        });
	}
	
});

$('#postal_code').keyup(function(){
		if($(this).val().length == 6)
		{
  		var zip = $(this).val();
		//var address = "068914";
		$.ajax({
		  url:    'https://gothere.sg/maps/geo',
		  dataType: 'jsonp',
		  data:   {
		    'output'  : 'json',
		    'q'     : zip,
		    'client'  : '',
		    'sensor'  : false
		  },
		  type: 'GET',
		  success: function(data) {
		    //console.log(data);
		    //var field = $("textarea");
		    var myString = "";
		    
		    var status = data.Status;
		    /*myString += "Status.code: " + status.code + "\n";
		    myString += "Status.request: " + status.request + "\n";
		    myString += "Status.name: " + status.name + "\n";*/
		    
		    if (status.code == 200) {         
		      for (var i = 0; i < data.Placemark.length; i++) {
		        var placemark = data.Placemark[i];
		        var status = data.Status[i];
		        //console.log(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);
		        $("#street_name").val(placemark.AddressDetails.Country.Thoroughfare.ThoroughfareName);

		        if(placemark.AddressDetails.Country.AddressLine == "undefined")
		        {
		        	$("#building_name").val("");
		        }
		        else
		        {
		        	$("#building_name").val(placemark.AddressDetails.Country.AddressLine);
		        }

		      }
		      $( '#form_postal_code' ).html('');
		      $( '#form_street_name' ).html('');
		      //field.val(myString);
		    } else if (status.code == 603) {
		    	$( '#form_postal_code' ).html('<span class="help-block">*No Record Found</span>');
		      //field.val("No Record Found");
		    }

		  },
		  statusCode: {
		    404: function() {
		      alert('Page not found');
		    }
		  },
	    });
	}
});


</script>

