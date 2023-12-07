<link href="<?= base_url() ?>node_modules/jquery-datatables/media/css/jquery.dataTables.min.css" rel="stylesheet">

<script src="<?= base_url() ?>node_modules/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>node_modules/jquery-datatables-bs3/assets/js/datatables.js"></script>

<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions">
			<a class="create_client themeColor_blue" href="timesheet/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_blue" style="font-size:16px;height:45px;"></i> Create Timesheet</a>
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
							<a href="Interview" class="btn btn_blue">Show All Interview</a>
						<?= form_close();?>
					</div> -->
				</div>
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					<table class="table" id="datatable-default" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left">Timesheet no. </th>
								<th class="text-left">Status</th>
								<th class="text-left">Month</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								foreach($timesheet_list as $row)
					  			{
					  				echo '<tr>';
					  				echo '<td><a href="timesheet/edit/'. $row->id .'">'. $row->timesheet_no .'</td>';
					  				echo '<td>'.$row->status_id.'</td>';
					  				echo '<td>'.date('F Y', strtotime($row->month)).'</td>';
					  				// echo '<td>'. date('d F Y', strtotime($row->date)) .'</td>';
					  				// echo '<td>'. $row->client_name .'</td>';
					  				// echo '<td>'. $row->description .'</td>';
					  				// echo '<td>'. $row->firm_name .'</td>';
					  				// echo '<td>'. $row->amount .'</td>';
					  				// // echo '<td align="center";><img src='. base_url() . 'uploads/reimbursement/' . $row->receipt_img_filename .' style=" width:30%; height:30%;"></td>';
					  				// echo '<td>'. $row->invoice_no .'</td>';
					  				// echo '<td>'. date('d F Y', strtotime($row->date_applied)) .'</td>';
					  				// echo '<td>'. $row->status_id .'</td>';
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

<script>
	$(document).ready( function () {
	    $('#datatable-default').DataTable( {
	    } );
	} );

	// function confirmationOL(id){
	// 	$.post("./offer_letter/sendOL_NewEmployee", { 'id': id }, function(data, status){
	// 		$('.modal-body').empty();
 //            $('.modal-body').prepend(data);
 //            $('#exampleModal').modal('show');
 //            // $('#exampleModal').show();
 //        });
	// }
</script>