<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GST extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library(array('session'));
        $this->load->model(array('master_model'));

        $this->bc = array(array('link' => '/gst', 'page' => lang('gst')));
        $this->meta = array('page_title' => lang('gst'), 'bc' => $this->bc, 'page_name' => 'GST');
    }

    public function index()
    {
    	$this->data["gst"] = $this->master_model->getGSTLists();
        $this->page_construct('gst/all.php', $this->meta, $this->data);
    }

    public function edit($id)
    {
    	$this->data["gst"] = $this->master_model->getGST($id);
        $this->data["jurisdiction"] = $this->master_model->getJurisdiction();
        $this->page_construct('gst/edit.php', $this->meta, $this->data);
    }

    public function create()
    {
        $this->data["jurisdiction"] = $this->master_model->getJurisdiction();
        $this->page_construct('gst/create.php', $this->meta, $this->data);
    }

    public function save()
    {
        try {
            if (isset($_POST['end_date']) && $_POST['end_date'] != "") {
                $sql = "INSERT INTO `gst_category_info` 
                    (`gst_category_id`, `jurisdiction_id`, `start_date`, `end_date`, `rate`, `deleted`)
                    VALUES
                    (".($_POST['jurisdiction'] == 1 ? 1 : 2).",
                    ".(isset($_POST['jurisdiction']) ? $_POST['jurisdiction'] : 1).",
                    '".(isset($_POST['start_date']) ? $_POST['start_date'] : date("Y-m-d"))."',
                    '".$_POST['end_date']."',
                    ".(isset($_POST['rate']) ? $_POST['rate'] : 1).", false)";
            } else {
                $sql = "INSERT INTO `gst_category_info` 
                    (`gst_category_id`, `jurisdiction_id`, `start_date`, `rate`, `deleted`)
                    VALUES
                    (".($_POST['jurisdiction'] == 1 ? 1 : 2).",
                    ".(isset($_POST['jurisdiction']) ? $_POST['jurisdiction'] : 1).",
                    '".(isset($_POST['start_date']) ? $_POST['start_date'] : date("Y-m-d"))."',
                    ".(isset($_POST['rate']) ? $_POST['rate'] : 1).", false)";
            }

            $result = $this->db->query($sql);

            echo json_encode(array("Status" => 1, 'message' => 'Information created', 'title' => 'Created'));
        } catch (\Throwable $th) {
            echo json_encode(array("Status" => 0, 'message' => 'Information create was fail', 'title' => 'Create Fail'));
        }
        
    }

    public function update()
    {
        try {
            $gst = $this->master_model->getGST($_POST['id']);
            
            $sql = "UPDATE `gst_category_info` SET 
                `jurisdiction_id`= ".(isset($_POST['jurisdiction']) ? $_POST['jurisdiction'] : $gst[0]->jurisdiction_id).",
                `start_date`= '".(isset($_POST['start_date']) ? $_POST['start_date'] : $gst[0]->start_date)."',
                `end_date`= '".(isset($_POST['end_date']) ? $_POST['end_date'] : $gst[0]->end_date)."',
                `rate`= ".(isset($_POST['rate']) ? $_POST['rate'] : $gst[0]->rate).",
                `deleted`= ".(isset($_POST['deleted']) ? $_POST['deleted'] : $gst[0]->deleted)."
                WHERE id = ".$_POST['id'];

            $result = $this->db->query($sql);

            echo json_encode(array("Status" => 1, 'message' => 'Process was successed', 'title' => 'Success'));
        } catch (\Throwable $th) {
            echo json_encode(array("Status" => 0, 'message' => 'Process was failed', 'title' => 'Fail'));
        }
    }

    public function delete()
    {
        try {
            $sql = "UPDATE `gst_category_info` SET 
                `deleted`= true
                WHERE id = ".$_POST['id'];

            $result = $this->db->query($sql);

            echo json_encode(array("Status" => 1, 'message' => 'Process was successed', 'title' => 'Success'));
        } catch (\Throwable $th) {
            echo json_encode(array("Status" => 0, 'message' => 'Process was failed', 'title' => 'Fail'));
        }
    }
}