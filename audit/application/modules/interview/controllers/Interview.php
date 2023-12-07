<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Interview extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
        
        if(!$this->data['Admin']){
            redirect('welcome');
        }
        
        $this->load->helper(array('form', 'url', 'security'));
        $this->load->library('form_validation');
        $this->load->library(array('session','parser'));
        $this->load->model('interview_model');
        $this->load->model('applicant/applicant_model');
        $this->load->model('firm/master_model');
        $this->load->model('actions_json_model');
        $this->load->model('day_time_json_model');
        $this->load->model('country_json_model');
        $this->load->model('personal_json_model');

        $this->meta['page_name'] = 'Interview';
    }

    public function index()
    {   
        // $this->load->library('mybreadcrumb');
        // $this->mybreadcrumb->add('Applicant', base_url('Applicant'));

        // $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->data['interview_list'] = $this->interview_model->get_interviewList();
        $this->data['interview_status'] = $this->actions_json_model->get_interview_status();
        $this->data['interview_result'] = $this->actions_json_model->get_interview_result();
        // foreach($this->data['interview_list'] as $item){
        //     $item->status = $this->actions_json_model->get_interview_status_name($item->status);
        // }

        // $this->page_construct('interview/index.php', $meta, $this->data);
        $this->page_construct('index.php', $this->meta, $this->data);
        // $meta['logo']       = 'assets/logo/logo.png';
        // $meta['page_name']  = '';

        // $footer['project_name'] = "PAYROLL SYSTEM";

        // echo modules::run('interview/index'); 

        // $this->_render_page('interview/index', $this->data);
        
        // $this->load->view('header', $meta);
        // $this->load->view('index');
        // $this->load->view('footer', $footer);
    }

    public function applicant_profile($applicant_id){
        $this->data['applicant_id'] = $applicant_id;

        $this->data['applicant_profile'] = $this->applicant_model->get_applicant($applicant_id);
        $this->data['education']         = $this->applicant_model->get_applicant_education($applicant_id);
        $this->data['experience']        = $this->applicant_model->get_applicant_experience($applicant_id);
        $this->data['professional']      = $this->applicant_model->get_applicant_professional($applicant_id);
        $this->data['referral']          = $this->applicant_model->get_applicant_referral($applicant_id);
        $this->data['language']          = $this->applicant_model->get_applicant_language($applicant_id);

        foreach($this->data['education'] as $row){
            $row->graduate_month   = $this->day_time_json_model->getMonth_name($row->graduate_month);
            $row->uni_country      = $this->country_json_model->get_country_name($row->uni_country);
            $row->uni_fieldOfStudy = $this->personal_json_model->getFieldOfStudy_name($row->uni_fieldOfStudy);
        }

        foreach($this->data['experience'] as $row){
            $row->join_month     = $this->day_time_json_model->getMonth_name($row->join_month);
            $row->country        = $this->country_json_model->get_country_name($row->country);
            $row->position_level = $this->personal_json_model->getPosition_level_name($row->position_level);
        }

        // $this->page_construct('view_applicant_profile.php', $this->meta, $this->data);
        $this->page_construct('applicant/applicant_profile/index', $this->meta, $this->data);
    }

    public function interviewList(){
        echo json_encode($this->interview_model->get_interviewList());
    }

    public function create()
    {   
        // $this->load->library('mybreadcrumb');
        // $this->mybreadcrumb->add('Create Interview', base_url('Create_Interview'));

        // $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        $this->meta['page_name'] = 'Create Interview';

        $this->data['firm_list'] = $this->master_model->get_firm_dropdown_list();

        $this->page_construct('interview/create_interview.php', $this->meta, $this->data);
    }

    // public function edit($applicant_id = NULL)
    // {
    //     // if($applicant_id != null){
    //     //     // $this->data['staff'] = $this->employee_model->get_staff_info($staff_id);
    //     // }

    //     $this->interview_model->edit_interview($applicant_id);
        
    //     $this->page_construct('interview/create_interview.php', $this->meta, $this->data);
    // }

    public function edit_interview($interview_id){

        $this->meta['page_name'] = 'Edit Interview';

        $data = $this->interview_model->edit_interview($interview_id);

        $interview_data = array(
            'id' => $data->id,
            'applicant_id' => $data->applicant_id,
            'firm' => $data->firm,
            'applicant_name'    => $data->applicant_name,
            'applicant_email'   => $data->applicant_email,
            'interview_time'    => date('d F Y - h:s a', strtotime($data->interview_time)),
            'venue'   => $data->venue,
            'interview_num_valid_until'    => date('d F Y - h:s a', strtotime($data->interview_num_valid_until)),
            'interview_no'      => $data->interview_no
        );

        $this->data['interview_detail'] = $interview_data;
        $this->data['firm_list'] = $this->master_model->get_firm_dropdown_list();

        // echo json_encode($this->data['interview_detail']);

        $this->page_construct('interview/create_interview.php', $this->meta, $this->data);
    }

    public function interview_datetime($str)
    {
        $date_time = explode(' ',$str);
        if(sizeof($date_time)==2)
        {
        $date = $date_time[0];
        $date_values = explode('-',$date);
        if((sizeof($date_values)!=3) || !checkdate( (int) $date_values[1], (int) $date_values[2], (int) $date_values[0]))
        {
          $this->form_validation->set_message('interview_datetime', 'The date inside the Date of interview field is not valid.');
          return FALSE;
        }
        $time = $date_time[1];
        $time_values = explode(':',$time);
        if((int) $time_values[0]>23 || (int) $time_values[1]>59 || (int) $time_values[2]>59)
        {
          $this->form_validation->set_message('interview_datetime', 'The time inside the Date of interview field is not valid.');
          return FALSE;
        }
        return TRUE;
        }
        $this->form_validation->set_message('interview_datetime', 'The Date of interview field must have a DATETIME format.');
        return FALSE;
    }

    public function create_applicant(){
        $this->form_validation->set_rules('interview_company_name', 'Firm', 'required');
        $this->form_validation->set_rules('applicant_name', 'Name', 'required');
        $this->form_validation->set_rules('applicant_email', 'Email', 'required|is_unique[users.email]');
        $this->form_validation->set_rules('interview_datetime', 'Date of interview', 'trim|required');
        $this->form_validation->set_rules('venue', 'Venue', 'required');
        $this->form_validation->set_rules('interview_valid_datetime', 'Interview number valid until', 'required');

        if ($this->form_validation->run() == true)
        {
            $form_data = $this->input->post();

            // echo json_encode($form_data);

            $time = explode('-', $form_data['interview_datetime']);

            $interview_time = $time[0] . str_replace(" ", "", $time[1]);

            $interview_valid_datetime = explode('-', $form_data['interview_valid_datetime']);

            $interview_valid_date_time = $interview_valid_datetime[0] . str_replace(" ", "", $interview_valid_datetime[1]);

            $applicant = array(
                'id'    => $form_data['applicant_id'],
                'name'  => $form_data['applicant_name'],
                'email' => $form_data['applicant_email']
            );

            if($form_data['interview_id'] != "") {
                $interview = array(
                    'id'            => $form_data['interview_id'],
                    'interview_no'  => $form_data['interview_no'],
                    'interview_time'=> date('Y-m-d H:i:s', strtotime($interview_time)),
                    'venue' => $form_data['venue'],
                    'interview_num_valid_until' => date('Y-m-d H:i:s', strtotime($interview_valid_date_time)),
                    'firm'          => $form_data['interview_company_name']
                );
            }
            else{
                $interview = array(
                    'id'            => '',
                    'interview_no'  => uniqid(),    // generate random alphanumeric
                    'interview_time'=> date('Y-m-d H:i:s', strtotime($interview_time)),
                    'venue' => $form_data['venue'],
                    'interview_num_valid_until' => date('Y-m-d H:i:s', strtotime($interview_valid_date_time)),
                    'firm'          => $form_data['interview_company_name'],
                    //'expired_at'    => date('Y-m-d H:i:s', strtotime(date("Y-m-d") . " +48 hours")),
                    'status'        => '1',
                    'result'        => '1'
                );
            }
            // echo $interview['interview_time'];

            $applicant_id = $this->interview_model->create_applicant($applicant);

            // echo $applicant_id;

            if($applicant_id > 0){
                $interview_id = $this->interview_model->create_interview($interview);
            }

            if($interview_id > 0){
                $applicant_interview = array(
                    'applicant_id'  => $applicant_id,
                    'interview_id'  => $interview_id,
                    'status'        => 'pending'
                );

                $interview_id = $this->interview_model->create_applicant_interview($applicant_interview);
            }

            // $parse_data = array(
            //     'name'          => $applicant['name'],
            //     'link'          => base_url()."applicant/",
            //     'interview_no'  => $interview['interview_no'],
            //     'expired_by'    => $interview['expired_at']
            // );

            // $sendEmailStatus = $this->interview_model->sendInvitationEmail($parse_data, $applicant['email']);
            
            // if($sendEmailStatus){
            //     echo $interview['interview_no'];
            // }
            $error = array(
                'result'=> false
            );
            
            echo json_encode($error);
        }
        else
        {
            $error = array(
                'result'=> true,
                'interview_company_name' => strip_tags(form_error('interview_company_name')),
                'applicant_name' => strip_tags(form_error('applicant_name')),
                'applicant_email' => strip_tags(form_error('applicant_email')),
                'interview_datetime' => strip_tags(form_error('interview_datetime')),
                'venue' => strip_tags(form_error('venue')),
                'interview_valid_datetime' => strip_tags(form_error('interview_valid_datetime')),
            );

            echo json_encode($error);
        }

        // echo $interview['interview_no'];
    }

    public function change_interview_status(){
        $form_data = $this->input->post();

        $data = array(
            'status' => 2
        );

        echo $result = $this->interview_model->change_interview_status($data, $form_data['interview_id']);
    }

    public function change_interview_result(){
        $form_data = $this->input->post();

        $data = array(
            'result' => $form_data['result']
        );

        echo $result = $this->interview_model->change_interview_result($data, $form_data['interview_id']);
    }

    public function move_to_employee(){
        $form_data = $this->input->post();

        $interview_id = $form_data['interview_id'];

        $result = $this->interview_model->move_to_employee($interview_id);

        echo json_encode($result);
    }

    public function view_offer_letter(){
        $form_data = $this->input->post();
        $applicant_id = $form_data['applicant_id'];

        $data = $this->offer_letter_model->getApplicant_OL($applicant_id);

        $data[0]->is_employee = false;
        $data[0]->is_pr_singaporean = false;

        if($data[0]->nationality_id == 165){
            $data[0]->is_pr_singaporean = true;
        }   

        $offer_letter_pdf = modules::load('offer_letter/Offer_letter/');
        $return_data      = $offer_letter_pdf->info($data);

        echo $return_data;
    }
}