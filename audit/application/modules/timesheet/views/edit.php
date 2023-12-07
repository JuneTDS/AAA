<script src="<?= base_url() ?>node_modules/handsontable/dist/handsontable.full.min.js"></script>
<link href="<?= base_url() ?>node_modules/handsontable/dist/handsontable.full.min.css" rel="stylesheet" media="screen">

<style>
	.highlight{
		color: black;
	}

	.handsontable .htCore .htDimmed {
	    background-color: #F2F2F2;
	    font-style: italic;
	}
</style>

<section class="panel" style="margin-top: 30px;">
	<div class="panel-body">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-1">
					<span>Staff: </span>
				</div>
				<div class="col-md-10">
					<span><?php echo $timesheet[0]->employee_name ?></span>
				</div>
			</div>
			<div class="row">
				<div class="col-md-1">
					<span>Month: </span>
				</div>
				<div class="col-md-10">
					<span><?php echo date('M-y', strtotime($timesheet[0]->month)) ?></span>
				</div>
			</div>
			<div class="row">
				<div class="col-md-1">
					<span>Status: </span>
				</div>
				<div class="col-md-5">
					<span><?php echo $timesheet_status_name ?></span>
				</div>
				<div class="col-md-6" style="text-align: right;">
					<button class="btn" onclick="insert_row()">Insert Row</button>
				</div>
			</div>
			<div class="row">
				<label></label>
			</div>
			<div style="height: 75%; overflow: auto;">
				<div id="timesheet_excel"></div>
			</div>

			<div class="form-group row">
				<span style="font-size:12px; margin-left: 1%">This timesheet status is <?php echo strtolower($timesheet_status_name) ?>.</span>
			</div>

			<div class="form-group row">
				<div class="col-md-5">
					<?php if($timesheet[0]->status_id == 1){
						echo '<button class="btn btn-dark" onclick="submit_timesheet()">Submit</button>';
					} ?>
				</div>
				<div class="col-md-7" style="text-align: right">
			    	<?php echo '<a href="'.base_url().'timesheet/index" class="btn pull-right btn_cancel" style="margin:0.5%; cursor: pointer;">Cancel</a>';

			    		echo '<button class="btn btn_blue pull-right" style="margin:0.5%" onclick="save_timesheet()">Save</button>';
			    	?>
			    </div>
            </div>
		</div>
	</div>
</section>

