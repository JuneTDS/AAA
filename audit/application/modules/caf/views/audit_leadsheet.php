<script src="<?= base_url() ?>node_modules/webix/webix/codebase/webix.js" type="text/javascript"></script>
<script src="<?= base_url() ?>node_modules/webix/spreadsheet/codebase/spreadsheet.js" type="text/javascript"></script>
 
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>node_modules/webix/webix/codebase/webix.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>node_modules/webix/spreadsheet/codebase/spreadsheet.css">

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

	.icon_linknote
	{
		position:relative;
	    left: -4px;
	    margin: 6px;
	  	content: url("<?= base_url() ?>/img/note.png");
	}


	
</style>

<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel"  style="margin-top: 0px;">
		<table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="audit_objectives_table">
            <tr>
                <td colspan="3">
                    <h4 style="color:black;padding:0;margin:0;"><b>LEAD SHEET - <?= (isset($caf_detail->name)?strtoupper($caf_detail->name):'')?></b></h4>
                </td>
            </tr>
              <tr>
                <td width="2%;">
                    <input type="checkbox" class="leadsheet_flag cbx" onclick="change_setup(this);" <?=(isset($leadsheet_setup['leadsheet_flag'])?($leadsheet_setup['leadsheet_flag']==1?"checked":""):"checked")?> name="leadsheet_flag" value="<?=$caf_id ?>">
                </td>
                <td width="98%;" colspan="2">
                    <b>Lead</b>
                </td>
            </tr>



        </table>
        
    	<?php
			if($show_data_content)
			{	

		?>
			<input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">


			<h5 id="account_balance_msg" style="color:red"></h5>

			<table  class="table table-hover table-borderless leadsheet_table" style="border-collapse: collapse; margin: 2%; width: 95%; color:black">
				<thead>
					<tr>
						<th style="width: <?=$width['account_desc'] ?>%;">&nbsp;</th>
						<th style="width: <?=$width['value'] ?>%; text-align: center;">
							<?php
								if($show_third_col)
								{
									echo '</br>';
								} 
								echo $current_fye_end ."<br/>". "Unadjusted"; 
							?>	
						</th>
						<th style="width: <?=$width['value'] ?>%; text-align: center;" colspan=2>
							<?php
								if($show_third_col)
								{
									echo '</br>';
								} 
								echo "<br/>Adjustments"
							?>	
						</th>
						<th style="width: <?=$width['value'] ?>%; text-align: center;">
							<?php
								if($show_third_col)
								{
									echo '</br>';
								} 
								echo $current_fye_end ."<br/>". "Final"; 
							?>	
						</th>
						

						<?php 
							if(!empty($last_fye_end))
							{
								echo 
								'<th style="width: ' . $width['value'] . '%; text-align: center;">';
									if($show_third_col)
									{
										echo 'Restated</br>';
									}
									echo $last_fye_end."<br/>". "Final"; 
								echo '</th>';

								if($show_third_col)
								{
									echo '<th style="width: ' . $width['value'] . '%; text-align: center;">Restated</br>' . $last_fye_beg . '</th>';
								}

								echo '<th style="width:'.$width['variance']*2 .'%;text-align: center;" colspan=2><br/>Variance</th>';

							}

							if($last_fye_end)
							{
								$last_fye_end_flag = true;
							}
							else
							{
								$last_fye_end_flag = false;
							}
						?>
						
					</tr>

					<tr>
						<th style="width: <?=$width['account_desc'] ?>%;"></th>
						<th style="width: <?=$width['value'] ?>%; text-align: center; border-top: 1px solid #000;"></br>$</th>
						<th style="width: <?=$width['value'] ?>%; text-align: center; border-top: 1px solid #000;">DR</br>$</th>
						<th style="width: <?=$width['value'] ?>%; text-align: center; border-top: 1px solid #000;">CR</br>$</th>
						<th style="width: <?=$width['value'] ?>%; text-align: center; border-top: 1px solid #000;"></br>$</th>

						<?php 
							if(!empty($last_fye_end))
							{
								echo '<th style="width:'.$width['value'].'%; text-align: center; border-top: 1px solid #000;"></br>$</th>';

								if($show_third_col)
								{
									echo '<th style="width:'.$width['value'].'%; text-align: center; border-top: 1px solid #000;"></br>$</th>';
								}
								
								echo '<th style="width:'.$width['variance'].'%;text-align: center;border-top: 1px solid #000;"></br>$</th>
									<th style="width:'.$width['variance'] .'%;text-align: center;border-top: 1px solid #000;"></br>%</th>';
						
							}
						?>
						

					</tr>
				</thead>
				<tbody>
					<?php

						$grand_total_c 			= 0.00;
						$grand_total_c_adjusted = 0.00;
						$grand_total_c_end 	    = 0.00;

						foreach ($data[0]['child_array'] as $level_1_key => $level_1) 
						{
							// print_r($level_2);
						
							$check_l2_parent_counter = 0;
							$temp_total_c 	  	   = 0.00;
							$temp_total_c_adjusted = 0.00;
							$temp_total_c_end 	   = 0.00;
							$temp_total_c_beg 	   = 0.00;
							
							if(!empty($level_1['parent_array']))
							{
								echo 
								'<tr>'.
									'<td><strong><u>' . ucfirst(strtolower($level_1['parent_array'][0]['description'])) . '</u></strong></td>' .
									'<td align="center"></td>' .
									'<td style=">&nbsp;</td>' .
									'<td colspan="9"></td>' .
								'</tr>';
							
							}

							foreach ($level_1['child_array'] as $level_2_key => $level_2)
							{
								// echo '</br></br>';
								// print_r($level_3);
								/* DISPLAY LEVEL 3 THAT HAS SUBCATEGORY */
								if(!empty($level_2['parent_array']))
								{
									$check_l2_parent_counter++;

									


									$temp_c 		 = isset($level_2['parent_array'][0]['total_c'])?$level_2['parent_array'][0]['total_c']:0;
									$temp_c_adjusted = isset($level_2['parent_array'][0]['total_c_adjusted'])?$level_2['parent_array'][0]['total_c_adjusted']:0;
									$temp_c_lye 	 = isset($level_2['parent_array'][0]['total_c_lye'])?$level_2['parent_array'][0]['total_c_lye']:0;
									
									if(!empty($temp_c) && !empty($level_2['parent_array'][0]['account_code']))
				            		{

				            			if($level_2['parent_array'][0]['account_code'][0] == "Q" || $level_2['parent_array'][0]['account_code'][0] == "R" || $level_2['parent_array'][0]['account_code'][0] == "L" || $level_2['parent_array'][0]['account_code'][0] == "I" || $level_1['parent_array'][0]['account_code'][0] == "Q" || $level_1['parent_array'][0]['account_code'][0] == "R" || $level_1['parent_array'][0]['account_code'][0] == "L" || $level_1['parent_array'][0]['account_code'][0] == "I")
			            				{
			            					$temp_c = $temp_c * (-1);
			            				}
			            				
				            		}
									$temp_id 		 = $level_2['parent_array'][0]['id'];
									// $temp_adjustment_info = isset($adjustment_data[$temp_id])?$adjustment_data[$temp_id][0]['adjust_value']:"";
									$temp_child_id = isset($level_2['parent_array'][0]['child_id'])?$level_2['parent_array'][0]['child_id']:"";
									$temp_adjustment_info = get_adjustment_of_child($temp_child_id, $adjustment_data);
									$temp_adjust_value = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['adjusted_value']:"";
									$temp_je_no = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['type_name'].$temp_adjustment_info[0]['je_no']:"";

									$adjustment_for_rev_rsrve = 0.00;
									$adjustment_for_pl = 0.00;

									$variance_value  = calculate_variance($temp_c_adjusted ,$temp_c_lye);

									

									// print_r($level_3['parent_array'][0]);
									// echo json_encode($level_3['fs_note_details']);
									echo 
									'<tr class="rows-for-'.$level_2['parent_array'][0]['id'].'">' .
										'<td>' .
											// hidden fields
											'<input type="hidden" name="FP_audit_categorized_account_id[]" class="SFP_audit_categorized_account_id" value="'. $level_2['parent_array'][0]['id'] .'">'.
											$level_2['parent_array'][0]['description'] . 
										'</td>';

										echo '<td align="right">' . negative_bracket($temp_c) . '</td>';

										// adjustment detail for PL when no adjustment of retained earning
										if($level_2['parent_array'][0]['account_code'] == 'Q1030000' && $temp_adjust_value == "")
										{
											//multiply with -1 because retained earning is opposite sign
											$adjustment_for_pl = -1*($temp_c_adjusted - $temp_c);
						
											if($adjustment_for_pl > 0)
											{
												echo 
													'<td align="right"><span style="float:left"><strong>P/L</strong></span>'.negative_bracket($adjustment_for_pl).'</td>';
												echo 
													'<td align="right"></td>';
											}
											else if($adjustment_for_pl < 0 && $adjustment_for_pl != 0)
											{
												echo 
												'<td align="right"></td>';

												echo 
												'<td align="right"><span style="float:left"><strong>P/L</strong></span>'.negative_bracket(abs($adjustment_for_pl)).'</td>';
											}
										}

										if($level_2['parent_array'][0]['account_code'] == 'Q1030000' && $temp_adjust_value != "")
										{
											$adjustment_for_rev_rsrve += $temp_adjust_value;
										}
										

										if($temp_adjust_value > 0)
										{
											echo 
												'<td align="right"><span style="float:left"><strong>'.$temp_je_no.'</strong></span>'.negative_bracket($temp_adjust_value).'</td>';
											echo 
												'<td align="right"></td>';
										}
										else if($temp_adjust_value < 0 && $temp_adjust_value != "")
										{
											echo 
												'<td align="right"></td>';
											echo 
												'<td align="right"><span style="float:left"><strong>'.$temp_je_no.'</strong></span>'.negative_bracket(abs($temp_adjust_value)).'</td>';
										}
										else if($temp_adjust_value == "" && $adjustment_for_pl == 0)
										{
											echo 
												'<td align="right"></td>';
											echo 
												'<td align="right"></td>';
										}
										echo '<td align="right">' . negative_bracket($temp_c_adjusted) . '</td>';
										
										if(!empty($last_fye_end))
										{

											echo 
											'<td align="right">' . negative_bracket($temp_c_lye) . '</td>';
										
										 	if($show_third_col)
											{
												echo 
												'<td align="right">' . 
													'<input type="number" name="' . $FP_lye_beg_value_name . '[' . $index . ']" class="form-control ' . $display_class_lye_beg . '_values_under_' . $level_1['parent_array'][0]['id'] . ' ' . $display_class_lye_beg .  '_account_code_' . $level_1_description . '" style="text-align:right;" value="' . $level_2['parent_array'][0]['company_beg_prev_ye_value'] . '" onchange="FP_calculation('. $level_1['parent_array'][0]['id'] .', \'' . $display_class_lye_beg . '\', \'' . $level_1_description . '\', \'' . "company" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
												'</td>';
											}
											echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
											echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';
										
										}



										$temp_total_c 	  	   += (double)$temp_c;
										$temp_total_c_adjusted += (double)$temp_c_adjusted;
										$temp_total_c_end 	   += (double)$temp_c_lye;
										$temp_total_c_beg 	   += (double)$level_2['parent_array'][0]['company_beg_prev_ye_value'];
										// }
									echo '</tr>';

									//loop all adjustment info 
									if(count($temp_adjustment_info) > 1)
									{
										foreach ($temp_adjustment_info as $info_key => $each_info) {
											if($info_key != 0)
											{
												$temp_adjust_value = $each_info['adjusted_value'];
												$temp_je_no = $each_info['type_name'].$each_info['je_no'];

												if($temp_adjust_value != "")
												{
													$adjustment_for_rev_rsrve += $temp_adjust_value;
												}

												echo '<tr>
														<td></td>
														<td></td>';
												if($temp_adjust_value > 0)
												{
													echo 
														'<td align="right"><span style="float:left"><strong>'.$temp_je_no.'</strong></span>'.negative_bracket($temp_adjust_value).'</td>';
													echo 
														'<td align="right"></td>';
												}
												else if($temp_adjust_value < 0 && $temp_adjust_value != "")
												{
													echo 
														'<td align="right"></td>';
													echo 
														'<td align="right"><span style="float:left"><strong>'.$temp_je_no.'</strong></span>'.negative_bracket(abs($temp_adjust_value)).'</td>';
												}
												else
												{
													echo 
														'<td align="right"></td>';
													echo 
														'<td align="right"></td>';
												}
												echo  '</tr>';
											}
										}
										
									}
								}
							}


							if(!empty($level_1['parent_array']))
							{
								if($check_l2_parent_counter == 0)
								{
									$temp_c 		 = isset($level_1['parent_array'][0]['total_c'])?$level_1['parent_array'][0]['total_c']:0;
									$temp_c_adjusted = isset($level_1['parent_array'][0]['total_c_adjusted'])?$level_1['parent_array'][0]['total_c_adjusted']:0;
									$temp_c_lye 	 = isset($level_1['parent_array'][0]['total_c_lye'])?$level_1['parent_array'][0]['total_c_lye']:0;
									
									if((!empty($temp_c) && !empty($level_1['parent_array'][0]['account_code'])) || (!empty($temp_c_lye) && !empty($level_1['parent_array'][0]['account_code'])))
				            		{
				            			// print_r($data[0]['parent_array'][0]);

				            			if($level_1['parent_array'][0]['account_code'][0] == "Q" || 
				            			   $level_1['parent_array'][0]['account_code'][0] == "R" || 
				            			   $level_1['parent_array'][0]['account_code'][0] == "L" || 
				            			   $level_1['parent_array'][0]['account_code'][0] == "I")
			            				{
			            					$temp_c 		 = $temp_c * (-1);
			            					$temp_c_adjusted = $temp_c_adjusted * (-1);
			            					$temp_c_lye 	 = $temp_c_lye * (-1);
			            				} 
			            				
				            		}
				            		else if((!empty($temp_c) && !empty($data[0]['parent_array'][0]['account_code'])) || (!empty($temp_c_lye) && !empty($data[0]['parent_array'][0]['account_code'])))
				            		{

				            			if($data[0]['parent_array'][0]['account_code'][0] == "Q" || 
				            			   $data[0]['parent_array'][0]['account_code'][0] == "R" || 
				            			   $data[0]['parent_array'][0]['account_code'][0] == "L" || 
				            			   $data[0]['parent_array'][0]['account_code'][0] == "I")
			            				{
			            					$temp_c 		 = $temp_c * (-1);
			            					$temp_c_adjusted = $temp_c_adjusted * (-1);
			            					$temp_c_lye 	 = $temp_c_lye * (-1);
			            				} 
			            				
				            		}

									$temp_id 		 = isset($level_1['parent_array'][0]['id'])?$level_1['parent_array'][0]['id']:"";
									// $temp_adjustment_info = isset($adjustment_data[$temp_id])?$adjustment_data[$temp_id][0]['adjust_value']:"";
									$temp_child_id = isset($level_1['parent_array'][0]['child_id'])?$level_1['parent_array'][0]['child_id']:"";
									$temp_adjustment_info = get_adjustment_of_child($temp_child_id, $adjustment_data);
									$temp_adjust_value = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['adjusted_value']:"";
									$temp_je_no = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['type_name'].$temp_adjustment_info[0]['je_no']:"";

									$adjustment_for_rev_rsrve = 0.00;
									$adjustment_for_pl = 0.00;

									$variance_value  = calculate_variance($temp_c_adjusted ,$temp_c_lye);

									// print_r($level_3['parent_array'][0]);
									// echo json_encode($level_3['fs_note_details']);
									echo 
									'<tr class="rows-for-'.$temp_id.'">' .
										'<td>' .
											// hidden fields
											'<input type="hidden" name="FP_audit_categorized_account_id[]" class="SFP_audit_categorized_account_id" value="'. $temp_id .'">' .
											$level_1['parent_array'][0]['description'] . 
										'</td>';

										echo '<td align="right">' . negative_bracket($temp_c) . '</td>';

										// adjustment detail for PL when no adjustment of retained earning
										if($level_1['parent_array'][0]['account_code'] == 'Q1030000' && $temp_adjust_value == "")
										{
											// print_r(array($adjustment_for_pl, "hi"));
											//multiply with -1 because retained earning is opposite sign
											$adjustment_for_pl = -1*($temp_c_adjusted - $temp_c);
						
											if($adjustment_for_pl > 0)
											{
												echo 
													'<td align="right"><span style="float:left"><strong>P/L</strong></span>'.negative_bracket($adjustment_for_pl).'</td>';
												echo 
													'<td align="right"></td>';
											}
											else if($adjustment_for_pl < 0 && $adjustment_for_pl != 0)
											{
												echo 
												'<td align="right"></td>';

												echo 
												'<td align="right"><span style="float:left"><strong>P/L</strong></span>'.negative_bracket(abs($adjustment_for_pl)).'</td>';
											}
										}

										if($level_1['parent_array'][0]['account_code'] == 'Q1030000' && $temp_adjust_value != "")
										{
											$adjustment_for_rev_rsrve += $temp_adjust_value;
										}
										

										if($temp_adjust_value > 0)
										{
											echo 
												'<td align="right"><span style="float:left"><strong>'.$temp_je_no.'</strong></span>'.negative_bracket($temp_adjust_value).'</td>';
											echo 
												'<td align="right"></td>';
										}
										else if($temp_adjust_value < 0 && $temp_adjust_value != "")
										{
											echo 
												'<td align="right"></td>';
											echo 
												'<td align="right"><span style="float:left"><strong>'.$temp_je_no.'</strong></span>'.negative_bracket(abs($temp_adjust_value)).'</td>';
										}
										else if($temp_adjust_value == "" && $adjustment_for_pl == 0)
										{
											echo 
												'<td align="right"></td>';
											echo 
												'<td align="right"></td>';
										}
										echo '<td align="right">' . negative_bracket($temp_c_adjusted) . '</td>';
										
										if(!empty($last_fye_end))
										{

											echo 
											'<td align="right">' . negative_bracket($temp_c_lye) . '</td>';
										
										 // 	if($show_third_col)
											// {
											// 	echo 
											// 	'<td align="right">' . 
											// 		'<input type="number" name="' . $FP_lye_beg_value_name . '[' . $index . ']" class="form-control ' . $display_class_lye_beg . '_values_under_' . $level_1['parent_array'][0]['id'] . ' ' . $display_class_lye_beg .  '_account_code_' . $level_1_description . '" style="text-align:right;" value="' . $level_2['parent_array'][0]['company_beg_prev_ye_value'] . '" onchange="FP_calculation('. $level_1['parent_array'][0]['id'] .', \'' . $display_class_lye_beg . '\', \'' . $level_1_description . '\', \'' . "company" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
											// 	'</td>';
											// }
											echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
											echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';
										
										}



										$temp_total_c 	  	   += (double)$temp_c;
										$temp_total_c_adjusted += (double)$temp_c_adjusted;
										$temp_total_c_end 	   += (double)$temp_c_lye;
										$temp_total_c_beg 	   += (double)$level_1['parent_array'][0]['company_beg_prev_ye_value'];
										// }
									echo '</tr>';

									//loop all adjustment info 
									if(count($temp_adjustment_info) > 1)
									{
										foreach ($temp_adjustment_info as $info_key => $each_info) {
											if($info_key != 0)
											{
												$temp_adjust_value = $each_info['adjusted_value'];
												$temp_je_no = $each_info['type_name'].$each_info['je_no'];

												if($temp_adjust_value != "")
												{
													$adjustment_for_rev_rsrve += $temp_adjust_value;
												}

												echo '<tr>
														<td></td>
														<td></td>';
												if($temp_adjust_value > 0)
												{
													echo 
														'<td align="right"><span style="float:left"><strong>'.$temp_je_no.'</strong></span>'.negative_bracket($temp_adjust_value).'</td>';
													echo 
														'<td align="right"></td>';
												}
												else if($temp_adjust_value < 0 && $temp_adjust_value != "")
												{
													echo 
														'<td align="right"></td>';
													echo 
														'<td align="right"><span style="float:left"><strong>'.$temp_je_no.'</strong></span>'.negative_bracket(abs($temp_adjust_value)).'</td>';
												}
												else
												{
													echo 
														'<td align="right"></td>';
													echo 
														'<td align="right"></td>';
												}
												echo  '</tr>';
											}
										}
										
									}

									// adjustment detail for PL when has adjustment of retained earning so that it will display below
									if($level_1['parent_array'][0]['account_code'] == 'Q1030000' && $adjustment_for_rev_rsrve != 0)
									{
										//multiply with -1 because retained earning is opposite sign
										$adjustment_for_pl = -1*($temp_c_adjusted - $temp_c) - $adjustment_for_rev_rsrve;
					
										echo '<tr>
												<td></td>
												<td></td>';
										if($adjustment_for_pl > 0)
										{
											echo 
												'<td align="right"><span style="float:left"><strong>P/L</strong></span>'.negative_bracket($adjustment_for_pl).'</td>';
											echo 
												'<td align="right"></td>';
										}
										else if($adjustment_for_pl < 0 && $adjustment_for_pl != 0)
										{
											echo 
											'<td align="right"></td>';

											echo 
											'<td align="right"><span style="float:left"><strong>P/L</strong></span>'.negative_bracket(abs($adjustment_for_pl)).'</td>';
										}
										echo  '</tr>';
									}
								}


								$variance_value  = calculate_variance($temp_total_c_adjusted ,$temp_total_c_end);

								echo 
									'<tr>' . 
										'<td></td>' . 
										'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
											'<span>' . 
												negative_bracket($temp_total_c) . 
											'</span>' . 
										'</td>';
										
									echo 
										'<td align="right"></td>';
									echo 
										'<td align="right"></td>';

									echo 
										'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
											'<span>' . 
												negative_bracket($temp_total_c_adjusted) . 
											'</span>' . 
										'</td>';

										if(!empty($last_fye_end))
										{
											echo 
											'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
												'<span>' . 
													negative_bracket($temp_total_c_end) . 
												'</span>' . 
											'</td>';

										 // 	if($show_third_col)
											// {
											// 	echo 
											// 	'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
											// 		'<span id="' . $display_class_lye_beg . '_subtotal_'. $level_2['parent_array'][0]['id'] .'">' . 
											// 			negative_bracket($total_beg) . 
											// 		'</span>' . 
											// 	'</td>';
											// }

											echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
											echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';

							
										}

										
										
									echo '</tr>';

									$grand_total_c 			+= $temp_total_c;
									$grand_total_c_adjusted += $temp_total_c_adjusted;
									$grand_total_c_end 	    += $temp_total_c_end;
							
							}


						}

						if(!empty($data[0]['parent_array']))
						{
				

							$variance_value  = calculate_variance($grand_total_c_adjusted ,$grand_total_c_end);

							echo 
								'<tr>' . 
									'<td></td>' . 
									'<td align="right" style="border-top: 1px solid #000; border-bottom: 5px double #000;">' . 
										'<span>' . 
											negative_bracket($grand_total_c) . 
										'</span>' . 
									'</td>';
									
								echo 
									'<td align="right"></td>';
								echo 
									'<td align="right"></td>';

								echo 
									'<td align="right" style="border-top: 1px solid #000; border-bottom: 5px double #000;">' . 
										'<span>' . 
											negative_bracket($grand_total_c_adjusted) . 
										'</span>' . 
									'</td>';

									if(!empty($last_fye_end))
									{
										echo 
										'<td align="right" style="border-top: 1px solid #000; border-bottom: 5px double #000;">' . 
											'<span>' . 
												negative_bracket($grand_total_c_end) . 
											'</span>' . 
										'</td>';

									 // 	if($show_third_col)
										// {
										// 	echo 
										// 	'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
										// 		'<span id="' . $display_class_lye_beg . '_subtotal_'. $level_2['parent_array'][0]['id'] .'">' . 
										// 			negative_bracket($total_beg) . 
										// 		'</span>' . 
										// 	'</td>';
										// }

										echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
										echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';

						
									}

									
									
								echo '</tr>';
					}


					?>
				</tbody>
        	</table>
        <?php
        	}
        ?>
        <hr/>

        <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="audit_objectives_table">
            
            <tr>
                <td width="2%;">

                    <input type="checkbox" onclick="change_setup(this);" class="documentation_flag cbx" name="documentation_route" <?=(isset($leadsheet_setup['documentation_flag'])?($leadsheet_setup['documentation_flag']==1?"checked":""):"checked")?> value="<?=$caf_id ?>" >
                </td>
                <td width="98%;" colspan="2">
                    <b>Documentation</b>
                </td>
            </tr>
            <tr class="leadsheet_doc_tr">
            	<td width="100%;" colspan="3">
            		<div id="leadsheet_doc" style="height: 600px;"></div>
            	</td>
            </tr>
  
