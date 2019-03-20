<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends FrontendController {
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

	public function news($offset=0){
		
		$result = $this->ArticleModel->get_n_articles_by_category($this->guest, $offset, ['others','events','programs','news']);
		// load terms
		$length = sizeof($result['articles']);
		for ($i=0; $i < $length; $i++) { 
			$result['articles'][$i]['terms'] = $this->TermsModel->get_article_terms($result['articles'][$i]['id']);
		}
		
		$data['head'] = ['menu'=> 'News','category'=> 'Latest news'];

		$data['articles'] = $result['articles'];

		//Paging
		$config['base_url'] = base_url().'/latest-news/page/';
		$config['first_url'] = base_url(). '/latest-news'; 
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
		$this->twig->display('article/news_list');
	}

	public function meetsholar($offset=0){
		
		$result = $this->ArticleModel->get_n_articles_by_category($this->guest, $offset, ['meet-our-scholars']);
		// load terms
		$length = sizeof($result['articles']);
		for ($i=0; $i < $length; $i++) { 
			$result['articles'][$i]['terms'] = $this->TermsModel->get_article_terms($result['articles'][$i]['id']);
		}
		
		$data['head'] = ['menu'=> 'News','category'=> 'meet our scholars'];

		$data['articles'] = $result['articles'];

		//Paging
		$config['base_url'] = base_url().'/meet-our-scholars/page/';
		$config['first_url'] = base_url(). '/meet-our-scholars'; 
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
		$this->twig->display('article/news_scholar_list');
	}

	public function newsletter($offset=0){
		
		$result = $this->ArticleModel->get_n_articles_by_category($this->guest, $offset, ['president-s-letter','win-email','vn-newsletter']);
		// load terms
		$length = sizeof($result['articles']);
		for ($i=0; $i < $length; $i++) { 
			$result['articles'][$i]['terms'] = $this->TermsModel->get_article_terms($result['articles'][$i]['id']);
		}
		
		$data['head'] = ['menu'=> 'News','category'=> 'viethope newsletter'];

		$data['articles'] = $result['articles'];

		//Paging
		$config['base_url'] = base_url().'/newsletter/page/';
		$config['first_url'] = base_url(). '/newsletter'; 
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
		$this->twig->display('article/news_letter_list');
	}

	public function category($category, $offset=0){
		$result = $this->ArticleModel->get_n_articles_by_category($this->guest, $offset, $category);
		// load terms
		$length = sizeof($result['articles']);
		for ($i=0; $i < $length; $i++) { 
			$result['articles'][$i]['terms'] = $this->TermsModel->get_article_terms($result['articles'][$i]['id']);
		}
		$category_title = "PRESIDENT’S LETTER";
		switch ($category) {
			case 'win-email':
				$category_title = "WINNING EMAIL";
				break;
			case 'vn-newsletter':
				$category_title = "VIETNAM NEWSLETTER";
				break;
		}

		$data['head'] = ['menu'=> 'category','category'=> $category_title];

		$data['articles'] = $result['articles'];

		//Paging
		$config['base_url'] = base_url()."/category/$category/page/";
		$config['first_url'] = base_url()."/category/$category"; 
		$config['total_rows'] = $result['total_page'];
		$config['per_page'] = ArticleShow::ITEM_PER_PAGE;
		$config['num_links'] = 4;
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
		if(in_array($category, ['president-s-letter','win-email','vn-newsletter'])){
			$this->twig->display('article/news_letter_list');
		}
		else{
			$this->twig->display('article/news_list');
		}
	}

	public function tag($tag, $offset=0){
		$result = $this->ArticleModel->get_n_articles_by_tag($this->guest, $offset, $tag);
		// load terms
		$length = sizeof($result['articles']);
		for ($i=0; $i < $length; $i++) { 
			$result['articles'][$i]['terms'] = $this->TermsModel->get_article_terms($result['articles'][$i]['id']);
		}
		
		$data['head'] = ['menu'=> 'tag','category'=> $tag];

		$data['articles'] = $result['articles'];

		//Paging
		$config['base_url'] = base_url()."/tag/$tag/page/";
		$config['first_url'] = base_url()."/tag/$tag"; 
		$config['total_rows'] = $result['total_page'];
		$config['per_page'] = ArticleShow::ITEM_PER_PAGE;
		$config['num_links'] = 4;
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
		$this->twig->display('article/news_list');
	}

	public function author($author, $offset=1){
		$result = $this->ArticleModel->get_n_articles_by_author($this->guest, $offset, $author);
		// load terms
		$length = sizeof($result['articles']);
		for ($i=0; $i < $length; $i++) { 
			$result['articles'][$i]['terms'] = $this->TermsModel->get_article_terms($result['articles'][$i]['id']);
		}
		
		$data['head'] = ['menu'=> 'author','category'=> $author];

		$data['articles'] = $result['articles'];

		//Paging
		$config['base_url'] = base_url()."/author/$author/page/";
		$config['first_url'] = base_url()."/author/$author";
		$config['total_rows'] = $result['total_page'];
		$config['per_page'] = ArticleShow::ITEM_PER_PAGE;
		$config['num_links'] = 4;
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
		$this->twig->display('article/news_list');
	}

	public function detail($id){
		$data['article'] = $this->ArticleModel->get_article($id);
		if($data['article'] != FALSE){
			$data['article']['terms'] = $this->TermsModel->get_article_terms($data['article']['id']);
			$menu = 'News';
			foreach ($data['article']['terms'] as $term) {
				if($term['taxonomy'] == Taxonomy::CATEGORY)
				{
					$menu = $term['term_name'];
					break;
				}
			}
			$data['head'] = ['menu'=> $menu,'category'=> 'Latest news'];
			$data['seo'] = $this->ArticlemetaModel->get_articlemetas($id, $this->language);
			$data['articles'] = $this->ArticleModel->get_rand_articles($this->guest, 5);
			$data['near'] = $this->ArticleModel->get_near_articles($data['article']['id']);
			$this->twig->title($data['article']['article_title']);
		}
		$this->twig->set($data);
		$this->twig->display('article/article_detail');
	}

	// get ajax article
	public function getacticles($page){
		$this->output->set_content_type('application/json');
		$data['result'] = $this->ArticleModel->get_n_articles($page);
		// $this->twig->set($data);
		// $this->twig->display('article_detail');
		if(sizeof($data['result']) > 0){
			$data['errorcode'] = ErrorCode::SUCCESS_ER; 
		}
		else{
			$data['errorcode'] 	= ErrorCode::DATA_EMPTY;
			$data['message']	= "DATA_EMPTY";
			$data['result'] 	= [];
		}
		$this->output->set_output(json_encode($data));
	}

}
