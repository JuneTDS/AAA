<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//include 'application/js/random_alphanumeric_generator.php';

class Setting_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        
    }

    public function get_default_programme_type(){
        $q = $this->db->query("SELECT id, text_value FROM audit_programme_dropdown where type='programme type' ORDER BY id ");

        $text_value = array();

        $text_value[''] = "Please Select"; 
        foreach($q->result() as $val){
            $text_value[$val->id] = $val->text_value; 
        }
        // $text_value[''] = "Please select"; 

        return $text_value;
    }

    public function get_programme_type($programme_type_id){
        $q = $this->db->query("SELECT * FROM audit_programme_type where id='".$programme_type_id."'");

        $result = $q->result_array();

        return $result[0];
    }

    public function get_default_programme_list()
    {
        $q = $this->db->query("SELECT audit_programme_master.id, programme_index, title, programme_type, CONCAT(first_name,' ',last_name) as user_name FROM audit_programme_master LEFT JOIN users ON users.id = audit_programme_master.last_updated  where deleted=0 and archived=0 ORDER BY programme_index");

        $list = $q->result();

        return $list;
    }

    public function get_programme_list($assg_id)
    {
        $q = $this->db->query("SELECT audit_programme_master.id, programme_index, title, programme_type, CONCAT(first_name,' ',last_name) as user_name 
                                FROM audit_programme_master LEFT JOIN users ON users.id = audit_programme_master.last_updated 
                                where programme_index NOT IN (SELECT index_no FROM `audit_caf_master` where assignment_id ='".$assg_id."' AND programme_master_id IS NOT NULL AND deleted=0) and deleted=0 and archived=0 ORDER BY programme_index");

        $list = $q->result();

        // print_r($list);

        return $list;
    }

    public function get_archived_programme_list()
    {
        $q = $this->db->query("SELECT audit_programme_master.id, programme_index, title, programme_type, CONCAT(first_name,' ',last_name) as user_name, archived_at FROM audit_programme_master LEFT JOIN users ON users.id = audit_programme_master.last_updated  where deleted=0 and archived=1 ORDER BY programme_index");

        $list = $q->result();

        // print_r($list);

        return $list;
    }

    public function get_avail_alpha_index($id)
    {
        
        // $temp_alphas_dropdown = [];
        $temp_alphas_dropdown = range('A', 'Z');

        $q = $this->db->query("SELECT programme_index FROM audit_programme_master where deleted=0 and archived=0");

        $index_value = array();
        foreach($q->result() as $val){
            
            $index_value[$val->programme_index] = $val->programme_index; 
            
            
        }

        $index_array = array_combine($temp_alphas_dropdown, $temp_alphas_dropdown);
        $new = array('' => "Please Select");

        if($id == null)
        {
            $index_array = array_diff($index_array, $index_value);
        }

        $index_array = array_merge($new, $index_array);

        return $index_array;
    }

    public function check_index_existance($index)
    {
        
        // $temp_alphas_dropdown = [];
        // $temp_alphas_dropdown = range('A', 'Z');

        $q = $this->db->query("SELECT programme_index FROM audit_programme_master where deleted=0 and archived=0 and programme_index='".$index."'");

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

        return $index_array;
    }


    public function get_ra_factors()
    {
        $q = $this->db->query("SELECT * FROM audit_programme_ra_factor");

        $factor_array = array();
        foreach($q->result() as $val){
            $factor_array[$val->table_cell_code] = array('id'    => $val->id,
                                            'value' => $val->value); 
        }

        return $factor_array;
    }




    public function get_all_content_tree($programme_id)
    {

        if($programme_id == null)
        {
            $this->db->select('*');
            $this->db->from('audit_content_tree_default');
          
            $q = $this->db->get();

        }
        else
        {
            $q = $this->db->query('SELECT * from audit_programme_content 
                                WHERE programme_master_id = '.$programme_id.' 
                                AND deleted = 0 ORDER BY order_by');

            if($q->num_rows() < 1)
            {
                $this->db->select('*');
                $this->db->from('audit_content_tree_default');
                $q = $this->db->get();

                $insert_default_data = [];
                foreach($q->result_array() as $item)
                {
                    array_push($insert_default_data, 
                        array(
                            "programme_master_id" => $programme_id,
                            "parent" => $item["parent"],
                            "text"   => $item["text"],
                            'type'   => $item["type"]
                        )
                    );
                }

                $this->db->insert_batch('audit_programme_content', $insert_default_data);

                $q = $this->db->query('SELECT * from audit_programme_content 
                                WHERE programme_master_id = '.$programme_id.' 
                                AND deleted = 0 ORDER BY order_by');

            }
        }

        if ($q->num_rows() > 0 && $programme_id == null) {
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
                                        'id' => ""
                                    )
                    )
                );
            }

            return $data;


        }
        else if($q->num_rows() > 0)
        {
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

    public function get_edit_programme($id)
    {
        $q = $this->db->query('SELECT * from audit_programme_master where id ='.$id);


        if ($q->num_rows() > 0) {
            // $this->session->set_userdata('selected_programme_type', $q->result()[0]->type);
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data[0];
        }
        return FALSE;
    }

    public function get_objective_lines($master_id)
    {
        $q = $this->db->query('SELECT audit_programme_objectives.*, dropdown.text_value as assertion_text from audit_programme_objectives LEFT JOIN audit_programme_dropdown dropdown ON assertion = dropdown.id where programme_master_id ='.$master_id.' and deleted=0 order by order_by');


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

    public function get_procedure_lines($master_id)
    {
        $q = $this->db->query('SELECT * from audit_programme_procedure where programme_master_id ='.$master_id.' and deleted=0 order by order_by');


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

    public function get_question_lines($master_id)
    {
        $q = $this->db->query('SELECT * from audit_programme_qa_question where programme_master_id ='.$master_id.' and deleted=0 order by order_by');


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

    public function retrieve_previous_record($index, $programme_type){

        $q = $this->db->query('SELECT id FROM audit_programme_master where programme_index="'.$index.'" and programme_type ='.$programme_type.' and archived=1 and deleted=0 ORDER BY archived_at DESC LIMIT 1');

        if ($q->num_rows() > 0) {
            // $this->session->set_userdata('selected_programme_type', $q->result()[0]->type);
            $id = $q->result();

            $id = $id[0]->id;

            if($programme_type == 1)
            {
                $edit_programme = $this->get_edit_programme($id);
                $objective_lines = $this->get_objective_lines($id);
                foreach ($objective_lines as $key => $value) {
                    unset($objective_lines[$key]->id);
                }
                $procedure_lines = $this->get_procedure_lines($id);
                foreach ($procedure_lines as $key => $value) {
                    unset($procedure_lines[$key]->id);
                }

                $contentTree = $this->get_all_content_tree($id);
                foreach ($contentTree as $key => $value) {
                    // unset($contentTree[$key]['id']);
                    unset($contentTree[$key]['data']);
                }
                // print_r($contentTree);

                $data = array('edit_programme' => $edit_programme , 'objective_lines' => $objective_lines, 'procedure_lines' => $procedure_lines, 'contentTree_json' => $contentTree);
                return $data;
            }
            else if($programme_type == 2)
            {
                $edit_programme = $this->get_edit_programme($id);
             

                $contentTree = $this->get_all_content_tree($id);
                foreach ($contentTree as $key => $value) {
                    // unset($contentTree[$key]['id']);
                    unset($contentTree[$key]['data']);
                }
                // print_r($contentTree);

                $data = array('edit_programme' => $edit_programme , 'contentTree_json' => $contentTree);
                return $data;
            }
            else if($programme_type == 3)
            {
                $edit_programme = $this->get_edit_programme($id);
             

                $question_lines = $this->get_question_lines($id);
                foreach ($question_lines as $key => $value) {
                    // unset($contentTree[$key]['id']);
                    unset($question_lines[$key]->id);
                }
                // print_r($contentTree);

                $data = array('edit_programme' => $edit_programme , 'question_lines' => $question_lines);
                return $data;
            }
            else if($programme_type == 4 || $programme_type == 5 || $programme_type == 6)
            {
                $edit_programme = $this->get_edit_programme($id);
             

        
                $data = array('edit_programme' => $edit_programme);
                return $data;
            }
            



        }
        else
        {
            return FALSE;
        }


        
    }


    public function submit_audit_programme_info($data)
    {
        $data['programme_index'] = strtoupper($data['programme_index']);
       
        if($data['id'] != '')
        {
            // $query = $query_check->result_array();
            // print_r($data);


            $this->db->where('id', $data['id']);
            $this->db->update('audit_programme_master', $data);

            $q = $this->db->query('SELECT id FROM audit_caf_master where programme_master_id="'.$data['id'].'"');
            if ($q->num_rows() > 0) {
                // $this->session->set_userdata('selected_programme_type', $q->result()[0]->type);
                $temp = $q->result_array();

                foreach($temp as $existing_caf_id)
                {
                    $this->db->where('id', $existing_caf_id['id']);
                    $this->db->update('audit_caf_master', array('name' => $data['title'] ));
                }
                

            }

            $result = $data['id'];
        }
        else
        {
            $this->db->insert('audit_programme_master', $data); 
            $result = $this->db->insert_id();
        }


        return $result;
    }

    public function insert_audit_programme_objectives($data)
    {
        if($data['id'] != null)
        {
            $this->db->where('id', $data['id']);

            $result = $this->db->update('audit_programme_objectives', $data);
        }
        else
        {
            $result = $this->db->insert('audit_programme_objectives', $data); 
        }
        return $result;
    }

    public function insert_audit_programme_procedure($data)
    {
        if($data['id'] != null)
        {
            $this->db->where('id', $data['id']);

            $result = $this->db->update('audit_programme_procedure', $data);
        }
        else
        {
            $result = $this->db->insert('audit_programme_procedure', $data); 
        }
        return $result;
    }

    public function insert_audit_programme_question($data)
    {
        if($data['id'] != null)
        {
            $this->db->where('id', $data['id']);

            $result = $this->db->update('audit_programme_qa_question', $data);
        }
        else
        {
            $result = $this->db->insert('audit_programme_qa_question', $data); 
        }
        return $result;
    }

    public function update_ra_factor($data)
    {
        if($data['id'] != null)
        {
            $this->db->where('id', $data['id']);

            $result = $this->db->update('audit_programme_ra_factor', $data);
        }
        
        return $result;
    }

    public function delete_audit_objective($arr_delete_row)
    {
        if(count($arr_delete_row) > 0)
        {
            $arr_delete_row = explode(',',$arr_delete_row);
            $this->db->where_in('id', $arr_delete_row);
            $this->db->update('audit_programme_objectives',  array('deleted' => 1 ));
        }
    }

    public function delete_audit_procedure($arr_delete_row)
    {
        if(count($arr_delete_row) > 0)
        {
            $arr_delete_row = explode(',',$arr_delete_row);
            $this->db->where_in('id', $arr_delete_row);
            $this->db->update('audit_programme_procedure',  array('deleted' => 1 ));
        }
    }

    public function delete_audit_question($arr_delete_row)
    {
        if(count($arr_delete_row) > 0)
        {
            $arr_delete_row = explode(',',$arr_delete_row);
            $this->db->where_in('id', $arr_delete_row);
            $this->db->update('audit_programme_qa_question',  array('deleted' => 1 ));
        }
    }

    public function archive_programme($programme_id)
    {

        $this->db->where('id', $programme_id);

        $result = $this->db->update('audit_programme_master', array('archived' => 1, 'archived_at' =>  date("Y-m-d H:i:s")));

        return $result;
    
    }

    public function get_programme_type_dropdown(){
        $q = $this->db->query("SELECT id, type_name FROM audit_programme_type ORDER BY id ");

        $text_value = array();

        // $text_value[''] = "Please Select"; 
        foreach($q->result() as $val){
            $text_value[$val->id] = $val->type_name; 
        }
        // $text_value[''] = "Please select"; 

        return $text_value;
    }

    // public function get_choose_carry_forward_list(){
    //     $list = $this->db->query("SELECT * FROM payroll_choose_carry_forward");

    //     $choose_carry_forward_list = array();
    //     $choose_carry_forward_list[''] = 'Please Select';

    //     foreach($list->result()as $item){
    //         $choose_carry_forward_list[$item->id] = $item->choose_carry_forward_name;
    //     }

    //     return $choose_carry_forward_list;
    // }

    // public function get_approval_cap_list()
    // {
    //     $list = $this->db->query('SELECT * FROM payroll_approval_cap');

    //     return $list->result();
    // }

    // public function get_holiday_list(){

    //     $list = $this->db->query('SELECT * FROM payroll_block_holiday WHERE deleted = 0 AND year(holiday_date)='. date("Y") . ' ORDER BY holiday_date');

    //     return $list->result();
    // }

    // public function get_type_of_leave_list(){

    //     $list = $this->db->query('SELECT payroll_type_of_leave.*, payroll_choose_carry_forward.choose_carry_forward_name FROM payroll_type_of_leave LEFT JOIN payroll_choose_carry_forward ON payroll_choose_carry_forward.id = payroll_type_of_leave.choose_carry_forward_id WHERE deleted = 0 ORDER BY leave_name');

    //     return $list->result();
    // }

    // public function get_leave_cycle_list(){
    //     $list = $this->db->query('SELECT * FROM payroll_leave_cycle');

    //     return $list->result();
    // }

    // public function get_carry_forward_period_list(){
    //     $list = $this->db->query('SELECT * FROM payroll_carry_forward_period');

    //     return $list->result();
    // }

    // public function submit_type_of_leave($data, $id){

    //     if($id != null)
    //     {
    //         $this->db->where('id', $id);

    //         $result = $this->db->update('payroll_type_of_leave', $data);
    //     }
    //     else
    //     {
    //         $result = $this->db->insert('payroll_type_of_leave', $data); 
    //     }
    //     return $result;
    // }

    // public function delete_type_of_leave($type_of_leave_id){

    //     $this->db->where('id', $type_of_leave_id);

    //     $result = $this->db->update('payroll_type_of_leave', array('deleted' => 1));

    //     return $result;
    // }

    // public function submit_holiday($data, $id){

    //     if($id != null)
    //     {
    //         $this->db->where('id', $id);

    //         $result = $this->db->update('payroll_block_holiday', $data);
    //     }
    //     else
    //     {
    //         $result = $this->db->insert('payroll_block_holiday', $data);    // insert new customer to database
    //     }

    //     return $result;
    // }

    // public function delete_holiday($holiday_id){

    //     $this->db->where('id', $holiday_id);

    //     $result = $this->db->update('payroll_block_holiday', array('deleted' => 1));

    //     return $result;
    // }

    // public function submit_approval_cap($data, $id){

    //     if($id != null)
    //     {
    //         $this->db->where('id', $id);

    //         $result = $this->db->update('payroll_approval_cap', $data);
    //     }
    //     else
    //     {
    //         $result = $this->db->insert('payroll_approval_cap', $data); 
    //     }

    //     return $result;
    // }

    // public function submit_leave_cycle($data, $id){

    //     if($id != null)
    //     {
    //         $this->db->where('id', $id);

    //         $result = $this->db->update('payroll_leave_cycle', $data);
    //     }
    //     else
    //     {
    //         $result = $this->db->insert('payroll_leave_cycle', $data); 
    //     }

    //     return $result;
    // }

    // public function submit_carry_forward_period($data, $id){
    //     if($id != null)
    //     {
    //         $this->db->where('id', $id);

    //         $result = $this->db->update('payroll_carry_forward_period', $data);
    //     }
    //     else
    //     {
    //         $result = $this->db->insert('payroll_carry_forward_period', $data); 
    //     }

    //     return $result;
    // }

    // public function submit_block_leave($data, $id){

    //     if($id != null)
    //     {
    //         $this->db->where('id', $id);

    //         $result = $this->db->update('payroll_block_leave', $data);    // insert new customer to database
    //     }
    //     else
    //     {
    //         $result = $this->db->insert('payroll_block_leave', $data);    // insert new customer to database
    //     }

    //     return $result;
    // }

    // public function delete_block_leave($block_leave_id){

    //     $this->db->where('id', $block_leave_id);

    //     $result = $this->db->update('payroll_block_leave', array('deleted' => 1));

    //     return $result;
    // }


}
?>