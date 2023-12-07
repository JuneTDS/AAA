<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    // public function getNationality(){

    // 	$query = $this->db->query("SELECT * from nationality");

    // 	$nationality = array();

    // 	$nationality[''] = '-- Select Nationality --';

    // 	foreach($query->result_array() as $item) {
    // 		$nationality[$item['id']] = $item['nationality']; 
    // 	}

   	// 	return $nationality;
    // }

    public function get_applicant($applicant_id){
        $query = $this->db->query("SELECT * from applicant WHERE id ='".$applicant_id."'");

        return $query->result()[0];
    }

    public function get_applicant_language($applicant_id){
        $query = $this->db->query("SELECT * from language WHERE applicant_id ='".$applicant_id."'");

        return $query->result();
    }


    public function get_applicant_education($applicant_id){
        $query = $this->db->query("SELECT * from education WHERE applicant_id ='".$applicant_id."'");

        return $query->result();
    }

    public function get_applicant_experience($applicant_id){
        $query = $this->db->query("SELECT * from experience WHERE applicant_id ='".$applicant_id."'");

        return $query->result();
    }

    public function get_applicant_professional($applicant_id){
        $query = $this->db->query("SELECT * from professional WHERE applicant_id ='".$applicant_id."'");

        return $query->result();
    }

    public function get_applicant_referral($applicant_id){
        $query = $this->db->query("SELECT * from referral WHERE applicant_id ='".$applicant_id."'");

        return $query->result();
    }

    public function check_interview_no($interview_no){
        $query = $this->db->query('SELECT * from interview WHERE interview_no ="'. $interview_no.'"');

        if($query->num_rows()){

            $interview_id   = $query->result_array()[0]['id'];
            $interview_time = $query->result_array()[0]['expired_at'];

            if($interview_time > date('Y-m-d H:i:s')){
                $query1 = $this->db->query('SELECT * from applicant_interview WHERE interview_id ="' . $interview_id . '"');

                $applicant_id = $query1->result_array()[0]['applicant_id'];

                return json_encode(array('applicant_id' => $applicant_id, 'error' => ''));
            } else {
                return json_encode(array('applicant_id' => null, 'error' => 'Interview number is expired. Please contact our HR team to retrieve a new interview no.'));
            }

        }
        else {
            return json_encode(array('applicant_id' => null, 'error' => 'Interview no not found. Please make sure you have enter a correct interview no.'));
        }

    }

    public function save_applicant($id, $data){

        $this->db->where('id', $id);
        $result = $this->db->update('applicant', $data); 

        if($result){
            return true;
        }else{
            return $this->db->_error_message();
        }
    }

    // public function insert_bundle_education($data){

    //     $result = $this->db->insert_batch('education', $data); 

    //     if($result){
    //         return true;
    //     }else{
    //         return $this->db->_error_message();
    //     }
    // }

    public function insert_education($data){
        if(empty($data['id'])){
            $result = $this->db->insert('education', $data);
        }else{
            $this->db->where('id', $data['id']);
            $result = $this->db->update('education', $data);
        }
        return $result;
        // return !empty($data['id']);
    }

    // public function insert_bundle_experience($data){

    //     $result = $this->db->insert_batch('experience', $data); 

    //     if($result){
    //         return true;
    //     }else{
    //         return $this->db->_error_message();
    //     }
    // }

    public function insert_experience($data){
        if(empty($data['id'])){
            $result = $this->db->insert('experience', $data);
        }else{
            $this->db->where('id', $data['id']);
            $result = $this->db->update('experience', $data);
        }
        return $result;
        // return !empty($data['id']);
    }

    // public function insert_bundle_language($data){

    //     $result = $this->db->insert_batch('language', $data); 

    //     if($result){
    //         return true;
    //     }else{
    //         return $this->db->_error_message();
    //     }
    // }

    public function insert_language($data){
        if(empty($data['id'])){
            $result = $this->db->insert('language', $data);
        }else{
            $this->db->where('id', $data['id']);
            $result = $this->db->update('language', $data);
        }
        return $result;
        // return !empty($data['id']);
    }

    // public function insert_bundle_professional($data){

    //     $result = $this->db->insert_batch('professional', $data); 

    //     if($result){
    //         return true;
    //     }else{
    //         return $this->db->_error_message();
    //     }
    // }

    public function insert_professional($data){
        if(empty($data['id'])){
            $result = $this->db->insert('professional', $data);
        }else{
            $this->db->where('id', $data['id']);
            $result = $this->db->update('professional', $data);
        }
        return $result;
        // return !empty($data['id']);
    }

    // public function insert_bundle_referral($data){

    //     $result = $this->db->insert_batch('referral', $data); 

    //     if($result){
    //         return true;
    //     }else{
    //         return $this->db->_error_message();
    //     }
    // }

    public function insert_referral($data){
        if(empty($data['id'])){
            $result = $this->db->insert('referral', $data);
        }else{
            $this->db->where('id', $data['id']);
            $result = $this->db->update('referral', $data);
        }
        return $result;
    }

    public function delete_batch($table_name, $ids){    // delete education, experience and so on by multiple rows
        $this->db->where_in('id', $ids);
        $result = $this->db->delete($table_name);

        return $result;
    }
}
?>