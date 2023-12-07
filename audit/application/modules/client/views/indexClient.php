<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<link rel="stylesheet" href="<?=base_url()?>application/css/theme-custom.css" />
<link rel="stylesheet" href="<?= base_url()?>application/css/plugin/intlTelInput.css" />
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
<link rel="stylesheet" href="<?= base_url() ?>node_modules/toastr/build/toastr.min.css" />
<script src="<?= base_url()?>node_modules/toastr/build/toastr.min.js" /></script>
<script src="<?= base_url()?>application/js/intlTelInput.js" /></script>
<script src="<?= base_url()?>application/js/defaultCountryIp.js" /></script>
<script src="<?= base_url()?>application/js/utils.js" /></script>
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>	

<!-- <div class="header_between_all_section"> -->
	<section role="main" class="content_section" style="margin-left:0;">
		<header class="panel-heading">
			<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
				<div class="panel-actions">
					<!-- <a class="create_client amber" href="#" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Client" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create Client</a> -->
				</div>
				<h2></h2>
			<?php } ?>
		</header>
		<div class="panel-body">
			<div class="col-md-12">
				<div class="row datatables-header form-inline">
					<div class="col-sm-12 col-md-12">
						<div class="dataTables_filter" id="datatable-default_filter">
						
								<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
									echo form_open_multipart("client", $attrib);
									
								?>

								<input class="form-control search_input_width" aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?=isset($_POST['search'])?$_POST['search']:'';?>" type="search">
								<!-- <button name="search" type="submit" name="btn_cari_client" class="btn btn-primary" tabindex="-1">Search</button> -->
								<div class="search_group_button" style="display: inline;">
									<input type="submit" class="btn btn-primary" value="Search"/>
									<!-- <button name="showall" type="submit" class="btn btn-primary" tabindex="-1">Show All Clients</button> -->
									<!-- <a href="client" class="btn btn-primary" value="">Show All Clients</a> -->
									<input type="button" class="btn btn-primary" value="Show All Clients" onclick="location.href='<?= base_url();?>client/'" />
									<!-- <input type="button" style="cursor:pointer; margin-bottom: 10px;" class="btn btn_purple" value="Preview" onclick="preview_offer_letter('<?=isset($employee_data[0]->employee_applicant_id)?$employee_data[0]->employee_applicant_id:'' ?>')"/> -->
								</div>
								<?= form_close();?>
						</div>
					</div>
				</div>
				
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
						
					<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
						<thead>
								<tr style="background-color:white;">
								<th  class="text-center">Firm</th>
								<th  class="text-center">Company</th>
								<th  class="text-center">FYE</th> 
							</tr>
						</thead>
						<tbody>
							<?php
								$i=1;
								
								foreach($client as $key=>$c )
								{ 	
									
							?>
							<tr>
								
								<td style="width:20%;">
									<?=$c["firm_name"]?>
								</td>
								<td style="width:60%;">
									<div style="word-break:break-all;" >
										<a class="" href="<?=site_url('client/audit_client/'.$c["id"]);?>" data-name="<?=trim($c["company_name"])?>" style="cursor:pointer" data-toggle="tooltip" data-trigger="hover" data-original-title="Edit This Client"><?=ucwords(substr(trim($c["company_name"]),0,100))?><span id="f<?=$i?>" style="display:none;cursor:pointer"><?=substr(trim($c["company_name"]),100,strlen(trim($c["company_name"])))?></span></a>
										<?php
											if(strlen($c['company_name']) > 100)
											{
												echo '<a class="tonggle_readmore" data-id=f'.$i.'>...</a>';
											}
										?>
									</div>
								</td>
								
								<td style="width:20%;">
								</td>
							</tr>
							<?php
									$i++;
								}
							?>
						</tbody>
					</table>
				</div>
			</div>			
		</div>
	</section>
<!-- </div> -->
<script>

	$("#header_our_firm").removeClass("header_disabled");
	$("#header_manage_user").removeClass("header_disabled");
	$("#header_access_right").removeClass("header_disabled");
	$("#header_user_profile").removeClass("header_disabled");
	$("#header_setting").removeClass("header_disabled");
	$("#header_dashboard").removeClass("header_disabled");
	$("#header_client").addClass("header_disabled");
	$("#header_person").removeClass("header_disabled");
	$("#header_document").removeClass("header_disabled");
	$("#header_report").removeClass("header_disabled");
	$("#header_billings").removeClass("header_disabled");

	var no_of_client = <?php echo json_encode($no_of_client) ?>;
	var total_no_of_client = <?php echo json_encode($total_no_of_client) ?>;
	var Admin = <?php echo json_encode($Admin) ?>;

	$(document).ready(function () {
		$('#datatable-default').DataTable( {
	    	"order": [[0,'asc'],[1,'asc']]
	    } );
		
	    $('.create_client').click(function (e) {
	        e.preventDefault();

	        if(no_of_client == total_no_of_client)
	        {
	            bootbox.alert("Cannot exceed the total number of clients.", function() {
	              //Example.show("Hello world callback");
	            });
	        }
	        else
	        {
	            window.location.href = '<?= base_url();?>client/addClient';
	        }
	        
	    });
	});

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	$(document).on('click','.tonggle_readmore',function (){
		$id = $(this).data('id');
		$("#"+$id).toggle();
	});

	function delete_client(element){
		var tr = jQuery(element).parent().parent();
		var each_client_id = tr.find('input[name="each_client_id"]').val();


		bootbox.confirm("Are you confirm delete this client?", function (result) {
            if (result) 
            {
				if(each_client_id != undefined)
				{
					$('#loadingmessage').show();
					$.ajax({ //Upload common input
				        url: "client/delete_client",
				        type: "POST",
				        data: {"client_id": each_client_id},
				        dataType: 'json',
				        success: function (response) {
				        	//console.log(response);
				        	$('#loadingmessage').hide();

				        	toastr.success(response.message, response.title);
				
				       }
				    });
				}
				tr.remove();
			}
		});


	}

	
</script>
<style>
	#buttonclick .datatables-header {
		display:none;
	}
</style>
				