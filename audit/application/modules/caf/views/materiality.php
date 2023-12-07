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

.bordered 
{
	border:1px solid grey !important;
}

.custom-header
{
	background-color: #446687;
	color: white;
	text-align: center;
}
</style>




<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 0px;">
		<form id="materiality_form">
		<?php
			if($show_data_content)
			{

		?>

			<input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">
			<input type="hidden" class="materiality_id" name="id" value="<?=$materiality_id ?>">

			<table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black">
	
				<tr>
					<td colspan="4">
						<h5 style="color:black;"><b>DETERMINING OVERALL MATERIALITY</b></h5>
					</td>
				</tr>

				<tr>
					<td class="bordered custom-header" width="40%">Benchmark</td>
					<td class="bordered custom-header"width="20%">Initial value of benchmark</td>
					<td class="bordered custom-header" width="20%">Final value of benchmark</td>
					<td class="bordered custom-header" width="20%">% used</td>
				</tr>
				<tr>
					<td class="bordered" width="40%">
						<?php
						echo form_dropdown('benchmark', $benchmark_dropdown, (isset($materiality_data['benchmark'])?$materiality_data['benchmark']:''), 'class="benchmark select2" style="width:100%;"')
						?>
					</td>
					<td class="bordered" id="initial_benchmark_val" width="20%" align="right">
						-
					</td>
					<input type="hidden" name="initial_benchmark_value" class="form-control text-right initial_benchmark_input" value=""/>
					<td class="bordered" id="final_benchmark_val" width="20%" align="right">
						-
					</td>
					<input type="hidden" name="final_benchmark_value" class="form-control text-right final_benchmark_input" value=""/>
					<td class="bordered" width="20%">
						<input type="text" name="percent_used" class="form-control text-center percent_used" value="<?= (isset($materiality_data['percent_used'])?$materiality_data['percent_used']:'')?>"/>
					</td>
			
				</tr>
				<tr>

					<td colspan="4">&nbsp;</td>
			
				</tr>
				<tr>
					<td width="40%"></td>
					<td class="bordered custom-header"width="20%">Prior period materiality</td>
					<td class="bordered custom-header" width="20%">Initial assessment</td>
					<td class="bordered custom-header" width="20%">Final assessment</td>
				
				</tr>
				<tr>
					<td width="40%">Selected overall materiality ($)</td>
					<td class="bordered" width="20%">
						<input type="number" name="prior_period_materiality" class="form-control text-right prior_period_materiality" value="<?= (isset($materiality_data['prior_period_materiality'])?$materiality_data['prior_period_materiality']:'')?>" />
					</td>
					<td class="bordered text-right initial_assessment" width="20%">
						-
					</td>
					<input type="hidden" name="initial_assessment" class="form-control text-right initial_assessment_input"/>
					<td class="bordered text-right final_assessment" width="20%">
						-
						<!-- <input type="hidden" name="percent_used" class="form-control text-right "/> -->
					</td>
					<input type="hidden" name="final_assessment" class="form-control text-right final_assessment_input"/>
				
				</tr>
				
				<tr>

					<td colspan="4">&nbsp;</td>
			
				</tr>

				<tr>

					<td colspan="4">
						Document justification for selection of benchmark and selected manually percentage:
						<br>
						<textarea class="form-control" name="justification_text"  rows="2" style="width:100%"><?= (isset($materiality_data['justification_text'])?$materiality_data['justification_text']:'')?></textarea>
					</td>
			
				</tr>
				<tr>

					<td colspan="4">&nbsp;</td>
			
				</tr>

				<tr>
					<td colspan="4">
						<h5 style="color:black;"><b>DETERMINING CLEARLY TRIVAL THRESHOLD</b></h5>
					</td>
				</tr>

				<tr>
					<td colspan="3"></td>
					<td class="bordered custom-header" width="20%">Amount</td>
				
				</tr>

				<tr>
					<td colspan="3">Clearly trival threshold percentage (%)</td>
					<td class="bordered custom-header" width="20%">5%</td>
				
				</tr>

				<tr>
					<td colspan="3">Calculated clearly trival threshold ($)</td>
					<td class="bordered text-right trival_threshold" width="20%">-</td>
					<input type="hidden" name="trival_threshold" class="form-control text-right trival_threshold_input"/>
				
				</tr>
		
			</table>
			<?php 
				}
				else
				{
					echo '<p style="text-align:center; font-weight: bold; font-size: 20px; margin-top:50px;">Account Category is not managed!</p>' .
						 '<p style="text-align:center; font-weight: bold; font-size: 14px; margin-top:10px;">Please complete Account Category before preview this document!</p>';
				}
			?>
			
		</form>
	</section>
</section>

<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>

<?php include('application/modules/caf/template/adjustment_popup.html'); ?>

<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';

	var benchmark_value = '<?php echo isset($benchmark_value)?$benchmark_value:""; ?>';
	benchmark_value = JSON.parse(benchmark_value);

	var save_materiality_url = "<?php echo site_url('caf/save_materiality'); ?>";
	var export_materiality_pdf_url = "<?php echo (site_url('caf/export_materiality_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";

</script>


<script src="<?= base_url()?>application/modules/caf/js/materiality.js" charset="utf-8"></script>

<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>