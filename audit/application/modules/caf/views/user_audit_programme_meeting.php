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




</style>


<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 0px;">			
        <form id="user_audit_programme_meeting_form">

            <input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">
            <input type="hidden" class="master_id" name="master_id" value="<?=(isset($meeting_detail)&&$meeting_detail?$meeting_detail['id']:"")?>">

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
                    <td colspan="3">
                        <div class="form-group">
                            <label class="col-sm-3">Date and time of meeting</label>
                            <div class="col-sm-4">

                               
                                        <div style="width: 100%;">
                                            <div class="input-group date form_datetime" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                                                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                                                <input autocomplete="off" name="meeting_datetime" class="form-control" type="text" value="<?=isset($meeting_detail['meeting_datetime'])&&$meeting_detail?$meeting_detail['meeting_datetime']: '' ?>">
                                            </div>
                                    
                                        </div>
                                

                               <!--  <div class="form-group">
                                  
                                    <div style="width: 100%;">
                                        <div class="input-group date form_datetime" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                                            <span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>
                                            <input autocomplete="off" name="interview_datetime" class="interview_datetime form-control" type="text" value="" >
                                        </div>
                             
                                    </div>
                                   
                                </div> -->
                         <!--        <div class="form-group input-group" id="meeting_date_time" style="width: 30%;">
                                    <span class="input-group-addon"><i class="far fa-calendar-alt"></i></span>
                                    <input type="text" class="auth_date form-control" name="auth_date" data-date-format="dd/mm/yyyy" data-plugin-datepicker required value="">
                                </div> -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3">Location</label>
                            <div class="col-sm-4">
                                <textarea row="2" class="meeting_location form-control" name="meeting_location"><?=isset($meeting_detail['location'])?$meeting_detail['location']: '' ?></textarea>
                                <!-- <select class="transaction_task" style="width: 100%;" name="transaction_task" id="transaction_task">
                                    <option value="0">Select Index</option>
                                </select> -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3">Any team member absent during the discussion?</label>
                            <div class="col-sm-4">
                                <label class="verify_switch">
                                    <input name="absent_flag" onchange=hideshow_absent_tbl() class="absent_switch active_switch" type="checkbox" value="1" <?=(isset($meeting_detail['absent_flag'])?($meeting_detail['absent_flag']==1?"checked":""):"")?> ><span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        
                    </td>
                </tr>


            </table>

            


            <table class="table table-borderless" id="attendees_tbl" style="border-collapse: collapse; margin: 2%; width: 60%; color:black">
                <tr>
                    <td colspan="3" style="padding-left:0;">
                        <b>Attendees at meeting:</b>
                    </td>
                </tr>
                <tr>
                    <td class="custom-header" width="48%">
                        Name
                    </td>
                    <td class="custom-header" width="48%">
                        Designation
                    </td>
                    <td width="4%">
                        <a class="amber" href="javascript:void(0);" onclick=add_attendees_line(this) data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;">
                            <i class="fa fa-plus-circle amber" style="font-size:13px;"></i>
                        </a>
                    </td>
                </tr>
                <tbody id="attendees_line" style="border-top:none;">

                    <?php

                        if(!isset($meeting_attendees) || !$meeting_attendees)
                        {

                    ?>
                            <tr style="width:100%">
                                <input type="hidden" name="attendees_id[]" class="attendees_id[]">
                                
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
                    <?php
                        }
                    ?>
                    
                </tbody>
            </table>

            


            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 60%; color:black">
                <tr>
                    <td colspan="3" style="padding-left:0;">
                        <b>Suggested agenda topics for discussion with management and those charged with governance</b>
                    </td>
                </tr>
                <tr>
                    <td class="custom-header" width="48%">
                        Agenda
                    </td>
                    <td class="custom-header" width="48%">
                        Minutes of meeting
                    </td>
                    <td width="4%">
                        <a class="amber"  href="javascript:void(0);" onclick=add_agenda_line(this) data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;">
                            <i class="fa fa-plus-circle amber" style="font-size:13px;"></i>
                        </a>
                    </td>
                </tr>

                <tbody id="agenda_line" style="border-top:none;">
                    <?php
                        if(!isset($meeting_agenda) || !$meeting_agenda)
                        {

                    ?>
                
                    <tr style="width:100%">
                        <input type="hidden" name="agenda_id[]" class="agenda_id">
                        
                        <td class="bordered-cell">
                            <input type="text" class="form-control" name="agenda_text[]">
                        </td>
                        <td class="bordered-cell">
                            <input type="text" class="form-control" name="minutes_of_meeting[]">
                        </td>
                        <td style="vertical-align: middle;">
                            <a class="amber remove_agenda_line" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;top:50px;display: none;" data-original-title="Remove this line"  onclick=remove_agenda_line(this) ><i class="fa fa-minus-circle amber" style="font-size:13px;"></i></a>
                        </td>
                   
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>

            <table class="table table-borderless" id="absent_tbl" style="border-collapse: collapse; margin: 2%; width: 60%; color:black">
                <tr>
                    <td colspan="5" style="padding-left:0;">
                        <b>MATTERS TO BE COMMUNICATED TO OTHER MEMBERS ON THE ENGAGEMENT TEAM NOT INVOLVED IN THE DISCUSSION</b>
                    </td>
                </tr>
                <tr>
                    <td class="custom-header" width="24.5%">
                        Name
                    </td>
                    <td class="custom-header" width="24.5%">
                        Engagement Role
                    </td>
                    <td class="custom-header" width="24.5%">
                        Subject
                    </td>
                    <td class="custom-header" width="24.5%">
                       Date subject has been communicated
                    </td>
                    <td width="4%" style="vertical-align: middle;">
                        <a class="amber" id="add_absent_line" href="javascript:void(0);" onclick=add_absent_line(this) data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add question" >
                            <i class="fa fa-plus-circle amber" style="font-size:13px;"></i>
                        </a>
                    </td>
                </tr>
                <tbody id="absent_line" style="border-top:none;">
                    <?php
                        if(!isset($meeting_absent) || !$meeting_absent)
                        {

                    ?>
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
                    <?php
                        }
                    ?>
                </tbody>
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

    var arr_deleted_attendees = [];
    var arr_deleted_agenda    = [];
    var arr_deleted_absent    = [];
    // var programme_content = JSON.parse('<?php echo isset($programme_content)?json_encode($programme_content):""; ?>');
    var save_programme_meeting_input_url = "<?php echo site_url('caf/save_programme_meeting_input'); ?>";
    var export_programme_meeting_pdf_url = "<?php echo (site_url('caf/export_programme_meeting_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";

    var meeting_attendees = <?php echo json_encode(isset($meeting_attendees)?$meeting_attendees:"") ?>;
    var meeting_agenda    = <?php echo json_encode(isset($meeting_agenda)?$meeting_agenda:"") ?>;
    var meeting_absent    = <?php echo json_encode(isset($meeting_absent)?$meeting_absent:"") ?>;


</script>


<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_meeting.js" charset="utf-8"></script>
<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_toolbar.js" charset="utf-8"></script>
<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>