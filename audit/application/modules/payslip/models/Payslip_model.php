<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payslip_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_all_employee_list($data){

        // $q = $this->db->query("SELECT p.*, e.name, e.nric_fin_no FROM payslip p LEFT JOIN employee e ON e.id = p.employee_id WHERE p.shown = 1 AND year(p.payslip_for) = year('" . $data['date'] . "') AND month(p.payslip_for) = month('" . $data['date'] . "')");
        $q = $this->db->query("SELECT p.*, e.name, e.nric_fin_no FROM payslip p LEFT JOIN employee e ON e.id = p.employee_id WHERE year(p.payslip_for) = year('" . $data['date'] . "') AND month(p.payslip_for) = month('" . $data['date'] . "')");

        return $q->result();
    }

    public function get_all_bonus_list($data){
        $q = $this->db->query("SELECT p.* FROM payslip p WHERE year(p.payslip_for) = year('" . $data['date'] . "') AND month(p.payslip_for) = month('" . $data['date'] . "')  AND (p.aws > 0 OR p.bonus > 0 OR p.commission > 0 AND p.health_incentive > 0 AND p.other_incentive > 0)");

        return $q->result();
    }

    public function get_all_months(){
        $q = $this->db->query("SELECT DISTINCT p.payslip_for FROM payslip p ORDER BY YEAR(p.payslip_for), MONTH(p.payslip_for)");

        $list = array();

        foreach ($q->result() as $key=>$item) {
            $list[$key] = $item->payslip_for;
        }
        // echo json_encode($list);
        return $list;
    }

    /* get employee */
    public function get_list($employee_id){
        $q = $this->db->query("SELECT * FROM payslip WHERE employee_id = '". $employee_id ."'");

        return $q->result();
    }

    public function get_payslip_months($employee_id){
        $q = $this->db->query("SELECT generate_by FROM payslip WHERE employee_id = '". $employee_id ."'");

        $month_list = array();
        $month_list[''] = "-- Select month --";

        // echo json_encode($q->result());

        foreach($q->result() as $date){
            $month_year = date('M Y', strtotime($date->generate_by));
            $month_list[$month_year] = $month_year;
            // echo date('YYYY/MM/DD', strtotime($date));
        }

        return $month_list;
    }

    public function view_payslip($payslip_id){
        $q = $this->db->query("SELECT p.*, e.name, e.nric_fin_no, e.department FROM payslip p LEFT JOIN employee e ON e.id = p.employee_id WHERE p.id ='". $payslip_id ."'");

        return $q->result()[0];
    }

    public function set_bonus_payslip($data){

        $return_result = array(
            'result' => 0,
            'data'   => array()
        );

        $temp = array();

        foreach($data as $item){

            if(!($item['aws'] == 0 && $item['bonus'] == 0.00 && $item['commission'] == 0.00 && $item['health_incentive'] == 0 && $item['other_incentive'] == 0.00)){

                $q = $this->db->query("SELECT * FROM payslip p WHERE p.id ='". $item['id'] ."' OR (p.employee_id='". $item['employee_id'] ."' AND p.payslip_for ='". $item['payslip_for'] ."')");

                // echo json_encode($q->result());

                if(!$q->num_rows()){
                    $result = $this->db->insert('payslip', $item);    // insert new payslip to database
                    $item['id'] = $this->db->insert_id();

                    if(!($result > 0)) {
                        $return_result['result'] = 0;
                        return $return_result;
                    }else{
                        array_push($temp, $item);
                    }
                } 
                else{
                    $item['id'] = $q->result()[0]->id;

                    $q2 = $this->db->where('id', $q->result()[0]->id);
                    $result = $q2->update('payslip', $item);

                    if(!($result > 0)) {
                        $return_result['result'] = 0;
                        return $return_result;
                    }else{
                        array_push($temp, $item);
                    }
                }
            }
        }

        $return_result['result'] = 1;
        $return_result['data']   = $temp;

        return $return_result;
    }

    public function get_payslip_settings(){
        $q = $this->db->query("SELECT * FROM payslip_setting");

         return $q->result()[0];
    }

    public function save_payslip_setting($data){
        $q = $this->db->select('*')->where('id', $data['id'])->get('payslip_setting');

        // echo json_encode($q->result());

        if(!$q->num_rows()){
            $this->db->insert('payslip_setting', $data); 
            $payslip_setting_id = $this->db->insert_id();

            return $payslip_setting_id;
        } 
        else{
            $this->db->where('id', $q->result()[0]->id);
            $result = $this->db->update('payslip_setting', $data);

            if($result){
                return $q->result()[0]->id; // retrieve id
            }
        }
    }

    public function generate_all_payslip($selected_month){
        $employee_list   = $this->db->query("SELECT * FROM employee")->result();
        $payslip_setting = $this->db->query("SELECT * FROM payslip_setting")->result()[0];

        // $payslip_bundle = array();

        foreach($employee_list as $employee){
            $cpf_employee = $employee->salary * $employee->cpf_employee / 100;
            $cpf_employer = $employee->salary * $employee->cpf_employer / 100;

            $payslip_item = array(
                'id'          => '',
                'employee_id' => $employee->id,
                'payslip_for' => $selected_month,
                'date'        => date('Y-m-d H:i:s'),
                'department'  => $employee->department,
                'basic_salary'=> $employee->salary,
                'cdac'        => $payslip_setting->cdac,
                'cpf_employee'=> $cpf_employee,
                'cpf_employer'=> $cpf_employer,
                'sd_levy'     => $payslip_setting->sdl,
                'generate_by' => date('Y-m-d H:i:s'),
                'remaining_al'=> $employee->remaining_annual_leave,
                'shown'       => 0
            );

            // array_push($payslip_bundle, $payslip);

            $q = $this->db->query("SELECT * FROM payslip p WHERE p.employee_id='". $employee->id ."' AND p.payslip_for ='". $selected_month ."'");

            if(!$q->num_rows()){
                $result = $this->db->insert('payslip', $payslip_item);    // insert new payslip to database
                $payslip_id = $this->db->insert_id();

                if(!($result > 0)) {
                    return false;
                }
            } 
            else{
                $payslip_item['id'] = $q->result()[0]->id;

                $q2 = $this->db->where('id', $q->result()[0]->id);
                $result = $q2->update('payslip', $payslip_item);

                if(!($result > 0)) {
                    return false;
                }
            }
        }

        return true;

        // echo json_encode($payslip_bundle);
    }

    public function remove_bonus($payslip_id){
        $bonus = array(
            'aws' => 0,
            'bonus' => 0,
            'commission' => 0,
            'health_incentive' => 0,
            'other_incentive' => 0
        );

        $q      = $this->db->where('id', $payslip_id);
        $result = $q->update('payslip', $bonus);

        return $result;
        // $q = $this->db->query("UPDATE payslip p SET p.aws = 0, p.bonus = 0, p.commission = 0, p.health_incentive = 0, p.other_incentive = 0 WHERE id'". $payslip ."'");
    }
}
?>