<!-- 
            <tr>
            	<td>
            		<button onclick="generate_table()">HTML</button>
            	</td>
            </tr>
 -->
        </table>

        <div id="testing_table" class="wss_1">
     
        </div>
	</section>
</section>

<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>

<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	var caf_id = '<?php echo $caf_id; ?>';
	var assignment_id     = '<?php echo $assignment_id; ?>';
	var leadsheet_documentation = '<?php echo $leadsheet_documentation ?>';
	// console.log(leadsheet_documentation);
	if(leadsheet_documentation != "")
	{
		leadsheet_documentation = JSON.parse(leadsheet_documentation);
	}

	var display_documention = "<?php echo (isset($leadsheet_setup['documentation_flag'])?($leadsheet_setup['documentation_flag']==1?true:false):true); ?>";
	var display_leadsheet   = "<?php echo (isset($leadsheet_setup['leadsheet_flag'])?($leadsheet_setup['leadsheet_flag']==1?true:false):true) ?>";


	var save_leadsheet_doc_url = "<?php echo site_url('caf/save_leadsheet_doc'); ?>" + "/"+ caf_id + "/" + assignment_id;

	var export_audit_leadsheet_pdf_url = "<?php echo (site_url('caf/export_audit_leadsheet_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";
	var update_leadsheet_setup_flag_url = "<?php echo (site_url('caf/update_leadsheet_setup_flag')); ?>";



