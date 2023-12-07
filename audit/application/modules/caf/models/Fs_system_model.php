<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//include 'application/js/random_alphanumeric_generator.php';

class Fs_system_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));

        $this->load->model('caf_model');
        $this->load->model('fs_model');
        $this->load->model('fs/fs_statements_model');
        $this->load->model('fs/Fs_account_category_model');
    }

    // public function get_fs_statement_json()
    // {
    //     $url         = 'assets/json/fs_statements_insert_account.json'; // path to your JSON file
    //     $data        = file_get_contents($url); // put the contents of the file into a variable
    //     $data_decode = json_decode($data); // decode the JSON feed

    //     return $data_decode[0];
    // }

    public function get_default_account_list()
    {
        $this->db->select('*');
        $this->db->from('fs_default_acc_category');
        $this->db->where_not_in('parent', array(''));
        $this->db->order_by('order ASC');
        
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        else
        {
            return '';
        }
    }

    // public function get_fs_ntfs_json()
    // {
    //     $url         = 'assets/json/fs_ntfs.json'; // path to your JSON file
    //     $data        = file_get_contents($url); // put the contents of the file into a variable
    //     $data_decode = json_decode($data); // decode the JSON feed

    //     return json_decode(json_encode($data_decode[0]), true);
    // }

    // public function get_sub_categories($fs_company_info_id, $parent_account_code)
    // {
    //     $parent_id = $this->db->query("SELECT * FROM audit_categorized_account WHERE account_code = '" . $parent_account_code . "' AND fs_company_info_id =" . $fs_company_info_id . " ORDER BY order_by");
    //     $parent_id = $parent_id->result_array();
    //     // print_r($parent_id);
        

    //     if(!empty($parent_id))
    //     {
    //         $parent_id = $parent_id[0]['id'];
    //         $q = $this->db->query("SELECT * FROM audit_categorized_account WHERE parent = '" . $parent_id . "' AND fs_company_info_id =" . $fs_company_info_id . " ORDER BY order_by");

    //         if(count($q->result_array()) > 0)
    //         {
    //             $sub_ids = [];

    //             foreach ($q->result_array() as $key => $value) 
    //             {
    //                 array_push($sub_ids, $value['id']);
    //             }

    //             return $sub_ids;
    //         }
    //     }
    //     else
    //     {
    //         return [];
    //     }
    // }

    public function get_account_with_exclude_sub_round_off($fs_company_info_id, $account_code_list, $exclude_account_code_list)
    {   
        $data = [];

        // get parent account list
        foreach ($account_code_list as $key => $value) 
        {
            $parent_fca_id = $this->fs_model->get_fca_id($fs_company_info_id, array($value));
            $account_list = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $parent_fca_id); 

            if(count($account_list) > 0)
            {
                foreach ($account_list[0]['child_array'] as $key2 => $value2) 
                {
                    if(isset($value2['parent_array'][0]['account_code']))
                    {
                        if(!in_array($value2['parent_array'][0]['account_code'], $exclude_account_code_list))
                        {
                            array_push($data, $value2);
                        }
                    }
                }
            }
        }

        return $data;
    }

    // public function get_fca_id($fs_company_info_id, $account_code)   // account code as known as reference id
    // {
    //     $fca_ids = [];

    //     foreach ($account_code as $ac_key => $ac_value) 
    //     {
    //         $q = $this->db->query("SELECT * FROM audit_categorized_account WHERE account_code = '" . $ac_value . "' AND fs_company_info_id=" . $fs_company_info_id);
    //         $q = $q->result_array();

    //         foreach ($q as $key => $value) 
    //         {
    //             array_push($fca_ids, $value['id']);
    //         }
    //     }
        
    //     return $fca_ids;
    // }

    /* -------------------------------------------- functions for notes -------------------------------------------- */

    public function get_fs_comparative_figures($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_comparative_figures WHERE fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_default_template_comparative_figures()
    {
        $q = $this->db->query("SELECT * FROM fs_default_template_comparative_figures");
        $q = $q->result_array();

        return $q;
    }

    public function get_add_note_list($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_note_templates_master m WHERE m.fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        // create note list if if is empty.
        if(count($q) == 0)
        {
            $temp_array = [];

            $fs_categorized_account = $this->get_note_list_default_depend_final_report($fs_company_info_id); 

            foreach ($fs_categorized_account as $key => $value) 
            {
                array_push($temp_array, 
                            array(
                                'fs_company_info_id'            => $fs_company_info_id,
                                'fs_note_templates_default_id'  => $value['fs_note_template_default_id'],
                                'order_by'                      => $key,
                                'link_allowed'                  => 1
                            ));
            }

            $result = $this->db->insert_batch('fs_note_templates_master', $temp_array);
        }

        // get and retrieve data from database
        $fs_list_final_report_type_id = $this->fs_model->get_final_document_type($fs_company_info_id);

        $q2 = $this->db->query("SELECT m.id AS `fs_note_templates_master_id`, m.fs_note_templates_default_id, t.section_name AS `default_name`, d.pdf_template, d.fs_ntfs_layout_template_default_id
                                FROM fs_note_templates_master m
                                LEFT JOIN fs_note_template_default d ON d.id = m.fs_note_templates_default_id
                                LEFT JOIN fs_ntfs_layout_template_default_fs_list_final_report nltd_lfr ON nltd_lfr.fs_ntfs_layout_template_default_id = d.fs_ntfs_layout_template_default_id AND nltd_lfr.fs_list_final_report_id = " . $fs_list_final_report_type_id . "
                                LEFT JOIN fs_ntfs_layout_template_default t ON d.fs_ntfs_layout_template_default_id = t.id
                                WHERE m.fs_company_info_id=" . $fs_company_info_id . " AND m.link_allowed = 1 ORDER by nltd_lfr.order_by");

        return $q2->result_array();
    }

    public function get_fs_note_details($fs_company_info_id, $fs_list_statement_doc_type_id)
    {
        $arranged_note_list = $this->get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id);

        $q = $this->db->query("SELECT fnd.*, fntd.fs_ntfs_layout_template_default_id
                                FROM fs_note_details fnd 
                                INNER JOIN 
                                    (SELECT fnd_1.fs_categorized_account_round_off_id, max(fnd_1.created_at) as `MaxDate` 
                                     FROM fs_categorized_account fca 
                                     JOIN fs_note_details fnd_1 
                                     WHERE fca.fs_company_info_id = " . $fs_company_info_id . "
                                     GROUP BY fnd_1.fs_categorized_account_round_off_id) fnd_max_date 
                                ON fnd.fs_categorized_account_round_off_id = fnd_max_date.fs_categorized_account_round_off_id AND fnd.created_at = fnd_max_date.MaxDate
                                LEFT JOIN fs_note_templates_master fntm ON fnd.fs_note_templates_master_id = fntm.id
                                LEFT JOIN fs_note_template_default fntd ON fntd.id = fntm.fs_note_templates_default_id
                                WHERE fnd.fs_company_info_id = " . $fs_company_info_id . " AND fnd.fs_list_statement_doc_type_id = " . $fs_list_statement_doc_type_id . ' AND fnd.in_use = 1 ORDER BY fnd.note_num_displayed');
        $q = $q->result_array();

        // update note number displayed (follow the arranged note)
        foreach ($q as $key => $value) 
        {
            if(empty($value['fs_ntfs_layout_template_default_id']))
            {
                unset($q[$key]);
            }
            elseif(in_array($value['fs_ntfs_layout_template_default_id'], array_column($arranged_note_list, 'fs_ntfs_layout_template_default_id')))
            {
                $anl_key = array_search($value['fs_ntfs_layout_template_default_id'], array_column($arranged_note_list, 'fs_ntfs_layout_template_default_id'));

                $q[$key]['note_num_displayed'] = $arranged_note_list[$anl_key]['note_no'];
            }
        }
        return array_values($q);
    }

    public function get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id)
    {
        $q = $this->db->query("SELECT lytd.id, lytd.section_name, lytd.parent, lytd.section_no, lytd.is_roman_section, lyt.is_shown, lyt.is_checked, lytd.default_checked, lyt.order_by, fntd.fs_ntfs_layout_template_default_id, fntm.id AS `fs_note_templates_master_id`
                                FROM fs_ntfs_layout_template lyt
                                LEFT JOIN fs_ntfs_layout_template_default lytd ON lytd.id = lyt.fs_ntfs_layout_template_default_id
                                LEFT JOIN fs_note_template_default fntd ON fntd.fs_ntfs_layout_template_default_id = lytd.id
                                LEFT JOIN fs_note_templates_master fntm ON fntm.fs_note_templates_default_id = fntd.id AND fntm.fs_company_info_id=" . $fs_company_info_id . "
                                WHERE lyt.fs_company_info_id=" . $fs_company_info_id . " AND lyt.set_parent = 0 ORDER BY lyt.order_by ASC");
        $q = $q->result_array();

        $note_no = 1;

        foreach ($q as $key => $value) 
        {
            if($value['is_checked'])
            {   
                if(!(empty($value['fs_note_templates_master_id']) && $key > 1))
                {
                    $q[$key]['note_no'] = $note_no;

                    $note_no++;
                }
            }
            else
            {
                $q[$key]['note_no'] = ''; 
            }

            // remove row to prevent duplicate data especially tax expense.
            if($key > 1)
            {
                if(empty($value['fs_note_templates_master_id']))
                {
                    unset($q[$key]);
                }
            }
        }

        return array_values($q);
    }

    public function get_update_note_num_displayed($q, $fs_company_info_id)
    {
        $arranged_note_list = $this->get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id);

        foreach ($q as $key => $value) // update note number displayed (follow the arranged note)
        {
            if(in_array($value['fs_note_templates_master_id'], array_column($arranged_note_list, 'fs_note_templates_master_id')))
            {
                $anl_key = array_search($value['fs_note_templates_master_id'], array_column($arranged_note_list, 'fs_note_templates_master_id'));

                $q[$key]['note_num_displayed'] = $arranged_note_list[$anl_key]['note_no'];
            }
            else
            {
                $q[$key]['note_num_displayed'] = '';
            }
        }

        return $q;
    }

    public function get_note_list_default_depend_final_report($fs_company_info_id)
    {
        $final_doc_type = $this->fs_model->get_final_document_type($fs_company_info_id);

        $fs_note_template_default = $this->db->query("SELECT ntd.id AS `fs_note_template_default_id`, ntd.default_name, ntd.fs_ntfs_layout_template_default_id, ntd.in_used, ntd_fr.order_by
                                                            FROM fs_list_note_template_default_fs_final_report ntd_fr
                                                            JOIN fs_note_template_default ntd ON ntd.id = ntd_fr.fs_note_template_default_id
                                                            WHERE ntd_fr.fs_list_final_report_type_id = " . $final_doc_type . " ORDER BY ntd_fr.order_by");
        $fs_note_template_default = $fs_note_template_default->result_array();

        return $fs_note_template_default;
    }

    public function get_ntfs_layout_default($fs_company_info_id)
    {
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $fs_list_final_report_type_id = 0;

        if($fs_company_info[0]['accounting_standard_used'] != 4)
        {
            $fs_list_final_report_id = 1;
        }
        else
        {
            if($fs_company_info[0]['is_audited'])
            {
                $fs_list_final_report_id = 2;
            }
            else
            {
                $fs_list_final_report_id = 3;
            }
        }

        $q = $this->db->query("SELECT lytd.id, lytd.section_name, lytd.parent, lytd.section_no, lytd.is_roman_section, lytd.default_checked 
                                FROM fs_ntfs_layout_template_default lytd 
                                LEFT JOIN fs_ntfs_layout_template_default_fs_list_final_report lytd_fr ON lytd_fr.fs_ntfs_layout_template_default_id = lytd.id 
                                WHERE lytd.in_used = 1 AND lytd_fr.fs_list_final_report_id = " . $fs_list_final_report_id . "
                                ORDER BY lytd_fr.order_by");
        $q = $q->result_array();

        return $q;
    }

    public function insert_update_fs_state_comp_income($fs_company_info_id) // when tree is update, run this function to insert or update data. 
    {
        $fs_company_info   = $this->caf_model->get_fs_company_info($fs_company_info_id); 
        $fs_statement_list = $this->fs_statements_model->get_fs_statement_json();    // get list of code from json

        $array_template = array(
                                'value_company_ye'      => 0,
                                'value_company_lye_end' => 0,
                                'value_group_ye'        => 0,
                                'value_group_lye_end'   => 0
                            );

        $income_list_data                   = $array_template;
        $other_income_list_data             = $array_template;
        $changes_in_inventories_data        = $array_template;
        $purchases_and_related_costs_data   = $array_template;
        $expenses_data                      = $array_template;
        $pl_be4_tax_data                    = $array_template;
        $pl_after_tax_data                  = $array_template;
        $additional_data                    = $array_template;
        $soa_pl_data                        = $array_template;

        foreach ($fs_statement_list->statement_comprehensive_income[0]->sections as $sci_json_key => $sci_json_value) 
        {
            $data = [];

            if($sci_json_value->list_name == "income_list") // income list 
            {
                $income_list = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $this->fs_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code));

                foreach ($income_list as $ikey => $ivalue) 
                {
                    $income_list_data['value_company_ye']       += !empty($ivalue['parent_array'][0]['total_c'])     ? $ivalue['parent_array'][0]['total_c'] * (-1)      : 0;
                    $income_list_data['value_company_lye_end']  += !empty($ivalue['parent_array'][0]['total_c_lye']) ? $ivalue['parent_array'][0]['total_c_lye'] * (-1)  : 0;
                    $income_list_data['value_group_ye']         += !empty($ivalue['parent_array'][0]['group_end_this_ye_value']) ? $ivalue['parent_array'][0]['group_end_this_ye_value'] * (-1)  : 0;
                    $income_list_data['value_group_lye_end']    += !empty($ivalue['parent_array'][0]['group_end_prev_ye_value']) ? $ivalue['parent_array'][0]['group_end_prev_ye_value'] * (-1)  : 0;
                }
            }
            elseif($sci_json_value->list_name == "other_income_list") // other list 
            {
                $other_income_list = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $this->fs_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code));

                foreach ($other_income_list as $ikey => $ivalue) 
                {
                    $other_income_list_data['value_company_ye']       += !empty($ivalue['parent_array'][0]['total_c'])     ? $ivalue['parent_array'][0]['total_c'] * (-1)      : 0;
                    $other_income_list_data['value_company_lye_end']  += !empty($ivalue['parent_array'][0]['total_c_lye']) ? $ivalue['parent_array'][0]['total_c_lye'] * (-1)  : 0;
                    $other_income_list_data['value_group_ye']         += !empty($ivalue['parent_array'][0]['group_end_this_ye_value']) ? $ivalue['parent_array'][0]['group_end_this_ye_value'] * (-1)  : 0;
                    $other_income_list_data['value_group_lye_end']    += !empty($ivalue['parent_array'][0]['group_end_prev_ye_value']) ? $ivalue['parent_array'][0]['group_end_prev_ye_value'] * (-1)  : 0;
                }
            }
            elseif($sci_json_value->list_name == "changes_in_inventories")  // Changes in inventories
            {
                $opening_inventories_g_ye  = 0;
                $opening_inventories_g_lye = 0;
                $closing_inventories_g_ye  = 0;

                $opening_inventories_c_lye = 0;
                $closing_inventories_c_lye = 0;

                /* GET AND SET OPENING, CLOSING AND PURCAHSES */
                if(!empty($this->fs_model->get_fca_id($fs_company_info_id, array($sci_json_value->sub_account_code[0]))) && !empty($this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $this->fs_model->get_fca_id($fs_company_info_id, array($sci_json_value->sub_account_code[0])))))
                {
                    $opening_inventories = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $this->fs_model->get_fca_id($fs_company_info_id, array($sci_json_value->sub_account_code[0])))[0];


                }
                if(!empty($this->fs_model->get_fca_id($fs_company_info_id, array($sci_json_value->sub_account_code[1]))) && !empty($this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $this->fs_model->get_fca_id($fs_company_info_id, array($sci_json_value->sub_account_code[1])))))
                {
                    $closing_inventories = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $this->fs_model->get_fca_id($fs_company_info_id, array($sci_json_value->sub_account_code[1])))[0];
                }

                if(isset($opening_inventories) && isset($closing_inventories))
                {
                    if(count($opening_inventories) >= 0 || count($closing_inventories) >= 0)
                    {
                        $data = array(
                                    'fs_company_info_id'    => $fs_company_info_id,
                                    'description'           => 'Changes in inventories',
                                    'value_group_ye'        => (float)$opening_inventories['parent_array'][0]['group_end_this_ye_value'] + (float)$closing_inventories['parent_array'][0]['group_end_this_ye_value'],
                                    'value_group_lye_end'   => (float)$opening_inventories['parent_array'][0]['group_end_prev_ye_value'] + (float)$closing_inventories['parent_array'][0]['group_end_prev_ye_value'],
                                    'fs_list_state_comp_income_section_id' => 1,
                                    // 'value'                     => $opening_inventories['parent_array'][0]['total_c'] + 
                                    //                                $purchases['parent_array'][0]['total_c'] - 
                                    //                                $closing_inventories['parent_array'][0]['total_c'], 
                                    'value_company_ye'      => (float)$opening_inventories['parent_array'][0]['total_c'] + (float)$closing_inventories['parent_array'][0]['total_c'],
                                    // 'company_end_prev_ye_value' => $opening_inventories_c_lye + $purchases_c_lye - $closing_inventories_c_lye
                                    'value_company_lye_end' => (float)$opening_inventories['parent_array'][0]['total_c_lye'] + (float)$closing_inventories['parent_array'][0]['total_c_lye'],
                                    'in_use' => 1
                                );

                        $changes_in_inventories_data['value_company_ye']       = $data['value_company_ye'] *(-1);
                        $changes_in_inventories_data['value_company_lye_end']  = $data['value_company_lye_end'] *(-1);
                        $changes_in_inventories_data['value_group_ye']         = $data['value_group_ye'] *(-1);
                        $changes_in_inventories_data['value_group_lye_end']    = $data['value_group_lye_end'] *(-1);
                    }
                }

                $cii_data = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=1"); // get changes in inventories
                $cii_data = $cii_data->result_array();

                if(count($cii_data) > 0)
                {
                    $changes_in_inventories_data['value_group_ye']         = (float)$cii_data[0]['value_group_ye'];
                    $changes_in_inventories_data['value_group_lye_end']    = (float)$cii_data[0]['value_group_lye_end'];
                }
            }
            elseif($sci_json_value->list_name == "expense_list") // Purchases and related costs
            {
                
                // get sub account codes list
                $expense_sub_list = $this->Fs_account_category_model->get_sub_categories($fs_company_info_id, $sci_json_value->account_category_code[0]);

                if(count($expense_sub_list) > 0)
                {
                    $expense_list     = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $expense_sub_list); 

                    // $expenses_data
                    foreach ($expense_list as $ekey => $evalue) 
                    {
                        $expenses_data['value_company_ye']      += !empty($evalue['parent_array'][0]['total_c'])     ? $evalue['parent_array'][0]['total_c'] * (-1)     : 0;
                        $expenses_data['value_company_lye_end'] += !empty($evalue['parent_array'][0]['total_c_lye']) ? $evalue['parent_array'][0]['total_c_lye'] * (-1) : 0;
                        $expenses_data['value_group_ye']        += !empty($evalue['parent_array'][0]['total_g'])     ? $evalue['parent_array'][0]['total_g'] * (-1)     : 0;
                        $expenses_data['value_group_lye_end']   += !empty($evalue['parent_array'][0]['total_g_lye']) ? $evalue['parent_array'][0]['total_g_lye'] * (-1) : 0;
                    }
                }
            }
            elseif($sci_json_value->list_name == "purchases_and_related_costs") // Purchases and related costs
            {
                // print_r(array($sci_json_value->exclude_sub_account_code));
                // print_r(array($sci_json_value));

                $purchases_and_related_costs = $this->get_account_with_exclude_sub_round_off($fs_company_info_id, $sci_json_value->account_category_code, $sci_json_value->exclude_sub_account_code);

                foreach ($purchases_and_related_costs as $prckey => $prcvalue) 
                {
                    $purchases_and_related_costs_data['value_company_ye']       += !empty($prcvalue['parent_array'][0]['total_c'])     ? $prcvalue['parent_array'][0]['total_c'] * (-1)     : 0;
                    $purchases_and_related_costs_data['value_company_lye_end']  += !empty($prcvalue['parent_array'][0]['total_c_lye']) ? $prcvalue['parent_array'][0]['total_c_lye'] * (-1) : 0;
                    $purchases_and_related_costs_data['value_group_ye']         += !empty($prcvalue['parent_array'][0]['total_g'])     ? $prcvalue['parent_array'][0]['total_g'] * (-1)     : 0;
                    $purchases_and_related_costs_data['value_group_lye_end']    += !empty($prcvalue['parent_array'][0]['total_g_lye']) ? $prcvalue['parent_array'][0]['total_g_lye'] * (-1) : 0;
                }

                $data = array(
                        'fs_company_info_id'    => $fs_company_info_id,
                        'description'           => 'Purchases and related costs',
                        'value_company_ye'      => ($purchases_and_related_costs_data['value_company_ye'] != 0)      ? $purchases_and_related_costs_data['value_company_ye']       : '',
                        'value_company_lye_end' => ($purchases_and_related_costs_data['value_company_lye_end'] != 0) ? $purchases_and_related_costs_data['value_company_lye_end']  : '',
                        'fs_list_state_comp_income_section_id' => 2,
                        'value_group_ye'        => ($purchases_and_related_costs_data['value_group_ye'] != 0)        ? $purchases_and_related_costs_data['value_group_ye']       : '',
                        'value_group_lye_end'   => ($purchases_and_related_costs_data['value_group_lye_end'] != 0)   ? $purchases_and_related_costs_data['value_group_lye_end']  : '',
                        'in_use' => 1
                    );

                /* ------------- if 'fs_state_comp_income' has record, we follow it ------------- */
                $prc_data = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=2"); // get purchases and related costs
                $prc_data = $prc_data->result_array();

                if(count($prc_data) > 0)
                {
                    $purchases_and_related_costs_data['value_group_ye']      = (float)$prc_data[0]['value_group_ye'];
                    $purchases_and_related_costs_data['value_group_lye_end'] = (float)$prc_data[0]['value_group_lye_end'];
                }
                /* ------------- END OF if 'fs_state_comp_income' has record, we follow it ------------- */
            }
            elseif ($sci_json_value->list_name == "additional_list") 
            {
                $additional_list = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $this->fs_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code));    // TAXATION

                foreach ($additional_list as $akey => $avalue) 
                {
                    $additional_data['value_company_ye']      += !empty($avalue['parent_array'][0]['total_c'])     ? $avalue['parent_array'][0]['total_c'] * (-1)      : 0;
                    $additional_data['value_company_lye_end'] += !empty($avalue['parent_array'][0]['total_c_lye']) ? $avalue['parent_array'][0]['total_c_lye'] * (-1)  : 0;
                    $additional_data['value_group_ye']        += !empty($avalue['parent_array'][0]['total_g'])     ? $avalue['parent_array'][0]['total_g'] * (-1)      : 0;
                    $additional_data['value_group_lye_end']   += !empty($avalue['parent_array'][0]['total_g_lye']) ? $avalue['parent_array'][0]['total_g_lye'] * (-1)  : 0;
                }
            }
            elseif($sci_json_value->list_name == "soa_pl_list") // Share of associates profit or loss
            {
                $temp_data = [];

                $fca_id = $this->fs_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code);

                foreach ($fca_id as $fca_id_key => $fca_id_value) 
                {
                    $temp_data = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($fca_id_value));

                    if(!empty($temp_data) && count($temp_data[0]['child_array']) > 0)
                    {
                        array_push($data, $temp_data);
                    }
                }

                $soa_pl_list = $data;

                foreach ($soa_pl_list as $akey => $avalue) 
                {
                    $soa_pl_data['value_company_ye']      += !empty($avalue[0]['parent_array'][0]['total_c'])     ? $avalue[0]['parent_array'][0]['total_c'] * (-1)     : 0;
                    $soa_pl_data['value_company_lye_end'] += !empty($avalue[0]['parent_array'][0]['total_c_lye']) ? $avalue[0]['parent_array'][0]['total_c_lye'] * (-1) : 0;
                    $soa_pl_data['value_group_ye']        += !empty($avalue[0]['parent_array'][0]['total_g'])     ? $avalue[0]['parent_array'][0]['total_g'] * (-1)     : 0;
                    $soa_pl_data['value_group_lye_end']   += !empty($avalue[0]['parent_array'][0]['total_g_lye']) ? $avalue[0]['parent_array'][0]['total_g_lye'] * (-1) : 0;
                }
            }
            elseif($sci_json_value->list_name == "pl_be4_tax" || $sci_json_value->list_name == "pl_after_tax")  // profit/loss before tax  AND  profit/loss after tax
            {
                $profit_loss_description = '';

                $income_bigger_group_ty   = '';
                $income_bigger_group_ly   = '';
                $income_bigger_company_ty = '';
                $income_bigger_company_ly = '';

                $income_bigger_group_ty   = (($income_list_data['value_group_ye'] + $other_income_list_data['value_group_ye'] +
                                             ($changes_in_inventories_data['value_group_ye'] + $purchases_and_related_costs_data['value_group_ye'] + $expenses_data['value_group_ye']) ) >= 0) ? true : false;

                $income_bigger_group_ly   = (($income_list_data['value_group_lye_end'] + $other_income_list_data['value_group_lye_end'] +
                                             ($changes_in_inventories_data['value_group_lye_end'] + $purchases_and_related_costs_data['value_group_lye_end'] + $expenses_data['value_group_lye_end'])) >= 0) ? true : false;

                $income_bigger_company_ty = (($income_list_data['value_company_ye'] + $other_income_list_data['value_company_ye'] +
                                             ($changes_in_inventories_data['value_company_ye'] + $purchases_and_related_costs_data['value_company_ye'] + $expenses_data['value_company_ye'])) >= 0) ? true : false;

                $income_bigger_company_ly = (($income_list_data['value_company_lye_end'] + $other_income_list_data['value_company_lye_end'] +
                                             ($changes_in_inventories_data['value_company_lye_end'] + $purchases_and_related_costs_data['value_company_lye_end'] + $expenses_data['value_company_lye_end'])) >= 0) ? true : false;

                // for display description ("Profit, Loss, Profit/Loss, Loss/Profit")
                if($fs_company_info[0]['group_type'] != 1)    // for group
                {
                    // if($income_bigger_group_ty && $income_bigger_group_ly && $income_bigger_company_ty && $income_bigger_company_ly && $fs_company_info[0]['group_type'])   // if all are positive
                    if($income_bigger_group_ty && $income_bigger_group_ly && $income_bigger_company_ty && $income_bigger_company_ly) // if all are positive
                    {
                        $profit_loss_description = 'Profit';
                    }
                    elseif(!$income_bigger_group_ty && !$income_bigger_group_ly && !$income_bigger_company_ty && !$income_bigger_company_ly)
                    {
                        $profit_loss_description = 'Loss';
                    }
                    else
                    {
                        if($income_bigger_group_ty) // check the first value if it is bigger value, set Profit/Loss, else set Loss/Profit
                        {
                            $profit_loss_description = 'Profit/Loss';   
                        }
                        else
                        {
                            $profit_loss_description = 'Loss/Profit';
                        }
                    }
                }
                elseif($fs_company_info[0]['group_type'] == 1)    // no group (company only)
                {
                    if($income_bigger_company_ty && $income_bigger_company_ly)
                    {
                        $profit_loss_description = 'Profit';
                    }
                    elseif(!$income_bigger_company_ty && !$income_bigger_company_ly)
                    {
                        $profit_loss_description = 'Loss';
                    }
                    else
                    {
                        if($income_bigger_company_ty)
                        {
                            $profit_loss_description = 'Profit/Loss';
                        }
                        else
                        {
                            $profit_loss_description = 'Loss/Profit';
                        }
                    }   
                }

                if($sci_json_value->list_name == "pl_be4_tax")
                {
                    /* --- profit/loss before tax = income - (changes in inventories + purchases and related costs + expenses) --- */
                    $pl_be4_tax_data['value_company_ye']        = $income_list_data['value_company_ye']               + 
                                                                $other_income_list_data['value_company_ye']           +
                                                                ($changes_in_inventories_data['value_company_ye']     + 
                                                                $purchases_and_related_costs_data['value_company_ye'] + 
                                                                $expenses_data['value_company_ye']);

                    $pl_be4_tax_data['value_company_lye_end']   = $income_list_data['value_company_lye_end']               + 
                                                                $other_income_list_data['value_company_lye_end']           +
                                                                ($changes_in_inventories_data['value_company_lye_end']     + 
                                                                $purchases_and_related_costs_data['value_company_lye_end'] + 
                                                                $expenses_data['value_company_lye_end']);

                    $pl_be4_tax_data['value_group_ye']          = $income_list_data['value_group_ye']               + 
                                                                $other_income_list_data['value_group_ye']           +
                                                                ($changes_in_inventories_data['value_group_ye']     + 
                                                                $purchases_and_related_costs_data['value_group_ye'] + 
                                                                $expenses_data['value_group_ye']);

                    $pl_be4_tax_data['value_group_lye_end']     = $income_list_data['value_group_lye_end']               + 
                                                                $other_income_list_data['value_group_lye_end']           +
                                                                ($changes_in_inventories_data['value_group_lye_end']     + 
                                                                $purchases_and_related_costs_data['value_group_lye_end'] + 
                                                                $expenses_data['value_group_lye_end']);

                    $data = array(
                            'fs_company_info_id'    => $fs_company_info_id,
                            'description'           => $profit_loss_description . " before tax",
                            'value_group_ye'        => ($pl_be4_tax_data['value_group_ye'] != 0)        ? $pl_be4_tax_data['value_group_ye']        : '',
                            'value_group_lye_end'   => ($pl_be4_tax_data['value_group_lye_end'] != 0)   ? $pl_be4_tax_data['value_group_lye_end']   : '',
                            'fs_list_state_comp_income_section_id' => 3,
                            'value_company_ye'      => ($pl_be4_tax_data['value_company_ye'] != 0)      ? $pl_be4_tax_data['value_company_ye']      : '',
                            'value_company_lye_end' => ($pl_be4_tax_data['value_company_lye_end'] != 0) ? $pl_be4_tax_data['value_company_lye_end'] : '',
                            'in_use' => 1
                        );
                }
                elseif ($sci_json_value->list_name == "pl_after_tax") 
                {
                    /* --- Method 1: profit/loss after tax = profit/loss before tax - additional --- */
                    /* --- Method 2: profit/loss after tax = (income - (changes in inventories + purchases and related costs + expenses)) - additional --- */
                    // print_r($soa_pl_data);

                    $pl_after_tax_data['value_group_ye']        = $pl_be4_tax_data['value_group_ye']        + $additional_data['value_group_ye']        - $soa_pl_data['value_group_ye'];
                    $pl_after_tax_data['value_group_lye_end']   = $pl_be4_tax_data['value_group_lye_end']   + $additional_data['value_group_lye_end']   - $soa_pl_data['value_group_lye_end'];
                    $pl_after_tax_data['value_company_ye']      = $pl_be4_tax_data['value_company_ye']      + $additional_data['value_company_ye']      - $soa_pl_data['value_company_ye'];
                    $pl_after_tax_data['value_company_lye_end'] = $pl_be4_tax_data['value_company_lye_end'] + $additional_data['value_company_lye_end'] - $soa_pl_data['value_company_lye_end'];

                    $data = array(
                            'fs_company_info_id'    => $fs_company_info_id,
                            'description'           => $profit_loss_description . " after tax",
                            'value_group_ye'        => ($pl_after_tax_data['value_group_ye'] != 0)        ? $pl_after_tax_data['value_group_ye']        : '',
                            'value_group_lye_end'   => ($pl_after_tax_data['value_group_lye_end'] != 0)   ? $pl_after_tax_data['value_group_lye_end']   : '',
                            'fs_list_state_comp_income_section_id' => 4,
                            'value_company_ye'      => ($pl_after_tax_data['value_company_ye'] != 0)      ? $pl_after_tax_data['value_company_ye']      : '',
                            'value_company_lye_end' => ($pl_after_tax_data['value_company_lye_end'] != 0) ? $pl_after_tax_data['value_company_lye_end'] : '',
                            'in_use' => 1
                        );
                }
            }

            // Save data to fs_state_comp_income
            if(isset($sci_json_value->fs_list_state_comp_income_section_id) && $sci_json_value->fs_list_state_comp_income_section_id!= 5)
            {
                $retrieve_sci_data = $this->db->query("SELECT * FROM fs_state_comp_income sci WHERE sci.fs_company_info_id=" . $fs_company_info_id . " AND sci.fs_list_state_comp_income_section_id=" . $sci_json_value->fs_list_state_comp_income_section_id);
                $retrieve_sci_data = $retrieve_sci_data->result_array();

                if(count($retrieve_sci_data) > 0)
                {
                    // update data
                    if(!empty($data))
                    {   
                        $result = $this->db->update('fs_state_comp_income', $data, array('id' => $retrieve_sci_data[0]['id']));
                    }
                    else
                    {
                        $result = $this->db->delete('fs_state_comp_income', array('id' => $retrieve_sci_data[0]['id']));
                    }
                }
                else
                {
                    // insert data
                    if(!empty($data))
                    {
                        $result = $this->db->insert('fs_state_comp_income', $data);
                    }
                }
            }
        }
        
        return $result;
    }

    public function delete_state_changes_in_equity_with_prior($fs_company_info_id, $group_type)
    {   
        $query_for_prior = " AND current_prior='prior'";

        if($group_type == '1')
        {
            $query_for_prior = " AND (current_prior='prior'";
            $query_for_group = " OR group_company = 'group')"; 
        }

        // delete from table "fs_state_changes_in_equity"
        $this->db->query("DELETE FROM fs_state_changes_in_equity WHERE fs_company_info_id = " . $fs_company_info_id . $query_for_prior . $query_for_group);

        // delete from table "fs_state_changes_in_equity_footer"
        $this->db->query("DELETE FROM fs_state_changes_in_equity_footer WHERE fs_company_info_id = " . $fs_company_info_id . $query_for_prior . $query_for_group);
    }
    

    public function update_reset_fs_ntfs($fs_company_info_id)   // check the section if the condition is met
    {
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $q = $this->db->query("SELECT * FROM fs_ntfs_layout_template lyt WHERE lyt.fs_company_info_id =" . $fs_company_info_id);
        $q = $q->result_array();

        $fs_default_acc_category_fs_ntfs_layout_template = $this->db->query("SELECT * FROM fs_default_acc_category_fs_ntfs_layout_template");
        $fs_default_acc_category_fs_ntfs_layout_template = $fs_default_acc_category_fs_ntfs_layout_template->result_array();

        $fs_categorized_account = $this->db->query("SELECT * FROM audit_categorized_account WHERE fs_company_info_id =" . $fs_company_info_id);
        $fs_categorized_account = $fs_categorized_account->result_array();

        $fs_default_acc_category = $this->get_default_account_list();
        $lyt_default_list = $this->get_ntfs_layout_default($fs_company_info_id);

        $data = $q;

        foreach ($q as $key => $value) 
        {
            // reset to default setttings
            $lyt_default_list_key = array_search($value['fs_ntfs_layout_template_default_id'], array_column($lyt_default_list, "id"));

            if($lyt_default_list_key || (string)$lyt_default_list_key == '0')
            {
                $lyt_default_list_key = array_search($value['fs_ntfs_layout_template_default_id'], array_column($lyt_default_list, "id"));
                $data[$key]['is_checked'] = $lyt_default_list[$lyt_default_list_key]['default_checked'];
                $data[$key]['fs_default_acc_category_id'] = 0;
            }

            // check from fs_default_acc_category_fs_ntfs_layout_template
            if(array_search($value['fs_ntfs_layout_template_default_id'], array_column($fs_default_acc_category_fs_ntfs_layout_template, "fs_ntfs_layout_template_default_id"))) // check if need to depend on checked account
            {
                $dac_ntfslt_key = array_search($value['fs_ntfs_layout_template_default_id'], array_column($fs_default_acc_category_fs_ntfs_layout_template, "fs_ntfs_layout_template_default_id"));
                $linked_dac_ids = json_decode($fs_default_acc_category_fs_ntfs_layout_template[$dac_ntfslt_key]['fs_default_acc_category_ids']);    // get fs_default_acc_category_ids

                foreach ($linked_dac_ids as $linked_dac_ids_key => $linked_dac_ids_value) 
                {
                    $matched_key = array_search($linked_dac_ids_value, array_column($fs_categorized_account, 'fs_default_acc_category_id'));

                    if($matched_key || (string)$matched_key == '0')
                    {
                        $matched_key = array_search((int)$linked_dac_ids_value, array_column($fs_categorized_account, 'fs_default_acc_category_id'));

                        $data[$key]['is_checked'] = true;
                        $data[$key]['fs_default_acc_category_id'] = json_decode($fs_categorized_account[$matched_key]['fs_default_acc_category_id']);   // link with note
                    }
                }
            }
        }

        $result = $this->update_fs_ntfs_specific_require($fs_company_info_id); // specific requirement (certain note depends on condition to decide whether want to checked or not)

        // update data
        $data_to_save = [];

        foreach ($data as $key => $value) 
        {
            array_push($data_to_save, 
                array(
                    'id' => $value['id'],
                    'info' => array(
                                'fs_default_acc_category_id' => $value['fs_default_acc_category_id'],
                                'is_checked' => $value['is_checked']
                            )
                )
            );
        }

        if(count($data_to_save) > 0)
        {
            $result = $this->update_tbl_data('fs_ntfs_layout_template', $data_to_save);
        }
    }

    public function update_fs_ntfs_specific_require($fs_company_info_id)
    {   
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $fs_ntfs = $this->fs_model->get_fs_ntfs_json(); 

        $data = $this->db->query("SELECT * FROM fs_ntfs_layout_template lyt WHERE lyt.fs_company_info_id =" . $fs_company_info_id);
        $data = $data->result_array();

        // specific requirement (certain note depends on condition to decide whether want to checked or not)
        foreach ($data as $key => $value) 
        {
            $matched_fs_ntfs_key = '';
            $matched_fs_ntfs_key = array_search($value['fs_ntfs_layout_template_default_id'], array_column($fs_ntfs['ntfs'], 'fs_ntfs_layout_template_default_id'));

            $temp_data = array(
                            'fs_company_info_id' => $fs_company_info_id,
                            'content'   => '',
                            'fs_default_template_comparative_figures_id' => 0
                        );

            if($matched_fs_ntfs_key || (string)$matched_fs_ntfs_key == '0')
            {
                if($fs_ntfs['ntfs'][$matched_fs_ntfs_key]['name'] == "COMPARATIVE FIGURES")   // for comparative figures
                {
                    $fs_comp_fig_type = 0;

                    $fs_comparative_figures                      = $this->get_fs_comparative_figures($fs_company_info_id);
                    $get_fs_default_template_comparative_figures = $this->get_fs_default_template_comparative_figures();

                    if($fs_company_info[0]['first_set'] == 1)
                    {
                        $data[$key]['is_checked'] = true;
                        $data[$key]['fs_default_acc_category_id'] = $value['fs_ntfs_layout_template_default_id'];

                        $temp_data['content'] = $get_fs_default_template_comparative_figures[1]['content'];
                        $temp_data['fs_default_template_comparative_figures_id'] = 2;
                    }
                    else // check dates
                    {
                        $lye_beg = substr_replace(preg_replace('/\s+/', '', $fs_company_info[0]['last_fye_begin']), "", -4);
                        $lye_end = substr_replace(preg_replace('/\s+/', '', $fs_company_info[0]['last_fye_end']), "", -4);

                        $current_beg = substr_replace(preg_replace('/\s+/', '', $fs_company_info[0]['current_fye_begin']), "", -4);
                        $current_end = substr_replace(preg_replace('/\s+/', '', $fs_company_info[0]['current_fye_end']), "", -4);

                        $interval_beg = $this->fs_model->compare_date_period($fs_company_info[0]['last_fye_begin'], $fs_company_info[0]['current_fye_begin'], '0 day');
                        $interval_end = $this->fs_model->compare_date_period($fs_company_info[0]['last_fye_end'], $fs_company_info[0]['current_fye_end'], '0 day');

                        if($lye_beg == $current_beg && $lye_end == $current_end && $interval_beg->y == 1 && $interval_end->y == 1)
                        {
                            $data[$key]['is_checked'] = false;
                            $data[$key]['fs_default_acc_category_id'] = 0;

                            if(count($fs_comparative_figures) > 0)
                            {
                                $temp_data['content'] = $fs_comparative_figures[0]['content'];
                                $temp_data['fs_default_template_comparative_figures_id'] = 1;
                                $data[$key]['is_checked'] = true;
                            }
                            else
                            {
                                $temp_data['content'] = '';
                                $temp_data['fs_default_template_comparative_figures_id'] = 1;
                            }
                        }
                        else
                        {
                            $data[$key]['is_checked'] = true;
                            $data[$key]['fs_default_acc_category_id'] = $value['fs_ntfs_layout_template_default_id'];

                            if($lye_beg == $current_beg && $lye_end != $current_end)
                            {
                                $temp_data['content'] = $get_fs_default_template_comparative_figures[3]['content'];
                                $temp_data['fs_default_template_comparative_figures_id'] = 4;
                            }
                            else
                            {
                                $temp_data['content'] = $get_fs_default_template_comparative_figures[2]['content'];
                                $temp_data['fs_default_template_comparative_figures_id'] = 3;
                            }
                        }
                    }

                    if(count($fs_comparative_figures) > 0)  // save fs_comparative_figures
                    {
                        $result = $this->update_tbl_data('fs_comparative_figures', 
                                        array(
                                            array(
                                                'id'    => $fs_comparative_figures[0]['id'],
                                                'info'  => $temp_data
                                            )
                                    ));
                    }
                    else
                    {
                        $result = $this->insert_tbl_data('fs_comparative_figures', 
                                        array(
                                            array(
                                                'info' => $temp_data
                                            )
                                        ));
                    }
                }
                elseif($fs_ntfs['ntfs'][$matched_fs_ntfs_key]['name'] == "GOING CONCERN") // for going concern
                {
                    foreach ($fs_ntfs['ntfs'][$matched_fs_ntfs_key]['compare_ref_id'] as $cri_key => $compare_ref_id) // full formula "A001 < L100"
                    {
                        $split_formula = preg_split('/\s+/', $compare_ref_id);

                        $formula_data_list = [];
                        $operator = '';

                        // insert data in array first
                        foreach ($split_formula as $key1 => $formula_data) 
                        {
                            if($formula_data == "<")
                            {
                                $operator = $formula_data;
                            }
                            else
                            {
                                // get fca data from database.
                                // get id
                                $fca_id = $this->fs_model->get_fca_id($fs_company_info_id, array($formula_data));

                                array_push($formula_data_list, $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_id));
                            }
                        }

                        // print_r(array(count($formula_data_list[0]), count($formula_data_list[1])));

                        if(count($formula_data_list[0]) == 0)
                        {
                            $formula_data_list[0][0]['parent_array'][0]['total_c']      = 0;
                            $formula_data_list[0][0]['parent_array'][0]['total_c_lye']  = 0;
                            $formula_data_list[0][0]['parent_array'][0]['total_g']      = 0;
                            $formula_data_list[0][0]['parent_array'][0]['total_g_lye']  = 0;
                        }
                        elseif(count($formula_data_list[1]) == 0)
                        {
                            $formula_data_list[1][0]['parent_array'][0]['total_c']      = 0;
                            $formula_data_list[1][0]['parent_array'][0]['total_c_lye']  = 0;
                            $formula_data_list[1][0]['parent_array'][0]['total_g']      = 0;
                            $formula_data_list[1][0]['parent_array'][0]['total_g_lye']  = 0;
                        }

                        // check condition (formula)
                        if(
                            $formula_data_list[0][0]['parent_array'][0]['total_c']      < $formula_data_list[1][0]['parent_array'][0]['total_c']     || 
                            $formula_data_list[0][0]['parent_array'][0]['total_c_lye']  < $formula_data_list[1][0]['parent_array'][0]['total_c_lye'] || 
                            $formula_data_list[0][0]['parent_array'][0]['total_g']      < $formula_data_list[1][0]['parent_array'][0]['total_g']     || 
                            $formula_data_list[0][0]['parent_array'][0]['total_g_lye']  < $formula_data_list[1][0]['parent_array'][0]['total_g_lye']
                        )
                        {
                            $data[$key]['is_checked'] = true;

                            break;
                        }
                        else
                        {
                            $data[$key]['is_checked'] = false;
                        }
                    }
                }
            }
        }

        // update data
        $data_to_save = [];

        foreach ($data as $key => $value) 
        {
            array_push($data_to_save, 
                array(
                    'id' => $value['id'],
                    'info' => array(
                                'fs_default_acc_category_id' => $value['fs_default_acc_category_id'],
                                'is_checked' => $value['is_checked']
                            )
                )
            );
        }

        $result = false;

        if(count($data_to_save) > 0)
        {
            $result = $this->update_tbl_data('fs_ntfs_layout_template', $data_to_save);
        }

        return $result;
    }

    public function insert_update_fs_note_details($fs_company_info_id)
    {
        $this->get_add_note_list($fs_company_info_id); 
        
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $fs_ntfs_with_arranged_note = $this->get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id);

        // print_r($fs_ntfs_with_arranged_note);

        $q = $this->db->query(
                                "SELECT fca.fs_default_acc_category_id, fca.account_code, fcaro.id AS `fcaro_id` 
                                FROM audit_categorized_account fca 
                                LEFT JOIN fs_categorized_account_round_off fcaro ON fca.id = fcaro.fs_categorized_account_id
                                WHERE fca.fs_company_info_id =" . $fs_company_info_id
                            );
        $q = $q->result_array();

        $fs_ntfs = $this->fs_model->get_fs_ntfs_json(); 

        // get connection info default between 2 tables
        $fs_default_acc_category_fs_ntfs_layout_template = $this->db->query("SELECT * FROM fs_default_acc_category_fs_ntfs_layout_template");
        $fs_default_acc_category_fs_ntfs_layout_template = $fs_default_acc_category_fs_ntfs_layout_template->result_array();

        $fs_note_templates_master = $this->db->query(
                                                        "SELECT fntm.id AS `fs_note_templates_master_id`, fntd.fs_ntfs_layout_template_default_id
                                                        FROM fs_note_templates_master fntm 
                                                        LEFT JOIN fs_note_template_default fntd ON fntd.id = fntm.fs_note_templates_default_id
                                                        WHERE fntm.fs_company_info_id = " . $fs_company_info_id
                                                    );
        $fs_note_templates_master = $fs_note_templates_master->result_array();

        foreach (array_column($fs_ntfs['statements'], "link_note") as $key => $value)   // list of account code to link to note for statement by statement (from json file)
        {
            foreach ($value as $ac_key => $ac_value) // loop by account codes
            {
                foreach ($q as $q_key => $q_value) 
                {
                    if($q_value['account_code'] == $ac_value)
                    {
                        foreach ($fs_default_acc_category_fs_ntfs_layout_template as $key_2 => $value_2) // find back the linked
                        {
                            $note_num_displayed = '';

                            if(in_array($q_value['fs_default_acc_category_id'], json_decode($value_2['fs_default_acc_category_ids'])))
                            {
                                $fs_ntfs_layout_template_default_id = $value_2['fs_ntfs_layout_template_default_id'];

                                $fs_note_templates_master_key = array_search($fs_ntfs_layout_template_default_id, array_column($fs_note_templates_master, 'fs_ntfs_layout_template_default_id'));

                                // get note number displayed
                                $fs_ntfs_with_arranged_note_key = array_search($fs_ntfs_layout_template_default_id, array_column($fs_ntfs_with_arranged_note, 'fs_ntfs_layout_template_default_id'));

                                if($fs_ntfs_with_arranged_note_key || (string)$fs_ntfs_with_arranged_note_key == '0')
                                {
                                    $note_num_displayed = $fs_ntfs_with_arranged_note[$fs_ntfs_with_arranged_note_key]['note_no'];
                                }

                                // update / save data for fs_note_details
                                if($fs_note_templates_master_key || (string)$fs_note_templates_master_key == '0')
                                {
                                    if( !empty($q_value['fcaro_id']) && 
                                        !empty($fs_note_templates_master[$fs_note_templates_master_key]['fs_note_templates_master_id']) && 
                                        !empty($fs_ntfs['statements'][$key]['fs_list_statement_doc_type']))
                                    {
                                        $data = array(
                                                    "info" => array(
                                                                'fs_categorized_account_round_off_id' => $q_value['fcaro_id'],
                                                                'fs_company_info_id'                  => $fs_company_info_id,
                                                                'fs_note_templates_master_id'         => $fs_note_templates_master[$fs_note_templates_master_key]['fs_note_templates_master_id'],
                                                                'fs_list_statement_doc_type_id'       => $fs_ntfs['statements'][$key]['fs_list_statement_doc_type'],
                                                                'note_num_displayed'                  => $note_num_displayed,
                                                                'in_use'                              => 1
                                                            )
                                                );

                                        $fs_note_details_data = $this->db->query("SELECT * 
                                                                                    FROM fs_note_details 
                                                                                    WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_note_templates_master_id=" . $fs_note_templates_master[$fs_note_templates_master_key]['fs_note_templates_master_id'] . " AND fs_categorized_account_round_off_id=" . $q_value['fcaro_id']);
                                        $fs_note_details_data = $fs_note_details_data->result_array();

                                        if(count($fs_note_details_data) > 0)
                                        {   
                                            $data['id'] = $fs_note_details_data[0]['id'];
                                            $result = $this->update_tbl_data('fs_note_details', array($data));
                                        }
                                        else
                                        {
                                            $result = $this->insert_tbl_data('fs_note_details', array($data));
                                        }
                                    }
                                    
                                    break;
                                }
                            }
                        }
                    }
                }
            }

            $this->update_checked_fs_ntfs_layout_template($fs_company_info_id);
        }
    }

    public function update_checked_fs_ntfs_layout_template($fs_company_info_id) // checked or unchecked when statement updates notes.
    {
        $arranged_note_list = $this->get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id);

        // get fs_note_details by latest date
        $q = $this->db->query("SELECT fnd_main.*, fntd.fs_ntfs_layout_template_default_id
                                FROM fs_note_details fnd_main 
                                INNER JOIN 
                                    (SELECT fnd.fs_categorized_account_round_off_id, max(fnd.created_at) as `MaxDate` 
                                     FROM fs_categorized_account fca 
                                     JOIN fs_note_details fnd 
                                     WHERE fca.fs_company_info_id = " . $fs_company_info_id . "
                                     GROUP BY fnd.fs_categorized_account_round_off_id) fnd_max_date 
                                ON fnd_main.fs_categorized_account_round_off_id = fnd_max_date.fs_categorized_account_round_off_id 
                                AND fnd_main.created_at = fnd_max_date.MaxDate 
                                AND fnd_main.fs_company_info_id = " . $fs_company_info_id . "
                                LEFT JOIN fs_note_templates_master fntm ON fnd_main.fs_note_templates_master_id = fntm.id 
                                LEFT JOIN fs_note_template_default fntd ON fntd.id = fntm.fs_note_templates_default_id 
                                GROUP BY fnd_main.fs_categorized_account_round_off_id
                                ORDER BY fnd_main.id");
        $q = $q->result_array(); 

        // update note number displayed (follow the arranged note)
        foreach ($q as $key => $value) 
        {
            if(empty($value['fs_ntfs_layout_template_default_id']))
            {
                unset($q[$key]);    // remove unlinked note (due to changed document type)
            }
        }

        $q = array_values($q);

        $linked_note_fnltd_list = array_column($q, "fs_ntfs_layout_template_default_id");

        foreach ($q as $key1 => $value1) 
        {
            $matched_keys = array_keys($linked_note_fnltd_list, $value1['fs_ntfs_layout_template_default_id']);

            $fnlt = $this->db->query("SELECT * FROM fs_ntfs_layout_template WHERE fs_company_info_id = " . $fs_company_info_id . " AND fs_ntfs_layout_template_default_id = " . $value1['fs_ntfs_layout_template_default_id']);
            $fnlt = $fnlt->result_array();

            if(count($matched_keys) > 1)    // if note is used more than 1 time (maybe in different statement appears)
            {
                $in_use = 0;

                foreach ($matched_keys as $m_key => $m_value) 
                {
                    if($q[$m_value]['in_use'])
                    {
                        $in_use = 1;
                    }
                }

                if(count($fnlt) > 0)
                {
                    $data = array(
                                    'id'   => $fnlt[0]['id'],
                                    'info' => array('is_checked' => $in_use)
                                );

                    // if note is in use, checked the linked note in NTA list, else unchecked the linked note in NTA list
                    if(!$in_use)  // remove the linked note in Statement of Cash Flow
                    {
                        $fs_state_cash_flows = $this->db->query("SELECT * FROM fs_state_cash_flows WHERE fs_company_info_id = " . $fs_company_info_id . " AND fs_note_details_id = " . $value1['id']);
                        $fs_state_cash_flows = $fs_state_cash_flows->result_array();

                        if(count($fs_state_cash_flows) > 0)
                        {
                            // empty fs_note_details_id
                            $fs_scf_data = array(
                                                'id'    => $fs_state_cash_flows[0]['id'],
                                                'info'  => array('fs_note_details_id' => '')
                                            );

                            $this->update_tbl_data('fs_state_cash_flows', array($fs_scf_data)); // update fs_state_cash_flows
                        }
                    }

                    $this->update_tbl_data('fs_ntfs_layout_template', array($data));
                }
            }
            else // for only 1 note tagged in statement
            {
                if(count($fnlt) > 0)
                {
                    $data = array(
                                    'id'   => $fnlt[0]['id'],
                                    'info' => array('is_checked' => 0)
                                );

                    // if note is in use, checked the linked note in NTA list, else unchecked the linked note in NTA list
                    if($value1['in_use'])
                    {
                        $data['info']['is_checked'] = 1;
                    }
                    else   // remove the linked note in Statement of Cash Flow
                    {
                        $fs_state_cash_flows = $this->db->query("SELECT * FROM fs_state_cash_flows WHERE fs_company_info_id = " . $fs_company_info_id . " AND fs_note_details_id = " . $value1['id']);
                        $fs_state_cash_flows = $fs_state_cash_flows->result_array();

                        if(count($fs_state_cash_flows) > 0)
                        {
                            // empty fs_note_details_id
                            $fs_scf_data = array(
                                                'id'    => $fs_state_cash_flows[0]['id'],
                                                'info'  => array('fs_note_details_id' => '')
                                            );

                            $this->update_tbl_data('fs_state_cash_flows', array($fs_scf_data)); // update fs_state_cash_flows
                        }
                    }

                    $this->update_tbl_data('fs_ntfs_layout_template', array($data));
                }
            }
        }
    }

    public function insert_fs_state_comp_income_fs_note_details($fs_company_info_id) 
    {
        $sci_data = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id);
        $sci_data = $sci_data->result_array();

        $fs_ntfs = $this->fs_model->get_fs_ntfs_json();

        $statement_key = array_search('Statement of comprehensive income', array_column($fs_ntfs['statements'], 'document_name'));

        $link_note_not_in_tree_data = $fs_ntfs['statements'][$statement_key]['link_note_not_in_tree'];

        $data = [];
        $fs_state_comp_income_ids = [];

        // build fs_note_details data
        foreach ($sci_data as $key => $value) 
        {
            if(in_array($value['fs_list_state_comp_income_section_id'], array_column($link_note_not_in_tree_data, 'fs_list_state_comp_income_section_id')))
            {
                $link_note_not_in_tree_data_key = array_search($value['fs_list_state_comp_income_section_id'], array_column($link_note_not_in_tree_data, 'fs_list_state_comp_income_section_id'));

                $fs_note_templates_master_id = $this->db->query("SELECT fntm.id AS `fntm_id`, fntd.*
                                                                FROM fs_note_template_default fntd
                                                                LEFT JOIN fs_note_templates_master fntm ON fntm.fs_note_templates_default_id = fntd.id AND fntm.fs_company_info_id = " . $fs_company_info_id . "
                                                                WHERE fntd.fs_ntfs_layout_template_default_id = " . $link_note_not_in_tree_data[$link_note_not_in_tree_data_key]['fs_ntfs_layout_template_default_id']);
                $fs_note_templates_master_id = $fs_note_templates_master_id->result_array();

                $temp_data =  array(
                                'fs_categorized_account_round_off_id' => 0,
                                'fs_company_info_id'                  => $fs_company_info_id,
                                'fs_note_templates_master_id'         => $fs_note_templates_master_id[0]['fntm_id'],
                                'fs_list_statement_doc_type_id'       => $link_note_not_in_tree_data[$link_note_not_in_tree_data_key]['fs_list_statement_doc_type_id'],
                                'in_use'                              => 1
                            );

                $arranged_noted_detail = $this->get_update_note_num_displayed(array($temp_data), $fs_company_info_id);

                // print_r($arranged_noted_detail);

                $temp_data['note_num_displayed'] = $arranged_noted_detail[0]['note_num_displayed'];

                array_push($data, $temp_data);
                array_push($fs_state_comp_income_ids, $value['id']);
            }
        }

        // retrieve fs_state_comp_income_fs_note_details to do add or update
        $fs_state_comp_income_fs_note_details = $this->db->query("SELECT fsci_fnd.*
                                                                FROM `fs_state_comp_income_fs_note_details` fsci_fnd
                                                                LEFT JOIN fs_state_comp_income fsci ON fsci.id = fsci_fnd.fs_state_comp_income_id
                                                                WHERE fsci.fs_company_info_id = " . $fs_company_info_id . " AND fsci.in_use = 1");
        $fs_state_comp_income_fs_note_details = $fs_state_comp_income_fs_note_details->result_array();
        
        if(count($fs_state_comp_income_fs_note_details) > 0)    // update / add notes
        {
            foreach ($data as $key => $value) 
            {
                if(in_array($fs_state_comp_income_ids[$key], array_column($fs_state_comp_income_fs_note_details, 'fs_state_comp_income_id')))
                {
                    $sci_fnd_key = array_search($fs_state_comp_income_ids[$key], array_column($fs_state_comp_income_fs_note_details, 'fs_state_comp_income_id'));
                    
                    // update data
                    $this->db->where('id', $fs_state_comp_income_fs_note_details[$sci_fnd_key]['fs_note_details_id']);
                    $result = $this->db->update('fs_note_details', $value);
                }
                else
                {
                    // insert new data
                    $result_a = $this->db->insert('fs_note_details', $value);
                    $fs_note_details_id = $this->db->insert_id();

                    // insert to fs_state_comp_income_fs_note_details
                    $result_b = $this->db->insert('fs_state_comp_income_fs_note_details', 
                                                    array(
                                                        'fs_state_comp_income_id' => $fs_state_comp_income_ids[$key],
                                                        'fs_note_details_id'      => $fs_note_details_id
                                                    )
                                                );
                }
            }
        }
        else // add notes
        {
            foreach ($data as $key => $value) 
            {
                // insert to fs_note_details
                $result_a = $this->db->insert('fs_note_details', $value);
                $fs_note_details_id = $this->db->insert_id();

                // insert to fs_state_comp_income_fs_note_details
                $result_b = $this->db->insert('fs_state_comp_income_fs_note_details', 
                                                array(
                                                    'fs_state_comp_income_id' => $fs_state_comp_income_ids[$key],
                                                    'fs_note_details_id'      => $fs_note_details_id
                                                )
                                            );

            }
        }
    }
    /* -------------------------------------------- END OF functions for notes -------------------------------------------- */

    public function insert_update_tbl_data($data)
    {
        $return_ids = array();

        if(count($data['deleted_ids']) > 0)
        {   
            foreach ($data['deleted_ids'] as $delete_key => $delete_value) 
            {
                if(!empty($delete_value))
                {
                    $this->db->delete($data['table_name'], array('id' => $delete_value));
                }
            }
        }

        foreach ($data['ntfs_data'] as $key => $value)
        {
            if(!empty($value['id']))    // update
            {
                $info = $this->db->query("SELECT * FROM " . $data['table_name'] . " WHERE id=" . $value['id']);
                $info = $info->result_array();

                $result = $this->update_tbl_data($data['table_name'], array($value));

                array_push($return_ids, $value['id']);
            }
            else
            {
                $result = $this->db->insert($data['table_name'], $value['info']);
                $id = $this->db->insert_id();

                array_push($return_ids, $id);
            }
        }

        return $return_ids;
    }

    public function insert_tbl_data($tbl_name, $data)    // insert new data only
    {
        foreach ($data as $key => $value) 
        {
            // print_r(array($data));
            $result = $this->db->insert($tbl_name, $value['info']);
        }

        return $result;
    }

    public function update_tbl_data($tbl_name, $data)   // update data only
    {
        foreach ($data as $key => $value) 
        {
            $this->db->where('id', $value['id']);
            $result = $this->db->update($tbl_name, $value['info']);
        }

        return $result;
    }
}
?>