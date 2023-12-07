<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once('assets/vendor/tcpdf/tcpdf.php');


class Stocktake extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
        
        $this->load->library(array('session','parser','encryption'));
        $this->load->library(array('zip'));
        $this->load->model('bank/bank_model');
        $this->load->model('client/client_model');
        $this->load->model('list_of_auditor/list_of_auditor_model');
        $this->load->model('stocktake_model');
    }

    public function index()
    {   
        // $this->meta['page_name'] = 'List of Auditor';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $temp_auditor_list = $this->list_of_auditor_model->get_auditor_list();
        if($temp_auditor_list)
        {
            foreach ($temp_auditor_list as $key => $value) {
                $temp_auditor_list[$key]->full_address = $this->write_address($value->street_name, $value->unit_no1, $value->unit_no2, $value->building_name, $value->postal_code, 'letter');
            }
        }
        $this->data['auditor_list'] = $temp_auditor_list;
        $this->data['first_letter'] = $this->list_of_auditor_model->get_first_letter();

        $temp_auditor_dropdown = $this->stocktake_model->get_auditor_dropdown_list();

        // $firstItem = array('0' => 'All');
        $this->data['auditor_filter_dropdown'] = $temp_auditor_dropdown;
        $this->data['sts_status_dropdown'] = $this->stocktake_model->get_sts_status_dropdown_list();
        $this->data['stocktake_setting_list'] = $this->stocktake_model->get_client_stocktake_setting();
        $this->data['stocktake_arrangement_list'] = $this->stocktake_model->get_all_stocktake_arrangement();
        $this->data['stocktake_subsequent_list'] = $this->stocktake_model->get_all_stocktake_subsequent();
        $this->data['office']     = $this->stocktake_model->get_office();
        $this->data['department']     = $this->stocktake_model->get_department();

        $bc = array(array('link' => '#', 'page' => 'List of Auditor'));
        $meta = array('page_title' => 'Stocktake', 'bc' => $bc, 'page_name' => 'Stocktake');

        $this->page_construct('index.php', $meta, $this->data);


        // $this->data['bank_auth'] = $this->bank_model->get_bank_auth();
        // $this->data['bank_auth_deactivate'] = $this->bank_model->get_bank_auth_deactivate();
        // $temp_bank_confirm_setting = $this->bank_model->get_bank_confirm_setting();
        // $this->data['bank_confirm_setting'] = $temp_bank_confirm_setting;
        // $this->data['bank_confirm'] = $this->bank_model->get_bank_confirm();

        // $this->data['status_dropdown'] = $this->bank_model->get_status_dropdown_list();
        // $this->data['holiday_list'] = $this->bank_model->get_holiday_list();
        // $this->data['type_of_leave_list'] = $this->bank_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->bank_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->bank_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->bank_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->bank_model->get_choose_carry_forward_list();

        // $this->page_construct('index.php', $this->meta, $this->data);
    }

    public function update_stocktake_reminder()
    {
        // $this->db->delete("client_setup_reminder",array('company_code'=>$_POST['company_code']));
        $this->session->set_userdata("tab_active", "st_setting_list");
        $form_data = $this->input->post();

        if($form_data['company_code'] != null)
        {
        //     for($g = 0; $g < count($_POST['select_reminder']); $g++)
        //     {
            $reminder['company_code'] = $form_data['company_code'];
            $reminder['reminder_flag'] = $form_data['active'];
        
        //         $this->db->insert('client_setup_reminder', $reminder);
        //     }
        }


        $query_check = $this->db->query('SELECT * FROM audit_stocktake_reminder_setting where company_code = "'.$form_data['company_code'].'"');

        if($query_check->num_rows() > 0)
        {
            $query_c = $query_check->result_array();

            $this->db->where('id', $query_c[0]['id']);
            $this->db->update('audit_stocktake_reminder_setting', $reminder);

            // $result = $query_c[0]['id'];
        }
        else
        {
            $this->db->insert('audit_stocktake_reminder_setting', $reminder); 
            // $result = $this->db->insert_id();
        }
        // return $result;

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function add_stocktake_arrangement()
    {
        $this->meta['page_name'] = 'Stocktake Arrangement';

        // $this->data['active_tab'] = $this->session->userdata('tab_active');
        // $this->session->unset_userdata('tab_active');
        $this->session->set_userdata("tab_active", "st_arrangement");

        $this->data['auditor_name_dropdown'] = $this->stocktake_model->get_auditor_dropdown_list();

        // $this->data['holiday_list'] = $this->bank_model->get_holiday_list();
        // $this->data['type_of_leave_list'] = $this->bank_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->bank_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->bank_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->bank_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->bank_model->get_choose_carry_forward_list();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Stocktake', base_url('stocktake'));
        $this->mybreadcrumb->add('Add Stocktake Arrangement', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Add Stocktake Arrangement'));
        $meta = array('page_title' => 'Add Stocktake Arrangement', 'bc' => $bc, 'page_name' => 'Add Stocktake Arrangement');

        $this->page_construct('add_stocktake_arrangement.php', $meta, $this->data);
    }

    public function edit_stocktake_arrangement($id)
    {
        $this->meta['page_name'] = 'Stocktake Arrangement';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $this->data['edit_stocktake_arrangement'] = $this->stocktake_model->get_edit_stocktake_arrangement($id);
        $this->data['auditor_name_dropdown'] = $this->stocktake_model->get_auditor_dropdown_list();
        // $this->data['type_of_leave_list'] = $this->bank_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->bank_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->bank_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->bank_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->bank_model->get_choose_carry_forward_list();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Stocktake', base_url('stocktake'));
        $this->mybreadcrumb->add('Edit Stocktake Arrangement', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Edit Stocktake Arrangement'));
        $meta = array('page_title' => 'Edit Stocktake Arrangement', 'bc' => $bc, 'page_name' => 'Edit Stocktake Arrangement');

        $this->page_construct('add_stocktake_arrangement.php', $meta, $this->data);
    }

    public function submit_subsequent(){
        $this->session->set_userdata("tab_active", "st_arrangement");

        $form_data = $this->input->post();

        $data = array(
            'remark' => $form_data['remark'],
            'status' => $form_data['sts_status'],
            'updated_at' => date("Y-m-d H:i:s"),
            'done' => 1
            );

        $result = $this->stocktake_model->insert_stocktake_arrangement($data, $form_data['arrangement_id']);


        // print_r($_FILES['attachment']);
        if(isset($_FILES['attachment']))
        {
            $filesCount = count($_FILES['attachment']['name']);

            for($i = 0; $i < $filesCount; $i++)
            {   //echo json_encode($_FILES['uploadimages']);
                $_FILES['each']['name']     = $_FILES['attachment']['name'][$i];
                $_FILES['each']['type']     = $_FILES['attachment']['type'][$i];
                $_FILES['each']['tmp_name'] = $_FILES['attachment']['tmp_name'][$i];
                $_FILES['each']['error']    = $_FILES['attachment']['error'][$i];
                $_FILES['each']['size']     = $_FILES['attachment']['size'][$i];

                $uploadPath = './document/subsequent';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('each'))
                {
                    $fileData = $this->upload->data();
                    $uploadData[$i]['stocktake_arrangement_id'] = $form_data['arrangement_id'];
                    $uploadData[$i]['file_path'] = "subsequent";
                    $uploadData[$i]['file_name'] = $fileData['file_name'];

                }
                else
                {
                    $error = $this->upload->display_errors();
                    echo json_encode($error);
                }

            }
            if(!empty($uploadData))
            {
                $this->db->insert_batch('audit_stocktake_subsequent_document',$uploadData);
                // print_r($uploadData);
                
            }


        }
    }

    public function submit_edit_subsequent(){
        $this->session->set_userdata("tab_active", "st_subsequent");

        $form_data = $this->input->post();
        $uploadData = array();

        $data = array(
            'remark' => $form_data['edit_remark'],
            'status' => $form_data['edit_sts_status'],
            'updated_at' => $form_data['edit_last_update'],
            );

        $result = $this->stocktake_model->insert_stocktake_arrangement($data, $form_data['arrangement_id']);


        // print_r($_FILES['attachment']);
        if(isset($_FILES['edit_attachment']))
        {
            $filesCount = count($_FILES['edit_attachment']['name']);

            for($i = 0; $i < $filesCount; $i++)
            {   //echo json_encode($_FILES['uploadimages']);
                $_FILES['each']['name']     = $_FILES['edit_attachment']['name'][$i];
                $_FILES['each']['type']     = $_FILES['edit_attachment']['type'][$i];
                $_FILES['each']['tmp_name'] = $_FILES['edit_attachment']['tmp_name'][$i];
                $_FILES['each']['error']    = $_FILES['edit_attachment']['error'][$i];
                $_FILES['each']['size']     = $_FILES['edit_attachment']['size'][$i];

                $uploadPath = './document/subsequent';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('each'))
                {
                    $fileData = $this->upload->data();
                    $uploadData[$i]['stocktake_arrangement_id'] = $form_data['arrangement_id'];
                    $uploadData[$i]['file_path'] = "subsequent";
                    $uploadData[$i]['file_name'] = $fileData['file_name'];

                }
                else
                {
                    $error = $this->upload->display_errors();
                    // echo json_encode($error);
                    $result = array("Status" => 2, 'message' => 'Information Updated (no file uploaded) ', 'title' => 'Updated');
                }

            }

            if(!empty($uploadData))
            {
                

                $this->db->insert_batch('audit_stocktake_subsequent_document',$uploadData);
                // print_r($uploadData);
                $result = array("Status" => 1,  'message' => 'Information Updated (file uploaded) ', 'title' => 'Updated');
            }


        }


        // $data = array(
        //     'bank_name_for_user' => $form_data['bank_name_for_user'],
        //     'bank_name' => $form_data['bank_name'],
        //     'add_line1' => $form_data['add_line1'],
        //     'add_line2' => $form_data['add_line2'],
        //     'add_line3' => $form_data['add_line3']
        // );

        // $result = $this->bank_model->submit_bank_list($data, $form_data['bank_list_id']);

        echo json_encode($result);
    }

    public function add_rp_info()
    {
        $data['stocktake_arrangement_id'] = $_POST['sta_id'];
        //$data['family_info_id']=$_POST['family_info_id'][$i];
        // $data['paf_child_id']=$_POST['paf_index'];

        if($_POST['save_type'] == "point")
        {
            $data['point'] = $_POST['point'];
        }

        if($_POST['save_type'] == "response")
        {
            $data['response'] = $_POST['response'];
        }


        $q = $this->db->get_where("audit_stocktake_review_point", array("id" => $_POST['review_point_id']));

        if (!$q->num_rows())
        {   
            if($_POST['save_type'] == "point")
            {
                $data['point_raised_by'] = $this->session->userdata('user_id');
                $data['point_raised_at'] = date("Y-m-d H:i:s");
            }

            $this->db->insert("audit_stocktake_review_point",$data);
            $insert_review_point_id = $this->db->insert_id();

            $point_raise_by = $this->stocktake_model->get_point_raise_detail($insert_review_point_id);

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_review_point_id" => $insert_review_point_id, "point_raise_detail" => $point_raise_by[0]));
        }
        else
        {

            if($_POST['save_type'] == "response")
            {
                $data['response_updated_by'] = $this->session->userdata('user_id');
                $data['response_updated_at'] = date("Y-m-d H:i:s");
            }

            if($_POST['save_type'] == "point")
            {
                $data['point_updated_by'] = $this->session->userdata('user_id');
                $data['point_updated_at'] = date("Y-m-d H:i:s");
            }


            $this->db->update("audit_stocktake_review_point",$data,array("id" => $_POST['review_point_id']));

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
    }

    public function delete_sta()
    {
        $this->session->set_userdata("tab_active", "st_arrangement"); 

        $form_data = $this->input->post();

        $result = $this->stocktake_model->delete_sta($form_data['sta_id']);

        echo $result;
    }
    
    public function delete_arrangement_info()
    {
        // $this->session->set_userdata("tab_active", "st_arrangement");

        $form_data = $this->input->post();

        $result = $this->stocktake_model->delete_arrangement_info($form_data['arrangement_info_id']);

        echo $result;
    }

    public function save_stocktake_arrangement()
    {
        $this->session->set_userdata("tab_active", "st_arrangement");
        $form_data = $this->input->post();

        $arrangement_id = $form_data['arrangement_id'];
        $reminder_id = $form_data['client_name'];
        $fye_date = $form_data['fye_date'];
        $pic_name = $form_data['pic_name'];
        $arrange_flag = 1;

        $info_id = $form_data['arrangement_info_id'];
        $our_auditor = isset($form_data['our_auditor'])?$form_data['our_auditor']:'';
        $hidden_our_auditor = $form_data['hidden_our_auditor'];
        $stocktake_date = $form_data['stocktake_date'];
        $stocktake_time = $form_data['stocktake_time'];
        $stocktake_address = $form_data['stocktake_address'];
        $client_pic = $form_data['client_pic'];

        $date = DateTime::createFromFormat('d F Y', $fye_date );
        $fye_date = $date->format('Y-m-d');

        foreach ($hidden_our_auditor as $key => $auditor) 
        {
            if($hidden_our_auditor[$key] == '' || $stocktake_date[$key] == '' || $stocktake_time[$key] == '' || $stocktake_address[$key] == '' )
            {
                $arrange_flag = 0;
            }
            else
            {
                $arrange_flag = 1;
            }
        }

        $stocktake_arrangement_data = array("reminder_id"    => $form_data['reminder_id'],
                                            "stocktake_pic"  => $pic_name,
                                            "arranged"         => $arrange_flag);

        if($arrangement_id == 0)
        {
            $query_check = $this->db->query('SELECT * FROM audit_stocktake_arrangement where reminder_id = "'.$stocktake_arrangement_data['reminder_id'].'"');
        }
        else
        {
            $query_check = $this->db->query('SELECT * FROM audit_stocktake_arrangement where id ="'.$arrangement_id.'"');
        }

        if($pic_name != 0)
        {
              
            $pic_info = $this->stocktake_model->get_pic_details($pic_name);
            $client_details = $this->stocktake_model->get_client_with_reminder_id($reminder_id);

            $parse_data = array(
                'pic_name'  => $pic_info[0]['first_name']." ".$pic_info[0]['last_name'],
                'client_name'      => $client_details['company_name']
            );

            $pic_email = $pic_info[0]['email'];
            if($pic_email == '"james@acumenbizcorp.com.sg"'){
                $pic_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$pic_email);
            }
            
            $manager_email = "penny@aaa-global.com";
            // $manager_email = "xinyee@aaa-global.com";

            $msg = file_get_contents('./application/modules/stocktake/email_templates/pic_stocktake_arrangement.html');
            $message = $this->parser->parse_string($msg, $parse_data, true);

            $subject = 'Physical Inventory Count Observation - '.$client_details['company_name'];

            $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");
        }

        if($query_check->num_rows() > 0)
        {
            $query = $query_check->result_array();

            $previous_pic = $query[0]['stocktake_pic'];
            if($previous_pic != $pic_name)
            {
                if($pic_name != 0)
                {
                    $cc_email = array();
                    $previous_pic_email = "";
                    if($previous_pic != 0)
                    {
                        $previous_pic_info = $this->stocktake_model->get_pic_details($previous_pic);
                        $previous_pic_email = $previous_pic_info[0]['email'];
                        // $previous_pic_email = "xinyee@aaa-global.com";
                        if($previous_pic_email != $manager_email){
                            // array_push($liste, $value);
                            array_push($cc_email, array("email" => $previous_pic_email));
                        }
                        
                    }
                    
                    $receiver_email = array();
                    //this the right query
                    array_push($receiver_email, array("email" => $pic_email) );

                    //this is for testing purpose
                    // array_push($receiver_email, array("email" => "xinyee@aaa-global.com"));
                    // $cc_email = array();
                    if(!in_array($manager_email, array_column($cc_email, 'email')) && !in_array($manager_email, array_column($receiver_email, 'email'))){
                        // array_push($liste, $value);
                        array_push($cc_email, array("email" => $manager_email));
                    }
                    
                    array_push($cc_email, array("email" => $previous_pic_email));

                    //SMA
                    // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

                    //SEND IN BLUE
                    // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray

                    //this the right query
                    $this->send_email($subject, $sender_email, $receiver_email, $message, $cc_email, "");
                    // $this->send_email($subject, $sender_email, $receiver_email, $message, "", "");
                }

            }
        }
        else
        {
            if($pic_name != 0)
            {
                

                $receiver_email = array();

                //this the right query
                array_push($receiver_email, array("email" => $pic_email) );

                //this is for testing purpose
                // array_push($receiver_email, array("email" => "xinyee@aaa-global.com"));


                $cc_email = array();

                if(!in_array($manager_email, array_column($receiver_email, 'email'))){
                    // array_push($liste, $value);
                    array_push($cc_email, array("email" => $manager_email));
                }
                
                // array_push($cc_email, array("email" => "xinyee@aaa-global.com"));
                //SMA
                // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

                //SEND IN BLUE
                // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray

                //this the right query
                $this->send_email($subject, $sender_email, $receiver_email, $message, $cc_email, "");
                // $this->send_email($subject, $sender_email, $receiver_email, $message, "", "");
            }
        }


        // need
        $stocktake_arrangement_id = $this->stocktake_model->insert_stocktake_arrangement($stocktake_arrangement_data);


        $all_st_arrangement_info = $this->build_st_arrangement_info_arr($info_id, $hidden_our_auditor, $stocktake_arrangement_id,
                                                                        $stocktake_date, $stocktake_time, 
                                                                        $stocktake_address, $client_pic);


        foreach ($all_st_arrangement_info as $key => $each_info) {
            if($each_info['auditor_id'] != "" && $each_info['stocktake_date'] != "" && $each_info['stocktake_time'] != "" && $each_info['stocktake_address'] != "")
            {
                if($each_info['id'] != null)
                {
                    $previous_record = $this->db->query('SELECT * FROM audit_stocktake_arrangement_info where id = "'.$each_info['id'].'"');
                    $previous_record = $previous_record->result_array();


                    if($each_info['stocktake_date'] == $previous_record[0]['stocktake_date'] && ($each_info['stocktake_time'].":00")== $previous_record[0]['stocktake_time'] && $each_info['stocktake_address'] == $previous_record[0]['stocktake_address'])
                    {
                        $a1 = explode(",", $each_info['auditor_id']);
                        $a2 = explode(",", $previous_record[0]['auditor_id']);

                        $send_list = array_diff($a1,$a2);
                    }
                    else
                    {
                        $send_list = explode(",", $each_info['auditor_id']);
                    }
                }
                else
                {
                    $send_list = explode(",", $each_info['auditor_id']);
                }

                // print_r($send_list);
                $cc_email = array();
                $info_parse_data = array(
                    'arrangement_date'    => DateTime::createFromFormat('Y-m-d', $each_info['stocktake_date'] )->format('d/m/Y'),
                    'arrangement_time'    => $each_info['stocktake_time'],
                    'arrangement_address' => $each_info['stocktake_address'],
                    'client_pic'          => $each_info['client_pic'],
                    'client_name'      => $client_details['company_name']

                );

                
                

                $msg = file_get_contents('./application/modules/stocktake/email_templates/stocktake_arrangement_notification.html');
                $message = $this->parser->parse_string($msg, $info_parse_data, true);
                $receiver_email = array();
                foreach ($send_list as $recipient_id) {

                    $pic_info = $this->stocktake_model->get_pic_details_users($recipient_id);

                    $pic_email = $pic_info[0]['email'];
                    if($pic_email == '"james@acumenbizcorp.com.sg"'){
                        $pic_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$pic_email);
                    }

                    
                    //this the right query
                    array_push($receiver_email, array("email" => $pic_email) );

                }

                if(!in_array($manager_email, array_column($cc_email, 'email')) && !in_array($manager_email, array_column($receiver_email, 'email'))){
                    // array_push($liste, $value);
                    array_push($cc_email, array("email" => $manager_email));
                }


                if(count($receiver_email) > 0)
                {
                    $this->send_email($subject, $sender_email, $receiver_email, $message, $cc_email, "");
                }

                
            }



            
            //need
            $result = $this->stocktake_model->insert_stocktake_arrangement_info($each_info, $each_info['id']);
            
        }





        $data = array('status'=>'success');

        echo json_encode($data);



        // print_r($form_data);
    }

    public function build_st_arrangement_info_arr($info_id, $our_auditor, $stocktake_arrangement_id,
                                                  $stocktake_date, $stocktake_time, 
                                                  $stocktake_address, $client_pic)
    {
        $temp_arr = array();

        foreach ($our_auditor as $key => $auditor) 
        {
            
            if($stocktake_date[$key] != "")
            {
                $stocktake_date[$key] = DateTime::createFromFormat('d/m/Y', $stocktake_date[$key] )->format('Y-m-d');
            }
            

            array_push(
                    $temp_arr, array(
                        'id'                        => $info_id[$key],
                        'stocktake_arrangement_id'  => $stocktake_arrangement_id,
                        'auditor_id'                => $our_auditor[$key],
                        'stocktake_date'            => $stocktake_date[$key],
                        'stocktake_time'            => $stocktake_time[$key],
                        'stocktake_address'         => $stocktake_address[$key],
                        'client_pic'                => $client_pic[$key]
                    )
                );

        }

        return $temp_arr;
    }

    

    // public function generate_reminder_document($file_counter = 1, $parse_data)
    // {

    //     // $po_list = $this->products_model->getPdfData(); // retrieve all po from database
    //     // if(!empty($this->input->post('confirm_id')))
    //     // {
    //     //     $confirm_id = $this->input->post('confirm_id');
    //     // }
        
    //     // print_r($confirm_id);

    //     // $document_info_query = $this->db->query("SELECT ba.*, bc.*, c.company_name, bank.bank_name, bank.add_line1, bank.add_line2, f.id as firm_id, f.name as firm_name from audit_bank_confirm bc, audit_bank_auth ba
    //     //                                         LEFT JOIN client c on ba.company_code = c.company_code 
    //     //                                         LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
    //     //                                         LEFT JOIN firm f on ba.firm_id = f.id
    //     //                                         where ba.id = bc.bank_auth_id and bc.id = ".$confirm_id);
    //     // $document_info_query = $document_info_query->result_array();

    //     // foreach ($document_info_query as $key => $value) {
    //     //     $document_info_query[$key]['company_name'] = $this->encryption->decrypt($document_info_query[$key]['company_name']);
    //     // }



        
    //     // $header_company_info = $this->write_header($document_info_query[0]['firm_id'], false);
    //     $obj_pdf = new NOFOOTER_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    //     $obj_pdf->SetCreator(PDF_CREATOR);
    //     $title = "Stocktake Reminder";
    //     $obj_pdf->SetTitle($title);
    //     $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='<table style="width:100%; height: auto;"><tr style="text-align: center;"><td style="width:80%; height: auto;" align="center"></td></tr></table>', $tc=array(0,0,0), $lc=array(0,0,0));


    //     $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    //     $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    //     $obj_pdf->SetDefaultMonospacedFont('times');
    //     $obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    //     // $obj_pdf->SetFooterMargin(10);
    //     $obj_pdf->SetMargins(28, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    //     $obj_pdf->SetAutoPageBreak(FALSE, 18);
    //     $obj_pdf->SetFont('times', '', 9);
    //     $obj_pdf->setFontSubsetting(false);
    //     $obj_pdf->setY(33);

    //     $obj_pdf->startPageGroup();

    //     $obj_pdf->AddPage();

    //     $counter = 0;


    //     $do_template_query = $this->db->query("select * from audit_doc_master_template where id = 4");

    //     $do_template_query = $do_template_query->result_array();

    //     // $this->load->helper('pdf_helper');

    //     $pattern = "/{{[^}}]*}}/";
    //     $subject = $do_template_query[0]["document_content"];
    //     preg_match_all($pattern, $subject, $matches);

    //     $contents_info = $do_template_query[0]["document_content"];
                
        
    //     // $detail = $document_info_query[0];

    //     // $company_add = $this->write_address($detail['street_name'], $detail["unit_no1"], $detail["unit_no2"], $detail['building_name'], $detail["postal_code"], 'letter');

    //     // $firm_add = $this->write_address($detail['firm_street_name'], $detail["firm_unit_no1"], $detail["firm_unit_no2"], $detail['firm_building_name'], $detail["firm_postal_code"], 'letter');

    //     // $bank_add = $detail['add_line1'].', <br/>'.$detail['add_line2'].'.';
                  
    //     $toggle_array = $matches[0];

    //     if(count($toggle_array) != 0)
    //     {
    //         for($r = 0; $r < count($toggle_array); $r++)
    //         {
    //             $string1 = (str_replace('{{', '',$toggle_array[$r]));
    //             $string2 = (str_replace('}}', '',$string1));

 
                
    //             if($string2 == "today_date")
    //             {
    //                 $replace_string = $toggle_array[$r];

    //                 // $date = DateTime::createFromFormat('Y-m-d', $detail["sent_on_date"]);
    //                 // $new_date = $date->format('d F Y');   
                   
    //                 $content = $parse_data['today_date'];        
    //             }
    //             elseif($string2 == "client_name")
    //             {
    //                 $replace_string = $toggle_array[$r];

    //                 // $content = $document_info_query[0]["do_number"];
    //                 $content = $parse_data['client_name'];
    //             }
    //             elseif($string2 == "client_address")
    //             {
    //                 $replace_string = $toggle_array[$r];

    //                 // $date = DateTime::createFromFormat('Y-m-d', $detail["auth_date"]);
    //                 // $auth_date = $date->format('d F Y');

    //                 $content = $parse_data['client_address'];
    //             }
    //             elseif($string2 == "firm_name")
    //             {
    //                 $replace_string = $toggle_array[$r];

    //                 $content = $parse_data["firm_name"];
    //             }
    //             elseif($string2 == "fye_date")
    //             {
    //                 $replace_string = $toggle_array[$r];

    //                 // $date = DateTime::createFromFormat('Y-m-d', $detail["auth_date"]);
    //                 // $auth_date = $date->format('d F Y');

    //                 // $content = $document_info_query[0]["order_code"];
    //                 $content = $parse_data["fye_date"];
    //             }

    //             $contents_info = str_replace($replace_string, $content, $contents_info);


    //         }
    //     }

    //         $new_content_info = $contents_info;
       

    //         $obj_pdf->writeHTML($new_content_info, true, false, false, false, '');

        
    //         $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Stocktake Reminder ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf', 'F');

    //         $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

    //         // $link = 'https://'. $_SERVER['SERVER_NAME'] .'/generate_do/pdf/document/Delivery Order ('.DATE("Y-m-d",now()).').pdf';
    //         $link = 'http://'. $_SERVER['SERVER_NAME'] .'/audit/pdf/document/Stocktake Reminder ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf';

    //         $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> $_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Stocktake Reminder ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf');

    //         // kill session for doc_cus_id array
    //         $this->session->unset_userdata('doc_cus_id');
    //         $this->session->set_userdata(array('doc_cus_id' =>array())); // create new session back for session 'doc_cus_id'

    //         echo json_encode($data);
    //         return $data;
            
    // }

    public function generate_reminder_document()
    {
        $form_data = $this->input->post();
        $reminder_id = $form_data['reminder_id'];

        $reminder_detail = $this->stocktake_model->get_client_with_reminder_id($reminder_id);

        $client_detail = $this->client_model->getClientbyCode($reminder_detail['company_code']);
        $client_address = $this->write_address($client_detail->street_name, $client_detail->unit_no1, $client_detail->unit_no2, $client_detail->building_name, $client_detail->postal_code, 'letter');
        $firm = $this->client_model->getFirmbyCode($reminder_detail['company_code']);

        // print_r($reminder_detail);
        $reminder_detail['sent_on_date'] = date("d F Y", strtotime($reminder_detail['sent_on_date']));
        $reminder_detail['fye_date'] = date("d F Y", strtotime($reminder_detail['fye_date']));

        $parse_data = array(
                    'today_date'       => $reminder_detail['sent_on_date'],
                    'client_name'      => $client_detail->company_name,
                    'client_address'   => $client_address,
                    'fye_date'         => $reminder_detail['fye_date'],
                    'firm_name'        => $firm->firm_name
                );

        $header_company_info = $this->write_header($firm->servicing_firm, false);
        $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title = "Stocktake Reminder";
        $obj_pdf->SetTitle($title);
        // $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='<table style="width:100%; height: auto;"><tr style="text-align: center;"><td style="width:80%; height: auto;" align="center"></td></tr></table>', $tc=array(0,0,0), $lc=array(0,0,0));
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info, $tc=array(0,0,0), $lc=array(0,0,0));


        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('times');
        $obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        // $obj_pdf->SetFooterMargin(10);
        $obj_pdf->SetMargins(28, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $obj_pdf->SetAutoPageBreak(FALSE, 18);
        $obj_pdf->SetFont('times', '', 9);
        $obj_pdf->setFontSubsetting(false);
        $obj_pdf->setY(33);

        $obj_pdf->startPageGroup();

        $obj_pdf->AddPage();

        $counter = 0;


        $do_template_query = $this->db->query("select * from audit_doc_master_template where id = 4");

        $do_template_query = $do_template_query->result_array();

        // $this->load->helper('pdf_helper');

        $pattern = "/{{[^}}]*}}/";
        $subject = $do_template_query[0]["document_content"];
        preg_match_all($pattern, $subject, $matches);

        $contents_info = $do_template_query[0]["document_content"];
                
        
        // $detail = $document_info_query[0];

        // $company_add = $this->write_address($detail['street_name'], $detail["unit_no1"], $detail["unit_no2"], $detail['building_name'], $detail["postal_code"], 'letter');

        // $firm_add = $this->write_address($detail['firm_street_name'], $detail["firm_unit_no1"], $detail["firm_unit_no2"], $detail['firm_building_name'], $detail["firm_postal_code"], 'letter');

        // $bank_add = $detail['add_line1'].', <br/>'.$detail['add_line2'].'.';
                  
        $toggle_array = $matches[0];

        if(count($toggle_array) != 0)
        {
            for($r = 0; $r < count($toggle_array); $r++)
            {
                $string1 = (str_replace('{{', '',$toggle_array[$r]));
                $string2 = (str_replace('}}', '',$string1));

 
                
                if($string2 == "today_date")
                {
                    $replace_string = $toggle_array[$r];

                    // $date = DateTime::createFromFormat('Y-m-d', $detail["sent_on_date"]);
                    // $new_date = $date->format('d F Y');   
                   
                    $content = $parse_data['today_date'];        
                }
                elseif($string2 == "client_name")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["do_number"];
                    $content = $parse_data['client_name'];
                }
                elseif($string2 == "client_address")
                {
                    $replace_string = $toggle_array[$r];

                    // $date = DateTime::createFromFormat('Y-m-d', $detail["auth_date"]);
                    // $auth_date = $date->format('d F Y');

                    $content = $parse_data['client_address'];
                }
                elseif($string2 == "firm_name")
                {
                    $replace_string = $toggle_array[$r];

                    $content = $parse_data["firm_name"];
                }
                elseif($string2 == "fye_date")
                {
                    $replace_string = $toggle_array[$r];

                    // $date = DateTime::createFromFormat('Y-m-d', $detail["auth_date"]);
                    // $auth_date = $date->format('d F Y');

                    // $content = $document_info_query[0]["order_code"];
                    $content = $parse_data["fye_date"];
                }

                $contents_info = str_replace($replace_string, $content, $contents_info);


            }
        }

            $new_content_info = $contents_info;
       

            $obj_pdf->writeHTML($new_content_info, true, false, false, false, '');

        
            $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Stocktake Reminder ('.DATE("Y-m-d",now()).')_'.$reminder_id.'.pdf', 'F');
            chmod($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Stocktake Reminder ('.DATE("Y-m-d",now()).')_'.$reminder_id.'.pdf',0644);                

            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

            // $link = 'https://'. $_SERVER['SERVER_NAME'] .'/generate_do/pdf/document/Delivery Order ('.DATE("Y-m-d",now()).').pdf';
            $link = 'http://'. $_SERVER['SERVER_NAME'] .'/audit/pdf/document/Stocktake Reminder ('.DATE("Y-m-d",now()).')_'.$reminder_id.'.pdf';

            $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> $_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Stocktake Reminder ('.DATE("Y-m-d",now()).')_'.$reminder_id.'.pdf');

            // kill session for doc_cus_id array
            $this->session->unset_userdata('doc_cus_id');
            $this->session->set_userdata(array('doc_cus_id' =>array())); // create new session back for session 'doc_cus_id'

            echo json_encode($data);
            return $data;
            
    }

    // send email controller
    public function send_reminder_report()
    {
        $month = date('F');
        $year = date('Y');

        $next_month = date('F Y', strtotime("+1 month"));

        //for sql condition to retrieve reminder
        $year_month = date('Y-m');

        //to get PIC this of this month
        $pic_info = $this->bank_model->get_bank_confirm_pic($month, $year);

        //get all sent reminder 
        $sent_reminder = $this->stocktake_model->get_sent_reminder($year_month);
        // echo sizeof($sent_reminder);

        //get all arranged reminder
        $arranged = $this->stocktake_model->get_arranged_stocktake($year_month);
        // echo sizeof($arranged);

        $report_content = '<p>Sent: '.sizeof($sent_reminder).'<br/>Arranged: '.sizeof($arranged).'<br/></p>';

        $parse_report_data = array(
            'pic_name'  => $pic_info[0]['first_name']." ".$pic_info[0]['last_name'],
            'month'      => $next_month,
            'report_content' => $report_content
        );

        $pic_email = $pic_info[0]['email'];
        if($pic_email == '"james@acumenbizcorp.com.sg"'){
            $pic_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$pic_email);
        }
    

        $manager_email = "penny@aaa-global.com";
        $partner_email = "woellywilliam@acumenbizcorp.com.sg";

        //get report message template
        $report_msg = file_get_contents('./application/modules/stocktake/email_templates/stocktake_summary_report.html');

        $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");
        $subject = 'Inventories Count Observation - '.$next_month;
        $report_message = $this->parser->parse_string($report_msg, $parse_report_data);

        $cc_email = array();
        array_push($cc_email, array("email" => $manager_email), array("email" => $partner_email));

        $receiver_email = array();
        array_push($receiver_email, array("email" => $pic_email) );



        //SMA
        // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

        //SEND IN BLUE
        // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray

        if(sizeof($sent_reminder)!= sizeof($arranged))
        {
            if(date('d') == 20 || date('d') == 27)
            {
                $this->send_email($subject, $sender_email, $receiver_email, $report_message, $cc_email, "");
            }
            else
            {
                $this->send_email($subject, $sender_email, $receiver_email, $report_message, "", "");
            }
        }
        



    }

    


    public static function getAuditorName() {

        //try {
        //echo $nationalityId;
        $ci =& get_instance();

        $query = "SELECT id, status FROM audit_status";

        $result = $ci->db->query($query);
        $result = $result->result_array();
        //echo json_encode($result);
        if(!$result) {
          throw new exception("Status not found.");
        }

        $res = array();
        foreach($result as $row) {
          $res[$row['id']] = $row['status'];
        }
        //$res = json_decode($res);
        $ci =& get_instance();
        $select_auth_status = $ci->session->userdata('auth_status');
        $ci->session->unset_userdata('auth_status');

        /*if($nationalityId != "null")
        {
        $select_nationality = $nationalityId;
        }*/
        /*else
        {
        $select_nationality = "null";
        }*/
        //$select_country = $select_country->result_array();
        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Acquried By fetched successfully.", 'result'=>$res, 'select_auth_status'=>$select_auth_status);
        /*} catch (Exception $e) {
        $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
        }*/ /*finally {
        echo json_encode($data);
        //return $data;

        }*/
        echo json_encode($data);
    }

    public function stocktake_arrangement_filter()
    {
        $form_data = $this->input->post();

        $month    = isset($form_data['month'])?$form_data['month']:"";
        $auditor   = isset($form_data['auditor'])?$form_data['auditor']:"";
        $arranged   = isset($form_data['arranged'])?$form_data['arranged']:"";
        $department   = isset($form_data['department'])?$form_data['department']:"";
        $office   = isset($form_data['office'])?$form_data['office']:"";

        if($month == ""){
            $month = '%%';
        }
        else 
        {
            $month = "%".date_format(date_create($month),"Y-m")."%";
            // echo $month;
        }

        // echo $auditor;
        if($auditor == null || $auditor == "null"){
            $auditor = "empty" ;

        }
        else
        {
            $auditor = "(find_in_set(".$auditor;
            $auditor = str_replace("," , ", auditor_id) != 0 or find_in_set(" , $auditor);
            $auditor = $auditor.", auditor_id) != 0) ";

        }
        // $auditor = "(find_in_set(".$auditor;
        // $auditor = str_replace("," , ", auditor_id) != 0 or find_in_set(" , $auditor);
        // $auditor = $auditor.", auditor_id) != 0) ";

        // print_r($auditor);

        if($arranged == ""){
            $arranged = '%%';
        }
 
        $result = $this->stocktake_model->stocktake_arrangement_filter($month, $auditor, $arranged, $office, $department);
        
        echo json_encode($result);
    }

    public function stocktake_subsequent_filter()
    {
        $form_data = $this->input->post();

        $month    = isset($form_data['month'])?$form_data['month']:"";
        $auditor   = isset($form_data['auditor'])?$form_data['auditor']:"";
        $department   = isset($form_data['department'])?$form_data['department']:"";
        $office   = isset($form_data['office'])?$form_data['office']:"";

        if($month == ""){
            $month = '%%';
        }
        else 
        {
            $month = "%".date_format(date_create($month),"Y-m")."%";
            // echo $month;
        }

        // echo $auditor;
        if($auditor == null || $auditor == "null"){
            $auditor = "empty" ;

        }
        else
        {
            $auditor = "(find_in_set(".$auditor;
            $auditor = str_replace("," , ", all_auditor_id) != 0 or find_in_set(" , $auditor);
            $auditor = $auditor.", all_auditor_id) != 0) ";

        }
        // $auditor = "(find_in_set(".$auditor;
        // $auditor = str_replace("," , ", auditor_id) != 0 or find_in_set(" , $auditor);
        // $auditor = $auditor.", auditor_id) != 0) ";

        // print_r($auditor);

        $result = $this->stocktake_model->stocktake_subsequent_filter($month, $auditor, $office, $department);
        
        echo json_encode($result);
    }
   
    public function send_email($subject, $sender_email, $receiver_email, $content, $cc = null, $attachment = null)
    {
        // $subject-string, $sender_email-object, $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray

        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

        $apiInstance = new SendinBlue\Client\Api\SMTPApi(
          // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
          // This is optional, `GuzzleHttp\Client` will be used as default.
          new GuzzleHttp\Client(),
          $config
        );

        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
        $sendSmtpEmail['subject'] = $subject;
        // $sender_email = $from_email;
        $sendSmtpEmail['sender'] = $sender_email;
        $sendSmtpEmail['to'] = $receiver_email;
        $sendSmtpEmail['htmlContent'] = $content;
        if($cc != null)
        {
            $sendSmtpEmail['cc'] = $cc;
        }
        // $attachment['content'] = base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/AA-20200013.pdf'));
        // $attachment['name'] = "AA-20200013.pdf";
        //array_push($pdfDocPath, json_decode($email_queue_info[$i]['attachment'], true));
        if($attachment != null)
        {
             $sendSmtpEmail['attachment'] = $attachment;
        }
       
        //print_r($sendSmtpEmail);

        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            //print_r($result);
            if ($result) 
            {
            // $email_queue['sended'] = 1;
            // $email_queue['sendInBlueResult'] = $result;
            // $this->db->update("email_queue",$email_queue,array("id" => $email_queue_info[$i]['id']));
                // echo 'Your Email has successfully been sent.';
            }
        } catch (Exception $e) {
            echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function write_address($street_name, $unit_no1, $unit_no2, $building_name, $postal_code, $type)
    {
        $unit = '';
        $unit_building_name = '';

        $comma = '';

        if($type == "normal")
        {
            $br = '';
        }
        elseif($type == "letter")
        {
            $br = ' <br/>';
        }
        elseif($type == "letter with comma")
        {
            $br = ' <br/>';
            $comma = ',';
        }
        elseif($type == "comma")
        {
            $br = '';
        }

        // Add unit
        if(!empty($unit_no1) && !empty($unit_no2))
        {
            $unit = '#' . $unit_no1 . '-' . $unit_no2 . $comma;
        }

        // Add building
        if(!empty($building_name) && !empty($unit))
        {
            $unit_building_name = $unit . ' ' . $building_name . $comma;
        }
        elseif(!empty($unit))
        {
            $unit_building_name = $unit;
        }
        elseif(!empty($building_name))
        {
            $unit_building_name = $building_name . $comma;
        }
        //print_r($street_name . $br . $unit_building_name . $br . 'Singapore ' . $postal_code);
        if(!empty($unit))
        {
            $address = $street_name . $comma . $br . $unit_building_name . $br . 'SINGAPORE ' . $postal_code;
        }
        elseif(!empty($building_name))
        {
            $address = $street_name . $comma . $br . $building_name . $comma . $br . 'SINGAPORE ' . $postal_code;
        }
        else
        {
            $address = $street_name . $comma . $br . 'Singapore ' . $postal_code;
        }
        return $address;
    }

    public function write_header($firm_id, $use_own_header)
    {
        $query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
                                                JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
                                                JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
                                                JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
                                                where firm.id = '".$firm_id."'");
        $query = $query->result_array();

        // Calling getimagesize() function 
        list($width, $height, $type, $attr) = getimagesize("uploads/logo/" . $query[0]["file_name"]); 

        $different_w_h = (float)$width - (float)$height;

        if((float)$width > (float)$height && $different_w_h > 100)
        {
            //before width is 25, height is 73.75
            $td_width = 25;
            $td_height = 73.75;
        }
        else
        {
            $td_width = 15;
            $td_height = 83.75;
        }

        if(!$use_own_header){
            if(!empty($query[0]["file_name"]))
            {
                $img = '<img src="uploads/logo/'. $query[0]["file_name"] .'" height="55" />';
            }
            else
            {
                $img = '';
            }
        }

        if(!$use_own_header)
        {
            $header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
                    <tbody>
                    <tr style="height: 60px;">
                        <td style="text-align: left; height: 60px; padding: 5%;" width="'.$td_width.'%" align="center">
                            <table style="border-collapse: collapse; width: 100%;" border="0">
                            <tbody>
                            <tr>
                            <td style="text-align: left; height: 60px;" align="center"><p>'. $img .'  </p></td>
                            </tr>
                            </tbody>
                            </table>
                        </td>
                        <td style="text-align: left;" width="1.25%">&nbsp;</td>
                        <td style="height: 60px;" width="'.$td_height.'%"><span style="font-size: 18pt;">'.$query[0]["name"].'</span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />Address: '. $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"] .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $query[0]["fax"] .'&nbsp;</span></td>
                    </tr>
                    </tbody>
                    </table>';
        }
        else
        {
            $header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
                                <tbody>
                                <tr style="height: 80px;"><td style="height: 60px;"></td></tr>
                                </tbody>
                               </table>';
        }

        return $header_content;
    }

    public static function getStocktakeClient() 
    {
        $ci =& get_instance();

        // $this->db->select('client.id, client.company_code, client.company_name');
        // $this->db->from('client');
        // // $this->db->join('audit_client', 'audit_client.company_code = client.company_code', 'left');
        // $this->db->join('client_billing_info', 'client_billing_info.company_code = client.company_code', 'right');
        // $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service AND our_service_info.service_type = 1', 'right');
        // // $query = $this->db->get();

        $query = 'SELECT reminder.company_code, reminder.id as reminder_id,reminder.fye_date, client.company_name  FROM audit_stocktake_reminder reminder
                LEFT JOIN (SELECT company_code,MAX(fye_date) as lastest_fye FROM audit_stocktake_reminder GROUP BY company_code) subq ON reminder.company_code = subq.company_code
                LEFT JOIN client ON reminder.company_code = client.company_code 
                LEFT JOIN audit_stocktake_reminder_setting reminder_setting ON reminder_setting.company_code = reminder.company_code
                WHERE reminder.fye_date = subq.lastest_fye 
                AND NOT EXISTS (SELECT reminder_id FROM audit_stocktake_arrangement sta WHERE sta.reminder_id = reminder.id AND deleted = 0)
                AND client.deleted = 0
                AND reminder_setting.reminder_flag = 1
                GROUP BY company_code';

        // echo $query;

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        // if ($result->num_rows() > 0) 
        // {

            $result = $result->result_array();

            // if(!$result) {
            //   throw new exception("Client Name not found.");
            // }

            $res = array();
            foreach($result as $row) {
                if($row['company_name'] != null)
                {

                    $res[$row['reminder_id']] = $this->encryption->decrypt($row['company_name']);
                }
              
            }
            //$res = json_decode($res);
            $ci =& get_instance();
            $selected_reminder_id = $ci->session->userdata('reminder_id');
            $ci->session->unset_userdata('reminder_id');

            $selected_client_name = $this->stocktake_model->get_client_with_reminder_id($selected_reminder_id);
            // print_r($selected_client_name);

            // print_r($res);

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client Name fetched successfully.", 'result'=>$res, 'selected_client_name'=>$selected_client_name);

            echo json_encode($data);
        // }
        // else
        // { 
        //     $res = array();

        //     $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_client_name'=>'');

        //     echo json_encode($data);
        // }
    }

    public static function getFyeDate() 
    {
        $ci =& get_instance();
        $form_data = $this->input->get();

        if(isset($form_data['reminder_id']))
        {
          
            $query = "SELECT fye_date FROM audit_stocktake_reminder reminder
                      WHERE reminder.id = '".$form_data['reminder_id']."' 
                      LIMIT 1";
            
        }
        

        // echo $query;

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("No FYE date.");
            }

            // $res = array();
            // foreach($result as $row) {
            //     if($row['bank_name'] != null)
            //     {
            //         $res[$row['id']] = $row['bank_name'];
            //     }
              
            // }
            //$res = json_decode($res);
            $res = $result[0]['fye_date'];
            $res = DateTime::createFromFormat('Y-m-d', $res )->format('d F Y');

            $ci =& get_instance();
            $selected_bank_name = $ci->session->userdata('auth_bank_id');
            $ci->session->unset_userdata('auth_bank_id');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"FYE date fetched successfully.", 'result'=>$res, 'selected_bank_name'=>$selected_bank_name);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'fail', 'tp'=>0, 'msg'=>"No FYE date set for selected company.", 'result'=>$res, 'selected_bank_name'=>'');

            echo json_encode($data);
        }
    }


    public function filter_review_points()
    {
        $form_data = $this->input->post();

        $cleared   = isset($form_data['cleared'])?$form_data['cleared']:"";
        $sta_id   = isset($form_data['sta_id'])?$form_data['sta_id']:"";
       
        // echo $auditor;
        if($cleared == ""){
            $cleared = '%%' ;

        }

        // echo $company_code;
        $result = $this->stocktake_model->filter_review_points($cleared, $sta_id);
        
        echo json_encode($result);
    }

    public function check_cleared_points()
    {
        $form_data = $this->input->post();

        $sta_id = isset($form_data['sta_id'])?$form_data['sta_id']:"";
        $data = $this->stocktake_model->get_uncleared_points($sta_id);
        $check_exist_rp = $this->stocktake_model->get_rp_existance($sta_id);

        if(count($data) > 0 )
        {
            echo json_encode(array("Status" => 1, 'cleared' => "uncleared"));
        }
        else if (count($data) == 0 && count($check_exist_rp) > 0)
        {
            echo json_encode(array("Status" => 1, 'cleared' => "cleared"));
        }
        else 
        {
            echo json_encode(array("Status" => 1, 'cleared' => "no_rp"));

        }


    }

    public function clear_rp()
    {
        // $data['company_code'] = $_POST['company_code'];
        //$data['family_info_id']=$_POST['family_info_id'][$i];
        // $data['paf_child_id']=$_POST['paf_index'];


        $q = $this->db->get_where("audit_stocktake_review_point", array("id" => $_POST['review_point_id']));

        if (!$q->num_rows())
        {   
            echo json_encode(array("Status" => 0, 'message' => 'Failed to update information', 'title' => 'Failed'));
        }
        else
        {
           
            $data['response_updated_by'] = $this->session->userdata('user_id');
            $data['response_updated_at'] = date("Y-m-d H:i:s");
            $data['point_updated_by'] = $this->session->userdata('user_id');
            $data['point_updated_at'] = date("Y-m-d H:i:s");
            $data['cleared'] = $_POST['cleared'];
            


            $this->db->update("audit_stocktake_review_point",$data,array("id" => $_POST['review_point_id']));

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
    }

    public function delete_review_point ()
    {
        $id = $_POST["rp_id"];

        $data["deleted"] = 1;

        $this->db->update("audit_stocktake_review_point", $data, array('id'=>$id));

        echo json_encode(array("Status" => 1));
                
    }

    public function delete_subsequent_doc(){

        $form_data = $this->input->post();

        $result = $this->stocktake_model->delete_subsequent_doc($form_data['doc_id']);

        echo $result;
    }






}



