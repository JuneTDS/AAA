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

.formula-table > tbody > tr > td
{
	text-align: center;
}

.materiality-matrix-table > tbody > tr > td
{
	border: 1px solid black;
	text-align: center;
}

.border-top
{
	border-top: 1px solid black;
}

.border-left
{
	border-left: 1px solid black;
}

.border-right
{
	border-right: 1px solid black;
}

.border-bottom
{
	border-bottom: 1px solid black;
}

.border-all
{
	border: 1px solid black;
}

.revenue_factor_table
{
	display: none;
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

			<table style="border-collapse: collapse; margin: 2%; width: 65%; color:black;">
	
				<tr>
					<td colspan="5">
						<h5 style="color:black;"><b>A. Computation of Materiality</b></h5>
					</td>
				</tr>

				<tr>
					<td></td>
					<td valign="top">1</td>
					<td colspan="4">
						Understand the Audit Committee's, Directors and/or Management's Views on Materiality
						<br><br>
						<textarea class="form-control" name="understand_on_materiality"  rows="2" style="width:100%"><?= (isset($materiality_data['understand_on_materiality'])?$materiality_data['understand_on_materiality']:'')?></textarea>
						<br>
					</td>
				</tr>
				<tr>
					<td></td>
					<td valign="top">2</td>
					<td colspan="4">
						Identify the Materiality Benchmark
						<br><br>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td valign="top">(i)</td>
					<td colspan="2">
						Select the most relevant materiality benchmark:
						<br><br>
					</td>
					<td class="text-center" style="border-bottom: 1px solid black;">
						Measurement Percentage
					</td>
				</tr>

				<tr>
					<td colspan="3"></td>
					<td></td>
					<td>
						<b>Income from continuing operations: </b>
					</td>
					<td></td>
				</tr>

				<tr>
					<td colspan="3"></td>
					<td><input type="radio" class="relevant_materiality" name="benchmark" value="1"></td>
					<td>
						- Private company
					</td>
					<td class="text-center" >5% - 15%</td>
				</tr>

				<tr>
					<td colspan="3"></td>
					<td><input type="radio" class="relevant_materiality" name="benchmark" value="2"></td>
					<td>
						- Public company
					</td>
					<td class="text-center" >5% - 10%</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td></td>
					<td>
						<b>Normalised income from continuing operations: </b>
					</td>
					<td></td>
				</tr>

				<tr>
					<td colspan="3"></td>
					<td><input type="radio" class="relevant_materiality" name="benchmark" value="3"></td>
					<td>
						- Private company
					</td>
					<td class="text-center" >5% - 15%</td>
				</tr>

				<tr>
					<td colspan="3"></td>
					<td><input type="radio" class="relevant_materiality" name="benchmark" value="4"></td>
					<td>
						- Public company
					</td>
					<td class="text-center" >5% - 10%</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td></td>
					<td>
						<b>Total revenue</b>
					</td>
					<td></td>
				</tr>

				<tr>
					<td colspan="3"></td>
					<td><input type="radio" class="relevant_materiality" name="benchmark" value="5"></td>
					<td>
						- Private company
					</td>
					<td class="text-center" >1% - 10%</td>
				</tr>

				<tr>
					<td colspan="3"></td>
					<td><input type="radio" class="relevant_materiality" name="benchmark" value="6"></td>
					<td>
						- Public company
					</td>
					<td class="text-center" >1% - 5%</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" class="relevant_materiality" name="benchmark" value="7"></td>
					<td>
						<b>Total assets</b>
					</td>
					<td class="text-center" >0.5% - 5%</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" class="relevant_materiality" name="benchmark" value="8"></td>
					<td>
						<b>Net assets or equity</b>
					</td>
					<td class="text-center" >1% - 10%</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" class="relevant_materiality" name="benchmark" value="9"></td>
					<td>
						<b>Total expenditure</b>
					</td>
					<td class="text-center" >1% - 10%</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" class="relevant_materiality" name="benchmark" value="10"></td>
					<td>
						<b>Other (specify benchmark and percentage):</b>
					</td>
					<td></td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td valign="top">(ii)</td>
					<td colspan="3">
						Indicate benchmark amount (monetary value)
						<br><br>
					</td>
				</tr>

				<tr>
					<td colspan="3"></td>
					<td colspan="3"><input type="number" name="initial_benchmark_value" class="form-control initial_benchmark_input" value=""></td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td valign="top">(iii)</td>
					<td colspan="3">
						Indicate the source of the benchmark:
						<br><br>
					</td>
					
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" name="benchmark_source" value="1"></td>
					<td colspan="2">
						Prior year financial statements
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" name="benchmark_source" value="2"></td>
					<td colspan="2">
						Current year projection (indicate date below)
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" name="benchmark_source" value="3"></td>
					<td colspan="2">
						Computation (show below)
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" name="benchmark_source" value="4"></td>
					<td colspan="2">
						Other (describe below)
					</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td colspan="4">
						<textarea class="form-control" name="benchmark_source_description"  rows="2" style="width:100%"><?= (isset($materiality_data['benchmark_source_description'])?$materiality_data['benchmark_source_description']:'')?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td valign="top">(iv)</td>
					<td colspan="3">
						Indicate the reason for the benchmark selection (check one):
						<br><br>
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" name="benchmark_reason" value="1"></td>
					<td colspan="2">
						For-profit company operating under normal circumstances
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" name="benchmark_reason" value="2"></td>
					<td colspan="2">
						Companies with volatile income
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" name="benchmark_reason" value="3"></td>
					<td colspan="2">
						Companies operating at breakeven level
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" name="benchmark_source" value="4"></td>
					<td colspan="2">
						Companies reporting losses
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" name="benchmark_reason" value="5"></td>
					<td colspan="2">
						Buy-sell agreements
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" name="benchmark_reason" value="6"></td>
					<td colspan="2">
						Organisations that do not report earnings, e.g. not-for-profit ang governmant entities
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" name="benchmark_reason" value="7"></td>
					<td colspan="2">
						Cost-plus entities
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td><input type="radio" name="benchmark_reason" value="8"></td>
					<td colspan="2">
						Other
					</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr>
					<td></td>
					<td valign="top">3</td>
					<td colspan="4">
						Determine the Measurement Percentage
						<br><br>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td valign="top">(i)</td>
					<td colspan="3">
						For the identified benchmark, select the most relevant materiality measurement percentage:
						<br><br>
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td colspan="3"><input type="number" name="materiality_measurement_percentage" style="display:inline-block;width: 20%;"  class="form-control materiality_measurement_percentage" value="<?= (isset($materiality_data['materiality_measurement_percentage'])?$materiality_data['materiality_measurement_percentage']:'')?>">%</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td valign="top">(ii)</td>
					<td colspan="3">
						Indicate the reason for percentage:
						<br><br>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td colspan="4">
						<textarea class="form-control" name="reason_for_percentage"  rows="2" style="width:100%"><?= (isset($materiality_data['reason_for_percentage'])?$materiality_data['reason_for_percentage']:'')?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr>
					<td></td>
					<td valign="top">4</td>
					<td colspan="4">
						Calculate Materiality
						<br><br>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td colspan="4">
						<table class="formula-table" width="100%;">
							<tr>
								<td>Benchmark Amount (from Step 2ii)</td>
								<td></td>
								<td>Measurement Percentage (from Step 3i)</td>
								<td></td>
								<td>Materiality Amount</td>
								<td></td>
								<td>Rounding</td>
							</tr>
							<tr style="line-height: 32px">
								<td class="bordered">$ <span id="initial_benchmark_val" class="initial_benchmark_calculate"></span></td>
								<td> x </td>
								<td class="bordered"><span class="measurement_percentage_calculate"></span> %</td>
								<td> = </td>
								<td class="bordered">$ <span class="materiality_calculate"></span></td>
								<td>&nbsp;</td>
								<td class="bordered"><span class="materiality_rounding_calculate"></span></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr>
					<td colspan="6">
						<h5 style="color:black;"><b>B. Computation of Performance Materiality</b></h5>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td colspan="4">
						<table width="100%;">
							<tr>
								<td>1 Determine the Materiality</td>
								<td>2 Engagement risk category</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<!-- <td rowspan="5"><div class="bordered materiality_rounding_calculate" style="height: 32px;width: 100px;text-align: center;vertical-align: middle;display: table-cell;"></div></td> -->
								<td rowspan="5" vertical-align="middle">
									<input type="number" name="editable_materiality" style="display:inline-block;width: 100px;"  class="form-control editable_materiality" value="">
									<a style="margin-left:5px; cursor:pointer;" onclick="reset_materiality()"><i style="font-size:17px;top: 4px !important;" class="glyphicon glyphicon-repeat"></i></a>
								</td>
								<td><input type="radio" class="engagement_risk" name="engagement_risk" value="1"> Low Risk</td>
							</tr>
							<tr>
								<td><input type="radio" class="engagement_risk" name="engagement_risk" value="2"> Medium Risk</td>
							</tr>
							<tr>
								<td><input type="radio" class="engagement_risk" name="engagement_risk" value="3"> High Risk</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td></td>
					<td></td>
					<td colspan="4">
						Include support for Step 2
						<br><br>
						<textarea class="form-control" name="support_for_risk"  rows="2" style="width:100%"><?= (isset($materiality_data['support_for_risk'])?$materiality_data['support_for_risk']:'')?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr>
					<td></td>
					<td valign="top">3</td>
					<td colspan="4">
						Determine the Performance Materiality Percentage
						<br><br>
					</td>
				</tr>

				<tr>
					<td></td>
					<td></td>
					<td colspan="4">
						<table class="materiality-matrix-table" width="50%;" style="border: 1px solid black;">
							<tr>
								<th colspan="2" class="text-center">Performance Materiality Matrix</th>
							</tr>
							<tr>
								<td>Engagement Risk Category (from Step 2)</td>
								<td>Performance Materiality as a % of Materiality</td>
							</tr>
							<tr>
								<td> Low </td>
								<td><input type="radio" class="performance_materiality_percentage" name="performance_materiality_percentage" value="1"> 85% </td>
							</tr>
							<tr>
								<td> Medium </td>
								<td><input type="radio" class="performance_materiality_percentage" name="performance_materiality_percentage" value="2"> 80% </td>
							</tr>
							<tr>
								<td> High </td>
								<td><input type="radio" class="performance_materiality_percentage" name="performance_materiality_percentage" value="3"> 70%</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr>
					<td></td>
					<td valign="top">4</td>
					<td colspan="4">
						Calculate Performance Materiality
						<br><br>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td colspan="4">
						<table class="formula-table" width="100%;">
							<tr>
								<td>Performance Materiality % (from Step 3)</td>
								<td></td>
								<td>Materiality (from Step 1)</td>
								<td></td>
								<td>Performance Materiality</td>
								<td></td>
								<td>Rounding</td>
							</tr>
							<tr style="line-height: 32px">
								<td class="bordered"><span class="materiality_percentage_calculate"></span> %</td>
								<td> x </td>
								<td class="bordered"><span class="step1_materiality"></span></td>
								<td> = </td>
								<td class="bordered">$ <span class="performance_materiality"></span></td>
								<td>&nbsp;</td>
								<td class="bordered"><span class="performance_materiality_rounding"></span></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr>
					<td colspan="6">
						<h5 style="color:black;"><b>C. Determination of CTT scope</b></h5>
					</td>
				</tr>

				<tr>
					<td></td>
					<td></td>
					<td colspan="4">
						Clearly Trivial Treshold (CTT)
						<br><br>
					</td>
				</tr>
				<tr>
					<td colspan="3"></td>
					<td>Basis: </td>
					<td colspan="2" style="border-bottom: 1px solid black;">
						<input type="number" name="ctt_basis_percentage" style="width:20%;display: inline-block;" class="form-control ctt_basis_input" min="1" max="10" value="<?= (isset($materiality_data['ctt_basis_percentage'])?$materiality_data['ctt_basis_percentage']:'')?>">% of Materiality
					</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td colspan="4">
						<table class="formula-table" width="100%;">
							<tr>
								<td>Applicable %</td>
								<td></td>
								<td>Applicable Basis</td>
								<td></td>
								<td>CTT Scope</td>
								<td></td>
								<td>Rounding</td>
							</tr>
							<tr style="line-height: 32px">
								<td class="bordered"><span class="ctt_basis"></span> %</td>
								<td> x </td>
								<td class="bordered">$ <span class="step1_materiality"></span></td>
								<td> = </td>
								<td class="bordered">$ <span class="ctt_scope"></span></td>
								<td>&nbsp;</td>
								<td class="bordered"><span class="ctt_scope_rounding"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr class="revenue_factor_table">
					<td class="text-center" style="border: 1px solid black;" colspan="6"><b>Revenue Sliding Scale - Materiality Factor</b></td>					
				</tr>
				<tr>
					<td colspan="6">&nbsp;</td>					
				</tr>
				<tr class="revenue_factor_table">
					<td class="text-center" colspan="5">
						<table width="100%;" style="margin-right: 20px;">
							<tr>
								<th>&nbsp;</th>
								<th colspan="4" class="text-center border-all"><b>Privately-Held Entities</b></th>
								
							</tr>
							<tr class="border-all">
								<th class="text-center border-top border-left border-right" >Revenue</th>
								<th colspan="2" class="text-center">Factor to apply</th>
								<th colspan="2" class="text-center border-right">Percentage to apply</th>
						
							</tr>
							<tr class="border-all">
								<th class="text-center border-bottom border-left border-right">Up to:</th>
								<th class="border-bottom text-center">Between</th>
								<th class="border-bottom text-center">And</th>
								<th class="border-bottom text-center">Between</th>
								<th class="border-right border-bottom">And</th>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">500,000</td>
								<td class="text-right">0.096</td>
								<td class="text-right">0.100</td>
								<td class="text-right">9.6%</td>
								<td class="text-right">10.0%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">600,000</td>
								<td class="text-right">0.092</td>
								<td class="text-right">0.096</td>
								<td class="text-right">9.2%</td>
								<td class="text-right">9.6%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">700,000</td>
								<td class="text-right">0.088</td>
								<td class="text-right">0.092</td>
								<td class="text-right">8.8%</td>
								<td class="text-right">9.2%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">800,000</td>
								<td class="text-right">0.084</td>
								<td class="text-right">0.088</td>
								<td class="text-right">8.4%</td>
								<td class="text-right">8.8%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">900,000</td>
								<td class="text-right">0.080</td>
								<td class="text-right">0.084</td>
								<td class="text-right">8.0%</td>
								<td class="text-right">8.4%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">1,000,000</td>
								<td class="text-right">0.077</td>
								<td class="text-right">0.080</td>
								<td class="text-right">7.7%</td>
								<td class="text-right">8.0%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">2,000,000</td>
								<td class="text-right">0.074</td>
								<td class="text-right">0.077</td>
								<td class="text-right">7.4%</td>
								<td class="text-right">7.7%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">6,000,000</td>
								<td class="text-right">0.071</td>
								<td class="text-right">0.074</td>
								<td class="text-right">7.1%</td>
								<td class="text-right">7.4%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">10,000,000</td>
								<td class="text-right">0.069</td>
								<td class="text-right">0.071</td>
								<td class="text-right">6.9%</td>
								<td class="text-right">7.1%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">15,000,000</td>
								<td class="text-right">0.067</td>
								<td class="text-right">0.069</td>
								<td class="text-right">6.7%</td>
								<td class="text-right">6.9%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">30,000,000</td>
								<td class="text-right">0.065</td>
								<td class="text-right">0.067</td>
								<td class="text-right">6.5%</td>
								<td class="text-right">6.7%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">50,000,000</td>
								<td class="text-right">0.063</td>
								<td class="text-right">0.065</td>
								<td class="text-right">6.3%</td>
								<td class="text-right">6.5%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">100,000,000</td>
								<td class="text-right">0.062</td>
								<td class="text-right">0.063</td>
								<td class="text-right">6.2%</td>
								<td class="text-right">6.3%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">300,000,000</td>
								<td class="text-right">0.061</td>
								<td class="text-right">0.062</td>
								<td class="text-right">6.1%</td>
								<td class="text-right">6.2%</td>
							</tr>
							<tr class="border-all">
								<td class="bordered text-right">1,000,000,000</td>
								<td class="text-right">0.060</td>
								<td class="text-right">0.061</td>
								<td class="text-right">6.0%</td>
								<td class="text-right">6.1%</td>
							</tr>
						</table>
					</td>					
					<td class="text-center" >
						<table width="100%;" style="margin-left: 20px;">
							<tr>
								<th colspan="4" class="text-center border-all"><b>Privately-Held Entities & Public-Interest Entities</b></th>
							</tr>
							<tr>
								<th colspan="2" class="text-center border-left">Factor to apply</th>
								<th colspan="2" class="text-center border-right">Percentage to apply</th>
						
							</tr>
							<tr>
								<th class="border-bottom border-left text-center">Between</th>
								<th class="border-bottom text-center">And</th>
								<th class="border-bottom text-center">Between</th>
								<th class="border-bottom text-center border-right">And</th>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.046</td>
								<td class="text-right">0.050</td>
								<td class="text-right">4.6%</td>
								<td class="text-right">5.0%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.042</td>
								<td class="text-right">0.046</td>
								<td class="text-right">4.2%</td>
								<td class="text-right">4.6%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.038</td>
								<td class="text-right">0.042</td>
								<td class="text-right">3.8%</td>
								<td class="text-right">4.2%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.034</td>
								<td class="text-right">0.038</td>
								<td class="text-right">3.4%</td>
								<td class="text-right">3.8%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.030</td>
								<td class="text-right">0.034</td>
								<td class="text-right">3.0%</td>
								<td class="text-right">3.4%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.027</td>
								<td class="text-right">0.030</td>
								<td class="text-right">2.7%</td>
								<td class="text-right">3.0%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.024</td>
								<td class="text-right">0.027</td>
								<td class="text-right">2.4%</td>
								<td class="text-right">2.7%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.021</td>
								<td class="text-right">0.024</td>
								<td class="text-right">2.1%</td>
								<td class="text-right">2.4%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.019</td>
								<td class="text-right">0.021</td>
								<td class="text-right">1.9%</td>
								<td class="text-right">2.1%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.017</td>
								<td class="text-right">0.019</td>
								<td class="text-right">1.7%</td>
								<td class="text-right">1.9%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.015</td>
								<td class="text-right">0.017</td>
								<td class="text-right">1.5%</td>
								<td class="text-right">1.7%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.013</td>
								<td class="text-right">0.015</td>
								<td class="text-right">1.3%</td>
								<td class="text-right">1.5%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.012</td>
								<td class="text-right">0.013</td>
								<td class="text-right">1.2%</td>
								<td class="text-right">1.3%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.011</td>
								<td class="text-right">0.012</td>
								<td class="text-right">1.1%</td>
								<td class="text-right">1.2%</td>
							</tr>
							<tr class="border-all">
								<td class="text-right">0.010</td>
								<td class="text-right">0.011</td>
								<td class="text-right">1.0%</td>
								<td class="text-right">1.1%</td>
							</tr>
						</table>
					</td>					
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

	var materiality_data = '<?php echo isset($materiality_data)?json_encode($materiality_data):""; ?>';
	// console.log(jsonEscape(materiality_data));
	materiality_data = JSON.parse(jsonEscape(materiality_data));

	var save_materiality_v2_url = "<?php echo site_url('caf/save_materiality_v2'); ?>";
	var export_materiality_v2_pdf_url = "<?php echo (site_url('caf/export_materiality_v2_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";


	function jsonEscape(str)  {
		return str.replace(/\n/g, "\\\\n").replace(/\r/g, "\\\\r").replace(/\t/g, "\\\\t");
	}
</script>


<script src="<?= base_url()?>application/modules/caf/js/materiality_v2.js" charset="utf-8"></script>

<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>