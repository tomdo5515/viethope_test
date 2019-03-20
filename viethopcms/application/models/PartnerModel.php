<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PartnerModel extends CI_Model
{
    public $id;
    
    public $name;

    public $link;

    public $thumbnail;

    public $status = 1;

    public function get_partners(){
        $this->db->select('*');
        $this->db->from('partner');
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

}