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

		<form id="form_state_detailed_profit_loss">
			<?php
				if($show_data_content)
				{
					echo form_dropdown('pl_assertion_clone', $pl_assertion_dropdown, '', 'id="pl_assertion_clone" style="display:none;width:100%;"');
					echo form_dropdown('risk_level_clone', $risk_lvl_dropdown, '', 'id="risk_level_clone" style="display:none;width:100%;"');
			?>
			<table id="tbl_state_detailed_profit_loss" class="table table-hover table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black">
				<input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">
				<thead>
					<tr>
						<th rowspan="0" style="width: <?=$width['account_desc'] ?>%;"></th>
						<?php 
							if(!empty($current_fye_end))
							{
								echo '<th style="border-bottom:1px solid black; text-align:center; width: '.$width['value'].'%">';
								
								if($display_restated)
								{
									echo '<br/>';
								}

								echo $current_fye_end . '<br/>' ."Unadjusted <br/>".
									
								'</th>';
							}

							if(!empty($last_fye_end))
							{
								echo '<th style="border-bottom:1px solid black; text-align:center; width: '.$width['value'].'%">';

								if($display_restated)
								{
									echo 'Restated <br/>';
								}
								
								echo $last_fye_end . ' <br/>' . "Final".
									'</th>';

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
					<tr>
						<th style="width: <?=$width['value'] ?>%; text-align: center; border-top: 1px solid #000;">$</th>

						<?php 
							if(!empty($last_fye_end))
							{
								echo '<th style="width:'.$width['value'].'%; text-align: center; border-top: 1px solid #000;">$</th>';

								if($display_restated)
								{
									echo '<th style="width:'.$width['value'].'%; text-align: center; border-top: 1px solid #000;">$</th>';
								}

								echo '<th style="width:'.$width['variance'] .'%;text-align: center;border-top: 1px solid #000;">$</th>
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
						$total = [];
						$total_revenue_current 			= 0.00; // (+) ** M1003
						$total_revenue_current_ly 		= 0.00; // (+) ** M1003
						$total_cost_of_sale_current 	= 0.00; // (-) ** M1004
						$total_cost_of_sale_current_ly 	= 0.00; // (-) ** M1004
						$total_other_income				= 0.00; // 	   ** M1005
						$total_other_income_ly			= 0.00; // 	   ** M1005

						$total_ly = [];

						$revenue_exist			= false;
						$cost_of_sales_exist	= false;

						$counter = 0;

						$temp_total_ly		= 0.00;

						$closing_inventories_ac = "";
						$closing_inventories_key = array_search("Closing inventories", array_column($fs_ntfs_list, "description"));	// get key

						if(!empty($closing_inventories_key) || (string)$closing_inventories_key == 0)
			            {
			            	$closing_inventories_ac = $fs_ntfs_list[$closing_inventories_key]['account_code'];	// get description from fs_ntfs_list json from document name "Statement of detailed profit or loss"
			            }


						foreach($state_detailed_pro_loss_data as $key => $main_category)
				        {
				        	$add_less_display = "";
				        	$main_account_description = "";
				            $main_account_code = $main_category[0]['parent_array'][0]['account_code'];
				            
				            /* Settings */
				            // check using this account code > take the default description > do checking using description (list take from json)
				            $fs_ntfs_list_key = array_search($main_account_code, array_column($fs_ntfs_list, "account_code"));	// get key

				            if(!empty($fs_ntfs_list_key) || (string)$fs_ntfs_list_key == 0)
				            {
				            	$main_account_description = $fs_ntfs_list[$fs_ntfs_list_key]['description'];	// get description from fs_ntfs_list json from document name "Statement of detailed profit or loss"
				            }

				            if($main_account_description == "Revenue")	// revenue
				            {
				            	if($main_category[0]['parent_array'][0]['description'] == "Revenue" )
				            	{
				            		foreach ($main_category[0]['child_array'] as $key2 => $child_array) 
				            		{
				            			if(isset($child_array['parent_array']))
				            			{
					            			if($child_array['parent_array'] != null && $child_array['child_array'] != null)
					            			{
					            				$revenue_exist = true;
					            			}

					            		}
					            		elseif ( $child_array['child_array'] != null) {
				            				$revenue_exist = true;
				            			}


						            }
				            		// $revenue_exist = true;
				            	}
				            	// $revenue_exist = true;
				            	
				            }
				            elseif($main_account_description == "Cost of Sales")	// cost of sale
				            {
				            	if($main_category[0]['parent_array'][0]['description'] == "Cost of Sales" )
				            	{
				            		foreach ($main_category[0]['child_array'] as $key2 => $child_array) 
				            		{
				            			if(isset($child_array['parent_array']))
				            			{
					            			if($child_array['parent_array'] != null && $child_array['child_array'] != null)
					            			{
					            				$cost_of_sales_exist = true;
				            					$add_less_display 	 = "Less: ";

					            			}

					            		}
					            		elseif ( $child_array['child_array'] != null) {
				            				$cost_of_sales_exist = true;
				            				$add_less_display 	 = "Less: ";

				            			}


						            }
				            						            	}
				            	// $cost_of_sales_exist = true;
				            	// $add_less_display 	 = "Less: ";
				            	
				            }
				            elseif($main_account_description == "Income")	// other income
				            {
				            	$add_less_display = "Add: ";
				            }
				            /* END OF Settings */
				            // print_r($main_category);

				            /* ------------------- If NO SUB under main category ------------------- */
			            	if(count($main_category[0]['child_array']) == 0)
			            	{
			            		$c_lye_value = 0.00;

			            		if(!empty($main_category[0]['parent_array'][0]['company_end_prev_ye_value']))
			            		{
			            			if($main_account_description == "Revenue" || $main_account_description == "Income")
		            				{
		            					$c_lye_value = $main_category[0]['parent_array'][0]['company_end_prev_ye_value'] * (-1);
		            				}
		            				else
		            				{
		            					$c_lye_value = $main_category[0]['parent_array'][0]['company_end_prev_ye_value'];
		            				}
			            		}

			            		
				    			if(ucfirst(strtolower($main_category[0]['parent_array'][0]['description'])) == "Revenue")
				            	{
			            		
				            		echo '<tr>' . 
											'<td><em>' . $add_less_display . ucfirst(strtolower($main_category[0]['parent_array'][0]['description'])) . '</em></td>' . 
											'<td style="text-align:right; border-bottom:1px solid black;"> - </td>';

											if(!empty($last_fye_end))
											{
												echo '<td align="right" style="border-bottom:1px solid black;">' . 
														// '<input type="hidden" name="SDPL_parent_fs_categorized_account_id['. $counter .']" value="'. $main_category[0]['parent_array'][0]['account_code'] .'">' .
														// '<input type="hidden" name="SDPL_sub_fs_categorized_account_id['. $counter .']" value="'. $main_category[0]['parent_array'][0]['id'] .'">' .
														// '<input type="text" name="SDPL_company_end_prev_year_value[' . $counter . ']" class="form-control SDPL_all_values SDPL_values_under_' . $main_category['parent_array'][0]['id'] . '" style="text-align: right;" value="' . $c_lye_value . '" onchange="calculation('. $main_category['parent_array'][0]['id'] .')">' .
														negative_bracket($c_lye_value) . 
													'</td>';
											}
									echo '</tr>';
									echo '<tr>' .
											'<td></td>' .
											'<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' .
												'<span>' . negative_bracket(0) . '</span>' .
											'</td>';

											if(!empty($last_fye_end))
											{
												echo '<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' . 
														// '<span id="SDPL_subtotal_'. $main_category['parent_array'][0]['id'] .'">' . negative_bracket($temp_total_ly) . '</span>' . 
														'<span>' . negative_bracket(0) . '</span>' . 
													'</td>';
												echo '<td align="right">'.negative_bracket(0).'</td>';
												echo '<td align="center">'.number_format(0).'%</td>';  
											}
					
									    
									echo '</tr>';
								}
								
								

								// total up 
								$temp_total    = 0.00;
								$temp_total_ly = $c_lye_value;
								$counter ++;
			            	}
			            	/* ------------------- END OF If NO SUB under main category ------------------- */

			            	/* If there are subs under main category */
			            	else
			            	{
			            		$temp_total = 0.00;
			            		$temp_total_ly = 0.00;

			            		$check_existance = false;

			            		foreach ($main_category[0]['child_array'] as $key2 => $child_array) 
			            		{
			            			if(isset($child_array['parent_array']))
			            			{
				            			if($child_array['parent_array'] != null && $child_array['child_array'] != null)
				            			{
				            				$check_existance = true;
				            			}

				            		}
				            		elseif ( $child_array['child_array'] != null) {
			            				$check_existance = true;
			            			}


					            }


				            	if($check_existance || ucfirst(strtolower($main_category[0]['parent_array'][0]['description'])) == "Revenue" )
				            	{
				            		echo '<tr>' . 
											'<td><em>' . $add_less_display . ucfirst(strtolower($main_category[0]['parent_array'][0]['description'])) . '</em></td>' .
											'<td></td>';
											if(!empty($last_fye_end))
											{
												echo '<td></td>';
											}
									echo '</tr>';
								}

			            		foreach ($main_category[0]['child_array'] as $key2 => $child_array) 
			            		{
			            			if(isset($child_array['parent_array']))
			            			{
				            			if($child_array['parent_array'] != null && $child_array['child_array'] != null)
				            			{
				            				if($main_account_description == "Revenue" || $main_account_description == "Income")
				            				{
				            					$temp_value 	= $child_array['parent_array'][0]['total_c'] * (-1);
				            					$temp_value_ly 	= $child_array['parent_array'][0]['total_c_lye'] * (-1);
				            				}
				            				else
				            				{
				            					$temp_value 	= $child_array['parent_array'][0]['total_c'];
				            					$temp_value_ly 	= $child_array['parent_array'][0]['total_c_lye'];
				            				}

				            				/* ------ Add in Display "Less:" for Closing inventories ------ */
				            				if($child_array['parent_array'][0]['account_code'] == $closing_inventories_ac)
				            				{
				            					$child_array['parent_array'][0]['description'] = $add_less_display . $child_array['parent_array'][0]['description'];
				            				}
				            				/* ------ END OF Add in Display "Less:" for Closing inventories ------ */

				            				$variance_value = calculate_variance($temp_value, $temp_value_ly);
				            				// Display child with subcategory. 

				            				if($temp_value || $temp_value_ly)
				            				{
					            				echo '<tr class="rows-for-'.$child_array['parent_array'][0]['id'].'">' . 
					            						'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $child_array['parent_array'][0]['id'].'">'.
														'<td>' . $child_array['parent_array'][0]['description'] . '</td>' .
														'<td style="text-align:right;">'. negative_bracket($temp_value) .'</td>';
														if(!empty($last_fye_end))
														{
															echo '<td style="text-align:right;">' . negative_bracket($temp_value_ly);
															echo '</td>';
															echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
															echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';
														}

												
												echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$child_array['parent_array'][0]['id'].'][]" value="'.(isset($line_items[$child_array['parent_array'][0]['id']][0])?$line_items[$child_array['parent_array'][0]['id']][0]['id']:"").'"/>
															<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add line item" id="" onclick="addRow(this,'.$last_fye_end_flag.')"><i class="fa fa-plus-circle amber" style="font-size:16px;"></i></a>
													  </td>
													  <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.(isset($line_items[$child_array['parent_array'][0]['id']][0])?$line_items[$child_array['parent_array'][0]['id']][0]['id']:"").'"></td>
													  <td style="text-align: center;" class="ref_no">1</td>
													  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$child_array['parent_array'][0]['id'].'][]"  rows="1" style="width:100%">'.(isset($line_items[$child_array['parent_array'][0]['id']][0])?$line_items[$child_array['parent_array'][0]['id']][0]['risk_text']:"").'</textarea></td>
												      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$child_array['parent_array'][0]['id'].'][]', $pl_assertion_dropdown,(isset($line_items[$child_array['parent_array'][0]['id']][0])?$line_items[$child_array['parent_array'][0]['id']][0]['assertion']:""), 'class="pl_assertion select2" style="width:100%;"').'</td>
													  <td style="text-align: center;">'.form_dropdown('risk_level['.$child_array['parent_array'][0]['id'].'][]', $risk_lvl_dropdown,(isset($line_items[$child_array['parent_array'][0]['id']][0])?$line_items[$child_array['parent_array'][0]['id']][0]['risk_level']:""), 'class="risk_level select2" style="width:100%;"').'</td>
													  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$child_array['parent_array'][0]['id'].'][]"  rows="1" style="width:100%">'.(isset($line_items[$child_array['parent_array'][0]['id']][0])?$line_items[$child_array['parent_array'][0]['id']][0]['response']:"").'</textarea></td>';      
												echo '</tr>';

												//loop line item other than first row
												if(isset($line_items[$child_array['parent_array'][0]['id']])){
													if(count($line_items[$child_array['parent_array'][0]['id']]) > 1)
													{
														foreach ($line_items[$child_array['parent_array'][0]['id']] as $key => $each_line) 
														{
															if($key > 0)
															{


																echo  '<tr class="rows-for-'.$child_array['parent_array'][0]['id'].'">' .
																	      '<td></td>';

																echo '<td align="right"></td>';
																	
																	if(!empty($last_fye_end))
																	{

																		echo 
																		'<td align="right"></td>';
																		echo '<td align="right"></td>';
																		echo '<td align="center"></td>';
																	
												
																	}
																	echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$child_array['parent_array'][0]['id'].'][]" value="'.$each_line['id'].'"/></td>
																	      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.$each_line['id'].'"></td>
																	  <td style="text-align: center;" class="ref_no"></td>
																	  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$child_array['parent_array'][0]['id'].'][]"  rows="1" style="width:100%">'.$each_line['risk_text'].'</textarea></td>
																      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$child_array['parent_array'][0]['id'].'][]', $pl_assertion_dropdown,$each_line['assertion'], 'class="bs_assertion select2" style="width:100%;"').'</td>
																	  <td style="text-align: center;">'.form_dropdown('risk_level['.$child_array['parent_array'][0]['id'].'][]', $risk_lvl_dropdown, $each_line['risk_level'], 'class="risk_level select2" style="width:100%;"').'</td>
																	  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$child_array['parent_array'][0]['id'].'][]"  rows="1" style="width:100%">'.$each_line['response'].'</textarea></td>';

																	// }
																echo '</tr>';
															}
														}
													}
												}

												// total up 
												$temp_total    += $temp_value;
												$temp_total_ly += $temp_value_ly;

												$counter++;
					            			}
					            		}
				            		}
			            			elseif($child_array['child_array'] != null)
			            			{
			            				if($main_account_description == "Revenue" || $main_account_description == "Income")
			            				{
			            					$temp_value = $child_array['child_array']['value'] * (-1);
			            					$temp_value_ly = $child_array['child_array']['company_end_prev_ye_value'] * (-1);
			            				}
			            				else
			            				{
			            					$temp_value = $child_array['child_array']['value'];
			            					$temp_value_ly = $child_array['child_array']['company_end_prev_ye_value'];
			            				}

			            				// Display child without subcategory. 
			            				$variance_value = calculate_variance($temp_value, $temp_value_ly);
			            				$temp_id = $child_array['child_array']['id'];

			            				if($temp_value || $temp_value_ly)
			            				{
				            				echo '<tr class="rows-for-'.$temp_id.'">' . 
				            						'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
													'<td>' . $child_array['child_array']['description'] . '</td>' .
													'<td style="text-align:right;">'. negative_bracket($temp_value) .'</td>';

													if(!empty($last_fye_end))
													{
														echo '<td style="text-align:right;">' . 
															// '<input type="hidden" name="SDPL_parent_fs_categorized_account_id['. $counter .']" value="'. $main_category['parent_array'][0]['account_code'] .'">' .
															// '<input type="hidden" name="SDPL_sub_fs_categorized_account_id['. $counter .']" value="'. $child_array['child_array']['id'] .'">' . 
															// '<input type="text" name="SDPL_company_end_prev_year_value[' . $counter . ']" class="form-control SDPL_all_values SDPL_values_under_' . $main_category['parent_array'][0]['id'] . '" style="text-align: right;" value="' . $child_array[0]['company_end_prev_ye_value'] . '" onchange="calculation('. $main_category['parent_array'][0]['id'] .')">';
															negative_bracket($temp_value_ly);
														echo '</td>';
														echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
														echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';
													}
													
											      
											echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"/>
														<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add line item" id="" onclick="addRow(this,'.$last_fye_end_flag.')"><i class="fa fa-plus-circle amber" style="font-size:16px;"></i></a>
												  </td>
												  <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"></td>
												  <td style="text-align: center;" class="ref_no">1</td>
												  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_text']:"").'</textarea></td>
											      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['assertion']:""), 'class="pl_assertion select2" style="width:100%;"').'</td>
												  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_level']:""), 'class="risk_level select2" style="width:100%;"').'</td>
												  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['response']:"").'</textarea></td>';      
											echo '</tr>';

											//loop line item other than first row
											if(isset($line_items[$temp_id])){
												if(count($line_items[$temp_id]) > 1)
												{
													foreach ($line_items[$temp_id] as $key => $each_line) 
													{
														if($key > 0)
														{


															echo  '<tr class="rows-for-'.$temp_id.'">' .
																      '<td></td>';

															echo '<td align="right"></td>';
																
																if(!empty($last_fye_end))
																{

																	echo 
																	'<td align="right"></td>';
																	echo '<td align="right"></td>';
																	echo '<td align="center"></td>';															
																}

																
																echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.$each_line['id'].'"/></td>
																      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.$each_line['id'].'"></td>
																  <td style="text-align: center;" class="ref_no"></td>
																  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['risk_text'].'</textarea></td>
															      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,$each_line['assertion'], 'class="bs_assertion select2" style="width:100%;"').'</td>
																  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown, $each_line['risk_level'], 'class="risk_level select2" style="width:100%;"').'</td>
																  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['response'].'</textarea></td>';

																// }
															echo '</tr>';
														}
													}
												}
											}

											// total up 
											$temp_total    += $temp_value;
											$temp_total_ly += $temp_value_ly;

											$counter ++;
										}
			            			}
			            		
				            	}

				            	// show total for the category
				            	if($check_existance || ucfirst(strtolower($main_category[0]['parent_array'][0]['description'])) == "Revenue")
				            	{
					            	$variance_value = calculate_variance($temp_total, $temp_total_ly);

					            	if($temp_total || $temp_total_ly)
					            	{
										echo '<tr>' .
												'<td></td>' .
												'<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' .
													// '<input type="hidden" name="current_fye_end['. $key .']" value="'. $current_fye_end .'">' .
													// '<input type="hidden" name="category_total['. $key .']" value="'. $temp_total .'">' .
													// '<input type="hidden" name="categorized_account_id['. $key .']" value="'. $main_category['parent_array'][0]['id'] .'">' .
													'<span>' . negative_bracket($temp_total) . '</span>' .
												'</td>';

												if(!empty($last_fye_end))
												{
													echo '<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' . 
															// '<span id="SDPL_subtotal_'. $main_category['parent_array'][0]['id'] .'">' . negative_bracket($temp_total_ly) . '</span>' . 
															'<span>' . negative_bracket($temp_total_ly) . '</span>' . 
														'</td>';
													echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
													echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';  
												}
						
										    
										echo '</tr>';
									}
								}

			            	}
			            	/* END OF If there are subs under main category */

			            	if(isset($main_category['child_array']))
			            	{
			            		if($main_account_description == "Income" || (count($main_category['child_array']) == 0))
					            {
									echo '<tr><td colspan="3">&nbsp;</td></tr>';
					            }
			            	}
							

				            if($main_account_description == "Revenue")
				            {
				            	$total_revenue_current 	  = $temp_total;
								$total_revenue_current_ly = $temp_total_ly;
				            }
				            elseif($main_account_description == "Cost of Sales")
				            {
				            	$total_cost_of_sale_current    = $temp_total;
								$total_cost_of_sale_current_ly = $temp_total_ly;
				            }
				            elseif($main_account_description == "Income")
				            {
				            	$total_other_income    = $temp_total;
								$total_other_income_ly = $temp_total_ly;
				            }

				            /* CALCULATE AND DISPLAY GROSS PROFIT */
				            if($main_account_description == "Cost of Sales" && ($revenue_exist || $cost_of_sales_exist))
				            {
				            	$gross_profit 	 = $total_revenue_current - $total_cost_of_sale_current;
								$gross_profit_ly = $total_revenue_current_ly - $total_cost_of_sale_current_ly;

								$variance_value = calculate_variance($gross_profit, $gross_profit_ly);

								if($gross_profit || $gross_profit_ly)
					            {
					            	echo '<tr>'.
					            			'<td><em>Gross Profit</em></td>' . 
					            			'<td style="text-align:right; border-bottom:1px solid black;">'. 
													// '<input type="hidden" name="gross_profit" value="' . $gross_profit . '" >' .
													negative_bracket($gross_profit) .
												'</td>';
												if(!empty($last_fye_end))
												{
													echo '<td style="text-align:right; border-bottom:1px solid black;">'. 
															// '<input type="hidden" name="gross_profit_ly" value="' . $gross_profit_ly . '" >' .
															'<span class="gross_profit_ly">' . negative_bracket($gross_profit_ly) . '</span>' .
														'</td>';
													echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
													echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';     
												}
					            	 
									echo '</tr>';

					            	echo '<tr>' . 
											'<td>&nbsp;</td>'.
											'<td>&nbsp;</td>';
											if(!empty($last_fye_end))
											{
												echo '<td>&nbsp;</td>';
											}
									echo '</tr>';
								}
				            }
				            /* END OF CALCULATE AND DISPLAY GROSS PROFIT */
				        }

				         /* DISPLAY OPERATING EXPENSES */
				         echo '<tr>' . 
										'<td>&nbsp;</td>'.
										'<td>&nbsp;</td>';
										if(!empty($last_fye_end))
										{
											echo '<td>&nbsp;</td>';
										}
						echo '</tr>';
				        echo '<tr>' .
								'<td style="text-align:left;"><em>Less: Operating expenses</em></td>' . 
								'<td class="total_operating_expenses"></td>';
								if(!empty($last_fye_end))
								{
									echo '<td></td>';
								}
						echo '</tr>';

				        foreach($schedule_operating_expense_data[0]['child_array'] as $key => $value)
						{
							// print_r($value);

							$temp_total 	= 0.00;
							$temp_total_ly 	= 0.00;
							// print_r($value);
							if(isset($value['parent_array']))
							{

								if(count($value['parent_array']) > 0)
								{
									echo 
										'<tr>' . 
											'<td><i>' . $value['parent_array'][0]['description'] . '</i></td>' .	// Description in Level 2
											'<td colspan="2"></td>' .
										'</tr>';

									foreach ($value['child_array'] as $key2 => $details_data) 
									{
										// print_r($details_data);
										


										if(isset($details_data['parent_array']))
										{
											if(count($details_data['parent_array']) > 0)
											{

												if(!isset($details_data['child_array'][0]['parent_array']))
												{
													if(isset($details_data['parent_array'][0]['total_c']))
													{
														$temp_total 	+= convert_string_to_number($details_data['parent_array'][0]['total_c']);
														$temp_total_ly 	+= convert_string_to_number($details_data['parent_array'][0]['total_c_lye']);
													}

													$temp_total_c = isset($details_data['parent_array'][0]['total_c'])?$details_data['parent_array'][0]['total_c']:0 ;
													$temp_total_c_lye = isset($details_data['parent_array'][0]['total_c'])?$details_data['parent_array'][0]['total_c_lye']:0 ;

													// echo 
													// 	'<tr>' . 
													// 		'<td>' . $details_data['parent_array'][0]['description'] . '</td>' .	// Description in level 3 (have sub)
													// 		'<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['parent_array'][0]['total_c']) . '</td>';
													$variance_value = calculate_variance($temp_total_c, $temp_total_c_lye);
													$temp_id = $details_data['parent_array'][0]['id'];

													if($temp_total_c || $temp_total_c_lye)
					            					{
														echo 
															'<tr class="rows-for-'.$temp_id.'">' .
																'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'. 
																'<td>' . $details_data['parent_array'][0]['description'] . '</td>' .	// Description in level 3 (have sub)
																'<td style="text-align: right;">' . negative_bracket($temp_total_c) . '</td>';

														if(!empty($last_fye_end))
														{
															// echo '<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['parent_array'][0]['total_c_lye']) . '</td>';

															echo '<td style="text-align: right;">' . negative_bracket($temp_total_c_lye) . '</td>';
															echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
															echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
														}

														     
														echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"/>
																<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add line item" id="" onclick="addRow(this,'.$last_fye_end_flag.')"><i class="fa fa-plus-circle amber" style="font-size:16px;"></i></a>
															  </td>
															  <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"></td>
															  <td style="text-align: center;" class="ref_no">1</td>
															  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_text']:"").'</textarea></td>
														      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['assertion']:""), 'class="pl_assertion select2" style="width:100%;"').'</td>
															  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_level']:""), 'class="risk_level select2" style="width:100%;"').'</td>
															  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['response']:"").'</textarea></td>';      
														echo '</tr>';

														//loop line item other than first row
														if(isset($line_items[$temp_id])){
															if(count($line_items[$temp_id]) > 1)
															{
																foreach ($line_items[$temp_id] as $key => $each_line) 
																{
																	if($key > 0)
																	{


																		echo  '<tr class="rows-for-'.$temp_id.'">' .
																			      '<td></td>';

																		echo '<td align="right"></td>';
																			
																			if(!empty($last_fye_end))
																			{

																				echo 
																				'<td align="right"></td>';
																				echo '<td align="right"></td>';
																				echo '<td align="center"></td>';
																	
																			}

																			
																			echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.$each_line['id'].'"/></td>
																			      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.$each_line['id'].'"></td>
																			  <td style="text-align: center;" class="ref_no"></td>
																			  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['risk_text'].'</textarea></td>
																		      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,$each_line['assertion'], 'class="bs_assertion select2" style="width:100%;"').'</td>
																			  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown, $each_line['risk_level'], 'class="risk_level select2" style="width:100%;"').'</td>
																			  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['response'].'</textarea></td>';

																			// }
																		echo '</tr>';
																	}
																}
															}
														}
													}
												}
												else
												{
													echo 
														'<tr>' .
															'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id">'. 
															'<td><b>' . $details_data['parent_array'][0]['description'] . ':</b></td>' .	// Description in level 3 (have sub)
															'<td colspan="8"></td>
														<tr>';
												}
												foreach ($details_data['child_array'] as $key3 => $details_data_child) 
												{
													if(isset($details_data_child['parent_array']) && count($details_data_child['parent_array']) > 0)
													{
														if(isset($details_data_child['parent_array'][0]['total_c']))
														{
															$temp_total 	+= convert_string_to_number($details_data_child['parent_array'][0]['total_c']);
															$temp_total_ly 	+= convert_string_to_number($details_data_child['parent_array'][0]['total_c_lye']);
														}

														$temp_total_c = isset($details_data_child['parent_array'][0]['total_c'])?$details_data_child['parent_array'][0]['total_c']:0 ;
														$temp_total_c_lye = isset($details_data_child['parent_array'][0]['total_c'])?$details_data_child['parent_array'][0]['total_c_lye']:0 ;

														// echo 
														// 	'<tr>' . 
														// 		'<td>' . $details_data['parent_array'][0]['description'] . '</td>' .	// Description in level 3 (have sub)
														// 		'<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['parent_array'][0]['total_c']) . '</td>';
														$variance_value = calculate_variance($temp_total_c, $temp_total_c_lye);
														$temp_id = $details_data_child['parent_array'][0]['id'];

														if($temp_total_c || $temp_total_c_lye)
					            						{
															echo 
																'<tr class="rows-for-'.$temp_id.'">' .
																	'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'. 
																	'<td>- ' . $details_data_child['parent_array'][0]['description'] . '</td>' .	// Description in level 3 (have sub)
																	'<td style="text-align: right;">' . negative_bracket($temp_total_c) . '</td>';

															if(!empty($last_fye_end))
															{
																// echo '<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['parent_array'][0]['total_c_lye']) . '</td>';

																echo '<td style="text-align: right;">' . negative_bracket($temp_total_c_lye) . '</td>';
																echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
																echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
															}

															     
															echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"/>
																	<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add line item" id="" onclick="addRow(this,'.$last_fye_end_flag.')"><i class="fa fa-plus-circle amber" style="font-size:16px;"></i></a>
																  </td>
																  <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"></td>
																  <td style="text-align: center;" class="ref_no">1</td>
																  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_text']:"").'</textarea></td>
															      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['assertion']:""), 'class="pl_assertion select2" style="width:100%;"').'</td>
																  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_level']:""), 'class="risk_level select2" style="width:100%;"').'</td>
																  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['response']:"").'</textarea></td>';      
															echo '</tr>';

															//loop line item other than first row
															if(isset($line_items[$temp_id])){
																if(count($line_items[$temp_id]) > 1)
																{
																	foreach ($line_items[$temp_id] as $key => $each_line) 
																	{
																		if($key > 0)
																		{


																			echo  '<tr class="rows-for-'.$temp_id.'">' .
																				      '<td></td>';

																			echo '<td align="right"></td>';
																				
																				if(!empty($last_fye_end))
																				{

																					echo 
																					'<td align="right"></td>';
																					echo '<td align="right"></td>';
																					echo '<td align="center"></td>';
																		
																				}

																				
																				echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.$each_line['id'].'"/></td>
																				      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.$each_line['id'].'"></td>
																				  <td style="text-align: center;" class="ref_no"></td>
																				  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['risk_text'].'</textarea></td>
																			      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,$each_line['assertion'], 'class="bs_assertion select2" style="width:100%;"').'</td>
																				  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown, $each_line['risk_level'], 'class="risk_level select2" style="width:100%;"').'</td>
																				  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['response'].'</textarea></td>';

																				// }
																			echo '</tr>';
																		}
																	}
																}
															}
														}
													}
													
												}

											}
										}
										else
										{
											$temp_total 	+= convert_string_to_number($details_data['child_array']['value']);
											$temp_total_ly 	+= convert_string_to_number($details_data['child_array']['company_end_prev_ye_value']);

											// echo 
											// 	'<tr>' . 
											// 		'<td>' . $details_data['child_array']['description'] . '</td>' .	// Description in level 3 (no sub)
											// 		'<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['child_array']['value']) . '</td>';
											$variance_value = calculate_variance($details_data['child_array']['value'], $details_data['child_array']['company_end_prev_ye_value']);
											$temp_id = $details_data['child_array']['id'];

											if($details_data['child_array']['value'] || $details_data['child_array']['company_end_prev_ye_value'])
					            			{

												echo 
													'<tr class="rows-for-'.$temp_id.'">' . 
														'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
														'<td>' . $details_data['child_array']['description'] . '</td>' .	// Description in level 3 (no sub)
														'<td style="text-align: right;">' . negative_bracket($details_data['child_array']['value']) . '</td>';

												if(!empty($last_fye_end))
												{
													// echo '<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['child_array']['company_end_prev_ye_value']) . '</td>';

													echo '<td style="text-align: right;">' . negative_bracket($details_data['child_array']['company_end_prev_ye_value']) . '</td>';
													echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
													echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
												}

												   
												echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"/>
														<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add line item" id="" onclick="addRow(this,'.$last_fye_end_flag.')"><i class="fa fa-plus-circle amber" style="font-size:16px;"></i></a>
													  </td>
													  <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"></td>
													  <td style="text-align: center;" class="ref_no">1</td>
													  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_text']:"").'</textarea></td>
												      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['assertion']:""), 'class="pl_assertion select2" style="width:100%;"').'</td>
													  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_level']:""), 'class="risk_level select2" style="width:100%;"').'</td>
													  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['response']:"").'</textarea></td>';      
												echo '</tr>';  

												//loop line item other than first row
												if(isset($line_items[$temp_id])){
													if(count($line_items[$temp_id]) > 1)
													{
														foreach ($line_items[$temp_id] as $key => $each_line) 
														{
															if($key > 0)
															{


																echo  '<tr class="rows-for-'.$temp_id.'">' .
																	      '<td></td>';

																echo '<td align="right"></td>';
																	
																	if(!empty($last_fye_end))
																	{

																		echo 
																		'<td align="right"></td>';
																		echo '<td align="right"></td>';
																		echo '<td align="center"></td>';
																	
															
																	}

																	
																	echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.$each_line['id'].'"/></td>
																	      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.$each_line['id'].'"></td>
																	  <td style="text-align: center;" class="ref_no"></td>
																	  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['risk_text'].'</textarea></td>
																      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,$each_line['assertion'], 'class="bs_assertion select2" style="width:100%;"').'</td>
																	  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown, $each_line['risk_level'], 'class="risk_level select2" style="width:100%;"').'</td>
																	  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['response'].'</textarea></td>';

																	// }
																echo '</tr>';
															}
														}
													}
												}
											}
										}

										// echo 
										// 	'<tr>' . 
										// 		'<td>' . $details_data['description'] . '</td>' .
										// 		'<td style="text-align:right; ">'. negative_bracket($details_data['value']) .'</td>';

										// if(!empty($last_fye_end))
										// {
										// 	echo '<td style="text-align:right;">' . negative_bracket($details_data['company_end_prev_ye_value']) . '</td>';

										// 	$index++;	// for item in sub category.
										// }

										// echo '</tr>';
									}

									/* ---------------------- show total for the category ---------------------- */
									// if(count($details['data']) > 0)
									// {
										$variance_value = calculate_variance($temp_total, $temp_total_ly);

										if($temp_total || $temp_total_ly)
										{
											echo 
												'<tr>' .
													'<td></td>' .
													'<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' .
														// '<input type="hidden" name="current_fye_end['. $key .']" value="'. $current_fye_end .'">' .
														'<input type="hidden" name="category_total['. $key .']" value="'. $temp_total .'">' .
														'<span>' . negative_bracket($temp_total) . '</span>' .
													'</td>';

											if(!empty($last_fye_end))
											{
												echo '<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' . 
														'<span>' . negative_bracket($temp_total_ly) . '</span>' . 
													'</td>';
												echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
												echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';
											}

											   

											echo '</tr>';
										}
									// }
									}

									array_push($total, $temp_total);
									array_push($total_ly, $temp_total_ly);
									/* ---------------------- END OF show total for the category ---------------------- */
								
							}
							else 
							{

								$temp_total 	+= convert_string_to_number($value['child_array']['value']);
								$temp_total_ly 	+= convert_string_to_number($value['child_array']['company_end_prev_ye_value']);

								echo '<tr>' . 
										'<td colspan="5">&nbsp;</td>' . 
									'</tr>';

								// echo 
								// 	'<tr>' . 
								// 		'<td>' . $value['child_array']['description'] . '</td>' .	// Description in Level 2 (without child)
								// 		'<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($value['child_array']['value']) . '</td>';
								$variance_value = calculate_variance($value['child_array']['value'], $value['child_array']['company_end_prev_ye_value']);
								$temp_id = $value['child_array']['id'];

								if($$value['child_array']['value'] || $value['child_array']['company_end_prev_ye_value'])
								{
									echo 
										'<tr class="rows-for-'.$temp_id.'">' . 
											'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
											'<td>' . $value['child_array']['description'] . '</td>' .	// Description in Level 2 (without child)
											'<td style="text-align: right;">' . negative_bracket($value['child_array']['value']) . '</td>';

									if(!empty($last_fye_end))
									{
										// echo '<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($value['child_array']['company_end_prev_ye_value']) . '</td>';

										echo '<td style="text-align: right;">' . negative_bracket($value['child_array']['company_end_prev_ye_value']) . '</td>';
										echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
										echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';   
									}

									
									echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"/>
														<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add line item" id="" onclick="addRow(this,'.$last_fye_end_flag.')"><i class="fa fa-plus-circle amber" style="font-size:16px;"></i></a>
													  </td>
													  <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"></td>
													  <td style="text-align: center;" class="ref_no">1</td>
													  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_text']:"").'</textarea></td>
												      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['assertion']:""), 'class="pl_assertion select2" style="width:100%;"').'</td>
													  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_level']:""), 'class="risk_level select2" style="width:100%;"').'</td>
													  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['response']:"").'</textarea></td>';   
									echo '</tr>';

									//loop line item other than first row
									if(isset($line_items[$temp_id])){
										if(count($line_items[$temp_id]) > 1)
										{
											foreach ($line_items[$temp_id] as $key => $each_line) 
											{
												if($key > 0)
												{


													echo  '<tr class="rows-for-'.$temp_id.'">' .
														      '<td></td>';

													echo '<td align="right"></td>';
														
														if(!empty($last_fye_end))
														{

															echo 
															'<td align="right"></td>';
															echo '<td align="right"></td>';
															echo '<td align="center"></td>';
														
												
														}

														
														echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.$each_line['id'].'"/></td>
														      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.$each_line['id'].'"></td>
														  <td style="text-align: center;" class="ref_no"></td>
														  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['risk_text'].'</textarea></td>
													      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,$each_line['assertion'], 'class="bs_assertion select2" style="width:100%;"').'</td>
														  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown, $each_line['risk_level'], 'class="risk_level select2" style="width:100%;"').'</td>
														  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['response'].'</textarea></td>';

														// }
													echo '</tr>';
												}
											}
										}
									}
								}

								array_push($total, $temp_total);
								array_push($total_ly, $temp_total_ly);
							}

							
						}

						// calculate overall total
						$total_operating_expenses_current = 0.00;
						$total_operating_expenses_ly = 0.00;
						
						foreach($total as $counter => $each_num)
						{
							$total_operating_expenses_current += $each_num;
						}

						foreach($total_ly as $counter => $each_num)
						{
							$total_operating_expenses_ly += $each_num;
						}

						$variance_value = calculate_variance($total_operating_expenses_current, $total_operating_expenses_ly);

						echo '<tr>' .
								'<td>Total operating expenses</td>' .
								'<td style="text-align:right; border-top:1px solid black;border-bottom:1px solid black;">' . 
									'<input type="hidden" name="overall_operating_expenses" value="'. $total_operating_expenses_current .'">' .
									'<span>' . negative_bracket($total_operating_expenses_current) . '</span>'.
								'</td>';

								if(!empty($last_fye_end))
								{
									echo 
									'<td style="text-align:right; border-top:1px solid black;border-bottom:1px solid black;">' . 
										'<input type="hidden" name="overall_operating_expenses_ly" value="' . $total_operating_expenses_ly . '">' .
										'<span id="SOE_lye_overall_total">' . negative_bracket($total_operating_expenses_ly) . '</span>'.
									'</td>';
									echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
									echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
								}
						     
						echo '</tr>';
						/* END OF DISPLAY OPERATING EXPENSES */

						/* CALCULATE AND DISPLAY PROFIT BEFORE TAX */
						$profit_before_tax = ($total_revenue_current - $total_cost_of_sale_current) + $total_other_income - $total_operating_expenses_current;
						$profit_before_tax_ly = ($total_revenue_current_ly - $total_cost_of_sale_current_ly) + $total_other_income_ly - $total_operating_expenses_ly;

						$variance_value = calculate_variance($profit_before_tax, $profit_before_tax_ly);

			
						echo 
						'<tr>' .
							'<td style="text-align:left;">Profit before tax</td>' . 
							'<td style="text-align:right; border-top:1px solid black;">' . 
								// '<input type="hidden" name="profit_of_the_year" value="'. $profit_of_the_year .'">' .
								negative_bracket($profit_before_tax) . 
							'</td>';
							if(!empty($last_fye_end))
							{
								echo '<td style="text-align:right; border-top:1px solid black;">' . 
										// '<input type="hidden" name="profit_of_the_year_ly" value="'. $profit_of_the_year_ly .'">' .
										'<span class="profit_of_the_year_ly">' . negative_bracket($profit_before_tax_ly) . '</span>' .  
									'</td>';
								echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
								echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';  
							}
						    
						echo '</tr>';
						

						/* END OF CALCULATE AND DISPLAY PROFIT BEFORE TAX */

						// TAXATION 
						$total_additional_company_ye 	= 0.00;
						$total_additional_company_lye 	= 0.00;

						foreach($tax_data[0] as $key => $value)
						{
							$variance_value = calculate_variance($value['parent_array'][0]['total_c'], $value['parent_array'][0]['total_c_lye']);
							$temp_id = $value['parent_array'][0]['id'];

							if($value['parent_array'][0]['total_c'] || $value['parent_array'][0]['total_c_lye'])
							{

								echo 
								'<tr class="rows-for-'.$temp_id.'">'.
									'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
									'<td>'. $value['parent_array'][0]['description'] . '</td>'; 
								
								if(!empty($last_fye_end))
								{
									echo 
									// '<td align="right" class="taxation_company_ye">' . 
									'<td align="right">' . 
										negative_bracket($value['parent_array'][0]['total_c']) . 
									'</td>' . 
									'<td align="right">' . 
										negative_bracket($value['parent_array'][0]['total_c_lye']) .
									'</td>';
									echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
									echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
								}
								else
								{
									echo 
									// '<td align="right" class="taxation_company_ye">' . 
									'<td align="right">' . 
										negative_bracket($value['parent_array'][0]['total_c']) . 
									'</td>';
								}

								
								 
								echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"/>
										<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add line item" id="" onclick="addRow(this,'.$last_fye_end_flag.')"><i class="fa fa-plus-circle amber" style="font-size:16px;"></i></a>
									  </td>
									  <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"></td>
									  <td style="text-align: center;" class="ref_no">1</td>
									  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_text']:"").'</textarea></td>
								      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['assertion']:""), 'class="pl_assertion select2" style="width:100%;"').'</td>
									  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_level']:""), 'class="risk_level select2" style="width:100%;"').'</td>
									  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['response']:"").'</textarea></td>';    
								echo '</tr>';

								//loop line item other than first row
								if(isset($line_items[$temp_id])){
									if(count($line_items[$temp_id]) > 1)
									{
										foreach ($line_items[$temp_id] as $key => $each_line) 
										{
											if($key > 0)
											{


												echo  '<tr class="rows-for-'.$temp_id.'">' .
													      '<td></td>';

												echo '<td align="right"></td>';
													
													if(!empty($last_fye_end))
													{

														echo 
														'<td align="right"></td>';
														echo '<td align="right"></td>';
														echo '<td align="center"></td>';
										
													}

													
													echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.$each_line['id'].'"/></td>
													      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.$each_line['id'].'"></td>
													  <td style="text-align: center;" class="ref_no"></td>
													  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['risk_text'].'</textarea></td>
												      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,$each_line['assertion'], 'class="bs_assertion select2" style="width:100%;"').'</td>
													  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown, $each_line['risk_level'], 'class="risk_level select2" style="width:100%;"').'</td>
													  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['response'].'</textarea></td>';

													// }
												echo '</tr>';
											}
										}
									}
								}
							}
						
							$total_additional_company_ye 	+= (double)$value['parent_array'][0]['total_c'];
							$total_additional_company_lye 	+= (double)$value['parent_array'][0]['total_c_lye'];
						}
		
					 // END OF TAXATION 

					// SHARE OF ASSOCIATES PROFIT OR LOSS
						$total_soa_pl_company_ye 	= 0.00;
						$total_soa_pl_company_lye 	= 0.00;

						if(isset($soa_pl_list[0])){
							foreach($soa_pl_list[0] as $key => $value)
							{
								$variance_value = calculate_variance($value['parent_array'][0]['total_c'], $value['parent_array'][0]['total_c_lye']);
								$temp_id = $value['parent_array'][0]['id'];

								if($value['parent_array'][0]['total_c'] || $value['parent_array'][0]['total_c_lye'])
								{
									echo 
									'<tr class="rows-for-'.$temp_id.'">'.
										'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
										'<td>'. $value['parent_array'][0]['description'] . '</td>';


									if(!empty($last_fye_end))
									{
										echo 
										'<td align="right">' . 
											negative_bracket($value['parent_array'][0]['total_c']) . 
										'</td>' . 
										'<td align="right">' . 
											negative_bracket($value['parent_array'][0]['total_c_lye']) .
										'</td>';
										echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
										echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
									}
									else
									{
										echo 
										'<td align="right">' . 
											negative_bracket($value['parent_array'][0]['total_c']) . 
										'</td>';
									}

									
									
									echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"/>
										<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add line item" id="" onclick="addRow(this,'.$last_fye_end_flag.')"><i class="fa fa-plus-circle amber" style="font-size:16px;"></i></a>
									  </td>
									  <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"></td>
									  <td style="text-align: center;" class="ref_no">1</td>
									  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_text']:"").'</textarea></td>
								      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['assertion']:""), 'class="pl_assertion select2" style="width:100%;"').'</td>
									  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_level']:""), 'class="risk_level select2" style="width:100%;"').'</td>
									  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['response']:"").'</textarea></td>';  
									echo '</tr>';

									//loop line item other than first row
									if(isset($line_items[$temp_id])){
										if(count($line_items[$temp_id]) > 1)
										{
											foreach ($line_items[$temp_id] as $key => $each_line) 
											{
												if($key > 0)
												{


													echo  '<tr class="rows-for-'.$temp_id.'">' .
														      '<td></td>';

													echo '<td align="right"></td>';
														
														if(!empty($last_fye_end))
														{

															echo 
															'<td align="right"></td>';
															echo '<td align="right"></td>';
															echo '<td align="center"></td>';
														
														}

														
														echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.$each_line['id'].'"/></td>
														      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.$each_line['id'].'"></td>
														  <td style="text-align: center;" class="ref_no"></td>
														  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['risk_text'].'</textarea></td>
													      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,$each_line['assertion'], 'class="bs_assertion select2" style="width:100%;"').'</td>
														  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown, $each_line['risk_level'], 'class="risk_level select2" style="width:100%;"').'</td>
														  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['response'].'</textarea></td>';

														// }
													echo '</tr>';
												}
											}
										}
									}

								}
								$total_soa_pl_company_ye 	+= (double)$value['parent_array'][0]['total_c'];
								$total_soa_pl_company_lye 	+= (double)$value['parent_array'][0]['total_c_lye'];
							}
						}
					 // END OF SHARE OF ASSOCIATES PROFIT OR LOSS

					 // CALCULATE PROFIT AFTER TAX
						$profit_after_tax = $profit_before_tax + $total_additional_company_ye + $total_soa_pl_company_ye;
						$profit_after_tax_ly = $profit_before_tax_ly + $total_additional_company_lye + $total_soa_pl_company_lye;

						$variance_value = calculate_variance($profit_after_tax, $profit_after_tax_ly);
						echo 
						'<tr>' .
							'<td style="text-align:left;">Profit after tax</td>' . 
							'<td style="text-align:right; border-top:1px solid black;border-bottom-style: double; border-bottom-width: 5px;">' . 
								// '<input type="hidden" name="profit_of_the_year" value="'. $profit_of_the_year .'">' .
								negative_bracket($profit_after_tax) . 
							'</td>';
							if(!empty($last_fye_end))
							{
								echo '<td style="text-align:right; border-top:1px solid black;border-bottom-style: double; border-bottom-width: 5px;">' . 
										// '<input type="hidden" name="profit_of_the_year_ly" value="'. $profit_of_the_year_ly .'">' .
										'<span class="profit_of_the_year_ly">' . negative_bracket($profit_after_tax_ly) . '</span>' .  
									'</td>';
								echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
								echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';   
							}
						   
						echo '</tr>';
						echo '<tr><td colspan="15">&nbsp;</td></tr>';
					// END OF CALCULATE PROFIT AFTER TAX

					// DIVIDEND 
						$total_dividend_company_ye 	= 0.00;
						$total_dividend_company_lye 	= 0.00;

	            		$check_div_existance = false;

	           // 		print_r($div_data);
	                    if(count($div_data)) {
    	            		foreach ($div_data[0] as $key2 => $child_array) 
    	            		{
    	            			if(isset($child_array['parent_array']))
    	            			{
    		            			if($child_array['parent_array'] != null && $child_array['child_array'] != null)
    		            			{
    		            				$check_div_existance = true;
    		            			}
    
    		            		}
    		            		elseif ( $child_array['child_array'] != null) {
    	            				$check_div_existance = true;
    	            			}
    
    
    			            }
	                    }

			            if($check_div_existance)
			            {

							foreach($div_data[0] as $key => $value)
							{
								$variance_value = calculate_variance($value['parent_array'][0]['total_c'], $value['parent_array'][0]['total_c_lye']);
								$temp_id = $value['parent_array'][0]['id'];

								if($value['parent_array'][0]['total_c'] || $value['parent_array'][0]['total_c_lye'])
								{

									echo 
									'<tr class="rows-for-'.$temp_id.'">'.
										'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
										'<td>'. $value['parent_array'][0]['description'] . '</td>'; 
									
									if(!empty($last_fye_end))
									{
										echo 
										// '<td align="right" class="taxation_company_ye">' . 
										'<td align="right">' . 
											negative_bracket($value['parent_array'][0]['total_c']) . 
										'</td>' . 
										'<td align="right">' . 
											negative_bracket($value['parent_array'][0]['total_c_lye']) .
										'</td>';
										echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
										echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
									}
									else
									{
										echo 
										// '<td align="right" class="taxation_company_ye">' . 
										'<td align="right">' . 
											negative_bracket($value['parent_array'][0]['total_c']) . 
										'</td>';
									}

									
									 
									echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"/>
											<a href="javascript: void(0);" data-toggle="tooltip" data-trigger="hover" style="font-weight:bold;" data-original-title="Add line item" id="" onclick="addRow(this,'.$last_fye_end_flag.')"><i class="fa fa-plus-circle amber" style="font-size:16px;"></i></a>
										  </td>
										  <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['id']:"").'"></td>
										  <td style="text-align: center;" class="ref_no">1</td>
										  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_text']:"").'</textarea></td>
									      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['assertion']:""), 'class="pl_assertion select2" style="width:100%;"').'</td>
										  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown,(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['risk_level']:""), 'class="risk_level select2" style="width:100%;"').'</td>
										  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.(isset($line_items[$temp_id][0])?$line_items[$temp_id][0]['response']:"").'</textarea></td>';    
									echo '</tr>';

									//loop line item other than first row
									if(isset($line_items[$temp_id])){
										if(count($line_items[$temp_id]) > 1)
										{
											foreach ($line_items[$temp_id] as $key => $each_line) 
											{
												if($key > 0)
												{


													echo  '<tr class="rows-for-'.$temp_id.'">' .
														      '<td></td>';

													echo '<td align="right"></td>';
														
														if(!empty($last_fye_end))
														{

															echo 
															'<td align="right"></td>';
															echo '<td align="right"></td>';
															echo '<td align="center"></td>';
											
														}

														
														echo '<td style="text-align: center;"><input type="hidden" name="line_item_id['.$temp_id.'][]" value="'.$each_line['id'].'"/></td>
														      <td style="text-align: center;"><input type="checkbox" class="cbx" name="" value="'.$each_line['id'].'"></td>
														  <td style="text-align: center;" class="ref_no"></td>
														  <td style="text-align: center;"><textarea class="form-control compulsory" name="risk_text['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['risk_text'].'</textarea></td>
													      <td style="text-align: center;">'.form_dropdown('pl_assertion['.$temp_id.'][]', $pl_assertion_dropdown,$each_line['assertion'], 'class="bs_assertion select2" style="width:100%;"').'</td>
														  <td style="text-align: center;">'.form_dropdown('risk_level['.$temp_id.'][]', $risk_lvl_dropdown, $each_line['risk_level'], 'class="risk_level select2" style="width:100%;"').'</td>
														  <td style="text-align: center;"><textarea class="form-control compulsory" name="response['.$temp_id.'][]"  rows="1" style="width:100%">'.$each_line['response'].'</textarea></td>';

														// }
													echo '</tr>';
												}
											}
										}
									}
								}
							
								$total_dividend_company_ye 	+= (double)$value['parent_array'][0]['total_c'];
								// echo $total_dividend_company_ye;
								$total_dividend_company_lye 	+= (double)$value['parent_array'][0]['total_c_lye'];
							}
						}
		
					 // END OF DIVIDEND 

					// RETAINED EARNINGS B/F 
						$total_bf_company_ye 	= 0.00;
						$total_bf_company_lye 	= 0.00;

						if(count($bf_data) > 0){
							foreach($bf_data[0] as $key => $value)
							{
								$variance_value = calculate_variance($value['parent_array'][0]['total_c'], $value['parent_array'][0]['total_c_lye']);
								$temp_id = $value['parent_array'][0]['id'];

								if($value['parent_array'][0]['total_c'] || $value['parent_array'][0]['total_c_lye'])
								{
									echo 
									'<tr class="rows-for-'.$temp_id.'">'.
										'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
										'<td>Retained earnings b/f</td>'; 
									
									if(!empty($last_fye_end))
									{
										echo 
										// '<td align="right" class="taxation_company_ye">' . 
										'<td align="right">';
										if(!$rev_rsrv_agree)
										{
											echo '<span data-toggle="tooltip" title="Value does not agree with last year value of Revenue reserve in statement of financial position" data-trigger="hover" data-original-title="Test" style="float:left;color:red;"><i class="fas fa-exclamation-circle"></i></span>';
										}

										echo 	negative_bracket($value['parent_array'][0]['total_c']) . 
										'</td>' . 
										'<td align="right">' . 
											negative_bracket($value['parent_array'][0]['total_c_lye']) .
										'</td>';
										echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
										echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
									}
									else
									{
										echo 
										// '<td align="right" class="taxation_company_ye">' . 
										'<td align="right">' . 
											negative_bracket($value['parent_array'][0]['total_c']) . 
										'</td>';
									}

					    
									echo '</tr>';
								}

										
								$total_bf_company_ye 	+= (double)$value['parent_array'][0]['total_c'];
								$total_bf_company_lye 	+= (double)$value['parent_array'][0]['total_c_lye'];
							}
						}
		
					 // END OF RETAINED EARNINGS B/F 


					// CALCULATE RETAINED EARNINGS C/F 
						$retained_earning_cf = $profit_after_tax + $total_dividend_company_ye + $total_bf_company_ye;
						$retained_earning_cf_ly = $profit_after_tax_ly + $total_dividend_company_lye + $total_bf_company_lye;

						$variance_value = calculate_variance($retained_earning_cf, $retained_earning_cf_ly);
						echo 
						'<tr>' .
							'<td style="text-align:left;">Retained earnings c/f</td>' . 
							'<td style="text-align:right; border-top:1px solid black;border-bottom-style: double; border-bottom-width: 5px;">' . 
								// '<input type="hidden" name="profit_of_the_year" value="'. $profit_of_the_year .'">' .
								negative_bracket($retained_earning_cf) . 
							'</td>';
							if(!empty($last_fye_end))
							{
								echo '<td style="text-align:right; border-top:1px solid black;border-bottom-style: double; border-bottom-width: 5px;">' . 
										// '<input type="hidden" name="profit_of_the_year_ly" value="'. $profit_of_the_year_ly .'">' .
										'<span class="profit_of_the_year_ly">' . negative_bracket($retained_earning_cf_ly) . '</span>' .  
									'</td>';
								echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
								echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';   
							}
						   
						echo '</tr>';
						
					// END OF CALCULATE RETAINED EARNINGS C/F 


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

	function convert_string_to_number($number)
	{
		if($number == "-")
      	{
          	return 0;
      	}
      	elseif($number == "")
      	{
          	return 0;
      	}
      	else
      	{
          	return str_replace('(', "", str_replace(')', "", str_replace(',', "", $number)));;
      	}
	}
?>


<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>';
	var show_data_content = '<?php echo $show_data_content ?>';
	var adjustment_caf_id = '<?php echo $adjustment_caf_id?>';
	var reserved_je_no = [];
	var arr_deleted_info = [];

	var save_profit_loss_url = "<?php echo site_url('caf/save_profit_loss'); ?>";
	var delete_pl_line_item_url =  "<?php echo site_url('caf/delete_pl_line_item'); ?>";
	var export_profitloss_pdf_url = "<?php echo (site_url('caf/export_profitloss_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";
	var get_je_no_url  = "<?php echo site_url('caf/get_je_no'); ?>";
	var save_adjustment_url = "<?php echo site_url('caf/save_adjustment'); ?>";
	


</script>
<script src="<?= base_url()?>application/modules/caf/js/profit_loss.js" charset="utf-8"></script>

<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>