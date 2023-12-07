<!-- bootstrap-switch -->
<link href="<?= base_url() ?>node_modules/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" media="all" rel="stylesheet" type="text/css"/>
<script src="<?= base_url() ?>node_modules/bootstrap-switch/dist/js/bootstrap-switch.min.js" type="text/javascript"></script>

<!-- datepicker -->
<script src="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" />

<!-- TOASTR -->
<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script> 


<section role="main" class="content_section" style="margin-left:0;">
    <section class="panel" style="margin-top: 0px;">
        <div id="fs_company_info_form" style=" margin: 2%;">
            <form id="form_fs_company_info" method="POST"> 
                <input type="hidden" name="fs_company_info_id" id="fs_company_info_id" value="<?=$fs_report_details[0]['id'];?>">

                <div class="form-group">
                    <label class="col-xs-4" for="w2-username">Report Date: </label>
                    <div class="col-xs-8">
                        <div class="input-group mb-md" style="width: 200px;">
                            <span class="input-group-addon">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                            <input type="text" class="form-control valid datepicker_input" id="report_date" name="report_date" data-date-format="dd MM yyyy" data-plugin-datepicker="" value="<?=$fs_report_details[0]['report_date'];?>" placeholder="DD MM YYYY">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-4">Report is Audited: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 200px;" >
                            <input type="checkbox" name="hidden_is_audited_checkbox" <?=$fs_report_details[0]['is_audited']?'checked':'';?> />
                            <input type="hidden" name="is_audited_checkbox" value="<?=$fs_report_details[0]['is_audited']?>"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-4">Group: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 200px;">
                            <?php
                                echo form_dropdown('group_type', $group_type_list, isset($fs_report_details[0]['group_type'])?$fs_report_details[0]['group_type']: '', 'id="group_type" onchange="show_is_group_consolidated()" class="fs_cp_status"');
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group group_consolidated" <?=($fs_report_details[0]['group_type'] == "2" || $fs_report_details[0]['group_type'] == "3")?'':'style="display:none;"';?>>
                    <label class="col-xs-4">Group is consolidated: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 200px;" >
                            <input type="checkbox" name="hidden_is_consolidated_checkbox" <?=$fs_report_details[0]['is_group_consolidated']?'checked':'';?> />
                            <input type="hidden" name="is_group_consolidated" value="<?=$fs_report_details[0]['is_group_consolidated']?>"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-4">First set of report: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 200px;" >
                            <input type="checkbox" name="hidden_first_set_checkbox" <?=$fs_report_details[0]['first_set']?'checked':'';?> />
                            <input type="hidden" name="first_set_checkbox" value="<?=$fs_report_details[0]['first_set']?>"/>
                        </div>
                    </div>
                </div>

                <hr/>

                <div class="form-group"><label class="col-xs-4" for="w2-username"><strong>This Final Year End: </strong></label></div>
                <div class="form-group">
                    <label class="col-xs-4" for="w2-username">Begining of This Final Year End: </label>
                    <div class="col-xs-8">
                        <div class="input-group mb-md" style="width: 200px;">
                            <span class="input-group-addon">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                            <input type="text" class="form-control valid datepicker_input" id="current_fye_begin" name="current_fye_begin" data-date-format="dd MM yyyy" data-plugin-datepicker="" value="<?=$fs_report_details[0]['current_fye_begin'];?>" placeholder="DD MM YYYY">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4" for="w2-username">End of This Final Year End: </label>
                    <div class="col-xs-8">
                        <div class="input-group mb-md" style="width: 200px;">
                            <span class="input-group-addon">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                            <input type="text" class="form-control valid datepicker_input" id="current_fye_end" name="current_fye_end" data-date-format="dd MM yyyy" data-plugin-datepicker="" value="<?=$fs_report_details[0]['current_fye_end'];?>" placeholder="DD MM YYYY">
                        </div>
                    </div>
                </div>
                
                <div class="form-group last_year_end_display" style="margin-top: 20px;"><label class="col-xs-4" for="w2-username"><strong>Last Final Year End: </strong></label></div>
                <div class="form-group last_year_end_display" style="margin-top: 20px;">
                    <label class="col-xs-4" for="w2-username">Begining of Last Final Year End: </label>
                    <div class="col-xs-8">
                        <div class="input-group mb-md" style="width: 200px;">
                            <span class="input-group-addon">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                            <input type="text" class="form-control valid datepicker_input" id="last_fye_begin" name="last_fye_begin" data-date-format="dd MM yyyy" data-plugin-datepicker="" value="<?=$fs_report_details[0]['last_fye_begin'];?>" placeholder="DD MM YYYY">
                        </div>
                    </div>
                </div>
                <div class="form-group last_year_end_display">
                    <label class="col-xs-4" for="w2-username">End of Last Final Year End:</label>
                    <div class="col-xs-8">
                        <div class="input-group mb-md" style="width: 200px;">
                            <span class="input-group-addon">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                            <input type="text" class="form-control valid datepicker_input" id="last_fye_end" name="last_fye_end" data-date-format="dd MM yyyy" data-plugin-datepicker="" value="<?=$fs_report_details[0]['last_fye_end'];?>" placeholder="DD MM YYYY">
                        </div>
                    </div>
                </div>

                <hr/>

                <div class="form-group">
                    <label class="col-xs-4">Company changed name during the year: </label>
                    <div class="col-xs-2">
                        <div class="input-group" style="width: 200px;" >
                            <input type="checkbox" name="hidden_change_com_name_checkbox" <?=(!is_null($fs_report_details[0]['old_company_name']) && !empty($fs_report_details[0]['old_company_name']))?'checked':'';?> />
                            <input type="hidden" name="change_com_name_checkbox" value="<?=(!is_null($fs_report_details[0]['old_company_name']) && !empty($fs_report_details[0]['old_company_name']))?1:0;?>"/>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-md prev_com_name" <?=(!is_null($fs_report_details[0]['old_company_name'])&& !empty($fs_report_details[0]['old_company_name']))?'':'style="display:none;"';?> >
                    <label class="col-xs-4"></label>
                    <div class="col-xs-6">
                        <div>
                            <label>Previous Company Name:</label>
                            <input type="text" class="form-control" name="prev_com_name" value="<?=$fs_report_details[0]['old_company_name']?>"/>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-md prev_com_name" <?=(!is_null($fs_report_details[0]['old_company_name'])&& !empty($fs_report_details[0]['old_company_name']))?'':'style="display:none;"';?> >
                    <label class="col-xs-4"></label>
                    <div class="col-xs-6">
                        <div>
                            <label>Date of resolution for change of name:</label>
                            <!-- <div class="col-xs-8"> -->
                                <div class="input-group mb-md" style="width: 200px;">
                                    <span class="input-group-addon">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                    <input type="text" class="form-control valid datepicker_input" id="date_resol_change_com_name" name="date_resol_change_com_name" data-date-format="dd MM yyyy" data-plugin-datepicker="" value="<?=$fs_report_details[0]['date_of_resolution_for_change_of_name'];?>" placeholder="DD MM YYYY">
                                </div>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-4">Company is going to be liquidated:</label>
                    <div class="col-xs-2">
                        <div class="input-group" style="width: 200px;" >
                            <input type="checkbox" name="hidden_company_liquidated_checkbox" <?=(!is_null($fs_report_details[0]['company_liquidated']) && !empty($fs_report_details[0]['company_liquidated']))?'checked':'';?> />
                            <input type="hidden" name="company_liquidated" value="<?=(!is_null($fs_report_details[0]['company_liquidated']) && !empty($fs_report_details[0]['company_liquidated']))?1:0;?>"/>
                        </div>
                    </div>
                </div>
                <!-- <div class="form-group">
                    <label class="col-xs-4">Director Signature 1: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 400px;">
                            <input type="text" class="form-control" style="width: 400px;" name="director_signature_1" value="<?=$fs_report_details[0]['director_signature_1']?>"/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4">Director Signature 2: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 400;">
                            <input type="text" class="form-control" style="width: 400px;" name="director_signature_2" value="<?=$fs_report_details[0]['director_signature_2']?>"/>
                        </div>
                    </div>
                </div> -->
                <div class="form_director_signature_1 form-group">
                    <label class="col-xs-4" for="w2-DS1">Director Signature 1:</label>
                    <div class="col-xs-6">
                        <div class="director_signature_1_group" style="float: left;margin-right: 10px">
                            <select id="director_signature_1" class="form-control director_signature_1" style="text-align:right !important; width: 400px;" name="director_signature_1">
                                <option value="0">Select Director Signature 1</option>
                            </select>
                        </div>
                        <input type="button" class="btn btn-primary btnShowAllDirectorSig1" onclick="showAllDirectorSig1(this);" value='Show All' style="float: left;">
                    </div>
                    
                </div>
                <div class="form_director_signature_2 form-group">
                    <label class="col-xs-4" for="w2-DS2">Director Signature 2:</label>
                    <div class="col-xs-6">
                        <div class="director_signature_2_group" style="float: left;margin-right: 10px">
                            <select id="director_signature_2" class="form-control director_signature_2" style="text-align:right; width: 400px;" name="director_signature_2" disabled="disabled">
                                <option value="0">Select Director Signature 2</option>
                            </select>
                        </div>
                        <input type="button" class="btn btn-primary btnShowAllDirectorSig2" onclick="showAllDirectorSig2(this);" value='Show All' style="float: left;">
                    </div>
                </div>
                <hr/>

                <div class="form-group last_year_fc">
                    <input type="hidden" name="fs_fp_currency_id" value="<?php echo isset($fs_fp_currency_details[0])?$fs_fp_currency_details[0]['id']:''; ?>">
                    <label class="col-xs-4">Last Year Functional currency: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 200px;">
                            <?php
                                echo form_dropdown('last_year_fc_currency_id', $currency_list, isset($fs_fp_currency_details[0]['last_year_fc_currency_id'])?$fs_fp_currency_details[0]['last_year_fc_currency_id']: '', 'id="last_year_fc_currency_id" onchange="show_hide_reason_of_changing()" class="fs_cp_currency_status"');
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-4">Current Year Functional currency: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 200px;">
                            <?php
                                echo form_dropdown('current_year_fc_currency_id', $currency_list, isset($fs_fp_currency_details[0]['current_year_fc_currency_id'])?$fs_fp_currency_details[0]['current_year_fc_currency_id']: '', 'id="current_year_fc_currency_id" onchange="show_hide_reason_of_changing()" class="fs_cp_currency_status"');
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group reason_changing_fc" 
                    <?php 
                        if (isset($fs_fp_currency_details[0])) {
                            if(($fs_fp_currency_details[0]['last_year_fc_currency_id'] == $fs_fp_currency_details[0]['current_year_fc_currency_id']) || empty($fs_fp_currency_details[0]['last_year_fc_currency_id'])) 
                            { echo "style='display:none';"; } 
                        }
                        else
                        {
                            echo "style='display:none';"; 
                        }
                        
                    ?>
                >
                    <label class="col-xs-4">Reason of changing functional currency:</label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 75%;">
                            <textarea id="reason_of_changing_fc" class="form-control" name="reason_of_changing_fc" style="width:100%; height: 80px;"><?php 
                                if(!empty($fs_fp_currency_details[0]['reason_of_changing_fc']))
                                {   
                                    echo $fs_fp_currency_details[0]['reason_of_changing_fc']; 
                                }
                                else
                                {   echo '';    }
                            ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group last_year_pc">
                    <label class="col-xs-4">Last Year Presentation currency: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 200px;">
                            <?php
                                echo form_dropdown('last_year_pc_currency_id', $currency_list, isset($fs_fp_currency_details[0]['last_year_pc_currency_id'])?$fs_fp_currency_details[0]['last_year_pc_currency_id']: '', 'id="last_year_pc_currency_id" onchange="show_hide_reason_of_changing()" class="fs_cp_currency_status"');
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-4">Current Year Presentation currency: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 200px;">
                            <?php
                                echo form_dropdown('current_year_pc_currency_id', $currency_list, isset($fs_fp_currency_details[0]['current_year_pc_currency_id'])?$fs_fp_currency_details[0]['current_year_pc_currency_id']: '', 'id="current_year_pc_currency_id" onchange="show_hide_reason_of_changing()" class="fs_cp_currency_status"');
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group reason_changing_fc_pc" 
                    <?php 

                        if (isset($fs_fp_currency_details[0])) {
                            if(($fs_fp_currency_details[0]['current_year_fc_currency_id'] == $fs_fp_currency_details[0]['current_year_pc_currency_id']) || empty($fs_fp_currency_details[0]['current_year_pc_currency_id']))
                            { echo "style='display:none';"; } 
                        }
                        else
                        {
                            echo "style='display:none';"; 
                        }
                        
                    ?>
                >
                    <label class="col-xs-4">Reason of changing functional currency/presentation currency:</label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 75%;">
                            <textarea id="reason_changing_fc_pc" class="form-control" name="reason_changing_fc_pc" style="width:100%; height: 80px;"><?php 
                                if(!empty($fs_fp_currency_details[0]['reason_changing_fc_pc']))
                                {   
                                    echo $fs_fp_currency_details[0]['reason_changing_fc_pc']; 
                                }
                                else
                                {   echo '';    }
                            ?></textarea>
                        </div>
                    </div>
                </div>

                <hr/>
                <div class="form-group fs_accounting_standard_used">
                    <label class="col-xs-4">Accounting Standard Used: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 200px;">
                            <?php
                                echo form_dropdown('accounting_standard_used', $accounting_standard_list, isset($fs_report_details[0]['accounting_standard_used'])?$fs_report_details[0]['accounting_standard_used']: '', 'id="accounting_standard_used" class="fs_cp_status"');
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-4">Act Applicable: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 200px;">
                            <?php
                                echo form_dropdown('act_applicable_type', $act_applicable_list, isset($fs_report_details[0]['act_applicable_type'])?$fs_report_details[0]['act_applicable_type']: '', 'id="act_applicable_type" class="fs_cp_status"');
                            ?>
                        </div>
                    </div>
                </div>

                <!-- <hr/>

                <div class="form-group">
                    <label class="col-xs-4">Is Prior Year Amount Restated: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 200px;">
                            <?php
                                echo form_dropdown('is_prior_year_amount_restated', $is_prior_year_restated_dp, isset($fs_report_details[0]['is_prior_year_amount_restated'])?$fs_report_details[0]['is_prior_year_amount_restated']: '', 'id="is_prior_year_amount_restated"');
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group show_effect_of_restatement_since" <?=$fs_report_details[0]['is_prior_year_amount_restated']?'':'style="display:none;"';?>>
                    <label class="col-xs-4">Effect of Restatement Since: </label>
                    <div class="col-xs-8">
                        <div class="input-group" style="width: 200px;"> -->
                            <!-- <select id="effect_of_restatement_since" name="effect_of_restatement_since" class="browser-default custom-select"> -->
                                <!-- <option value="1">Yes</option>
                                <option value="0" selected>No</option> -->
                            <!-- </select> -->
                            <!-- <?php
                                echo form_dropdown('effect_of_restatement_since', $effect_of_restatement_since_dp, isset($fs_report_details[0]['effect_of_restatement_since'])?$fs_report_details[0]['effect_of_restatement_since']: '', 'id="effect_of_restatement_since"');
                            ?> -->
                       <!--  </div>
                    </div>
                </div> -->

                <!-- Can be deleted -->
                <!-- <div class="form-group">
                    <div class="col-sm-12">
                        <input type="button" class="btn btn-primary submit_company_particular" id="submit_company_particular" value="Save" style="float: right; margin-bottom: 20px; margin-top: 20px;">
                    </div>
                </div> -->
                <!-- END OF Can be deleted -->

            </form>

        </div>

    </section>
</section>

<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>

<script type="text/javascript">
    var pathArray = location.href.split( '/' );
    var protocol = pathArray[0];
    var host = pathArray[2];
    var folder = pathArray[3];

    var base_url = '<?php echo base_url(); ?>';

    var client_signing_info = <?php echo json_encode($client_signing_info);?>;

    var fs_report_details = <?php echo json_encode($fs_report_details); ?>;
    var fs_first_set = <?php echo $fs_report_details[0]['first_set']; ?>;
    var company_code = fs_report_details[0]['company_code'];
    var firm_id      = fs_report_details[0]['firm_id'];

    var submit_fs_company_info_url = "<?= base_url();?>caf/submit_fs_company_info";
  
</script>

<script src="<?= base_url()?>application/modules/caf/js/fs_company_info.js" charset="utf-8"></script>