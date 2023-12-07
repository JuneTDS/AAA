<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Leave extends MX_Controller
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
        $this->load->model('leave_model');
        $this->load->model('holiday_model');
        $this->load->model('employee/employee_model');
        $this->load->model('employment_json_model');
        //$this->load->model('setting/setting_model');

        if(!$this->data['Admin']){
            //echo json_encode($this->user_id);
            $this->employee_id  = $this->employee_model->get_employee_id_from_user_id($this->user_id);

            $employee_data      = $this->employee_model->get_staff_info($this->employee_id)[0];
            //echo json_encode($employee_data);
            if($employee_data != null)
            {
                $this->remaining_al = $employee_data->remaining_annual_leave;
            }
            else
            {
                $this->remaining_al = FALSE;
            }
        }

        // check if it is new year or employee has empty leave record, then create new record for leave remaining record.
        $this->leave_model->reset_number_of_leave();
        //echo json_encode($result);
        $this->meta['page_name'] = 'Leave';
    }

    public function index()
    {
        if(!$this->data['Admin'] && !$this->data['Manager']){
            $this->data['leave_list'] = $this->leave_model->get_employee_leaveList($this->employee_id);
        
            $this->page_construct('index.php', $this->meta, $this->data);
        }elseif($this->data['Manager']){
            $this->data['employee_leave_list'] = $this->leave_model->get_employee_leaveList($this->employee_id);

            $this->data['leave_list']   = $this->leave_model->get_leaveList(); //this is for admin
            $this->data['action_list']  = $this->employment_json_model->get_action_result();
            $this->data['history_list'] = $this->leave_model->get_history_leaveList();
            $this->data['calender_list'] = $this->leave_model->get_calender_leaveList();

            foreach($this->data['history_list'] as $row){
                $row->status = $this->employment_json_model->get_action_name($row->status);
            }

            $this->page_construct('index_manager.php', $this->meta, $this->data);
        }else{
            $this->data['leave_list']   = $this->leave_model->get_leaveList(); //this is for admin
            $this->data['action_list']  = $this->employment_json_model->get_action_result();
            $this->data['history_list'] = $this->leave_model->get_history_leaveList();
            $this->data['calender_list'] = $this->leave_model->get_calender_leaveList();

            foreach($this->data['history_list'] as $row){
                $row->status = $this->employment_json_model->get_action_name($row->status);
            }

            $this->page_construct('index_admin.php', $this->meta, $this->data);
        }
        
    }

    public function get_the_balance()
    {
        $form_data = $this->input->post();

        $employee_id = $form_data['employee_id'];
        $type_of_leave_id = $form_data['type_of_leave_id'];

        $q2 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE employee_id='". $employee_id ."' AND type_of_leave_id = '". $type_of_leave_id ."' AND year(last_updated) = YEAR(CURDATE()) AND last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = ". $employee_id ." AND type_of_leave_id = ".$type_of_leave_id.")");

        if($q2->num_rows())
        {
            $annual_leave_days = $q2->result()[0];
        }
        else
        {
            $annual_leave_days = FALSE;
        }

        echo json_encode(array($annual_leave_days));
    }

    public function apply_leave($leave_id = NULL, $status = NULL)
    {   
        // $this->load->library('mybreadcrumb');
        // $this->mybreadcrumb->add('Create Interview', base_url('Create_Interview'));

        // $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        $this->meta['page_name'] = 'Apply Leave';

        if(!$leave_id == NULL){
            $leave_details = $this->leave_model->get_leave_details($leave_id);
        }
        
        // echo json_encode($leave_details);

        if($leave_id == NULL){
            $leave_array = array(
                'employee_id'=> $this->employee_id,
                'user_id'    => $this->user_id,
                'leave_id'   => '',
                'start_date' => '',
                'start_time' => '',
                'end_date'   => '',
                'end_time'   => '',
                'total_leave_apply'  => 0,
                'status'     => '',
                // 'days_left_before'   => 
                // 'total_remaining_al' => $this->remaining_al
            );
        }else{
            $leave_array = array(
                'employee_id'=> $leave_details[0]->employee_id,
                'user_id'    => $leave_details[0]->user_id,
                'leave_id'   => $leave_details[0]->id,
                'type_of_leave_id'   => $leave_details[0]->type_of_leave_id,
                'balance_before_approve'   => $leave_details[0]->balance_before_approve,
                'start_date' => date('m/d/Y', strtotime($leave_details[0]->start_date)),
                'start_time' => $leave_details[0]->start_time,
                'end_date'   => date('m/d/Y', strtotime($leave_details[0]->end_date)),
                'end_time'   => $leave_details[0]->end_time,
                'total_leave_apply'  => $leave_details[0]->total_days,
                'status'     => $leave_details[0]->status,
                // 'total_remaining_al' => $leave_details->days_left_after
            );
        }

        $this->data['leave_data']      = $leave_array;
        $this->data['start_time_list'] = $this->holiday_model->get_Leave_StartTime($leave_array['employee_id']);
        $this->data['end_time_list']   = $this->holiday_model->get_Leave_EndTime($leave_array['employee_id']);
        $this->data['active_type_of_leave'] = $this->leave_model->get_active_type_of_leave_list($leave_array['employee_id']);
        $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        $this->data['status'] = $status;

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Leave', base_url('leave'));
        $this->mybreadcrumb->add('Apply Leave', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('leave/apply_leave.php', $this->meta, $this->data);
    }

    public function submit_leave(){
        $form_data = $this->input->post();

        // echo date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date'])));

        $leave_data = array(
            'id'         => $form_data['leave_id'],
            'employee_id'=> $form_data['employee_id'],
            'type_of_leave_id'=> $form_data['type_of_leave_id'],
            'balance_before_approve'=> $form_data['balance'],
            'start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_start_date']))),
            'start_time' => $form_data['leave_start_time'],
            'end_date'   => date('Y-m-d', strtotime(str_replace('/', '-', $form_data['leave_end_date']))),
            'end_time'   => $form_data['leave_end_time'],
            'total_days' => $form_data['leave_total_days'],
            'status'     => 1
        );

        $result = $this->leave_model->apply_leave($leave_data);

        echo json_encode($result[0]);
    }

    public function calculate_working_days(){
        
        $form_data = $this->input->post();

        $start_date   = strtotime($form_data['start_date']);
        $end_date     = strtotime($form_data['end_date']);

        $start_time = $form_data['start_time'];
        $end_time   = $form_data['end_time'];

        $total_days = date_diff(date_create($form_data['start_date']), date_create($form_data['end_date']))->days + 1;

        $total_working_days = 0;

        $date     = $start_date;

        $addDay   = 86400;    // 1 day = 86400 seconds
        $holidays = $this->holiday_model->getAllHolidays();     // get all defined holiday. New holiday can be added in block_holiday module.

        for($i=0; $i<$total_days; $i++){
            $d = date('w', ($date));

            if($d != 0 && $d != 6) {
                $total_working_days++;

                foreach($holidays as $holiday){
                    if(strtotime(date_format(date_create($holiday->holiday_date), 'Y-m-d')) == $date){
                        $total_working_days--;
                    }
                }
            }
            $date = $date + $addDay;
        }

        if(date('w', ($start_date))!= 0 && date('w', ($start_date))!= 6){

            if(!strcmp($start_time, '13:00')){
                $total_working_days -= 0.5;
            }

            if(!strcmp($end_time, '13:00')){
                $total_working_days -= 0.5;
            }

        }

        $return_data = (object)array(
            'total_working_days' => $total_working_days,
            'total_remaining_al' => $this->remaining_al - $total_working_days
        );

        echo json_encode($return_data);
    }

    public function change_status(){
        $form_data = $this->input->post();
        $employee_id = $form_data['employee_id'];
        $type_of_leave_id = $form_data['type_of_leave_id'];

        // To get the last remaining annual leave left
        $q = $this->db->query("SELECT * FROM payroll_employee_annual_leave eal_1 WHERE eal_1.last_updated = (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id=" . $employee_id . " AND eal_2.type_of_leave_id = ".$type_of_leave_id.") AND eal_1.type_of_leave_id = ".$type_of_leave_id."");

        $data = array();

        if($form_data['is_approve']){

            array_push($data, array(
                'id' => $form_data['leave_id'],
                'status' => 2,
                'status_updated_by' => date('Y-m-d H:i:s'),
                'al_left_before' => $q->result()[0]->annual_leave_days,
                'al_left_after' => $q->result()[0]->annual_leave_days
            ));
        }else {
            array_push($data, array(
                'id' => $form_data['leave_id'],
                'status' => 3,
                'status_updated_by' => date('Y-m-d H:i:s'),
                'al_left_before' => $q->result()[0]->annual_leave_days,
                'al_left_after' => $q->result()[0]->annual_leave_days
            ));
        }

        $result = $this->leave_model->update_status($data, $form_data['is_approve'], $employee_id, $type_of_leave_id);

        echo $result;
        // echo $form_data['is_approve'];
    }

    public function withdraw_leave()
    {
        $form_data = $this->input->post();
        $leave_id = $form_data['leave_id'];
        $employee_id = $form_data['employee_id'];
        $total_days = $form_data['total_days'];
        $type_of_leave_id = $form_data['type_of_leave_id'];
        $status_id = $form_data['status_id'];

        if($status_id == 2)
        {
            $q = $this->db->query("SELECT * FROM payroll_employee_annual_leave eal_1 WHERE eal_1.last_updated = (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id=" . $employee_id . " AND eal_2.type_of_leave_id = ".$form_data['type_of_leave_id'].") AND eal_1.type_of_leave_id = ".$form_data['type_of_leave_id']."");

            if($q->num_rows())
            {
                $q = $q->result_array();

                $final_data = array(
                    'employee_id' => $employee_id,
                    'type_of_leave_id' => $type_of_leave_id,
                    'annual_leave_days' => round($q[0]["annual_leave_days"] + $total_days, 1)
                );

                $this->db->insert('payroll_employee_annual_leave', $final_data);
            }
        }

        $payroll_leave_data = array(
            'status' => 4,
        );

        $this->db->where('id', $leave_id);
        $this->db->update('payroll_leave', $payroll_leave_data);

        echo json_encode("success");
    }

}