<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once('assets/vendor/tcpdf/tcpdf.php');


class PAF_upload extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
        
        $this->load->library(array('session','parser','encryption'));
        $this->load->library(array('zip'));
        $this->load->model('bank/bank_model');
        $this->load->model('leave/leave_model');
        $this->load->model('paf_upload_model');
    }

    public function index()
    {   
        // $this->meta['page_name'] = 'List of Auditor';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        // $temp_auditor_list = $this->paf_upload_model->get_auditor_list();
        // if($temp_auditor_list)
        // {
        //     foreach ($temp_auditor_list as $key => $value) {
        //         $temp_auditor_list[$key]->full_address = $this->write_address($value->street_name, $value->unit_no1, $value->unit_no2, $value->building_name, $value->postal_code, 'letter');
        //     }
        // }
        // $this->data['auditor_list'] = $temp_auditor_list;
        // $this->data['first_letter'] = $this->paf_upload_model->get_first_letter();
        $this->data['paf_list'] = $this->paf_upload_model->get_paf_list();

        $bc = array(array('link' => '#', 'page' => 'PAF Upload'));
        $meta = array('page_title' => 'PAF Upload', 'bc' => $bc, 'page_name' => 'PAF Upload');

        $this->page_construct('index.php', $meta, $this->data);

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

    public function edit_paf($id)
    {
        // $this->meta['page_name'] = 'Stocktake Arrangement';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $this->data['edit_paf'] = $this->paf_upload_model->get_edit_paf($id);

        // $this->data['auditor_name_dropdown'] = $this->stocktake_model->get_auditor_dropdown_list();
        // $this->data['type_of_leave_list'] = $this->bank_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->bank_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->bank_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->bank_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->bank_model->get_choose_carry_forward_list();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('PAF Upload', base_url('paf_upload'));
        $this->mybreadcrumb->add('Edit PAF', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Edit PAF'));
        $meta = array('page_title' => 'Edit PAF', 'bc' => $bc, 'page_name' => 'Edit PAF');

        $this->page_construct('add_paf.php', $meta, $this->data);
    }

    public function edit_paf_client($id)
    {
        // $this->meta['page_name'] = 'Stocktake Arrangement';

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $this->data['edit_paf'] = $this->paf_upload_model->get_edit_paf($id);

        // $this->data['auditor_name_dropdown'] = $this->stocktake_model->get_auditor_dropdown_list();
        // $this->data['type_of_leave_list'] = $this->bank_model->get_type_of_leave_list();
        // $this->data['leave_cycle_list'] = $this->bank_model->get_leave_cycle_list();
        // $this->data['carry_forward_period_list'] = $this->bank_model->get_carry_forward_period_list();
        // $this->data['block_leave_list'] = $this->leave_model->get_block_leave_list();
        // $this->data['approval_cap_list'] = $this->bank_model->get_approval_cap_list();
        // $this->data['choose_carry_forward_list'] = $this->bank_model->get_choose_carry_forward_list();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Clients', base_url('client'));
        $this->mybreadcrumb->add('Edit Client - '.$this->data['edit_paf'][0]->company_name, base_url('client/edit/'.$this->data['edit_paf'][0]->c_id));
        $this->mybreadcrumb->add('Edit PAF', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $bc = array(array('link' => '#', 'page' => 'Edit PAF'));
        $meta = array('page_title' => 'Edit PAF', 'bc' => $bc, 'page_name' => 'Edit PAF');

        $this->page_construct('add_paf.php', $meta, $this->data);
    }

    public function pafAllData($paf_id=null)
    {
        $all_list = $this->paf_upload_model->get_all_paftree($paf_id);

        echo json_encode($all_list);
        // print_r($categorized_acc_list);
    }

    public function save_paf()
    {
        // $this->session->set_userdata("tab_active", "st_arrangement");
        $form_data = $this->input->post();

        // $arrangement_id = $form_data['arrangement_id'];

        $company_code = $form_data['client_name'];
        // $fye_date     = $form_data['fye_date'];
        $data         = $form_data['paf_tree'];

        // $arrange_flag = 1;

        $db_data = array(
            'company_code'     => $company_code,
            // 'fye_date'         => $fye_date
        );

        $paf_id = $this->paf_upload_model->submit_paf_record($db_data);

        if($paf_id != "")
        {
            $paf_node = $this->db->query("SELECT * FROM audit_paf_node node WHERE node.paf_id = " . $paf_id);
            $ori_paf_node = $paf_node->result_array();

            $deleted_node_id = [];

            foreach ($ori_paf_node as $ori_pn_key => $ori_pn_value) 
            {
                $temp_pn_id = $ori_pn_value['id'];

                foreach ($data as $key => $value) 
                {
                    if($ori_pn_value['id'] == $value['data']['id'])
                    {
                        $temp_pn_id = "";
                    }
                }

                if(!empty($temp_pn_id))
                {
                    array_push($deleted_node_id, $temp_pn_id);
                }
            }

            if(count($deleted_node_id) > 0)
            {
                $this->db->where_in('id', $deleted_node_id);
                $result = $this->db->update('audit_paf_node', array('deleted' => 1));         
            }

            //** delete node **//

            // print_r(json_decode($form_data['paf_tree']));
            // print_r($form_data['paf_tree']);
            
            $temp_data = $data;

            foreach ($data as $key => $value) 
            {

                $each_node = array(
                        'paf_id'             => $paf_id,
                        'text'        => $value['text'],
                        'parent'             => $temp_data[$key]['parent'],
                        'type'               => $value['type']
                    );

                if(empty($value['data']['id']))
                {
                    $result = $this->db->insert('audit_paf_node', $each_node);

                    $this_node_id = $this->db->insert_id();
                     
                    foreach ($temp_data as $key_2 => $value_2)
                    {
                        // print_r(array($value_2['parent'], $value['id']));
                        if($value_2['parent'] == $value['id'])
                        {
                            $temp_data[$key_2]['parent'] = $this_node_id;
                        }
                    }

                    // array_push($fs_categorized_account_ids, $this_category_id);
                }
                else
                {
                    $this->db->where('id', $value['data']['id']);
                    $result = $this->db->update('audit_paf_node', $each_node);

                    foreach ($temp_data as $key_2 => $value_2)
                    {
                        // print_r(array($value_2['parent'], $value['id']));
                        if($value_2['parent'] == $value['id'])
                        {
                            $temp_data[$key_2]['parent'] = $value['data']['id'];
                        }
                    }

                    // array_push($fs_categorized_account_ids, $value['data']['id']);
                }
            }
        }

        //doesn't mean anything, just pass back something for response
        echo json_encode($paf_id);

        // print_r($form_data);
    }

    public function delete_paf()
    {
        $form_data = $this->input->post();

        $result = $this->paf_upload_model->delete_paf($form_data['paf_id']);

        echo $result;
    }

    public function uploadPafDoc()
    {
        if(isset($_FILES['paf_docs']))
        {
            $filesCount = count($_FILES['paf_docs']['name']);
            $uploadedFiles  = array();

            for($i = 0; $i < $filesCount; $i++)
            {   //echo json_encode($_FILES['uploadimages']);
                $_FILES['paf_doc']['name']     = $_FILES['paf_docs']['name'][$i];
                $_FILES['paf_doc']['type']     = $_FILES['paf_docs']['type'][$i];
                $_FILES['paf_doc']['tmp_name'] = $_FILES['paf_docs']['tmp_name'][$i];
                $_FILES['paf_doc']['error']    = $_FILES['paf_docs']['error'][$i];
                $_FILES['paf_doc']['size']     = $_FILES['paf_docs']['size'][$i];

                $uploadPath = './document/paf';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('paf_doc'))
                {
                    $fileData = $this->upload->data();
                    // $uploadData[$i]['bank_auth_id'] = $_POST['ba_id'];
                    $uploadData[$i]['file_name'] = $fileData['file_name'];
                    array_push($uploadedFiles, $fileData['file_name']);
                }
                else
                {
                    $error = $this->upload->display_errors();
                    echo json_encode($error);
                }

            }
            if(!empty($uploadData))
            {
                // $this->db->insert_batch('audit_bank_auth_document',$uploadData);
                // print_r($uploadData);
                
            }


        }

        // if (count($this->session->userdata('ba_files_id')) != 0)
        // {
        //     $ba_files_id = $this->session->userdata('ba_files_id');
        //     $this->session->unset_userdata('ba_files_id');
        //     for($i = 0; $i < count($ba_files_id); $i++)
        //     {
        //         $files = $this->db->query("select * from audit_bank_auth_document where id='".$ba_files_id[$i]."'");
        //         $file_info = $files->result_array();

        //         $this->db->where('id', $ba_files_id[$i]);

        //         if(file_exists("./uploads/bank_images_or_pdf/".$file_info[0]["file_name"]))
        //         {
        //             unlink("./uploads/bank_images_or_pdf/".$file_info[0]["file_name"]);

        //         }
                
        //         $this->db->delete('audit_bank_auth_document', array('id' => $ba_files_id[$i]));
        //     }
        // }

        
        if(isset($uploadedFiles))
        {
            echo json_encode($uploadedFiles);
        }
        else
        {
            echo json_encode("");
        }

        
    }

   

    public static function getClientName() 
    {
        $ci =& get_instance();

        // $this->db->select('client.id, client.company_code, client.company_name');
        // $this->db->from('client');
        // // $this->db->join('audit_client', 'audit_client.company_code = client.company_code', 'left');
        // $this->db->join('client_billing_info', 'client_billing_info.company_code = client.company_code', 'right');
        // $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service AND our_service_info.service_type = 1', 'right');
        // // $query = $this->db->get();

        $query = 'SELECT client.id, client.company_code, client.company_name FROM client right join client_billing_info on client_billing_info.company_code = client.company_code right join our_service_info on our_service_info.id = client_billing_info.service AND our_service_info.service_type = 1';

        

       

        // echo $query;

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("Client Name not found.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['company_name'] != null)
                {
                    $res[$row['company_code']] = $this->encryption->decrypt($row['company_name']);
                }
              
            }
            //$res = json_decode($res);
            $ci =& get_instance();
            $selected_client_name = $ci->session->userdata('paf_company_code');
            $ci->session->unset_userdata('paf_company_code');

            // print_r($res);

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client Name fetched successfully.", 'result'=>$res, 'selected_client_name'=>$selected_client_name);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_client_name'=>'');

            echo json_encode($data);
        }
    }

    public static function getFyeDate() 
    {
        $ci =& get_instance();
        $form_data = $this->input->get();

        if(isset($form_data['company_code']))
        {
          
            $query = "SELECT year_end FROM filing 
                      WHERE company_code = '".$form_data['company_code']."'";
            
        }
        

        // echo $query;

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("No FYE date.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['year_end'] != null)
                {

                    $res[DateTime::createFromFormat('d F Y', $row['year_end'] )->format('Y-m-d')] = $row['year_end'];
                }
              
            }
            //$res = json_decode($res);
            // $res = $result[0]['fye_date'];
            // $res = DateTime::createFromFormat('Y-m-d', $res )->format('d F Y');

            $ci =& get_instance();
            $selected_fye = $ci->session->userdata('paf_fye');
            $ci->session->unset_userdata('paf_fye');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"FYE date fetched successfully.", 'result'=>$res, 'selected_fye'=>$selected_fye);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'fail', 'tp'=>0, 'msg'=>"No FYE date set for selected company.", 'result'=>$res, 'selected_fye'=>'');

            echo json_encode($data);
        }
    }

   
    public static function getAuthStatus() {

        //try {
        //echo $nationalityId;
        $ci =& get_instance();

        $query = "SELECT id, status FROM audit_status";

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
        $select_auth_status = $ci->session->userdata('auth_status');
        $ci->session->unset_userdata('auth_status');

        /*if($nationalityId != "null")
        {
        $select_nationality = $nationalityId;
        }*/
        /*else
        {
        $select_nationality = "null";
        }*/
        //$select_country = $select_country->result_array();
        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Acquried By fetched successfully.", 'result'=>$res, 'select_auth_status'=>$select_auth_status);
        /*} catch (Exception $e) {
        $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
        }*/ /*finally {
        echo json_encode($data);
        //return $data;

        }*/
        echo json_encode($data);
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
            $address = $street_name . $comma . $br . $unit_building_name . $br . 'Singapore ' . $postal_code;
        }
        elseif(!empty($building_name))
        {
            $address = $street_name . $comma . $br . $building_name . $comma . $br . 'Singapore ' . $postal_code;
        }
        else
        {
            $address = $street_name . $comma . $br . 'Singapore ' . $postal_code;
        }
        return $address;
    }

    public function write_header($firm_id, $use_own_header)
    {
        $query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
                                                JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
                                                JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
                                                JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
                                                where firm.id = '".$firm_id."'");
        $query = $query->result_array();

        // Calling getimagesize() function 
        list($width, $height, $type, $attr) = getimagesize("uploads/logo/" . $query[0]["file_name"]); 

        $different_w_h = (float)$width - (float)$height;

        if((float)$width > (float)$height && $different_w_h > 100)
        {
            //before width is 25, height is 73.75
            $td_width = 25;
            $td_height = 73.75;
        }
        else
        {
            $td_width = 15;
            $td_height = 83.75;
        }

        if(!$use_own_header){
            if(!empty($query[0]["file_name"]))
            {
                $img = '<img src="uploads/logo/'. $query[0]["file_name"] .'" height="55" />';
            }
            else
            {
                $img = '';
            }
        }

        if(!$use_own_header)
        {
            $header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
                    <tbody>
                    <tr style="height: 60px;">
                        <td style="text-align: left; height: 60px; padding: 5%;" width="'.$td_width.'%" align="center">
                            <table style="border-collapse: collapse; width: 100%;" border="0">
                            <tbody>
                            <tr>
                            <td style="text-align: left; height: 60px;" align="center"><p>'. $img .'  </p></td>
                            </tr>
                            </tbody>
                            </table>
                        </td>
                        <td style="text-align: left;" width="1.25%">&nbsp;</td>
                        <td style="height: 60px;" width="'.$td_height.'%"><span style="font-size: 18pt;">'.$query[0]["name"].'</span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />Address: '. $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"] .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $query[0]["fax"] .'&nbsp;</span></td>
                    </tr>
                    </tbody>
                    </table>';
        }
        else
        {
            $header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
                                <tbody>
                                <tr style="height: 80px;"><td style="height: 60px;"></td></tr>
                                </tbody>
                               </table>';
        }

        return $header_content;
    }



}

