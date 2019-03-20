<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ArticlemetaModel extends CI_Model
{
    public $meta_id;

    public $article_id;

    public $meta_key;

    public $meta_value;

    public $language_code;
    // READ
    public function get_articlemetas($article_id, $language_code)
    {
        $this->db->select('meta_id, meta_key, meta_value');
        $this->db->from('articlemeta');
        $this->db->where('article_id', $article_id);
        $this->db->where('language_code', $language_code);
        $query = $this->db->get();
        return $query->result_array();
    }

    // CREATE OR UPDATE
    public function insert_update_articlemeta($article_id, $meta_key, $meta_value, $language_code)
    {
        $this->article_id  = $article_id; // please read the below note
        $this->meta_key    = $meta_key;
        $this->meta_value  = $meta_value;
        $this->language_code  = $language_code;
        
        $this->db->where('article_id',$article_id);
        $this->db->where('meta_key',$meta_key);
        $this->db->where('language_code',$language_code);
        $q = $this->db->get('articlemeta');

        if ( $q->num_rows() > 0 )
        {
            $this->db->update('articlemeta', ['meta_value'=>$meta_value], ['article_id' => $article_id, 'meta_key' => $meta_key, 'language_code' => $language_code]);
            return $article_id;
        } else {
            if($this->db->insert('articlemeta', $this))
                return $this->db->insert_id();
            else
                return FALSE;
        }
    }

    // DELETE
    public function delete_articlemeta()
    {
        $this->db->delete('articlemeta', ['article_id' => $this->input->post('article_id')]);
    }

    // HELPER
    function isExistKey()
	{
        //check  exist
        $this->db->select('meta_id');
        $this->db->from('articlemeta');
        $this->db->where('article_id', $this->article_id);
        $this->db->where('meta_key', $this->meta_key);
		$this->db->limit(1);
		$query = $this->db->get();

		if($query -> num_rows() > 0)
			return $query->unbuffered_row()->meta_id;
		else
		    return FALSE;
    }
}
