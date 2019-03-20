<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends FrontendController {
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

	public function __construct()
    {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model(['ArticleModel','TermsModel','ArticlemetaModel']);
		$this->lang->load(['article'], $this->language);
		$this->twig->set(["current_id" => 22], NULL, TRUE);
    }

	// public function index(){
		
	// }

	public function searchfulltext($keyword, $offset=0){
		
		$keyword = urldecode($keyword);
		$result = $this->ArticleModel->get_fts_article($this->guest, $keyword);
		// load terms
		$length = sizeof($result['articles']);
		// highline
		$temp = explode(" ", $keyword);
		// use preg_quote to escape the literal strings
		$temp = array_map(function ($val) { return preg_quote($val, "~");}, $temp);
		// stitch the literals together with the variable pattern 
		$pattern = '~('.implode("[^A-Za-z0-9]+", $temp).')~i';
		
		for ($i=0; $i < $length; $i++) { 
			$result['articles'][$i]['terms'] = $this->TermsModel->get_article_terms($result['articles'][$i]['id']);
			// highline
			// here the $1 means the result of the first capture group... 
			// capture group is denoted in the pattern as the text in (). 
			$result['articles'][$i]['article_title'] = preg_replace( $pattern , '<b>$1</b>', $result['articles'][$i]['article_title'] );
			$result['articles'][$i]['article_description'] = preg_replace( $pattern , '<b>$1</b>', $result['articles'][$i]['article_description'] );
		}
		
		$data['head'] = ['menu'=> 'Search','category'=> $keyword];

		$data['articles'] = $result['articles'];

		//Paging
		$config['base_url'] = base_url()."/search/$keyword/page/";
		$config['first_url'] = base_url()."/search/$keyword"; 
		$config['total_rows'] = $result['total_page'];
		$config['per_page'] = ArticleShow::ITEM_PER_PAGE;
		$config['full_tag_open'] = '<div class="paging"><div class="item-inner col"><div class="row"><div class="list-page">';
		$config['full_tag_close'] = '</div></div></div></div>';
		$config['attributes'] = array('class' => 'btn mr-1');
		$config['first_link'] = '<i class="fas fa-angle-double-left"></i>';
		$config['last_link'] = '<i class="fas fa-angle-double-right"></i>';
		$config['next_link'] = '<i class="fas fa-angle-right"></i>';
		$config['prev_link'] = '<i class="fas fa-angle-left"></i>';
		$config['cur_tag_open'] = '<a class="btn active mr-1">';
		$config['cur_tag_close'] = '</a>';
		$this->pagination->initialize($config);
		$data['paging'] = $this->pagination->create_links();
		// end paging 
		$this->twig->set($data);
		$this->twig->display('article/search_result_list');
	}

}
