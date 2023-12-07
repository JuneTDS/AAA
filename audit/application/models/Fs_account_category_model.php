<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fs_account_category_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));

        $this->load->model('fs_model');
    }

    public function get_fs_account_category_json()
    {
        $url         = 'assets/json/fs_account_category.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        return json_decode(json_encode($data_decode[0]), true);
    }

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

    public function get_all_level_account_code($fs_company_info_id) // get all level 1 account code
    {
        $q = $this->db->query('SELECT fca.account_code 
                                FROM audit_categorized_account fca 
                                WHERE fca.parent = "#" AND fca.fs_company_info_id = ' . $fs_company_info_id . ' AND is_deleted = 0');

        return array_column($q->result_array(), 'account_code');
    }

    public function get_sub_categories($fs_company_info_id, $parent_account_code)
    {
        $parent_id = $this->db->query("SELECT fca.id, fca.assignment_id, fca.fs_company_info_id, fca.description, fca.fs_default_acc_category_id, dac.account_code, fca.parent, fca.type, fca.order_by
                                        FROM audit_categorized_account fca
                                        LEFT JOIN fs_default_acc_category dac ON dac.id = fca.fs_default_acc_category_id
                                        WHERE dac.account_code = '" . $parent_account_code . "' AND fca.fs_company_info_id =" . $fs_company_info_id . " ORDER BY order_by");
        $parent_id = $parent_id->result_array();
        
        if(isset($parent_id[0]['id']) && !empty($parent_id[0]['id']))
        {
            $parent_id = $parent_id[0]['id'];

            $q = $this->db->query("SELECT * FROM audit_categorized_account WHERE parent = '" . $parent_id . "' AND fs_company_info_id =" . $fs_company_info_id . " ORDER BY order_by");

            if(count($q->result_array()) > 0)
            {
                $sub_ids = [];

                foreach ($q->result_array() as $key => $value) 
                {
                    array_push($sub_ids, $value['id']);
                }

                return $sub_ids;
            }
        }
        else
        {
            return [];
        }
    }

    public function get_categorized_fcaro_data($fs_company_info_id, $fs_categorized_account_id)
    {
        // $data = [];
        // $q = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fca.account_code
        //                             FROM fs_categorized_account_round_off fcaro
        //                             LEFT JOIN fs_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
        //                             WHERE fca.id=" . $fs_categorized_account_id . " ORDER BY fca.order_by");

        // $q = $this->db->query("SELECT * FROM fs_categorized_account WHERE id =" . $fs_categorized_account_id);

        $fs_categorized_data = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($fs_categorized_account_id));

        foreach ($fs_categorized_data as $key => $value) 
        {
            if(isset($value['parent_array']))
            {
                return $value['parent_array'];
            }
            elseif(isset($value['child_array'][0]))
            {
                return $value['child_array'][0];
            }
            elseif(isset($value['child_array']))
            {
                return $value['child_array'];
            }
        }

        return [];
    }

    public function get_default_main_sub_account_list($main_sub)
    {
        $this->db->select('*');
        $this->db->from('fs_default_acc_category');

        if($main_sub == "main")
        {
            $this->db->where('parent', "#");
        }
        else if($main_sub == "sub")
        {
            $this->db->where_not_in('parent', array('#'));
        }
        else if($main_sub == "default")
        {
            $this->db->where_not_in('parent', array(''));
        }
        
        $this->db->order_by('order ASC');
        
        $q = $this->db->get();

        if ($q->num_rows() > 0) 
        {
            return $q->result_array();
        }
        else
        {
            return '';
        }
    }

    public function get_categorizedData_or_default($fs_company_info_id)
    {
        $q = $this->db->query("SELECT fca.*, fdac.account_code AS `default_account_code`
                                FROM audit_categorized_account fca
                                LEFT JOIN fs_default_acc_category fdac ON fdac.id = fca.fs_default_acc_category_id
                                WHERE fca.fs_company_info_id=" . $fs_company_info_id . " ORDER BY fca.order_by");

        if($q->num_rows() > 0)
        {
            $data = [];

            // print_r($q->result_array());

            foreach ($q->result_array() as $key => $item) 
            {
                $value = '';
                $id = '';
                $account_code = '';

                if($item['account_code'] == '')
                {
                    $value = $item['value'];
                    // $id = $key;
                }
                else
                {
                    // $id = $item['default_account_code'];  
                    // $id = $item['account_code'];    
                }

                if($item['fs_default_acc_category_id'] != 0)
                {
                    $account_code = $item['default_account_code'];
                    // $account_code = $item['account_code'];
                }
                

                if(empty($value))
                {
                    $value = 0;
                }

                // array_push($data,
                //     array(
                //         "id"     => $id, 
                //         "parent" => $item["parent"], 
                //         "text"   => $item["description"],
                //         'type'   => $item["type"],
                //         'data'   => array(
                //                         account_code => $account_code,
                //                         value => number_format($value, 2)
                //                     )
                //     ));

                array_push($data,
                    array(
                        "id"     => $item["id"], 
                        "parent" => $item["parent"], 
                        "text"   => $item["description"],
                        'type'   => $item["type"],
                        'data'   => array(
                                        'account_code' => $account_code,
                                        'value' => $this->negative_bracket($value),
                                        'company_end_prev_ye_value' => $this->negative_bracket($item['company_end_prev_ye_value']),
                                        'fs_default_acc_category_id' => $item['fs_default_acc_category_id'],
                                        'id' => $item['id']
                                    )
                    ));
            }

            return $data;
        }
        else
        {   
            $data = [];

            $fs_company_info_this_ye = $this->fs_model->get_fs_company_info($fs_company_info_id);
            $fs_this_ye_company_code = $fs_company_info_this_ye[0]['company_code']; 

            $fs_company_info_last_ye = $this->db->query("SELECT * FROM fs_company_info WHERE company_code='" . $fs_this_ye_company_code . "' AND id <> '" . $fs_company_info_id . "'");
            $fs_company_info_last_ye = $fs_company_info_last_ye->result_array();

            if(count($fs_company_info_last_ye) > 0)
            {
                // $temp_target_ye = $fs_company_info_this_ye[0]['current_fye_end'];
                // $shortest_day = 1000000; // set initial for more days to prevent taking wrong date.
                // $last_ye_fs_company_info_id = 0;

                // foreach ($fs_company_info_last_ye as $key => $value)
                // {
                //     $temp_shortest_day = $this->fs_model->compare_date_latest($temp_target_ye, $value['current_fye_end'], $shortest_day);
                    
                //     if((int)$shortest_day > (int)$temp_shortest_day)
                //     {
                //         $last_ye_fs_company_info_id = $value['id'];
                //         $shortest_day = $temp_shortest_day;
                //     }
                // }

                $last_ye_fs_company_info_id = $this->fs_model->get_fs_company_info_last_year($fs_company_info_id);

                // if last year end is exists, update the value
                if($last_ye_fs_company_info_id != 0)
                {
                    $fs_categorized_account_list_last_ye = $this->db->query("SELECT * FROM audit_categorized_account WHERE fs_company_info_id=" . $last_ye_fs_company_info_id . " ORDER BY order_by");
                    $fs_categorized_account_list_last_ye = $fs_categorized_account_list_last_ye->result_array();

                    if(count($fs_categorized_account_list_last_ye) > 0)
                    {
                        $delete_trial_bl_account = [];

                        foreach ($fs_categorized_account_list_last_ye as $key => $item) 
                        {
                            $this_year_value = '';
                            $last_year_value = '';
                            $id = '';
                            $inserted = false;

                            if($item['account_code'] == '')
                            {
                                $value = $item['value'];
                                $last_year_value = str_replace(',', '', $item['value']);
                            }

                            // if(!empty($item['fs_default_acc_category_id']))
                            // {
                            //     // $account_code = $item['default_account_code'];
                            //     $account_code = $item['account_code'];
                            // }

                            // if($item['account_code'] == '')
                            // {
                            //     $last_year_value = str_replace(',', '', $item['value']);
                            //     $id = $key;
                            // }
                            // else
                            // {
                            //     $id = $item['account_code'];
                            // }

                            // $account_code = $item['account_code'];

                            $trial_balance_data = $this->db->query("SELECT * FROM fs_trial_balance WHERE fs_company_info_id=" . $fs_company_info_id);
                            $trial_balance_data = $trial_balance_data->result_array();

                            foreach ($trial_balance_data as $trial_bl_key => $trial_bl_value) 
                            {
                                if($trial_bl_value['description'] == $item["description"])
                                {
                                    $this_year_value = $trial_bl_value['value'];

                                    array_push($delete_trial_bl_account, $trial_bl_value['id']);

                                    if(empty($last_year_value))
                                    {
                                        $last_year_value = 0;
                                    }

                                    if(empty($this_year_value) || $item["type"] == "Branch")
                                    {
                                        $this_year_value = 0;
                                    }

                                    array_push($data,
                                        array(
                                            "id"     => $item['id'], 
                                            "parent" => $item["parent"], 
                                            "text"   => $item["description"],
                                            'type'   => $item["type"],
                                            'data'   => array(
                                                            'account_code' => $item['account_code'],
                                                            'value' => $this->negative_bracket($this_year_value),
                                                            'company_end_prev_ye_value' => $this->negative_bracket($last_year_value),
                                                            'fs_default_acc_category_id' => $item['fs_default_acc_category_id'],
                                                            'id' => ''
                                                        )
                                    ));
                                    $inserted = true;
                                }
                            }
                            
                            if(empty($last_year_value))
                            {
                                $last_year_value = 0;
                            }

                            if(empty($this_year_value))
                            {
                                $this_year_value = 0;
                            }

                            if(!$inserted)
                            {
                                array_push($data,
                                    array(
                                        "id"     => $item['id'], 
                                        "parent" => $item["parent"], 
                                        "text"   => $item["description"],
                                        'type'   => $item["type"],
                                        'data'   => array(
                                                        'account_code' => $item['account_code'],
                                                        'value' => $this->negative_bracket($this_year_value),
                                                        'company_end_prev_ye_value' => $this->negative_bracket($last_year_value),
                                                        'fs_default_acc_category_id' => $item['fs_default_acc_category_id'],
                                                        'id' => ''
                                                    )
                                ));
                            }
                        }

                        // if(count($delete_trial_bl_account) > 0)
                        // {
                        //     // delete last year exist account
                        //     $this->db->where_in('id', $delete_trial_bl_account);
                        //     $this->db->delete('fs_trial_balance');
                        // }
                    }
                    else
                    {
                        $data = $this->set_default_tree();
                    }

                }
            }
            else
            {
                $data = $this->set_default_tree();
            }

            // print_r($data);

            return $data;
        }
    }

    public function set_default_tree()
    {
        $data = [];

        foreach($this->get_default_account_list() as $item)
        {
            $account_code = $item["account_code"];

            if(empty($account_code)) // to solve some child under parent without account code
            {
                $account_code = $item['id'];
            }

            array_push($data, 
                array(
                    // "id"     => $item["account_code"], 
                    "id"     => $account_code,
                    "parent" => $item["parent"], 
                    "text"   => $item["tree_name"],
                    'type'   => $item["type"],
                    'data'   => array(
                                    'account_code' => $item['account_code'],
                                    'description' => $item['description'],
                                    'value' => '',
                                    'fs_default_acc_category_id' => $item['id'],
                                    'id' => ''
                                )
                )
            );
        }
        return $data;
    }

    public function get_create_uncategorizedData($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM `fs_uncategorized_account` WHERE `fs_company_info_id` = " . $fs_company_info_id . " ORDER BY order_by");

        $return_array = [];

        if($q->num_rows() > 0)
        {
            $return_array = $q->result_array();
        }
        else
        {   
            // for case like if all account is categorized.
            $q2 = $this->db->query("SELECT * FROM audit_categorized_account WHERE fs_company_info_id=" . $fs_company_info_id . ' AND type = "leaf" ORDER BY order_by');

            if($q2->num_rows() > 0)
            {
                return [];
            }
            else
            {
                $trial_balance_data = $this->db->query("SELECT * FROM fs_trial_balance WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
                $trial_balance_data = $trial_balance_data->result_array();

                $sorted_trial_balance_data = $trial_balance_data;

                $last_ye_fs_company_info_id = $this->fs_model->get_fs_company_info_last_year($fs_company_info_id);

                // print_r(array($last_ye_fs_company_info_id));

                if(!empty($last_ye_fs_company_info_id))  // if have previous year report
                {
                    $fs_categorized_account_list_last_ye = $this->db->query("SELECT * FROM audit_categorized_account WHERE fs_company_info_id=" . $last_ye_fs_company_info_id . " ORDER BY order_by");
                    $fs_categorized_account_list_last_ye = $fs_categorized_account_list_last_ye->result_array();

                    if(count($fs_categorized_account_list_last_ye) > 0)
                    {
                        foreach ($trial_balance_data as $trial_bl_key => $trial_bl_value) 
                        {
                            $matched = false;

                            foreach ($fs_categorized_account_list_last_ye as $fca_key => $fca_value) 
                            {
                                if($trial_bl_value['description'] == $fca_value['description'])
                                {
                                    $matched = true;
                                }
                            }

                            if($matched)
                            {
                                unset($sorted_trial_balance_data[$trial_bl_key]);
                            }
                        }
                    }

                    $return_array = $sorted_trial_balance_data;
                }
                else // if first report / no previous year record.
                {
                    $ly_trial_balance_data = $this->db->query("SELECT * FROM fs_ly_trial_balance WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
                    $ly_trial_balance_data = $ly_trial_balance_data->result_array();

                    if(count($sorted_trial_balance_data) > 0)
                    {
                        $tb_desc = array_column($sorted_trial_balance_data, 'description');

                        foreach ($ly_trial_balance_data as $key => $value) 
                        {
                            if(in_array($value['description'], $tb_desc))
                            {
                                $tb_desc_key = array_search($value['description'], $tb_desc);

                                $sorted_trial_balance_data[$tb_desc_key]['company_end_prev_ye_value'] = $value['value'];
                            }
                        }

                        $return_array = $sorted_trial_balance_data;
                    }
                    else
                    {
                        if(count($ly_trial_balance_data) > 0)
                        {
                            $return_array = $ly_trial_balance_data;
                            $swap_a = array_column($return_array, 'value');
                            $swap_b = array_column($return_array, 'company_end_prev_ye_value');

                            foreach ($return_array as $key => $value) 
                            {
                                $return_array[$key]['company_end_prev_ye_value'] = $value['value'];
                                $return_array[$key]['value'] = 0.00;
                            }
                        }
                    }
                }
            }
        }

        foreach ($return_array as $key => $value) 
        {
            $return_array[$key]['value'] = $this->negative_bracket($value['value']); 
            $return_array[$key]['company_end_prev_ye_value'] = $this->negative_bracket($value['company_end_prev_ye_value']); 
        }

        return $return_array;
    }

    // public function get_fs_total_by_account_category($fs_categorized_account_id)
    // {
    //     $q = $this->db->query("SELECT * FROM fs_total_by_account_category WHERE fs_categorized_account_id =" . $fs_categorized_account_id);

    //     return $q->result_array();
    // }

    /* ----- Retrieve original account ----- */
    public function check_account_data($assignment_id)
    {
        $return_data = array(
                            'take_parent_ly_values' => 0
                        );

        $account_list = $this->db->query("SELECT * FROM audit_categorized_account WHERE assignment_id=" . $assignment_id);
        $account_list = $account_list->result_array();

        foreach ($account_list as $key => $value) 
        {
            if($value['type'] == 'Branch')
            {
                if(!(empty($value['company_end_prev_ye_value']) || $value['company_end_prev_ye_value'] == 0.00))
                {
                    $return_data['take_parent_ly_values'] = 1;

                    break;
                }
            }
        }

        return $return_data;
    }

    public function get_account_with_sub_ids($assignment_id, $id_list)
    {
        $data = [];

        $check_condition_acc = $this->check_account_data($assignment_id);

        foreach ($id_list as $key => $id) 
        {
            $q = $this->db->query("SELECT aca.*,  aca.id AS `aca_id`, fs_default_acc_category.account_code
                                    FROM audit_categorized_account aca
                                    LEFT JOIN fs_default_acc_category ON aca.fs_default_acc_category_id = fs_default_acc_category.id
                                    WHERE aca.id=" . $id . " ORDER BY aca.order_by");

            if(count($q->result_array()) > 0)
            {
                $parent_array = $q->result_array();

                $child_array = $this->recursive_sub_ids($assignment_id, $id, $check_condition_acc);
                $total_category = $this->calculate_total_ids($assignment_id, $id);

                // if last year value for 'parent node' is not 0, take the value as total_c_lye
                if($parent_array[0]['type'] == 'Branch' && $check_condition_acc['take_parent_ly_values'])
                {
                    if(!empty($parent_array[0]['company_end_prev_ye_value']))
                    {
                        $total_category[0]['total_c_lye'] = $parent_array[0]['company_end_prev_ye_value'];
                    }
                }

                $parent_array[0]['total_c']          = $total_category['total_c'];
                $parent_array[0]['total_c_adjusted'] = $total_category['total_c_adjusted'];
                $parent_array[0]['total_c_lye']      = $total_category['total_c_lye'];
                $parent_array[0]['total_g']          = $total_category['total_g'];
                $parent_array[0]['total_g_lye']      = $total_category['total_g_lye'];
                $parent_array[0]['child_id']         =  implode(",", $total_category['child_id']);

                array_push($data, array('parent_array' => $parent_array, 'child_array' => $child_array));
            }
        }

        return $data;
    }

    public function recursive_sub_ids($assignment_id, $id, $check_condition_acc)
    {
        // print_r(array($id));
        $temp = [];

        $all_account = $this->db->query("SELECT aca.*, fs_default_acc_category.account_code FROM audit_categorized_account aca LEFT JOIN fs_default_acc_category ON aca.fs_default_acc_category_id = fs_default_acc_category.id WHERE assignment_id=" . $assignment_id . " ORDER BY order_by");
        $all_account = $all_account->result_array();

        // $q = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fca.account_code
        //                         FROM fs_categorized_account_round_off fcaro
        //                         LEFT JOIN fs_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
        //                         WHERE fca.parent=" . $id . " ORDER BY fca.order_by");

        $q = $this->db->query("SELECT aca.*,  aca.id AS `aca_id`, fs_default_acc_category.account_code
                                FROM audit_categorized_account aca
                                LEFT JOIN fs_default_acc_category ON aca.fs_default_acc_category_id = fs_default_acc_category.id
                                WHERE aca.parent=" . $id . " ORDER BY aca.order_by");

        // print_r($q->result_array());

        if(count($q->result_array()) > 0)
        {
            foreach ($q->result_array() as $key => $value) 
            {
                if(array_search($value['aca_id'], array_column($all_account, 'parent'), true))
                {
                    $child_data = $this->recursive_sub_ids($assignment_id, $value['aca_id'], $check_condition_acc);

                    $total_category = $this->calculate_total_ids($assignment_id, $value['aca_id']);

                    // if last year value for 'parent node' is not 0, take the value as total_c_lye
                    if($value['type'] == 'Branch' && $check_condition_acc['take_parent_ly_values'])
                    {
                        if(!empty($value['company_end_prev_ye_value']))
                        {
                            $total_category['total_c_lye'] = $value['company_end_prev_ye_value'];
                        }
                    }

                    $value['total_c']           = $total_category['total_c'];
                    $value['total_c_adjusted']  = $total_category['total_c_adjusted'];
                    $value['total_c_lye']       = $total_category['total_c_lye'];
                    $value['total_g']           = $total_category['total_g'];
                    $value['total_g_lye']       = $total_category['total_g_lye'];
                    $value['child_id']          =  implode(",", $total_category['child_id']);

                    array_push($temp, array('parent_array' => array($value), 'child_array' => $child_data));
                }
                else
                {
                     // print_r($value);
                    // print_r(array($value['type']));

                    if($value['type'] == 'Branch')
                    {
                        array_push($temp, array('parent_array' => array($value), 'child_array' => []));
                    }
                    else
                    {
                        array_push($temp, array('child_array' => $value));
                    }
                }
            }
        }

        return $temp;
    }

    public function calculate_total_ids($assignment_id, $id) // calculate total of value from the bottom level
    {   
        $total_c = 0.00;
        $total_c_adjusted = 0.00;
        $total_c_lye = 0.00;
        $total_g = 0.00;
        $total_g_lye = 0.00;
        $child_id = array();

        if(!empty($id))
        {
            // $all_account = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fca.account_code
            //                     FROM fs_categorized_account_round_off fcaro
            //                     LEFT JOIN fs_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
            //                     WHERE fcaro.fs_company_info_id = " . $fs_company_info_id);
            $all_account = $this->db->query("SELECT aca.*, aca.id AS `aca_id`
                                FROM audit_categorized_account aca
                                WHERE aca.assignment_id = " . $assignment_id);  // for original values
            $all_account = $all_account->result_array();

            $temp_account_id = [];

            array_push($temp_account_id, $id);

            do{
                // $q = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fca.account_code
                //                 FROM fs_categorized_account_round_off fcaro
                //                 LEFT JOIN fs_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
                //                 WHERE fca.parent=" . $temp_account_id[0] . " AND fcaro.fs_company_info_id = " . $fs_company_info_id);
                $q = $this->db->query("SELECT aca.*, aca.id AS `aca_id`
                                FROM audit_categorized_account aca
                                WHERE aca.parent=" . $temp_account_id[0] . " AND aca.assignment_id = " . $assignment_id);  // for original values
                if(count($q->result_array()) > 0)
                {
                    foreach ($q->result_array() as $key => $value) 
                    {

                        $total_c              += (float)$value['value'];
                        $total_c_adjusted     += (float)$value['adjusted_value'];
                        $total_c_lye          += (float)$value['company_end_prev_ye_value'];
                        $total_g              += (float)$value['group_end_this_ye_value'];
                        $total_g_lye          += (float)$value['group_end_prev_ye_value'];
                        array_push($child_id, $value['aca_id']);

                        // print_r($child_id);

                        if(in_array($value['aca_id'], array_column($all_account, 'parent')))  // if this account got child
                        {
                            array_push($temp_account_id, $value['aca_id']);
                        }
                    }
                }

                unset($temp_account_id[0]);

                if(count($temp_account_id) > 0)
                {
                    $temp_account_id = array_values($temp_account_id);
                }
            }
            while(count($temp_account_id) > 0);
        }

        if(is_null($total_c))
        {
            $total_c = 0.00;
        }
        
        if(is_null($total_c_lye))
        {
            $total_c_lye = 0.00;
        }

        return array('total_c' => $total_c, 'total_c_adjusted' => $total_c_adjusted, 'total_c_lye' => $total_c_lye, 'total_g' => $total_g, 'total_g_lye' => $total_g_lye, 'child_id' => $child_id);
    }
    /* ----- END OF Retrieve original account ----- */

    public function get_account_with_exclude_sub_round_off($fs_company_info_id, $account_code_list, $exclude_account_code_list)
    {   
        $data = [];

        // get parent account list
        foreach ($account_code_list as $key => $value) 
        {
            $parent_fca_id = $this->fs_model->get_fca_id($fs_company_info_id, array($value));
            $account_list = $this->get_account_with_sub_round_off_ids($fs_company_info_id, $parent_fca_id); 

            foreach ($account_list[0]['child_array'] as $key2 => $value2) 
            {
                if(!in_array($value2['parent_array'][0]['account_code'], $exclude_account_code_list))
                {
                    array_push($data, $value2);
                }
            }

        }

        return $data;
    }

    public function get_account_with_sub_round_off_ids($fs_company_info_id, $id_list)
    {
        $data = [];
     
        foreach ($id_list as $key => $id) 
        {
            $q = $this->db->query("SELECT fcaro.*, fdac.parent, fca.type, fca.id AS `fca_id`, fdac.account_code
                                    FROM fs_categorized_account_round_off fcaro
                                    LEFT JOIN audit_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
                                    LEFT JOIN fs_default_acc_category fdac ON fca.fs_default_acc_category_id = fdac.id
                                    WHERE fca.id=" . $id . " ORDER BY fca.order_by");

            if(count($q->result_array()) > 0)
            {
                $parent_array = $q->result_array();

                $child_array = $this->recursive_sub_round_off_ids($fs_company_info_id, $id);
                $total_category = $this->calculate_total_round_off_ids($fs_company_info_id, $id);

                $parent_array[0]['total_c']     = $total_category['total_c'];
                $parent_array[0]['total_c_lye'] = $total_category['total_c_lye'];
                $parent_array[0]['total_g']     = $total_category['total_g'];
                $parent_array[0]['total_g_lye'] = $total_category['total_g_lye'];

                array_push($data, array('parent_array' => $parent_array, 'child_array' => $child_array));
            }
        }

        return $data;
    }

    public function recursive_sub_round_off_ids($fs_company_info_id, $id)
    {
        // print_r(array($id));
        $temp = [];

        $all_account = $this->db->query("SELECT * FROM audit_categorized_account WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
        $all_account = $all_account->result_array();

        $q = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fdac.account_code
                                FROM fs_categorized_account_round_off fcaro
                                LEFT JOIN audit_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
                                LEFT JOIN fs_default_acc_category fdac ON fca.fs_default_acc_category_id = fdac.id
                                WHERE fca.parent=" . $id . " ORDER BY fca.order_by");

        if(count($q->result_array()) > 0)
        {
            foreach ($q->result_array() as $key => $value) 
            {
                if(array_search($value['fca_id'], array_column($all_account, 'parent'), true))
                {
                    $child_data = $this->recursive_sub_round_off_ids($fs_company_info_id, $value['fca_id']);

                    $total_category = $this->calculate_total_round_off_ids($fs_company_info_id, $value['fca_id']);

                    $value['total_c']       = $total_category['total_c'];
                    $value['total_c_lye']   = $total_category['total_c_lye'];
                    $value['total_g']       = $total_category['total_g'];
                    $value['total_g_lye']   = $total_category['total_g_lye'];

                    array_push($temp, array('parent_array' => array($value), 'child_array' => $child_data));
                }
                else
                {
                    // print_r(array($value['type']));

                    if($value['type'] == 'Branch')
                    {
                        array_push($temp, array('parent_array' => array($value), 'child_array' => []));
                    }
                    else
                    {
                        array_push($temp, array('child_array' => $value));
                    }
                }
            }
        }

        return $temp;
    }

    public function calculate_total_round_off_ids($fs_company_info_id, $id) // calculate total of value from the bottom level
    {   
        $total_c     = 0.00;
        $total_c_lye = 0.00;
        $total_g     = 0.00;
        $total_g_lye = 0.00;

        if(!empty($id))
        {
            $all_account = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fdac.account_code
                                FROM fs_categorized_account_round_off fcaro
                                LEFT JOIN audit_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
                                LEFT JOIN fs_default_acc_category fdac ON fca.fs_default_acc_category_id = fdac.id
                                WHERE fcaro.fs_company_info_id = " . $fs_company_info_id);

            $all_account = $all_account->result_array();

            $temp_account_id = [];

            array_push($temp_account_id, $id);

            do{
                $q = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fdac.account_code
                                FROM fs_categorized_account_round_off fcaro
                                LEFT JOIN audit_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
                                LEFT JOIN fs_default_acc_category fdac ON fca.fs_default_acc_category_id = fdac.id
                                WHERE fca.parent=" . $temp_account_id[0] . " AND fcaro.fs_company_info_id = " . $fs_company_info_id);

                if(count($q->result_array()) > 0)
                {
                    foreach ($q->result_array() as $key => $value) 
                    {
                        $total_c     += (float)$value['value'];
                        $total_c_lye += (float)$value['company_end_prev_ye_value'];
                        $total_g     += (float)$value['group_end_this_ye_value'];
                        $total_g_lye += (float)$value['group_end_prev_ye_value'];

                        if(in_array($value['fca_id'], array_column($all_account, 'parent')))  // if this account got child
                        {
                            array_push($temp_account_id, $value['fca_id']);
                        }
                    }
                }

                unset($temp_account_id[0]);

                if(count($temp_account_id) > 0)
                {
                    $temp_account_id = array_values($temp_account_id);
                }
            }
            while(count($temp_account_id) > 0);
        }

        if(is_null($total_c))
        {
            $total_c = 0.00;
        }
        
        if(is_null($total_c_lye))
        {
            $total_c_lye = 0.00;
        }

        return array('total_c' => $total_c, 'total_c_lye' => $total_c_lye, 'total_g' => $total_g, 'total_g_lye' => $total_g_lye);
    }

    public function change_sign_in_account($data)   // Flip the sign of account (Credit has no bracket, Debit has bracket / POsitive to Negative - , Negative to Positive + )
    {
        foreach ($data as $key => $value) 
        {
            if(isset($value['parent_array']) && count($value['parent_array']) > 0)
            {
                $data[$key]['parent_array'][0]['value']                     = ((!empty($value['parent_array'][0]['value']))?$value['parent_array'][0]['value']:0) * -1;
                $data[$key]['parent_array'][0]['company_end_prev_ye_value'] = ((!empty($value['parent_array'][0]['company_end_prev_ye_value']))?$value['parent_array'][0]['company_end_prev_ye_value']:0) * -1;
                $data[$key]['parent_array'][0]['company_beg_prev_ye_value'] = ((!empty($value['parent_array'][0]['company_beg_prev_ye_value']))?$value['parent_array'][0]['company_beg_prev_ye_value']:0) * -1;

                $data[$key]['parent_array'][0]['total_c']       = (isset($value['parent_array'][0]['total_c'])?$value['parent_array'][0]['total_c']:0)         * -1;
                $data[$key]['parent_array'][0]['total_c_lye']   = (isset($value['parent_array'][0]['total_c_lye'])?$value['parent_array'][0]['total_c_lye']:0) * -1;
            }

            if(isset($value['child_array']) && count($value['child_array']) > 0)
            {
                if(isset($value['child_array']['value']))
                {
                    $data[$key]['child_array']['value']                     = ((!empty($value['child_array']['value']))?$value['child_array']['value']:0) * -1;
                    $data[$key]['child_array']['company_end_prev_ye_value'] = ((!empty($value['child_array']['company_end_prev_ye_value']))?$value['child_array']['company_end_prev_ye_value']:0) * -1;
                    $data[$key]['child_array']['company_beg_prev_ye_value'] = ((!empty($value['child_array']['company_beg_prev_ye_value']))?$value['child_array']['company_beg_prev_ye_value']:0) * -1;
                    // $data[$key]['child_array']['group_end_this_ye_value']   = $value['child_array']['group_end_this_ye_value']   * -1;
                    // $data[$key]['child_array']['group_end_prev_ye_value']   = $value['child_array']['group_end_prev_ye_value']   * -1;
                    // $data[$key]['child_array']['group_beg_prev_ye_value']   = $value['child_array']['group_beg_prev_ye_value']   * -1;
                }
                else
                {
                    $data[$key]['child_array'] = $this->change_sign_in_account($value['child_array']);
                }
            }
        }

        return $data;
    }

    public function operate_account_value($data, $search_account_code_list, $operate_values_list)   // operate (+ - x /) to specific account with account code
    {
        foreach ($data as $key => $value) 
        {
            if(isset($value['parent_array']) && count($value['parent_array']) > 0)
            {
                if(in_array($value['parent_array'][0]['account_code'], $search_account_code_list))
                {
                    $sac_key = array_search($value['parent_array'][0]['account_code'], $search_account_code_list);

                    if($operate_values_list[$sac_key]['operator'] == "+")
                    {
                        $data[$key]['parent_array'][0]['total_c']     += $operate_values_list[$sac_key]['insert_values_arr']['total_c'];
                        $data[$key]['parent_array'][0]['total_c_lye'] += $operate_values_list[$sac_key]['insert_values_arr']['total_c_lye'];
                        // $data[$key]['parent_array'][0]['total_g']     += $operate_values_list[$sac_key]['insert_values_arr']['total_g'];
                        // $data[$key]['parent_array'][0]['total_g_lye'] += $operate_values_list[$sac_key]['insert_values_arr']['total_g_lye'];
                    }
                    
                }
            }

            if(count($value['child_array']) > 0)
            {
                if(!isset($value['child_array']['value']))
                {
                    $data[$key]['child_array'] = $this->operate_account_value($value['child_array'], $search_account_code_list, $operate_values_list);
                }
            }
        }

        return $data;
    }

    // public function get_account_with_sub_round_off($fs_company_info_id, $account_code_list)
    // {
    //     $data = [];

    //     foreach ($account_code_list as $key => $account_code) 
    //     {
    //         $q = $this->db->query("SELECT * FROM fs_categorized_account_round_off WHERE account_code='" . $account_code ."' AND fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");

    //         if(count($q->result_array()) > 0)
    //         {
    //             $parent_array = $q->result_array();

    //             $child_array = $this->recursive_sub_round_off($fs_company_info_id, $account_code);

    //             $total_category = $this->calculate_total_round_off($fs_company_info_id, $account_code);

    //             $parent_array[0]['total_c']     = $total_category['total_c'];
    //             $parent_array[0]['total_c_lye'] = $total_category['total_c_lye'];
    //             $parent_array[0]['total_g']     = $total_category['total_g'];
    //             $parent_array[0]['total_g_lye'] = $total_category['total_g_lye'];

    //             array_push($data, array('parent_array' => $parent_array, 'child_array' => $child_array));
    //         }
    //     }
        
    //     return array($data);
    // }

    // public function recursive_sub_round_off($fs_company_info_id, $account_code)
    // {
    //     $temp = [];

    //     $q = $this->db->query("SELECT * FROM fs_categorized_account_round_off fca WHERE fca.parent = '" . $account_code . "' AND fca.fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");

    //     if(count($q->result_array()) > 0)
    //     {
    //         foreach ($q->result_array() as $key => $value) 
    //         {
    //             if(!empty($value['account_code']))
    //             {
    //                 $child_data = $this->recursive_sub_round_off($fs_company_info_id, $value['account_code']);

    //                 $total_category = $this->calculate_total_round_off($fs_company_info_id, $value['account_code']);

    //                 $value['total_c']       = $total_category['total_c'];
    //                 $value['total_c_lye']   = $total_category['total_c_lye'];
    //                 $value['total_g']       = $total_category['total_g'];
    //                 $value['total_g_lye']   = $total_category['total_g_lye'];

    //                 array_push($temp, array('parent_array' => array($value), 'child_array' => $child_data));
    //             }
    //             else
    //             {
    //                 array_push($temp, array('child_array' => $value));
    //             }
    //         }
    //     }

    //     return $temp;
    // }

    // public function calculate_total_round_off($fs_company_info_id, $account_code) // calculate total of value from the bottom level
    // {   
    //     $total_c = 0.00;
    //     $total_c_lye = 0.00;

    //     if(!empty($account_code))
    //     {
    //         $temp_account_code = [];

    //         array_push($temp_account_code, $account_code);

    //         do{
    //             $q = $this->db->query("SELECT * FROM fs_categorized_account_round_off WHERE parent='" . $temp_account_code[0] . "' AND fs_company_info_id=" . $fs_company_info_id);

    //             if(count($q->result_array()) > 0)
    //             {
    //                 foreach ($q->result_array() as $key => $value) 
    //                 {
    //                     $total_c     += (float)$value['value'];
    //                     $total_c_lye += (float)$value['company_end_prev_ye_value'];
    //                     $total_g     += (float)$value['group_end_this_ye_value'];
    //                     $total_g_lye += (float)$value['group_end_prev_ye_value'];

    //                     if(!empty($value['account_code']))
    //                     {
    //                         array_push($temp_account_code, $value['account_code']);
    //                     }
    //                 }
    //             }

    //             unset($temp_account_code[0]);

    //             if(count($temp_account_code) > 0)
    //             {
    //                 $temp_account_code = array_values($temp_account_code);
    //             }
    //         }
    //         while(count($temp_account_code) > 0);
    //     }

    //     if(is_null($total_c))
    //     {
    //         $total_c = 0.00;
    //     }
        
    //     if(is_null($total_c_lye))
    //     {
    //         $total_c_lye = 0.00;
    //     }

    //     return array('total_c' => $total_c, 'total_c_lye' => $total_c_lye, 'total_g' => $total_g, 'total_g_lye' => $total_g_lye);
    // }

    // public function insert_new_category($data)
    // {
    //     if(!empty($data['description']))
    //     {
    //         $q = $this->db->query("SELECT MAX(fs_default_acc_category.order) AS max_order FROM fs_default_acc_category");

    //         if ($q->num_rows() > 0) {
    //             $data['order'] = $q->result_array()[0]['max_order'] + 1;
    //         }

    //         $sub_account_list = $this->get_default_main_sub_account_list('sub');
    //         $last_sub_account_code = $sub_account_list[count($sub_account_list) - 1]['account_code'];

    //         $last_sub_account_code_index = str_replace('S', '', $last_sub_account_code);

    //         // return $last_sub_account_code_index + 1;

    //         if($last_sub_account_code_index + 1 < 10)
    //         {
    //             $data['account_code'] = 'S' . '00' . (string)($last_sub_account_code_index + 1);
    //         }
    //         else if($last_sub_account_code_index + 1 < 100)
    //         {
    //             $data['account_code'] = 'S' . '0' . (string)($last_sub_account_code_index + 1);
    //         }
    //         else if($last_sub_account_code_index + 1 < 1000)
    //         {
    //             $data['account_code'] = 'S' . (string)($last_sub_account_code_index + 1);
    //         }
    //         else
    //         {
    //             $data['account_code'] = 'S' . (string)($last_sub_account_code_index + 1);
    //         }

    //         $result = $this->db->insert('fs_default_acc_category', $data);

    //         return array('result' => true, 'data' => $data);
    //     }
    //     else
    //     {
    //         return array('result' => false, 'data' => $data);
    //     }
    // }

    // public function insert_batch_trial_balance($trial_balance, $fs_company_info_id)
    // {
    //     // delete previous record if exist
    //     $this->db->where_in('fs_company_info_id', $fs_company_info_id);
    //     $this->db->delete('fs_trial_balance');

    //     // insert new data
    //     $this->db->insert_batch('fs_trial_balance', $trial_balance);

    //     // compare and remove item which is not in list of trial balance.

    //     // // remove all uncategorized list
    //     // $this->db->where_in('fs_company_info_id', $fs_company_info_id);
    //     // $this->db->delete('fs_uncategorized_account');

    //     // categorized list
    //     $categorized = $this->db->query("SELECT * FROM fs_categorized_account WHERE fs_company_info_id=" . $fs_company_info_id);

    //     if($categorized->num_rows() > 0)
    //     {
    //         $categorized = $categorized->result_array();

    //         // trial balance list
    //         $trial_balance_d = $this->db->query("SELECT * FROM  fs_trial_balance WHERE fs_company_info_id=" . $fs_company_info_id);

    //         if($trial_balance_d->num_rows() > 0)
    //         {
    //             $trial_balance_d = $trial_balance_d->result_array();
    //         }

    //         $exist_before = [];
    //         $for_encategorized = [];

    //         // $delete_categorized_account = [];

    //         $uncategorized_list_new = [];

    //         // $temp_key = 0;

    //         foreach($categorized as $key => $value_1)
    //         {
    //             if($value_1['type'] == "Leaf")
    //             {
    //                 foreach ($trial_balance_d as $tb_key => $value_2) 
    //                 {
    //                     if($value_1['description'] == $value_2['description'] && $value_1['value'] == $value_2['value'])    // if both description and value are same
    //                     {
    //                         array_push($exist_before, $tb_key);
    //                     }
    //                     elseif($value_1['description'] == $value_2['description'])
    //                     {
    //                         array_push($exist_before, $tb_key);

    //                         $this->db->where('id', $value_1['id']);
    //                         $result = $this->db->update('fs_categorized_account', array('value' => $value_2['value']));
    //                     }
    //                     // else
    //                     // {
    //                     //     array_push($delete_categorized_account, $value_1['id']);
    //                     // }
    //                 }
    //             }
    //         }

    //         // remove existed list
    //         foreach ($exist_before as $eb_key => $eb_value) 
    //         {
    //             unset($trial_balance_d[$eb_value]);
    //         }

    //         // if(count($delete_categorized_account) > 0)  // delete
    //         // {
    //         //     $this->db->where_in('id', $delete_categorized_account);
    //         //     $this->db->delete('fs_categorized_account');
    //         // }

    //         /* insert uncategorized list */
            

    //         foreach ($trial_balance_d as $key => $value) 
    //         {
    //             array_push($uncategorized_list_new, 
    //                 array(
    //                     'fs_company_info_id' => $value['fs_company_info_id'],
    //                     'description'        => $value['description'],
    //                     'value'              => $value['value'],
    //                     // 'company_end_prev_ye_value' => 0.00,
    //                     'order_by'           => $key + 1
    //                 )
    //             );
    //         }

    //         // print_r($uncategorized_list_new);

    //         $uncategorized_list_db = $this->db->query("SELECT * FROM fs_uncategorized_account WHERE fs_company_info_id=" . $fs_company_info_id);
    //         $uncategorized_list_db = $uncategorized_list_db->result_array();

    //         foreach ($uncategorized_list_new as $ucn_key => $ucn_value) 
    //         {
    //             $matched = false;

    //             foreach ($uncategorized_list_db as $uc_key => $uc_value) 
    //             {
    //                 if($uc_value['description'] == $ucn_value['description'])
    //                 {
    //                     $this->db->where('id', $uc_value['id']);
    //                     $result = $this->db->update('fs_uncategorized_account', array('value' => $ucn_value['value']));

    //                     $matched = true;
    //                 }
    //             }

    //             if(!$matched)
    //             {
    //                 $this->db->insert('fs_uncategorized_account', $ucn_value);
    //             }
    //         }

    //         // $this->db->insert_batch('fs_uncategorized_account', $uncategorized_list_new);
    //         /* END OF insert uncategorized list */
    //     }

    //     return json_encode($trial_balance_d);
    // }

    // public function insert_batch_LY_trial_balance($trial_balance, $fs_company_info_id)
    // {
    //     // delete previous record if exist
    //     $this->db->where_in('fs_company_info_id', $fs_company_info_id);
    //     $this->db->delete('fs_ly_trial_balance');

    //     // insert new data
    //     $this->db->insert_batch('fs_ly_trial_balance', $trial_balance);

    //     // last year trial balance list
    //     $ly_trial_balance_d = $this->db->query("SELECT * FROM fs_ly_trial_balance WHERE fs_company_info_id=" . $fs_company_info_id);

    //     if($ly_trial_balance_d->num_rows() > 0)
    //     {
    //         $ly_trial_balance_d = $ly_trial_balance_d->result_array();
    //     }

    //     // categorized list
    //     $categorized = $this->db->query("SELECT * FROM fs_categorized_account WHERE fs_company_info_id=" . $fs_company_info_id);

    //     if($categorized->num_rows() > 0)
    //     {
    //         $categorized = $categorized->result_array();

    //         $same_desc_list = [];
    //         $for_encategorized = [];

    //         foreach($categorized as $key => $value_1)
    //         {
    //             if($value_1['type'] == "Leaf")
    //             {
    //                 foreach ($ly_trial_balance_d as $tb_key => $value_2) 
    //                 {
    //                     if($value_1['description'] == $value_2['description'])
    //                     {
    //                         $this->db->where('id', $value_1['id']);
    //                         $result = $this->db->update('fs_categorized_account', array('company_end_prev_ye_value' => $value_2['value']));

    //                         array_push($same_desc_list, $tb_key);
    //                     }
    //                 }
    //             }
    //         }

    //         // remove existed list
    //         foreach ($same_desc_list as $eb_key => $eb_value) 
    //         {
    //             unset($ly_trial_balance_d[$eb_value]);
    //         }

    //         /* insert uncategorized list */
    //         // trial balance list
    //         $uncategorized_list = $this->db->query("SELECT * FROM  fs_uncategorized_account WHERE fs_company_info_id=" . $fs_company_info_id);
    //         $uncategorized_list = $uncategorized_list->result_array();

    //         /* insert uncategorized list */
    //         $uncategorized_desc_list = array_column($uncategorized_list, 'description');
    //         $order_by = count($uncategorized_list);

    //         foreach ($ly_trial_balance_d as $ly_key => $ly_value) 
    //         {
    //             if(in_array($ly_value['description'], $uncategorized_desc_list))
    //             {
    //                 $udl_key = array_search($ly_value['description'], $uncategorized_desc_list);

    //                 $this->db->where('id', $uncategorized_list[$udl_key]['id']);
    //                 $result = $this->db->update('fs_uncategorized_account', array('company_end_prev_ye_value' => $ly_value['value']));
    //             }
    //             else
    //             {
    //                 $order_by++;

    //                 $result = $this->db->insert('fs_uncategorized_account', 
    //                                         array(
    //                                             'fs_company_info_id' => $ly_value['fs_company_info_id'],
    //                                             'description'        => $ly_value['description'],
    //                                             'company_end_prev_ye_value' => $ly_value['value'],
    //                                             'order_by'           => $order_by
    //                                         ));
    //             }
    //         }
    //         /* END OF insert uncategorized list */
    //     }

    //     return $result;
    // }

    public function insert_categorized_account_round_off($fs_company_info_id) 
    {
        $fs_categorized_account = $this->db->query("SELECT * FROM audit_categorized_account fca WHERE fca.fs_company_info_id = " . $fs_company_info_id);
        $fs_categorized_account = $fs_categorized_account->result_array();

        $fs_db_categorized_account_round_off = $this->db->query("SELECT * FROM fs_categorized_account_round_off fca WHERE fca.fs_company_info_id = " . $fs_company_info_id);
        $fs_db_categorized_account_round_off = $fs_db_categorized_account_round_off->result_array();
        $delete_fcaro_ids = [];

        foreach ($fs_db_categorized_account_round_off as $fcaro_key => $fcaro_value) 
        {   
            if(empty(in_array($fcaro_value['fs_categorized_account_id'], array_column($fs_categorized_account, 'id'))))
            {
                array_push($delete_fcaro_ids, $fcaro_value['id']);
            }
        }

        if(count($delete_fcaro_ids) > 0)
        {
            $this->db->where_in('id', $delete_fcaro_ids);
            $this->db->delete('fs_categorized_account_round_off');

            $this->db->where_in('fs_categorized_account_round_off_id', $delete_fcaro_ids);
            $this->db->delete('fs_note_details');
        }

        $fs_categorized_account_round_off = [];

        // print_r($data);

        foreach ($fs_categorized_account as $key => $value) 
        {
            $temp_array = [];

            $temp_array = array(
                'fs_categorized_account_id' => $value['id'],
                'fs_company_info_id'        => $fs_company_info_id,
                'description'               => $value['description'],
                // 'account_code'              => $value['account_code'],
                // 'parent'                    => $value['parent'],
                // 'type'                      => $value['type'],
                // 'order_by'                  => $value['order_by'],
                'value'                     => $value['adjusted_value'],
                'company_end_prev_ye_value' => $value['company_end_prev_ye_value'],
                'company_beg_prev_ye_value' => $value['company_beg_prev_ye_value'],
                // 'group_end_this_ye_value'   => $value['group_end_this_ye_value'],
                // 'group_end_prev_ye_value'   => $value['group_end_prev_ye_value'],
                // 'group_beg_prev_ye_value'   => $value['group_beg_prev_ye_value'],
                'is_deleted'                => 0
            );

            $q = $this->db->query("SELECT * 
                                    FROM fs_categorized_account_round_off fca_ro 
                                    WHERE fca_ro.fs_categorized_account_id =" . $value['id'] . " AND fca_ro.fs_company_info_id=" . $fs_company_info_id . " AND fca_ro.is_deleted = 0");
            $q = $q->result_array();

            if(count($q) > 0)
            {
                $this->db->where('id', $q[0]['id']);
                $result = $this->db->update('fs_categorized_account_round_off', $temp_array);
            }
            else
            {
                $result = $this->db->insert('fs_categorized_account_round_off', $temp_array);
            }
        }

        // return $result;
    }

    // public function insert_categorized_account($data, $fs_company_info_id)
    // {
    //     // // delete previous record if exist
    //     // $this->db->where_in('fs_company_info_id', $fs_company_info_id);
    //     // $this->db->delete('fs_categorized_account');

    //     // $categorized_account = [];

    //     // foreach ($data as $key => $value) {
    //     //     $account_code = '';

    //     //     if($value['type'] == "Branch")
    //     //     {
    //     //         $account_code = $value['id'];
    //     //     }

    //     //     array_push($categorized_account,
    //     //         array(
    //     //             'fs_company_info_id' => $fs_company_info_id,
    //     //             'description'        => $value['text'],
    //     //             'value'              => $value['data']['value'],
    //     //             'account_code'       => $account_code,
    //     //             'parent'             => $value['parent'],
    //     //             'type'               => $value['type'],
    //     //             'order_by'           => $key + 1
    //     //         )
    //     //     );
    //     // }

    //     // // insert new data
    //     // if(count($data) > 0)
    //     // {
    //     //     $result = $this->db->insert_batch('fs_categorized_account', $categorized_account);
    //     // }

    //     // return $result;

    //     // print_r($data);


    //     /* ----- deleted account ----- */
    //     $fs_categorized_account = $this->db->query("SELECT * FROM fs_categorized_account fca WHERE fca.fs_company_info_id = " . $fs_company_info_id);
    //     $ori_fs_categorized_account = $fs_categorized_account->result_array();

    //     $deleted_fca_id = [];

    //     foreach ($ori_fs_categorized_account as $ori_fca_key => $ori_fca_value) 
    //     {
    //         $temp_fca_id = $ori_fca_value['id'];

    //         foreach ($data as $key => $value) 
    //         {
    //             if($ori_fca_value['id'] == $value['data']['id'])
    //             {
    //                 $temp_fca_id = "";
    //             }
    //         }

    //         if(!empty($temp_fca_id))
    //         {
    //             array_push($deleted_fca_id, $temp_fca_id);
    //         }
    //     }

    //     if(count($deleted_fca_id) > 0)
    //     {
    //         $this->db->where_in('id', $deleted_fca_id);
    //         $result = $this->db->delete('fs_categorized_account');
    //     }
    //     /* ----- deleted account ----- */

    //     $fs_categorized_account_ids = [];

    //     $temp_data = $data;

    //     // print_r($temp_data);

    //     foreach ($data as $key => $value) 
    //     {
    //         $account_code = '';
    //         $fs_default_acc_category_id = 0;

    //         if($value['type'] == "Branch")
    //         {
    //             $account_code = $value['data']['account_code'];

    //             if(is_null($account_code))
    //             {
    //                 $account_code = '';
    //             }
    //         }

    //         if(!empty($value['data']['fs_default_acc_category_id']))
    //         {
    //             $fs_default_acc_category_id = $value['data']['fs_default_acc_category_id'];
    //         }

    //         $value_this_year = str_replace(',', '', $value['data']['value']);
    //         $value_this_year = str_replace('(', '-', $value_this_year);
    //         $value_this_year = str_replace(')', '', $value_this_year);

    //         $value_last_year = str_replace(',', '', $value['data']['company_end_prev_ye_value']);
    //         $value_last_year = str_replace('(', '-', $value_last_year);
    //         $value_last_year = str_replace(')', '', $value_last_year);

    //         $categorized_account = array(
    //                 'fs_company_info_id' => $fs_company_info_id,
    //                 'description'        => $value['text'],
    //                 'fs_default_acc_category_id' => $fs_default_acc_category_id,
    //                 'value'              => $value_this_year,
    //                 'company_end_prev_ye_value'=> $value_last_year,
    //                 'account_code'       => $account_code,
    //                 'parent'             => $temp_data[$key]['parent'],
    //                 'type'               => $value['type'],
    //                 'order_by'           => $key + 1
    //             );

    //         // print_r($categorized_account);

    //         if(empty($value['data']['id']) || $value['data']['id'] == 0)
    //         {
    //             $result = $this->db->insert('fs_categorized_account', $categorized_account);

    //             $this_category_id = $this->db->insert_id();
                 
    //             foreach ($temp_data as $key_2 => $value_2)
    //             {
    //                 // print_r(array($value_2['parent'], $value['id']));
    //                 if($value_2['parent'] == $value['id'])
    //                 {
    //                     $temp_data[$key_2]['parent'] = $this_category_id;
    //                 }
    //             }

    //             array_push($fs_categorized_account_ids, $this_category_id);
    //         }
    //         else
    //         {
    //             $this->db->where('id', $value['data']['id']);
    //             $result = $this->db->update('fs_categorized_account', $categorized_account);

    //             foreach ($temp_data as $key_2 => $value_2)
    //             {
    //                 // print_r(array($value_2['parent'], $value['id']));
    //                 if($value_2['parent'] == $value['id'])
    //                 {
    //                     $temp_data[$key_2]['parent'] = $value['data']['id'];
    //                 }
    //             }

    //             array_push($fs_categorized_account_ids, $value['data']['id']);
    //         }
    //     }
        
    //     return json_encode(array("result" => $result, "fs_categorized_account_ids" => $fs_categorized_account_ids));
    // }

    // public function insert_uncategorized_account($data, $fs_company_info_id)
    // {
    //     // print_r($data);
    //     // delete previous record if exist
    //     $this->db->where_in('fs_company_info_id', $fs_company_info_id);
    //     $this->db->delete(' fs_uncategorized_account');

    //     // $uncategorized_account = [];

    //     if(count($data) > 0)
    //     {
    //         foreach ($data as $key => $value) 
    //         {
    //             $account_code = '';

    //             if($value['type'] == "Branch")
    //             {
    //                 $account_code = $value['id'];
    //             }

    //             $value_this_year = str_replace(',', '', $value['data']['value']);
    //             $value_this_year = str_replace('(', '-', $value_this_year);
    //             $value_this_year = str_replace(')', '', $value_this_year);

    //             $value_last_year = str_replace(',', '', $value['data']['company_end_prev_ye_value']);
    //             $value_last_year = str_replace('(', '-', $value_last_year);
    //             $value_last_year = str_replace(')', '', $value_last_year);

    //             $uncategorized_account = array(
    //                     'fs_company_info_id'        => $fs_company_info_id,
    //                     'description'               => $value['text'],
    //                     'value'                     => $value_this_year,
    //                     'company_end_prev_ye_value' => $value_last_year,
    //                     'order_by'                  => $key + 1
    //                 );

    //             // print_r($uncategorized_account);

    //             $result = $this->db->insert('fs_uncategorized_account', $uncategorized_account);

    //             // array_push($uncategorized_account,
    //             //     array(
    //             //         'fs_company_info_id' => $fs_company_info_id,
    //             //         'description'        => $value['text'],
    //             //         'value'              => $value['data']['value'],
    //             //         'order_by'           => $key + 1
    //             //     )
    //             // );   

    //             if(!$result)
    //             {
    //                 return json_encode(array("result" => $result));
    //             }
    //         }
    //     }
    //     else
    //     {
    //         return json_encode(array("result" => true));
    //     }

    //     // // insert new data
    //     // if(count($data) > 0)
    //     // {
    //     //     $result = $this->db->insert_batch('fs_uncategorized_account', $uncategorized_account);
    //     // }
    //     // else
    //     // {
    //     //     $result = true;
    //     // }
        
    //     return json_encode(array("result" => $result));
    // }

    public function update_categorzied_account($data)
    {
        foreach ($data as $key => $value) 
        {
            $this->db->where('id', $value['id']);
            $result = $this->db->update('audit_categorized_account', $value);

            if(!$result)
            {
                return 0;
            }
        }
        
        return 1;
    }

    public function update_categorzied_account_batch($data)
    {
        foreach ($data as $key => $value) 
        {
            $this->db->where('id', $value['id']);
            $result = $this->db->update('audit_categorized_account', $value['info']);

            if(!$result)
            {
                return 0;
            }
        }
        
        return 1;
    }

    public function update_categorized_account_round_off_batch($data)
    {
        // print_r($data[0]);
        $ids = array_column($data[0], 'id');

        foreach ($data[0] as $key => $value)    // remove id object 
        {
            unset($data[0][$key]['id']);
        }

        $temp_data = [];

        foreach ($data[0] as $key => $value) 
        {
            array_push($temp_data, 
                array(
                    'id'    => $ids[$key],
                    'info'  => $value 
                )
            );
        }

        foreach ($temp_data as $temp_key => $temp_value) 
        {
            $this->db->where('id', $temp_value['id']);
            $result = $this->db->update('fs_categorized_account_round_off', $temp_value['info']);

            if(!$result)
            {
                return 0;
            }
        }
        
        return 1;
    }

    // general function 
    public function negative_bracket($number)   // insert negative brackets
    {
        if($number < 0)
        {
            return "(" . number_format(abs($number), 2) . ")";
        }
        else
        {
            if($number == '')
            {
                $number = 0;
            }

            return number_format($number, 2);
        }
    }

    public function fs_adjust_round_off($fs_company_info_id)
    {
        $data = $this->db->query("SELECT fcaro.id, fcaro.value, fca.adjusted_value, fca.company_end_prev_ye_value
                                    FROM fs_categorized_account_round_off fcaro
                                    LEFT JOIN audit_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
                                    WHERE fcaro.fs_company_info_id=" . $fs_company_info_id . " AND fca.type = 'Leaf'
                                    ORDER BY fca.order_by");
        $data = $data->result_array();

        $ty_value = 0;
        $ly_value = 0;

        $update_data = [];

        // simple round off values
        foreach ($data as $key => $value) 
        {
            $data[$key]['value']                     = round($value['adjusted_value']); // current year values
            $data[$key]['company_end_prev_ye_value'] = round($value['company_end_prev_ye_value']); // last year values

            $ty_value += (float)$data[$key]['value'];
            $ly_value += (float)$data[$key]['company_end_prev_ye_value'];

            unset($data[$key]['adjusted_value']);
        }

        // get round off node and put values
        $ro_data = $this->db->query("SELECT fcaro.id, fcaro.value, fca.adjusted_value, fca.company_end_prev_ye_value
                                    FROM fs_categorized_account_round_off fcaro
                                    LEFT JOIN audit_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
                                    WHERE fcaro.fs_company_info_id=" . $fs_company_info_id . " AND fca.type = 'Round_off'
                                    ORDER BY fca.order_by");
        $ro_data = $ro_data->result_array();

        if(count($ro_data) > 0)
        {
            $ro_data[0]['value'] = $ty_value * -1; // change sign
            $ro_data[0]['company_end_prev_ye_value'] = $ly_value * -1; // change sign

            unset($ro_data[0]['adjusted_value']);

            array_push($data, $ro_data[0]);
        }

        $update_result = $this->update_categorized_account_round_off_batch(array($data)); 
    }

    // public function child_recursive($data, $collected_array)
    // {   
    //     $temp = [];

    //     foreach ($data as $key => $value) 
    //     {
    //         if(!empty($value['parent_array']))
    //         {
    //             $collected_array = $this->child_recursive($value['child_array'], $collected_array);
    //         }
    //         else
    //         {
    //             array_push($collected_array, $value['child_array']);
    //         }
    //     }

    //     return $collected_array;
    // }

    // public function adjust_child_lowest_highest_decimal($data, $differences, $col_name) // adjust lowest or highest decimal to perform round up or round down
    // {
    //     if(!(round($differences) == 0 || round($differences) == -0))
    //     {
    //         if(round($differences) < 0)     // if differences is less than 0, need to round up
    //         {
    //             for($x = (int)$differences; $x < 0; $x++)
    //             {
    //                 $largest_gap   = 0;
    //                 $located_key_ty = null;

    //                 // take child lowest decimal value.
    //                 foreach ($data[0] as $key => $value) 
    //                 {
    //                     $gap        = null;

    //                     $whole      = floor($value[$col_name]);
    //                     $decimal    = $value[$col_name] - $whole;
    //                     $decimal    = number_format($decimal, 2);

    //                     if($value[$col_name] < 0)
    //                     {
    //                         $decimal = 1 - $decimal;
    //                     }

    //                     if($value[$col_name] > 0 && $decimal < 0.5)   // positive
    //                     {
    //                         $gap = $decimal;
    //                     }
    //                     elseif($value[$col_name] < 0 && $decimal >= 0.5)   // negative
    //                     {
    //                         $gap = 1 - $decimal;
    //                     }

    //                     if(!is_null($gap) && $gap > $largest_gap)
    //                     {
    //                         $located_key_ty = $key;
    //                         $largest_gap   = $gap;
    //                     }
    //                 }

    //                 if(!is_null($located_key_ty)) // if got nearest decimal to 0.5, round up
    //                 {
    //                     // round up highest decimal value.
    //                     $data[0][$located_key_ty][$col_name] = ceil($data[0][$located_key_ty][$col_name]);
    //                 }
    //                 /* -------- round down the value if the list does not have decimal with lower than 0.5 -------- */
    //                 else // for all values does not have decimal lower than 0.5, round down
    //                 {
    //                     $smallest_gap      = 1;
    //                     $located_key_ty_rd = null;

    //                     foreach ($data[0] as $key => $value) 
    //                     {   
    //                         $round_down_gap = null;

    //                         $whole      = floor($value[$col_name]);
    //                         $decimal    = $value[$col_name] - $whole;
    //                         $decimal    = number_format($decimal, 2);

    //                         if($value[$col_name] < 0)
    //                         {
    //                             $decimal = 1 - $decimal;
    //                         }

    //                         if($value[$col_name] > 0 && $decimal >= 0.5)   // positive
    //                         {
    //                             $round_down_gap = $decimal - 0.5;
    //                         }
    //                         elseif($value[$col_name] < 0 && $decimal < 0.5)   // negative
    //                         {
    //                             $round_down_gap = 0.5 - $decimal;
    //                         }
    //                     }

    //                     if(!is_null($round_down_gap) && $round_down_gap < $smallest_gap)
    //                     {
    //                         $located_key_ty_rd = $key;
    //                         $smallest_gap   = $round_down_gap;
    //                     }

    //                     if(!is_null($located_key_ty_rd)) // if got nearest decimal to 0.5, round up
    //                     {
    //                         // round down value > 0.5 and nearest to 0.5
    //                         $data[0][$located_key_ty_rd][$col_name] = floor($data[0][$located_key_ty_rd][$col_name]);
    //                     }
    //                 }
    //                 /* -------- END OF round down the value if the list does not have decimal with lower than 0.5 -------- */
    //             }
    //         }
    //         elseif(round($differences) > 0)     // if differences is more than 0
    //         {
    //             for($y = $differences; $y > 0; $y--)
    //             {
    //                 $located_key_ty = null;
    //                 $smallest_gap   = 1;

    //                 // take child highest decimal value.
    //                 foreach ($data[0] as $key1 => $value) 
    //                 {
    //                     $gap = null;

    //                     $whole      = floor($value[$col_name]);
    //                     $decimal    = $value[$col_name] - ($whole);
    //                     $decimal    = number_format($decimal, 2);

    //                     if($value[$col_name] < 0)
    //                     {
    //                         $decimal = 1 - $decimal;
    //                     }

    //                     if($value[$col_name] > 0 && $decimal >= 0.5)   // positive
    //                     {
    //                         $gap = $decimal - 0.5;
    //                     }
    //                     elseif($value[$col_name] < 0 && $decimal < 0.5)   // negative
    //                     {
    //                         $gap = $decimal;
    //                     }

    //                     if(!is_null($gap) && $gap < $smallest_gap)
    //                     {
    //                         $located_key_ty = $key1;
    //                         $smallest_gap   = $gap;
    //                     }
    //                 }

    //                 if(!is_null($located_key_ty))
    //                 {
    //                     // round down lowest decimal value.
    //                     $data[0][$located_key_ty][$col_name] = floor($data[0][$located_key_ty][$col_name]);
    //                 }
    //                 /* -------- round up the value if the list does not have decimal > 0.5 -------- */
    //                 else
    //                 {
    //                     // print_r(array("hello"));

    //                     $largest_gap = 0;
    //                     $located_key_ty_ru = null;

    //                     foreach ($data[0] as $key1 => $value) 
    //                     {   
    //                         $round_up_gap = null;

    //                         $whole      = floor($value[$col_name]);
    //                         $decimal    = $value[$col_name] - $whole;
    //                         $decimal    = number_format($decimal, 2);

    //                         if($value[$col_name] < 0)
    //                         {
    //                             $decimal = 1 - $decimal;
    //                         }

    //                         if($value[$col_name] > 0 && $decimal < 0.5)   // positive
    //                         {
    //                             $round_up_gap = $decimal;
    //                         }
    //                         elseif($value[$col_name] < 0 && $decimal >= 0.5)   // negative
    //                         {
    //                             $round_up_gap = $decimal - 0.5;
    //                         }
    //                     }

    //                     if(!is_null($round_up_gap) && $round_up_gap > $largest_gap)
    //                     {
    //                         $located_key_ty_ru  = $key1;
    //                         $largest_gap        = $round_up_gap;
    //                     }

    //                     if(!is_null($located_key_ty_ru)) // if got nearest decimal to 0.5, round up
    //                     {
    //                         // round down value > 0.5 and nearest to 0.5
    //                         $data[0][$located_key_ty_ru][$col_name] = ceil($data[0][$located_key_ty_ru][$col_name]);
    //                     }
    //                 }
    //                 /* -------- END OF round down the value if the list does not have decimal with lower than 0.5 -------- */
    //             }
    //         }   
    //     }

    //     return $data;
    // }

    // public function round_off_all_child($expenses_children_list)    // round off all other child account
    // {
    //     foreach ($expenses_children_list[0] as $key => $value) 
    //     {
    //         $expenses_children_list[0][$key]['value']                       = round($value['value']);
    //         $expenses_children_list[0][$key]['company_end_prev_ye_value']   = round($value['company_end_prev_ye_value']);
    //         $expenses_children_list[0][$key]['group_end_this_ye_value']     = round($value['group_end_this_ye_value']);
    //         $expenses_children_list[0][$key]['group_end_prev_ye_value']     = round($value['group_end_prev_ye_value']);
    //         // print_r(array(round($value['value'])));
    //     }

    //     return $expenses_children_list;
    // }

    public function update_next_ye_values($fs_company_info_id)
    {
        $fs_company_info_next_year_id = $this->fs_model->get_fs_company_info_next_year($fs_company_info_id);

        if(!empty($fs_company_info_next_year_id))
        {
            $fca_ty = $this->db->query("SELECT * FROM audit_categorized_account WHERE fs_company_info_id = " . $fs_company_info_id . " ORDER BY order_by");
            $fca_ty = $fca_ty->result_array();

            $fca_ny = $this->db->query("SELECT * FROM audit_categorized_account WHERE fs_company_info_id = " . $fs_company_info_next_year_id . " ORDER BY order_by");
            $fca_ny = $fca_ny->result_array();

            $fca_ny_desc = [];

            if(count($fca_ny))
            {
                // keey all description with type "Leaf" in an array.
                foreach ($fca_ny as $tkey => $tvalue) 
                {
                    if($tvalue['type'] != 'Branch')
                    {
                        array_push($fca_ny_desc, $tvalue['description']);
                    }
                    else
                    {
                        array_push($fca_ny_desc, "");
                    }
                }

                // update company_end_prev_ye_value in next year end report
                foreach ($fca_ty as $key => $value) 
                {
                    if($value['type'] != 'Branch' && !empty($value['description']))
                    {
                        if(in_array($value['description'], $fca_ny_desc))
                        {
                            $fca_ny_key = array_search($value['description'], $fca_ny_desc);

                            $this->db->where('id', $fca_ny[$fca_ny_key]['id']);
                            $result = $this->db->update('audit_categorized_account', array('company_end_prev_ye_value' => $value['value']));

                            // update round off table for next year
                            $fcaro_ty = $this->db->query("SELECT * FROM fs_categorized_account_round_off WHERE fs_categorized_account_id=" . $value['id'] . " AND is_deleted = 0");
                            $fcaro_ty = $fcaro_ty->result_array();

                            $fcaro_ny = $this->db->query("SELECT * FROM fs_categorized_account_round_off WHERE fs_categorized_account_id=" . $fca_ny[$fca_ny_key]['id'] . " AND is_deleted = 0");
                            $fcaro_ny = $fcaro_ny->result_array();

                            $this->db->where('id', $fcaro_ny[0]['id']);
                            $result = $this->db->update('fs_categorized_account_round_off', array('company_end_prev_ye_value' => $fcaro_ty[0]['value']));
                        }
                    }
                }
            }
        }
        else
        {
            return true;
        }
    }
}