class MYPDF extends TCPDF {
    public function Header() {
        $headerData = $this->getHeaderData();
        $this->writeHTMLCell(0, 0, 20, 7, $headerData['string'], 0, 0, false, "C", true);
   }

   public function Footer() {
        // Position at 25 mm from bottom
        $this->SetY(-18);
        // $this->SetX(10);


        // Set font
        $this->SetFont('helvetica', 'B', 8);

        $logoX = 160; // The logo will be displayed on the left side close to the border of the page
        $logoFileName = base_url()."img/footer_img.png";
        $logoWidth = 40; // 15mm
        $logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);

        $this->Cell(0,0, $logo, 0, 0, 'R');
        
        // $this->Cell(0, 0, '53 Ubi Ave 3 #01-01 Travelite Building, Singapore 408863', 0, 0, 'C');
        // $this->Ln();
        // $this->Cell(0, 0,'Tel:(65) 6785 8000      Fax:(65) 6785 7000      Email: jonfresh@singnet.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // // Page number
        // if (empty($this->pagegroups)) {
        //     $pagenumtxt = 'Page '.' '.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
        // } else {
        //     $pagenumtxt = 'Page '.' '.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
        // }
        // $this->SetFont('helvetica', 'B', 10);
        // $this->Cell(0, 5, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // $this->Ln(1);
        // $this->SetFont('helvetica', 'B', 8);
        // $this->Cell(170, 15, 'Powered by ', 0, false, 'C', 0, '', 0, false, 'T', 'M');


