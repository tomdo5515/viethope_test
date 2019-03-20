<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class TypeSwitcher extends FrontendController
{
    public function __construct() {
        parent::__construct();     
    }
 
    function switchType($type = "") {
        
        $this->guest = $type;

        set_cookie('guestmark', $type, '31536000'); // 1 year

        redirect($_SERVER['HTTP_REFERER']);
    }
}