<link href="<?= base_url() ?>node_modules/jquery-datatables/media/css/jquery.dataTables.min.css" rel="stylesheet">

<script src="<?= base_url() ?>node_modules/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>node_modules/jquery-datatables-bs3/assets/js/datatables.js"></script>

<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions">
			<!-- <a class="create_client themeColor_blue" href="timesheet/create" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_blue" style="font-size:16px;height:45px;"></i> Create Timesheet</a> -->
		</div>
		<h2></h2>
	</header>
	<div class="panel-body">
		<div class="col-md-12">
			<div class="row pull-right">
				<label>Year: </label>
				<?php echo form_dropdown('timesheet_year', $years, '', 'onchange="selected_year()"');?>
				<label>Month: </label>
				<?php echo form_dropdown('timesheet_month', '', '', 'onchange="selected_month()"');?>
			</div>

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
								<th class="text-left">Employee</th>
								<th class="text-left">Status</th>
								<!-- <th class="text-left">Month</th> -->
							</tr>
						</thead>
						<tbody id="timesheet_list"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	var base_url = '<?php echo base_url(); ?>';

	$(document).ready( function () {
	    $('#datatable-default').DataTable( {
	    } );

		update_months();
	    // get_timesheet_list();
	});

	function selected_year(){
		update_months();
		// get_timesheet_list();
	}

	function selected_month(){
		// console.log($element);
		get_timesheet_list();
	}

	function update_months(){
		var selected_year = $('select[name="timesheet_year"]').val();	// get selected month
		var all_months_list = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	    
	    $.post(base_url + 'timesheet/get_month', { 'year': selected_year }, function(data, status){
	    	var months = JSON.parse(data);

	    	$('select[name="timesheet_month"]').find('option').remove();	// clear all month options

			months.forEach(function(data, index){
				$('select[name="timesheet_month"]').append('<option value="' + data['month'] + '">' + all_months_list[data['month']-1] +'</option>');
			})

			get_timesheet_list();
        });

        // return true;
	}

	function get_timesheet_list(){
		var selected_year  = $('select[name="timesheet_year"]').val();	// get selected month
		var selected_month = $('select[name="timesheet_month"]').val();	// get selected month

		// console.log(selected_month);

		$.post(base_url + 'timesheet/get_list_from_year_month', { 'year': selected_year, 'month': selected_month }, function(data, status){
	    	var data_list = JSON.parse(data);

	    	data_list.forEach(function(data){
	    		$("#timesheet_list").empty();
	    		
		    	$.post(base_url + 'timesheet/timesheet_tr_partial', { data: data }, function(data, status){
		    		$('#timesheet_list').append(data);
		    	});
		    })
	    	// console.log(data_list);

	  //   	$('select[name="timesheet_month"]').find('option').remove();	// clear all month options

			// months.forEach(function(data, index){
			// 	$('select[name="timesheet_month"]').append('<option value="' + data['month'] + '">' + all_months_list[data['month']-1] +'</option>');
			// })
        });
	}

</script>