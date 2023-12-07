<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//include 'application/js/random_alphanumeric_generator.php';

class Stocktake_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        
    }

    public function get_client_stocktake_setting(){
        // $q = $this->db->query("SELECT DISTINCT (client.company_name), client.*, audit_stocktake_reminder_setting.reminder_flag, audit_stocktake_reminder_setting.email FROM client
        //                         RIGHT JOIN client_billing_info ON client_billing_info.company_code = client.company_code
        //                         RIGHT JOIN our_service_info ON our_service_info.id = client_billing_info.service
        //                         LEFT JOIN audit_client ON audit_client.company_code = client.company_code
        //                         LEFT JOIN audit_stocktake_reminder_setting ON client.company_code = audit_stocktake_reminder_setting.company_code
        //                         WHERE (our_service_info.service_type = 1 OR our_service_info.service_type = 10)
        //                         AND client.deleted = 0");

        $q = $this->db->query("SELECT DISTINCT (client.company_name), client.*, audit_stocktake_reminder_setting.reminder_flag, audit_stocktake_reminder_setting.email, client_billing_info.servicing_firm FROM client
                                RIGHT JOIN client_billing_info ON client_billing_info.company_code = client.company_code
                                RIGHT JOIN our_service_info ON our_service_info.id = client_billing_info.service
                                LEFT JOIN audit_client ON audit_client.company_code = client.company_code
                                LEFT JOIN audit_stocktake_reminder_setting ON client.company_code = audit_stocktake_reminder_setting.company_code
                                LEFT JOIN user_firm ON user_firm.firm_id = client.firm_id
                                WHERE our_service_info.service_type = 1 
                                AND client.deleted = 0  
                                AND user_firm.user_id = ".$this->session->userdata('user_id')."
                                ORDER BY client.id  DESC");    

        $data = $q->result();

        foreach ($data as $key => $value) {
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
        }
        return $data;
    }

    public function get_all_stocktake_arrangement(){
        $q = $this->db->query('SELECT client.company_name, reminder.fye_date, sta.id as sta_id, sta.arranged, sta.reminder_id, users.first_name, info.* FROM audit_stocktake_arrangement sta 
                                LEFT JOIN audit_stocktake_arrangement_info info ON sta.id = info.stocktake_arrangement_id 
                                LEFT JOIN audit_stocktake_reminder reminder ON sta.reminder_id = reminder.id
                                LEFT JOIN client ON client.company_code = reminder.company_code
                                LEFT JOIN users ON auditor_id = users.id
                                WHERE client.deleted = 0 and info.deleted = 0 and sta.deleted = 0 and sta.done = 0');
        // SELECT * FROM `audit_stocktake_arrangement` sta, audit_stocktake_arrangement_info info WHERE sta.id = info.stocktake_arrangement_id

        $data = $q->result();

        foreach ($data as $key => $value) {
            $auditor_list = array();
            if($data[$key]->auditor_id != null)
            {
                $auditor_id_str = $data[$key]->auditor_id;
                $auditor_id_arr = explode(',', $auditor_id_str);

                foreach ($auditor_id_arr as $value) {
                    $q_name = $this->db->query('SELECT CONCAT(first_name, " ", last_name) as auditor_name FROM users
                                            WHERE id ='.$value);

                    $name_data = $q_name->result();

                    array_push($auditor_list, $name_data[0]->auditor_name);
                }



            }
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
            $data[$key]->auditor_name = $auditor_list;
        }
        
        // $outer_data = array();

        // foreach ($data as $key => $value) {
        //     $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
        // }

        // foreach ($data as $key => $value) {
        //     $test = array();
        //     foreach ($value as $key => $detail) {
        //         array_push($test, $detail);
        //     }
        //     array_push($outer_data, $test);
        // }

        // return json_encode($outer_data);
        // $data = array_values($data);

        // $test = array_values($data);
        // print_r(json_encode($data));
        return $data;
        // print_r(json_encode(array('1','2','3')));
        // print_r(['1','2','3']);

    }

    public function get_all_stocktake_subsequent(){
        $q = $this->db->query('SELECT client.company_name, client.company_code, reminder.fye_date, sta.* , GROUP_CONCAT(info.auditor_id SEPARATOR ",") as all_auditor_id, stat.status as status_text FROM audit_stocktake_arrangement sta 
                                LEFT JOIN audit_stocktake_arrangement_info info ON sta.id = info.stocktake_arrangement_id 
                                LEFT JOIN audit_stocktake_reminder reminder ON sta.reminder_id = reminder.id
                                LEFT JOIN client ON client.company_code = reminder.company_code
                                LEFT JOIN audit_subsequent_status stat ON stat.id = sta.status 
                                WHERE client.deleted = 0 and info.deleted = 0 and sta.deleted = 0 and sta.done = 1
                                GROUP BY sta.id');
        // SELECT * FROM `audit_stocktake_arrangement` sta, audit_stocktake_arrangement_info info WHERE sta.id = info.stocktake_arrangement_id

        $data = $q->result();

        foreach ($data as $key => $d_value) {
            $auditor_list = array();
            if($data[$key]->all_auditor_id != null)
            {
                $auditor_id_str = $data[$key]->all_auditor_id;
                $auditor_id_arr = explode(',', $auditor_id_str);

                foreach ($auditor_id_arr as $value) {
                    $q_name = $this->db->query('SELECT CONCAT(first_name, " ", last_name) as auditor_name FROM users
                                            WHERE id ='.$value);

                    $name_data = $q_name->result();

                    array_push($auditor_list, $name_data[0]->auditor_name);
                }



            }

            $this->db->select('*');
            $this->db->from('audit_stocktake_subsequent_document');
            $this->db->where(array('stocktake_arrangement_id' => $d_value->id, 'deleted' => 0));

      
            $aq = $this->db->get();

        
            $attachment_array = $aq->result_array();

            $data[$key]->attachment = $attachment_array;

            $rp_q = $this->db->query("SELECT audit_stocktake_review_point.*, CONCAT(first_name,' ',last_name) as raised_user_name
                                FROM audit_stocktake_review_point 
                                LEFT JOIN users ON users.id = audit_stocktake_review_point.point_raised_by 
                                WHERE audit_stocktake_review_point.stocktake_arrangement_id = '".$d_value->id."' AND audit_stocktake_review_point.deleted = '0'
                                ORDER BY id");
        
            $review_point_array = $rp_q->result_array();

            $data[$key]->review_point = $review_point_array;
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
            $data[$key]->auditor_name = $auditor_list;
        }
        

        return $data;
     
    }

    public function get_auditor_dropdown_list(){

        $q = $this->db->query("SELECT users.id, first_name ,last_name FROM `users` 
                                LEFT JOIN payroll_user_employee on users.id = user_id
                                LEFT JOIN payroll_employee on payroll_user_employee.employee_id = payroll_employee.id
                                WHERE `department_id` = 1 and payroll_employee.employee_status_id != 3 and payroll_employee.employee_status_id != 4");

        $auditor = array();

        foreach($q->result() as $auditor_list){
            $auditor[$auditor_list->id] = $auditor_list->first_name." ".$auditor_list->last_name; 
        }

        return $auditor;
    }

    public function get_edit_stocktake_arrangement($sta_id)
    {
        $q = $this->db->query('SELECT client.company_name, reminder.fye_date, reminder.company_code, sta.id as sta_id, sta.arranged, sta.stocktake_pic, info.* ,reminder.id as reminder_id FROM audit_stocktake_arrangement sta 
                                LEFT JOIN audit_stocktake_reminder reminder ON sta.reminder_id = reminder.id
                                LEFT JOIN client ON client.company_code = reminder.company_code
                                LEFT JOIN audit_stocktake_arrangement_info info ON sta.id = info.stocktake_arrangement_id 
                                WHERE sta.id = '.$sta_id.' and client.deleted = 0 and info.deleted = 0');

        $data = $q->result();

        if ($q->num_rows() > 0) {
            $this->session->set_userdata('reminder_id', $q->result()[0]->reminder_id);
            $this->session->set_userdata('stocktake_pic', $q->result()[0]->stocktake_pic);
        }


        foreach ($data as $key => $value) {
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);

            if($data[$key]->stocktake_date != "0000-00-00")
            {
                $data[$key]->stocktake_date = DateTime::createFromFormat('Y-m-d', $data[$key]->stocktake_date )->format('d/m/Y');
            }
            else
            {
                $data[$key]->stocktake_date = "";
            }

            if($data[$key]->stocktake_time == "00:00:00")
            {
                $data[$key]->stocktake_time = "";
            }
            $data[$key]->stocktake_time = substr($data[$key]->stocktake_time,0,5);
            
        }

        return $data;


    }

    public function get_client_with_reminder_id($reminder_id)
    {

        $q = $this->db->query('SELECT reminder.company_code, reminder.id as reminder_id,reminder.fye_date, reminder.sent_on_date, client.company_name  FROM audit_stocktake_reminder reminder
                            LEFT JOIN client ON reminder.company_code = client.company_code 
                            WHERE client.deleted = 0
                            and reminder.id = "'.$reminder_id.'"');

        $data = $q->result_array();

        if ($q->num_rows() > 0) {
            $data[0]["company_name"] = $this->encryption->decrypt($data[0]["company_name"]);
            return $data[0];
        }
        else
        {
            return $data;
        }
        
    }

    public function get_sent_reminder($year_month)
    {
        $q = $this->db->query("SELECT * from audit_stocktake_reminder WHERE sent_on_date LIKE '%".$year_month."%' ");

        $data = $q->result_array();

        // foreach ($data as $key => $value) {
        //     $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
        // }
        return $data;
    }

    public function get_arranged_stocktake($year_month)
    {
        $q = $this->db->query("SELECT * from audit_stocktake_reminder 
                                RIGHT JOIN audit_stocktake_arrangement ON audit_stocktake_arrangement.reminder_id = audit_stocktake_reminder.id
                                WHERE arranged = 1
                                and sent_on_date LIKE '%".$year_month."%' ");

        $data = $q->result_array();

        // foreach ($data as $key => $value) {
        //     $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
        // }
        return $data;
    }

    public function get_office(){
        $q = $this->db->query("SELECT * FROM payroll_offices WHERE id NOT IN (1) AND office_deleted = 0");

        $office['0'] = 'All Offices';

        foreach($q->result() as $row){
            $office[$row->id] = $row->office_name;
        }

        return $office;
    }

    public function get_department(){
        $q = $this->db->query("SELECT * FROM department WHERE id NOT IN (7) ORDER BY list_order");

        $department['0'] = 'All Departments';

        foreach($q->result() as $row){
            $department[$row->id] = $row->department_name;
        }

        return $department;
    }

    public function get_pic_details($employee_id)
    {
        $q = $this->db->query('SELECT  users.* FROM  users , payroll_user_employee
                                WHERE payroll_user_employee.employee_id = '.$employee_id.'
                                AND payroll_user_employee.user_id = users.id');

        return $q->result_array();
    }

    public function get_pic_details_users($user_id)
    {
        $q = $this->db->query('SELECT  users.* FROM  users , payroll_user_employee
                                WHERE users.id = '.$user_id.'
                                AND payroll_user_employee.user_id = users.id');

        return $q->result_array();
    }


    public function submit_reminder_record($data)
    {
      
        $query_check = $this->db->query('SELECT * FROM audit_stocktake_reminder where fye_date = "'.$data['fye_date'].'" and company_code = "'.$data['company_code'].'"');

        if($query_check->num_rows() > 0)
        {
            $query = $query_check->result_array();

            $this->db->where('id', $query[0]['id']);
            $this->db->update('audit_stocktake_reminder', $data);

            $result = $query[0]['id'];
        }
        else
        {
            $this->db->insert('audit_stocktake_reminder', $data); 
            $result = $this->db->insert_id();
        }

        
        // $result = $this->db->insert('audit_stocktake_reminder', $data); 
        
        return $result;
    }

    public function add_stocktake_arrangement($data)
    {
      
        $query_check = $this->db->query('SELECT * FROM audit_stocktake_arrangement where reminder_id = "'.$data['reminder_id'].'"');

        if($query_check->num_rows() > 0)
        {
            $query = $query_check->result_array();

            $this->db->where('id', $query[0]['id']);
            $this->db->update('audit_stocktake_arrangement', $data);

            $result = $query[0]['id'];
        }
        else
        {
            $this->db->insert('audit_stocktake_arrangement', $data); 
            $result = $this->db->insert_id();
        }
        // $result = $this->db->insert('audit_stocktake_reminder', $data); 
        
        return $result;
    }

    public function delete_sta($sta_id){

        $this->db->where('id', $sta_id);

        $result = $this->db->update('audit_stocktake_arrangement', array('deleted' => 1));

        return $result;
    }

    public function delete_arrangement_info($arrangement_info_id){

        $this->db->where('id', $arrangement_info_id);

        $result = $this->db->update('audit_stocktake_arrangement_info', array('deleted' => 1));

        return $result;
    }

    public function insert_stocktake_arrangement($data, $id=0)
    {
        
        if($id == 0)
        {
            $query_check = $this->db->query('SELECT * FROM audit_stocktake_arrangement where reminder_id = "'.$data['reminder_id'].'"');

            if($query_check->num_rows() > 0)
            {
                $query = $query_check->result_array();

                $this->db->where('id', $query[0]['id']);
                $this->db->update('audit_stocktake_arrangement', $data);

                $result = $query[0]['id'];
            }
            else
            {
                $this->db->insert('audit_stocktake_arrangement', $data); 
                $result = $this->db->insert_id();
            }
        }
        else
        {

            $this->db->where('id', $id);
            $this->db->update('audit_stocktake_arrangement', $data);

            $result = $id;
        }
        
        return $result;
    }

    public function insert_stocktake_arrangement_info($data, $id=null)
    {
        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('audit_stocktake_arrangement_info', $data);
        }
        else
        {
            $result = $this->db->insert('audit_stocktake_arrangement_info', $data); 
        }
        return $result;
    }

    public function stocktake_arrangement_filter($month, $auditor, $arranged, $office, $department){

        if($office == '0'){
            $office = '%%';
        }

        if($department == '0'){
            $department = '%%';
        }

        $query = $this->db->query("SELECT user_id FROM payroll_employee 
                                    INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id 
                                    LEFT JOIN users ON users.id = payroll_user_employee.user_id 
                                    WHERE payroll_employee.office LIKE '".$office."' AND payroll_employee.department LIKE '".$department."'");

        $office_department = array();

        foreach($query->result() as $key => $row){
            $office_department[$key] = $row->user_id;
        }

        if(json_encode($office_department) != '[]')
        {
            $office_department = json_encode($office_department);
            $office_department = str_replace(str_split('["]'), "" , $office_department);

            $office_department = "AND (find_in_set(".$office_department;
            $office_department = str_replace("," , ", auditor_id) != 0 or find_in_set(" , $office_department);
            $office_department = $office_department.", auditor_id) != 0) ";

    
            if($auditor == "empty")
            {
                // $q = $this->db->query("SELECT client.company_name, reminder.fye_date, sta.id as sta_id, sta.arranged, users.first_name, users.last_name, info.* FROM audit_stocktake_arrangement sta    
                //                                     LEFT JOIN audit_stocktake_reminder reminder ON sta.reminder_id = reminder.id
                //                                     LEFT JOIN client ON client.company_code = reminder.company_code
                //                                     LEFT JOIN audit_stocktake_arrangement_info info ON sta.id = info.stocktake_arrangement_id 
                //                                     LEFT JOIN users ON users.id = '".$auditor."'
                                                   
                //                                     WHERE client.deleted = 0 and info.deleted = 0 and sta.deleted = 0
                //                                     and reminder.fye_date like '".$month."' 
                //                                     and find_in_set('".$auditor."', auditor_id) != 0
                //                                     and arranged like '".$arranged."'");
                // $q = $this->db->query("SELECT client.company_name, reminder.fye_date, sta.id as sta_id, sta.arranged, users.first_name, users.last_name, info.* FROM audit_stocktake_arrangement sta    
                //                                     LEFT JOIN audit_stocktake_reminder reminder ON sta.reminder_id = reminder.id
                //                                     LEFT JOIN client ON client.company_code = reminder.company_code
                //                                     LEFT JOIN audit_stocktake_arrangement_info info ON sta.id = info.stocktake_arrangement_id 
                //                                     LEFT JOIN users ON auditor_id = users.id
                                                   
                //                                     WHERE client.deleted = 0 and info.deleted = 0 and sta.deleted = 0 and sta.done = 0
                //                                     ".$office_department."
                //                                     and reminder.fye_date like '".$month."' 
                //                                     and arranged like '".$arranged."'");
                $q = $this->db->query("SELECT client.company_name, reminder.fye_date, sta.id as sta_id, sta.arranged, users.first_name, users.last_name, info.* FROM audit_stocktake_arrangement sta    
                                                    LEFT JOIN audit_stocktake_reminder reminder ON sta.reminder_id = reminder.id
                                                    LEFT JOIN client ON client.company_code = reminder.company_code
                                                    LEFT JOIN audit_stocktake_arrangement_info info ON sta.id = info.stocktake_arrangement_id 
                                                    LEFT JOIN users ON auditor_id = users.id
                                                   
                                                    WHERE client.deleted = 0 and info.deleted = 0 and sta.deleted = 0 and sta.done = 0
                                                    and reminder.fye_date like '".$month."' 
                                                    and arranged like '".$arranged."'");

                
            }
            else
            {
                 $q = $this->db->query("SELECT client.company_name, reminder.fye_date, sta.id as sta_id, sta.arranged, users.first_name, users.last_name, info.* FROM audit_stocktake_arrangement sta    
                                                    LEFT JOIN audit_stocktake_reminder reminder ON sta.reminder_id = reminder.id
                                                    LEFT JOIN client ON client.company_code = reminder.company_code
                                                    LEFT JOIN audit_stocktake_arrangement_info info ON sta.id = info.stocktake_arrangement_id 
                                                    LEFT JOIN users ON auditor_id = users.id
                                                                                                 
                                                    WHERE client.deleted = 0 and info.deleted = 0 and sta.deleted = 0 and sta.done = 0
                                                    ".$office_department."
                                                    and reminder.fye_date like '".$month."' 
                                                    and ".$auditor
                                                    ."and arranged like '".$arranged."'");
                
            }

            $data = $q->result();

            foreach ($data as $key => $value) {
                $auditor_list = array();
                if($data[$key]->auditor_id != null)
                {
                    $auditor_id_str = $data[$key]->auditor_id;
                    $auditor_id_arr = explode(',', $auditor_id_str);

                    foreach ($auditor_id_arr as $value) {
                        $q_name = $this->db->query('SELECT CONCAT(first_name, " ", last_name) as auditor_name FROM users
                                                WHERE id ='.$value);

                        $name_data = $q_name->result();

                        array_push($auditor_list, $name_data[0]->auditor_name);
                    }



                }
                $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
                $data[$key]->auditor_name = $auditor_list;
            }
            return $data;
        }
        else
        {
            return array();
        }

        


        
    }

    public function stocktake_subsequent_filter($month, $auditor, $office, $department){

         if($office == '0'){
            $office = '%%';
        }

        if($department == '0'){
            $department = '%%';
        }

        $query = $this->db->query("SELECT user_id FROM payroll_employee 
                                    INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id 
                                    LEFT JOIN users ON users.id = payroll_user_employee.user_id 
                                    WHERE payroll_employee.office LIKE '".$office."' AND payroll_employee.department LIKE '".$department."'");

        $office_department = array();

        foreach($query->result() as $key => $row){
            $office_department[$key] = $row->user_id;
        }

        if(json_encode($office_department) != '[]')
        {
            $office_department = json_encode($office_department);
            $office_department = str_replace(str_split('["]'), "" , $office_department);

            $office_department = "(find_in_set(".$office_department;
            $office_department = str_replace("," , ", all_auditor_id) != 0 or find_in_set(" , $office_department);
            $office_department = $office_department.", all_auditor_id) != 0) ";

    
    
            if($auditor == "empty")
            {
                // $q = $this->db->query("SELECT client.company_name, reminder.fye_date, sta.id as sta_id, sta.arranged, users.first_name, users.last_name, info.* FROM audit_stocktake_arrangement sta    
                //                                     LEFT JOIN audit_stocktake_reminder reminder ON sta.reminder_id = reminder.id
                //                                     LEFT JOIN client ON client.company_code = reminder.company_code
                //                                     LEFT JOIN audit_stocktake_arrangement_info info ON sta.id = info.stocktake_arrangement_id 
                //                                     LEFT JOIN users ON users.id = '".$auditor."'
                                                   
                //                                     WHERE client.deleted = 0 and info.deleted = 0 and sta.deleted = 0
                //                                     and reminder.fye_date like '".$month."' 
                //                                     and find_in_set('".$auditor."', auditor_id) != 0
                //                                     and arranged like '".$arranged."'");
                $q = $this->db->query("SELECT client.company_name, reminder.fye_date, sta.* , GROUP_CONCAT(info.auditor_id SEPARATOR ',') as all_auditor_id, stat.status as                 status_text FROM audit_stocktake_arrangement sta 
                                        LEFT JOIN audit_stocktake_arrangement_info info ON sta.id = info.stocktake_arrangement_id 
                                        LEFT JOIN audit_stocktake_reminder reminder ON sta.reminder_id = reminder.id
                                        LEFT JOIN client ON client.company_code = reminder.company_code
                                        LEFT JOIN audit_subsequent_status stat ON stat.id = sta.status 
                                        WHERE client.deleted = 0 and info.deleted = 0 and sta.deleted = 0 and sta.done = 1
                                                        and reminder.fye_date like '".$month."' GROUP BY sta.id
                                        HAVING ".$office_department);

                
            }
            else
            {
                 $q = $this->db->query("SELECT client.company_name, reminder.fye_date, sta.* , GROUP_CONCAT(info.auditor_id SEPARATOR ',') as all_auditor_id, stat.status as                 status_text FROM audit_stocktake_arrangement sta 
                                        LEFT JOIN audit_stocktake_arrangement_info info ON sta.id = info.stocktake_arrangement_id 
                                        LEFT JOIN audit_stocktake_reminder reminder ON sta.reminder_id = reminder.id
                                        LEFT JOIN client ON client.company_code = reminder.company_code
                                        LEFT JOIN audit_subsequent_status stat ON stat.id = sta.status 
                                        WHERE client.deleted = 0 and info.deleted = 0 and sta.deleted = 0 and sta.done = 1
                                                    and reminder.fye_date like '".$month."' GROUP BY sta.id
                                        HAVING ".$auditor."AND ".$office_department);
                
            }

            


            $data = $q->result();

            foreach ($data as $key => $value) {
                $auditor_list = array();
                if($data[$key]->all_auditor_id != null)
                {
                    $auditor_id_str = $data[$key]->all_auditor_id;
                    $auditor_id_arr = explode(',', $auditor_id_str);

                    foreach ($auditor_id_arr as $value) {
                        $q_name = $this->db->query('SELECT CONCAT(first_name, " ", last_name) as auditor_name FROM users
                                                WHERE id ='.$value);

                        $name_data = $q_name->result();

                        array_push($auditor_list, $name_data[0]->auditor_name);
                    }



                }
                $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
                $data[$key]->auditor_name = $auditor_list;
            }
            return $data;
        }
        else
        {
            return array();
        }
    }

    public function get_sts_status_dropdown_list(){
        $q = $this->db->query("SELECT * FROM audit_subsequent_status");

        $status = array();
        $status[''] = "-- Status --";

        foreach($q->result() as $stat){
            $status[$stat->id] = $stat->status; 
        }

        return $status;
    }

    public function get_point_raise_detail($review_point_id)
    {
        $q = $this->db->query('SELECT CONCAT(first_name," ",last_name) as user_name, point_raised_at FROM audit_stocktake_review_point
                                LEFT JOIN users ON users.id = audit_stocktake_review_point.point_raised_by
                                WHERE audit_stocktake_review_point.id = '.$review_point_id);

        return $q->result_array();
    }

    public function filter_review_points($cleared, $sta_id)
    {
        $q = $this->db->query("SELECT audit_stocktake_review_point.*, CONCAT(first_name,' ',last_name) as raised_user_name
                                FROM audit_stocktake_review_point 
                                LEFT JOIN users ON users.id = audit_stocktake_review_point.point_raised_by 
                                WHERE audit_stocktake_review_point.stocktake_arrangement_id = '".$sta_id."' AND audit_stocktake_review_point.cleared LIKE '".$cleared."' AND audit_stocktake_review_point.deleted = '0'
                                ORDER BY id");

  
        return $q->result();
    }

    public function get_uncleared_points($sta_id)
    {
        $q = $this->db->query('SELECT * FROM audit_stocktake_review_point
                                WHERE stocktake_arrangement_id = '.$sta_id.' 
                                AND cleared = 0 AND deleted = 0');

        return $q->result_array();
    }

    public function get_rp_existance($sta_id)
    {
        $q = $this->db->query('SELECT * FROM audit_stocktake_review_point
                                WHERE stocktake_arrangement_id = '.$sta_id.' 
                                AND deleted = 0');

        return $q->result_array();
    }

    public function delete_subsequent_doc($doc_id)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('id', $doc_id);


        $result = $this->db->update('audit_stocktake_subsequent_document', array('deleted' => 1));


        return $result;
    }



}
?>