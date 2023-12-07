<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>

<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 30px;">
		<header class="panel-heading">
			
				<div class="panel-actions">
					<a class="create_client themeColor_blue" href="employee/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Employee" ><i class="fa fa-plus-circle themeColor_blue" style="font-size:16px;height:45px;"></i> Create Employee</a>
				</div>
			
			<h2></h2>
		</header>
		<div class="panel-body">
			<div class="col-md-12">
				<div class="row datatables-header form-inline">
					<div class="col-sm-12 col-md-12">
						<!-- <div class="dataTables_filter" id="datatable-default_filter">
							<input style="width: 45%;" aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?=$_POST['search']?$_POST['search']:'';?>" class="form-control" type="search">
								<input type="submit" class="btn btn_blue" value="Search"/>
								<a href="Employee" class="btn btn_blue">Show All Employee</a>
							<?= form_close();?>
						</div> -->
					</div>
					<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					<table class="table table-bordered table-striped mb-none" id="datatable-employee_info" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left">Name</th>
								<th class="text-center">Phone no.</th>
								<th class="text-left">Designation</th>
								<th class="text-left">Department</th>
								<th class="text-center">Workpass</th>
								<th class="text-center">Account</th>
								<th class="text-center">Offer Letter</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								foreach($staff_list as $row)
					  			{
					  				echo '<tr>';
					  				echo '<td><a href="employee/edit/'.$row->id.'">'.$row->name.'</td>';
					  				echo '<td>'.$row->phoneno.'</td>';
					  				echo '<td>'.$row->designation.'</td>';
					  				echo '<td>'.$row->department_name.'</td>';
					  				echo '<td>'.$row->workpass.'</td>';

					  				if($row->user_id == null){
					  					echo '<td align="center"><a href="'. base_url() .'employee/create_user/'. $row->id .'" class="btn btn-success">Create</a></td>';
					  				}else{
					  					echo '<td>'.$row->user_email.'</td>';
					  				}
					  				
					  				echo '<td style="text-align:center;"><button type="button" class="btn btn_blue" onclick=confirmationOL("'. $row->id .'")>Offer Letter</button></td>';
					  				echo '</tr>';
					  			}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
</section>

<!-- Modal -->
<div class="modal fade" id="offer_letter_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<form id="offer_letter" method="POST">
	    	<div class="modal-header">
	          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          	<span aria-hidden="true">&times;</span>
		    	</button>
	          	<h4 class="modal-title">Send Offer Letter</h4>
	        </div>
	      <!-- <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Send Offer Letter</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div> -->
	      <div class="modal-body"></div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn_blue">Save</button>
	      </div>
  		</form>
    </div>
  </div>
</div>

<script type="text/javascript">
	var base_url = <?php echo base_url(); ?>;

	function confirmationOL(id){
		$.post(base_url + "offer_letter/sendOL_ExistingEmployee", { 'id': id }, function(data, status){
			$('.modal-body').empty();
            $('.modal-body').prepend(data);
            $('#offer_letter_modal').modal('show');
            // $('#exampleModal').show();
        });
	}

	function preview_offer_letter(employee_id){
        // console.log(payslip_id);

        $.post(base_url + "employee/view_offer_letter", { 'employee_id': employee_id }, function(data, status){
            // console.log(data);
            var response = JSON.parse(data);

            window.open(
                response.pdf_link,
                '_blank' // <- This is what makes it open in a new window.
            );
        });
    }

    (function( $ ) {
		'use strict';

		var datatableInit = function() {

			var table1 = $('#datatable-employee_info').DataTable({
				
	            "order": [[ 1, 'asc' ]]
			});
		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);
</script>
