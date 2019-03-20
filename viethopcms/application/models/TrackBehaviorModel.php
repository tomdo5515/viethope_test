<?php defined('BASEPATH') or exit('No direct script access allowed');

class TrackBehaviorModel extends CI_Model
{
    public $id;
    
    public $ip_address;

    public $sessionid;

    public $time;

    public $url_request;

    public $user_id;

    public $ipinfo;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('httpcall');
    }

    public function track($user_id)
    {
        $this->ip_address = $this->input->ip_address();
        $url = "http://api.ipinfodb.com/v3/ip-city/";
        $data = [
            'key'=>'6e1c564e16581199f97ca7ebc288fff7a342663ccd2e50369a77e02f40c14bc1',
            'ip'=>$this->ip_address,
            'format' => 'json'
        ];
        $this->sessionid = '';

        $ipinfo = Call_API($url, $data);

        if($ipinfo['errorcode'] == 0){
            $this->ipinfo = $ipinfo['result'];
        }

        $this->user_id              = $user_id; // please read the below note
        $this->time                 = date("Y-m-d H:i:s");
        $this->url_request          = current_url();

        if($this->db->insert('track_behavior', $this)){
            return $this->db->insert_id();
        }
        else
            return FALSE;
    }
}
