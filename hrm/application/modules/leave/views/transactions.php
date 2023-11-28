<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>

<style>
	#datatable-default_length label {
		display: none !important;
	}
</style>

<section role="main" class="content_section" style="margin-left:0;">
<section class="panel" style="margin-top: 30px;">
	<!-- <header class="panel-heading">
		<div class="panel-actions">
			<a class="create_client themeColor_purple" href="leave/apply_leave/" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Apply Leave</a>
		</div>
		<h2></h2>
	</header> -->

	<div class="panel-body">
		<div class="col-md-12">
			<div class="tabs">				
				<ul class="nav nav-tabs nav-justify">

					<li class="active check_state" data-information="pending">
						<a href="#w2-transaction" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">1</span>
							Leave Transaction
						</a>
					</li>
					<li class="check_state" data-information="day_off">
						<a href="#w2-day_off" data-toggle="tab" class="text-center">
							<span class="badge hidden-xs">2</span>
							Day Off
						</a>
					</li>
				</ul>
				<div class="tab-content clearfix">
					<div>
						<form action="/hrm/leave/transactions" method="get">
							<?php
								if ($this->data['Manager'] || $this->data['Admin']) {
							?>
							<div class="col-sm-12 col-md-12" style="margin-bottom: 20px;">
								<div class="col-sm-6 col-md-4">
									<select class="form-control filter" name="employee" id="employee">
										<option value="" disabled selected>Select Employee</option>
									<?php 
										if (isset($employee_list)) {
											foreach($employee_list as $id => $name)
											{
												if ($employee != "" && $employee == $id) {
													echo '<option value="'.$id.'" selected>'.$name.'</option>';
												} else {
													echo '<option value="'.$id.'">'.$name.'</option>';
												}
											}
										}
									?>
									</select>
								</div>
								<div class="col-sm-6 col-md-4">
									<select class="form-control filter" name="leave-type" id="leave_type">
										<option value="" disabled selected>Select Leave Type</option>
									<?php 
										if (isset($leave_type_list)) {
											foreach($leave_type_list as $row)
											{
												if ($leave_type != "" && $leave_type == $row->id) {
													echo '<option value="'.$row->id.'" selected>'.$row->leave_name.'</option>';
												} else {
													echo '<option value="'.$row->id.'">'.$row->leave_name.'</option>';
												}
											}
										}
									?>
									</select>
								</div>
							</div>
							<?php
								}
							?>
							<div class="col-sm-12 col-md-12">
								<div class="col-sm-6 col-md-4">
									<input type="date" name="fromdate" class="form-control filter" id="fromdate" value="<?= $fromdate;?>" required/>
								</div>
								<div class="col-sm-6 col-md-4">
									<input type="date" name="todate" class="form-control filter" id="todate" value="<?= $todate;?>" required/>
								</div>
								<div class="col-sm-6 col-md-4">
									<button type="button" class="btn btn_purple pull-left search">Search</button>
								</div>
							</div>
						</form>
					</div>
					<div id="w2-transaction" class="tab-pane active">
						<div class="col-sm-12 col-md-12">
							<?= form_close();?>
						</div>
						<div style="font-size: 2.4rem;padding-top: 50px; margin: 7px 0 14px 0;">Leave Transactions</div>
						<!-- <table class="table table-bordered table-striped mb-none datatable-default" id="" style="width:100%"> -->
						<table class="table table-bordered table-striped mb-none datatable-default" id="datatable-default" style="width:100%">
							<thead>
								<tr style="background-color:white;">
									<th class="text-left">Leave No.</th>
									<th class="text-left">Application Date</th>
									<th class="text-left">Start Date</th>
									<th class="text-left">End Date</th>
									<th class="text-left">Employee Name</th>
									<th class="text-left">Firm</th>
									<th class="text-left">Before</th>
									<th class="text-left">Deducted</th>
									<th class="text-left">After</th>
									<th class="text-center">Type of Leave</th>
									<th class="text-center">Reason</th>
									<!-- <th></th> -->
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($leave_list as $row)
						  			{
										echo '<tr>';
										echo '<td><a href="leave/apply_leave/'.$row->id.'">'.$row->leave_no.'</a></td>';
									  	echo '<td>'.date('d F Y', strtotime($row->date_applied)).'</td>';
										echo '<td>'.date('d F Y', strtotime($row->start_date)).'</td>';
										echo '<td>'.date('d F Y', strtotime($row->end_date)).'</td>';
										echo '<td>'.$row->employee_name.'</td>';
										echo '<td>'.$row->firm_name.'</td>';
										echo '<td>'.$row->al_left_before.'</td>';
										echo '<td>'.$row->total_days.'</td>';
										echo '<td>'.$row->al_left_after.'</td>';
										echo '<td>'.$row->leave_name.'</td>';
										echo '<td>'.$row->reason.'</td>';
										echo '</tr>';
						  			}
								?>
							</tbody>
						</table>
					</div>

					<div id="w2-day_off" class="tab-pane">
					<div style="font-size: 2.4rem;padding-top: 50px; margin: 7px 0 14px 0;">Day Off</div>
						<!-- <table class="table table-bordered table-striped mb-none datatable-default" id="" style="width:100%"> -->
						<table class="table table-bordered table-striped mb-none datatable-default" id="datatable-default" style="width:100%">
							<thead>
								<tr style="background-color:white;">
									<th class="text-left">Employee Name</th>
									<th class="text-left">Firm</th>
									<th class="text-left">Added</th>
									<th class="text-center">Type of Leave</th>
									<th class="text-center">Annual Leave Day</th>
									<th class="text-left">Updated Date</th>
									<!-- <th></th> -->
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach($dayoff_list as $row)
						  			{
										echo '<tr>';
										echo '<td>'.$row->employee_name.'</td>';
										echo '<td>'.$row->firm_name.'</td>';
										echo '<td>'.$row->added.'</td>';
										echo '<td>'.$row->leave_name.'</td>';
										echo '<td>'.$row->annual_leave_days.'</td>';
										echo '<td>'.date('d F Y', strtotime($row->last_updated)).'</td>';
										echo '</tr>';
						  			}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- <div class="row datatables-header form-inline">
				<div class="col-sm-12 col-md-12">
					<?= form_close();?>
				</div>
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
						</tbody>
					</table>
					<div style="font-size: 2.4rem;padding: 0; margin: 7px 0 14px 0;">Leave Transactions</div>
					<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left">Leave No.</th>
								<th class="text-left">Application Date</th>
								<th class="text-left">Start Date</th>
								<th class="text-left">End Date</th>
								<th class="text-left">Employee Name</th>
								<th class="text-left">Firm</th>
								<th class="text-left">Before</th>
								<th class="text-left">Deducted</th>
								<th class="text-left">After</th>
								<th class="text-center">Type of Leave</th>
								<th class="text-center">Reason</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$date = date('d F Y');

								// print_r($dayoff_list);
								
								foreach($leave_list as $row)
					  			{

									// $balance = (int) $row->balance_before_approve - (int) $row->total_days;
					  				echo '<tr>';
					  				echo '<td><a href="leave/apply_leave/'.$row->id.'">'.$row->leave_no.'</a></td>';
									echo '<td>'.date('d F Y', strtotime($row->date_applied)).'</td>';
					  				echo '<td>'.date('d F Y', strtotime($row->start_date)).'</td>';
					  				echo '<td>'.date('d F Y', strtotime($row->end_date)).'</td>';
									echo '<td>'.$row->employee_name.'</td>';
									echo '<td>'.$row->firm_name.'</td>';
									echo '<td>'.$row->al_left_before.'</td>';
					  				echo '<td>'.$row->total_days.'</td>';
					  				echo '<td>'.$row->al_left_after.'</td>';
									echo '<td>'.$row->leave_name.'</td>';
									echo '<td>'.$row->reason.'</td>';
					  				echo '</tr>';
					  			}
							?>
						</tbody>
					</table>
				</div>
			</div> -->
		</div>
	</div>
