<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once('assets/vendor/tcpdf/tcpdf.php');


class List_of_Auditor extends MX_Controller
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
        $this->load->model('leave/leave_model');
        $this->load->model('list_of_auditor_model');
        $this->load->model('client/client_model');
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
         $this->data['resignation_letter'] = $this->list_of_auditor_model->get_resignation_letter();
        $this->data['status_dropdown'] = $this->list_of_auditor_model->get_status_dropdown_list();

        $bc = array(array('link' => '#', 'page' => 'Audit Clearance'));
        $meta = array('page_title' => 'Audit clearance', 'bc' => $bc, 'page_name' => 'Audit Clearance');

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

    public function add_first_clearance_letter()
    {
        // $this->meta['page_name'] = 'First Clearance Letter';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $this->data['resend_flag'] = 1;


        // $this->data['holiday_list'] = $this->bank_model->get_holiday_list();
        // $this->data['type_of_leave_list'] = $this->bank_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->bank_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->bank_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->bank_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->bank_model->get_choose_carry_forward_list();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Audit Clearance', base_url('list_of_auditor'));
        $this->mybreadcrumb->add('Create Letter', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Audit Clearance'));
        $meta = array('page_title' => 'Audit Clearance', 'bc' => $bc, 'page_name' => 'Audit Clearance');

        $this->page_construct('add_first_clearance_letter.php', $meta, $this->data);
    }

    public function add_resignation()
    {


        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Audit Clearance', base_url('list_of_auditor'));
        $this->mybreadcrumb->add('Create Resignation', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Audit Clearance'));
        $meta = array('page_title' => 'Audit Clearance', 'bc' => $bc, 'page_name' => 'Audit Clearance');



        $this->page_construct('add_resignation.php', $meta, $this->data);
    }

    public function add_subsequent_resignation($id)
    {


        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Audit Clearance', base_url('list_of_auditor'));
        $this->mybreadcrumb->add('Create Resignation', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->data['edit_resignation_letter'] = $this->list_of_auditor_model->get_edit_resignation_letter($id);

        $bc = array(array('link' => '#', 'page' => 'Audit Clearance'));
        $meta = array('page_title' => 'Audit Clearance', 'bc' => $bc, 'page_name' => 'Audit Clearance');



        $this->page_construct('add_resignation.php', $meta, $this->data);
    }

    public function add_bank_auth()
    {
        $this->meta['page_name'] = 'Bank Authorization';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

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

        $this->page_construct('add_bank_auth.php', $this->meta, $this->data);
    }


    public function add_bank_confirm()
    {
        $this->meta['page_name'] = 'Bank Confirmation';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

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

        $this->page_construct('add_bank_confirm.php', $this->meta, $this->data);
    }

    public function edit_first_letter($id)
    {
        // $this->meta['page_name'] = 'Edit First Clearance Letter';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $temp_edit_first_letter = $this->list_of_auditor_model->get_edit_first_letter($id);

        // print_r($temp_edit_bank_auth);

        $date = DateTime::createFromFormat('Y-m-d', $temp_edit_first_letter[0]->fye_date );
        $temp_edit_first_letter[0]->fye_date = $date->format('d/m/Y');

        $date = DateTime::createFromFormat('Y-m-d', $temp_edit_first_letter[0]->send_date );
        $temp_edit_first_letter[0]->send_date = $date->format('d/m/Y');

        // $date2 = DateTime::createFromFormat('Y-m-d', $temp_edit_first_letter[0]->send_date );
        // $temp_edit_first_letter[0]->send_date = $date2->format('d/m/Y');

        $this->data['edit_first_letter'] = $temp_edit_first_letter;
        // print_r($this->data['edit_first_letter']);
        $this->data['clearance_history_list'] = $this->list_of_auditor_model->get_clearance_history_list($id);
        // $this->data['holiday_list'] = $this->bank_model->get_holiday_list();
        // $this->data['type_of_leave_list'] = $this->bank_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->bank_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->bank_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->bank_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->bank_model->get_choose_carry_forward_list();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('First Clearance Letter', base_url('list_of_auditor'));
        $this->mybreadcrumb->add('Edit First Clearance Letter', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Edit First Clearance Letter'));
        $meta = array('page_title' => 'Edit First Clearance Letter', 'bc' => $bc, 'page_name' => 'Edit First Clearance Letter');

        $this->page_construct('add_first_clearance_letter.php', $meta, $this->data);

        // $this->page_construct('add_first_clearance_letter.php', $this->meta, $this->data);
    }

    public function resend_first_letter($id)
    {
        // $this->meta['page_name'] = 'Edit First Clearance Letter';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $temp_edit_first_letter = $this->list_of_auditor_model->get_edit_first_letter($id);

        // print_r($temp_edit_bank_auth);

        $date = DateTime::createFromFormat('Y-m-d', $temp_edit_first_letter[0]->fye_date );
        $temp_edit_first_letter[0]->fye_date = $date->format('d/m/Y');

        // $date2 = DateTime::createFromFormat('Y-m-d', $temp_edit_first_letter[0]->send_date );
        // $temp_edit_first_letter[0]->send_date = $date2->format('d/m/Y');

        $this->data['edit_first_letter'] = $temp_edit_first_letter;
        $this->data['clearance_history_list'] = $this->list_of_auditor_model->get_clearance_history_list($id);
        $this->data['resend_flag'] = 2;

        // $this->data['holiday_list'] = $this->bank_model->get_holiday_list();
        // $this->data['type_of_leave_list'] = $this->bank_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->bank_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->bank_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->bank_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->bank_model->get_choose_carry_forward_list();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('First Clearance Letter', base_url('list_of_auditor'));
        $this->mybreadcrumb->add('Resend First Clearance Letter', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Edit First Clearance Letter'));
        $meta = array('page_title' => 'Edit First Clearance Letter', 'bc' => $bc, 'page_name' => 'Edit First Clearance Letter');

        $this->page_construct('add_first_clearance_letter.php', $meta, $this->data);

        // $this->page_construct('add_first_clearance_letter.php', $this->meta, $this->data);
    }

    public function edit_bank_auth($id)
    {
        $this->meta['page_name'] = 'Edit Bank Authorization';

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

        $this->page_construct('add_bank_auth.php', $this->meta, $this->data);
    }

    public function submit_auditor_list(){
        $this->session->set_userdata("tab_active", "auditor_list");

        $form_data = $this->input->post();

        $data = array(
            'audit_firm_name' => strtoupper($form_data['audit_firm_name']),
            'audit_firm_email' => $form_data['audit_firm_email'],
            'postal_code' => $form_data['postal_code'],
            'street_name' => strtoupper($form_data['street_name']),
            'building_name' => strtoupper($form_data['building_name']),
            'unit_no1' => $form_data['unit_no1'],
            'unit_no2' => $form_data['unit_no2']
        );  

        $result = $this->list_of_auditor_model->submit_auditor_list($data, $form_data['auditor_list_id']);

        echo $result;
    }

    public function save_first_letter()
    {
        $this->session->set_userdata("tab_active", "first_clearance_letter");
        $form_data = $this->input->post();

        $date = DateTime::createFromFormat('d/m/Y', $form_data['fye_date']);
        $fye_date = $date->format('Y-m-d');

        // $date2 = DateTime::createFromFormat('d/m/Y', $form_data['send_date']);
        // $send_date = $date2->format('Y-m-d');

        // $get_firm_from_service = $this->bank_model->get_firm_from_service($form_data['client_name']);
       // echo $form_data['auth_date'];

        // if($get_firm_from_service != null && $get_firm_from_service[0]["servicing_firm"] != 0)
        // {
     
        $data = array(
            'company_code' => $form_data['client_name'],
            'auditor_id' => $form_data['audit_firm_name'],
            'fye_date' => $fye_date,
            // 'send_date' => $send_date,
            'firm_id' => $form_data['our_firm_name'],
            'remarks' => $form_data['remark']
        );

        $result = $this->list_of_auditor_model->submit_first_letter($data);

        $data = array('status'=>'success', 'letter_id'=>$result);
          
        // }
        // else
        // {
        //     $data = array('status'=>'fail', 'bank_auth_id'=>"");
        // }

        echo json_encode($data);

    }


    public function save_bank_auth()
    {
        $this->session->set_userdata("tab_active", "bank_auth");
        $form_data = $this->input->post();

        $date = DateTime::createFromFormat('d/m/Y', $form_data['auth_date']);
        $auth_date = $date->format('Y-m-d');

        $get_firm_from_service = $this->bank_model->get_firm_from_service($form_data['client_name']);
       // echo $form_data['auth_date'];

        if($get_firm_from_service != null && $get_firm_from_service[0]["servicing_firm"] != 0)
        {
            $data = array(
                'company_code' => $form_data['client_name'],
                'bank_id' => $form_data['bank_name'],
                'auth_date' => $auth_date,
                'auth_status' => $form_data['auth_status'],
                'firm_id' => $get_firm_from_service[0]["servicing_firm"]
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
            'confirm_status' => $form_data['confirm_status'],
            'sent_on_date' => $send_date
        );

        $result = $this->bank_model->submit_bank_confirm($data);

        $data = array('status'=>'success', 'bank_confirm_id'=>$result);

        echo json_encode($data);
    }

    public function delete_auditor(){

        $form_data = $this->input->post();

        $result = $this->list_of_auditor_model->delete_auditor($form_data['auditor_id']);

        echo $result;
    }

    public function delete_first_letter(){
        $this->session->set_userdata("tab_active", "first_clearance_letter");

        $form_data = $this->input->post();

        $result = $this->list_of_auditor_model->delete_first_letter($form_data['letter_id']);

        echo $result;
    }


    public function generate_first_letter()
    {

        // $po_list = $this->products_model->getPdfData(); // retrieve all po from database
        if(!empty($this->input->post('letter_id')))
        {
            $letter_id = $this->input->post('letter_id');

            $date = DateTime::createFromFormat('d/m/Y', $this->input->post('send_date'));
            $send_date = $date->format('Y-m-d');
        
        }
        
        // print_r($confirm_id);
        if($letter_id != "")
        {
            $document_info_query = $this->db->select('transaction_master.*,  transaction_service_proposal_info.*,  fl.*, auditor.*, f.name as firm_name');
                                $this->db->from('transaction_master');
                                $this->db->join('transaction_service_proposal_info', 'transaction_service_proposal_info.transaction_id = transaction_master.id ', 'left');
                                $this->db->join('audit_first_clearance_letter fl', 'fl.company_code = transaction_master.company_code', 'left');
                                $this->db->join('firm f', 'fl.firm_id = f.id', 'left');
                                $this->db->join('audit_auditor_list auditor', 'auditor.id = fl.auditor_id', 'left');
                                $this->db->where('transaction_master.transaction_task_id', "29");
                                $this->db->where('fl.id', $letter_id);
                                $this->db->where('(transaction_master.service_status = 1 or transaction_master.service_status = 3)');
                                $this->db->order_by("transaction_master.id", "asc");
            $document_info_query = $this->db->get();
            
        }

        if($document_info_query->num_rows() == 0)
        {
            $document_info_query = $this->db->query("SELECT fl.*, auditor.*, c.company_name, c.client_code, f.id as firm_id, f.name as firm_name from audit_first_clearance_letter fl
                                                LEFT JOIN client c on fl.company_code = c.company_code 
                                                LEFT JOIN audit_auditor_list auditor on auditor.id = fl.auditor_id
                                                LEFT JOIN firm f on fl.firm_id = f.id
                                                WHERE fl.id = ".$letter_id);
        }

        
        $document_info_query = $document_info_query->result_array();

        // print_r($document_info_query);

        if(isset($document_info_query[0]['company_name']))
        {
            foreach ($document_info_query as $key => $value) {
                $document_info_query[$key]['company_name'] = $this->encryption->decrypt($document_info_query[$key]['company_name']);
            }
        }
        else
        {
            foreach ($document_info_query as $key => $value) {
                $document_info_query[$key]['company_name'] = $this->encryption->decrypt($document_info_query[$key]['client_name']);
            }
        }
        

        $detail = $document_info_query[0];



        
        $header_company_info = $this->write_header($document_info_query[0]['firm_id'], false);
        $obj_pdf = new NOFOOTER_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title = "First Clearance Letter";
        $obj_pdf->SetTitle($title);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info, $tc=array(0,0,0), $lc=array(0,0,0));


        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('times');
        $obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        // $obj_pdf->SetFooterMargin(10);
        $obj_pdf->SetMargins(28, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $obj_pdf->SetAutoPageBreak(true, 18);
        $obj_pdf->SetFont('times', '', 12);
        $obj_pdf->setFontSubsetting(false);
        $obj_pdf->setY(33);

        $obj_pdf->startPageGroup();

        $obj_pdf->AddPage();

        $counter = 0;


        $do_template_query = $this->db->query("select * from audit_doc_master_template where id = 3");

        $do_template_query = $do_template_query->result_array();

        // $this->load->helper('pdf_helper');

        $pattern = "/{{[^}}]*}}/";
        $subject = $do_template_query[0]["document_content"];
        preg_match_all($pattern, $subject, $matches);

        $contents_info = $do_template_query[0]["document_content"];
                
        
        $detail = $document_info_query[0];

        // $company_add = $this->write_address($detail['street_name'], $detail["unit_no1"], $detail["unit_no2"], $detail['building_name'], $detail["postal_code"], 'letter');

        // $firm_add = $this->write_address($detail['firm_street_name'], $detail["firm_unit_no1"], $detail["firm_unit_no2"], $detail['firm_building_name'], $detail["firm_postal_code"], 'letter');

        $audit_add = $this->write_address($detail['street_name'], $detail["unit_no1"], $detail["unit_no2"], $detail['building_name'], $detail["postal_code"], 'letter');
                  
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

                    $date = DateTime::createFromFormat('Y-m-d', $send_date);
                    $new_date = $date->format('d F Y');   
                   
                    $content = $new_date;        
                }
                elseif($string2 == "Audit firm name")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["do_number"];
                    $content = $detail['audit_firm_name'];
                }
                elseif($string2 == "Audit firm address")
                {
                    $replace_string = $toggle_array[$r];

                    // $date = DateTime::createFromFormat('Y-m-d', $detail["auth_date"]);
                    // $auth_date = $date->format('d F Y');

                    $content = $audit_add;
                }
                elseif($string2 == "Company name")
                {
                    $replace_string = $toggle_array[$r];

                    $content = $detail["company_name"];
                }
                elseif($string2 == "FYE date")
                {
                    $replace_string = $toggle_array[$r];

                    $date = DateTime::createFromFormat('Y-m-d', $detail["fye_date"]);
                    $fye_date = $date->format('d F Y');

                    // $content = $document_info_query[0]["order_code"];
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

            $file_name = "Clearance Letter - ".preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $this->myUrlEncode($detail["company_name"])).'('.DATE("Y-m-d His",now()).').pdf';
            $uploadData['clearance_id'] = $letter_id;
            $uploadData['file_path'] = "clearance";
            $uploadData['file_name'] = $file_name;
            $uploadData['send_date'] = $send_date;
            // $uploadData[$i]['sys_generated'] = 0;
            $this->db->insert('audit_clearance_doc', $uploadData);
       

            $obj_pdf->writeHTML($new_content_info, true, false, false, false, '');

        
            $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'audit/document/clearance/'.$file_name, 'F');

            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

            // $link = 'https://'. $_SERVER['SERVER_NAME'] .'/generate_do/pdf/document/Delivery Order ('.DATE("Y-m-d",now()).').pdf';
            $link = 'http://'. $_SERVER['SERVER_NAME'] .'/audit/document/clearance/'.$file_name;

            $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> $_SERVER['DOCUMENT_ROOT'].'audit/document/clearance/'.$file_name);

            // kill session for doc_cus_id array
            $this->session->unset_userdata('doc_cus_id');
            $this->session->set_userdata(array('doc_cus_id' =>array())); // create new session back for session 'doc_cus_id'

            echo json_encode($data);
            return $data;
        
    }

    public function generate_resignation_letter()
    {

        // $po_list = $this->products_model->getPdfData(); // retrieve all po from database
        if(!empty($this->input->post('rl_id')))
        {
            $letter_id = $this->input->post('rl_id');

            // $date = DateTime::createFromFormat('d/m/Y', $this->input->post('send_date'));
            // $send_date = $date->format('Y-m-d');
        
        }
        
        // print_r($confirm_id);

        $document_info_query = $this->db->query("SELECT rl.*, auditor.*, c.company_name, c.client_code, f.id as firm_id, f.name as firm_name from audit_resignation rl
                                                LEFT JOIN client c on rl.company_code = c.company_code 
                                                LEFT JOIN audit_auditor_list auditor on auditor.id = rl.auditor_id
                                                LEFT JOIN firm f on rl.firm_id = f.id
                                                WHERE rl.id = ".$letter_id);
        $document_info_query = $document_info_query->result_array();

        foreach ($document_info_query as $key => $value) {
            $document_info_query[$key]['company_name'] = $this->encryption->decrypt($document_info_query[$key]['company_name']);
        }

        // $detail = $document_info_query[0];



        
        $header_company_info = $this->write_header($document_info_query[0]['firm_id'], false);
        $obj_pdf = new NOFOOTER_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title = "First Clearance Letter";
        $obj_pdf->SetTitle($title);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info, $tc=array(0,0,0), $lc=array(0,0,0));


        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $obj_pdf->SetDefaultMonospacedFont('times');
        $obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        // $obj_pdf->SetFooterMargin(10);
        $obj_pdf->SetMargins(28, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $obj_pdf->SetAutoPageBreak(true, 18);
        $obj_pdf->SetFont('times', '', 12);
        $obj_pdf->setFontSubsetting(false);
        $obj_pdf->setY(33);

        $obj_pdf->startPageGroup();

        $obj_pdf->AddPage();

        $counter = 0;


        $do_template_query = $this->db->query("select * from audit_doc_master_template where id = 5");

        $do_template_query = $do_template_query->result_array();

        // $this->load->helper('pdf_helper');

        $pattern = "/{{[^}}]*}}/";
        $subject = $do_template_query[0]["document_content"];
        preg_match_all($pattern, $subject, $matches);

        $contents_info = $do_template_query[0]["document_content"];
                
        
        $detail = $document_info_query[0];

        // $company_add = $this->write_address($detail['street_name'], $detail["unit_no1"], $detail["unit_no2"], $detail['building_name'], $detail["postal_code"], 'letter');

        // $firm_add = $this->write_address($detail['firm_street_name'], $detail["firm_unit_no1"], $detail["firm_unit_no2"], $detail['firm_building_name'], $detail["firm_postal_code"], 'letter');

        $audit_add = $this->write_address($detail['street_name'], $detail["unit_no1"], $detail["unit_no2"], $detail['building_name'], $detail["postal_code"], 'letter');
                  
        $toggle_array = $matches[0];

        if(count($toggle_array) != 0)
        {
            for($r = 0; $r < count($toggle_array); $r++)
            {
                $string1 = (str_replace('{{', '',$toggle_array[$r]));
                $string2 = (str_replace('}}', '',$string1));

 
                
                if($string2 == "our date")
                {
                    $replace_string = $toggle_array[$r];

                    $date = DateTime::createFromFormat('Y-m-d', $detail['date_of_our_letter']);
                    $new_date = $date->format('d F Y');   
                   
                    $content = $new_date;        
                }
                elseif($string2 == "Audit firm name")
                {
                    $replace_string = $toggle_array[$r];

                    // $content = $document_info_query[0]["do_number"];
                    $content = $detail['audit_firm_name'];
                }
                elseif($string2 == "Audit firm address")
                {
                    $replace_string = $toggle_array[$r];

                    // $date = DateTime::createFromFormat('Y-m-d', $detail["auth_date"]);
                    // $auth_date = $date->format('d F Y');

                    $content = $audit_add;
                }
                elseif($string2 == "Company name")
                {
                    $replace_string = $toggle_array[$r];

                    $content = $detail["company_name"];
                }
                elseif($string2 == "their date")
                {
                    $replace_string = $toggle_array[$r];

                    $date = DateTime::createFromFormat('Y-m-d', $detail["date_of_letter"]);
                    $their_date = $date->format('d F Y');

                    // $content = $document_info_query[0]["order_code"];
                    $content = $their_date;
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

            // if($detail['firm_name'] == "ACUMEN ASSOCIATES LLP")
            // {
            //     //$img_tag = '<img src="img/Signature - AA LLP.png" height="85px;"' . ' />';
            //     $img_tag = 'img/Signature - AA LLP.png';
            //     // if($q[0]["document_name"] == "Audit Engagement")
            //     // {
            //     //  $obj_pdf->setY($obj_pdf->getY() - 33);
            //     // }
            //     // else
            //     // {
            //         // $obj_pdf->setY($obj_pdf->getY() - 40);
            //     // }
            //         $obj_pdf->Image($img_tag, '', '155', 82, 23, '', '', '', false, 1000, '', false, false, 1, false, false, false);
            // }
            // elseif($detail['firm_name'] == "SYA PAC")
            // {
                
            //     //$img_tag = '<img src="img/Signature - SYA.png" height="200px;"' . ' />';
            //     $img_tag = 'img/Signature - SYA.png';

            //     // if($q[0]["document_name"] == "Engagement letter - Corporate Tax")
            //     // {
            //     //  $obj_pdf->setY($obj_pdf->getY() - 38);
            //     // }
            //     // elseif($q[0]["document_name"] == "Audit Engagement")
            //     // {
            //     //  $obj_pdf->setY($obj_pdf->getY() - 38);
            //     // }
            //     // elseif($q[0]["document_name"] == "ML Quarterly Statements")
            //     // {
            //     //  $obj_pdf->setY($obj_pdf->getY() - 38);
            //     // }
            //     // else
            //     // {
            //         // $obj_pdf->setY($obj_pdf->getY() - 0);
            //     //}
            //     $obj_pdf->Image($img_tag, '', '150', 45, 40, '', '', 'RTL', false, 1000, '', false, false, 1, false, false, false);
            // }
            // elseif($detail['firm_name'] == "ACUMEN ASSURANCE")
            // {
            //     //$img_tag = '<img src="img/Signature - AA.png" height="75px;"' . ' />';
            //     $img_tag = 'img/Signature - AA.png';
            //     // if($q[0]["document_name"] == "Audit Engagement")
            //     // {
            //     //  $obj_pdf->setY($obj_pdf->getY() - 40);
            //     // }
            //     // else
            //     // {
            //         // $obj_pdf->setY($obj_pdf->getY() - 45);
            //     // }
            //         $obj_pdf->Image($img_tag, '', '154', 82, 24, '', '', 'RTL', false, 1000, '', false, false, 1, false, false, false);
            // }
            // elseif($detail['firm_name'] == "ACUMEN BIZCORP PTE. LTD.")
            // {
            //     //$img_tag = '<img src="img/Signature - AA.png" height="75px;"' . ' />';
            //     $img_tag = 'img/Signature - ABC.png';
            //     // if($q[0]["document_name"] == "Audit Engagement")
            //     // {
            //     //  $obj_pdf->setY($obj_pdf->getY() - 40);
            //     // }
            //     // else
            //     // {
            //         // $obj_pdf->setY($obj_pdf->getY() - 45);
            //     // }
            //         $obj_pdf->Image($img_tag, '', '150', 58, 30, '', '', 'RTL', false, 1000, '', false, false, 1, false, false, false);
            // }

            $new_content_info = $contents_info;

            $file_name = "Resignation Letter - ".preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $this->myUrlEncode($detail["company_name"])).'('.DATE("Y-m-d His",now()).').pdf';
            // $uploadData['clearance_id'] = $letter_id;
            // $uploadData['file_path'] = "clearance";
            $uploadData['generated_letter'] = $file_name;
            // $uploadData['send_date'] = $send_date;
            // $uploadData[$i]['sys_generated'] = 0;
            $this->db->where('id', $letter_id);
            $result = $this->db->update('audit_resignation', $uploadData);

            $this->resignation_to_paf($letter_id);
       

            $obj_pdf->writeHTML($new_content_info, true, false, false, false, '');

        
            $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'audit/document/resignation/'.$file_name, 'F');

            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

            // $link = 'https://'. $_SERVER['SERVER_NAME'] .'/generate_do/pdf/document/Delivery Order ('.DATE("Y-m-d",now()).').pdf';
            $link = 'http://'. $_SERVER['SERVER_NAME'] .'/audit/document/resignation/'.$file_name;

            $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> $_SERVER['DOCUMENT_ROOT'].'audit/document/resignation/'.$file_name);

            // kill session for doc_cus_id array
            $this->session->unset_userdata('doc_cus_id');
            $this->session->set_userdata(array('doc_cus_id' =>array())); // create new session back for session 'doc_cus_id'

            echo json_encode($data);
            return $data;
        
    }

    public function move_clearance_to_paf()
    {
        $this->session->set_userdata("tab_active", "first_clearance_letter");
        $user_id = $this->session->userdata('user_id');
        $form_data = $this->input->post();
        $i = 0;

        $selected_move_clearance = $form_data['selected_move_clearance'];
        
        //change bank authorization move status to 1
        $this->list_of_auditor_model->move_clearance($selected_move_clearance[0]['clearance_id']);

        $cl_fix_child_id = $this->list_of_auditor_model->get_cl_fix_child_id($selected_move_clearance[0]['company_code']);

        // //insert child paf
        // $c_data   = array('company_code' =>  $selected_move_auth['company_code'],
        //                 'parent_id'    =>  $paf_parent_id,
        //                 'index_no'     =>  "",
        //                 'text'         =>  $selected_move_auth['bank_name'],
        //                 'type'         =>  "dynmc"
        //             );

        // $paf_child_id = $this->client_model->insert_paf_child($c_data);

        //move documents
        // $auth_documents = $this->list_of_auditor_model->get_clearance_doc_byId($selected_move_clearance['clearance_id']);

        foreach ($selected_move_clearance as $file_key => $each) {
                # code...

            $upload_data[$i]['paf_child_id'] = $cl_fix_child_id;
            $upload_data[$i]['file_name'] = $each['file_name'];
            $upload_data[$i]['file_path'] = $each['file_path'];

            $i++;
        }

        //log upload document to paf
        $data   = array(
                    'paf_id'     => $cl_fix_child_id,
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $user_id,
                    'paf_log'    => "upload document(s)",
                    'company_code' => $selected_move_clearance[0]['company_code']
                  );

        $this->db->insert('audit_paf_log', $data);

        if(!empty($upload_data))
        {
            $this->db->insert_batch('audit_paf_document',$upload_data);
            // print_r($uploadData);
            
        }


    }

    public function resignation_to_paf($rl_id)
    {
        $this->session->set_userdata("tab_active", "resignation");
        $user_id = $this->session->userdata('user_id');
        $form_data = $this->input->post();
        $i = 0;
        
        $selected_rl = $this->list_of_auditor_model->get_rl_details($rl_id);

        // print_r($selected_rl);

        $paf_parent_id = $this->list_of_auditor_model->get_paf_parent_id($selected_rl['company_code']);

        //insert child paf
        $c_data   = array('company_code' =>  $selected_rl['company_code'],
                        'parent_id'    =>  $paf_parent_id,
                        'index_no'     =>  "",
                        'text'         =>  "Outgoing clearance",
                        'type'         =>  "dynmc"
                    );

        $outgoing_paf_child_id = $this->client_model->insert_paf_child($c_data);

        //insert doc
        $c_doc_data   = array('paf_child_id' =>  $outgoing_paf_child_id,
                        'file_name'    =>  $selected_rl['file_name'],
                        'file_path'    =>  $selected_rl['file_path']
                    );

        $this->db->insert("audit_paf_document", $c_doc_data);

        //log upload document to paf
        $data   = array(
                    'paf_id'     => $outgoing_paf_child_id,
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $user_id,
                    'paf_log'    => "upload document(s)",
                    'company_code' => $selected_rl['company_code']
                  );

        $this->db->insert('audit_paf_log', $data);

        //insert child paf
        $i_data   = array('company_code' =>  $selected_rl['company_code'],
                        'parent_id'    =>  $paf_parent_id,
                        'index_no'     =>  "",
                        'text'         =>  "Clearance request from incoming auditors",
                        'type'         =>  "dynmc"
                    );

        $incoming_paf_child_id = $this->client_model->insert_paf_child($i_data);

        //insert doc
        $i_doc_data   = array('paf_child_id' =>  $incoming_paf_child_id,
                        'file_name'    =>  $selected_rl['generated_letter'],
                        'file_path'    =>  $selected_rl['file_path']
                    );

        $this->db->insert("audit_paf_document", $i_doc_data);

        //log upload document to paf
        $data   = array(
                    'paf_id'     => $incoming_paf_child_id,
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $user_id,
                    'paf_log'    => "upload document(s)",
                    'company_code' => $selected_rl['company_code']
                  );

        $this->db->insert('audit_paf_log', $data);

        // if(!empty($upload_data))
        // {
        //     $this->db->insert_batch('audit_paf_document',$upload_data);
        //     // print_r($uploadData);
            
        // }


    }

    public function get_latest_first_letter()
    {
        if(!empty($this->input->post('letter_id')))
        {
            $letter_id = $this->input->post('letter_id');
        }

        $q = $this->db->query("SELECT * FROM `audit_clearance_doc` 
                                WHERE clearance_id = ".$letter_id." AND sys_generated = 1
                                ORDER BY created_at DESC
                                LIMIT 1");
        $file_info = $q->result_array();

        $link = 'http://'. $_SERVER['SERVER_NAME'] .'/audit/document/clearance/'.$file_info[0]["file_name"];

        $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> $_SERVER['DOCUMENT_ROOT'].'audit/document/clearance/'.$file_info[0]["file_name"]);

        echo json_encode($data);
        return $data;
    }

    public function get_clearance_attachment()
    {
        if(!empty($this->input->post('cl_id')))
        {
            $letter_id = $this->input->post('cl_id');
        }

        $this->db->select('doc.*, company_code');
        $this->db->from('audit_clearance_doc doc');
        $this->db->join('audit_first_clearance_letter cl','doc.clearance_id = cl.id', 'left');
        $this->db->where(array('clearance_id' => $letter_id));
        $this->db->order_by("created_at asc");

  
        $aq = $this->db->get(); 

        $data = array('status'=>'success', 'attachment'=>$aq->result_array());

        echo json_encode($data);
        return $data;
    }
   

   

    public static function getPotentialClient() 
    {
        $ci =& get_instance();
        
        $result = $this->db->query('select client.id, client.company_code, client.company_name from client
    where client.deleted = 0 and client.company_code not in (select company_code from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service where (our_service_info.service_type = 1) and client_billing_info.deactive = 0 and client_billing_info.deleted = 0)');

        $result = $result->result_array();
        //echo json_encode($result);
        if(!$result) {
            throw new exception("Client not found.");
        }

        $res = array();
        $master_client_name_info = array();
        foreach($result as $row) {
            $row['company_name'] = $this->encryption->decrypt($row['company_name']);
            array_push($master_client_name_info, trim($row['company_name']));
            $res[$row['company_code']] = $row['company_name'];
        }

        $trans_master_result = $this->db->query('select transaction_master.* from transaction_master left join transaction_service_proposal_info on transaction_master.id = transaction_service_proposal_info.transaction_id where transaction_master.service_status = 3 and transaction_master.transaction_task_id = 29 and transaction_service_proposal_info.potential_client = 1');// and firm_id = "'.$this->session->userdata('firm_id').'" //transaction_master.service_status != 2 and transaction_master.service_status != 4 and

        $trans_master_result = $trans_master_result->result_array();
        //echo json_encode($result);
        // if(!$trans_master_result) {
        //  throw new exception("Potential Client not found.");
        // }
        //echo json_encode($master_client_name_info);
        foreach($trans_master_result as $row) {
            $row['client_name'] = $this->encryption->decrypt($row['client_name']);
            //print_r(in_array($row['client_name'], $master_client_name_info));
            if(!(in_array(trim($row['client_name']), $master_client_name_info))) {
                $res[$row['company_code']] = $row['client_name'].' (Potential Client)';
            }

        }

        $selected_client = $ci->session->userdata('potential_company_code');
        $ci->session->unset_userdata('potential_company_code');

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client fetched successfully.", 'result'=>$res, 'selected_potential_client'=>$selected_client);

        echo json_encode($data);
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
        //         WHERE our_service_info.service_type = 1  AND client_billing_info.deactive = 0 AND client.deleted = 0  AND user_firm.user_id = "'.$this->session->userdata('user_id').'"';

        $query = 'SELECT client.id, client.company_code, client.company_name FROM client 
                right join client_billing_info on client_billing_info.company_code = client.company_code 
                right join our_service_info on our_service_info.id = client_billing_info.service
                WHERE our_service_info.service_type = 1  AND client_billing_info.deactive = 0 AND client.deleted = 0';


        

       

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
            $selected_client_name = $ci->session->userdata('rl_company_code');
            $ci->session->unset_userdata('rl_company_code');

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

    public static function getAuditorName() 
    {
        $ci =& get_instance();
        $form_data = $this->input->get();

        if(isset($form_data['company_code']))
        {
           if($form_data['company_code'] == "" || $form_data['company_code'] == null)
            {
                $query = 'SELECT auditor.id, auditor.audit_firm_name FROM audit_auditor_list auditor where deleted != 1';
            }
            else
            {
                //get list of auditor of respective client if compsany is choosen
                $query = "SELECT f_letter.auditor_id as id, auditor.audit_firm_name FROM audit_auditor_list auditor, audit_first_clearance_letter f_letter bank WHERE company_code = '".$form_data['company_code']."' and auditor.id = f_letter.auditor_id";
            } 
        }
        else
        {
            $query = 'SELECT auditor.id, auditor.audit_firm_name FROM audit_auditor_list auditor where deleted != 1';
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
                if($row['audit_firm_name'] != null)
                {
                    $res[$row['id']] = $row['audit_firm_name'];
                }
              
            }
            //$res = json_decode($res);
            $ci =& get_instance();
            $selected_auditor_name = $ci->session->userdata('auth_auditor_id');
            $ci->session->unset_userdata('auth_auditor_id');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Bank Name fetched successfully.", 'result'=>$res, 'selected_auditor_name'=>$selected_auditor_name);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_auditor_name'=>'');

            echo json_encode($data);
        }
    }

    public static function getFirmName()
    {
        $ci =& get_instance();

        $query = 'SELECT firm.id, firm.name FROM firm 
                  LEFT JOIN user_firm on user_firm.firm_id = firm.id 
                  WHERE user_firm.user_id = '.$this->session->userdata('user_id');

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("Firm not found.");
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
            $selected_firm_name = $ci->session->userdata('rl_firm_id');
            $ci->session->unset_userdata('rl_firm_id');

            // print_r($res);

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Firm Name fetched successfully.", 'result'=>$res, 'selected_firm_name'=>$selected_firm_name);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_firm_name'=>'');

            echo json_encode($data);
        }
    }

    public static function getAuthStatus() {

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

    public static function getPicName() 
    {
        $ci =& get_instance();

        // $this->db->select('client.id, client.company_code, client.company_name');
        // $this->db->from('client');
        // // $this->db->join('audit_client', 'audit_client.company_code = client.company_code', 'left');
        // $this->db->join('client_billing_info', 'client_billing_info.company_code = client.company_code', 'right');
        // $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service AND our_service_info.service_type = 1', 'right');
        // // $query = $this->db->get();

        $query = 'SELECT `id`,`first_name`,`last_name` FROM `users` WHERE `department_id` = 1';

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
                    $res[$row['id']] = $row['first_name']." ".$row['last_name'];
                }
              
            }
            //$res = json_decode($res);
            $ci =& get_instance();
            // $selected_client_name = $ci->session->userdata('auth_company_code');
            // $ci->session->unset_userdata('auth_company_code');

            // print_r($res);

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"PIC Name fetched successfully.", 'result'=>$res);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_client_name'=>'');

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
                    $res[$row['company_code']] = $row['company_name'];
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

    public function updt_clearance_status(){
        
        $this->session->set_userdata("tab_active", "first_clearance_letter");

        $form_data = $this->input->post();

        $result = $this->list_of_auditor_model->updt_clearance_status($form_data['status_id'], $form_data['letter_id']);

        echo $result;
    }


    public function updt_auth_status(){
        
        $this->session->set_userdata("tab_active", "bank_auth");

        $form_data = $this->input->post();

        $result = $this->bank_model->updt_auth_status($form_data['status_id'], $form_data['bank_auth_id']);

        echo $result;
    }

    

    public function updt_confirm_sent_date(){
        
        $this->session->set_userdata("tab_active", "bank_confirm");

        $form_data = $this->input->post();

        $result = $this->bank_model->updt_confirm_sent_date($form_data['bank_confirm_id'], $form_data['sent_on_date']);

        echo $result;
    }

    public static function get_clearance_doc_list()
    {
        $form_data = $this->input->post();
        $cl_id = $form_data['letter_id'];

        $query = 'SELECT clearance_id, GROUP_CONCAT(DISTINCT CONCAT(id,",",file_name)SEPARATOR ";") as file_names FROM audit_clearance_doc 
                    WHERE clearance_id ='.$cl_id.' AND sys_generated = 0';

        $q = $this->db->query($query);

        foreach ($q->result() as $key => $value) {
            if($q->result()[$key]->file_names != null)
            {
                $q->result()[$key]->file_names = explode(';', $q->result()[$key]->file_names);
            }
        }
        

        echo json_encode($q-> result());

    }

    public function uploadClDoc()
    {
        $this->session->set_userdata("tab_active", "first_clearance_letter");
        if(isset($_FILES['cl_docs']))
        {
            $filesCount = count($_FILES['cl_docs']['name']);

            for($i = 0; $i < $filesCount; $i++)
            {   //echo json_encode($_FILES['uploadimages']);
                $_FILES['cl_doc']['name']     = $_FILES['cl_docs']['name'][$i];
                $_FILES['cl_doc']['type']     = $_FILES['cl_docs']['type'][$i];
                $_FILES['cl_doc']['tmp_name'] = $_FILES['cl_docs']['tmp_name'][$i];
                $_FILES['cl_doc']['error']    = $_FILES['cl_docs']['error'][$i];
                $_FILES['cl_doc']['size']     = $_FILES['cl_docs']['size'][$i];

                $uploadPath = './document/clearance';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('cl_doc'))
                {
                    $fileData = $this->upload->data();
                    $uploadData[$i]['clearance_id'] = $_POST['letter_id'];
                    $uploadData[$i]['file_path'] = "clearance";
                    $uploadData[$i]['file_name'] = $fileData['file_name'];
                    $uploadData[$i]['sys_generated'] = 0;

                }
                else
                {
                    $error = $this->upload->display_errors();
                    // echo json_encode($error);
                    // return;
                }

            }
            if(!empty($uploadData))
            {
                $this->db->insert_batch('audit_clearance_doc',$uploadData);
                // print_r($uploadData);
                
            }


        }

        if (!is_null($this->session->userdata('cl_files_id')) && count($this->session->userdata('cl_files_id')) != 0)
        {
            $cl_files_id = $this->session->userdata('cl_files_id');
            $this->session->unset_userdata('cl_files_id');
            for($i = 0; $i < count($cl_files_id); $i++)
            {
                $files = $this->db->query("select * from audit_clearance_doc where id='".$cl_files_id[$i]."'");
                $file_info = $files->result_array();

                $this->db->where('id', $cl_files_id[$i]);

                if(file_exists("./document/".$file_info[0]["file_path"]."/".$file_info[0]["file_name"]))
                {
                    unlink("./document/".$file_info[0]["file_path"]."/".$file_info[0]["file_name"]);

                }
                
                $this->db->delete('audit_clearance_doc', array('id' => $cl_files_id[$i]));
            }
        }

        
        if(isset($fileData))
        {
            echo json_encode($_POST['letter_id']);
        }
        else
        {
            echo json_encode("empty");
        }

        
    }

    public function deleteClFile($id)
    {
        if($this->session->userdata('cl_files_id') != null)
        {
             $cl_files_id = $this->session->userdata('cl_files_id');
        }
        else
        {
            $cl_files_id = array();
        }
       
        array_push($cl_files_id, $id);
        $this->session->set_userdata(array(
            'cl_files_id'  =>  $cl_files_id,
        ));

        echo json_encode($cl_files_id);
    }

    public function clear_delete_clearance_session()
    {

        $this->session->unset_userdata('cl_files_id');
    }

    public function myUrlEncode($string) {
        $replacements = array('');
        $entities = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
        return str_replace($entities, $replacements, $string);
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
            $address = $street_name . $comma . $br . 'SINGAPORE ' . $postal_code;
        }
        return $address;
    }

    public function uploadResignationDoc()
    {
        $this->session->set_userdata("tab_active", "resignation");
        if(isset($_FILES['resignation_doc']))
        {
            // $filesCount = count($_FILES['cl_docs']['name']);

            // for($i = 0; $i < $filesCount; $i++)
            // {   //echo json_encode($_FILES['uploadimages']);
            //     $_FILES['cl_doc']['name']     = $_FILES['cl_docs']['name'][$i];
            //     $_FILES['cl_doc']['type']     = $_FILES['cl_docs']['type'][$i];
            //     $_FILES['cl_doc']['tmp_name'] = $_FILES['cl_docs']['tmp_name'][$i];
            //     $_FILES['cl_doc']['error']    = $_FILES['cl_docs']['error'][$i];
            //     $_FILES['cl_doc']['size']     = $_FILES['cl_docs']['size'][$i];
                $form_data = json_decode($_POST['resignation_form']);
                $uploadPath = './document/resignation';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';

                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('resignation_doc'))
                {
                    // print_r($form_data);
            
                    $fileData = $this->upload->data();
                    $date = DateTime::createFromFormat('d/m/Y', $form_data->our_date);
                    $our_date = $date->format('Y-m-d');

                    $their_date = DateTime::createFromFormat('d/m/Y', $form_data->their_date);
                    $their_date = $their_date->format('Y-m-d');

                    // $date2 = DateTime::createFromFormat('d/m/Y', $form_data['send_date']);
                    // $send_date = $date2->format('Y-m-d');

                    // $get_firm_from_service = $this->bank_model->get_firm_from_service($form_data['client_name']);
                   // echo $form_data['auth_date'];

                    // if($get_firm_from_service != null && $get_firm_from_service[0]["servicing_firm"] != 0)
                    // {
                 
                    $data = array(
                        'company_code' => $form_data->client_name,
                        'auditor_id' => $form_data->audit_firm_name,
                        'date_of_letter' => $their_date,
                        'date_of_our_letter' => $our_date,
                        // 'send_date' => $send_date,
                        'firm_id' => $form_data->our_firm_name,
                        'file_path' => "resignation",
                        'file_name' => $fileData['file_name']
                    );



                    // $uploadData[$i]['clearance_id'] = $_POST['letter_id'];
                    // $uploadData[$i]['file_path'] = "clearance";
                    // $uploadData[$i]['file_name'] = $fileData['file_name'];
                    // $uploadData[$i]['sys_generated'] = 0;

                }
                else
                {
                    $error = $this->upload->display_errors();
                    // echo json_encode($error);
                    // return;
                }

            // }
            if(!empty($data))
            {
                $result = $this->list_of_auditor_model->submit_resignation($data);

                if(!$form_data->resignation_letter_id)
                {
                    $this->list_of_auditor_model->set_company_resign($form_data->client_name);
                }
                
                // $this->resignation_to_paf($result);
                 //Deactivate the service of statutory audit, stocktake reminder for that client and bank confirmation after save resignation


                // print_r($result);
                
            }


        }

        // if (count($this->session->userdata('cl_files_id')) != 0)
        // {
        //     $cl_files_id = $this->session->userdata('cl_files_id');
        //     $this->session->unset_userdata('cl_files_id');
        //     for($i = 0; $i < count($cl_files_id); $i++)
        //     {
        //         $files = $this->db->query("select * from audit_clearance_doc where id='".$cl_files_id[$i]."'");
        //         $file_info = $files->result_array();

        //         $this->db->where('id', $cl_files_id[$i]);

        //         if(file_exists("./document/".$file_info[0]["file_path"]."/".$file_info[0]["file_name"]))
        //         {
        //             unlink("./document/".$file_info[0]["file_path"]."/".$file_info[0]["file_name"]);

        //         }
                
        //         $this->db->delete('audit_clearance_doc', array('id' => $cl_files_id[$i]));
        //     }
        // }

        
        if(isset($fileData))
        {
            echo json_encode($result);
        }
        else
        {
            echo json_encode("empty");
        }

        
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


