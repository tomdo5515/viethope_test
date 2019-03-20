<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rss extends FrontendController {
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
	 
	
	*/
	public function __construct()
    {
		parent::__construct();
		$this->load->model(['ArticleModel']);
		$this->config->load('rssconfig');
		$this->load->helper('xml');
        $this->load->helper('text');
        // Ideally you would autoload the parser
	}
	
	public function loadrss()
	{
		$data['feed_name'] = $this->config->item('feed_name');
		$data['encoding'] = $this->config->item('encoding');
		$data['feed_url'] = $this->config->item('feed_url');
		$data['page_description'] = $this->config->item('page_description');
		$data['page_language'] = $this->config->item('page_language');
		$data['creator_email'] = $this->config->item('creator_email');
		$data['posts'] = $this->ArticleModel->get_rss_articles(30);  
		header("Content-Type: application/rss+xml"); // important!
		$this->twig->set($data);
        $this->twig->display('rss');
	}

}
