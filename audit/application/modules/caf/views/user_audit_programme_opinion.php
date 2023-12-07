<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>	

<script src="<?= base_url() ?>node_modules/jquery-validation/dist/jquery.validate.js"></script>
<link href="<?= base_url() ?>application/modules/caf/css/adjustment_popup.css" rel="stylesheet" type="text/css">

<!-- <link rel="stylesheet" href="<?= base_url() ?>plugin/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>plugin/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
 -->
<link rel="stylesheet" href="<?= base_url() ?>node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" />
<script src="<?= base_url() ?>node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

<style type="text/css">

.table-borderless > tbody > tr > td,
.table-borderless > tbody > tr > th,
.table-borderless > tfoot > tr > td,
.table-borderless > tfoot > tr > th,
.table-borderless > thead > tr > td,
.table-borderless > thead > tr > th
{
    border: none;
    /*border: 1px solid black;*/
}

.custom-header
{
    background-color: #446687;
    color: white;
    text-align: center;
    border-left:1px solid black !important;
    border-right:1px solid black !important;
    vertical-align: middle !important;
}

.black-bg
{
    background-color: black;
    border: 1px solid gray !important;
}

.bordered-cell
{
    border:1px solid black !important;
}

.content_main_line
{
    font-style: italic;
    font-weight: bold; 
}

.alpha_index
{
    width: 5%;
}

.desc
{
    width: 30%;
}




</style>


