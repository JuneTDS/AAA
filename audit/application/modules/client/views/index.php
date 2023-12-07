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

	<div class="header_between_all_section">
		<section class="panel">
			<header class="panel-heading">
					<div class="panel-actions">
						<a class="create_client amber" href="<?= base_url() ?>client/addclient" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Client" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create Client</a>
												
						<!-- a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a-->
						<!--a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a-->
					</div>
					<h2></h2>
			</header>
			<div class="panel-body">
				<div class="col-md-12">
					
							
							<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
								<thead>
									<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
										<tr style="background-color:white;">
											<th rowspan="2" class="text-center">Registration No</th>
											<th rowspan="2" class="text-center">Company</th>
											<!-- <th rowspan="2" style="vertical-align: middle;font-weight:bold;width:200px;">Address</th> -->
											
											<th colspan="3" class="text-center">Contact</th>
									<!-- 		<th rowspan="2" class="text-center">Unpaid Invoice</th>
											
											<th rowspan="2" class="text-center">Unreceived Document</th> -->
											<th rowspan="2" class="text-center">Filing Deadline</th>
											<th rowspan="2"></th>
											<!-- <th rowspan="2" style="vertical-align: middle;font-weight:bold; width:50px;">Delete</th> -->
										</tr>
										<tr style="background-color:white;">
											<th style="text-align: center">Name</th>
											<th class="text-center">Phone</th>
											<th class="text-center">E-Mail</th>
										</tr>
									<?php } ?>
									<?php if($Individual) {?>
										<tr style="background-color:white;">
											<th class="text-center">Registration No</th>
											<th class="text-center">Company</th>
											<!-- <th class="text-center">Unreceived Document</th> -->
											<th class="text-center">Filing Deadline</th>
											<th ></th>

										</tr>
										
									<?php } ?>
									<?php if($Client) {?>
										<tr style="background-color:white;">
											<th class="text-center">Registration No</th>
											<th class="text-center">Company</th>
									<!-- 		<th class="text-center">Unpaid Invoice</th>
											<th class="text-center">Unreceived Document</th> -->
											<th class="text-center">Filing Deadline</th>
											

										</tr>
										
									<?php } ?>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
							<style>
								/*.tonggle_readmore {
									font-size:30px;
									line-height:12px;
								}*/
							</style>
							<script>

								
								
								var no_of_client = <?php echo json_encode($no_of_client) ?>;
							    var total_no_of_client = <?php echo json_encode($total_no_of_client) ?>;
							    var Admin = <?php echo json_encode($Admin) ?>;
							    $(document).ready(function () {
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
							                window.location.href = '<?= base_url();?>client/addclient';
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

									/*console.log(tr);
									console.log(each_customer_id);*/
									bootbox.confirm("Are you confirm delete this client?", function (result) {
							            if (result) 
							            {
											if(each_client_id != undefined)
											{
												$('#loadingmessage').show();
												$.ajax({ //Upload common input
											        url: "masterclient/delete_client",
											        type: "POST",
											        data: {"client_id": each_client_id},
											        dataType: 'json',
											        success: function (response) {
											        	//console.log(response);
											        	$('#loadingmessage').hide();

											        	toastr.success(response.message, response.title);
											        	//activity_data = response.activity_data;

											  
											        }
											    });
											}
											tr.remove();
										}
									});

									
									//check_due_date_175();
									//$("#year_end_date").prop('disabled', false);
									//$(".change_year_end_button").hide();
								}
							</script>
				</div>
				
	</div>
				<!-- end: page -->




<script>
	var base_url = '<?php echo base_url(); ?>';

	$(document).ready( function () {
	    $('#datatable-default').DataTable( {
	    	"order": [[0,'asc'],[1,'asc']]
	    } );
	} );

</script>

<!-- 
<script>

	var access_right_client_module = <?php echo json_encode($client_module);?>;
	//var service_category = <?php echo json_encode($service_category);?>;

	// $(document).ready( function () {
	//     $('#datatable-test').DataTable( {
	//     	pageLength: 10,
	// 	    filter: true,
	// 	    deferRender: true,
	// 	    scrollY: 200,
	// 	    scrollCollapse: true,
	// 	    scroller: true
	//     } );
	// } );

	if(access_right_client_module == "read")
	{
		$('.edit_client').hide();
		$('.delete_client').attr("disabled", true);
	}

	$(document).on('click','.edit_client',function() {
		// alert($(this).data('name'));
		localStorage.setItem('slitems', $(this).data('name'));
			$("#file_add_client").hide();
		location.href = "<?= base_url();?>masterclient/edit";
	});

	// $.each(service_category, function(key, val) {
	//     var option = $('<option />');
	//     option.attr('value', key).text(val);

	//     $("#service_category").append(option);
	// });
	
		// $(document).on('click','.edit_client',function(){
		// });	
		$(document).on('ready',function() {
			$("#pencarian").focus();
			$("#pencarian").select();
		});
</script> -->
<!-- <style>
	#buttonclick .datatables-header {
		display:none;
	}
</style> -->