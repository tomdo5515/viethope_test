<?php defined('BASEPATH') OR exit('No direct script access allowed');

class TermmetaModel extends CI_Model
{
    public $meta_id;
    
    public $term_id;
    
    public $meta_key;

    public $meta_value;

    public $language_code;

    // READ
    public function get_termmetas($term_id, $language_code)
    {
        $this->db->select('meta_id, meta_key, meta_value');
        $this->db->from('termmeta');
        $this->db->where('term_id', $term_id);
        $this->db->where('language_code', $language_code);
        $query = $this->db->get();
        return $query->result_array();
    }

    // CREATE
    public function insert_update_termmeta($term_id, $meta_key, $meta_value, $language_code)
    {
        $this->term_id  = $term_id; // please read the below note
        $this->meta_key    = $meta_key;
        $this->meta_value  = $meta_value;
        $this->language_code  = $language_code;
        
        $this->db->where('term_id',$term_id);
        $this->db->where('meta_key',$meta_key);
        $this->db->where('language_code',$language_code);
        $q = $this->db->get('termmeta');

        if ( $q->num_rows() > 0 )
        {
            $this->db->update('termmeta', ['meta_value'=>$meta_value], ['term_id' => $term_id, 'meta_key' => $meta_key, 'language_code' => $language_code]);
            return $term_id;
        } else {
            if($this->db->insert('termmeta', $this))
                return $term_id;
            else
                return FALSE;
        }
    }

    // DELETE
    public function delete_termmeta()
    {
        $this->db->delete('termmeta', ['meta_id' => $this->input->post('meta_id')]);
    }

    public function delete_all_termmeta($term_id)
    {
        $this->db->delete('termmeta', ['term_id' => $term_id]);
    }
}
