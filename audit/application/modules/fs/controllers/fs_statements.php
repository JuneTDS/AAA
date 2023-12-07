<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fs_statements extends MX_Controller
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
        $this->load->model('fs_model');
        $this->load->model('fs_notes_model');
        $this->load->model('fs_statements_model');
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

    public function state_cash_flow($audit_caf_master_id, $assg_id)
    {
        $fs_company_info_id = $this->caf_model->getReportIdWithAssg($assg_id);

        $fs_company_info                 = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $temp_all_state_cash_flows_fixed = $this->fs_statements_model->get_fs_state_cash_flows_fixed($fs_company_info_id);
        $temp_arr = array();

        $pl_be4_tax_values_from_sci = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=3");
        $pl_be4_tax_values_from_sci = $pl_be4_tax_values_from_sci->result_array(); 

        if(count($temp_all_state_cash_flows_fixed) == 0) // Pre-defined value for "Cash and equivalent at beginning of the year"
        {
            // retrieve data for "Cash and cash equivalents"
            $parent_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, array('A1060000'));
            $account_list  = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $parent_fca_id); 

            if(count($account_list) > 0)
            {
                $temp_arr['cash_equivalent_begin']['fs_note_details_id'] = '';
                $temp_arr['cash_equivalent_begin']['group_ye']           = 0;
                $temp_arr['cash_equivalent_begin']['group_lye_end']      = 0;
                $temp_arr['cash_equivalent_begin']['company_ye']         = $account_list[0]['parent_array'][0]['total_c_lye'];
                $temp_arr['cash_equivalent_begin']['company_lye_end']    = 0;
                $temp_arr['cash_equivalent_begin']['note_display_num']   = '';
            }
        }
        else
        {
            foreach ($temp_all_state_cash_flows_fixed as $key => $each) 
            {
                $temp_arr[$each['fixed_tag']]['fs_note_details_id'] = $each['fs_note_details_id'];
                $temp_arr[$each['fixed_tag']]['group_ye']           = $each['value_group_ye'];
                $temp_arr[$each['fixed_tag']]['group_lye_end']      = $each['value_group_lye_end'];
                $temp_arr[$each['fixed_tag']]['company_ye']         = $each['value_company_ye'];
                $temp_arr[$each['fixed_tag']]['company_lye_end']    = $each['value_company_lye_end'];
                
                if($each['fs_note_details_id'] != null)
                {
                    $temp_arr[$each['fixed_tag']]['note_display_num'] = $this->fs_notes_model->get_input_note_num($fs_company_info_id, $each['fs_note_details_id']);
                }
            }
        }

        // get fs_state_cash_flows data
        $fs_state_cash_flows = $this->fs_statements_model->get_fs_state_cash_flows($fs_company_info_id);

        if(count($fs_state_cash_flows) == 0)
        {
            $this->fs_statements_model->create_state_cash_flows_w_ly_val($fs_company_info_id);
            $fs_state_cash_flows = $this->fs_statements_model->get_fs_state_cash_flows($fs_company_info_id);
        }

        $this->data['fs_state_cash_flows'] = $fs_state_cash_flows;
        $this->data['fs_state_cash_flows_fixed'] = $temp_arr;
        $this->data['check_operating_act'] = $this->fs_statements_model->get_fs_state_cash_flows_section($fs_company_info_id, 1);
        $this->data['check_investing_act'] = $this->fs_statements_model->get_fs_state_cash_flows_section($fs_company_info_id, 2);
        $this->data['check_financing_act'] = $this->fs_statements_model->get_fs_state_cash_flows_section($fs_company_info_id, 3); 

        if($fs_company_info[0]["group_type"] == '1')
        {
            $this->data['is_group'] = false;
        }
        else
        {
            $this->data['is_group'] = true;
        }

        // first set report for colspan
        if($fs_company_info[0]['first_set'])
        {
            $this->data['colspan_val'] = 1;
        }
        else
        {
            $this->data['colspan_val'] = 2;
        }

        $current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id, "General");

        $this->data["last_fye_end"]     = $current_last_year_end['last_fye_end'];
        $this->data["current_fye_end"]  = $current_last_year_end['current_fye_end'];

        $this->data['show_data_content'] = $this->fs_statements_model->is_saved_fs_categorized_account_round_off($fs_company_info_id);
        $this->data['pl_be4_tax_values_from_sci'] = $pl_be4_tax_values_from_sci;

        $bc = array(array('link' => '#', 'page' => 'Statement of Cash flows Information'));
        $meta = array('page_title' => 'Statement of Cash flows Information', 'bc' => $bc, 'page_name' => 'Statement of Cash flows Information');

        $this->page_construct('state_cash_flow_info.php', $meta, $this->data);
    }
}



