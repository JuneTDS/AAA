<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends MX_Controller
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

        $this->load->library(array('session','parser'));
         $this->load->library(array('session', 'form_validation','encryption'));
        $this->load->helper("file");
        // $this->load->model('offer_letter_model');
        // $this->load->model('block_holiday_model');
        $this->load->model("client/client_model");
        $this->load->model("firm/master_model");
        $this->load->model('paf_upload/paf_upload_model');
    }

    public function index()
    {   
    	 $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        
        //echo json_encode($_POST);
        /*if (isset($_POST['showall']))
        {
            $this->data['client'] = $this->db_model->getClient();
        } else*/
        //echo json_encode($_POST['search']);
        if (isset($_POST['search'])) {
            if (isset($_POST['search']))
            {

                // if ($_POST['pencarian'] != '')
                // {
                    $this->data['client'] = $this->client_model->getClient($_SESSION['group_id'], null,$_POST['search'], 1);
                    // print_r($this->client_model->getClient($_SESSION['group_id'], null,$_POST['search'], 1));
                // }
            } 
        }
        else
        {
            $this->data['client'] = $this->client_model->getClient($_SESSION['group_id'], null, null, 1);
        }
       
        $bc = array(array('link' => '#', 'page' => 'Clients'));
        $meta = array('page_title' => 'Clients', 'bc' => $bc, 'page_name' => 'Clients');

        $this->db->select("*")
            ->from("users")
            ->where('id = "'.$this->session->userdata("user_id").'"');

        $user = $this->db->get();
        $user = $user->result_array();

        $this->data["no_of_client"] = $user[0]["no_of_client"];
        $this->data["total_no_of_client"] = $user[0]["total_no_of_client"];

        // print_r($this->data['client']);
        // $this->meta['page_name'] = 'Block Holiday';
        // $this->data['holiday_list'] = $this->block_holiday_model->get_holiday_list();

        $this->page_construct('indexClient.php', $meta, $this->data);
    }

    public function addClient()
    {
        $bc = array(array('link' => '#', 'page' => 'Create Client'));
        $meta = array('page_title' => 'Create Client', 'bc' => $bc, 'page_name' => 'Create Client');
        // if(isset($_SESSION['open_unique_code']) && $_SESSION['open_unique_code'] !='')
        // {
        //     $unique_code =$_SESSION['open_unique_code'];
        //     $this->data['client'] = $this->client_model->getClientbyUcode($unique_code);
        //     // exit();
        //     $this->session->set_userdata('open_unique_code','');
        // }else{
        //     $this->data['client'] = $this->client_model->getClientbyID($id);
        //     //echo json_encode($this->data['client']->company_code);
        //     $company_code =$this->data['client']->company_code;
        //     $registration_no =$this->data['client']->registration_no;
        //     $unique_code =$this->data['client']->unique_code;
        // }
        $this->session->set_userdata(array(
            'company_type'  => null,
        ));
        $this->session->set_userdata(array(
            'company_code'  => null,
        ));
        $this->session->set_userdata(array(
            'acquried_by'  =>  null,
        ));
        $this->session->set_userdata(array(
            'status'  => null,
        ));

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Clients', base_url('client'));
        $this->mybreadcrumb->add('Create Client', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();


        $this->db->select('our_service_registration_address.id, our_service_info.service_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2')
                ->from('firm')
                ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                ->join('our_service_info', 'our_service_info.user_admin_code_id = '.$this->session->userdata('user_admin_code_id').' and service_type = 7', 'left')
                ->join('our_service_registration_address', 'our_service_registration_address.our_service_info_id = our_service_info.id', 'left')
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                ->where('user_firm.in_use = 1');
        $registered_address = $this->db->get();

        $registered_address_info = $registered_address->result_array();

        $this->data['client_billing_info'] = $this->client_model->get_all_default_client_service();

        $this->data['filing_data'] = "";
        $this->data['first_time'] = false;
        $this->data['registered_address_info'] = $registered_address_info;
       
    	$this->page_construct('client/addClient.php', $meta, $this->data);
    }

    public function edit ($id = null, $tab = null) //edit_client
    {

        // if(isset($_SESSION['open_unique_code']) && $_SESSION['open_unique_code'] !='')
        // {
        //     $unique_code =$_SESSION['open_unique_code'];
        //     $this->data['client'] = $this->client_model->getClientbyUcode($unique_code);
        //     // exit();
        //     $this->session->set_userdata('open_unique_code','');
        // }else{
      
        $this->data['client'] = $this->client_model->getClientbyID($id);
        //echo json_encode($this->data['client']->company_code);
        // print_r($this->client_model->getClientbyID($id));
        $company_code =$this->data['client']->company_code;
        $registration_no =$this->data['client']->registration_no;
            // $unique_code =$this->data['client']->unique_code;
        // }
            // echo $unique_code;
        // print_r($_SESSION['open_unique_code']);
        if($tab == "filing")
        {
            $this->data['tab'] = "filing";
        }
        else if($tab == "setup")
        {
            $this->data['tab'] = "setup";
        }
        else if($tab == "billing")
        {
            $this->data['tab'] = "billing";
        }
        else if($tab == "bank_detail")
        {
            $this->data['tab'] = "bank_detail";
        }
        //destroy session for setup tab
        $this->session->unset_userdata('chairman');
        $this->session->unset_userdata('director_signature_1');
        $this->session->unset_userdata('director_signature_2');

        $this->data['sharetype'] = $this->master_model->get_all_share_type();
        //$this->data['service'] = $this->master_model->get_all_service();
        $this->data['currency'] = $this->master_model->get_all_currency();
        $this->data['citizen'] = $this->master_model->get_all_citizen();
        $this->data['citizen'] = $this->master_model->get_all_citizen();
        $this->data['typeofdoc'] = $this->master_model->get_all_typeofdoc();
        //$this->data['doccategory'] = $this->master_model->get_all_doccategory();
        // $this->session->set_userdata('unique_code', $unique_code);
        $this->session->set_userdata('company_code', $company_code);
        $this->session->set_userdata('client_id', $id);


        $this->data['client_officers'] =$this->client_model->getClientOfficer($company_code);
        $this->data['client_guarantee'] =$this->client_model->getClientGuarantee($company_code);
        $this->data['client_controller'] =$this->client_model->getClientController($company_code);
        $this->data['client_charges'] = $this->master_model->get_all_chargee($company_code);
        $this->data['client_share_capital'] = $this->master_model->get_all_client_share_capital($company_code);
        //$this->data['allotment'] = $this->master_model->get_all_allotment_group($company_code);
        $this->data['member'] = $this->master_model->get_all_member($company_code);
        $this->data['member_certificate'] = $this->master_model->get_all_member_certificate($company_code);
        $this->data['client_signing_info'] = $this->master_model->get_all_client_signing_info($company_code);
        $this->data['client_contact_info'] = $this->master_model->get_all_client_contact_info($company_code);
        // print_r($this->data['client_contact_info']);
        // $this->data['client_reminder_info'] = $this->master_model->get_all_client_reminder_info($company_code);
        $this->data['client_reminder_info'] = $this->client_model->get_client_reminder_info($company_code);
        $this->data['client_billing_info'] = $this->client_model->get_all_client_billing_info($company_code);
        if($this->data['client_billing_info'] == false)
        {
            $this->data['client_billing_info'] = $this->client_model->get_all_default_client_service();

        }
        $this->data['filing_data'] = $this->master_model->get_all_filing_data($company_code);
        // $this->data['eci_filing_data'] = $this->master_model->get_all_eci_filing_data($company_code);
        // $this->data['tax_filing_data'] = $this->master_model->get_all_tax_filing_data($company_code);
        // $this->data['gst_filing_data'] = $this->master_model->get_all_gst_filing_data($company_code);
        // $this->data['template'] = $this->master_model->get_all_template_data($company_code);
        $this->data['director_retiring'] = $this->client_model->get_all_director_retiring($company_code);
        $this->data['corp_rep_data'] = $this->client_model->get_all_corp_rep($registration_no);

        
        //$this->data['officer'] =$this->db_model->getOfficerUC($unique_code);
        // $this->data['issued_sharetype'] = $this->master_model->get_all_issued_sharetype($unique_code);
        //$this->data['paid_share'] = $this->master_model->get_all_paid_share($unique_code);
        //$this->data['member_capital'] = $this->master_model->get_all_member_capital($unique_code);
        $this->data['person'] = $this->master_model->get_all_person();
        //$this->data['chargee'] = $this->master_model->get_all_chargee($unique_code);
        //$this->data['client_others'] = $this->master_model->get_typeofdoc($unique_code);
        //$this->data['allotment_member'] = $this->master_model->get_all_alotment_member($unique_code);
        
        $this->data['client_service'] = $this->master_model->get_all_client_service();
        // $this->data['client_setup'] = $this->master_model->get_all_client_setup();

        //$registered_address = $this->db->query("select postal_code, street_name, building_name, unit_no1, unit_no2 from firm ");

        $this->data['client_bank_detail'] = $this->client_model->get_all_bank_detail($company_code);
        $this->data['client_bank_summary'] = $this->client_model->get_all_bank_summary($company_code);
        // $this->data['client_paf'] = $this->client_model->get_all_paf($company_code);
        // print_r($this->client_model->get_all_bank_detail($company_code)."empty");

        $this->db->select('our_service_registration_address.id, our_service_info.service_name, our_service_registration_address.postal_code, our_service_registration_address.street_name, our_service_registration_address.building_name, our_service_registration_address.unit_no1, our_service_registration_address.unit_no2')
                ->from('firm')
                ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                ->join('our_service_info', 'our_service_info.user_admin_code_id = '.$this->session->userdata('user_admin_code_id').' and service_type = 7', 'left')
                ->join('our_service_registration_address', 'our_service_registration_address.our_service_info_id = our_service_info.id', 'left')
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                ->where('user_firm.in_use = 1');
        $registered_address = $this->db->get();

        $registered_address_info = $registered_address->result_array();
        $this->data['registered_address_info'] = $registered_address_info;

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Clients', base_url('client'));
        $this->mybreadcrumb->add($this->data['client']->company_name, base_url('client/audit_client').'/'.$id);
        $this->mybreadcrumb->add('Profile', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->data['first_time'] = true;
        //echo json_encode($this->data);
            // $this->sma->print_arrays($this->data['member_capital']);
        $bc = array(array('link' => '#', 'page' => 'Edit Client'));
        $meta = array('page_title' => 'Edit Client', 'bc' => $bc, 'page_name' => 'Edit Client');
        // $this->data['page_name'] = 'Clients';
        $this->page_construct('client/addClient.php', $meta, $this->data);
        
    }


    public function audit_client($id = null)
    {
        $this->data['client'] = $this->client_model->getClientbyID($id);
        $this->data['rights_dropdown'] = $this->client_model->getRights();
        $this->data['caf_list'] = $this->client_model->get_caf_list( $this->data['client']->company_code);

        // print_r($this->data['caf_list']);

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Clients', base_url('client'));
        $this->mybreadcrumb->add($this->data['client']->company_name, base_url('client/audit'));
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Clients'));
        $meta = array('page_title' => 'Clients', 'bc' => $bc, 'page_name' => 'Clients - '. $this->data['client']->company_name);



        $this->page_construct('client/audit_client.php', $meta, $this->data);

    }

    public function paf($id = null)
    {

        // $this->meta['page_name'] = 'Stocktake Arrangement';

        $this->data['active_tab'] = $this->session->userdata('tab_active');

        $this->session->unset_userdata('tab_active');

        $this->data['client'] = $this->client_model->getClientbyID($id);
        $company_code =$this->data['client']->company_code;

        $this->data['paf_data'] = $this->client_model->getPaf($company_code);

        $this->data['paf_archived_data'] = $this->client_model->getArchivedPaf($company_code);

        $this->data['paf_documents'] = $this->client_model->get_paf_doc($company_code);

        $this->data['paf_logs'] = $this->client_model->get_paf_logs($company_code);

        $this->data['review_point_info'] = $this->client_model->get_review_point_info($company_code);
        // print_r($this->data['paf_documents']);

        // print_r($this->data['paf_data']);
        // $this->data['edit_paf'] = $this->paf_upload_model->get_edit_paf($id);

        // $this->data['auditor_name_dropdown'] = $this->stocktake_model->get_auditor_dropdown_list();
        // $this->data['type_of_leave_list'] = $this->bank_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->bank_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->bank_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->bank_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->bank_model->get_choose_carry_forward_list();

         $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Clients', base_url('client'));
        $this->mybreadcrumb->add($this->data['client']->company_name, base_url('client/audit_client').'/'.$id);
        $this->mybreadcrumb->add('Permanent Audit File', base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Edit PAF'));
        $meta = array('page_title' => 'Edit PAF', 'bc' => $bc, 'page_name' => 'Edit PAF');

        $this->page_construct('client_paf.php', $meta, $this->data);
    }

    public function submit_paf_list(){

        $this->session->set_userdata("tab_active", "active_paf_list");
        $user_id = $this->session->userdata('user_id');

        $form_data = $this->input->post();

        // print_r($form_data);
        $upload_data = [];
        $company_code = $form_data['company_code'];

        $p_id = $form_data['p_id'];
        $p_text = $form_data['p_text'];
        $p_type = $form_data['p_type'];
        $p_form_id = $form_data['p_form_id'];
        $p_index_no = $form_data['p_index_no'];

        $c_id = $form_data['c_id'];
        $c_text = $form_data['c_text'];
        $c_type = $form_data['c_type'];
        $c_parent_id = $form_data['c_parent_id'];
        $c_temp_parent_id = $form_data['c_temp_parent_id'];
        $c_index_no = $form_data['c_index_no'];
        $c_rename_flag = $form_data['rename_flag'];

        $uploaded_doc = json_decode($form_data['uploaded_docs']);
        // print_r($uploaded_doc);

        $all_parent = $this->build_parent_arr($p_id, $p_text, $p_type, $p_form_id, $p_index_no, $company_code);

        foreach ($all_parent as $key => $parent) {
            //need
            $p_result = $this->client_model->insert_paf_parent($parent['data'], $parent['id']);
            
        }


        $all_child = $this->build_child_arr($c_id, $c_text, $c_type, $c_parent_id, $c_index_no, $c_temp_parent_id, $company_code, $c_rename_flag);

        // print_r($all_parent);

        // print_r($all_child);
       
        foreach ($all_child as $c_key => $child) {
            //need
            $c_result = $this->client_model->insert_paf_child($child['data'], $child['id']);
            if($child['rename_flag'] == 1)
            {
                //log rename paf
                $data   = array(
                            'paf_id'     => $child['id'],
                            'date_time'  => date("Y-m-d H:i:s"),
                            'user_id'    => $user_id,
                            'paf_log'    => "rename",
                            'company_code' => $company_code
                          );

                $this->db->insert('audit_paf_log', $data);
            }
            
        }

        $i = 0;


        foreach ($uploaded_doc as $upload_key => $upload_value) {
            # code...
            $q = $this->db->query('select id from audit_paf_child where index_no ="'.$upload_key.'" and company_code = "'.$company_code.'" and deleted = 0');
            $c_id = $q->result()[0]->id;

            foreach ($upload_value as $file_key => $file_name) {
                # code...

                $upload_data[$i]['paf_child_id'] = $c_id;
                $upload_data[$i]['file_name'] = $file_name;
                $upload_data[$i]['file_path'] = "paf";

                $i++;
            }

            //log upload document to paf
            $data   = array(
                        'paf_id'     => $c_id,
                        'date_time'  => date("Y-m-d H:i:s"),
                        'user_id'    => $user_id,
                        'paf_log'    => "upload document(s)",
                        'company_code' => $company_code
                      );

            $this->db->insert('audit_paf_log', $data);


        }

        if(!empty($upload_data))
        {
            $this->db->insert_batch('audit_paf_document',$upload_data);
            // print_r($uploadData);
            
        }


        $data = array('status'=>'success');

        echo json_encode($data);

   
        // $result = $this->bank_model->submit_bank_list($data, $form_data['bank_list_id']);

        // echo $result;
    }

    public function build_parent_arr($p_id, $p_text, $p_type, $p_form_id, $p_index_no, $company_code)
    {
        $temp_arr = array();

        foreach ($p_id as $key => $p) 
        {
            
            

            array_push(
                    $temp_arr, array(
                        'id'      => $p_id[$key],
                        'data'    =>  array('company_code' =>  $company_code,
                                            'form_id'      =>  $p_form_id[$key],
                                            'index_no'     =>  $p_index_no[$key],
                                            'text'         =>  $p_text[$key],
                                            'type'         =>  $p_type[$key]
                                        )
                    )
                );

        }

        return $temp_arr;
    }

    public function build_child_arr($c_id, $c_text, $c_type, $c_parent_id, $c_index_no, $c_temp_parent_id, $company_code, $c_rename_flag)
    {
        $temp_arr = array();

        foreach ($c_id as $key => $c) 
        {
            if($c_parent_id[$key] == "")
            {
                $q = $this->db->query('select id from audit_paf_parent where form_id ="'.$c_temp_parent_id[$key].'" and company_code = "'.$company_code.'"');
                $c_parent_id[$key] = $q->result()[0]->id;
            }
            array_push(
                    $temp_arr, array(
                        'id'      => $c_id[$key],
                        'rename_flag' => $c_rename_flag[$key],
                        'data'    =>  array('company_code' =>  $company_code,
                                            'parent_id'    =>  $c_parent_id[$key],
                                            'index_no'     =>  $c_index_no[$key],
                                            'text'         =>  $c_text[$key],
                                            'type'         =>  $c_type[$key]
                                        )
                    )
                );

        }

        return $temp_arr;
    }

    public function delete_paf_parent($tab){
        $this->session->set_userdata("tab_active", $tab);

        $form_data = $this->input->post();

        $result = $this->client_model->delete_paf_parent($form_data['p_id']);

        echo $result;
    }


    public function delete_paf_child($tab){
        $this->session->set_userdata("tab_active", $tab);

        $form_data = $this->input->post();

        $result = $this->client_model->delete_paf_child($form_data['c_id']);

        echo $result;
    }

    public function delete_paf_doc(){

        $form_data = $this->input->post();

        $result = $this->client_model->delete_paf_doc($form_data['doc_id']);

        echo $result;
    }

    public function archive_paf_child($tab){
        $this->session->set_userdata("tab_active", $tab);

        $form_data = $this->input->post();

        $result = $this->client_model->archive_paf_child($form_data['c_id']);

        echo $result;
    }

    public function archive_paf_parent($tab){
        $this->session->set_userdata("tab_active", $tab);

        $form_data = $this->input->post();

        $result = $this->client_model->archive_paf_parent($form_data['p_id']);

        echo $result;
    }

    public function restore_paf($tab){
        $this->session->set_userdata("tab_active", $tab);

        $form_data = $this->input->post();

        $result = $this->client_model->restore_paf($form_data['c_id'],$form_data['c_type']);

        echo $result;
    }

    public function add_rp_info()
    {
        $data['company_code'] = $_POST['company_code'];
        //$data['family_info_id']=$_POST['family_info_id'][$i];
        $data['paf_child_id']=$_POST['paf_index'];

        if($_POST['save_type'] == "point")
        {
            $data['point'] = $_POST['point'];
        }

        if($_POST['save_type'] == "response")
        {
            $data['response'] = $_POST['response'];
        }


        $q = $this->db->get_where("audit_paf_review_point", array("id" => $_POST['review_point_id']));

        if (!$q->num_rows())
        {   
            if($_POST['save_type'] == "point")
            {
                $data['point_raised_by'] = $this->session->userdata('user_id');
                $data['point_raised_at'] = date("Y-m-d H:i:s");
            }

            $this->db->insert("audit_paf_review_point",$data);
            $insert_review_point_id = $this->db->insert_id();

            $point_raise_by = $this->client_model->get_point_raise_detail($insert_review_point_id);

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_review_point_id" => $insert_review_point_id, "point_raise_detail" => $point_raise_by[0]));
        }
        else
        {

            if($_POST['save_type'] == "response")
            {
                $data['response_updated_by'] = $this->session->userdata('user_id');
                $data['response_updated_at'] = date("Y-m-d H:i:s");
            }

            if($_POST['save_type'] == "point")
            {
                $data['point_updated_by'] = $this->session->userdata('user_id');
                $data['point_updated_at'] = date("Y-m-d H:i:s");
            }


            $this->db->update("audit_paf_review_point",$data,array("id" => $_POST['review_point_id']));

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
    }

    public function clear_rp()
    {
        $data['company_code'] = $_POST['company_code'];
        //$data['family_info_id']=$_POST['family_info_id'][$i];
        // $data['paf_child_id']=$_POST['paf_index'];


        $q = $this->db->get_where("audit_paf_review_point", array("id" => $_POST['review_point_id']));

        if (!$q->num_rows())
        {   
            echo json_encode(array("Status" => 0, 'message' => 'Failed to update information', 'title' => 'Failed'));
        }
        else
        {
           
            $data['response_updated_by'] = $this->session->userdata('user_id');
            $data['response_updated_at'] = date("Y-m-d H:i:s");
            $data['point_updated_by'] = $this->session->userdata('user_id');
            $data['point_updated_at'] = date("Y-m-d H:i:s");
            $data['cleared'] = $_POST['cleared'];
            


            $this->db->update("audit_paf_review_point",$data,array("id" => $_POST['review_point_id']));

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
    }

    public function check_cleared_points()
    {
        $form_data = $this->input->post();

        $paf_child_id = isset($form_data['paf_child_id'])?$form_data['paf_child_id']:"";
        $data = $this->client_model->get_uncleared_points($paf_child_id);
        $check_exist_rp = $this->client_model->get_rp_existance($paf_child_id);

        if(count($data) > 0 )
        {
            echo json_encode(array("Status" => 1, 'cleared' => "uncleared"));
        }
        else if (count($data) == 0 && count($check_exist_rp) > 0)
        {
            echo json_encode(array("Status" => 1, 'cleared' => "cleared"));
        }
        else 
        {
            echo json_encode(array("Status" => 1, 'cleared' => "no_rp"));

        }


    }

    public function delete_review_point ()
    {
        $id = $_POST["rp_id"];

        $data["deleted"] = 1;

        $this->db->update("audit_paf_review_point", $data, array('id'=>$id));

        echo json_encode(array("Status" => 1));
                
    }


    // public function uploadPafDoc()
    // {
    //     // $index_no = $POST['index_no'];

    //     if(isset($_FILES['paf_docs']))
    //     {
    //         $filesCount = count($_FILES['paf_docs']['name']);
    //         $uploadedFiles  = array();

    //         for($i = 0; $i < $filesCount; $i++)
    //         {   //echo json_encode($_FILES['uploadimages']);
    //             $_FILES['paf_doc']['name']     = $_FILES['paf_docs']['name'][$i];
    //             $_FILES['paf_doc']['type']     = $_FILES['paf_docs']['type'][$i];
    //             $_FILES['paf_doc']['tmp_name'] = $_FILES['paf_docs']['tmp_name'][$i];
    //             $_FILES['paf_doc']['error']    = $_FILES['paf_docs']['error'][$i];
    //             $_FILES['paf_doc']['size']     = $_FILES['paf_docs']['size'][$i];

    //             $uploadPath = './uploads/paf_client_documents';
    //             $config['upload_path'] = $uploadPath;
    //             $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
                
    //             $this->load->library('upload', $config);
    //             $this->upload->initialize($config);

    //             if($this->upload->do_upload('paf_doc'))
    //             {
    //                 $fileData = $this->upload->data();
    //                 // $uploadData[$i]['bank_auth_id'] = $_POST['ba_id'];
    //                 $uploadData[$i]['file_name'] = $fileData['file_name'];
    //                 array_push($uploadedFiles, $fileData['file_name']);
    //             }
    //             else
    //             {
    //                 $error = $this->upload->display_errors();
    //                 echo json_encode($error);
    //             }

    //         }
    //         if(!empty($uploadData))
    //         {
    //             // $this->db->insert_batch('audit_bank_auth_document',$uploadData);
    //             // print_r($uploadData);
                
    //         }


    //     }

    //     // if (count($this->session->userdata('ba_files_id')) != 0)
    //     // {
    //     //     $ba_files_id = $this->session->userdata('ba_files_id');
    //     //     $this->session->unset_userdata('ba_files_id');
    //     //     for($i = 0; $i < count($ba_files_id); $i++)
    //     //     {
    //     //         $files = $this->db->query("select * from audit_bank_auth_document where id='".$ba_files_id[$i]."'");
    //     //         $file_info = $files->result_array();

    //     //         $this->db->where('id', $ba_files_id[$i]);

    //     //         if(file_exists("./uploads/bank_images_or_pdf/".$file_info[0]["file_name"]))
    //     //         {
    //     //             unlink("./uploads/bank_images_or_pdf/".$file_info[0]["file_name"]);

    //     //         }
                
    //     //         $this->db->delete('audit_bank_auth_document', array('id' => $ba_files_id[$i]));
    //     //     }
    //     // }

        
    //     if(isset($uploadedFiles))
    //     {
    //         echo json_encode($uploadedFiles);
    //     }
    //     else
    //     {
    //         echo json_encode("");
    //     }

        
    // }

    public function save() //save_client
    {
        // echo "Save mou?";
        $change_cn = false;

        if(!isset($_POST['client_code']) && !isset($_POST['registration_no']) && !isset($_POST['acquried_by']) && !isset($_POST['company_name']) && !isset($_POST['incorporation_date']) && !isset($_POST['company_type']) && !isset($_POST['activity1']) && !isset($_POST['postal_code']) && !isset($_POST['street_name']))
        {
            $data['status']=$_POST['status'];

            $this->db->update("client",$data,array("company_code" =>  $_POST['company_code']));

            echo json_encode(array("Status" => 3,'message' => 'Information Updated', 'title' => 'Updated'));

        }
        else
        {
            
            $this->form_validation->set_rules('client_code', 'Client Code', 'required');
            $this->form_validation->set_rules('registration_no', 'Registration No', 'required');
            $this->form_validation->set_rules('company_name', 'Company Name', 'required');
            /*$this->form_validation->set_rules('former_name', 'Former Name', 'required');*/
            $this->form_validation->set_rules('incorporation_date', 'Incorporation Date', 'required');
            $this->form_validation->set_rules('company_type', 'Company Type', 'required');
            $this->form_validation->set_rules('activity1', 'Activity 1', 'required');
            /*$this->form_validation->set_rules('activity2', 'Activity 2', 'required');*/
            $this->form_validation->set_rules('postal_code', 'Postal Code', 'required');
            $this->form_validation->set_rules('street_name', 'Street Name', 'required');

            //echo($_POST['company_type']);
            if ($this->form_validation->run() == FALSE || $_POST['company_type'] == "0" || $_POST['status'] == "0" || $_POST['acquried_by'] == "0")
            {
                $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
                $bc = array(array('link' => '#', 'page' => lang('Add Clients')));
                $meta = array('page_title' => lang('Add Clients'), 'bc' => $bc, 'page_name' => 'Add Clients');

                if($_POST['company_type'] == "0")
                {
                    //echo (validation_errors());
                    $validate_company_type = "*The Company Type field is required.";
                    //form_error('company_type') = $validate_company_type;
                    //$this->form_validation->set_message('company_type', $validate_company_type);
                }
                else
                {
                    $validate_company_type = "";
                }
                if($_POST['acquried_by'] == "0")
                {
                    //echo (validation_errors());
                    $validate_acquried_by = "The Acquried By field is required.";
                    //form_error('company_type') = $validate_company_type;
                    //$this->form_validation->set_message('company_type', $validate_company_type);
                }
                else
                {
                    $validate_acquried_by = "";
                }
                if($_POST['status'] == "0")
                {
                    //echo (validation_errors());
                    $validate_status = "The Status field is required.";
                    //form_error('company_type') = $validate_company_type;
                    //$this->form_validation->set_message('company_type', $validate_company_type);
                }
                else
                {
                    $validate_status = "";
                }
                //echo json_encode(validation_errors());
                $error = array(
                    'client_code' => strip_tags(form_error('client_code')),
                    'registration_no' => strip_tags(form_error('registration_no')),
                    'company_name' => strip_tags(form_error('company_name')),
                    'company_type' => $validate_company_type,
                    /*'former_name' => strip_tags(form_error('former_name')),*/
                    'incorporation_date' => strip_tags(form_error('incorporation_date')),
                    'activity1' => strip_tags(form_error('activity1')),
                    /*'activity2' => strip_tags(form_error('activity2')),*/
                    'postal_code' => strip_tags(form_error('postal_code')),
                    'street_name' => strip_tags(form_error('street_name')),
                    'status' => $validate_status,
                    'acquried_by' => $validate_acquried_by,
                );
                $this->data["company_type"] = $validate_company_type;    

                $registered_address = $this->db->query("select postal_code, street_name, building_name, unit_no1, unit_no2 from firm ");
                $registered_address_info = $registered_address->result_array();
                $this->data['registered_address_info'] = $registered_address_info;  

                $this->session->set_userdata(array(
                        'company_type'  => $_POST['company_type'],
                    ));      
                $this->session->set_userdata(array(
                    'company_type'  =>  $_POST['acquried_by'],
                ));
                $this->session->set_userdata(array(
                    'status'  => $_POST['status'],
                ));
                //$this->page_construct('addpersonprofile.php', $meta, $this->data);

                //$this->page_construct('client/edit_client.php', $meta, $this->data);
                echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field', 'title' => 'Error', "error" => $error, $this->data));
            }
            else
            {
                $date_of_appointment = $this->db->query("select date_of_appointment from client_officers where company_code = '".$_POST['company_code']."' AND STR_TO_DATE(date_of_appointment,'%d/%m/%Y') < STR_TO_DATE('". $_POST['incorporation_date']. "','%d/%m/%Y') ");
                
                if ($date_of_appointment->num_rows())
                {
                    echo json_encode(array("Status" => 2,'message' => 'Appointment date of some officers are dated prior to incorporation date. Please change the date of appointment of all relevant officers first before changing the date of incorporation.', 'title' => 'Error'));
                }
                else
                {
                    $check_unique_client_code = $this->db->get_where("client", array("client_code" => $_POST['client_code'], "company_code !=" => $_POST['company_code'], "deleted !=" => 1));
                    //echo json_encode($check_unique_client_code->result_array());
                    if (!$check_unique_client_code->num_rows())
                    {
                        $check_unique_registration_no = $this->db->get_where("client", array("registration_no" => $_POST['registration_no'], "company_code !=" => $_POST['company_code'], "deleted !=" => 1));
                        if (!$check_unique_registration_no->num_rows())
                        {
                            $audit_data = array(
                                'company_code' => $_POST['company_code']
                            );

                            $data['created_by']=$this->session->userdata('user_id');
                            $data['firm_id']=$this->session->userdata('firm_id');

                            $data['acquried_by']=$_POST['acquried_by'];
                            $data['company_code']=$_POST['company_code'];
                            $company_code=$data['company_code'];
                            $data['client_code']=strtoupper($_POST['client_code']);
                            $data['registration_no']=strtoupper($_POST['registration_no']);
                            $registration_no = $data['registration_no'];
                            $data['company_name']=strtoupper($_POST['company_name']);
                            $data['former_name']=strtoupper($_POST['former_name']);

                            $data['incorporation_date']=$_POST['incorporation_date'];
                            $data['company_type']=$_POST['company_type'];
                            $data['status']=$_POST['status'];
                            $data['activity1']=strtoupper($_POST['activity1']);
                            $data['description1']=$_POST['description1'];
                            $data['activity2']=strtoupper($_POST['activity2']);
                            $data['description2']=$_POST['description2'];
                            $data['registered_address']=(isset($_POST['use_registered_address'])) ? 1 : 0;
                            $data['our_service_regis_address_id']= $_POST['service_reg_off'];

                            $data['postal_code']=strtoupper($_POST['postal_code']);
                            /*$data['city']=$_POST['city'];*/
                            $data['street_name']=strtoupper($_POST['street_name']);
                            $data['building_name']=strtoupper($_POST['building_name']);
                            $data['unit_no1']=strtoupper($_POST['unit_no1']);
                            $data['unit_no2']=strtoupper($_POST['unit_no2']);
                            $data['foreign_add_1']=strtoupper($_POST['foreign_add_1']);
                            $data['foreign_add_2']=strtoupper($_POST['foreign_add_2']);
                            $data['foreign_add_3']=strtoupper($_POST['foreign_add_3']);
                            $data['use_foreign_add_as_billing_add']=(isset($_POST['use_foreign_add_as_billing_add'])) ? 1 : 0;

                            $q = $this->db->get_where("client", array("company_code" => $_POST['company_code']));


                            if (!$q->num_rows())
                            {
                                if ($data['registration_no'] && $data['company_name'])
                                $this->db->insert("client",$data);
                                $this->db->insert("audit_client", $audit_data);

                                if($_POST['acquried_by'] == "1")
                                {
                                    $this->create_invoice("newly_incorporated", $_POST['company_code']);
                                }
                                
                                $this->recalculate();
                                
                            } 
                            else 
                            {
                                if ($data['registration_no'] && $data['company_name'])
                                {
                                    $old_client_data = $q->result_array();

                                    if($old_client_data[0]["company_name"] != $_POST['company_name'])
                                    {
                                        $data['former_name'] = $old_client_data[0]["company_name"]."\r\n".$data['former_name'];

                                        $change_company_name['subsidiary_name'] = strtoupper($_POST['company_name']);

                                        $this->db->update("corporate_representative",$change_company_name,array("subsidiary_name" => $old_client_data[0]["company_name"]));
                                        $change_cn = true; 
                                    }
                                    else
                                    {
                                        $change_cn = false; 
                                    }

                                    $check_address = [];
                                    $check_company_name = [];
                                    $check_company_type = [];
                                    $check_activity1 = [];
                                    $check_activity2 = [];

                                    $check_address[0]['postal_code']=$_POST['postal_code'];
                                    $check_address[0]['street_name']=$_POST['street_name'];
                                    $check_address[0]['building_name']=$_POST['building_name'];
                                    $check_address[0]['unit_no1']=$_POST['unit_no1'];
                                    $check_address[0]['unit_no2']=$_POST['unit_no2'];

                                    $check_company_name[0]['company_name']=$_POST['company_name'];

                                    $check_company_type[0]['company_type']=$_POST['company_type'];

                                    $check_acquried_by=$_POST['acquried_by'];

                                    $check_status=$_POST['status'];

                                    $check_activity1[0]["activity1"]=$_POST['activity1'];

                                    $check_activity2[0]["activity2"]=$_POST['activity2'];

                                    $old_client_address_result = $this->db->query("select postal_code, street_name, building_name, unit_no1, unit_no2 from client where company_code='".$company_code."'");

                                    $old_client_company_name_result = $this->db->query("select company_name from client where company_code='".$company_code."'");

                                    $old_client_company_type_result = $this->db->query("select company_type from client where company_code='".$company_code."'");

                                    $old_client_company_activity1_result = $this->db->query("select activity1 from client where company_code='".$company_code."'");

                                    $old_client_company_activity2_result = $this->db->query("select activity2 from client where company_code='".$company_code."'");

                                    $old_client_address_result = $old_client_address_result->result_array();

                                    $old_client_company_name_result = $old_client_company_name_result->result_array();

                                    $old_client_company_type_result = $old_client_company_type_result->result_array();

                                    $old_client_company_activity1_result = $old_client_company_activity1_result->result_array();

                                    $old_client_company_activity2_result = $old_client_company_activity2_result->result_array();

                                    /*echo json_encode($check_address);
                                    echo json_encode($old_client_address_result);
                                    echo json_encode($old_client_address_result == $check_address);*/
                                    //echo(!($old_client_address_result == $check_address));

                                    $this->db->update("client",$data,array("company_code" =>  $_POST['company_code']));



                                    if($check_acquried_by == 1 || $check_acquried_by == 2)
                                    {
                                        $check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));

                                        $query_check_history_client = $check_history_client->result_array();

                                        
                                        if (!$check_history_client->num_rows())
                                        {
                                            $k = $q->result();

                                            foreach($k as $r) {
                                                $this->db->insert("history_client",$r);
                                            }
                                        } 
                                        else 
                                        {
                                            //$c = $q->result_array();

                                            $data_history['acquried_by'] = $check_acquried_by;

                                            $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
                                            
                                        }

                                        // if($query_check_history_client[0]["acquried_by"] == 2)
                                        // {
                                        //     //$this->create_document("acquried_by", $_POST['company_code']);
                                        // }
                                    }

                                    if($check_status == 2 || $check_status == 3)
                                    {
                                        $check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));
                                        if (!$check_history_client->num_rows())
                                        {
                                            $k = $q->result();

                                            foreach($k as $r) {
                                                $this->db->insert("history_client",$r);
                                            }
                                        } 
                                        else 
                                        {
                                            //$c = $q->result_array();

                                            $data_history['status'] = $check_status;

                                            $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
                                            
                                        }

                                        //$this->create_document("status", $_POST['company_code']);
                                    }

                                    if(!($old_client_company_activity1_result == $check_activity1))
                                    {
                                        $check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));
                                        if (!$check_history_client->num_rows())
                                        {
                                            $w = $q->result();

                                            foreach($w as $r) {
                                                $this->db->insert("history_client",$r);
                                            }
                                        } 
                                        else 
                                        {
                                            $x = $q->result_array();

                                            $data_history['activity1'] = $x[0]["activity1"];

                                            $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
                                            
                                        }

                                        //$this->create_document("change_activity1", $_POST['company_code']);
                                    }

                                    if($old_client_company_activity2_result[0]["activity2"] != '')
                                    {
                                        if(!($old_client_company_activity2_result == $check_activity2))
                                        {
                                            $check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));
                                            if (!$check_history_client->num_rows())
                                            {
                                                $w = $q->result();

                                                foreach($w as $r) {
                                                    $this->db->insert("history_client",$r);
                                                }
                                            } 
                                            else 
                                            {
                                                $x = $q->result_array();

                                                $data_history['activity2'] = $x[0]["activity2"];

                                                $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
                                                
                                            }

                                            //$this->create_document("change_activity2", $_POST['company_code']);
                                        }
                                    }

                                    if(!($old_client_company_type_result == $check_company_type))
                                    {
                                        $check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));
                                        if (!$check_history_client->num_rows())
                                        {
                                            $d = $q->result();

                                            foreach($d as $r) {
                                                $this->db->insert("history_client",$r);
                                            }
                                        } 
                                        else 
                                        {
                                            $c = $q->result_array();

                                            $data_history['company_type'] = $c[0]["company_type"];

                                            $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
                                            
                                        }

                                        //$this->create_document("change_company_type", $_POST['company_code']);
                                    }

                                    if(!($old_client_address_result == $check_address))
                                    {
                                        $this->create_invoice("change_address", $_POST['company_code']);
                                        $check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));
                                        if (!$check_history_client->num_rows())
                                        {
                                            $t = $q->result();

                                            foreach($t as $r) {
                                                $this->db->insert("history_client",$r);
                                            }
                                        } 
                                        else 
                                        {
                                            $h = $q->result_array();

                                            $data_history['registered_address'] = $h[0]["registered_address"];
                                            $data_history['our_service_regis_address_id'] = $h[0]["our_service_regis_address_id"];
                                            $data_history['postal_code'] = $h[0]["postal_code"];
                                            $data_history['street_name'] = $h[0]["street_name"];
                                            $data_history['building_name'] = $h[0]["building_name"];
                                            $data_history['unit_no1'] = $h[0]["unit_no1"];
                                            $data_history['unit_no2'] = $h[0]["unit_no2"];

                                            $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
                                            
                                        }

                                        //$this->create_document("change_address", $_POST['company_code']);
                                    }
                                    if(!($old_client_company_name_result == $check_company_name))
                                    {
                                        $this->create_invoice("change_company_name", $_POST['company_code']);
                                        $check_history_client = $this->db->get_where("history_client", array("company_code" => $_POST['company_code']));
                                        if (!$check_history_client->num_rows())
                                        {
                                            $f = $q->result();

                                            foreach($f as $r) {
                                                $this->db->insert("history_client",$r);
                                            }
                                        } 
                                        else 
                                        {
                                            $d = $q->result_array();

                                            $data_history['company_name'] = $d[0]["company_name"];

                                            $this->db->update("history_client",$data_history,array("company_code" =>  $_POST['company_code']));
                                            
                                        }

                                        //$this->create_document("change_company_name", $_POST['company_code']);
                                    }
                                }
                                
                            }

                            $this->data['client_billing_data'] = $this->client_model->get_all_client_billing_info($_POST['company_code']);

                            


                            echo json_encode(array("Status" => 1,'message' => 'Information Updated', 'title' => 'Updated', 'client_billing' => $this->data, 'change_company_name' => $change_cn));
                            // $this->session->set_userdata('unique_code', '');
                        }
                        else
                        {
                            echo json_encode(array("Status" => 2,'message' => 'The registration no. already in the system.', 'title' => 'Error'));
                        }

                    }
                    else
                    {
                        echo json_encode(array("Status" => 2,'message' => 'The client code already in the system.', 'title' => 'Error'));
                    }
                }
            }
        }
    }

    //  public function send_stocktake_reminder()
    // {
    //     $next_month = date('d F yy', strtotime("+30 days"));
    //     // echo $next_month.'here next month';

    //     // get all client which FYE date is 1 month after today
    //     $get_due_client = $this->client_model->get_stocktake_client_list($next_month);

    //     // $create_bc_list = $this->bank_model->get_create_bc_list($month, $year);
    //     $bc_id_list = array();

    //     // print_r($create_bc_list);
    //     foreach ($get_due_client as $key => $client) {
    //         // $date = DateTime::createFromFormat('d F yy', $create_bc['year_end']);
    //         $client_email = $client['email'];
    //         if($client_email != null)
    //         {
    //             $fye_date = $client['year_end'];
    //             // $client_email = $client['email'];
    //             $send_date = date("d F yy");
    //             $client_detail = $this->client_model->getClientbyCode($client['company_code']);
    //             $client_name = $client_detail->company_name;
    //             $client_address = $this->write_address($client_detail->street_name, $client_detail->unit_no1, $client_detail->unit_no2, $client_detail->building_name, $client_detail->postal_code, 'letter');
    //             $client_servicing_firm = $this->client_model->getFirmbyCode($client['company_code']);

    //             $parse_data = array(
    //                 'today_date'  => $send_date,
    //                 'client_name'      => $client_name,
    //                 'client_address'   => $client_address,
    //                 'fye_date'         => $fye_date,
    //                 'firm_name'        => $client_servicing_firm
    //             );


    //             $manager_email = "penny@aaa-global.com";
    //             $partner_email = "woellywilliam@acumenbizcorp.com.sg";
                
    //             $cc_email = array();
    //             array_push($cc_email, $manager_email, $partner_email);
    //             $msg = file_get_contents('./application/modules/client/email_templates/stocktake_reminder.html');
    //             $message = $this->parser->parse_string($msg, $parse_data);

    //             $subject = 'Physical Inventory Count Observation - '.$client_name;
    //             $this->sma->send_email($client_email, $subject, $message,"" ,"" ,"", $cc_email);

    //         }
    //         else
    //         {
            
    //             // $client_email = $client['email'];
    //             // $send_date = date("d F yy");
    //             // $client_detail = $this->client_model->getClientbyCode($client['company_code']);
    //             // $client_name = $client_detail->company_name;
    //             // $client_address = $this->write_address($client_detail->street_name, $client_detail->unit_no1, $client_detail->unit_no2, $client_detail->building_name, $client_detail->postal_code, 'letter');
    //             // $client_servicing_firm = $this->client_model->getFirmbyCode($client['company_code']);

    //             // $parse_data = array(
    //             //     'today_date'  => $send_date,
    //             //     'client_name'      => $client_name,
    //             //     'client_address'   => $client_address,
    //             //     'fye_date'         => $fye_date,
    //             //     'firm_name'        => $client_servicing_firm
    //             // );


    //             // $manager_email = "penny@aaa-global.com";
    //             // $partner_email = "woellywilliam@acumenbizcorp.com.sg";
                
    //             // $cc_email = array();
    //             // array_push($cc_email, $manager_email, $partner_email);
    //             // $msg = file_get_contents('./application/modules/client/email_templates/stocktake_reminder.html');
    //             // $message = $this->parser->parse_string($msg, $parse_data);

    //             // $subject = 'Physical Inventory Count Observation - '.$client_name;
    //             // $this->sma->send_email($client_email, $subject, $message,"" ,"" ,"", $cc_email);

    //         }
           



    //        // echo $form_data['auth_date'];

    //         // $data = array(
    //         //     'company_code' => $create_bc['company_code'],
    //         //     'bank_id' => $create_bc['bank_id'],
    //         //     'fye_date' => $fye_date,
    //         //     'confirm_status' => 0,
    //         //     'sent_on_date' => $send_date
    //         // );

    //         // $result = $this->bank_model->submit_bank_confirm($data);
    //         // array_push($bc_id_list, $result);

        // }

        // foreach ($bc_id_list as $key => $bc_id) {
        //     $data = $this->generate_confirm_document($key+1, $bc_id);
        //     $this->zip->read_file($data["path"]);
        // }

        // $this->zip->archive($_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/bank_confirmation('.$month.$year.').zip');

        
        // $pic_email = $pic_info[0]['email'];
        // if($pic_email == '"james@acumenbizcorp.com.sg"'){
        //     $pic_email = str_replace('@acumenbizcorp.com.sg', '@aaa-global.com',$pic_email);
        // }
        
        // $manager_email = "penny@aaa-global.com";
        // $msg = file_get_contents('./application/modules/bank/email_templates/pic_bank_confirmation.html');
        // $message = $this->parser->parse_string($msg, $parse_data);

        // $subject = 'Bank Confirmation - '.$month." ".$year;
        // $this->sma->send_email($pic_email, $subject, $message,"" ,"" ,$_SERVER['DOCUMENT_ROOT'].'audit/pdf/document/bank_confirmation('.$month.$year.').zip' ,$manager_email, "");
        // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)

        // print_r($bc_id_list);
    // }



    public function check_incorporation_date()
    {
        $company_code = $_POST["company_code"];

        $q = $this->db->query("select incorporation_date from client where company_code = '".$company_code."'");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        echo FALSE;

    }

    public function check_client_data()
    {
        $flag = false;

        $company_code=$_POST['company_code'];
        // echo json_encode($company_code);
 
        $check_address = [];
        $check_company_name = [];
        $check_company_type = [];
        $check_activity1 = [];
        $check_activity2 = [];
        

        $check_address[0]['postal_code']=$_POST['postal_code'];
        $check_address[0]['street_name']=$_POST['street_name'];
        $check_address[0]['building_name']=$_POST['building_name'];
        $check_address[0]['unit_no1']=$_POST['unit_no1'];
        $check_address[0]['unit_no2']=$_POST['unit_no2'];

        $check_company_name[0]['company_name']=$_POST['company_name'];

        $check_company_type[0]['company_type']=$_POST['company_type'];

        $check_activity1[0]["activity1"]=$_POST['activity1'];
        $check_activity2[0]["activity2"]=$_POST['activity2'];

        $query = $this->db->get_where("history_client", array("company_code" => $company_code));

        if (!$query->num_rows())//if don't have anythings
        {
            // echo json_encode(false);
            $flag = false;
        }
        else
        {
            $query = $query->result_array();

            $old_client_company_name_result = $this->db->query("select company_name from client where company_code='".$company_code."'");

            $old_client_company_name_result = $old_client_company_name_result->result_array();

            if(!($old_client_company_name_result == $check_company_name))
            {
                $pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "2"));
                if($pending_documents_query->num_rows())
                {
                    // echo json_encode(true);
                    $flag = true;
                }
                else
                {
                    // echo json_encode(false);
                    $flag = false;
                }
            }
            else
            {
                // echo json_encode(false);
                $flag = false;
            }

            $old_client_address_result = $this->db->query("select postal_code, street_name, building_name, unit_no1, unit_no2 from client where company_code='".$company_code."'");

            $old_client_address_result = $old_client_address_result->result_array();

            if(!($old_client_address_result == $check_address))
            {
                $pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "7"));

                if($pending_documents_query->num_rows())
                {
                    // echo json_encode(true);
                    $flag = true;
                }
                else
                {
                    // echo json_encode(false);
                    $flag = false;
                }
            }
            else
            {
                // echo json_encode(false);
                $flag = false;
            }

            $old_client_company_type_result = $this->db->query("select company_type from client where company_code='".$company_code."'");

            $old_client_company_type_result = $old_client_company_type_result->result_array();

            if(!($old_client_company_type_result == $check_company_type))
            {
                $pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "3"));

                if($pending_documents_query->num_rows())
                {
                    // echo json_encode(true);
                    $flag = true;
                }
                else
                {
                    // echo json_encode(false);
                    $flag = false;
                }
            }
            else
            {
                // echo json_encode(false);
                $flag = false;
            }

            if($_POST['acquried_by'] == 1)
            {
                $pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "1"));

                if($pending_documents_query->num_rows())
                {
                    // echo json_encode(true);
                    $flag = true;
                }
                else
                {
                    // echo json_encode(false);
                    $flag = false;
                }
            }

            if($_POST['status'] == 2 || $_POST['status'] == 3)
            {
                $pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "4"));

                if($pending_documents_query->num_rows())
                {
                    // echo json_encode(true);
                    $flag = true;
                }
                else
                {
                    // echo json_encode(false);
                    $flag = false;
                }
            }

            $old_client_company_activity1_result = $this->db->query("select activity1 from client where company_code='".$company_code."'");

            $old_client_company_activity1_result = $old_client_company_activity1_result->result_array();

            if(!($old_client_company_activity1_result == $check_activity1))
            {
                $pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "5"));

                if($pending_documents_query->num_rows())
                {
                    // echo json_encode(true);
                    $flag = true;
                }
                else
                {
                    // echo json_encode(false);
                    $flag = false;
                }
            }
            else
            {
                // echo json_encode(false);
                $flag - false;
            }

            $old_client_company_activity2_result = $this->db->query("select activity2 from client where company_code='".$company_code."'");

            $old_client_company_activity2_result = $old_client_company_activity2_result->result_array();

            if(!($old_client_company_activity2_result == $check_activity2))
            {
                $pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $query[0]["id"], "received_on" => "", "triggered_by" => "6"));

                if($pending_documents_query->num_rows())
                {
                    // echo json_encode(true);
                    $flag = true;
                }
                else
                {
                    // echo json_encode(false);
                    $flag = false;
                }
            }
            else
            {
                // echo json_encode(false);
                $flag = false;
            }
            
        }

        echo json_encode($flag);

    }

     // Fetch all Company Type list
    public static function getCompanytype() {

        //try {
        //echo $nationalityId;
        $ci =& get_instance();

        $query = "SELECT id, company_type FROM company_type";

        $result = $ci->db->query($query);
        $result = $result->result_array();
        //echo json_encode($result);
        if(!$result) {
          throw new exception("Company type not found.");
        }

        $res = array();
        foreach($result as $row) {
          $res[$row['id']] = $row['company_type'];
        }
        //$res = json_decode($res);
        $ci =& get_instance();
        $select_company_type = $ci->session->userdata('company_type');

        /*if($nationalityId != "null")
        {
        $select_nationality = $nationalityId;
        }*/
        /*else
        {
        $select_nationality = "null";
        }*/
        //$select_country = $select_country->result_array();
        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Company Type fetched successfully.", 'result'=>$res, 'selected_company_type'=>$select_company_type);
        /*} catch (Exception $e) {
        $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
        }*/ /*finally {
        echo json_encode($data);
        //return $data;

        }*/
        echo json_encode($data);
    }

    public static function getAcquriedBy() {

        //try {
        //echo $nationalityId;
        $ci =& get_instance();

        $query = "SELECT id, acquried_by FROM acquried_by";

        $result = $ci->db->query($query);
        $result = $result->result_array();
        //echo json_encode($result);
        if(!$result) {
          throw new exception("Acquried By not found.");
        }

        $res = array();
        foreach($result as $row) {
          $res[$row['id']] = $row['acquried_by'];
        }
        //$res = json_decode($res);
        $ci =& get_instance();
        $select_acquried_by = $ci->session->userdata('acquried_by');

        /*if($nationalityId != "null")
        {
        $select_nationality = $nationalityId;
        }*/
        /*else
        {
        $select_nationality = "null";
        }*/
        //$select_country = $select_country->result_array();
        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Acquried By fetched successfully.", 'result'=>$res, 'selected_acquried_by'=>$select_acquried_by);
        /*} catch (Exception $e) {
        $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
        }*/ /*finally {
        echo json_encode($data);
        //return $data;

        }*/
        echo json_encode($data);
    }

    public static function getStatus() {

        //try {
        //echo $nationalityId;
        $ci =& get_instance();

        $query = "SELECT id, status FROM status";

        $result = $ci->db->query($query);
        $result = $result->result_array();
        //echo json_encode($result);
        if(!$result) {
          throw new exception("Status not found.");
        }

        $res = array();
        foreach($result as $row) {
          $res[$row['id']] = $row['status'];
        }
        //$res = json_decode($res);
        $ci =& get_instance();
        $select_status = $ci->session->userdata('status');

        /*if($nationalityId != "null")
        {
        $select_nationality = $nationalityId;
        }*/
        /*else
        {
        $select_nationality = "null";
        }*/
        //$select_country = $select_country->result_array();
        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Status fetched successfully.", 'result'=>$res, 'selected_status'=>$select_status);
        /*} catch (Exception $e) {
        $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
        }*/ /*finally {
        echo json_encode($data);
        //return $data;

        }*/
        echo json_encode($data);
    }

    public function recalculate()
    {
        $this->db->select("users.id")
                ->from("users")
                ->join('groups', 'users.group_id = groups.id', 'inner')
                ->join('user_firm as a', 'a.user_id = "'.$this->session->userdata("user_id").'"', 'inner')
                ->join('user_firm as b', 'a.firm_id=b.firm_id', 'inner')
                ->where('b.user_id = users.id')
                ->where('b.user_id != "'.$this->session->userdata("user_id").'"')
                ->group_by('users.id');
        $test = $this->db->get();
        $test = $test->result_array();

        $data_user_id = array();

        foreach ($test as $rr) {
            array_push($data_user_id, $rr["id"]);
        }

        $this->db->select("firm_id")
        ->from("user_firm")
        ->where('user_id = "'.$this->session->userdata("user_id").'"');

        $firm_id = $this->db->get();
        $firm_id = $firm_id->result_array();

        $data_firm_id = array();

        foreach ($firm_id as $rows) {
            array_push($data_firm_id, $rows["firm_id"]);
        }

        $user["no_of_user"] = count($data_user_id);
        $user["no_of_firm"] = count($data_firm_id);
        $this->db->where('id', $this->session->userdata("user_id"));
        $this->db->update('users',$user);

        if(count($data_firm_id) != 0)
        {
            

            $this->db->select('id');
            $this->db->from('client');
            $this->db->where_in('firm_id', $data_firm_id);

            $num_client = $this->db->get();
            $num_client = $num_client->result_array();

            if(count($num_client) != 0)
            {   
                //echo json_encode($row["id"]);
                $users["no_of_client"] = count($num_client);

            }
            else
            {
                $users["no_of_client"] = 0;
            }

            $this->db->where('id', $this->session->userdata("user_id"));
            $this->db->update('users',$users);

            $data_user_id = array();

            foreach ($test as $r) {
                array_push($data_user_id, $r["id"]);
            }

            if(count($data_user_id) != 0)
            {  
                $this->db->where_in('id', $data_user_id);
                $this->db->update('users',$users);
            }
        }
    }

    public function create_invoice($type, $company_code, $position_id = null)
    {
        //$type = "change_charges";
        if($type == "change_address")
        {
            $result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 3 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
        }
        elseif($type == "change_charges")
        {
            $result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 7 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
        }
        elseif($type == "change_company_name")
        {
            $result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 8 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
        }
        elseif($type == "change_director")
        {
            $result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 4 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
        }
        elseif($type == "change_secretary")
        {
            $result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 6 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
        }
        elseif($type == "change_auditor") 
        {
            $result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 5 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
        }
        elseif($type == "share_allotment")
        {
            $result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 9 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
        }
        elseif($type == "share_transfer")
        {
            $result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 10 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
        }
        elseif($type == "share_buyback")
        {
            $result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 11 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
        }
        elseif($type == "newly_incorporated")
        {
            $result = $this->db->query("select client_billing_info.* from client_billing_info left join client on client.company_code = client_billing_info.company_code where client_billing_info.service = 12 AND client_billing_info.company_code='".$company_code."' AND client.auto_generate = 1");
        }

        $result = $result->result_array();
        //echo json_encode($result);
        if($result) 
        {
            //echo json_encode($result[0]['service']);
            $now = getDate();

            $current_date = DATE("Y-m-d",now());

            $billing_result = $this->db->query("select * from billing where date_format(created_at, '%Y-%m-%d') = '".$current_date."' AND company_code='".$company_code."' AND outstanding != 0.00 AND status != 1");

            $billing_result = $billing_result->result_array();

            $client = $this->db->query("select * from client where company_code='".$company_code."'");

            $client = $client->result_array();

            $firm = $this->db->query("select * from firm where id = '".$client[0]["firm_id"]."'");

            $firm = $firm->result_array();

            if($firm[0]["gst_checkbox"] == 1)
            {
                if($firm[0]["gst_date"] != null)
                {
                    $array = explode('/', $firm[0]["gst_date"]);
                    $tmp = $array[0];
                    $array[0] = $array[1];
                    $array[1] = $tmp;
                    unset($tmp);
                    $gst_date = implode('/', $array);
                    $time = strtotime($gst_date);
                    $gst_date = date('Y-m-d',$time);
                    $gst_date = strtotime($gst_date);
                }

                if($firm[0]["previous_gst_date"] != null)
                {
                    $array = explode('/', $firm[0]["previous_gst_date"]);
                    $tmp = $array[0];
                    $array[0] = $array[1];
                    $array[1] = $tmp;
                    unset($tmp);
                    $previous_gst_date = implode('/', $array);
                    $time = strtotime($previous_gst_date);
                    $previous_gst_date = date('Y-m-d',$time);
                    $previous_gst_date = strtotime($gst_date);
                }

                //echo json_encode($previous_gst_date > $gst_date);
                $invoice_date = DATE("Y-m-d",now());
                $invoice_date = strtotime($invoice_date);

                if($previous_gst_date == null && $gst_date != null)
                {
                    if($invoice_date >= $gst_date)
                    {
                        $billing_service['gst_rate'] = $firm[0]["gst"];
                    }
                    else
                    {
                        $billing_service['gst_rate'] = 0;
                    }
                }
                else
                {
                    if($previous_gst_date == $gst_date)
                    {
                        $billing_service['gst_rate'] = $firm[0]["gst"];
                    }
                    else if($previous_gst_date > $gst_date)
                    {
                        if($previous_gst_date > $invoice_date && $invoice_date >= $gst_date)
                        {
                            $billing_service['gst_rate'] = $firm[0]["gst"];
                        }
                        else if($invoice_date >= $previous_gst_date)
                        {
                            $billing_service['gst_rate'] = $firm[0]["previous_gst"];
                        }
                        else
                        {
                            $billing_service['gst_rate'] = 0;
                        }
                    }
                    else if($gst_date > $previous_gst_date)
                    {
                        if($gst_date > $invoice_date && $invoice_date >= $previous_gst_date)
                        {
                            $billing_service['gst_rate'] = $firm[0]["previous_gst"];
                        }
                        else if($invoice_date >= $gst_date)
                        {
                            $billing_service['gst_rate'] = $firm[0]["gst"];
                        }
                        else
                        {
                            $billing_service['gst_rate'] = 0;
                        }
                    }
                }
                
            }
            else
            {
                $billing_service['gst_rate'] = 0;
            }
            
            if($billing_result)
            {
                $billing['amount'] = $billing_result[0]['amount'] + ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);
                $billing['outstanding'] = $billing_result[0]['outstanding'] + ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);

                $this->db->update("billing",$billing,array("id" => $billing_result[0]['id']));


                $billing_service['billing_id'] = $billing_result[0]['id'];
            }
            else
            {
                //$num_row_billing_table = $this->db->query("select COUNT(*) from billing where company_code='".$company_code."'");
                //echo json_encode($num_row_billing_table->result_array());

                // $query_invoice_no = $this->db->query("SELECT invoice_no FROM billing where id = (SELECT max(id) FROM billing where status = '0' and firm_id = '".$this->session->userdata('firm_id')."')");
                //$id = $query->row()->id;

                $query_invoice_no = $this->db->query("select id, invoice_no, MAX(CAST(SUBSTRING(invoice_no, -4) AS UNSIGNED)) as latest_invoice_no from billing where status = '0' and firm_id = '".$this->session->userdata('firm_id')."' GROUP BY invoice_no ORDER BY latest_invoice_no DESC LIMIT 1");

                if ($query_invoice_no->num_rows() > 0) 
                {
                    $query_invoice_no = $query_invoice_no->result_array();

                    // $last_section_invoice_no = (int)$query_invoice_no[0]["invoice_no"] + 1;
                    // $number = "AB-".date("Y")."-".str_pad($last_section_invoice_no,4,"0",STR_PAD_LEFT);

                    $last_section_invoice_no = (string)$query_invoice_no[0]["invoice_no"];
                    //echo (substr_replace($last_section_invoice_no, "", -1));
                    //$number = substr_replace($last_section_invoice_no, "", -1).((int)($last_section_invoice_no[strlen($last_section_invoice_no)-1]) + 1);
                    $number = substr_replace($last_section_invoice_no, "", -4).(str_pad((int)(substr($last_section_invoice_no, -4)) + 1, 4, '0', STR_PAD_LEFT));

                }
                else
                {
                    $number = "AB-".date("Y")."-".str_pad(1,4,"0",STR_PAD_LEFT);
                }

                // $query_invoice_no = $this->db->query("select MAX(CAST(SUBSTRING(invoice_no,10, length(invoice_no)-9) AS UNSIGNED)) as invoice_no from billing");

    //             //echo json_encode($query_test);

    //             if ($query_invoice_no->num_rows() > 0) 
    //             {
    //                 $query_invoice_no = $query_invoice_no->result_array();
    //                 //$array_invoice_no = explode('-', $query_invoice_no[0]["invoice_no"]);
    //              $last_section_invoice_no = (int)$query_invoice_no[0]["invoice_no"] + 1;
    //                 $number = date("Y")."-ABC-".$last_section_invoice_no;

    //             }
    //             else
    //             {
    //                 $number = date("Y")."-ABC-1";
    //             }
                /*$number = sprintf('%02d', $now[0]);
                $number = 'INV - '.$number;*/
                $billing['firm_id'] = $client[0]["firm_id"];
                $billing['invoice_no'] = $number;
                $billing['currency_id'] = 1;
                $billing['company_code'] = $company_code;
                $billing['invoice_date'] = DATE("d/m/Y",now());
                $billing['rate'] = 1.0000;
                $billing['amount'] = ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);
                $billing['outstanding'] = ((1+($billing_service['gst_rate'] / 100)) * $result[0]['amount']);

                //$billing_service['client_billing_info_id'] = $result[0]['client_billing_info_id'];

                $this->db->insert("billing",$billing);
                $billing_service['billing_id'] = $this->db->insert_id();

                

            }
            $billing_service['service'] = $result[0]['service'];
            $billing_service['invoice_date'] = DATE("d/m/Y",now());
            //$billing_service['client_billing_info_id'] = $result[0]['client_billing_info_id'];
            $billing_service['invoice_description'] = $result[0]['invoice_description'];
            $billing_service['amount'] = $result[0]['amount'];

            $this->db->insert("billing_service",$billing_service);
            

            //echo true;

        }
    }

    public function delete_filing ()
    {
        $id = $_POST["filing_id"];
        $this->db->delete("filing",array('id'=>$id));

        $get_all_filing_data = $this->db->query("select filing.*, financial_year_period.period from filing left join financial_year_period on financial_year_period.id = filing.financial_year_period_id where company_code='".$_POST['company_code']."' order by id");

        //echo json_encode($q->result());

        if ($get_all_filing_data->num_rows() > 0) {
            foreach (($get_all_filing_data->result()) as $row) {
                $data[] = $row;
            }
        }

        $get_all_eci_filing_data = $this->db->query("select eci_filing.* from eci_filing where company_code='".$_POST['company_code']."' order by id");

        $get_all_eci_filing_data = $get_all_eci_filing_data->result_array();

        if(count($get_all_eci_filing_data) > 0)
        {
            $have_eci = false;
        }
        else
        {
            $have_eci = true;
        }

        $get_all_tax_filing_data = $this->db->query("select tax_filing.* from tax_filing where company_code='".$_POST['company_code']."' order by id");

        $get_all_tax_filing_data = $get_all_tax_filing_data->result_array();

        if(count($get_all_tax_filing_data) > 0)
        {
            $have_tax = false;
        }
        else
        {
            $have_tax = true;
        }

        echo json_encode(array("filing_data" => $data, 'have_eci' => $have_eci, 'have_tax' => $have_tax));
    }


    public function check_filing_data()
    {
        $check_year_end = [];

        $check_year_end[0]['year_end'] = $_POST['year_end'];

        $query = $this->db->get_where("history_filing", array("company_code" => $_POST['company_code'], "id" => $_POST['filing_id']));
        
        if (!$query->num_rows())//if don't have anythings
        {
            echo json_encode(false);
        }
        else
        {
            $query = $query->result_array();

            $old_filing_result = $this->db->query("select year_end from filing where id='".$_POST['filing_id']."' AND company_code = '".$_POST['company_code']."'");

            $old_filing_result = $old_filing_result->result_array();

            $get_client_info = $this->db->query("select * from client where company_code='".$_POST['company_code']."'");

            $get_client_info = $get_client_info->result_array();

            if(!($old_filing_result == $check_year_end))
            {
                $pending_documents_query = $this->db->get_where("pending_documents", array("client_id" => $get_client_info[0]["id"], "filing_id" => $_POST['filing_id'], "received_on" => "", "triggered_by" => "19"));
                if($pending_documents_query->num_rows())
                {
                    echo json_encode(true);
                }
                else
                {
                    echo json_encode(false);
                }
            }
            else
            {
                echo json_encode(false);
            }

        }
    }

    public function calculate_new_filing_date()
    {   
        $latest_year_end = $_POST["latest_year_end"];

        $year_end_date = new DateTime($latest_year_end);

        $date_175 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

        $new_filing['due_date_175'] =  date("t F Y", strtotime($date_175));

        $date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

        $new_filing['due_date_201'] =  date("t F Y", strtotime($date_201));

        $date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

        $new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Year End fetched successfully.", 'result'=>$new_filing);

        echo json_encode($data);
        //echo ($year_end_date);
    }

    public function get_financial_year_period()
    {
        $result = $this->db->query("select * from financial_year_period");

        $result = $result->result_array();

        if(!$result) {
            throw new exception("Financial Year Period not found.");
        }
        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['period'];
        }

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Financial Year Period fetched successfully.", 'result'=>$res);

        echo json_encode($data);
    }

    public function get_gst_filing_cycle()
    {
        $result = $this->db->query("select * from gst_filing_cycle");

        $result = $result->result_array();

        if(!$result) {
            throw new exception("GST Filing Cycle not found.");
        }
        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['gst_filing_cycle_name'];
        }
        
        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"GST Filing Cycle fetched successfully.", 'result'=>$res);

        echo json_encode($data);
    }

    public function add_filing_info()
    {
        $this->form_validation->set_rules('year_end', 'Year End', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $error = array(
                'year_end' => strip_tags(form_error('year_end')),
            );

            echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field', 'title' => 'Error', "error" => $error));
        }
        else
        {
            $filing['company_code'] = $_POST['company_code'];
            $filing['year_end'] = $_POST['year_end'];
            $filing['agm'] = $_POST['agm'];
            $filing['ar_filing_date'] = $_POST['ar_filing_date'];
            $filing['financial_year_period_id'] = $_POST['financial_year_period'];
            $filing['due_date_175'] = $_POST['due_date_175'];
            $filing['175_extended_to'] = $_POST['extended_to_175'];
            $filing['due_date_201'] = $_POST['due_date_201'];
            $filing["201_extended_to"] = $_POST['extended_to_201'];
            $filing['due_date_197'] = $_POST['due_date_197'];
            $filing["197_extended_to"] = $_POST['extended_to_197'];
            /*if($_POST['extended_to_175'] != 0)
            {
                $filing['due_date_175'] = $_POST['extended_to_175'];
            }

            if($_POST['extended_to_201'] != 0)
            {
                $filing['due_date_201'] = $_POST['extended_to_201'];
            }*/

            $q = $this->db->get_where("filing", array("company_code" => $_POST['company_code'], "id" => $_POST['filing_id']));

            if (!$q->num_rows())
            {   
                if($_POST["agm"] != "" && $_POST['agm'] != "dispensed")
                {
                    //$this->create_document("agm_held", $_POST['company_code'], null, null, null, null);
                }

                if($_POST["agm"] != "" && $_POST['agm'] == "dispensed")
                {
                    //$this->create_document("dispense_agm", $_POST['company_code'], null, null, null, null);
                }           

                $this->db->insert("filing",$filing);
                //$insert_share_capital_id = $this->db->insert_id();
            } 
            else 
            {   
                $check_year_end = [];
                $check_agm = [];

                $check_year_end[0]['year_end'] = $_POST['year_end'];
                $check_agm[0]['agm'] = $_POST['agm'];

                $old_filing_result = $this->db->query("select year_end from filing where id='".$_POST['filing_id']."' AND company_code = '".$_POST['company_code']."'");

                $old_filing_result = $old_filing_result->result_array();

                $old_agm_result = $this->db->query("select agm from filing where id='".$_POST['filing_id']."' AND company_code = '".$_POST['company_code']."'");

                $old_agm_result = $old_agm_result->result_array();

                $this->db->where(array("company_code" => $_POST['company_code'], "id" => $_POST['filing_id']));
                $this->db->update("filing",$filing);

                $check_history_filing = $this->db->get_where("history_filing", array("company_code" => $_POST['company_code'], "id" => $_POST['filing_id']));

                if(!($old_filing_result == $check_year_end))
                {
                    if (!$check_history_filing->num_rows())
                    {
                        $w = $q->result();

                        foreach($w as $r) {
                            $this->db->insert("history_filing",$r);
                        }
                    } 
                    else 
                    {
                        $x = $q->result_array();

                        $data_history['year_end'] = $x[0]["year_end"];

                        $this->db->update("history_filing",$data_history,array("company_code" => $_POST['company_code'], "id" => $_POST['filing_id']));
                        
                    }

                    //$this->create_document("change_of_year_end", $_POST['company_code'], null, null, null, $_POST['filing_id']);
                }

                if(!($old_agm_result == $check_agm) && $_POST["agm"] != "" && $_POST['agm'] != "dispensed")
                {
                    //$this->create_document("agm_held", $_POST['company_code'], null, null, null, null);
                }

                if(!($old_agm_result == $check_agm) && $_POST["agm"] != "" && $_POST['agm'] == "dispensed")
                {
                    //$this->create_document("dispense_agm", $_POST['company_code'], null, null, null, null);
                }

                $next_filing_id = (int)$_POST['filing_id'] + 1;

                $check_due_date_175_not_empty = $this->db->get_where("filing", array("company_code" => $_POST['company_code'], "id" => $next_filing_id));

                if ($check_due_date_175_not_empty->num_rows())
                {               
                    if($check_due_date_175_not_empty->result()[0]->due_date_175 == "")
                    {
                        $latest_two_digit_year_previous_agm = date('y', strtotime($_POST['agm']));
                        $latest_two_digit_year_latest_agm = date('y', strtotime('+15 month', strtotime($_POST['agm'])));

                        // if(((int)$latest_two_digit_year_latest_agm - (int)$latest_two_digit_year_previous_agm) > 1)
                        // {
                        //  $new_due_date_175['due_date_175'] = date('d F Y', strtotime('31 December '.((int)$latest_two_digit_year_previous_agm + 1)));
                        // }
                        // else
                        // {
                            $new_due_date_175['due_date_175'] = date('d F Y', strtotime('+15 month', strtotime($_POST['agm'])));
                        //}
                        $latest_agm = date('Y-m-d', strtotime($_POST['agm']));

                        $agm_date = new DateTime($latest_agm);
                        // We extract the day of the month as $start_day
                        $agm_date = $this->MonthShifter($agm_date,15)->format(('Y-m-d'));
                        $new_due_date_175['due_date_175'] = date('d F Y', strtotime($agm_date));


                        $this->db->where(array("company_code" => $_POST['company_code'], "id" => $next_filing_id));
                        $this->db->update("filing",$new_due_date_175);
                    }
                } 
            }

            $latest_id = $this->db->query("select * from filing where company_code='".$_POST['company_code']."' ORDER BY id DESC LIMIT 1");

            //echo json_encode($latest_id->result()[0]->id);

            
                if($_POST['agm'] != null && $_POST['agm'] != "dispensed")
                {
                    $new_filing['company_code'] = $_POST['company_code'];
                    //$futureDate=date('d-m-Y', strtotime('+1 year', strtotime($_POST['year_end'])) );
                    $new_filing['year_end'] = date('d F Y', strtotime('+1 year', strtotime($_POST['year_end'])));
                    $new_filing['ar_filing_date'] = "";
                    $new_filing['financial_year_period_id'] = 1;
                    $new_filing['175_extended_to'] = 0;
                    $new_filing["201_extended_to"] = 0;
                    $new_filing["197_extended_to"] = 0;

                    $latest_year_end = date('Y-m-d', strtotime($new_filing['year_end']));

                    $year_end_date = new DateTime($latest_year_end);

                    //$latest_due_date_197 = date('Y-m-d', strtotime($new_filing['year_end']));

                    //$date2 = new DateTime($latest_due_date_197);
                    // We extract the day of the month as $start_day
                    //$date2 = $this->MonthShifter($date2,7)->format(('Y-m-d'));
                    if(date('Y-m-d', strtotime("8/31/2018")) > date('Y-m-d', strtotime('+1 year', strtotime($_POST['year_end'])))) 
                    {
                        $two_digit_year_previous_agm = date('y', strtotime($_POST['agm']));
                        $two_digit_year_latest_agm = date('y', strtotime('+15 month', strtotime($_POST['agm'])));
                        $new_filing['agm'] = "";

                        // if(((int)$two_digit_year_latest_agm - (int)$two_digit_year_previous_agm) > 1)
                        // {
                        //  /*echo json_encode($two_digit_year_latest_agm);*/
                        //  $new_filing['due_date_175'] = date('d F Y', strtotime('31 December '.((int)$two_digit_year_previous_agm + 1)));

                        //  $new_format_due_date_175 = new DateTime($new_filing['due_date_175']);
                        // }
                        // else
                        // {
                            $latest_agm = date('Y-m-d', strtotime($_POST['agm']));

                            $agm_date = new DateTime($latest_agm);
                            // We extract the day of the month as $start_day
                            $agm_date = $this->MonthShifter($agm_date,15)->format(('Y-m-d'));
                            $new_filing['due_date_175'] = date('d F Y', strtotime($agm_date));

                            $new_format_due_date_175 = new DateTime($new_filing['due_date_175']);
                        //}
                        //$filing['extended_to_175'] = $_POST['extended_to_175'];
                        /*$latest_due_date_201 = date('t F Y', strtotime('+6 months', strtotime($new_filing['year_end'])));
                        $new_filing['due_date_201'] = date("t F Y", strtotime($latest_due_date_201));*/

                        
                        // We extract the day of the month as $start_day
                        $date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

                        $new_filing['due_date_201'] =  date("t F Y", strtotime($date_201));

                        $new_format_due_date_201 = new DateTime($new_filing['due_date_201']);

                        // $date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

                        // $new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));

                        if($new_format_due_date_175 >= $new_format_due_date_201)
                        {
                            $date_197 = $this->MonthShifter($new_format_due_date_201,1)->format(('Y-m-d'));

                            $new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
                        }
                        else if($new_format_due_date_201 > $new_format_due_date_175)
                        {
                            $date_197 = $this->MonthShifter($new_format_due_date_175,1)->format(('Y-m-d'));

                            $new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
                        }
                        
                        //$new_filing['due_date_197'] = date('d F Y', strtotime('+30 days', strtotime($new_filing['due_date_175'])));
                    }
                    else
                    {
                        $new_filing['agm'] = "";

                        $date_175 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

                        //$new_filing['due_date_175'] = date('d F Y', strtotime($date_175));

                        $date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

                        $new_filing['due_date_201'] = date('t F Y', strtotime($date_201));

                        $date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

                        $new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
                    }
                    
                    //echo json_encode($latest_id->result()[0]);
                    //$latest_id->result()[0]->agm == null
                    if($latest_id->result()[0]->id != $_POST['filing_id'] && $_POST['filing_id'] != "")
                    {
                        $this->db->where(array("id" => $latest_id->result()[0]->id));
                        $this->db->update("filing",$new_filing);
                    }
                    else
                    {
                        $this->db->insert("filing",$new_filing);
                    }
                }
                elseif($_POST['agm'] != null && $_POST['agm'] == "dispensed")
                {
                    $new_filing['company_code'] = $_POST['company_code'];
                    //$futureDate=date('d-m-Y', strtotime('+1 year', strtotime($_POST['year_end'])) );
                    $new_filing['year_end'] = date('d F Y', strtotime('+1 year', strtotime($_POST['year_end'])));
                    $new_filing['ar_filing_date'] = "";
                    $new_filing['financial_year_period_id'] = 1;
                    $new_filing['175_extended_to'] = 0;
                    $new_filing["201_extended_to"] = 0;
                    $new_filing["197_extended_to"] = 0;
                    $new_filing['due_date_175'] = "Not Applicable";

                    $latest_year_end = date('Y-m-d', strtotime($new_filing['year_end']));

                    $year_end_date = new DateTime($latest_year_end);
                    /*$two_digit_year_previous_agm = date('y', strtotime($_POST['agm']));
                    $two_digit_year_latest_agm = date('y', strtotime('+15 month', strtotime($_POST['agm'])));

                    if(((int)$two_digit_year_latest_agm - (int)$two_digit_year_previous_agm) > 1)
                    {
                        
                        $new_filing['due_date_175'] = date('d F Y', strtotime('31 December '.((int)$two_digit_year_previous_agm + 1)));
                    }
                    else
                    {
                        $latest_agm = date('Y-m-d', strtotime($_POST['agm']));

                        $agm_date = new DateTime($latest_agm);
                        // We extract the day of the month as $start_day
                        $agm_date = $this->MonthShifter($agm_date,15)->format(('Y-m-d'));
                        $new_filing['due_date_175'] = date('d F Y', strtotime($agm_date));
                    }*/
                    //$filing['extended_to_175'] = $_POST['extended_to_175'];
                    /*$latest_due_date_201 = date('t F Y', strtotime('+6 months', strtotime($new_filing['year_end'])));
                    $new_filing['due_date_201'] = date("t F Y", strtotime($latest_due_date_201));*/

                    

                    //$latest_due_date_197 = date('Y-m-d', strtotime($new_filing['year_end']));

                    //$date2 = new DateTime($latest_due_date_197);
                    // We extract the day of the month as $start_day
                    //$date2 = $this->MonthShifter($date2,7)->format(('Y-m-d'));

                    if(date('Y-m-d', strtotime("8/31/2018")) > date('Y-m-d', strtotime('+1 year', strtotime($_POST['year_end'])))) 
                    {
                        $new_filing['agm'] = "";

                        $latest_due_date_201 = date('Y-m-d', strtotime($new_filing['year_end']));

                        $date1 = new DateTime($latest_due_date_201);
                        // We extract the day of the month as $start_day
                        $date1 = $this->MonthShifter($date1,6)->format(('Y-m-d'));

                        $new_filing['due_date_201'] =  date("t F Y", strtotime($date1));

                        if($new_filing['due_date_175'] == "Not Applicable")
                        {
                            $new_filing['due_date_197'] = "Not Applicable";
                        }
                        //$new_filing['due_date_197'] = date('d F Y', strtotime('+30 days', strtotime($new_filing['year_end'])));
                    }
                    else
                    {
                        $new_filing['agm'] = "";

                        $date_175 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

                        //$new_filing['due_date_175'] = date('d F Y', strtotime($date_175));
                        $new_filing['due_date_175'] = "";

                        $date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

                        $new_filing['due_date_201'] = date('t F Y', strtotime($date_201));

                        $date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

                        $new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
                    }

                    

                    // echo json_encode($latest_id->result()[0]->id);
                    // echo json_encode($_POST['filing_id']);
                    //$latest_id->result()[0]->agm == null
                    if($latest_id->result()[0]->id != $_POST['filing_id'] && $_POST['filing_id'] != "")
                    {
                        $this->db->where(array("id" => $latest_id->result()[0]->id));
                        $this->db->update("filing",$new_filing);
                    }
                    else
                    {
                        $this->db->insert("filing",$new_filing);
                    }
                }
            

            $get_all_filing_data = $this->db->query("select filing.*, financial_year_period.period from filing left join financial_year_period on financial_year_period.id = filing.financial_year_period_id where company_code='".$_POST['company_code']."' order by id");

            //echo json_encode($q->result());

            if ($get_all_filing_data->num_rows() > 0) {
                foreach (($get_all_filing_data->result()) as $row) {
                    $data[] = $row;
                }
            }

            // $get_all_eci_filing_data = $this->db->query("select eci_filing.* from eci_filing where company_code='".$_POST['company_code']."' order by id");

            // $get_all_eci_filing_data = $get_all_eci_filing_data->result_array();

            // if(count($get_all_eci_filing_data) > 0)
            // {
            //     $have_eci = false;
            // }
            // else
            // {
            //     $have_eci = true;
            // }

            // $get_all_tax_filing_data = $this->db->query("select tax_filing.* from tax_filing where company_code='".$_POST['company_code']."' order by id");

            // $get_all_tax_filing_data = $get_all_tax_filing_data->result_array();

            // if(count($get_all_tax_filing_data) > 0)
            // {
            //     $have_tax = false;
            // }
            // else
            // {
            //     $have_tax = true;
            // }

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "filing_data" => $data));
        }
    }

    public function delete_client_billing_info()
    {
        //$this->session->unset_userdata('allotment_id');
        $client_billing_info_id = "";
        $company_code = "";

        if (isset($_POST["client_billing_info_id"]))
        {
            $client_billing_info_id = $_POST["client_billing_info_id"];
        }
        
        if(isset($_POST["company_code"]))
        {
            $company_code = $_POST["company_code"];
        }
        
        // $this->db->delete('client_billing_info',array('id'=>$client_billing_info_id));
        // echo "success";

        $check_billing_service_id = $this->db->get_where("client_billing_info", array("client_billing_info_id" => $client_billing_info_id, "company_code" => $company_code, "deleted"=> 0));
        //echo json_encode(!$check_billing_service_id->num_rows());
        if ($check_billing_service_id->num_rows())
        {   
            $check_billing_service_id = $check_billing_service_id->result_array();
            //echo json_encode($check_billing_service_id[0]["id"]);
            $check_billing_service_info = $this->db->get_where("billing_service", array("service" => $check_billing_service_id[0]["id"]));

            if (!$check_billing_service_info->num_rows())
            {
                $check_recur_billing_service_info = $this->db->get_where("recurring_billing_service", array("service" => $check_billing_service_id[0]["id"]));

                if (!$check_recur_billing_service_info->num_rows())
                {
                    echo json_encode(array("Status" => 1));
                }
                else
                {
                    echo json_encode(array("Status" => 2));
                }
            }
            else
            {
                $check_billing_service_info = $check_billing_service_info->result_array();

                $check_billing_info = $this->db->get_where("billing", array("id" => $check_billing_service_info[0]["billing_id"], "status" => 0));

                if(!$check_billing_info->num_rows())
                {
                    echo json_encode(array("Status" => 1));
                }
                else
                {
                    echo json_encode(array("Status" => 2));
                }
                
                //echo json_encode(array("Status" => 2));
            }
        }
        else
        {
            echo json_encode(array("Status" => 1));
        }
    }


    public function MonthShifter (DateTime $aDate,$months)
    {
        $dateA = clone($aDate);
        $dateB = clone($aDate);
        $plusMonths = clone($dateA->modify($months . ' Month'));
        //check whether reversing the month addition gives us the original day back
        if($dateB != $dateA->modify($months*-1 . ' Month')){ 
            $result = $plusMonths->modify('last day of last month');
        } elseif($aDate == $dateB->modify('last day of this month')){
            $result =  $plusMonths->modify('last day of this month');
        } else {
            $result = $plusMonths;
        }
        return $result;
    }

    public function search_client_billing()
    {
        $company_code = $_POST["company_code"];

        $service_category = $this->db->query("select * from billing_info_service_category where category_code='C001' or category_code='C010'");
        $service_category = $service_category->result_array();
        for($j = 0; $j < count($service_category); $j++)
        {
            $info[$service_category[$j]['id']] = $service_category[$j]['category_description'];
        }
        $this->data["service_category"] = $info;

        $this->data['client_billing_info'] = $this->client_model->get_all_client_billing_info($company_code);
        // echo "hi";
        if($this->data['client_billing_info'] == false)
        {
            $this->data['client_billing_info'] = $this->client_model->get_all_default_client_service();
        }
        // print_r($this->data['client_billing_info']);
        // print_r($service_category);

        echo json_encode($this->data);
    }

    public function add_client_billing_info()
    {
        // print_r($_POST);
        if (isset($_POST['client_billing_info_id']))
        {
            $_POST['client_billing_info_id'] = array_values($_POST['client_billing_info_id']);

        }

        if (isset($_POST['service']) )
        {
            $_POST['service'] = array_values($_POST['service']);

        }
         
        if (isset($_POST['invoice_description']) )
        {
            $_POST['invoice_description'] = array_values($_POST['invoice_description']);

        }

        if (isset($_POST['amount']) )
        {
            $_POST['amount'] = array_values($_POST['amount']);

        }

        if (isset($_POST['currency']) )
        {
            $_POST['currency'] = array_values($_POST['currency']);

        }

        if (isset($_POST['unit_pricing']) )
        {
            $_POST['unit_pricing'] = array_values($_POST['unit_pricing']);

        }  

        if (isset($_POST['servicing_firm']) )
        {
            $_POST['servicing_firm'] = array_values($_POST['servicing_firm']);

        }   

        if (isset($_POST['hidden_deactive_switch']) )
        {
            $_POST['deactive'] = array_values($_POST['hidden_deactive_switch']);

        }   

        // $_POST['deactive'] = array_values($_POST['hidden_deactive_switch']);    
        
        if (isset($_POST['array_client_billing_info_id'])) {
           if(count(json_decode($_POST['array_client_billing_info_id'])) != 0)
            {
                // print_r(count(json_decode($_POST['array_client_billing_info_id'])));
                for($g = 0; $g < count(json_decode($_POST['array_client_billing_info_id'])); $g++ )
                {

                    $deleted_client_billing_info['deleted'] = 1;

                    $this->db->where(array("company_code" => $_POST['company_code'], "client_billing_info_id" => json_decode($_POST['array_client_billing_info_id'])[$g]));
                    $this->db->update("client_billing_info",$deleted_client_billing_info);
                }
            }
        }
                
        // if(count(json_decode($_POST['array_client_billing_info_id'])) != 0)
        // {
        //     // print_r(count(json_decode($_POST['array_client_billing_info_id'])));
        //     for($g = 0; $g < count(json_decode($_POST['array_client_billing_info_id'])); $g++ )
        //     {

        //         $deleted_client_billing_info['deleted'] = 1;

        //         $this->db->where(array("company_code" => $_POST['company_code'], "client_billing_info_id" => json_decode($_POST['array_client_billing_info_id'])[$g]));
        //         $this->db->update("client_billing_info",$deleted_client_billing_info);
        //     }
        // }
        // print_r($_POST['client_billing_info_id']);
        if (isset($_POST['client_billing_info_id']))
        {
            for($i = 0; $i < count($_POST['client_billing_info_id']); $i++ )
            {

                $client_billing_info['company_code'] = $_POST['company_code'];
                $client_billing_info['client_billing_info_id'] = $_POST['client_billing_info_id'][$i];
                $client_billing_info['service'] = $_POST['service'][$i];
                $client_billing_info['invoice_description'] = $_POST['invoice_description'][$i];
                $client_billing_info['amount'] = (float)str_replace(',', '', $_POST['amount'][$i]);
                $client_billing_info['currency'] = $_POST['currency'][$i];
                $client_billing_info['unit_pricing'] = $_POST['unit_pricing'][$i];
                $client_billing_info['servicing_firm'] = $_POST['servicing_firm'][$i];
                $client_billing_info['deactive'] = $_POST['deactive'][$i];
            
                $q = $this->db->get_where("client_billing_info", array("company_code" => $_POST['company_code'], "client_billing_info_id" => $_POST['client_billing_info_id'][$i], "deleted =" => 0));

                if (!$q->num_rows())
                {               
                    $this->db->insert("client_billing_info",$client_billing_info);
                
                } 
                else 
                {   
                    
                    $this->db->where(array("company_code" => $_POST['company_code'], "client_billing_info_id" => $_POST['client_billing_info_id'][$i], "deleted =" => 0));
                    $this->db->update("client_billing_info",$client_billing_info);
                }
                


            }
        }
        
        $this->add_paf();
        
        $client_info = $this->db->query("select * from client where company_code = '".$_POST['company_code']."'");

        $client_info = $client_info->result_array();

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', 'client_id' => $client_info[0]['id']));

    }

    public function get_billing_info_service()
    {
        if(isset($_POST['service']))
        {
            $service = $_POST['service'];
        }
        else
        {
            $service = "";
        }

        
        $company_code = $_POST['company_code'];
        //$is_template = $_POST['is_template'];

        $ci =& get_instance();

        $query = "select our_service_info.*, billing_info_service_category.category_description from our_service_info left join billing_info_service_category on billing_info_service_category.id = our_service_info.service_type where our_service_info.user_admin_code_id = '".$this->session->userdata("user_admin_code_id")."' order by our_service_info.id";

       
        $selected_query = "select A.id from our_service_info AS A WHERE EXISTS (SELECT service from client_billing_info AS B WHERE company_code = '".$company_code."' AND A.id = B.service)";
        

        $selected_billing_info_service_category = "select billing_info_service_category.* from billing_info_service_category";

        $result = $ci->db->query($query);
        $selected_result = $ci->db->query($selected_query);
        $selected_billing_info_service_category = $ci->db->query($selected_billing_info_service_category);
        
       
        $result = $result->result_array();
        $selected_result = $selected_result->result_array();
        $selected_billing_info_service_category = $selected_billing_info_service_category->result_array();

        if (count($selected_result) == 0) {
            $selected_querys = "select A.id from our_service_info AS A WHERE EXISTS (SELECT service from billing_template AS B WHERE A.id = B.service)";

            $selected_result = $ci->db->query($selected_querys);

            $selected_result = $selected_result->result_array();
        }



        $selected_res = array();
        foreach($selected_result as $key => $row) {
            $selected_res[$key] = $row['id'];
        }

        //$ci =& get_instance();
        if($service != "")
        {
            $select_service = $service;
        }
        else
        {
            $select_service = null;
        }
        

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Service fetched successfully.", 'result'=>$result, 'selected_service'=>$select_service, 'selected_query'=> $selected_res, 'selected_billing_info_service_category' => $selected_billing_info_service_category, 'firm_id'=>$this->session->userdata("firm_id"));

        echo json_encode($data);
    }

    public function get_servicing_firm()
    {
        $get_all_firm_info = $this->client_model->getAllFirmInfo();
        for($j = 0; $j < count($get_all_firm_info); $j++)
        {
            $res_firm[$get_all_firm_info[$j]->id] = $get_all_firm_info[$j]->name;
        }

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Frequency fetched successfully.", 'result'=>$res_firm);

        echo json_encode($data);
    }

    public function get_currency()
    {
        if(isset($_POST['currency']))
        {
            $currency = $_POST['currency'];
        }
        else
        {
            $currency = "";
        }

        $result_currency = $this->db->query("select * from currency order by currency");

        $result = $result_currency->result_array();
        //echo json_encode($result);
        if(!$result_currency) {
            throw new exception("Currency not found.");
        }
        $res = array();

        for($j = 0; $j < count($result); $j++)
        {
            $res[$result[$j]['id']] = $result[$j]['currency'];
        }
        
        /*$ci =& get_instance();*/
        if ($currency != "")
        {
            $selected_currency = $currency;
        }
        else
        {
            $selected_currency = null;
        }

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Currency fetched successfully.", 'result'=>$res, 'selected_currency'=>$selected_currency);

        echo json_encode($data);

    }

    public function get_unit_pricing()
    {
        $ci =& get_instance();
        $query = "select * from unit_pricing";

        $result = $ci->db->query($query);
        $result = $result->result_array();

        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['unit_pricing_name'];
        }

        $data = array('status'=>'success', 'tp'=>1,'result'=>$res);

        echo json_encode($data);
    }

    public function get_billing_info_frequency()
    {
        //$frequency = $_POST['frequency'];

        $ci =& get_instance();

        $query = "select * from billing_info_frequency";

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        $result = $result->result_array();

        if(!$result) {
          throw new exception("Frequency not found.");
        }

        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['frequency'];
        }

        //$ci =& get_instance();
        // if($frequency != "")
        // {
        //  $selected_frequency = $frequency;
        // }
        // else
        // {
        //  $selected_frequency = null;
        // }

        $ci =& get_instance();
        $selected_frequency = $ci->session->userdata('billing_period');
        $ci->session->unset_userdata('billing_period');
        

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Frequency fetched successfully.", 'result'=>$res, 'selected_frequency'=>$selected_frequency);

        echo json_encode($data);
    }

    public function submit_contact_information()
    {
        // $this->form_validation->set_rules('contact_email[]', 'Email', 'valid_email');
        $this->form_validation->set_rules('contact_phone[]', 'Phone Number', 'numeric');

        if(count($_POST['hidden_contact_phone']) > 1 && $_POST['contact_phone_primary'] == null)
        {
            $validate_contact_phone_primary = FALSE;
        }
        else
        {
            $validate_contact_phone_primary = TRUE;
        }

        if(count($_POST['contact_email']) > 1 && $_POST['contact_email_primary'] == null)
        {
            $validate_contact_email_primary = FALSE;
        }
        else
        {
            $validate_contact_email_primary = TRUE;
        }

        if ($this->form_validation->run() == FALSE || $validate_contact_phone_primary == FALSE || $validate_contact_email_primary == FALSE)
        {
            if($validate_contact_phone_primary == FALSE)
            {
                $validate_contact_phone = "Please set the primary field.";
            }
            else
            {
                $validate_contact_phone = strip_tags(form_error('contact_phone[]'));
            }

            if($validate_contact_email_primary == FALSE)
            {
                $validate_contact_email = "Please set the primary field.";
            }
            else
            {
                $validate_contact_email = strip_tags(form_error('contact_email[]'));
            }

            $arr = array(

                'contact_phone' => $validate_contact_phone,
                'contact_email' => $validate_contact_email,
            );

            echo json_encode(array("Status" => 0, "error" => $arr, 'message' => 'Please complete all required field', 'title' => 'Error'));
        }
        else
        {
            $client_contact_info['company_code'] = $_POST['company_code'];
            $client_contact_info['name'] = strtoupper($_POST['contact_name']);
            $query = $this->db->get_where("client_contact_info", array("company_code" => $_POST['company_code']));

            if (!$query->num_rows())
            {               
                $this->db->insert("client_contact_info",$client_contact_info);
                $client_contact_info_id = $this->db->insert_id();
                for($g = 0; $g < count($_POST['hidden_contact_phone']); $g++)
                {
                    if($_POST['hidden_contact_phone'][$g] != "")
                    {
                        $contactPhone['client_contact_info_id'] = $client_contact_info_id;
                        $contactPhone['phone'] = strtoupper($_POST['hidden_contact_phone'][$g]);
                        if($_POST['contact_phone_primary'] == $_POST['hidden_contact_phone'][$g])
                        {
                            $contactPhone['primary_phone'] = 1;
                        }
                        else
                        {
                            $contactPhone['primary_phone'] = 0;
                        }
                        $this->db->insert('client_contact_info_phone', $contactPhone);
                    }
                }

                for($g = 0; $g < count($_POST['contact_email']); $g++)
                {
                    if($_POST['contact_email'][$g] != "")
                    {
                        $contactEmail['client_contact_info_id'] = $client_contact_info_id;
                        $contactEmail['email'] = strtoupper($_POST['contact_email'][$g]);
                        if($_POST['contact_email_primary'] == $_POST['contact_email'][$g])
                        {
                            $contactEmail['primary_email'] = 1;
                        }
                        else
                        {
                            $contactEmail['primary_email'] = 0;
                        }
                        $this->db->insert('client_contact_info_email', $contactEmail);
                    }
                }
            } 
            else 
            {   
                $this->db->where(array("company_code" => $_POST['company_code']));
                $this->db->update("client_contact_info",$client_contact_info);
                $client_contact_information = $query->result_array(); 
                $client_contact_info_id = $client_contact_information[0]["id"];

                $this->db->delete("client_contact_info_phone",array('client_contact_info_id'=>$client_contact_info_id));

                for($g = 0; $g < count($_POST['hidden_contact_phone']); $g++)
                {
                    if($_POST['hidden_contact_phone'][$g] != "")
                    {
                        $contactPhone['client_contact_info_id'] = $client_contact_info_id;
                        $contactPhone['phone'] = strtoupper($_POST['hidden_contact_phone'][$g]);
                        if($_POST['contact_phone_primary'] == $_POST['hidden_contact_phone'][$g])
                        {
                            $contactPhone['primary_phone'] = 1;
                        }
                        else
                        {
                            $contactPhone['primary_phone'] = 0;
                        }
                        $this->db->insert('client_contact_info_phone', $contactPhone);
                    }
                }

                $this->db->delete("client_contact_info_email",array('client_contact_info_id'=>$client_contact_info_id));

                for($g = 0; $g < count($_POST['contact_email']); $g++)
                {
                    if($_POST['contact_email'][$g] != "")
                    {
                        $contactEmail['client_contact_info_id'] = $client_contact_info_id;
                        $contactEmail['email'] = strtoupper($_POST['contact_email'][$g]);
                        if($_POST['contact_email_primary'] == $_POST['contact_email'][$g])
                        {
                            $contactEmail['primary_email'] = 1;
                        }
                        else
                        {
                            $contactEmail['primary_email'] = 0;
                        }
                        $this->db->insert('client_contact_info_email', $contactEmail);
                    }
                }
            }

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
    }

    public function submit_reminder()
    {
        // $this->db->delete("client_setup_reminder",array('company_code'=>$_POST['company_code']));

        if($_POST['company_code'] != null)
        {
        //     for($g = 0; $g < count($_POST['select_reminder']); $g++)
        //     {
            $reminder['company_code'] = $_POST['company_code'];
            $reminder['reminder_flag'] = $_POST['hidden_stocktake_checkbox'];
            $reminder['email'] = $_POST['stocktake_email'];

        //         $this->db->insert('client_setup_reminder', $reminder);
        //     }
        }


        $query_check = $this->db->query('SELECT * FROM audit_stocktake_reminder_setting where company_code = "'.$_POST['company_code'].'"');

        if($query_check->num_rows() > 0)
        {
            $query_c = $query_check->result_array();

            $this->db->where('id', $query_c[0]['id']);
            $this->db->update('audit_stocktake_reminder_setting', $reminder);

            // $result = $query_c[0]['id'];
        }
        else
        {
            $this->db->insert('audit_stocktake_reminder_setting', $reminder); 
            // $result = $this->db->insert_id();
        }
        // return $result;

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
    }


    public function get_type_of_day()
    {
        if(isset($_POST['type_of_day']))
        {
            $type_of_day = $_POST['type_of_day'];
        }
        else
        {
            $type_of_day = "";
        }
        

        $ci =& get_instance();

        $query = "select * from type_of_day";

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        $result = $result->result_array();

        if(!$result) {
          throw new exception("Type of Day not found.");
        }

        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['type_of_day'];
        }

        //$ci =& get_instance();
        if($type_of_day != "")
        {
            $selected_type_of_day = $type_of_day;
        }
        else
        {
            $selected_type_of_day = null;
        }
        

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Type of Day fetched successfully.", 'result'=>$res, 'selected_type_of_day'=>$selected_type_of_day);

        echo json_encode($data);
    }

    public function check_next_recurring_date()
    {
        // $type_of_day = $_POST["type_of_day"];
        // $days = $_POST["days"];
        if(isset($_POST["frequency"]) && isset($_POST["period_end_date"]) && isset($_POST["recurring_issue_date"]))
        {
            $frequency = $_POST["frequency"];
            $to_billing_cycle_date = $_POST["period_end_date"];
            $recurring_issue_date = $_POST["recurring_issue_date"];
        }
        else
        {
            $frequency = "";
            $to_billing_cycle_date = "";
            $recurring_issue_date = "";
        }
        
        // $from_date = $_POST["from"];
        // $to_date = $_POST["to"];

        $current_date = DATE("Y-m-d",now());

        // if($from_date != null)
        // {
        //  $date_from = str_replace('/', '-', $from_date);
        //  $from = strtotime($date_from);
        //  $new_from = date('Y-m-d',$from);
        //  //echo ($new_to);
        // }
        // else
        // {
        //  $new_from = null;
        // }

        // if($to_date != null)
        // {
        //  $date_to = str_replace('/', '-', $to_date);
        //  $to = strtotime($date_to);
        //  $new_to = date('Y-m-d',$to);
        //  //echo ($new_to);
        // }
        // else
        // {
        //  $new_to = null;
        // }

        $date_to_billing_cycle = str_replace('/', '-', $to_billing_cycle_date);
        $date_for_issue = str_replace('/', '-', $recurring_issue_date);
        // $to_billing_cycle = strtotime($date_for_issue);
        // //echo ($new_to);
        // if($type_of_day == 1)
        // {
        //  $new_to_billing_cycle = date('Y-m-d', strtotime('-'.$days.' days', $to_billing_cycle));
        // }
        // elseif($type_of_day == 2)
        // {
        //  $new_to_billing_cycle = date('Y-m-d', strtotime('+'.$days.' days', $to_billing_cycle));
        // }
        // else
        // {
        //  $new_to_billing_cycle = date('Y-m-d',$to_billing_cycle);
        // }
        
        $next_billing_cycle = new DateTime(date('Y-m-d', strtotime($date_to_billing_cycle)));
        $latest_date_for_issue = new DateTime(date('Y-m-d', strtotime($date_for_issue)));
        //echo json_encode($next_billing_cycle);
        // We extract the day of the month as $start_day

        if($frequency == 2)
        {
            //$last_recurring_date = date("Y-m-d", strtotime("+1 month", $new_from));
            $next_from_billing_cycle = date('Y-m-d', strtotime('+ 1 days', strtotime($date_to_billing_cycle)));
            $next_to_billing_cycle = $this->MonthShifter($next_billing_cycle,1)->format(('Y-m-d'));
            $latest_date_for_issue = $this->MonthShifter($latest_date_for_issue,1)->format(('Y-m-d'));
        }
        elseif($frequency == 3)
        {
            //$last_recurring_date = date("Y-m-d", strtotime("+3 months", $new_from));
            // $next_from_billing_cycle = $this->MonthShifter($next_from_billing_cycle,3)->format(('Y-m-d'));
            // $next_billing_cycle = new DateTime($next_from_billing_cycle);
            $next_from_billing_cycle = date('Y-m-d', strtotime('+ 1 days', strtotime($date_to_billing_cycle)));
            $next_to_billing_cycle = $this->MonthShifter($next_billing_cycle,3)->format(('Y-m-d'));
            $latest_date_for_issue = $this->MonthShifter($latest_date_for_issue,3)->format(('Y-m-d'));
        }
        elseif($frequency == 4)
        {
            //$last_recurring_date = date("Y-m-d", strtotime("+6 months", $new_from));
            // $next_from_billing_cycle = $this->MonthShifter($next_from_billing_cycle,6)->format(('Y-m-d'));
            // $next_billing_cycle = new DateTime($next_from_billing_cycle);
            $next_from_billing_cycle = date('Y-m-d', strtotime('+ 1 days', strtotime($date_to_billing_cycle)));
            $next_to_billing_cycle = $this->MonthShifter($next_billing_cycle,6)->format(('Y-m-d'));
            $latest_date_for_issue = $this->MonthShifter($latest_date_for_issue,6)->format(('Y-m-d'));
        }
        elseif($frequency == 5)
        {
            //$last_recurring_date = date("Y-m-d", strtotime("+1 year", $new_from));
            // $next_from_billing_cycle = $this->MonthShifter($next_from_billing_cycle,12)->format(('Y-m-d'));
            // $next_billing_cycle = new DateTime($next_from_billing_cycle);
            $next_from_billing_cycle = date('Y-m-d', strtotime('+ 1 days', strtotime($date_to_billing_cycle)));
            $next_to_billing_cycle = $this->MonthShifter($next_billing_cycle,12)->format(('Y-m-d'));
            $latest_date_for_issue = $this->MonthShifter($latest_date_for_issue,12)->format(('Y-m-d'));
        }

        // if(($new_from == null || ($next_from_billing_cycle != null && $next_from_billing_cycle >= $new_from)) && ($new_to == null || ($next_to_billing_cycle != null && $next_to_billing_cycle <= $new_to)))
        // {
            echo json_encode(array("status" => 1, "issue_date" => date('d/m/Y', strtotime($latest_date_for_issue)), "next_from_billing_cycle" => date('d/m/Y', strtotime($next_from_billing_cycle)), 'next_to_billing_cycle' => date('d/m/Y', strtotime($next_to_billing_cycle))));
        // }
        // else
        // {
        //  echo json_encode(array("status" => 2));
        // }
    }

    public function add_paf()
    {

        // $this->load->library('mybreadcrumb');
        // $this->mybreadcrumb->add('PAF Upload', base_url('paf_upload'));
        // $this->mybreadcrumb->add('Add PAF', base_url());

        // $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        // $bc = array(array('link' => '#', 'page' => 'Add PAF'));
        // $meta = array('page_title' => 'Add PAF', 'bc' => $bc, 'page_name' => 'Add PAF');

        // $this->page_construct('add_paf.php', $meta, $this->data);

         // $company_code = $form_data['client_name'];
        // $fye_date     = $form_data['fye_date'];
        // $data         = $form_data['paf_tree'];

        // $arrange_flag = 1;
        $stat_client_list = $this->paf_upload_model->get_client_stat_audit();
        // print_r($stat_client_list);
        foreach ($stat_client_list as $key => $value) {
            $db_data = array(
                'company_code'     => $value->company_code,
             // 'fye_date'         => $fye_date
            );

            $paf_id = $this->paf_upload_model->submit_paf_record($db_data);
        }

        
    }

    public function get_paf_index()
    {
        $form_data = $this->input->post();

        $company_code  = isset($form_data['company_code'])?$form_data['company_code']:"";
        $data = $this->client_model->get_paf_index($company_code);

        echo json_encode($data);
    }

    public function filter_review_points()
    {
        $form_data = $this->input->post();

        $paf_child_id    = isset($form_data['paf_child_id'])?$form_data['paf_child_id']:"";
        $cleared   = isset($form_data['cleared'])?$form_data['cleared']:"";
        $company_code   = isset($form_data['company_code'])?$form_data['company_code']:"";
       

        if($paf_child_id == 0){
            $paf_child_id = '%%';
        }
    
        // echo $auditor;
        if($cleared == ""){
            $cleared = '%%' ;

        }

        // echo $company_code;
        $result = $this->client_model->filter_review_points($paf_child_id, $cleared, $company_code);
        
        echo json_encode($result);
    }


    public function write_address($street_name, $unit_no1, $unit_no2, $building_name, $postal_code, $type)
    {
        $unit = '';
        $unit_building_name = '';

        $comma = '';

        if($type == "normal")
        {
            $br = '';
        }
        elseif($type == "letter")
        {
            $br = ' <br/>';
        }
        elseif($type == "letter with comma")
        {
            $br = ' <br/>';
            $comma = ',';
        }
        elseif($type == "comma")
        {
            $br = '';
        }

        // Add unit
        if(!empty($unit_no1) && !empty($unit_no2))
        {
            $unit = '#' . $unit_no1 . '-' . $unit_no2 . $comma;
        }

        // Add building
        if(!empty($building_name) && !empty($unit))
        {
            $unit_building_name = $unit . ' ' . $building_name . $comma;
        }
        elseif(!empty($unit))
        {
            $unit_building_name = $unit;
        }
        elseif(!empty($building_name))
        {
            $unit_building_name = $building_name . $comma;
        }
        //print_r($street_name . $br . $unit_building_name . $br . 'Singapore ' . $postal_code);
        if(!empty($unit))
        {
            $address = $street_name . $comma . $br . $unit_building_name . $br . 'SINGAPORE ' . $postal_code;
        }
        elseif(!empty($building_name))
        {
            $address = $street_name . $comma . $br . $building_name . $comma . $br . 'SINGAPORE ' . $postal_code;
        }
        else
        {
            $address = $street_name . $comma . $br . 'SINGAPORE ' . $postal_code;
        }
        return $address;
    }

}