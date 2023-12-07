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
</style>


<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 0px;">
		<form id="form_balance_sheet">
			<?php
				if($show_data_content)
				{	

			?>
			<input type="hidden" class="statement_doc_type" name="statement_doc_type" value="2">
			<input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">


			<h5 id="account_balance_msg" style="color:red"></h5>

			<table id="tbl_financial_position" class="table table-hover table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black">
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
						$index = 0;
						$note_no = 0;


						$total_assets_c 		 = 0.00;
						$total_assets_c_adjusted = 0.00;
						$total_assets_c_end 	 = 0.00;
						$total_assets_c_beg 	 = 0.00;

						$total_equity_c 		 = 0.00;
						$total_equity_c_adjusted = 0.00;
						$total_equity_c_end 	 = 0.00;
						$total_equity_c_beg 	 = 0.00;

						$total_liabilities_c 		  = 0.00;
						$total_liabilities_c_adjusted = 0.00;
						$total_liabilities_c_end	  = 0.00;
						$total_liabilities_c_beg	  = 0.00;

						$display_class_ye = "";
						$display_class_lye_end = "FP_company_lye_end";
						$display_class_lye_beg = "FP_company_lye_beg";

						// $display_class_subtotal_ye 		= "FP_subtotal_ye"; 
						// $display_class_subtotal_lye_end = "FP_subtotal_lye_end"; 
						// $display_class_subtotal_lye_beg = "FP_subtotal_lye_beg"; 
						

						// display name of input
						$FP_ye_end_value_name  = "FP_company_ye_end_value";
						$FP_lye_end_value_name = "FP_company_lye_end_value";
						$FP_lye_beg_value_name = "FP_company_lye_beg_value";
						

						// echo json_encode($data[0]);

						// print_r($data);

						$displayed_eq_liabi_title = false;

						foreach ($data as $level_1_key => $level_1) 
						{
							$hide_main_title = false;
							$level_1_description = "";

							$fs_ntfs_list_key = array_search($level_1['parent_array'][0]['account_code'], array_column($fs_ntfs_list, "account_code"));	// get key

							if(!empty($fs_ntfs_list_key) || (string)$fs_ntfs_list_key == 0)
				            {
				            	$level_1_description = $fs_ntfs_list[$fs_ntfs_list_key]['description'];	// get description from fs_ntfs_list json from document name "Statement of financial position"
				            }

				            /* ---------------------------- Display main category (Level 1). eg. Assets, Equity and Liabilities ---------------------------- */
				            if(count($level_1['child_array']) == 1)
				            {
				            	foreach ($level_1['child_array'] as $key => $value) 
				            	{
				            		if(empty($value['parent_array']))
				            		{
				            			$hide_main_title = true;
				            		}
				            	}
				            }

							if($level_1_description == "Assets" && (count($level_1['child_array']) > 0) && !$hide_main_title)
							{
								echo 
								'<tr>'.
									'<td><strong>' . ucfirst(strtolower($level_1['parent_array'][0]['description'])) . '</strong></td>' .
									'<td align="center"></td>' .
									'<td>&nbsp;</td>' .
									'<td colspan="9"></td>' .
								'</tr>';

								// print_r($data);
							}

							if(($level_1_description == "Equity" || $level_1_description == "Liabilities"))
							{
								$empty_inner_item = false;

								if($level_1_description == "Equity")
								{
									$level_1['child_array'] = array($level_1); 
								}
								elseif($level_1_description == "Liabilities")
								{
									if(isset($level_1['child_array'][0]['parent_array']))
									{
										if($level_1['child_array'][0]['parent_array'] == null)
										{
											// $level_1['child_array'] = array(array('child_array' => $level_1['child_array']));
											$level_1['child_array'] = [];
											$empty_inner_item = true;
										}
									}
									
								}

								if(!$displayed_eq_liabi_title && !$empty_inner_item && !empty($e_l_title))
								{
									echo 
									'<tr>'.
										'<td><strong>' . $e_l_title . '</strong></td>' .
										'<td align="center"></td>' .
										'<td style="width: 0.5%;">&nbsp;</td>' .
										'<td colspan="3"></td>' .
									'</tr>';

									$displayed_eq_liabi_title = true;
								}
							}

							/* ---------------------------- END OF Display main category (Level 1). eg. Assets, Equity and Liabilities ---------------------------- */

							// print_r($level_1['child_array']);

							if(!empty($level_1['parent_array']))
							// {
							// 	echo 
							// 	'<tr>'.
							// 		'<td><strong><em>' . ucfirst(strtolower($level_1['child_array'][0]['description'])) . '</em></strong></td>' .
							// 		'<td align="center"></td>' .
							// 		'<td style="width: 0.5%;">&nbsp;</td>' .
							// 		'<td colspan="3"></td>' .
							// 	'</tr>';
							// }
							// else
							{
								foreach ($level_1['child_array'] as $level_2_key => $level_2) 
								{
									// print_r($level_2);
								
									$temp_total_c 	  	   = 0.00;
									$temp_total_c_adjusted = 0.00;
									$temp_total_c_end 	   = 0.00;
									$temp_total_c_beg 	   = 0.00;
									

									/* DISPLAY 1 LINE ONLY IF NO CHILD UNDER LEVEL 2 */
									if(count($level_2['child_array']) > 0)
									{
										if(!empty($level_2['parent_array']))
										{
											echo 
											'<tr>'.
												'<td><strong><em>' . ucfirst(strtolower($level_2['parent_array'][0]['description'])) . '</em></strong></td>' .
												'<td align="center"></td>' .
												'<td style=">&nbsp;</td>' .
												'<td colspan="9"></td>' .
											'</tr>';

											// print_r($level_2['child_array']);

											foreach ($level_2['child_array'] as $level_3_key => $level_3)
											{
												// echo '</br></br>';
												// print_r($level_3);
												/* DISPLAY LEVEL 3 THAT HAS SUBCATEGORY */
												if(!empty($level_3['parent_array']) && $level_1_description != "Equity")
                                                {

                                                    echo 
														'<tr>'.
															'<td><u><em>' . ucfirst(strtolower($level_3['parent_array'][0]['description'])) . '</em></u></td>' .
															'<td align="center"></td>' .
															'<td style=">&nbsp;</td>' .
															'<td colspan="9"></td>' .
														'</tr>';

                                                    foreach ($level_3['child_array'] as $level_4_key => $level_4)
													{

														if(!empty($level_4['parent_array']))
														{
															$temp_c 		 = isset($level_4['parent_array'][0]['total_c'])?$level_4['parent_array'][0]['total_c']:0;
															$temp_c_adjusted = isset($level_4['parent_array'][0]['total_c_adjusted'])?$level_4['parent_array'][0]['total_c_adjusted']:0;
															$temp_c_lye 	 = isset($level_4['parent_array'][0]['total_c_lye'])?$level_4['parent_array'][0]['total_c_lye']:0;

															$temp_id 		 = $level_4['parent_array'][0]['id'];
															// $temp_adjustment_info = isset($adjustment_data[$temp_id])?$adjustment_data[$temp_id][0]['adjust_value']:"";
															$temp_child_id = isset($level_4['parent_array'][0]['child_id'])?$level_4['parent_array'][0]['child_id']:"";
															$temp_adjustment_info = get_adjustment_of_child($temp_child_id, $adjustment_data);
															$temp_adjust_value = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['adjusted_value']:"";
															$temp_je_no = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['type_name'].$temp_adjustment_info[0]['je_no']:"";

															$adjustment_for_rev_rsrve = 0.00;
															$adjustment_for_pl = 0.00;

															$variance_value  = calculate_variance($temp_c_adjusted ,$temp_c_lye);

															// print_r($level_3['parent_array'][0]);
															// echo json_encode($level_3['fs_note_details']);
															echo 
															'<tr class="rows-for-'.$level_4['parent_array'][0]['id'].'">' .
																'<td>' .
																	// hidden fields
																	'<input type="hidden" name="FP_audit_categorized_account_id[]" class="SFP_audit_categorized_account_id" value="'. $level_4['parent_array'][0]['id'] .'">' .
																	$level_4['parent_array'][0]['description'] . 
																'</td>';

																echo '<td align="right">' . negative_bracket($temp_c) . '</td>';

																// adjustment detail for PL when no adjustment of retained earning
																if($level_4['parent_array'][0]['account_code'] == 'Q1030000' && $temp_adjust_value == "")
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

																if($level_4['parent_array'][0]['account_code'] == 'Q1030000' && $temp_adjust_value != "")
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
																			'<input type="number" name="' . $FP_lye_beg_value_name . '[' . $index . ']" class="form-control ' . $display_class_lye_beg . '_values_under_' . $level_2['parent_array'][0]['id'] . ' ' . $display_class_lye_beg .  '_account_code_' . $level_1_description . '" style="text-align:right;" value="' . $level_3['parent_array'][0]['company_beg_prev_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array'][0]['id'] .', \'' . $display_class_lye_beg . '\', \'' . $level_1_description . '\', \'' . "company" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
																		'</td>';
																	}
																	echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
																	echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';
																
																}



																$temp_total_c 	  	   += (double)$temp_c;
																$temp_total_c_adjusted += (double)$temp_c_adjusted;
																$temp_total_c_end 	   += (double)$temp_c_lye;
																$temp_total_c_beg 	   += (double)$level_4['parent_array'][0]['company_beg_prev_ye_value'];
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
															if($level_4['parent_array'][0]['account_code'] == 'Q1030000' && $adjustment_for_rev_rsrve != 0)
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

															$index++;
														}
														/* END OF DISPLAY LEVEL 3 THAT HAS SUBCATEGORY */

														/* DISPLAY LEVEL 3 WITHOUT SUBCATEGORY UNDER IT */
														elseif($level_1_description == "Liabilities" || $level_1_description == "Assets")
														{
															$temp_id 		 = $level_4['child_array']['id'];
															$temp_adjustment_info = isset($adjustment_data[$temp_id][0])?$adjustment_data[$temp_id][0]['adjust_value']:"";
															$temp_je_no = $temp_adjustment_info != ""?$adjustment_data[$temp_id][0]['type_name'].$adjustment_data[$temp_id][0]['je_no']:"";

															$variance_value = calculate_variance($level_4['child_array']['adjusted_value'],$level_4['child_array']['company_end_prev_ye_value']);
															echo 
															'<tr class="rows-for-'.$level_4['child_array']['id'].'">'.
																'<td>' . 
																	// hidden fields
																	'<input type="hidden" name="FP_audit_categorized_account_id[]" class="SFP_audit_categorized_account_id" value="'. $level_4['child_array']['id'] .'">' .

																	$level_4['child_array']['description'] . 
																'</td>';
													
															
																echo 
																'<td align="right">' .  negative_bracket($level_4['child_array']['value']) . '</td>';
																if($temp_adjustment_info > 0)
																{
																	echo 
																		'<td align="right"><span style="float:left"><strong>'.$temp_je_no.'</strong></span>'.negative_bracket($temp_adjustment_info).'</td>';
																	echo 
																		'<td align="right"></td>';
																}
																else if($temp_adjustment_info < 0 && $temp_adjustment_info != "")
																{
																	echo 
																		'<td align="right"></td>';
																	echo 
																		'<td align="right"><span style="float:left"><strong>'.$temp_je_no.'</strong></span>'.negative_bracket(abs($temp_adjustment_info)).'</td>';
																}
																else
																{
																	echo 
																		'<td align="right"></td>';
																	echo 
																		'<td align="right"></td>';
																}
																echo 
																'<td align="right">' .  negative_bracket($level_4['child_array']['adjusted_value']) . '</td>';

																if(!empty($last_fye_end))
																{
																 	// echo 
																 	// '<td align="right">' . 
																 	// 	'<input type="number" class="form-control ' . $display_class_lye_end . '_values_under_' . $level_2['parent_array']['id'] . ' ' . $display_class_lye_end . '_account_code_' . $level_2['parent_array']['account_code'] . '" name="' . $FP_lye_end_value_name . '[' . $index . ']" style="text-align:right;" value="' . $level_3['child_array']['company_end_prev_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array']['id'] .', \'' . $display_class_lye_end. '\', \'' . $level_2['parent_array']['account_code'] . '\', \'' . "company" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
																 	// '</td>';

																 	echo
																 		'<td align="right">' . negative_bracket($level_4['child_array']['company_end_prev_ye_value']) . '</td>';

																 	if($show_third_col)
																	{
																		echo 
																		'<td align="right">' . 
																			'<input type="number" class="form-control ' . $display_class_lye_beg . '_values_under_' . $level_2['parent_array'][0]['id'] . ' ' . $display_class_lye_beg . '_account_code_' . $level_1_description . '" name="' . $FP_lye_beg_value_name . '[' . $index . ']" style="text-align:right;" value="' . $level_3['child_array']['company_beg_prev_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array'][0]['id'] .', \'' . $display_class_lye_beg. '\', \'' . $level_1_description . '\', \'' . "company" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' . 
																		'</td>';
																	}

																	echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
																	echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';

																	
																}


																$temp_total_c 	  	  += (double)$level_4['child_array']['value'];
																$temp_total_c_adjusted  += (double)$level_4['child_array']['adjusted_value'];
																$temp_total_c_end 	  += (double)$level_4['child_array']['company_end_prev_ye_value'];
																$temp_total_c_beg 	  += (double)$level_4['child_array']['company_beg_prev_ye_value'];
																
															echo '</tr>';

															//loop all adjustment info
															if(isset($adjustment_data[$temp_id]) && count($adjustment_data[$temp_id]) > 1)
															{
																foreach ($adjustment_data[$temp_id] as $info_key => $each_info) {
																	if($info_key != 0)
																	{
																		$temp_adjustment_info = isset($adjustment_data[$temp_id])?$adjustment_data[$temp_id][$info_key]['adjust_value']:"";
																		$temp_je_no = $temp_adjustment_info != ""?$adjustment_data[$temp_id][$info_key]['type_name'].$adjustment_data[$temp_id][$info_key]['je_no']:"";
																		echo '<tr>
																				<td></td>
																				<td></td>';
																		if($temp_adjustment_info > 0)
																		{
																			echo 
																				'<td align="right"><span style="float:left"><strong>'.$temp_je_no.'</strong></span>'.negative_bracket($temp_adjustment_info).'</td>';
																			echo 
																				'<td align="right"></td>';
																		}
																		else if($temp_adjustment_info < 0 && $temp_adjustment_info != "")
																		{
																			echo 
																				'<td align="right"></td>';
																			echo 
																				'<td align="right"><span style="float:left"><strong>'.$temp_je_no.'</strong></span>'.negative_bracket(abs($temp_adjustment_info)).'</td>';
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

															$index++;
														}
														/* END OF DISPLAY LEVEL 3 WITHOUT SUBCATEGORY UNDER IT */
													}
													$temp_c 		 = isset($level_3['parent_array'][0]['total_c'])?$level_3['parent_array'][0]['total_c']:0;
													$temp_c_adjusted = isset($level_3['parent_array'][0]['total_c_adjusted'])?$level_3['parent_array'][0]['total_c_adjusted']:0;
													$temp_c_lye 	 = isset($level_3['parent_array'][0]['total_c_lye'])?$level_3['parent_array'][0]['total_c_lye']:0;

													$temp_id 		 = $level_3['parent_array'][0]['id'];
													// $temp_adjustment_info = isset($adjustment_data[$temp_id])?$adjustment_data[$temp_id][0]['adjust_value']:"";
													$variance_value  = calculate_variance($temp_c_adjusted ,$temp_c_lye);

													echo 
													'<tr>' . 
														'<td></td>' . 
														'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
															'<span id="' . $display_class_ye . '_subtotal_'. $temp_id .'">' . 
																negative_bracket($temp_c) . 
															'</span>' . 
														'</td>';
														
													echo 
														'<td align="right"></td>';
													echo 
														'<td align="right"></td>';

													echo 
														'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
															'<span>' . 
																negative_bracket($temp_c_adjusted) . 
															'</span>' . 
														'</td>';

													if(!empty($last_fye_end))
													{
														echo 
														'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
															'<span id="' . $display_class_lye_end . '_subtotal_'. $temp_id .'">' . 
																negative_bracket($temp_c_lye) . 
															'</span>' . 
														'</td>';

													 	if($show_third_col)
														{
															echo 
															'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
																'<span id="' . $display_class_lye_beg . '_subtotal_'. $temp_id .'">' . 
																	negative_bracket($total_beg) . 
																'</span>' . 
															'</td>';
														}

														echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
														echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';

										
													}

														
														
													echo '</tr>';
													// echo '<tr>' . 
													// 		'<td colspan="5">&nbsp;</td>' . 
													// 	'</tr>';
												}
												elseif(!empty($level_3['parent_array']) && $level_1_description == "Equity")
												{
													if(!empty($level_3['parent_array']))
													{
														$temp_c 		 = isset($level_3['parent_array'][0]['total_c'])?$level_3['parent_array'][0]['total_c']:0;
														$temp_c_adjusted = isset($level_3['parent_array'][0]['total_c_adjusted'])?$level_3['parent_array'][0]['total_c_adjusted']:0;
														$temp_c_lye 	 = isset($level_3['parent_array'][0]['total_c_lye'])?$level_3['parent_array'][0]['total_c_lye']:0;

														$temp_id 		 = $level_3['parent_array'][0]['id'];
														// $temp_adjustment_info = isset($adjustment_data[$temp_id])?$adjustment_data[$temp_id][0]['adjust_value']:"";
														$temp_child_id = isset($level_3['parent_array'][0]['child_id'])?$level_3['parent_array'][0]['child_id']:"";
														$temp_adjustment_info = get_adjustment_of_child($temp_child_id, $adjustment_data);
														$temp_adjust_value = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['adjusted_value']:"";
														$temp_je_no = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['type_name'].$temp_adjustment_info[0]['je_no']:"";

														$adjustment_for_rev_rsrve = 0.00;
														$adjustment_for_pl = 0.00;

														$variance_value  = calculate_variance($temp_c_adjusted ,$temp_c_lye);

														// print_r($level_3['parent_array'][0]);
														// echo json_encode($level_3['fs_note_details']);
														echo 
														'<tr class="rows-for-'.$level_3['parent_array'][0]['id'].'">' .
															'<td>' .
																// hidden fields
																'<input type="hidden" name="FP_audit_categorized_account_id[]" class="SFP_audit_categorized_account_id" value="'. $level_3['parent_array'][0]['id'] .'">' .
																$level_3['parent_array'][0]['description'] . 
															'</td>';

															echo '<td align="right">' . negative_bracket($temp_c) . '</td>';

															// adjustment detail for PL when no adjustment of retained earning
															if($level_3['parent_array'][0]['account_code'] == 'Q1030000' && $temp_adjust_value == "")
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

															if($level_3['parent_array'][0]['account_code'] == 'Q1030000' && $temp_adjust_value != "")
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
																		'<input type="number" name="' . $FP_lye_beg_value_name . '[' . $index . ']" class="form-control ' . $display_class_lye_beg . '_values_under_' . $level_2['parent_array'][0]['id'] . ' ' . $display_class_lye_beg .  '_account_code_' . $level_1_description . '" style="text-align:right;" value="' . $level_3['parent_array'][0]['company_beg_prev_ye_value'] . '" onchange="FP_calculation('. $level_2['parent_array'][0]['id'] .', \'' . $display_class_lye_beg . '\', \'' . $level_1_description . '\', \'' . "company" . '\')" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1">' .
																	'</td>';
																}
																echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
																echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';
															
															}



															$temp_total_c 	  	   += (double)$temp_c;
															$temp_total_c_adjusted += (double)$temp_c_adjusted;
															$temp_total_c_end 	   += (double)$temp_c_lye;
															$temp_total_c_beg 	   += (double)$level_3['parent_array'][0]['company_beg_prev_ye_value'];
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
														if($level_3['parent_array'][0]['account_code'] == 'Q1030000' && $adjustment_for_rev_rsrve != 0)
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

														$index++;
													}
												}
											}
											
											/* DISPLAY TOTAL FOR EACH CATEGORY	*/
											$total 			= $temp_total_c;
											$total_adjusted = $temp_total_c_adjusted;
											$total_end 	= $temp_total_c_end;
											$total_beg 	= $temp_total_c_beg;

											/* CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES - COMPANY */
											if($level_1_description == "Assets")	// NON-CURRENT ASSETS || CURRENT ASSETS
											{
												$total_assets_c 	   	 += $total;
												$total_assets_c_adjusted += $total_adjusted;
												$total_assets_c_end		 += $total_end;
												$total_assets_c_beg 	 += $total_beg;
											}
											elseif($level_1_description == "Equity") // EQUITY
											{
												$total_equity_c 		 += $total;
												$total_equity_c_adjusted += $total_adjusted;
												$total_equity_c_end 	 += $total_end;
												$total_equity_c_beg 	 += $total_beg;
											}
											elseif($level_1_description == "Liabilities") // NON-CURRENT LIABILITIES || CURRENT LIABILITIES
											{
												$total_liabilities_c 	 	   += $total;
												$total_liabilities_c_adjusted  += $total_adjusted;
												$total_liabilities_c_end 	   += $total_end;
												$total_liabilities_c_beg 	   += $total_beg;
											}
											/* END OF CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES */
											
											$variance_value = calculate_variance($total_adjusted,$total_end);

											echo 
												'<tr>' . 
													'<td></td>' . 
													'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
														'<span id="' . $display_class_ye . '_subtotal_'. $level_2['parent_array'][0]['id'] .'">' . 
															negative_bracket($total) . 
														'</span>' . 
													'</td>';
													
												echo 
													'<td align="right"></td>';
												echo 
													'<td align="right"></td>';

												echo 
													'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
														'<span>' . 
															negative_bracket($total_adjusted) . 
														'</span>' . 
													'</td>';

													if(!empty($last_fye_end))
													{
														echo 
														'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
															'<span id="' . $display_class_lye_end . '_subtotal_'. $level_2['parent_array'][0]['id'] .'">' . 
																negative_bracket($total_end) . 
															'</span>' . 
														'</td>';

													 	if($show_third_col)
														{
															echo 
															'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
																'<span id="' . $display_class_lye_beg . '_subtotal_'. $level_2['parent_array'][0]['id'] .'">' . 
																	negative_bracket($total_beg) . 
																'</span>' . 
															'</td>';
														}

														echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
														echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';

										
													}

													
													
												echo '</tr>';
												/* END OF DISPLAY TOTAL FOR EACH CATEGORY */

											
												
										}
									}
								}
							}


							
							/* DISPLAY TOTAL ASSETS */
							if($level_1_description == "Assets" && (count($level_1['child_array']) > 0) && !$hide_main_title)
							{
								

								$total_assets 	  	   = 0.00;
								$total_assets_adjusted = 0.00;
								$total_assets_end      = 0.00;
								$total_assets_beg      = 0.00;

						
								$total_assets 	  = $total_assets_c;
								$total_assets_adjusted = $total_assets_c_adjusted;
								$total_assets_end = $total_assets_c_end;
								$total_assets_beg = $total_assets_c_beg;


								$variance_value = calculate_variance($total_assets_adjusted,$total_assets_end);

								echo 
								'<tr class="total_assets">' . 
									'<td><strong>Total assets</strong></td>' . 
									'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
										'<span id="'. $display_class_ye .'_total_assets" class="total_assets">' . negative_bracket($total_assets) . '</span>' .
									'</td>';

								echo 
									'<td align="right"></td>';
								echo 
									'<td align="right"></td>';

								echo 
									'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
										'<span>' . negative_bracket($total_assets_adjusted) . '</span>' . 
									'</td>';

									if(!empty($last_fye_end))
									{
										echo 
										'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
											'<span id="' . $display_class_lye_end . '_total_assets" class="total_assets_end">' . negative_bracket($total_assets_end) . '</span>' . 
										'</td>';

									 	if($show_third_col)
										{
											echo 
											'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
												'<span id="' . $display_class_lye_beg . '_total_assets" class="total_assets_beg">' . negative_bracket($total_assets_beg) . '</span>' . 
											'</td>';
										}

										echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
										echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';
								
									}
									
								echo '</tr>';
								
							}
							/* END OF DISPLAY TOTAL ASSETS */

							/* DISPLAY TOTAL LIABILITES */
							if($level_1_description == "Liabilities" && (count($level_1['child_array']) > 0) && !$hide_main_title)
							{
								if(in_array("Liabilities", $eq_liabi_title_list))
								{
									$total_liabilities 	   		  = 0.00;
									$total_liabilities_adjusted   = 0.00;
									$total_liabilities_end  	  = 0.00;
									$total_liabilities_beg 		  = 0.00;

						
									$total_liabilities 	   		= $total_liabilities_c;
									$total_liabilities_adjusted = $total_liabilities_c_adjusted;
									$total_liabilities_end 		= $total_liabilities_c_end;
									$total_liabilities_beg 		= $total_liabilities_c_beg;

									$variance_value = calculate_variance($total_liabilities_adjusted,$total_liabilities_end);

									echo 
									'<tr>' .  
										'<td><strong>Total liabilities</strong></td>' . 
										'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
											'<span id="'. $display_class_ye .'_total_liabilities">' . negative_bracket($total_liabilities) . '</span>' .
										'</td>';

									echo 
										'<td align="right"></td>';
									echo 
										'<td align="right"></td>';

									echo 
										'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
											'<span>' . negative_bracket($total_liabilities_adjusted) . '</span>' .
										'</td>';

										if(!empty($last_fye_end))
										{
											echo 
											'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
												'<span id="'. $display_class_lye_end .'_total_liabilities">' . negative_bracket($total_liabilities_end) . '</span>' .
											'</td>';

										 	if($show_third_col)
											{
												echo 
												'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
													'<span id="'. $display_class_lye_beg .'_total_liabilities">' . negative_bracket($total_liabilities_beg) . '</span>' . 
												'</td>';
											}

											echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
											echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';

										
										}
										
									echo '</tr>';

									echo 
									'<tr>' . 
										'<td colspan="5">&nbsp;</td>' . 
									'</tr>';
								}
							}
							/* END OF DISPLAY TOTAL LIABILITES */

							/* DISPLAY TOTAL EQUITY & LIABILITIES */
							elseif($level_1_key == count($data) - 1)
							{
								if(count($eq_liabi_title_list) > 1)
								{
									$total_equity_liabilities 	  		= 0.00;
									$total_equity_liabilities_adjusted  = 0.00;
									$total_equity_liabilities_end 		= 0.00;
									$total_equity_liabilities_beg 		= 0.00;

								
									$total_equity_liabilities 	  		= $total_equity_c 	+ $total_liabilities_c;
									$total_equity_liabilities_adjusted  = $total_equity_c_adjusted 	+ $total_liabilities_c_adjusted;
									$total_equity_liabilities_end 		= $total_equity_c_end + $total_liabilities_c_end;
									$total_equity_liabilities_beg 		= $total_equity_c_beg + $total_liabilities_c_beg;

									$variance_value = calculate_variance($total_equity_liabilities_adjusted,$total_equity_liabilities_end);
								

									echo 
									'<tr class="total_equity_liabilities">' . 
										'<td><strong>Total equity and liabilities</strong></td>' . 
										'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
											'<span id="'. $display_class_ye .'_total_equity_liabilities" class="total_equity_liabilities">' . negative_bracket($total_equity_liabilities) . '</span>' . 
										'</td>';

									echo 
										'<td align="right"></td>';
									echo 
										'<td align="right"></td>';

									echo 
										'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
											'<span class="total_equity_liabilities_adjusted">' .negative_bracket($total_equity_liabilities_adjusted) . '</span>' . 
										'</td>';
										if(!empty($last_fye_end))
										{
											echo 
											'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
												'<span id="'. $display_class_lye_end .'_total_equity_liabilities" class="total_equity_liabilities_end">' .negative_bracket($total_equity_liabilities_end) . '</span>' . 
											'</td>';

										 	if($show_third_col)
											{
												echo 
												'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
													'<span id="'. $display_class_lye_beg .'_total_equity_liabilities" class="total_equity_liabilities_beg">' . negative_bracket($total_equity_liabilities_beg) . '</span>' .
												'</td>';
											}

											echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
											echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';
						
										}

										
									echo '</tr>';
								}

								
							}
							/* END OF DISPLAY TOTAL LIABILITES, TOTAL EQUITY & LIABILITIES */

							/* BREAK LINE */
							if($level_1_key < (count($data[0]) - 1) && (count($level_1['child_array']) > 0) && !$hide_main_title)
							{
								echo 
								'<tr>' . 
									'<td colspan="5">&nbsp;</td>' . 
								'</tr>';
							}
							/* END OF BREAK LINE */
						}
					?>
				</tbody>
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


<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	var account_width = '<?php echo $width['account_desc']; ?>'; 
	var value_width = '<?php echo $width['value']; ?>'; 
	var variance_width = '<?php echo $width['variance']; ?>'; 
	var show_data_content = '<?php echo $show_data_content ?>';

	var adjustment_caf_id = '<?php echo $adjustment_caf_id?>';
	var reserved_je_no = [];
	var arr_deleted_info = [];

	var save_adjustment_url = "<?php echo site_url('caf/save_adjustment'); ?>";
	var get_je_no_url  = "<?php echo site_url('caf/get_je_no'); ?>";

	var export_farbs_pdf_url = "<?php echo (site_url('caf/export_farbs_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";
</script>


<script src="<?= base_url()?>application/modules/caf/js/far_bs.js" charset="utf-8"></script>

<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>