<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ProgramModel extends CI_Model
{
    public function get_programs(){
        $this->db->select('*');
        $this->db->from('program');
        $this->db->where('status', 1);
        $this->db->order_by('program_order', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_program($acronym){
        $this->db->select('*');
        $this->db->from('program');
        $this->db->where('acronym', $acronym);
        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            return $query->row();
        } else {
            return FALSE;
        }
    }
}