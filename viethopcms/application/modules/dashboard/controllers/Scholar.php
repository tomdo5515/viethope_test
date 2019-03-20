<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Scholar extends BackendController {
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

	public function __construct()
    {
		parent::__construct();
		$this->load->library('grocery_CRUD');
		$this->load->model(['PageModel','ArticlemetaModel','TermsModel','LanguageModel']);
        // Ideally you would autoload the parser
    }
	
	public function index()
	{
		$crud = new grocery_CRUD();
		$crud->set_table('articles');
		$crud->set_subject('Scholars');

		$crud->unset_add();
		$crud->unset_edit();
		
		// $crud->display_as('id','Title');
		$crud->display_as('article_title','Scholar title');
		$crud->display_as('article_status','Scholar status');
		$crud->display_as('article_author','Scholar author');
		$crud->display_as('article_date','Create date');
		
		if(!$this->ion_auth->is_admin())
		{
			$crud->unset_delete(); 
			$crud->columns('article_title','article_status','thumbnail','article_date');
			$crud->where('article_author', $this->ion_auth->get_user_id());
		}
		else{
			$crud->callback_after_delete(array($this,'article_after_delete'));
			$crud->set_relation('article_author','users','first_name');
			$crud->columns('article_title','article_author','article_status','thumbnail','article_date');
		}
		
		$crud->where('article_type', ArticleType::SCHOLAR);

		$crud->add_extra_function('Add scholar', '/dashboard/scholar/addscholar','glyphicon-plus');
		$crud->add_action('Edit', '', '/dashboard/scholar/editscholar', 'glyphicon-pencil');
		// $crud->add_action('Delete', '', '/dashboard/scholar/deletearticle', 'glyphicon-trash');
		$crud->set_field_upload('thumbnail','/uploads');
		$output = $crud->render();
		$array = json_decode(json_encode($output), true);

		$this->twig->set($array);
		$this->twig->display('managercrud');
	}

	public function article_after_delete($primary_key)
	{
		return $this->PageModel->after_delete_article($primary_key);
	}
	
	public function addscholar(){
		$data['menus'] = $this->TermsModel->get_children_menus(2);
		$data['languages'] = $this->LanguageModel->get_languages();
		$data['default_language'] = $this->language;
		$this->twig->set($data);
		$this->twig->display('scholar/scholar_add');
	}

	public function editscholar($id){

		// get page
		$data['article_id'] = $id;
		$data['article'] = $this->PageModel->get_scholar_default_lang($id);
		$data['languages'] = $this->LanguageModel->get_languages();
		$articlemetas = $this->ArticlemetaModel->get_articlemetas($id, $this->default_language);
		foreach ($articlemetas as $key => $meta) {
			$data['seo_'.$meta['meta_key']]= $meta['meta_value'];
		}
		// get menus
		$menus = $this->TermsModel->get_children_menus(2);
		$menuselected = $this->TermsModel->get_menus($id);
		$selected = '';
		$data['menus'] = [];
		foreach ($menus as $key => $menu) {
			foreach ($menuselected as $s_key => $s_menu) {
				if($menu['term_id']===$s_menu['term_id']){
					$selected = 'selected';
				}
			}
			array_push($data['menus'], ['term_id'=>$menu['term_id'],'term_name'=>$menu['term_name'], 'selected'=> $selected]);
			$selected = '';
		}
		$this->twig->set($data);
		$this->twig->display('scholar/scholar_edit');
	}

	public function deletescholar($id){

		
	}
	
	// Helper
}

/* End of file Scholar.php */
/* Location: ./application/models/Scholar.php */