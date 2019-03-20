<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends BackendController {
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
	private $data;

	private $focus = [
		'all' => 'All',
		'student' => 'Student',
		'member' => 'Member',
		'supporter' => 'Supporter',
	];

	public function __construct()
    {
		parent::__construct();
		$this->load->library('grocery_CRUD');
		$this->load->model(['ArticleModel','ArticlemetaModel','TermsModel','LanguageModel']);
        // Ideally you would autoload the parser
    }
	
	public function index()
	{
		$crud = new grocery_CRUD();
		$crud->set_table('articles');
		$crud->set_subject('Article');

		$crud->unset_add();
		$crud->unset_edit();
		
		$crud->display_as('id','Title');
		$crud->set_relation('article_author','users','first_name');
		$crud->columns('article_author','article_title','thumbnail','article_status','article_date');
		if(!$this->ion_auth->is_admin())
		{
			$crud->unset_delete();
			$group = array("moder");
			if (!$this->ion_auth->in_group($group)) {
				$crud->where('article_author', $this->ion_auth->get_user_id());
			}
		}
		else{
			$crud->callback_after_delete(array($this,'article_after_delete'));
		}

		$crud->where('articles.article_type', ArticleType::ARTICLE);
		$crud->order_by('article_time_gmt', 'desc');
		$crud->add_extra_function('Add article', '/dashboard/article/addarticle','glyphicon-plus');
		$crud->add_action('Edit', '', '/dashboard/article/editarticle', 'glyphicon-pencil');
		$crud->set_field_upload('thumbnail','/uploads/images');

		$output = $crud->render();
		$array = json_decode(json_encode($output), true);

		$this->twig->set($array);
		$this->twig->display('managercrud');
	}

	public function article_after_delete($primary_key)
	{
		return $this->ArticleModel->after_delete_article($primary_key);
	}
	
	public function addarticle(){

		$data['categories'] = $this->TermsModel->get_categories();
		$data['languages'] = $this->LanguageModel->get_languages();
		$data['default_language'] = $this->language;

		$data['focus'] = $this->focus;

		$this->twig->set($data);
		$this->twig->display('article/article_add');
	}

	public function editarticle($id){

		// get article
		$data['languages'] = $this->LanguageModel->get_languages();
		$data['article'] = $this->ArticleModel->get_article_default_lang($id);
		$data['article_id'] = $id;
		$data['focus'] = $this->focus;
		$articlemetas = $this->ArticlemetaModel->get_articlemetas($id, $this->default_language);
		foreach ($articlemetas as $key => $meta) {
			$data['seo_'.$meta['meta_key']]= $meta['meta_value'];
		}
		// get categories
		$categories = $this->TermsModel->get_categories();
		$categoryselected = $this->TermsModel->get_categories($id);
		$selected = '';
		$data['categories'] = [];
		foreach ($categories as $key => $category) {
			foreach ($categoryselected as $s_key => $s_category) {
				if($category['term_id']===$s_category['term_id']){
					$selected = 'selected';
				}
			}
			array_push($data['categories'], ['term_id'=>$category['term_id'],'term_name'=>$category['term_name'], 'selected'=> $selected]);
			$selected = '';
		}
		// get tags
		$data['tags'] = $this->TermsModel->get_tags($id);

		$this->twig->set($data);
		$this->twig->display('article/article_edit');
	}

	public function deletearticle($id){

	}
	
	// Helper
}

/* End of file Article.php */
/* Location: ./application/models/Article.php */