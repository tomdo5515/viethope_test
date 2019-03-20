<?php defined('BASEPATH') OR exit('No direct script access allowed');

class FacebookdataModel extends CI_Model
{
    public function get_posts($columns = '', $page = 1, $limit = 10){

        //paging
        $page=($page-1);
        $from = $page*$limit;

        if($columns != ''){
            $this->db->select($columns);
        }else{
            $this->db->select('*');
        }
        $this->db->limit($limit, $from);
        $this->db->from('facebookdata');
        $query = $this->db->get();
        return $query->result_array();
    }

     public function insert_update($id, $full_picture, $link, $created_time, $like, $love, $wow, $sad, $angry, $shares, $source, $description)
    {
        $data=[
            'id'                  => $id,
            'full_picture'        => $full_picture,
            'link'                => $link,
            'created_time'        => $created_time,
            'like'                => $like,
            'love'                => $love,
            'wow'                 => $wow,
            'sad'                 => $sad,
            'angry'               => $angry,
            'shares'              => $shares,
            'source'              => $source,
            'description'         => $description
        ];

        $this->db->where('id',$id);
        $q = $this->db->get('facebookdata');

        if ( $q->num_rows() > 0 )
        {
            $this->db->where('id',$id);
            $this->db->update('facebookdata',$data);
        } else {
            $this->db->insert('facebookdata',$data);
        }
    }

}