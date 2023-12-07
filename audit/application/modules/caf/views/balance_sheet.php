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
					echo form_dropdown('bs_assertion_clone', $bs_assertion_dropdown, '', 'id="bs_assertion_clone" style="display:none;width:100%;"');
					echo form_dropdown('risk_level_clone', $risk_lvl_dropdown, '', 'id="risk_level_clone" style="display:none;width:100%;"');
					

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

								echo '<th style="width:'.$width['variance'] .'%;text-align: center;" colspan=2><br/>Variance</th>';
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
						
						<th style="width:3%;text-align: center;"></th>
						<th style="width:2%;text-align: center;"></th>
						<th style="width:2%;text-align: center;"></th>
						<th style="width:10%;text-align: center;"></th>
						<th style="width:10%;text-align: center;"></th>
						<th style="width:5%;text-align: center;"></th>
						<th style="width:13%;text-align: center;"></th>
					</tr>
<!-- 					<tr style="padding:0;">
						<th style="width: <?=$width['account_desc'] ?>%;">&nbsp;</th>
						<th style="width: <?=$width['value'] ?>%; text-align: center;">
							<?php
								if($show_third_col)
								{
									echo '</br>';
								} 
								echo "Unadjusted"; 
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
									echo "Final"; 
								echo '</th>';

								if($show_third_col)
								{
									echo '<th style="width: ' . $width['value'] . '%; text-align: center;">Restated</br>' . $last_fye_beg . '</th>';
								}
							}
						?>
						<th style="width: <?=$width['variance'] ?>%;text-align: center;" colspan=2>Variance</th>
					</tr> -->
					<tr>
						<th style="width: <?=$width['account_desc'] ?>%;"></th>
						<th style="width: <?=$width['value'] ?>%; text-align: center; border-top: 1px solid #000;">$</th>

						<?php 
							if(!empty($last_fye_end))
							{
								echo '<th style="width:'.$width['value'].'%; text-align: center; border-top: 1px solid #000;">$</th>';

								if($show_third_col)
								{
									echo '<th style="width:'.$width['value'].'%; text-align: center; border-top: 1px solid #000;">$</th>';
								}

								echo '<th style="width:'.$width['variance'].'%;text-align: center;border-top: 1px solid #000;">$</th>
									<th style="width:'.$width['variance'] .'%;text-align: center;border-top: 1px solid #000;">%</th>';
							}
						?>
						
						<th style="width:3%;text-align: center;"></th>
						<th style="width:2%;text-align: center;"></th>
						<th style="width:2%;text-align: center;">Ref</th>
						<th style="width:10%;text-align: center;">Risk</th>
						<th style="width:9%;text-align: center;">Assertion</th>
						<th style="width:5%;text-align: center;">Risk Level</th>
						<th style="width:13%;text-align: center;">Response</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$index = 0;
						$note_no = 0;


						$total_assets_c 		 = 0.00;
						$total_assets_c_end 	 = 0.00;
						$total_assets_c_beg 	 = 0.00;

						$total_equity_c 		 = 0.00;
						$total_equity_c_end 	 = 0.00;
						$total_equity_c_beg 	 = 0.00;

						$total_liabilities_c 	 = 0.00;
						$total_liabilities_c_end = 0.00;
						$total_liabilities_c_beg = 0.00;

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
								
									$temp_total_c 	  = 0.00;
									$temp_total_c_end = 0.00;
									$temp_total_c_beg = 0.00;
									

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
												if(count($level_3['child_array']) > 0)
												{
													if(!empty($level_3['parent_array']) && $level_1_description != "Equity")
													{
														echo 
														'<tr>'.
															'<td><u><em>' . ucfirst(strtolower($level_3['parent_array'][0]['description'])) . '</em></u></td>' .
															'<td align="center"></td>' .
															'<td style=">&nbsp;</td>' .
															'<td colspan="9"></td>' .
														'</tr>';
												// echo '</br></br>';
														foreach ($level_3['child_array'] as $level_4_key => $level_4)
														{

															/* DISPLAY LEVEL 3 THAT HAS SUBCATEGORY */
															if(!empty($level_4['parent_array']))
															{
																$temp_c = isset($level_4['parent_array'][0]['total_c'])?$level_4['parent_array'][0]['total_c']:0;
																$temp_c_lye = isset($level_4['parent_array'][0]['total_c_lye'])?$level_4['parent_array'][0]['total_c_lye']:0;
																$variance_value = calculate_variance($temp_c ,$temp_c_lye);

																// print_r($variance_value);
																// echo json_encode($level_3['fs_note_details']);
																echo 
																'<tr class="rows-for-'.$level_4['parent_array'][0]['id'].'">' .
																	'<td>' .
																		// hidden fields
																		'<input type="hidden" name="FP_audit_categorized_account_id[]" class="SFP_audit_categorized_account_id" value="'. $level_4['parent_array'][0]['id'] .'">' .
																		$level_4['parent_array'][0]['description'] . 
																	'</td>';


																	if($level_4['parent_array'][0]['account_code'] == 'Q103')
																	{
																		// print_r($level_3['parent_array'][0]);
																	}

																	echo '<td align="right">' . negative_bracket($temp_c) . '</td>';
																	
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

																	
																	echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$level_4['parent_array'][0]['id'].'][]" value="'.(isset($line_items[$level_4['parent_array'][0]['id']][0])?$line_items[$level_4['parent_array'][0]['id']][0]['id']:"").'"/>
																	<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add line item" id="" onclick="addRow(this,'.$last_fye_end_flag.')"><i class="fa fa-plus-circle amber" style="font-size:16px;"></i></a>
																	      </td>
																	      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.(isset($line_items[$level_4['parent_array'][0]['id']][0])?$line_items[$level_4['parent_array'][0]['id']][0]['id']:"").'"></td>
																	  <td style="text-align: center;" class="ref_no"></td>
																	  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$level_4['parent_array'][0]['id'].'][]"  rows="1" style="width:100%">'.(isset($line_items[$level_4['parent_array'][0]['id']][0])?$line_items[$level_4['parent_array'][0]['id']][0]['risk_text']:"").'</textarea></td>
																      <td style="text-align: center;">'.form_dropdown('bs_assertion['.$level_4['parent_array'][0]['id'].'][]', $bs_assertion_dropdown,(isset($line_items[$level_4['parent_array'][0]['id']][0])?$line_items[$level_4['parent_array'][0]['id']][0]['assertion']:""), 'class="bs_assertion select2" style="width:100%;"').'</td>
																	  <td style="text-align: center;">'.form_dropdown('risk_level['.$level_4['parent_array'][0]['id'].'][]', $risk_lvl_dropdown,(isset($line_items[$level_4['parent_array'][0]['id']][0])?$line_items[$level_4['parent_array'][0]['id']][0]['risk_level']:""), 'class="risk_level select2" style="width:100%;"').'</td>
																	  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$level_4['parent_array'][0]['id'].'][]"  rows="1" style="width:100%">'.(isset($line_items[$level_4['parent_array'][0]['id']][0])?$line_items[$level_4['parent_array'][0]['id']][0]['response']:"").'</textarea></td>';

																	$temp_total_c 	  += (double)$temp_c;
																	$temp_total_c_end += (double)$temp_c_lye;
																	$temp_total_c_beg += (double)$level_4['parent_array'][0]['company_beg_prev_ye_value'];
																	// }
																echo '</tr>';

																//loop line item other than first row
																if(isset($line_items[$level_4['parent_array'][0]['id']])){
																	if(count($line_items[$level_4['parent_array'][0]['id']]) > 1)
																	{
																		foreach ($line_items[$level_4['parent_array'][0]['id']] as $key => $each_line) 
																		{
																			if($key > 0)
																			{


																				echo  '<tr class="rows-for-'.$level_4['parent_array'][0]['id'].'">' .
																					      '<td></td>';

																				echo '<td align="right"></td>';
																					
																					if(!empty($last_fye_end))
																					{

																						echo 
																						'<td align="right"></td>';
																					
																					 	if($show_third_col)
																						{
																							echo 
																							'<td align="right"></td>';
																						}
																						echo '<td align="right"></td>';
																						echo '<td align="center"></td>';
																					}

																					
																					echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$level_4['parent_array'][0]['id'].'][]" value="'.$each_line['id'].'"/></td>
																					      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.$each_line['id'].'"></td>
																					  <td style="text-align: center;" class="ref_no"></td>
																					  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$level_4['parent_array'][0]['id'].'][]"  rows="1" style="width:100%">'.$each_line['risk_text'].'</textarea></td>
																				      <td style="text-align: center;">'.form_dropdown('bs_assertion['.$level_4['parent_array'][0]['id'].'][]', $bs_assertion_dropdown,$each_line['assertion'], 'class="bs_assertion select2" style="width:100%;"').'</td>
																					  <td style="text-align: center;">'.form_dropdown('risk_level['.$level_4['parent_array'][0]['id'].'][]', $risk_lvl_dropdown, $each_line['risk_level'], 'class="risk_level select2" style="width:100%;"').'</td>
																					  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$level_4['parent_array'][0]['id'].'][]"  rows="1" style="width:100%">'.$each_line['response'].'</textarea></td>';

																					// $temp_total_c 	  += (double)$level_3['parent_array'][0]['total_c'];
																					// $temp_total_c_end += (double)$level_3['parent_array'][0]['total_c_lye'];
																					// $temp_total_c_beg += (double)$level_3['parent_array'][0]['company_beg_prev_ye_value'];
																					// }
																				echo '</tr>';
																			}
																		}
																	}
																}

																

																$index++;
															}
															/* END OF DISPLAY LEVEL 3 THAT HAS SUBCATEGORY */

															/* DISPLAY LEVEL 3 WITHOUT SUBCATEGORY UNDER IT */
															elseif($level_1_description == "Liabilities" || $level_1_description == "Assets")
															{
																$variance_value = calculate_variance($level_4['child_array']['value'],$level_4['child_array']['company_end_prev_ye_value']);
																echo 
																'<tr class="rows-for-'.$level_4['child_array']['id'].'">'.
																	'<td>' . 
																		// hidden fields
																		'<input type="hidden" name="FP_audit_categorized_account_id[]" class="SFP_audit_categorized_account_id" value="'. $level_4['child_array']['id'] .'">' .

																		$level_4['child_array']['description'] . 
																	'</td>';
														
																
																	echo 
																	'<td align="right">' .  negative_bracket($level_4['child_array']['value']) . '</td>';

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

																	
																	echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$level_4['child_array']['id'].'][]" value="'.(isset($line_items[$level_4['child_array']['id']][0])?$line_items[$level_4['child_array']['id']][0]['id']:"").'"/>
																	<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add line item" id="" onclick="addRow(this,'.$last_fye_end_flag.')"><i class="fa fa-plus-circle amber" style="font-size:16px;"></i></a>
																	      </td>
																	      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.(isset($line_items[$level_4['child_array']['id']][0])?$line_items[$level_4['child_array']['id']][0]['id']:"").'"></td>
																	  <td style="text-align: center;" class="ref_no"></td>
																	  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$level_4['child_array']['id'].'][]"  rows="1" style="width:100%">'.(isset($line_items[$level_4['child_array']['id']][0])?$line_items[$level_4['child_array']['id']][0]['risk_text']:"").'</textarea></td>
																      <td style="text-align: center;">'.form_dropdown('bs_assertion['.$level_4['child_array']['id'].'][]', $bs_assertion_dropdown,(isset($line_items[$level_4['child_array']['id']][0])?$line_items[$level_4['child_array']['id']][0]['assertion']:""), 'class="bs_assertion select2" style="width:100%;"').'</td>
																	  <td style="text-align: center;">'.form_dropdown('risk_level['.$level_4['child_array']['id'].'][]', $risk_lvl_dropdown,(isset($line_items[$level_4['child_array']['id']][0])?$line_items[$level_4['child_array']['id']][0]['risk_level']:""), 'class="risk_level select2" style="width:100%;"').'</td>
																	  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$level_4['child_array']['id'].'][]"  rows="1" style="width:100%">'.(isset($line_items[$level_4['child_array']['id']][0])?$line_items[$level_4['child_array']['id']][0]['response']:"").'</textarea></td>';
																	

																	$temp_total_c 	  += (double)$level_4['child_array']['value'];
																	$temp_total_c_end += (double)$level_4['child_array']['company_end_prev_ye_value'];
																	$temp_total_c_beg += (double)$level_4['child_array']['company_beg_prev_ye_value'];
																	
																echo '</tr>';

																//loop line item other than first row

																if(isset($line_items[$level_4['child_array']['id']]) && count($line_items[$level_4['child_array']['id']]) > 1)
																{
																	foreach ($line_items[$level_4['child_array']['id']] as $key => $each_line) 
																	{
																		if($key > 0)
																		{


																			echo  '<tr class="rows-for-'.$level_4['child_array']['id'].'">' .
																				      '<td></td>';

																			echo '<td align="right"></td>';
																				
																				if(!empty($last_fye_end))
																				{

																					echo 
																					'<td align="right"></td>';
																				
																				 	if($show_third_col)
																					{
																						echo 
																						'<td align="right"></td>';
																					}
																					echo '<td align="right"></td>';
																					echo '<td align="center"></td>';
																				}
																				echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$level_4['child_array']['id'].'][]" value="'.$each_line['id'].'"/></td>
																				      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.$each_line['id'].'"></td>
																				  <td style="text-align: center;" class="ref_no"></td>
																				  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$level_4['child_array']['id'].'][]"  rows="1" style="width:100%">'.$each_line['risk_text'].'</textarea></td>
																			      <td style="text-align: center;">'.form_dropdown('bs_assertion['.$level_4['child_array']['id'].'][]', $bs_assertion_dropdown,$each_line['assertion'], 'class="bs_assertion select2" style="width:100%;"').'</td>
																				  <td style="text-align: center;">'.form_dropdown('risk_level['.$level_4['child_array']['id'].'][]', $risk_lvl_dropdown, $each_line['risk_level'], 'class="risk_level select2" style="width:100%;"').'</td>
																				  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$level_4['child_array']['id'].'][]"  rows="1" style="width:100%">'.$each_line['response'].'</textarea></td>';

																				// $temp_total_c 	  += (double)$level_3['parent_array'][0]['total_c'];
																				// $temp_total_c_end += (double)$level_3['parent_array'][0]['total_c_lye'];
																				// $temp_total_c_beg += (double)$level_3['parent_array'][0]['company_beg_prev_ye_value'];
																				// }
																			echo '</tr>';
																		}
																	}
																}

																$index++;
															}

															
														}


														$temp_c 		 = isset($level_3['parent_array'][0]['total_c'])?$level_3['parent_array'][0]['total_c']:0;
														$temp_c_lye 	 = isset($level_3['parent_array'][0]['total_c_lye'])?$level_3['parent_array'][0]['total_c_lye']:0;

														$temp_id 		 = $level_3['parent_array'][0]['id'];
														// $temp_adjustment_info = isset($adjustment_data[$temp_id])?$adjustment_data[$temp_id][0]['adjust_value']:"";
														$variance_value  = calculate_variance($temp_c ,$temp_c_lye);	

														echo 
														'<tr>' . 
															'<td></td>' . 
															'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
																'<span id="' . $display_class_ye . '_subtotal_'. $temp_id .'">' . 
																	negative_bracket($temp_c) . 
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
																		'<span id="' . $display_class_lye_beg . '_subtotal_'. $temp_ids.'">' . 
																			negative_bracket($total_beg) . 
																		'</span>' . 
																	'</td>';
																}

																echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
																echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';
															}
															
															
														echo '</tr>';
														// echo 
														// '<tr>' . 
														// 	'<td colspan="5">&nbsp;</td>' . 
														// '</tr>';


													}
													elseif(!empty($level_3['parent_array']) && $level_1_description == "Equity")
													{

												
														/* DISPLAY LEVEL 3 THAT HAS SUBCATEGORY */
														if(!empty($level_3['parent_array']))
														{
															$temp_c = isset($level_3['parent_array'][0]['total_c'])?$level_3['parent_array'][0]['total_c']:0;
															$temp_c_lye = isset($level_3['parent_array'][0]['total_c_lye'])?$level_3['parent_array'][0]['total_c_lye']:0;
															$variance_value = calculate_variance($temp_c ,$temp_c_lye);

															// print_r($variance_value);
															// echo json_encode($level_3['fs_note_details']);
															echo 
															'<tr class="rows-for-'.$level_3['parent_array'][0]['id'].'">' .
																'<td>' .
																	// hidden fields
																	'<input type="hidden" name="FP_audit_categorized_account_id[]" class="SFP_audit_categorized_account_id" value="'. $level_3['parent_array'][0]['id'] .'">' .
																	$level_3['parent_array'][0]['description'] . 
																'</td>';


																if($level_3['parent_array'][0]['account_code'] == 'Q103')
																{
																	// print_r($level_3['parent_array'][0]);
																}

																echo '<td align="right">' . negative_bracket($temp_c) . '</td>';
																
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

																
																echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$level_3['parent_array'][0]['id'].'][]" value="'.(isset($line_items[$level_3['parent_array'][0]['id']][0])?$line_items[$level_3['parent_array'][0]['id']][0]['id']:"").'"/>
																<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add line item" id="" onclick="addRow(this,'.$last_fye_end_flag.')"><i class="fa fa-plus-circle amber" style="font-size:16px;"></i></a>
																      </td>
																      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.(isset($line_items[$level_3['parent_array'][0]['id']][0])?$line_items[$level_3['parent_array'][0]['id']][0]['id']:"").'"></td>
																  <td style="text-align: center;" class="ref_no"></td>
																  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$level_3['parent_array'][0]['id'].'][]"  rows="1" style="width:100%">'.(isset($line_items[$level_3['parent_array'][0]['id']][0])?$line_items[$level_3['parent_array'][0]['id']][0]['risk_text']:"").'</textarea></td>
															      <td style="text-align: center;">'.form_dropdown('bs_assertion['.$level_3['parent_array'][0]['id'].'][]', $bs_assertion_dropdown,(isset($line_items[$level_3['parent_array'][0]['id']][0])?$line_items[$level_3['parent_array'][0]['id']][0]['assertion']:""), 'class="bs_assertion select2" style="width:100%;"').'</td>
																  <td style="text-align: center;">'.form_dropdown('risk_level['.$level_3['parent_array'][0]['id'].'][]', $risk_lvl_dropdown,(isset($line_items[$level_3['parent_array'][0]['id']][0])?$line_items[$level_3['parent_array'][0]['id']][0]['risk_level']:""), 'class="risk_level select2" style="width:100%;"').'</td>
																  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$level_3['parent_array'][0]['id'].'][]"  rows="1" style="width:100%">'.(isset($line_items[$level_3['parent_array'][0]['id']][0])?$line_items[$level_3['parent_array'][0]['id']][0]['response']:"").'</textarea></td>';

																$temp_total_c 	  += (double)$temp_c;
																$temp_total_c_end += (double)$temp_c_lye;
																$temp_total_c_beg += (double)$level_3['parent_array'][0]['company_beg_prev_ye_value'];
																// }
															echo '</tr>';

															//loop line item other than first row
															if(isset($line_items[$level_3['parent_array'][0]['id']])){
																if(count($line_items[$level_3['parent_array'][0]['id']]) > 1)
																{
																	foreach ($line_items[$level_3['parent_array'][0]['id']] as $key => $each_line) 
																	{
																		if($key > 0)
																		{


																			echo  '<tr class="rows-for-'.$level_3['parent_array'][0]['id'].'">' .
																				      '<td></td>';

																			echo '<td align="right"></td>';
																				
																				if(!empty($last_fye_end))
																				{

																					echo 
																					'<td align="right"></td>';
																				
																				 	if($show_third_col)
																					{
																						echo 
																						'<td align="right"></td>';
																					}
																					echo '<td align="right"></td>';
																					echo '<td align="center"></td>';
																				}

																				
																				echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$level_3['parent_array'][0]['id'].'][]" value="'.$each_line['id'].'"/></td>
																				      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.$each_line['id'].'"></td>
																				  <td style="text-align: center;" class="ref_no"></td>
																				  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$level_3['parent_array'][0]['id'].'][]"  rows="1" style="width:100%">'.$each_line['risk_text'].'</textarea></td>
																			      <td style="text-align: center;">'.form_dropdown('bs_assertion['.$level_3['parent_array'][0]['id'].'][]', $bs_assertion_dropdown,$each_line['assertion'], 'class="bs_assertion select2" style="width:100%;"').'</td>
																				  <td style="text-align: center;">'.form_dropdown('risk_level['.$level_3['parent_array'][0]['id'].'][]', $risk_lvl_dropdown, $each_line['risk_level'], 'class="risk_level select2" style="width:100%;"').'</td>
																				  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$level_3['parent_array'][0]['id'].'][]"  rows="1" style="width:100%">'.$each_line['response'].'</textarea></td>';

																				// $temp_total_c 	  += (double)$level_3['parent_array'][0]['total_c'];
																				// $temp_total_c_end += (double)$level_3['parent_array'][0]['total_c_lye'];
																				// $temp_total_c_beg += (double)$level_3['parent_array'][0]['company_beg_prev_ye_value'];
																				// }
																			echo '</tr>';
																		}
																	}
																}
															}


															

															$index++;
														}
														/* END OF DISPLAY LEVEL 3 THAT HAS SUBCATEGORY */


														
													

													}

												}
												/* END OF DISPLAY LEVEL 3 WITHOUT SUBCATEGORY UNDER IT */
											}
											
											/* DISPLAY TOTAL FOR EACH CATEGORY	*/
											$total 		= $temp_total_c;
											$total_end 	= $temp_total_c_end;
											$total_beg 	= $temp_total_c_beg;

											/* CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES - COMPANY */
											if($level_1_description == "Assets")	// NON-CURRENT ASSETS || CURRENT ASSETS
											{
												$total_assets_c 	+= $total;
												$total_assets_c_end += $total_end;
												$total_assets_c_beg += $total_beg;
											}
											elseif($level_1_description == "Equity") // EQUITY
											{
												$total_equity_c 	+= $total;
												$total_equity_c_end += $total_end;
												$total_equity_c_beg += $total_beg;
											}
											elseif($level_1_description == "Liabilities") // NON-CURRENT LIABILITIES || CURRENT LIABILITIES
											{
												$total_liabilities_c 	 += $total;
												$total_liabilities_c_end += $total_end;
												$total_liabilities_c_beg += $total_beg;
											}
											/* END OF CALCULATE TOTAL ASSETS, TOTAL EQUITY, LIABILITIES */
											
											$variance_value = calculate_variance($total,$total_end);

											echo 
												'<tr>' . 
													'<td></td>' . 
													'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
														'<span id="' . $display_class_ye . '_subtotal_'. $level_2['parent_array'][0]['id'] .'">' . 
															negative_bracket($total) . 
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
								

								$total_assets 	  = 0.00;
								$total_assets_end = 0.00;
								$total_assets_beg = 0.00;

						
								$total_assets 	  = $total_assets_c;
								$total_assets_end = $total_assets_c_end;
								$total_assets_beg = $total_assets_c_beg;


								$variance_value = calculate_variance($total_assets,$total_assets_end);

								echo 
								'<tr class="total_assets_tr">' . 
									'<td><strong>Total assets</strong></td>' . 
									'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
										'<span id="'. $display_class_ye .'_total_assets" class="total_assets">' . negative_bracket($total_assets) . '</span>' .
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
									$total_liabilities 	   = 0.00;
									$total_liabilities_end = 0.00;
									$total_liabilities_beg = 0.00;

						
									$total_liabilities 	   = $total_liabilities_c;
									$total_liabilities_end = $total_liabilities_c_end;
									$total_liabilities_beg = $total_liabilities_c_beg;

									$variance_value = calculate_variance($total_liabilities,$total_liabilities_end);

									echo 
									'<tr>' .  
										'<td><strong>Total liabilities</strong></td>' . 
										'<td align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . 
											'<span id="'. $display_class_ye .'_total_liabilities">' . negative_bracket($total_liabilities) . '</span>' .
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
									$total_equity_liabilities 	  = 0.00;
									$total_equity_liabilities_end = 0.00;
									$total_equity_liabilities_beg = 0.00;

								
									$total_equity_liabilities 	  = $total_equity_c 	+ $total_liabilities_c;
									$total_equity_liabilities_end = $total_equity_c_end + $total_liabilities_c_end;
									$total_equity_liabilities_beg = $total_equity_c_beg + $total_liabilities_c_beg;

									$variance_value = calculate_variance($total_equity_liabilities,$total_equity_liabilities_end);
								

									echo 
									'<tr class="total_equity_liabilities_tr">' . 
										'<td><strong>Total equity and liabilities</strong></td>' . 
										'<td align="right" style="border-bottom-style: double; border-bottom-width: 5px;">' . 
											'<span id="'. $display_class_ye .'_total_equity_liabilities" class="total_equity_liabilities">' . negative_bracket($total_equity_liabilities) . '</span>' . 
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

<!-- <include src="<?= base_url()?>application/modules/caf/template/adjustment_popup.html"></include> -->

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
	var save_balance_sheet_url = "<?php echo site_url('caf/save_balance_sheet'); ?>";
	var delete_bs_line_item_url =  "<?php echo site_url('caf/delete_bs_line_item'); ?>";
	var export_balancesheet_pdf_url = "<?php echo (site_url('caf/export_balancesheet_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";
	var get_je_no_url  = "<?php echo site_url('caf/get_je_no'); ?>";
	// var bs_assertion_dropdown = JSON.parse('<?php echo json_encode($bs_assertion_dropdown); ?>');
	// var risk_lvl_dropdown = JSON.parse('<?php echo json_encode($risk_lvl_dropdown); ?>');

	
</script>


<script src="<?= base_url()?>application/modules/caf/js/balance_sheet.js" charset="utf-8"></script>
<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>