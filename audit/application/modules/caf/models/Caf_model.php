<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//include 'application/js/random_alphanumeric_generator.php';

class Caf_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function getAssignmentDetail($assg_id)
    {
        // $q = $this->db->query("SELECT payroll_assignment.*,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job, completed.report_date , completed.partner
        //                         FROM payroll_assignment 
        //                         LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
        //                         LEFT JOIN payroll_assignment_completed completed ON payroll_assignment.id = completed.payroll_assignment_id
        //                         LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
        //                         LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
        //                         WHERE payroll_assignment.id = '".$assg_id."' AND payroll_assignment.status != '10' AND payroll_assignment.deleted = '0'");

        $q = $this->db->query("SELECT payroll_assignment.*,firm.name as firm_name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job, completed.report_date , completed.partner, client.company_name as client_name
                                FROM payroll_assignment 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN client ON client.company_code = payroll_assignment.client_id
                                LEFT JOIN payroll_assignment_completed completed ON payroll_assignment.id = completed.payroll_assignment_id
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                WHERE payroll_assignment.id = '".$assg_id."' AND payroll_assignment.deleted = '0'");

        if(count($q->result()) > 0)
        {
            $q = $q->result();

            $q[0]->client_name = $this->encryption->decrypt($q[0]->client_name);
            
            return $q[0];
        }
        
        
    }

    public function getCaf($assg_id)
    {
        $user_id = $this->session->userdata('user_id');

        $this->db->select('audit_caf_master.*, type.worksheet_url');
        $this->db->join('audit_caf_type type','type.id = audit_caf_master.type_id', 'left');
        $this->db->where(array('assignment_id' => $assg_id, 'deleted' => 0, 'type.fixed' => 0));
        
        $check_no_added_caf = $this->db->get("audit_caf_master");

        
        $this->db->select('audit_caf_master.*, type.worksheet_url');
        $this->db->join('audit_caf_type type','type.id = audit_caf_master.type_id', 'left');
        $this->db->where(array('assignment_id' => $assg_id, 'deleted' => 0));
        // $this->db->order_by('order_time asc, id ASC');
            //$this->db->where('unique_code', $unique_code);
            //$this->db->where('row_status', 0);
        // $this->db->order_by('id', 'desc');
        $q = $this->db->get("audit_caf_master");

        
        if ($q->num_rows() > 0) {

            if ($check_no_added_caf->num_rows() == 0) {
                
                $this->insertRollForwardProgramme($assg_id);
            }

            $this->db->select('audit_caf_master.*, type.worksheet_url');
            $this->db->join('audit_caf_type type','type.id = audit_caf_master.type_id', 'left');
            $this->db->where(array('assignment_id' => $assg_id, 'deleted' => 0));
            // $this->db->order_by('order_time asc, id ASC');
                //$this->db->where('unique_code', $unique_code);
                //$this->db->where('row_status', 0);
            // $this->db->order_by('id', 'desc');
            $q = $this->db->get("audit_caf_master");
 
            $caf = $q->result();
            // print_r($caf);

            return $caf;
        }
        else
        {

            $this->insertInitialCaf($assg_id);

            if ($check_no_added_caf->num_rows() == 0) {
            
                $this->insertRollForwardProgramme($assg_id);
            } 

            $this->db->select('audit_caf_master.*, type.worksheet_url');
            $this->db->join('audit_caf_type type','type.id = audit_caf_master.type_id', 'left');
            $this->db->where(array('assignment_id' => $assg_id, 'deleted' => 0));
            // $this->db->order_by('order_time asc, id ASC');
                //$this->db->where('unique_code', $unique_code);
                //$this->db->where('row_status', 0);
            // $this->db->order_by('id', 'desc');
            $q = $this->db->get("audit_caf_master");
            $caf = $q->result();

            return $caf;



        }
    }

    public function getEmployeeIdWithUserId($user_id)
    {
        $query = 'SELECT employee_id  FROM `payroll_user_employee`
                    WHERE payroll_user_employee.user_id = '.$user_id;

        $result = $this->db->query($query);

        $result = $result->result_array();

        if(count($result) > 0)
        {
            return $result[0]['employee_id'];
        }
        

    }


    public function insertInitialCaf($assg_id)
    {
        

        $this->db->where(array('fixed' => 1));
            
        $q = $this->db->get("audit_caf_type");

        $fixed_caf_arr = $q->result_array();

        foreach ($fixed_caf_arr as $key => $each) {
            $data   = array(
                        'assignment_id'     => $assg_id,
                        'created_by'    => $this->session->userdata('user_id'),
                        'index_no'    => $each['index_no'],
                        'name'         => $each['name'],
                        'type_id'      => $each['id'],

                      );

            $this->db->insert('audit_caf_master', $data);
        }

        //add audit programme to caf
        // $q = $this->db->query("SELECT * FROM audit_programme_master where deleted=0 and archived=0 ORDER BY programme_index");

        // $audit_programme = $q->result_array();
        // foreach ($audit_programme as $programme_key => $programme_each) {
        //     $data   = array(
        //                 'assignment_id'       => $assg_id,
        //                 'created_by'          => $this->session->userdata('user_id'),
        //                 'index_no'            => $programme_each['programme_index'],
        //                 'name'                => $programme_each['title'],
        //                 'programme_master_id' => $programme_each['id'],
        //                 'type_id'             => 7,  // type id 7 is audit_programme

        //             );

        //     $this->db->insert('audit_caf_master', $data);
        // }

    }

    public function insertRollForwardProgramme($assg_id)
    {
        
        $last_ye_company_info_id = $this->get_company_info_last_year($assg_id);

        if($last_ye_company_info_id != 0)
        {
            $this->db->select('audit_caf_master.*, type.worksheet_url, type.id as type_id');
            $this->db->join('audit_caf_type type','type.id = audit_caf_master.type_id', 'left');
            $this->db->where(array('assignment_id' => $last_ye_company_info_id, 'deleted' => 0, 'type.fixed' => 0));
            
            $last_ye_audit_programmes = $this->db->get("audit_caf_master");

            $last_ye_audit_programmes = $last_ye_audit_programmes->result_array();

            foreach ($last_ye_audit_programmes as $programme_key => $programme_each) {
                $data   = array(
                            'assignment_id'       => $assg_id,
                            'created_by'          => $this->session->userdata('user_id'),
                            'index_no'            => $programme_each['index_no'],
                            'name'                => $programme_each['name'],
                            'programme_master_id' => $programme_each['programme_master_id'],
                            'type_id'             => $programme_each['type_id'],  
                        );

                $this->db->insert('audit_caf_master', $data);
                $insert_id = $this->db->insert_id();

                if($programme_each['type_id'] == 7) //default programme
                {
                    $q = $this->db->query('SELECT * from  audit_procedure_caf_input p_caf where p_caf.caf_id ='.$programme_each['id']);


                    if ($q->num_rows() > 0) {
                        // $this->session->set_userdata('selected_programme_type', $q->result()[0]->type);
                        $programme_procedure_lines = $q->result_array();
                    }
                    else
                    {
                        $programme_procedure_lines = false;
                    }

                    if($programme_procedure_lines)
                    {
                        $temp_arr = [];
                        foreach ($programme_procedure_lines as $programme_procedure_line_key => $programme_procedure_line) 
                        {
                            $temp_arr['audit_procedure_id']  = $programme_procedure_line['audit_procedure_id'];
                            $temp_arr['caf_id']  = $insert_id;
                            $temp_arr['yn_value'] = $programme_procedure_line['yn_value'];
                            $temp_arr['comment'] = $programme_procedure_line['comment'];

                            $this->db->insert('audit_procedure_caf_input', $temp_arr);
                            
                        }

                    }

                    $programme_conclusion      = $this->caf_model->get_programme_conclusion($programme_each['id']);
                    // print_r($programme_conclusion['planning_conclusion']);
                    $dropdown_data['caf_id'] = $insert_id;
                    $dropdown_data['planning_conclusion'] = $programme_conclusion['planning_conclusion'];
                    $dropdown_data['conclusion'] = $programme_conclusion['conclusion'];

                    $this->db->insert('audit_programme_conclusion_input', $dropdown_data);

                    
                }
                else if($programme_each['type_id'] == 8) // programme yn
                {
                    $q = $this->db->query('SELECT * from  audit_programme_yn_input where caf_id ='.$programme_each['id']);

                    if ($q->num_rows() > 0) {
                        // $this->session->set_userdata('selected_programme_type', $q->result()[0]->type);
                        $programme_yn_lines = $q->result_array();
                    }
                    else
                    {
                        $programme_yn_lines = false;
                    }

                    if($programme_yn_lines)
                    {
                        $temp_arr = [];
                        foreach ($programme_yn_lines as $programme_yn_line_key => $programme_yn_line) 
                        {
                            $temp_arr['programme_content_id']  = $programme_yn_line['programme_content_id'];
                            $temp_arr['caf_id']  = $insert_id;
                            $temp_arr['yn_value'] = $programme_yn_line['yn_value'];
                            $temp_arr['remark'] = $programme_yn_line['remark'];
                            
                            $this->db->insert('audit_programme_yn_input', $temp_arr);
                        }

                    }
                }
                else if($programme_each['type_id'] == 9) // programme qa
                {
                    
                    $q = $this->db->query('SELECT * from  audit_programme_qa_answer where caf_id ='.$programme_each['id']);

                    if ($q->num_rows() > 0) {
                        // $this->session->set_userdata('selected_programme_type', $q->result()[0]->type);
                        $programme_qa_lines = $q->result_array();
                    }
                    else
                    {
                        $programme_qa_lines = false;
                    }

                    if($programme_qa_lines)
                    {
                        $temp_arr = [];
                        foreach ($programme_qa_lines as $programme_qa_line_key => $programme_qa_line) 
                        {
                            $temp_arr['id']  = '';
                            $temp_arr['caf_id']  = $insert_id;
                            $temp_arr['programme_question_id'] = $programme_qa_line['programme_question_id'];
                            $temp_arr['answer_text'] = $programme_qa_line['answer_text'];
                            
                            $result = $this->insert_audit_programme_qa_caf_input($temp_arr);
                        }

                    }
                }
                else if($programme_each['type_id'] == 10) // programme meeting
                {
                    $data_meeting   = $this->get_meeting_master_detail($programme_each['id']);

                     $data = array(
                        'id'               => '',
                        'caf_id'           => $insert_id,
                        'meeting_datetime' => $data_meeting['meeting_datetime'],
                        'location'         => $data_meeting['location'],
                        'absent_flag'      => $data_meeting['absent_flag']
                    );

                    $new_meeting_master_id = $this->submit_audit_programme_meeting_master($data);
                    // $meeting_master_id = "";
                    // echo $meeting_datetime;
                    $meeting_master_id = $data_meeting?$data_meeting['id']:"";

                    if($meeting_master_id != "")
                    {
                        $meeting_attendees         = $this->get_meeting_attendees($meeting_master_id);
                        $meeting_agenda            = $this->get_meeting_agenda($meeting_master_id);
                        $meeting_absent            = $this->get_meeting_absent($meeting_master_id);
                    }

                    $temp_arr = [];

                    foreach ($meeting_attendees as $key => $each_line) {

                        $temp_arr['id']                     = '';
                        $temp_arr['meeting_master_id']      = $new_meeting_master_id;
                        $temp_arr['attendees_name']         = $each_line['attendees_name'];
                        $temp_arr['attendees_designation']  = $each_line['attendees_designation'];

                        // print_r($temp_arr);
                        if(array_filter($temp_arr)) {
                            // print_r($temp_arr);
                            $result = $this->insert_audit_programme_meeting_attendees($temp_arr);
                        }
                        
                    }

                    $temp_arr = [];

                    foreach ($meeting_agenda as $key => $each_line) {

                        $temp_arr['id']                     = '';
                        $temp_arr['meeting_master_id']      = $new_meeting_master_id;
                        $temp_arr['agenda_text']            = $each_line['agenda_text'];
                        $temp_arr['minutes_of_meeting']     = $each_line['minutes_of_meeting'];

                        if(array_filter($temp_arr)) {
                            // print_r($temp_arr);
                            $result = $this->insert_audit_programme_meeting_agenda($temp_arr);
                        }
                        

                    }

                    $temp_arr = [];

                    foreach ($meeting_absent as $key => $each_line) {
                  
                        $temp_arr['id']                     = '';
                        $temp_arr['meeting_master_id']      = $new_meeting_master_id;
                        $temp_arr['absent_name']            = $each_line['absent_name'];
                        $temp_arr['engagement_role']        = $each_line['engagement_role'];
                        $temp_arr['absent_subject']         = $each_line['absent_subject'];
                        $temp_arr['date_communicated']      = $each_line['date_communicated'];

                        

                        if(array_filter($temp_arr)) {
                            // print_r($temp_arr);
                            $result = $this->insert_audit_programme_meeting_absent($temp_arr);
                        }   

                    }

                    
                }
                else if($programme_each['type_id'] == 11) //programme opinion
                {
                    $opinion_detail  = $this->get_opinion_detail($programme_each['id']);

                    $data = array(
                        'id'                     => '',
                        'caf_id'                 => $insert_id,
                        'date_of_report'         => $opinion_detail['date_of_report'],
                        'fs_opinion'             => $opinion_detail['fs_opinion'],
                        'fs_opinion_modified'    => $opinion_detail['fs_opinion_modified'],
                        'concern_assumption'     => $opinion_detail['concern_assumption'],
                        'concern_desc'           => $opinion_detail['concern_desc'],
                        'emphasis_required'      => $opinion_detail['emphasis_required'],
                        'emphasis_desc'          => $opinion_detail['emphasis_desc'],
                        'key_audit_required'     => $opinion_detail['key_audit_required'],
                        'key_audit_desc'         => $opinion_detail['key_audit_desc'],
                        'other_matters_required' => $opinion_detail['key_audit_required'],
                        'other_matters_desc'     => $opinion_detail['other_matters_desc'],
                        'audit_opinion'          => $opinion_detail['audit_opinion'],
                        'audit_opinion_modified' => $opinion_detail['audit_opinion_modified']

                    );

                    $result = $this->submit_audit_programme_opinion_input($data);
                }
                else if($programme_each['type_id'] == 12) //programme opinion
                {
                    $currency_detail = $this->get_currency_detail($programme_each['id']);

                    $currency_detail['id'] = '';
                    $currency_detail['caf_id'] = $insert_id;


                    $result = $this->submit_audit_programme_currency_input($currency_detail);
                }
                else if($programme_each['type_id'] == 13) //programme cdd
                {
                    $cdd_detail = $this->db->query('SELECT * from audit_programme_cdd_input where caf_id ='.$programme_each['id']);


                    if ($cdd_detail->num_rows() > 0) {
                        $cdd_detail = $cdd_detail->result_array();

                        $cdd_detail[0]['id'] = '';
                        $cdd_detail[0]['caf_id'] = $insert_id;

                        $result = $this->submit_audit_programme_cdd_input($cdd_detail[0]);
                    }
                   
                }
                else if($programme_each['type_id'] == 14 || $programme_each['type_id'] == 15) //programme apm/fcm
                {
                    $fcm_apm_detail = $this->get_fcm_apm_detail($programme_each['id']);


                    if ($q->num_rows() > 0) {

                        $fcm_apm_detail['id'] = '';
                        $fcm_apm_detail['caf_id'] = $insert_id;

                        $result = $this->submit_audit_programme_fcm_apm_input($fcm_apm_detail);
                    }
                   
                }
            }

        }

        //add audit programme to caf
        // $q = $this->db->query("SELECT * FROM audit_programme_master where deleted=0 and archived=0 ORDER BY programme_index");

        // $audit_programme = $q->result_array();
        // foreach ($audit_programme as $programme_key => $programme_each) {
        //     $data   = array(
        //                 'assignment_id'       => $assg_id,
        //                 'created_by'          => $this->session->userdata('user_id'),
        //                 'index_no'            => $programme_each['programme_index'],
        //                 'name'                => $programme_each['title'],
        //                 'programme_master_id' => $programme_each['id'],
        //                 'type_id'             => 7,  // type id 7 is audit_programme

        //             );

        //     $this->db->insert('audit_caf_master', $data);
        // }

    }

    // public function insert_batch_trial_balance($trial_balance, $fs_company_info_id)
    // {
    //     // delete previous record if exist
    //     $this->db->where_in('fs_company_info_id', $fs_company_info_id);
    //     $this->db->delete('fs_trial_balance');

    //     // insert new data
    //     $this->db->insert_batch('fs_trial_balance', $trial_balance);

    //     // compare and remove item which is not in list of trial balance.

    //     // remove all uncategorized list
    //     // $this->db->where_in('fs_company_info_id', $fs_company_info_id);
    //     // $this->db->delete('fs_uncategorized_account');

    //     // categorized list
    //     $categorized = $this->db->query("SELECT * FROM fs_categorized_account WHERE fs_company_info_id=" . $fs_company_info_id);

    //     if($categorized->num_rows() > 0)
    //     {
    //         $categorized = $categorized->result_array();

    //         // trial balance list
    //         $trial_balance_d = $this->db->query("SELECT * FROM  fs_trial_balance WHERE fs_company_info_id=" . $fs_company_info_id);

    //         if($trial_balance_d->num_rows() > 0)
    //         {
    //             $trial_balance_d = $trial_balance_d->result_array();
    //         }

    //         $exist_before = [];
    //         $for_encategorized = [];

    //         $delete_categorized_account = [];

    //         foreach($categorized as $key => $value_1)
    //         {
    //             if($value_1['type'] == "Leaf")
    //             {
    //                 foreach ($trial_balance_d as $tb_key => $value_2) 
    //                 {
    //                     if($value_1['description'] == $value_2['description'] && $value_1['value'] == $value_2['value'])    // if both description and value are same
    //                     {
    //                         array_push($exist_before, $tb_key);
    //                     }
    //                     elseif($value_1['description'] == $value_2['description'])
    //                     {
    //                         array_push($exist_before, $tb_key);

    //                         $this->db->where('id', $value_1['id']);
    //                         $result = $this->db->update('fs_categorized_account', array('value' => $value_2['value']));
    //                     }
    //                     // else
    //                     // {
    //                     //     array_push($delete_categorized_account, $value_1['id']);
    //                     // }
    //                 }
    //             }
    //         }

    //         // return json_encode($exist_before);

    //         // remove existed list
    //         foreach ($exist_before as $eb_key => $eb_value) 
    //         {
    //             unset($trial_balance_d[$eb_value]);
    //         }

    //         // return json_encode($trial_balance_d);

    //         if(count($delete_categorized_account) > 0)  // delete
    //         {
    //             // $this->db->where_in('fs_company_info_id', $fs_company_info_id);
    //             // $this->db->delete('fs_categorized_account');

    //             $this->db->where_in('id', $delete_categorized_account);
    //             $this->db->delete('fs_categorized_account');
    //         }

    //         /* insert uncategorized list */
    //         $uncategorized_list_new = [];

    //         foreach ($trial_balance_d as $key => $value) 
    //         {
    //             array_push($uncategorized_list_new, 
    //                 array(
    //                     'fs_company_info_id' => $value['fs_company_info_id'],
    //                     'description'        => $value['description'],
    //                     'value'              => $value['value'],
    //                     // 'company_end_prev_ye_value' => 0.00,
    //                     'order_by'           => $key + 1
    //                 )
    //             );
    //         }

    //         $uncategorized_list_db = $this->db->query("SELECT * FROM fs_uncategorized_account WHERE fs_company_info_id=" . $fs_company_info_id);
    //         $uncategorized_list_db = $uncategorized_list_db->result_array();

    //         foreach ($uncategorized_list_new as $ucn_key => $ucn_value)
    //         {
    //             foreach ($uncategorized_list_db as $uc_key => $uc_value)
    //             {
    //                 if($uc_value['description'] == $ucn_value['description'])
    //                 {
    //                     $this->db->where('id', $uc_value['id']);
    //                     $result = $this->db->update('fs_uncategorized_account', array('value' => $ucn_value['value']));
    //                 }
    //             }
    //         }

    //         // $this->db->insert_batch('fs_uncategorized_account', $uncategorized_list_new);
    //         /* END OF insert uncategorized list */
    //     }

    //     return json_encode($trial_balance_d);
    // }

    public function insert_batch_trial_balance($trial_balance, $assignment_id)
    {
        // delete previous record if exist
        $this->db->where_in('assignment_id', $assignment_id);
        $this->db->delete('audit_trial_balance');

        // insert new data
        $this->db->insert_batch('audit_trial_balance', $trial_balance);

        // compare and remove item which is not in list of trial balance.

        // remove all uncategorized list
        // $this->db->where_in('fs_company_info_id', $fs_company_info_id);
        // $this->db->delete('fs_uncategorized_account');

        // categorized list
        $categorized = $this->db->query("SELECT * FROM audit_categorized_account WHERE assignment_id=" . $assignment_id);

        if($categorized->num_rows() > 0)
        {
            $categorized = $categorized->result_array();

            // trial balance list
            $trial_balance_d = $this->db->query("SELECT * FROM  audit_trial_balance WHERE assignment_id=" . $assignment_id);

            if($trial_balance_d->num_rows() > 0)
            {
                $trial_balance_d = $trial_balance_d->result_array();
            }

            $exist_before = [];
            $for_encategorized = [];

            $delete_categorized_account = [];

            foreach($categorized as $key => $value_1)
            {
                if($value_1['type'] == "Leaf")
                {
                    foreach ($trial_balance_d as $tb_key => $value_2) 
                    {
                        // print_r(array($value_1['description'], $value_2['description']));
                        
                        if($value_1['description'] == $value_2['description'] && $value_1['value'] == $value_2['value'])    // if both description and value are same
                        {
                            array_push($exist_before, $tb_key);
                        }
                        elseif($value_1['description'] == $value_2['description'])
                        {
                            array_push($exist_before, $tb_key);

                            $this->db->where('id', $value_1['id']);
                            $result = $this->db->update('audit_categorized_account', array('value' => $value_2['value']));
                        }
                        // else
                        // {
                        //     array_push($delete_categorized_account, $value_1['id']);
                        // }
                    }
                }
            }

            // return json_encode($exist_before);

            // // remove existed list
            foreach ($exist_before as $eb_key => $eb_value) 
            {
                unset($trial_balance_d[$eb_value]);
            }

            // // return json_encode($trial_balance_d);

            // if(count($delete_categorized_account) > 0)  // delete
            // {
            //     // $this->db->where_in('fs_company_info_id', $fs_company_info_id);
            //     // $this->db->delete('fs_categorized_account');

            //     $this->db->where_in('id', $delete_categorized_account);
            //     $this->db->delete('fs_categorized_account');
            // }

            /* insert uncategorized list */
            $uncategorized_list_new = [];

            foreach ($trial_balance_d as $key => $value) 
            {
                array_push($uncategorized_list_new, 
                    array(
                        'assignment_id'    => $value['assignment_id'],
                        'description'      => $value['description'],
                        'value'            => $value['value'],
                        // 'company_end_prev_ye_value' => 0.00,
                        'order_by'         => $key + 1
                    )
                );
            }

            $uncategorized_list_db = $this->db->query("SELECT * FROM audit_uncategorized_account WHERE assignment_id=" . $assignment_id);
            $uncategorized_list_db = $uncategorized_list_db->result_array();

            foreach ($uncategorized_list_new as $ucn_key => $ucn_value) 
            {
                $matched = false;

                foreach ($uncategorized_list_db as $uc_key => $uc_value) 
                {
                    if($uc_value['description'] == $ucn_value['description'])
                    {
                        $this->db->where('id', $uc_value['id']);
                        $result = $this->db->update('audit_uncategorized_account', array('value' => $ucn_value['value']));

                        $matched = true;
                    }
                }

                if(!$matched)
                {
                    $this->db->insert('audit_uncategorized_account', $ucn_value);
                }
            }
            /* END OF insert uncategorized list */
        }
        else
        {
            $trial_balance_d = "";
        }

        return json_encode($trial_balance_d);
    }

    public function insert_batch_LY_trial_balance($trial_balance, $assignment_id)
    {
        // delete previous record if exist
        $this->db->where_in('assignment_id', $assignment_id);
        $this->db->delete('audit_ly_trial_balance');

        // insert new data
        $this->db->insert_batch('audit_ly_trial_balance', $trial_balance);

        // last year trial balance list
        $ly_trial_balance_d = $this->db->query("SELECT * FROM audit_ly_trial_balance WHERE assignment_id=" . $assignment_id);

        if($ly_trial_balance_d->num_rows() > 0)
        {
            $ly_trial_balance_d = $ly_trial_balance_d->result_array();
        }

        // categorized list
        $categorized = $this->db->query("SELECT * FROM audit_categorized_account WHERE assignment_id=" . $assignment_id);

        if($categorized->num_rows() > 0)
        {
            $categorized = $categorized->result_array();

            $same_desc_list = [];
            $for_encategorized = [];

            foreach($categorized as $key => $value_1)
            {
                if($value_1['type'] == "Leaf")
                {
                    foreach ($ly_trial_balance_d as $tb_key => $value_2) 
                    {
                        if($value_1['description'] == $value_2['description'])
                        {
                            $this->db->where('id', $value_1['id']);
                            $result = $this->db->update('audit_categorized_account', array('company_end_prev_ye_value' => $value_2['value']));

                            array_push($same_desc_list, $tb_key);
                        }
                    }
                }
            }



            // remove existed list
            foreach ($same_desc_list as $eb_key => $eb_value) 
            {
                unset($ly_trial_balance_d[$eb_value]);
            }

            /* insert uncategorized list */
            // trial balance list
            $uncategorized_list = $this->db->query("SELECT * FROM  audit_uncategorized_account WHERE assignment_id=" . $assignment_id);
            $uncategorized_list = $uncategorized_list->result_array();

            /* insert uncategorized list */
            $uncategorized_desc_list = array_column($uncategorized_list, 'description');
            $order_by = count($uncategorized_list);



            foreach ($ly_trial_balance_d as $ly_key => $ly_value) 
            {
                if(in_array($ly_value['description'], $uncategorized_desc_list))
                {
                    $udl_key = array_search($ly_value['description'], $uncategorized_desc_list);
                    // print_r($uncategorized_desc_list[$udl_key]."----");

                    $this->db->where('id', $uncategorized_list[$udl_key]['id']);
                    $result = $this->db->update('audit_uncategorized_account', array('company_end_prev_ye_value' => $ly_value['value']));
                }
                else
                {
                    $order_by++;

                    $result = $this->db->insert('audit_uncategorized_account', 
                                    array(
                                        'assignment_id' => $ly_value['assignment_id'],
                                        'description'        => $ly_value['description'],
                                        'company_end_prev_ye_value' => $ly_value['value'],
                                        'order_by'           => $order_by
                                    ));
                }
            }
            /* END OF insert uncategorized list */
        }
        else
        {
            return "no existing record";
        }

        return $result;
    }


    // public function get_create_uncategorizedData($assignment_id)
    // {
    //     $q = $this->db->query("SELECT * FROM `audit_uncategorized_account` WHERE `assignment_id` = " . $assignment_id . " ORDER BY order_by");

    //     $return_array = [];

    //     if($q->num_rows() > 0)
    //     {
    //         $return_array = $q->result_array();
    //     }
    //     else
    //     {   
    //         // for case like if all account is categorized.
    //         $q2 = $this->db->query("SELECT * FROM audit_categorized_account WHERE assignment_id=" . $assignment_id . ' AND type = "leaf" ORDER BY order_by');

    //         if($q2->num_rows() > 0)
    //         {
    //             return [];
    //         }
    //         else
    //         {
    //             $trial_balance_data = $this->db->query("SELECT * FROM audit_trial_balance WHERE assignment_id=" . $assignment_id . " ORDER BY order_by");
    //             $trial_balance_data = $trial_balance_data->result_array();

    //             $sorted_trial_balance_data = $trial_balance_data;

    //             $last_ye_assignment_id = $this->caf_model->get_company_info_last_year($assignment_id);

    //             // print_r(array($last_ye_assignment_id));

    //             if(!empty($last_ye_assignment_id))  // if have previous year report
    //             {
    //                 $fs_categorized_account_list_last_ye = $this->db->query("SELECT * FROM audit_categorized_account WHERE assignment_id=" . $last_ye_assignment_id . " ORDER BY order_by");
    //                 $fs_categorized_account_list_last_ye = $fs_categorized_account_list_last_ye->result_array();

    //                 if(count($fs_categorized_account_list_last_ye) > 0)
    //                 {
    //                     foreach ($trial_balance_data as $trial_bl_key => $trial_bl_value) 
    //                     {
    //                         $matched = false;

    //                         foreach ($fs_categorized_account_list_last_ye as $fca_key => $fca_value) 
    //                         {
    //                             if($trial_bl_value['description'] == $fca_value['description'])
    //                             {
    //                                 $matched = true;
    //                             }
    //                         }

    //                         if($matched)
    //                         {
    //                             unset($sorted_trial_balance_data[$trial_bl_key]);
    //                         }
    //                     }
    //                 }

    //                 $return_array = $sorted_trial_balance_data;
    //             }
    //             else // if first report / no previous year record.
    //             {
    //                 $ly_trial_balance_data = $this->db->query("SELECT * FROM audit_ly_trial_balance WHERE assignment_id=" . $assignment_id . " ORDER BY order_by");
    //                 $ly_trial_balance_data = $ly_trial_balance_data->result_array();

    //                 if(count($sorted_trial_balance_data) > 0)
    //                 {
    //                     $tb_desc = array_column($sorted_trial_balance_data, 'description');

    //                     foreach ($ly_trial_balance_data as $key => $value) 
    //                     {
    //                         if(in_array($value['description'], $tb_desc))
    //                         {
    //                             $tb_desc_key = array_search($value['description'], $tb_desc);

    //                             $sorted_trial_balance_data[$tb_desc_key]['company_end_prev_ye_value'] = $value['value'];
    //                         }
    //                     }

    //                     $return_array = $sorted_trial_balance_data;
    //                 }
    //                 else
    //                 {
    //                     if(count($ly_trial_balance_data) > 0)
    //                     {
    //                         $return_array = $ly_trial_balance_data;
    //                         $swap_a = array_column($return_array, 'value');
    //                         $swap_b = array_column($return_array, 'company_end_prev_ye_value');

    //                         foreach ($return_array as $key => $value) 
    //                         {
    //                             $return_array[$key]['company_end_prev_ye_value'] = $value['value'];
    //                             $return_array[$key]['value'] = 0.00;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     foreach ($return_array as $key => $value) 
    //     {
    //         $return_array[$key]['value'] = $this->negative_bracket($value['value']); 
    //         $return_array[$key]['company_end_prev_ye_value'] = $this->negative_bracket($value['company_end_prev_ye_value']); 
    //     }

    //     return $return_array;
    // }

    public function get_create_uncategorizedData($assignment_id)
    {
        $q = $this->db->query("SELECT * FROM `audit_uncategorized_account` WHERE `assignment_id` = " . $assignment_id . " ORDER BY order_by");

        $return_array = [];

        if($q->num_rows() > 0)
        {
            $return_array = $q->result_array();
        }
        else
        {   
            // for case like if all account is categorized.
            $q2 = $this->db->query("SELECT * FROM audit_categorized_account WHERE assignment_id=" . $assignment_id . ' AND type = "leaf" ORDER BY order_by');

            if($q2->num_rows() > 0)
            {
                return [];
            }
            else
            {
                //for case which has no categorized and uncategorized account -- means havent save, only upload trial balance
                $trial_balance_data = $this->db->query("SELECT * FROM audit_trial_balance WHERE assignment_id=" . $assignment_id . " ORDER BY order_by");
                $trial_balance_data = $trial_balance_data->result_array();

                $sorted_trial_balance_data = $trial_balance_data;

                $last_ye_company_info_id = $this->caf_model->get_company_info_last_year($assignment_id);

                // print_r(array($last_ye_company_info_id));
                $audit_categorized_account_list_last_ye = $this->db->query("SELECT * FROM audit_categorized_account WHERE assignment_id=" . $last_ye_company_info_id . " ORDER BY order_by");
                $audit_categorized_account_list_last_ye = $audit_categorized_account_list_last_ye->result_array();

                if(!empty($last_ye_company_info_id) && count($audit_categorized_account_list_last_ye) > 0)  // if have previous year report
                {
                    
                    // to check if trial balance exist in categorised account before, remove it from the categorized interface
                    // $audit_categorized_account_list_last_ye = $this->db->query("SELECT * FROM audit_categorized_account WHERE assignment_id=" . $last_ye_company_info_id . " ORDER BY order_by");
                    // $audit_categorized_account_list_last_ye = $audit_categorized_account_list_last_ye->result_array();

                    if(count($audit_categorized_account_list_last_ye) > 0)
                    {
                        foreach ($trial_balance_data as $trial_bl_key => $trial_bl_value) 
                        {
                            $matched = false;

                            foreach ($audit_categorized_account_list_last_ye as $aca_key => $aca_value) 
                            {
                                if($trial_bl_value['description'] == $aca_value['description'])
                                {
                                    $matched = true;
                                }
                            }

                            if($matched)
                            {
                                unset($sorted_trial_balance_data[$trial_bl_key]);
                            }
                        }
                    }

                    $return_array = $sorted_trial_balance_data;
                }
                else // if first report / no previous year record.
                {
                    $ly_trial_balance_data = $this->db->query("SELECT * FROM audit_ly_trial_balance WHERE assignment_id=" . $assignment_id . " ORDER BY order_by");
                    $ly_trial_balance_data = $ly_trial_balance_data->result_array();

                    if(count($sorted_trial_balance_data) > 0) // this year trial balance
                    {
                        $tb_desc = array_column($sorted_trial_balance_data, 'description');

                        foreach ($ly_trial_balance_data as $key => $value) 
                        {
                            if(in_array($value['description'], $tb_desc))
                            {
                                $tb_desc_key = array_search($value['description'], $tb_desc);

                                $sorted_trial_balance_data[$tb_desc_key]['company_end_prev_ye_value'] = $value['value'];
                            }
                            else
                            {
                                $value['company_end_prev_ye_value'] = $value['value'];
                                $value['value'] = 0.00;
                                array_push($sorted_trial_balance_data, $value);
                            }
                        }

                        $return_array = $sorted_trial_balance_data;
                    }
                    else //only has LY trial balance
                    {

                        if(count($ly_trial_balance_data) > 0)
                        {
                            $return_array = $ly_trial_balance_data;
                            $swap_a = array_column($return_array, 'value');
                            $swap_b = array_column($return_array, 'company_end_prev_ye_value');

                            foreach ($return_array as $key => $value) 
                            {
                                $return_array[$key]['company_end_prev_ye_value'] = $value['value'];
                                $return_array[$key]['value'] = 0.00;
                            }
                        }
                    }
                }
            }
        }

        foreach ($return_array as $key => $value) 
        {
            $return_array[$key]['value'] = $this->negative_bracket($value['value']); 
            $return_array[$key]['company_end_prev_ye_value'] = $this->negative_bracket($value['company_end_prev_ye_value']); 
        }

        return $return_array;
    }

    public function insert_categorized_account_round_off($fs_company_info_id) 
    {
        $fs_categorized_account = $this->db->query("SELECT * FROM audit_categorized_account fca WHERE fca.fs_company_info_id = " . $fs_company_info_id);
        $fs_categorized_account = $fs_categorized_account->result_array();

        $fs_db_categorized_account_round_off = $this->db->query("SELECT * FROM fs_categorized_account_round_off fca WHERE fca.fs_company_info_id = " . $fs_company_info_id);
        $fs_db_categorized_account_round_off = $fs_db_categorized_account_round_off->result_array();
        $delete_fcaro_ids = [];

        foreach ($fs_db_categorized_account_round_off as $fcaro_key => $fcaro_value) 
        {   
            if(empty(in_array($fcaro_value['fs_categorized_account_id'], array_column($fs_categorized_account, 'id'))))
            {
                array_push($delete_fcaro_ids, $fcaro_value['id']);
            }
        }

        if(count($delete_fcaro_ids) > 0)
        {
            $this->db->where_in('id', $delete_fcaro_ids);
            $this->db->delete('fs_categorized_account_round_off');

            $this->db->where_in('fs_categorized_account_round_off_id', $delete_fcaro_ids);
            $this->db->delete('fs_note_details');
        }

        $fs_categorized_account_round_off = [];

        // print_r($data);

        foreach ($fs_categorized_account as $key => $value) 
        {
            $temp_array = [];

            $temp_array = array(
                'fs_categorized_account_id' => $value['id'],
                'fs_company_info_id'        => $fs_company_info_id,
                'description'               => $value['description'],
                // 'account_code'              => $value['account_code'],
                // 'parent'                    => $value['parent'],
                // 'type'                      => $value['type'],
                // 'order_by'                  => $value['order_by'],
                'value'                     => $value['adjusted_value'],
                'company_end_prev_ye_value' => $value['company_end_prev_ye_value'],
                'company_beg_prev_ye_value' => $value['company_beg_prev_ye_value'],
                // 'group_end_this_ye_value'   => $value['group_end_this_ye_value'],
                // 'group_end_prev_ye_value'   => $value['group_end_prev_ye_value'],
                // 'group_beg_prev_ye_value'   => $value['group_beg_prev_ye_value'],
                'is_deleted'                => 0
            );

            $q = $this->db->query("SELECT * 
                                    FROM fs_categorized_account_round_off fca_ro 
                                    WHERE fca_ro.fs_categorized_account_id =" . $value['id'] . " AND fca_ro.fs_company_info_id=" . $fs_company_info_id . " AND fca_ro.is_deleted = 0");
            $q = $q->result_array();

            if(count($q) > 0)
            {
                $this->db->where('id', $q[0]['id']);
                $result = $this->db->update('fs_categorized_account_round_off', $temp_array);
            }
            else
            {
                $result = $this->db->insert('fs_categorized_account_round_off', $temp_array);
            }
        }

        // return $result;
    }

    // public function fs_adjust_round_off($fs_company_info_id) // adjust this year and last year value only, because group value is key in with whole number.
    // {
    //     $data = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`
    //                                 FROM fs_categorized_account_round_off fcaro
    //                                 LEFT JOIN audit_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
    //                                 LEFT JOIN fs_default_acc_category fdac ON fdac.id = fca.fs_default_acc_category_id
    //                                 WHERE fcaro.fs_company_info_id=" . $fs_company_info_id . " ORDER BY fca.order_by");
    //     $data = $data->result_array();

    //     $fs_ly_trial_balance = $this->db->query("SELECT * FROM audit_ly_trial_balance WHERE fs_company_info_id=" . $fs_company_info_id);
    //     $fs_ly_trial_balance = $fs_ly_trial_balance->result_array();

    //     if(count($data) > 0)
    //     {
    //         $parent_account_keys = array_keys(array_column($data, 'parent'), "#");
    //         $parent_account_ids = [];

    //         // get all account with parent '#' id (level 1)
    //         foreach ($parent_account_keys as $key => $value) 
    //         {
    //             array_push($parent_account_ids, $data[$value]['fca_id']);
    //         }

    //         /* ----------- calculate sum of parent after round off ----------- */
    //         $total_account_parent = array(
    //                                     'total_c'     => [],
    //                                     'total_c_lye' => []
    //                                 );

    //         $total_account_parent_ori = array(
    //                                     'total_c'     => [],
    //                                     'total_c_lye' => []
    //                                 );
    //         $all_account_ori = [];

    //         foreach ($parent_account_ids as $pa_key1 => $item_id1) 
    //         {
    //             $account1 = $this->get_account_with_sub_round_off_ids($fs_company_info_id, array($item_id1));
    //             $account_parent1 = $account1[0]['parent_array'][0];    // get ['total_c'] / ['total_c_lye'] to get total

    //             array_push($total_account_parent['total_c'], round($account_parent1['total_c']));
    //             array_push($total_account_parent['total_c_lye'], round($account_parent1['total_c_lye']));

    //             array_push($total_account_parent_ori['total_c'], $account_parent1['total_c']);
    //             array_push($total_account_parent_ori['total_c_lye'], $account_parent1['total_c_lye']);

    //             array_push($all_account_ori, $account1);
    //         }

    //         $sum_of_round_off_account_parent     = array_sum($total_account_parent['total_c']);
    //         $sum_of_round_off_account_parent_lye = array_sum($total_account_parent['total_c_lye']);

    //         /* ----------- END OF calculate sum of parent after round off ----------- */

    //          /* ----------- Adjust round off for the sum of main parent (top layer) ----------- */
    //         $array1 = [];
    //         array_push($array1, []);

    //         $all_account = [];

    //         // rebuild array for this year
    //         // print_r(array($total_account_parent_ori['total_c'], $total_account_parent_ori['total_c_lye']));

    //         foreach ($total_account_parent_ori['total_c'] as $key1 => $value1) 
    //         {
    //             $array1[$key1]['total_c'] = $value1;
    //             // array_push($array1[0], array('total_c' => $value1));
    //         }

    //         // rebuild array for last year
    //         foreach ($total_account_parent_ori['total_c_lye'] as $key1 => $value1) 
    //         {
    //             // array_push($array1[0], array('total_c_lye' => $value1));
    //             $array1[$key1]['total_c_lye'] = $value1;
    //         }

    //         if($sum_of_round_off_account_parent != 0)   // if after round off total is not 0.
    //         {
    //             $temp_total_c = $this->adjust_child_lowest_highest_decimal($array1, $sum_of_round_off_account_parent - 0, 'total_c');   // adjust round off for sum of each main parents

    //             if(count($fs_ly_trial_balance) > 0)
    //             {
    //                 $temp_total_c_lye = $this->adjust_child_lowest_highest_decimal($array1, $sum_of_round_off_account_parent_lye - 0, 'total_c_lye');   // adjust round off for sum of each main parents
    //             }

    //             $temp_total_c = $this->adjust_child_lowest_highest_decimal($array1, $sum_of_round_off_account_parent_lye - 0, 'total_c_lye');   // adjust round off for sum of each main parents

    //             // update total values
    //             foreach ($parent_account_ids as $pa_key2 => $item_id2) 
    //             {
    //                 $account1 = $this->get_account_with_sub_round_off_ids($fs_company_info_id, array($item_id2));
    //                 $account_parent1 = $account1[0]['parent_array'][0];    // get ['total_c'] / ['total_c_lye'] to get total

    //                 $account1[0]['parent_array'][0]['total_c']      = $temp_total_c[$pa_key2]['total_c'];
    //                 $account1[0]['parent_array'][0]['total_c_lye']  = $temp_total_c[$pa_key2]['total_c_lye'];

    //                 array_push($all_account, $account1);
    //             }
    //         }
    //         else
    //         {
    //             $all_account = $all_account_ori;
    //         }
    //         /* ----------- Adjust round off for the sum of main parent (top layer) ----------- */
    //     }
        
    //     foreach ($parent_account_ids as $pa_key => $item_id) 
    //     {
    //         $account = $all_account[$pa_key];
    //         $tempaccount = $this->get_account_with_sub_round_off_ids($fs_company_info_id, array($item_id));

    //         $account_parent = $account[0]['parent_array'][0];    // get ['total_c'] / ['total_c_lye'] to get total
    //         $account_children_list = [];

    //         // store all child in $account_children_list
    //         foreach ($account as $a_key => $a_value) 
    //         {
    //             array_push($account_children_list, $this->child_recursive(array($a_value), []));
    //         }

    //         $value_round_off            = [];
    //         $value_round_off_ly_com     = [];
    //         $value_round_off_ty_group   = [];
    //         $value_round_off_ly_group   = [];

    //         // round off child of account
    //         foreach ($account_children_list[0] as $acl_key1 => $acl_value1) 
    //         {
    //             array_push($value_round_off, round($acl_value1['value']));
    //             array_push($value_round_off_ly_com, round($acl_value1['company_end_prev_ye_value']));
    //             // array_push($value_round_off_ty_group, round($acl_value1['group_end_this_ye_value']));
    //             // array_push($value_round_off_ly_group, round($acl_value1['group_end_prev_ye_value']));
    //         }

    //         // if($account_parent['total_c'] != array_sum($value_round_off) || $account_parent['total_c_lye'] != array_sum($value_round_off))

    //         if($total_account_parent['total_c'][$pa_key] != array_sum($value_round_off) || $total_account_parent['total_c_lye'] != array_sum($value_round_off_ly_com))
    //         {
    //             // $differences            = array_sum($value_round_off) - $account_parent['total_c'];
    //             // $differences_ly_com     = array_sum($value_round_off_ly_com) - $account_parent['total_c_lye'];
    //             // $differences_ty_group   = array_sum($value_round_off_ty_group) - $account_parent['group_end_this_ye_value'];
    //             // $differences_ly_group   = array_sum($value_round_off_ly_group) - $account_parent['group_end_prev_ye_value'];

    //             $differences        = array_sum($value_round_off) - $total_account_parent['total_c'][$pa_key];
    //             $differences_ly_com = array_sum($value_round_off_ly_com) - $total_account_parent['total_c_lye'][$pa_key];

    //             // adjust the value so that can match the total after round off.
    //             $account_children_list = $this->adjust_child_lowest_highest_decimal($account_children_list, $differences, 'value');

    //             if(count($fs_ly_trial_balance) > 0)
    //             {
    //                 // for last year trial balance is uploaded. If carry forward from last year, the values are rounded off.
    //                 $account_children_list = $this->adjust_child_lowest_highest_decimal($account_children_list, $differences_ly_com, 'company_end_prev_ye_value');
    //             }

    //             // $account_children_list = $this->adjust_child_lowest_highest_decimal($account_children_list, $differences_ty_group, 'group_end_this_ye_value');
    //             // $account_children_list = $this->adjust_child_lowest_highest_decimal($account_children_list, $differences_ly_group, 'group_end_prev_ye_value');
    //         }

    //         $account_children_list = $this->round_off_all_child($account_children_list);

    //         $acl_data = [];

    //         foreach ($account_children_list[0] as $acl_key => $acl_value) 
    //         {
    //             unset($acl_value['type']);
    //             unset($acl_value['parent']);
    //             unset($acl_value['fca_id']);
    //             unset($acl_value['type']);
    //             unset($acl_value['account_code']);

    //             array_push($acl_data, $acl_value);
    //         }

    //         $update_result = $this->update_categorized_account_round_off_batch(array($acl_data)); 
    //     }

    //     // return $account_children_list;
    // }

    public function get_company_info_last_year($assignment_id)
    {
        $fs_company_info         = $this->getAssignmentDetail($assignment_id);
        $fs_company_info_this_ye = $this->getAssignmentDetail($assignment_id);

        // $fs_company_info_last_ye = $this->db->query("SELECT * FROM payroll_assignment WHERE client_id='" . $fs_company_info_this_ye->client_id . "' AND id <> '" . $assignment_id . "' AND firm_id=" . $fs_company_info->firm_id. " AND type_of_job=" . $fs_company_info->type_of_job);
        $fs_company_info_last_ye = $this->db->query("SELECT * FROM payroll_assignment WHERE client_id='" . $fs_company_info_this_ye->client_id . "' AND id <> '" . $assignment_id . "' AND type_of_job=" . $fs_company_info->type_of_job);


        $fs_company_info_last_ye = $fs_company_info_last_ye->result_array();

        $last_ye_assignment_id = 0;

        if(count($fs_company_info_last_ye) > 0)
        {
            $temp_target_ye = $fs_company_info_this_ye->FYE;
            $shortest_day = 1000000; // set initial for more days to prevent taking wrong date.

            foreach ($fs_company_info_last_ye as $key => $value)
            {
                $startTimeStamp = new DateTime(date('Y-m-d', strtotime($value['FYE'])));
                $startTimeStamp  = $startTimeStamp->format('Y-m-d');
                $startTimeStamp = date_create($startTimeStamp);

                $endTimeStamp = new DateTime(date('Y-m-d', strtotime($temp_target_ye)));
                $endTimeStamp  = $endTimeStamp->format('Y-m-d');
                $endTimeStamp = date_create($endTimeStamp);

                // echo $startTimeStamp."---------".$endTimeStamp;

                if($startTimeStamp < $endTimeStamp)
                {
                    $temp_shortest_day = $this->fs_model->compare_date_latest($temp_target_ye, $value['FYE'], $shortest_day);
                
                    if((int)$shortest_day > 0)
                    {
                        if((int)$shortest_day > (int)$temp_shortest_day)
                        {
                            $last_ye_assignment_id = $value['id'];
                            $shortest_day = $temp_shortest_day;
                        }
                    }
                }
            }
        }

        return $last_ye_assignment_id;
    }

    public function get_fs_company_info_last_year($fs_company_info_id)
    {
        $fs_company_info         = $this->get_fs_company_info($fs_company_info_id);
        $fs_company_info_this_ye = $this->get_fs_company_info($fs_company_info_id);

        $fs_company_info_last_ye = $this->db->query("SELECT * FROM fs_company_info WHERE company_code='" . $fs_company_info_this_ye[0]['company_code'] . "' AND id <> '" . $fs_company_info_id . "' AND firm_id=" . $fs_company_info[0]['firm_id']);
        $fs_company_info_last_ye = $fs_company_info_last_ye->result_array();

        $last_ye_fs_company_info_id = 0;

        if(count($fs_company_info_last_ye) > 0)
        {
            $temp_target_ye = $fs_company_info_this_ye[0]['current_fye_end'];
            $shortest_day = 1000000; // set initial for more days to prevent taking wrong date.

            foreach ($fs_company_info_last_ye as $key => $value)
            {
                $startTimeStamp = new DateTime(date('Y-m-d', strtotime($value['current_fye_end'])));
                $startTimeStamp  = $startTimeStamp->format('Y-m-d');
                $startTimeStamp = date_create($startTimeStamp);

                $endTimeStamp = new DateTime(date('Y-m-d', strtotime($temp_target_ye)));
                $endTimeStamp  = $endTimeStamp->format('Y-m-d');
                $endTimeStamp = date_create($endTimeStamp);

                if($startTimeStamp < $endTimeStamp)
                {
                    $temp_shortest_day = $this->caf_model->compare_date_latest($temp_target_ye, $value['current_fye_end'], $shortest_day);
                
                    if((int)$shortest_day > 0)
                    {
                        if((int)$shortest_day > (int)$temp_shortest_day)
                        {
                            $last_ye_fs_company_info_id = $value['id'];
                            $shortest_day = $temp_shortest_day;
                        }
                    }
                }
            }
        }

        return $last_ye_fs_company_info_id;
    }

    // public function get_company_info_next_year($assignment_id)
    // {
    //     // $fs_company_info         = $this->getAssignmentDetail($assignment_id);
    //     $company_info_this_ye = $this->getAssignmentDetail($assignment_id);

    //     // $fs_company_info_this_ye = $this->get_fs_company_info($fs_company_info_id);

    //     $company_info_other_ye = $this->db->query("SELECT * FROM payroll_assignment WHERE client_id='" . $company_info_this_ye->client_id . "' AND id <> '" . $assignment_id . "' AND firm_id=" . $company_info_this_ye->firm_id. " AND type_of_job=" . $company_info_this_ye->type_of_job);

    //     // echo ("SELECT * FROM payroll_assignment WHERE client_id='" . $company_info_this_ye->client_id . "' AND id <> '" . $assignment_id . "' AND firm_id=" . $company_info_this_ye->firm_id. " AND type_of_job=" . $company_info_this_ye->type_of_job);

    //     $company_info_other_ye = $company_info_other_ye->result_array();

    //     $next_ye_company_info_id = 0;

    //     if(count($company_info_other_ye) > 0)
    //     {
    //         $temp_target_ye = $company_info_this_ye->FYE;
    //         $shortest_day = 367; // set initial for more days to prevent taking wrong date.

    //         foreach ($company_info_other_ye as $key => $value)
    //         {
    //             $startTimeStamp = new DateTime(date('Y-m-d', strtotime($value['FYE'])));
    //             $startTimeStamp  = $startTimeStamp->format('Y-m-d');
    //             $startTimeStamp = date_create($startTimeStamp);

    //             $endTimeStamp = new DateTime(date('Y-m-d', strtotime($temp_target_ye)));
    //             $endTimeStamp  = $endTimeStamp->format('Y-m-d');
    //             $endTimeStamp = date_create($endTimeStamp);

    //             if($startTimeStamp > $endTimeStamp) // if next year date is latest than this year end
    //             {
    //                 $temp_shortest_day = $this->compare_date_latest($value['FYE'], $temp_target_ye, $shortest_day);

    //                 if((int)$temp_shortest_day > 0)
    //                 {
    //                     if((int)$shortest_day > (int)$temp_shortest_day)
    //                     {
    //                         $next_ye_fs_company_info_id = $value['id'];
    //                         $shortest_day = $temp_shortest_day;
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     if(isset($next_ye_fs_company_info_id))
    //     {
    //         return $next_ye_fs_company_info_id;
    //     }
    //     else
    //     {
    //         return "";
    //     }

     
    // }

    public function get_company_info_next_year($fs_company_info_id)
    {
        $fs_company_info_this_ye = $this->get_fs_company_info($fs_company_info_id);

        $fs_company_info_other_ye = $this->db->query("SELECT * FROM fs_company_info WHERE company_code='" . $fs_company_info_this_ye[0]['company_code'] . "' AND id <> '" . $fs_company_info_id . "' AND firm_id=" . $fs_company_info_this_ye[0]['firm_id']);
        $fs_company_info_other_ye = $fs_company_info_other_ye->result_array();

        $next_ye_fs_company_info_id = 0;

        if(count($fs_company_info_other_ye) > 0)
        {
            $temp_target_ye = $fs_company_info_this_ye[0]['current_fye_end'];
            $shortest_day = 367; // set initial for more days to prevent taking wrong date.

            foreach ($fs_company_info_other_ye as $key => $value)
            {
                $startTimeStamp = new DateTime(date('Y-m-d', strtotime($value['current_fye_end'])));
                $startTimeStamp = $startTimeStamp->format('Y-m-d');
                $startTimeStamp = date_create($startTimeStamp);

                $endTimeStamp = new DateTime(date('Y-m-d', strtotime($temp_target_ye)));
                $endTimeStamp = $endTimeStamp->format('Y-m-d');
                $endTimeStamp = date_create($endTimeStamp);

                if($startTimeStamp > $endTimeStamp) // if next year date is latest than this year end
                {
                    $temp_shortest_day = $this->fs_model->compare_date_latest($value['current_fye_end'], $temp_target_ye, $shortest_day);

                    if((int)$temp_shortest_day > 0)
                    {
                        if((int)$shortest_day > (int)$temp_shortest_day)
                        {
                        $next_ye_fs_company_info_id = $value['id'];
                        $shortest_day = $temp_shortest_day;
                        }
                    }
                }
            }
        }

        return $next_ye_fs_company_info_id;
    }

    public function get_fs_company_info_next_year($fs_company_info_id)
    {
        $fs_company_info_this_ye = $this->get_fs_company_info($fs_company_info_id);

        $fs_company_info_other_ye = $this->db->query("SELECT * FROM fs_company_info WHERE company_code='" . $fs_company_info_this_ye[0]['company_code'] . "' AND id <> '" . $fs_company_info_id . "' AND firm_id=" . $fs_company_info_this_ye[0]['firm_id']);
        $fs_company_info_other_ye = $fs_company_info_other_ye->result_array();

        $next_ye_fs_company_info_id = 0;

        if(count($fs_company_info_other_ye) > 0)
        {
            $temp_target_ye = $fs_company_info_this_ye[0]['current_fye_end'];
            $shortest_day = 367; // set initial for more days to prevent taking wrong date.

            foreach ($fs_company_info_other_ye as $key => $value)
            {
                $startTimeStamp = new DateTime(date('Y-m-d', strtotime($value['current_fye_end'])));
                $startTimeStamp  = $startTimeStamp->format('Y-m-d');
                $startTimeStamp = date_create($startTimeStamp);

                $endTimeStamp = new DateTime(date('Y-m-d', strtotime($temp_target_ye)));
                $endTimeStamp  = $endTimeStamp->format('Y-m-d');
                $endTimeStamp = date_create($endTimeStamp);

                if($startTimeStamp > $endTimeStamp) // if next year date is latest than this year end
                {
                    $temp_shortest_day = $this->fs_model->compare_date_latest($value['current_fye_end'], $temp_target_ye, $shortest_day);

                    if((int)$temp_shortest_day > 0)
                    {
                        if((int)$shortest_day > (int)$temp_shortest_day)
                        {
                            $next_ye_fs_company_info_id = $value['id'];
                            $shortest_day = $temp_shortest_day;
                        }
                    }
                }
            }
        }

        return $next_ye_fs_company_info_id;
    }


    // public function get_categorizedData_or_default($assignment_id)
    // {
    //     $q = $this->db->query("SELECT aca.*, fdac.account_code AS `default_account_code`
    //                             FROM audit_categorized_account aca
    //                             LEFT JOIN fs_default_acc_category fdac ON fdac.id = aca.fs_default_acc_category_id
    //                             WHERE aca.assignment_id=" . $assignment_id . " ORDER BY aca.order_by");

    //     if($q->num_rows() > 0)
    //     {
    //         $data = [];

    //         // print_r($q->result_array());

    //         foreach ($q->result_array() as $key => $item) 
    //         {
    //             $value = '';
    //             $id = '';
    //             $account_code = '';

    //             if($item['account_code'] == '')
    //             {
    //                 $value = $item['value'];
    //                 // $id = $key;
    //             }
    //             else
    //             {
    //                 // $id = $item['default_account_code'];  
    //                 // $id = $item['account_code'];    
    //             }

    //             if($item['fs_default_acc_category_id'] != 0)
    //             {
    //                 $account_code = $item['default_account_code'];
    //                 // $account_code = $item['account_code'];
    //             }
                

    //             if(empty($value))
    //             {
    //                 $value = 0;
    //             }

    //             // array_push($data,
    //             //     array(
    //             //         "id"     => $id, 
    //             //         "parent" => $item["parent"], 
    //             //         "text"   => $item["description"],
    //             //         'type'   => $item["type"],
    //             //         'data'   => array(
    //             //                         account_code => $account_code,
    //             //                         value => number_format($value, 2)
    //             //                     )
    //             //     ));

    //             array_push($data,
    //                 array(
    //                     "id"     => $item["id"], 
    //                     "parent" => $item["parent"], 
    //                     "text"   => $item["description"],
    //                     'type'   => $item["type"],
    //                     'data'   => array(
    //                                     'account_code' => $account_code,
    //                                     'value' => $this->negative_bracket($value),
    //                                     'company_end_prev_ye_value' => $this->negative_bracket($item['company_end_prev_ye_value']),
    //                                     'fs_default_acc_category_id' => $item['fs_default_acc_category_id'],
    //                                     'id' => $item['id']
    //                                 )
    //                 ));
    //         }

    //         return $data;
    //     }
    //     else
    //     {   
    //         $data = [];

    //         $fs_company_info_this_ye = $this->fs_model->get_fs_company_info($assignment_id);
    //         $fs_this_ye_company_code = $fs_company_info_this_ye[0]['company_code']; 

    //         $fs_company_info_last_ye = $this->db->query("SELECT * FROM fs_company_info WHERE company_code='" . $fs_this_ye_company_code . "' AND id <> '" . $assignment_id . "'");
    //         $fs_company_info_last_ye = $fs_company_info_last_ye->result_array();

    //         if(count($fs_company_info_last_ye) > 0)
    //         {
    //             // $temp_target_ye = $fs_company_info_this_ye[0]['current_fye_end'];
    //             // $shortest_day = 1000000; // set initial for more days to prevent taking wrong date.
    //             // $last_ye_assignment_id = 0;

    //             // foreach ($fs_company_info_last_ye as $key => $value)
    //             // {
    //             //     $temp_shortest_day = $this->fs_model->compare_date_latest($temp_target_ye, $value['current_fye_end'], $shortest_day);
                    
    //             //     if((int)$shortest_day > (int)$temp_shortest_day)
    //             //     {
    //             //         $last_ye_assignment_id = $value['id'];
    //             //         $shortest_day = $temp_shortest_day;
    //             //     }
    //             // }

    //             $last_ye_assignment_id = $this->get_fs_company_info_last_year($assignment_id);

    //             // if last year end is exists, update the value
    //             if($last_ye_assignment_id != 0)
    //             {
    //                 $fs_categorized_account_list_last_ye = $this->db->query("SELECT * FROM fs_categorized_account WHERE assignment_id=" . $last_ye_assignment_id . " ORDER BY order_by");
    //                 $fs_categorized_account_list_last_ye = $fs_categorized_account_list_last_ye->result_array();

    //                 if(count($fs_categorized_account_list_last_ye) > 0)
    //                 {
    //                     $delete_trial_bl_account = [];

    //                     foreach ($fs_categorized_account_list_last_ye as $key => $item) 
    //                     {
    //                         $this_year_value = '';
    //                         $last_year_value = '';
    //                         $id = '';
    //                         $inserted = false;

    //                         if($item['account_code'] == '')
    //                         {
    //                             $value = $item['value'];
    //                             $last_year_value = str_replace(',', '', $item['value']);
    //                         }

    //                         // if(!empty($item['fs_default_acc_category_id']))
    //                         // {
    //                         //     // $account_code = $item['default_account_code'];
    //                         //     $account_code = $item['account_code'];
    //                         // }

    //                         // if($item['account_code'] == '')
    //                         // {
    //                         //     $last_year_value = str_replace(',', '', $item['value']);
    //                         //     $id = $key;
    //                         // }
    //                         // else
    //                         // {
    //                         //     $id = $item['account_code'];
    //                         // }

    //                         // $account_code = $item['account_code'];

    //                         $trial_balance_data = $this->db->query("SELECT * FROM fs_trial_balance WHERE assignment_id=" . $assignment_id);
    //                         $trial_balance_data = $trial_balance_data->result_array();

    //                         foreach ($trial_balance_data as $trial_bl_key => $trial_bl_value) 
    //                         {
    //                             if($trial_bl_value['description'] == $item["description"])
    //                             {
    //                                 $this_year_value = $trial_bl_value['value'];

    //                                 array_push($delete_trial_bl_account, $trial_bl_value['id']);

    //                                 if(empty($last_year_value))
    //                                 {
    //                                     $last_year_value = 0;
    //                                 }

    //                                 if(empty($this_year_value) || $item["type"] == "Branch")
    //                                 {
    //                                     $this_year_value = 0;
    //                                 }

    //                                 array_push($data,
    //                                     array(
    //                                         "id"     => $item['id'], 
    //                                         "parent" => $item["parent"], 
    //                                         "text"   => $item["description"],
    //                                         'type'   => $item["type"],
    //                                         'data'   => array(
    //                                                         'account_code' => $item['account_code'],
    //                                                         'value' => $this->negative_bracket($this_year_value),
    //                                                         'company_end_prev_ye_value' => $this->negative_bracket($last_year_value),
    //                                                         'fs_default_acc_category_id' => $item['fs_default_acc_category_id'],
    //                                                         'id' => ''
    //                                                     )
    //                                 ));
    //                                 $inserted = true;
    //                             }
    //                         }
                            
    //                         if(empty($last_year_value))
    //                         {
    //                             $last_year_value = 0;
    //                         }

    //                         if(empty($this_year_value))
    //                         {
    //                             $this_year_value = 0;
    //                         }

    //                         if(!$inserted)
    //                         {
    //                             array_push($data,
    //                                 array(
    //                                     "id"     => $item['id'], 
    //                                     "parent" => $item["parent"], 
    //                                     "text"   => $item["description"],
    //                                     'type'   => $item["type"],
    //                                     'data'   => array(
    //                                                     'account_code' => $item['account_code'],
    //                                                     'value' => $this->negative_bracket($this_year_value),
    //                                                     'company_end_prev_ye_value' => $this->negative_bracket($last_year_value),
    //                                                     'fs_default_acc_category_id' => $item['fs_default_acc_category_id'],
    //                                                     'id' => ''
    //                                                 )
    //                             ));
    //                         }
    //                     }

    //                     // if(count($delete_trial_bl_account) > 0)
    //                     // {
    //                     //     // delete last year exist account
    //                     //     $this->db->where_in('id', $delete_trial_bl_account);
    //                     //     $this->db->delete('fs_trial_balance');
    //                     // }
    //                 }
    //                 else
    //                 {
    //                     $data = $this->set_default_tree();
    //                 }

    //             }
    //         }
    //         else
    //         {
    //             $data = $this->set_default_tree();
    //         }

    //         // print_r($data);

    //         return $data;
    //     }
    // }

    public function get_categorizedData_or_default($assignment_id)
    {
        $q = $this->db->query("SELECT aca.*, fdac.account_code AS `default_account_code`
                                FROM audit_categorized_account aca
                                LEFT JOIN fs_default_acc_category fdac ON fdac.id = aca.fs_default_acc_category_id
                                WHERE aca.assignment_id=" . $assignment_id . " ORDER BY aca.order_by");

        if($q->num_rows() > 0)
        {
            $data = [];

            // print_r($q->result_array());

            foreach ($q->result_array() as $key => $item) 
            {
                $value = '';
                $id = '';
                $account_code = '';

                if($item['account_code'] == '')
                {
                    $value = $item['value'];
                    // $id = $key;
                }
                else
                {
                    // $id = $item['default_account_code'];  
                    // $id = $item['account_code'];    
                }

                if($item['fs_default_acc_category_id'] != 0)
                {
                    $account_code = $item['default_account_code'];
                    // $account_code = $item['account_code'];
                }
                

                if(empty($value))
                {
                    $value = 0;
                }

                // array_push($data,
                //     array(
                //         "id"     => $id, 
                //         "parent" => $item["parent"], 
                //         "text"   => $item["description"],
                //         'type'   => $item["type"],
                //         'data'   => array(
                //                         account_code => $account_code,
                //                         value => number_format($value, 2)
                //                     )
                //     ));

                array_push($data,
                    array(
                        "id"     => $item["id"], 
                        "parent" => $item["parent"], 
                        "text"   => $item["description"],
                        'type'   => $item["type"],
                        'data'   => array(
                                        'account_code' => $account_code,
                                        'value' => $this->negative_bracket($value),
                                        'company_end_prev_ye_value' => $this->negative_bracket($item['company_end_prev_ye_value']),
                                        'fs_default_acc_category_id' => $item['fs_default_acc_category_id'],
                                        'id' => $item['id']
                                    )
                    ));
            }

            return $data;
        }
        else
        {   
            $data = [];

            $company_info_this_ye = $this->getAssignmentDetail($assignment_id);
            $this_ye_company_code = $company_info_this_ye->client_id; 

            $last_ye_company_info_id = $this->get_company_info_last_year($assignment_id);

            if($last_ye_company_info_id != 0)
            {
                // $temp_target_ye = $fs_company_info_this_ye[0]['current_fye_end'];
                // $shortest_day = 1000000; // set initial for more days to prevent taking wrong date.
                // $last_ye_fs_company_info_id = 0;

                // foreach ($fs_company_info_last_ye as $key => $value)
                // {
                //     $temp_shortest_day = $this->fs_model->compare_date_latest($temp_target_ye, $value['current_fye_end'], $shortest_day);
                    
                //     if((int)$shortest_day > (int)$temp_shortest_day)
                //     {
                //         $last_ye_fs_company_info_id = $value['id'];
                //         $shortest_day = $temp_shortest_day;
                //     }
                // }

                // $last_ye_fs_company_info_id = $this->get_fs_company_info_last_year($fs_company_info_id);

                // if last year end is exists, update the value
                $audit_categorized_account_list_last_ye = $this->db->query("SELECT * FROM audit_categorized_account WHERE assignment_id=" . $last_ye_company_info_id . " ORDER BY order_by");
                $audit_categorized_account_list_last_ye = $audit_categorized_account_list_last_ye->result_array();

                if(count($audit_categorized_account_list_last_ye) > 0)
                {
                    $delete_trial_bl_account = [];

                    foreach ($audit_categorized_account_list_last_ye as $key => $item) 
                    {
                        $this_year_value = '';
                        $last_year_value = '';
                        $id = '';
                        $inserted = false;

                        if($item['account_code'] == '')
                        {
                            $value = $item['value'];
                            $last_year_value = str_replace(',', '', $item['adjusted_value']);
                        }

                        // if(!empty($item['fs_default_acc_category_id']))
                        // {
                        //     // $account_code = $item['default_account_code'];
                        //     $account_code = $item['account_code'];
                        // }

                        // if($item['account_code'] == '')
                        // {
                        //     $last_year_value = str_replace(',', '', $item['value']);
                        //     $id = $key;
                        // }
                        // else
                        // {
                        //     $id = $item['account_code'];
                        // }

                        // $account_code = $item['account_code'];

                        $trial_balance_data = $this->db->query("SELECT * FROM audit_trial_balance WHERE assignment_id=" . $assignment_id);
                        $trial_balance_data = $trial_balance_data->result_array();

                        foreach ($trial_balance_data as $trial_bl_key => $trial_bl_value) 
                        {
                            // print_r(array($item["description"], $trial_bl_value['description']));
                            if($trial_bl_value['description'] == $item["description"])
                            {
                                $this_year_value = $trial_bl_value['value'];

                                array_push($delete_trial_bl_account, $trial_bl_value['id']);

                                if(empty($last_year_value))
                                {
                                    $last_year_value = 0;
                                }

                                if(empty($this_year_value) || $item["type"] == "Branch")
                                {
                                    $this_year_value = 0;
                                }

                                array_push($data,
                                    array(
                                        "id"     => $item['id'], 
                                        "parent" => $item["parent"], 
                                        "text"   => $item["description"],
                                        'type'   => $item["type"],
                                        'data'   => array(
                                                        'account_code' => $item['account_code'],
                                                        'value' => $this->negative_bracket($this_year_value),
                                                        'company_end_prev_ye_value' => $this->negative_bracket($last_year_value),
                                                        'fs_default_acc_category_id' => $item['fs_default_acc_category_id'],
                                                        'id' => ''
                                                    )
                                ));
                                $inserted = true;
                            }
                        }
                        
                        if(empty($last_year_value))
                        {
                            $last_year_value = 0;
                        }

                        if(empty($this_year_value))
                        {
                            $this_year_value = 0;
                        }

                        if(!$inserted)
                        {
                            array_push($data,
                                array(
                                    "id"     => $item['id'], 
                                    "parent" => $item["parent"], 
                                    "text"   => $item["description"],
                                    'type'   => $item["type"],
                                    'data'   => array(
                                                    'account_code' => $item['account_code'],
                                                    'value' => $this->negative_bracket($this_year_value),
                                                    'company_end_prev_ye_value' => $this->negative_bracket($last_year_value),
                                                    'fs_default_acc_category_id' => $item['fs_default_acc_category_id'],
                                                    'id' => ''
                                                )
                            ));
                        }
                    }

                    // if(count($delete_trial_bl_account) > 0)
                    // {
                    //     // delete last year exist account
                    //     $this->db->where_in('id', $delete_trial_bl_account);
                    //     $this->db->delete('fs_trial_balance');
                    // }
                }
                else
                {
                    $data = $this->set_default_tree();
                }

                
            }
            else
            {
                $data = $this->set_default_tree();
            }

            // print_r($data);

            return $data;
        }
    }

    public function get_fs_company_info($id)
    {
        $q = $this->db->query("SELECT fs_company_info.*, client.company_name, fs_act_applicable_type.name AS `act_applicable_type_name`, fs_accounting_standard.name AS `accounting_standard_used_name`, director_signature_1 AS `director_signature_id_1`, director_signature_2 AS `director_signature_id_2`
                                FROM fs_company_info 
                                LEFT JOIN client ON client.company_code = fs_company_info.company_code
                                LEFT JOIN fs_accounting_standard ON fs_accounting_standard.id = fs_company_info.accounting_standard_used
                                LEFT JOIN fs_act_applicable_type ON fs_act_applicable_type.id = fs_company_info.act_applicable_type
                                WHERE fs_company_info.id='" . $id . "'");
        $q = $q->result_array();

        // update director signature 1 & director signature 1 from id to name
        if($q[0]['director_signature_1'] != 0)
        {
            $director_signature_1 = $this->db->query("SELECT * FROM client_officers co 
                                                    LEFT JOIN officer o ON o.id = co.officer_id
                                                    WHERE co.id =" . $q[0]['director_signature_1']);
            $director_signature_1 = $director_signature_1->result_array();
            $director_signature_1 = $director_signature_1[0]['name'];
        }
        else
        {
            $director_signature_1 = '';
        }

        if($q[0]['director_signature_2'] != 0)
        {
            $director_signature_2 = $this->db->query("SELECT * FROM client_officers co 
                                                    LEFT JOIN officer o ON o.id = co.officer_id
                                                    WHERE co.id =" . $q[0]['director_signature_2']);
            $director_signature_2 = $director_signature_2->result_array();
            $director_signature_2 = $director_signature_2[0]['name'];
        }
        else
        {
            $director_signature_2 = '';
        }

        $q[0]['director_signature_1'] = $director_signature_1;
        $q[0]['director_signature_2'] = $director_signature_2;
        
        foreach ($q as $key => $value) {
            $q[$key]['company_name'] = $this->encryption->decrypt($q[$key]['company_name']);
        }


        return $q;
    }


    public function getReportIdWithAssg($assignment_id)
    {
        // echo $assignment_id;
        $assignment_detail = $this->db->query("SELECT * FROM payroll_assignment WHERE id = ".$assignment_id);
        $assignment_detail = $assignment_detail->result();


        if(count($assignment_detail) > 0){
            $company_code = $assignment_detail[0]->client_id;

            $date_change_format = date("d F Y", strtotime($assignment_detail[0]->FYE));

            $fs_company_info_id_with_assignment_id = $this->db->query("SELECT id, assignment_id from fs_company_info WHERE assignment_id='".$assignment_id."'");
            $fs_company_info_id_with_assignment_id = $fs_company_info_id_with_assignment_id->result_array();

            $fs_company_info_id = $this->db->query("SELECT id, assignment_id from fs_company_info WHERE company_code='".$assignment_detail[0]->client_id."' AND firm_id=". $assignment_detail[0]->firm_id." AND current_fye_end='". $date_change_format."'");
            $fs_company_info_id = $fs_company_info_id->result_array();

            

            // print_r($assignment_detail);

            if(count($fs_company_info_id_with_assignment_id) > 0)
            {
                // print_r("1.".$fs_company_info_id[0]['id']);
                return $fs_company_info_id_with_assignment_id[0]['id'];
            }
            else if(count($fs_company_info_id) > 0 && $fs_company_info_id[0]['assignment_id'] == null)
            {   
                // print_r("1.".$fs_company_info_id[0]['id']);
                return $fs_company_info_id[0]['id'];
            }
            else
            {
                $fs_company_info     = $this->fs_model->get_fs_company_info_by_company_code($assignment_detail[0]->client_id);
                $client_signing_info = $this->fs_model->get_all_client_signing_info($assignment_detail[0]->client_id);

                if(count($fs_company_info) > 0) // if not first
                {
                    // echo  $fs_company_info[0]["current_fye_end"]."===".$date_change_format;
                    // echo $date_change_format;

                    if($fs_company_info[0]["current_fye_end"] != $date_change_format)
                    {
                        $fye_data = $this->fs_model->get_new_FYE_date($assignment_detail[0]->client_id);
                    }
                    else
                    {
                        $fye_data = $fs_company_info[0];
                    //     $fye_data['current_fye_end'] = $date_change_format;
                    }

                    

                    // if(strtotime($fs_company_info[0]["current_fye_end"]) > strtotime($date_change_format))
                    // {
                        
                        

                    // }

                    $fye_data['current_fye_end'] = $date_change_format;
                    $fye_data['last_fye_end'] = "";
                    $fye_data['last_fye_begin'] = "";
                    $fye_data['current_fye_begin'] = "";

                    // print_r($fye_data);


                    $fs_fp_currency_details = $this->fs_model->get_fs_fp_currency_details($fye_data['id']);

                    $fs_report_details[0]['id'] = 0;
                    $fs_report_details          = array($fye_data);

                    // print_r($fs_fp_currency_details);

                    if(isset($client_signing_info) && count($client_signing_info) > 0)
                    {
                        $fs_report_details[0]['director_signature_1'] = $client_signing_info[0]->director_signature_1;
                        $fs_report_details[0]['director_signature_2'] = $client_signing_info[0]->director_signature_2;
                    }
                    else
                    {
                        $fs_report_details[0]['director_signature_1'] = "";
                        $fs_report_details[0]['director_signature_2'] = "";
                    }
                    

                    // check company change name 
                    $company_change_name = $this->db->query("SELECT tccn.company_name, tccn.new_company_name
                                                                FROM transaction_master tm
                                                                LEFT JOIN transaction_change_company_name tccn ON tccn.transaction_id = tm.id
                                                                WHERE tm.transaction_task_id = 12 AND tm.company_code = '" . $company_code . "' AND (tm.created_at BETWEEN '" . $fs_report_details[0]['current_fye_begin'] . "'AND '" . $fs_report_details[0]['current_fye_end'] . "')
                                                                ORDER BY tm.created_at");
                    $company_change_name = $company_change_name->result_array();

                    if(count($company_change_name) > 0)
                    {

                        $old_company_name = $company_change_name[0]['company_name'];
                    }
                    else
                    {
                        $old_company_name = "";
                    }

                    $fs_report_details = array(
                        'id'                    => '',
                        'assignment_id'         => $assignment_id,
                        'firm_id'               => $assignment_detail[0]->firm_id,
                        'company_code'          => $fye_data["company_code"],
                        'company_liquidated'    => $fye_data["company_liquidated"],
                        'old_company_name'      => $old_company_name,
                        'date_of_resolution_for_change_of_name' => $fye_data["date_of_resolution_for_change_of_name"],
                        'first_set'             => $fye_data["first_set"],
                        'last_fye_begin'        => $fye_data['last_fye_begin'],
                        'last_fye_end'          => $fye_data['last_fye_end'],
                        'current_fye_begin'     => $fye_data['current_fye_begin'],
                        'current_fye_end'       => $fye_data['current_fye_end'],
                        'report_date'           => "",
                        'is_audited'            => $fye_data['is_audited'],
                        'group_type'            => $fye_data['group_type'],
                        'is_group_consolidated' => $fye_data['is_group_consolidated'],
                        'director_signature_1'     => $fye_data['director_signature_1'],
                        'director_signature_2'     => $fye_data['director_signature_2'],
                        'accounting_standard_used' => $fye_data['accounting_standard_used'],
                        'act_applicable_type'      => $fye_data['act_applicable_type']
                        // 'is_prior_year_amount_restated'=> $form_data['is_prior_year_amount_restated'],
                        // 'effect_of_restatement_since'  => $form_data['effect_of_restatement_since']
                    );

                    // // print_r($fs_company_info);
                    if(count($fs_fp_currency_details) > 0)
                    {
                        $fs_fp_currency_info = array(
                            'id'                          => '',
                            'fs_company_info_id'          => '',
                            'last_year_fc_currency_id'    => $fs_fp_currency_details[0]['last_year_fc_currency_id'],
                            'current_year_fc_currency_id' => $fs_fp_currency_details[0]['current_year_fc_currency_id'],
                            'reason_of_changing_fc'       => $fs_fp_currency_details[0]['reason_of_changing_fc'],
                            'last_year_pc_currency_id'    => $fs_fp_currency_details[0]['last_year_pc_currency_id'],
                            'current_year_pc_currency_id' => $fs_fp_currency_details[0]['current_year_pc_currency_id'],
                            'reason_changing_fc_pc'       => $fs_fp_currency_details[0]['reason_changing_fc_pc']
                        );
                    }
                    else
                    {
                        $fs_fp_currency_info = array(
                            'id'                          => '',
                            'fs_company_info_id'          => '',
                            'last_year_fc_currency_id'    => '',
                            'current_year_fc_currency_id' => '',
                            'reason_of_changing_fc'       => '',
                            'last_year_pc_currency_id'    => '',
                            'current_year_pc_currency_id' => '',
                            'reason_changing_fc_pc'       => ''
                        );
                    }

                    $fs_company_info_id = $this->fs_model->save_fs_company_info($fs_report_details);

                    if(empty($fs_fp_currency_info['fs_company_info_id']))
                    {
                        $fs_fp_currency_info['fs_company_info_id'] = $fs_company_info_id;
                    }

                    $fs_fp_currency = $this->fs_model->save_fs_fp_currency_info($fs_fp_currency_info);


                }
                else
                {
                    // print_r("3.".$client_signing_info);
                    if($client_signing_info)
                    {
                        $fs_report_details =    array(
                                                    'id'               => '',
                                                    'company_code'     => $assignment_detail[0]->client_id,
                                                    'firm_id'          => $assignment_detail[0]->firm_id,
                                                    'current_fye_end'    => $date_change_format,
                                                    'first_set' => 1,
                                                    'accounting_standard_used' => 4,
                                                    'director_signature_1' => $client_signing_info[0]->director_signature_1,
                                                    'director_signature_2' => $client_signing_info[0]->director_signature_2
                                                );
                    }
                    else
                    {
                        $fs_report_details =    array(
                                                    'id'               => '',
                                                    'company_code'     => $assignment_detail[0]->client_id,
                                                    'firm_id'          => $assignment_detail[0]->firm_id,
                                                    'current_fye_end'    => $date_change_format,
                                                    'first_set' => 1,
                                                    'accounting_standard_used' => 4
                                                );
                    }

                    // print_r($fs_report_details);

                    $fs_company_info_id = $this->fs_model->save_fs_company_info($fs_report_details);
                }

                // check company change name 
                // $company_change_name = $this->db->query("SELECT tccn.company_name, tccn.new_company_name
                //                                             FROM transaction_master tm
                //                                             LEFT JOIN transaction_change_company_name tccn ON tccn.transaction_id = tm.id
                //                                             WHERE tm.transaction_task_id = 12 AND tm.company_code = '" . $company_code . "' AND (tm.created_at BETWEEN '" . $this->data["fs_report_details"][0]['current_fye_begin'] . "'AND '" . $this->data["fs_report_details"][0]['current_fye_end'] . "')
                //                                             ORDER BY tm.created_at");
                // $company_change_name = $company_change_name->result_array();

                // $this->data["fs_report_details"][0]['old_company_name'] = $company_change_name[0]['company_name'];

                // $new_report = array(
                //                 'company_code'     => $assignment_detail[0]->client_id,
                //                 'firm_id'  => $assignment_detail[0]->firm_id,
                //                 'current_fye_end'    => $date_change_format);

                // $this->db->insert('fs_company_info', $new_report);    // insert new child to database

                // $fs_company_info_id = $this->db->insert_id();

                return $fs_company_info_id;

            }
        }
    }

    public function get_default_main_sub_account_list($main_sub)
    {
        $this->db->select('*');
        $this->db->from('fs_default_acc_category');

        if($main_sub == "main")
        {
            $this->db->where('parent', "#");
            $this->db->where_not_in('account_code', array(''));
        }
        else if($main_sub == "sub")
        {
            $this->db->where_not_in('parent', array('#'));
            $this->db->where_not_in('account_code', array(''));
        }
        else if($main_sub == "default")
        {
            $this->db->where_not_in('parent', array(''));
            $this->db->where_not_in('account_code', array(''));
        }
        
        $this->db->order_by('account_code ASC');
        
        $q = $this->db->get();

        if ($q->num_rows() > 0) 
        {
            return $q->result_array();
        }
        else
        {
            return '';
        }
    }


    public function get_fs_account_category_json()
    {
        $url         = 'assets/json/fs_account_category.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        return json_decode(json_encode($data_decode[0]), true);
    }

    public function get_audit_mapping_condition_json()
    {
        $url         = './assets/json/audit_mapping_condition.json'; // path to your JSON file
        // echo file_get_contents($url);
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        return json_decode(json_encode($data_decode[0]), true);
    }


    
    // general function 
    public function negative_bracket($number)   // insert negative brackets
    {
        if($number < 0)
        {
            return "(" . number_format(abs($number), 2) . ")";
        }
        else
        {
            if($number == '')
            {
                $number = 0;
            }

            return number_format($number, 2);
        }
    }

    public function compare_date_latest($date_1, $date_2, $shortest_day) // used to calculate days to get last year end
    {   
        // echo $date_1."========".$date_2;

        $startTimeStamp = new DateTime(date('Y-m-d', strtotime($date_1)));
        $startTimeStamp  = $startTimeStamp->format('Y-m-d');
        $startTimeStamp = date_create($startTimeStamp);

        $endTimeStamp = new DateTime(date('Y-m-d', strtotime($date_2)));
        $endTimeStamp  = $endTimeStamp->format('Y-m-d');
        $endTimeStamp = date_create($endTimeStamp);

        $interval = date_diff($startTimeStamp, $endTimeStamp);  // compare 2 dates

        return $interval->days;
    }

    public function set_default_tree() 
    {
        $data = [];

        foreach($this->get_default_account_list() as $item)
        {
            $account_code = $item["account_code"];

            if(empty($account_code)) // to solve some child under parent without account code
            {
                $account_code = $item['id'];
            }

            array_push($data, 
                array(
                    "id"     => $account_code,
                    "parent" => $item["parent"], 
                    "text"   => $item["tree_name"],
                    'type'   => $item["type"],
                    'data'   => array(
                                    'account_code' => $item['account_code'],
                                    'description' => $item['description'],
                                    'value' => '',
                                    'fs_default_acc_category_id' => $item['id'],
                                    'id' => ''
                                )
                )
            );
        }
        return $data;
    }

    public function get_default_account_list()
    {
        $this->db->select('*');
        $this->db->from('fs_default_acc_category');
        $this->db->where_not_in('parent', array(''));
        $this->db->order_by('order ASC');
        
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        else
        {
            return '';
        }
    }

    public function get_show_ly_TB_btn($assignment_id)
    {
        $fs_company_info_id = $this->getReportIdWithAssg($assignment_id);
        $assignment_detail = $this->getAssignmentDetail($assignment_id);

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $show_ly_TB_btn = false;

        if(!$fs_company_info[0]['first_set'])
        {
            $ly_tb_record = $this->db->query("SELECT * FROM audit_ly_trial_balance WHERE assignment_id=" . $assignment_id . " ORDER BY order_by");
            $ly_tb_record = $ly_tb_record->result_array();

            if(count($ly_tb_record) > 0)
            {
                $show_ly_TB_btn = true;
            }
            else
            {
                $q = $this->db->query("SELECT * FROM fs_company_info WHERE company_code = '" . $fs_company_info[0]['company_code'] . "' AND current_fye_end='" . $fs_company_info[0]['last_fye_end'] . "' AND firm_id=" . $assignment_detail->firm_id);
                $q = $q->result_array();

                if(count($q) == 0)
                {
                    $show_ly_TB_btn = true;
                }
                else
                {
                    if($q[0]["assignment_id"])
                    {
                        $ly_record = $this->db->query("SELECT * FROM audit_trial_balance WHERE assignment_id=" . $q[0]["assignment_id"] . " ORDER BY order_by");
                        $ly_record = $ly_record->result_array();

                        if(count($ly_record) == 0)
                        {
                            $show_ly_TB_btn = true;
                        }
                    }
                }
            }
        }
        else
        {
            $show_ly_TB_btn = true;
        }    

        return $show_ly_TB_btn;
    }

    public function get_leadsheet_documentation($caf_id)
    {
        $this->db->select('*');
        $this->db->from('audit_leadsheet_documentation');
        $this->db->where('caf_id', $caf_id);
        
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            $q = $q->result_array();
            return $q[0]['documentation'];
        }
        else
        {
            return '';
        }
    }

    public function get_leadsheet_setup($caf_id)
    {   
        $this->db->select('*');
        $this->db->from('audit_leadsheet_setup');
        $this->db->where('caf_id', $caf_id);
        
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            $q = $q->result_array();
            return $q[0];
        }
        else
        {
            return '';
        }

    }

    public function insert_categorized_account($initial_data, $data, $assignment_id)
    {
        $value_last_year = "";
        $value_this_year = "";

        $fs_company_info_id    = $this->getReportIdWithAssg($assignment_id);

        // print_r($initial_data);
        // // delete previous record if exist
        // $this->db->where_in('fs_company_info_id', $fs_company_info_id);
        // $this->db->delete('fs_categorized_account');

        // $categorized_account = [];

        // foreach ($data as $key => $value) {
        //     $account_code = '';

        //     if($value['type'] == "Branch")
        //     {
        //         $account_code = $value['id'];
        //     }

        //     array_push($categorized_account,
        //         array(
        //             'fs_company_info_id' => $fs_company_info_id,
        //             'description'        => $value['text'],
        //             'value'              => $value['data']['value'],
        //             'account_code'       => $account_code,
        //             'parent'             => $value['parent'],
        //             'type'               => $value['type'],
        //             'order_by'           => $key + 1
        //         )
        //     );
        // }

        // // insert new data
        // if(count($data) > 0)
        // {
        //     $result = $this->db->insert_batch('fs_categorized_account', $categorized_account);
        // }

        // return $result;

        // print_r($data);


        /* ----- deleted account ----- */
        $audit_categorized_account = $this->db->query("SELECT * FROM audit_categorized_account aca WHERE aca.assignment_id = " . $assignment_id);
        $ori_audit_categorized_account = $audit_categorized_account->result_array();

        $deleted_aca_id = [];

        // print_r($ori_audit_categorized_account);

        foreach ($initial_data as $ori_aca_key => $ori_aca_value) 
        {
            // print_r($ori_fca_value);

            $temp_aca_id = $ori_aca_value['data']['id'];

            foreach ($data as $key => $value) 
            {
                // print_r($value);
                if(isset($value['data']['id']))
                {
                    if($ori_aca_value['data']['id'] == $value['data']['id'])
                    {
                        $temp_aca_id = "";
                    }
                }
                
            }

            if(!empty($temp_aca_id))
            {
                array_push($deleted_aca_id, $temp_aca_id);
            }
        }

        if(count($deleted_aca_id) > 0)
        {
            $this->db->where_in('id', $deleted_aca_id);
            $result = $this->db->delete('audit_categorized_account');
        }
        /* ----- deleted account ----- */

        /* Retrieve adjustment data */
        $audit_adjustment_account = $this->db->query("SELECT ai.*, am.type FROM audit_adjustment_info ai
                                                        LEFT JOIN audit_adjustment_master am on ai.adjustment_master_id = am.id
                                                        LEFT JOIN audit_caf_master cm on cm.id = am.caf_id
                                                        WHERE cm.assignment_id = " . $assignment_id .  " and ai.deleted = 0 and am.deleted = 0");
        $audit_adjustment_account = $audit_adjustment_account->result_array();
        /* End of retrieve data */

        $audit_categorized_account_ids = [];

        $temp_data = $data;

        // print_r($temp_data);

        foreach ($data as $key => $value) 
        {
            $adjusted_value_this_year = 0.00;
            $account_code = '';
            $fs_default_acc_category_id = 0;

            if($value['type'] == "Branch")
            {
                $account_code = $value['data']['account_code'];

                if(is_null($account_code))
                {
                    $account_code = '';
                }
            }

            if(!empty($value['data']['fs_default_acc_category_id']))
            {
                $fs_default_acc_category_id = $value['data']['fs_default_acc_category_id'];
            }

            if(isset( $value['data']['value']))
            {
                $value_this_year = str_replace(',', '', $value['data']['value']);
                $value_this_year = str_replace('(', '-', $value_this_year);
                $value_this_year = str_replace(')', '', $value_this_year);
            }
            else
            {
                $value_this_year = str_replace(',', '', 0.00);
                $value_this_year = str_replace('(', '-', $value_this_year);
                $value_this_year = str_replace(')', '', $value_this_year);
            }
            
            

            if(isset($value['data']['company_end_prev_ye_value']))
            {
                $value_last_year = str_replace(',', '', $value['data']['company_end_prev_ye_value']);
                $value_last_year = str_replace('(', '-', $value_last_year);
                $value_last_year = str_replace(')', '', $value_last_year);
            }
            else
            {
                $value_last_year = str_replace(',', '', 0.00);
                $value_last_year = str_replace('(', '-', $value_last_year);
                $value_last_year = str_replace(')', '', $value_last_year);
            }

            /* Check adjustment to save adjusted value */
            $adjusted_value_this_year = $value_this_year;
            // echo ($adjusted_value_this_year);


            // print_r($value);
            if(isset($value['data']['id']))
            {
                
                if(count($audit_adjustment_account) > 0)
                {
                    foreach ($audit_adjustment_account as $adjustment_key => $adjustment_value) {
                        if($adjustment_value['categorized_account_id'] == $value['data']['id'])
                        {
                            if($adjustment_value['type'] != 8)  //type 8 is SOUE
                            { 
                                $adjusted_value_this_year +=  $adjustment_value['adjust_value'];
                            }

                        }
                    }
                }
            }

            // echo("------------------Adjusted------------------".$adjusted_value_this_year);

            /* end of Check adjustment to save adjusted value */
            $value['text'] = htmlspecialchars(htmlspecialchars_decode($value['text']));
            $value['text'] = trim($value['text']);

            $categorized_account = array(
                    'assignment_id'      => $assignment_id,
                    'fs_company_info_id' => $fs_company_info_id,
                    'description'        => $value['text'],
                    'fs_default_acc_category_id' => $fs_default_acc_category_id,
                    'value'              => $value_this_year,
                    'adjusted_value'     => $adjusted_value_this_year,
                    'company_end_prev_ye_value'=> $value_last_year,
                    'account_code'       => $account_code,
                    'parent'             => $temp_data[$key]['parent'],
                    'type'               => $value['type'],
                    'order_by'           => $key + 1
                );

            // print_r($categorized_account);

            if(empty($value['data']['id']) || $value['data']['id'] == 0)
            {
                $result = $this->db->insert('audit_categorized_account', $categorized_account);

                $this_category_id = $this->db->insert_id();
                 
                foreach ($temp_data as $key_2 => $value_2)
                {
                    // print_r(array($value_2['parent'], $value['id']));
                    if($value_2['parent'] == $value['id'])
                    {
                        $temp_data[$key_2]['parent'] = $this_category_id;
                    }
                }

                array_push($audit_categorized_account_ids, $this_category_id);
            }
            else
            {
                $this->db->where('id', $value['data']['id']);
                $result = $this->db->update('audit_categorized_account', $categorized_account);

                foreach ($temp_data as $key_2 => $value_2)
                {
                    // print_r(array($value_2['parent'], $value['id']));
                    if($value_2['parent'] == $value['id'])
                    {
                        $temp_data[$key_2]['parent'] = $value['data']['id'];
                    }
                }

                array_push($audit_categorized_account_ids, $value['data']['id']);
            }
        }
        
        return array("result" => $result, "audit_categorized_account_ids" => $audit_categorized_account_ids);
    }

    public function insert_uncategorized_account($data, $assignment_id)
    {
        $fs_company_info_id = $this->getReportIdWithAssg($assignment_id);
        // print_r($data);
        // delete previous record if exist
        $this->db->where_in('assignment_id', $assignment_id);
        $this->db->delete('audit_uncategorized_account');

        // $uncategorized_account = [];

        if(count($data) > 0)
        {
            foreach ($data as $key => $value) 
            {
                $account_code = '';

                if($value['type'] == "Branch")
                {
                    $account_code = $value['id'];
                }

                $value_this_year = str_replace(',', '', isset($value['data']['value'])?$value['data']['value']:'');
                $value_this_year = str_replace('(', '-', $value_this_year);
                $value_this_year = str_replace(')', '', $value_this_year);

                $value_last_year = str_replace(',', '', isset($value['data']['company_end_prev_ye_value'])?$value['data']['company_end_prev_ye_value']:'');
                $value_last_year = str_replace('(', '-', $value_last_year);
                $value_last_year = str_replace(')', '', $value_last_year);

                $value['text'] = htmlspecialchars(htmlspecialchars_decode($value['text']));
                $value['text'] = trim($value['text']);

                $uncategorized_account = array(
                        'assignment_id'             => $assignment_id,
                        'fs_company_info_id'        => $fs_company_info_id,
                        'description'               => $value['text'],
                        'value'                     => $value_this_year,
                        'company_end_prev_ye_value' => $value_last_year,
                        'order_by'                  => $key + 1
                    );

                // print_r($uncategorized_account);

                $result = $this->db->insert('audit_uncategorized_account', $uncategorized_account);

                // array_push($uncategorized_account,
                //     array(
                //         'fs_company_info_id' => $fs_company_info_id,
                //         'description'        => $value['text'],
                //         'value'              => $value['data']['value'],
                //         'order_by'           => $key + 1
                //     )
                // );   

                if(!$result)
                {
                    return array("result" => $result);
                }
            }
        }
        else
        {
            return array("result" => true);
        }

        // // insert new data
        // if(count($data) > 0)
        // {
        //     $result = $this->db->insert_batch('fs_uncategorized_account', $uncategorized_account);
        // }
        // else
        // {
        //     $result = true;
        // }
        
        return array("result" => $result);
    }

    public function insert_line_items($data)
    {
        if($data['id']=="")
        {
            $data['created_by'] = $this->session->userdata('user_id');
            $result = $this->db->insert('audit_bs_line_item', $data); 
        }
        else
        {
            $this->db->where('id', $data['id']);
            $result = $this->db->update('audit_bs_line_item', $data);
        }

        
        
        return $result;
    }

    public function insert_pl_line_items($data)
    {
        if($data['id']=="")
        {
            
            $data['created_by'] = $this->session->userdata('user_id');
            $result = $this->db->insert('audit_pl_line_item', $data); 
        
        }
        else
        {
            $this->db->where('id', $data['id']);
            $result = $this->db->update('audit_pl_line_item', $data);
        }

        
        
        return $result;
    }

    public function insert_materiality($data)
    {
        if($data['id']=="")
        {
            
         
            $result = $this->db->insert('audit_materiality', $data); 
            $result = $this->db->insert_id();
         
        }
        else
        {
            $this->db->where('id', $data['id']);
            $result = $this->db->update('audit_materiality', $data);
            $result = $data['id'];
        }
        
        return $result;
    }

    public function insert_materiality_v2($data)
    {
        if($data['id']=="")
        {
            
         
            $result = $this->db->insert('audit_materiality_v2', $data); 
            $result = $this->db->insert_id();
         
        }
        else
        {
            $this->db->where('id', $data['id']);
            $result = $this->db->update('audit_materiality_v2', $data);
            $result = $data['id'];
        }
        
        return $result;
    }

    public function insert_adjustment_master($data)
    {
        if($data['id'] == "")
        {

            $this->db->insert('audit_adjustment_master', $data); 
            $result = $this->db->insert_id();
        }
        else
        {

            $this->db->where('id', $data['id']);
            $result = $data['id'];
            unset($data['id']); 
            $this->db->update('audit_adjustment_master', $data);

            
        }
        
        return $result;
    }

    public function insert_adjustment_info($data)
    {
        if($data['id']=="")
        {
          
            $result = $this->db->insert('audit_adjustment_info', $data);
        
        }
        else
        {
            $this->db->where('id', $data['id']);
            $result = $this->db->update('audit_adjustment_info', $data);
        }

        
        
        return $result;
    }

    public function insert_audit_procedure_caf_input($data)
    {
        if($data['id'] != null)
        {
            $this->db->where('id', $data['id']);

            $result = $this->db->update('audit_procedure_caf_input', $data);
        }
        else
        {
            $result = $this->db->insert('audit_procedure_caf_input', $data); 
        }
        return $result;
    }

    public function insert_audit_programme_yn_caf_input($data)
    {
        if($data['id'] != null)
        {
            $this->db->where('id', $data['id']);

            $result = $this->db->update('audit_programme_yn_input', $data);
        }
        else
        {
            $result = $this->db->insert('audit_programme_yn_input', $data); 
        }
        return $result;
    }

    public function insert_audit_programme_qa_caf_input($data)
    {
        if($data['id'] != null)
        {
            $this->db->where('id', $data['id']);

            $result = $this->db->update('audit_programme_qa_answer', $data);
        }
        else
        {
            $result = $this->db->insert('audit_programme_qa_answer', $data); 
        }
        return $result;
    }

    public function insert_programme_dropdown($data)
    {
        if($data['id']=="")
        {
            
         
            $result = $this->db->insert('audit_programme_conclusion_input', $data); 
            $result = $this->db->insert_id();
         
        }
        else
        {
            $this->db->where('id', $data['id']);
            $result = $this->db->update('audit_programme_conclusion_input', $data);
            $result = $data['id'];
        }
        
        return $result;
    }

    public function submit_audit_programme_meeting_master($data)
    {
       
        if($data['id'] != '')
        {
            // $query = $query_check->result_array();

            $this->db->where('id', $data['id']);
            $this->db->update('audit_programme_meeting_master', $data);

            $result = $data['id'];
        }
        else
        {
            $this->db->insert('audit_programme_meeting_master', $data); 
            $result = $this->db->insert_id();
        }


        return $result;
    }

    public function submit_audit_programme_opinion_input($data)
    {
       
        if($data['id'] != '')
        {
            // $query = $query_check->result_array();

            // print_r($data);

            $this->db->where('id', $data['id']);
            $this->db->update('audit_programme_opinion_input', $data);

            $result = $data['id'];
        }
        else
        {
            $this->db->insert('audit_programme_opinion_input', $data); 
            $result = $this->db->insert_id();
        }


        return $result;
    }

    public function insert_audit_programme_meeting_attendees($data)
    {
        if($data['id'] != null)
        {
            $this->db->where('id', $data['id']);

            $result = $this->db->update('audit_programme_meeting_attendees', $data);
        }
        else
        {
            $result = $this->db->insert('audit_programme_meeting_attendees', $data); 
        }
        return $result;
    }

    public function insert_audit_programme_meeting_agenda($data)
    {
        if($data['id'] != null)
        {
            $this->db->where('id', $data['id']);

            $result = $this->db->update('audit_programme_meeting_agenda', $data);
        }
        else
        {
            $result = $this->db->insert('audit_programme_meeting_agenda', $data); 
        }
        return $result;
    }

    public function insert_audit_programme_meeting_absent($data)
    {
        if($data['id'] != null)
        {
            $this->db->where('id', $data['id']);

            $result = $this->db->update('audit_programme_meeting_absent', $data);
        }
        else
        {
            $result = $this->db->insert('audit_programme_meeting_absent', $data); 
        }
        return $result;
    }

    public function submit_audit_programme_currency_input($data)
    {
       
        if($data['id'] != '')
        {
            // $query = $query_check->result_array();

            $this->db->where('id', $data['id']);
            $this->db->update('audit_programme_currency_input', $data);

            $result = $data['id'];
        }
        else
        {
            $this->db->insert('audit_programme_currency_input', $data); 
            $result = $this->db->insert_id();
        }


        return $result;
    }

    public function submit_audit_programme_cdd_input($data)
    {
       
        if($data['id'] != '')
        {
            // $query = $query_check->result_array();

            $this->db->where('id', $data['id']);
            $this->db->update('audit_programme_cdd_input', $data);

            $result = $data['id'];
        }
        else
        {
            $this->db->insert('audit_programme_cdd_input', $data); 
            $result = $this->db->insert_id();
        }


        return $result;
    }

    public function submit_audit_programme_fcm_apm_input($data)
    {
        if(!isset($data['require_flag']))
        {
            $data['require_flag'] = 0;
        }
        if($data['id'] != '')
        {
            // $query = $query_check->result_array();

            $this->db->where('id', $data['id']);
            $this->db->update('audit_programme_fcm_apm_input', $data);

            $result = $data['id'];
        }
        else
        {
            $this->db->insert('audit_programme_fcm_apm_input', $data); 
            $result = $this->db->insert_id();
        }


        return $result;
    }

    public function submit_audit_leadsheet_documentation($data)
    {
        $this->db->select('*');
        $this->db->from('audit_leadsheet_documentation');
        $this->db->where('caf_id', $data['caf_id']);
        
        $q = $this->db->get();

        if ($q->num_rows() > 0) 
        {
            $this->db->where('caf_id', $data['caf_id']);
            $this->db->update('audit_leadsheet_documentation', $data);

            $result = $data['id'];
        }
        else
        {
            $this->db->insert('audit_leadsheet_documentation', $data);
            $result = $this->db->insert_id();
        }
         
        

        return $result;
    }

    public function submit_audit_programme_gai_master($data)
    {
       
        if($data['id'] != '')
        {
            // $query = $query_check->result_array();

            $this->db->where('id', $data['id']);
            $this->db->update('audit_programme_gai_master', $data);

            $result = $data['id'];
        }
        else
        {
            $this->db->insert('audit_programme_gai_master', $data); 
            $result = $this->db->insert_id();
        }


        return $result;
    }

    public function insert_audit_programme_gai_team($data)
    {
        if($data['id'] != null)
        {
            $this->db->where('id', $data['id']);

            $result = $this->db->update('audit_programme_gai_team', $data);
        }
        else
        {
            $result = $this->db->insert('audit_programme_gai_team', $data); 
        }
        return $result;
    }

    public function insert_audit_programme_gai_component($data)
    {
        if($data['id'] != null)
        {
            $this->db->where('id', $data['id']);

            $result = $this->db->update('audit_programme_gai_component', $data);
        }
        else
        {
            $result = $this->db->insert('audit_programme_gai_component', $data); 
        }
        return $result;
    }


    public function get_meeting_master_detail($caf_id)
    {
        $q = $this->db->query('SELECT * from audit_programme_meeting_master where caf_id ='.$caf_id);


        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $data[] = $row;
            }
            return $data[0];
        }
        return FALSE;
    }

    public function get_meeting_attendees($meeting_master_id)
    {
        $q = $this->db->query('SELECT * from audit_programme_meeting_attendees where meeting_master_id ='.$meeting_master_id.' and deleted=0');


        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_meeting_agenda($meeting_master_id)
    {
        $q = $this->db->query('SELECT * from audit_programme_meeting_agenda where meeting_master_id ='.$meeting_master_id.' and deleted=0');


        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_meeting_absent($meeting_master_id)
    {
        $q = $this->db->query('SELECT * from audit_programme_meeting_absent where meeting_master_id ='.$meeting_master_id.' and deleted=0');


        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_opinion_detail($caf_id)
    {
        $q = $this->db->query('SELECT * from audit_programme_opinion_input where caf_id ='.$caf_id);


        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $data[] = $row;
            }

            if ($data[0]['date_of_report'] != "1970-01-01" && $data[0]['date_of_report'] != "0000-00-00")
            {
                $data[0]['date_of_report'] = date('d/m/Y', strtotime($data[0]['date_of_report']));
            }
            else
            {
                $data[0]['date_of_report'] = "";
            }
            return $data[0];
        }
        return FALSE;
    }

    public function get_currency_detail($caf_id)
    {
        $q = $this->db->query('SELECT * from audit_programme_currency_input where caf_id ='.$caf_id);


        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $data[] = $row;
            }
            return $data[0];
        }
        return FALSE;
    }

    public function get_cdd_detail($caf_id)
    {
        $q = $this->db->query('SELECT * from audit_programme_cdd_input where caf_id ='.$caf_id);


        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $data[] = $row;
            }
            $temp_s1_input = json_decode($data[0]['s1_input'], true);
            $temp_s2_input = json_decode($data[0]['s2_input'], true);

            $temp_all_s1_s2 = $temp_s1_input + $temp_s2_input;

            $all_no = true;

            if(count($temp_all_s1_s2) == 31)
            {
                foreach ($temp_all_s1_s2 as $key => $value) {
                    if($value == 1)
                    {
                       

                        $all_no = false;

                        break;
                    }
                }
                

                if(!$all_no)
                {
                    $temp['s3_risk'] = "High";
                    $temp['s3_cdd'] = "Enhanced CDD";
                }
                else
                {
                    $temp['s3_risk'] = "Low";
                    $temp['s3_cdd'] = "Normal CDD";
                }
            }
            else
            {
                $temp['s3_risk'] = "";
                $temp['s3_cdd']  = "";
            }

            $all_no_s1 = true;

            if(count($temp_s1_input) == 4)
            {
                foreach ($temp_s1_input as $key => $value) {
                    if($value == 1)
                    {
                       

                        $all_no_s1 = false;

                        break;
                    }
                }

                if(!$all_no_s1)
                {
                    $temp['s4_relationship'] = "Terminate";
                }
                else
                {
                    $temp['s4_relationship'] = "Maintain";
                }
   
            }
            else
            {
                $temp['s4_relationship']  = "";
            }


            return $data[0] + $temp_s1_input + $temp_s2_input + $temp ;
        }
        return FALSE;
    }

    public function get_fcm_apm_detail($caf_id)
    {
        $q = $this->db->query('SELECT * from audit_programme_fcm_apm_input where caf_id ='.$caf_id);


        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $data[] = $row;
            }
            return $data[0];
        }
        return FALSE;
    }

    public function get_gai_master_detail($caf_id)
    {
        $q = $this->db->query('SELECT * from audit_programme_gai_master where caf_id ='.$caf_id);


        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $data[] = $row;
            }
            return $data[0];
        }
        return FALSE;
    }

    public function get_gai_team($gai_master_id)
    {
        $q = $this->db->query('SELECT * from audit_programme_gai_team where gai_master_id ='.$gai_master_id.' and deleted=0 ORDER BY fixed desc');


        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function get_gai_component($gai_master_id)
    {
        $q = $this->db->query('SELECT * from audit_programme_gai_component where gai_master_id ='.$gai_master_id.' and deleted=0');


        if ($q->num_rows() > 0) {
            foreach (($q->result_array()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function delete_meeting_attendees($arr_delete_row)
    {
        if($arr_delete_row != null)
        {
            $arr_delete_row = explode(',',$arr_delete_row);
            $this->db->where_in('id', $arr_delete_row);
            $this->db->update('audit_programme_meeting_attendees',  array('deleted' => 1 ));
        }
    }

    public function delete_meeting_agenda($arr_delete_row)
    {
        if($arr_delete_row != null)
        {
            $arr_delete_row = explode(',',$arr_delete_row);
            $this->db->where_in('id', $arr_delete_row);
            $this->db->update('audit_programme_meeting_agenda',  array('deleted' => 1 ));
        }
    }

    public function delete_meeting_absent($arr_delete_row)
    {
        if($arr_delete_row != null)
        {
            $arr_delete_row = explode(',',$arr_delete_row);
            $this->db->where_in('id', $arr_delete_row);
            $this->db->update('audit_programme_meeting_absent',  array('deleted' => 1 ));
        }
    }


    public function delete_adjustment_info($arr_delete_row)
    {
        if($arr_delete_row != null)
        {
            $arr_delete_row = explode(',',$arr_delete_row);
            $this->db->where_in('id', $arr_delete_row);
            $this->db->update('audit_adjustment_info',  array('deleted' => 1 ));
        }
    }

    public function delete_gai_component($arr_delete_row)
    {
        if($arr_delete_row != null)
        {
            $arr_delete_row = explode(',',$arr_delete_row);
            $this->db->where_in('id', $arr_delete_row);
            $this->db->update('audit_programme_gai_component',  array('deleted' => 1 ));
        }
    }

    public function delete_gai_team($arr_delete_row)
    {
        if($arr_delete_row != null)
        {
            $arr_delete_row = explode(',',$arr_delete_row);
            $this->db->where_in('id', $arr_delete_row);
            $this->db->update('audit_programme_gai_team',  array('deleted' => 1 ));
        }
    }


    public function update_categorized_account_round_off_batch($data)
    {
        // print_r($data[0]);
        $ids = array_column($data[0], 'id');

        foreach ($data[0] as $key => $value)    // remove id object 
        {
            unset($data[0][$key]['id']);
        }

        $temp_data = [];

        foreach ($data[0] as $key => $value) 
        {
            array_push($temp_data, 
                array(
                    'id'    => $ids[$key],
                    'info'  => $value 
                )
            );
        }

        foreach ($temp_data as $temp_key => $temp_value) 
        {
            $this->db->where('id', $temp_value['id']);
            $result = $this->db->update('fs_categorized_account_round_off', $temp_value['info']);

            if(!$result)
            {
                return 0;
            }
        }
        
        return 1;
    }



    // public function update_next_ye_values($assignment_id)
    // {
    //     $fs_company_info_next_year_id = $this->get_company_info_next_year($assignment_id);

    //     if(!empty($fs_company_info_next_year_id))
    //     {
    //         $fca_ty = $this->db->query("SELECT * FROM fs_categorized_account WHERE fs_company_info_id = " . $fs_company_info_id . " ORDER BY order_by");
    //         $fca_ty = $fca_ty->result_array();

    //         $fca_ny = $this->db->query("SELECT * FROM fs_categorized_account WHERE fs_company_info_id = " . $fs_company_info_next_year_id . " ORDER BY order_by");
    //         $fca_ny = $fca_ny->result_array();

    //         $fca_ny_desc = [];

    //         if(count($fca_ny))
    //         {
    //             // keey all description with type "Leaf" in an array.
    //             foreach ($fca_ny as $tkey => $tvalue) 
    //             {
    //                 if($tvalue['type'] != 'Branch')
    //                 {
    //                     array_push($fca_ny_desc, $tvalue['description']);
    //                 }
    //                 else
    //                 {
    //                     array_push($fca_ny_desc, "");
    //                 }
    //             }

    //             // update company_end_prev_ye_value in next year end report
    //             foreach ($fca_ty as $key => $value) 
    //             {
    //                 if($value['type'] != 'Branch' && !empty($value['description']))
    //                 {
    //                     if(in_array($value['description'], $fca_ny_desc))
    //                     {
    //                         $fca_ny_key = array_search($value['description'], $fca_ny_desc);

    //                         $this->db->where('id', $fca_ny[$fca_ny_key]['id']);
    //                         $result = $this->db->update('fs_categorized_account', array('company_end_prev_ye_value' => $value['value']));

    //                         // update round off table for next year
    //                         $fcaro_ty = $this->db->query("SELECT * FROM fs_categorized_account_round_off WHERE fs_categorized_account_id=" . $value['id'] . " AND is_deleted = 0");
    //                         $fcaro_ty = $fcaro_ty->result_array();

    //                         $fcaro_ny = $this->db->query("SELECT * FROM fs_categorized_account_round_off WHERE fs_categorized_account_id=" . $fca_ny[$fca_ny_key]['id'] . " AND is_deleted = 0");
    //                         $fcaro_ny = $fcaro_ny->result_array();

    //                         $this->db->where('id', $fcaro_ny[0]['id']);
    //                         $result = $this->db->update('fs_categorized_account_round_off', array('company_end_prev_ye_value' => $fcaro_ty[0]['value']));
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     else
    //     {
    //         return true;
    //     }
    // }

    public function update_next_ye_values($assignment_id, $fs_company_info_id)
    {
        $fs_company_info_next_year_id = $this->get_company_info_next_year($fs_company_info_id);

        if(!empty($fs_company_info_next_year_id))
        {
            $fca_ty = $this->db->query("SELECT * FROM audit_categorized_account WHERE assignment_id = " . $assignment_id . " ORDER BY order_by");
            $fca_ty = $fca_ty->result_array();

            $fca_ny = $this->db->query("SELECT * FROM audit_categorized_account WHERE fs_company_info_id = " . $fs_company_info_next_year_id . " ORDER BY order_by");
            $fca_ny = $fca_ny->result_array();

            $fca_ny_desc = [];

            if(count($fca_ny))
            {
                // keey all description with type "Leaf" in an array.
                foreach ($fca_ny as $tkey => $tvalue) 
                {
                    if($tvalue['type'] != 'Branch')
                    {
                        array_push($fca_ny_desc, $tvalue['description']);
                    }
                    else
                    {
                        array_push($fca_ny_desc, "");
                    }
                }

                // update company_end_prev_ye_value in next year end report
                foreach ($fca_ty as $key => $value) 
                {
                    if($value['type'] != 'Branch' && !empty($value['description']))
                    {
                        if(in_array($value['description'], $fca_ny_desc))
                        {
                            $fca_ny_key = array_search($value['description'], $fca_ny_desc);

                            $this->db->where('id', $fca_ny[$fca_ny_key]['id']);
                            $result = $this->db->update('audit_categorized_account', array('company_end_prev_ye_value' => $value['adjusted_value']));

                            // update round off table for next year
                            $fcaro_ty = $this->db->query("SELECT * FROM fs_categorized_account_round_off WHERE fs_categorized_account_id=" . $value['id'] . " AND is_deleted = 0");
                            $fcaro_ty = $fcaro_ty->result_array();

                            $fcaro_ny = $this->db->query("SELECT * FROM fs_categorized_account_round_off WHERE fs_categorized_account_id=" . $fca_ny[$fca_ny_key]['id'] . " AND is_deleted = 0");
                            $fcaro_ny = $fcaro_ny->result_array();

                            if(count($fcaro_ny) > 0){
                                $this->db->where('id', $fcaro_ny[0]['id']);
                                $result = $this->db->update('fs_categorized_account_round_off', array('company_end_prev_ye_value' => $fcaro_ty[0]['value']));
                            }
                        }
                    }
                }
            }
        }
        else
        {
            return true;
        }
    }

    // public function check_account_data($assignment_id)
    // {
    //     $return_data = array(
    //                         'take_parent_ly_values' => 0
    //                     );

    //     $account_list = $this->db->query("SELECT * FROM audit_categorized_account WHERE assignment_id=" . $assignment_id);
    //     $account_list = $account_list->result_array();

    //     foreach ($account_list as $key => $value) 
    //     {
    //         if($value['type'] == 'Branch')
    //         {
    //             if(!(empty($value['company_end_prev_ye_value']) || $value['company_end_prev_ye_value'] == 0.00))
    //             {
    //                 $return_data['take_parent_ly_values'] = 1;

    //                 break;
    //             }
    //         }
    //     }

    //     return $return_data;
    // }

    // public function get_account_with_sub_ids($assignment_id, $id_list)
    // {
    //     $data = [];

    //     $check_condition_acc = $this->check_account_data($assignment_id);

    //     // print_r($id_list);

    //     // if (is_array($id_list) || is_object($id_list))
    //     // {

    //         foreach ($id_list as $key => $id) 
    //         {
    //             // $q = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fca.account_code
    //             //                         FROM fs_categorized_account_round_off fcaro
    //             //                         LEFT JOIN fs_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
    //             //                         WHERE fca.id=" . $id . " ORDER BY fca.order_by");

    //             $q = $this->db->query("SELECT aca.*,  aca.id AS `aca_id`, fs_default_acc_category.account_code
    //                                     FROM audit_categorized_account aca
    //                                     LEFT JOIN fs_default_acc_category ON aca.fs_default_acc_category_id = fs_default_acc_category.id
    //                                     WHERE aca.id=" . $id . " ORDER BY aca.order_by");

    //             if(count($q->result_array()) > 0)
    //             {
    //                 $parent_array = $q->result_array();

    //                 $child_array = $this->recursive_sub_ids($assignment_id, $id, $check_condition_acc);
    //                 $total_category = $this->calculate_total_ids($assignment_id, $id);

    //                 // if last year value for 'parent node' is not 0, take the value as total_c_lye
    //                 if($parent_array[0]['type'] == 'Branch' && $check_condition_acc['take_parent_ly_values'])
    //                 {
    //                     if(!empty($parent_array[0]['company_end_prev_ye_value']))
    //                     {
    //                         $total_category[0]['total_c_lye'] = $parent_array[0]['company_end_prev_ye_value'];
    //                     }
    //                 }

    //                 $parent_array[0]['total_c']             = $total_category['total_c'];
    //                 $parent_array[0]['total_c_adjusted']    = $total_category['total_c_adjusted'];
    //                 $parent_array[0]['total_c_lye']         = $total_category['total_c_lye'];
    //                 $parent_array[0]['total_g']             = $total_category['total_g'];
    //                 $parent_array[0]['total_g_lye']         = $total_category['total_g_lye'];
    //                 $parent_array[0]['child_id']          =  implode(",", $total_category['child_id']);

    //                 array_push($data, array('parent_array' => $parent_array, 'child_array' => $child_array));
    //             }
    //         }
    //     // }

    //     // print_r($data);

    //     return $data;
    // }


    // public function recursive_sub_ids($assignment_id, $id, $check_condition_acc)
    // {
    //     // print_r(array($id));
    //     $temp = [];

    //     $all_account = $this->db->query("SELECT aca.*, fs_default_acc_category.account_code FROM audit_categorized_account aca LEFT JOIN fs_default_acc_category ON aca.fs_default_acc_category_id = fs_default_acc_category.id WHERE assignment_id=" . $assignment_id . " ORDER BY order_by");
    //     $all_account = $all_account->result_array();

    //     // $q = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fca.account_code
    //     //                         FROM fs_categorized_account_round_off fcaro
    //     //                         LEFT JOIN fs_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
    //     //                         WHERE fca.parent=" . $id . " ORDER BY fca.order_by");

    //     $q = $this->db->query("SELECT aca.*,  aca.id AS `aca_id`, fs_default_acc_category.account_code
    //                             FROM audit_categorized_account aca
    //                             LEFT JOIN fs_default_acc_category ON aca.fs_default_acc_category_id = fs_default_acc_category.id
    //                             WHERE aca.parent=" . $id . " ORDER BY aca.order_by");

    //     // print_r($q->result_array());

    //     if(count($q->result_array()) > 0)
    //     {
    //         foreach ($q->result_array() as $key => $value) 
    //         {
    //             if(array_search($value['aca_id'], array_column($all_account, 'parent'), true))
    //             {
    //                 $child_data = $this->recursive_sub_ids($assignment_id, $value['aca_id'], $check_condition_acc);

    //                 $total_category = $this->calculate_total_ids($assignment_id, $value['aca_id']);

    //                 // if last year value for 'parent node' is not 0, take the value as total_c_lye
    //                 if($value['type'] == 'Branch' && $check_condition_acc['take_parent_ly_values'])
    //                 {
    //                     if(!empty($value['company_end_prev_ye_value']))
    //                     {
    //                         $total_category['total_c_lye'] = $value['company_end_prev_ye_value'];
    //                     }
    //                 }

    //                 $value['total_c']           = $total_category['total_c'];
    //                 $value['total_c_adjusted']  = $total_category['total_c_adjusted'];
    //                 $value['total_c_lye']       = $total_category['total_c_lye'];
    //                 $value['total_g']           = $total_category['total_g'];
    //                 $value['total_g_lye']       = $total_category['total_g_lye'];
    //                 $value['child_id']          =  implode(",", $total_category['child_id']);

    //                 array_push($temp, array('parent_array' => array($value), 'child_array' => $child_data));
    //             }
    //             else
    //             {
    //                  // print_r($value);
    //                 // print_r(array($value['type']));

    //                 if($value['type'] == 'Branch')
    //                 {
    //                     array_push($temp, array('parent_array' => array($value), 'child_array' => []));
    //                 }
    //                 else
    //                 {
    //                     array_push($temp, array('child_array' => $value));
    //                 }
    //             }
    //         }
    //     }

    //     return $temp;
    // }

    // public function calculate_total_ids($assignment_id, $id) // calculate total of value from the bottom level
    // {   
    //     $total_c = 0.00;
    //     $total_c_adjusted = 0.00;
    //     $total_c_lye = 0.00;
    //     $total_g = 0.00;
    //     $total_g_lye = 0.00;
    //     $child_id = array();

    //     if(!empty($id))
    //     {
    //         // $all_account = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fca.account_code
    //         //                     FROM fs_categorized_account_round_off fcaro
    //         //                     LEFT JOIN fs_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
    //         //                     WHERE fcaro.fs_company_info_id = " . $fs_company_info_id);
    //         $all_account = $this->db->query("SELECT aca.*, aca.id AS `aca_id`
    //                             FROM audit_categorized_account aca
    //                             WHERE aca.assignment_id = " . $assignment_id);  // for original values
    //         $all_account = $all_account->result_array();

    //         $temp_account_id = [];

    //         array_push($temp_account_id, $id);

    //         do{
    //             // $q = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fca.account_code
    //             //                 FROM fs_categorized_account_round_off fcaro
    //             //                 LEFT JOIN fs_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
    //             //                 WHERE fca.parent=" . $temp_account_id[0] . " AND fcaro.fs_company_info_id = " . $fs_company_info_id);
    //             $q = $this->db->query("SELECT aca.*, aca.id AS `aca_id`
    //                             FROM audit_categorized_account aca
    //                             WHERE aca.parent=" . $temp_account_id[0] . " AND aca.assignment_id = " . $assignment_id);  // for original values
    //             if(count($q->result_array()) > 0)
    //             {
    //                 foreach ($q->result_array() as $key => $value) 
    //                 {

    //                     $total_c              += (float)$value['value'];
    //                     $total_c_adjusted     += (float)$value['adjusted_value'];
    //                     $total_c_lye          += (float)$value['company_end_prev_ye_value'];
    //                     $total_g              += (float)$value['group_end_this_ye_value'];
    //                     $total_g_lye          += (float)$value['group_end_prev_ye_value'];
    //                     array_push($child_id, $value['aca_id']);

    //                     // print_r($child_id);

    //                     if(in_array($value['aca_id'], array_column($all_account, 'parent')))  // if this account got child
    //                     {
    //                         array_push($temp_account_id, $value['aca_id']);
    //                     }
    //                 }
    //             }

    //             unset($temp_account_id[0]);

    //             if(count($temp_account_id) > 0)
    //             {
    //                 $temp_account_id = array_values($temp_account_id);
    //             }
    //         }
    //         while(count($temp_account_id) > 0);
    //     }

    //     if(is_null($total_c))
    //     {
    //         $total_c = 0.00;
    //     }
        
    //     if(is_null($total_c_lye))
    //     {
    //         $total_c_lye = 0.00;
    //     }

    //     return array('total_c' => $total_c, 'total_c_adjusted' => $total_c_adjusted, 'total_c_lye' => $total_c_lye, 'total_g' => $total_g, 'total_g_lye' => $total_g_lye, 'child_id' => $child_id);
    // }

    public function get_bs_line_item($caf_id)
    {
    
        $all_line = $this->db->query("SELECT audit_bs_line_item.* FROM audit_bs_line_item WHERE audit_bs_line_item.caf_id=" . $caf_id.' and audit_bs_line_item.deleted = 0' );
        $all_line = $all_line->result_array();

        
        $temp_arr = array();
        foreach ($all_line as $line_key => $each_line) {

            if(array_key_exists($each_line['categorized_account_id'], $temp_arr))
            {
                array_push($temp_arr[$each_line['categorized_account_id']], $each_line);
            }
            else
            {
                $temp_arr[$each_line['categorized_account_id']] = array();
                array_push($temp_arr[$each_line['categorized_account_id']], $each_line);
            }
        }

        return $temp_arr;

    }

    public function get_pl_line_item($caf_id)
    {
    
        $all_line = $this->db->query("SELECT audit_pl_line_item.* FROM audit_pl_line_item WHERE audit_pl_line_item.caf_id=" . $caf_id.' and audit_pl_line_item.deleted = 0' );
        $all_line = $all_line->result_array();

        
        $temp_arr = array();
        foreach ($all_line as $line_key => $each_line) {

            if(array_key_exists($each_line['categorized_account_id'], $temp_arr))
            {
                array_push($temp_arr[$each_line['categorized_account_id']], $each_line);
            }
            else
            {
                $temp_arr[$each_line['categorized_account_id']] = array();
                array_push($temp_arr[$each_line['categorized_account_id']], $each_line);
            }
        }

        return $temp_arr;

    }

    public function get_benchmark_dropdown_list(){
        $q = $this->db->query("SELECT id, text_value FROM audit_caf_dropdown where type='materiality' ORDER BY id ");

        $text_value = array();

        $text_value[0] = "Please Select"; 
        foreach($q->result() as $val){
            $text_value[$val->id] = $val->text_value; 
        }
        // $text_value[''] = "Please select"; 

        return $text_value;
    }

    public function get_types_dropdown_list(){
        $q = $this->db->query("SELECT id, text_value FROM audit_caf_dropdown where type='adjustment' ORDER BY id ");

        $text_value = array();

        $text_value[''] = "Please Select"; 
        foreach($q->result() as $val){
            $text_value[$val->id] = $val->text_value; 
        }
        // $text_value[''] = "Please select"; 

        return $text_value;
    }

    public function get_accounts_dropdown_list($assignment_id){
        $q = $this->db->query("SELECT id, description FROM audit_categorized_account where type='Leaf' AND assignment_id=".$assignment_id." ORDER BY order_by");

        $text_value = array();

        $text_value[''] = "Select account"; 
        foreach($q->result() as $val){
            $text_value[$val->id] = $val->description; 
        }
        // $text_value[''] = "Please select"; 

        return $text_value;
    }

    public function get_caf_type_dropdown(){
        $q = $this->db->query("SELECT id, type_name FROM audit_caf_type where user_add='1' ORDER BY id ");

        $text_value = array();

        $text_value[''] = "Please Select"; 
        foreach($q->result() as $val){
            $text_value[$val->id] = $val->type_name; 
        }
        // $text_value[''] = "Please select"; 

        return $text_value;
    }

    public function get_sign_off_dropdown_list($is_admin_manager = false)
    {

        $user = $this->session->userdata();

        // echo($is_admin_manager);

        if($is_admin_manager)
        {
            $query = 'SELECT payroll_employee.id,`name` FROM `payroll_employee` 
                        LEFT JOIN payroll_user_employee ON payroll_employee.id = payroll_user_employee.employee_id 
                        WHERE (department = 1 and employee_status_id != 3 and employee_status_id != 4) OR payroll_user_employee.user_id = '.$this->session->userdata('user_id');

        // // echo $query;

            $result = $this->db->query($query);
        // //echo json_encode($result->result_array());
            if ($result->num_rows() > 0) 
            {

                $result = $result->result_array();

        //     if(!$result) {

        //       throw new exception("PIC Name not found.");
        //     }

        //     $res = array();
        //     foreach($result as $row) {
        //         if($row['id'] != null)
        //         {
        //             $res[$row['id']] = $row['name'];
        //         }
              
        //     }
        //     //$res = json_decode($res);
        //     $ci =& get_instance();
        //     $selected_pic_name = $ci->session->userdata('stocktake_pic');
        //     $ci->session->unset_userdata('stocktake_pic');

        //     // print_r($res);

        //     $data = array('status'=>'success', 'tp'=>1, 'msg'=>"PIC Name fetched successfully.", 'result'=>$res, 'selected_pic_name'=>$selected_pic_name);

                
            }
        }
        else
        {
            $query = 'SELECT payroll_employee.id ,`name` FROM `payroll_employee` 
                        LEFT JOIN payroll_user_employee ON payroll_employee.id = payroll_user_employee.employee_id 
                        WHERE employee_status_id != 3 and employee_status_id != 4  and payroll_user_employee.user_id = '.$this->session->userdata('user_id');

            $result = $this->db->query($query);

            $result = $result->result_array();
        }

        $text_value = array();

        foreach($result as $val){
            $text_value[$val['id']] = $val['name']; 
        }
        // $text_value[''] = "Please select"; 

        return $text_value;

        // return $result;

    
        // $query = 'SELECT `id`,`name` FROM `payroll_employee` WHERE (`department` = 1 OR `department` = 3)and employee_status_id != 3 and employee_status_id != 4';

        // // echo $query;

        // $result = $ci->db->query($query);
        // //echo json_encode($result->result_array());
        // if ($result->num_rows() > 0) 
        // {

        //     $result = $result->result_array();

        //     if(!$result) {
        //       throw new exception("PIC Name not found.");
        //     }

        //     $res = array();
        //     foreach($result as $row) {
        //         if($row['id'] != null)
        //         {
        //             $res[$row['id']] = $row['name'];
        //         }
              
        //     }
        //     //$res = json_decode($res);
        //     $ci =& get_instance();
        //     $selected_pic_name = $ci->session->userdata('stocktake_pic');
        //     $ci->session->unset_userdata('stocktake_pic');

        //     // print_r($res);

        //     $data = array('status'=>'success', 'tp'=>1, 'msg'=>"PIC Name fetched successfully.", 'result'=>$res, 'selected_pic_name'=>$selected_pic_name);

            // echo json_encode($data);
        // }
        // else
        // { 
        //     $res = array();

        //     $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_pic_name'=>'');

        //     echo json_encode($data);
        // }
    
    }

    public function get_materiality_id($caf_id)
    {
        $q = $this->db->query("SELECT id FROM audit_materiality where caf_id='".$caf_id."'");

        $result = $q->result_array();

        if($q->num_rows() > 0)
        {
            $id = $result[0]['id'];
        }
        else
        {
            return "";
        }

        return $id;
        
    }

    public function get_materiality_v2_id($caf_id)
    {
        $q = $this->db->query("SELECT id FROM audit_materiality_v2 where caf_id='".$caf_id."'");

        $result = $q->result_array();

        if($q->num_rows() > 0)
        {
            $id = $result[0]['id'];
        }
        else
        {
            return "";
        }

        return $id;
        
    }
    

    public function get_materiality_data($caf_id)
    {
        $q = $this->db->query("SELECT * FROM audit_materiality where caf_id='".$caf_id."'");

        $result = $q->result_array();

        if($q->num_rows() > 0)
        {
            $data = $result[0];
            $data['reason_for_percentage'] = str_replace('"', "&quot;", $data['reason_for_percentage']);
            // print_r($data);
        }
        else
        {
            return [];
        }

        return $data;
        
    }

    public function get_materiality_v2_data($caf_id)
    {
        $q = $this->db->query("SELECT * FROM audit_materiality_v2 where caf_id='".$caf_id."'");

        $result = $q->result_array();

        if($q->num_rows() > 0)
        {
            $data = $result[0];
            $data['reason_for_percentage'] = str_replace('"', "&quot;", $data['reason_for_percentage']);
            $data['support_for_risk'] = str_replace('"', "&quot;", $data['support_for_risk']);
            $data['understand_on_materiality'] = str_replace('"', "&quot;", $data['understand_on_materiality']);
            $data['benchmark_source_description'] = str_replace('"', "&quot;", $data['benchmark_source_description']);

        }
        else
        {
            return [];
        }

        return $data;
        
    }

    public function get_adjustment_data($caf_id)
    {
        $q = $this->db->query("SELECT * FROM audit_adjustment_master where caf_id='".$caf_id."' and deleted=0 ORDER BY type,je_no");

        $result = $q->result_array();

        if($q->num_rows() > 0)
        {
            foreach ($result as $key => $value) {
                $parent_id = $value['id'];
                $child_q = $this->db->query("SELECT audit_adjustment_info.*, audit_categorized_account.description FROM audit_adjustment_info 
                                            LEFT JOIN audit_categorized_account on audit_adjustment_info.categorized_account_id = audit_categorized_account.id 
                                            where adjustment_master_id='".$parent_id."' and audit_adjustment_info.deleted=0 ORDER BY adjust_value DESC");

                $child_result = $child_q->result_array();
                if($child_q->num_rows() > 0)
                {
                    $result[$key]['adjustment_info'] = $child_result;
                }
                else
                {
                    $result[$key]['adjustment_info'] = array();
                }
                
            }

            $data = $result;
            

        }
        else
        {
            return [];
        }
        // print_r($data);

        return $data;
    }

    public function get_je_no($caf_id, $type)
    {
        $q = $this->db->query("SELECT je_no FROM audit_adjustment_master where caf_id='".$caf_id."' and type='".$type."' and deleted=0 ORDER BY je_no");

        $result = $q->result_array();
        $temp_arr = array();

        if($q->num_rows() > 0)
        {
            foreach ($result as $key => $value) {
                array_push($temp_arr, $value['je_no']);
            }

        }
        else
        {
            return [];
        }
        // print_r($data);

        return $temp_arr;
    }

    public function get_leadsheet_list($assg_id, $parent_id = '#')
    {
        $q = $this->db->query('SELECT * FROM audit_categorized_account WHERE assignment_id = '.$assg_id.' and type = "Branch" and parent="'.$parent_id.'" ORDER BY order_by');

        $text_value = array();

        $text_value[''] = "Please select"; 
        foreach($q->result() as $val){
            $text_value[$val->id] = $val->description; 
        }
        // $text_value[''] = "Please select"; 

        return $text_value;
    }

    // public function update_adjusted_value($account_ids, $caf_id)

    public function update_adjusted_value($caf_id)
    {
        // $this->db->where_in('id', $account_ids);
        // $q = $this->db->get('audit_categorized_account');
        // $q = $this->db->query("SELECT GROUP_CONCAT(ai.categorized_account_id) as account_ids  FROM audit_adjustment_info ai
        //                         LEFT JOIN audit_adjustment_master am on ai.adjustment_master_id = am.id
        //                         WHERE am.caf_id =".$caf_id);


        $q = $this->db->query("SELECT audit_categorized_account.*  FROM audit_categorized_account
                                LEFT JOIN audit_caf_master cm on cm.assignment_id = audit_categorized_account.assignment_id
                                WHERE cm.id =".$caf_id);

        // $accounts = $q->result();

        // $accounts = explode(',', $accounts[0]->account_ids);

        // $this->db->where_in('id', $accounts);
        // $q = $this->db->get('audit_categorized_account');

        $categorized_account = $q->result();

        // print_r($categorized_account);

        // print_r($categorized_account);
        foreach ($categorized_account as $key => $value) {
            $audit_adjustment_account =  array();
            $audit_adjustment_account = $this->db->query("SELECT ai.*, am.type FROM audit_adjustment_info ai
                                                        LEFT JOIN audit_adjustment_master am on ai.adjustment_master_id = am.id
                                                        WHERE ai.categorized_account_id = " . $value->id .  " and ai.deleted = 0 and am.deleted = 0 and caf_id =".$caf_id);
            $audit_adjustment_account = $audit_adjustment_account->result_array();

            // print_r($audit_adjustment_account);

            $adjusted_value_this_year = $value->value;
            if(count($audit_adjustment_account) > 0)
            {
                foreach ($audit_adjustment_account as $adjustment_key => $adjustment_value) {
             
                    if($adjustment_value['type'] != 8)  //type 8 is SOUE
                    { 
                        $adjusted_value_this_year +=  $adjustment_value['adjust_value'];
                    }

                    
                }
            }
            else
            {
                $adjusted_value_this_year = $value->value;
            }

            // echo "update: " . $adjusted_value_this_year . "id: " . $value->id . "<br/>";

            $this->db->where('id', $value->id);
            $result = $this->db->update('audit_categorized_account', array('adjusted_value' => $adjusted_value_this_year));

        }

        if(isset($categorized_account) && count($categorized_account) > 0)
        {
            $fs_company_info['fs_company_info_id'] = $categorized_account[0]->fs_company_info_id;
            $fs_company_info['assignment_id']      = $categorized_account[0]->assignment_id;
            return $fs_company_info;
        }
        else
        {
            return false;
        }
        // $fs_company_info_id = $categorized_account[0]->fs_company_info_id;
        // return $fs_company_info_id;
        // print_r($categorized_account);


    }

    public function getCafDetail($caf_id)
    {
        $this->db->where_in('id', $caf_id);
        $q = $this->db->get('audit_caf_master');

        $caf_detail = $q->result();

        if($q->num_rows() > 0)
        {
            return $caf_detail[0];
        }
        else
        {
            return [];
        }
    }

    public function get_adjustment_caf_id($assignment_id)
    {
        $q = $this->db->query("SELECT * FROM audit_caf_master WHERE assignment_id=" . $assignment_id . " and type_id = 4");
        $adjustment_caf = $q->result_array();

        if($q->num_rows() > 0)
        {
            return $adjustment_caf[0]['id'];
        }
        else
        {
            return 0;
        }    
    }

    public function get_programme_content($programme_master_id, $caf_id)
    {
        // $this->db->where(array('programme_master_id' => $programme_master_id, 'deleted' => 0, 'type' => 'level_1'));
        // $this->db->order_by('order_by asc');
            //$this->db->where('unique_code', $unique_code);
            //$this->db->where('row_status', 0);
        // $this->db->order_by('id', 'desc');
        // $l1_q = $this->db->get("audit_programme_content");
        $l1_q = $this->db->query('SELECT audit_programme_content.*, yn_caf.id as yn_caf_id, yn_value, remark from audit_programme_content LEFT JOIN audit_programme_yn_input yn_caf on audit_programme_content.id = yn_caf.programme_content_id AND yn_caf.caf_id ='.$caf_id.' where programme_master_id ='.$programme_master_id.' and type="level_1" and deleted=0 order by order_by');
        if ($l1_q->num_rows() > 0) {
            
            $l1 = $l1_q->result_array();

            foreach ($l1 as $l1_key => $l1_each) {
                // $this->db->select('*');
                // $this->db->from('audit_programme_content');
                // $this->db->where(array('parent' => $l1_each->id, 'deleted' => 0));
                // $this->db->where ("(audit_paf_child.type='dynmc' and audit_paf_child.archived='0') or (audit_paf_child.type='fixed' and parent_id =".$parent->id.")");
                // $this->db->order_by('order_time asc, id ASC');
          
          
                // $cq = $this->db->get();

            
                // $child_array = $cq->result_array();
                $child_array = $this->recursive_content_child($l1_each['id'], $programme_master_id, $caf_id);
                $l1[$l1_key]['text'] = str_replace("'", '&#39;', $l1[$l1_key]['text']);
                $l1[$l1_key]['remark'] = str_replace("'", '&#39;', $l1[$l1_key]['remark']);
                $l1[$l1_key]['text'] = str_replace('"', '&quot;', $l1[$l1_key]['text']);
                $l1[$l1_key]['remark'] = str_replace('"', '&quot;', $l1[$l1_key]['remark']);
                $l1[$l1_key]['text'] = str_replace('<', '&#60;', $l1[$l1_key]['text']);
                $l1[$l1_key]['remark'] = str_replace('<', '&#60;', $l1[$l1_key]['remark']);
                $l1[$l1_key]['text'] = str_replace('>', '&#62;', $l1[$l1_key]['text']);
                $l1[$l1_key]['remark'] = str_replace('>', '&#62;', $l1[$l1_key]['remark']);
                $l1[$l1_key]['child'] = $child_array;

                // print_r("HERE");

            }

            return $l1;
        }
    }

    public function get_procedure_lines($master_id, $caf_id)
    {
        $q = $this->db->query('SELECT audit_programme_procedure.*, p_caf.id as procedure_caf_id, yn_value, comment, audit_procedure_id from audit_programme_procedure LEFT JOIN audit_procedure_caf_input p_caf on audit_programme_procedure.id = p_caf.audit_procedure_id AND p_caf.caf_id ='.$caf_id.' where programme_master_id ='.$master_id.' and deleted=0 order by order_by');


        if ($q->num_rows() > 0) {
            // $this->session->set_userdata('selected_programme_type', $q->result()[0]->type);
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            foreach ($data as $key => $value) {
                $data[$key]->child_text = json_decode($value->child_json);
            }

            // print_r($data);
            return $data;
        }
        return FALSE;
    }

    public function get_question_lines($master_id, $caf_id)
    {
        $q = $this->db->query('SELECT audit_programme_qa_question.*, audit_programme_qa_answer.id as qa_caf_id, audit_programme_qa_answer.answer_text from audit_programme_qa_question LEFT JOIN audit_programme_qa_answer on audit_programme_qa_question.id = audit_programme_qa_answer.programme_question_id  AND audit_programme_qa_answer.caf_id ='.$caf_id.' where programme_master_id ='.$master_id.' and deleted=0 order by order_by');


        if ($q->num_rows() > 0) {
            // $this->session->set_userdata('selected_programme_type', $q->result()[0]->type);
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            // print_r($data);
            return $data;
        }
        return FALSE;
    }

    public function get_programme_conclusion($caf_id)
    {
        $q = $this->db->query("SELECT * FROM audit_programme_conclusion_input where caf_id='".$caf_id."'");

        $result = $q->result_array();

        if($q->num_rows() > 0)
        {
            $data = $result[0];

            if(isset($data['planning_conclusion']))
            {
                $data['pc_yes_selected'] = $data['planning_conclusion']==1?"selected":"";
                $data['pc_no_selected'] = $data['planning_conclusion']==0?"selected":"";
            }
            else
            {
                $data['pc_yes_selected'] = "";
                $data['pc_no_selected']  = "";
            }

            if(isset($data['conclusion']))
            {
                $data['c_yes_selected'] = $data['conclusion']==1?"selected":"";
                $data['c_no_selected'] = $data['conclusion']==0?"selected":"";
            }
            else
            {
                $data['c_yes_selected'] = "";
                $data['c_no_selected']  = "";
            }

        }
        else
        {
            return [];
        }

        return $data;
    }

    public function get_currency_dropdown()
    {
        $result = $this->db->query("select * from currency order by id ASC");
        $result = $result->result_array();
        $res = array();
        $res[''] = "";

        foreach($result as $row) {
            $res[$row['id']] = $row['currency'];
        }

        // $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Currency fetched successfully.", 'result'=>$res, 'selected_currency'=>$selected_currency);
        // echo json_encode($data);
        return $res;
    }

    /*---------------------------to retrieve programme content tree leveling child-------------------------------*/
    public function recursive_content_child($parent_id, $programme_master_id, $caf_id) {
        $final_child_array = array();
    
        // $this->db->select('*');
        // $this->db->from('audit_programme_content');
        // $this->db->where(array('parent' => $parent_id, 'deleted' => 0));
        // $this->db->order_by('order_by asc');
  
        // $cq = $this->db->get();
        $cq = $this->db->query('SELECT audit_programme_content.*, yn_caf.id as yn_caf_id, yn_value, remark from audit_programme_content LEFT JOIN audit_programme_yn_input yn_caf on audit_programme_content.id = yn_caf.programme_content_id AND yn_caf.caf_id ='.$caf_id.' where programme_master_id ='.$programme_master_id.' and parent='.$parent_id.' and deleted=0 order by order_by');

        $child_array = $cq->result_array();   

        if(count($child_array)>0 && is_array($child_array)){      
            foreach ($child_array as $key => $val) {
                if(!empty($val['parent']))
                {
                    // echo $val['parent'];
                    $temp_child = $this->recursive_content_child($val['id'], $programme_master_id, $caf_id);
                    // array_push($final_child_array, array('child_array' => $temp_child));
                    $child_array[$key]['text'] = str_replace("'", '&#39;', $child_array[$key]['text']);
                    $child_array[$key]['remark'] = str_replace("'", '&#39;', $child_array[$key]['remark']);
                    $child_array[$key]['text'] = str_replace('"', '&quot;', $child_array[$key]['text']);
                    $child_array[$key]['remark'] = str_replace('"', '&quot;', $child_array[$key]['remark']);
                    $child_array[$key]['text'] = str_replace('<', '&#60;', $child_array[$key]['text']);
                    $child_array[$key]['remark'] = str_replace('<', '&#60;', $child_array[$key]['remark']);
                    $child_array[$key]['text'] = str_replace('>', '&#62;', $child_array[$key]['text']);
                    $child_array[$key]['remark'] = str_replace('>', '&#62;', $child_array[$key]['remark']);
                    $child_array[$key]['child'] = $temp_child;
                }
            } 
        }
          
        return $child_array;
    }

    public function check_caf_index_existance($index, $assg_id)
    {
        
        // $temp_alphas_dropdown = [];
        // $temp_alphas_dropdown = range('A', 'Z');

        $q = $this->db->query("SELECT index_no FROM audit_caf_master where deleted=0 and index_no='".$index."' and assignment_id='".$assg_id."'");

        if($q->num_rows() > 0)
        {
            return false;
        }
        else
        {
            return true;
        }

        // $index_value = array();
        // foreach($q->result() as $val){
            
        //     $index_value[$val->programme_index] = $val->programme_index; 
            
            
        // }

        // $index_array = array_combine($temp_alphas_dropdown, $temp_alphas_dropdown);
        // $new = array('' => "Please Select");

        // if($id == null)
        // {
        //     $index_array = array_diff($index_array, $index_value);
        // }

        // $index_array = array_merge($new, $index_array);

        // return $index_array;
    }


    /* -------------------------------------------- functions for round off -------------------------------------------- */
    // public function child_recursive($data, $collected_array)
    // {   
    //     $temp = [];

    //     foreach ($data as $key => $value) 
    //     {
    //         if(!empty($value['parent_array']))
    //         {
    //             $collected_array = $this->child_recursive($value['child_array'], $collected_array);
    //         }
    //         else
    //         {
    //             array_push($collected_array, $value['child_array']);
    //         }
    //     }

    //     return $collected_array;
    // }

    // public function adjust_child_lowest_highest_decimal($data, $differences, $col_name) // adjust lowest or highest decimal to perform round up or round down
    // {
    //     if(!(round($differences) == 0 || round($differences) == -0))
    //     {
    //         if(round($differences) < 0)     // if differences is less than 0, need to round up
    //         {
    //             for($x = (int)$differences; $x < 0; $x++)
    //             {
    //                 $largest_gap   = 0;
    //                 $located_key_ty = null;

    //                 // take child lowest decimal value.
    //                 foreach ($data[0] as $key => $value) 
    //                 {
    //                     $gap        = null;

    //                     $whole      = floor($value[$col_name]);
    //                     $decimal    = $value[$col_name] - $whole;
    //                     $decimal    = number_format($decimal, 2);

    //                     if($value[$col_name] < 0)
    //                     {
    //                         $decimal = 1 - $decimal;
    //                     }

    //                     if($value[$col_name] > 0 && $decimal < 0.5)   // positive
    //                     {
    //                         $gap = $decimal;
    //                     }
    //                     elseif($value[$col_name] < 0 && $decimal >= 0.5)   // negative
    //                     {
    //                         $gap = 1 - $decimal;
    //                     }

    //                     if(!is_null($gap) && $gap > $largest_gap)
    //                     {
    //                         $located_key_ty = $key;
    //                         $largest_gap   = $gap;
    //                     }
    //                 }

    //                 if(!is_null($located_key_ty)) // if got nearest decimal to 0.5, round up
    //                 {
    //                     // round up highest decimal value.
    //                     $data[0][$located_key_ty][$col_name] = ceil($data[0][$located_key_ty][$col_name]);
    //                 }
    //                 /* -------- round down the value if the list does not have decimal with lower than 0.5 -------- */
    //                 else // for all values does not have decimal lower than 0.5, round down
    //                 {
    //                     $smallest_gap      = 1;
    //                     $located_key_ty_rd = null;

    //                     foreach ($data[0] as $key => $value) 
    //                     {   
    //                         $round_down_gap = null;

    //                         $whole      = floor($value[$col_name]);
    //                         $decimal    = $value[$col_name] - $whole;
    //                         $decimal    = number_format($decimal, 2);

    //                         if($value[$col_name] < 0)
    //                         {
    //                             $decimal = 1 - $decimal;
    //                         }

    //                         if($value[$col_name] > 0 && $decimal >= 0.5)   // positive
    //                         {
    //                             $round_down_gap = $decimal - 0.5;
    //                         }
    //                         elseif($value[$col_name] < 0 && $decimal < 0.5)   // negative
    //                         {
    //                             $round_down_gap = 0.5 - $decimal;
    //                         }
    //                     }

    //                     if(!is_null($round_down_gap) && $round_down_gap < $smallest_gap)
    //                     {
    //                         $located_key_ty_rd = $key;
    //                         $smallest_gap   = $round_down_gap;
    //                     }

    //                     if(!is_null($located_key_ty_rd)) // if got nearest decimal to 0.5, round up
    //                     {
    //                         // round down value > 0.5 and nearest to 0.5
    //                         $data[0][$located_key_ty_rd][$col_name] = floor($data[0][$located_key_ty_rd][$col_name]);
    //                     }
    //                 }
    //                 /* -------- END OF round down the value if the list does not have decimal with lower than 0.5 -------- */
    //             }
    //         }
    //         elseif(round($differences) > 0)     // if differences is more than 0
    //         {
    //             for($y = $differences; $y > 0; $y--)
    //             {
    //                 $located_key_ty = null;
    //                 $smallest_gap   = 1;

    //                 // take child highest decimal value.
    //                 foreach ($data[0] as $key1 => $value) 
    //                 {
    //                     $gap = null;

    //                     $whole      = floor($value[$col_name]);
    //                     $decimal    = $value[$col_name] - ($whole);
    //                     $decimal    = number_format($decimal, 2);

    //                     if($value[$col_name] < 0)
    //                     {
    //                         $decimal = 1 - $decimal;
    //                     }

    //                     if($value[$col_name] > 0 && $decimal >= 0.5)   // positive
    //                     {
    //                         $gap = $decimal - 0.5;
    //                     }
    //                     elseif($value[$col_name] < 0 && $decimal < 0.5)   // negative
    //                     {
    //                         $gap = $decimal;
    //                     }

    //                     if(!is_null($gap) && $gap < $smallest_gap)
    //                     {
    //                         $located_key_ty = $key1;
    //                         $smallest_gap   = $gap;
    //                     }
    //                 }

    //                 if(!is_null($located_key_ty))
    //                 {
    //                     // round down lowest decimal value.
    //                     $data[0][$located_key_ty][$col_name] = floor($data[0][$located_key_ty][$col_name]);
    //                 }
    //                 /* -------- round up the value if the list does not have decimal > 0.5 -------- */
    //                 else
    //                 {
    //                     // print_r(array("hello"));

    //                     $largest_gap = 0;
    //                     $located_key_ty_ru = null;

    //                     foreach ($data[0] as $key1 => $value) 
    //                     {   
    //                         $round_up_gap = null;

    //                         $whole      = floor($value[$col_name]);
    //                         $decimal    = $value[$col_name] - $whole;
    //                         $decimal    = number_format($decimal, 2);

    //                         if($value[$col_name] < 0)
    //                         {
    //                             $decimal = 1 - $decimal;
    //                         }

    //                         if($value[$col_name] > 0 && $decimal < 0.5)   // positive
    //                         {
    //                             $round_up_gap = $decimal;
    //                         }
    //                         elseif($value[$col_name] < 0 && $decimal >= 0.5)   // negative
    //                         {
    //                             $round_up_gap = $decimal - 0.5;
    //                         }
    //                     }

    //                     if(!is_null($round_up_gap) && $round_up_gap > $largest_gap)
    //                     {
    //                         $located_key_ty_ru  = $key1;
    //                         $largest_gap        = $round_up_gap;
    //                     }

    //                     if(!is_null($located_key_ty_ru)) // if got nearest decimal to 0.5, round up
    //                     {
    //                         // round down value > 0.5 and nearest to 0.5
    //                         $data[0][$located_key_ty_ru][$col_name] = ceil($data[0][$located_key_ty_ru][$col_name]);
    //                     }
    //                 }
    //                 /* -------- END OF round down the value if the list does not have decimal with lower than 0.5 -------- */
    //             }
    //         }   
    //     }

    //     return $data;
    // }

    // public function round_off_all_child($expenses_children_list)    // round off all other child account
    // {
    //     foreach ($expenses_children_list[0] as $key => $value) 
    //     {
    //         $expenses_children_list[0][$key]['value']                       = round($value['value']);
    //         $expenses_children_list[0][$key]['company_end_prev_ye_value']   = round($value['company_end_prev_ye_value']);
    //         $expenses_children_list[0][$key]['group_end_this_ye_value']     = round($value['group_end_this_ye_value']);
    //         $expenses_children_list[0][$key]['group_end_prev_ye_value']     = round($value['group_end_prev_ye_value']);
    //     }

    //     return $expenses_children_list;
    // }

    // public function get_account_with_sub_round_off_ids($fs_company_info_id, $id_list)
    // {
    //     $data = [];
     
    //     foreach ($id_list as $key => $id) 
    //     {
    //         $q = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fca.account_code
    //                                 FROM fs_categorized_account_round_off fcaro
    //                                 LEFT JOIN audit_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
    //                                 WHERE fca.id=" . $id . " ORDER BY fca.order_by");

    //         if(count($q->result_array()) > 0)
    //         {
    //             $parent_array = $q->result_array();

    //             $child_array = $this->recursive_sub_round_off_ids($fs_company_info_id, $id);
    //             $total_category = $this->calculate_total_round_off_ids($fs_company_info_id, $id);

    //             $parent_array[0]['total_c']     = $total_category['total_c'];
    //             $parent_array[0]['total_c_lye'] = $total_category['total_c_lye'];
    //             $parent_array[0]['total_g']     = $total_category['total_g'];
    //             $parent_array[0]['total_g_lye'] = $total_category['total_g_lye'];

    //             array_push($data, array('parent_array' => $parent_array, 'child_array' => $child_array));
    //         }
    //     }
        

    //     return $data;
    // }

    // public function recursive_sub_round_off_ids($fs_company_info_id, $id)
    // {
    //     // print_r(array($id));
    //     $temp = [];

    //     $all_account = $this->db->query("SELECT * FROM audit_categorized_account WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
    //     $all_account = $all_account->result_array();

    //     $q = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fca.account_code
    //                             FROM fs_categorized_account_round_off fcaro
    //                             LEFT JOIN audit_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
    //                             WHERE fca.parent=" . $id . " ORDER BY fca.order_by");

    //     if(count($q->result_array()) > 0)
    //     {
    //         foreach ($q->result_array() as $key => $value) 
    //         {
    //             if(array_search($value['fca_id'], array_column($all_account, 'parent'), true))
    //             {
    //                 $child_data = $this->recursive_sub_round_off_ids($fs_company_info_id, $value['fca_id']);

    //                 $total_category = $this->calculate_total_round_off_ids($fs_company_info_id, $value['fca_id']);

    //                 $value['total_c']       = $total_category['total_c'];
    //                 $value['total_c_lye']   = $total_category['total_c_lye'];
    //                 $value['total_g']       = $total_category['total_g'];
    //                 $value['total_g_lye']   = $total_category['total_g_lye'];

    //                 array_push($temp, array('parent_array' => array($value), 'child_array' => $child_data));
    //             }
    //             else
    //             {
    //                 // print_r(array($value['type']));

    //                 if($value['type'] == 'Branch')
    //                 {
    //                     array_push($temp, array('parent_array' => array($value), 'child_array' => []));
    //                 }
    //                 else
    //                 {
    //                     array_push($temp, array('child_array' => $value));
    //                 }
    //             }
    //         }
    //     }

    //     return $temp;
    // }

    // public function calculate_total_round_off_ids($fs_company_info_id, $id) // calculate total of value from the bottom level
    // {   
    //     $total_c     = 0.00;
    //     $total_c_lye = 0.00;
    //     $total_g     = 0.00;
    //     $total_g_lye = 0.00;

    //     if(!empty($id))
    //     {
    //         $all_account = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fca.account_code
    //                             FROM fs_categorized_account_round_off fcaro
    //                             LEFT JOIN audit_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
    //                             WHERE fcaro.fs_company_info_id = " . $fs_company_info_id);

    //         $all_account = $all_account->result_array();

    //         $temp_account_id = [];

    //         array_push($temp_account_id, $id);

    //         do{
    //             $q = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.id AS `fca_id`, fca.account_code
    //                             FROM fs_categorized_account_round_off fcaro
    //                             LEFT JOIN audit_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
    //                             WHERE fca.parent=" . $temp_account_id[0] . " AND fcaro.fs_company_info_id = " . $fs_company_info_id);

    //             if(count($q->result_array()) > 0)
    //             {
    //                 foreach ($q->result_array() as $key => $value) 
    //                 {
    //                     $total_c     += (float)$value['value'];
    //                     $total_c_lye += (float)$value['company_end_prev_ye_value'];
    //                     $total_g     += (float)$value['group_end_this_ye_value'];
    //                     $total_g_lye += (float)$value['group_end_prev_ye_value'];

    //                     if(in_array($value['fca_id'], array_column($all_account, 'parent')))  // if this account got child
    //                     {
    //                         array_push($temp_account_id, $value['fca_id']);
    //                     }
    //                 }
    //             }

    //             unset($temp_account_id[0]);

    //             if(count($temp_account_id) > 0)
    //             {
    //                 $temp_account_id = array_values($temp_account_id);
    //             }
    //         }
    //         while(count($temp_account_id) > 0);
    //     }

    //     if(is_null($total_c))
    //     {
    //         $total_c = 0.00;
    //     }
        
    //     if(is_null($total_c_lye))
    //     {
    //         $total_c_lye = 0.00;
    //     }

    //     return array('total_c' => $total_c, 'total_c_lye' => $total_c_lye, 'total_g' => $total_g, 'total_g_lye' => $total_g_lye);
    // }
    /* -------------------------------------------- END OF functions for round off -------------------------------------------- */
}
?>