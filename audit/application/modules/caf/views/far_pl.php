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
				
			?>
			<table id="tbl_state_detailed_profit_loss" class="table table-hover table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black">
				<input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">
				<thead>
					<tr>
						<th rowspan="0" style="width: <?=$width['account_desc'] ?>%;"></th>
						<th style="width: <?=$width['value'] ?>%; text-align: center;">
							<?php
								if($display_restated)
								{
									echo '</br>';
								} 
								echo $current_fye_end ."<br/>". "Unadjusted"; 
							?>	
						</th>
						<th style="width: <?=$width['value'] ?>%; text-align: center;" colspan=2>
							<?php
								if($display_restated)
								{
									echo '</br>';
								} 
								echo "<br/>Adjustments"
							?>	
						</th>
						<th style="width: <?=$width['value'] ?>%; text-align: center;">
							<?php
								if($display_restated)
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
									if($display_restated)
									{
										echo 'Restated</br>';
									}
									echo $last_fye_end."<br/>". "Final"; 
								echo '</th>';

								if($display_restated)
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
						<th style="width: <?=$width['value'] ?>%; text-align: center; border-top: 1px solid #000;"></br>$</th>
						<th style="width: <?=$width['value'] ?>%; text-align: center; border-top: 1px solid #000;">DR</br>$</th>
						<th style="width: <?=$width['value'] ?>%; text-align: center; border-top: 1px solid #000;">CR</br>$</th>
						<th style="width: <?=$width['value'] ?>%; text-align: center; border-top: 1px solid #000;"></br>$</th>

						<?php 
							if(!empty($last_fye_end))
							{
								echo '<th style="width:'.$width['value'].'%; text-align: center; border-top: 1px solid #000;"></br>$</th>';

								if($display_restated)
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
						// print_r($adjustment_data);
						$total = [];
						$total_revenue_current 				 = 0.00; // (+) ** M1003
						$total_revenue_current_adjusted		 = 0.00;
						$total_revenue_current_ly 			 = 0.00; // (+) ** M1003
						$total_cost_of_sale_current 		 = 0.00; // (-) ** M1004
						$total_cost_of_sale_current_adjusted = 0.00;
						$total_cost_of_sale_current_ly 		 = 0.00; // (-) ** M1004
						$total_other_income					 = 0.00; // 	   ** M1005
						$total_other_income_adjusted		 = 0.00; // 	   ** M1005
						$total_other_income_ly				 = 0.00; // 	   ** M1005

						$total_adjusted = [];
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
			            // print_r($state_detailed_pro_loss_data);

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
											'<td style="text-align:right; border-bottom:1px solid black;"> - </td>
											<td></td>
											<td></td>'.

											'<td style="text-align:right; border-bottom:1px solid black;">' .
												'<span>' . negative_bracket(0) . '</span>' .
											'</td>';

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
											'</td>
											<td></td>
											<td></td>'.
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
								$temp_total    		 = 0.00;
								$temp_total_adjusted = 0.00;
								$temp_total_ly 		 = $c_lye_value;
								
								$counter ++;
			            	}
			            	/* ------------------- END OF If NO SUB under main category ------------------- */

			            	/* If there are subs under main category */
			            	else
			            	{
			            		$temp_total 		 = 0.00;
			            		$temp_total_adjusted = 0.00;
			            		$temp_total_ly 		 = 0.00;

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

					            // echo ucfirst(strtolower($main_category[0]['parent_array'][0]['description']));


				            	if($check_existance || ucfirst(strtolower($main_category[0]['parent_array'][0]['description'])) == "Revenue")
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
				            					$temp_value 		 = $child_array['parent_array'][0]['total_c'] * (-1);
				            					$temp_value_adjusted = $child_array['parent_array'][0]['total_c_adjusted'] * (-1);
				            					$temp_value_ly 		 = $child_array['parent_array'][0]['total_c_lye'] * (-1);
				            				}
				            				else
				            				{
				            					$temp_value 		 = $child_array['parent_array'][0]['total_c'];
				            					$temp_value_adjusted = $child_array['parent_array'][0]['total_c_adjusted'];
				            					$temp_value_ly 		 = $child_array['parent_array'][0]['total_c_lye'];
				            				}

				            				/* ------ Add in Display "Less:" for Closing inventories ------ */
				            				if($child_array['parent_array'][0]['account_code'] == $closing_inventories_ac)
				            				{
				            					$child_array['parent_array'][0]['description'] = $add_less_display . $child_array['parent_array'][0]['description'];
				            				}
				            				/* ------ END OF Add in Display "Less:" for Closing inventories ------ */

				            				//for adjustment column data if has child under it
				            				$temp_id 		 = $child_array['parent_array'][0]['id'];
											$temp_adjustment_info = get_adjustment_of_child($child_array['parent_array'][0]['child_id'], $adjustment_data);
											$temp_adjust_value = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['adjusted_value']:"";
											$temp_je_no = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['type_name'].$temp_adjustment_info[0]['je_no']:"";

				            				$variance_value = calculate_variance($temp_value_adjusted, $temp_value_ly);
				            				// Display child with subcategory. 
				            				echo '<tr class="rows-for-'.$child_array['parent_array'][0]['id'].'">' . 
				            						'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $child_array['parent_array'][0]['id'].'">'.
													'<td>' . $child_array['parent_array'][0]['description'] . '</td>' .
													'<td style="text-align:right;">'. negative_bracket($temp_value) .'</td>';
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
											echo	'<td style="text-align:right;">'. negative_bracket($temp_value_adjusted) .'</td>';
													if(!empty($last_fye_end))
													{
														echo '<td style="text-align:right;">' . negative_bracket($temp_value_ly);
														echo '</td>';
														echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
														echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';
													}
    
											echo '</tr>';

											//loop all adjustment info 
											if(count($temp_adjustment_info) > 1)
											{
												foreach ($temp_adjustment_info as $info_key => $each_info) {
													if($info_key != 0)
													{
														$temp_adjust_value = $each_info['adjusted_value'];
														$temp_je_no = $each_info['type_name'].$each_info['je_no'];
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

											// total up 
											$temp_total    		 += $temp_value;
											$temp_total_adjusted += $temp_value_adjusted;
											$temp_total_ly 		 += $temp_value_ly;

											$counter++;
				            			}
				            		}
			            			elseif($child_array['child_array'] != null)
			            			{
			            				if($main_account_description == "Revenue" || $main_account_description == "Income")
			            				{
			            					$temp_value 		 = $child_array['child_array']['value'] * (-1);
			            					$temp_value_adjusted = $child_array['child_array']['adjusted_value'] * (-1);
			            					$temp_value_ly    	 = $child_array['child_array']['company_end_prev_ye_value'] * (-1);
			            				}
			            				else
			            				{
			            					$temp_value 		 = $child_array['child_array']['value'];
			            					$temp_value_adjusted = $child_array['child_array']['adjusted_value'];
			            					$temp_value_ly   	 = $child_array['child_array']['company_end_prev_ye_value'];
			            				}

			            				// Display child without subcategory. 

			            				//for adjustment column data if no child under it
			            				$temp_id 		 = $child_array['child_array']['id'];
										$temp_adjustment_info = isset($adjustment_data[$temp_id][0])?$adjustment_data[$temp_id][0]['adjust_value']:"";
										$temp_je_no = $temp_adjustment_info != ""?$adjustment_data[$temp_id][0]['type_name'].$adjustment_data[$temp_id][0]['je_no']:"";			       

										$variance_value = calculate_variance($temp_value_adjusted, $temp_value_ly);

			            				$temp_id = $child_array['child_array']['id'];
			            				echo '<tr class="rows-for-'.$temp_id.'">' . 
			            						'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
												'<td>' . $child_array['child_array']['description'] . '</td>' .
												'<td style="text-align:right;">'. negative_bracket($temp_value) .'</td>';
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
										echo	'<td style="text-align:right;">'. negative_bracket($temp_value_adjusted) .'</td>';

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

										// total up 
										$temp_total    		 += $temp_value;
										$temp_total_adjusted += $temp_value_adjusted;
										$temp_total_ly 		 += $temp_value_ly;

										$counter ++;
			            			}
			            		
				            	}

				            	// show total for the category
				            	if($check_existance || ucfirst(strtolower($main_category[0]['parent_array'][0]['description'])) == "Revenue")
				            	{
					            	$variance_value = calculate_variance($temp_total_adjusted, $temp_total_ly);
									echo '<tr>' .
											'<td></td>' .
											'<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' .
												'<span>' . negative_bracket($temp_total) . '</span>' .
											'</td>'.
											'<td></td>
											<td></td>'.
											'<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' .
												'<span>' . negative_bracket($temp_total_adjusted) . '</span>' .
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
				            	$total_revenue_current 	  		= $temp_total;
				            	$total_revenue_current_adjusted = $temp_total_adjusted;
								$total_revenue_current_ly 		= $temp_total_ly;
				            }
				            elseif($main_account_description == "Cost of Sales")
				            {
				            	$total_cost_of_sale_current    		 = $temp_total;
				            	$total_cost_of_sale_current_adjusted = $temp_total_adjusted;
								$total_cost_of_sale_current_ly       = $temp_total_ly;
				            }
				            elseif($main_account_description == "Income")
				            {
				            	$total_other_income    		 = $temp_total;
				            	$total_other_income_adjusted = $temp_total_adjusted;
								$total_other_income_ly 		 = $temp_total_ly;
				            }

				            /* CALCULATE AND DISPLAY GROSS PROFIT */
				            if($main_account_description == "Cost of Sales" && ($revenue_exist || $cost_of_sales_exist))
				            {
				            	$gross_profit 	 	   = $total_revenue_current - $total_cost_of_sale_current;
				            	$gross_profit_adjusted = $total_revenue_current_adjusted - $total_cost_of_sale_current_adjusted;
								$gross_profit_ly 	   = $total_revenue_current_ly - $total_cost_of_sale_current_ly;

								$variance_value = calculate_variance($gross_profit_adjusted, $gross_profit_ly);
				            	echo '<tr>'.
				            			'<td><em>Gross Profit</em></td>' . 
				            			'<td style="text-align:right; border-bottom:1px solid black;">'. 
												// '<input type="hidden" name="gross_profit" value="' . $gross_profit . '" >' .
												negative_bracket($gross_profit) .
											'</td>'.
											'<td></td>
											<td></td>'.
											'<td style="text-align:right; border-bottom:1px solid black;">'. 
												// '<input type="hidden" name="gross_profit" value="' . $gross_profit . '" >' .
												negative_bracket($gross_profit_adjusted) .
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
				            /* END OF CALCULATE AND DISPLAY GROSS PROFIT */
				        }

				         /* DISPLAY OPERATING EXPENSES */
				         echo '<tr>' . 
										'<td>&nbsp;</td>'.
										'<td>&nbsp;</td>'.
										'<td>&nbsp;</td>';
										if(!empty($last_fye_end))
										{
											echo '<td>&nbsp;</td>';
										}
						echo '</tr>';
				        echo '<tr>' .
								'<td style="text-align:left;"><em>Less: Operating expenses</em></td>' . 
								'<td class="total_operating_expenses"></td>'.
								'<td></td>';
								if(!empty($last_fye_end))
								{
									echo '<td></td>';
								}
						echo '</tr>';

				        foreach($schedule_operating_expense_data[0]['child_array'] as $key => $value)
						{

							$temp_total 		 = 0.00;
							$temp_total_adjusted = 0.00;
							$temp_total_ly 		 = 0.00;
							// print_r($value);
							if(isset($value['parent_array']))
							{

								if(count($value['parent_array']) > 0)
								{
									echo 
										'<tr>' . 
											'<td><i>' . $value['parent_array'][0]['description'] . '</i></td>' .	// Description in Level 2
											'<td></td>' .
											'<td></td>' .
											'<td></td>' .
											'<td></td>' .
										'</tr>';

									foreach ($value['child_array'] as $key2 => $details_data) 
									{

										if(isset($details_data['parent_array'])){
											if(count($details_data['parent_array']) > 0)
											{

												if(!isset($details_data['child_array'][0]['parent_array']))
												{
													if(isset($details_data['parent_array'][0]['total_c']))
													{
														$temp_total 		 += convert_string_to_number($details_data['parent_array'][0]['total_c']);
														$temp_total_adjusted += convert_string_to_number($details_data['parent_array'][0]['total_c_adjusted']);
														$temp_total_ly 		 += convert_string_to_number($details_data['parent_array'][0]['total_c_lye']);
													}

													$temp_total_c 		   = isset($details_data['parent_array'][0]['total_c'])?$details_data['parent_array'][0]['total_c']:0 ;
													$temp_total_c_adjusted = isset($details_data['parent_array'][0]['total_c_adjusted'])?$details_data['parent_array'][0]['total_c_adjusted']:0 ;
													$temp_total_c_lye 	   = isset($details_data['parent_array'][0]['total_c_lye'])?$details_data['parent_array'][0]['total_c_lye']:0 ;

													// echo 
													// 	'<tr>' . 
													// 		'<td>' . $details_data['parent_array'][0]['description'] . '</td>' .	// Description in level 3 (have sub)
													// 		'<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['parent_array'][0]['total_c']) . '</td>';
													
													//for adjustment column data if has child under it
						            				$temp_id 		 = $details_data['parent_array'][0]['id'];
						            				if(isset($details_data['parent_array'][0]['child_id']))
						            				{
						            					$temp_adjustment_info = get_adjustment_of_child($details_data['parent_array'][0]['child_id'], $adjustment_data);
						            				}
						            				else
						            				{
						            					$temp_adjustment_info = [];
						            				}
													
													$temp_adjust_value = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['adjusted_value']:"";
													$temp_je_no = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['type_name'].$temp_adjustment_info[0]['je_no']:"";

													$variance_value = calculate_variance($temp_total_c_adjusted, $temp_total_c_lye);
													// $temp_id = $details_data['parent_array'][0]['id'];
													echo 
														'<tr class="rows-for-'.$temp_id.'">' .
															'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'. 
															'<td>' . $details_data['parent_array'][0]['description'] . '</td>' .	// Description in level 3 (have sub)
															'<td style="text-align: right;">' . negative_bracket($temp_total_c) . '</td>';
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
													echo 	'<td style="text-align: right;">' . negative_bracket($temp_total_c_adjusted) . '</td>';

													if(!empty($last_fye_end))
													{
														// echo '<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['parent_array'][0]['total_c_lye']) . '</td>';

														echo '<td style="text-align: right;">' . negative_bracket($temp_total_c_lye) . '</td>';
														echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
														echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
													}   
													echo '</tr>';

													//loop all adjustment info 
													if(count($temp_adjustment_info) > 1)
													{
														foreach ($temp_adjustment_info as $info_key => $each_info) {
															if($info_key != 0)
															{
																$temp_adjust_value = $each_info['adjusted_value'];
																$temp_je_no = $each_info['type_name'].$each_info['je_no'];
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
												else
												{
													echo 
														'<tr>' .
															'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id">'. 
															'<td><b>' . $details_data['parent_array'][0]['description'] . ':</b></td>' .	// Description in level 3 (have sub)
															'<td colspan="8"></td>
														</tr>';
												}

												foreach ($details_data['child_array'] as $key3 => $details_data_child) 
												{
													if(isset($details_data_child['parent_array']) && count($details_data_child['parent_array']) > 0)
													{
														if(isset($details_data_child['parent_array'][0]['total_c']))
														{
															$temp_total 		 += convert_string_to_number($details_data_child['parent_array'][0]['total_c']);
															$temp_total_adjusted += convert_string_to_number($details_data_child['parent_array'][0]['total_c_adjusted']);
															$temp_total_ly 		 += convert_string_to_number($details_data_child['parent_array'][0]['total_c_lye']);
														}

														$temp_total_c 		   = isset($details_data_child['parent_array'][0]['total_c'])?$details_data_child['parent_array'][0]['total_c']:0 ;
														$temp_total_c_adjusted = isset($details_data_child['parent_array'][0]['total_c_adjusted'])?$details_data_child['parent_array'][0]['total_c_adjusted']:0 ;
														$temp_total_c_lye 	   = isset($details_data_child['parent_array'][0]['total_c_lye'])?$details_data_child['parent_array'][0]['total_c_lye']:0 ;

														// echo 
														// 	'<tr>' . 
														// 		'<td>' . $details_data['parent_array'][0]['description'] . '</td>' .	// Description in level 3 (have sub)
														// 		'<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['parent_array'][0]['total_c']) . '</td>';
														
														//for adjustment column data if has child under it
							            				$temp_id 		 = $details_data_child['parent_array'][0]['id'];
							            				if(isset($details_data_child['parent_array'][0]['child_id']))
							            				{
							            					$temp_adjustment_info = get_adjustment_of_child($details_data_child['parent_array'][0]['child_id'], $adjustment_data);
							            				}
							            				else
							            				{
							            					$temp_adjustment_info = [];
							            				}
														
														$temp_adjust_value = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['adjusted_value']:"";
														$temp_je_no = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['type_name'].$temp_adjustment_info[0]['je_no']:"";

														$variance_value = calculate_variance($temp_total_c_adjusted, $temp_total_c_lye);
														// $temp_id = $details_data['parent_array'][0]['id'];
														echo 
															'<tr class="rows-for-'.$temp_id.'">' .
																'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'. 
																'<td>- ' . $details_data_child['parent_array'][0]['description'] . '</td>' .	// Description in level 3 (have sub)
																'<td style="text-align: right;">' . negative_bracket($temp_total_c) . '</td>';
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
														echo 	'<td style="text-align: right;">' . negative_bracket($temp_total_c_adjusted) . '</td>';

														if(!empty($last_fye_end))
														{
															// echo '<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['parent_array'][0]['total_c_lye']) . '</td>';

															echo '<td style="text-align: right;">' . negative_bracket($temp_total_c_lye) . '</td>';
															echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
															echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
														}   
														echo '</tr>';

														//loop all adjustment info 
														if(count($temp_adjustment_info) > 1)
														{
															foreach ($temp_adjustment_info as $info_key => $each_info) {
																if($info_key != 0)
																{
																	$temp_adjust_value = $each_info['adjusted_value'];
																	$temp_je_no = $each_info['type_name'].$each_info['je_no'];
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


											}
										}
										else
										{
											$temp_total 		 += convert_string_to_number($details_data['child_array']['value']);
											$temp_total_adjusted += convert_string_to_number($details_data['child_array']['adjusted_value']);
											$temp_total_ly 	  	 += convert_string_to_number($details_data['child_array']['company_end_prev_ye_value']);

											// echo 
											// 	'<tr>' . 
											// 		'<td>' . $details_data['child_array']['description'] . '</td>' .	// Description in level 3 (no sub)
											// 		'<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['child_array']['value']) . '</td>';
											$variance_value = calculate_variance($details_data['child_array']['adjusted_value'], $details_data['child_array']['company_end_prev_ye_value']);
											// $temp_id = $details_data['child_array']['id'];
											
											//for adjustment column data if no child under it
				            				$temp_id 		 = $details_data['child_array']['id'];
											$temp_adjustment_info = isset($adjustment_data[$temp_id][0])?$adjustment_data[$temp_id][0]['adjust_value']:"";
											$temp_je_no = $temp_adjustment_info != ""?$adjustment_data[$temp_id][0]['type_name'].$adjustment_data[$temp_id][0]['je_no']:"";	

											echo 
												'<tr class="rows-for-'.$temp_id.'">' . 
													'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
													'<td>' . $details_data['child_array']['description'] . '</td>' .	// Description in level 3 (no sub)
													'<td style="text-align: right;">' . negative_bracket($details_data['child_array']['value']) . '</td>';
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
											echo '<td style="text-align: right;">' . negative_bracket($details_data['child_array']['adjusted_value']) . '</td>';

											if(!empty($last_fye_end))
											{
												// echo '<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($details_data['child_array']['company_end_prev_ye_value']) . '</td>';

												echo '<td style="text-align: right;">' . negative_bracket($details_data['child_array']['company_end_prev_ye_value']) . '</td>';
												echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
												echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
											}
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
										$variance_value = calculate_variance($temp_total_adjusted, $temp_total_ly);
										echo 
											'<tr>' .
												'<td></td>' .
												'<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' .
													// '<input type="hidden" name="current_fye_end['. $key .']" value="'. $current_fye_end .'">' .
													'<input type="hidden" name="category_total['. $key .']" value="'. $temp_total .'">' .
													'<span>' . negative_bracket($temp_total) . '</span>' .
												'</td>'.
												'<td></td>
												<td></td>'.
												'<td style="text-align:right; border-top:1px solid black; border-bottom:1px solid black;">' .
													// '<input type="hidden" name="current_fye_end['. $key .']" value="'. $current_fye_end .'">' .
													'<span>' . negative_bracket($temp_total_adjusted) . '</span>' .
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
									// }
									

									array_push($total, $temp_total);
									array_push($total_adjusted, $temp_total_adjusted);
									array_push($total_ly, $temp_total_ly);
									/* ---------------------- END OF show total for the category ---------------------- */
								}
							}
							else 
							{
								$temp_total 		 += convert_string_to_number($value['child_array']['value']);
								$temp_total_adjusted += convert_string_to_number($value['child_array']['adjusted_value']);
								$temp_total_ly 		 += convert_string_to_number($value['child_array']['company_end_prev_ye_value']);

								// echo '<tr>' . 
								// 		'<td colspan="5">&nbsp;</td>' . 
								// 	'</tr>';

								// echo 
								// 	'<tr>' . 
								// 		'<td>' . $value['child_array']['description'] . '</td>' .	// Description in Level 2 (without child)
								// 		'<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($value['child_array']['value']) . '</td>';
								$variance_value = calculate_variance($value['child_array']['adjusted_value'], $value['child_array']['company_end_prev_ye_value']);
								// $temp_id = $value['child_array']['id'];

								//for adjustment column data if no child under it
	            				$temp_id 		 = $value['child_array']['id'];
								$temp_adjustment_info = isset($adjustment_data[$temp_id][0])?$adjustment_data[$temp_id][0]['adjust_value']:"";
								$temp_je_no = $temp_adjustment_info != ""?$adjustment_data[$temp_id][0]['type_name'].$adjustment_data[$temp_id][0]['je_no']:"";

								echo 
									'<tr class="rows-for-'.$temp_id.'">' . 
										'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
										'<td>' . $value['child_array']['description'] . '</td>' .	// Description in Level 2 (without child)
										'<td style="text-align: right;">' . negative_bracket($value['child_array']['value']) . '</td>';
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
								echo	'<td style="text-align: right;">' . negative_bracket($value['child_array']['adjusted_value']) . '</td>';

								if(!empty($last_fye_end))
								{
									// echo '<td style="border-bottom:1px solid black; text-align: right;">' . negative_bracket($value['child_array']['company_end_prev_ye_value']) . '</td>';

									echo '<td style="text-align: right;">' . negative_bracket($value['child_array']['company_end_prev_ye_value']) . '</td>';
									echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
									echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>';   
								}

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

								array_push($total, $temp_total);
								array_push($total_adjusted, $temp_total_adjusted);
								array_push($total_ly, $temp_total_ly);
							}

							
						}

						// calculate overall total
						$total_operating_expenses_current 	 = 0.00;
						$total_operating_expenses_adjustment = 0.00;
						$total_operating_expenses_ly 		 = 0.00;
						
						foreach($total as $counter => $each_num)
						{
							$total_operating_expenses_current += $each_num;
						}

						foreach($total_adjusted as $counter => $each_num)
						{
							$total_operating_expenses_adjustment += $each_num;
						}

						foreach($total_ly as $counter => $each_num)
						{
							$total_operating_expenses_ly += $each_num;
						}

						$variance_value = calculate_variance($total_operating_expenses_adjustment, $total_operating_expenses_ly);
						echo '<tr>' .
								'<td>Total operating expenses</td>' .
								'<td style="text-align:right; border-top:1px solid black;border-bottom:1px solid black;">' . 
									'<input type="hidden" name="overall_operating_expenses" value="'. $total_operating_expenses_current .'">' .
									'<span>' . negative_bracket($total_operating_expenses_current) . '</span>'.
								'</td>
								<td></td>
								<td></td>'.
								'<td style="text-align:right; border-top:1px solid black;border-bottom:1px solid black;">' . 
									'<span>' . negative_bracket($total_operating_expenses_adjustment) . '</span>'.
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
						$profit_before_tax 			= ($total_revenue_current - $total_cost_of_sale_current) + $total_other_income - $total_operating_expenses_current;
						$profit_before_tax_adjusted = ($total_revenue_current_adjusted - $total_cost_of_sale_current_adjusted) + $total_other_income_adjusted - $total_operating_expenses_adjustment;
						$profit_before_tax_ly 		= ($total_revenue_current_ly - $total_cost_of_sale_current_ly) + $total_other_income_ly - $total_operating_expenses_ly;

						$variance_value = calculate_variance($profit_before_tax_adjusted, $profit_before_tax_ly);
						echo 
						'<tr>' .
							'<td style="text-align:left;">Profit before tax</td>' . 
							'<td style="text-align:right; border-top:1px solid black;">' . 
								// '<input type="hidden" name="profit_of_the_year" value="'. $profit_of_the_year .'">' .
								negative_bracket($profit_before_tax) . 
							'</td>'.
							'<td></td>
							<td></td>'.
							'<td style="text-align:right; border-top:1px solid black;">' . 
								// '<input type="hidden" name="profit_of_the_year" value="'. $profit_of_the_year .'">' .
								negative_bracket($profit_before_tax_adjusted) . 
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
						$total_additional_company_ye 		  = 0.00;
						$total_additional_company_ye_adjusted = 0.00;
						$total_additional_company_lye 		  = 0.00;

						foreach($tax_data[0] as $key => $value)
						{
							// print_r($value);
							$variance_value = calculate_variance($value['parent_array'][0]['total_c_adjusted'], $value['parent_array'][0]['total_c_lye']);
							// $temp_id = $value['parent_array'][0]['id'];
							//for adjustment column data if has child under it
            				$temp_id 		 = $value['parent_array'][0]['id'];
							$temp_adjustment_info = get_adjustment_of_child($value['parent_array'][0]['child_id'], $adjustment_data);
							$temp_adjust_value = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['adjusted_value']:"";
							$temp_je_no = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['type_name'].$temp_adjustment_info[0]['je_no']:"";

							echo 
							'<tr class="rows-for-'.$temp_id.'">'.
								'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
								'<td>'. $value['parent_array'][0]['description'] . '</td>'; 

							echo 
								// '<td align="right" class="taxation_company_ye">' . 
								'<td align="right">' . 
									negative_bracket($value['parent_array'][0]['total_c']) . 
								'</td>';
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
							echo
								'<td align="right">' . 
									negative_bracket($value['parent_array'][0]['total_c_adjusted']) . 
								'</td>';
							
							if(!empty($last_fye_end))
							{
								echo 
								'<td align="right">' . 
									negative_bracket($value['parent_array'][0]['total_c_lye']) . 
								'</td>';
								echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
								echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
							}
							// else
							// {
							// 	echo 
							// 	// '<td align="right" class="taxation_company_ye">' . 
							// 	'<td align="right">' . 
							// 		negative_bracket($value['parent_array'][0]['total_c']) . 
							// 	'</td>'.
							// 	'<td></td>
							// 	<td></td>'.
							// 	'<td align="right">' . 
							// 		negative_bracket($value['parent_array'][0]['total_c_adjusted']) . 
							// 	'</td>';
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

						
							$total_additional_company_ye 		  += (double)$value['parent_array'][0]['total_c'];
							$total_additional_company_ye_adjusted += (double)$value['parent_array'][0]['total_c_adjusted'];
							$total_additional_company_lye 		  += (double)$value['parent_array'][0]['total_c_lye'];
						}
		
					 // END OF TAXATION 

					// SHARE OF ASSOCIATES PROFIT OR LOSS
						$total_soa_pl_company_ye 		  = 0.00;
						$total_soa_pl_company_ye_adjusted = 0.00;
						$total_soa_pl_company_lye 		  = 0.00;

						if(isset($soa_pl_list[0])){
							foreach($soa_pl_list[0] as $key => $value)
							{
								$variance_value = calculate_variance($value['parent_array'][0]['total_c_adjusted'], $value['parent_array'][0]['total_c_lye']);
								// $temp_id = $value['parent_array'][0]['id'];
								//for adjustment column data if has child under it
		        				$temp_id 		 = $value['parent_array'][0]['id'];
								$temp_adjustment_info = get_adjustment_of_child($value['parent_array'][0]['child_id'], $adjustment_data);
								$temp_adjust_value = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['adjusted_value']:"";
								$temp_je_no = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['type_name'].$temp_adjustment_info[0]['je_no']:"";

								echo 
								'<tr class="rows-for-'.$temp_id.'">'.
									'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
									'<td>'. $value['parent_array'][0]['description'] . '</td>';
								echo 
									'<td align="right">' . 
										negative_bracket($value['parent_array'][0]['total_c']) . 
									'</td>';

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

								echo '<td align="right">'.
										negative_bracket($value['parent_array'][0]['total_c_adjusted']) . 
									'</td>' ;


								if(!empty($last_fye_end))
								{
									echo
									'<td align="right">' . 
										negative_bracket($value['parent_array'][0]['total_c_lye']) .
									'</td>';
									echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
									echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
								}
								// else
								// {
								// 	echo 
								// 	'<td align="right">' . 
								// 		negative_bracket($value['parent_array'][0]['total_c']) . 
								// 	'</td>'.
								// 	'<td></td>
								// 	<td></td>'.
								// 	'<td align="right">' . 
								// 		negative_bracket($value['parent_array'][0]['total_c_adjusted']) . 
								// 	'</td>';
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


								$total_soa_pl_company_ye 		  += (double)$value['parent_array'][0]['total_c'];
								$total_soa_pl_company_ye_adjusted += (double)$value['parent_array'][0]['total_c_adjusted'];
								$total_soa_pl_company_lye 		  += (double)$value['parent_array'][0]['total_c_lye'];
							}
						}
					 // END OF SHARE OF ASSOCIATES PROFIT OR LOSS

					// CALCULATE PROFIT AFTER TAX
						$profit_after_tax 		   = $profit_before_tax + $total_additional_company_ye + $total_soa_pl_company_ye;
						$profit_after_tax_adjusted = $profit_before_tax_adjusted + $total_additional_company_ye_adjusted + $total_soa_pl_company_ye_adjusted;
						$profit_after_tax_ly 	   = $profit_before_tax_ly + $total_additional_company_lye + $total_soa_pl_company_lye;

						$variance_value = calculate_variance($profit_after_tax_adjusted, $profit_after_tax_ly);
						echo 
						'<tr>' .
							'<td style="text-align:left;">Profit after tax</td>' . 
							'<td style="text-align:right; border-top:1px solid black;border-bottom-style: double; border-bottom-width: 5px;">' . 
								// '<input type="hidden" name="profit_of_the_year" value="'. $profit_of_the_year .'">' .
								negative_bracket($profit_after_tax) . 
							'</td>'.
							'<td></td>
							<td></td>'.
							'<td style="text-align:right; border-top:1px solid black;border-bottom-style: double; border-bottom-width: 5px;">' . 
								// '<input type="hidden" name="profit_of_the_year" value="'. $profit_of_the_year .'">' .
								negative_bracket($profit_after_tax_adjusted) . 
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

					// TAXATION 
						$total_dividend_company_ye 		  = 0.00;
						$total_dividend_company_ye_adjusted = 0.00;
						$total_dividend_company_lye 		  = 0.00;

                        if(count($div_data)) {
    						foreach($div_data[0] as $key => $value)
    						{
    							// print_r($value);
    							$variance_value = calculate_variance($value['parent_array'][0]['total_c_adjusted'], $value['parent_array'][0]['total_c_lye']);
    							// $temp_id = $value['parent_array'][0]['id'];
    							//for adjustment column data if has child under it
                				$temp_id 		 = $value['parent_array'][0]['id'];
    							$temp_adjustment_info = get_adjustment_of_child($value['parent_array'][0]['child_id'], $adjustment_data);
    							$temp_adjust_value = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['adjusted_value']:"";
    							$temp_je_no = count($temp_adjustment_info)>0?$temp_adjustment_info[0]['type_name'].$temp_adjustment_info[0]['je_no']:"";
    
    							echo 
    							'<tr class="rows-for-'.$temp_id.'">'.
    								'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
    								'<td>'. $value['parent_array'][0]['description'] . '</td>'; 
    
    							echo 
    								// '<td align="right" class="taxation_company_ye">' . 
    								'<td align="right">' . 
    									negative_bracket($value['parent_array'][0]['total_c']) . 
    								'</td>';
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
    							echo
    								'<td align="right">' . 
    									negative_bracket($value['parent_array'][0]['total_c_adjusted']) . 
    								'</td>';
    							
    							if(!empty($last_fye_end))
    							{
    								echo 
    								'<td align="right">' . 
    									negative_bracket($value['parent_array'][0]['total_c_lye']) . 
    								'</td>';
    								echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
    								echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
    							}
    			
    							echo '</tr>';
    
    							//loop all adjustment info 
    							if(count($temp_adjustment_info) > 1)
    							{
    								foreach ($temp_adjustment_info as $info_key => $each_info) {
    									if($info_key != 0)
    									{
    										$temp_adjust_value = $each_info['adjusted_value'];
    										$temp_je_no = $each_info['type_name'].$each_info['je_no'];
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
    
    						
    							$total_dividend_company_ye 		  += (double)$value['parent_array'][0]['total_c'];
    							$total_dividend_company_ye_adjusted += (double)$value['parent_array'][0]['total_c_adjusted'];
    							$total_dividend_company_lye 		  += (double)$value['parent_array'][0]['total_c_lye'];
    						}
                        }
		
					 // END OF TAXATION 

					// RETAINED EARNINGS B/F 
						$total_bf_company_ye 		  = 0.00;
						$total_bf_company_ye_adjusted = 0.00;
						$total_bf_company_lye 		  = 0.00;

						if(count($bf_data) > 0)
						{

							foreach($bf_data[0] as $key => $value)
							{
								// print_r($value);
								$variance_value = calculate_variance($value['parent_array'][0]['total_c_adjusted'], $value['parent_array'][0]['total_c_lye']);
								// $temp_id = $value['parent_array'][0]['id'];
								//for adjustment column data if has child under it
	            				$temp_id 		 = $value['parent_array'][0]['id'];
					

								echo 
								'<tr class="rows-for-'.$temp_id.'">'.
									'<input type="hidden" name="PL_audit_categorized_account_id[]" class="PL_audit_categorized_account_id" value="'. $temp_id.'">'.
									'<td>Retained earnings b/f</td>'; 

								echo 
									// '<td align="right" class="taxation_company_ye">' . 
									'<td align="right">' . 
										negative_bracket($value['parent_array'][0]['total_c']) . 
									'</td>';
									
								echo 
									'<td align="right"></td>';
								echo 
									'<td align="right"></td>';
									
								echo
									'<td align="right">' . 
										negative_bracket($value['parent_array'][0]['total_c_adjusted']) . 
									'</td>';
								
								if(!empty($last_fye_end))
								{
									echo 
									'<td align="right">' . 
										negative_bracket($value['parent_array'][0]['total_c_lye']) . 
									'</td>';
									echo '<td align="right">'.negative_bracket($variance_value['dollar']).'</td>';
									echo '<td align="center">'.number_format($variance_value['percentage'], 2).'%</td>'; 
								}
				
								echo '</tr>';

							
								$total_bf_company_ye 		  += (double)$value['parent_array'][0]['total_c'];
								$total_bf_company_ye_adjusted += (double)$value['parent_array'][0]['total_c_adjusted'];
								$total_bf_company_lye 		  += (double)$value['parent_array'][0]['total_c_lye'];
							}
						}
		
					 // END OF RETAINED EARNINGS B/F 

					// CALCULATE RETAINED EARNINGS C/F 
						$retained_earning_cf 		   = $profit_after_tax + $total_dividend_company_ye + $total_bf_company_ye;
						$retained_earning_cf_adjusted  = $profit_after_tax_adjusted + $total_dividend_company_ye_adjusted + $total_bf_company_ye_adjusted;
						$retained_earning_cf_ly 	   = $profit_after_tax_ly + $total_dividend_company_lye + $total_bf_company_lye;

						$variance_value = calculate_variance($retained_earning_cf, $retained_earning_cf_ly);
						echo 
						'<tr>' .
							'<td style="text-align:left;">Retained earnings c/f</td>' . 
							'<td style="text-align:right; border-top:1px solid black;border-bottom-style: double; border-bottom-width: 5px;">' . 
								// '<input type="hidden" name="profit_of_the_year" value="'. $profit_of_the_year .'">' .
								negative_bracket($retained_earning_cf) . 
							'</td>'.
							'<td></td>
							<td></td>'.
							'<td style="text-align:right; border-top:1px solid black;border-bottom-style: double; border-bottom-width: 5px;">' . 
								// '<input type="hidden" name="profit_of_the_year" value="'. $profit_of_the_year .'">' .
								negative_bracket($retained_earning_cf_adjusted) . 
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

	var adjustment_caf_id = '<?php echo $adjustment_caf_id?>';
	var reserved_je_no = [];
	var arr_deleted_info = [];

	var save_adjustment_url = "<?php echo site_url('caf/save_adjustment'); ?>";
	var get_je_no_url  = "<?php echo site_url('caf/get_je_no'); ?>";

	var save_profit_loss_url = "<?php echo site_url('caf/save_profit_loss'); ?>";
	var delete_pl_line_item_url =  "<?php echo site_url('caf/delete_pl_line_item'); ?>";
	var export_farpl_pdf_url = "<?php echo (site_url('caf/export_farpl_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";
	var show_data_content = '<?php echo $show_data_content ?>';




</script>
<script src="<?= base_url()?>application/modules/caf/js/far_pl.js" charset="utf-8"></script>
<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>