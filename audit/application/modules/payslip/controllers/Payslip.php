<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payslip extends MX_Controller
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
        $this->load->model('employee/employee_model');
        $this->load->model('payslip_model');
        // $this->load->module('payslip/create_document_pdf');
        // $this->load->library('controllers/createpayslippdf');

        if($this->user_group_name != 'admin'){
            $this->employee_id  = $this->employee_model->get_employee_id_from_user_id($this->user_id);
        }

        $this->meta['page_name'] = 'Payslip';
    }

    public function index()
    {   
        // $this->load->library('mybreadcrumb');
        // $this->mybreadcrumb->add('Employee', base_url('Employee'));

        // $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        if($this->user_group_name != 'admin'){
            $this->data['payslip_list']   = $this->payslip_model->get_list($this->employee_id);

            $available_month_list = array();

            $available_month_list[''] = "-- Select month --";

            foreach($this->data['payslip_list'] as $item){
                $available_month_list[$item->id] = date('F Y', strtotime($item->payslip_for));
            }

            $this->data['payslip_months'] = $available_month_list;

            $this->page_construct('index.php', $this->meta, $this->data);
        }else{
            $data = array(
                'date' => date('Y-m-d') // date of today
            );

            $this->data['payslip'] = $this->payslip_model->get_all_employee_list($data);
            $this->data['month_list'] = $this->payslip_model->get_all_months();

            $this->page_construct('index_admin.php', $this->meta, $this->data);
        }
    }

    //  merge with index after complete
    public function index_admin()
    {   
        // $this->load->library('mybreadcrumb');
        // $this->mybreadcrumb->add('Employee', base_url('Employee'));

        // $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $data = array(
            'date' => date('Y-m-d') // date of today
        );

        $this->data['payslip'] = $this->payslip_model->get_all_employee_list($data);
        $this->data['month_list'] = $this->payslip_model->get_all_months();

        // echo json_encode($this->data['month_list']);
        // echo json_encode($this->data['payslip']);
        // echo date('F Y');   // November 2018

        $this->page_construct('index_admin.php', $this->meta, $this->data);
    }

    public function payslip_settings(){

        $this->data['payslip_settings'] = $this->payslip_model->get_payslip_settings();

        $this->page_construct('settings.php', $this->meta, $this->data);
    }

    public function set_bonus($month = NULL){

        $this->data['selected_month'] = $month;

        $selected_month = array(
            'date' => $month
        );

        $this->data['payslip_list'] = $this->payslip_model->get_all_bonus_list($selected_month);

        $this->page_construct('set_bonus.php', $this->meta, $this->data);
    }

    public function set_bonus_tr_partial()
    {
        $data = $this->input->post();

        $this->data['count']         = $data['count'];
        $this->data['employee_name'] = $this->employee_model->get_employeeList_dropdown();

        if(!empty($data['bonus_details'])){
            $this->data['bonus_details'] = $data['bonus_details'];
        }

        $this->load->view('set_bonus_tr_partial', $this->data);
    }

    public function remove_bonus(){
        $data = $this->input->post();
        $payslip_id = $data['payslip_id'];

        $result = $this->payslip_model->remove_bonus($payslip_id);

        echo $result;
    }

    public function generate_payslip(){ 

        $data = $this->input->post();

        $result = $this->payslip_model->generate_all_payslip($data['selected_month']);

        echo $result;
    }

    public function getThisMonthPayslipList(){
        $form_data = $this->input->post();

        $this->data['payslip'] = $this->payslip_model->get_all_employee_list($form_data);

        echo json_encode($this->data);
    }

    public function view_payslip(){
        $form_data = $this->input->post();

        $payslip_id = $form_data['payslip_id'];

        $data = $this->payslip_model->view_payslip($payslip_id);

        $subtotal_salary     = $data->basic_salary + $data->aws + $data->bonus + $data->commission;
        $subtotal_less       = $data->cpf_employee + $data->cdac + $data->salary_advancement + $data->unpaid_leave;
        $subtotal_add        = $data->health_incentive + $data->other_incentive;
        $total_net_remun_pay = $subtotal_salary - $subtotal_less + $subtotal_add;
        $total_cpf           = $data->cpf_employer + $data->cpf_employee;

        $payslip_info = array(
            'payslip_for'          => date('F Y', strtotime($data->payslip_for)),
            'employee_name'        => $data->name,
            'nric'                 => $data->nric_fin_no,
            'date'                 => $data->date,
            'department'           => $data->department,
            'pv_no'                => $data->pv_no,
            'basic_salary'         => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->basic_salary,2,'.',',')),
            'aws'                  => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->aws,2,'.',',')),
            'bonus'                => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->bonus,2,'.',',')),
            'commission'           => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->commission,2,'.',',')),
            'subtotal_salary'      => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($subtotal_salary,2,'.',',')),    // subtotal salary
            'less_contribution'    => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->cpf_employee,2,'.',',')),
            'less_cdac'            => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->cdac,2,'.',',')),
            'less_salary_advance'  => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->salary_advancement,2,'.',',')),
            'less_unpaid_leave'    => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->unpaid_leave,2,'.',',')),
            'subtotal_less'        => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($subtotal_less * -1 ,2,'.',',')), // subtotal less
            'add_health_incentive' => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->health_incentive,2,'.',',')),
            'add_other_incentive'  => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->other_incentive,2,'.',',')),
            'subtotal_add'         => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($subtotal_add,2,'.',',')),        // subtotal add
            'total_net_remun_pay'  => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($total_net_remun_pay,2,'.',',')), // total net remuneration payable
            'cpf_employer'         => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->cpf_employer,2,'.',',')),
            'cpf_employee'         => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->cpf_employee,2,'.',',')),
            'total_cpf'            => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($total_cpf,2,'.',',')),
            'sd_levy'              => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->sd_levy,2,'.',',')),
            'remaining_days'       => $data->remaining_al,
            'payment_mode'         => $data->payment_mode
        );

        $payslip_pdf = modules::load('payslip/CreatePayslipPdf/');
        $return_data = $payslip_pdf->create_document_pdf($payslip_info);

        echo $return_data;
    }

    public function submit_bonus(){
        $form_data = $this->input->post();

        $bonus_data = array();

        $payslip_setting = $this->db->query('SELECT * FROM payslip_setting')->result()[0];

        $form_data['payslip_id']                       = array_values($form_data['payslip_id']);
        $form_data['payslip_employee_name']            = array_values($form_data['payslip_employee_name']);
        $form_data['payslip_employee_bonus']           = array_values($form_data['payslip_employee_bonus']);
        $form_data['payslip_employee_commission']      = array_values($form_data['payslip_employee_commission']);
        $form_data['payslip_employee_other_incentive'] = array_values($form_data['payslip_employee_other_incentive']);

        for($i = 0; $i < count($form_data['payslip_employee_name']); $i++) {

            $employee_list   = $this->employee_model->get_staff_info($form_data['payslip_employee_name'][$i]);

            $health_incentive = $form_data['hidden_payslip_employee_health_incentive'][$i] > 0 ? $payslip_setting->health_incentive: 0;
            $aws              = $form_data['hidden_payslip_employee_aws'][$i] > 0 ? $employee_list[0]->salary: 0;

            $temp = array(
                'id'                => $form_data['payslip_id'][$i],
                'employee_id'       => $form_data['payslip_employee_name'][$i],
                'payslip_for'       => $form_data['selected_month'],
                'basic_salary'      => $employee_list[0]->salary,
                'aws'               => $aws,
                'bonus'             => $form_data['payslip_employee_bonus'][$i],
                'commission'        => $form_data['payslip_employee_commission'][$i],
                'health_incentive'  => $health_incentive,
                'other_incentive'   => $form_data['payslip_employee_other_incentive'][$i]
            );

            array_push($bonus_data, $temp);
        }

        $result = $this->payslip_model->set_bonus_payslip($bonus_data);

        echo json_encode($result);
    }

    public function submit_settings(){
        $form_data = $this->input->post();

        $payslip_setting = array(
            'id'            => $form_data['payslip_setting_id'],
            'cdac'          => $form_data['payslip_setting_cdac'],
            'sdl'           => $form_data['payslip_setting_sdl'],
            'last_updated'  => date('Y-m-d H:i:s')
        );

        $payslip_setting_id = $this->payslip_model->save_payslip_setting($payslip_setting);

        // echo json_encode($payslip_setting_id);
    }
}