<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once('assets/vendor/tcpdf/tcpdf.php');


class Engagement extends MX_Controller
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
        $this->load->model('engagement_model');
        $this->load->model('list_of_auditor/list_of_auditor_model');
        $this->load->model('client/client_model');
    }

    public function index()
    {   
        // $this->meta['page_name'] = 'List of Auditor';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $this->data['el_list'] = $this->engagement_model->get_initial_el_list();
        $this->data['subsequent_el_list'] = $this->engagement_model->get_subsequent_el_list();
        $this->data['status_dropdown'] = $this->engagement_model->get_status_dropdown_list();

        $this->data['initial_el_logs'] = $this->engagement_model->get_initial_el_logs();
        $this->data['subsequent_el_logs'] = $this->engagement_model->get_subsequent_el_logs();
        
        // $this->data['first_letter'] = $this->list_of_auditor_model->get_first_letter();
        // $this->data['resignation_letter'] = $this->list_of_auditor_model->get_resignation_letter();

        

        $bc = array(array('link' => '#', 'page' => 'Engagement'));
        $meta = array('page_title' => 'Engagement', 'bc' => $bc, 'page_name' => 'Engagement');

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

    public function add_engagement_letter()
    {
        
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Engagement', base_url('engagement'));
        $this->mybreadcrumb->add('Create Engagement Letter', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        $this->data['transaction_engagement_letter_list'] = $this->engagement_model->getTransactionEngagementLetterList();

        $bc = array(array('link' => '#', 'page' => 'Engagement'));
        $meta = array('page_title' => 'Engagement', 'bc' => $bc, 'page_name' => 'Create Engagement');

        $this->page_construct('add_engagement_letter.php', $meta, $this->data);
    }

    public function edit_engagement_letter($el_id)
    {
        
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Engagement', base_url('engagement'));
        $this->mybreadcrumb->add('Edit Engagement Letter', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        $this->data['transaction_engagement_letter_list'] = $this->engagement_model->getTransactionEngagementLetterList();

        $this->data['selected_el'] = $this->engagement_model->get_el_detail($el_id);

        $bc = array(array('link' => '#', 'page' => 'Engagement'));
        $meta = array('page_title' => 'Engagement', 'bc' => $bc, 'page_name' => 'Edit Engagement');

        $this->page_construct('add_engagement_letter.php', $meta, $this->data);
    }

    public function add_subsequent_el()
    {
        
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Subsequent Engagement', base_url('engagement'));
        $this->mybreadcrumb->add('Create Subsequent Engagement Letter', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        $this->data['transaction_engagement_letter_list'] = $this->engagement_model->getTransactionEngagementLetterList();

        $bc = array(array('link' => '#', 'page' => 'Subsequent Engagement'));
        $meta = array('page_title' => 'Subsequent Engagement', 'bc' => $bc, 'page_name' => 'Create Subsequent Engagement');

        $this->page_construct('add_subsequent_el.php', $meta, $this->data);
    }

    public function edit_subsequent_el($el_id)
    {
        
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Engagement', base_url('engagement'));
        $this->mybreadcrumb->add('Edit Subsequent Engagement Letter', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        //initial engagement letter type list
        $this->data['transaction_engagement_letter_list'] = $this->engagement_model->getTransactionEngagementLetterList();

        $this->data['selected_sub_el'] = $this->engagement_model->get_sub_el_detail($el_id);

        $bc = array(array('link' => '#', 'page' => 'Engagement'));
        $meta = array('page_title' => 'Engagement', 'bc' => $bc, 'page_name' => 'Edit Subsequent Engagement');

        $this->page_construct('add_subsequent_el.php', $meta, $this->data);
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
        // $this->session->set_userdata("tab_active", "bank_list");

        $form_data = $this->input->post();

        $data = array(
            'audit_firm_name' => $form_data['audit_firm_name'],
            'audit_firm_email' => $form_data['audit_firm_email'],
            'postal_code' => $form_data['postal_code'],
            'street_name' => $form_data['street_name'],
            'building_name' => $form_data['building_name'],
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

        $document_info_query = $this->db->query("SELECT fl.*, auditor.*, c.company_name, c.client_code, f.id as firm_id, f.name as firm_name from audit_first_clearance_letter fl
                                                LEFT JOIN client c on fl.company_code = c.company_code 
                                                LEFT JOIN audit_auditor_list auditor on auditor.id = fl.auditor_id
                                                LEFT JOIN firm f on fl.firm_id = f.id
                                                WHERE fl.id = ".$letter_id);
        $document_info_query = $document_info_query->result_array();

        foreach ($document_info_query as $key => $value) {
            $document_info_query[$key]['company_name'] = $this->encryption->decrypt($document_info_query[$key]['company_name']);
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

            if($detail['firm_name'] == "ACUMEN ASSOCIATES LLP")
            {
                //$img_tag = '<img src="img/Signature - AA LLP.png" height="85px;"' . ' />';
                $img_tag = 'img/Signature - AA LLP.png';
                // if($q[0]["document_name"] == "Audit Engagement")
                // {
                //  $obj_pdf->setY($obj_pdf->getY() - 33);
                // }
                // else
                // {
                    // $obj_pdf->setY($obj_pdf->getY() - 40);
                // }
                    $obj_pdf->Image($img_tag, '', '186', 82, 23, '', '', '', false, 1000, '', false, false, 1, false, false, false);
            }
            elseif($detail['firm_name'] == "SYA PAC")
            {
                
                //$img_tag = '<img src="img/Signature - SYA.png" height="200px;"' . ' />';
                $img_tag = 'img/Signature - SYA.png';

                // if($q[0]["document_name"] == "Engagement letter - Corporate Tax")
                // {
                //  $obj_pdf->setY($obj_pdf->getY() - 38);
                // }
                // elseif($q[0]["document_name"] == "Audit Engagement")
                // {
                //  $obj_pdf->setY($obj_pdf->getY() - 38);
                // }
                // elseif($q[0]["document_name"] == "ML Quarterly Statements")
                // {
                //  $obj_pdf->setY($obj_pdf->getY() - 38);
                // }
                // else
                // {
                    // $obj_pdf->setY($obj_pdf->getY() - 0);
                //}
                $obj_pdf->Image($img_tag, '', '182', 45, 40, '', '', 'RTL', false, 1000, '', false, false, 1, false, false, false);
            }
            elseif($detail['firm_name'] == "ACUMEN ASSURANCE")
            {
                //$img_tag = '<img src="img/Signature - AA.png" height="75px;"' . ' />';
                $img_tag = 'img/Signature - AA.png';
                // if($q[0]["document_name"] == "Audit Engagement")
                // {
                //  $obj_pdf->setY($obj_pdf->getY() - 40);
                // }
                // else
                // {
                    // $obj_pdf->setY($obj_pdf->getY() - 45);
                // }
                    $obj_pdf->Image($img_tag, '', '184', 82, 24, '', '', 'RTL', false, 1000, '', false, false, 1, false, false, false);
            }
            elseif($detail['firm_name'] == "ACUMEN BIZCORP PTE. LTD.")
            {
                //$img_tag = '<img src="img/Signature - AA.png" height="75px;"' . ' />';
                $img_tag = 'img/Signature - ABC.png';
                // if($q[0]["document_name"] == "Audit Engagement")
                // {
                //  $obj_pdf->setY($obj_pdf->getY() - 40);
                // }
                // else
                // {
                    // $obj_pdf->setY($obj_pdf->getY() - 45);
                // }
                    $obj_pdf->Image($img_tag, '', '184', 82, 24, '', '', 'RTL', false, 1000, '', false, false, 1, false, false, false);
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

            chmod($_SERVER['DOCUMENT_ROOT'].'audit/document/clearance/'.$file_name,0644);

            $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';


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

            if($detail['firm_name'] == "ACUMEN ASSOCIATES LLP")
            {
                //$img_tag = '<img src="img/Signature - AA LLP.png" height="85px;"' . ' />';
                $img_tag = 'img/Signature - AA LLP.png';
                // if($q[0]["document_name"] == "Audit Engagement")
                // {
                //  $obj_pdf->setY($obj_pdf->getY() - 33);
                // }
                // else
                // {
                    // $obj_pdf->setY($obj_pdf->getY() - 40);
                // }
                    $obj_pdf->Image($img_tag, '', '155', 82, 23, '', '', '', false, 1000, '', false, false, 1, false, false, false);
            }
            elseif($detail['firm_name'] == "SYA PAC")
            {
                
                //$img_tag = '<img src="img/Signature - SYA.png" height="200px;"' . ' />';
                $img_tag = 'img/Signature - SYA.png';

                // if($q[0]["document_name"] == "Engagement letter - Corporate Tax")
                // {
                //  $obj_pdf->setY($obj_pdf->getY() - 38);
                // }
                // elseif($q[0]["document_name"] == "Audit Engagement")
                // {
                //  $obj_pdf->setY($obj_pdf->getY() - 38);
                // }
                // elseif($q[0]["document_name"] == "ML Quarterly Statements")
                // {
                //  $obj_pdf->setY($obj_pdf->getY() - 38);
                // }
                // else
                // {
                    // $obj_pdf->setY($obj_pdf->getY() - 0);
                //}
                $obj_pdf->Image($img_tag, '', '150', 45, 40, '', '', 'RTL', false, 1000, '', false, false, 1, false, false, false);
            }
            elseif($detail['firm_name'] == "ACUMEN ASSURANCE")
            {
                //$img_tag = '<img src="img/Signature - AA.png" height="75px;"' . ' />';
                $img_tag = 'img/Signature - AA.png';
                // if($q[0]["document_name"] == "Audit Engagement")
                // {
                //  $obj_pdf->setY($obj_pdf->getY() - 40);
                // }
                // else
                // {
                    // $obj_pdf->setY($obj_pdf->getY() - 45);
                // }
                    $obj_pdf->Image($img_tag, '', '154', 82, 24, '', '', 'RTL', false, 1000, '', false, false, 1, false, false, false);
            }
            elseif($detail['firm_name'] == "ACUMEN BIZCORP PTE. LTD.")
            {
                //$img_tag = '<img src="img/Signature - AA.png" height="75px;"' . ' />';
                $img_tag = 'img/Signature - ABC.png';
                // if($q[0]["document_name"] == "Audit Engagement")
                // {
                //  $obj_pdf->setY($obj_pdf->getY() - 40);
                // }
                // else
                // {
                    // $obj_pdf->setY($obj_pdf->getY() - 45);
                // }
                    $obj_pdf->Image($img_tag, '', '150', 58, 30, '', '', 'RTL', false, 1000, '', false, false, 1, false, false, false);
            }

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

            chmod($_SERVER['DOCUMENT_ROOT'].'audit/document/clearance/'.$file_name, 0644);
            

            $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';


            // $link = 'https://'. $_SERVER['SERVER_NAME'] .'/generate_do/pdf/document/Delivery Order ('.DATE("Y-m-d",now()).').pdf';
            $link = 'http://'. $_SERVER['SERVER_NAME'] .'/audit/document/resignation/'.$file_name;

            $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> $_SERVER['DOCUMENT_ROOT'].'audit/document/resignation/'.$file_name);

            // kill session for doc_cus_id array
            $this->session->unset_userdata('doc_cus_id');
            $this->session->set_userdata(array('doc_cus_id' =>array())); // create new session back for session 'doc_cus_id'

            echo json_encode($data);
            return $data;
        
    }

    public function move_initial_el_to_paf()
    {
        $this->session->set_userdata("tab_active", "engagement");
        $user_id = $this->session->userdata('user_id');
        $form_data = $this->input->post();
        $i = 0;

        $selected_move_el = $form_data['selected_move_el'];
        
        //change bank authorization move status to 1
        $this->engagement_model->move_initial_el($selected_move_el[0]['engagement_letter_id']);

        $el_fix_child_id = $this->engagement_model->get_el_fix_child_id($selected_move_el[0]['company_code']);


        foreach ($selected_move_el as $file_key => $each) {
                # code...

            $upload_data[$i]['paf_child_id'] = $el_fix_child_id;
            $upload_data[$i]['file_name'] = $each['file_name'];
            $upload_data[$i]['file_path'] = $each['file_path'];

            $i++;
        }

        //log upload document to paf
        $data   = array(
                    'paf_id'     => $el_fix_child_id,
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $user_id,
                    'paf_log'    => "upload document(s)",
                    'company_code' => $selected_move_el[0]['company_code']
                  );

        $this->db->insert('audit_paf_log', $data);

        if(!empty($upload_data))
        {
            $this->db->insert_batch('audit_paf_document',$upload_data);
            // print_r($uploadData);
            
        }


    }

    public function move_subsequent_el_to_paf()
    {
        $this->session->set_userdata("tab_active", "subsequent");
        $user_id = $this->session->userdata('user_id');
        $form_data = $this->input->post();
        $i = 0;

        $selected_move_sub_el = $form_data['selected_move_sub_el'];
        
        //change bank authorization move status to 1
        $this->engagement_model->move_subsequent_el($selected_move_sub_el[0]['subsequent_el_id']);

        $el_fix_child_id = $this->engagement_model->get_el_fix_child_id($selected_move_sub_el[0]['company_code']);

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
        
        //archive existing document in engagement
        $this->client_model->archive_paf_child($el_fix_child_id);


        foreach ($selected_move_sub_el as $file_key => $each) {
                # code...

            $upload_data[$i]['paf_child_id'] = $el_fix_child_id;
            $upload_data[$i]['file_name'] = $each['file_name'];
            $upload_data[$i]['file_path'] = $each['file_path'];

            $i++;
        }

        //log upload document to paf
        $data   = array(
                    'paf_id'     => $el_fix_child_id,
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $user_id,
                    'paf_log'    => "upload document(s)",
                    'company_code' => $selected_move_sub_el[0]['company_code']
                  );

        $this->db->insert('audit_paf_log', $data);

        if(!empty($upload_data))
        {
            $this->db->insert_batch('audit_paf_document',$upload_data);
            // print_r($uploadData);
            
        }


    }

    public function replace_initial_el_paf()
    {
        
        $user_id = $this->session->userdata('user_id');
        $form_data = $this->input->post();
        $i = 0;

        $selected_move_el = $form_data['selected_move_el'];
        
        //change bank authorization move status to 1
        $this->engagement_model->move_initial_el($selected_move_el[0]['engagement_letter_id']);

        $el_fix_child_id = $this->engagement_model->get_el_fix_child_id($selected_move_el[0]['company_code']);

        //delete existing document in engagement
        $this->db->select('id');
        $this->db->from('audit_paf_document');
        $this->db->where(array('paf_child_id' => $el_fix_child_id, 'deleted' => 0, 'archived' => 0));

        $doc_id = $this->db->get();
        foreach ($doc_id->result_array() as $key => $d_id) {
           $this->client_model->delete_paf_doc($d_id['id']);
        }


        foreach ($selected_move_el as $file_key => $each) {
                # code...

            $upload_data[$i]['paf_child_id'] = $el_fix_child_id;
            $upload_data[$i]['file_name'] = $each['file_name'];
            $upload_data[$i]['file_path'] = $each['file_path'];

            $i++;
        }

        //log upload document to paf
        $data   = array(
                    'paf_id'     => $el_fix_child_id,
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $user_id,
                    'paf_log'    => "upload document(s)",
                    'company_code' => $selected_move_el[0]['company_code']
                  );

        $this->db->insert('audit_paf_log', $data);

        if(!empty($upload_data))
        {
            $this->db->insert_batch('audit_paf_document',$upload_data);
            // print_r($uploadData);
            
        }

    }

    public function replace_subsequent_el_paf()
    {
        $this->session->set_userdata("tab_active", "subsequent");
        $user_id = $this->session->userdata('user_id');
        $form_data = $this->input->post();
        $i = 0;

        $selected_move_sub_el = $form_data['selected_move_sub_el'];
        
        $el_fix_child_id = $this->engagement_model->get_el_fix_child_id($selected_move_sub_el[0]['company_code']);

        
        //delete existing document in engagement
        $this->db->select('id');
        $this->db->from('audit_paf_document');
        $this->db->where(array('paf_child_id' => $el_fix_child_id, 'deleted' => 0, 'archived' => 0));

        $doc_id = $this->db->get();
        foreach ($doc_id->result_array() as $key => $d_id) {
           $this->client_model->delete_paf_doc($d_id['id']);
        }
        // $this->client_model->archive_paf_child($el_fix_child_id);


        foreach ($selected_move_sub_el as $file_key => $each) {
                # code...

            $upload_data[$i]['paf_child_id'] = $el_fix_child_id;
            $upload_data[$i]['file_name'] = $each['file_name'];
            $upload_data[$i]['file_path'] = $each['file_path'];

            $i++;
        }

        //log upload document to paf
        $data   = array(
                    'paf_id'     => $el_fix_child_id,
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $user_id,
                    'paf_log'    => "upload document(s)",
                    'company_code' => $selected_move_sub_el[0]['company_code']
                  );

        $this->db->insert('audit_paf_log', $data);

        if(!empty($upload_data))
        {
            $this->db->insert_batch('audit_paf_document',$upload_data);
            // print_r($uploadData);
            
        }


    }



    public function get_latest_first_letter()
    {
        if(!empty($this->input->post('letter_id')))
        {
            $letter_id = $this->input->post('letter_id');
        }

        $q = $this->db->query("SELECT * FROM `audit_clearance_doc` 
                                WHERE clearance_id = ".$letter_id." AND sys_generated = 1
                                HAVING max(created_at)");
        $file_info = $q->result_array();

        $link = 'http://'. $_SERVER['SERVER_NAME'] .'/audit/document/clearance/'.$file_info[0]["file_name"];

        $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> $_SERVER['DOCUMENT_ROOT'].'audit/document/clearance/'.$file_info[0]["file_name"]);

        echo json_encode($data);
        return $data;
    }

    public function get_initial_el_attachment()
    {
        if(!empty($this->input->post('el_id')))
        {
            $letter_id = $this->input->post('el_id');
        }

        $this->db->select('doc.*, company_code');
        $this->db->from('audit_engagement_doc doc');
        $this->db->join('audit_engagement_letter el','doc.engagement_letter_id = el.id', 'left');
        $this->db->where(array('engagement_letter_id' => $letter_id));
        $this->db->order_by("created_at asc");

  
        $aq = $this->db->get(); 

        $data = array('status'=>'success', 'attachment'=>$aq->result_array());

        echo json_encode($data);
        return $data;
    }

    public function get_subsequent_el_attachment()
    {
        if(!empty($this->input->post('sub_el_id')))
        {
            $letter_id = $this->input->post('sub_el_id');
        }

        $this->db->select('doc.*, company_code');
        $this->db->from('audit_subsequent_el_doc doc');
        $this->db->join('audit_subsequent_el el','doc.subsequent_el_id = el.id', 'left');
        $this->db->where(array('subsequent_el_id' => $letter_id));
        $this->db->order_by("created_at asc");

  
        $aq = $this->db->get(); 

        $data = array('status'=>'success', 'attachment'=>$aq->result_array());

        echo json_encode($data);
        return $data;
    }
   

   

    public static function getPotentialClient() 
    {
        $ci =& get_instance();
        
        $result = $this->db->query('select client.id, client.company_code, client.company_name from client left join user_firm on user_id = "'.$ci->session->userdata("user_id").'" 
            right join client_billing_info on client_billing_info.company_code = client.company_code 
            right join our_service_info on our_service_info.id = client_billing_info.service 
            where client.deleted = 0 and user_firm.firm_id = client.firm_id and (our_service_info.service_type != 1 and our_service_info.service_type != 10) ');

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

     public static function getEngagementClient() 
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

        $query = 'SELECT DISTINCT(el.company_code), client.company_name FROM audit_engagement_letter el
                    left join client on el.company_code = client.company_code 
                    where el.deleted = 0 and el.moved = 1';


        

       

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

    public static function getMovedEngagementClient() 
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

        $query = 'SELECT DISTINCT(el.company_code), client.company_name FROM audit_engagement_letter el
                    left join client on el.company_code = client.company_code 
                    where el.deleted = 0 and el.moved = 1 and client.deleted = 0';


        

       

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
            $selected_firm_name = $ci->session->userdata('our_firm_id');
            $ci->session->unset_userdata('our_firm_id');

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



    public function uploadElDoc()
    {
        $this->session->set_userdata("tab_active", "engagement");
        if(isset($_FILES['el_docs']))
        {
            $filesCount = count($_FILES['el_docs']['name']);

            for($i = 0; $i < $filesCount; $i++)
            {   //echo json_encode($_FILES['uploadimages']);
                $_FILES['el_doc']['name']     = $_FILES['el_docs']['name'][$i];
                $_FILES['el_doc']['type']     = $_FILES['el_docs']['type'][$i];
                $_FILES['el_doc']['tmp_name'] = $_FILES['el_docs']['tmp_name'][$i];
                $_FILES['el_doc']['error']    = $_FILES['el_docs']['error'][$i];
                $_FILES['el_doc']['size']     = $_FILES['el_docs']['size'][$i];

                $uploadPath = './document/engagement';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('el_doc'))
                {
                    $fileData = $this->upload->data();
                    $uploadData[$i]['engagement_letter_id'] = $_POST['letter_id'];
                    $uploadData[$i]['file_path'] = "engagement";
                    $uploadData[$i]['file_name'] = $fileData['file_name'];
                    // $uploadData[$i]['sys_generated'] = 0;

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
                $this->db->insert_batch('audit_engagement_doc',$uploadData);
                // print_r($uploadData);

                //log upload 
                $log_data   = array(
                    'engagement_letter_id'     => $_POST['letter_id'],
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $this->session->userdata('user_id'),
                    'log_message'    => "upload signed engagement letter",
                );

                $this->db->insert('audit_initial_el_log', $log_data);
                
            }


        }
        
        if (count(array($this->session->userdata('el_files_id'))) != 0)
        {
            $el_files_id = $this->session->userdata('el_files_id');
            $this->session->unset_userdata('el_files_id');
            for($i = 0; $i < count(array($el_files_id)); $i++)
            {
                $files = $this->db->query("select * from audit_engagement_doc where id='".$el_files_id[$i]."'");
                $file_info = $files->result_array();

                $this->db->where('id', $el_files_id[$i]);

                if(isset($file_info[0]))
                {

                    if(file_exists("./document/".$file_info[0]["file_path"]."/".$file_info[0]["file_name"]))
                    {
                        unlink("./document/".$file_info[0]["file_path"]."/".$file_info[0]["file_name"]);

                    }
                }

                $this->db->delete('audit_engagement_doc', array('id' => $el_files_id[$i]));

                //log delete doc
                $log_data   = array(
                    'engagement_letter_id'     => $_POST['letter_id'],
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $this->session->userdata('user_id'),
                    'log_message'    => "delete uploaded document",
                );

                $this->db->insert('audit_initial_el_log', $log_data);
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

    public function deleteElFile($id)
    {
        if($this->session->userdata('el_files_id') != null)
        {
             $el_files_id = $this->session->userdata('el_files_id');
        }
        else
        {
            $el_files_id = array();
        }
       
        array_push($el_files_id, $id);
        $this->session->set_userdata(array(
            'el_files_id'  =>  $el_files_id,
        ));

        echo json_encode($el_files_id);
    }

    public function clear_delete_engagement_session()
    {

        $this->session->unset_userdata('el_files_id');
    }

    public function myUrlEncode($string) {
        $replacements = array('');
        $entities = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
        return str_replace($entities, $replacements, $string);
    }

    public function uploadSubElDoc()
    {
        $this->session->set_userdata("tab_active", "subsequent");
        if(isset($_FILES['sub_el_docs']))
        {
            $filesCount = count($_FILES['sub_el_docs']['name']);

            for($i = 0; $i < $filesCount; $i++)
            {   //echo json_encode($_FILES['uploadimages']);
                $_FILES['sub_el_doc']['name']     = $_FILES['sub_el_docs']['name'][$i];
                $_FILES['sub_el_doc']['type']     = $_FILES['sub_el_docs']['type'][$i];
                $_FILES['sub_el_doc']['tmp_name'] = $_FILES['sub_el_docs']['tmp_name'][$i];
                $_FILES['sub_el_doc']['error']    = $_FILES['sub_el_docs']['error'][$i];
                $_FILES['sub_el_doc']['size']     = $_FILES['sub_el_docs']['size'][$i];

                $uploadPath = './document/engagement';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('sub_el_doc'))
                {
                    $fileData = $this->upload->data();
                    $uploadData[$i]['subsequent_el_id'] = $_POST['letter_id'];
                    $uploadData[$i]['file_path'] = "engagement";
                    $uploadData[$i]['file_name'] = $fileData['file_name'];
                    // $uploadData[$i]['sys_generated'] = 0;

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
                $this->db->insert_batch('audit_subsequent_el_doc',$uploadData);
                // print_r($uploadData);

                //log upload 
                $log_data   = array(
                    'subsequent_el_id'     => $_POST['letter_id'],
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $this->session->userdata('user_id'),
                    'log_message'    => "upload signed subsequent engagement letter",
                );

                $this->db->insert('audit_subsequent_el_log', $log_data);
                
            }


        }

        if (!is_null($this->session->userdata('sub_el_files_id')) && count($this->session->userdata('sub_el_files_id')) != 0)
        {
            $sub_el_files_id = $this->session->userdata('sub_el_files_id');
            $this->session->unset_userdata('sub_el_files_id');
            for($i = 0; $i < count($sub_el_files_id); $i++)
            {
                $files = $this->db->query("select * from audit_subsequent_el_doc where id='".$sub_el_files_id[$i]."'");
                $file_info = $files->result_array();

                $this->db->where('id', $sub_el_files_id[$i]);

                if(file_exists("./document/".$file_info[0]["file_path"]."/".$file_info[0]["file_name"]))
                {
                    unlink("./document/".$file_info[0]["file_path"]."/".$file_info[0]["file_name"]);

                }
                
                $this->db->delete('audit_subsequent_el_doc', array('id' => $sub_el_files_id[$i]));

                //log delete doc
                $log_data   = array(
                    'subsequent_el_id'     => $_POST['letter_id'],
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $this->session->userdata('user_id'),
                    'log_message'    => "delete uploaded document",
                );

                $this->db->insert('audit_subsequent_el_log', $log_data);
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

    public function deleteSubElFile($id)
    {
        if($this->session->userdata('sub_el_files_id') != null)
        {
             $sub_el_files_id = $this->session->userdata('sub_el_files_id');
        }
        else
        {
            $sub_el_files_id = array();
        }
       
        array_push($sub_el_files_id, $id);
        $this->session->set_userdata(array(
            'sub_el_files_id'  =>  $sub_el_files_id,
        ));

        echo json_encode($sub_el_files_id);
    }

    public function clear_delete_subsequent_engagement_session()
    {

        $this->session->unset_userdata('sub_el_files_id');
    }

    public function delete_initial_el(){
        $this->session->set_userdata("tab_active", "engagement");

        $form_data = $this->input->post();

        $result = $this->engagement_model->delete_initial_el($form_data['letter_id']);

        echo $result;
    }

    public function delete_subsequent_el(){
        $this->session->set_userdata("tab_active", "subsequent");

        $form_data = $this->input->post();

        $result = $this->engagement_model->delete_subsequent_el($form_data['letter_id']);

        echo $result;
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
            $address = $street_name . $comma . $br . $unit_building_name . $br . 'Singapore ' . $postal_code;
        }
        elseif(!empty($building_name))
        {
            $address = $street_name . $comma . $br . $building_name . $comma . $br . 'Singapore ' . $postal_code;
        }
        else
        {
            $address = $street_name . $comma . $br . 'Singapore ' . $postal_code;
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
                $this->list_of_auditor_model->set_company_resign($form_data->client_name);
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

    // latest version include foreign address. 
    public function write_address_local_foreign($address, $type, $style)
    {
        $unit = '';
        $unit_building_name = '';

        if($type == "normal")
        {
            $br1 = '';
            $br2 = '';
        }
        elseif($type == "letter")
        {
            $br1 = ', <br/>';
            $br2 = ', <br/>';
        }
        elseif($type == "comma")
        {
            $br1 = ', ';
            $br2 = ', ';
        }

        if($address['type'] == "Local")
        {
            // Add unit
            if(!empty($address['unit_no1']) && !empty($address['unit_no2']))
            {
                $unit = '#' . $address['unit_no1'] . '-' . $address['unit_no2'];
            }
            else
            {
                if($type != "letter")
                {
                    $br2 = '';
                }
            }

            // Add building
            if(!empty($address['building_name1']) && !empty($unit))
            {
                $unit_building_name = $unit . ' ' . $address['building_name1'] . $br2;
            }
            elseif(!empty($unit))
            {
                $unit_building_name = $unit . $br2;
            }
            elseif(!empty($address['building_name1']))
            {
                $unit_building_name = $address['building_name1'] . $br2;
            }

            if($style == "big_cap")
            {
                $sg_word = 'SINGAPORE ';
            }
            else
            {
                $sg_word = 'Singapore ';
            }

            return $address['street_name1'] . $br1 . $unit_building_name . $sg_word . $address['postal_code1'];
        }
        else if($address['type'] == "Foreign")
        {
            $foreign_address1 = !empty($address["foreign_address1"])? $address["foreign_address1"]: '';

            if(!empty($address["foreign_address1"]))
            {
                if(substr($address["foreign_address1"], -1) == ",")
                {
                    $foreign_address1 = rtrim($address["foreign_address1"],',');    // remove , if there is any at last character
                }
                else
                {
                    $foreign_address1 = $address["foreign_address1"];
                }
            }

            if(!empty($address["foreign_address2"]))
            {
                if(substr($address["foreign_address2"], -1) == ",")
                {
                    $foreign_address2 = $br1 . rtrim($address["foreign_address2"],',');     // remove , if there is any at last character
                }
                else
                {
                    $foreign_address2 = $br1 . $address["foreign_address2"];
                }
            }
            else
            {
                $foreign_address2 = '';
            }

            $foreign_address3 = !empty($address["foreign_address3"])? $br2 . $address["foreign_address3"]: '';

            return $foreign_address1.$foreign_address2.$foreign_address3;
        }
    }


    // Engagement controller
    public function get_engagement_letter_detail()
    {
        $company_code = $_POST["company_code"];
        $el_id = isset($_POST['el_id'])?$_POST['el_id']: "";

        if($el_id != null || $el_id != "")
        {
            $data['engagement_letter_info'] = $this->engagement_model->getEngagementLetterInfo($el_id);
            // echo "hi";
        }

        $data['client_info'] = $this->engagement_model->check_client_info($company_code);

        $data['get_service_proposal_service_info'] = $this->engagement_model->get_service_proposal_service_info($company_code);

        if(ISSET($data['client_info'][0]))
        {
            $director_result_1 = $this->db->query("select officer.* from client_officers left join officer on officer.id = client_officers.officer_id and officer.field_type = client_officers.field_type where client_officers.id='".$data['client_info'][0]->director_signature_1."'");

            $director_result_1 = $director_result_1->result_array();

            if($director_result_1)
            {
                $data['director_result_1'] = $this->encryption->decrypt($director_result_1[0]["name"]);
            }

            

        }
        else
        {
            $data['director_result_1'] = "";
        }

       
        

        $get_all_firm_info = $this->engagement_model->getAllFirmInfo();
        for($j = 0; $j < count($get_all_firm_info); $j++)
        {
            if($get_all_firm_info[$j]->branch_name != null)
            {
                $res_firm[$get_all_firm_info[$j]->id] = $get_all_firm_info[$j]->name.' ('.$get_all_firm_info[$j]->branch_name.')';
            }
            else
            {
                $res_firm[$get_all_firm_info[$j]->id] = $get_all_firm_info[$j]->name;
            }
        }
        $data['get_all_firm_info'] = $res_firm;

        $result_currency = $this->db->query("select * from currency order by currency");
        $result_currency = $result_currency->result_array();
        for($j = 0; $j < count($result_currency); $j++)
        {
            $res[$result_currency[$j]['id']] = $result_currency[$j]['currency'];
        }
        $data["currency"] = $res;

        $result_unit_pricing = $this->db->query("select * from unit_pricing");
        $result_unit_pricing = $result_unit_pricing->result_array();
        for($j = 0; $j < count($result_unit_pricing); $j++)
        {
            $res_unit_pricing[$result_unit_pricing[$j]['id']] = $result_unit_pricing[$j]['unit_pricing_name'];
        }
        $data["unit_pricing_name"] = $res_unit_pricing;


        echo json_encode($data);
    }

    public function save_engagement_letter()
    {
        $this->session->set_userdata("tab_active", "engagement");
        $el_data['id'] = $_POST["el_id"];
        $el_data['engagement_letter_date'] = $_POST["engagement_letter_date"];
        $el_data['uen'] = strtoupper($_POST["uen"]);
        $el_data['fye_date'] = $_POST["fye_date"];
        $el_data['start_fye_date'] = $_POST["start_fye_date"];
        $el_data['director_signing'] = strtoupper($_POST["director_signing"]);
        $el_data['company_code'] = $_POST["company_code"];
        $el_data['company_name'] = $_POST["company_name"];

        // print_r($_POST);

        $engagement_letter_query = $this->db->get_where("audit_engagement_letter", array("id" => $el_data['id']));

        if (!$engagement_letter_query->num_rows())
        {
            $this->db->insert("audit_engagement_letter",$el_data);
            $el_id = $this->db->insert_id();

            //log insert 
            $log_data   = array(
                'engagement_letter_id'     => $el_id,
                'date_time'  => date("Y-m-d H:i:s"),
                'user_id'    => $this->session->userdata('user_id'),
                'log_message'    => "insert initial engagement letter",
              );

            $this->db->insert('audit_initial_el_log', $log_data);
        }
        else
        {
            $this->db->update("audit_engagement_letter",$el_data,array("id" => $el_data['id']));
            $el_id = $el_data['id'];

            //log update 
            $log_data   = array(
                'engagement_letter_id'     => $el_id,
                'date_time'  => date("Y-m-d H:i:s"),
                'user_id'    => $this->session->userdata('user_id'),
                'log_message'    => "update initial engagement letter",
              );

            $this->db->insert('audit_initial_el_log', $log_data);
        }

        $this->db->delete("audit_engagement_letter_info",array('engagement_letter_id'=>$el_data['id']));

        for($h = 0; $h < count($_POST['hidden_selected_el_id']); $h++)
        {
            if($_POST['hidden_selected_el_id'][$h] != "")
            {
                $el_info_data['engagement_letter_id'] = $el_id;
                $el_info_data['engagement_letter_type'] = $_POST['hidden_selected_el_id'][$h];
                $el_info_data['currency_id'] = $_POST['currency'][$h];
                $el_info_data['fee'] = str_replace(',', '', $_POST['fee'][$h]);
                $el_info_data['unit_pricing'] = $_POST['unit_pricing'][$h];
                $el_info_data['servicing_firm'] = $_POST['servicing_firm'][$h];

                $this->db->insert('audit_engagement_letter_info', $el_info_data);
            }
        }

        echo json_encode(array('status'=>'1', 'message' => "Information updated successfully", "title" => "Success", "el_id" => $el_id));

    }

    public function get_header_template($document_type, $firm_id = NULL)
    {
        if(!is_null($firm_id))
        {
            $firm = $this->db->query("SELECT firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
                                        JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
                                        JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
                                        JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
                                        where firm.id = ". $firm_id);
            $firm = $firm->result_array();
            $firm_logo = !empty($firm[0]["file_name"])?'<img src="uploads/logo/'. $firm[0]["file_name"] .'" height="60" />' : ''; 
            // $firm_logo = !empty($firm[0]["file_name"])?'<img src="uploads/logo/AA_LLP1.jpg" height="60" />' : '';

            if($firm[0]["branch_name"] != null)
            {
                $branch_name = 'Branch: '.$firm[0]["branch_name"].'<br />';
            }
            else
            {
                $branch_name = '';
            }
        }   

        if($document_type == "DRIW")
        {
            return '<p style="text-align: center;"><strong style="font-size: 12pt;"><span class="myclass mceNonEditable">{{Company current name}}</span><br /></strong><span style="font-size: 9pt;">(the &ldquo;Company&rdquo;)</span><br /><span style="font-size: 9pt;">(Company Registration No.: </span><span style="font-size: 9pt;"><span class="myclass mceNonEditable">{{UEN}}</span></span><span style="font-size: 9pt;">)</span><br /><span style="font-size: 9pt;">(Incorporated in the Republic of Singapore)</span></p>
                <p style="text-align: center;"><span style="font-size: 10pt;">RESOLUTION IN WRITING PURSUANT TO REGULATION OF THE COMPANY&rsquo;S CONSTITUTION</span></p>
                <hr />';
        }
        elseif($document_type == "Attendance")
        {
            return '<p style="text-align: center;"><strong style="font-size: 12pt;"><span class="myclass mceNonEditable">{{Company current name}}</span><br /></strong><span style="font-size: 9pt;">(the &ldquo;Company&rdquo;)</span><br /><span style="font-size: 9pt;">(Company Registration No.: </span><span style="font-size: 9pt;"><span class="myclass mceNonEditable">{{UEN}}</span></span><span style="font-size: 9pt;">)</span><br /><span style="font-size: 9pt;">(Incorporated in the Republic of Singapore)</span></p>
                <p style="text-align: center;"><span style="font-size: 10pt;"><strong>ATTENDANCE LIST</strong></span></p>';
        }
        elseif($document_type == "Company Info Header")
        {

            if($firm[0]["building_name"] == "Singapore Business Federation Center (SBF Center)")
            {
                $firm[0]["building_name"] = "SBF Center";
            }

            if($firm[0]["name"] == "SYA PAC")
            {
                $logo_width = 15;
                $details_width  = 80;
                $align = "left";
            }
            else
            {
                $logo_width = 35;
                $details_width  = 60;
                $align = "center";
            }

            $firm[0]["telephone"] = str_replace("+", "", $firm[0]["telephone"]);
            $firm[0]["fax"]       = str_replace("+", "", $firm[0]["fax"]);

            return '<table style="width: 100%; border-collapse: collapse; height: 80px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
                    <tbody>
                        <tr style="height: 80px;">
                            <td style="width: '.$logo_width.'%; text-align: left; height: 80px;" align="left">'.$firm_logo.'</td>
                            <td style="width: 5%;"></td>
                            <td style="width: '.$details_width.'%; height: 80px;"><span style="font-size: 13pt;"><strong>'.$firm[0]["name"].'</strong></span><br /><span style="font-size: 7pt; text-align: left;"><strong>PUBLIC ACCOUNTANTS AND CHARTERED ACCOUNTANTS OF SINGAPORE</strong><br />UEN: '. $firm[0]["registration_no"] .'<br />'.$branch_name.'Address: '. $firm[0]["street_name"] .', #'. $firm[0]["unit_no1"] .'-'.$firm[0]["unit_no2"].' '. $firm[0]["building_name"] .', Singapore '. $firm[0]["postal_code"] .'<br />Tel: '. $firm[0]["telephone"] .' &nbsp; Fax: '. $firm[0]["fax"] .'&nbsp;</span></td>
                        </tr>
                    </tbody>
                </table>';
        }
        elseif($document_type == "headerOnly")
        {
            return '<p style="text-align: center;"><strong style="font-size: 12pt;"><span class="myclass mceNonEditable">{{Company current name}}</span><br /></strong><span style="font-size: 9pt;">(the &ldquo;Company&rdquo;)</span><br /><span style="font-size: 9pt;">(Company Registration No.: </span><span style="font-size: 9pt;"><span class="myclass mceNonEditable">{{UEN}}</span></span><span style="font-size: 9pt;">)</span><br /><span style="font-size: 9pt;">(Incorporated in the Republic of Singapore)</span></p>';
        }
    }

    public function generate_engagement_letter()
    {

        // $po_list = $this->products_model->getPdfData(); // retrieve all po from database
        if(!empty($this->input->post('el_id')))
        {
            $el_id = $this->input->post('el_id');
        }
        else
        {
            $el_id = "";
        }

        if(!empty($this->input->post('sub_el_id')))
        {
            $sub_el_id = $this->input->post('sub_el_id');
        }
        else
        {
            $sub_el_id = "";
        }


        
        // print_r($confirm_id);
        if($el_id != "")
        {
            $client_engagement_info = $this->db->select('transaction_master.*,  transaction_service_proposal_info.*,  audit_engagement_letter.uen, audit_engagement_letter.start_fye_date, audit_engagement_letter.fye_date, audit_engagement_letter.director_signing, audit_engagement_letter.engagement_letter_date');
                                $this->db->from('transaction_master');
                                $this->db->join('transaction_service_proposal_info', 'transaction_service_proposal_info.transaction_id = transaction_master.id ', 'left');
                                $this->db->join('audit_engagement_letter', 'audit_engagement_letter.company_code = transaction_master.company_code', 'left');
                                $this->db->where('transaction_master.transaction_task_id', "29"); //service proposal
                                $this->db->where('audit_engagement_letter.id', $el_id);
                                $this->db->where('(transaction_master.service_status = 1 OR transaction_master.service_status = 3)');
                                $this->db->where('audit_engagement_letter.company_code NOT IN (select company_code from client where deleted=0)');
                                $this->db->order_by("transaction_master.id", "asc");
            
        }

        if($sub_el_id != "")
        {
            $client_engagement_info = $this->db->select('transaction_master.*,  transaction_service_proposal_info.*, audit_subsequent_el.fye_date,  audit_subsequent_el.start_fye_date, audit_subsequent_el.director_signing, audit_subsequent_el.new_date');
                                $this->db->from('transaction_master');
                                $this->db->join('transaction_service_proposal_info', 'transaction_service_proposal_info.transaction_id = transaction_master.id ', 'left');
                                $this->db->join('audit_subsequent_el', 'audit_subsequent_el.company_code = transaction_master.company_code', 'left');
                                $this->db->where('transaction_master.transaction_task_id', "29");
                                $this->db->where('audit_subsequent_el.id', $sub_el_id);
                                $this->db->where('(transaction_master.service_status = 1 OR transaction_master.service_status = 3)');
                                $this->db->where('audit_subsequent_el.company_code NOT IN (select company_code from client where deleted=0)');
                                $this->db->order_by("transaction_master.id", "asc");
        }

        

        // $document_info_query = $document_info_query->result_array();
        $client_engagement_info = $this->db->get();

        // print_r($this->db->last_query());
        if($client_engagement_info->num_rows() > 0 && $sub_el_id == "")
        {
            $client_engagement_info = $client_engagement_info->result_array();
            if($this->encryption->decrypt($client_engagement_info[0]['registration_no']) != null)
            {
                $client_engagement_info[0]['registration_no'] = $this->encryption->decrypt($client_engagement_info[0]['registration_no']);
            }
            $client_engagement_info[0]['client_name'] = $this->encryption->decrypt($client_engagement_info[0]['client_name']);

            // print_r( $client_engagement_info[0]['client_name']);
        }
        else
        {
            if ($el_id != "")
            {
                $client_engagement_info = $this->db->query("SELECT client.*, client.company_name AS `client_name`, audit_engagement_letter.uen, audit_engagement_letter.fye_date, audit_engagement_letter.start_fye_date, audit_engagement_letter.director_signing, audit_engagement_letter.engagement_letter_date FROM audit_engagement_letter 
                                                        LEFT JOIN client ON client.company_code = audit_engagement_letter.company_code AND client.deleted != 1
                                                        WHERE audit_engagement_letter.id = " . $el_id);
            }

            if ($sub_el_id != "") 
            {
                $client_engagement_info = $this->db->query("SELECT client.*, client.company_name AS `client_name`, audit_subsequent_el.fye_date, audit_subsequent_el.start_fye_date, audit_subsequent_el.director_signing, audit_subsequent_el.new_date FROM audit_subsequent_el 
                                                        LEFT JOIN client ON client.company_code = audit_subsequent_el.company_code AND client.deleted != 1
                                                        WHERE audit_subsequent_el.id = " . $sub_el_id);
            }
            

            $client_engagement_info = $client_engagement_info->result_array();
            $client_engagement_info[0]['registration_no'] = $this->encryption->decrypt($client_engagement_info[0]['registration_no']);
            $client_engagement_info[0]['client_name'] = $this->encryption->decrypt($client_engagement_info[0]['client_name']);


        }

        // print_r($client_engagement_info);

        // foreach ($document_info_query as $key => $value) {
        //     $document_info_query[$key]['company_name'] = $this->encryption->decrypt($document_info_query[$key]['company_name']);
        // }
        if ($el_id != "")
        {
            $service_info_list = $this->db->query("SELECT audit_engagement_letter_info.*, currency.currency, unit_pricing.unit_pricing_name, firm.name, firm.branch_name FROM audit_engagement_letter_info 
                                                                LEFT JOIN currency ON currency.id = audit_engagement_letter_info.currency_id 
                                                                LEFT JOIN firm ON firm.id = audit_engagement_letter_info.servicing_firm
                                                                LEFT JOIN unit_pricing ON audit_engagement_letter_info.unit_pricing = unit_pricing.id
                                                                WHERE audit_engagement_letter_info.engagement_letter_id =" .$el_id);
            $letter_date = $client_engagement_info[0]['engagement_letter_date'];
        }

        if ($sub_el_id != "") 
        {
            $service_info_list = $this->db->query("SELECT audit_subsequent_el_info.*, currency.currency, unit_pricing.unit_pricing_name, firm.name, firm.branch_name FROM audit_subsequent_el_info 
                                                                LEFT JOIN currency ON currency.id = audit_subsequent_el_info.currency_id 
                                                                LEFT JOIN firm ON firm.id = audit_subsequent_el_info.servicing_firm
                                                                LEFT JOIN unit_pricing ON audit_subsequent_el_info.unit_pricing = unit_pricing.id
                                                                WHERE audit_subsequent_el_info.subsequent_el_id =" .$sub_el_id);
            $letter_date = $client_engagement_info[0]['new_date'];
        }

        

        // print_r($service_info_list->result_array());

        $service_info_list = $service_info_list->result_array();

        $el_template_query = $this->engagement_model->get_el_doc_template();



        // $el_template_query = $do_template_query->result_array();

        if(count($service_info_list) > 0)
        {

            foreach ($service_info_list as $each_info) {
                
                //audit engagement
                if($each_info['engagement_letter_type'] == 3)
                {
                    $doc_template_code = 6;
                    $document_name = "Audit engagement";
                }
                //ML Quarterly
                elseif($each_info['engagement_letter_type'] == 4)
                {
                    $doc_template_code = 7;
                    $document_name = "ML Quarterly Statements";
                }
                //PMFT
                elseif($each_info['engagement_letter_type'] == 5)
                {
                    $doc_template_code = 8;
                    $document_name = "PMFT Audit";
                }

                if($each_info["servicing_firm"] != 0)
                {
                    
                    $header_content = $this->get_header_template("Company Info Header", $each_info["servicing_firm"]);

                        // $header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $each_info["servicing_firm"], $header_content, null, $document_name);
                    // $header_content = "";
                    
                }
                else
                {
                    $header_content = '';
                }


                //this one need check if user got pre-printed letterhead
                $obj_pdf = new ENGAGEMENT_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                $obj_pdf->SetCreator(PDF_CREATOR);
                $title = "Engagement";
                $obj_pdf->SetTitle($title);
                $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));

                $obj_pdf->setPrintHeader(true);
                $obj_pdf->setPrintFooter(true);

                $obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER + 8);

                // $obj_pdf->setFontStretching(95);
                $obj_pdf->setCellHeightRatio(1.4);

                // set margins
                // if($each_info["engagement_letter_type"] == 3)
                // {
                //     $obj_pdf->SetMargins(PDF_MARGIN_LEFT+20, PDF_MARGIN_TOP + 15, PDF_MARGIN_RIGHT);
                // }
                // else
                // {
                $obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT+6);
                // }

                $obj_pdf->SetAutoPageBreak(TRUE, 25);
                $obj_pdf->AddPage();

                if($each_info['branch_name'] != null)
                {
                    $service_firm_name = $each_info['name'].' ('.$each_info['branch_name'].')';
                }
                else
                {
                    $service_firm_name = $each_info['name'];
                }

                $el_template = $el_template_query[$doc_template_code];
                // print_r($el_template);

                $pattern = "/{{[^}}]*}}/";
                $subject = $el_template;
                preg_match_all($pattern, $subject, $matches);

                $new_contents_info = $el_template;
                        
                $detail = $each_info;

                if($each_info['branch_name'] != null)
                {
                    $service_firm_name = $each_info['name'].' ('.$each_info['branch_name'].')';
                }
                else
                {
                    $service_firm_name = $each_info['name'];
                }

                for($r = 0; $r < count($matches[0]); $r++)
                {
                    $string1 = (str_replace('{{', '',$matches[0][$r]));
                    $string2 = (str_replace('}}', '',$string1));
                    
                    $replace_string = $matches[0][$r];
                    $temp_content = "______________";

                    if($string2 == "FYE Date")
                    {   
                        if(!empty($client_engagement_info[0]['fye_date']))
                        {
                            $temp_content = date('d F Y', strtotime(str_replace('/', '-', $client_engagement_info[0]['fye_date'])));
                        }

                        $new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
                    }

                    if($string2 == "fye period start date")
                    {   
                        if(!empty($client_engagement_info[0]['start_fye_date']))
                        {
                            $temp_content = date('d F Y', strtotime(str_replace('/', '-', $client_engagement_info[0]['start_fye_date'])));
                        }

                        $new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
                    }

                    if($string2 == "client company name")
                    {
                        if(!empty($client_engagement_info[0]['client_name']))
                        {
                            $temp_content = $client_engagement_info[0]['client_name'];
                        }

                        $new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
                    }
                    elseif($string2 == "client address")
                    {
                        $company_address = array(
                            'type'          => 'Local',
                            'street_name1'  => strtoupper(strtolower($client_engagement_info[0]['street_name'])),
                            'unit_no1'      => strtoupper(strtolower($client_engagement_info[0]["unit_no1"])),
                            'unit_no2'      => strtoupper(strtolower($client_engagement_info[0]["unit_no2"])),
                            'building_name1'=> strtoupper(strtolower($client_engagement_info[0]["building_name"])),
                            'postal_code1'  => strtoupper(strtolower($client_engagement_info[0]["postal_code"]))
                        );

                        $temp_content = $this->write_address_local_foreign($company_address, "letter", "big_cap");

                        if(empty($temp_content))
                        {
                            $temp_content = "______________________________________________________________";
                        }

                        $new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
                    }
                    elseif($string2 == "client company uen")
                    {   
                        if(!empty($client_engagement_info[0]['registration_no']))
                        {
                            $temp_content = "(UEN: " . $client_engagement_info[0]['registration_no'] . ")";
                        }
                        else
                        {
                            $temp_content = '';
                        }

                        $new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
                    }
                    elseif($string2 == "Total Engagement Fees")
                    {
                        $total_engagement_fee = 0;

                        // if(count($each_info) > 0)
                        // {
                        //     for($b = 0; $b < count($service_info_list); $b++)
                        //     {
                        //         // echo json_encode($service_info_list[$b]) . " ";
                        //         $total_engagement_fee = $total_engagement_fee + $service_info_list[$b]["fee"];
                        //         $temp_content      = number_format($total_engagement_fee, 2);
                        //         $new_contents_info = str_replace($replace_string, $service_info_list[$b]["currency"] . $temp_content, $new_contents_info);
                        //     }
                        // }
                        if($each_info['fee'])
                        {
                            $temp_content      = number_format($each_info['fee'], 2);
                            $new_contents_info = str_replace($replace_string, $each_info["currency"] . $temp_content, $new_contents_info);
                        }
                        else
                        {
                            $temp_content      = "______________";
                            $new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
                        }
                    }
                    elseif($string2 == "Director Name")
                    {
                        if($service_firm_name == "ACUMEN ALPHA ADVISORY PTE. LTD. (SBF CENTER)")
                        {
                            $temp_content = "";
                        }
                        else
                        {
                            $temp_content = $client_engagement_info[0]['director_signing'];
                        }

                        $new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
                    }
                    elseif($string2 == "Engagement Letter Date")
                    {
                        $temp_content = $letter_date;

                        $new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
                    }
                    elseif($string2 == "servicing firm name")
                    {
                        $temp_content = $each_info['name'];

                        $new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
                    }
                    elseif($string2 == "Company Director Name")
                    {
                        if($service_firm_name == "ACUMEN ALPHA ADVISORY PTE. LTD. (SBF CENTER)")
                        {
                            $temp_content = "George Yeo";
                        }
                        else
                        {
                            $temp_content = "Woelly William";
                        }
                        

                        $new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
                    }
                    elseif($string2 == "ML Fee" || $string2 == "PMFT Fee" || $string2 == "Secretarial Fee")
                    {
                        if($each_info["fee"] > 0)
                        {
                            $temp_content = $each_info["currency"] . number_format($each_info["fee"], 2) . " per " .strtolower($each_info["unit_pricing_name"]);

                            $new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);

                        }
                        else
                        {
                            if(strpos($new_contents_info, '<p class="our_fee" style="text-align: justify;">') !== false)
                            {
                                $new_contents_info = str_replace('<p class="our_fee" style="text-align: justify;">', '<p class="our_fee" style="text-align: justify; display: none;">;', $new_contents_info);
                            }
                            elseif(strpos($new_contents_info, '<table class="check_new_page" id="our_fee_table" style="border-collapse: collapse; width: 100%; height: 28px;" border="0">') !== false)
                            {
                                $new_contents_info = str_replace('<table class="check_new_page" id="our_fee_table" style="border-collapse: collapse; width: 100%; height: 28px;" border="0">', '<table class="check_new_page" id="our_fee_table" style="border-collapse: collapse; width: 100%; height: 28px;  display: none;" border="0">', $new_contents_info);
                            }
                        }
                    }
                }

                $tagvs = array('p' => array(1 => array('h' => 0.0001, 'n' => 1)), 'ul' => array(0 => array('h' => 0.0001, 'n' => 1)));
                        $obj_pdf->setHtmlVSpace($tagvs);

                if($doc_template_code == "6" && $client_engagement_info[0]['client_name'] == "TOONG CHAI PRESBYTERIAN CHURCH")
                {
                    
                    $new_contents_info = str_replace("The Board of Directors", "Members of the Council", $new_contents_info);
                    $new_contents_info = str_replace("statement of changes in equity", "statement of changes in accumulated funds", $new_contents_info);
                    $new_contents_info = str_replace("audit engagement by means of this letter", "audit engagement pursuant to Charities Act (Cap. 37)", $new_contents_info);
                    $new_contents_info = str_replace("provisions of the Companies Act, Chapter 50 (the Act)", "provisions of the Charities Act (the Act)", $new_contents_info);
                    $new_contents_info = str_replace("NOT APPLICABLE", "", $new_contents_info);
                    $new_contents_info = str_replace("Director", "Designation:", $new_contents_info);
                }

                $content = $new_contents_info;


                $content = str_replace('class="check_new_page"', 'nobr="true"', $content);  // replace static text paragraph to make sure text is displayed in block together.

                $obj_pdf->writeHTML($content, true, 0, true, true);

                // company signature
                // if($service_firm_name == "ACUMEN BIZCORP PTE. LTD.")
                // {
                //     $img_tag = 'img/Signature - ABC.png';

                //     $obj_pdf->setY($obj_pdf->getY() - 45);
                    
                //     $obj_pdf->Image($img_tag, '', '', 50, 30, '', '', 'T', false, 1000, '', false, false, 1, false, false, false);
                // }
                // elseif($service_firm_name == "ACUMEN ASSOCIATES LLP")
                // {
                //     //$img_tag = '<img src="img/Signature - AA LLP.png" height="85px;"' . ' />';
                //     $img_tag = 'img/Signature - AA LLP.png';

                //     if($document_name == "PMFT Audit" || $document_name == "ML Quarterly Statements")
                //     {
                //         $obj_pdf->setY($obj_pdf->getY() - 45);
                //     }
                //     else
                //     {
                //         $obj_pdf->setY($obj_pdf->getY() - 40);
                //     }
                //     $obj_pdf->Image($img_tag, '', '', 80, 30, '', '', 'T', false, 1000, '', false, false, 1, false, false, false);
                // }
                // elseif($service_firm_name == "SYA PAC")
                // {
                //     $img_tag = 'img/Signature - SYA.png';

                //     $obj_pdf->setY($obj_pdf->getY() - 48);
                    
                //     $obj_pdf->Image($img_tag, '', '', 45, 40, '', '', 'T', false, 1000, '', false, false, 1, false, false, false);
                // }
                // elseif($service_firm_name == "ACUMEN ASSURANCE")
                // {
                //     $img_tag = 'img/Signature - AA.png';

                //     $obj_pdf->setY($obj_pdf->getY() - 45);
                    
                //     $obj_pdf->Image($img_tag, '', '', 80, 30, '', '', 'T', false, 1000, '', false, false, 1, false, false, false);
                // }
                // elseif($service_firm_name == "SIMPEX CONSULTING (S) PTE. LTD.")
                // {
                //     $img_tag = 'img/Signature - Simpex.png';

                //     $obj_pdf->setY($obj_pdf->getY() - 48);

                //     $obj_pdf->Image($img_tag, '', '', 45, 35, '', '', 'T', false, 1000, '', false, false, 1, false, false, false);
                // }
                // elseif($service_firm_name == "ACUMEN ALPHA ADVISORY PTE. LTD. (SBF CENTER)")
                // {
                //     $img_tag = 'img/george_signature_chop.png';

                //     $obj_pdf->setY($obj_pdf->getY() - 48);
                    
                //     $obj_pdf->Image($img_tag, '', '', 45, 38, '', '', 'T', false, 1000, '', false, false, 1, false, false, false);
                // }
                // elseif($service_firm_name == "ACUMEN ALPHA ADVISORY PTE. LTD. (NOVELTY BIZCENTRE)")
                // {
                //     $img_tag = 'img/Woelly_AAA_Signature.png';

                //     if($document_name == "PMFT Audit" || $document_name == "ML Quarterly Statements")
                //     {
                //         $obj_pdf->setY($obj_pdf->getY() - 55);
                //     }
                //     else
                //     {
                //         $obj_pdf->setY($obj_pdf->getY() - 48);
                //     }
                    
                //     $obj_pdf->Image($img_tag, '', '', 63, 38, '', '', 'T', false, 1000, '', false, false, 1, false, false, false);
                // }
                // else
                // {
                //     $img_tag = '';
                // }

                $string_client_name = $this->myUrlEncode($client_engagement_info[0]['client_name']);
                $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'audit/document/engagement/'.$document_name.' - '.$string_client_name.'.pdf', 'F');

                chmod($_SERVER['DOCUMENT_ROOT'].'audit/document/engagement/'.$document_name.' - '.$string_client_name.'.pdf', 0644);

                if(!file_exists($_SERVER['DOCUMENT_ROOT'].'audit/document/engagement/'.$document_name.' - '.$string_client_name.'.pdf'))
                {
                    echo "File Doesn't Exist...";exit;
                }

                $this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'audit/document/engagement/'.$document_name.' - '.$string_client_name.'.pdf');


            }

            if ($el_id != "")
            {

                $zipfile_title = "EngagementLetter";
            }

            if ($sub_el_id != "")
            {

                $zipfile_title = "SubsequentEngagementLetter";
            }

            $this->zip->archive($_SERVER['DOCUMENT_ROOT'].'audit/document/engagement/'.$zipfile_title.'('.$string_client_name.').zip');

            chmod($_SERVER['DOCUMENT_ROOT'].'audit/document/engagement/'.$zipfile_title.'('.$string_client_name.').zip',0644);

            $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';

            $file_link = $protocol.$_SERVER['SERVER_NAME'].'/audit/document/engagement/'.$zipfile_title.'('.$string_client_name.').zip';

            echo json_encode(array('file_link' => $file_link));


        }

        
    }

    public function lodge_engagement()
    {
        $form_data = $this->input->post();
        $el_id = $form_data['el_id'];

        $query = 'SELECT * FROM audit_engagement_letter, client
                    WHERE audit_engagement_letter.company_code = client.company_code and client.deleted = 0 and audit_engagement_letter.id ='.$el_id;

        $check_client_exist = $this->db->query($query)->result();

        $service_query = 'SELECT audit_engagement_letter_info.*, audit_engagement_letter.*, our_service_info.id as our_service_info_id, our_service_info.invoice_description FROM audit_engagement_letter_info
                          LEFT JOIN audit_engagement_letter ON audit_engagement_letter.id = audit_engagement_letter_info.engagement_letter_id
                          LEFT JOIN our_service_info ON our_service_info.engagement_letter_list_id = audit_engagement_letter_info.engagement_letter_type
                    WHERE engagement_letter_id ='.$el_id;

        $service_array = $this->db->query($service_query)->result_array();

        // print_r($check_client_exist);

        if(count($check_client_exist) > 0)
        {
            $this->update_service_engagement($service_array, $check_client_exist[0]->company_code);

            echo json_encode("Added new service engagement");

        }
        else
        {
            $proposal_client = $this->db->select('transaction_master.*,  transaction_service_proposal_info.*,  audit_engagement_letter.uen, audit_engagement_letter.fye_date, audit_engagement_letter.director_signing, audit_engagement_letter.engagement_letter_date');
                                $this->db->from('transaction_master');
                                $this->db->join('transaction_service_proposal_info', 'transaction_service_proposal_info.transaction_id = transaction_master.id ', 'left');
                                $this->db->join('audit_engagement_letter', 'audit_engagement_letter.company_code = transaction_master.company_code', 'left');
                                $this->db->where('transaction_master.transaction_task_id', "29");
                                $this->db->where('audit_engagement_letter.id', $el_id);
                                $this->db->where('(transaction_master.service_status = 1 or transaction_master.service_status = 3)');
                                $this->db->order_by("transaction_master.id", "asc");
            $proposal_client = $this->db->get();
            $proposal_client = $proposal_client->result_array();

            $new_client["firm_id"] = $proposal_client[0]['firm_id'];
            $new_client["acquried_by"] = 1;
            $new_client["company_type"] = 1;
            $new_client["status"] = 1;
            $new_client["company_code"] = $proposal_client[0]['company_code'];
            $new_client["client_code"] = $this->engagement_model->detect_client_code($this->encryption->decrypt($proposal_client[0]['client_name']));
            $new_client["registration_no"] = $this->encryption->encrypt(strtoupper($proposal_client[0]['uen']));
            $new_client["company_name"] = $proposal_client[0]['client_name'];
            $new_client["activity1"] = $proposal_client[0]['activity1'];
            $new_client["activity2"] = $proposal_client[0]['activity2'];
            $new_client["postal_code"] = $proposal_client[0]['postal_code'];
            $new_client["street_name"] = $proposal_client[0]['street_name'];
            $new_client["building_name"] = $proposal_client[0]['building_name'];
            $new_client["unit_no1"] = $proposal_client[0]['unit_no1'];
            $new_client["unit_no2"] = $proposal_client[0]['unit_no2'];
            $new_client["created_by"] = $this->session->userdata('user_id');

            $this->db->insert("client",$new_client);

            for($i = 0; $i < count($service_array); $i++ )
            {
                $billing_info_id = $i + 1;
                $client_billing_info['company_code'] = $proposal_client[0]['company_code'];
                $client_billing_info['client_billing_info_id'] = $i + 1;
                $client_billing_info['service'] = $service_array[$i]['our_service_info_id'];
                $client_billing_info['invoice_description'] = $service_array[$i]['invoice_description'];
                //(int)str_replace(',', '', $amount[$p]);
                $client_billing_info['amount'] = (float)str_replace(',', '', $service_array[$i]['fee']);
                $client_billing_info['currency'] = $service_array[$i]['currency_id'];
                $client_billing_info['unit_pricing'] = $service_array[$i]['unit_pricing'];
                $client_billing_info['servicing_firm'] = $service_array[$i]['servicing_firm'];

                $this->db->insert("client_billing_info",$client_billing_info);
            }

            $disbursements['company_code'] = $proposal_client[0]['company_code'];
            $disbursements['client_billing_info_id'] = $billing_info_id + 1;
            $disbursements['service'] = 26;
            $disbursements['invoice_description'] = "Printing, stationery and transport charges";
            $disbursements['amount'] = 0;
            $disbursements['currency'] = 1;
            $disbursements['unit_pricing'] = 6;
            $disbursements['servicing_firm'] = $this->session->userdata('firm_id');

            $this->db->insert("client_billing_info",$disbursements);

            // print_r($service_array);

            $this->db->select('id, company_code, name, created_at');
            $this->db->from('transaction_client_contact_info');
            $this->db->where('company_code', $service_array[0]['company_code']);
            $this->db->where('transaction_id',  $proposal_client[0]['transaction_id']);
            $check_transaction_client_contact_info = $this->db->get();

            foreach($check_transaction_client_contact_info->result() as $g) {
                $f['company_code'] = $g->company_code;
                $f['name'] = $g->name;
                $f['created_at'] = $g->created_at;
            }

            // print_r($check_transaction_client_contact_info->result());
            
            //echo json_encode($latest_contact_info);
            if($check_transaction_client_contact_info->num_rows())
            {
                $this->db->insert("client_contact_info",$f);
                $client_contact_info_id = $this->db->insert_id();
            }

            $this->db->select('client_contact_info_id, email, primary_email');
            $this->db->from('transaction_client_contact_info_email');
            $this->db->where('client_contact_info_id', $check_transaction_client_contact_info->result_array()[0]["id"]);
            $check_transaction_client_contact_info_email = $this->db->get();

            foreach($check_transaction_client_contact_info_email->result() as $r) {
                $r->client_contact_info_id = $client_contact_info_id;
                $this->db->insert("client_contact_info_email",$r);
            }
             

            $this->db->select('client_contact_info_id, phone, primary_phone');
            $this->db->from('transaction_client_contact_info_phone');
            $this->db->where('client_contact_info_id', $check_transaction_client_contact_info->result_array()[0]["id"]);
            $check_transaction_client_contact_info_phone = $this->db->get();

            foreach($check_transaction_client_contact_info_phone->result() as $r) {
                $r->client_contact_info_id = $client_contact_info_id;
                $this->db->insert("client_contact_info_phone",$r);
            }

            echo json_encode("Added new client");

            
        }


    }


    public function lodge_subsequent_engagement()
    {
        $form_data = $this->input->post();
        $sub_el_id = $form_data['sub_el_id'];

        $service_query = 'SELECT audit_subsequent_el_info.*, audit_subsequent_el.*, our_service_info.id as our_service_info_id, our_service_info.invoice_description FROM audit_subsequent_el_info
                          LEFT JOIN audit_subsequent_el ON audit_subsequent_el.id = audit_subsequent_el_info.subsequent_el_id
                          LEFT JOIN our_service_info ON our_service_info.engagement_letter_list_id = audit_subsequent_el_info.engagement_letter_type
                    WHERE subsequent_el_id ='.$sub_el_id;

        $service_array = $this->db->query($service_query)->result_array();

        // print_r($check_client_exist);

        if(count($service_array) > 0)
        {
            $this->update_service_engagement($service_array, $service_array[0]['company_code']);

            echo json_encode("Added new service engagement");

        }
    }

    public function updt_initial_el_status()
    {
        $this->session->set_userdata("tab_active", "engagement");

        $form_data = $this->input->post();

        $result = $this->engagement_model->updt_initial_el_status($form_data['status_id'], $form_data['letter_id']);

        echo $result;
    }

    public function updt_subsequent_el_status()
    {
        $this->session->set_userdata("tab_active", "subsequent");

        $form_data = $this->input->post();

        $result = $this->engagement_model->updt_subsequent_el_status($form_data['status_id'], $form_data['letter_id']);

        echo $result;
    }


    public static function get_engagement_doc_list()
    {
        $form_data = $this->input->post();
        $el_id = $form_data['letter_id'];

        $query = 'SELECT engagement_letter_id, GROUP_CONCAT(DISTINCT CONCAT(id,",",file_name)SEPARATOR ";") as file_names FROM audit_engagement_doc 
                    WHERE engagement_letter_id ='.$el_id;

        $q = $this->db->query($query);

        foreach ($q->result() as $key => $value) {
            if($q->result()[$key]->file_names != null)
            {
                $q->result()[$key]->file_names = explode(';', $q->result()[$key]->file_names);
            }
        }
        

        echo json_encode($q-> result());

    }

    public static function get_sub_engagement_doc_list()
    {
        $form_data = $this->input->post();
        $sub_el_id = $form_data['letter_id'];

        $query = 'SELECT subsequent_el_id, GROUP_CONCAT(DISTINCT CONCAT(id,",",file_name)SEPARATOR ";") as file_names FROM audit_subsequent_el_doc 
                    WHERE subsequent_el_id ='.$sub_el_id;

        $q = $this->db->query($query);

        foreach ($q->result() as $key => $value) {
            if($q->result()[$key]->file_names != null)
            {
                $q->result()[$key]->file_names = explode(';', $q->result()[$key]->file_names);
            }
        }
        

        echo json_encode($q-> result());

    }

    public function update_service_engagement($service_array, $company_code)
    {
        $this->db->select('client_billing_info.*');
        $this->db->from('client_billing_info');
        $this->db->where('company_code', $company_code);
        $this->db->where('deleted = 0');
        $client_billing_info_data = $this->db->get();

        $service_id_array = array();

        foreach($client_billing_info_data->result() as $g) 
        {
            array_push($service_id_array, $g->service);
        }

        //echo json_encode($service_id_array);

        $this->db->select('MAX(client_billing_info_id) as max_client_billing_id');
        $this->db->from('client_billing_info');
        $this->db->where('company_code', $company_code);
        $row = $this->db->get();
        $row_max_id = $row->result_array();

        if (!$row->num_rows())
        {   
            $max_id = 0;
        } 
        else 
        {
            $max_id = (int)$row_max_id[0]['max_client_billing_id'];
        }

        for($i = 0; $i < count($service_array); $i++ )
        {

            if(in_array($service_array[$i]['our_service_info_id'], $service_id_array)) //match
            {
                //echo json_encode("match: ".$check_is_potential_client_array[$i]['our_service_id']);
                $client_billing_info['amount'] = (float)str_replace(',', '', $service_array[$i]['fee']);
                $client_billing_info['currency'] = $service_array[$i]['currency_id'];
                $client_billing_info['unit_pricing'] = $service_array[$i]['unit_pricing'];
                $client_billing_info['servicing_firm'] = $service_array[$i]['servicing_firm'];
                $client_billing_info['deactive'] = 0;
                //$client_billing_info['deleted'] = 1;
                $this->db->update("client_billing_info",$client_billing_info,array("company_code" =>  $company_code, "service" => $service_array[$i]['our_service_info_id']));
                //$haveService = false;
            }
            else //not match
            {
                //echo json_encode("not match: ".$check_is_potential_client_array[$i]['our_service_id']);
                $client_billing_info['company_code'] = $company_code;
                $client_billing_info['client_billing_info_id'] = $max_id + 1;
                $client_billing_info['service'] = $service_array[$i]['our_service_info_id'];
                $client_billing_info['invoice_description'] = $service_array[$i]['invoice_description'];
                //(int)str_replace(',', '', $amount[$p]);
                $client_billing_info['amount'] = (float)str_replace(',', '', $service_array[$i]['fee']);
                $client_billing_info['currency'] = $service_array[$i]['currency_id'];
                $client_billing_info['unit_pricing'] = $service_array[$i]['unit_pricing'];
                $client_billing_info['servicing_firm'] = $service_array[$i]['servicing_firm'];
                $this->db->insert("client_billing_info",$client_billing_info);

                $max_id = $max_id + 1;
            }
        }


    }

    public function get_subsequent_el_detail()
    {
        $company_code = $_POST["company_code"];
        $el_id = isset($_POST['el_id'])?$_POST['el_id']: "";

        if($el_id != null || $el_id != "")
        {
            $data['engagement_letter_info'] = $this->engagement_model->getEngagementLetterInfo($el_id);
            // echo "hi";
        }

        $data['client_info'] = $this->engagement_model->check_subsequent_el_client_info($company_code);



        $data['get_previous_el_info'] = $this->engagement_model->get_previous_el_info($company_code);

        if(ISSET($data['client_info'][0]))
        {
            
            $data['director_result_1'] = $data['client_info'][0]->director_signing;
            

            

        }
        else
        {
            $data['director_result_1'] = "";
        }

       
        

        $get_all_firm_info = $this->engagement_model->getAllFirmInfo();
        for($j = 0; $j < count($get_all_firm_info); $j++)
        {
            if($get_all_firm_info[$j]->branch_name != null)
            {
                $res_firm[$get_all_firm_info[$j]->id] = $get_all_firm_info[$j]->name.' ('.$get_all_firm_info[$j]->branch_name.')';
            }
            else
            {
                $res_firm[$get_all_firm_info[$j]->id] = $get_all_firm_info[$j]->name;
            }
        }
        $data['get_all_firm_info'] = $res_firm;

        $result_currency = $this->db->query("select * from currency order by currency");
        $result_currency = $result_currency->result_array();
        for($j = 0; $j < count($result_currency); $j++)
        {
            $res[$result_currency[$j]['id']] = $result_currency[$j]['currency'];
        }
        $data["currency"] = $res;

        $result_unit_pricing = $this->db->query("select * from unit_pricing");
        $result_unit_pricing = $result_unit_pricing->result_array();
        for($j = 0; $j < count($result_unit_pricing); $j++)
        {
            $res_unit_pricing[$result_unit_pricing[$j]['id']] = $result_unit_pricing[$j]['unit_pricing_name'];
        }
        $data["unit_pricing_name"] = $res_unit_pricing;


        echo json_encode($data);
    }

    public function save_subsequent_el()
    {
        $this->session->set_userdata("tab_active", "subsequent");
        
        $sub_el_data['id'] = $_POST["subsequent_el_id"];
        $sub_el_data['previous_date'] = $_POST["previous_letter_date"];
        $sub_el_data['new_date'] = $_POST["new_letter_date"];
        $sub_el_data['start_fye_date'] = $_POST["start_fye_date"];
        $sub_el_data['fye_date'] = $_POST["fye_date"];
        $sub_el_data['director_signing'] = strtoupper($_POST["director_signing"]);
        $sub_el_data['company_code'] = $_POST["company_code"];
        // $el_data['company_name'] = $_POST["company_name"];

        // print_r($_POST);

        $engagement_letter_query = $this->db->get_where("audit_subsequent_el", array("id" => $sub_el_data['id']));

        if (!$engagement_letter_query->num_rows())
        {
            $this->db->insert("audit_subsequent_el",$sub_el_data);
            $sub_el_id = $this->db->insert_id();

            //log insert 
            $log_data   = array(
                'subsequent_el_id'     => $sub_el_id,
                'date_time'  => date("Y-m-d H:i:s"),
                'user_id'    => $this->session->userdata('user_id'),
                'log_message'    => "insert subsequent engagement letter",
            );

            $this->db->insert('audit_subsequent_el_log', $log_data);
        }
        else
        {
            $this->db->update("audit_subsequent_el",$sub_el_data,array("id" => $sub_el_data['id']));
            $sub_el_id = $sub_el_data['id'];

            //log update 
            $log_data   = array(
                'subsequent_el_id'     => $sub_el_id,
                'date_time'  => date("Y-m-d H:i:s"),
                'user_id'    => $this->session->userdata('user_id'),
                'log_message'    => "update subsequent engagement letter",
            );

            $this->db->insert('audit_subsequent_el_log', $log_data);
        }

        $this->db->delete("audit_subsequent_el_info",array('subsequent_el_id'=>$sub_el_data['id']));

        for($h = 0; $h < count($_POST['hidden_selected_el_id']); $h++)
        {
            if($_POST['hidden_selected_el_id'][$h] != "")
            {
                $el_info_data['subsequent_el_id'] = $sub_el_id;
                $el_info_data['engagement_letter_type'] = $_POST['hidden_selected_el_id'][$h];
                $el_info_data['currency_id'] = $_POST['currency'][$h];
                $el_info_data['fee'] = str_replace(',', '', $_POST['fee'][$h]);
                $el_info_data['unit_pricing'] = $_POST['unit_pricing'][$h];
                $el_info_data['servicing_firm'] = $_POST['servicing_firm'][$h];

                $this->db->insert('audit_subsequent_el_info', $el_info_data);
            }
        }

        echo json_encode(array('status'=>'1', 'message' => "Information updated successfully", "title" => "Success", "sub_el_id" => $sub_el_id));

    }

    public function search_moved_al()
    {
        $company_code = $_POST["company_code"];

        $data['letter_info'] = $this->engagement_model->check_subsequent_el_client_info($company_code);

        echo json_encode($data['letter_info'][0]);
    }

    public function change_letter_date()
    {
        $subsequent_el_flag = $_POST["subsequent_flag"];
        $result = false;

        if($subsequent_el_flag == 1)
        {
            $result = $this->engagement_model->update_subsequent_date($_POST["moved_letter_date"], $_POST["hidden_letter_id"]);
        }
        else
        {
            $result = $this->engagement_model->update_initial_date($_POST["moved_letter_date"], $_POST["hidden_letter_id"]);
        }

        if($result)
        {
            echo json_encode(array('status'=>'1', 'message' => "Information updated successfully", "title" => "Success"));
        }
        else
        {
            echo json_encode(array('status'=>'0', 'message' => "Failed to update letter date", "title" => "Error"));
        }
    }

    public static function getEngagementPotentialClient() 
    {
        $ci =& get_instance();
        
        // $result = $this->db->query('select client.id, client.company_code, client.company_name from client
        //     where client.deleted = 0 and client.company_code not in (select company_code from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service where (our_service_info.service_type = 10 OR our_service_info.service_type = 1) and client_billing_info.deactive = 0 and client_billing_info.deleted = 0) and client.company_code NOT IN (select company_code from audit_engagement_letter where deleted = 0)');

        $result = $this->db->query('select client.id, client.company_code, client.company_name from client
    where client.deleted = 0 and client.company_code not in (select company_code from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service where (our_service_info.service_type = 1) and client_billing_info.deactive = 0 and client_billing_info.deleted = 0) and client.company_code NOT IN (select company_code from audit_engagement_letter where deleted = 0)');

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

        // $result = $this->db->query('select client.id, client.company_code, client.company_name from client
        //     where client.deleted = 0 and client.company_code in (select company_code from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service where (our_service_info.service_type = 10 OR our_service_info.service_type = 1) and client_billing_info.deactive = 0 and client_billing_info.deleted = 0) and client.company_code NOT IN (select company_code from audit_engagement_letter where deleted = 0)');

        $result = $this->db->query('select client.id, client.company_code, client.company_name from client
            where client.deleted = 0 and client.company_code in (select company_code from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service where (our_service_info.service_type = 1) and client_billing_info.deactive = 0 and client_billing_info.deleted = 0) and client.company_code NOT IN (select company_code from audit_engagement_letter where deleted = 0)');

        $result = $result->result_array();
        //echo json_encode($result);
        if(!$result) {
            throw new exception("Client not found.");
        }

        $existing_audit_client = array();
        foreach($result as $row) {
            $row['company_name'] = $this->encryption->decrypt($row['company_name']);
            array_push($existing_audit_client, trim($row['company_name']));
        }

        $trans_master_result = $this->db->query('select transaction_master.* from transaction_master left join transaction_service_proposal_info on transaction_master.id = transaction_service_proposal_info.transaction_id where transaction_master.service_status = 3 and transaction_master.transaction_task_id = 29 and transaction_service_proposal_info.potential_client = 1 and transaction_master.company_code NOT IN (select company_code from audit_engagement_letter where deleted = 0)');// and firm_id = "'.$this->session->userdata('firm_id').'" //transaction_master.service_status != 2 and transaction_master.service_status != 4 and

        $trans_master_result = $trans_master_result->result_array();
        //echo json_encode($result);
        // if(!$trans_master_result) {
        //  throw new exception("Potential Client not found.");
        // }
        //echo json_encode($master_client_name_info);
        foreach($trans_master_result as $row) {
            $row['client_name'] = $this->encryption->decrypt($row['client_name']);
            //print_r(in_array($row['client_name'], $master_client_name_info));
            if(!(in_array(trim($row['client_name']), $master_client_name_info)) && !(in_array(trim($row['client_name']), $existing_audit_client))) {
                $res[$row['company_code']] = $row['client_name'].' (Potential Client)';
            }
        }

        $selected_client = $ci->session->userdata('potential_company_code');
        $ci->session->unset_userdata('potential_company_code');

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client fetched successfully.", 'result'=>$res, 'selected_potential_client'=>$selected_client);

        echo json_encode($data);
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

class ENGAGEMENT_PDF extends TCPDF {
    public function Header() {
        $headerData = $this->getHeaderData();
        $this->writeHTMLCell(0, 0, 26, '', $headerData['string'], 0, 0, false, "L", true);
   }

   public function Footer() {
        // Position at 25 mm from bottom
        $this->SetY(-16);
        $this->SetX(36);
        // Set font
        $this->SetFont('helvetica', '', 9);
        
        // Page number
        $this->Cell(0, 0, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Ln();

        // $logoX = 133; // 186mm. The logo will be displayed on the right side close to the border of the page
        // $logoFileName = base_url()."/img/footer_img.png";
        // $logoWidth = 50; // 15mm
        // $logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);
   }
   
}

class ENGAGEMENT_PDF_With_PREPrinter extends TCPDF {
    public function Header() {
        $headerData = $this->getHeaderData();
        $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);
   }

   public function Footer() {
        // Position at 25 mm from bottom
        $this->SetY(-20);
        $this->SetX(10);
        // Set font
        $this->SetFont('helvetica', '', 8);
        
        // Page number
        $this->Cell(0, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Ln();
   }
}

class ENGAGEMENT_PDF_WITH_NORMAL_FOOTER extends TCPDF {
    public function Header() {
        $headerData = $this->getHeaderData();
        $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);
    }

    public function Footer() {
        // Position at 25 mm from bottom
        $this->SetY(-20);
        $this->SetX(10);
        // Set font
        $this->SetFont('helvetica', '', 8);
        
        // Page number
        $this->Cell(0, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Ln();
   }
}


