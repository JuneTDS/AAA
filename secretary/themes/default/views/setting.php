<section class="panel" style="margin-top: 30px;">
	<div class="panel-body">
		<div class="col-md-12">
			<div class="tabs">				
				<ul class="nav nav-tabs nav-justify">
					<li class="active check_stat">
						<a href="#w2-company" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">1</span>
							Company Setting
						</a>
					</li>
					<li class="check_stat">
						<a href="#w2-service" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">2</span>
							Field
						</a>
					</li>
					<li class="check_stat">
						<a href="#w2-service" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">3</span>
							Service Setting
						</a>
					</li>
					<li class="check_stat">
						<a href="#w2-logbook" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">4</span>
							Logbook
						</a>
					</li>
				</ul>
				
					<div class="tab-content" style="min-height:620px;">
						<div id="w2-general" class="tab-pane">
							<div class="bordered">
								<div class="col-md-4">
									<label>Client Code</label>&nbsp;&nbsp;&nbsp;<label><input type="checkbox">&nbsp;Autorun</label>&nbsp;&nbsp;&nbsp;<label><input type="checkbox">&nbsp;Manual</label>
									<input type="text" class="form-control" value="Comp">
								</div>
								<div class="col-md-4">
									<label>Invoice No</label>
									<input type="text" class="form-control">
								</div>
								<div class="col-md-4">
									<label>Duration to Save / List of Data</label>
									<input type="text" class="form-control" value=5>
								</div>
							</div>
						</div>
						<div id="w2-company" class="tab-pane active">
							<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
								echo form_open_multipart("setting/save", $attrib);
								
							?>
							<div class="col-md-8 col-md-offset-2">
								<div class="col-md-12">
									<label>Company Name</label>
									<input type="text" name="company_name" value="<?=$maincompany->company_name?>" class="form-control">
								</div>
								<div class="col-md-12">
									<label>Company UEN</label>
									<input type="text" name="company_uen" value="<?=$maincompany->company_uen?>"  class="form-control">
								</div>
								<div class="col-md-12">
									<label>Phone</label>
									<input type="text" name="company_phone" value="<?=$maincompany->company_phone?>"  class="form-control">
								</div>
								<div class="col-md-12">
									<label>Email</label>
									<input type="text" name="company_email" value="<?=$maincompany->company_email?>"   class="form-control">
								</div>
								<div class="col-md-12">
									<label>Fax</label>
									<input type="text" name="company_fax"  value="<?=$maincompany->company_fax?>"  class="form-control">
								</div>
								<div class="col-md-12">
									<label>Post Code</label>
									<input type="text" name="company_postcode"  value="<?=$maincompany->company_postcode?>"  class="form-control">
								</div>
								<div class="col-md-12">
									<label>Street Name</label>
									<input type="text"  name="company_street" value="<?=$maincompany->company_street?>"  class="form-control">
								</div>
								<div class="col-md-12">
									<label>Building Name</label>
									<input type="text"  name="company_building" value="<?=$maincompany->company_building?>"  class="form-control">
								</div>
								<div class="col-md-12">
									<label>Unit</label>
									<input type="text" name="company_unit"  value="<?=$maincompany->company_unit?>"  class="form-control">
								</div>
							</div>
							<div class="col-md-12 text-right">
								<!--button class="btn btn-primary modal-confirm">Confirm</button-->
								<input type="submit" class="btn btn-primary" value="Save"/>
							</div>
							<?= form_close();?>
						</div>
						<div id="w2-service" class="tab-pane">
							<header class=" text-right">
									<!-- a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a-->
									<!--a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a-->
									
								<span class=" amber pointer" data-target="#modal_service_setting" data-toggle="modal"style="height:45px;font-weight:bold;" data-original-title="Add Service" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i>Add Service</span>
									
							</header>
							<p>Word document Uploaded must contain Mail Merge Field or you can <a href="templates.docx">Download Sample Document here</a> and you can <a href="generatedoc.php">Test Sample Here</a></p>
							<div class="bordered">
								<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default">
									<thead>
										<tr>
											<th>Service</th>
											<th>Price</th>
											<th>Link to Field</th>
											<th>Link to Document</th>
											<th></th>
										</tr>
									</thead>
										<tbody id="service_body">
											<tr>
												<td><a href="#" data-target="#modal_edit_service_setting" data-toggle="modal"style="height:45px;font-weight:bold;" data-original-title="Edit Service">Service 1</a></td>
												<td class="text-right">$ 1.000,00</td>
												<td class="text-right">
													Company Info -> Field 1 <br/>
													Company Info -> Field 2 <br/>
													Officer -> Name<br/>
													Officer -> Birthday <br/>
												</td>
												<td class="text-right">
													Document 1 <br/>
													Document 2 <br/>
													Document 3 <br/>
												</td>
												<td>
													<a href="#" class="fa fa-trash"></a>
												</td>
											</tr>
											<tr>
												<td><a href="#" data-target="#modal_edit_service_setting" data-toggle="modal"style="height:45px;font-weight:bold;" data-original-title="Edit Service">Service 2</a></td>
												<td class="text-right">$ 1.500,00</td>
												<td class="text-right">
													Company Info -> Field 3 <br/>
													Company Info -> Field 4 <br/>
													Officer -> Nationality<br/>
													Officer -> Certificate <br/>
												</td>
												<td class="text-right">
													Document 4 <br/>
													Document 5 <br/>
												</td>
												<td>
													</a>
													<a href="#" class="fa fa-trash"></a>
												</td>
											</tr>
										</tbody>
									</table>
							</div>
						</div>
						<div id="w2-service" class="tab-pane">
							<header class=" text-right">
									<!-- a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a-->
									<!--a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a-->
									
								<span class=" amber pointer" data-target="#modal_service_setting" data-toggle="modal"style="height:45px;font-weight:bold;" data-original-title="Add Service" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i>Add Service</span>
									
							</header>
							<p>Word document Uploaded must contain Mail Merge Field or you can <a href="templates.docx">Download Sample Document here</a> and you can <a href="generatedoc.php">Test Sample Here</a></p>
							<div class="bordered">
								<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default">
									<thead>
										<tr>
											<th>Service</th>
											<th>Price</th>
											<th>Link to Field</th>
											<th>Link to Document</th>
											<th></th>
										</tr>
									</thead>
										<tbody id="service_body">
											<tr>
												<td><a href="#" data-target="#modal_edit_service_setting" data-toggle="modal"style="height:45px;font-weight:bold;" data-original-title="Edit Service">Service 1</a></td>
												<td class="text-right">$ 1.000,00</td>
												<td class="text-right">
													Company Info -> Field 1 <br/>
													Company Info -> Field 2 <br/>
													Officer -> Name<br/>
													Officer -> Birthday <br/>
												</td>
												<td class="text-right">
													Document 1 <br/>
													Document 2 <br/>
													Document 3 <br/>
												</td>
												<td>
													<a href="#" class="fa fa-trash"></a>
												</td>
											</tr>
											<tr>
												<td><a href="#" data-target="#modal_edit_service_setting" data-toggle="modal"style="height:45px;font-weight:bold;" data-original-title="Edit Service">Service 2</a></td>
												<td class="text-right">$ 1.500,00</td>
												<td class="text-right">
													Company Info -> Field 3 <br/>
													Company Info -> Field 4 <br/>
													Officer -> Nationality<br/>
													Officer -> Certificate <br/>
												</td>
												<td class="text-right">
													Document 4 <br/>
													Document 5 <br/>
												</td>
												<td>
													</a>
													<a href="#" class="fa fa-trash"></a>
												</td>
											</tr>
										</tbody>
									</table>
							</div>
						</div>
						<div id="w2-postcode" class="tab-pane">
							<div class="bordered">
								<span class="btn btn-primary" data-toggle="modal" data-target="#modal_postcode">Add Post Code</span>
								<table class="table table-bordered table-striped table-condensed mb-none">
										<tr>
											<th>Post Code</th>
											<th>Area</th>
										</tr>
										<tbody id="post_body">
											<tr>
												<td>14400</td>
												<td class="text-right">Jakarta Barat</td>
											</tr>
											<tr>
												<td>14500</td>
												<td class="text-right">Jakarta Utara</td>
											</tr>
										</tbody>
									</table>
							</div>
						</div>
						<div id="w2-logbook" class="tab-pane">
							<div class="bordered">
								<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default">
									<thead>
										<tr>
											<th>Date</th>
											<th>User</th>
											<th>Action</th>
											<th>Changes</th>
										</tr>
									</thead>
									<tbody id="post_body">
										<tr>
											<td>04/10/2017 21:00:50</td>
											<td>User 1</td>
											<td>Edit Company</td>
											<td>Value 1 -> Value 2</td>
										</tr>
										<tr>
											<td>04/10/2017 20:00:50</td>
											<td>User 2</td>
											<td>ADD Company</td>
											<td>Company : bestCOMP</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div id="w2-backup" class="tab-pane">
							<div class="bordered">
								<div class="col-md-6">
									<button class="btn btn-primary modal-dismiss">Backup</button>
									<table class="table table-bordered table-striped table-condensed mb-none">
										<tr>
											<th>Date</th>
											<th>File</th>
										</tr>
										<tbody id="post_body">
											<tr>
												<td>04/10/2017</td>
												<td class="text-right"><a href="#">backup_04_10_2017.zip</a></td>
											</tr>
											<tr>
												<td>03/01/2017</td>
												<td class="text-right"><a href="#">backup_03_01_2017.zip</a></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-md-6">
									<input type="file" class="form-control">
									<button class="btn btn-primary">Restore</button>
								</div>
								
							</div>
						</div>
						
					</div>
				</form>
				<footer class="panel-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button class="btn btn-primary modal-dismiss">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</footer>
			<div>							
													
		</div>
	</div>
	
	<div id="modal_postcode" class="modal fade" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
			  <div class="modal-header">
				<header class="panel-heading">
					<h2 class="panel-title">Post Code Setting</h2>
				</header>
			  </div>
				<div class="modal-body panel-body" >
					<div class="col-md-12">
						<label>Post Code</label>
						<input type="text" class="form-control">
					</div>
					<div class="col-md-12">
						<label>Area</label>
						<textarea class="form-control"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button class="btn btn-primary modal-dismiss">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="modal_service_setting" class="modal fade" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
			  <div class="modal-header">
				<header class="panel-heading">
					<h2 class="panel-title">Service Setting</h2>
				</header>
			  </div>
				<div class="modal-body panel-body" >
					<div class="col-md-12">
						<label>Service</label>
						<select data-plugin-selectTwo id="select_edit_p" class="form-control populate">
							<optgroup label="Type">
								<option value="aud">Auditor</option>
								<option value="comp">Company</option>
								<option value="indv">Individual</option>
							</optgroup>
						</select>
					</div>
					<div class="col-md-12">
						<label>Service Name</label>
						<input type="text" class="form-control ">
					</div>
					<div class="col-md-6" id="div_field">
						<h1>Field <span id="add_field" class="amber"><i class="fa fa-plus"></i></span></h1>
						<select data-plugin-selectTwo class="form-control populate">
							<optgroup label="Type">
								<option value="AK">Field 1</option>
								<option value="HI">Field 2</option>
								<option value="FIN">Field 3</option>
								<option value="Pas">Field 4</option>
							</optgroup>
						</select>
						<select data-plugin-selectTwo class="form-control populate">
							<optgroup label="Type">
								<option value="AK">Field 1</option>
								<option value="HI">Field 2</option>
								<option value="FIN">Field 3</option>
								<option value="Pas">Field 4</option>
							</optgroup>
						</select>
					</div>
					<div class="col-md-6" id="div_document">
						<p>Word document Uploaded must contain Mail Merge Field or you can <a href="#">Download Sample Document here</a></p>
						<p>
							Field You can used :<br/>
							«Unique identifier»<br/>
							«Name»<br/>
							«Client_Company»<br/>
						</p>
						<h3>Default for Dot Exe,LTE PTE :</h3>
						<p>
							Field You can used :<br/>
							«comp_name»<br/>
							«comp_street»<br/>
							«comp_block»<br/>
							«comp_city»<br/>
							«comp_country»<br/>
							«comp_phone»<br/>
							«comp_fax»<br/>
							«comp_mail»<br/>
						
						</p>
					</div>
					<h3>Upload Document</h3>
					<div class="col-md-12">
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
					</div>
					
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button class="btn btn-primary modal-dismiss">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="modal_edit_service_setting" class="modal fade" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
			  <div class="modal-header">
				<header class="panel-heading">
					<h2 class="panel-title">Service Setting</h2>
				</header>
			  </div>
				<div class="modal-body panel-body" >
					<div class="col-md-12">
						<label>Service</label>
						<select data-plugin-selectTwo id="select_edit_p" class="form-control populate">
							<optgroup label="Type">
								<option value="aud">Auditor</option>
								<option value="comp">Company</option>
								<option value="indv">Individual</option>
							</optgroup>
						</select>
					</div>
					<div class="col-md-12">
						<label>Service Name</label>
						<input type="text" class="form-control ">
					</div>
					<div class="col-md-6" id="div_field">
						<h1>Field <span id="add_field" class="amber"><i class="fa fa-plus"></i></span></h1>
						<select data-plugin-selectTwo class="form-control populate">
							<optgroup label="Type">
								<option value="AK">Field 1</option>
								<option value="HI">Field 2</option>
								<option value="FIN">Field 3</option>
								<option value="Pas">Field 4</option>
							</optgroup>
						</select>
						<select data-plugin-selectTwo class="form-control populate">
							<optgroup label="Type">
								<option value="AK">Field 1</option>
								<option value="HI">Field 2</option>
								<option value="FIN">Field 3</option>
								<option value="Pas">Field 4</option>
							</optgroup>
						</select>
					</div>
					<div class="col-md-6" id="div_document">
						<p>Word document Uploaded must contain Mail Merge Field or you can <a href="../templates.docx">Download Sample Document here</a></p>
						<p>
							Field You can used :<br/>
							«Unique identifier»<br/>
							«Name»<br/>
							«Client_Company»<br/>
						</p>
						<h3>Default for Dot Exe,LTE PTE :</h3>
						<p>
							Field You can used :<br/>
							«comp_name»<br/>
							«comp_street»<br/>
							«comp_block»<br/>
							«comp_city»<br/>
							«comp_country»<br/>
							«comp_phone»<br/>
							«comp_fax»<br/>
							«comp_mail»<br/>
						
						</p>
					</div>
					<table class="table">
						<tr>
							<td><a href="#">Document 1</a></td>
							<td><input type="file" class="form-control"></td>
							<td><a href="#" class="btn btn-primary">Replace</a></td>
						</tr>
						<tr>
							<td><a href="#">Document 2</a></td>
							<td><input type="file" class="form-control"></td>
							<td><a href="#" class="btn btn-primary">Replace</a></td>
						</tr>
						<tr>
							<td><a href="#">Document 3</a></td>
							<td><input type="file" class="form-control"></td>
							<td><a href="#" class="btn btn-primary">Replace</a></td>
						</tr>
					</table>
					<h3>Upload Document</h3>
					<div class="col-md-12">
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
						<input type="file" class="form-control"></br>
					</div>
					
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<!--button class="btn btn-primary modal-confirm">Confirm</button-->
							<button class="btn btn-primary modal-dismiss">Save</button>
							<button class="btn btn-default modal-dismiss">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		
			<!--h1>Document<span id="add_documet" class="amber"><i class="fa fa-plus"></i></span></h1>
						
						<select data-plugin-selectTwo class="form-control populate">
							<optgroup label="Type">
								<option value="AK">Document1</option>
								<option value="HI">Document2</option>
								<option value="FIN">Document3</option>
								<option value="Pas">Document4</option>
							</optgroup>
						</select>
						<select data-plugin-selectTwo class="form-control populate">
							<optgroup label="Type">
								<option value="AK">Document1</option>
								<option value="HI">Document2</option>
								<option value="FIN">Document3</option>
								<option value="Pas">Document4</option>
							</optgroup>
						</select-->
	
<!-- end: page -->
</section>
<script>
	$("#add_field").on('click',function(){
		$html = '<select data-plugin-selectTwo class="form-control populate">' +
							'<optgroup label="Type">' +
								'<option value="AK">Field 1</option>' +
								'<option value="HI">Field 2</option>' +
								'<option value="FIN">Field 3</option>' +
								'<option value="Pas">Field 4</option>' +
							'</optgroup>' +
						'</select>';
		$("#div_field").append($html);
	});
	$("#add_documet").on('click',function(){
		$html = '<select data-plugin-selectTwo class="form-control populate">' +
							'<optgroup label="Type">' +
								'<option value="AK">Document1</option>' +
								'<option value="HI">Document2</option>' +
								'<option value="FIN">Document3</option>' +
								'<option value="Pas">Document4</option>' +
							'</optgroup>' +
						'</select>';
		$("#div_document").append($html);
	});
</script>
<style>
	.select2-container {
		display:block;
	}
	.dataTables_filter {
		display:none;
	}
	.dataTables_length label{
		width:100%;
	}
</style>