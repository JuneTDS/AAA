<link href="<?= base_url() ?>node_modules/jquery-datatables/media/css/jquery.dataTables.min.css" rel="stylesheet">

<script src="<?= base_url() ?>node_modules/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>node_modules/jquery-datatables-bs3/assets/js/datatables.js"></script>

<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div style="text-align:center;">
			<div style="display:inline-block;" class="pull-left">
				<a id="prevBtn" style="cursor: pointer;" onclick="getNewIndexAndRender('prev')">
		          <span class="glyphicon glyphicon-menu-left"></span>
		        </a>
			</div>
			<div style="display:inline-block;">
				<h3 style="margin: 0px;" id="display_month"></h3>
				<input id="hidden_display_month" type="hidden" value=""/>
			</div>
			<div style="display:inline-block;" class="pull-right">
				<a id="nextBtn" style="cursor: pointer;" onclick="getNewIndexAndRender('next')">
		          <span class="glyphicon glyphicon-menu-right"></span>
		        </a>
			</div>
		</div>
	</header>
	<div class="panel-body">
		<div class="col-md-12">
			<div id="action_section" style="text-align: center;"></div>
			
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
				<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
					<thead>
						<tr style="background-color:white;">
							<th class="text-left">Employee Name</th>
							<th class="text-left">Commission</th>
							<th class="text-left">Salary Advancement</th>
							<th class="text-center">Unpaid Leave</th>
							<th class="text-center">Other Incentive</th>
							<th class="text-center">Annual Leave Left</th>
							<th class="text-center">Payslip</th>
						</tr>
					</thead>
					<tbody id="employee_payslip_list"></tbody>
				</table>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	var month_list = <?php echo json_encode($month_list); ?>;
	var this_month_date = generate_date('','now');

	if(!(month_list.length > 0)){
		month_list.push(this_month_date);
	}

	$(document).ready( function () {
		$('#datatable-default').DataTable();
	    getNewIndexAndRender("right");
	} );

	var resultBox = document.getElementById('display_month');
	var months 	  = month_list;
	var length 	  = months.length;
	var idx 	  = length - 1; // idx is undefined, so getNextIdx will take 0 as default
	var this_month_beg = new Date(this_month_date);

	// // get the month where it is same month with this month.
	// month_list.forEach(function(element, index){
	// 	var temp_date = new Date(element);
	// 	// console.log(temp_date, this_month_beg);
	// 	if(temp_date.getTime() == this_month_beg.getTime()){
	// 		idx = index;
	// 	}
	// });

	var getNextIdx = (idx = 0, length, direction) => {

	   switch (direction) {
	     case 'next': {
	     	// return (idx + 1) % length;
	     	if(months[idx + 1] == undefined){
				var selected_month_temp_date = generate_date(month_list[idx], "next");

				months.push(selected_month_temp_date);
	     	}

	     	return idx + 1;
	     };
	     case 'prev': {
	     	// return (idx == 0) && length - 1 || idx - 1;
	     	if(months[idx - 1] == undefined){
	     		var selected_month_temp = new Date(month_list[idx]);
				selected_month_temp.setMonth(selected_month_temp.getMonth() - 1);

				var selected_month_temp_date = generate_date(month_list[idx], "prev");

				months.unshift(selected_month_temp_date);

				return idx;
	     	}
	     	else{
	     		return idx - 1;
	     	}
	     };
	     default: return idx;
	   }
	}

	const getNewIndexAndRender = (direction) => {
	    idx = getNextIdx(idx, length, direction);

	    // if(idx == length-1){
	   		// $('#prevBtn').hide();
	     // 	$('#nextBtn').show();
	    // }
	    // else if(idx == 0){
	     	// $('#nextBtn').hide();
	     	// $('#prevBtn').show();
	    // }
	    // else{
	     	// $('#prevBtn').show();
	     	// $('#nextBtn').show();
	    // }

	    $('#hidden_display_month').val(months[idx]);
	    resultBox.innerHTML = moment(months[idx], 'Y-M-D').format('MMMM Y');

	    refresh_table_display(months[idx]);
	}

	function refresh_table_display(selected_month){
		$.post("payslip/getThisMonthPayslipList", { 'date': selected_month }, function(data, status){
	    	var info = JSON.parse(data);

	    	// var info = [];
			// console.log(info);

			$("#employee_payslip_list").empty();
			$("#action_section").empty();

			info.payslip.forEach(function(element){
				
				$('#employee_payslip_list').append(
					'<tr>' +
						'<td>'+ element.name +'</td>'+
						'<td>'+ element.commission +'</td>'+
						'<td>'+ element.salary_advancement +'</td>'+
						'<td>'+ element.unpaid_leave +'</td>'+
						'<td>'+ element.other_incentive +'</td>'+
						'<td>'+ element.remaining_al +'</td>'+
						'<td align="center"><button class="btn btn_blue" onclick="view_payslip('+ element.id +')">View</button></td>'+
					'</tr>'
				);
				// $('#datatable-default').DataTable().ajax.reload();
			});

			$('#action_section').append(
				'<div style="margin: 0.1%; text-align: center; display: inline-block;">' +
					'<a href=<?php echo base_url("payslip/set_bonus/"); ?>' + selected_month + ' class="btn btn_blue">Set Bonus</a>' +
				'</div>' +
				'<div style="margin: 0.1%; text-align: center; display: inline-block;">' +
					'<button class="btn btn_blue" onclick="generate_payslip(\'' + selected_month + '\')">Generate Payslip</button>' +
				'</div>' +
				'<div class="pull-right" style="margin-bottom: 1%; display: inline-block;">' +
					'<a href=<?php echo base_url("payslip/payslip_settings/"); ?> style="cursor: pointer; size: 10px; font-size: 180%">' +
						'<span class="glyphicon glyphicon-cog"></span>' +
					'</a>' +
				'</div>'
			);

			if(info.payslip.length == 0 || info.payslip.length == undefined){
				// $('#datatable-default').DataTable().ajax.reload();
				$('#datatable-default').DataTable().draw();
			}
        });
	}

	function view_payslip(payslip_id){
		// console.log(payslip_id);

		$.post("payslip/view_payslip", { 'payslip_id': payslip_id }, function(data, status){
			// console.log(data);
			var response = JSON.parse(data);

			window.open(
              	response.pdf_link,
              	'_blank' // <- This is what makes it open in a new window.
            );
		});
	}

	function generate_payslip(selected_month){
		$.post("payslip/generate_payslip", { 'selected_month': selected_month }, function(data, status){
            var selected_month = $('#hidden_display_month').val();

            if(data){
            	refresh_table_display(selected_month);
            }
        });
	}

	function generate_date(input_date, prev_next){
		var date = new Date(input_date);

		if(input_date == ''){
			date = new Date();
		}

		if(prev_next == "prev"){
			date.setMonth(date.getMonth() - 1);
		}
		else if(prev_next == "next"){
			date.setMonth(date.getMonth() + 1);
		}

		var output_date = date.getFullYear() + '-' + parseInt(date.getMonth() + 1) + '-' + '01';

		return output_date;
	}
</script>