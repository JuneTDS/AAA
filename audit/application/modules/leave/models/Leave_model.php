<?php defined('BASEPATH') OR exit('No direct script access allowed');
include 'application/js/random_alphanumeric_generator.php';

class Leave_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        $this->load->model('Employment_json_model');
    }

    public function get_block_leave_list(){

        $list = $this->db->query('SELECT * FROM payroll_block_leave WHERE deleted = 0 AND year(block_leave_date_from)='. date("Y") . ' ORDER BY block_leave_date_from');

        return $list->result();
    }

    public function get_leave_details($leave_id){
        $q = $this->db->query("SELECT payroll_leave.*, payroll_user_employee.user_id FROM `payroll_leave` LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_leave.employee_id WHERE payroll_leave.id ='". $leave_id ."'");

        return $q->result();
    }

    public function get_employee_leaveList($employee_id){
        $q = $this->db->query("SELECT l.*, l.status as status_id FROM `payroll_leave` l WHERE year(l.date_applied) = YEAR(CURDATE()) AND l.employee_id='". $employee_id ."' ORDER BY l.status ASC");

        if ($q->num_rows() > 0) {
            $action_list = $this->Employment_json_model->get_action_result();   // Get list and name

            foreach($q->result() as $item){
                $item->status = $action_list[$item->status];
            }

            return $q->result();
        }else {
            return [];
        }
    }

    public function get_active_type_of_leave_list($employee_id){
        $list = $this->db->query("SELECT * FROM payroll_employee_type_of_leave LEFT JOIN payroll_type_of_leave ON payroll_type_of_leave.id = payroll_employee_type_of_leave.type_of_leave_id WHERE employee_id='".$employee_id."'");

        $type_of_leave_list = array();
        $type_of_leave_list[''] = 'Select a Type of Leave';

        foreach($list->result()as $item){
            $type_of_leave_list[$item->id] = $item->leave_name; 
        }

        return $type_of_leave_list;
    }

    public function get_leaveList(){    // for admin side
        $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            WHERE l.status=1");

        // $action_list = $this->Employment_json_model->get_action_result();   // Get list and name

        // foreach($q->result() as $item){
        //     $item->status = $action_list[$item->status];
        // }

        return $q->result();
    }

    public function get_history_leaveList(){    // for admin side
        $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, ptl.leave_name FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            WHERE l.status NOT IN(1) ORDER BY e.name ASC");

        return $q->result();
    }

    public function get_calender_leaveList(){
        $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, ptl.leave_name FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            WHERE l.status IN(2)");

        return $q->result_array();
    }

    public function apply_leave($data){

        $q = $this->db->query("SELECT * FROM `payroll_leave` WHERE id ='". $data['id'] ."'");

        $result_array = array();

        if(!$q->num_rows()){
            $data['leave_no'] = random_code(8);

            $result = $this->db->insert('payroll_leave', $data);    // insert new payslip to database
            $data['id'] = $this->db->insert_id();

            if(!($result > 0)) {
                array_push($result_array, array('result' => false, 'data' => array()));

                return $result_array;
            }else{
                array_push($result_array, array('result' => true, 'data' => $data));
                
                return $result_array;
            }
        } 
        else{
            $data['id'] = $q->result()[0]->id;

            $q2 = $this->db->where('id', $q->result()[0]->id);
            $result = $q2->update('payroll_leave', $data);

            if(!($result > 0)) {
                array_push($result_array, array('result' => false, 'data' => array()));
                
                // echo json_encode($result_array);
                return $result_array;
            }else{
                array_push($result_array, array('result' => true, 'data' => $data));
                
                // echo json_encode($result_array);
                return $result_array;
            }
        }

        return true;
    }

    public function reset_number_of_leave(){
        $q = $this->db->query("SELECT payroll_employee.*, payroll_type_of_leave.choose_carry_forward_id, payroll_type_of_leave.id as type_of_leave_id, payroll_employee_type_of_leave.days FROM payroll_employee LEFT JOIN payroll_employee_type_of_leave ON payroll_employee.id = payroll_employee_type_of_leave.employee_id LEFT JOIN payroll_type_of_leave ON payroll_type_of_leave.id = payroll_employee_type_of_leave.type_of_leave_id");

        foreach($q->result() as $employee){

            $q2 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE employee_id='". $employee->id ."' AND type_of_leave_id = '". $employee->type_of_leave_id ."' AND year(last_updated) = YEAR(CURDATE())");

            if(!$q2->num_rows()){
                
                if($employee->employee_status_id == 2)
                {
                    $q4 = $this->db->query("SELECT * FROM payroll_leave_cycle");
                    $q4 = $q4->result_array();

                    if($employee->choose_carry_forward_id == 1)
                    {
                        $q3 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE employee_id='". $employee->id ."' AND type_of_leave_id = '". $employee->type_of_leave_id ."' AND year(last_updated) = (YEAR(CURDATE())-1)");

                        if($q3->num_rows())
                        {
                            $total_annual_leave = $employee->days + $q3->result()[0]->annual_leave_days;
                        }
                        else
                        {
                            $date1 = new DateTime($employee->date_joined);
                            $date2 = new DateTime(date("Y").'-'.$q4[0]["leave_cycle_date_to"]);

                            $interval = $date1->diff($date2);

                            $years = $interval->y;
                            $months = $interval->m;
                            $days = $interval->d;

                            $balance_for_annual_leave_days = $employee->days * ($months/12);

                            $total_annual_leave = round($balance_for_annual_leave_days);
                        }
                    }
                    else
                    {
                        $date1 = new DateTime($employee->date_joined);
                        $date2 = new DateTime(date("Y").'-'.$q4[0]["leave_cycle_date_to"]);

                        $interval = $date1->diff($date2);

                        $years = $interval->y;
                        $months = $interval->m;
                        $days = $interval->d;

                        $balance_for_annual_leave_days = $employee->days * ($months/12);

                        $total_annual_leave = round($balance_for_annual_leave_days);

                        //$total_annual_leave = $employee->days;
                    }
                }
                else
                {
                    $total_annual_leave = 0;
                }

                $data = array(
                    'employee_id' => $employee->id,
                    'type_of_leave_id' => $employee->type_of_leave_id,
                    'annual_leave_days' => $total_annual_leave
                );

                $result = $this->db->insert('payroll_employee_annual_leave', $data);
            }
        }
    }

    public function update_status($data, $is_approve, $employee_id, $type_of_leave_id){  // for admin to approve or reject the leave
        $this->db->where('id', $data[0]['id']);
        $result = $this->db->update('payroll_leave', $data[0]);

        if($is_approve){    // Update employee remaining number of annual leave
            $q = $this->db->query("SELECT * FROM `payroll_leave` WHERE id =". $data[0]['id']);

            // create new record for updating employee remaining annual leave.
            $q2 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = ". $employee_id ." AND type_of_leave_id = ".$type_of_leave_id.") AND type_of_leave_id = ".$type_of_leave_id."");

            $numOfLeave_Applied = $q->result()[0]->total_days;
            $annual_leave_days  = $q2->result()[0]->annual_leave_days;

            $data_2 = array(
                'employee_id'       => $employee_id,
                'type_of_leave_id'  => $type_of_leave_id,
                'annual_leave_days' => $annual_leave_days - $numOfLeave_Applied
            );

            $result_2 = $this->db->insert('payroll_employee_annual_leave', $data_2);

            if($type_of_leave_id == 2)
            {
                $q3 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = ". $employee_id ." AND type_of_leave_id = 3) AND type_of_leave_id = 3");

                if($q3->num_rows())
                {
                    $data_3 = array(
                        'employee_id'       => $employee_id,
                        'type_of_leave_id'  => 3,
                        'annual_leave_days' => $q3->result()[0]->annual_leave_days - $numOfLeave_Applied
                    );

                    $result_3 = $this->db->insert('payroll_employee_annual_leave', $data_3);
                }
            }

            // update al_left_after
            $data_4 = array(
                'al_left_after' => $annual_leave_days - $numOfLeave_Applied
            );
            $this->db->where('id', $data[0]['id']);
            $result_4 = $this->db->update('payroll_leave', $data_4);

            if($result && $result_2 && $result_4){

                if($type_of_leave_id == 1)
                {
                    $approve_query = $this->db->query("SELECT * from payroll_leave WHERE 
                        ((start_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR 
                        (end_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR 
                        (start_date <= '".date('Y-m-d', strtotime($q->result()[0]->start_date))."' AND end_date >= '".date('Y-m-d', strtotime($q->result()[0]->end_date))."')) AND status = 2 AND type_of_leave_id = 1");
                    // echo json_encode($approve_query->result_array());
                    // "start_date":"2019-11-12 00:00:00"
                    //         "end_date":"2019-11-15 00:00:00"
                    if($approve_query->num_rows())
                    {
                        $approve_query = $approve_query->result_array();

                        $payroll_approval_cap_query = $this->db->query("SELECT * FROM `payroll_approval_cap`");

                        $payroll_approval_cap_query = $payroll_approval_cap_query->result_array();

                        if($payroll_approval_cap_query[0]["number_of_employee"] <= count($approve_query))
                        {
                            $query = $this->db->query("SELECT * from payroll_leave WHERE
                                        ((start_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR 
                                        (end_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR 
                                        (start_date <= '".date('Y-m-d', strtotime($q->result()[0]->start_date))."' AND end_date >= '".date('Y-m-d', strtotime($q->result()[0]->end_date))."')) AND status = 1 AND type_of_leave_id = 1");
                            
                            if($query->num_rows())
                            {
                                $query = $query->result_array();

                                for($t = 0; $t < count($query); $t++)
                                {
                                    // To get the last remaining annual leave left
                                    $annual_leave_left_q = $this->db->query("SELECT * FROM payroll_employee_annual_leave eal_1 WHERE eal_1.last_updated = (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id=" . $query[$t]['employee_id'] . " AND eal_2.type_of_leave_id = ".$query[$t]['type_of_leave_id'].") AND eal_1.type_of_leave_id = ".$query[$t]['type_of_leave_id']."");
                                    //echo json_encode($annual_leave_left_q->result_array());
                                    $annual_leave_left_q = $annual_leave_left_q->result_array();

                                    $payroll_leave_data['status'] = 3;
                                    $payroll_leave_data['status_updated_by'] = date('Y-m-d H:i:s');
                                    $payroll_leave_data['al_left_before'] = $annual_leave_left_q[0]['annual_leave_days'];
                                    $payroll_leave_data['al_left_after'] = $annual_leave_left_q[0]['annual_leave_days'];

                                    $update_payroll_leave = $this->db->where('id', $query[$t]['id']);
                                    $update_payroll_leave->update('payroll_leave', $payroll_leave_data);
                                }
                            }
                        }
                    }
                }

                return true;
            }else{
                return false;
            }
        }

        return $result;
    }
}
?>