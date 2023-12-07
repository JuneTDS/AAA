<?php defined('BASEPATH') OR exit('No direct script access allowed');
// include 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Assignment extends MX_Controller
{
	function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
        
        //$this->load->library('form_validation');
        $this->load->library(array('session','parser'));
        $this->load->model('assignment_model');
        $this->load->model('firm/master_model');

        $this->meta['page_name'] = 'Assignment';
    }

    public function index()
    {
        $bc   = array(array('link' => '#', 'page' => 'Assignment'));
        $meta = array('page_title' => 'Assignment', 'bc' => $bc, 'page_name' => 'Assignment');

        $this->data['firm_list'] = $this->assignment_model->get_firm_dropdown_list();
        $this->data['client_list'] = $this->assignment_model->get_client_dropdown_list();
        $this->data['status_list'] = $this->assignment_model->get_status_dropdown_list();
        $this->data['status_list2'] = $this->assignment_model->get_status_dropdown_list2();

        // print_r($this->data['status_list']);

        if(!$this->data['Admin']) {
            $this->data['signed_list'] = $this->assignment_model->get_user_signed_list($this->user_id);
        }else{
            $this->data['signed_list'] = $this->assignment_model->get_signed_list();
        }

        if(!$this->data['Admin']) {
            $this->data['completed_list'] = $this->assignment_model->get_user_completed_list($this->user_id);
        }else{
            $this->data['completed_list'] = $this->assignment_model->get_completed_list();
        }

        if(!$this->data['Admin']) {
            $this->data['assignment_list'] = $this->assignment_model->get_user_assignment_list($this->user_id);
        }else{
            $this->data['assignment_list'] = $this->assignment_model->get_assignment_list();
        }

        // if(!$this->data['Admin'] && !$this->data['Manager']) {
        //     $this->data['assignment_list'] = $this->assignment_model->get_user_assignment_list($this->user_id);
        // }else if($this->data['Manager']){
        //     $this->data['assignment_list'] = $this->assignment_model->get_manager_assignment_list($this->user_id);
        // }else if($this->data['Admin']){
        //     $this->data['assignment_list'] = $this->assignment_model->get_assignment_list();
        // }

        $this->data['completed_assignment_list'] = $this->assignment_model->get_completed_assignment_list();
        $this->data['yes_no_list'] = $this->assignment_model->get_yes_no_list();
        $this->data['partner_list'] = $this->assignment_model->get_partner_list();
        $this->data['partner_list2'] = $this->assignment_model->get_partner_list2();
        $this->data['users_list'] = $this->assignment_model->get_users_list($this->user_id);
        $this->data['manager_list'] = $this->assignment_model->get_manager_list();
        $this->data['jobs_list'] = $this->assignment_model->get_jobs_list();
        // $this->data['calender_list'] = $this->assignment_model->get_calender_leaveList();

        if(!$this->data['Admin']) {
            $this->data['calender_list'] = $this->assignment_model->get_calender_leaveList2($this->user_id);
        }else{
            $this->data['calender_list'] = $this->assignment_model->get_calender_leaveList();
        }

        // $this->page_construct('index.php', $this->meta, $this->data);
        $this->page_construct('index.php', $meta, $this->data);
        
    }

    public function submit_assignment(){

        $this->session->set_userdata("tab_active", "new_assignment");

        $form_data = $this->input->post();
        // echo json_encode($form_data);
        $q = $this->db->query("SELECT * FROM payroll_assignment WHERE assignment_id = '".$form_data['assignment_code']."'");

        foreach($q->result() as $client){
            $company_name = $client->client_name; 
            $pic_list = $client->PIC;
        }
        if($company_name==null)
        {
            $q = $this->db->query("SELECT * FROM client WHERE company_code = '".$form_data['client_id']."'");

            foreach($q->result() as $client){
                $company_name = $client->company_name; 
            }
        }

        $assistant = $form_data['assistant'];

        $existing_leader =  json_decode($pic_list)->leader;
        $existing_assistant =  json_decode($pic_list)->assistant;

        for($i = 0; $i < sizeof($assistant); $i++){
            $query = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$assistant[$i]."' ");
            $assistant_info[$i] = $query->result();
        }

        $query2 = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$form_data['leader']."' ");
        $leader_info = $query2->result();

        $pic= array(
            'partner'       => $form_data['A_partner'],
            'leader'        => $form_data['leader'],
            'assistant'     => $assistant,
            'manager'       => $form_data['manager']
        );

        // ENABLE NULL TO DATE TYPE
        if($form_data['assignment_fye']==NULL){
            $fye = NULL;
        }
        else{
            $fye = date('Y-m-d', strtotime(str_replace('/', '-', $form_data['assignment_fye'])));
        }

        if($form_data['assignment_account_received']==NULL){
            $account_received = NULL;
        }
        else{
            $account_received = date('Y-m-d', strtotime(str_replace('/', '-', $form_data['assignment_account_received'])));
        }

        if($form_data['assignment_due_date']==NULL){
            $due_date = NULL;
        }
        else{
            $due_date = date('Y-m-d', strtotime(str_replace('/', '-', $form_data['assignment_due_date'])));
        }

        if($form_data['assignment_id'] != null || $form_data['assignment_id'] != ''){
            $leader_email = "";
            $assistant_email =array();
            $change_to_assistant_info = null;


            $data = array(
                'assignment_id'    => $form_data['assignment_code'],
                'client_id'        => $form_data['client_id'],
                'client_name'      => strtoupper($company_name),
                'firm_id'          => $form_data['firm_id'],
                'type_of_job'      => $form_data['jobs_list'],
                'PIC'              => json_encode($pic),
                'FYE'              => $fye,
                'account_received' => $account_received,
                'due_date'         => $due_date,
                'remark'           => $form_data['assignment_remark']
            );

            $result = $this->assignment_model->submit_assignment($data, $form_data['assignment_id']);

            if ($form_data['leader'] !== $existing_leader){
                $leader_email = $leader_info;
            }

            if(sizeof($assistant) > sizeof($existing_assistant)){

                $new_assistant = array_diff($assistant,$existing_assistant);
                for($i = 0; $i < sizeof($assistant); $i++){
                    $query3 = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$new_assistant[$i]."' ");
                    $change_to_assistant_info[$i] = $query3->result();
                    
                    if(json_encode($change_to_assistant_info[$i]) != '[]'){
                        array_push($assistant_email,$change_to_assistant_info[$i]);
                    }
                }

            }else if(sizeof($assistant) < sizeof($existing_assistant)){

                $new_assistant = array_diff($assistant,$existing_assistant);
                for($i = 0; $i <= sizeof($existing_assistant); $i++){
                    $query3 = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$new_assistant[$i]."' ");
                    $change_to_assistant_info[$i] = $query3->result();
                    
                    if(json_encode($change_to_assistant_info[$i]) != '[]'){
                        array_push($assistant_email,$change_to_assistant_info[$i]);
                    }
                }

            }else if(sizeof($assistant) == sizeof($existing_assistant)){

                $new_assistant = array_diff($assistant,$existing_assistant);

                for($i = 0; $i <= sizeof($assistant); $i++)
                {
                    $query3 = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$new_assistant[$i]."' ");
                    $change_to_assistant_info[$i] = $query3->result();

                    if(json_encode($change_to_assistant_info[$i]) != '[]'){
                        array_push($assistant_email,$change_to_assistant_info[$i]);
                    }
                }
            }

            if($assistant_email != [] || $leader_email != ""){

                $forgotten = $this->assignment_model->change_pic_email($this->user_id,$leader_email,$assistant_email,$company_name,$form_data['assignment_code']);
            }

            echo $result;

        }else{
            $q2 = $this->db->query("SELECT MAX(SUBSTRING(assignment_id, -6)) AS A_ID FROM payroll_assignment");

            if ($q2->num_rows() > 0){
                $generateID = $q2->result_array();
                $generateID = (int)$generateID[0]["A_ID"]+1;
            }
            $generateID = date("Y").'-'.str_pad($generateID,6,0,STR_PAD_LEFT);

            $data = array(
                'assignment_id'                 => $generateID,
                'client_id'                     => $form_data['client_id'],
                'client_name'                   => strtoupper($company_name),
                'firm_id'                       => $form_data['firm_id'],
                'type_of_job'                   => $form_data['jobs_list'],
                'PIC'                           => json_encode($pic),
                'FYE'                           => $fye,
                'account_received'              => $account_received,
                'due_date'                      => $due_date,
                'remark'                        => $form_data['assignment_remark'],
                'create_on'                     => date("Y-m-d"),
                'expected_completion_date'      => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['Expected_Completion'])))
            );

            $result = $this->assignment_model->submit_assignment($data, $form_data['assignment_id']);

            $forgotten = $this->assignment_model->new_assignment_email($this->user_id,$leader_info,$assistant_info,$company_name,$generateID);

            echo $result;
        }
    }

    public function save_completed_assignment(){

        $this->session->set_userdata("tab_active", "complete_assignment");

        $form_data = $this->input->post();
        $pbt_lbt = str_replace([',',')'], '',$form_data['PBT_LBT']);
        $pbt_lbt = str_replace('(', "-", $pbt_lbt);
        $data = array(
            'payroll_assignment_id' => $form_data['payroll_assignment_id'],
            'firm_id'               => $form_data['firm_id'],
            'client_id'             => $form_data['client_id'],
            'client_name'           => strtoupper($form_data['client_name']),
            'FYE'                   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['FYE']))),
            'report_date'           => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['report_date']))),
            'partner'               => $form_data['CA_partner_name'],
            'revenue'               => str_replace(',', '',$form_data['revenue']),
            'asset'                 => str_replace(',', '',$form_data['asset']),
            'PBT_LBT'               => $pbt_lbt,
            'functional_currency'   => $form_data['functional_currency'],
            'subsidiary'            => $form_data['subsidiary'],
            'holding_company'       => $form_data['holding_company'],
            'normal_audit'          => $form_data['normal_audit'],
            'principal_activity'    => $form_data['principal_activity'],
            'audit_fee'             => str_replace(',', '',$form_data['audit_fee'])
        );

        $result = $this->assignment_model->save_completed_assignment($data,$form_data['id']);

        $signed = '1';

        $result = $this->assignment_model->updt_status($form_data['status_id'], $signed, $form_data['payroll_assignment_id']);

        echo $result;
    }

    public function get_final_year_end(){

        $form_data = $this->input->post();

        $client_id = $form_data['client_id'];

        $result = $this->assignment_model->get_final_year_end($client_id);

        echo json_encode($result);
    }

    public function CA_filter(){

        $form_data = $this->input->post();

        $partner = $form_data['partner'];

        if($form_data['from']==""){
            $from = "";
        }
        else{
            $from = date('Y-m-d', strtotime(str_replace('/', '-', $form_data['from'])));
        }
        if($form_data['to']==""){
            $to = "";
        }
        else{
            $to = date('Y-m-d', strtotime(str_replace('/', '-',$form_data['to'])));
        }

        if(!$this->data['Admin']) {
            $result = $this->assignment_model->CA_filter2($partner,$from,$to,$this->user_id);
        }else{
            $result = $this->assignment_model->CA_filter($partner,$from,$to);
        }
    
        echo json_encode($result);
    }

    public function SA_filter(){

        $form_data = $this->input->post();

        $partner = $form_data['partner'];

        if($form_data['from']==""){
            $from = "";
        }
        else{
            $from = date('Y-m-d', strtotime(str_replace('/', '-', $form_data['from'])));
        }
        if($form_data['to']==""){
            $to = "";
        }
        else{
            $to = date('Y-m-d', strtotime(str_replace('/', '-',$form_data['to'])));
        }

        if(!$this->data['Admin']) {
            $result = $this->assignment_model->SA_filter2($partner,$from,$to,$this->user_id);
        }else{
            $result = $this->assignment_model->SA_filter($partner,$from,$to);
        }

        echo json_encode($result);
    }

    public function A_filter(){

        $form_data = $this->input->post();

        $partner = $form_data['partner'];

        // if(!$this->data['Admin'] && !$this->data['Manager']) {
        if(!$this->data['Admin']) {
            $result = $this->assignment_model->A_filter2($partner,$this->user_id);
        }else{
            $result = $this->assignment_model->A_filter($partner);
        }

        echo json_encode($result);
    }

    public function delete_assignment(){
        
        $this->session->set_userdata("tab_active", "delete_assignment");

        $form_data = $this->input->post();

        $result = $this->assignment_model->delete_assignment($form_data['assignment_id']);

        echo $result;
    }

    public function edit_assignment(){
        
        $this->session->set_userdata("tab_active", "edit_assignment");

        $form_data = $this->input->post();

        $result = $this->assignment_model->get_selected_assignment($form_data['edit_assignment']);

        echo $result;
    }

    public function updt_status(){
        
        $this->session->set_userdata("tab_active", "update_assignment");

        $form_data = $this->input->post();

        $signed = '0';

        $result = $this->assignment_model->updt_status($form_data['status_id'], $signed, $form_data['assignment_id']);

        echo $result;
    }

    public function check_signed_assignment(){

        $form_data = $this->input->post();

        $result = $this->assignment_model->check_signed_assignment($form_data['assignment_id']);

        echo json_encode($result);
    }

    public function check_expected_completion_date(){

        $form_data = $this->input->post();

        $result = $this->assignment_model->check_expected_completion_date($form_data['assignment_id']);

        echo json_encode($result);
    }

    public function submit_expected_completion_date(){

        $form_data = $this->input->post();

        $result = $this->assignment_model->submit_expected_completion_date($form_data['assignment_id'], date('Y-m-d', strtotime(str_replace('/', '-', $form_data['expected_completion_date']))));

        echo json_encode($result);
    }

    public function check_assignment_deadline(){

        $form_data = $this->input->post();

        $id = $this->user_id;

        $result = $this->assignment_model->check_assignment_deadline(date('Y-m-d', strtotime(str_replace('/', '-', $form_data['date']))),$id);

        echo json_encode($result);
    }

    public function check_assignment_remain_day(){

        $form_data = $this->input->post();

        $id = $this->user_id;

        $result = $this->assignment_model->check_assignment_remain_day(date('Y-m-d', strtotime(str_replace('/', '-', $form_data['date']))));

        echo json_encode($result);
    }

    public function check_assignment_remain_day_email(){

        $form_data = $this->input->post();

        $result = $this->assignment_model->check_assignment_remain_day_email($form_data['id'],date("Y-m-d"));

        echo json_encode($result);
    }

    public function check_missed_reason(){

        $form_data = $this->input->post();

        $result = $this->assignment_model->check_missed_reason($form_data['id'],date("Y-m-d"));

        echo json_encode($result);
    }

    public function show_log(){

        $form_data = $this->input->post();

        $result = $this->assignment_model->show_log($form_data['assignment_id']);

        echo json_encode($result);

    }

    public function change_ExpectedCompletionDate(){

        $form_data = $this->input->post();

        $id = $this->user_id;

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");
        $userName = $q1->result();
        $userName = $userName[0]->name;

        $data = array(
            'assignment_id' => $form_data['assignment_id'],
            'date' => date("Y-m-d H:i:s"),
            'assignment_log' => "".$userName." Changed Expected Completion Date To ".date('Y-m-d', strtotime(str_replace('/', '-', $form_data['expected_completion_date'])))." With Reason: ".$form_data['reason']
        );

        $result = $this->assignment_model->submit_log($form_data['assignment_id'],$data);

        echo json_encode($result);
    }

    public function set_ExpectedCompletionDate(){

        $form_data = $this->input->post();

        $id = $this->user_id;

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");
        $userName = $q1->result();
        $userName = $userName[0]->name;

        $data = array(
            'assignment_id' => $form_data['assignment_id'],
            'date' => date("Y-m-d H:i:s"),
            'assignment_log' => "".$userName." Set Expected Completion Date To ".date('Y-m-d', strtotime(str_replace('/', '-', $form_data['expected_completion_date'])))." With Reason: ".$form_data['reason']
        );

        $result = $this->assignment_model->submit_log($form_data['assignment_id'],$data);

        echo json_encode($result);
    }

    public function add_ExpectedCompletionDate(){

        $form_data = $this->input->post();

        $id = $this->user_id;

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");
        $userName = $q1->result();
        $userName = $userName[0]->name;

        $data = array(
            'assignment_id' => $form_data['assignment_id'],
            'date' => date("Y-m-d H:i:s"),
            'assignment_log' => "".$userName." Add Expected Completion Date To ".date('Y-m-d', strtotime(str_replace('/', '-', $form_data['expected_completion_date'])))
        );

        $result = $this->assignment_model->submit_log($form_data['assignment_id'],$data);

        echo json_encode($result);
    }

    public function missed_ExpectedCompletionDate(){

        $form_data = $this->input->post();

        $id = $this->user_id;

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");
        $userName = $q1->result();
        $userName = $userName[0]->name;

        $data = array(
            'assignment_id' => $form_data['assignment_id'],
            'date' => date("Y-m-d H:i:s"),
            'assignment_log' => "".$userName." Missed Expected Completion Date With Reason: ".$form_data['reason']
        );

        $result = $this->assignment_model->submit_log($form_data['assignment_id'],$data);

        echo json_encode($result);
    }

    public function email_notification_log(){

        $form_data = $this->input->post();

        $id = $this->user_id;

        $data = array(
            'assignment_id' => $form_data['assignment_id'],
            'date' => date("Y-m-d H:i:s"),
            'assignment_log' => " Email Notification Sent: Expected Completion Date is less than 3 days"
        );

        $result = $this->assignment_model->submit_log($form_data['assignment_id'],$data);

        echo json_encode($result);
    }

    public function change_status_log(){

        $form_data = $this->input->post();

        $id = $this->user_id;
        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");
        $userName = $q1->result();
        $userName = $userName[0]->name;

        $q2 = $this->db->query("SELECT assignment_status FROM payroll_assignment_status WHERE id = '".$form_data['status_id']."'");
        $status = $q2->result();
        $status = $status[0]->assignment_status;

        $data = array(
            'assignment_id' => $form_data['assignment_id'],
            'date' => date("Y-m-d H:i:s"),
            'assignment_log' => "".$userName." Changed Status: ".$status
        );

        $result = $this->assignment_model->submit_log($form_data['assignment_id'],$data);

        echo json_encode($result);
    }

    public function get_users_list(){

        $data = $this->assignment_model->get_users_list();

        echo json_encode($data);
        // echo json_encode($data);

        // return json_encode($data);
    }

    public function email_notification_email(){

        $form_data = $this->input->post();

        $pic = $form_data['pic'];

        $manager_name = json_decode($pic)->manager;
        $leader_name  = json_decode($pic)->leader;
        $assistant    = json_decode($pic)->assistant;

        $query1 = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$leader_name."' ");
        $leader_info = $query1->result();

        $query2 = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$manager_name."' ");
        $manager_id = $query2->result();

        for($i = 0; $i < sizeof($assistant); $i++){
            $query3 = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$assistant[$i]."' ");
            $assistant_info[$i] = $query3->result();
        }

        $forgotten = $this->assignment_model->assignment_deadline_email($manager_id,$leader_info,$assistant_info,$form_data['client_name'],$form_data['assignment_id']);

        // echo json_encode($data);
    }

    public function generateCAExcel(){
        $spreadsheet = new Spreadsheet();

        $form_data = $this->input->post();
        // $spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold( true );
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/CA_format.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $partner = $form_data['partner'];

        if($form_data['to']==""){
            $to = "";
        }
        else{
            $to = date('Y-m-d', strtotime(str_replace('/', '-',$form_data['to'])));
        }

        if($form_data['from']==""){
            $from = "";
        }
        else{
            $from = date('Y-m-d', strtotime(str_replace('/', '-',$form_data['from'])));
        }

        // IF ONLY FILTER BY PARTNER
        if($partner != "0" && ($from == "" && $to == "")){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' AND payroll_assignment.status = 10 ");
        }
        // IF FILTER BY PARTNER AND FROM&TO DATE
        else if($partner != "0" && ($from != "" && $to != "")){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' AND payroll_assignment.account_received <='".$to."' AND payroll_assignment.account_received >='".$from."'AND payroll_assignment.status = 10 ");
        }
        // IF ONLY FILTER BY FROM&TO DATE
        else if ($partner == "0" && ($from != "" && $to != "")){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.account_received <='".$to."' AND payroll_assignment.account_received >='".$from."'AND payroll_assignment.status = 10");
        }
        // IF ONLY FILTER BY FROM DATE
        else if ($partner == "0" && $from != "" && $to == ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.account_received >='".$from."'AND payroll_assignment.status = 10 ");
        }
        // IF ONLY FILTER BY TO DATE
        else if ($partner == "0" && $from == "" && $to != ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.account_received <='".$to."' AND payroll_assignment.status = 10 ");
        }
        // IF FILTER BY TO DATE & PARTNER
        else if ($partner != "0" && $from == "" && $to != ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' AND payroll_assignment.account_received <='".$to."' AND payroll_assignment.status = 10 ");
        }
        // IF FILTER BY FROM DATE & PARTNER
        else if ($partner != "0" && $from != "" && $to == ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' AND payroll_assignment.account_received >='".$from."'AND payroll_assignment.status = 10 ");
        }
        else{
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.status = 10 ");

        }

        $i = 2;

        foreach($q->result() as $data){

                foreach( range('A', 'N') as $v ) {
                    switch( $v ) {
                        case 'A': {
                            $value = strtoupper($data->client_name);
                            break;
                        }
                        case 'B': {
                            $value = $data->name;
                            break;
                        }
                        case 'C': {
                            $value = $data->FYE;
                            break;
                        }
                        case 'D': {
                            $value = $data->report_date;
                            break;
                        }
                        case 'E': {
                            $value = strtoupper($data->partner);
                            break;
                        }
                        case 'F': {
                            $value = $data->revenue;
                            break;
                        }
                        case 'G': {
                            $value = $data->asset;
                            break;
                        }
                        case 'H': {
                            $value = $data->PBT_LBT;
                            break;
                        }
                        case 'I': {
                            $value = $data->functional_currency;
                            break;
                        }
                        case 'J': {
                            $value = $data->subsidiary;
                            if($value == 1){
                                $value = 'Yes';
                                break;
                            }else{
                                $value = 'No';
                                break;
                            }
                        }
                        case 'K': {
                            $value = $data->holding_company;
                            if($value == 1){
                                $value = 'Yes';
                                break;
                            }else{
                                $value = 'No';
                                break;
                            }
                        }
                        case 'L': {
                            $value = $data->normal_audit;
                            if($value == 1){
                                $value = 'Yes';
                                break;
                            }else{
                                $value = 'No';
                                break;
                            }
                        }
                        case 'M': {
                            $value = $data->principal_activity;
                            break;
                        }
                        case 'N': {
                            $value = $data->audit_fee;
                            break;
                        }
                    }
                    $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
                }
                $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/completed_assignment/completed_assignment.xlsx';
        $response = $filename;

        $writer->save($filename);
        echo $response;

    }


    public function generateCAExcel2(){
        $spreadsheet = new Spreadsheet();

        $form_data = $this->input->post();
        // $spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold( true );
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/CA_format.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $id = $this->user_id;
        $partner = $form_data['partner'];

        $query1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $query1->result();
        $userName = json_encode($userName[0]->name);

        if($form_data['to']==""){
            $to = "";
        }
        else{
            $to = date('Y-m-d', strtotime(str_replace('/', '-',$form_data['to'])));
        }

        if($form_data['from']==""){
            $from = "";
        }
        else{
            $from = date('Y-m-d', strtotime(str_replace('/', '-',$form_data['from'])));
        }

        // IF ONLY FILTER BY PARTNER
        if($partner != "0" && ($from == "" && $to == "")){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.status = 10 AND payroll_assignment.PIC LIKE '%".$userName."%'");
        }
        // IF FILTER BY PARTNER AND FROM&TO DATE
        else if($partner != "0" && ($from != "" && $to != "")){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' AND payroll_assignment.account_received <='".$to."' AND payroll_assignment.account_received >='".$from."'AND payroll_assignment.status = 10 AND payroll_assignment.PIC LIKE '%".$userName."%'");
        }
        // IF ONLY FILTER BY FROM&TO DATE
        else if ($partner == "0" && ($from != "" && $to != "")){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.account_received <='".$to."' AND payroll_assignment.account_received >='".$from."'AND payroll_assignment.status = 10 AND payroll_assignment.PIC LIKE '%".$userName."%'");
        }
        // IF ONLY FILTER BY FROM DATE
        else if ($partner == "0" && $from != "" && $to == ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.account_received >='".$from."'AND payroll_assignment.status = 10 AND payroll_assignment.PIC LIKE '%".$userName."%' ");
        }
        // IF ONLY FILTER BY TO DATE
        else if ($partner == "0" && $from == "" && $to != ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.account_received <='".$to."' AND payroll_assignment.status = 10 AND payroll_assignment.PIC LIKE '%".$userName."%' ");
        }
        // IF FILTER BY TO DATE & PARTNER
        else if ($partner != "0" && $from == "" && $to != ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' AND payroll_assignment.account_received <='".$to."' AND payroll_assignment.status = 10 AND payroll_assignment.PIC LIKE '%".$userName."%' ");
        }
        // IF FILTER BY FROM DATE & PARTNER
        else if ($partner != "0" && $from != "" && $to == ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' AND payroll_assignment.account_received >='".$from."'AND payroll_assignment.status = 10 AND payroll_assignment.PIC LIKE '%".$userName."%' ");
        }
        else{
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.status = 10
                                        AND payroll_assignment.PIC LIKE '%".$userName."%' ");

        }

        $i = 2;

        foreach($q->result() as $data){

                foreach( range('A', 'N') as $v ) {
                    switch( $v ) {
                        case 'A': {
                            $value = strtoupper($data->client_name);
                            break;
                        }
                        case 'B': {
                            $value = $data->name;
                            break;
                        }
                        case 'C': {
                            $value = $data->FYE;
                            break;
                        }
                        case 'D': {
                            $value = $data->report_date;
                            break;
                        }
                        case 'E': {
                            $value = strtoupper($data->partner);
                            break;
                        }
                        case 'F': {
                            $value = $data->revenue;
                            break;
                        }
                        case 'G': {
                            $value = $data->asset;
                            break;
                        }
                        case 'H': {
                            $value = $data->PBT_LBT;
                            break;
                        }
                        case 'I': {
                            $value = $data->functional_currency;
                            break;
                        }
                        case 'J': {
                            $value = $data->subsidiary;
                            if($value == 1){
                                $value = 'Yes';
                                break;
                            }else{
                                $value = 'No';
                                break;
                            }
                        }
                        case 'K': {
                            $value = $data->holding_company;
                            if($value == 1){
                                $value = 'Yes';
                                break;
                            }else{
                                $value = 'No';
                                break;
                            }
                        }
                        case 'L': {
                            $value = $data->normal_audit;
                            if($value == 1){
                                $value = 'Yes';
                                break;
                            }else{
                                $value = 'No';
                                break;
                            }
                        }
                        case 'M': {
                            $value = $data->principal_activity;
                            break;
                        }
                        case 'N': {
                            $value = $data->audit_fee;
                            break;
                        }
                    }
                    $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
                }
                $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/completed_assignment/completed_assignment.xlsx';
        $response = $filename;

        $writer->save($filename);
        echo $response;

    }


    public function generateSAExcel(){
        $spreadsheet = new Spreadsheet();

        $form_data = $this->input->post();
        // $spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold( true );
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/CA_format.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $partner = $form_data['partner'];

        if($form_data['to']==""){
            $to = "";
        }
        else{
            $to = date('Y-m-d', strtotime(str_replace('/', '-',$form_data['to'])));
        }

        if($form_data['from']==""){
            $from = "";
        }
        else{
            $from = date('Y-m-d', strtotime(str_replace('/', '-',$form_data['from'])));
        }

        // IF ONLY FILTER BY PARTNER
        if($partner != "0" && ($from == "" && $to == "")){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1' ");
        }
        // IF FILTER BY PARTNER AND FROM&TO DATE
        else if($partner != "0" && ($from != "" && $to != "")){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1' ");
        }
        // IF ONLY FILTER BY FROM&TO DATE
        else if ($partner == "0" && ($from != "" && $to != "")){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1'");
        }
        // IF ONLY FILTER BY FROM DATE
        else if ($partner == "0" && $from != "" && $to == ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1' ");
        }
        // IF ONLY FILTER BY TO DATE
        else if ($partner == "0" && $from == "" && $to != ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1' ");
        }
        // IF FILTER BY TO DATE & PARTNER
        else if ($partner != "0" && $from == "" && $to != ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1' ");
        }
        // IF FILTER BY FROM DATE & PARTNER
        else if ($partner != "0" && $from != "" && $to == ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1' ");
        }
        else{
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.status != 10 
                                        AND payroll_assignment.signed = '1' ");

        }

        $i = 2;

        foreach($q->result() as $data){

                foreach( range('A', 'N') as $v ) {
                    switch( $v ) {
                        case 'A': {
                            $value = strtoupper($data->client_name);
                            break;
                        }
                        case 'B': {
                            $value = $data->name;
                            break;
                        }
                        case 'C': {
                            $value = $data->FYE;
                            break;
                        }
                        case 'D': {
                            $value = $data->report_date;
                            break;
                        }
                        case 'E': {
                            $value = strtoupper($data->partner);
                            break;
                        }
                        case 'F': {
                            $value = $data->revenue;
                            break;
                        }
                        case 'G': {
                            $value = $data->asset;
                            break;
                        }
                        case 'H': {
                            $value = $data->PBT_LBT;
                            break;
                        }
                        case 'I': {
                            $value = $data->functional_currency;
                            break;
                        }
                        case 'J': {
                            $value = $data->subsidiary;
                            if($value == 1){
                                $value = 'Yes';
                                break;
                            }else{
                                $value = 'No';
                                break;
                            }
                        }
                        case 'K': {
                            $value = $data->holding_company;
                            if($value == 1){
                                $value = 'Yes';
                                break;
                            }else{
                                $value = 'No';
                                break;
                            }
                        }
                        case 'L': {
                            $value = $data->normal_audit;
                            if($value == 1){
                                $value = 'Yes';
                                break;
                            }else{
                                $value = 'No';
                                break;
                            }
                        }
                        case 'M': {
                            $value = $data->principal_activity;
                            break;
                        }
                        case 'N': {
                            $value = $data->audit_fee;
                            break;
                        }
                    }
                    $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
                }
                $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/signed_assignment/signed_assignment.xlsx';
        $response = $filename;

        $writer->save($filename);
        echo $response;

    }


    public function generateSAExcel2(){
        $spreadsheet = new Spreadsheet();

        $form_data = $this->input->post();
        // $spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold( true );
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/CA_format.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $id = $this->user_id;
        $partner = $form_data['partner'];

        $query1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $query1->result();
        $userName = json_encode($userName[0]->name);

        if($form_data['to']==""){
            $to = "";
        }
        else{
            $to = date('Y-m-d', strtotime(str_replace('/', '-',$form_data['to'])));
        }

        if($form_data['from']==""){
            $from = "";
        }
        else{
            $from = date('Y-m-d', strtotime(str_replace('/', '-',$form_data['from'])));
        }

        // IF ONLY FILTER BY PARTNER
        if($partner != "0" && ($from == "" && $to == "")){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1' 
                                        AND payroll_assignment.PIC LIKE '%".$userName."%'");
        }
        // IF FILTER BY PARTNER AND FROM&TO DATE
        else if($partner != "0" && ($from != "" && $to != "")){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1' 
                                        AND payroll_assignment.PIC LIKE '%".$userName."%'");
        }
        // IF ONLY FILTER BY FROM&TO DATE
        else if ($partner == "0" && ($from != "" && $to != "")){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1'
                                        AND payroll_assignment.PIC LIKE '%".$userName."%' ");
        }
        // IF ONLY FILTER BY FROM DATE
        else if ($partner == "0" && $from != "" && $to == ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1' 
                                        AND payroll_assignment.PIC LIKE '%".$userName."%'");
        }
        // IF ONLY FILTER BY TO DATE
        else if ($partner == "0" && $from == "" && $to != ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1' 
                                        AND payroll_assignment.PIC LIKE '%".$userName."%'");
        }
        // IF FILTER BY TO DATE & PARTNER
        else if ($partner != "0" && $from == "" && $to != ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1' 
                                        AND payroll_assignment.PIC LIKE '%".$userName."%'");
        }
        // IF FILTER BY FROM DATE & PARTNER
        else if ($partner != "0" && $from != "" && $to == ""){
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = '1' 
                                        AND payroll_assignment.PIC LIKE '%".$userName."%'");
        }
        else{
            $q = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        WHERE payroll_assignment.status != 10 
                                        AND payroll_assignment.signed = '1' 
                                        AND payroll_assignment.PIC LIKE '%".$userName."%'");

        }

        $i = 2;

        foreach($q->result() as $data){

                foreach( range('A', 'N') as $v ) {
                    switch( $v ) {
                        case 'A': {
                            $value = strtoupper($data->client_name);
                            break;
                        }
                        case 'B': {
                            $value = $data->name;
                            break;
                        }
                        case 'C': {
                            $value = $data->FYE;
                            break;
                        }
                        case 'D': {
                            $value = $data->report_date;
                            break;
                        }
                        case 'E': {
                            $value = strtoupper($data->partner);
                            break;
                        }
                        case 'F': {
                            $value = $data->revenue;
                            break;
                        }
                        case 'G': {
                            $value = $data->asset;
                            break;
                        }
                        case 'H': {
                            $value = $data->PBT_LBT;
                            break;
                        }
                        case 'I': {
                            $value = $data->functional_currency;
                            break;
                        }
                        case 'J': {
                            $value = $data->subsidiary;
                            if($value == 1){
                                $value = 'Yes';
                                break;
                            }else{
                                $value = 'No';
                                break;
                            }
                        }
                        case 'K': {
                            $value = $data->holding_company;
                            if($value == 1){
                                $value = 'Yes';
                                break;
                            }else{
                                $value = 'No';
                                break;
                            }
                        }
                        case 'L': {
                            $value = $data->normal_audit;
                            if($value == 1){
                                $value = 'Yes';
                                break;
                            }else{
                                $value = 'No';
                                break;
                            }
                        }
                        case 'M': {
                            $value = $data->principal_activity;
                            break;
                        }
                        case 'N': {
                            $value = $data->audit_fee;
                            break;
                        }
                    }
                    $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
                }
                $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/signed_assignment/signed_assignment.xlsx';
        $response = $filename;

        $writer->save($filename);
        echo $response;

    }


    public function generateAExcel(){
        $spreadsheet = new Spreadsheet();
        $form_data = $this->input->post();
        // $spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold( true );
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/A_format.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $partner = $form_data['partner'];

        if($partner!="0"){
            $q = $this->db->query(" SELECT payroll_assignment.*,firm.name,payroll_assignment_jobs.type_of_job AS job,payroll_assignment_status.assignment_status FROM payroll_assignment 
                                    LEFT JOIN firm ON payroll_assignment.firm_id = firm.id
                                    LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                    LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                    WHERE payroll_assignment.status != 10 
                                    AND payroll_assignment.PIC LIKE '%".$partner."%' 
                                    ORDER BY payroll_assignment.client_name ASC ");
        }else{
            $q = $this->db->query(" SELECT payroll_assignment.*,firm.name,payroll_assignment_jobs.type_of_job AS job,payroll_assignment_status.assignment_status FROM payroll_assignment 
                                    LEFT JOIN firm ON payroll_assignment.firm_id = firm.id
                                    LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                    LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                    WHERE payroll_assignment.status != 10 
                                    ORDER BY payroll_assignment.client_name ASC");
        }

        $i = 2;

        foreach($q->result() as $data){
                foreach( range('A', 'L') as $v ) {
                    switch( $v ) {
                        case 'A': {
                            $value = strtoupper($data->client_name);
                            break;
                        }
                        case 'B': {
                            $value = $data->name;
                            break;
                        }
                        case 'C': {
                            $value = $data->job;
                            break;
                        }
                        case 'D': {
                            $value = strtoupper(json_decode($data->PIC)->partner);
                            break;
                        }
                        case 'E': {
                            $value = strtoupper(json_decode($data->PIC)->manager);
                            break;
                        }
                        case 'F': {
                           $value = strtoupper(json_decode($data->PIC)->leader);
                            break;
                        }
                        case 'G': {
                            $assistant = json_decode($data->PIC)->assistant;
                            $value = strtoupper(implode( ", ", $assistant));
                            break;
                        }
                        case 'H': {
                            $value = $data->FYE;
                            break;
                        }
                        case 'I': {
                            $value = $data->account_received;
                            break;
                        }
                        case 'J': {
                            $value = $data->due_date;
                            break;
                        }
                        case 'K': {
                            $value = $data->assignment_status;
                            break;
                        }
                        case 'L': {
                            $value = $data->remark;
                            break;
                        }
                    }
                    $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
                }
                $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/assignment/assignment.xlsx';
        $response = $filename;

        $writer->save($filename);
        echo $response;

    }


    public function generateAExcel2(){
        $spreadsheet = new Spreadsheet();
        $form_data = $this->input->post();
        // $spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold( true );
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/A_format.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $id = $this->user_id;
        $partner = $form_data['partner'];

        $query1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $query1->result();
        $userName = json_encode($userName[0]->name);

        if($partner!="0"){
            $q = $this->db->query(" SELECT payroll_assignment.*,firm.name,payroll_assignment_jobs.type_of_job AS job,payroll_assignment_status.assignment_status FROM payroll_assignment 
                                    LEFT JOIN firm ON payroll_assignment.firm_id = firm.id
                                    LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                    LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                    WHERE payroll_assignment.status != 10 
                                    AND payroll_assignment.PIC LIKE '%".$partner."%'
                                    AND payroll_assignment.PIC LIKE '%".$userName."%'
                                    ORDER BY payroll_assignment.client_name ASC ");
        }else{
            $q = $this->db->query(" SELECT payroll_assignment.*,firm.name,payroll_assignment_jobs.type_of_job AS job,payroll_assignment_status.assignment_status FROM payroll_assignment 
                                    LEFT JOIN firm ON payroll_assignment.firm_id = firm.id
                                    LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                    LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                    WHERE payroll_assignment.status != 10
                                    AND payroll_assignment.PIC LIKE '%".$userName."%' 
                                    ORDER BY payroll_assignment.client_name ASC");
        }

        $i = 2;

        foreach($q->result() as $data){
                foreach( range('A', 'L') as $v ) {
                    switch( $v ) {
                        case 'A': {
                            $value = strtoupper($data->client_name);
                            break;
                        }
                        case 'B': {
                            $value = $data->name;
                            break;
                        }
                        case 'C': {
                            $value = $data->job;
                            break;
                        }
                        case 'D': {
                            $value = strtoupper(json_decode($data->PIC)->partner);
                            break;
                        }
                        case 'E': {
                            $value = strtoupper(json_decode($data->PIC)->manager);
                            break;
                        }
                        case 'F': {
                           $value = strtoupper(json_decode($data->PIC)->leader);
                            break;
                        }
                        case 'G': {
                            $assistant = json_decode($data->PIC)->assistant;
                            $value = strtoupper(implode( ", ", $assistant));
                            break;
                        }
                        case 'H': {
                            $value = $data->FYE;
                            break;
                        }
                        case 'I': {
                            $value = $data->account_received;
                            break;
                        }
                        case 'J': {
                            $value = $data->due_date;
                            break;
                        }
                        case 'K': {
                            $value = $data->assignment_status;
                            break;
                        }
                        case 'L': {
                            $value = $data->remark;
                            break;
                        }
                    }
                    $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
                }
                $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/assignment/assignment.xlsx';
        $response = $filename;

        $writer->save($filename);
        echo $response;

    }


    public function generateLogExcel(){
        $spreadsheet = new Spreadsheet();
        $form_data = $this->input->post();
        // $spreadsheet->getActiveSheet()->getStyle('A1:N1')->getFont()->setBold( true );
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/Log_format.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $id = $form_data['assignment_id'];

        $q = $this->db->query(" SELECT * FROM payroll_assignment_log WHERE payroll_assignment_log.assignment_id = '".$id."' ORDER BY payroll_assignment_log.date");

        $i = 2;

        foreach($q->result() as $data){
                foreach( range('A', 'C') as $v ) {
                    switch( $v ) {
                        case 'A': {
                            $value = $data->assignment_id;
                            break;
                        }
                        case 'B': {
                            $value = $data->date;
                            break;
                        }
                        case 'C': {
                            $value = $data->assignment_log;
                            break;
                        }
                    }
                    $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
                }
                $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/assignment_log/assignment_log.xlsx';
        $response = $filename;

        $writer->save($filename);
        echo $response;

    }

    public function get_new_assignment_code(){
        $q2 = $this->db->query("SELECT MAX(SUBSTRING(assignment_id, -6)) AS A_ID FROM payroll_assignment");

        if ($q2->num_rows() > 0){
            $generateID = $q2->result_array();
            $generateID = (int)$generateID[0]["A_ID"]+1;
        }
        $generateID = date("Y").'-'.str_pad($generateID,6,0,STR_PAD_LEFT);

        echo $generateID;
    }
}