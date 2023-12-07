<!-- <link href="<?= base_url() ?>assets/vendor/jquery-datatables/media/css/jquery.dataTables.min.css" rel="stylesheet"> -->
<script src="<?= base_url() ?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>

<script src="<?= base_url() ?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>

<style>
	a { color: var(--main-theme-color); }
	a:hover, a:focus { color: var(--main-theme-color); }
	a:active { color: var(--main-theme-color); }

</style>
<!-- 
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-switch-master/dist/js/bootstrap-switch.js"></script> -->


<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions">
			<a class="create_client themeColor_blue" href="interview/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_blue" style="font-size:16px;height:45px;"></i> Create Interview</a>
		</div>
		<h2></h2>
	</header>
	<div class="panel-body">
		<div class="col-md-12">
			<div class="row datatables-header form-inline">
				
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
				<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
					<thead>
						<tr style="background-color:white;">
							<th class="text-left">Interview no</th>
							<th class="text-left">Applicant Name</th>
							<th class="text-left">Date & Time</th>
							<th class="text-center">Interview Status</th>
							<th class="text-center">Interview Result</th>
							<th class="text-center">Offer Letter</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach($interview_list as $key=>$row)
				  			{
				  				echo '<tr>';
				  				echo '<td><a href="interview/edit_interview/'.$row->interview_id.'">'.$row->interview_no.'</a></td>';
				  				echo '<td><a href="interview/applicant_profile/'.$row->applicant_id.'">'.$row->name.'</a></td>';
				  				echo '<td>'.$row->interview_time.'</td>';
				  				if($row->status != 2){
				  					echo '<td>'.
				  						form_dropdown('interview_status', $interview_status, $row->status, 'onchange="change_interview_status(this,'. $row->interview_id .', '.$key.')" style="width:100%;"')
				  					.'</td>';

				  					echo '<td align="center">-</td>';
				  				}else{
				  					echo '<td>'.
				  						form_dropdown('interview_status', $interview_status, $row->status, 'style="width:100%;" disabled')
				  					.'</td>';

									echo '<td>'.
				  						form_dropdown('interview_result', $interview_result, $row->result, 'onchange="change_interview_result(this,'. $row->interview_id .', '. $row->result .')" style="width:100%;"')
				  					.'</td>';
				  				}

				  				
				  				if($row->result == 2){
				  					echo '<td style="text-align:center;"><button type="button" class="btn btn_blue" onclick=confirmationOL("'.$row->applicant_id.'")>Offer Letter</button></td>';
				  				}else{
				  					echo '<td style="text-align:center;"> - </td>';
				  				}
				  				echo '</tr>';
				  			}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
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
	          	<h4 class="modal-title">Offer Letter Details</h4>
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

<script>
	// $(document).ready( function () {
	    var table = $('#datatable-default').DataTable( {
	    	"dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>"
	    } );
	// } );

	var base_url ='<?php echo base_url() ?>';

	$(document).ready(function() {
		$('.dataTables_filter input').addClass('form-control');
		$('.dataTables_filter input').attr('placeholder', 'Search');
	});

	function confirmationOL(id){
		$.post("./offer_letter/sendOL_NewEmployee", { 'id': id }, function(data, status){
			$('.modal-body').empty();
            $('.modal-body').prepend(data);
            $('#offer_letter_modal').modal('show');
        });
	}

	function change_interview_status(element, interview_id, row_index){
		// console.log(row_index);
		if(confirm("Once it is applied, status cannot be change back. Are you sure to confirm applicant?")){
			$.post(base_url + "interview/change_interview_status", {'interview_id': interview_id }, function(data, status){
				if(data){
					location.reload();
					// $(element).prop("disabled", true);
					// table.ajax.reload();
					// table.row(row_index).data(temp).invalidate();
				}
			});
		}else{
			$(element).val(1);	// Change back to pending status
		}
	}

	function change_interview_result(element, interview_id, ori_value){
		var choice = $(element).val();

		if(choice != 3){
			if(confirm("Are you sure to change interview result?")){
			
				$.post(base_url + "interview/change_interview_result", {'interview_id': interview_id, 'result': choice }, function(data, status){
					if(data){
						location.reload();
						// $(element).prop("disabled", true);
						// table.ajax.reload();
					}
				});
			}else{
				$(element).val(ori_value); // Change back to original status
			}
		}else{
			if(confirm("Record will be move to employee record once accept applicant and cannot be undo. Are you sure to accept applicant as employee?")){
				// move to employee record and delete interview record
				$.post(base_url + "interview/move_to_employee", { 'interview_id': interview_id }, function(data, status){
					var response = JSON.parse(data);

					alert(response.msg);

					if(response.result == 0){
						$(element).val(ori_value); // Change back to original status
					}else{
						location.reload();
					}
				})
			}else{
				$(element).val(ori_value); // Change back to original status
			}
		}
	}

	function preview_offer_letter(applicant_id){

        $.post(base_url + "interview/view_offer_letter", { 'applicant_id': applicant_id }, function(data, status){
            var response = JSON.parse(data);

            window.open(
                response.pdf_link,
                '_blank' // <- This is what makes it open in a new window.
            );
        });
    }
</script>