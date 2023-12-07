<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            
                $this->session->set_userdata('requested_page', $this->uri->uri_string());
                redirect('auth/login');

        }
        
        
        $this->load->library(array('session','parser'));
        $this->load->model('setting_model');
        $this->load->model('leave/leave_model');
        $this->load->model('caf/caf_model');
    }

    public function index()
    {   
        // $this->meta['page_name'] = 'Setting';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        // $this->data['holiday_list'] = $this->setting_model->get_holiday_list();
        // $this->data['type_of_leave_list'] = $this->setting_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->setting_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->setting_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->setting_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->setting_model->get_choose_carry_forward_list();
        $this->data['programme_list'] = $this->setting_model->get_default_programme_list();
        $this->data['archived_programme_list'] = $this->setting_model->get_archived_programme_list();

        $this->data['programme_type_dropdown'] = $this->setting_model->get_programme_type_dropdown();

        $Admin = $this->sma->in_group('admin') ? TRUE : NULL;
        $Manager = $this->sma->in_group('manager') ? TRUE : NULL;

        $bc = array(array('link' => '#', 'page' => 'Setting'));
        $meta = array('page_title' => 'Setting', 'bc' => $bc, 'page_name' => 'Setting');

        if($Admin || $Manager)
        {
            $this->page_construct('index.php',  $meta, $this->data);
        }
        else
        {
            redirect('welcome');
        }
        
    }

    public function add_audit_programme($id = null)
    {
        
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Setting', base_url('setting'));

        $this->data['editing_flag'] = FALSE;
        
        // $names = array_combine($names, $names);
        if($id != "")
        {
            $this->data['edit_programme'] = $this->setting_model->get_edit_programme($id);
            $this->data['objective_lines'] = $this->setting_model->get_objective_lines($id);
            $this->data['procedure_lines'] = $this->setting_model->get_procedure_lines($id);
            $this->data['editing_flag'] = TRUE;

            $this->mybreadcrumb->add('Edit Audit Programme - '. $this->data['edit_programme']->programme_index, base_url());
        }
        else
        {
             $this->mybreadcrumb->add('Add Audit Programme', base_url());
        }


        

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        // print_r($this->data['objective_lines']);
        $this->data['alphas_dropdown'] = $this->setting_model->get_avail_alpha_index($id);
        //sub type for default programme
        $this->data['programme_type_dropdown'] = $this->setting_model->get_default_programme_type();
        $this->data['ra_factors'] = $this->setting_model->get_ra_factors();

        // print_r($this->data['ra_factors']);

        $Admin = $this->sma->in_group('admin') ? TRUE : NULL;
        $Manager = $this->sma->in_group('manager') ? TRUE : NULL;

        $bc = array(array('link' => '#', 'page' => 'Add Programme'));
        $meta = array('page_title' => 'Add Programme', 'bc' => $bc, 'page_name' => 'Add Audit Programme');

        if($Admin || $Manager)
        {
            $this->page_construct('audit_programme.php', $meta, $this->data);
        }
        else
        {
            redirect('welcome');
        }
    }

    public function add_audit_programme_yn($id = null)
    {
        
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Setting', base_url('setting'));

        $this->data['editing_flag'] = FALSE;
        
        // $names = array_combine($names, $names);
        if($id != "")
        {
            $this->data['edit_programme'] = $this->setting_model->get_edit_programme($id);
            $this->data['objective_lines'] = $this->setting_model->get_objective_lines($id);
            $this->data['procedure_lines'] = $this->setting_model->get_procedure_lines($id);
            $this->data['editing_flag'] = TRUE;

            $this->mybreadcrumb->add('Edit Audit Programme - '. $this->data['edit_programme']->programme_index, base_url());
        }
        else
        {
             $this->mybreadcrumb->add('Add Audit Programme - (Y/N Comment)', base_url());
        }


        

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        // print_r($this->data['objective_lines']);
        $this->data['alphas_dropdown'] = $this->setting_model->get_avail_alpha_index($id);
        // $this->data['programme_type_dropdown'] = $this->setting_model->get_programme_type();
        // $this->data['ra_factors'] = $this->setting_model->get_ra_factors();

        // print_r($this->data['ra_factors']);

        $Admin = $this->sma->in_group('admin') ? TRUE : NULL;
        $Manager = $this->sma->in_group('manager') ? TRUE : NULL;

        $bc = array(array('link' => '#', 'page' => 'Add Programme'));
        $meta = array('page_title' => 'Add Programme', 'bc' => $bc, 'page_name' => 'Add Audit Programme');

        if($Admin || $Manager)
        {
            $this->page_construct('audit_programme_yn.php', $meta, $this->data);
        }
        else
        {
            redirect('welcome');
        }
    }

    public function add_audit_programme_qa($id = null)
    {
        
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Setting', base_url('setting'));

        $this->data['editing_flag'] = FALSE;
        
        // $names = array_combine($names, $names);
        if($id != "")
        {
            $this->data['edit_programme'] = $this->setting_model->get_edit_programme($id);
            $this->data['question_lines'] = $this->setting_model->get_question_lines($id);
            $this->data['editing_flag'] = TRUE;

            $this->mybreadcrumb->add('Edit Audit Programme - '. $this->data['edit_programme']->programme_index, base_url());
        }
        else
        {
             $this->mybreadcrumb->add('Add Audit Programme - (Q & A)', base_url());
        }


        

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        // print_r($this->data['objective_lines']);
        $this->data['alphas_dropdown'] = $this->setting_model->get_avail_alpha_index($id);
        // $this->data['programme_type_dropdown'] = $this->setting_model->get_programme_type();
        // $this->data['ra_factors'] = $this->setting_model->get_ra_factors();

        // print_r($this->data['ra_factors']);

        $Admin = $this->sma->in_group('admin') ? TRUE : NULL;
        $Manager = $this->sma->in_group('manager') ? TRUE : NULL;

        $bc = array(array('link' => '#', 'page' => 'Add Programme'));
        $meta = array('page_title' => 'Add Programme', 'bc' => $bc, 'page_name' => 'Add Audit Programme');

        if($Admin || $Manager)
        {
            $this->page_construct('audit_programme_qa.php', $meta, $this->data);
        }
        else
        {
            redirect('welcome');
        }
    }

    public function add_audit_programme_only_master($programme_type_id, $id = null)
    {
        
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Setting', base_url('setting'));

        $this->data['editing_flag'] = FALSE;
        $this->data['programme_type'] = $programme_type_id;
        $programme_type = $this->setting_model->get_programme_type($programme_type_id)['type_name'];

        // if($programme_type_id == 4)
        // {
        //     $programme_type = "Meeting";
        // }
        // if($programme_type_id == 5)
        // {
        //     $programme_type = "Meeting";
        // }

        
        // $names = array_combine($names, $names);
        if($id != "")
        {
            $this->data['edit_programme'] = $this->setting_model->get_edit_programme($id);
            $this->data['editing_flag'] = TRUE;

            $this->mybreadcrumb->add('Edit Audit Programme - '. $this->data['edit_programme']->programme_index, base_url());
        }
        else
        {
             $this->mybreadcrumb->add('Add Audit Programme - ('.$programme_type.')', base_url());
        }


        

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        // print_r($this->data['objective_lines']);
        $this->data['alphas_dropdown'] = $this->setting_model->get_avail_alpha_index($id);
        // $this->data['programme_type_dropdown'] = $this->setting_model->get_programme_type();
        // $this->data['ra_factors'] = $this->setting_model->get_ra_factors();

        // print_r($this->data['ra_factors']);

        $Admin = $this->sma->in_group('admin') ? TRUE : NULL;
        $Manager = $this->sma->in_group('manager') ? TRUE : NULL;

        $bc = array(array('link' => '#', 'page' => 'Add Programme'));
        $meta = array('page_title' => 'Add Programme', 'bc' => $bc, 'page_name' => 'Add Audit Programme');

        if($Admin || $Manager)
        {
            $this->page_construct('audit_programme_only_master.php', $meta, $this->data);
        }
        else
        {
            redirect('welcome');
        }
    }


    public function contentAllData($programme_id=null)
    {
        $all_list = $this->setting_model->get_all_content_tree($programme_id);

        echo json_encode($all_list);
        // print_r($categorized_acc_list);
    }

    public static function getAssertionDropdown() 
    {
        $ci =& get_instance();
        $form_data = $this->input->get();

        if($form_data['programme_type'] == 1)
        {
            $programme_type = "P/L";
        }
        else if($form_data['programme_type'] == 2)
        {
            $programme_type = "BS";
        }
        else if($form_data['programme_type'] == 3)
        {
            $programme_type = "Neither";
        }
        // $this->db->select('client.id, client.company_code, client.company_name');
        // $this->db->from('client');
        // // $this->db->join('audit_client', 'audit_client.company_code = client.company_code', 'left');
        // $this->db->join('client_billing_info', 'client_billing_info.company_code = client.company_code', 'right');
        // $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service AND our_service_info.service_type = 1', 'right');
        // // $query = $this->db->get();

        $query = 'SELECT `id`,`text_value` FROM `audit_programme_dropdown` WHERE `type` LIKE "%'.$programme_type.'%" ';

        // echo $query;

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("Dropdown element not found not found.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['id'] != null)
                {
                    $res[$row['id']] = $row['text_value'];
                }
              
            }
            //$res = json_decode($res);
            $ci =& get_instance();
            // $selected_client_name = $ci->session->userdata('auth_company_code');
            // $ci->session->unset_userdata('auth_company_code');

            // print_r($res);

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Dropdown element fetched successfully.", 'result'=>$res);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_programme_type'=>'');

            echo json_encode($data);
        }
    }

    public function save_all_programme_setting()
    {
        $form_data = $this->input->post();
        // print_r($form_data);
        //save audit programme master
         $data = array(
            'id'              => $form_data['master_id'],
            'programme_index' => $form_data['index_alpha'],
            'type'       => $form_data['programme_type'],
            'last_updated' => $this->session->userdata("user_id"),
            'title'      => $form_data['programme_title'],
            'programme_type' => 1
        );

        $arr_deleted_objtv  = isset($form_data['arr_deleted_objtv'])?$form_data['arr_deleted_objtv']:array();
        $programme_master_id = $this->setting_model->submit_audit_programme_info($data);

        if(!isset($form_data['programme_assertion']))
        {
            $form_data['programme_assertion'] = array();
        }
        /*------Objectives line------*/
        $objective_line_data_arr = $this->build_objective_line_arr($programme_master_id, $form_data['objective_id'], $form_data['objective_text'], $form_data['programme_assertion']);

        foreach ($objective_line_data_arr as $key => $each_line) {
            //need
            $result = $this->setting_model->insert_audit_programme_objectives($each_line);
            
        }

        $this->setting_model->delete_audit_objective($arr_deleted_objtv);
        /*------End of objectives line-----*/

        /*-----Save ra_factor-----*/
        $ra_factor_data_arr = $this->build_ra_factor_arr($form_data['cell_id'], $form_data['cell_value']);

        // print_r($ra_factor_data_arr);

        foreach ($ra_factor_data_arr as $key => $each_line) {
            //need
            $result = $this->setting_model->update_ra_factor($each_line);
            
        }
        /*-----End of save ra_factor-----*/


        /*-----Procedure design-----*/
        $arr_deleted_step  = isset($form_data['arr_deleted_step'])?$form_data['arr_deleted_step']:array();

        $temp_arr = array();
        $i = 1;

        foreach ($form_data['procedure'] as $key => $each_line) {
            $temp_arr['id'] = $each_line['id'];
            $temp_arr['step_text']  = $each_line['step_text'];
            $temp_arr['programme_master_id']  = $programme_master_id;
            $temp_arr['child_json'] = json_encode($each_line['child_line']);
            $temp_arr['order_by'] = $i;

            $i++;
            
            $result = $this->setting_model->insert_audit_programme_procedure($temp_arr);

        }

        $this->setting_model->delete_audit_procedure($arr_deleted_step);
        /*-----End of procedure design-----*/

        /*-----Programme content tree-----*/
        if($programme_master_id != "")
        {
            $data         = $form_data['programme_content_tree'];

            $content_node = $this->db->query("SELECT * FROM audit_programme_content WHERE programme_master_id = " . $programme_master_id);
            $ori_content_node = $content_node->result_array();

            $deleted_node_id = [];

            foreach ($ori_content_node as $ori_cn_key => $ori_cn_value) 
            {
                $temp_cn_id = $ori_cn_value['id'];

                foreach ($data as $key => $value) 
                {
                    if($ori_cn_value['id'] == $value['data']['id'])
                    {
                        $temp_cn_id = "";
                    }
                }

                if(!empty($temp_cn_id))
                {
                    array_push($deleted_node_id, $temp_cn_id);
                }
            }

            if(count($deleted_node_id) > 0)
            {
                $this->db->where_in('id', $deleted_node_id);
                $result = $this->db->update('audit_programme_content', array('deleted' => 1));         
            }

            //** delete node **//

            // print_r(json_decode($form_data['paf_tree']));
            // print_r($form_data['paf_tree']);
            
            $temp_data = $data;

            foreach ($data as $key => $value) 
            {

                $each_node = array(
                        'programme_master_id' => $programme_master_id,
                        'text'                => $value['text'],
                        'parent'              => $temp_data[$key]['parent'],
                        'type'                => $value['type'],
                        'order_by'            => $key+1
                    );

                if(empty($value['data']['id']))
                {
                    $result = $this->db->insert('audit_programme_content', $each_node);

                    $this_node_id = $this->db->insert_id();
                     
                    foreach ($temp_data as $key_2 => $value_2)
                    {
                        // print_r(array($value_2['parent'], $value['id']));
                        if($value_2['parent'] == $value['id'])
                        {
                            $temp_data[$key_2]['parent'] = $this_node_id;
                        }
                    }

                    // array_push($fs_categorized_account_ids, $this_category_id);
                }
                else
                {
                    $this->db->where('id', $value['data']['id']);
                    $result = $this->db->update('audit_programme_content', $each_node);

                    foreach ($temp_data as $key_2 => $value_2)
                    {
                        // print_r(array($value_2['parent'], $value['id']));
                        if($value_2['parent'] == $value['id'])
                        {
                            $temp_data[$key_2]['parent'] = $value['data']['id'];
                        }
                    }

                    // array_push($fs_categorized_account_ids, $value['data']['id']);
                }
            }
        }
        /*-----End of programme tree-----*/


        echo json_encode(array('status' => "success" , 'master_id' => $programme_master_id));


    }

    public function save_all_yn_programme_setting()
    {
        $form_data = $this->input->post();
        // print_r($form_data);
        //save audit programme master
         $data = array(
            'id'              => $form_data['master_id'],
            'programme_index' => $form_data['index_alpha'],
            'last_updated' => $this->session->userdata("user_id"),
            'title'      => $form_data['programme_title'],
            'programme_type' => 2
        );

        $programme_master_id = $this->setting_model->submit_audit_programme_info($data);


        /*-----Programme content tree-----*/
        if($programme_master_id != "")
        {
            $data         = $form_data['programme_content_tree'];

            $content_node = $this->db->query("SELECT * FROM audit_programme_content WHERE programme_master_id = " . $programme_master_id);
            $ori_content_node = $content_node->result_array();

            $deleted_node_id = [];

            foreach ($ori_content_node as $ori_cn_key => $ori_cn_value) 
            {
                $temp_cn_id = $ori_cn_value['id'];

                foreach ($data as $key => $value) 
                {
                    if($ori_cn_value['id'] == $value['data']['id'])
                    {
                        $temp_cn_id = "";
                    }
                }

                if(!empty($temp_cn_id))
                {
                    array_push($deleted_node_id, $temp_cn_id);
                }
            }

            if(count($deleted_node_id) > 0)
            {
                $this->db->where_in('id', $deleted_node_id);
                $result = $this->db->update('audit_programme_content', array('deleted' => 1));         
            }

            //** delete node **//

            // print_r(json_decode($form_data['paf_tree']));
            // print_r($form_data['paf_tree']);
            
            $temp_data = $data;

            foreach ($data as $key => $value) 
            {

                $each_node = array(
                        'programme_master_id' => $programme_master_id,
                        'text'                => $value['text'],
                        'parent'              => $temp_data[$key]['parent'],
                        'type'                => $value['type'],
                        'order_by'            => $key+1
                    );

                if(empty($value['data']['id']))
                {
                    $result = $this->db->insert('audit_programme_content', $each_node);

                    $this_node_id = $this->db->insert_id();
                     
                    foreach ($temp_data as $key_2 => $value_2)
                    {
                        // print_r(array($value_2['parent'], $value['id']));
                        if($value_2['parent'] == $value['id'])
                        {
                            $temp_data[$key_2]['parent'] = $this_node_id;
                        }
                    }

                    // array_push($fs_categorized_account_ids, $this_category_id);
                }
                else
                {
                    $this->db->where('id', $value['data']['id']);
                    $result = $this->db->update('audit_programme_content', $each_node);

                    foreach ($temp_data as $key_2 => $value_2)
                    {
                        // print_r(array($value_2['parent'], $value['id']));
                        if($value_2['parent'] == $value['id'])
                        {
                            $temp_data[$key_2]['parent'] = $value['data']['id'];
                        }
                    }

                    // array_push($fs_categorized_account_ids, $value['data']['id']);
                }
            }
        }
        /*-----End of programme tree-----*/


        echo json_encode(array('status' => "success" , 'master_id' => $programme_master_id));


    }

    public function save_all_qa_programme_setting()
    {
        $form_data = $this->input->post();
        // print_r($form_data);
        //save audit programme master
        // print_r($form_data);

        $data = array(
            'id'              => $form_data['master_id'],
            'programme_index' => $form_data['index_alpha'],
            'last_updated' => $this->session->userdata("user_id"),
            'title'      => $form_data['programme_title'],
            'programme_type' => 3
        );

        $programme_master_id = $this->setting_model->submit_audit_programme_info($data);

        /*------Question line------*/
        $arr_deleted_ques  = isset($form_data['arr_deleted_ques'])?$form_data['arr_deleted_ques']:array();

        $question_line_data_arr = $this->build_question_line_arr($programme_master_id, $form_data['question_id'], $form_data['question_text']);

        foreach ($question_line_data_arr as $key => $each_line) {
            //need
            $result = $this->setting_model->insert_audit_programme_question($each_line);
            
        }

        // print_r($arr_deleted_ques);

        $this->setting_model->delete_audit_question($arr_deleted_ques);
        /*------End of question line-----*/
      


        echo json_encode(array('status' => "success" , 'master_id' => $programme_master_id));


    }

    public function save_only_master_programme_setting()
    {
        $form_data = $this->input->post();
        // print_r($form_data);
        //save audit programme master
        // print_r($form_data);

        $data = array(
            'id'              => $form_data['master_id'],
            'programme_index' => $form_data['index_alpha'],
            'last_updated' => $this->session->userdata("user_id"),
            'title'      => $form_data['programme_title'],
            'programme_type' => $form_data['programme_type']
        );

        $programme_master_id = $this->setting_model->submit_audit_programme_info($data);


        echo json_encode(array('status' => "success" , 'master_id' => $programme_master_id));
    }

    public function build_objective_line_arr($master_id, $objective_id, $objective_text, $programme_assertion)
    {
        $temp_arr = array();

        foreach ($objective_text as $key => $each_text) 
        {
        

            array_push(
                $temp_arr, array(
                    'id'                        => $objective_id[$key],
                    'programme_master_id'       => $master_id,
                    'objective_text'            => $objective_text[$key],
                    'assertion'                 => isset($programme_assertion[$key])?$programme_assertion[$key]:"",
                    'order_by'                  => $key+1
                )
            );

        }

        return $temp_arr;
    }

    public function build_question_line_arr($master_id, $question_id, $question_text)
    {
        $temp_arr = array();

        foreach ($question_id as $key => $each_text) 
        {
        

            array_push(
                $temp_arr, array(
                    'id'                        => $question_id[$key],
                    'programme_master_id'       => $master_id,
                    'question_text'            => $question_text[$key],
                    'order_by'                  => $key+1
                )
            );

        }

        return $temp_arr;
    }

    public function save_ra_factor()
    {
        $form_data = $this->input->post();

        $data = array(
            'id'              => $form_data['master_id'],
            'programme_index' => $form_data['index_alpha'],
            'type'       => $form_data['programme_type'],
            'last_updated' => $this->session->userdata("user_id"),
            'title'      => $form_data['programme_title']
        );


        $programme_master_id = $this->setting_model->submit_audit_programme_info($data);

        $ra_factor_data_arr = $this->build_ra_factor_arr($form_data['cell_id'], $form_data['cell_value']);

        // print_r($ra_factor_data_arr);

        foreach ($ra_factor_data_arr as $key => $each_line) {
            //need
            $result = $this->setting_model->update_ra_factor($each_line);
            
        }

        

        echo json_encode(array('status' => "success" , 'master_id' => $programme_master_id));
        // print_r($form_data);
    }

    public function build_ra_factor_arr($cell_id, $cell_value)
    {
        $temp_arr = array();

        foreach ($cell_id as $key => $each) 
        {
            array_push(
                $temp_arr, array(
                    'id'                        => $each,
                    'value'                     => $cell_value[$key]
                )
            );

        }

        return $temp_arr;
    }

    public function save_procedure_design()
    {
        $form_data = $this->input->post();

        // print_r($form_data);
       
        $data = array(
            'id'              => $form_data['master_id'],
            'programme_index' => $form_data['index_alpha'],
            'type'       => $form_data['programme_type'],
            'last_updated' => $this->session->userdata("user_id"),
            'title'      => $form_data['programme_title']
        );

        $programme_master_id = $this->setting_model->submit_audit_programme_info($data);
        $arr_deleted_step  = isset($form_data['arr_deleted_step'])?$form_data['arr_deleted_step']:array();

        $temp_arr = array();
        $i = 1;

        foreach ($form_data['procedure'] as $key => $each_line) {
            $temp_arr['id'] = $each_line['id'];
            $temp_arr['step_text']  = $each_line['step_text'];
            $temp_arr['programme_master_id']  = $programme_master_id;
            $temp_arr['child_json'] = json_encode($each_line['child_line']);
            $temp_arr['order_by'] = $i;

            $i++;
            
            $result = $this->setting_model->insert_audit_programme_procedure($temp_arr);

        }

        $this->setting_model->delete_audit_procedure($arr_deleted_step);

        echo json_encode(array('status' => "success" , 'master_id' => $programme_master_id));
    }

    public function save_programme_content()
    {
        $form_data = $this->input->post();

        $data = array(
            'id'              => $form_data['master_id'],
            'programme_index' => $form_data['index_alpha'],
            'type'       => $form_data['programme_type'],
            'last_updated' => $this->session->userdata("user_id"),
            'title'      => $form_data['programme_title']
        );

        $programme_master_id = $this->setting_model->submit_audit_programme_info($data);

        if($programme_master_id != "")
        {
            $data         = $form_data['programme_content_tree'];

            $content_node = $this->db->query("SELECT * FROM audit_programme_content WHERE programme_master_id = " . $programme_master_id);
            $ori_content_node = $content_node->result_array();

            $deleted_node_id = [];

            foreach ($ori_content_node as $ori_cn_key => $ori_cn_value) 
            {
                $temp_cn_id = $ori_cn_value['id'];

                foreach ($data as $key => $value) 
                {
                    if($ori_cn_value['id'] == $value['data']['id'])
                    {
                        $temp_cn_id = "";
                    }
                }

                if(!empty($temp_cn_id))
                {
                    array_push($deleted_node_id, $temp_cn_id);
                }
            }

            if(count($deleted_node_id) > 0)
            {
                $this->db->where_in('id', $deleted_node_id);
                $result = $this->db->update('audit_programme_content', array('deleted' => 1));         
            }

            //** delete node **//

            // print_r(json_decode($form_data['paf_tree']));
            // print_r($form_data['paf_tree']);
            
            $temp_data = $data;

            foreach ($data as $key => $value) 
            {

                $each_node = array(
                        'programme_master_id' => $programme_master_id,
                        'text'                => $value['text'],
                        'parent'              => $temp_data[$key]['parent'],
                        'type'                => $value['type'],
                        'order_by'            => $key+1
                    );

                if(empty($value['data']['id']))
                {
                    $result = $this->db->insert('audit_programme_content', $each_node);

                    $this_node_id = $this->db->insert_id();
                     
                    foreach ($temp_data as $key_2 => $value_2)
                    {
                        // print_r(array($value_2['parent'], $value['id']));
                        if($value_2['parent'] == $value['id'])
                        {
                            $temp_data[$key_2]['parent'] = $this_node_id;
                        }
                    }

                    // array_push($fs_categorized_account_ids, $this_category_id);
                }
                else
                {
                    $this->db->where('id', $value['data']['id']);
                    $result = $this->db->update('audit_programme_content', $each_node);

                    foreach ($temp_data as $key_2 => $value_2)
                    {
                        // print_r(array($value_2['parent'], $value['id']));
                        if($value_2['parent'] == $value['id'])
                        {
                            $temp_data[$key_2]['parent'] = $value['data']['id'];
                        }
                    }

                    // array_push($fs_categorized_account_ids, $value['data']['id']);
                }
            }
        }

        echo json_encode(array('status' => "success" , 'master_id' => $programme_master_id));
        // print_r($form_data);
    }

    public function archive_programme(){

        $form_data = $this->input->post();

        $result = $this->setting_model->archive_programme($form_data['programme_id']);

        echo $result;
    }

    public function retrieve_previous_record(){
        $form_data = $this->input->post();

        $index          = $form_data['index'];
        $programme_type = $form_data['programme_type'];

        $result = $this->setting_model->retrieve_previous_record($index, $programme_type);
        echo json_encode($result);
    }

    public function check_avail_index()
    {
        $form_data = $this->input->post();
        // print_r($form_data);
        $index = $form_data['index'];
        $result = $this->setting_model->check_index_existance($index);
        echo json_encode($result);
    }

    public function load_fs_default_acc_category()
    {
        $fs_default_acc_category = $this->caf_model->set_default_tree();

        echo json_encode($fs_default_acc_category);
    }

    public function save_fs_default_acc_list()
    {
        $form_data = $this->input->post();

        $data = $form_data['default_acc_category_tree'];

        $fs_default_acc_category = $this->db->query("SELECT * FROM fs_default_acc_category");
        $ori_fs_default_acc_category = $fs_default_acc_category->result_array();

        $deleted_id = [];

        foreach ($ori_fs_default_acc_category as $key => $ori_dac_value) 
        {
            $temp_dac_id = $ori_dac_value['id'];

            foreach ($data as $key => $value) 
            {
                if(isset($value['data']['fs_default_acc_category_id']))
                {
                    if($ori_dac_value['id'] == $value['data']['fs_default_acc_category_id'])
                    {
                        $temp_dac_id = "";
                    }
                }
                
            }

            if(!empty($temp_dac_id))
            {
                array_push($deleted_id, $temp_dac_id);
            }
        }

        if(count($deleted_id) > 0)
        {
            $this->db->where_in('id', $deleted_id);
            $result = $this->db->delete('fs_default_acc_category');
        }

        // $fs_default_acc_category_ids = [];
        $temp_data = $data;

        // print_r($temp_data);

        foreach ($data as $key => $value) 
        {
            $account_code = $temp_data[$key]['data']['account_code'];

            if(is_null($account_code))
            {
                $account_code = '';
            }

            $data_dac = array(
                    'account_code'  => $account_code,
                    'parent'        => $temp_data[$key]['parent'],
                    'tree_name'     => $temp_data[$key]['text'],
                    'description'   => $temp_data[$key]['data']['description'],
                    'type'          => $temp_data[$key]['type'],
                    'order'         => $key + 1
                );

            if(empty($value['data']['fs_default_acc_category_id']) || $value['data']['fs_default_acc_category_id'] == 0) // if fs_default_acc_category_id is empty
            {
                $result = $this->db->insert('fs_default_acc_category', $data_dac);

                if(empty($value['data']['account_code']))
                {
                    // change children parent id to newly insert (id from create by id)
                    $this_category_id = $this->db->insert_id();
                    
                    foreach ($temp_data as $key_2 => $value_2)
                    {
                        if($value_2['parent'] == $value['id'])
                        {
                            $temp_data[$key_2]['parent'] = $this_category_id;
                        }
                    }
                }
            }
            else
            {
                $this->db->where('id', $value['data']['fs_default_acc_category_id']);
                $result = $this->db->update('fs_default_acc_category', $data_dac);

                if(empty($value['data']['account_code']))
                {
                    // change children parent id to newly insert (id from create by id)
                    foreach ($temp_data as $key_2 => $value_2)
                    {
                        if($value_2['parent'] == $value['id'])
                        {
                            $temp_data[$key_2]['parent'] = $value['data']['fs_default_acc_category_id'];
                        }
                    }
                }
            }
        }

        echo json_encode(array("result" => true, "message" => "Succesfully updated Mapping Default List"));
    }

    // temporary created function to get parent_id
    public function test()
    {
        $fs_default_acc_category = $this->db->query("SELECT * FROM fs_default_acc_category");
        $fs_default_acc_category = $fs_default_acc_category->result_array();



        // print_r($this->db->query("SELECT * FROM fs_default_acc_category"));
    }

    // public function submit_type_of_leave(){
    //     $this->session->set_userdata("tab_active", "type_of_leave");

    //     $form_data = $this->input->post();

    //     $data = array(
    //         'leave_name' => $form_data['leave_name'],
    //         'days'  => $form_data['leave_days'],
    //         'choose_carry_forward_id'  => $form_data['choose_carry_forward_id']
    //     );

    //     $result = $this->setting_model->submit_type_of_leave($data, $form_data['type_of_leave_id']);

    //     echo $result;
    // }

    // public function delete_type_of_leave(){
    //     $this->session->set_userdata("tab_active", "type_of_leave");

    //     $form_data = $this->input->post();

    //     $result = $this->setting_model->delete_type_of_leave($form_data['type_of_leave_id']);

    //     echo $result;
    // }

    // public function submit_holiday(){

    //     $this->session->set_userdata("tab_active", "block_holiday");

    //     $form_data = $this->input->post();

    //     $data = array(
    //         'holiday_date' => date('Y-m-d', strtotime($form_data['block_holiday'])),
    //         'description'  => $form_data['holiday_description']
    //     );

    //     $result = $this->setting_model->submit_holiday($data, $form_data['block_holiday_id']);

    //     echo $result;
    // }

    // public function delete_holiday(){
        
    //     $this->session->set_userdata("tab_active", "block_holiday");

    //     $form_data = $this->input->post();

    //     $result = $this->setting_model->delete_holiday($form_data['holiday_id']);

    //     echo $result;
    // }

    // public function submit_approval_cap(){

    //     $this->session->set_userdata("tab_active", "block_leave");

    //     $form_data = $this->input->post();

    //     $data = array(
    //         'number_of_employee' => $form_data['number_of_employee']
    //     );

    //     $result = $this->setting_model->submit_approval_cap($data, $form_data['approval_cap_id']);

    //     echo $result;
    // }

    // public function submit_leave_cycle(){

    //     $this->session->set_userdata("tab_active", "leave_cycle");

    //     $form_data = $this->input->post();

    //     $data = array(
    //         'leave_cycle_date_from' => date('m-d', strtotime($form_data['from'])),
    //         'leave_cycle_date_to'  => date('m-d', strtotime($form_data['to']))
    //     );

    //     $result = $this->setting_model->submit_leave_cycle($data, $form_data['leave_cycle_id']);

    //     echo $result;
    // }

    // public function submit_carry_forward_period(){
    //     $this->session->set_userdata("tab_active", "carry_forward_period");

    //     $form_data = $this->input->post();

    //     $data = array(
    //         'carry_forward_period_date' => date('m-d', strtotime($form_data['carry_forward_period_date']." 2019"))
    //     );

    //     $result = $this->setting_model->submit_carry_forward_period($data, $form_data['carry_forward_period_id']);

    //     echo $result;
    // }

    // public function submit_block_leave(){
    //     $this->session->set_userdata("tab_active", "block_leave");

    //     $form_data = $this->input->post();

    //     $query = $this->db->query("SELECT * from payroll_leave WHERE
    //         ((start_date BETWEEN '".date('Y-m-d', strtotime($form_data['from']))."'AND '".date('Y-m-d', strtotime($form_data['to']))."') OR 
    //         (end_date BETWEEN '".date('Y-m-d', strtotime($form_data['from']))."'AND '".date('Y-m-d', strtotime($form_data['to']))."') OR 
    //         (start_date <= '".date('Y-m-d', strtotime($form_data['from']))."' AND end_date >= '".date('Y-m-d', strtotime($form_data['to']))."')) AND status = 1");

    //     if($query->num_rows())
    //     {
    //         $query = $query->result_array();

    //         for($t = 0; $t < count($query); $t++)
    //         {
    //             // To get the last remaining annual leave left
    //             $q = $this->db->query("SELECT * FROM payroll_employee_annual_leave eal_1 WHERE eal_1.last_updated = (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id=" . $query[$t]['employee_id'] . " AND eal_2.type_of_leave_id = ".$query[$t]['type_of_leave_id'].") AND eal_1.type_of_leave_id = ".$query[$t]['type_of_leave_id']."");

    //             $data['status'] = 3;
    //             $data['status_updated_by'] = date('Y-m-d H:i:s');
    //             $data['al_left_before'] = $q->result()[0]->annual_leave_days;
    //             $data['al_left_after'] = $q->result()[0]->annual_leave_days;

    //             $q2 = $this->db->where('id', $query[$t]['id']);
    //             $result2 = $q2->update('payroll_leave', $data);
    //         }
    //     }

    //     $data = array(
    //         'block_leave_date_from' => date('Y-m-d', strtotime($form_data['from'])),
    //         'block_leave_date_to'  => date('Y-m-d', strtotime($form_data['to']))
    //     );

    //     $result = $this->setting_model->submit_block_leave($data, $form_data['block_leave_id']);

    //     echo $result;
       
    // }

    // public function delete_block_leave(){
        
    //     $this->session->set_userdata("tab_active", "block_leave");

    //     $form_data = $this->input->post();

    //     $result = $this->setting_model->delete_block_leave($form_data['block_leave_id']);

    //     echo $result;
    // }

}