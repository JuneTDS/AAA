<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//include 'application/js/random_alphanumeric_generator.php';

class Report_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        
    }

    public function get_ml_client(){
        $q = $this->db->query('SELECT * FROM client WHERE activity1 LIKE "%64924 - MONEY-LENDING%" and deleted = 0');

        if($q->num_rows() > 0)
        {
            $client_info = $q->result_array();

            foreach (($client_info) as$key=> $row) {
                // $row["registration_no"] = $this->encryption->decrypt($row["registration_no"]);
                $client_info[$key]["company_name"] = $this->encryption->decrypt($row["company_name"]);
                
            }

            return $client_info;
        }
        else
        {
            return false;
        }
    }

    public function get_audit_ml_report_json()
    {
        $url         = './assets/json/audit_ml_report.json'; // path to your JSON file
        // echo file_get_contents($url);
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        return json_decode(json_encode($data_decode[0]), true);
    }

    public function get_fca_id_with_name($assignment_id, $account_name)
    {
        $fca_ids = [];

        foreach ($account_name as $ac_key => $ac_value) 
        {

            // print_r($ac_value);
            $q = $this->db->query("SELECT aca.*, fs_default_acc_category.account_code 
                                    FROM audit_categorized_account aca 
                                    LEFT JOIN fs_default_acc_category ON aca.fs_default_acc_category_id = fs_default_acc_category.id 
                                    WHERE UPPER(aca.description) = UPPER('" . $ac_value . "') AND aca.type='Branch' AND aca.assignment_id=" . $assignment_id);
            $q = $q->result_array();

            foreach ($q as $key => $value) 
            {
                array_push($fca_ids, $value['id']);
            }
        }
        // print_r($assignment_id);
        return $fca_ids;
    }


    
}
?>