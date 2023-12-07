
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>	

<script src="<?= base_url() ?>node_modules/jquery-validation/dist/jquery.validate.js"></script>
<link href="<?= base_url() ?>application/modules/caf/css/adjustment_popup.css" rel="stylesheet" type="text/css">

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
    border:1px solid black !important;
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
    /*font-style: italic;*/
    font-weight: bold; 
}

.side-border
{
    border-left: 1px solid black !important;
    border-right: 1px solid black !important;
}

.divider-line
{
    border-bottom: 1px solid black !important;
    text-align: center;
}

</style>


<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 0px;">			
        <form id="user_audit_programme_fcm_form">

            <input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">
            <input type="hidden" class="id" name="id" value="<?=(isset($fcm_detail['id'])?$fcm_detail['id']:"")?>">

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="audit_objectives_table">
                <tr>
                    <td colspan="3">
                        <h4 style="color:black;padding:0;margin:0;"><b>AUDIT PROGRAMME - <?= (isset($programme_master_detail->title)?strtoupper($programme_master_detail->title):'')?></b></h4>
                    </td>
                </tr>




            </table>

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black">
                <tr>
                    <td class="content_main_line" colspan="3">
                        <div class="form-group">
                            <label class="col-sm-3">Requires second partner?</label>
                            <div class="col-sm-4">
                                <label class="verify_switch">
                                    <input name="require_flag" onchange=hideshow_2nd_partner() class="require_switch active_switch" type="checkbox" value="1" <?=(isset($fcm_detail['require_flag'])?($fcm_detail['require_flag']==1?"checked":""):"")?> ><span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </td>

                </tr>

                <tr>
                    <th class="content_main_line" colspan="3">Approval for signing the audit report</th>

                </tr>

                

                <tr>
                    <td width="100%" colspan="3">I confirm that:</td>
        

                </tr>
                <tr>

                    <td width="3%" >1. </td>
                    <td width="97%" colspan="2">The audit complies with professional standards and the applicable legal and regulatory requirements.</td>
        

                </tr>
                <tr>

                    <td >2. </td>
                    <td colspan="2">In particular, the audit complies with all relevant requirements of the SSAs</td>
        

                </tr>
                <tr>

                    <td>3. </td>
                    <td colspan="2">A sufficient and appropriate record for the basis of the audit report has been documented.</td>
        

                </tr>
                <tr>

                    <td >4. </td>
                    <td colspan="2">There are no factors to indicate that the representations received from the directors cannot be relied upon.</td>
        

                </tr>
                <tr>

                    <td>5. </td>
                    <td colspan="2">All outstanding matters have been concluded.</td>
        

                </tr>
                <tr>

                    <td>6. </td>
                    <td colspan="2">Subsequent events review has been updated. We have performed the procedures required to cover the period from the date of the financial statements to the date of the auditor’s report, or as near as practicable thereto.</td>
        

                </tr>
                <tr>

                    <td>7. </td>
                    <td colspan="2">The auditor's report issued is appropriate in the circumstances. The auditor’s report is dated no earlier than the date on which sufficient appropriate audit evidence to support the auditor’s opinion on the financial statements has been obtained. The evidence include:</td>
        

                </tr>
                <tr>

                    <td width="3%" ></td>
                    <td width="3%" >(a)</td>
                    <td width="94%" colspan="2">All statements that comprise the financial statements, including the related notes, have been prepared; and</td>
        

                </tr>
                <tr>

                    <td></td>
                    <td>(b)</td>
                    <td colspan="2">Those with recognised authority have asserted that they have taken responsibility for those financial statements.</td>
        

                </tr>


                

                <tr class="hide_show_tr">

                    <td>8. </td>
                    <td colspan="2">Where a second partner review is involved, the second partner review is completed on or before the date of the auditor’s report.</td>
        

                </tr>

                <tr>
                    <td colspan="3">I authorise the issue of the audit report.</td>

                </tr>
                
            </table>


            
   
            
        </form>

	</section>
</section>

<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>

<?php include('application/modules/caf/template/adjustment_popup.html'); ?>




<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>'; 

    var save_programme_fcm_input_url = "<?php echo site_url('caf/save_programme_fcm_input'); ?>";
    var export_programme_fcm_pdf_url = "<?php echo (site_url('caf/export_programme_fcm_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";
</script>


<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_fcm.js" charset="utf-8"></script>
<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_toolbar.js" charset="utf-8"></script>
<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>