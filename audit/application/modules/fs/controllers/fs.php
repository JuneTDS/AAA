<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fs extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
        
        $this->load->library(array('session','parser','encryption'));
        // $this->load->library(array('zip'));
        $this->load->model('caf/caf_model');
    }

    public function index($audit_caf_master_id, $assg_id)
    {   
        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $this->data['assignment_id'] = $assg_id;
        $this->data['fs_company_info_id'] = $this->caf_model->getReportIdWithAssg($assg_id);
        

        $bc = array(array('link' => '#', 'page' => 'Financial Statement'));
        $meta = array('page_title' => 'Financial Statement', 'bc' => $bc, 'page_name' => 'Financial Statement');

        $this->page_construct('index.php', $meta, $this->data);
    }

    // public function state_cash_flow($audit_caf_master_id, $assign_id)
    // {
    //     $bc = array(array('link' => '#', 'page' => 'Statement of Cash flows Information'));
    //     $meta = array('page_title' => 'Statement of Cash flows Information', 'bc' => $bc, 'page_name' => 'Statement of Cash flows Information');

    //     $this->page_construct('state_cash_flow_info.php', $meta, $this->data);
    // }
}



