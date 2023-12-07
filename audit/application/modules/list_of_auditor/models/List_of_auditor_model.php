<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//include 'application/js/random_alphanumeric_generator.php';

class List_of_auditor_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        
    }

    public function get_auditor_list(){
        $q = $this->db->query('SELECT * from audit_auditor_list where deleted = 0');

        if($q->num_rows() > 0)
        {
            return $q->result();
        }
        else
        {
            return false;
        }
    }

    public function get_first_letter()
    {
        $q = $this->db->query('SELECT fl.*, c.company_name, auditor.audit_firm_name, firm.name as firm_name from audit_first_clearance_letter fl
                                LEFT JOIN client c on c.company_code = fl.company_code 
                                LEFT JOIN audit_auditor_list auditor on auditor.id = fl.auditor_id
                                LEFT JOIN firm on fl.firm_id = firm.id
                                WHERE fl.deleted = 0 and fl.moved = 0');

        $data = $q->result();


        foreach ($data as $key => $value) {
            $this->db->select('*');
            $this->db->from('audit_clearance_doc');
            $this->db->where(array('clearance_id' => $value->id));
            $this->db->order_by("created_at asc");

      
            $aq = $this->db->get(); 

        
            $attachment_array = $aq->result_array();

            $data[$key]->attachment = $attachment_array;
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
            if($data[$key]->company_name == "")
            {
               
                $name_q =  $this->db->query('SELECT client_name from transaction_master where company_code = "'.$data[$key]->company_code.'"');
                $potential_client_name = $name_q->result_array();

                $data[$key]->company_name = $this->encryption->decrypt($potential_client_name[0]['client_name'])." (Potential Client)";
            }
        }


        return $data;
    }

    public function get_resignation_letter()
    {
        $q = $this->db->query('SELECT rl.*, c.company_name, auditor.audit_firm_name, firm.name as firm_name from audit_resignation rl
                                LEFT JOIN client c on c.company_code = rl.company_code 
                                LEFT JOIN audit_auditor_list auditor on auditor.id = rl.auditor_id
                                LEFT JOIN firm on rl.firm_id = firm.id');
                                

        $data = $q->result();

        foreach ($data as $key => $value) {
            // $this->db->select('*');
            // $this->db->from('audit_clearance_doc');
            // $this->db->where(array('clearance_id' => $value->id));
            // $this->db->order_by("created_at asc");

      
            // $aq = $this->db->get(); 

        
            // $attachment_array = $aq->result_array();

            // $data[$key]->attachment = $attachment_array;
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
        }
        return $data;
    }

    public function get_edit_resignation_letter($id)
    {
        $q = $this->db->query('SELECT rl.*, c.company_name, auditor.audit_firm_name, firm.name as firm_name from audit_resignation rl
                                LEFT JOIN client c on c.company_code = rl.company_code 
                                LEFT JOIN audit_auditor_list auditor on auditor.id = rl.auditor_id
                                LEFT JOIN firm on rl.firm_id = firm.id where rl.id ='.$id);


        if ($q->num_rows() > 0) 
        {
            $this->session->set_userdata('rl_company_code', $q->result()[0]->company_code);
            $this->session->set_userdata('rl_firm_id', $q->result()[0]->firm_id);
            foreach (($q->result()) as $row) {
                $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }


    public function get_bank_auth(){
        $q = $this->db->query('SELECT ba.*, c.company_name, bank.bank_name from audit_bank_auth ba
                                LEFT JOIN client c on ba.company_code = c.company_code 
                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
                                WHERE ba.deleted = 0 and ba.active = 1');

         return $q->result();
    }

    public function get_bank_auth_deactivate(){
        $q = $this->db->query('SELECT ba.*, c.company_name, bank.bank_name from audit_bank_auth ba
                                LEFT JOIN client c on ba.company_code = c.company_code 
                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
                                WHERE ba.deleted = 0 and ba.active = 0');

         return $q->result();
    }

    

    // public function get_bank_confirm_setting(){
    //     $q = $this->db->query('SELECT bcs.*, u.first_name, u.last_name from audit_bank_confirm_setting bcs
    //                             LEFT JOIN users u on bcs.pic_id = u.id 
    //                             WHERE bcs.deleted = 0');

    //      return $q->result();
    // }

    public function get_bank_confirm(){
        $q = $this->db->query('SELECT bc.*, c.company_name, bank.bank_name, f.name as firm_name from audit_bank_confirm bc, audit_bank_auth ba
                                                LEFT JOIN client c on ba.company_code = c.company_code 
                                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
                                                LEFT JOIN firm f on ba.firm_id = f.id
                                                where ba.id = bc.bank_auth_id
                                                and bc.deleted = 0 and ba.deleted = 0
                                                ORDER BY sent_on_date');

         return $q->result();
    }


    public function get_edit_bank_auth($id)
    {
        $q = $this->db->query('SELECT * from audit_bank_auth where id ='.$id);


        if ($q->num_rows() > 0) {
            $this->session->set_userdata('auth_company_code', $q->result()[0]->company_code);
            $this->session->set_userdata('auth_bank_id', $q->result()[0]->bank_id);
            $this->session->set_userdata('auth_status', $q->result()[0]->auth_status);
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_edit_first_letter($id)
    {
        $q = $this->db->query('SELECT * from audit_first_clearance_letter LEFT JOIN audit_clearance_doc on audit_first_clearance_letter.id = audit_clearance_doc.clearance_id where sys_generated=1 and audit_first_clearance_letter.id ='.$id.' order by send_date DESC');

        // for select2 initial "selected"
        if ($q->num_rows() > 0) {
            $this->session->set_userdata('potential_company_code', $q->result()[0]->company_code);
            $this->session->set_userdata('auth_auditor_id', $q->result()[0]->auditor_id);
            $this->session->set_userdata('our_firm_id', $q->result()[0]->firm_id);

            // $this->session->set_userdata('auth_status', $q->result()[0]->auth_status);
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }


    public function get_status_dropdown_list(){
        $q = $this->db->query("SELECT id, status FROM audit_status where type = 'clearance'");

        $status = array();
        $status[''] = '-- Select Status --';

        foreach($q->result() as $stat){
            $status[$stat->id] = $stat->status; 
        }

        return $status;
    }

    public function get_firm_from_service($company_code){
        $q = $this->db->query("SELECT servicing_firm from client_billing_info left join our_service_info on client_billing_info.service = our_service_info.id and our_service_info.user_admin_code_id = '".$this->session->userdata('user_admin_code_id')."' left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where company_code ='".$company_code."' and client_billing_info.deleted = 0 and our_service_info.service_type = 1 order by client_billing_info_id");

        return $q->result_array();
    }

    public function get_rl_details($rl_id)
    {
        $q = $this->db->query("SELECT * from audit_resignation where id = ".$rl_id); 

        $result = $q->result_array();

        return $result[0];
    }

    // public function get_choose_carry_forward_list(){
    //     $list = $this->db->query("SELECT * FROM payroll_choose_carry_forward");

    //     $choose_carry_forward_list = array();
    //     $choose_carry_forward_list[''] = 'Please Select';

    //     foreach($list->result()as $item){
    //         $choose_carry_forward_list[$item->id] = $item->choose_carry_forward_name;
    //     }

    //     return $choose_carry_forward_list;
    // }

    // public function get_approval_cap_list()
    // {
    //     $list = $this->db->query('SELECT * FROM payroll_approval_cap');

    //     return $list->result();
    // }

    // public function get_holiday_list(){

    //     $list = $this->db->query('SELECT * FROM payroll_block_holiday WHERE deleted = 0 AND year(holiday_date)='. date("Y") . ' ORDER BY holiday_date');

    //     return $list->result();
    // }

    // public function get_type_of_leave_list(){

    //     $list = $this->db->query('SELECT payroll_type_of_leave.*, payroll_choose_carry_forward.choose_carry_forward_name FROM payroll_type_of_leave LEFT JOIN payroll_choose_carry_forward ON payroll_choose_carry_forward.id = payroll_type_of_leave.choose_carry_forward_id WHERE deleted = 0 ORDER BY leave_name');

    //     return $list->result();
    // }

    // public function get_leave_cycle_list(){
    //     $list = $this->db->query('SELECT * FROM payroll_leave_cycle');

    //     return $list->result();
    // }

    // public function get_carry_forward_period_list(){
    //     $list = $this->db->query('SELECT * FROM payroll_carry_forward_period');

    //     return $list->result();
    // }

    public function submit_auditor_list($data, $id)
    {
        $query_check = $this->db->query('SELECT * FROM audit_auditor_list where audit_firm_name = "'.$data['audit_firm_name'].'" and deleted = 0');

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('audit_auditor_list', $data);
        }
        else if($query_check->num_rows() > 0)
        {
            $query_check = $query_check->result_array();
            $this->db->where('id', $query_check[0]['id']);

            $result = $this->db->update('audit_auditor_list', $data);
        }
        else
        {
            $result = $this->db->insert('audit_auditor_list', $data); 
        }
        return $result;
    }

    public function delete_auditor($auditor_id){

        $this->db->where('id', $auditor_id);

        $result = $this->db->update('audit_auditor_list', array('deleted' => 1));

        return $result;
    }

    public function delete_first_letter($letter_id){

        $this->db->where('id', $letter_id);

        $result = $this->db->update('audit_first_clearance_letter', array('deleted' => 1));

        return $result;
    }


    public function delete_bank($bank_id){

        $this->db->where('id', $bank_id);

        $result = $this->db->update('audit_bank_list', array('deleted' => 1));

        return $result;
    }


    public function submit_first_letter($data)
    {
        $query_check = $this->db->query('SELECT * FROM audit_first_clearance_letter where company_code = "'.$data['company_code'].'" and auditor_id ='.$data['auditor_id'].' and deleted=0');

        if($query_check->num_rows() > 0)
        {
            $query = $query_check->result_array();

            $this->db->where('id', $query[0]['id']);
            $this->db->update('audit_first_clearance_letter', $data);

            $result = $query[0]['id'];
        }
        else
        {
            // $data['status'] = 9;
            $this->db->insert('audit_first_clearance_letter', $data); 
            $result = $this->db->insert_id();
        }
        return $result;
    }

    public function submit_resignation($data)
    {
        
        $this->db->insert('audit_resignation', $data); 
        $result = $this->db->insert_id();

        return $result;
    }


    public function delete_bank_auth($bank_auth_id){

        $this->db->where('id', $bank_auth_id);

        $result = $this->db->update('audit_bank_auth', array('deleted' => 1));

        return $result;
    }

    public function update_bank_auth($bank_auth_id, $active){

        $this->db->where('id', $bank_auth_id);

        $result = $this->db->update('audit_bank_auth', array('active' => $active));

        return $result;
    }



    public function submit_bank_confirm_setting($data, $id)
    {
        if($id != null)
        {
            $this->db->where('setting_id', $id);

            $result = $this->db->update('audit_bank_confirm_setting', $data);
        }
        else
        {
            $query_check = $this->db->query('SELECT * FROM audit_bank_confirm_setting where confirm_month = "'.$data['confirm_month'].'" and deleted = 0');

            if($query_check->num_rows() > 0)
            {
                $query = $query_check->result_array();

                $this->db->where('setting_id', $query[0]['setting_id']);
                $this->db->update('audit_bank_confirm_setting', $data);

                $result = $query[0]['setting_id'];
            }
            else
            {
                $result = $this->db->insert('audit_bank_confirm_setting', $data); 
                // $this->db->insert('audit_bank_auth', $data); 
                // $result = $this->db->insert_id();
            }
            
        }
        return $result;
    }

    public function delete_bank_confirm_setting($id){

        $this->db->where('setting_id', $id);

        $result = $this->db->update('audit_bank_confirm_setting', array('deleted' => 1));

        return $result;
    }


    public function submit_bank_confirm($data)
    {
        $query_check = $this->db->query('SELECT * FROM audit_bank_auth where company_code = "'.$data['company_code'].'" and bank_id ='.$data['bank_id']);

        $query = $query_check->result_array();
        $bank_auth_id = $query[0]['id'];

        $data_c = array(
            'bank_auth_id' => $bank_auth_id,
            'fye_date' => $data['fye_date'],
            'confirm_status' => $data['confirm_status'],
            'sent_on_date' => $data['sent_on_date']
        );

        $query_check_c = $this->db->query('SELECT * FROM audit_bank_confirm where bank_auth_id = "'.$bank_auth_id.'"');

        if($query_check_c->num_rows() > 0)
        {
            $query_c = $query_check_c->result_array();

            $this->db->where('id', $query_c[0]['id']);
            $this->db->update('audit_bank_confirm', $data_c);

            $result = $query_c[0]['id'];
        }
        else
        {
            $this->db->insert('audit_bank_confirm', $data_c); 
            $result = $this->db->insert_id();
        }
        return $result;
    }

       public function delete_bank_confirm($id){

        $this->db->where('id', $id);

        $result = $this->db->update('audit_bank_confirm', array('deleted' => 1));

        return $result;
    }

    public function submit_type_of_leave($data, $id){

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_type_of_leave', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_type_of_leave', $data); 
        }
        return $result;
    }

    public function delete_type_of_leave($type_of_leave_id){

        $this->db->where('id', $type_of_leave_id);

        $result = $this->db->update('payroll_type_of_leave', array('deleted' => 1));

        return $result;
    }

    public function submit_holiday($data, $id){

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_block_holiday', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_block_holiday', $data);    // insert new customer to database
        }

        return $result;
    }

    public function delete_holiday($holiday_id){

        $this->db->where('id', $holiday_id);

        $result = $this->db->update('payroll_block_holiday', array('deleted' => 1));

        return $result;
    }

    public function submit_approval_cap($data, $id){

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_approval_cap', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_approval_cap', $data); 
        }

        return $result;
    }

    public function submit_leave_cycle($data, $id){

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_leave_cycle', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_leave_cycle', $data); 
        }

        return $result;
    }

    public function submit_carry_forward_period($data, $id){
        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_carry_forward_period', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_carry_forward_period', $data); 
        }

        return $result;
    }

    public function submit_block_leave($data, $id){

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_block_leave', $data);    // insert new customer to database
        }
        else
        {
            $result = $this->db->insert('payroll_block_leave', $data);    // insert new customer to database
        }

        return $result;
    }

    public function delete_block_leave($block_leave_id){

        $this->db->where('id', $block_leave_id);

        $result = $this->db->update('payroll_block_leave', array('deleted' => 1));

        return $result;
    }

    public function updt_auth_status($status, $bank_auth_id){

        $this->db->where('id', $bank_auth_id);

        
        $result = $this->db->update('audit_bank_auth', array('auth_status' => $status));
        

        return $result;
    }

    public function updt_clearance_status($status, $letter_id){

        $this->db->where('id', $letter_id);

        
        $result = $this->db->update('audit_first_clearance_letter', array('status' => $status));
        

        return $result;
    }


    public function updt_confirm_sent_date($bank_confirm_id, $sent_on_date){

        $this->db->where('id', $bank_confirm_id);

        $sent_on_date = date_format(date_create($sent_on_date),"yy-m-d");

        // echo $sent_on_date;
        
        $result = $this->db->update('audit_bank_confirm', array('sent_on_date' => $sent_on_date));
           

        return $result;
    }

    public function get_create_bc_list($month, $year)
    {
        $q = $this->db->query('SELECT ba.*, f.year_end FROM audit_bank_auth ba, filing f
                                    WHERE ba.company_code = f.company_code
                                    AND f.year_end like "%'.$month.'%"
                                    AND f.year_end like "%'.$year.'%"
                                    AND ba.deleted = 0');

        return $q->result_array();
    }

    public function get_bank_confirm_pic($month, $year)
    {
        $q = $this->db->query('SELECT bcs.*, users.* FROM audit_bank_confirm_setting bcs, users 
                                WHERE bcs.confirm_month like "%'.$month.'%"
                                AND bcs.confirm_month like "%'.$year.'%"
                                AND bcs.deleted = 0
                                AND bcs.pic_id = users.id');

        return $q->result_array();
    }

    public function get_clearance_history_list($id)
    {
        $query = 'SELECT * FROM audit_clearance_doc 
                    WHERE clearance_id ='.$id.' AND sys_generated = 1
                    ORDER BY created_at';

        $q = $this->db->query($query);

        return $q->result_array();

    }

    
    public function move_clearance($cl_id){

        $this->db->where('id', $cl_id);

        $result = $this->db->update('audit_first_clearance_letter', array('moved' => 1));

        return $result;
    }

    public function get_cl_fix_child_id($company_code)
    {
        $user_id = $this->session->userdata('user_id');

        $this->db->where(array('company_code' => $company_code, 'index_no' => 1.10));
        $q = $this->db->get("audit_paf_child");

        if ($q->num_rows() > 0) {
            
            $result = $q->result();
            $c_id = $result[0]->id;

            

            return $c_id;
        }
        else
        {
            $data = array(
                       array(
                          'company_code' => $company_code ,
                          'form_id'  => 'paf1' ,
                          'index_no' => 1,
                          'text'     => 'Audit Administration',
                          'type'     => 'fixed',
                          'order_time' => date('Y-m-d H:i:s')
                       ),
                       array(
                          'company_code' => $company_code ,
                          'form_id'  => 'paf2' ,
                          'index_no' => 2,
                          'text'     => 'Bank Authorization',
                          'type'     => 'fixed',
                          'order_time' => date('Y-m-d H:i:s')
                       )
                    );

            $this->db->insert_batch('audit_paf_parent', $data); 
            $first_id = $this->db->insert_id();

            $child_data = array(
                       array(
                          'company_code' => $company_code ,
                          'parent_id' => $first_id ,            
                          'index_no' => 1.1,
                          'text'     => 'Clearance letter',
                          'type'     => 'fixed',
                          'order_time' => date('Y-m-d H:i:s')
                       ),
                       array(
                          'company_code' => $company_code ,
                          'parent_id' => $first_id ,                       
                          'index_no' => 1.2,
                          'text'     => 'Engagement letter',
                          'type'     => 'fixed',
                          'order_time' => date('Y-m-d H:i:s')
                       )
                    );
            
            $this->db->insert_batch('audit_paf_child', $child_data); 

            $this->db->where(array('company_code' => $company_code, 'deleted' => 0));
            
            $q = $this->db->get("audit_paf_child");

            $child_array = $q->result_array();

            if($child_array)
            {
                //log initial insert paf
                foreach ($child_array as $c_key => $child) {
                    $data   = array(
                                'paf_id'     => $child['id'],
                                'date_time'  => date("Y-m-d H:i:s"),
                                'user_id'    => $user_id,
                                'paf_log'    => "initial insert",
                                'company_code' => $company_code
                              );

                    $this->db->insert('audit_paf_log', $data);
                }
            }
                

            

            $this->db->where(array('company_code' => $company_code, 'index_no' => 1.10));
            $q = $this->db->get("audit_paf_child");
            
            $result = $q->result();
            $child = $result[0]->id;

            return $child;

        }
    }

    public function get_clearance_doc_byId($cl_id)
    {
        $q = $this->db->query('SELECT * FROM audit_clearance_doc WHERE clearance_id ='.$cl_id);

        return $q-> result();
    }

    public function set_company_resign($company_code)
    {
        //deactivate statutory 
        $array = array('company_code' => $company_code, 'our_service_info.service_type' => 1);
        $serviceData['deactive'] = 1;
        // $this->db->join("client_billing_info", "client_billing_info.company_code = '".$company_code."'", "right");
        // $this->db->join("our_service_info", "our_service_info.id = client_billing_info.service", "right");
        $query = "UPDATE client_billing_info join our_service_info on our_service_info.id = client_billing_info.service SET `deactive` = 1 WHERE `company_code` = '".$company_code."' AND `our_service_info`.`service_type` = 1";
        $this->db->query($query);
        // $this->db->set($serviceData);
        // $this->db->where($array);
        // $result = $this->db->update('client_billing_info right join our_service_info on our_service_info.id = client_billing_info.service');

        //deactive bank confirmation
        $this->db->where('company_code', $company_code);
        $bankData['active'] = 0;
        $result = $this->db->update('audit_bank_auth', $bankData);

        //deactive stocktake
        $this->db->where('company_code', $company_code);
        $stocktakeData['reminder_flag'] = 0;
        $result = $this->db->update('audit_stocktake_reminder_setting', $stocktakeData);
    }

    public function get_paf_parent_id($company_code)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where(array('company_code' => $company_code, 'index_no' => 1.00));
        $q = $this->db->get("audit_paf_parent");

        if ($q->num_rows() > 0) {
            
            $result = $q->result();
            $parent = $result[0]->id;

            return $parent;
        }
        else
        {
            $data = array(
                       array(
                          'company_code' => $company_code ,
                          'form_id'  => 'paf1' ,
                          'index_no' => 1,
                          'text'     => 'Audit Administration',
                          'type'     => 'fixed',
                          'order_time' => date('Y-m-d H:i:s')
                       ),
                       array(
                          'company_code' => $company_code ,
                          'form_id'  => 'paf2' ,
                          'index_no' => 2,
                          'text'     => 'Bank Authorization',
                          'type'     => 'fixed',
                          'order_time' => date('Y-m-d H:i:s')
                       )
                    );

            $this->db->insert_batch('audit_paf_parent', $data); 
            $first_id = $this->db->insert_id();

            $child_data = array(
                       array(
                          'company_code' => $company_code ,
                          'parent_id' => $first_id ,            
                          'index_no' => 1.1,
                          'text'     => 'Clearance letter',
                          'type'     => 'fixed',
                          'order_time' => date('Y-m-d H:i:s')
                       ),
                       array(
                          'company_code' => $company_code ,
                          'parent_id' => $first_id ,                       
                          'index_no' => 1.2,
                          'text'     => 'Engagement letter',
                          'type'     => 'fixed',
                          'order_time' => date('Y-m-d H:i:s')
                       )
                    );
            
            $this->db->insert_batch('audit_paf_child', $child_data); 

            $this->db->where(array('company_code' => $company_code, 'deleted' => 0));
            
            $q = $this->db->get("audit_paf_child");

            $child_array = $q->result_array();

            if($child_array)
            {
                //log initial insert paf
                foreach ($child_array as $c_key => $child) {
                    $data   = array(
                                'paf_id'     => $child['id'],
                                'date_time'  => date("Y-m-d H:i:s"),
                                'user_id'    => $user_id,
                                'paf_log'    => "initial insert",
                                'company_code' => $company_code
                              );

                    $this->db->insert('audit_paf_log', $data);
                }
            }
                

            $this->db->where(array('company_code' => $company_code, 'index_no' => 1.00));
            $q = $this->db->get("audit_paf_parent");
            
            $result = $q->result();
            $parent = $result[0]->id;

            return $parent;

        }
    }

    



}
?>