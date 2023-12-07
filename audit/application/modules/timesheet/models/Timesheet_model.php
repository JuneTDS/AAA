<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
include 'application/js/random_alphanumeric_generator.php';

class Timesheet_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_all_timesheet(){
        $q = $this->db->query("SELECT t.*, e.name AS `employee_name` FROM timesheet t LEFT JOIN employee e ON e.id = t.employee_id ORDER BY t.month DESC");

        return $q->result();
    }

    public function get_timesheet($timesheet_id){
        $q = $this->db->query("SELECT t.*, e.name AS `employee_name` FROM timesheet t LEFT JOIN employee e ON e.id = t.employee_id WHERE t.id=". $timesheet_id);

        return $q->result();
    }

    public function get_employee_timesheet($employee_id){
        $q = $this->db->query("SELECT t.*, e.name AS `employee_name` FROM timesheet t LEFT JOIN employee e ON e.id = t.employee_id WHERE t.employee_id=". $employee_id ." ORDER BY t.month DESC");

        return $q->result();
    }

    public function create_timesheet($data){
        $data['timesheet_no'] = random_code(8);
        $result = $this->db->insert('timesheet', $data);    // insert new record to database

        return $result;
    }

    public function get_years(){
        $q = $this->db->query("SELECT YEAR(month) AS `year` FROM timesheet GROUP BY YEAR(month) ORDER BY YEAR(month) DESC");

        $years = array();

        foreach($q->result() as $row){
            $years[$row->year] = $row->year; 
        }

        return $years;
    }

    public function get_months_from_this_year($year){
        $q = $this->db->query("SELECT MONTH(month) AS `month` FROM timesheet WHERE YEAR(month) = ". $year ." GROUP BY MONTH(month)");

        return $q->result();
    }

    public function get_list_from_year_month($year, $month){
        $q = $this->db->query("SELECT t.*,  e.name AS `employee_name` FROM timesheet t LEFT JOIN employee e ON e.id = t.employee_id  WHERE YEAR(t.month) = ". $year ." AND MONTH(t.month) = ". $month);

        return $q->result();
    }

    public function edit_timesheet($data, $timesheet_id){
        $this->db->where('id', $timesheet_id);
        $result = $this->db->update('timesheet', $data);

        return $result;
    }
}
?>