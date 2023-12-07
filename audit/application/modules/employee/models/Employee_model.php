<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_employee_id_from_user_id($user_id){
        $q = $this->db->query("SELECT ue.employee_id FROM payroll_user_employee ue WHERE ue.user_id ='". $user_id ."'");

        if ($q->num_rows() > 0) {
            return $q->result()[0]->employee_id;
        }else{
            return FALSE;
        }
    }

    public function get_employeeList($user_id = NULL){
        if($user_id == NULL)
        {
            $q = $this->db->query("SELECT e.*, ue.user_id AS `user_id`, u.email AS `user_email`, department.department_name FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id LEFT JOIN department ON department.id = e.department");
        }
        else
        {
            $q = $this->db->query("SELECT e.*, ue.user_id AS `user_id`, u.email AS `user_email`, department.department_name FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id LEFT JOIN department ON department.id = e.department WHERE ue.user_id ='". $user_id ."'");
        }

        return $q->result();
    }

    public function get_employeeStatusList()
    {
        $list = $this->db->query("SELECT * FROM payroll_employee_status");

        $employee_status_list = array();
        $employee_status_list[''] = 'Select a Employee Status';

        foreach($list->result()as $item){
            $employee_status_list[$item->id] = $item->employee_status; 
        }

        return $employee_status_list;
    }

    public function get_employeeDepartment()
    {
        $list = $this->db->query("SELECT * FROM department");

        $employee_department_list = array();
        $employee_department_list[''] = 'Select a Employee Department';

        foreach($list->result()as $item){
            $employee_department_list[$item->id] = $item->department_name; 
        }

        return $employee_department_list;
    }

    public function get_employeeList_dropdown(){
        $q = $this->db->query("SELECT * FROM payroll_employee");

        $employee_names     = array();

        foreach($q->result() as $employee){
            $employee_names[$employee->id] = $employee->name; 
        }

        return $employee_names;
    }

    public function get_staff_info($staff_id){
        $q = $this->db->query("SELECT * FROM payroll_employee WHERE id='".$staff_id."'");

        if ($q->num_rows() > 0) {
            return $q->result();
        }else{
            return FALSE;
        }
    }

    public function get_family_info($staff_id){
        $q = $this->db->query("SELECT payroll_family_info.*, payroll_user_employee.user_id FROM payroll_family_info LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_family_info.employee_id WHERE payroll_family_info.employee_id='".$staff_id."' AND payroll_family_info.deleted=0");

        if ($q->num_rows() > 0) {
            return $q->result_array();
        }else{
            return FALSE;
        }
    }

    public function get_active_type_of_leave($staff_id){
        $q = $this->db->query("SELECT * FROM payroll_employee_type_of_leave WHERE employee_id='".$staff_id."'");

        if ($q->num_rows() > 0) {
            return $q->result();
        }else{
            return FALSE;
        }
    }

    public function create_employee($data, $annual_leave, $annual_leave_days, $previous_staff_status, $previous_status_date){
        $q = $this->db->get_where('payroll_employee', array('id' => $data['id']));    // check if customer existed before.
        // $q = $this->db->where('id', $data['id']);
        $q4 = $this->db->query("SELECT * FROM payroll_leave_cycle");
        $q4 = $q4->result_array();

        if(!$q->num_rows()){
            $this->db->insert('payroll_employee', $data);    // insert new customer to database
            $employee_id = $this->db->insert_id();

            for($r = 0; $r < count($annual_leave); $r++)
            {
                $employee_type_of_leave["employee_id"] = $employee_id;
                $employee_type_of_leave["type_of_leave_id"] = $annual_leave[$r];
                $employee_type_of_leave["days"] = $annual_leave_days[$r];

                $this->db->insert('payroll_employee_type_of_leave', $employee_type_of_leave);

                if($previous_staff_status == 1 && $previous_status_date == null)
                {
                    $q5 = $this->db->query("SELECT * FROM payroll_employee_type_of_leave WHERE type_of_leave_id = ".$annual_leave[$r]." AND employee_id = ".$q->result()[0]->id);
                    $q5 = $q5->result_array();

                    $date1 = new DateTime($data['date_joined']);
                    $date2 = new DateTime(date("Y").'-'.$q4[0]["leave_cycle_date_to"]);

                    $interval = $date1->diff($date2);

                    $years = $interval->y;
                    $months = $interval->m;
                    $days = $interval->d;

                    $balance_for_annual_leave_days = $q5[0]['days'] * ($months/12);

                    $total_annual_leave = floor($balance_for_annual_leave_days * 2) / 2;

                    $final_data = array(
                        'employee_id' => $employee_id,
                        'type_of_leave_id' => $annual_leave[$r],
                        'annual_leave_days' => $total_annual_leave
                    );

                    $this->db->insert('payroll_employee_annual_leave', $final_data);
                }
            }

            return array("status" => "created", "employee_id" => $employee_id);
        }
        else{
            $this->db->where('id', $q->result()[0]->id);
            $this->db->update('payroll_employee', $data);

            $this->db->delete('payroll_employee_type_of_leave', array('employee_id' => $q->result()[0]->id));

            for($r = 0; $r < count($annual_leave); $r++)
            {
                $employee_type_of_leave["employee_id"] = $q->result()[0]->id;
                $employee_type_of_leave["type_of_leave_id"] = $annual_leave[$r];
                $employee_type_of_leave["days"] = $annual_leave_days[$r];

                $this->db->insert('payroll_employee_type_of_leave', $employee_type_of_leave);
            }

            if($previous_staff_status == 1 && $previous_status_date == null)
            {
                $q6 = $this->db->query("SELECT * FROM payroll_employee_type_of_leave WHERE employee_id = ".$q->result()[0]->id);
                
                if($q6->num_rows())
                {
                    $q6 = $q6->result_array();

                    for($t = 0; $t < count($q6); $t++)
                    {
                        $q5 = $this->db->query("SELECT * FROM payroll_employee_type_of_leave WHERE type_of_leave_id = ".$q6[$t]['type_of_leave_id']." AND employee_id = ".$q->result()[0]->id);

                        $annual_leave_result = $q5->result_array();

                        $annual_leave_result_day = $annual_leave_result[0]['days'];
                         
                        $date1 = new DateTime($data['date_joined']);
                        $date2 = new DateTime(date("Y").'-'.$q4[0]["leave_cycle_date_to"]);

                        $interval = $date1->diff($date2);

                        $years = $interval->y;
                        $months = $interval->m;
                        $days = $interval->d;

                        $balance_for_annual_leave_days = $annual_leave_result_day * ($months/12);

                        $q7 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE employee_id='". $q->result()[0]->id ."' AND type_of_leave_id = '". $q6[$t]['type_of_leave_id'] ."' AND year(last_updated) = YEAR(CURDATE()) AND last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = ". $q->result()[0]->id ." AND type_of_leave_id = ".$q6[$t]['type_of_leave_id'].")");
                        
                        if(!$q7->num_rows())
                        {
                            $total_annual_leave = floor($balance_for_annual_leave_days * 2) / 2;

                        }
                        else
                        {
                            $q7_query = $q7->result_array();

                            $q7_query = $q7_query[0]['annual_leave_days'];

                            $total_annual_leave = (floor($balance_for_annual_leave_days * 2) / 2) + $q7_query;
                        }

                        $final_data = array(
                            'employee_id' => $q->result()[0]->id,
                            'type_of_leave_id' => $q6[$t]['type_of_leave_id'],
                            'annual_leave_days' => $total_annual_leave
                        );

                        $this->db->insert('payroll_employee_annual_leave', $final_data);
                    }
                }
            }

            // return $q->result()[0]->id; // retrieve id
            return array("status" => "updated");
        }

        return array("status" => "failed");
    }

    public function create_new_employee($data){
        $result = $this->db->insert('payroll_employee', $data);    // insert new customer to database
        $employee_id = $this->db->insert_id();

        $return_result = array(
            'result'      => 1,
            'employee_id' => $employee_id
        );
        
        return $return_result;
    }
}
?>