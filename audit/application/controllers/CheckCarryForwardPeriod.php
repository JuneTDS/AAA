<?php define( 'APPLICATION_LOADED', true );


class CheckCarryForwardPeriod extends CI_Controller {

    public function message($to = 'World') {
        echo "Hello {$to}!" . PHP_EOL;
    }

    public function check_latest_annual_leave() {

        $payroll_carry_forward_period_query = $this->db->query("SELECT * FROM payroll_carry_forward_period");

        if($payroll_carry_forward_period_query->num_rows())
        {
            $payroll_carry_forward_period_query = $payroll_carry_forward_period_query->result_array();

            $carry_forward_period_date = date('Y-m-d', strtotime(date("Y").'-'.$payroll_carry_forward_period_query[0]["carry_forward_period_date"]));
            //echo json_encode(date("Y-m-d"));
            if($carry_forward_period_date == date("Y-m-d"))
            {
                $payroll_employee_query = $this->db->query("SELECT payroll_employee.*, payroll_employee_type_of_leave.days FROM payroll_employee LEFT JOIN payroll_employee_type_of_leave ON payroll_employee.id = payroll_employee_type_of_leave.employee_id");

                if($payroll_employee_query->num_rows())
                {
                    $payroll_employee_query = $payroll_employee_query->result_array();

                    for($g = 0; $g < count($payroll_employee_query); $g++)
                    {
                        $payroll_employee_annual_leave_query = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE employee_id='". $payroll_employee_query[$g]['id'] ."' AND type_of_leave_id = 1 AND year(last_updated) = YEAR(CURDATE()) AND last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = ". $payroll_employee_query[$g]['id'] ." AND type_of_leave_id = 1)");

                        if($payroll_employee_annual_leave_query->num_rows())
                        {
                            $payroll_employee_annual_leave_query = $payroll_employee_annual_leave_query->result_array();

                            if($payroll_employee_query[$g]['days'] < $payroll_employee_annual_leave_query[0]["annual_leave_days"])
                            {
                                $payroll_employee_annual_leave_data['annual_leave_days'] = $payroll_employee_query[$g]['days'];
                                $update_payroll_employee_annual_leave = $this->db->where('id', $payroll_employee_annual_leave_query[0]['id']);
                                $update_payroll_employee_annual_leave->update('payroll_employee_annual_leave', $payroll_employee_annual_leave_data);
                            }
                        }
                    }
                }
            }
        }
    }
}