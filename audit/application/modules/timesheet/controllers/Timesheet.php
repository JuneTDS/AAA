<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Timesheet extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }

        $this->load->library(array('session','parser'));
        $this->load->helper("file");
        $this->load->helper(array('form', 'url'));
        $this->load->model('holiday_model');
        $this->load->model('timesheet_model');
        $this->load->model('employee/employee_model');
        $this->load->model('employment_json_model');

        if($this->user_group_name != 'admin'){
            $this->employee_id  = $this->employee_model->get_employee_id_from_user_id($this->user_id);
        }

        $this->meta['page_name'] = 'Timesheet';
    }

    public function index()
    {   
        if($this->user_group_name != 'admin'){
            $this->data['timesheet_list'] = $this->timesheet_model->get_employee_timesheet($this->employee_id);

            foreach($this->data['timesheet_list'] as $row){
                $row->status_id = $this->employment_json_model->get_timesheet_action_name($row->status_id);
            }

            $this->page_construct('index.php', $this->meta, $this->data);
        }else{
            $this->data['timesheet_list'] = $this->timesheet_model->get_all_timesheet();
            $this->data['years']          = $this->timesheet_model->get_years();

            foreach($this->data['timesheet_list'] as $row){
                $row->status_id = $this->employment_json_model->get_timesheet_action_name($row->status_id);
            }

            $this->page_construct('index_admin.php', $this->meta, $this->data);
        }
    }

    // public function index_admin()
    // {   
    //     $this->data['timesheet_list'] = $this->timesheet_model->get_employee_timesheet($this->employee_id);
    //     $this->data['years']          = $this->timesheet_model->get_years();

    //     foreach($this->data['timesheet_list'] as $row){
    //         $row->status_id = $this->employment_json_model->get_timesheet_action_name($row->status_id);
    //     }

    //     $this->page_construct('index_admin.php', $this->meta, $this->data);
    // }

    public function timesheet_tr_partial(){
        $form_data = $this->input->post();
        $this->data['timesheet'] = $form_data['data'];
        
        $this->data['timesheet']['status_id'] = $this->employment_json_model->get_timesheet_action_name($this->data['timesheet']['status_id']);

        $this->load->view('timesheet_tr_partial', $this->data);
        // echo json_encode($this->data['timesheet']);
    }

    public function create(){
        $this->data['employee_id']    = $this->employee_id;
        $this->data['timesheet_list'] = $this->timesheet_model->get_employee_timesheet($this->employee_id);

        $this->page_construct('create.php', $this->meta, $this->data);
    }

    public function edit($timesheet_id){
        $this->data['timesheet']             = $this->timesheet_model->get_timesheet($timesheet_id);
        $this->data['timesheet_status_name'] = $this->employment_json_model->get_timesheet_action_name($this->data['timesheet'][0]->status_id);

        $date = $this->data['timesheet'][0]->month;

        $this_month_date_days = $this->set_arrayObj_date_days($date);
        
        $header_setting = $this->setup_header_setting($this_month_date_days);

        // set header and if column is readonly
        $this->data['header'] = $header_setting[0];
        $this->data['array_header_col_readonly'] = $header_setting[1];

        $this->page_construct('edit.php', $this->meta, $this->data);
    }

    public function create_timesheet(){
        $form_data = $this->input->post();

        $data = array(
            'employee_id' => $form_data['employee_id'],
            'month'       => date('Y-m-d', strtotime('01 ' . $form_data['timesheet_month'])),
            'status_id'   => 1
        );

        $result = $this->timesheet_model->create_timesheet($data);

        echo $result;
    }

    public function get_month(){
        $form_data = $this->input->post();

        $year = $form_data['year'];

        $months = $this->timesheet_model->get_months_from_this_year($year);

        echo json_encode($months);
    }

    public function get_list_from_year_month(){
        $form_data = $this->input->post();

        $year  = $form_data['year'];
        $month = $form_data['month'];

        $list = $this->timesheet_model->get_list_from_year_month($year, $month);

        echo json_encode($list);
    }

    public function setup_header_setting($this_month_date_days){
        // setup header
        $i = 1;
        $header = array("Activities");
        $array_header_col_readonly = array(array("data" => "Activities"));

        foreach($this_month_date_days as $row){
            $is_public_holiday = $this->holiday_model->is_public_holiday($row['date']);
            // print_r($is_public_holiday . "<br>");

            array_push($header, (string)$i);
            if($row[$i] == 6 || $row[$i] == 7 || $is_public_holiday){   // set if header and if column is readonly 
                array_push($array_header_col_readonly, array(
                    "data"      => (string)$i,
                    "readOnly"  => true
                ));
            }else{
                array_push($array_header_col_readonly, array(
                    "data"      => (string)$i
                ));
            }

            $i++;
        }

        array_push($array_header_col_readonly, 
            array("data" => "current", "readOnly" => true),
            array("data" => "b/f"),
            array("data" => "total", "readOnly" => true)
        );
        array_push($header, "current", "b/f", "total");

        return [$header, $array_header_col_readonly];
    }

    public function set_arrayObj_date_days($date){
        $this_month_date_days  = array();
        $month = date('m', strtotime($date));
        $year  = date('Y', strtotime($date));

        $days_in_month = date("t", strtotime($date));  // get total no. of days in this month

        // get dates and days in this month
        for($d=1; $d<=$days_in_month; $d++)
        {
            $time = mktime(12, 0, 0, $month, $d, $year);

            if (date('m', $time) == $month){
                array_push($this_month_date_days, 
                    array(
                        (int)(date('d', $time)) => date('N', $time),
                        'date'                  => $year . "-" . $month . "-" . date('d', $time)
                    )
                );
            }
        }

        return $this_month_date_days;
    }

    public function save_timesheet(){
        $form_data = $this->input->post();
        $timesheet_id = $form_data['timesheet_id'];

        $data = array(
            'content' => json_encode($form_data['data'])
        );

        $result = $this->timesheet_model->edit_timesheet($data, $timesheet_id);

        echo $result;
    }

    public function submit_timesheet(){
        $form_data = $this->input->post();
        $timesheet_id = $form_data['timesheet_id'];

        $data = array(
            "status_id" => 2
        );

        $result = $this->timesheet_model->edit_timesheet($data, $timesheet_id);

        echo $result;
    }
}