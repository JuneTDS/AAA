
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
        <form id="user_audit_programme_apm_form">

            <input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">
            <input type="hidden" class="id" name="id" value="<?=(isset($apm_detail['id'])?$apm_detail['id']:"")?>">

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
                                    <input name="require_flag" onchange=hideshow_2nd_partner() class="require_switch active_switch" type="checkbox" value="1" <?=(isset($apm_detail['require_flag'])?($apm_detail['require_flag']==1?"checked":""):"")?> ><span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </td>

                </tr>

                <tr>
                    <th class="content_main_line" colspan="3">APPROVAL OF PLANNING</th>

                </tr>
                <tr>
                    <td width="100%" colspan="3">I confirm that:</td>
        

                </tr>
                <tr>

                    <td width="3%" >1. </td>
                    <td width="97%" colspan="2">The preconditions for an audit are present and the scope of the audit engagement has been agreed with management and those charged with governance.</td>
        

                </tr>
                <tr>

                    <td >2. </td>
                    <td colspan="2">An audit strategy has been established for the audit.</td>
        

                </tr>
                <tr>

                    <td>3. </td>
                    <td colspan="2">An audit plan, which includes the following, has been developed to reduce risk to an acceptable level:</td>
                </tr>
                <tr>

                    <td width="3%" ></td>
                    <td width="3%" >(a)</td>
                    <td width="94%" colspan="2">Assessment of the risks of material misstatement in the financial statements due to fraud;</td>
        

                </tr>
                <tr>

                    <td></td>
                    <td>(b)</td>
                    <td colspan="2">Design of audit procedures to obtain sufficient appropriate audit evidence in accordance with the requirements in the SSAs, relevant ethics code, laws and regulations, the terms of the audit engagement and financial reporting requirements; and</td>
        

                </tr>
                <tr>

                    <td></td>
                    <td>(c)</td>
                    <td colspan="2">Customised audit programmes to address the financial statement and assertion level risks in accordance with the SSAs.</td>
        

                </tr>
                <tr>

                    <td >4. </td>
                    <td colspan="2">Professional scepticism has been exercised to establish the audit strategy and design the audit procedures, taking into account the circumstances which may cause the financial statements to be materially misstated.</td>
        

                </tr>
                <tr>

                    <td>5. </td>
                    <td colspan="2">The engagement team collectively has the appropriate capabilities, competence and time to perform the audit engagement in accordance with professional standards and regulatory and legal requirements. The engagement team has been adequately briefed to perform the audit in order to issue an appropriate audit opinion.</td>
        

                </tr>
                <tr>
                    <th class="content_main_line" colspan="3">UPDATE AT COMPLETION STAGE</th>

                </tr>
                <tr>
                    <td width="100%" colspan="3">I confirm that:</td>
        

                </tr>
                <tr>
                    <td>1. </td>
                    <td colspan="2">The overall strategy and audit plan were updated as necessary during the course of the audit.</td>
                </tr>
                <tr>
                    <td>2. </td>
                    <td colspan="2">All issues arising from the audit plan have been addressed and documented.</td>
                </tr>
                <tr>
                    <td>3. </td>
                    <td colspan="2">The audit plan has been cross-referenced to where the relevant work was performed.</td>
                </tr>
                <tr>
                    <td>4. </td>
                    <td colspan="2">The assessment of materiality and risk have been reviewed and amended where necessary.</td>
                </tr>
               <tr>
                    <th class="content_main_line" colspan="3">CONFIRMATION BY AUDIT TEAM</th>

                </tr>
                <tr>
                    <td width="100%" colspan="3">I confirm</td>
        

                </tr>
                <tr>

                    <td width="3%" >1. </td>
                    <td width="97%" colspan="2">that I have read and understood the audit plan (Section C); and</td>
        

                </tr>
                <tr>

                    <td >2. </td>
                    <td colspan="2">to the best of my knowledge and belief that I am independent in accordance with the independent requirements of Code of Professional Conduct and Ethics under the Forth Schedule of Accountants (Public Accountants) Rule / the firm’s statement of policy on independence*.</td>
        

                </tr>
                <tr>

                    <td>3. </td>
                    <td colspan="2">to the best of my knowledge and belief, the following matters might affect the independence of the firm’s provision of professional service to this client.  The matters which are required to be immediately notified to the firm are the following, but not limited to:  </td>
                </tr>
                <tr>

                    <td width="3%" ></td>
                    <td width="3%" >(a)</td>
                    <td width="94%" colspan="2">Financial interest in the client;</td>
        

                </tr>
                <tr>

                    <td></td>
                    <td>(b)</td>
                    <td colspan="2">Loans or guarantee obtained from the client;</td>
        

                </tr>
                <tr>
                    <td></td>
                    <td>(c)</td>
                    <td colspan="2">Gifts and hospitality received from the client;</td>
                </tr>
                <tr>
                    <td></td>
                    <td>(d)</td>
                    <td colspan="2">Family or personal relationships with the client;</td>
                </tr>
                <tr>
                    <td></td>
                    <td>(e)</td>
                    <td colspan="2">Employment with the client;</td>
                </tr>
                <tr>
                    <td></td>
                    <td>(f)</td>
                    <td colspan="2">Close business relationship with the client; and</td>
                </tr>
                <tr>
                    <td></td>
                    <td>(g)</td>
                    <td colspan="2">Others stated in the Code of Professional Conduct and Ethics under the Forth Schedule of Accountants (Public Accountants) Rules.</td>
                </tr>
                
            </table>


            
   
            
        </form>

	</section>
</section>

<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>

<?php include('application/modules/caf/template/adjustment_popup.html'); ?>




<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>'; 

    var save_programme_fcm_apm_input_url = "<?php echo site_url('caf/save_programme_fcm_apm_input'); ?>";
    var export_programme_apm_pdf_url = "<?php echo (site_url('caf/export_programme_apm_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";
</script>


<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_apm.js" charset="utf-8"></script>
<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_toolbar.js" charset="utf-8"></script>
<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>