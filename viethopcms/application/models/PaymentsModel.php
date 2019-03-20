<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentsModel extends CI_Model
{
    public $id;
    
    public $price;

    public $payer_email;

    public $txn_id;

    public $logs;

    public $status = '0';

     public function insert_update_option($id, $price, $payer_email, $txn_id, $logs, $status=0)
    {
        $data=[
            'price'                 => $price,
            'status'                => $status
        ];

        $this->db->where('id', $id);
        $q = $this->db->get('payments');

        if ( $q->num_rows() > 0 )
        {
            $this->db->where('id', $id);
            $data=[
                'payer_email'           => $payer_email,
                'txn_id'                => $txn_id,
                'logs'                  => $logs,
                'status'                => $status
            ];
            $this->db->update('payments',$data);

            return $id;
        } else {
            $this->db->insert('payments',$data);
            return $this->db->insert_id();
        }
    }

}