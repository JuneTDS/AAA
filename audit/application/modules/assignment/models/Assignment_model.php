<?php defined('BASEPATH') OR exit('No direct script access allowed');
include 'application/js/random_alphanumeric_generator.php';

class Assignment_model extends CI_Model
{
	public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_firm_dropdown_list(){
        $q = $this->db->query("SELECT firm.* FROM firm left join user_firm on user_firm.firm_id = firm.id AND user_firm.user_id = '".$this->session->userdata('user_id')."' WHERE user_firm.user_id = '".$this->session->userdata('user_id')."'");

        $firms = array();
        $firms[''] = '-- Please Select the Firm --';

        foreach($q->result() as $firm){
            $firms[$firm->id] = $firm->name; 
        }

        return $firms;
    }

    public function get_client_dropdown_list(){
        $q = $this->db->query("SELECT * FROM client");

        $clients = array();
        $clients[''] = '-- Please Select the Client --';

        foreach($q->result() as $client){
            $clients[$client->company_code] = $client->company_name; 
        }

        return $clients;
    }

    public function get_status_dropdown_list(){
        $q = $this->db->query("SELECT * FROM payroll_assignment_status ORDER BY list_order");

        $status = array();
        $status[''] = "-- Status --";

        foreach($q->result() as $client){
            $status[$client->id] = $client->assignment_status; 
        }

        return $status;
    }

    public function get_status_dropdown_list2(){
        $q = $this->db->query("SELECT * FROM payroll_assignment_status WHERE id IN(7,8,9,10,11) ORDER BY list_order");

        $status = array();
        $status[''] = "-- Status --";

        foreach($q->result() as $client){
            $status[$client->id] = $client->assignment_status; 
        }

        return $status;
    }

    public function get_completed_list(){

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                WHERE payroll_assignment.status = '10' AND payroll_assignment.deleted = '0'");

        return $q->result();
    }

    public function get_user_completed_list($id){

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                WHERE payroll_assignment.status = '10' 
                                AND payroll_assignment.deleted = '0'
                                AND payroll_assignment.PIC like '%".$userName."%'");

        return $q->result();
    }

    public function get_signed_list(){

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                WHERE payroll_assignment.signed = '1' 
                                AND payroll_assignment.status != '10'
                                AND payroll_assignment.deleted = '0'");

        return $q->result();
    }

    public function get_user_signed_list($id){

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                WHERE payroll_assignment.signed = '1' 
                                AND payroll_assignment.status != '10'
                                AND payroll_assignment.deleted = '0'
                                AND payroll_assignment.PIC like '%".$userName."%'");

        return $q->result();
    }

    public function get_assignment_list(){

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                WHERE payroll_assignment.status != '10' AND payroll_assignment.deleted = '0'");
        
        return $q->result();
        // print_r($q->result_array());
    }

    public function get_user_assignment_list($id){

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                WHERE payroll_assignment.status != '10' AND payroll_assignment.deleted = '0' AND payroll_assignment.PIC like '%".$userName."%'");
        return $q->result();
        // print_r($q->result_array());
    }

    // public function get_manager_assignment_list($id){

    //     $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

    //     $userName = $q1->result();
    //     $userName = json_encode($userName[0]->name);

    //     $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
    //                             FROM payroll_assignment 
    //                             LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
    //                             LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
    //                             LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
    //                             LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
    //                             WHERE payroll_assignment.status != '10' AND payroll_assignment.deleted = '0' AND payroll_assignment.PIC like '%".$userName."%'");
    //     return $q->result();
    //     // print_r($q->result_array());
    // }

    public function get_completed_assignment_list(){

        $q = $this->db->query("SELECT payroll_assignment_completed.*,firm.name,client.company_name FROM payroll_assignment_completed INNER JOIN firm ON payroll_assignment_completed.firm_id = firm.id INNER JOIN client ON payroll_assignment_completed.client_id = client.company_code");
        
        return $q->result();
    }