</script>

<script src="<?= base_url()?>application/modules/caf/js/audit_leadsheet.js" charset="utf-8"></script>
<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>

<?php

  	function negative_bracket($number)
  	{
      	if($number == 0)
      	{
          	return "-";
      	}
      	elseif($number < 0)
      	{
          	return "(" . number_format(abs($number),2) . ")";
      	}
      	else
      	{
          	return number_format($number,2);
      	}
  	}

  	function calculate_variance($current_yr, $last_yr)
  	{
  	  	$variance_arr = array();

  	  	$variance_arr['dollar'] = $current_yr - $last_yr;
  	  
  	  	if($last_yr == 0 || $last_yr == "" || $last_yr == null)
  	  	{
  	  		$variance_arr['percentage'] = 100;
  	  	}
  	  	else
  	  	{
  	  		$variance_arr['percentage'] = ($variance_arr['dollar']/$last_yr)*100;
  	  	}

  	  	return $variance_arr;
  	}

  	function get_adjustment_of_child($ids, $adjustment_data)
  	{
  		$ids = explode(",", $ids);
  		$temp_arr = array();

  		foreach ($ids as $id_key => $id) {
  			if(isset($adjustment_data[$id]))
  			{
  				array_push($temp_arr, $adjustment_data[$id]);
  			}
  		}

  		$parent_total_adjustment = array();

  		foreach ($temp_arr as $key => $level1_value) {
  			foreach ($level1_value as $level2_key => $level2_value) {
  				if(count($parent_total_adjustment) > 0)
	  			{
	  				// print_r(array_search($value[0]['type'], array_column($parent_total_adjustment, 'type')));
	  				// print_r(array_column($parent_total_adjustment, 'type'));
	  				

		  			if(array_search($level2_value['type'], array_column($parent_total_adjustment, 'type')) !== FALSE)
		  			{
		  				$arr_index = array_search($level2_value['type'], array_column($parent_total_adjustment, 'type'));
		  				// echo ($parent_total_adjustment[$arr_index]['je_no']."-----");
		  				// echo ($level2_value['je_no']);
		  				if($parent_total_adjustment[$arr_index]['je_no'] != $level2_value['je_no'])
		  				{
		  					if ($level2_value['adjust_value'] > 0)
			  				{
				  				$temp_adjustment_total = array('type'  => $level2_value['type'],
				  											   'je_no' => $level2_value['je_no'],
				  											   'type_name' => $level2_value['type_name'],
				  											   'ntve_adjust_value' => 0,
				  											   'ptve_adjust_value' => $level2_value['adjust_value']);
				  			}
				  			else
				  			{
				  				$temp_adjustment_total = array('type'  => $level2_value['type'],
				  											   'je_no' => $level2_value['je_no'],
				  											   'type_name' => $level2_value['type_name'],
				  											   'ntve_adjust_value' => $level2_value['adjust_value'],
				  											   'ptve_adjust_value' => 0);
				  			}

	  						array_push($parent_total_adjustment, $temp_adjustment_total);
		  				}
		  				else
		  				{
		  					if ($level2_value['adjust_value'] > 0)
		  					{
		  					
		  						$parent_total_adjustment[$arr_index]['ptve_adjust_value'] += $level2_value['adjust_value'];
		  					}
		  					else
		  					{
		  						$parent_total_adjustment[$arr_index]['ntve_adjust_value'] += $level2_value['adjust_value'];
		  					}
	
		  				}
		  			}
		  			else
		  			{
		  				if ($level2_value['adjust_value'] > 0)
		  				{
			  				$temp_adjustment_total = array('type'  => $level2_value['type'],
			  											   'je_no' => $level2_value['je_no'],
			  											   'type_name' => $level2_value['type_name'],
			  											   'ntve_adjust_value' => 0,
			  											   'ptve_adjust_value' => $level2_value['adjust_value']);
			  			}
			  			else
			  			{
			  				$temp_adjustment_total = array('type'  => $level2_value['type'],
			  											   'je_no' => $level2_value['je_no'],
			  											   'type_name' => $level2_value['type_name'],
			  											   'ntve_adjust_value' => $level2_value['adjust_value'],
			  											   'ptve_adjust_value' => 0);
			  			}

	  					array_push($parent_total_adjustment, $temp_adjustment_total);
		  			}
		  		}
	  			else
	  			{

	  				if ($level2_value['adjust_value'] > 0)
	  				{
		  				$temp_adjustment_total = array('type'  => $level2_value['type'],
		  											   'je_no' => $level2_value['je_no'],
		  											   'type_name' => $level2_value['type_name'],
		  											   'ntve_adjust_value' => 0,
		  											   'ptve_adjust_value' => $level2_value['adjust_value']);
		  			}
		  			else
		  			{
		  				$temp_adjustment_total = array('type'  => $level2_value['type'],
		  											   'je_no' => $level2_value['je_no'],
		  											   'type_name' => $level2_value['type_name'],
		  											   'ntve_adjust_value' => $level2_value['adjust_value'],
		  											   'ptve_adjust_value' => 0);
		  			}

	  				array_push($parent_total_adjustment, $temp_adjustment_total);
	  			}
  			}
  			
  		}


  		$final_total_adjustment = array();

  		foreach ($parent_total_adjustment as $parent_key => $parent_value) {

  			// print_r($parent_value);
  			if($parent_value['ptve_adjust_value'] != 0)
  			{
  				$temp_adjustment_total = array('type'  => $parent_value['type'],
	  											   'je_no' => $parent_value['je_no'],
	  											   'type_name' => $parent_value['type_name'],
	  											   'adjusted_value' => $parent_value['ptve_adjust_value']);

  				array_push($final_total_adjustment, $temp_adjustment_total);
  			}
  			

  			if($parent_value['ntve_adjust_value'] != 0)
  			{
  				$temp_adjustment_total = array('type'  => $parent_value['type'],
	  											   'je_no' => $parent_value['je_no'],
	  											   'type_name' => $parent_value['type_name'],
	  											   'adjusted_value' => $parent_value['ntve_adjust_value']);

  				array_push($final_total_adjustment, $temp_adjustment_total);
  			}
  			
	  		
	  			
  		}


  		return $final_total_adjustment;
	  		

  	}





?>