<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Interview_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));

        $this->load->model('employee/employee_model');
        $this->load->model('offer_letter/offer_letter_model');
    }

    public function get_interviewList(){
        $q = $this->db->query("SELECT applicant_interview.id AS `interview_id`, interview.interview_no, applicant.id AS `applicant_id`, applicant.name, interview.interview_time, interview.status, interview.result FROM interview 
            LEFT JOIN applicant_interview ON applicant_interview.interview_id = interview.id 
            LEFT JOIN applicant ON applicant_interview.applicant_id = applicant.id 
            WHERE interview.result NOT IN (3)
            ");

        return $q->result();
    }

    public function create_applicant($data){
        $q = $this->db->get_where('applicant', array('id' => $data['id'])); 
        // $q = $this->db->get_where('applicant', $data);    // check if applicant existed before.

        if(!$q->num_rows()){
            $this->db->insert('applicant', $data);    // insert new applicant to database
            $applicant_id = $this->db->insert_id();

            return $applicant_id;
        }
        else{
            return $q->result()[0]->id; // retrieve id
        }
    }

    public function create_interview($data){
        $q = $this->db->get_where('interview', array('id' => $data['id'])); 

        // $q = $this->db->get_where('interview', $data);    // check if interview existed before.

        if(!$q->num_rows()){
            $this->db->insert('interview', $data);    // insert new interview to database.
            $interview_id = $this->db->insert_id();

            return $interview_id;
        }
        else{
            $this->db->where('id', $q->result()[0]->id);
            $this->db->update('interview', $data);

            return $q->result()[0]->id; // retrieve id
        }
    }

    public function create_applicant_interview($data){
        $q = $this->db->get_where('applicant_interview', $data);    // check if interview existed before.

        if(!$q->num_rows()){
            $this->db->insert('applicant_interview', $data);    // insert new interview to database.
            $interview_id = $this->db->insert_id();

            return $interview_id;
        }
        else{
            return $q->result()[0]->id; // retrieve id
        }
    }

    public function edit_interview($interview_id){
        $q = $this->db->query("SELECT interview.*, applicant.id AS `applicant_id`, applicant.name AS `applicant_name`, applicant.email AS `applicant_email` FROM interview LEFT JOIN applicant_interview ON applicant_interview.interview_id = interview.id LEFT JOIN applicant ON applicant_interview.applicant_id = applicant.id WHERE interview.id ='". $interview_id ."'");

        return $q->result()[0];
    }

    public function sendInvitationEmail($data, $applicant_email){
        // send email with interview code
        $msg = file_get_contents('application/views/email_templates/interview_invitation.html');
        $message = $this->parser->parse_string($msg, $data, TRUE);

        $subject = "Interview Invitation";
        if($this->sma->send_email($applicant_email, $subject, $message)){
            return true;
        }
    }

    public function change_interview_status($data, $interview_id){
        $this->db->where('id', $interview_id);

        return $result = $this->db->update('interview', $data);
    }

    public function change_interview_result($data, $interview_id){
        $this->db->where('id', $interview_id);
        
        return $result = $this->db->update('interview', $data);
    }

    public function move_to_employee($interview_id){
        $q = $this->db->query("SELECT a.*, i.firm FROM applicant_interview ai
                                JOIN interview i ON i.id = ai.interview_id
                                JOIN applicant a ON a.id = ai.applicant_id
                                WHERE ai.interview_id ='" .$interview_id. "'");

        $q2 = $this->db->query("SELECT * FROM offer_letter_applicant ola 
                                JOIN offer_letter ol ON ol.id = ola.offer_letter_id
                                WHERE ola.applicant_id = '". $q->result()[0]->id ."'");

        if(count($q2->result()) == 0){
            $result = array(
                'result' => 0,
                'msg'    => "Please complete offer letter before you accept the applicant."
            );

            return $result;
        }

        $data = array(
            'name'                   => $q->result()[0]->name,
            'nric_fin_no'            => $q->result()[0]->ic_passport_no,
            'address'                => $q->result()[0]->address,
            'phoneno'                => $q->result()[0]->phoneno,
            'nationality_id'         => $q->result()[0]->nationality_id,
            'dob'                    => $q->result()[0]->dob,
            'date_joined'            => $q->result()[0]->name,
            // 'date_cessation'         => null,
            'designation'            => $q->result()[0]->position,
            'salary'                 => $q2->result()[0]->given_salary,
            // 'workpass'               => $q->result()[0]->name,
            // 'pass_expire'            => $q->result()[0]->name,
            // 'annual_leave_year'      => $q->result()[0]->name,
            // 'remaining_annual_leave' => $q->result()[0]->name,
            'aws_given'              => 0,
            // 'cpf_employee'           => $q->result()[0]->name,
            // 'cpf_employer'           => $q->result()[0]->name,
            // 'cdac'                   => $q->result()[0]->name,
            // 'remark'                 => $q->result()[0]->name,
            // 'supervisor'             => $q->result()[0]->name,
            // 'department'             => $q->result()[0]->name,
            'firm_id'                => $q->result()[0]->firm
        );

        $result2 = $this->employee_model->create_new_employee($data);

        if($result2['result']){
            $offer_letter_employee_data = array(
                'offer_letter_id' => $q2->result()[0]->offer_letter_id,
                'employee_id'     => $result2['employee_id']
            );

            $result_add = $this->offer_letter_model->add_offer_letter_employee($offer_letter_employee_data);

            if($result_add){
                // $result_remove = $this->offer_letter_model->remove_offer_letter_applicant($q->result()[0]->id);

                // if($result_remove){
                    $interview_data = array(
                        'result' => 3
                    );

                    $interview_result = $this->change_interview_result($interview_data, $interview_id);

                    if($interview_result){
                        return $result = array(
                            'result' => 1,
                            'msg'    => "Successfully accepted applicant as employee."
                        );
                    }
                // }
            }
        }

        $result = array(
            'result' => 0,
            'msg'    => "Something went wrong. Please try again later."
        );

        return $result;

        // echo json_encode($result2);


        // $q->result()[0]->name;

        // $result = array(
        //         'result' => 0,
        //         'msg'    => "Please complete offer letter before you accept the applicant."
        // );

        // return json_encode($q->result());
    }
}
?>