<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//include 'application/js/random_alphanumeric_generator.php';

class PAF_upload_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        
    }

    public function get_client_stat_audit(){
        // $q = $this->db->query("SELECT DISTINCT (client.company_name), client.*, audit_stocktake_reminder_setting.reminder_flag, audit_stocktake_reminder_setting.email FROM client
        //                         RIGHT JOIN client_billing_info ON client_billing_info.company_code = client.company_code
        //                         RIGHT JOIN our_service_info ON our_service_info.id = client_billing_info.service
        //                         LEFT JOIN audit_client ON audit_client.company_code = client.company_code
        //                         LEFT JOIN audit_stocktake_reminder_setting ON client.company_code = audit_stocktake_reminder_setting.company_code
        //                         WHERE (our_service_info.service_type = 1 OR our_service_info.service_type = 10)
        //                         AND client.deleted = 0");

        $q = $this->db->query("SELECT DISTINCT (client.company_name), client.* from client
                                RIGHT JOIN client_billing_info ON client_billing_info.company_code = client.company_code
                                RIGHT JOIN our_service_info ON our_service_info.id = client_billing_info.service
                                LEFT JOIN audit_client ON audit_client.company_code = client.company_code
                                LEFT JOIN user_firm ON user_firm.firm_id = client.firm_id
                                WHERE our_service_info.service_type = 1 
                                AND client.deleted = 0  
                                AND user_firm.user_id = ".$this->session->userdata('user_id')."
                                ORDER BY client.id  DESC");    

        $data = $q->result();

        foreach ($data as $key => $value) {
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
        }
        return $data;


    }


    public function submit_paf_record($data)
    {
        
        // $result = $this->db->insert('audit_paf', $data); 

        // $result = $this->db->insert_id();
        
        // return $result;

        $query_check = $this->db->query('SELECT * FROM audit_paf_tree where company_code = "'.$data['company_code'].'"');


        if($query_check->num_rows() > 0)
        {
            $query = $query_check->result_array();

            $result = $query[0]['id'];
        }
        else
        {
            $this->db->insert('audit_paf_tee', $data); 
            $result = $this->db->insert_id();
        }
        return $result;
    }


    public function get_paf_list(){
        $q = $this->db->query('SELECT audit_paf_tree.*, company_name from audit_paf_tree
                               LEFT JOIN client on audit_paf_tree.company_code = client.company_code
                               where audit_paf_tree.deleted = 0 and client.deleted = 0');

        if($q->num_rows() > 0)
        {
            $data = $q->result();

            foreach ($data as $key => $value) {
                $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
              
            }

            return $data;
        }
        else
        {
            return false;
        }
    }

    public function get_edit_paf($paf_id)
    {
        $q = $this->db->query('SELECT client.company_name, client.id as c_id, paf.* FROM audit_paf_tree paf 
                                LEFT JOIN client ON client.company_code = paf.company_code
                                WHERE paf.id = '.$paf_id.' and client.deleted = 0');

        $data = $q->result();

        if ($q->num_rows() > 0) {
            $this->session->set_userdata('paf_company_code', $q->result()[0]->company_code);
            // $this->session->set_userdata('paf_fye', $q->result()[0]->fye_date);
        }


        foreach ($data as $key => $value) {
            $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);

            // if($data[$key]->stocktake_date != "0000-00-00")
            // {
            //     $data[$key]->stocktake_date = DateTime::createFromFormat('Y-m-d', $data[$key]->stocktake_date )->format('d/m/Y');
            // }
            // else
            // {
            //     $data[$key]->stocktake_date = "";
            // }

            // if($data[$key]->stocktake_time == "00:00:00")
            // {
            //     $data[$key]->stocktake_time = "";
            // }
            // $data[$key]->stocktake_time = substr($data[$key]->stocktake_time,0,5);
            
        }

        return $data;


    }


    // public function get_status_dropdown_list(){
    //     $q = $this->db->query("SELECT id, status FROM audit_status");

    //     $status = array();
    //     $status[''] = '-- Select Status --';

    //     foreach($q->result() as $stat){
    //         $status[$stat->id] = $stat->status; 
    //     }

    //     return $status;
    // }

    // public function get_firm_from_service($company_code){
    //     $q = $this->db->query("SELECT servicing_firm from client_billing_info left join our_service_info on client_billing_info.service = our_service_info.id and our_service_info.user_admin_code_id = '".$this->session->userdata('user_admin_code_id')."' left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where company_code ='".$company_code."' and client_billing_info.deleted = 0 and our_service_info.service_type = 1 order by client_billing_info_id");

    //     return $q->result_array();
    // }


    public function get_all_paftree($paf_id)
    {

        if($paf_id == null)
        {
            $this->db->select('*');
            $this->db->from('audit_default_paf');
          
            $q = $this->db->get();
        }
        else
        {
            $q = $this->db->query('SELECT d.id, null as paf_id,d.parent,d.text,d.type, null as deleted FROM audit_default_paf d
                                UNION
                                SELECT * from audit_paf_node 
                                WHERE audit_paf_node.paf_id = '.$paf_id.' 
                                AND deleted = 0');
        }

        if ($q->num_rows() > 0) {
            // return $q->result_array();
            $data = [];
            foreach($q->result_array() as $item)
            {
                array_push($data, 
                    array(
                        "id"     => $item["id"], 
                        "parent" => $item["parent"], 
                        "text"   => $item["text"],
                        'type'   => $item["type"],
                        'data'   => array(
                                        'id' => $item['id']
                                    )
                    )
                );
            }

            return $data;
        }
        else
        {
            return '';
        }

       
    }

    public function delete_paf($paf_id){

        $this->db->where('id', $paf_id);

        $result = $this->db->update('audit_paf_tree', array('deleted' => 1));

        return $result;
    }


}
?>