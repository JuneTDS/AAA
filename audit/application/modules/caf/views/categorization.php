<link rel="stylesheet" href="<?= base_url()?>application/modules/caf/css/layout_tree_structure.css" />
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" />
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script> 

<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/dataTables.checkboxes.min.js"></script>
<link rel="stylesheet" href="<?=base_url()?>assets/vendor/jquery-datatables/media/css/dataTables.checkboxes.css" />
<script src="<?=base_url()?>assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
<script src="<?=base_url()?>assets/vendor/jquery-datatables/media/js/natural.js"></script>

<!-- bootstrap-switch -->
<link href="<?= base_url() ?>node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" media="all" rel="stylesheet" type="text/css"/>
<script src="<?= base_url() ?>node_modules/bootstrap-switch/dist/js/bootstrap-switch.min.js" type="text/javascript"></script>

<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>
<script src="<?= base_url() ?>node_modules/moment/moment.js"></script>
<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/bootbox/bootbox.min.css" /> -->

<link rel="stylesheet" href="<?= base_url() ?>node_modules/jstree/dist/themes/default/style.min.css" />
<!-- <link rel="stylesheet" href="<?= base_url() ?>node_modules/jstree/dist/themes/default/custom-style.css" /> -->
<script type="text/javascript" src="<?= base_url() ?>node_modules/jstree/dist/jstree.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>node_modules/jstree/src/misc.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/vendor/jstree-grid-master/jstreegrid.js"></script>


<style type="text/css">
	.fileUpload {
	  position: relative;
	  overflow: hidden;
	  margin-top: 0;
	}

	.fileUpload input.upload_TB,  .fileUpload input.upload_LY_TB{
	  position: absolute;
	  top: 0;
	  right: 0;
	  margin: 0;
	  padding: 0;
	  font-size: 20px;
	  cursor: pointer;
	  opacity: 0;
	  filter: alpha(opacity=0);
	  width: 100%;
	}

	.jstree-grid-wrapper a
	{
		color: black !important;
	}

	.vakata-context { 
	    z-index:999 !important; 
	}

	.acenter
	{
	    /*text-align: right;*/
	    color: green;
	}

	div.jstree-grid-cell-root-Uncategoried_Treeview
	{
		line-height: 24px;
		height: 24px;
	}

	div.jstree-grid-cell-root-Categoried_Treeview
	{
		line-height: 24px;
		height: 24px;
	}

	.jstree-hidden{ display:none; } 
</style>

<section role="main" class="content_section" style="margin-left:0;">
	<input type="hidden" name="assignment_id" id="assignment_id" value="<?= $assignment_id ?>">

	<?php
      if($show_ly_TB_btn == 1)
      {
        echo 
        '<div class="col-xs-11">
          <div class="fileUpload btn btn-primary">
            <span>Last Year Trial Balance</span>
            <input type="file" class="upload_LY_TB" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onclick="this.value=null"/>
          </div>
        </div>';

        echo '<div class="col-xs-12"><br/></div>';
        // echo '<label class="col-xs-1"><br/></label>';
      }
    ?>

	<div class="col-xs-12" style="overflow: 	visible;	">
      <div class="fileUpload btn btn-primary">
        <span>Upload Trial Balance</span>
        <input type="file" class="upload_TB" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onclick="this.value=null"/>
      </div>
      <i id="trial_balance_info_btn" class="fa fa-info-circle" aria-hidden="true" style="font-size: 12pt; margin: 10px; cursor:pointer;" data-name="trial_balance_info" data-toggle="tooltip" data-trigger="hover" data-placement="right" data-original-title="Click to see or download trial balance format" onclick="show_trial_b_info()"></i>
    </div>

    <div class="col-xs-12" style="padding-top: 20px;">
		<div class="wrapper">
		  	<div id="sidebar" class="box" style="min-width:20%; height:77vh; overflow-y: scroll;">
		      	<div style="display: inline-block;">
		       		<h4>Account to be classified</h4>
		      	</div>
		      	<div style="display: inline-block; float:right; padding: 10px;">
		        	<a id="CreateAccount" style="cursor: pointer"><span class="glyphicon glyphicon-plus fa-fw"></span> Create Account</a>
		      	</div>

		      	<hr class="divider"/>

		     	<div><input type="text" id="uncategorized_account_search" class="form-control" placeholder="Keyword Search"></input></div>
		      	<br/>
		      	<div id="Uncategoried_Treeview"></div>
		  	</div>
		  	<div class="handler"></div>
		  	<div id="main" class="box" style="min-width:35%; height:77vh; overflow-y: scroll;">
			    <div style="display: inline-block;">
			        <h4>Classified Account</h4>
			    </div>
			      <div style="display: inline-block; float:right; padding: 10px;">
			      	<button class="btn btn-success" onclick="calculate_parents_val()">Recalculate parents values</button>
			      	<button class="btn btn-primary" onclick="clear_this_year_val()">Clear CY</button>
			      	<button class="btn" onclick="clear_last_year_parent_val()">Clear LY</button>
			        <!-- <a id="CreateMainCategory" style="cursor: pointer"><span class="glyphicon glyphicon-plus fa-fw"></span> Create Main Category</a> -->
			      </div>
			    <hr class="divider" />
			    <div><input type="text" id="categorized_account_search" class="form-control" placeholder="Keyword Search"></input></div>
			    <br>
			    <div id="Categoried_Treeview" style="padding-right: 20px;"></div>
			</div>
		</div>
	</div>
		
