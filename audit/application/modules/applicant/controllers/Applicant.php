<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        // if (!$this->loggedIn) {
        //     $this->session->set_userdata('requested_page', $this->uri->uri_string());
        //     redirect('applicant/index');
        // }

        $this->load->library('form_validation');

        $this->load->model('Applicant_model');
        $this->load->model('Day_time_json_model');
        $this->load->model('Personal_json_model');
        $this->load->model('Country_json_model');
    }

    public function index()
    {
        $this->data['errorMsg'] = "";
        $this->data['page_title'] = "Interview";
        $this->data['site_name'] = "PAYROLL";

        $this->load->view('index', $this->data);
    }

    public function applicant_profile($applicant_id){

        $this->data['applicant_profile'] = $this->Applicant_model->get_applicant($applicant_id);
        // $this->data['education']         = $this->Applicant_model->get_applicant_education($applicant_id);
        // $this->data['experience']        = $this->Applicant_model->get_applicant_experience($applicant_id);
        // $this->data['professional']      = $this->Applicant_model->get_applicant_professional($applicant_id);
        // $this->data['referral']          = $this->Applicant_model->get_applicant_referral($applicant_id);
        // $this->data['language']          = $this->Applicant_model->get_applicant_language($applicant_id);

        // echo json_encode($this->data['education']);

        $this->load->view('applicant_profile/index', $this->data);
        // $this->load->view('applicant_profile', $this->data);
    }

    public function submit_interview_no(){
        $form_data      = $this->input->post();
        $interview_no   = $form_data['interview_no'];

        $result = json_decode($this->Applicant_model->check_interview_no($interview_no));
        $applicant_id = $result->applicant_id;

        if(!is_null($applicant_id)){
            $this->session->set_flashdata('applicant_id', $applicant_id);

            redirect('applicant/form/'.$applicant_id, 'refresh');
        }else{
            // $this->form_validation->set_message('errorMsg', 'error');
            $this->data['errorMsg'] = $result->error;

            $this->load->view('index', $this->data);
        }

        // $this->load->view($this->theme . 'applicant/form', $this->data);
        // 
    }

    public function success_msg(){
        $this->load->view($this->theme . 'applicant/success_message', $this->data);
    }

    public function form($applicant_id = NULL)
    {   
        $this->data['page_title']       = "Application Form";
        $this->data['nationality_list'] = $this->Country_json_model->getNationality();
        $this->data['gender']           = $this->Personal_json_model->getGender_dropdown();
        // $this->data['applicant_id']     = $this->session->flashdata('applicant_id');
        $this->data['applicant_id']     = $applicant_id;

        // $this->session->keep_flashdata($applicant_id);

        $this->data['applicant']    = $this->Applicant_model->get_applicant($applicant_id);
        $this->data['education']    = $this->Applicant_model->get_applicant_education($applicant_id);
        $this->data['experience']   = $this->Applicant_model->get_applicant_experience($applicant_id);
        $this->data['professional'] = $this->Applicant_model->get_applicant_professional($applicant_id);
        $this->data['referral']     = $this->Applicant_model->get_applicant_referral($applicant_id);
        $this->data['language']     = $this->Applicant_model->get_applicant_language($applicant_id);

        $this->load->view('form', $this->data);
    }

    public function education_partial()
    {   
        $data = $this->input->post();

        if(!empty($data['content'])){
            // echo json_encode($data['content']);
            $this->data['content'] = $data['content'];
        }

        $this->data['count']            = $data['count'];
        $this->data['months']           = $this->Day_time_json_model->getMonth_dropdown();
        $this->data['qualification']    = $this->Personal_json_model->getQualification_dropdown();
        $this->data['grade']            = $this->Personal_json_model->getGrade_dropdown();
        $this->data['fieldOfStudy']     = $this->Personal_json_model->getFieldOfStudy_dropdown();
        $this->data['country']          = $this->Country_json_model->getCountry_dropdown();

        // echo $this->data['fieldOfStudy'];
        $this->load->view('education_partial', $this->data);
    }

    public function experience_partial(){
        $data = $this->input->post();

        if(!empty($data['content'])){
            // echo json_encode($data['content']);

            $this->data['content'] = $data['content'];
        }

        $this->data['count']            = $data['count'];
        $this->data['months']           = $this->Day_time_json_model->getMonth_dropdown();
        $this->data['country']          = $this->Country_json_model->getCountry_dropdown();
        $this->data['currency']         = $this->Country_json_model->getCurrency_dropdown();
        $this->data['position_level']   = $this->Personal_json_model->getPosition_level_dropdown();
        
        // echo $this->data['position_level'][0];

        $this->load->view('experience_partial', $this->data);
    }

    public function add_language_tr_partial(){
        $data = $this->input->post();

        if(!empty($data['content'])){
            $this->data['content'] = $data['content'];
        }

        $this->data['count'] = $data['count'];
        $this->load->view('language_tr_partial', $this->data);
    }

    public function professional_partial(){
        $data = $this->input->post();

        if(!empty($data['content'])){
            // echo json_encode($data['content']);

            $this->data['content'] = $data['content'];
        }

        $this->data['count'] = $data['count'];
        $this->load->view('professional_partial', $this->data);
    }

    public function referral_partial(){
        $data = $this->input->post();

        if(!empty($data['content'])){
            // echo json_encode($data['content']);

            $this->data['content'] = $data['content'];
        }

        $this->data['count'] = $data['count'];
        $this->load->view('referral_partial', $this->data);
    }

    public function save_applicant(){
        $form_data  = $this->input->post();

        $applicant_id = $form_data['applicant_id'];

        // echo json_encode($form_data);

        // $date_array         = explode("/",$form_data['applicant_DOB']); // split the array
        // $var_day            = $date_array[0]; //day seqment
        // $var_month          = $date_array[1]; //month segment
        // $var_year           = $date_array[2]; //year segment
        // $applicant_dob      = "$var_year-$var_day-$var_month"; // join them together

        // echo $applicant_dob;

        // // echo json_encode($form_data);

        // // echo $_FILES['applicant_resume']['name'];

        // if(!empty($_FILES['applicant_resume']['name'])){
        //     // Set preference
        //     $config['upload_path'] = 'uploads/applicant_resume/'; 
        //     $config['allowed_types'] = 'pdf';
        //     $config['file_name'] = $_FILES['applicant_resume']['name'];

        //     //Load upload library
        //     $this->load->library('upload',$config); 

        //     // File upload
        //     if($this->upload->do_upload('applicant_resume')){
        //        // Get data about the file
        //        $uploadData = $this->upload->data();

        //        $applicant_resume_name = $uploadData['file_name'];
        //        // echo $uploadData['full_path'];
        //     }
        // }

        $applicant = array(
            'position'          => $form_data['applicant_position'],
            'name'              => $form_data['applicant_name'],
            'email'             => $form_data['applicant_email'],
            'phoneno'           => $form_data['applicant_phoneno'],
            'ic_passport_no'    => $form_data['applicant_ic_passport_no'],
            'nationality_id'    => $form_data['applicant_nationality'],
            'address'           => $form_data['applicant_address'],
            // 'postal_code'       => $form_data['applicant_postal_code'],
            // 'street_name'       => $form_data['applicant_street_name'],
            // 'building_name'     => $form_data['applicant_building_name'],
            // 'unit_no_floor'     => $form_data['applicant_unit_no1'],
            // 'unit_no'           => $form_data['applicant_unit_no2'],
            'dob'               => date('Y-d-m', strtotime(str_replace('/', '-', $form_data['applicant_DOB']))),
            'gender'            => $form_data['applicant_gender'],
            'skills'            => $form_data['applicant_skills'],
            'expected_salary'   => $form_data['applicant_expected_salary'],
            'last_drawn_salary' => $form_data['applicant_last_drawn_salary'],
            // 'uploaded_resume'   => $applicant_resume_name,
            'about'             => $form_data['applicant_about'],
            'pic'               => $form_data['applicant_preview_pic']
        );

        $applicant_resultMsg = $this->Applicant_model->save_applicant($applicant_id, $applicant);
        
        // save bundle of education to database
        $education = array();
        $education_resultMsg = true; // set as "true" so that the form can still be submited when there is no item added.

        $form_data['edu_id']               = array_values($form_data['edu_id']);
        $form_data['edu_uni_name']         = array_values($form_data['edu_uni_name']);
        $form_data['edu_graduate_month']   = array_values($form_data['edu_graduate_month']);
        $form_data['edu_graduate_year']    = array_values($form_data['edu_graduate_year']);
        $form_data['edu_qualification']    = array_values($form_data['edu_qualification']);
        $form_data['edu_uni_country']      = array_values($form_data['edu_uni_country']);
        $form_data['edu_uni_fieldOfStudy'] = array_values($form_data['edu_uni_fieldOfStudy']);
        $form_data['edu_major']            = array_values($form_data['edu_major']);
        $form_data['edu_grade']            = array_values($form_data['edu_grade']);
        $form_data['edu_cgpa']             = array_values($form_data['edu_cgpa']);
        $form_data['edu_total_cgpa']       = array_values($form_data['edu_total_cgpa']);
        $form_data['edu_add_Info']         = array_values($form_data['edu_add_Info']);

        // echo json_encode($form_data);

        for($i = 0; $i < count($form_data['edu_uni_name']); $i++) {

            $temp = array(
                'id'                => $form_data['edu_id'][$i],
                'applicant_id'      => $applicant_id,
                'uni_name'          => $form_data['edu_uni_name'][$i],
                'graduate_month'    => $form_data['edu_graduate_month'][$i],
                'graduate_year'     => $form_data['edu_graduate_year'][$i],
                'qualification'     => $form_data['edu_qualification'][$i],
                'uni_country'       => $form_data['edu_uni_country'][$i],
                'uni_fieldOfStudy'  => $form_data['edu_uni_fieldOfStudy'][$i],
                'major'             => $form_data['edu_major'][$i],
                'grade'             => $form_data['edu_grade'][$i],
                'score'             => $form_data['edu_cgpa'][$i],
                'total_score'       => $form_data['edu_total_cgpa'][$i],
                'additional_info'   => $form_data['edu_add_Info'][$i]
            );

            if(!$this->Applicant_model->insert_education($temp)){
                $education_resultMsg = false;
            }

            // array_push($education, $temp);
        }

        // if(count($education) > 0){
        //     // $education_resultMsg = $this->Applicant_model->insert_bundle_education($education);
        // }

        // save bundle of experience to database
        $experience = array();
        $experience_resultMsg = true; // set as "true" so that the form can still be submited when there is no item added.

        $form_data['exp_id']             = array_values($form_data['exp_id']);
        $form_data['exp_position']       = array_values($form_data['exp_position']);
        $form_data['exp_company']        = array_values($form_data['exp_company']);
        $form_data['exp_joined_month']   = array_values($form_data['exp_joined_month']);
        $form_data['exp_joined_year']    = array_values($form_data['exp_joined_year']);
        $form_data['exp_specialization'] = array_values($form_data['exp_specialization']);
        $form_data['exp_role']           = array_values($form_data['exp_role']);
        $form_data['exp_country']        = array_values($form_data['exp_country']);
        $form_data['exp_industry']       = array_values($form_data['exp_industry']);
        $form_data['exp_position_level'] = array_values($form_data['exp_position_level']);
        $form_data['exp_currency']       = array_values($form_data['exp_currency']);
        $form_data['exp_salary']         = array_values($form_data['exp_salary']);
        $form_data['exp_description']    = array_values($form_data['exp_description']);

        for($i = 0; $i < count($form_data['exp_position']); $i++) {

            $temp = array(
                'id'                        => $form_data['exp_id'][$i],
                'applicant_id'              => $applicant_id,
                'position'                  => $form_data['exp_position'][$i],
                'company_name'              => $form_data['exp_company'][$i],
                'join_month'                => $form_data['exp_joined_month'][$i],
                'join_year'                 => $form_data['exp_joined_year'][$i],
                'specialization'            => $form_data['exp_specialization'][$i],
                'role'                      => $form_data['exp_role'][$i],
                'country'                   => $form_data['exp_country'][$i],
                'industry'                  => $form_data['exp_industry'][$i],
                'position_level'            => $form_data['exp_position_level'][$i],
                'monthly_salary_currency'   => $form_data['exp_currency'][$i],
                'monthly_salary_amount'     => $form_data['exp_salary'][$i],
                'experience_description'    => $form_data['exp_description'][$i]
            );

            if(!$this->Applicant_model->insert_experience($temp)){
                $experience_resultMsg = false;
            }

            // array_push($experience, $temp);
        }

        // if(count($experience) > 0){
        //     // $experience_resultMsg = $this->Applicant_model->insert_bundle_experience($experience);
        // }

        // save bundle of language to database
        $language = array();
        $language_resultMsg = true; // set as "true" so that the form can still be submited when there is no item added.

        $form_data['lang_id']      = array_values($form_data['lang_id']);
        $form_data['lang_name']    = array_values($form_data['lang_name']);
        $form_data['lang_spoken']  = array_values($form_data['lang_spoken']);
        $form_data['lang_written'] = array_values($form_data['lang_written']);

        for($i = 0; $i < count($form_data['lang_name']); $i++) {

            $temp = array(
                'id'            => $form_data['lang_id'][$i],
                'applicant_id'  => $applicant_id,
                'name'          => $form_data['lang_name'][$i],
                'spoken'        => $form_data['lang_spoken'][$i],
                'written'       => $form_data['lang_written'][$i]
            );

            if(!$this->Applicant_model->insert_language($temp)){
                $language_resultMsg = false;
            }

            // array_push($language, $temp);
        }

        // if(count($language) > 0){
        //     // $language_resultMsg = $this->Applicant_model->insert_bundle_language($language);
        // }

        // save bundle of professional to database
        $professional = array();
        $professional_resultMsg = true; // set as "true" so that the form can still be submited when there is no item added.

        $form_data['pro_id']      = array_values($form_data['pro_id']);
        $form_data['pro_body']    = array_values($form_data['pro_body']);
        $form_data['pro_no']      = array_values($form_data['pro_no']);
        $form_data['pro_type']    = array_values($form_data['pro_type']);
        $form_data['pro_awarded'] = array_values($form_data['pro_awarded']);

        for($i = 0; $i < count($form_data['pro_body']); $i++) {

            $temp = array(
                'id'                 => $form_data['pro_id'][$i],
                'applicant_id'       => $applicant_id,
                'professional_body'  => $form_data['pro_body'][$i],
                'membership_no'      => $form_data['pro_no'][$i],
                'membership_type'    => $form_data['pro_type'][$i],
                'membership_awarded' => $form_data['pro_awarded'][$i]
            );

            if(!$this->Applicant_model->insert_professional($temp)){
                $professional_resultMsg = false;
            }

            // array_push($professional, $temp);
        }
        // echo json_encode($professional);

        // if(count($professional) > 0){
        //     // $professional_resultMsg = $this->Applicant_model->insert_bundle_professional($professional);
        // }

        // save bundle of referral to database
        $referral = array();
        $referral_resultMsg = true; // set as "true" so that the form can still be submited when there is no item added.

        $form_data['ref_id']      = array_values($form_data['ref_id']);
        $form_data['ref_name']    = array_values($form_data['ref_name']);
        $form_data['ref_company'] = array_values($form_data['ref_company']);
        $form_data['ref_job']     = array_values($form_data['ref_job']);
        $form_data['ref_phoneno'] = array_values($form_data['ref_phoneno']);
        $form_data['ref_email']   = array_values($form_data['ref_email']);

        for($i = 0; $i < count($form_data['ref_name']); $i++) {

            $temp = array(
                'id'            => $form_data['ref_id'][$i],
                'applicant_id'  => $applicant_id,
                'name'          => $form_data['ref_name'][$i],
                'company'       => $form_data['ref_company'][$i],
                'job_title'     => $form_data['ref_job'][$i],
                'phoneno'       => $form_data['ref_phoneno'][$i],
                'email'         => $form_data['ref_email'][$i]
            );

            if(!$this->Applicant_model->insert_referral($temp)){
                $referral_resultMsg = false;
            }

            // array_push($referral, $temp);
        }

        // if(count($referral) > 0)
        // {
        //     $referral_resultMsg = $this->Applicant_model->insert_bundle_referral($referral);
        // }

        if($applicant_resultMsg && $education_resultMsg && $experience_resultMsg && $language_resultMsg && $professional_resultMsg && $referral_resultMsg){
            redirect('applicant/success_msg', 'refresh');
        } else {
            echo $applicant_resultMsg;
        }

        // echo json_encode($experience);
    }

    public function uploadFile($applicant_id = NULL)
    {
        // $applicant_id = $applicant_id;

        // echo $applicant_id;

        $_FILES['uploaded_resume']['name']     = $_FILES['applicant_resume']['name'];
        $_FILES['uploaded_resume']['type']     = $_FILES['applicant_resume']['type'];
        $_FILES['uploaded_resume']['tmp_name'] = $_FILES['applicant_resume']['tmp_name'];
        $_FILES['uploaded_resume']['error']    = $_FILES['applicant_resume']['error'];
        $_FILES['uploaded_resume']['size']     = $_FILES['applicant_resume']['size'];

        $uploadPath                 = './uploads/applicant_resume';
        $config['upload_path']      = $uploadPath;
        $config['allowed_types']    = 'pdf';
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if($this->upload->do_upload('uploaded_resume'))
        {
            $fileData = $this->upload->data();
            $uploadData['uploaded_resume'] = $fileData['file_name'];

            $files = $this->db->query("select * from applicant where id='".$applicant_id."'");
            $file_info = $files->result_array();

            unlink("./uploads/applicant_resume/".$file_info[0]["uploaded_resume"]);

            // echo "success";
        }

        if(!empty($uploadData))
        {
            $this->db->update("applicant",$uploadData,array("id" => $applicant_id));
        }

        echo "{}";
    }

    public function delete_resume($applicant_id){
        // echo $applicant_id;
        $this->session->set_userdata(array('applicant_id' => $applicant_id));

        echo true;
    }

    public function uploadFile_education()
    {
        // echo "uploadFile";
        /*if(isset($insert_id))
        {*/
            //echo ($this->session->userdata('officer_id'));
           //$filesCount = count($_FILES['uploadimages']['name']);
            //echo json_encode(count($_FILES['uploadimages']['name']));
            //for($i = 0; $i < $filesCount; $i++)
            //{   
                $applicant_id = 6;

                $_FILES['receipt']['name']     = $_FILES['receipt_img']['name'];
                $_FILES['receipt']['type']     = $_FILES['receipt_img']['type'];
                $_FILES['receipt']['tmp_name'] = $_FILES['receipt_img']['tmp_name'];
                $_FILES['receipt']['error']    = $_FILES['receipt_img']['error'];
                $_FILES['receipt']['size']     = $_FILES['receipt_img']['size'];

                $uploadPath                 = './uploads/education';
                $config['upload_path']      = $uploadPath;
                $config['allowed_types']    = 'gif|jpg|jpeg|png|ico|icon|image|image|ico';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                //echo json_encode($_FILES['logo']);
                //echo json_encode($this->upload->do_upload('logo'));
                if($this->upload->do_upload('receipt'))
                {
                    $fileData = $this->upload->data();
                    //echo json_encode($fileData);
                    // $firm_id = $this->session->userdata('submit_firm_id');
                    $uploadData['receipt_img'] = $fileData['file_name'];

                    $files = $this->db->query("select * from mc_claim where id='".$applicant_id."'");
                    $file_info = $files->result_array();

                    unlink("./uploads/education/".$file_info[0]["receipt_img"]);
                    /*$uploadData[$i]['created'] = date("Y-m-d H:i:s");
                    $uploadData[$i]['modified'] = date("Y-m-d H:i:s");*/

                    echo "success";
                }

            //}
            //echo json_encode($uploadData);
            if(!empty($uploadData))
            {
                $this->db->update("mc_claim",$uploadData,array("id" => $applicant_id));
                //$this->db->insert_batch('officer_files',$uploadData);
                
            } 
            // else {
            //     $this->db->where('id', 6);
            //     $this->db->update("mc_claim",$uploadData,array("id" => $claim_id));
            // }
            //redirect("personprofile");
            /*$this->session->unset_userdata('officer_id');*/
        //}
    }

    public function delete_data(){
        $form_data = $this->input->post();

        if(isset($form_data['edu'])){
            $this->Applicant_model->delete_batch('education', $form_data['edu']);
        }

        if(isset($form_data['exp'])){
            $this->Applicant_model->delete_batch('experience', $form_data['exp']);
        }

        if(isset($form_data['pro'])){
            $this->Applicant_model->delete_batch('professional', $form_data['pro']);
        }

        if(isset($form_data['ref'])){
            $this->Applicant_model->delete_batch('referral', $form_data['ref']);
        }

        if(isset($form_data['lang'])){
            $this->Applicant_model->delete_batch('language', $form_data['lang']);
        }

        // echo isset($form_data['edu']);
    }
}