class MYPDF extends TCPDF {
    public function Header() {
        $headerData = $this->getHeaderData();
        $this->writeHTMLCell(0, 0, 20, 7, $headerData['string'], 0, 0, false, "C", true);
   }

   public function Footer() {
        // Position at 25 mm from bottom
        $this->SetY(-18);
        // $this->SetX(10);


        // Set font
        $this->SetFont('helvetica', 'B', 8);

        $logoX = 160; // The logo will be displayed on the left side close to the border of the page
        $logoFileName = base_url()."img/footer_img.png";
        $logoWidth = 40; // 15mm
        $logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);

        $this->Cell(0,0, $logo, 0, 0, 'R');
        
        // $this->Cell(0, 0, '53 Ubi Ave 3 #01-01 Travelite Building, Singapore 408863', 0, 0, 'C');
        // $this->Ln();
        // $this->Cell(0, 0,'Tel:(65) 6785 8000      Fax:(65) 6785 7000      Email: jonfresh@singnet.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // // Page number
        // if (empty($this->pagegroups)) {
        //     $pagenumtxt = 'Page '.' '.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
        // } else {
        //     $pagenumtxt = 'Page '.' '.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
        // }
        // $this->SetFont('helvetica', 'B', 10);
        // $this->Cell(0, 5, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // $this->Ln(1);
        // $this->SetFont('helvetica', 'B', 8);
        // $this->Cell(170, 15, 'Powered by ', 0, false, 'C', 0, '', 0, false, 'T', 'M');