</section>
<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>

<!-- Modal for create account form for uncategorised account -->
<div class="modal fade" id="create_account_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>CREATE ACCOUNT</strong>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
      </div>
      <div class="modal-body">
        <input id="uncategorised_new_account" class="form-control" type="text" placeholder="New account name">
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="btn_create_account" type="button" class="btn btn-primary" onclick="create_uncategorized_account()">Insert</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal for create account form for uncategorised account -->

<!-- Modal to display trial balance error message -->
<div class="modal fade" id="trial_b_error_msg_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
        <h4 class="modal-title" id="myModalLabel">Trial balance template info</h4>
      </div>
      <div class="modal-body">
        <h5>Set 3 columns same as the image below or download from <a style="cursor:pointer;" onclick="download_trial_b_template()">here</a>.</h5>
        <br/>
        <img src="<?= base_url();?>img/Trial Balance Sample.PNG" alt="Trial balance - template" height="75%" width="75%">
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">OK</button>
        <!-- <button id="save_trial_b_error_msg" type="button" class="btn btn-primary">Save</button> -->
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display trial balance error message -->

<!-- Modal to display sub list so that user can choose -->
<div class="modal fade" id="sub_account_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>SUB ACCOUNT LIST</strong>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="btn_insert_sub" type="button" class="btn btn-primary">Insert</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display sub list so that user can choose -->

<!-- Modal to display edit/change sub list WITHOUT INPUT DESCRIPTION NAME so that user can choose -->
<div class="modal fade" id="edit_account_code_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <strong>ACCOUNT CODE LIST</strong>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
        <button id="btn_edit_sub" type="button" class="btn btn-primary">Insert</button>
      </div>
    </div>
  </div>
</div>
<!-- END OF Modal to display edit/change sub list WITHOUT INPUT DESCRIPTION NAME so that user can choose -->

<script type="text/javascript">
	var pathArray = location.href.split( '/' );
	var protocol = pathArray[0];
	var host = pathArray[2];
	var folder = pathArray[3];

	var base_url = '<?php echo base_url(); ?>';

	var active_tab = <?php echo json_encode(isset($active_tab)?$active_tab:"auditor_list") ?>;
	var auditor_list = <?php echo json_encode(isset($auditor_list)?$auditor_list:"") ?>;
	var bank_confirm_setting = <?php echo json_encode(isset($bank_confirm_setting)?$bank_confirm_setting:"") ?>;
	
	var read_extract_excel_url = "<?= base_url();?>caf/read_extract_excel";
	var uncategoriedData_url = "<?= base_url();?>caf/uncategoriedData";
	var categoriedDefaultData_url = "<?= base_url();?>caf/categoriedDefaultData";
	var partial_sub_account_list_url = "<?= base_url();?>caf/partial_sub_account_list";
	var partial_edit_account_code_list_url = "<?= base_url();?>caf/partial_edit_account_code_list";
	var save_categorized_uncategorized_account_url = "<?= base_url();?>caf/save_categorized_uncategorized_account";
	var get_trial_balance_template_excel_url = "<?= base_url();?>caf/get_trial_balance_template_excel";
	var check_previous_update_url 			 = "<?= base_url();?>caf/check_previous_update";

	var main_account_code_list = <?php echo json_encode($main_account_code_list); ?>;
	var initial_categorized = <?php echo json_encode($initial_categorized); ?>;

  	main_account_code_list = main_account_code_list.map(x => x['account_code']);

	var pv_index_tab_aktif;
	var startDate = new Date();

	function ajaxCall() {
	    this.send = function(data, url, method, success, type) {
	        type = type||'json';
	        //console.log(data);
	        var successRes = function(data) {
	            success(data);
	        };

	        var errorRes = function(e) {
	          //console.log(e);
	          alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
	        };
	        $.ajax({
	            url: url,
	            type: method,
	            data: data,
	            success: successRes,
	            error: errorRes,
	            dataType: type,
	            timeout: 60000
	        });

	    }

	}



</script>



<script src="<?= base_url()?>application/modules/caf/js/categorization.js" charset="utf-8"></script>