  //       $logoX = 110; // 186mm. The logo will be displayed on the right side close to the border of the page
        // $logoFileName = base_url()."/assets/uploads/logos/Audit_logo.png";
        // $logoWidth = 10; // 15mm
        // // $logo = $this->Image($logoFileName, $logoX, $this->GetY() + 5, $logoWidth);

        // $this->Ln(4);
  //       $this->SetFont('helvetica', 'B', 8);
  //       $this->Cell(0, 15, 'enquiry@acumenbizcorp.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //$this->GetY()
   }
   
}

class NOFOOTER_PDF extends TCPDF {
    public function Header() {
        $headerData = $this->getHeaderData();
        $this->writeHTMLCell(0, 0, 20, 12, $headerData['string'], 0, 0, false, "C", true);
   }

   public function Footer() {
        // Position at 25 mm from bottom
        // $this->SetY(-16);
        // $this->SetX(10);


        // // Set font
        // $this->SetFont('helvetica', 'B', 8);

        // $logoX = 20; // The logo will be displayed on the left side close to the border of the page
        // $logoFileName = base_url()."img/footer_img.png";
        // $logoWidth = 50; // 15mm
        // $logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);

        // $this->Cell(0,0, $logo, 0, 0, 'R');
        
        // $this->Cell(0, 0, '53 Ubi Ave 3 #01-01 Travelite Building, Singapore 408863', 0, 0, 'C');
        // $this->Ln();
        // $this->Cell(0, 0,'Tel:(65) 6785 8000      Fax:(65) 6785 7000      Email: jonfresh@singnet.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // // Page number
        // if (empty($this->pagegroups)) {
        //     $pagenumtxt = 'Page '.' '.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
        // } else {
        //     $pagenumtxt = 'Page '.' '.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
        // }
        // $this->SetFont('helvetica', 'B', 10);
        // $this->Cell(0, 5, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // $this->Ln(1);
        // $this->SetFont('helvetica', 'B', 8);
        // $this->Cell(170, 15, 'Powered by ', 0, false, 'C', 0, '', 0, false, 'T', 'M');


  //       $logoX = 110; // 186mm. The logo will be displayed on the right side close to the border of the page
        // $logoFileName = base_url()."/assets/uploads/logos/Audit_logo.png";
        // $logoWidth = 10; // 15mm
        // // $logo = $this->Image($logoFileName, $logoX, $this->GetY() + 5, $logoWidth);

        // $this->Ln(4);
  //       $this->SetFont('helvetica', 'B', 8);
  //       $this->Cell(0, 15, 'enquiry@acumenbizcorp.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //$this->GetY()
   }
   
}


