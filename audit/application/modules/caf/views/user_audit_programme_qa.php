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
        <form id="user_audit_programme_qa_form">

            <input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="audit_objectives_table">
                <tr>
                    <td colspan="3">
                        <h4 style="color:black;padding:0;margin:0;"><b>AUDIT PROGRAMME - <?= (isset($programme_master_detail->title)?strtoupper($programme_master_detail->title):'')?></b></h4>
                    </td>
                </tr>


            </table>

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black">
                <?php 
                    if($programme_question_lines)
                    {
                        foreach($programme_question_lines as $key => $line)
                        {
                            echo '<tr>';
                                echo '<td class="text-left" width="4%">'.($key + 1).'. </td>';
                                echo '<td style="text-align:left;" width="96%">'.$line->question_text.'</td>';
                            echo '</tr>';
                            echo '<tr>';
                                echo '<input type="hidden" name="option['.$line->id.'][qa_caf_id]" class="qa_caf_id" value="'.(isset($line->qa_caf_id)?$line->qa_caf_id:"").'" >';
                                echo '<td class="text-left"></td>';
                                echo '<td style="text-align:left;"><textarea name="option['.$line->id.'][answer]" rows="2" class="form-control">'.(isset($line->answer_text)?$line->answer_text:"").'</textarea></td>';
                            echo '</tr>';
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
    // var programme_content = JSON.parse('<?php echo isset($programme_content)?json_encode($programme_content):""; ?>');
    var save_programme_qa_input_url = "<?php echo site_url('caf/save_programme_qa_input'); ?>";
    var export_programme_qa_pdf_url = "<?php echo (site_url('caf/export_programme_qa_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";
</script>


<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_qa.js" charset="utf-8"></script>
<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_toolbar.js" charset="utf-8"></script>
<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>