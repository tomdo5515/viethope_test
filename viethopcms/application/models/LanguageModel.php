<?php defined('BASEPATH') or exit('No direct script access allowed');

class LanguageModel extends CI_Model
{
  
    public $language_code;

    public $name;

    public $flag;

    public $directory;

    public $status;

    
    // READ
    
    public function get_languages()
    {
        $this->db->select('*');
        $this->db->from('languages');
        $this->db->where('status', LanguageStatus::PUBLISH);
        $query = $this->db->get();
        return $query->result_array();
    }
}