  //       $logoX = 110; // 186mm. The logo will be displayed on the right side close to the border of the page
        // $logoFileName = base_url()."/assets/uploads/logos/Audit_logo.png";
        // $logoWidth = 10; // 15mm
        // // $logo = $this->Image($logoFileName, $logoX, $this->GetY() + 5, $logoWidth);

        // $this->Ln(4);
  //       $this->SetFont('helvetica', 'B', 8);
  //       $this->Cell(0, 15, 'enquiry@acumenbizcorp.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //$this->GetY()
   }
   
}

class NOFOOTER_PDF extends TCPDF {
    public function Header() {
        $headerData = $this->getHeaderData();
        $this->writeHTMLCell(0, 0, 20, 12, $headerData['string'], 0, 0, false, "C", true);
   }

   public function Footer() {
        // Position at 25 mm from bottom
        // $this->SetY(-16);
        // $this->SetX(10);


        // // Set font
        // $this->SetFont('helvetica', 'B', 8);

        // $logoX = 20; // The logo will be displayed on the left side close to the border of the page
        // $logoFileName = base_url()."img/footer_img.png";
        // $logoWidth = 50; // 15mm
        // $logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);

        // $this->Cell(0,0, $logo, 0, 0, 'R');
        
        // $this->Cell(0, 0, '53 Ubi Ave 3 #01-01 Travelite Building, Singapore 408863', 0, 0, 'C');
        // $this->Ln();
        // $this->Cell(0, 0,'Tel:(65) 6785 8000      Fax:(65) 6785 7000      Email: jonfresh@singnet.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // // Page number
        // if (empty($this->pagegroups)) {
        //     $pagenumtxt = 'Page '.' '.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
        // } else {
        //     $pagenumtxt = 'Page '.' '.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
        // }
        // $this->SetFont('helvetica', 'B', 10);
        // $this->Cell(0, 5, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // $this->Ln(1);
        // $this->SetFont('helvetica', 'B', 8);
        // $this->Cell(170, 15, 'Powered by ', 0, false, 'C', 0, '', 0, false, 'T', 'M');


  //       $logoX = 110; // 186mm. The logo will be displayed on the right side close to the border of the page
        // $logoFileName = base_url()."/assets/uploads/logos/Audit_logo.png";
        // $logoWidth = 10; // 15mm
        // // $logo = $this->Image($logoFileName, $logoX, $this->GetY() + 5, $logoWidth);

        // $this->Ln(4);
  //       $this->SetFont('helvetica', 'B', 8);
  //       $this->Cell(0, 15, 'enquiry@acumenbizcorp.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //$this->GetY()
   }


   
}


