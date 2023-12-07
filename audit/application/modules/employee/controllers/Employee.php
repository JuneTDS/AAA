<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }

        // if(!$this->data['Admin']){
        //     redirect('welcome');
        // }

        $this->load->library('form_validation');
        $this->load->library(array('session','parser'));
        $this->load->model('employee/employee_model');
        $this->load->model('employment_json_model');
        $this->load->model('country_json_model');
        $this->load->model('firm/master_model');
        $this->load->model('offer_letter/offer_letter_model');
        $this->load->model('auth/auth_model');
        $this->load->model('setting/setting_model');

        
    }

    public function index()
    {   
        $this->meta['page_name'] = 'Employee';
        if(!$this->data['Admin'] && !$this->data['Manager']){
            $this->data['staff_list'] = $this->employee_model->get_employeeList($this->user_id);
        }
        else
        {
            $this->data['staff_list'] = $this->employee_model->get_employeeList();
        }

        $this->page_construct('index.php', $this->meta, $this->data);
    }

    public function create()
    {   
        $this->meta['page_name'] = 'Create Employee';
        $this->data['nationality_list'] = $this->country_json_model->getNationality();
        $this->data['workpass_list']    = $this->employment_json_model->get_workpass_details();
        $this->data['firm_list']        = $this->master_model->get_firm_dropdown_list();
        $this->data['status_list']      = $this->employee_model->get_employeeStatusList();
        $this->data['department_list']  = $this->employee_model->get_employeeDepartment();
        $this->data['create']           = true;
        $this->data['type_of_leave_list'] = $this->setting_model->get_type_of_leave_list();
        
        // if($staff_id != null){
        //     $this->data['staff'] = $this->employee_model->get_staff_info($staff_id);
        //     // echo json_encode($this->employee_model->get_staff_info($staff_id));
        // }
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Employee', base_url('employee'));
        $this->mybreadcrumb->add('Create Employee', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('employee/create.php', $this->meta, $this->data);
    }

    public function edit($staff_id = NULL)
    {   
        $this->meta['page_name'] = 'Edit Employee';

        $this->data['nationality_list'] = $this->country_json_model->getNationality();
        $this->data['workpass_list']    = $this->employment_json_model->get_workpass_details();
        $this->data['firm_list']        = $this->master_model->get_firm_dropdown_list();
        $this->data['status_list']      = $this->employee_model->get_employeeStatusList();
        $this->data['department_list']  = $this->employee_model->get_employeeDepartment();
        $this->data['create']           = false;
        $this->data['type_of_leave_list'] = $this->setting_model->get_type_of_leave_list();

        if($staff_id != null){
            $this->data['staff'] = $this->employee_model->get_staff_info($staff_id);
            $this->data['family_info'] = $this->employee_model->get_family_info($staff_id);
            $this->data['active_type_of_leave'] = $this->employee_model->get_active_type_of_leave($staff_id);
        }

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Employee', base_url('employee'));
        $this->mybreadcrumb->add('Edit Employee - '.$this->data['staff'][0]->name, base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('create.php', $this->meta, $this->data);
    }

    public function create_employee(){
        $form_data = $this->input->post();
        //Attach Singapore PR
        $filesCount = count($_FILES['attachment_singapore_pr']['name']);
        $singapore_pr_attachment = array();
        for($i = 0; $i < $filesCount; $i++)
        {   
            $_FILES['uploadimage_singapore_pr']['name'] = $_FILES['attachment_singapore_pr']['name'][$i];
            $_FILES['uploadimage_singapore_pr']['type'] = $_FILES['attachment_singapore_pr']['type'][$i];
            $_FILES['uploadimage_singapore_pr']['tmp_name'] = $_FILES['attachment_singapore_pr']['tmp_name'][$i];
            $_FILES['uploadimage_singapore_pr']['error'] = $_FILES['attachment_singapore_pr']['error'][$i];
            $_FILES['uploadimage_singapore_pr']['size'] = $_FILES['attachment_singapore_pr']['size'][$i];

            $uploadPath = './uploads/singapore_pr';
            $config['upload_path'] = $uploadPath;
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = '*';
            
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if($this->upload->do_upload('uploadimage_singapore_pr'))
            {
                $fileData = $this->upload->data();
                $singapore_pr_attachment[] = $fileData['file_name'];
            }

            $attachment = json_encode($singapore_pr_attachment);
        }
        if($form_data['hidden_attachment_singapore_pr'] != "")
        {
            $attachment_singapore_pr = $form_data['hidden_attachment_singapore_pr'];
        }
        else
        {
            $attachment_singapore_pr = $attachment;
        }
        //Attach NRIC/Passport No
        $filesCount = count($_FILES['attachment_nric']['name']);
        $nric_attachment = array();
        for($i = 0; $i < $filesCount; $i++)
        {   
            $_FILES['uploadimage_nric']['name'] = $_FILES['attachment_nric']['name'][$i];
            $_FILES['uploadimage_nric']['type'] = $_FILES['attachment_nric']['type'][$i];
            $_FILES['uploadimage_nric']['tmp_name'] = $_FILES['attachment_nric']['tmp_name'][$i];
            $_FILES['uploadimage_nric']['error'] = $_FILES['attachment_nric']['error'][$i];
            $_FILES['uploadimage_nric']['size'] = $_FILES['attachment_nric']['size'][$i];

            $uploadPath = './uploads/nric';
            $config['upload_path'] = $uploadPath;
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = '*';
            
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if($this->upload->do_upload('uploadimage_nric'))
            {
                $fileData = $this->upload->data();
                $nric_attachment[] = $fileData['file_name'];
            }

            $attachment = json_encode($nric_attachment);
        }
        if($form_data['hidden_attachment_nric'] != "")
        {
            $attachment_nric = $form_data['hidden_attachment_nric'];
        }
        else
        {
            $attachment_nric = $attachment;
        }
        //Attach Marital Status
        $filesCount = count($_FILES['attachment_marital_status']['name']);
        $marital_status_attachment = array();
        for($i = 0; $i < $filesCount; $i++)
        {   
            $_FILES['uploadimage_marital_status']['name'] = $_FILES['attachment_marital_status']['name'][$i];
            $_FILES['uploadimage_marital_status']['type'] = $_FILES['attachment_marital_status']['type'][$i];
            $_FILES['uploadimage_marital_status']['tmp_name'] = $_FILES['attachment_marital_status']['tmp_name'][$i];
            $_FILES['uploadimage_marital_status']['error'] = $_FILES['attachment_marital_status']['error'][$i];
            $_FILES['uploadimage_marital_status']['size'] = $_FILES['attachment_marital_status']['size'][$i];

            $uploadPath = './uploads/marital_status';
            $config['upload_path'] = $uploadPath;
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = '*';
            
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if($this->upload->do_upload('uploadimage_marital_status'))
            {
                $fileData = $this->upload->data();
                $marital_status_attachment[] = $fileData['file_name'];
            }

            $attachment = json_encode($marital_status_attachment);
        }
        if($form_data['hidden_attachment_marital_status'] != "")
        {
            $attachment_marital_status = $form_data['hidden_attachment_marital_status'];
        }
        else
        {
            $attachment_marital_status = $attachment;
        }

        $employee = array(
            'id'                => $form_data['staff_id'],
            'name'              => strtoupper($form_data['staff_name']),
            'nric_fin_no'       => strtoupper($form_data['staff_nric_finno']),
            'singapore_pr'      => $form_data['hidden_singapore_pr'],
            'attachment_singapore_pr' => $attachment_singapore_pr,
            'attachment_nric'   => $attachment_nric,
            'address'           => strtoupper($form_data['staff_address']),
            'phoneno'           => $form_data['staff_phoneno'],
            'nationality_id'    => $form_data['staff_nationality'],
            'dob'               => date('Y-m-d', strtotime($form_data['staff_DOB'])),
            'marital_status'      => $form_data['hidden_marital_status'],
            'attachment_marital_status' => $attachment_marital_status,
            'firm_id'           => $form_data['firm_id'],
            'date_joined'       => empty($form_data['staff_joined'])? NULL:date('Y-m-d', strtotime($form_data['staff_joined'])),
            'date_cessation'    => empty($form_data['staff_cessation'])? NULL:date('Y-m-d', strtotime($form_data['staff_cessation'])),
            'designation'       => strtoupper($form_data['staff_designation']),
            'department'        => $form_data['staff_department'],
            'salary'            => $form_data['staff_salary'],
            'workpass'          => $form_data['staff_workpass'],
            'pass_expire'       => empty($form_data['staff_pass_expire'])? NULL:date('Y-m-d', strtotime($form_data['staff_pass_expire'])),
            //'annual_leave_year' => $form_data['staff_annual_leave'],
            'aws_given'         => $form_data['hidden_staff_aws_given'],
            'cpf_employee'      => $form_data['staff_cpf_employee'],
            'cpf_employer'      => $form_data['staff_cpf_employer'],
            'cdac'              => $form_data['staff_cdac'],
            'remark'            => $form_data['staff_remark'],
            'supervisor'        => $form_data['staff_supervisor'],
            'employee_status_id'=> $form_data['staff_status'],
            'date_of_letter'       => empty($form_data['date_of_letter'])? NULL:date('Y-m-d', strtotime($form_data['date_of_letter'])),
            'status_date'       => empty($form_data['status_date'])? NULL:date('Y-m-d', strtotime($form_data['status_date']))
        );

        $annual_leave = $form_data['active'];
        $annual_leave_days = $form_data['leave_days'];
        $previous_staff_status = $form_data['previous_staff_status'];
        $previous_status_date = $form_data['previous_status_date'];
        //$date_join = date('Y-m-d', strtotime($form_data['staff_joined']));
        // echo date('Y-m-d', strtotime($form_data['staff_DOB']));
        // return $employee_id = $this->employee_model->create_employee($employee);
        $result = $this->employee_model->create_employee($employee, $annual_leave, $annual_leave_days, $previous_staff_status, $previous_status_date);

        echo json_encode(array($result));

        // echo json_encode($employee);
    }

    public function create_user($employee_id){ 

        $this->meta['page_name'] = 'Create User';
        $this->session->set_flashdata('employee_id', $employee_id);
        $this->data['result_status'] = false;

        $this->data['users_group_dropdown'] = $this->auth_model->get_users_group_dropdown();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Employee', base_url('employee'));
        $this->mybreadcrumb->add('Create User', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('auth/create_user_account.php', $this->meta, $this->data);
    }

    public function view_offer_letter(){
        $form_data = $this->input->post();
        // echo json_encode($form_data);
        $employee_id = $form_data['employee_id'];

        $data = $this->offer_letter_model->getEmployee_OL($employee_id);

        $count_offer_letter = $this->db->query("SELECT * FROM payroll_offer_letter_employee ole WHERE ole.employee_id='". $employee_id ."'");
        
        if(count($count_offer_letter->result()) > 1){
            $data[0]->is_employee = true;
        }else{
            $data[0]->is_employee = false;
        }
        
        $data[0]->is_pr_singaporean = false;

        if($data[0]->nationality_id == 165){
            $data[0]->is_pr_singaporean = true;
        }

        $offer_letter_pdf = modules::load('offer_letter/Offer_letter/');
        $return_data      = $offer_letter_pdf->info($data);

        echo $return_data;
        // echo json_encode($data);
    }

    public function get_nationality(){
        $data = $this->country_json_model->getNationality();

        echo json_encode($data);
    }

    public function get_family_relationship(){
        $data = $this->country_json_model->getFamilyRelationship();

        echo json_encode($data);
    }

    public function check_verify_family(){
        $checked = $_POST["checked"];
        $staff_id = $_POST["staff_id"];
        $family_info_id = $_POST["family_info_id"];

        if($checked == "false")
        {
            $data["verify"] = 0;

            $this->db->update("payroll_family_info", $data, array("employee_id" => $staff_id, "id" => $family_info_id));

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
        else
        {
            $data["verify"] = 1;

            $this->db->update("payroll_family_info", $data, array("employee_id" => $staff_id, "id" => $family_info_id));

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
    }

    public function add_family_info(){
        for($i = 0; $i < count($_POST['family_info_id']); $i++ )
        {   
            //Proof of Document
            $filesCount = count($_FILES['attachment_proof_of_document']['name']);
            $proof_of_document_attachment = array();
            for($a = 0; $a < $filesCount; $a++)
            {   
                $_FILES['uploadimage_proof_of_document']['name'] = $_FILES['attachment_proof_of_document']['name'][$a];
                $_FILES['uploadimage_proof_of_document']['type'] = $_FILES['attachment_proof_of_document']['type'][$a];
                $_FILES['uploadimage_proof_of_document']['tmp_name'] = $_FILES['attachment_proof_of_document']['tmp_name'][$a];
                $_FILES['uploadimage_proof_of_document']['error'] = $_FILES['attachment_proof_of_document']['error'][$a];
                $_FILES['uploadimage_proof_of_document']['size'] = $_FILES['attachment_proof_of_document']['size'][$a];

                $uploadPath = './uploads/proof_of_document';
                $config['upload_path'] = $uploadPath;
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = '*';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('uploadimage_proof_of_document'))
                {
                    $fileData = $this->upload->data();
                    $proof_of_document_attachment[] = $fileData['file_name'];
                }

                $attachment = json_encode($proof_of_document_attachment);
            }

            if($_POST['hidden_attachment_proof_of_document'] != "")
            {
                $attachment_proof_of_document = $_POST['hidden_attachment_proof_of_document'];
            }
            else
            {
                $attachment_proof_of_document = $attachment;
            }

            $data['employee_id'] = $_POST['employee_id'][$i];
            //$data['family_info_id']=$_POST['family_info_id'][$i];
            $data['family_name']=strtoupper($_POST['family_name'][$i]);
            $data['nric']=strtoupper($_POST['nric'][$i]);
            $data['dob']=empty($_POST['dob'][$i])? NULL:date('Y-m-d', strtotime($_POST['dob'][$i]));
            $data['nationality']=$_POST['nationality'][$i];
            $data['relationship']=$_POST['relationship'][$i];
            $data['contact']=$_POST['contact'][$i];
            $data['proof_of_document']=$attachment_proof_of_document;

            $q = $this->db->get_where("payroll_family_info", array("id" => $_POST['family_info_id'][$i]));

            if (!$q->num_rows())
            {   
                $this->db->insert("payroll_family_info",$data);
                $insert_family_info_id = $this->db->insert_id();

                echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_family_info_id" => $insert_family_info_id));
            }
            else
            {
                $this->db->update("payroll_family_info",$data,array("id" => $_POST['family_info_id'][$i]));

                echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
            }
        }
    }

    public function delete_family_info ()
    {
        $id = $_POST["family_info_id"];

        $data["deleted"] = 1;

        $this->db->update("payroll_family_info", $data, array('id'=>$id));

        echo json_encode(array("Status" => 1));
                
    }
}