    public function submit_assignment($data,$id){

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_assignment', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_assignment', $data); 
        }

        return $result;
    }

    public function save_completed_assignment($data,$id){

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_assignment_completed', $data); 
        }
        else
        {
            $result = $this->db->insert('payroll_assignment_completed', $data); 
        }

        return $result;
    }

    public function CA_filter($partner,$from,$to){

        // IF ONLY FILTER BY PARTNER
        if($partner != "0" && ($from == "" && $to == "")){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.status = 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF FILTER BY PARTNER AND FROM&TO DATE
        else if($partner != "0" && ($from != "" && $to != "")){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status = 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF ONLY FILTER BY FROM&TO DATE
        else if ($partner == "0" && ($from != "" && $to != "")){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.account_received <= '".$to."' 
                                        AND payroll_assignment.account_received >= '".$from."' 
                                        AND payroll_assignment.status = 10
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF ONLY FILTER BY FROM DATE
        else if ($partner == "0" && $from != "" && $to == ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status = 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF ONLY FILTER BY TO DATE
        else if ($partner == "0" && $from == "" && $to != ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.status = 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF FILTER BY TO DATE & PARTNER
        else if ($partner != "0" && $from == "" && $to != ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.status = 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF FILTER BY FROM DATE & PARTNER
        else if ($partner != "0" && $from != "" && $to == ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status = 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        else{
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.status = 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }

    }

    public function CA_filter2($partner,$from,$to,$id){
        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);
        // IF ONLY FILTER BY PARTNER
        if($partner != "0" && ($from == "" && $to == "")){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."'
                                        AND payroll_assignment.PIC like '%".$userName."%' 
                                        AND payroll_assignment.status = 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF FILTER BY PARTNER AND FROM&TO DATE
        else if($partner != "0" && ($from != "" && $to != "")){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."'
                                        AND payroll_assignment.PIC like '%".$userName."%' 
                                        AND payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status = 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF ONLY FILTER BY FROM&TO DATE
        else if ($partner == "0" && ($from != "" && $to != "")){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.account_received <= '".$to."' 
                                        AND payroll_assignment.account_received >= '".$from."'
                                        AND payroll_assignment.PIC like '%".$userName."%' 
                                        AND payroll_assignment.status = 10
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF ONLY FILTER BY FROM DATE
        else if ($partner == "0" && $from != "" && $to == ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.status = 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF ONLY FILTER BY TO DATE
        else if ($partner == "0" && $from == "" && $to != ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.account_received <='".$to."'
                                        AND payroll_assignment.PIC like '%".$userName."%' 
                                        AND payroll_assignment.status = 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF FILTER BY TO DATE & PARTNER
        else if ($partner != "0" && $from == "" && $to != ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.status = 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF FILTER BY FROM DATE & PARTNER
        else if ($partner != "0" && $from != "" && $to == ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status = 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        else{
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.status = 10 
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }

    }

    public function SA_filter($partner,$from,$to){

        // IF ONLY FILTER BY PARTNER
        if($partner != "0" && ($from == "" && $to == "")){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF FILTER BY PARTNER AND FROM&TO DATE
        else if($partner != "0" && ($from != "" && $to != "")){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF ONLY FILTER BY FROM&TO DATE
        else if ($partner == "0" && ($from != "" && $to != "")){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.account_received <= '".$to."' 
                                        AND payroll_assignment.account_received >= '".$from."' 
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF ONLY FILTER BY FROM DATE
        else if ($partner == "0" && $from != "" && $to == ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF ONLY FILTER BY TO DATE
        else if ($partner == "0" && $from == "" && $to != ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF FILTER BY TO DATE & PARTNER
        else if ($partner != "0" && $from == "" && $to != ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF FILTER BY FROM DATE & PARTNER
        else if ($partner != "0" && $from != "" && $to == ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        else{
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }

    }

    public function SA_filter2($partner,$from,$to,$id){

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);

        // IF ONLY FILTER BY PARTNER
        if($partner != "0" && ($from == "" && $to == "")){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF FILTER BY PARTNER AND FROM&TO DATE
        else if($partner != "0" && ($from != "" && $to != "")){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."'
                                        AND payroll_assignment.PIC like '%".$userName."%' 
                                        AND payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF ONLY FILTER BY FROM&TO DATE
        else if ($partner == "0" && ($from != "" && $to != "")){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.account_received <= '".$to."' 
                                        AND payroll_assignment.account_received >= '".$from."' 
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF ONLY FILTER BY FROM DATE
        else if ($partner == "0" && $from != "" && $to == ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF ONLY FILTER BY TO DATE
        else if ($partner == "0" && $from == "" && $to != ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF FILTER BY TO DATE & PARTNER
        else if ($partner != "0" && $from == "" && $to != ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.account_received <='".$to."' 
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        // IF FILTER BY FROM DATE & PARTNER
        else if ($partner != "0" && $from != "" && $to == ""){
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment_completed.partner = '".$partner."' 
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.account_received >='".$from."'
                                        AND payroll_assignment.status != 10
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        else{
            $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                        LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                        LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.status != 10
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.signed = 1
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }

    }

    public function A_filter($partner){

        if($partner != "0"){
            $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                        LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.PIC like '%".$partner."%' 
                                        AND payroll_assignment.status != 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        else{
             $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                        LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.status != 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }

    }

    public function A_filter2($partner,$id){

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);

        if($partner != "0"){
            $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                        LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.PIC like '%".$partner."%' 
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.status != 10 
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        else{
             $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                        LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.status != 10 
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }

    }
    

    public function get_final_year_end($client_id){

    	if($client_id != '0'){
    		$q = $this->db->query("SELECT * FROM filing WHERE company_code = '".$client_id."' ORDER BY year_end DESC LIMIT 1");

    		foreach($q->result() as $item){
            	$fye = $item->year_end; 
            	return $fye;
        	}
        }
        else{

        	$fye = '0';
        	return $fye;
        }
    }

    public function delete_assignment($assignment_id){

        $this->db->where('id', $assignment_id);

        $result = $this->db->update('payroll_assignment', array('deleted' => 1));

        return $result;
    }

    public function updt_status($status, $signed,$assignment_id){

        $this->db->where('id', $assignment_id);

        if($signed != '0' && $signed == '1'){
            $result = $this->db->update('payroll_assignment', array('status' => $status, 'signed' => $signed));
        }else{
            $result = $this->db->update('payroll_assignment', array('status' => $status));
        }

        return $result;
    }

    public function check_signed_assignment($assignment_id){

        $q = $this->db->query("SELECT payroll_assignment_completed.*,firm.name FROM payroll_assignment_completed
                                LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id 
                                WHERE payroll_assignment_id = '".$assignment_id."'");
        
        // print_r($q);
        return $q->result();
    }

    public function check_expected_completion_date($assignment_id){

        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE id = '".$assignment_id."' ");
        
        // print_r($q);
        return $q->result();
    }

    public function submit_expected_completion_date($assignment_id,$expected_completion_date){

        if($assignment_id != null)
        {
            $this->db->where('id', $assignment_id);

            $q = $this->db->update('payroll_assignment', array('expected_completion_date' => $expected_completion_date)); 
        }
        return $q;
    }

    public function submit_log($assignment_id,$data){

        $q = $this->db->insert('payroll_assignment_log', $data); 
        return $q;

        // $q1 = $this->db->query("SELECT * FROM payroll_assignment_log WHERE assignment_id = '".$assignment_id."'");

        // if ($q1->num_rows()){

        //     $object = $q1->result();
        //     $id  = json_encode($object[0]->assignment_id);
        //     $log = $object[0]->assignment_log;

        //     $data['assignment_log'] = $data['assignment_log'].'<br/>'.$log;
        // }

        // if($id != null){
        //     $this->db->where('assignment_id', $assignment_id);
        //     $q = $this->db->update('payroll_assignment_log', $data); 

        // }else{

        //     $q = $this->db->insert('payroll_assignment_log', $data); 
        // }
        // return $q;
    }

    public function check_assignment_deadline($date,$id){

        $query = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");
        $userName = $query->result();
        $userName = json_encode($userName[0]->name);

        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE payroll_assignment.expected_completion_date = '".$date."' AND payroll_assignment.PIC like '%".$userName."%'");
        
        // print_r($q);
        return $q->result();
    }

    public function check_assignment_remain_day($date){

        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE expected_completion_date = '".$date."' ");
        
        return $q->result();
    }

    public function check_assignment_remain_day_email($id,$date){

        $q = $this->db->query(" SELECT * FROM payroll_assignment_log WHERE payroll_assignment_log.assignment_id = '".$id."' AND payroll_assignment_log.date LIKE '".$date."%' AND payroll_assignment_log.assignment_log LIKE '%Email Notification Sent: Expected Completion Date is less than 3 days%' ");
        // print_r($q);
        return $q->result();
    }

    public function check_missed_reason($id,$date){

        $q = $this->db->query(" SELECT * FROM payroll_assignment_log WHERE payroll_assignment_log.assignment_id = '".$id."' AND payroll_assignment_log.date LIKE '".$date."%' AND payroll_assignment_log.assignment_log LIKE '%Missed Expected Completion Date%' ");
        // print_r($q);
        return $q->result();
    }

    public function show_log($id){

        $q = $this->db->query(" SELECT * FROM payroll_assignment_log WHERE payroll_assignment_log.assignment_id = '".$id."' ORDER BY payroll_assignment_log.date ");
        // print_r($q);
        return $q->result();
    }

    public function get_selected_assignment($id){

        $q = $this->db->query("SELECT * FROM payroll_assignment WHERE id = '".$id."'");
        
        return $q->result();
    }

    public function get_yes_no_list(){
        $list = $this->db->query("SELECT * FROM payroll_choose_carry_forward");

        $choose_carry_forward_list = array();
        $choose_carry_forward_list[''] = 'Please Select';

        foreach($list->result()as $item){
            $choose_carry_forward_list[$item->id] = $item->choose_carry_forward_name;
        }

        return $choose_carry_forward_list;
    }

    public function get_partner_list(){
        $list = $this->db->query("SELECT * FROM payroll_partner WHERE deleted = '0'");

        $partner_list = array();
        $partner_list['0'] = 'All';

        foreach($list->result()as $item){
            $partner_list[strtoupper($item->partner_name)] = strtoupper($item->partner_name);
        }

        return $partner_list;
    }

    public function get_partner_list2(){
        $list = $this->db->query("SELECT * FROM payroll_partner WHERE deleted = '0'");

        $partner_list = array();
        $partner_list['0'] = 'Please Select';

        foreach($list->result()as $item){
            $partner_list[strtoupper($item->partner_name)] = strtoupper($item->partner_name);
        }

        return $partner_list;
    }

    public function get_users_list($user_id){
        $list = $this->db->query(" SELECT *,CONCAT(users.first_name , ' ' , users.last_name) as Name FROM users LEFT JOIN user_firm ON user_firm.user_id = users.id WHERE firm_id in (SELECT firm_id FROM user_firm WHERE user_id = '".$user_id."') AND users.group_id = '3' AND users.user_deleted = '0' ");

        $users_list = array();
        $users_list[''] = 'Please Select';

        foreach($list->result()as $item){
            $users_list[strtoupper($item->Name)] = strtoupper($item->Name);
        }

        return $users_list;
    }

    public function get_manager_list(){
        $list = $this->db->query("SELECT *,CONCAT(first_name , ' ' , last_name) as Name FROM users WHERE group_id = '5' AND user_deleted = '0' ");

        $manager_list = array();
        $manager_list[''] = 'Please Select';

        foreach($list->result()as $item){
            $manager_list[strtoupper($item->Name)] = strtoupper($item->Name);
        }

        return $manager_list;
    }

    public function get_jobs_list(){
        $list = $this->db->query("SELECT * FROM payroll_assignment_jobs");

        $jobs_list = array();
        $jobs_list['0'] = 'Please Select';

        foreach($list->result()as $item){
            $jobs_list[$item->id] = $item->type_of_job;
        }

        return $jobs_list;
    }

    public function get_calender_leaveList(){
        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE deleted = 0 ");

        return $q->result_array();
    }

    public function get_calender_leaveList2($id){
        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);
        
        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE payroll_assignment.PIC like '%".$userName."%' AND deleted = 0 ");

        return $q->result_array();
    }

    public function new_assignment_email($manager_id,$leader_info,$user_id,$client_name,$assigment_code){    

        $user_email="";

        for($i = 0; $i < sizeof($user_id); $i++){
            $user_email .= $user_id[$i][0]->email .",";
        }

        $to_email = $leader_info[0]->email .",". $user_email;

        $to_email = implode(',',array_unique(explode(',', $to_email)));

        $q1 = $this->db->query(" SELECT * FROM users WHERE id = '".$manager_id."' ");
        $query1 = $q1->result();
        $manager_email = $query1[0]->email;

        if(json_encode($manager_email) == '"penny@acumenbizcorp.com.sg"'){
            $manager_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$manager_email);
        }

        $this->load->library('parser');
        $parse_data = array(
            'assignment_code'  => $assigment_code,
            'client_name'      => $client_name,
        );

        $msg = file_get_contents('./application/modules/assignment/email_templates/new_assignment.html');
        $message = $this->parser->parse_string($msg, $parse_data);

        $subject = 'New Assignment - '.$client_name.'';
        $this->sma->send_email($to_email, $subject, $message,"" ,"" ,"" ,$manager_email);
        // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)
    }

    public function change_pic_email($manager_id,$leader_info,$user_id,$client_name,$assigment_code){    

        if($user_id != "" && $leader_info == ""){

            $user_email="";

            for($i = 0 ; $i < sizeof($user_id); $i++){
                $user_email .= $user_id[$i][0]->email .",";
            }

            $to_email = $user_email;

        }else if($user_id == "" && $leader_info != ""){

            $to_email = $leader_info[0]->email;

        }else if($user_id != "" && $leader_info != ""){

            $user_email="";

            for($i = 0; $i < sizeof($user_id); $i++){
                $user_email .= $user_id[$i][0]->email .",";
                echo json_encode( $user_id[$i][0]->email);
            }

            $to_email = $leader_info[0]->email .",". $user_email;

        }

        $to_email = implode(',',array_unique(explode(',', $to_email)));

        $q1 = $this->db->query(" SELECT * FROM users WHERE id = '".$manager_id."' ");
        $query1 = $q1->result();
        $manager_email = $query1[0]->email;

        if(json_encode($manager_email) == '"penny@acumenbizcorp.com.sg"'){
            $manager_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$manager_email);
        }

        $this->load->library('parser');
        $parse_data = array(
            'assignment_code'  => $assigment_code,
            'client_name'      => $client_name,
        );

        $msg = file_get_contents('./application/modules/assignment/email_templates/new_assignment.html');
        $message = $this->parser->parse_string($msg, $parse_data);

        $subject = 'New Assignment - '.$client_name.'';
        $this->sma->send_email($to_email, $subject, $message,"" ,"" ,"" ,$manager_email);
        // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)
    }

    public function assignment_deadline_email($manager_id,$leader_info,$user_id,$client_name,$assigment_code)
    {    
        $user_email="";

        for($i = 0 ; $i < sizeof($user_id); $i++){
            $user_email .= $user_id[$i][0]->email .",";
        }

        $to_email = $leader_info[0]->email .",". $user_email;

        $to_email = implode(',',array_unique(explode(',', $to_email)));

        $this->load->library('parser');
        $parse_data = array(
            'assignment_code'  => $assigment_code,
            'client_name'      => $client_name,
        );

        if(json_encode($manager_id[0]->email) == '"penny@acumenbizcorp.com.sg"'){
            $manager_id[0]->email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$manager_id[0]->email);
        }

        $msg = file_get_contents('./application/modules/assignment/email_templates/completion_date_notification.html');
        $message = $this->parser->parse_string($msg, $parse_data);

        $subject = 'Assignment Completion Date Notification - '.$client_name.'';
        $this->sma->send_email($to_email, $subject, $message,"" ,"" ,"" ,$manager_id[0]->email);
        // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)
    }
}

?>