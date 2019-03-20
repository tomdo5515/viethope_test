<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LanguageSwitcher extends FrontendController
{
    public function __construct() {
        parent::__construct();     
    }
 
    function switchLang($language = "") {
        
        $language = ($language != "") ? $language : "english";
        $this->session->set_userdata('language', $language);
        $this->language = $language;
        redirect($_SERVER['HTTP_REFERER']);
    }
}