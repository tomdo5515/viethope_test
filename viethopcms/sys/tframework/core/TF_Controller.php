<?php
/**
 * @package Tframework
 * @author  Joh Tran
 * @copyright   Copyright (c) 2017 - 2020, Gogosolution, Inc.
 * @copyright   Copyright (c) 2017 - 2020, EllisLab, Inc. (https://ellislab.com/)
 * @license http://opensource.org/licenses/MIT  MIT License
 * @link    https://Tframework.com
 * @since   Version 1.0.0
 * @filesource
 */

 (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Common Controler
 *
 * Loads the base classes and executes the request.
 *
 * @package     Tframework
 * @subpackage  Tframework
 * @category    Common controler
 * @author      Joh Tran
 * @link        https://Tframework.com/user_guide/
 */
class CommonController extends MX_Controller//CI_Controller
{
    protected $_config = array();

    // language
    protected $language = NULL;

    protected $default_language = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
        // Mutil languages
        $this->default_language = $this->config->item('language');

        $lang_uri_abbr = $this->config->item('lang_uri_abbr');
        $default_abbr  = $this->config->item('language_abbr');
        $lang_abbr = get_cookie($this->config->item('cookie_prefix').'user_lang', TRUE);

        if (isset($lang_uri_abbr[$lang_abbr])) {
            $this->language = $lang_uri_abbr[$lang_abbr];
        }
        
        if(!$this->language){
            $this->language = $this->default_language;
            set_cookie('user_lang', $default_abbr, $this->config->item('sess_expiration'));
        }

        if($this->language != $this->session->userdata('language'))
        {
            $this->session->set_userdata(['language' => $this->language]);
        }

        //load config
        $this->_config = $this->config->item('twig');

        $this->twig->set('language', $this->language ,TRUE);

        $this->twig->set('assets_folder', assets_folder() ,TRUE);

        //set global variable
        $this->twig->set('base_url', base_url(), TRUE);

        $this->twig->set('current_url', uri_string(), TRUE);

    }
}

/**
 * Backend Controler
 *
 * Loads the base classes and executes the request.
 *
 * @package     Tframework
 * @subpackage  Tframework
 * @category    Backend controler
 * @author      Joh Tran
 * @link        https://Tframework.com/user_guide/
 */
class BackendController extends CommonController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('ion_auth','form_validation'));
        //set theme
        $this->twig->theme($this->_config['default_backend_theme']);
        $this->twig->set('assets_backend_folder', assets_backend_folder() ,TRUE);
        
        $this->lang->load(['dashboard'], $this->default_language);
        
        $this->twig->title($this->lang->line('dashboard_title'));
        $this->twig->title($this->uri->segment(1));

        $uri = $this->uri->segment(2);

        if (!$this->ion_auth->logged_in() AND $uri !== 'login' AND $uri !== 'logout') {
            $prepend = empty($uri) ? '' : '?redirect=' . current_url();
            redirect('dashboard/login' . $prepend);
        }else {
            $current_user = $this->ion_auth->user()->row();
            $this->twig->set('current_user', $current_user ,TRUE);
            $this->twig->set('group_belong', $this->ion_auth->get_users_groups()->result(),TRUE);
        }
        //set global variable
        $this->twig->set('base_url', base_url(), TRUE);
    }
}

// ENABLE if your need api controler
// require_once(SOLUTION . '/libraries/REST_Controller.php');
/**
 * APIs Controler
 *
 * Loads the base classes and executes the request.
 *
 * @package     Tframework
 * @subpackage  Tframework
 * @category    Backend controler
 * @author      Joh Tran
 * @link        https://Tframework.com/user_guide/
 */
// class ResfulController extends REST_Controller
// {
//     function __construct()
//     {
//         // Construct the parent class
//         parent::__construct();

//         //$this->load->library('myencrypt', $this->config->item('data_encryption_key'));
//         // Configure limits on our controller methods
//         // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
//         // $this->methods['user_get']['limit'] = 500; // 500 requests per hour per user/key
//         // $this->methods['user_post']['limit'] = 100; // 100 requests per hour per user/key
//         // $this->methods['user_delete']['limit'] = 50; // 50 requests per hour per user/key
//     }
// }