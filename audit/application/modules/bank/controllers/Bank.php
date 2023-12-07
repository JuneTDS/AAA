<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once('assets/vendor/tcpdf/tcpdf.php');


class Bank extends MX_Controller
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
        $this->load->model('bank_model');
        $this->load->model('client/client_model');
        $this->load->model('leave/leave_model');
    }

    public function index()
    {   
        // $this->meta['page_name'] = 'Bank Confirmation';
        if(isset($_GET['tab_active']))
        {
            $this->session->set_userdata("tab_active", $_GET['tab_active']);
        }

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $this->data['bank_list'] = $this->bank_model->get_bank_list();
        $this->data['bank_auth'] = $this->bank_model->get_bank_auth();
        $this->data['bank_auth_deactivate'] = $this->bank_model->get_bank_auth_deactivate();
        $temp_bank_confirm_setting = $this->bank_model->get_bank_confirm_setting();
        // print_r($temp_bank_confirm_setting);
        $this->data['bank_confirm_setting'] = $temp_bank_confirm_setting;
        $this->data['disable_month'] = $this->bank_model->get_disable_month();
        $this->data['bank_confirm'] = $this->bank_model->get_bank_confirm();
        $this->data['all_bank_auth_files'] = $this->bank_model->get_bank_auth_files();

        $this->data['status_dropdown'] = $this->bank_model->get_status_dropdown_list();
        // $this->data['holiday_list'] = $this->bank_model->get_holiday_list();
        // $this->data['type_of_leave_list'] = $this->bank_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->bank_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->bank_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->bank_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->bank_model->get_choose_carry_forward_list();

        $long = array(
            'Please Select',
            'January', 
            'February', 
            'March', 
            'April', 
            'May', 
            'June', 
            'July', 
            'August', 
            'September', 
            'October', 
            'November', 
            'December'
        );
        $temp_status_dropdown = $this->data['status_dropdown'];
        array_unshift($temp_status_dropdown , 'Please Select');
        // $temp_status_dropdown[0] = "Please Select";
        $this->data['status_dropdown_filter'] = $temp_status_dropdown;

        $this->data['month'] = $long;

        $bc = array(array('link' => '#', 'page' => 'Bank Confirmations'));
        $meta = array('page_title' => 'Bank Confirmation', 'bc' => $bc, 'page_name' => 'Bank Confirmations');

        // $this->page_construct('index.php', $this->meta, $this->data);
        $this->page_construct('index.php', $meta, $this->data);
    }

    public function add_bank_auth()
    {
        $this->meta['page_name'] = 'Bank Authorization';

        // $this->data['active_tab'] = $this->session->userdata('tab_active');
        // $this->session->unset_userdata('tab_active');
        $this->session->set_userdata("tab_active", "bank_auth");

        // $this->data['holiday_list'] = $this->bank_model->get_holiday_list();
        // $this->data['type_of_leave_list'] = $this->bank_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->bank_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->bank_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->bank_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->bank_model->get_choose_carry_forward_list();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Bank Authorization', base_url('bank'));
        $this->mybreadcrumb->add('Add Bank Authorization', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Add Bank Authorization'));
        $meta = array('page_title' => 'Add Bank Authorization', 'bc' => $bc, 'page_name' => 'Add Bank Authorization');

        $this->page_construct('add_bank_auth.php', $meta, $this->data);
    }

    public function add_bank_confirm()
    {
        // $this->meta['page_name'] = 'Bank Confirmation';

        // $this->data['active_tab'] = $this->session->userdata('tab_active');
        // $this->session->unset_userdata('tab_active');

        $this->session->set_userdata("tab_active", "bank_confirm");

        // $this->data['holiday_list'] = $this->bank_model->get_holiday_list();
        // $this->data['type_of_leave_list'] = $this->bank_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->bank_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->bank_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->bank_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->bank_model->get_choose_carry_forward_list();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Bank Confirmation', base_url('bank'));
        $this->mybreadcrumb->add('Add Bank Confirmation', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Add Bank Confirmation'));
        $meta = array('page_title' => 'Add Bank Confirmation', 'bc' => $bc, 'page_name' => 'Add Bank Confirmation');

        $this->page_construct('add_bank_confirm.php', $meta, $this->data);
    }

    public function edit_bank_auth($id)
    {
        // $this->meta['page_name'] = 'Edit Bank Authorization';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $temp_edit_bank_auth = $this->bank_model->get_edit_bank_auth($id);

        // print_r($temp_edit_bank_auth);

        $date = DateTime::createFromFormat('Y-m-d', $temp_edit_bank_auth[0]->auth_date );
        $temp_edit_bank_auth[0]->auth_date = $date->format('d/m/Y');

        $this->data['edit_bank_auth'] = $temp_edit_bank_auth;
        // $this->data['holiday_list'] = $this->bank_model->get_holiday_list();
        // $this->data['type_of_leave_list'] = $this->bank_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->bank_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->bank_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->bank_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->bank_model->get_choose_carry_forward_list();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Bank Authorization', base_url('bank'));
        $this->mybreadcrumb->add('Edit Bank Authorization', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Edit Bank Authorization'));
        $meta = array('page_title' => 'Edit Bank Authorization', 'bc' => $bc, 'page_name' => 'Edit Bank Authorization');

        $this->page_construct('add_bank_auth.php', $meta, $this->data);
    }

    public function save_bank_auth()
    {
        $this->session->set_userdata("tab_active", "bank_auth");
        $form_data = $this->input->post();

        $date = DateTime::createFromFormat('d/m/Y', $form_data['auth_date']);
        $auth_date = $date->format('Y-m-d');

        $get_firm_from_service = $this->bank_model->get_firm_from_service($form_data['client_name']);
        // print_r($get_firm_from_service);
        $firm_id = 0;
        foreach ($get_firm_from_service as $key => $firm) {
            if($firm["servicing_firm"] != null && $firm["servicing_firm"] != 0)
            {
                $firm_id = $firm["servicing_firm"];
            }

        }



        // if($get_firm_from_service != null && $get_firm_from_service[0]["servicing_firm"] != 0)
        if($firm_id != 0)
        {
            $data = array(
                'company_code' => $form_data['client_name'],
                'bank_id' => $form_data['bank_name'],
                'auth_date' => $auth_date,
                // 'auth_status' => $form_data['auth_status'],
                'firm_id' => $firm_id
            );

            $result = $this->bank_model->submit_bank_auth($data);

            $data = array('status'=>'success', 'bank_auth_id'=>$result);
          
        }
        else
        {
            $data = array('status'=>'fail', 'bank_auth_id'=>"");
        }

        echo json_encode($data);
        
    }

    public function save_bank_confirm()
    {
        $this->session->set_userdata("tab_active", "bank_confirm");
        $form_data = $this->input->post();

        $date = DateTime::createFromFormat('d/m/Y', $form_data['fye_date']);
        $fye_date = $date->format('Y-m-d');

        $send_date = date("Y-m-d");

       // echo $form_data['auth_date'];

        $data = array(
            'company_code' => $form_data['client_name'],
            'bank_id' => $form_data['bank_name'],
            'fye_date' => $fye_date,
            // 'confirm_status' => $form_data['confirm_status'],
            'sent_on_date' => $send_date
        );

        $result = $this->bank_model->submit_bank_confirm($data);

        $data = array('status'=>'success', 'bank_confirm_id'=>$result);

        echo json_encode($data);
    }

    public function delete_bank(){

        $form_data = $this->input->post();

        $result = $this->bank_model->delete_bank($form_data['bank_id']);

        echo $result;
    }

    public function delete_bank_auth(){
        $this->session->set_userdata("tab_active", "bank_auth");

        $form_data = $this->input->post();

        $result = $this->bank_model->delete_bank_auth($form_data['bank_auth_id']);

        echo $result;
    }

    public function delete_bank_confirm_setting(){
        $this->session->set_userdata("tab_active", "bank_confirm_setting");

        $form_data = $this->input->post();

        $result = $this->bank_model->delete_bank_confirm_setting($form_data['bank_confirm_setting_id']);

        echo $result;
    }

    public function delete_bank_confirm(){
        $this->session->set_userdata("tab_active", "bank_confirm");

        $form_data = $this->input->post();

        $result = $this->bank_model->delete_bank_confirm($form_data['bank_confirm_id']);

        echo $result;
    }

    public function update_bank_auth(){

        $form_data = $this->input->post();
        
        if($form_data['active'] && isset($form_data['is_bank']))
        {
            $this->session->set_userdata("tab_active", "bank_auth_deactive");
        }
        else
        {
            $this->session->set_userdata("tab_active", "bank_auth");
        }

        $result = $this->bank_model->update_bank_auth($form_data['bank_auth_id'], $form_data['active']);

        echo $result;
    }

    public function uploadBaDoc()
    {
        $this->session->set_userdata("tab_active", "bank_auth");
        if(isset($_FILES['ba_docs']))
        {
            $filesCount = count($_FILES['ba_docs']['name']);

            for($i = 0; $i < $filesCount; $i++)
            {   //echo json_encode($_FILES['uploadimages']);
                $_FILES['ba_doc']['name']     = $_FILES['ba_docs']['name'][$i];
                $_FILES['ba_doc']['type']     = $_FILES['ba_docs']['type'][$i];
                $_FILES['ba_doc']['tmp_name'] = $_FILES['ba_docs']['tmp_name'][$i];
                $_FILES['ba_doc']['error']    = $_FILES['ba_docs']['error'][$i];
                $_FILES['ba_doc']['size']     = $_FILES['ba_docs']['size'][$i];

                $uploadPath = './document/bank_auth';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = '*';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('ba_doc'))
                {
                    $fileData = $this->upload->data();
                    $uploadData[$i]['bank_auth_id'] = $_POST['ba_id'];
                    $uploadData[$i]['file_path'] = "bank_auth";
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
                $this->db->insert_batch('audit_bank_auth_document',$uploadData);
                // print_r($uploadData);
                
            }


        }

        if (!is_null($this->session->userdata('ba_files_id')) && count($this->session->userdata('ba_files_id')) != 0)
            {
                $ba_files_id = $this->session->userdata('ba_files_id');
                $this->session->unset_userdata('ba_files_id');
                for($i = 0; $i < count($ba_files_id); $i++)
                {
                    $files = $this->db->query("select * from audit_bank_auth_document where id='".$ba_files_id[$i]."'");
                    $file_info = $files->result_array();

                    $this->db->where('id', $ba_files_id[$i]);

                    if(file_exists("./document/".$file_info[0]["file_path"]."/".$file_info[0]["file_name"]))
                    {
                        unlink("./document/".$file_info[0]["file_path"]."/".$file_info[0]["file_name"]);

                    }
                    
                    $this->db->delete('audit_bank_auth_document', array('id' => $ba_files_id[$i]));
                }
            }

        
        if(isset($fileData))
        {
            echo json_encode($fileData['file_name']);
        }
        else
        {
            echo json_encode("empty");
        }

        
    }

    public function deleteBaFile($id)
    {
        if($this->session->userdata('ba_files_id') != null)
        {
             $ba_files_id = $this->session->userdata('ba_files_id');
        }
        else
        {
            $ba_files_id = array();
        }
       
        array_push($ba_files_id, $id);
        $this->session->set_userdata(array(
            'ba_files_id'  =>  $ba_files_id,
        ));

        echo json_encode($ba_files_id);
    }

    public function uploadBcDoc()
    {
        $this->session->set_userdata("tab_active", "bank_confirm");
        if(isset($_FILES['bc_docs']))
        {
            $filesCount = count($_FILES['bc_docs']['name']);

            for($i = 0; $i < $filesCount; $i++)
            {   //echo json_encode($_FILES['uploadimages']);
                $_FILES['bc_doc']['name']     = $_FILES['bc_docs']['name'][$i];
                $_FILES['bc_doc']['type']     = $_FILES['bc_docs']['type'][$i];
                $_FILES['bc_doc']['tmp_name'] = $_FILES['bc_docs']['tmp_name'][$i];
                $_FILES['bc_doc']['error']    = $_FILES['bc_docs']['error'][$i];
                $_FILES['bc_doc']['size']     = $_FILES['bc_docs']['size'][$i];

                $uploadPath = './uploads/bank_images_or_pdf';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = '*';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('bc_doc'))
                {
                    $fileData = $this->upload->data();
                    $uploadData[$i]['bank_confirm_id'] = $_POST['bc_id'];
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
                $this->db->insert_batch('audit_bank_confirm_document',$uploadData);
                // print_r($uploadData);
                
            }


        }

        // print_r();
        if (!is_null($this->session->userdata('bc_files_id')) && count($this->session->userdata('bc_files_id')) != 0)
        {
            $bc_files_id = $this->session->userdata('bc_files_id');
            $this->session->unset_userdata('bc_files_id');
            for($i = 0; $i < count($bc_files_id); $i++)
            {
                $files = $this->db->query("select * from audit_bank_confirm_document where id='".$bc_files_id[$i]."'");
                $file_info = $files->result_array();

                $this->db->where('id', $bc_files_id[$i]);

                if(file_exists("./uploads/bank_images_or_pdf/".$file_info[0]["file_name"]))
                {
                    unlink("./uploads/bank_images_or_pdf/".$file_info[0]["file_name"]);

                }
                
                $this->db->delete('audit_bank_confirm_document', array('id' => $bc_files_id[$i]));
            }
        }

        
        if(isset($fileData))
        {
            echo json_encode($_POST['bc_id']);
        }
        else
        {
            echo json_encode("empty");
        }

        
    }

    public function deleteBcFile($id)
    {
        if($this->session->userdata('bc_files_id') != null)
        {
             $bc_files_id = $this->session->userdata('bc_files_id');
        }
        else
        {
            $bc_files_id = array();
        }
       
        array_push($bc_files_id, $id);
        $this->session->set_userdata(array(
            'bc_files_id'  =>  $bc_files_id,
        ));

        echo json_encode($bc_files_id);
    }

    public function clear_delete_auth_session()
    {

        $this->session->unset_userdata('ba_files_id');
    }

    public function clear_delete_confirm_session()
    {

        $this->session->unset_userdata('bc_files_id');
    }


    public function move_auth_to_paf()
    {
        $this->session->set_userdata("tab_active", "bank_auth");
        $user_id = $this->session->userdata('user_id');
        $form_data = $this->input->post();
        $i = 0;

        $selected_move_auth = $form_data['selected_move_auth'];
        
        //change bank authorization move status to 1
        $this->bank_model->move_bank_auth($selected_move_auth['id']);

        $paf_parent_id = $this->bank_model->get_paf_parent_id($selected_move_auth['company_code']);

        //insert child paf
        $c_data   = array('company_code' =>  $selected_move_auth['company_code'],
                        'parent_id'    =>  $paf_parent_id,
                        'index_no'     =>  "",
                        'text'         =>  $selected_move_auth['bank_name'],
                        'type'         =>  "dynmc"
                    );

        $paf_child_id = $this->client_model->insert_paf_child($c_data);

        //move documents
        $auth_documents = $this->bank_model->get_bank_auth_doc_byId($selected_move_auth['id']);

        foreach ($auth_documents as $file_key => $each) {
                # code...

            $upload_data[$i]['paf_child_id'] = $paf_child_id;
            $upload_data[$i]['file_name'] = $each->file_name;
            $upload_data[$i]['file_path'] = $each->file_path;

            $i++;
        }

        //log upload document to paf
        $data   = array(
                    'paf_id'     => $paf_child_id,
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $user_id,
                    'paf_log'    => "upload document(s)",
                    'company_code' => $selected_move_auth['company_code']
                  );

        $this->db->insert('audit_paf_log', $data);

        if(!empty($upload_data))
        {
            $this->db->insert_batch('audit_paf_document',$upload_data);
            // print_r($uploadData);
            
        }


    }

    // send email controller
    public function auto_generate_bank_confirm()
    {
        $month = date('F');
        $year = date('Y');

        //for sql condition to retrieve bank confirmation report
        $year_month = date('Y-m');

        $pic_info = $this->bank_model->get_bank_confirm_pic($month, $year);

        $create_bc_list = $this->bank_model->get_create_bc_list($month, $year);
        $bc_id_list = array();


        // print_r($create_bc_list);
        foreach ($create_bc_list as $key => $create_bc) {
            $date = DateTime::createFromFormat('d F Y', $create_bc['year_end']);
            $fye_date = $date->format('Y-m-d');

            $send_date = date("Y-m-d");

           // echo $form_data['auth_date'];

            $data = array(
                'company_code' => $create_bc['company_code'],
                'bank_id' => $create_bc['bank_id'],
                'fye_date' => $fye_date,
                'confirm_status' => 1,
                'sent_on_date' => $send_date
            );

            $result = $this->bank_model->submit_bank_confirm($data);
            array_push($bc_id_list, $result);

        }

        foreach ($bc_id_list as $key => $bc_id) {
            $data = $this->generate_confirm_document($key+1, $bc_id);
            $this->zip->read_file($data["path"]);
        }

        $this->zip->archive($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/bank_confirmation('.$month.$year.').zip');
        // $this->zip->archive($_SERVER['DOCUMENT_ROOT'].'test_audit/pdf/document/bank_confirmation('.$month.$year.').zip');

        $parse_data = array(
            'pic_name'  => $pic_info[0]['first_name']." ".$pic_info[0]['last_name'],
            'month'      => $month
        );

        $pic_email = $pic_info[0]['email'];
        if($pic_email == '"james@acumenbizcorp.com.sg"'){
            $pic_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$pic_email);
        }
        
        $manager_email = "penny@aaa-global.com";
        $partner_email = "woellywilliam@acumenbizcorp.com.sg";

        $msg = file_get_contents('./application/modules/bank/email_templates/pic_bank_confirmation.html');
        $message = $this->parser->parse_string($msg, $parse_data);

        $subject = 'Bank Confirmation - '.$month." ".$year;

        $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");

        $receiver_email = array();

        //this the right query
        // array_push($receiver_email, array("email" => $pic_email) );

        //this is for testing purpose
        array_push($receiver_email, array("email" => "xinyee@aaa-global.com") );

        $item = array();
        $item['content'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/bank_confirmation('.$month.$year.').zip'));
        // $item['content'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'test_audit/pdf/document/bank_confirmation('.$month.$year.').zip'));
        $item['name'] = 'bank_confirmation('.$month.$year.').zip';
        $attachment = array(); 
        array_push($attachment, $item);



        //SMA
        // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

        //SEND IN BLUE
        // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray

        //this the right query
        $this->send_email($subject, $sender_email, $receiver_email, $message, "", $attachment);

        // $this->sma->send_email($pic_email, $subject, $message,"" ,"" ,$_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/bank_confirmation('.$month.$year.').zip' , "", "");
        // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

        
        //get bank confirmation summary list-----------------------------------------------------------------------------------------------------------
        $report_summary = $this->bank_model->get_bank_confirm_report($year_month);

        if($report_summary != null)
        {
            $report_content = "";



            //loop summary of every bank
            foreach ($report_summary as $key => $each_bank) {
                $report_content .= '<p>Bank: '.$each_bank['bank_name'].'<br/>Sent: '.$each_bank['bc_total'].'</p>';
            }

            $parse_report_data = array(
                'pic_name'  => $pic_info[0]['first_name']." ".$pic_info[0]['last_name'],
                'month'      => $month,
                'report_content' => $report_content
            );

            //get report message template
            $report_msg = file_get_contents('./application/modules/bank/email_templates/bank_confirmation_report.html');
            

            $report_message = $this->parser->parse_string($report_msg, $parse_report_data);
           
            $cc_email = array();
            array_push($cc_email, array("email" => $manager_email), array("email" => $partner_email));

            $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");

            $receiver_email = array();
            // this is right query
            // array_push($receiver_email, array("email" => $pic_email) );

            //this is for testing purpose
            array_push($receiver_email, array("email" => "xinyee@aaa-global.com") );


            //SMA
            // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

            //SEND IN BLUE
            // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray
        
            // $this->send_email($subject, $sender_email, $receiver_email, $report_message, $cc_email, ""); 

            // $this->sma->send_email($pic_email, $subject, $report_message,"" ,"" ,"" , $cc_email, "");
        }
        
        // print_r($bc_id_list);
    }

    // send email controller
    public function end_month_bank_confirm_report()
    {
        $month = date('F');
        $year = date('Y');

        //for sql condition to retrieve bank confirmation report
        $year_month = date('Y-m');

        //to get PIC this of this month
        $pic_info = $this->bank_model->get_bank_confirm_pic($month, $year);

        $report_summary = $this->bank_model->get_end_month_bank_confirm_report($year_month);

        print_r($report_summary);

        if($report_summary != null)
        {
            $report_content = "";

            $bank_id_array = array();
            $report_array = array();

            
            //loop summary of every bank
            foreach ($report_summary as $key => $each_bank) {
                array_push($bank_id_array, $each_bank['id']);
                // $report_content .= '<p>Bank: '.$each_bank['bank_name'].'<br/>Sent: '.$each_bank['bc_total'].'<br/>Received: </p>';
            }

            $bank_id_array = array_unique($bank_id_array);

            foreach ($report_summary as $key => $each_bank) {
                foreach ($bank_id_array as $inner_key => $bank_id) {
                    if($bank_id == $each_bank['id'])
                    {
                        $single_data = array('bc_total'         => $each_bank['bc_total'],
                                             'bank_name'        => $each_bank['bank_name'],
                                             'confirm_status'   => $each_bank['confirm_status'] );

                        if(!array_key_exists($bank_id, $report_array))
                        {
                            $report_array[$bank_id] = array();
                        }
                        
                        array_push($report_array[$bank_id], $single_data);
                    }
                }
            }

            print_r($report_array);
            // count bc_total and write report---------------------------------------------------------------------------------------------------
            foreach ($report_array as $bank_id => $bank_array) {
                $sent_total = 0;
                $received_total = 0;
                $cancelled_total = 0;

                foreach ($bank_array as $key => $each_status) {
                    $bank_name = $each_status['bank_name'];

                    $sent_total += intval($each_status['bc_total']);
                    
                    if($each_status['confirm_status'] == 2)
                    {
                        $received_total = $each_status['bc_total'];
                    }
                    else if($each_status['confirm_status'] == 5)
                    {
                        $cancelled_total = $each_status['bc_total'];
                    }
                }


                // print_r($bank_name);
                if($cancelled_total > 0)
                {
                    $report_content .= '<p>Bank: '.$bank_name.'<br/>Sent: '.$sent_total.'<br/>Received: '.$received_total.'<br/>Cancelled: '.$cancelled_total.'</p>';
                }
                else
                {
                    $report_content .= '<p>Bank: '.$bank_name.'<br/>Sent: '.$sent_total.'<br/>Received: '.$received_total.'</p>';
                }
                

                
            }
            // print_r($report_content);

            $parse_report_data = array(
                'pic_name'  => $pic_info[0]['first_name']." ".$pic_info[0]['last_name'],
                'month'      => $month,
                'report_content' => $report_content
            );

            $pic_email = $pic_info[0]['email'];
            if($pic_email == '"james@acumenbizcorp.com.sg"'){
                $pic_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$pic_email);
            }
        

            $manager_email = "penny@aaa-global.com";
            $partner_email = "woellywilliam@acumenbizcorp.com.sg";

            //get report message template
            $report_msg = file_get_contents('./application/modules/bank/email_templates/bank_confirmation_report.html');

            $subject = 'Bank Confirmation - '.$month." ".$year;
            $report_message = $this->parser->parse_string($report_msg, $parse_report_data);
            
            $cc_email = array();
            array_push($cc_email, array("email" => $manager_email), array("email" => $partner_email));

            $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");

            $receiver_email = array();
            // this is right query
            array_push($receiver_email, array("email" => $pic_email) );
            $this->send_email($subject, $sender_email, $receiver_email, $report_massage, $cc_email, ""); 

            //this is for testing purpose
            // array_push($receiver_email, array("email" => "xinyee@aaa-global.com") );
            $this->send_email($subject, $sender_email, $receiver_email, $report_massage, "", ""); 



            //SMA
            // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

            //SEND IN BLUE
            // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray
        
            


            // $this->sma->send_email($pic_email, $subject, $report_message,"" ,"" ,"" , $cc_email, "");
        }

        $this->check_previous_bank_confirm();
    }

    // send email controller
    public function check_previous_bank_confirm()
    {
        $this_year_month = date('Y-m');

        $all_bank_confirm_by_month = $this->bank_model->get_previous_bank_confirm();

        $tally_flag = true;

        if($all_bank_confirm_by_month != null)
        {
            // $report_content = "";

            $each_month_arr = array();
            $check_array = array();

            
            //loop summary of every bank
            foreach ($all_bank_confirm_by_month as $key => $each_month) {
                if($each_month['month'] != $this_year_month)
                {
                    array_push($each_month_arr, $each_month['month']);
                }
                // $report_content .= '<p>Bank: '.$each_bank['bank_name'].'<br/>Sent: '.$each_bank['bc_total'].'<br/>Received: </p>';
            }
            // print_r($all_bank_confirm_by_month);

            $each_month_arr = array_unique($each_month_arr);

            foreach ($all_bank_confirm_by_month as $key => $each_bank) 
            {
                foreach ($each_month_arr as $inner_key => $month) {
                    if($month == $each_bank['month'])
                    {
                        $single_data = array('id'               => $each_bank['id'],
                                             'bc_total'         => $each_bank['bc_total'],
                                             'bank_name'        => $each_bank['bank_name'],
                                             'confirm_status'   => $each_bank['confirm_status'] );

                        if(!array_key_exists($month, $check_array))
                        {
                            $check_array[$month] = array();
                        }
                        
                        array_push($check_array[$month], $single_data);
                    }
                }
            }

            // print_r($check_array);
            $final_check_array = array();

            foreach ($check_array as $key => $month_arr) {
                $bank_id_array = array();
                $report_array = array();

                
                //loop summary of every bank
                foreach ($month_arr as $month_key => $each_bank) {
                    array_push($bank_id_array, $each_bank['id']);
                    // $report_content .= '<p>Bank: '.$each_bank['bank_name'].'<br/>Sent: '.$each_bank['bc_total'].'<br/>Received: </p>';
                }


                $bank_id_array = array_unique($bank_id_array);

                // print_r($bank_id_array);

                foreach ($month_arr as $bank_key => $each_bank) {
                    foreach ($bank_id_array as $inner_key => $bank_id) {
                        if($bank_id == $each_bank['id'])
                        {
                            $single_data = array('bc_total'         => $each_bank['bc_total'],
                                                 'bank_name'        => $each_bank['bank_name'],
                                                 'confirm_status'   => $each_bank['confirm_status'] );

                            if(!array_key_exists($bank_id, $report_array))
                            {
                                $report_array[$bank_id] = array();
                            }
                            
                            array_push($report_array[$bank_id], $single_data);
                        }
                    }
                }

                if(!array_key_exists($key, $final_check_array))
                {
                    $final_check_array[$key] = array();
                }

                $final_check_array[$key] = $report_array;

            }



            // print_r($final_check_array);
            foreach ($final_check_array as $final_check_key => $each_final_check) {
                $report_content = "";

                # code...
                // print_r($each_final_check);
                foreach ($each_final_check as $each_final_check_key => $each_final_check_bank) {
                    $sent_total = 0;
                    $received_total = 0;
                    $cancelled_total = 0;

                    

                    foreach ($each_final_check_bank as $key => $each_status) {
                        
                        $bank_name = $each_status['bank_name'];

                        $sent_total += intval($each_status['bc_total']);
                
                        if($each_status['confirm_status'] == 2)
                        {
                            $received_total = $each_status['bc_total'];
                        }
                        else if($each_status['confirm_status'] == 5)
                        {
                            $cancelled_total = $each_status['bc_total'];
                        }
                        
                        // print_r($bank_name);

                       
                                             
                    }

                    // echo $sent_total.",";
                    // echo $received_total+$cancelled_total." - ";

                    if($sent_total != ($received_total + $cancelled_total)){
                        $tally_flag = false;
                        // echo "yooooooooooooooo";

                        if($cancelled_total > 0)
                        {
                            $report_content .= '<p>Bank: '.$bank_name.'<br/>Sent: '.$sent_total.'<br/>Received: '.$received_total.'<br/>Cancelled: '.$cancelled_total.'</p>';
                        }
                        else
                        {
                            $report_content .= '<p>Bank: '.$bank_name.'<br/>Sent: '.$sent_total.'<br/>Received: '.$received_total.'</p>';
                        }
                    }
                }

                // echo $tally_flag;
                if(!$tally_flag)
                {
                    $date = date_create($final_check_key);
                    $date = date_format($date,"F Y");

                    $pieces = explode(" ", $date);

                    $month_full = $pieces[0];
                    $year_full = $pieces[1];

                    $pic_info = $this->bank_model->get_bank_confirm_pic($month_full, $year_full);

                     $parse_report_data = array(
                        'pic_name'  => $pic_info[0]['first_name']." ".$pic_info[0]['last_name'],
                        'month'      => $date,
                        'report_content' => $report_content
                    );

                    $pic_email = $pic_info[0]['email'];
                    if($pic_email == '"james@acumenbizcorp.com.sg"'){
                        $pic_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$pic_email);
                    }
                

                    $manager_email = "penny@aaa-global.com";
                    $partner_email = "woellywilliam@acumenbizcorp.com.sg";

                    //get report message template
                    $report_msg = file_get_contents('./application/modules/bank/email_templates/bank_confirmation_report.html');

                    $subject = 'Bank Confirmation - '.$month_full." ".$year_full;
                    $report_message = $this->parser->parse_string($report_msg, $parse_report_data);

                    $cc_email = array();
                    array_push($cc_email, array("email" => $manager_email), array("email" => $partner_email));

                    $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");

                    $receiver_email = array();

                    // this is right query
                    array_push($receiver_email, array("email" => $pic_email) );

                    //this is for testing purpose
                    // array_push($receiver_email, array("email" => "xinyee@aaa-global.com") );



                    //SMA
                    // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

                    //SEND IN BLUE
                    // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray
                
                    $this->send_email($subject, $sender_email, $receiver_email, $report_message, "", "");      


                }

               
            }
        }

    }

    // send email controller
    public function set_pic_reminder()
    {
        $day = date('d');
        $month = date('F');
        $year = date('Y');

        $next_month = date('F Y', strtotime('+1 month'));

        // $pic_info = $this->bank_model->get_bank_confirm_pic($month, $year);

        // $create_bc_list = $this->bank_model->get_create_bc_list($month, $year);

        $set_pic_flag = $this->bank_model->get_set_pic_flag($next_month);

        $bc_id_list = array();

        // print_r($create_bc_list);
        if ($day >= 10 && $set_pic_flag)
        {
            $parse_data = array(
                'month'      => $next_month
            );

            $manager_email = "penny@aaa-global.com";
            $msg = file_get_contents('./application/modules/bank/email_templates/set_pic_reminder.html');
            
            $message = $this->parser->parse_string($msg, $parse_data);
            $subject = 'Set PIC Reminder - '.$next_month;
            $sender_email = array("name" => "AUDIT SYSTEM", "email" => "admin@aaa-global.com");

            $receiver_email = array();
            // this is right query
            array_push($receiver_email, array("email" => $manager_email) );

            //this is for testing purpose
            // array_push($receiver_email, array("email" => "xinyee@aaa-global.com") );



            //SMA
            // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

            //SEND IN BLUE
            // $subject-string, $sender_email-object(name,email), $receiver_email-objectarray, $content-string, $cc-objectarray, $attachment-objectarray
        
            $this->send_email($subject, $sender_email, $receiver_email, $message, "", "");      
        }

        // $parse_data = array(
        //     'pic_name'  => $pic_info[0]['first_name']." ".$pic_info[0]['last_name'],
        //     'month'      => $month
        // );

        // $pic_email = $pic_info[0]['email'];
        // if($pic_email == '"james@acumenbizcorp.com.sg"'){
        //     $pic_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$pic_email);
        // }
        
        // $manager_email = "penny@aaa-global.com";
        // $msg = file_get_contents('./application/modules/bank/email_templates/pic_bank_confirmation.html');
        // $message = $this->parser->parse_string($msg, $parse_data);

        // $subject = 'Bank Confirmation - '.$month." ".$year;
        // $this->sma->send_email($pic_email, $subject, $message,"" ,"" ,$_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/bank_confirmation('.$month.$year.').zip' ,$manager_email, "");
        // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

        // print_r($bc_id_list);
    }



    public function generate_auth_document()
    {

        // $po_list = $this->products_model->getPdfData(); // retrieve all po from database
        $auth_id = $this->input->post('auth_id');
        // print_r($auth_id);
        

        $obj_pdf = new NOFOOTER_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title = "Bank Authorization";
        $obj_pdf->SetTitle($title);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='<table style="width:100%; height: auto;"><tr style="text-align: center;"><td style="width:80%; height: auto;" align="center"></td></tr></table>', $tc=array(0,0,0), $lc=array(0,0,0));


        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('times');
        $obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        // $obj_pdf->SetFooterMargin(10);
        $obj_pdf->SetMargins(28, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $obj_pdf->SetAutoPageBreak(TRUE, 40);
        $obj_pdf->SetFont('times', '', 12);
        $obj_pdf->setFontSubsetting(false);
        $obj_pdf->setY(33);

        $obj_pdf->startPageGroup();

        $obj_pdf->AddPage();

        $counter = 0;


        $do_template_query = $this->db->query("select * from audit_doc_master_template where id = 1");

        $do_template_query = $do_template_query->result_array();

        // $this->load->helper('pdf_helper');

        $pattern = "/{{[^}}]*}}/";
        $subject = $do_template_query[0]["document_content"];
        preg_match_all($pattern, $subject, $matches);

        $contents_info = $do_template_query[0]["document_content"];
                

        $document_info_query = $this->db->query("SELECT ba.*, c.company_name, c.former_name, c.postal_code, c.street_name, c.building_name, c.unit_no1, c.unit_no2, f.name , f.postal_code as firm_postal_code, f.street_name as firm_street_name, f.building_name as firm_building_name, f.unit_no1 as firm_unit_no1, f.unit_no2 as firm_unit_no2, bank.bank_name, bank.add_line1, bank.add_line2, bank.add_line3 from audit_bank_auth ba
                                                LEFT JOIN client c on ba.company_code = c.company_code 
                                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
                                                LEFT JOIN firm f on ba.firm_id = f.id
                                                where ba.id = '".$auth_id."'");


        $document_info_query = $document_info_query->result_array();

        foreach ($document_info_query as $key => $value) {
            $document_info_query[$key]['company_name'] = $this->encryption->decrypt($document_info_query[$key]['company_name']);
        }

        $detail = $document_info_query[0];

        $company_add = $this->write_address($detail['street_name'], $detail["unit_no1"], $detail["unit_no2"], $detail['building_name'], $detail["postal_code"], 'letter');

        $firm_add = $this->write_address($detail['firm_street_name'], $detail["firm_unit_no1"], $detail["firm_unit_no2"], $detail['firm_building_name'], $detail["firm_postal_code"], 'letter');

        $bank_add = $detail['add_line1'].' <br/>'.$detail['add_line2'].' <br/>'.$detail['add_line3'].'.';
        // echo $bank_add;
                
        $toggle_array = $matches[0];

        if(count($toggle_array) != 0)
        {
            for($r = 0; $r < count($toggle_array); $r++)
            {
                $string1 = (str_replace('{{', '',$toggle_array[$r]));
                $string2 = (str_replace('}}', '',$string1));

 
                
                if($string2 == "Company name header")
                {
                    $replace_string = $toggle_array[$r];

                    // $date = strtotime($document_info_query[0]["date_time"]);
                    // $new_date = date('d/m/Y',$date);    
                    if($detail['former_name'] == "") 
                    {
                        $content = $detail['company_name'];
                    }
                    else
                    {
                        $content = $detail['company_name'].'<br/>'.'('.$detail['former_name'].')';
                    }            

                    
                }
                elseif($string2 == "Address - new")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["do_number"];
                    $content = $company_add;
                }
                elseif($string2 == "Authorisation date")
                {
                    $replace_string = $toggle_array[$r];

                    $date = DateTime::createFromFormat('Y-m-d', $detail["auth_date"]);
                    $auth_date = $date->format('d F Y');

                    $content = $auth_date;
                }
                elseif($string2 == "Bank Name")
                {
                    $replace_string = $toggle_array[$r];

                    $content = $detail["bank_name"];
                }
                elseif($string2 == "Bank Address")
                {
                    $replace_string = $toggle_array[$r];
                    // echo $bank_add;

                    // $content = $document_info_query[0]["order_code"];
                    $content = $bank_add;
                }
                elseif($string2 == "Company name")
                {
                    $replace_string = $toggle_array[$r];

                    if($detail['former_name'] == "") 
                    {
                        $content = $detail['company_name'];
                    }
                    else
                    {
                        $content = $detail['company_name'].'('.$detail['former_name'].')';
                    }    
                }
                elseif($string2 == "Firm name")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["method"];
                    $content = $detail['name'];
                }
                elseif($string2 == "Firm address")
                {
                    $replace_string = $toggle_array[$r];
           

                    $content = $firm_add;
                }

                $contents_info = str_replace($replace_string, $content, $contents_info);


            }
        }

                $new_content_info = $contents_info;
       

                $obj_pdf->writeHTML($new_content_info, true, false, false, false, '');

        
            $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Bank Authorization ('.DATE("Y-m-d",now()).').pdf', 'F');
            chmod($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Bank Authorization ('.DATE("Y-m-d",now()).').pdf',0644);                

            // $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'test_audit/pdf/document/Bank Authorization ('.DATE("Y-m-d",now()).').pdf', 'F');

            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

            $link = 'http://'. $_SERVER['SERVER_NAME'] .'/audit/pdf/document/Bank Authorization ('.DATE("Y-m-d",now()).').pdf';
            // $link = 'http://'. $_SERVER['SERVER_NAME'] .'/test_audit/pdf/document/Bank Authorization ('.DATE("Y-m-d",now()).').pdf';

            $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> '/pdf/document/Bank Authorization ('.DATE("Y-m-d",now()).').pdf');

            // kill session for doc_cus_id array
            $this->session->unset_userdata('doc_cus_id');
            $this->session->set_userdata(array('doc_cus_id' =>array())); // create new session back for session 'doc_cus_id'

            echo json_encode($data);
            
        // }

        
    }

    public function generate_confirm_document($file_counter = 1, $confirm_id="")
    {

        // $po_list = $this->products_model->getPdfData(); // retrieve all po from database
        if(!empty($this->input->post('confirm_id')))
        {
            $confirm_id = $this->input->post('confirm_id');
        }
        
        // print_r($confirm_id);

        $document_info_query = $this->db->query("SELECT ba.*, bc.*, c.company_name, bank.bank_name, bank.add_line1, bank.add_line2, bank.add_line3, f.id as firm_id, f.name as firm_name from audit_bank_confirm bc, audit_bank_auth ba
                                                LEFT JOIN client c on ba.company_code = c.company_code 
                                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
                                                LEFT JOIN firm f on ba.firm_id = f.id
                                                where ba.id = bc.bank_auth_id and bc.id = ".$confirm_id);
        $document_info_query = $document_info_query->result_array();

        foreach ($document_info_query as $key => $value) {
            $document_info_query[$key]['company_name'] = $this->encryption->decrypt($document_info_query[$key]['company_name']);
        }



        
        $header_company_info = $this->write_header($document_info_query[0]['firm_id'], false);
        $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title = "Bank Confirmation";
        $obj_pdf->SetTitle($title);
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


        $do_template_query = $this->db->query("select * from audit_doc_master_template where id = 2");

        $do_template_query = $do_template_query->result_array();

        // $this->load->helper('pdf_helper');

        $pattern = "/{{[^}}]*}}/";
        $subject = $do_template_query[0]["document_content"];
        preg_match_all($pattern, $subject, $matches);

        $contents_info = $do_template_query[0]["document_content"];

                
        
        $detail = $document_info_query[0];
        // $company_add = $this->write_address($detail['street_name'], $detail["unit_no1"], $detail["unit_no2"], $detail['building_name'], $detail["postal_code"], 'letter');

        // $firm_add = $this->write_address($detail['firm_street_name'], $detail["firm_unit_no1"], $detail["firm_unit_no2"], $detail['firm_building_name'], $detail["firm_postal_code"], 'letter');

        $bank_add = $detail['add_line1'].', <br/>'.$detail['add_line2'].', <br/>'.$detail['add_line3'].'.';
                  
        $toggle_array = $matches[0];

        if(count($toggle_array) != 0)
        {
            for($r = 0; $r < count($toggle_array); $r++)
            {
                $string1 = (str_replace('{{', '',$toggle_array[$r]));
                $string2 = (str_replace('}}', '',$string1));

 
                
                if($string2 == "Send date")
                {
                    $replace_string = $toggle_array[$r];

                    $date = DateTime::createFromFormat('Y-m-d', $detail["sent_on_date"]);
                    $new_date = $date->format('d F Y');   
                   
                    $content = $new_date;        
                }
                elseif($string2 == "Bank name")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["do_number"];
                    $content = $detail['bank_name'];
                }
                elseif($string2 == "Bank address")
                {
                    $replace_string = $toggle_array[$r];

                    // $date = DateTime::createFromFormat('Y-m-d', $detail["auth_date"]);
                    // $auth_date = $date->format('d F Y');

                    $content = $bank_add;
                }
                elseif($string2 == "Client name")
                {
                    $replace_string = $toggle_array[$r];

                    $content = $detail["company_name"];
                }
                elseif($string2 == "Bank auth date")
                {
                    $replace_string = $toggle_array[$r];

                    $date = DateTime::createFromFormat('Y-m-d', $detail["auth_date"]);
                    $auth_date = $date->format('d F Y');

                    // $content = $document_info_query[0]["order_code"];
                    $content = $auth_date;
                }
                // elseif($string2 == "Company name")
                // {
                //     $replace_string = $toggle_array[$r];

                //     if($detail['former_name'] == "") 
                //     {
                //         $content = $detail['company_name'];
                //     }
                //     else
                //     {
                //         $content = $detail['company_name'].'('.$detail['former_name'].')';
                //     }    
                // }
                elseif($string2 == "Year end date")
                {
                    $replace_string = $toggle_array[$r];
           
                    $date = DateTime::createFromFormat('Y-m-d', $detail["fye_date"]);
                    $fye_date = $date->format('d F Y');

                    $content = $fye_date;
                }
                elseif($string2 == "Firm name")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["method"];
                    $content = $detail['firm_name'];
                }

                $contents_info = str_replace($replace_string, $content, $contents_info);


            }
        }

            $new_content_info = $contents_info;
       

            $obj_pdf->writeHTML($new_content_info, true, false, false, false, '');

        
            $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Bank Confirmation ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf', 'F');
            chmod($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Bank Confirmation ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf',0644);                

            // $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'test_audit/pdf/document/Bank Confirmation ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf', 'F');


            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

            // $link = 'https://'. $_SERVER['SERVER_NAME'] .'/generate_do/pdf/document/Delivery Order ('.DATE("Y-m-d",now()).').pdf';
            $link = 'http://'. $_SERVER['SERVER_NAME'] .'/audit/pdf/document/Bank Confirmation ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf';
            // $link = 'http://'. $_SERVER['SERVER_NAME'] .'/test_audit/pdf/document/Bank Confirmation ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf';


            $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> $_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/Bank Confirmation ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf');
            // $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> $_SERVER['DOCUMENT_ROOT'].'test_audit/pdf/document/Bank Confirmation ('.DATE("Y-m-d",now()).')_'.$file_counter.'.pdf');
            // kill session for doc_cus_id array
            $this->session->unset_userdata('doc_cus_id');
            $this->session->set_userdata(array('doc_cus_id' =>array())); // create new session back for session 'doc_cus_id'

            echo json_encode($data);
            return $data;
            
        
        
    }

    public function submit_bank_list(){
        $this->session->set_userdata("tab_active", "bank_list");

        $form_data = $this->input->post();

        $data = array(
            'bank_name_for_user' => $form_data['bank_name_for_user'],
            'bank_name' => $form_data['bank_name'],
            'add_line1' => $form_data['add_line1'],
            'add_line2' => $form_data['add_line2'],
            'add_line3' => $form_data['add_line3']
        );

        $result = $this->bank_model->submit_bank_list($data, $form_data['bank_list_id']);

        echo $result;
    }

    public function submit_bank_confirm_setting(){
        $this->session->set_userdata("tab_active", "bank_confirm_setting");

        $form_data = $this->input->post();

        $data = array(
            'confirm_month' => $form_data['confirm_month'],
            'pic_id' => $form_data['pic_name'],
        );

        // print_r($data);


        $result = $this->bank_model->submit_bank_confirm_setting($data, $form_data['bank_confirm_setting_list_id']);

        echo $result;
    }


    public static function getClientName() 
    {
        $ci =& get_instance();

        // $this->db->select('client.id, client.company_code, client.company_name');
        // $this->db->from('client');
        // // $this->db->join('audit_client', 'audit_client.company_code = client.company_code', 'left');
        // $this->db->join('client_billing_info', 'client_billing_info.company_code = client.company_code', 'right');
        // $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service AND our_service_info.service_type = 1', 'right');
        // // $query = $this->db->get();

        // $query = 'SELECT client.id, client.company_code, client.company_name FROM client 
        //         right join client_billing_info on client_billing_info.company_code = client.company_code 
        //         right join our_service_info on our_service_info.id = client_billing_info.service 
        //         LEFT JOIN user_firm ON user_firm.firm_id = client.firm_id
        //         WHERE (our_service_info.service_type = 1 OR our_service_info.service_type = 10) AND client.deleted = 0  AND user_firm.user_id = "'.$this->session->userdata('user_id').'"';

        $query = 'SELECT client.id, client.company_code, client.company_name FROM client 
                right join client_billing_info on client_billing_info.company_code = client.company_code 
                right join our_service_info on our_service_info.id = client_billing_info.service 
                WHERE (our_service_info.service_type = 1 OR our_service_info.service_type = 10) AND client.deleted = 0';


       

        // echo $query;

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("Client Name not found.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['company_name'] != null)
                {

                    $res[$row['company_code']] = $this->encryption->decrypt($row['company_name']);
                }
              
            }
            //$res = json_decode($res);
            $ci =& get_instance();
            $selected_client_name = $ci->session->userdata('auth_company_code');
            $ci->session->unset_userdata('auth_company_code');

            // print_r($res);

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client Name fetched successfully.", 'result'=>$res, 'selected_client_name'=>$selected_client_name);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_client_name'=>'');

            echo json_encode($data);
        }
    }

    public static function getBankName() 
    {
        $ci =& get_instance();
        $form_data = $this->input->get();

        if(isset($form_data['company_code']))
        {
           if($form_data['company_code'] == "" || $form_data['company_code'] == null)
            {
                $query = 'SELECT bank.id, bank.bank_name FROM audit_bank_list bank where deleted != 1';
            }
            else
            {
                $query = "SELECT auth.bank_id as id, bank.bank_name FROM audit_bank_auth auth, audit_bank_list bank WHERE company_code = '".$form_data['company_code']."' and bank.id = auth.bank_id";
            } 
        }
        else
        {
            $query = 'SELECT bank.id, bank.bank_name FROM audit_bank_list bank where deleted != 1';
        }

        // echo $query;

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("Bank Name not found.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['bank_name'] != null)
                {
                    $res[$row['id']] = $row['bank_name'];
                }
              
            }
            //$res = json_decode($res);
            $ci =& get_instance();
            $selected_bank_name = $ci->session->userdata('auth_bank_id');
            $ci->session->unset_userdata('auth_bank_id');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Bank Name fetched successfully.", 'result'=>$res, 'selected_bank_name'=>$selected_bank_name);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_bank_name'=>'');

            echo json_encode($data);
        }
    }

    public static function getBankNameForUser() 
    {
        $ci =& get_instance();
        $form_data = $this->input->get();

        if(isset($form_data['company_code']))
        {
           if($form_data['company_code'] == "" || $form_data['company_code'] == null)
            {
                $query = 'SELECT bank.id, bank.bank_name_for_user FROM audit_bank_list bank where deleted != 1';
            }
            else
            {
                $query = "SELECT auth.bank_id as id, bank.bank_name_for_user FROM audit_bank_auth auth, audit_bank_list bank WHERE company_code = '".$form_data['company_code']."' and bank.id = auth.bank_id";
            } 
        }
        else
        {
            $query = 'SELECT bank.id, bank.bank_name_for_user FROM audit_bank_list bank where deleted != 1';
        }

        // echo $query;

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("Bank Name not found.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['bank_name_for_user'] != null)
                {
                    $res[$row['id']] = $row['bank_name_for_user'];
                }
              
            }
            //$res = json_decode($res);
            $ci =& get_instance();
            $selected_bank_name = $ci->session->userdata('auth_bank_id');
            $ci->session->unset_userdata('auth_bank_id');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Bank Name fetched successfully.", 'result'=>$res, 'selected_bank_name'=>$selected_bank_name);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_bank_name'=>'');

            echo json_encode($data);
        }
    }
    



    public static function getAuthStatus() {

        //try {
        //echo $nationalityId;
        $ci =& get_instance();

        $query = "SELECT id, status FROM audit_status where type = 'bank'";

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

    public static function getPicName() 
    {
        $ci =& get_instance();

        // $this->db->select('client.id, client.company_code, client.company_name');
        // $this->db->from('client');
        // // $this->db->join('audit_client', 'audit_client.company_code = client.company_code', 'left');
        // $this->db->join('client_billing_info', 'client_billing_info.company_code = client.company_code', 'right');
        // $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service AND our_service_info.service_type = 1', 'right');
        // // $query = $this->db->get();

        $query = 'SELECT `id`,`name` FROM `payroll_employee` WHERE (`department` = 1 OR `department` = 3)and employee_status_id != 3 and employee_status_id != 4';

        // echo $query;

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("PIC Name not found.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['id'] != null)
                {
                    $res[$row['id']] = $row['name'];
                }
              
            }
            //$res = json_decode($res);
            $ci =& get_instance();
            $selected_pic_name = $ci->session->userdata('stocktake_pic');
            $ci->session->unset_userdata('stocktake_pic');

            // print_r($res);

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"PIC Name fetched successfully.", 'result'=>$res, 'selected_pic_name'=>$selected_pic_name);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_pic_name'=>'');

            echo json_encode($data);
        }
    }

    public static function getAuthClient() 
    {
        $ci =& get_instance();

        // $this->db->select('client.id, client.company_code, client.company_name');
        // $this->db->from('client');
        // // $this->db->join('audit_client', 'audit_client.company_code = client.company_code', 'left');
        // $this->db->join('client_billing_info', 'client_billing_info.company_code = client.company_code', 'right');
        // $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service AND our_service_info.service_type = 1', 'right');
        // // $query = $this->db->get();

        $query = 'SELECT auth.company_code, client.company_name FROM audit_bank_auth auth
                    left join client on auth.company_code = client.company_code 
                    where auth.deleted = 0 and auth.active = 1';

        
        // echo $query;

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("Client Name not found.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['company_name'] != null)
                {

                    $res[$row['company_code']] = $this->encryption->decrypt($row['company_name']);
                }
              
            }
            //$res = json_decode($res);
            $ci =& get_instance();
            $selected_client_name = $ci->session->userdata('confirm_company_code');
            $ci->session->unset_userdata('confirm_company_code');

            // print_r($res);

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client Name fetched successfully.", 'result'=>$res, 'selected_auth_client'=>$selected_client_name);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_auth_client'=>'');

            echo json_encode($data);
        }
    }

    public static function getFinPeriod() 
    {
        $ci =& get_instance();
        $form_data = $this->input->get();

        if(isset($form_data['company_code']))
        {
           $result = $this->client_model->get_caf_list( $form_data['company_code']);

        }
        
        // print_r($result);
        $res = array();

        foreach($result as $row) {
            if($row->status != 13)
            {
                $res[$row->id] = $row->FYE.' ('.$row->job.')';
            }
          
        }

        if ($res)
        {
            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Bank Name fetched successfully.", 'result'=>$res, 'selected_bank_name'=>'');

            echo json_encode($data);
        }
        else
        { 
            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_bank_name'=>'');

            echo json_encode($data);
        }

        //echo json_encode($result->result_array());
        // if ($result) 
        // {

            // $result = $result->result_array();

            // if(!$result) {
            //   throw new exception("Bank Name not found.");
            // }

            // $res = array();
            // foreach($result as $row) {
            //     if($row['bank_name'] != null)
            //     {
            //         $res[$row['id']] = $row['bank_name'];
            //     }
              
            // }
            // //$res = json_decode($res);
            // $ci =& get_instance();
            // $selected_bank_name = $ci->session->userdata('auth_bank_id');
            // $ci->session->unset_userdata('auth_bank_id');

            // $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Bank Name fetched successfully.", 'result'=>$result, 'selected_bank_name'=>$selected_bank_name);

            // echo json_encode($data);
        // }
        // else
        // { 
            // $res = array();

        //     $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_bank_name'=>'');

        //     echo json_encode($data);
        // }
    }

    public static function get_bank_auth_doc_list()
    {
        $form_data = $this->input->post();
        $ba_id = $form_data['auth_id'];

        $query = 'SELECT bank_auth_id, GROUP_CONCAT(DISTINCT CONCAT(id,",",file_name)SEPARATOR ";") as file_names FROM audit_bank_auth_document WHERE bank_auth_id ='.$ba_id;

        $q = $this->db->query($query);

        foreach ($q->result() as $key => $value) {
            if($q->result()[$key]->file_names != null)
            {
                $q->result()[$key]->file_names = explode(';', $q->result()[$key]->file_names);
            }
        }
        

        echo json_encode($q-> result());

    }

    public static function get_bank_confirm_doc_list()
    {
        $form_data = $this->input->post();
        $ba_id = $form_data['confirm_id'];

        $query = 'SELECT bank_confirm_id, GROUP_CONCAT(DISTINCT CONCAT(id,",",file_name)SEPARATOR ";") as file_names FROM audit_bank_confirm_document WHERE bank_confirm_id ='.$ba_id;

        $q = $this->db->query($query);

        foreach ($q->result() as $key => $value) {
            if($q->result()[$key]->file_names != null)
            {
                $q->result()[$key]->file_names = explode(';', $q->result()[$key]->file_names);
            }
        }
        

        echo json_encode($q-> result());

    }



    public function updt_auth_status(){
        
        $this->session->set_userdata("tab_active", "bank_auth");

        $form_data = $this->input->post();

        $result = $this->bank_model->updt_auth_status($form_data['status_id'], $form_data['bank_auth_id']);

        echo $result;
    }

    public function updt_confirm_status(){
        
        $this->session->set_userdata("tab_active", "bank_confirm");

        $form_data = $this->input->post();

        $result = $this->bank_model->updt_confirm_status($form_data['status_id'], $form_data['bank_confirm_id']);

        echo $result;
    }

    public function updt_confirm_sent_date(){
        
        $this->session->set_userdata("tab_active", "bank_confirm");

        $form_data = $this->input->post();

        $result = $this->bank_model->updt_confirm_sent_date($form_data['bank_confirm_id'], $form_data['sent_on_date']);

        echo $result;
    }

    public function bank_confirm_filter(){
        $form_data = $this->input->post();

        $month    = $form_data['month'];
        $status   = $form_data['status'];

        if($month == ""){
            $month = '%%';
        }
        else 
        {
            $month = "%".date_format(date_create($month),"Y-m")."%";
        }


        if($status == 0){
            $status = '%%';
        }

        $result = $this->bank_model->bank_confirm_filter($month, $status);
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
                echo 'Your Email has successfully been sent.';
            }
        } catch (Exception $e) {
            echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function set_banklist_active()
    {
        $this->session->set_userdata("tab_active", "bank_list");
        $this->index();
    
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

        $street_name = strtoupper($street_name);
        $building_name = strtoupper($building_name);
        $unit_no1 = strtoupper($unit_no1);
        $unit_no2 = strtoupper($unit_no2);
        $postal_code = strtoupper($postal_code);

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
            $address = $street_name . $comma . $br . 'SINGAPORE' . $postal_code;
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
                            <tr valign="center">
                            <td style="text-align: left; height: 60px;" align="center" valign="center"><p>'. $img .'  </p></td>
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
        $this->writeHTMLCell(0, 0, 20, 7, $headerData['string'], 0, 0, false, "C", true);
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


