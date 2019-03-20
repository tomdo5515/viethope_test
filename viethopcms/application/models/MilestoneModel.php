<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MilestoneModel extends CI_Model
{
    public function get_milestones(){
        $this->db->select('*');
        $this->db->from('milestone');
        $this->db->where('status', 1);
        $this->db->order_by('event_time', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

}