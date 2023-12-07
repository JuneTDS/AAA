<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//include 'application/js/random_alphanumeric_generator.php';

class Bank_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        
    }

    public function get_bank_list(){
        $q = $this->db->query('SELECT * from audit_bank_list where deleted = 0');

        if($q->num_rows() > 0)
        {
            return $q->result();
        }
        else
        {
            return false;
        }
    }

    public function get_bank_auth(){
        $q = $this->db->query('SELECT ba.*, c.company_name, bank.bank_name from audit_bank_auth ba
                                LEFT JOIN client c on ba.company_code = c.company_code 
                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
                                WHERE ba.deleted = 0 and ba.active = 1 and ba.moved = 0');

        $data = $q->result();

        foreach ($data as $key => $value) {
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
        }
        return $data;

         // return $q->result();
    }

    public function get_bank_auth_deactivate(){
        $q = $this->db->query('SELECT ba.*, c.company_name, bank.bank_name from audit_bank_auth ba
                                LEFT JOIN client c on ba.company_code = c.company_code 
                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
                                WHERE ba.deleted = 0 and ba.active = 0');

        $data = $q->result();

        foreach ($data as $key => $value) {
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
        }
        return $data;

         // return $q->result();
    }

    

    public function get_bank_confirm_setting(){
        $q = $this->db->query('SELECT bcs.*, u.name from audit_bank_confirm_setting bcs
                                LEFT JOIN payroll_employee u on bcs.pic_id = u.id 
                                WHERE bcs.deleted = 0');

         return $q->result();
    }

    public function get_disable_month(){
        $q = $this->db->query('SELECT confirm_month from audit_bank_confirm_setting 
                                WHERE deleted = 0');

        $result = $q->result();

        $disable_month = array();

        foreach ($result as $key => $value) {
            # code...
            array_push($disable_month, date_format(date_create($value->confirm_month),'M Y'));
        }

        return $disable_month;
    }


    public function get_bank_confirm(){
        $q = $this->db->query('SELECT bc.*, c.company_code, c.company_name, bank.bank_name, f.name as firm_name from audit_bank_confirm bc, audit_bank_auth ba
                                                LEFT JOIN client c on ba.company_code = c.company_code 
                                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
                                                LEFT JOIN firm f on ba.firm_id = f.id
                                                where ba.id = bc.bank_auth_id
                                                and bc.deleted = 0 and ba.deleted = 0
                                                ORDER BY sent_on_date');

        $data = $q->result();

        foreach ($data as $key => $value) {
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
        }
        return $data;

         // return $q->result();
    }

    public function get_bank_auth_files()
    {
        $q = $this->db->query('SELECT bank_auth_id, file_path, GROUP_CONCAT(DISTINCT CONCAT(id,",",file_name)SEPARATOR ";") as file_names FROM audit_bank_auth_document GROUP BY bank_auth_id');

        foreach ($q->result() as $key => $value) {
            if($q->result()[$key]->file_names != null)
            {
                $q->result()[$key]->file_names = explode(';', $q->result()[$key]->file_names);
            }
        }
        

        return $q-> result();
    }

    public function get_bank_auth_doc_byId($auth_id)
    {
        $q = $this->db->query('SELECT * FROM audit_bank_auth_document WHERE bank_auth_id ='.$auth_id);

        return $q-> result();
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

    public function get_status_dropdown_list(){
        $q = $this->db->query("SELECT id, status FROM audit_status where type='bank' ORDER BY id ");

        $status = array();

        foreach($q->result() as $stat){
            $status[$stat->id] = $stat->status; 
        }

        return $status;
    }

    public function get_firm_from_service($company_code){
        $q = $this->db->query("SELECT servicing_firm from client_billing_info 
                            left join our_service_info on client_billing_info.service = our_service_info.id and our_service_info.user_admin_code_id = '".$this->session->userdata('user_admin_code_id')."' 
                            left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type 
                            where company_code ='".$company_code."' and client_billing_info.deleted = 0 and client_billing_info.deactive = 0 and (our_service_info.service_type = 1 or our_service_info.service_type = 10) order by client_billing_info_id");

        return $q->result_array();
    }


    public function submit_bank_list($data, $id)
    {
        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('audit_bank_list', $data);
        }
        else
        {
            $result = $this->db->insert('audit_bank_list', $data); 
        }
        return $result;
    }

    public function delete_bank($bank_id){

        $this->db->where('id', $bank_id);

        $result = $this->db->update('audit_bank_list', array('deleted' => 1));

        return $result;
    }


    public function submit_bank_auth($data)
    {
        $query_check = $this->db->query('SELECT * FROM audit_bank_auth where company_code = "'.$data['company_code'].'" and bank_id ='.$data['bank_id'].' and deleted = 0');

        if($query_check->num_rows() > 0)
        {
            $query = $query_check->result_array();

            $this->db->where('id', $query[0]['id']);
            $this->db->update('audit_bank_auth', $data);

            $result = $query[0]['id'];
        }
        else
        {
            $this->db->insert('audit_bank_auth', $data); 
            $result = $this->db->insert_id();
        }
        return $result;
    }

    public function delete_bank_auth($bank_auth_id){

        $this->db->where('id', $bank_auth_id);

        $result = $this->db->update('audit_bank_auth', array('deleted' => 1));

        return $result;
    }

    public function move_bank_auth($bank_auth_id){

        $this->db->where('id', $bank_auth_id);

        $result = $this->db->update('audit_bank_auth', array('moved' => 1));

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
        $query_check = $this->db->query('SELECT * FROM audit_bank_auth where company_code = "'.$data['company_code'].'" and bank_id ='.$data['bank_id'].' and deleted=0');

        $query = $query_check->result_array();
        $bank_auth_id = $query[0]['id'];

        $data_c = array(
            'bank_auth_id' => $bank_auth_id,
            'fye_date' => $data['fye_date'],
            // 'confirm_status' => $data['confirm_status'],
            'sent_on_date' => $data['sent_on_date']
        );

        $query_check_c = $this->db->query('SELECT * FROM audit_bank_confirm where bank_auth_id = "'.$bank_auth_id.'" and fye_date="'.$data['fye_date'].'" and deleted = 0');

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
    // }

    // public function submit_holiday($data, $id){

    //     if($id != null)
    //     {
    //         $this->db->where('id', $id);

    //         $result = $this->db->update('payroll_block_holiday', $data);
    //     }
    //     else
    //     {
    //         $result = $this->db->insert('payroll_block_holiday', $data);    // insert new customer to database
    //     }

    //     return $result;
    // }

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

    public function updt_confirm_status($status, $bank_confirm_id){

        $this->db->where('id', $bank_confirm_id);

        
        $result = $this->db->update('audit_bank_confirm', array('confirm_status' => $status));
        

        return $result;
    }


    public function updt_confirm_sent_date($bank_confirm_id, $sent_on_date){

        $this->db->where('id', $bank_confirm_id);

        $sent_on_date = date_format(date_create($sent_on_date),"Y-m-d");

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
                                    AND ba.auth_status = 2
                                    AND ba.deleted = 0
                                    AND f.company_code IN (select company_code from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service where (our_service_info.service_type = 1) and client_billing_info.deactive = 0 and client_billing_info.deleted = 0)');

        return $q->result_array();
    }

    public function get_follow_up_ba_list($next_month)
    {
        $q = $this->db->query('SELECT ba.*, f.year_end, client.company_name, bank.bank_name, stat.status FROM filing f, audit_bank_auth ba
                                    LEFT JOIN client on client.company_code = ba.company_code
                                    LEFT JOIN audit_bank_list bank on ba.bank_id = bank.id
                                    LEFT JOIN audit_status stat on ba.auth_status = stat.id
                                    WHERE ba.company_code = f.company_code
                                    AND f.year_end like "%'.$next_month.'"
                                    AND f.company_code IN (select company_code from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service where (our_service_info.service_type = 1) and client_billing_info.deactive = 0 and client_billing_info.deleted = 0)
                                    AND ba.auth_status != 5
                                    AND ba.deleted = 0
                                    AND ba.id NOT IN (select DISTINCT(bank_auth_id) from audit_bank_auth_document)');

        $data =  $q->result();

        foreach ($data as $key => $value) {
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
        }

        return $data;
    }

    public function get_bank_confirm_pic($month, $year)
    {
        $q = $this->db->query('SELECT bcs.*, users.* FROM audit_bank_confirm_setting bcs, users , payroll_employee, payroll_user_employee
                                WHERE bcs.confirm_month like "%'.$month.'%"
                                AND bcs.confirm_month like "%'.$year.'%"
                                AND bcs.deleted = 0
                                AND bcs.pic_id = payroll_user_employee.employee_id 
                                AND payroll_user_employee.user_id = users.id');

        return $q->result_array();
    }

    public function get_bank_confirm_report($year_month)
    {
        $q = $this->db->query('SELECT ba.*, COUNT(bc.id) as bc_total, bank.bank_name FROM audit_bank_confirm bc, audit_bank_auth ba 
                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id 
                                where ba.id = bc.bank_auth_id 
                                AND bc.fye_date LIKE "%'.$year_month.'%"
                                AND bc.deleted = 0
                                GROUP BY bank.id');

        return $q->result_array();
    }

    public function get_end_month_bank_confirm_report($year_month)
    {
        $q = $this->db->query('SELECT COUNT(bc.id) as bc_total,bank.id, bank.bank_name, bc.confirm_status FROM audit_bank_confirm bc, audit_bank_auth ba 
                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id 
                                where ba.id = bc.bank_auth_id 
                                AND bc.fye_date LIKE "%'.$year_month.'%"
                                GROUP BY bank.id, bc.confirm_status');

        return $q->result_array();
    }

    public function get_previous_bank_confirm()
    {
        $q = $this->db->query('SELECT COUNT(bc.id) as bc_total, bank.id, bank.bank_name, bc.confirm_status, substring(bc.fye_date,1,7) as month FROM audit_bank_confirm bc, audit_bank_auth ba 
                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id  
                                where ba.id = bc.bank_auth_id 
                                GROUP BY bank.id, substring(month,1,7), bc.confirm_status
                                ORDER BY month');

        return $q->result_array();
    }



    public function get_set_pic_flag($next_month)
    {
        $q = $this->db->query('SELECT * FROM audit_bank_confirm_setting  
                                WHERE confirm_month = "'.$next_month.'" 
                                AND deleted = 0');


        if($q->num_rows() > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function check_bank_auth_exist($next_month)
    {
        $q = $this->db->query('SELECT f.year_end, client.company_name FROM filing f
                                INNER JOIN client on client.company_code = f.company_code
                                AND f.year_end like "%'.$next_month.'"
                                WHERE client.company_code in (select company_code from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service where (our_service_info.service_type = 1) and client_billing_info.deactive = 0 and client_billing_info.deleted = 0)');


        if($q->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_no_bank_auth_list($check_month)
    {
        $q = $this->db->query('SELECT f.year_end, client.company_name FROM filing f
                                INNER JOIN client on client.company_code = f.company_code
                                AND f.year_end like "%'.$check_month.'"
                                WHERE client.company_code in (select company_code from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service where (our_service_info.service_type = 1) and client_billing_info.deactive = 0 and client_billing_info.deleted = 0)
                                AND client.company_code not in (SELECT company_code from audit_bank_auth ba WHERE ba.deleted = 0 and ba.active = 1)');
        // return $q->result_array();
        $data =  $q->result();

        foreach ($data as $key => $value) {
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
        }

        return $data;
   
    }

    public function get_this_month_bank_auth($check_month)
    {
        $q = $this->db->query('SELECT f.year_end, client.company_name, client.company_code, GROUP_CONCAT(bl.bank_name_for_user, " ") as bank_name FROM filing f
                                INNER JOIN client on client.company_code = f.company_code
                                INNER JOIN audit_bank_auth ba on ba.company_code = f.company_code
                                INNER JOIN audit_bank_list bl on bl.id = ba.bank_id
                                AND f.year_end like "%'.$check_month.'"
                                WHERE client.company_code in (select company_code from client_billing_info left join our_service_info on our_service_info.id = client_billing_info.service where (our_service_info.service_type = 1) and client_billing_info.deactive = 0 and client_billing_info.deleted = 0)
                                AND ba.deleted = 0 and ba.active = 1
                                GROUP BY client.company_code');

        $data =  $q->result();

        foreach ($data as $key => $value) {
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
        }

        return $data;

        
    }

    public function bank_confirm_filter($month, $status){
        if($status == 1)
        {
            $q = $this->db->query("SELECT bc.*, c.company_name, bank.bank_name, f.name as firm_name from audit_bank_confirm bc, audit_bank_auth ba
                                                LEFT JOIN client c on ba.company_code = c.company_code 
                                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
                                                LEFT JOIN firm f on ba.firm_id = f.id
                                                where ba.id = bc.bank_auth_id
                                                and bc.deleted = 0 and ba.deleted = 0
                                                and bc.fye_date like '".$month."'
                                                and (bc.confirm_status like '".$status."' OR bc.confirm_status like '0')
                                                ORDER BY sent_on_date");
        }
        else
        {
            $q = $this->db->query("SELECT bc.*, c.company_name, bank.bank_name, f.name as firm_name from audit_bank_confirm bc, audit_bank_auth ba
                                                LEFT JOIN client c on ba.company_code = c.company_code 
                                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
                                                LEFT JOIN firm f on ba.firm_id = f.id
                                                where ba.id = bc.bank_auth_id
                                                and bc.deleted = 0 and ba.deleted = 0
                                                and bc.fye_date like '".$month."'
                                                and bc.confirm_status like '".$status."'
                                                ORDER BY sent_on_date");
        }
        


        $data = $q->result();

        foreach ($data as $key => $value) {
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
        }
        return $data;
    }

    public function get_paf_parent_id($company_code)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where(array('company_code' => $company_code, 'index_no' => 2.00));
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
                

            $this->db->where(array('company_code' => $company_code, 'index_no' => 2.00));
            $q = $this->db->get("audit_paf_parent");
            
            $result = $q->result();
            $parent = $result[0]->id;

            return $parent;

        }
    }




}
?>