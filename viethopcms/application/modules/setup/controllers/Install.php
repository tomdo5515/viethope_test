<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class install extends CommonController {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 
	public function __construct()
    {
        parent::__construct();

        // Ideally you would autoload the parser
    }
	*/
    private static $limit = 13;

	public function __construct()
    {
        parent::__construct();
        $this->load->helper('date');
        $this->load->library('migration');
    }

	public function index()
	{
        // if(is_cli())
        if(true)
        {
            if ($this->migration->current() === FALSE)
            {
                show_error($this->migration->error_string());
            }

            echo "complete!";
        }
        else
        {
            echo "hello word!!!";
        }
	}
}
