<?php defined('BASEPATH') OR exit('No direct script access allowed');

class OptionsModel extends CI_Model
{
    public $option_id;
    
    public $option_key;

    public $option_type;

    public $option_value;

    public $autoload = 'no';

    public function get_options($option_type){
        $this->db->select('*');
        $this->db->from('options');
        $this->db->where('option_type', $option_type);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_autoload($option_type = 'op_setting'){
        $this->db->select('*');
        $this->db->from('options');
        $this->db->where('autoload', 'yes');
        $this->db->where('option_type', $option_type);
        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            $temps = $query->result_array();
            $arr_result = [];
            foreach ($temps as $op) {
                $arr_result[$op["option_key"]] = $op["option_value"];
            }
            return $arr_result;
        } else {
            return FALSE;
        }
    }

    public function get_setting($option_key){
        $this->db->select('*');
        $this->db->from('options');
        $this->db->where('option_key', $option_key);
        $this->db->where('option_type', 'op_setting');
        $query = $this->db->get();

        if ( $query->num_rows() > 0 )
        {
            return $query->row();
        } else {
            return FALSE;
        }
    }

     public function insert_update_option($option_key, $option_value, $option_type = 'op_setting', $autoload = 'no')
    {
        $data=[
            'option_key'            => $option_key,
            'option_value'          => $option_value,
            'option_type'           => $option_type,
            'autoload'              => $autoload
        ];

        $this->db->where('option_key',$option_key);
        $this->db->where('option_type',$option_type);
        $q = $this->db->get('options');

        if ( $q->num_rows() > 0 )
        {
            $this->db->where('option_key',$option_key);
            $this->db->where('option_type',$option_type);
            $this->db->update('options',$data);
        } else {
            $this->db->insert('options',$data);
        }
    }

}