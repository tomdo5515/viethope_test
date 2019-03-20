<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Paypal extends FrontendController {
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
		$this->load->model(['PaymentsModel']);
		$this->lang->load(['home'], $this->language);
        // $this->load->driver('cache');
		// $this->output->cache(1);
    }

	public function index()
	{
		$this->twig->set($data);
		$this->twig->display('index');
	}

	public function canceled($idtrans)
	{
		//debug(serialize($this->input->post()));
		$order_token = $this->PaymentsModel->insert_update_option($idtrans, '', '', '', serialize($this->input->post()), -1);
		redirect('/', 'refresh');
	}

	public function success($idtrans)
	{
		//debug(serialize($this->input->post()));
		$order_token = $this->PaymentsModel->insert_update_option($idtrans, '', '', '', serialize($this->input->post()), 1);
		redirect('/', 'refresh');
	}

}