</section>
</section>

<!-- Modal -->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<div class="modal-header">
          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          	<span aria-hidden="true">&times;</span>
	    	</button>
          	<h4 class="modal-title">Send Offer Letter</h4>
        </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn_purple">Send</button>
      </div>
    </div>
  </div>
</div> -->

<script>
	var base_url = '<?php echo base_url(); ?>';

	$(document).ready( function () {
	    $('.datatable-default').DataTable( {
	    	"order": []
	    } );

		$(document).on("click", ".search", function() {
			$("form").submit();
		})
	} );

	function confirmationOL(id){
		$.post("./offer_letter/sendOL_NewEmployee", { 'id': id }, function(data, status){
			$('.modal-body').empty();
            $('.modal-body').prepend(data);
            $('#exampleModal').modal('show');
            // $('#exampleModal').show();
        });
	}

	function withdraw_leave(leave_id, employee_id, total_days, type_of_leave_id, status_id){

    	bootbox.confirm({
	        message: "Confirm to WITHDRAW the selected leave?",
	        closeButton: false,
	        buttons: {
	            confirm: {
	                label: 'Yes',
	                className: 'btn_purple'
	            },
	            cancel: {
	                label: 'No',
	                className: 'btn_cancel'
	            }
	        },
	        callback: function (result) {
	        	if(result == true)
	        	{
	        		$.post("<?php echo site_url('leave/withdraw_leave'); ?>", { leave_id: leave_id, employee_id: employee_id, total_days: total_days, type_of_leave_id: type_of_leave_id, status_id:status_id }, function (data, status){
			    			if(status){
			    				window.location = base_url + "leave/index";
			    			}
			    		} 
			    	);
	        	}
	        }
	    })
    }
</script>