<script type="text/javascript">
	var timesheet_id = <?php echo $timesheet[0]->id ?>;
	var header = '<?php echo json_encode($header); ?>';
	var array_header_col_readonly = '<?php echo json_encode($array_header_col_readonly); ?>';
	var data = [];

	var timesheet_content = '<?php echo $timesheet[0]->content; ?>';
	// console.log(timesheet_content);
	
	var container = document.getElementById('timesheet_excel');

	function get_data(){
		return [];
		// return [
			// { Activities: 'Reading file', 1: "8.0", 2: "1.0", 3: "4.0" },
			// { Activities: 'Reading file', 1: "-", 	2: "4.0", 3: "4.0"},
			// { Activities: 'Reading file', 1: "8.0", 2: "2.0", 3: "4.0"},
			// { Activities: 'Reading file', 1: "4.0", 2: "2.0", 3: "8.0"}
		// ];
	}

	var hot = new Handsontable(container, {
	  data: get_data(),
      minRows: 5,
      minCols: (JSON.parse(header)).length,
	  rowHeaders: true,
	  colHeaders: true,
	  filters: true,
	  contextMenu: true,
	  dropdownMenu: true,
	  colHeaders: JSON.parse(header),
	  columns: JSON.parse(array_header_col_readonly),
	  columnSummary: [
	    {
	      destinationRow: 0,
	      destinationColumn: 0,
	      reversedRowCoords: true,
	      type: 'sum',
	      forceNumeric: true
	    }
	  ],
	  afterChange: function(changes, src){
	  	// console.log(this);
	  	temp_all_data = this.getData();
	  	data = [];
	  	// console.log(data);

  	 	if(!changes){
  	 		return;
  	 	}

  	 	$.each(changes, function(index, element) {

  	 		console.log(index, element);

  	 		var row = changes[0][0];
		  	var col = changes[0][1];

		  	/* ------------------------------------------------------------------------------------------------------------ */
		  	var get_this_row_data = hot.getDataAtRow(row);
		  	var total_current = 0;

		  	var current_col_index = GetColFromName('current');	// get index of column named "current" 
		  	var bf_col_index   	  = GetColFromName('b/f');	// get index of column named "b/f" 
		  	var total_col_index   = GetColFromName('total');	// get index of column named "total" 

		  	// calculate total number for "current" cell
		  	get_this_row_data.forEach(function(item, index){
		  		if(index < current_col_index){
			  		var float_num = parseFloat(item);

			  		if(!isNaN(float_num)){
			  			total_current += float_num;
			  		}
		  		}
		  	});

	  		temp_all_data[row][current_col_index] = set_string(total_current);	// set new value for "current" cell

	  		// Calculate total number for "total" cell
			if(!isNaN(parseFloat(get_this_row_data[bf_col_index]))){
				temp_all_data[row][total_col_index] = set_string(total_current + parseFloat(get_this_row_data[bf_col_index]));
			}else{
				temp_all_data[row][total_col_index] = set_string(total_current);
			}

		  	temp_all_data.forEach(function(row, index){
		  		var obj = [];

		  		row.forEach(function(item, index){
		  			obj[hot.getColHeader()[index]] = item;
		  		});

		  		data.push(obj);
	  		});

	  		/* ------------------------------------------------------------------------------------------------------------ */
  			// Calculate total of all current
	  		var overall_total_current = 0;

	  		for(i=0; i<data.length - 1; i++){
	  			// console.log(data[i]["current"]);
	  			var float_num = parseFloat(data[i]["current"]);

	  			if(!isNaN(float_num)){
		  			overall_total_current += float_num;
		  		}
	  		}

	  		data[data.length - 1]["current"] = set_string(parseFloat(overall_total_current));

		  	/* ------------------------------------------------------------------------------------------------------------ */
		  	// Calculate total by column
		  	if(col != "Activities"){
			  	var get_this_col_data = hot.getDataAtCol(col);
			  	var col_length 		  = get_this_col_data.length;
			  	var total_col_value   = 0;

			  	get_this_col_data.forEach(function(item, index){
			  		if(index < col_length - 1){
			  			var float_num = parseFloat(item);

				  		if(!isNaN(float_num)){
				  			total_col_value += float_num;
				  		}
			  		}
			  	})
			  	
			  	data[data.length - 1]["Activities"] = "Total";	// Set "Total"

			  	if(col!= 'b/f'){
			  		data[col_length-1][col] = set_string(total_col_value);	// set values in last row
			  		data[row][col] = set_string(parseFloat(temp_all_data[row][col]));	// change number to 0.0 format

			  		data[col_length-1][get_this_row_data.length-1] = 0;
			  	}else{
			  		var b_f_col_num = hot.getDataAtCol(bf_col_index).length;	// get col length

			  		data[row][col] = set_string(parseFloat(temp_all_data[row][bf_col_index]));	// change and set number to 0.0 format for 'b/f' column

			  		data[b_f_col_num - 1]['b/f'] = '';	// let last row of 'b/f' column to be empty
			  		data[b_f_col_num-1]['total'] = '';	// let last row of 'total' column to be empty
			  	}
			}

		  	hot.loadData(data);
  	 	});
	  }
	});

	if(timesheet_content != ''){
		hot.loadData(change_array_format(JSON.parse(timesheet_content)));
	}

	if('<?php echo $this->user_group_name; ?>' == 'admin'){
		hot.updateSettings({
		    readOnly: true, // make table cells read-only
		    contextMenu: false, // disable context menu to change things
		    disableVisualSelection: true, // prevent user from visually selecting
		    manualColumnResize: false, // prevent dragging to resize columns
		    manualRowResize: false, // prevent dragging to resize rows
		    comments: false // prevent editing of comments
		});
	}
	

	// get index of column by name. Eg. find the header column with name "current" and get the column index
	function GetColFromName(name)
	{
	    var n_cols  =   hot.countCols();
	    var i       =   1;

	    for (i=1; i<=n_cols; i++)
	    {
	        if (name.toLowerCase() == (hot.getColHeader(i)).toLowerCase()) {
	            return i;
	        }
	    }
	    return -1; //return -1 if nothing can be found
	}

	function set_string(number){
		if(number == 0){
			return '0.0';
		}
		else{
			if(!isNaN((number.toFixed(1)).toString())){
				return (number.toFixed(1)).toString();
			}
			else{
				return '';
			}
		}
	}

	function save_timesheet(){
		var temp_all_data = hot.getData();

		$.post("<?php echo site_url('timesheet/save_timesheet'); ?>", { data: temp_all_data, timesheet_id: timesheet_id }, function(result, status){
			// console.log(result);
			if(result){
				alert("Successfully saved timesheet.");
			}
		});
	}

	function change_array_format(temp_all_data){
		// console.log($('#example'));
		var data = [];

		temp_all_data.forEach(function(row, index){
	  		var obj = [];

	  		row.forEach(function(item, index){
	  			obj[hot.getColHeader()[index]] = item;
	  		});

	  		data.push(obj);
  		});
  		return data;
	}

	function submit_timesheet(){
		if(confirm("Confirm to submit timesheet?")){
			$.post("<?php echo site_url('timesheet/submit_timesheet'); ?>", { timesheet_id: timesheet_id }, function(result, status){
				if(result){
					alert("Successfully saved timesheet.");
					location.reload();
				}
			});
		}
	}
	function insert_row(){
		// console.log(hot.countRows());
		hot.alter('insert_row', hot.countRows() - 1, 1);
	}
</script>