<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<style>
	#datatable-default_length label,
	input[type=search] {
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
			<div class="row datatables-header form-inline">
				<div class="col-sm-12 col-md-12">
					<!-- <div class="dataTables_filter" id="datatable-default_filter">
						<input style="width: 45%;" aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?= $_POST['search']?$_POST['search']:'';?>" class="form-control" type="search">
							<input type="submit" class="btn btn_purple" value="Search"/>
							<a href="Interview" class="btn btn_purple">Show All Interview</a>
					</div> -->
				</div>
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					<!-- <table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left">Leave no.</th>
								<th class="text-left">Employee</th>
								<th class="text-left">Firm</th>
								<th class="text-left">Start Date</th>
								<th class="text-left">End Date</th>
								<th class="text-center">Total Days</th>
								<th class="text-center">Days left (Before Apply)</th>
								<th class="text-center">Days left (After Approve)</th>
								<th class="text-center">Leave Status</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								// foreach($leave_list as $row)
					  			// {
					  			// 	echo '<tr>';
					  			// 	echo '<td><a href="leave/apply_leave/'.$row->id.'">'.$row->leave_no.'</a></td>';
					  			// 	echo '<td>'.$row->employee_name.'</td>';
					  			// 	echo '<td>'.$row->firm_name.'</td>';
					  			// 	echo '<td>'.$row->start_date.'</td>';
					  			// 	echo '<td>'.$row->end_date.'</td>';
					  			// 	echo '<td>'.$row->total_days.'</td>';
					  			// 	echo '<td>'.$row->days_left_before.'</td>';
					  			// 	echo '<td>'.$row->days_left_after.'</td>';
					  			// 	echo '<td>'.$row->status.'</td>';
					  			// 	echo '</tr>';
					  			// }
							?>
						</tbody>
					</table> -->
					<div style="font-size: 2.4rem;padding: 0; margin: 7px 0 14px 0;">Leave</div>
					<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left">Leave No.</th>
								<th class="text-left">Leave Name</th>
								<th class="text-left">Days</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$date = date('d F Y');

								foreach($leave_list as $key => $row)
					  			{
					  				echo '<tr class="row-'.$key.'">';
									echo '<td>'.($key + 1).'</td>';
									echo '<td>'.$row->leave_name.'</td>';
									echo '<td class="days">'.$row->days.'</td>';
									echo '<td><button class="btn btn_purple edit-btn" onclick="edit('.$key.')" style="margin-bottom:10px;">Edit</button>';
									echo '<button class="btn btn_purple save-btn" data-id="'.$row->id.'" onclick="save('.$key.')" style="margin-bottom:10px; display: none;">Save</button></td>';
					  				echo '</tr>';
					  			}
							?>
						</tbody>
					</table>
				</div>
			</div>
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
	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}
	var base_url = '<?php echo base_url(); ?>';

	$(document).ready( function () {
	    $('#datatable-default').DataTable( {
	    	"order": []
	    } );
	} );

	function edit(rowIndex){
		let days = $(".row-"+rowIndex+" .days").html();
		$(".row-"+rowIndex+" .days").html("");
		$(".row-"+rowIndex+" .days").append(`
			<input class="form-control days-${rowIndex}" type="text" pattern="[0-9]" value="${days}" />
		`);
		$(".edit-btn").hide();
		$(".save-btn").show();
	}

	function save(rowIndex) {
		let days = $(".days-"+rowIndex).val();
		let id = $(".row-"+rowIndex+" .save-btn").attr("data-id");
		console.log("days", days)
		console.log("id", id);

		$.post("<?php echo site_url('leave/editAmount'); ?>",
			{ id: id, days: days }, 
			function (data, status) {
				if(status){
					toastr.success('Information Updated', 'Updated');
				} else {
					toastr.error('Something went wrong. Please try again later.', 'Error');
				}

				$(".row-"+rowIndex+" .days").html(days);
				$(".row-"+rowIndex+" .days input").remove();
				$(".edit-btn").show();
				$(".save-btn").hide();
			} 
		);

		
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