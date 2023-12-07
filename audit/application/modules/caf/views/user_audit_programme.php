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

</style>


<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 0px;">			
        <form id="user_audit_programme_form">

            <input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">
            <input type="hidden" name="conclusion_id" value="<?=isset($programme_conclusion['id'])?$programme_conclusion['id']:"" ?>">

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="audit_objectives_table">
                <tr>
                    <td colspan="3">
                        <h4 style="color:black;padding:0;margin:0;"><b>AUDIT PROGRAMME - <?= (isset($programme_master_detail->title)?strtoupper($programme_master_detail->title):'')?></b></h4>
                        <br>
                        <h5 style="color:black;"><b><u>SUMMARY SHEET</u></b></h5>
                    </td>
                </tr>
                <tr>
                    <td width="2%;">
                        <h5 style="color:black;margin:0;"><b>I.</b></h5>
                    </td>
                    <td width="98%;" colspan="2">
                        <h5 style="color:black;margin:0;"><b>AUDIT OBJECTIVES</b></h5>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                    </td>
                    <td class="text-center custom-header" width="15%;">
                        Assertions
                    </td>
                </tr>

                <?php 
                    if($programme_objective_lines)
                    {
                        foreach($programme_objective_lines as $key => $line)
                        {
                            echo '<tr>';
                                echo '<td class="text-left">'.($key + 1).'. </td>';
                                echo '<td style="text-align:left;">'.$line->objective_text.'</td>';
                                echo '<td style="text-align:center;border:1px solid black;">'
                                        .$line->assertion_text.
                                      '</td>';
                            echo '</tr>';
                        }
                    }
                    
                ?>

            </table>

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="audit_procedure_table">
                <tr>
                    <td width="2%;">
                        <h5 style="color:black;margin:0;"><b>II.</b></h5>
                    </td>
                    <td width="98%;" colspan="4">
                        <h5 style="color:black;margin:0;"><b>AUDIT PROCEDURE DESIGN CONSIDERATIONS</b></h5>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                    </td>
                    <td class="text-center custom-header" width="4%;">
                        Yes
                    </td>
                     <td class="text-center custom-header" width="4%;">
                        No
                    </td>
                     <td class="text-center custom-header" width="12%;">
                        Comments
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        For risks indentified perform the audit procedures.
                    </td>
                    <td class="text-center black-bg" width="4%;">
                    
                    </td>
                     <td class="text-center black-bg" width="4%;">
                    
                    </td>
                     <td class="text-center bordered-cell"  width="12%;">
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="5">
                        <br>
                    </td>
                </tr>

                 <?php 
                    if($programme_procedure_lines)
                    {
                        // print_r($programme_procedure_lines);
                        foreach($programme_procedure_lines as $key => $line)
                        {
                            
                            if(isset($line->yn_value))
                            {
                                $yes_checked = $line->yn_value==1?"checked":"";
                                $no_checked  = $line->yn_value==0?"checked":"";
                            }
                            else
                            {
                                $yes_checked = "";
                                $no_checked  = "";
                            }
                            echo '<tr>';
                                echo '<input type=hidden name="option['.$line->id.'][procedure_caf_id]" value="'.(isset($line->procedure_caf_id)?$line->procedure_caf_id:"").'" >';
                                echo '<td></td>';
                                echo '<td style="text-align:left;">'.$line->step_text.'</td>';
                                echo '<td class="text-center bordered-cell" width="4%;">
                                        <input type="radio" name="option['.$line->id.'][yn]" '.$yes_checked.' value="1">
                                      </td>
                                      <td class="text-center bordered-cell" width="4%;">
                                        <input type="radio" name="option['.$line->id.'][yn]" '.$no_checked.' value="0">
                                      </td>
                                      <td class="text-center bordered-cell" width="12%;"><textarea name="option['.$line->id.'][comment]" rows="1" class="form-control">'.(isset($line->comment)?$line->comment:"").'</textarea></td>';
                            echo '</tr>';

                            if(isset($line->child_text) && count($line->child_text) > 0)
                            {
                                echo '<tr>
                                        <td></td>
                                        <td>
                                            <ul>';
                                foreach ($line->child_text as $child_key => $each_child) 
                                {
                                    echo '<li><i>'.$each_child.'</i></li>';
                                }

                                echo '      </ul>
                                        </td>
                                     </tr>';
                            }
                        }
                    }
                 // print_r($programme_procedure_lines);
                    
                ?>

            </table>

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="planning_conclusion_table">
                <tr>
                    <td width="2%;">
                        <h5 style="color:black;margin:0;"><b>III.</b></h5>
                    </td>
                    <td width="98%;" colspan="4">
                        <h5 style="color:black;margin:0;"><b>PLANNING CONCLUSION</b></h5>
                    </td>
                </tr>

                <tr>
                    <td>
                    </td>
                    <td class="text-left">
                        I am <select name="planning_conclusion"><option value="1" <?=isset($programme_conclusion['pc_yes_selected'])?$programme_conclusion['pc_yes_selected']:"" ?>>satisfied</option><option value="0" <?=isset($programme_conclusion['pc_no_selected'])?$programme_conclusion['pc_no_selected']:"" ?>>not satisfied</option></select> from the planned tests, sufficient appropriate evidence can be obtained to neet the audit objectives.
                    </td>
                
                </tr>
                
            </table>
            <hr/>
            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="programme_content_table">
                <?php 
                    if($programme_content)
                    {
                        $l1_index = 'A';
                        foreach($programme_content as $l1_key => $level_1)
                        {
                            echo '<tr class="content_main_line">';
                                echo '<td width="2%;" class="text-left">'.$l1_index.'. </td>';
                                echo '<td width="98%;" style="text-align:left;">'.$level_1['text'].'</td>';
                            echo '</tr>';

                            $l1_index++;

                            $l2_index = 1;
                            if(count($level_1['child']) > 0)
                            {
                                foreach ($level_1['child'] as $l2_key => $level_2) {
                                    echo '<tr>';
                                        echo '<td width="2%;" class="text-left">'.$l2_index.' </td>';
                                        echo '<td width="98%;" style="text-align:left;">'.$level_2['text'].'</td>';
                                    echo '</tr>';

                                    $l2_index++;

                                    $l3_index = 'a';
                                    if(count($level_2['child']) > 0)
                                    {
                                        echo '<tr>';
                                                echo '<td width="2%;" class="text-left"></td>';
                                                echo '<td width="98%;" style="text-align:left;padding:0;">
                                                        <table class="table table-borderless nested_audit_procedure_table" style="width:100%;" >'; 
                                        foreach ($level_2['child'] as $l3_key => $level_3) {
                                             
                                            echo    '<tr>
                                                        <td class="borderless text-center" style="width:2%;">('.$l3_index.')
                                                            
                                                        </td>
                                                        <td class="borderless text-left" style="width:98%;">'
                                                            .$level_3['text'].
                                                        '</td>
                                                    </tr>';
                                            

                                            $l3_index++;

                                            $l4_index = 1;
                                            if(count($level_3['child']) > 0)
                                            {
                                                echo '<tr>';
                                                        echo '<td width="2%;" class="text-left"></td>';
                                                        echo '<td width="98%;" style="text-align:left;padding:0;">
                                                                <table class="table table-borderless nested_audit_procedure_table" style="width:100%;" >';
                                                                   
                                                foreach ($level_3['child'] as $l4_key => $level_4) {
                                                     
                                                    echo    '<tr>
                                                                <td class="borderless text-center" style="width:2%;">('.strtolower(numberToRomanRepresentation($l4_index)).')
                                                                    
                                                                </td>
                                                                <td class="borderless text-left" style="width:98%;">'
                                                                    .$level_4['text'].
                                                                '</td>
                                                            </tr>';
                                                    

                                                    $l4_index++;
                                                }

                                                echo    '</table>
                                                        </td>';
                                                echo '</tr>';

                                            }
                                        }

                                        echo    '</table>
                                                </td>';
                                        echo '</tr>';
                                    }
                                }
                            }
                        }
                    }
                    
                ?>
                
            </table>
            <hr/>
            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="conclusion_table">
                <tr>
                    <td width="2%;">
                        <h5 style="color:black;margin:0;"><b>IV.</b></h5>
                    </td>
                    <td width="98%;" colspan="4">
                        <h5 style="color:black;margin:0;"><b>CONCLUSION</b></h5>
                    </td>
                </tr>

                <tr>
                    <td>
                        1.
                    </td>
                    <td class="text-left">
                        There are no exceptions in our response to the risks identified.
                    </td>
                
                </tr>

                <tr>
                    <td>
                        2.
                    </td>
                    <td class="text-left">
                        The work has been performed as planned and the findings and results have been adequately documented.
                    </td>
                
                </tr>

                <tr>
                    <td>
                        3.
                    </td>
                    <td class="text-left">
                        There are no additional comments to be included in the letter of representation or letter of comment. Where applicable, the planned extend of reliance on internal controls in this area remains appropriate.
                    </td>
                
                </tr>

                <tr>
                    <td>
                        4.
                    </td>
                    <td class="text-left">
                        All necessary information has been obtained for the presentation and disclosure in the financial statements.
                    </td>
                
                </tr>

                <tr>
                    <td>
                        5.
                    </td>
                    <td class="text-left">
                        Misstatements identified (other than those deemed to be clearly trivial) have been recorded.
                    </td>
                
                </tr>

                <tr>
                    <td>
                        6.
                    </td>
                    <td class="text-left">
                        Initial materiality and/or risk assessment need not be revised in view of the audit evidence obtained. 
                    </td>
                
                </tr>


                <tr>
                    <td style="vertical-align:middle;">
                        7.
                    </td>
                    <td class="text-left">
                        Sufficient appropriate evidence <select name="conclusion"><option value="1" <?=isset($programme_conclusion['c_yes_selected'])?$programme_conclusion['c_yes_selected']:"" ?>>has been</option><option value="0" <?=isset($programme_conclusion['c_yes_selected'])?$programme_conclusion['c_no_selected']:"" ?>>has not been</option></select> obtained to support the audit objectives.
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
    var save_programme_input_url = "<?php echo site_url('caf/save_programme_input'); ?>";
    var export_programme_pdf_url = "<?php echo (site_url('caf/export_programme_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";
</script>


<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme.js" charset="utf-8"></script>
<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_toolbar.js" charset="utf-8"></script>
<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>