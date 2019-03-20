<?php defined('BASEPATH') or exit('No direct script access allowed');

class GalleryModel extends CI_Model
{
    public $id;
    
    public $term_id;

    public $image;

    // READ
    public function get_gallery($menu_id=FALSE)
    {
        $this->db->select('*');
        $this->db->from('gallery');

        if($menu_id)
        {
            $this->db->where('term_id', $menu_id);
        }

        $query = $this->db->get();
        return $query->result_array();
    }
}
