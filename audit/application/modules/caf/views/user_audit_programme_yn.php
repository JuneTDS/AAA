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



</style>


<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 0px;">			
        <form id="user_audit_programme_yn_form">

            <input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="audit_objectives_table">
                <tr>
                    <td colspan="3">
                        <h4 style="color:black;padding:0;margin:0;"><b>AUDIT PROGRAMME - <?= (isset($programme_master_detail->title)?strtoupper($programme_master_detail->title):'')?></b></h4>
                    </td>
                </tr>


            </table>
            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="programme_content_table">
                <tr>
                    <th>
                        
                    </th>
                    <th colspan="3"></th>
                    <th class="custom-header">
                        Y/N/NA
                    </th>
                    <th class="custom-header">
                        Remark
                    </th>

                </tr>


                <?php 

                    if($programme_content)
                    {
                        $l1_index = '1';
                        foreach($programme_content as $l1_key => $level_1)
                        {
                            // echo '<tr>
                            //         <td width="75%;">
                            //             <table class="table table-borderless" style="width:100%;" >';
 
                            echo '<tr class="group-'.$level_1['id'].' first_level">';
                                echo '<input type="hidden" name="option['.$level_1['id'].'][yn_caf_id]" class="yn_caf_id" value="'.(isset($level_1['yn_caf_id'])?$level_1['yn_caf_id']:"").'" >';
                                echo '<td width="2%;" class="text-left">'.$l1_index.'. </td>';
                                echo '<td width="73%;" colspan="3" style="text-align:left;">'.$level_1['text'].'</td>';
                                echo '<td width="8%;" style="border-left:1px solid black;border-right:1px solid black;" class="y_n_td text-center">';
                                echo form_dropdown('option['.$level_1['id'].'][yn]', $y_n_na_dropdown, (isset($level_1['yn_value'])?$level_1['yn_value']:""), 'class="y_n_na select2" style="width:100%;"');
                                echo '</td>';
                                echo '<td class="review_td" width="17%;" style="border-left:1px solid black;border-right:1px solid black;text-align:left;"><textarea name="option['.$level_1['id'].'][remark]" rows="1" class="form-control">'.(isset($level_1['remark'])?$level_1['remark']:"").'</textarea></td>';
                            echo '</tr>';

                            $l1_index++;

                            $l2_index = 'a';
                            if(count($level_1['child']) > 0)
                            {
                                // echo '<tr>';
                                //     echo '<td width="2%;" class="text-left"></td>';
                                //     echo '<td width="2%;" style="text-align:left;padding:0;">
                                //             <table class="table table-borderless nested_audit_procedure_table" style="width:100%;" >'; 

                                foreach ($level_1['child'] as $l2_key => $level_2) 
                                {
                                    echo '<tr class="group-'.$level_1['id'].' second_gp-'.$level_2['id'].' second_level">';
                                        echo '<input type="hidden" name="option['.$level_2['id'].'][yn_caf_id]" class="yn_caf_id" value="'.(isset($level_2['yn_caf_id'])?$level_2['yn_caf_id']:"").'" >';
                                        echo '<td width="2%;"></td>';
                                        echo '<td width="2%;" class="text-left">('.$l2_index.')</td>';
                                        echo '<td width="71%;" colspan="2" style="text-align:left;">'.$level_2['text'].'</td>';
                                        echo '<td width="8%;" style="border-left:1px solid black;border-right:1px solid black;" class=" y_n_td text-center">';
                                        echo form_dropdown('option['.$level_2['id'].'][yn]', $y_n_na_dropdown, (isset($level_2['yn_value'])?$level_2['yn_value']:""), 'class="y_n_na select2" style="width:100%;"');
                                        echo '</td>';
                                        echo '<td class="review_td" width="17%;" style="border-left:1px solid black;border-right:1px solid black;text-align:left;"><textarea name="option['.$level_2['id'].'][remark]" rows="1" class="form-control">'.(isset($level_2['remark'])?$level_2['remark']:"").'</textarea></td>';
                                    echo '</tr>';

                                    $l2_index++;

                                    $l3_index = '1';
                                    if(count($level_2['child']) > 0)
                                    {
                            //             echo '<tr>';
                            //                     echo '<td width="2%;" class="text-left"></td>';
                            //                     echo '<td width="98%;" style="text-align:left;padding:0;">
                            //                             <table class="table table-borderless nested_audit_procedure_table" style="width:100%;" >'; 
                                        foreach ($level_2['child'] as $l3_key => $level_3) 
                                        {
                                             
                                            // echo    '<tr>
                                            //             <td class="borderless text-center" style="width:2%;">('.strtolower(numberToRomanRepresentation($l3_index)).')
                                                            
                                            //             </td>
                                            //             <td class="borderless text-left" style="width:98%;">'
                                            //                 .$level_3['text'].
                                            //             '</td>
                                            //         </tr>';

                                            echo '<tr class="group-'.$level_1['id'].' second_gp-'.$level_2['id'].'">';
                                                echo '<input type="hidden" name="option['.$level_3['id'].'][yn_caf_id]" class="yn_caf_id" value="'.(isset($level_3['yn_caf_id'])?$level_3['yn_caf_id']:"").'" >';
                                                echo '<td width="2%;"></td>';
                                                echo '<td width="2%;"></td>';
                                                echo '<td width="2%;" class="text-left">('.strtolower(numberToRomanRepresentation($l3_index)).')</td>';
                                                echo '<td width="69%;" style="text-align:left;">'.$level_3['text'].'</td>';
                                                echo '<td width="8%;"  style="border-left:1px solid black;border-right:1px solid black;" class="y_n_td text-center">';
                                                echo form_dropdown('option['.$level_3['id'].'][yn]', $y_n_na_dropdown, (isset($level_3['yn_value'])?$level_3['yn_value']:""), 'class="y_n_na select2" style="width:100%;"');
                                                echo '</td>';
                                                echo '<td class="review_td" width="15%;" style="border-left:1px solid black;border-right:1px solid black;text-align:left;"><textarea name="option['.$level_3['id'].'][remark]" rows="1" class="form-control">'.(isset($level_3['remark'])?$level_3['remark']:"").'</textarea></td>';
                                            echo '</tr>';
                                            

                                            $l3_index++;

                            //                 $l4_index = 1;
                            //                 if(count($level_3['child']) > 0)
                            //                 {
                            //                     echo '<tr>';
                            //                             echo '<td width="2%;" class="text-left"></td>';
                            //                             echo '<td width="98%;" style="text-align:left;padding:0;">
                            //                                     <table class="table table-borderless nested_audit_procedure_table" style="width:100%;" >';
                                                                   
                            //                     foreach ($level_3['child'] as $l4_key => $level_4) {
                                                     
                            //                         echo    '<tr>
                            //                                     <td class="borderless text-center" style="width:2%;">('.strtolower(numberToRomanRepresentation($l4_index)).')
                                                                    
                            //                                     </td>
                            //                                     <td class="borderless text-left" style="width:98%;">'
                            //                                         .$level_4['text'].
                            //                                     '</td>
                            //                                 </tr>';
                                                    

                            //                         $l4_index++;
                            //                     }

                            //                     echo    '</table>
                            //                             </td>';
                            //                     echo '</tr>';

                            //                 }
                                        }

                            //             echo    '</table>
                            //                     </td>';
                            //             echo '</tr>';
                                    }

                                }
                            //     echo    '</table>
                            //             </td>';
                            //     echo '</tr>';
                            }

                            // echo '</table>
                            //       </td>
                            //       <td width="10%;" class="bordered-cell"></td>
                            //       <td width="15%;" class="bordered-cell"></td>
                            //       </tr>';


                        }
                    }

                    
                ?>
                
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
    var programme_content = JSON.parse('<?php echo isset($programme_content)?json_encode($programme_content):""; ?>'.replace(/[\r]?[\n]/g, '\\n'));
    var save_programme_yn_input_url = "<?php echo site_url('caf/save_programme_yn_input'); ?>";
    var export_programme_yn_pdf_url = "<?php echo (site_url('caf/export_programme_yn_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";
</script>


<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_yn.js" charset="utf-8"></script>
<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_toolbar.js" charset="utf-8"></script>
<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>