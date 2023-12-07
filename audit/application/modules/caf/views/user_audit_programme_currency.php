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
    font-style: italic;
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
}

</style>


<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 0px;">			
        <form id="user_audit_programme_currency_form">

            <input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">
            <input type="hidden" class="id" name="id" value="<?=(isset($currency_detail['id'])?$currency_detail['id']:"")?>">

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="audit_objectives_table">
                <tr>
                    <td colspan="3">
                        <h4 style="color:black;padding:0;margin:0;"><b>AUDIT PROGRAMME - <?= (isset($programme_master_detail->title)?strtoupper($programme_master_detail->title):'')?></b></h4>
                    </td>
                </tr>


            </table>
            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 60%; color:black" id="programme_content_table">
                <tr>
                    <th colspan="3"></th>
        
                    <th class="custom-header">
                        Currency in use
                    </th>

                </tr>

                <tr>
                    <td width="85%" class="content_main_line" colspan="3">Primary indicators</td>
        
                    <td class="side-border">
                    </td>

                </tr>

                <tr>
                    <td width="5%" >1. </td>
                    <td width="80%" colspan="2">The currency: </td>
        
                    <td class="side-border">
                    </td>

                </tr>

                <tr>
                    <td width="5%" ></td>
                    <td width="5%" >(a) </td>
                    <td width="75%">That mainly influences sales prices for goods and services; and </td>
        
                    <td class="side-border divider-line">
                        <?php
                            echo form_dropdown('curr_influence_sales_price', $currency_dropdown, (isset($currency_detail['curr_influence_sales_price'])?$currency_detail['curr_influence_sales_price']:""), 'class="curr_influence_sales_price select2" style="width:100%;"');
                        ?>
                    </td>

                </tr>

                <tr>
                    <td width="5%" ></td>
                    <td width="5%" >(b) </td>
                    <td width="75%">Of the country whose competitive forces and regulations mainly determine the sales prices of its goods and services. </td>
        
                    <td class="side-border divider-line">
                        <?php
                            echo form_dropdown('curr_competitive_forces', $currency_dropdown, (isset($currency_detail['curr_competitive_forces'])?$currency_detail['curr_competitive_forces']:""), 'class="curr_competitive_forces select2" style="width:100%;"');
                        ?>
                    </td>

                </tr>

                <tr>
                    <td width="5%" >2. </td>
                    <td width="80%" colspan="2">The currency that mainly influences labour, material and other costs of providing goods or services. </td>
        
                    <td class="side-border divider-line">
                        <?php
                            echo form_dropdown('curr_influence_labour', $currency_dropdown, (isset($currency_detail['curr_influence_labour'])?$currency_detail['curr_influence_labour']:""), 'class="curr_influence_labour select2" style="width:100%;"');
                        ?>
                    </td>

                </tr>

                <tr>
                    <td width="85%" class="content_main_line" colspan="3">Other indicators </td>
        
                    <td class="side-border">
                    </td>

                </tr>

                <tr>
                    <td width="5%" >1. </td>
                    <td width="80%" colspan="2">What is the currency in which funds from financing activities are generated?  </td>
        
                    <td class="side-border divider-line">
                        <?php
                            echo form_dropdown('curr_financing_activities', $currency_dropdown, (isset($currency_detail['curr_financing_activities'])?$currency_detail['curr_financing_activities']:""), 'class="curr_financing_activities select2" style="width:100%;"');
                        ?>
                    </td>

                </tr>
                <tr>
                    <td width="5%" >2. </td>
                    <td width="80%" colspan="2">What is the currency in which receipts from operating activities are usually retained? </td>
        
                    <td class="side-border divider-line">
                        <?php
                            echo form_dropdown('curr_operating_activities', $currency_dropdown, (isset($currency_detail['curr_operating_activities'])?$currency_detail['curr_operating_activities']:""), 'class="curr_operating_activities select2" style="width:100%;"');
                        ?>
                    </td>

                </tr>

                <tr>
                    <td width="5%" >3. </td>
                    <td width="80%" colspan="2">Are activities of the foreign operation(s) an extension of reporting entity? </td>
        
                    <td class="side-border divider-line">
                        <?php
                            echo form_dropdown('yn_extension_report_entity', $yn_dropdown, (isset($currency_detail['yn_extension_report_entity'])?$currency_detail['yn_extension_report_entity']:""), 'class="input2 select2" style="width:100%;"');
                        ?>
                    </td>

                </tr>

                <tr>
                    <td width="5%" >4. </td>
                    <td width="80%" colspan="2">Are transactions with the reporting entity a high or low proportion of the activities in the foreign operation(s)? </td>
        
                    <td class="side-border divider-line">
                        <?php
                            echo form_dropdown('high_low_proportion', $hl_dropdown, (isset($currency_detail['high_low_proportion'])?$currency_detail['high_low_proportion']:""), 'class="high_low_proportion select2" style="width:100%;"');
                        ?>
                    </td>

                </tr>

                <tr>
                    <td width="5%" >5. </td>
                    <td width="80%" colspan="2">Do the cash flows arising from the activities in the foreign operation(s) directly affect the cash flow of the reporting entity and are they readily available for remittance? </td>
        
                    <td class="side-border divider-line">
                        <?php
                            echo form_dropdown('yn_affect_cash_flow', $yn_dropdown, (isset($currency_detail['yn_affect_cash_flow'])?$currency_detail['yn_affect_cash_flow']:""), 'class="yn_affect_cash_flow select2" style="width:100%;"');
                        ?>
                    </td>

                </tr>

                <tr>
                    <td width="5%" >6. </td>
                    <td width="80%" colspan="2">Is the cash flow from the activities of the foreign operation(s) sufficient to service its own debt obligations? </td>
        
                    <td class="side-border divider-line">
                        <?php
                            echo form_dropdown('yn_cash_flow_sufficient', $yn_dropdown, (isset($currency_detail['yn_cash_flow_sufficient'])?$currency_detail['yn_cash_flow_sufficient']:""), 'class="yn_cash_flow_sufficient select2" style="width:100%;"');
                        ?>
                    </td>

                </tr>

                <tr>
                    <td width="85%" class="content_main_line" colspan="3">Conclusion </td>
        
                    <td class="side-border">
                    </td>

                </tr>

                <tr>
                    <td width="5%" >7. </td>
                    <td width="80%" colspan="2">Based on our assessment of the entity’s business and records and corroborative procedures performed to evaluate management’s assessment of the functional currency of the entity, we conclude that the functional currency is:  </td>
        
                    <td class="side-border divider-line">
                        <?php
                            echo form_dropdown('curr_functional', $currency_dropdown, (isset($currency_detail['curr_functional'])?$currency_detail['curr_functional']:""), 'class="curr_functional select2" style="width:100%;"');
                        ?>
                    </td>

                </tr>

                <tr>
                    <td width="5%" >8. </td>
                    <td width="80%" colspan="2">Management assessment appears reasonable  </td>
        
                    <td class="side-border divider-line">
                        <?php
                            echo form_dropdown('yn_reasonable', $yn_dropdown, (isset($currency_detail['yn_reasonable'])?$currency_detail['yn_reasonable']:""), 'class="yn_reasonable select2" style="width:100%;"');
                        ?>
                    </td>

                </tr>


                
            </table>
            
        </form>

	</section>
</section>

<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>

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

    var save_programme_currency_input_url = "<?php echo site_url('caf/save_programme_currency_input'); ?>";
    var export_programme_currency_pdf_url = "<?php echo (site_url('caf/export_programme_currency_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";
</script>


<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_currency.js" charset="utf-8"></script>
<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_toolbar.js" charset="utf-8"></script>
<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>