<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 0px;">			
        <form id="user_audit_programme_opinion_form">

            <input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">
            <input type="hidden" class="master_id" name="master_id" value="<?=(isset($opinion_detail['id'])?$opinion_detail['id']:"")?>">

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="audit_objectives_table">
                <tr>
                    <td colspan="3">
                        <h4 style="color:black;padding:0;margin:0;"><b>AUDIT PROGRAMME - <?= (isset($programme_master_detail->title)?strtoupper($programme_master_detail->title):'')?></b></h4>
                    </td>
                </tr>
                  <tr>
                    <td colspan="3">
                        
                    </td>
                </tr>
                <tr>
                    <td class="alpha_index">A. </td>
                    <td class="desc">Date of auditor's report:</td>
                    <td >
                        <div class="form-group">
                            <div class="col-sm-4">

                               
                                        <div style="width: 100%;">
                                            <div class="input-group date form_date" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                <input autocomplete="off" name="date_of_report" class="form-control" type="text" value="<?=isset($opinion_detail['date_of_report'])?$opinion_detail['date_of_report']: '' ?>">
                                            </div>
                                    
                                        </div>
                                

                            </div>
                        </div>
                    </td>

                </tr>

                <tr>
                    <td class="alpha_index">B. </td>
                    <td class="desc">Audit opinion for financial statements:</td>
                    <td >
                        <div class="col-sm-4">
                            <?php
                            echo form_dropdown('fs_opinion', $opinion_dropdown, (isset($opinion_detail['fs_opinion'])?$opinion_detail['fs_opinion']:""), 'class="select2 check_B"');
                            ?>
                   <!--          <select name="fs_opinion" class="select2 check_B">
                                <option value=""></option>
                                <option value="Unqualified opinion">Unqualified opinion</option>
                                <option value="Qualified opinion">Qualified opinion</option>
                                <option value="Adverse opinion">Adverse opinion</option>
                                <option value="Disclaimer of opinion">Disclaimer of opinion</option>
                            </select> -->
                        </div>
                    </td>

                </tr>


                <tr class="hide_show_B">
                    <td class="alpha_index">C. </td>
                    <td class="desc">Basis for modified opinion:</td>
                    <td >
                    </td>

                </tr>
                <tr class="hide_show_B">
                    <td></td>
                    <td colspan="2">Basis for modified opinion on financial statements.</td>
                 

                </tr>

                <tr class="hide_show_B">
                    <td></td>
                    <td colspan="2">
                        <textarea name="fs_opinion_modified" class="form-control"><?=isset($opinion_detail['fs_opinion_modified'])?$opinion_detail['fs_opinion_modified']: '' ?></textarea>
                    </td>
                 

                </tr>

                <tr>
                    <td class="alpha_index">D. </td>
                    <td class="desc">Going concern assumption:</td>
                    <td >
                        <div class="col-sm-4">
                            <?php
                            echo form_dropdown('concern_assumption', $assumption_dropdown, (isset($opinion_detail['concern_assumption'])?$opinion_detail['concern_assumption']:""), 'class="select2 check_D"');
                            ?>
             <!--                <select name="concern_assumption" class="select2 check_D">
                                <option value=""></option>
                                <option value="Appropriate">Appropriate</option>
                                <option value="Inappropriate">Inappropriate</option>
                                <option value="Appropriate but a material uncertainty exists with adequate disclosure">
                                Appropriate but a material uncertainty exists with adequate disclosure</option>
                                <option value="Appropriate but a material uncertainty exists without adequate disclosure">
                                Appropriate but a material uncertainty exists without adequate disclosure</option>
                            </select> -->
                        </div>
                    </td>

                </tr>

                <tr class="hide_show_D">
                    <td></td>
                    <td colspan="2">Description of going concern paragraph.</td>
                 

                </tr>

                <tr class="hide_show_D">
                    <td></td>
                    <td colspan="2">
                        <textarea name="concern_desc" class="form-control"><?=isset($opinion_detail['concern_desc'])?$opinion_detail['concern_desc']: '' ?></textarea>
                    </td>
                 

                </tr>

                <tr>
                    <td class="alpha_index">E. </td>
                    <td class="desc">Emphasis of matter paragraph required?</td>
                    <td >
                        <div class="col-sm-4">
                            <?php
                            echo form_dropdown('emphasis_required', $yn_dropdown, (isset($opinion_detail['emphasis_required'])?$opinion_detail['emphasis_required']:""), 'class="select2 check_E"');
                            ?>
             <!--                <select name="emphasis_required" class="select2">
                                <option value=""></option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select> -->
                        </div>
                    </td>

                </tr>

                <tr class="hide_show_E">
                    <td></td>
                    <td colspan="2">Description of emphasis of matter paragraph.</td>
                 

                </tr>

                <tr class="hide_show_E">
                    <td></td>
                    <td colspan="2">
                        <textarea name="emphasis_desc" class="form-control"><?=isset($opinion_detail['emphasis_desc'])?$opinion_detail['emphasis_desc']: '' ?></textarea>
                    </td>
                 

                </tr>

                <tr>
                    <td class="alpha_index">F. </td>
                    <td class="desc">Key Audit Matters paragraph required?</td>
                    <td >
                        <div class="col-sm-4">
                            <?php
                            echo form_dropdown('key_audit_required', $yn_dropdown, (isset($opinion_detail['key_audit_required'])?$opinion_detail['key_audit_required']:""), 'class="select2 check_F"');
                            ?>
                        <!--     <select name="key_audit_required" class="select2 check_F">
                                <option value=""></option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select> -->
                        </div>
                    </td>

                </tr>

                <tr class="hide_show_F">
                    <td></td>
                    <td colspan="2">Description of each key audit matter.</td>
                 

                </tr>

                <tr class="hide_show_F">
                    <td></td>
                    <td colspan="2">
                        <textarea name="key_audit_desc" class="form-control"><?=isset($opinion_detail['key_audit_desc'])?$opinion_detail['key_audit_desc']: '' ?></textarea>
                    </td>
                 

                </tr>

                <tr>
                    <td class="alpha_index">G. </td>
                    <td class="desc">Other Matters paragraph required?</td>
                    <td >
                        <div class="col-sm-4">
                            <?php
                            echo form_dropdown('other_matters_required', $yn_dropdown, (isset($opinion_detail['other_matters_required'])?$opinion_detail['other_matters_required']:""), 'class="select2 check_G"');
                            ?>
                   <!--          <select name="other_matters_required" class="select2 check_G">
                                <option value=""></option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select> -->
                        </div>
                    </td>

                </tr>

                <tr class="hide_show_G">
                    <td></td>
                    <td colspan="2">Description of other matters paragraph.</td>
                 

                </tr>

                <tr class="hide_show_G">
                    <td></td>
                    <td colspan="2">
                        <textarea name="other_matters_desc" class="form-control"><?=isset($opinion_detail['other_matters_desc'])?$opinion_detail['other_matters_desc']: '' ?></textarea>
                    </td>
                 

                </tr>

                <tr>
                    <td class="alpha_index">H. </td>
                    <td class="desc">Audit opinion on other legal and regulatory requirements</td>
                    <td >
                        <div class="col-sm-4">
                            <?php
                            echo form_dropdown('audit_opinion', $opinion_dropdown, (isset($opinion_detail['audit_opinion'])?$opinion_detail['audit_opinion']:""), 'class="select2 check_H"');
                            ?>
                <!--             <select name="audit_opinion" class="select2 check_H">
                                <option value=""></option>
                                <option value="Unqualified opinion">Unqualified opinion</option>
                                <option value="Qualified opinion">Qualified opinion</option>
                                <option value="Adverse opinion">Adverse opinion</option>
                                <option value="Disclaimer of opinion">Disclaimer of opinion</option>
                            </select> -->
                        </div>
                    </td>

                </tr>

                 <tr class="hide_show_H">
                    <td class="alpha_index">I. </td>
                    <td class="desc">Basis for modified opinion:</td>
                    <td >
                    </td>

                </tr>

                <tr class="hide_show_H">
                    <td></td>
                    <td colspan="2">Basis for modified opinion on other legal and regulatory requirements (if different from C above)</td>
                 

                </tr>

                <tr class="hide_show_H">
                    <td></td>
                    <td colspan="2">
                        <textarea name="audit_opinion_modified" class="form-control"><?=isset($opinion_detail['audit_opinion_modified'])?$opinion_detail['audit_opinion_modified']: '' ?></textarea>
                    </td>
                 

                </tr>


            


            </table>
            
        </form>

	</section>
