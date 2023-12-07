<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function getClient($group_id=NULL, $tipe = NULL, $keyword = NULL, $service_category = 1)
    {
        $data = [];
        // if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
            // $this->db->where('created_by', $this->session->userdata('user_id'));
        // }
        /*$this->db->like('row_status', 0);*/
        //$this->db->select('*');
        //$this->db->from('client');
        // $this->db->select('DISTINCT (client.company_name), client.*, client_contact_info.name, client_contact_info_phone.phone, client_contact_info_email.email, firm.name as firm_name');
        $this->db->select('DISTINCT (client.company_name), client.*, firm.name as firm_name');
        $this->db->from('client');
     
        // if($keyword != null)
        // {
        //     // if ($tipe != 'All')
        //     // {
        //     //     $this->db->like($tipe, $keyword);
        //     // } else {
        //         $this->db->group_start();
        //         $this->db->or_like('client_code', $keyword);
        //         $this->db->or_like('registration_no', $keyword);
        //         $this->db->or_like('incorporation_date', $keyword);
        //         $this->db->or_like('company_name', $keyword);
        //         $this->db->or_like('postal_code', $keyword);
        //         $this->db->or_like('street_name', $keyword);
        //         $this->db->or_like('building_name', $keyword);
        //         $this->db->or_like('unit_no1', $keyword);
        //         $this->db->or_like('unit_no2', $keyword);
        //         $this->db->or_like('activity1', $keyword);
        //         $this->db->or_like('activity2', $keyword);
        //         $this->db->or_like('former_name', $keyword);
        //         $this->db->group_end();
        //     // }
        // }
        
        
        // $this->db->join('client_contact_info', 'client_contact_info.company_code = client.company_code', 'left');
        // $this->db->join('audit_client', 'audit_client.company_code = client.company_code', 'left');
        // $this->db->join('client_contact_info_email', 'client_contact_info_email.client_contact_info_id = client_contact_info.id AND client_contact_info_email.primary_email = 1', 'left');
        // $this->db->join('client_contact_info_phone', 'client_contact_info_phone.client_contact_info_id = client_contact_info.id AND client_contact_info_phone.primary_phone = 1', 'left');
       // $this->db->join('billing', 'billing.company_code = client.company_code', 'right');
        //$this->db->where('client.user_id', $this->session->userdata('user_id'));
        if($group_id == 4)
        {
            $this->db->join('user_client', 'user_client.client_id = client.id AND user_client.user_id = '.$this->session->userdata('user_id'), 'right');
        }

        if($service_category != "0")
        {
            $this->db->join('client_billing_info', 'client_billing_info.company_code = client.company_code', 'right');
            $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service', 'right');
            $this->db->join('firm', 'firm.id = client_billing_info.servicing_firm', 'left');
        }
        //echo json_encode($this->session->userdata('user_id'));
        //$this->db->where('client.firm_id', $this->session->userdata('firm_id'));
        // $this->db->join('user_firm', 'user_firm.firm_id = client.firm_id', 'left');
        // $this->db->where('user_firm.user_id = '.$this->session->userdata('user_id'));
        // $this->db->where_in('our_service_info.service_type', array($service_category, 10));

        $where = '(our_service_info`.`service_type` IN('.implode (", ", array($service_category, 10)).') OR client_billing_info.service =10)';
        $this->db->where($where);
        $this->db->where(array('client.deleted' => 0,
                                'client_billing_info.deactive' => 0,
                                 'client_billing_info.deleted' => 0));
        $this->db->order_by('client.id', 'desc');
        $q = $this->db->get();
        // echo $this->db->last_query(); 


        // $this->db->select('firm.*')
        //         ->from('firm')
        //         ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
        //         ->where('user_firm.user_id = '.$this->session->userdata('user_id'));

        $client_info = $q->result_array();

        if ($q->num_rows() > 0) 
        {
            for($i = 0; $i < count($client_info); $i++)
            {
                // $query = $this->db->query("select year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to from filing where (agm = '') AND company_code='".$client_info[$i]["company_code"]."'");
                // // or agm = 'dispensed'
                // $filing_info = $query->result_array();

                // if ($query->num_rows() > 0) {
                //     $client_info[$i] = array_merge($client_info[$i], $filing_info[0]);
                // }

                // $query_pending_documents = $this->db->query("select COUNT(*) as num_document from pending_documents where received_on = '' AND client_id ='".$client_info[$i]["id"]."'");

                // $pending_documents_info = $query_pending_documents->result_array();

                // if ($query_pending_documents->num_rows() > 0) {
                //     $client_info[$i] = array_merge($client_info[$i], $pending_documents_info[0]);
                // }

                // $query_unpaid = $this->db->query("select sum(outstanding) as outstanding from billing where company_code = '".$client_info[$i]["company_code"]."' AND status != 1");

                // $unpaid_info = $query_unpaid->result_array();

                // if ($query_unpaid->num_rows() > 0) {
                //     $client_info[$i] = array_merge($client_info[$i], $unpaid_info[0]);
                // }
                // $query_firm = $this->db->query("select firm.name from firm where id = '".$client_info[$i]["servicing_firm"]."'");

                // $firm_info = $query_firm->result_array();

                // if ($query_firm->num_rows() > 0) {
                //     $client_info[$i] = array_merge($client_info[$i], $firm_info[0]);
                // }
            }
            foreach (($client_info) as $row) {
                // $row["registration_no"] = $this->encryption->decrypt($row["registration_no"]);
                $row["company_name"] = $this->encryption->decrypt($row["company_name"]);
                
                if($keyword != null)
                {
                    // if(stripos($row["registration_no"], $keyword) !== FALSE)
                    // {
                    //     $data[] = $row;
                    // }
                    if(stripos(strtoupper($row["company_name"]), strtoupper($keyword)) !== FALSE)
                    {

                        $data[] = $row;
                    }
         
                }
                else
                {
                    $data[] = $row;
                }
            }
            // print_r($data);
            // foreach ($data as $key => $value) {
            //     $data[$key]['company_name'] = $this->encryption->decrypt($data[$key]['company_name']);
            //     $data[$key]['registration_no'] = $this->encryption->decrypt($data[$key]['registration_no']);
            // }
            // echo json_encode($data);
            return $data;


        }
    }

    public function get_caf_list($company_code){

        // $q = $this->db->query("SELECT payroll_assignment.*,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job, completed.report_date , completed.partner
        //                         FROM payroll_assignment 
        //                         LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
        //                         LEFT JOIN payroll_assignment_completed completed ON payroll_assignment.id = completed.payroll_assignment_id
        //                         LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
        //                         LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
        //                         WHERE payroll_assignment.client_id = '".$company_code."' AND payroll_assignment.status != '10' AND payroll_assignment.deleted = '0' and payroll_assignment_jobs.id  NOT IN (17,13)");

        $q = $this->db->query("SELECT payroll_assignment.*,firm.name as firm_name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job, completed.report_date , completed.partner
                        FROM payroll_assignment 
                        LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                        LEFT JOIN payroll_assignment_completed completed ON payroll_assignment.id = completed.payroll_assignment_id
                        LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                        WHERE payroll_assignment.client_id = '".$company_code."' AND payroll_assignment.deleted = '0' and payroll_assignment_jobs.audit_display = 1");
        
        return $q->result();
        // print_r($q->result_array());
    }

    public function get_review_point_info($company_code){

        $q = $this->db->query("SELECT audit_paf_review_point.*, CONCAT(first_name,' ',last_name) as raised_user_name, index_no
                                FROM audit_paf_review_point 
                                LEFT JOIN users ON users.id = audit_paf_review_point.point_raised_by 
                                LEFT JOIN audit_paf_child ON audit_paf_child.id = audit_paf_review_point.paf_child_id
                                WHERE audit_paf_review_point.company_code = '".$company_code."' AND audit_paf_review_point.not_active = '0' AND audit_paf_review_point.deleted = '0'
                                ORDER BY id");
        
        return $q->result();
        // print_r($q->result_array());
    }

    public function filter_review_points($paf_child_id, $cleared, $company_code)
    {
        $q = $this->db->query("SELECT audit_paf_review_point.*, CONCAT(first_name,' ',last_name) as raised_user_name, index_no
                                FROM audit_paf_review_point 
                                LEFT JOIN users ON users.id = audit_paf_review_point.point_raised_by 
                                LEFT JOIN audit_paf_child ON audit_paf_child.id = audit_paf_review_point.paf_child_id
                                WHERE audit_paf_review_point.company_code = '".$company_code."' AND audit_paf_review_point.cleared LIKE '".$cleared."' AND audit_paf_review_point.paf_child_id LIKE '".$paf_child_id."' AND audit_paf_review_point.not_active = '0' AND audit_paf_review_point.deleted = '0'
                                ORDER BY id");


  
        return $q->result();
    }

    public function getRights(){
        $q = $this->db->query("SELECT * FROM audit_client_rights ORDER BY id");

        $rights = array();

        foreach($q->result() as $right){
            $rights[$right->id] = $right->rights; 
        }

        return $rights;
    }

    public function delete_paf_parent($p_id){
        $this->db->where('id', $p_id);

        $result = $this->db->update('audit_paf_parent', array('deleted' => 1));

        $this->db->select('id');
        $this->db->from('audit_paf_child');
        $this->db->where(array('parent_id' => $p_id, 'deleted' => 0, 'archived' => 0));

        $children_id = $this->db->get();
        foreach ($children_id->result_array() as $key => $c_id) {
           $this->delete_paf_child($c_id['id']);
        }
      

        return $result;
    }


    public function delete_paf_child($c_id){
        $user_id = $this->session->userdata('user_id');
        $this->db->where('id', $c_id);


        $result = $this->db->update('audit_paf_child', array('deleted' => 1));

        $this->db->select('company_code');
        $this->db->from('audit_paf_child');
        $this->db->where(array('id' => $c_id));
        $company_code = $this->db->get()->result()[0]->company_code;


        //log delete paf
        $data   = array(
                    'paf_id'     => $c_id,
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $user_id,
                    'paf_log'    => "delete",
                    'company_code' => $company_code
                  );

        $this->db->insert('audit_paf_log', $data);


        return $result;
    }

    public function delete_paf_doc($doc_id){
        $user_id = $this->session->userdata('user_id');
        $this->db->where('id', $doc_id);


        $result = $this->db->update('audit_paf_document', array('deleted' => 1));

        $this->db->select('paf_child_id, company_code');
        $this->db->from('audit_paf_document');
        $this->db->join('audit_paf_child','audit_paf_child.id = audit_paf_document.paf_child_id', 'inner');
        $this->db->where(array('audit_paf_document.id' => $doc_id));
        $temp_result = $this->db->get()->result()[0];
        $company_code = $temp_result->company_code;
        $c_id = $temp_result->paf_child_id;


        //log delete paf
        $data   = array(
                    'paf_id'     => $c_id,
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $user_id,
                    'paf_log'    => "delete document",
                    'company_code' => $company_code
                  );

        $this->db->insert('audit_paf_log', $data);


        return $result;
    }


    public function archive_paf_child($c_id)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('id', $c_id);

        $result = $this->db->update('audit_paf_child', array('archived' => 1));

        $this->db->select('id');
        $this->db->from('audit_paf_document');
        $this->db->where(array('paf_child_id' => $c_id, 'deleted' => 0, 'archived' => 0));

        $doc_id = $this->db->get();
        foreach ($doc_id->result_array() as $key => $d_id) {
            $this->db->where('id', $d_id['id']);
            $this->db->update('audit_paf_document', array('archived' => 1));
        }

        $this->db->select('id');
        $this->db->from('audit_paf_review_point');
        $this->db->where(array('paf_child_id' => $c_id, 'deleted' => 0));

        $rp_id = $this->db->get();
        foreach ($rp_id->result_array() as $rp_key => $each_id) {
            $this->db->where('id', $each_id['id']);
            $this->db->update('audit_paf_review_point', array('not_active' => 1));
        }
      

        $this->db->select('company_code');
        $this->db->from('audit_paf_child');
        $this->db->where(array('id' => $c_id));
        $company_code = $this->db->get()->result()[0]->company_code;

        //log archive paf
        $data   = array(
                    'paf_id'     => $c_id,
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $user_id,
                    'paf_log'    => "archive",
                    'company_code' => $company_code
                  );

        $this->db->insert('audit_paf_log', $data);

        return $result;
    }

    public function archive_paf_parent($p_id)
    {
        $this->db->where('id', $p_id);

        $result = $this->db->update('audit_paf_parent', array('archived' => 1));

        $this->db->select('id');
        $this->db->from('audit_paf_child');
        $this->db->where(array('parent_id' => $p_id, 'deleted' => 0, 'archived' => 0));

        $children_id = $this->db->get();
        foreach ($children_id->result_array() as $key => $c_id) {
           $this->archive_paf_child($c_id['id']);
        }
      
        return $result;
    }


    public function restore_paf($c_id, $c_type)
    {
        $user_id = $this->session->userdata('user_id');
        $this->db->where('id', $c_id);

        if($c_type == "dynmc")
        {
            $result = $this->db->update('audit_paf_child', array('archived' => 0, 'order_time' => date('Y-m-d H:i:s')));
        }
        else 
        {
            $result = $this->db->update('audit_paf_child', array('archived' => 0));
        }
       

        $this->db->select('id');
        $this->db->from('audit_paf_document');
        $this->db->where(array('paf_child_id' => $c_id, 'deleted' => 0, 'archived' => 1));

        $doc_id = $this->db->get();
        foreach ($doc_id->result_array() as $key => $d_id) {
            $this->db->where('id', $d_id['id']);
            $this->db->update('audit_paf_document', array('archived' => 0));
        }


        $this->db->select('parent_id, company_code');
        $this->db->from('audit_paf_child');
        $this->db->where(array('id' => $c_id));

        $temp_result = $this->db->get()->result();

        $p_id = $temp_result[0]->parent_id;
        $company_code = $temp_result[0]->company_code;

        $this->db->where(array('id' => $p_id, 'archived' => 1));
        $this->db->update('audit_paf_parent', array('archived' => 0, 'order_time' => date('Y-m-d H:i:s')));

        //log restore paf
        $data   = array(
                    'paf_id'     => $c_id,
                    'date_time'  => date("Y-m-d H:i:s"),
                    'user_id'    => $user_id,
                    'paf_log'    => "restore",
                    'company_code' => $company_code
                  );

        $this->db->insert('audit_paf_log', $data);



        return $result;
    }

    public function get_client_reminder_info($company_code)
    {
        if ($company_code)
        {
            $q = $this->db->query("select * from audit_stocktake_reminder_setting where company_code = '".$company_code."'");

            return $q->result();
        }
        return false;
    }


    public function get_all_default_client_service()
    {   
        $q = $this->db->query('select our_service_info.*, our_service_info.id as service from our_service_info where user_admin_code_id = "'.$this->session->userdata('user_admin_code_id').'" and display_in_se_id = 1 order by id');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function get_all_client_billing_info($company_code)
    {   
        /*if ($company_code)
        {*/
            $q = $this->db->query('select client_billing_info.*, our_service_info.service_name, our_service_info.service_type, billing_info_service_category.category_description from client_billing_info left join our_service_info on client_billing_info.service = our_service_info.id and our_service_info.user_admin_code_id = "'.$this->session->userdata('user_admin_code_id').'" left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where company_code ="'.$company_code.'" and client_billing_info.deleted = 0 and (our_service_info.service_type = 1 or our_service_info.service_type = 10) order by client_billing_info_id');

            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
          
        return false;
    }

    
    

    public function get_all_filing_data($company_code) 
    {
        $get_all_filing_data = $this->db->query("select filing.*, financial_year_period.period from filing left join financial_year_period on financial_year_period.id = filing.financial_year_period_id where company_code='".$company_code."' order by id");

        //echo json_encode($q->result());

        if ($get_all_filing_data->num_rows() > 0) {
            foreach (($get_all_filing_data->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_all_bank_detail($company_code)
    {
        $get_all_bank_detail = $this->db->query("SELECT ba.*, c.company_name, bank.bank_name from audit_bank_auth ba
                                LEFT JOIN client c on ba.company_code = c.company_code 
                                LEFT JOIN audit_bank_list bank on bank.id = ba.bank_id
                                WHERE c.company_code = '".$company_code."' and ba.deleted = 0");

        if ($get_all_bank_detail->num_rows() > 0) {
            // foreach (($get_all_bank_detail->result()) as $row) {
            //     $data[] = $row;
            // }
            return $get_all_bank_detail->result_array();
        }
        return FALSE;
    }

    public function get_all_bank_summary($company_code)
    {
        $get_all_bank_detail = $this->db->query("SELECT 'Bank Authorization' as source, bank_name, s.status FROM audit_bank_auth ba, audit_bank_list, audit_status s where audit_bank_list.id = ba.bank_id and ba.auth_status = s.id and ba.company_code = '".$company_code."' and ba.deleted = 0 and ba.active = 1
                                                        UNION ALL
                                                        SELECT 'Bank Confirmation' as source, bank_name, s.status FROM audit_bank_confirm bc, audit_bank_auth ba, audit_bank_list bank, audit_status s where bc.bank_auth_id = ba.id and bc.confirm_status = s.id and ba.bank_id = bank.id and ba.company_code = '".$company_code."' and ba.deleted = 0 and ba.active = 1 and bc.deleted = 0");

        if ($get_all_bank_detail->num_rows() > 0) {
            // foreach (($get_all_bank_detail->result()) as $row) {
            //     $data[] = $row;
            // }
            return $get_all_bank_detail->result_array();
        }
        return FALSE;
    }


    // public function get_all_paf($company_code)
    // {
    //     $get_all_paf = $this->db->query("SELECT audit_paf.* from audit_paf
    //                                     where audit_paf.deleted = 0 and audit_paf.company_code = '".$company_code."'");

    //     if ($get_all_paf->num_rows() > 0) {
    //         // foreach (($get_all_bank_detail->result()) as $row) {
    //         //     $data[] = $row;
    //         // }
    //         return $get_all_paf->result_array();
    //     }
    //     return FALSE;
    // }

    public function getPaf($company_code)
    {

        $user_id = $this->session->userdata('user_id');
        $this->db->where(array('company_code' => $company_code, 'deleted' => 0, 'archived' => 0));
        $this->db->order_by('order_time asc, id ASC');
            //$this->db->where('unique_code', $unique_code);
            //$this->db->where('row_status', 0);
        // $this->db->order_by('id', 'desc');
        $q = $this->db->get("audit_paf_parent");
        if ($q->num_rows() > 0) {
            
            $parents = $q->result();

            foreach ($parents as $key => $parent) {
                $this->db->select('audit_paf_child.*, form_id as parent_form_id');
                $this->db->from('audit_paf_child');

                $this->db->join('audit_paf_parent','audit_paf_parent.id = audit_paf_child.parent_id', 'left');
                $this->db->where(array('parent_id' => $parent->id, 'audit_paf_child.deleted' => 0));
                $this->db->where ("(audit_paf_child.type='dynmc' and audit_paf_child.archived='0') or (audit_paf_child.type='fixed' and parent_id =".$parent->id.")");
                $this->db->order_by('order_time asc, id ASC');
          
          
                $cq = $this->db->get();

            
                $child_array = $cq->result_array();

                $parents[$key]->child = $child_array;

            }

            return $parents;
        }
        else
        {
            $data = array(
                       array(
                          'company_code' => $company_code ,
                          'form_id'  => 'paf1' ,
                          'index_no' => 1,
                          'text'     => 'Audit Administration',
                          'type'     => 'fixed',
                          'order_time' => date('Y-m-d H:i:s')
                       ),
                       array(
                          'company_code' => $company_code ,
                          'form_id'  => 'paf2' ,
                          'index_no' => 2,
                          'text'     => 'Bank Authorization',
                          'type'     => 'fixed',
                          'order_time' => date('Y-m-d H:i:s')
                       )
                    );

            $this->db->insert_batch('audit_paf_parent', $data); 
            $first_id = $this->db->insert_id();

            $child_data = array(
                       array(
                          'company_code' => $company_code ,
                          'parent_id' => $first_id ,            
                          'index_no' => 1.1,
                          'text'     => 'Clearance letter',
                          'type'     => 'fixed',
                          'order_time' => date('Y-m-d H:i:s')
                       ),
                       array(
                          'company_code' => $company_code ,
                          'parent_id' => $first_id ,                       
                          'index_no' => 1.2,
                          'text'     => 'Engagement letter',
                          'type'     => 'fixed',
                          'order_time' => date('Y-m-d H:i:s')
                       )
                    );
            
            $this->db->insert_batch('audit_paf_child', $child_data); 

            $this->db->where(array('company_code' => $company_code, 'deleted' => 0));
            
            $q = $this->db->get("audit_paf_parent");

            $parents = $q->result();

            foreach ($parents as $key => $parent) {
                $this->db->select('audit_paf_child.*, form_id as parent_form_id');
                $this->db->from('audit_paf_child');

                $this->db->join('audit_paf_parent','audit_paf_parent.id = audit_paf_child.parent_id', 'left');
                $this->db->where(array('parent_id' => $parent->id, 'audit_paf_parent.deleted' => 0, 'audit_paf_child.archived' => 0));

          
                $cq = $this->db->get();

            
                $child_array = $cq->result_array();

                $parents[$key]->child = $child_array;

                if($child_array)
                {
                    //log initial insert paf
                    foreach ($child_array as $c_key => $child) {
                        $data   = array(
                                    'paf_id'     => $child['id'],
                                    'date_time'  => date("Y-m-d H:i:s"),
                                    'user_id'    => $user_id,
                                    'paf_log'    => "initial insert",
                                    'company_code' => $company_code
                                  );

                        $this->db->insert('audit_paf_log', $data);
                    }
                }
                

            }

            


            return $parents;

        }
    }

    public function getArchivedPaf($company_code)
    {
        $this->db->where(array('company_code' => $company_code, 'deleted' => 0));
        $this->db->order_by('order_time asc, id ASC');
            //$this->db->where('unique_code', $unique_code);
            //$this->db->where('row_status', 0);
        // $this->db->order_by('id', 'desc');
        $q = $this->db->get("audit_paf_parent");
        if ($q->num_rows() > 0) {
            
            $parents = $q->result();

            foreach ($parents as $key => $parent) {
                $this->db->select('audit_paf_child.*, form_id as parent_form_id');
                $this->db->from('audit_paf_child');

                $this->db->join('audit_paf_parent','audit_paf_parent.id = audit_paf_child.parent_id', 'left');
                $this->db->where(array('parent_id' => $parent->id, 'audit_paf_child.deleted' => 0, 'audit_paf_child.archived' => 1));
                $this->db->order_by('order_time asc, id ASC');
          
                $cq = $this->db->get();

            
                $child_array = $cq->result_array();

                $parents[$key]->child = $child_array;

            }

            return $parents;
        }
        
    }


    public function get_paf_doc($company_code)
    {
        $this->db->where(array('company_code' => $company_code, 'deleted' => 0));
        $q = $this->db->get("audit_paf_child");
        if ($q->num_rows() > 0) {
            
            $children = $q->result();

            foreach ($children as $key => $child) {
                $this->db->select('*');
                $this->db->from('audit_paf_document');
                $this->db->where(array('paf_child_id' => $child->id, 'deleted' => 0));

          
                $cq = $this->db->get();

            
                $doc_arr = $cq->result_array();

                $children[$key]->doc = $doc_arr;

            }

        }
        return $children;
    }


    public function get_paf_logs($company_code)
    {
        $q = $this->db->query('SELECT log.*, CONCAT(first_name," ",last_name) as user_name
                            FROM audit_paf_log log
                            INNER JOIN
                                (SELECT paf_id, MAX(date_time) AS latest_date_time
                                FROM audit_paf_log
                                GROUP BY paf_id) groupedlog
                            ON log.paf_id = groupedlog.paf_id 
                            INNER JOIN users on users.id = log.user_id
                            AND log.date_time = groupedlog.latest_date_time');


        $data = $q->result_array();
        $temp_arr = array();

        foreach ($data as $key => $value) {
            $temp_arr[$value['paf_id']] = $value;
              # code...
        }  

        return $temp_arr;
    }

    public function get_paf_index($company_code){
        // echo $company_code;
        $query = $this->db->query("SELECT id,index_no FROM audit_paf_child WHERE company_code = '".$company_code."' AND archived = 0 AND deleted = 0 ORDER BY index_no");

        foreach($query->result_array() as $item){
            $paf_list[$item['id']] = $item['index_no']; 
        }

        // return $paf_list;
        return $query->result_array();
    }

    public function insert_paf_parent($data, $id)
    {
        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('audit_paf_parent', $data);    // update parent in database
        }
        else
        {
            $data['order_time'] = date('Y-m-d H:i:s');
            $result = $this->db->insert('audit_paf_parent', $data);    // insert new parent to database
        }

        return $result;
    }

    public function insert_paf_child($data, $id=null)
    {
        $user_id = $this->session->userdata('user_id');

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('audit_paf_child', $data);    // update child in database

            $c_id = $id;
        }
        else
        {
            $data['order_time'] = date('Y-m-d H:i:s');
            // print_r($data);
            $result = $this->db->insert('audit_paf_child', $data);    // insert new child to database

            $c_id = $this->db->insert_id();

            // log insert paf
            $data   = array(
                            'paf_id'     => $c_id,
                            'date_time'  => date("Y-m-d H:i:s"),
                            'user_id'    => $user_id,
                            'paf_log'    => "insert",
                            'company_code' => $data['company_code']
                          );

            $this->db->insert('audit_paf_log', $data);
        
        }

        

        return $c_id;
    }


    public function getClientbyUcode($unique_code)
    {
        // if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
        //     $this->db->where('created_by', $this->session->userdata('user_id'));
        // }

        $this->db->where('created_by', $this->session->userdata('user_id'));
            //$this->db->where('unique_code', $unique_code);
            //$this->db->where('row_status', 0);
        // $this->db->order_by('id', 'desc');
        $q = $this->db->get("client");
        if ($q->num_rows() > 0) {
            // foreach (($q->result()) as $row) {
                // $data[] = $row;
            // }
            return $q->result()[0];
        }
    }

    public function getClientbyID($id)
    {
        // if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
        //     
        // }

        // $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('id', $id);
            /*change on 5/3/2018 by justin*/
            /*$this->db->where('row_status', 0);*/
        // $this->db->order_by('id', 'desc');
        $q = $this->db->get("client");
        if ($q->num_rows() > 0) {
            // foreach (($q->result()) as $row) {
                // $data[] = $row;
            // }
            $this->session->set_userdata(array(
                'company_type'  => $q->result()[0]->company_type,
            ));
            $this->session->set_userdata(array(
                'acquried_by'  => $q->result()[0]->acquried_by,
            ));
            $this->session->set_userdata(array(
                'status'  => $q->result()[0]->status,
            ));

            $data = $q->result();

            foreach ($data as $key => $value) {
                $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
                $data[$key]->registration_no = $this->encryption->decrypt($data[$key]->registration_no);
            }
            return $data[0];
        }
    }

    public function getClientbyCode($company_code)
    {
        // if ($this->Settings->restrict_user && !$this->Owner && !$this->Admin) {
        //     
        // }

        // $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('company_code', $company_code);
            /*change on 5/3/2018 by justin*/
            /*$this->db->where('row_status', 0);*/
        // $this->db->order_by('id', 'desc');
        $q = $this->db->get("client");
        if ($q->num_rows() > 0) {
            // foreach (($q->result()) as $row) {
                // $data[] = $row;
            // }
            // $this->session->set_userdata(array(
            //     'company_type'  => $q->result()[0]->company_type,
            // ));
            // $this->session->set_userdata(array(
            //     'acquried_by'  => $q->result()[0]->acquried_by,
            // )); 
            // $this->session->set_userdata(array(
            //     'status'  => $q->result()[0]->status,
            // ));
            $data = $q->result();

            foreach ($data as $key => $value) {
                $data[$key]->company_name = $this->encryption->decrypt($data[$key]->company_name);
                $data[$key]->registration_no = $this->encryption->decrypt($data[$key]->registration_no);
            }
            return $data[0];
        }
    }

    public function getFirmbyCode($company_code){
        $q = $this->db->query("SELECT servicing_firm, firm.name as firm_name from firm, client_billing_info left join our_service_info on client_billing_info.service = our_service_info.id and our_service_info.user_admin_code_id = '".$this->session->userdata('user_admin_code_id')."' left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where company_code ='".$company_code."' and firm.id = client_billing_info.servicing_firm and client_billing_info.deleted = 0 and (our_service_info.service_type = 1 or our_service_info.service_type = 10) order by client_billing_info_id");

        if ($q->num_rows() > 0) {
            // foreach (($q->result()) as $row) {
                // $data[] = $row;
            // }
            // $this->session->set_userdata(array(
            //     'company_type'  => $q->result()[0]->company_type,
            // ));
            // $this->session->set_userdata(array(
            //     'acquried_by'  => $q->result()[0]->acquried_by,
            // ));
            // $this->session->set_userdata(array(
            //     'status'  => $q->result()[0]->status,
            // ));
            return $q->result()[0];
        }
        else
        {
            return "";
        }

        // return $q->result_array();
    }


    public function getAllFirmInfo(){

        $this->db->select('firm.*, firm_telephone.telephone, firm_fax.fax, firm_email.email, user_firm.user_id, user_firm.default_company, user_firm.in_use')
            ->from('firm')
            ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
            ->join('firm_telephone', 'firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1', 'left')
            ->join('firm_fax', 'firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1', 'left')
            ->join('firm_email', 'firm_email.firm_id = firm.id AND firm_email.primary_email = 1', 'left')
            ->where('user_firm.user_id = '.$this->session->userdata('user_id'));

        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

      public function getClientOfficer($id)
    {   
        if ($id)
        {
            $q = $this->db->query('select client_officers.*, client_officers_position.position as position_name, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where company_code = "'.$id.'" ORDER BY client_officers.date_of_cessation DESC, position_name DESC');
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return false;
    }

    public function getClientGuarantee($company_code)
    {
        if ($company_code)
        {
            $q = $this->db->query('select client_guarantee.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, currency.currency as currency_name from client_guarantee left join officer on client_guarantee.officer_id = officer.id and client_guarantee.field_type = officer.field_type left join officer_company on client_guarantee.officer_id = officer_company.id and client_guarantee.field_type = officer_company.field_type left join currency on currency.id = client_guarantee.currency_id where company_code ="'.$company_code.'"');
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return false;
    }

    public function getClientController($company_code)
    {
        if ($company_code)
        {
            $q = $this->db->query('select client_controller.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.company_name as client_company_name, client.registration_no from client_controller left join officer on client_controller.officer_id = officer.id and client_controller.field_type = officer.field_type left join officer_company on client_controller.officer_id = officer_company.id and client_controller.field_type = officer_company.field_type left join client on client.id = client_controller.officer_id AND client_controller.field_type = "client" where client_controller.company_code ="'.$company_code.'"');
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
                return $data;
            }
        }
        return false;
    }

    public function get_all_director_retiring($company_code)
    {

        $q = $this->db->query('select client_officers.*, officer.identification_no, officer.name from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type where position = 1 AND  date_of_cessation = "" AND company_code ="'.$company_code.'"');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }

        return false;
    }

    public function get_all_corp_rep($registration_no)
    {
         // $corp_rep_info = $this->db->query("select corporate_representative.*, client.company_name from corporate_representative LEFT JOIN client on client.id = corporate_representative.client_id and client.deleted = 0 where corporate_representative.registration_no = '".$registration_no."'");
        $corp_rep_info = $this->db->query("select corporate_representative.* from corporate_representative where corporate_representative.registration_no = '".$registration_no."'");

        if ($corp_rep_info->num_rows() > 0) {
            foreach (($corp_rep_info->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        
        return false;
    }    

    public function get_stocktake_client_list($next_month)
    {
        print_r($next_month);
        $q = $this->db->query('SELECT st.*, max(f.year_end) as year_end  FROM audit_stocktake_reminder_setting st, filing f 
                                WHERE st.company_code = f.company_code 
                                AND f.year_end LIKE "'.$next_month.'%"
                                AND st.reminder_flag = 1
                                GROUP BY company_code');

        return $q->result_array();
    }

    public function get_point_raise_detail($review_point_id)
    {
        $q = $this->db->query('SELECT CONCAT(first_name," ",last_name) as user_name, point_raised_at FROM audit_paf_review_point
                                LEFT JOIN users ON users.id = audit_paf_review_point.point_raised_by
                                WHERE audit_paf_review_point.id = '.$review_point_id);

        return $q->result_array();
    }

    public function get_uncleared_points($paf_child_id)
    {
        $q = $this->db->query('SELECT * FROM audit_paf_review_point
                                WHERE paf_child_id = '.$paf_child_id.' 
                                AND cleared = 0 AND deleted = 0 AND not_active=0');

        return $q->result_array();
    }

    public function get_rp_existance($paf_child_id)
    {
        $q = $this->db->query('SELECT * FROM audit_paf_review_point
                                WHERE paf_child_id = '.$paf_child_id.' 
                                AND deleted = 0');

        return $q->result_array();
    }


}