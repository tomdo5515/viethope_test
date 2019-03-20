<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends FrontendController {
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
		$this->load->model(['ArticleModel','PageModel','PartnerModel','ProgramModel','MilestoneModel']);
		$this->lang->load(['home'], $this->language);
        // $this->load->driver('cache');
		// $this->output->cache(1);
    }

	public function index()
	{
		$data['pins_news'] = $this->ArticleModel->get_pin_articles($this->guest, 3, ['others','events','programs','news','president-s-letter']);
		$length = sizeof($data['pins_news']);
		for ($i=0; $i < $length; $i++) { 
			$data['pins_news'][$i]['terms'] = $this->TermsModel->get_article_terms($data['pins_news'][$i]['id']);
		}
		$data['events'] = $this->MilestoneModel->get_milestones();
		$data['scholars'] = $this->PageModel->get_scholars();
		$data['partners'] = $this->PartnerModel->get_partners();
		$data['programs'] = $this->ProgramModel->get_programs();
		$data['options'] = $this->OptionsModel->get_autoload(OptionType::OP_SETTING);
		$this->twig->set($data);
		$this->twig->display('index');
	}
}
