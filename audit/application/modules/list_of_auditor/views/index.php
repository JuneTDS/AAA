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

<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<script src="<?= base_url() ?>node_modules/moment/moment.js"></script>
<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootbox/bootbox.min.css" /> -->

<link rel="stylesheet" href="<?= base_url() ?>node_modules/sweetalert2/dist/sweetalert2.min.css">
<script src="<?= base_url() ?>node_modules/sweetalert2/dist/sweetalert2.min.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>plugin/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>plugin/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

<style type="text/css">
	#clearance_attachment_table th, #clearance_attachment_table td
	{
		text-align: center !important;
		/*font-weight: normal !important;*/
	}

	#auditor_list_submit input[type=text]
	{
		text-transform: uppercase;
	}


</style>

<section role="main" class="content_section" style="margin-left:0;">
	<header class="panel-heading">
		<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
			<div class="panel-actions">
				<a class="create_first_clearance_letter amber" href="<?= base_url();?>list_of_auditor/add_first_clearance_letter" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create letter" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add letter</a>
				<a class="create_resignation amber" href="<?= base_url();?>list_of_auditor/add_resignation" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Add Resignation" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Add resignation</a>
			</div>
			<h2></h2>
		<?php } ?>
	</header>
	<section class="panel" style="margin-top: 0px;">
		<div class="panel-body">
			<div class="col-md-12">
				<div class="tabs">				
					<ul class="nav nav-tabs nav-justify">
						<li class="active check_state" data-information="first_clearance_letter">
							<a href="#w2-first_clearance_letter" data-toggle="tab" class="text-center">
								Appointment
							</a>
						</li>
						<li class="check_state" data-information="resignation">
							<a href="#w2-resignation" data-toggle="tab" class="text-center">
								Resignation
							</a>
						</li>
						<li class="check_state" data-information="auditor_list">
							<a href="#w2-auditor_list" data-toggle="tab" class="text-center">
								Auditors
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
						<div id="w2-auditor_list" class="tab-pane">
							<form id="auditor_list_submit">
								<input type="hidden" name="auditor_list_id" class="auditor_list_id" value="">
								<div class="col-md-12">
									<div class="form-group">
						                <div style="width: 100%;">
						                    <div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Auditor: </label>
						                    </div>
						                    <div style="width: 45%;float: left;">
						                        <div style="width:100%">
						                        	<input type="text" id="audit_firm_name" class="form-control" name="audit_firm_name" required>
						                        </div>
						                    </div>
						                </div>
						            </div>
									<div class="form-group">
										<div style="width: 100%;">
						                	<div style="width: 25%;float:left;margin-right: 20px;">
						                        <label>Email:</label>
						                    </div>
						                	<div style="width: 45%;float: left;">
						                        <div style="width:100%">
						                        	<input type="email" id="audit_firm_email" class="form-control" name="audit_firm_email" required>
						                        </div>
						                    </div>
						                </div>
									</div>
									<div class="form-group">
										<div style="width: 100%;">
											<div style="width: 25%;float:left;margin-right: 20px;">
							                        <label>Address: </label>
							                </div>
							                <div style="width: 50%;float: left;">
					                        	<div style="width: 25%;float:left;">
													<label>Postal Code :</label>
												</div>
												<div style="width: 65%;float:left;">
													<div class="" style="width: 100%;" >
														<input type="tel" class="form-control" id="postal_code" name="postal_code" value="">
													</div>
												</div>
						                    </div>
										</div>
									</div>
									<div class="form-group">
										<div style="width: 100%;">
											<div style="width: 25%;float:left;margin-right: 20px;">
							                        <label></label>
							                </div>
							                <div style="width: 50%;float: left;">
					                        	<div style="width: 25%;float:left;">
													<label>Street Name :</label>
												</div>
												<div style="width: 65%;float:left;">
													<div class="" style="width: 100%;" >
														<input type="text" class="form-control" id="street_name" name="street_name" value="">
													</div>
												</div>
						                    </div>
										</div>
									</div>

									 <div class="form-group">
										<div style="width: 100%;">
											<div style="width: 25%;float:left;margin-right: 20px;">
							                        <label></label>
							                </div>
							                <div style="width: 50%;float: left;">
					                        	<div style="width: 25%;float:left;">
													<label>Building Name :</label>
												</div>
												<div style="width: 65%;float:left;">
													<div class="" style="width: 100%;" >
														<input type="text" style="text-transform:uppercase" class="form-control" id="building_name" name="building_name" value="<?=isset($client->building_name)?$client->building_name:"" ?>">
													</div>
													<div id="form_postal_code"></div>
												</div>
						                    </div>
										</div>
									</div>
									<div class="form-group">
										<div style="width: 100%;">
											<div style="width: 25%;float:left;margin-right: 20px;">
							                        <label></label>
							                </div>
							                <div style="width: 50%;float: left;">
					                        	<div style="width: 25%;float:left;">
													<label>Unit No :</label>
												</div>
												<div style="width: 65%;float:left;">
													<div class="" style="width: 100%;" >
														<input style="width: 15%; float: left; margin-right: 10px; text-transform:uppercase;" type="text" class="form-control" id="unit_no1" name="unit_no1" value="<?=isset($client->unit_no1)?$client->unit_no1:"" ?>" maxlength="3">
														<label style="float: left; margin-right: 10px;" >-</label>
														<input style="width: 25%; text-transform:uppercase;" type="text" class="form-control" id="unit_no2" name="unit_no2" value="<?=isset($client->unit_no2)?$client->unit_no2:"" ?>" maxlength="10">
													</div>
													<div id="form_postal_code"></div>
												</div>
						                    </div>
										</div>
									</div>
									
									<div class="form-group">
						                <div style="width: 100%;">
						                    <div style="width: 25%;float:left;margin-right: 20px;">
						                        <label></label>
						                    </div>
						                    <div style="float:right;margin-bottom:5px;">
						                        <div class="input-group">
						                        	<button class="btn btn_blue" type="submit">Save</button>
						                        </div>
						                    </div>
						                </div>
						            </div>

						            <hr>
						            <table class="table table-bordered table-striped mb-none datatable-setting" id="datatable-setting" style="width:100%">
										<thead>
											<tr style="background-color:white;">
												<th class="text-left">Auditors</th>
												<th class="text-left">Email</th>
												<th class="text-left">Address</th>
												<th class="text-left"></th>
											</tr>
										</thead>
										<tbody>
											<?php 
												if($auditor_list)
												{
													foreach($auditor_list as $auditor)
										  			{
										  				echo '<tr>';
										  				echo '<td><a href="javascript:void(0)" class="edit_auditor" data-id="'.$auditor->id.'">'.$auditor->audit_firm_name.'</a></td>';
										  				echo '<td>'.$auditor->audit_firm_email.'</td>';
										  				echo '<td>'.$auditor->full_address.'</td>';
										  				echo '<td style="text-align:center;"><input type="hidden" class="auditor_id" value="'. $auditor->id .'" /><button type="button" class="btn btn_blue" onclick=delete_auditor(this)>Delete</button></td>';
										  				echo '</tr>';
										  			}
												}
												
											?>
										</tbody>
									</table>
								</div>
							</form>
						</div>
						<div id="w2-first_clearance_letter" class="tab-pane active">
							<table class="table table-bordered table-striped mb-none" id="datatable-clearance" style="width:100%">
								<thead style="width:100%">
									<tr>
									<!-- <th><input type="checkbox" name="selectall" class="selectall"/></th> -->
									<!-- <th style="text-align:center;">No</th> -->
									<th style="text-align:center;">Potential client</th>
									<th style="text-align:center;">Auditor</th>
									<th style="text-align:center;">Our firm</th>
									<th style="text-align:center;">Status</th>
									<!-- <th style="text-align:center;">FYE Date</th> -->
									<!-- <th style="text-align:center;">Send Date</th> -->
									<th style="text-align:center;"></th>
								</tr>
								</thead>
									<tbody id="first_letter_body">
										<?php 
											if($first_letter)
											{
												
												foreach($first_letter as $key=>$letter)
									  			{
									  				if($letter->status == 11)
													{
														$disable_resend = "disable";
														$disable_paf = "";
													}
													else
													{
														$disable_paf = "disabled";
														$disable_resend = "";
													}

									  				echo '<tr>';
									  				// echo '<td style="width:2.5%;>" "</td>';
									  				echo '<td style="width:25%;" class="company_name"><a href="list_of_auditor/edit_first_letter/'.$letter->id.'" class="pointer mb-sm mt-sm mr-sm">'.$letter->company_name.'</a></td>';
									  				echo '<td style="width:24%;">'.$letter->audit_firm_name.'</td>';
									  				// echo '<td style="width:18%;" data-order="'.$letter->fye_date.'">'.date_format(date_create($letter->fye_date),"d F yy").'</td>';
									  				echo '<td style="width:19%;">'.$letter->firm_name.'</td>';
									  				echo '<td style="width:13%;"><div class="input-group" style="width: 100%;">'.form_dropdown('clearance_status', $status_dropdown, isset($letter->status)?$letter->status:'', 'class="clearance_status" style="width:100%;" onchange=change_clearance_status(this)').'<input type="hidden" class="letter_id" value="'. $letter->id .'" /></div></td>';
									  				echo '<td style="text-align:justify;width:19%;">
									  						<input type="hidden" class="letter_id" value="'. $letter->id .'" />
									  						
									  						<a type="button" class="btn btn_blue '.$disable_resend.'" href="list_of_auditor/resend_first_letter/'.$letter->id.'" style="margin:4px;">Resend</a>
									  						<button type="button" class="btn btn_blue clearance_upload_btn" onclick=open_cl_doc_modal(this) style="margin:4px;">Upload</button>
									  						<button type="button" class="btn btn_blue" onclick=get_first_clearance_letter(this) style="margin:4px;">PDF</button>
									  						<button '.$disable_paf.' type="button" class="btn btn_blue" onclick="open_clearance_paf(this)" style="margin:4px;">PAF</button>
									  						<button type="button" class="btn btn_blue" onclick=delete_first_letter(this) style="margin:4px;">Delete</button></td>';
									  				echo '</tr>';
									  			}
											}
											
										?> 
									</tbody>
								</table>
						</div>
						<div id="w2-resignation" class="tab-pane">
							<table class="table table-bordered table-striped mb-none datatable-resignation" id="datatable-resignation" style="width:100%">
								<thead>
									<tr style="background-color:white;">
										<th class="text-left">Client Name</th>
										<th class="text-left">New Auditor</th>
										<th class="text-left">Date of our letter</th>
										<th class="text-left">Letter from auditor</th>
										<th class="text-left"></th>
									</tr>
								</thead>
								<tbody>
									<?php 
										if($resignation_letter)
										{
											foreach($resignation_letter as $letter)
								  			{
								  				echo '<tr>';
								  				echo '<td>'.$letter->company_name.'</td>';
								  				echo '<td>'.$letter->audit_firm_name.'</td>';
								  				echo '<td>'.$letter->date_of_our_letter.'</td>';
								  				echo '<td><a href="'.'document/resignation/'.$letter->file_name.'" target="_blank" >'.$letter->file_name.'</a></td>';
								  				echo '<td style="text-align:center;">
								  						<input type="hidden" class="auditor_id" value="'. $auditor->id .'" />
								  						<a class="btn btn_blue" href="'.'document/resignation/'.$letter->generated_letter.'" target="_blank" >PDF</a>
								  						<a class="btn btn_blue" href="'.base_url().'/list_of_auditor/add_subsequent_resignation/'.$letter->id.'">ADD LETTER</a>
								  					  </td>';
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
		</div>
	</section>
</section>

<!-- Upload Clearance Doc Pop up -->
<div id="upload_cl_doc_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;" data-backdrop="static">
	<div class="modal-dialog" style="width: 1000px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<h2 class="panel-title">Upload</h2>
			</header>
			<form id="upload_cl_doc_form">
				<div class="panel-body">
					<div class="col-md-12">
						<table class="table table-bordered table-striped table-condensed mb-none">
							<input type="hidden" name="doc_cl_id" class="doc_cl_id" value="">

							<tr>
								<th>Potential Client</th>
								<td>
							        <div style="width: 80%;">
										<input disabled type="text" class="form-control client_name" name="client_name" value="" style="width: 100%;" id="cl_client" />
							        </div>
								</td>
							</tr>

							<tr>
								<th>Document(s)</th>
								<td>
							        <div class="">
    			                        <input id="multiple_cl_doc" name="cl_docs[]" type="file" data-browse-on-zone-click="false" multiple="true">
							        </div>
								</td>
							</tr>

						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn_blue" id="upload_cl_doc_btn">Submit</button>
					<input type="button" class="btn btn-default cl_upload_cancel" data-dismiss="modal" name="" value="Cancel">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- File to PAF Pop up -->
<div id="clearance_paf_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  data-backdrop="static" style="margin-top:150px;">
	<div class="modal-dialog" style="width: 750px !important;">
		<div class="modal-content">
			<header class="panel-heading">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h2 class="panel-title">File to PAF</h2>
			</header>
			<form id="clearance_paf_form">
				<input type="hidden" class="paf_letter_id" name="paf_letter_id">
	
				<div class="panel-body">
					<div class="col-md-12">
				
						<div id="attachment_table_wrapper">
							<table class="table table-bordered" id="clearance_attachment_table">
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

	var active_tab = <?php echo json_encode(isset($active_tab)?$active_tab:"first_clearance_letter") ?>;
	var auditor_list = <?php echo json_encode(isset($auditor_list)?$auditor_list:"") ?>;
	// var bank_confirm_setting = <?php echo json_encode(isset($bank_confirm_setting)?$bank_confirm_setting:"") ?>;
	var clearance_list = <?php echo json_encode(isset($first_letter)?$first_letter:"") ?>;
	var get_clearance_attachment_url = "<?php echo site_url('list_of_auditor/get_clearance_attachment'); ?>";
	var clearance_url = "<?php echo site_url('list_of_auditor'); ?>";
	var initialPreviewArray = []; 
	var initialPreviewConfigArray = [];
	var selected_move_cl;

	var change_cl_status_flag = false;
	
	// var type_of_leave_list = <?php echo json_encode(isset($type_of_leave_list)?$type_of_leave_list:"") ?>;
	// var block_leave_list = <?php echo json_encode(isset($block_leave_list)?$block_leave_list:"") ?>;
	// var holiday_list = <?php echo json_encode(isset($holiday_list)?$holiday_list:"") ?>;
	var pv_index_tab_aktif;
	var previous_clearance = "";
	var base_url = window.location.origin;
	var startDate = new Date();

	// console.log(clearance_list);

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

	$(document).ready( function () {
		$('.form_datetime').datetimepicker({
			weekStart: 1,
			todayBtn: 1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			forceParse: 0,
			showMeridian: 1
		});
	});

	function Client() {
	    var base_url = window.location.origin;  
	    var call = new ajaxCall();

	    this.getPicName = function(selected_pic_name=null) {
	        var url = base_url+"/"+folder+"/"+'bank/getPicName';
	        //console.log(url);
	        var method = "get";
	        var data = {};
	        $('.pic_name').find("option:eq(0)").html("Please wait..");
	        call.send(data, url, method, function(data) {
	            //console.log(data);
	            $('.pic_name').find("option:eq(0)").html("Select PIC Name");
	            // console.log(data);
	            if(data.tp == 1){
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);
	                    if(selected_pic_name != null && key == selected_pic_name)
	                    {

	                        option.attr('selected', 'selected');
	                        // $('.pic_name').attr('disabled', true);
	                    }
	                    // console.log(option);
	                    $('.pic_name').append(option);
	                });
	                $('#pic_name').select2();
	                //$(".nationality").prop("disabled",false);
	            }
	            else{
	                alert(data.msg);
	            }
	        }); 
	    };

	}

	$(function() {
	    var cn = new Client();
	    cn.getPicName();
	});

	$(document).ready(function () {
	    $('#datatable-clearance').DataTable( {
	    	"order": []
	    });

	    $('#datatable-resignation').DataTable( {
	    	"order": []
	    });

	    // var t = $('#datatable-auth').DataTable( {
	    // 	// "order": []
	    // 	"order": [[ 3, 'desc' ]]
	    // });

	    // t.on( 'order.dt search.dt', function () {
	    //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	    //         cell.innerHTML = i+1;
	    //     } );
	    // } ).draw();

	    // var t_d = $('#datatable-auth-deactive').DataTable( {
	    // 	"order": [[ 3, 'desc' ]]
	    // 	// "order": [[ 3, 'asc' ]]
	    // });

	    // t_d.on( 'order.dt search.dt', function () {
	    //     t_d.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	    //         cell.innerHTML = i+1;
	    //     } );
	    // } ).draw();

	    // var t_c = $('#datatable-confirmation').DataTable( {
	    // 	"order": [[ 5, 'desc' ]]
	    // 	// "order": [[ 3, 'asc' ]]
	    // });

	    // t_c.on( 'order.dt search.dt', function () {
	    //     t_c.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
	    //         cell.innerHTML = i+1;
	    //     } );
	    // } ).draw();

	    
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


	$(document).on('click',".edit_bank",function(){
	    var edit_bank_id =  $(this).data("id");
	 
	    for(var i = 0; i < bank_list.length; i++)
	    {
	    	if(bank_list[i]["id"] == edit_bank_id)
	    	{
	    		$(".bank_list_id").val(bank_list[i]["id"]);
    			$("#bank_name_for_user").val(bank_list[i]["bank_name_for_user"]);
    			$("#bank_name").val(bank_list[i]["bank_name"]);
    			$("#add_line1").val(bank_list[i]["add_line1"]);
    			$("#add_line2").val(bank_list[i]["add_line2"]);
	    	}
	    }
	});

	$(document).on('click',".edit_auditor",function(){
	    var edit_auditor_id =  $(this).data("id");
	 
	    for(var i = 0; i < auditor_list.length; i++)
	    {
	    	if(auditor_list[i]["id"] == edit_auditor_id)
	    	{
	    		$(".auditor_list_id").val(auditor_list[i]["id"]);
    			$("#audit_firm_name").val(auditor_list[i]["audit_firm_name"]);
    			$("#audit_firm_email").val(auditor_list[i]["audit_firm_email"]);
    			$("#postal_code").val(auditor_list[i]["postal_code"]);
    			$("#street_name").val(auditor_list[i]["street_name"]);
    			$("#building_name").val(auditor_list[i]["building_name"]);
    			$("#unit_no1").val(auditor_list[i]["unit_no1"]);
    			$("#unit_no2").val(auditor_list[i]["unit_no2"]);
	    	}
	    }
	});

	$(document).on('click',".edit_bank_confirm_setting",function(){
	    var edit_bank_confirm_setting_id =  $(this).data("id");
	    
	    // console.log(bank_confirm_setting);
	 
	    for(var i = 0; i < bank_confirm_setting.length; i++)
	    {
	    	if(bank_confirm_setting[i]["setting_id"] == edit_bank_confirm_setting_id)
	    	{
	    		console.log("match");
	    		$(".bank_confirm_setting_list_id").val(bank_confirm_setting[i]["setting_id"]);
    			$(".confirm_month").val(bank_confirm_setting[i]["confirm_month"]);
    			var cn = new Client();
	    		cn.getPicName(bank_confirm_setting[i]["pic_id"]);
    			
	    	}
	    }

	
	});
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

<script src="<?= base_url()?>application/modules/list_of_auditor/js/clearance.js" charset="utf-8"></script>