</section>

<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>

<table id="attendees_clone_model" style="display: none;" >
    <tr style="width:100%">
        <input type="hidden" name="attendees_id[]" class="attendees_id">
        
        <td class="bordered-cell">
            <input type="text" class="form-control" name="attendees_name[]">
        </td>
        <td class="bordered-cell">
            <input type="text" class="form-control" name="designation[]">
        </td>
        <td style="vertical-align: middle;">
            <a class="amber remove_attendees_line" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;top:50px;display: none;" data-original-title="Remove this line"  onclick=remove_attendees_line(this) ><i class="fa fa-minus-circle amber" style="font-size:13px;"></i></a>
        </td>
   
    </tr>
</table>

<table id="agenda_clone_model" style="display: none;" >
    <tr style="width:100%">
        <input type="hidden" name="agenda_id[]" class="agenda_id">
        
        <td class="bordered-cell">
            <input type="text" class="form-control" name="agenda_text[]">
        </td>
        <td class="bordered-cell">
            <input type="text" class="form-control" name="minutes_of_meeting[]">
        </td>
        <td style="vertical-align: middle;">
            <a class="amber remove_agenda_line" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;top:50px;" data-original-title="Remove this line"  onclick=remove_agenda_line(this) ><i class="fa fa-minus-circle amber" style="font-size:13px;"></i></a>
        </td>
   
    </tr>
</table>

<table id="absent_clone_model" style="display: none;" >
    <tr style="width:100%">
        <input type="hidden" name="absent_id[]" class="absent_id">
        
        <td class="bordered-cell">
            <input type="text" class="form-control" name="absent_name[]">
        </td>
        <td class="bordered-cell">
            <input type="text" class="form-control" name="engagement_role[]">
        </td>
        <td class="bordered-cell">
            <input type="text" class="form-control" name="absent_subject[]">
        </td>
        <td class="bordered-cell">
            <div class="input-group date form_date">
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                <input autocomplete="off" name="date_communicated[]" class="form-control" type="text" value="">
            </div>
            <!-- <input type="text" class="form-control" name="date_communicated"> -->
        </td>
        <td style="vertical-align: middle;">
            <a class="amber remove_absent_line" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;top:50px;display: none;" data-original-title="Remove this line"  onclick=remove_absent_line(this) ><i class="fa fa-minus-circle amber" style="font-size:13px;"></i></a>
        </td>
   
    </tr>
</table>

<?php include('application/modules/caf/template/adjustment_popup.html'); ?>

<!-- <include src="<?= base_url()?>application/modules/caf/template/adjustment_popup.html"></include> -->

<?php

    function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
?>


<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>'; 

    // var programme_content = JSON.parse('<?php echo isset($programme_content)?json_encode($programme_content):""; ?>');
    var save_programme_opinion_input_url = "<?php echo site_url('caf/save_programme_opinion_input'); ?>";
    var export_programme_opinion_pdf_url = "<?php echo (site_url('caf/export_programme_opinion_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";

    var meeting_attendees = <?php echo json_encode(isset($meeting_attendees)?$meeting_attendees:"") ?>;


</script>


<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_opinion.js" charset="utf-8"></script>
<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_toolbar.js" charset="utf-8"></script>
<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>