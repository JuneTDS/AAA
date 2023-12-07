<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//include 'application/js/random_alphanumeric_generator.php';

class engagement_model extends CI_Model
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
    //     $q = $this->db->query('SELECT bcs.*, u.name from audit_bank_confirm_setting bcs
    //                             LEFT JOIN payroll_employee u on bcs.pic_id = u.id 
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
        $q = $this->db->query('SELECT * from audit_first_clearance_letter where id ='.$id);

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
        if($id != null)
        {
            $this->db->where('id', $id);

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
        $query_check = $this->db->query('SELECT * FROM audit_first_clearance_letter where company_code = "'.$data['company_code'].'" and auditor_id ='.$data['auditor_id']);

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

    
    public function move_initial_el($el_id){

        $this->db->where('id', $el_id);

        $result = $this->db->update('audit_engagement_letter', array('moved' => 1));

        return $result;
    }

    public function move_subsequent_el($sub_el_id){

        $this->db->where('id', $sub_el_id);

        $result = $this->db->update('audit_subsequent_el', array('moved' => 1));

        return $result;
    }

    public function get_el_fix_child_id($company_code)
    {
        $user_id = $this->session->userdata('user_id');

        $this->db->where(array('company_code' => $company_code, 'index_no' => 1.20));
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
                

            

            $this->db->where(array('company_code' => $company_code, 'index_no' => 1.20));
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

    //Engagement model

    public function getTransactionEngagementLetterList()
    {
        $this->db->select('engagement_letter_list.*');
        $this->db->from('engagement_letter_list');
        $this->db->where_in('id', ['3','4','5']);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    } 

    public function check_client_info($company_code)
    {
        $q = $this->db->query("select client.*, filing.year_end, financial_year_period.period, client_signing_info.director_signature_1 from client left join filing on filing.id = (select MAX(id) as filing_id from filing where filing.company_code = client.company_code) left join financial_year_period on financial_year_period.id = filing.financial_year_period_id left join client_signing_info on client_signing_info.company_code = client.company_code where client.company_code = '".$company_code."' and client.deleted = 0");
        // and client.firm_id = '".$this->session->userdata('firm_id')."'

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $row->registration_no = $this->encryption->decrypt($row->registration_no);
                $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_service_proposal_service_info($company_code)
    {
        $this->db->select('transaction_master.*, transaction_service_proposal_service_info.transaction_id, transaction_service_proposal_service_info.our_service_id, transaction_service_proposal_service_info.currency_id, transaction_service_proposal_service_info.fee, transaction_service_proposal_service_info.unit_pricing, transaction_service_proposal_service_info.servicing_firm, our_service_info.engagement_letter_list_id');
        $this->db->from('transaction_master');
        $this->db->join('transaction_service_proposal_service_info', 'transaction_service_proposal_service_info.transaction_id = transaction_master.id', 'left');
        $this->db->join('our_service_info', 'our_service_info.id = transaction_service_proposal_service_info.our_service_id', 'left');
        
        $this->db->where('transaction_master.transaction_task_id', "29");
        $this->db->where('transaction_master.company_code', $company_code);
        // $this->db->where('transaction_master.firm_id', $this->session->userdata("firm_id"));
        $this->db->where('(transaction_master.service_status = 1 or transaction_master.service_status = 3)');
        $this->db->order_by("transaction_master.id", "asc");
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $row->client_name = $this->encryption->decrypt($row->client_name);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllFirmInfo(){

        $this->db->select('firm.*, firm_telephone.telephone, firm_fax.fax, firm_email.email')
            ->from('firm')
            // ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
            ->join('firm_telephone', 'firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1', 'left')
            ->join('firm_fax', 'firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1', 'left')
            ->join('firm_email', 'firm_email.firm_id = firm.id AND firm_email.primary_email = 1', 'left');
            // ->where('user_firm.user_id = '.$this->session->userdata('user_id'));

        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_initial_el_list()
    {
        $q = $this->db->query('SELECT * from audit_engagement_letter where deleted = 0 and moved = 0');

        if($q->num_rows() > 0)
        {
            return $q->result();
        }
        else
        {
            return false;
        }
    }

    public function get_subsequent_el_list()
    {
        $q = $this->db->query('SELECT audit_subsequent_el.* , client.company_name from audit_subsequent_el
                                LEFT JOIN client on client.company_code = audit_subsequent_el.company_code
                                where audit_subsequent_el.deleted = 0 and audit_subsequent_el.moved = 0 and client.deleted = 0');

        if($q->num_rows() > 0)
        {
            foreach (($q->result()) as $row) {
                $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }
            return $data;
        
            return $q->result();
        }
        else
        {
            return false;
        }
    }

    public function get_status_dropdown_list(){
        $q = $this->db->query("SELECT id, status FROM audit_status where type = 'engagement'");

        $status = array();
        $status[''] = '-- Select Status --';

        foreach($q->result() as $stat){
            $status[$stat->id] = $stat->status; 
        }

        return $status;
    }

    public function get_el_detail($el_id)
    {
        $q = $this->db->query('SELECT * from audit_engagement_letter where deleted = 0 and id="'.$el_id.'"');

        if($q->num_rows() > 0)
        {
            return $q->result();
        }
        else
        {
            return false;
        }
    }

    public function get_sub_el_detail($el_id)
    {
        $q = $this->db->query('SELECT * from audit_subsequent_el where deleted = 0 and id="'.$el_id.'"');

        if($q->num_rows() > 0)
        {
            return $q->result();
        }
        else
        {
            return false;
        }
    }

    public function getEngagementLetterInfo($id)
    {
        $this->db->select('audit_engagement_letter_info.*, currency.currency as currency_name, unit_pricing.unit_pricing_name, firm.name as firm_name, firm.branch_name');
        $this->db->from('audit_engagement_letter_info');
        $this->db->join('unit_pricing', 'unit_pricing.id = audit_engagement_letter_info.unit_pricing', 'left');
        $this->db->join('currency', 'currency.id = audit_engagement_letter_info.currency_id ', 'left');
        $this->db->join('firm', 'firm.id = audit_engagement_letter_info.servicing_firm ', 'left');
        $this->db->where('engagement_letter_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        // print_r($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_el_doc_template()
    {
        $this->db->select('audit_doc_master_template.*');
        $this->db->from('audit_doc_master_template');
        $this->db->where_in('id', ['6','7','8']);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        foreach($q->result() as $doc){
            $document[$doc->id] = $doc->document_content; 
        }

        return $document;
    }

    public function updt_initial_el_status($status, $letter_id)
    {
        $this->db->where('id', $letter_id);

        
        $result = $this->db->update('audit_engagement_letter', array('status' => $status));

        //log delete doc
        $log_data   = array(
            'engagement_letter_id'     => $letter_id,
            'date_time'  => date("Y-m-d H:i:s"),
            'user_id'    => $this->session->userdata('user_id'),
            'log_message'    => "change status",
        );

        $this->db->insert('audit_initial_el_log', $log_data);
        

        return $result;
    }

    public function updt_subsequent_el_status($status, $letter_id)
    {
        $this->db->where('id', $letter_id);

        
        $result = $this->db->update('audit_subsequent_el', array('status' => $status));

        //log delete doc
        $log_data   = array(
            'subsequent_el_id'     => $letter_id,
            'date_time'  => date("Y-m-d H:i:s"),
            'user_id'    => $this->session->userdata('user_id'),
            'log_message'    => "change status",
        );

        $this->db->insert('audit_subsequent_el_log', $log_data);
        

        return $result;
    }

    public function detect_client_code($company_name)
    {
        $firstCharacter = strtoupper(substr($company_name, 0, 1));

        $q = $this->db->query("SELECT MAX(CAST(SUBSTRING(client_code, -5) AS UNSIGNED)) as latest_client_code FROM client WHERE client_code LIKE '".$firstCharacter."%' AND deleted = 0 ORDER BY latest_client_code DESC LIMIT 1");

        $q = $q->result_array();

        $num_padded = sprintf("%05d", $q[0]["latest_client_code"] + 1);

        return $firstCharacter.$num_padded;
    }

    public function get_initial_el_logs()
    {
        $q = $this->db->query('SELECT log.*, CONCAT(first_name," ",last_name) as user_name
                            FROM audit_initial_el_log log
                            INNER JOIN
                                (SELECT engagement_letter_id, MAX(date_time) AS latest_date_time
                                FROM audit_initial_el_log
                                GROUP BY engagement_letter_id) groupedlog
                            ON log.engagement_letter_id = groupedlog.engagement_letter_id 
                            INNER JOIN users on users.id = log.user_id
                            AND log.date_time = groupedlog.latest_date_time');


        $data = $q->result_array();
        $temp_arr = array();

        foreach ($data as $key => $value) {
            $temp_arr[$value['engagement_letter_id']] = $value;
              # code...
        }  

        return $temp_arr;
    }

    public function get_subsequent_el_logs()
    {
        $q = $this->db->query('SELECT log.*, CONCAT(first_name," ",last_name) as user_name
                            FROM audit_subsequent_el_log log
                            INNER JOIN
                                (SELECT subsequent_el_id, MAX(date_time) AS latest_date_time
                                FROM audit_subsequent_el_log
                                GROUP BY subsequent_el_id) groupedlog
                            ON log.subsequent_el_id = groupedlog.subsequent_el_id 
                            INNER JOIN users on users.id = log.user_id
                            AND log.date_time = groupedlog.latest_date_time');


        $data = $q->result_array();
        $temp_arr = array();

        foreach ($data as $key => $value) {
            $temp_arr[$value['subsequent_el_id']] = $value;
              # code...
        }  

        return $temp_arr;
    }


    public function get_previous_el_info($company_code)
    {

        $query_previous_sub_el = $this->db->query("SELECT audit_subsequent_el.* FROM audit_subsequent_el where id = (SELECT max(id) FROM audit_subsequent_el where deleted = 0 and company_code = '".$company_code."')");

        if ($query_previous_sub_el->num_rows() > 0) 
        {
            $query_previous_sub_el = $query_previous_sub_el->result_array();

            $this->db->select('audit_subsequent_el_info.*, currency.currency as currency_name, unit_pricing.unit_pricing_name, firm.name as firm_name, firm.branch_name');
            $this->db->from('audit_subsequent_el_info');
            $this->db->join('unit_pricing', 'unit_pricing.id = audit_subsequent_el_info.unit_pricing', 'left');
            $this->db->join('currency', 'currency.id = audit_subsequent_el_info.currency_id ', 'left');
            $this->db->join('firm', 'firm.id = audit_subsequent_el_info.servicing_firm ', 'left');
            $this->db->where(array('audit_subsequent_el_info.subsequent_el_id' => $query_previous_sub_el[0]["id"]));
            $this->db->order_by("id", "asc");
            $q = $this->db->get();
        }
        else
        {
            $this->db->select('audit_engagement_letter_info.*, currency.currency as currency_name, unit_pricing.unit_pricing_name, firm.name as firm_name, firm.branch_name');
            $this->db->from('audit_engagement_letter_info');
            $this->db->join('audit_engagement_letter', 'audit_engagement_letter.id = audit_engagement_letter_info.engagement_letter_id', 'left');
            $this->db->join('unit_pricing', 'unit_pricing.id = audit_engagement_letter_info.unit_pricing', 'left');
            $this->db->join('currency', 'currency.id = audit_engagement_letter_info.currency_id ', 'left');
            $this->db->join('firm', 'firm.id = audit_engagement_letter_info.servicing_firm ', 'left');
            $this->db->where(array('audit_engagement_letter.company_code' => $company_code, 'audit_engagement_letter.deleted' => 0));
            $this->db->order_by("id", "asc");
            $q = $this->db->get();
        }


        // $this->db->select('audit_engagement_letter_info.*, currency.currency as currency_name, unit_pricing.unit_pricing_name, firm.name as firm_name, firm.branch_name');
        // $this->db->from('audit_engagement_letter_info');
        // $this->db->join('audit_engagement_letter', 'audit_engagement_letter.id = audit_engagement_letter_info.engagement_letter_id', 'left');
        // $this->db->join('unit_pricing', 'unit_pricing.id = audit_engagement_letter_info.unit_pricing', 'left');
        // $this->db->join('currency', 'currency.id = audit_engagement_letter_info.currency_id ', 'left');
        // $this->db->join('firm', 'firm.id = audit_engagement_letter_info.servicing_firm ', 'left');
        // $this->db->where(array('audit_engagement_letter.company_code' => $company_code, 'audit_engagement_letter.deleted' => 0));
        // $this->db->order_by("id", "asc");
        // $q = $this->db->get();

          if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSubsequentElInfo($id)
    {
        $this->db->select('audit_engagement_letter_info.*, currency.currency as currency_name, unit_pricing.unit_pricing_name, firm.name as firm_name, firm.branch_name');
        $this->db->from('audit_engagement_letter_info');
        $this->db->join('unit_pricing', 'unit_pricing.id = audit_engagement_letter_info.unit_pricing', 'left');
        $this->db->join('currency', 'currency.id = audit_engagement_letter_info.currency_id ', 'left');
        $this->db->join('firm', 'firm.id = audit_engagement_letter_info.servicing_firm ', 'left');
        $this->db->where('engagement_letter_id', $id);
        $this->db->order_by("id", "asc");
        $q = $this->db->get();

        // print_r($q->result());

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function check_el_client_info($company_code)
    {
        $q = $this->db->query("select audit_engagement_letter.*, client.company_name from audit_engagement_letter LEFT JOIN client on client.company_code = audit_engagement_letter.company_code where client.company_code = '".$company_code."' and client.deleted = 0 and audit_engagement_letter.deleted = 0");
        // and client.firm_id = '".$this->session->userdata('firm_id')."'

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                // $row->registration_no = $this->encryption->decrypt($row->registration_no);
                // $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function check_subsequent_el_client_info($company_code)
    {

        $query_previous_sub_el = $this->db->query("SELECT audit_subsequent_el.* FROM audit_subsequent_el where id = (SELECT max(id) FROM audit_subsequent_el where deleted = 0 and moved = 1 and company_code = '".$company_code."')");

        if ($query_previous_sub_el->num_rows() > 0) 
        {
            $query_previous_sub_el = $query_previous_sub_el->result_array();

            $q = $this->db->query("select audit_subsequent_el.*, audit_subsequent_el.new_date as previous_letter_date, client.company_name from audit_subsequent_el LEFT JOIN client on client.company_code = audit_subsequent_el.company_code where audit_subsequent_el.id = '".$query_previous_sub_el[0]["id"]."' and client.deleted = 0 and audit_subsequent_el.deleted = 0");
        }
        else
        {
            $q = $this->db->query("select audit_engagement_letter.*, audit_engagement_letter.engagement_letter_date as previous_letter_date, client.company_name from audit_engagement_letter LEFT JOIN client on client.company_code = audit_engagement_letter.company_code where client.company_code = '".$company_code."' and client.deleted = 0 and audit_engagement_letter.deleted = 0");
        }
        
        // and client.firm_id = '".$this->session->userdata('firm_id')."'

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                // $row->registration_no = $this->encryption->decrypt($row->registration_no);
                $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    
    public function delete_initial_el($letter_id){

        $this->db->where('id', $letter_id);

        $result = $this->db->update('audit_engagement_letter', array('deleted' => 1));

        return $result;
    }

    public function delete_subsequent_el($letter_id){

        $this->db->where('id', $letter_id);

        $result = $this->db->update('audit_subsequent_el', array('deleted' => 1));



        return $result;
    }

    public function get_engagement_letter_record($company_code)
    {
        $query_previous_sub_el = $this->db->query("SELECT audit_subsequent_el.* FROM audit_subsequent_el where id = (SELECT max(id) FROM audit_subsequent_el where deleted = 0 and moved = 1 and company_code = '".$company_code."')");

        if ($query_previous_sub_el->num_rows() > 0) 
        {
            foreach (($q->result()) as $row) {
                $row->company_name = $this->encryption->decrypt($row->company_name);
                $data[] = $row;
            }
            return $data;
           
        }
        else
        {
            $this->db->select('audit_engagement_letter.*');
            $this->db->from('audit_engagement_letter');
            $this->db->where(array('company_code' => $company_code, 'deleted' => 0, 'moved' => 1));
            $this->db->order_by("id", "desc");
            $q = $this->db->get();

            // print_r($q->result());

            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $row->company_name = $this->encryption->decrypt($row->company_name);
                    $data[] = $row;
                }
                return $data;
            }
            return FALSE;
        }
    }

    public function update_subsequent_date($date, $letter_id)
    {
        $this->db->where('id', $letter_id);

        $result = $this->db->update('audit_subsequent_el', array('new_date' => $date));

        //log update letter date
        $log_data   = array(
            'subsequent_el_id'     => $letter_id,
            'date_time'  => date("Y-m-d H:i:s"),
            'user_id'    => $this->session->userdata('user_id'),
            'log_message'    => "change letter date",
        );

        $this->db->insert('audit_subsequent_el_log', $log_data);

        return $result;
    }

    public function update_initial_date($date, $letter_id)
    {
        $this->db->where('id', $letter_id);

        $result = $this->db->update('audit_engagement_letter', array('engagement_letter_date' => $date));

        //log update letter date
        $log_data   = array(
            'engagement_letter_id'     => $letter_id,
            'date_time'  => date("Y-m-d H:i:s"),
            'user_id'    => $this->session->userdata('user_id'),
            'log_message'    => "change letter date",
        );

        $this->db->insert('audit_initial_el_log', $log_data);

        return $result;
    }






}
?>