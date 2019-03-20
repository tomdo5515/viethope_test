<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Terms extends BackendController {
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
		$this->load->library('grocery_CRUD');
		$this->load->model(['TermsModel', 'TermmetaModel', 'LanguageModel']);
        // Ideally you would autoload the parser
    }
	
	public function index()
	{
		try{
			if (!$this->ion_auth->is_admin()) {
				$this->twig->display('_layouts/permission_denied');
			}
			else{
				
			}
		}
		catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
 	}

	public function menu(){

		if (!$this->ion_auth->is_admin()) {
			$this->twig->display('_layouts/permission_denied');
		}
		else{
			$crud = new grocery_CRUD();
			$crud->set_table('terms');
			$crud->set_subject('Menu');

			$crud->unset_add();
			$crud->unset_edit();

			$crud->add_extra_function('Add menu', '/dashboard/terms/addmenu','glyphicon-plus');
			$crud->add_action('Edit', '', '/dashboard/terms/editmenu', 'glyphicon-pencil');

			$crud->set_relation('parent','terms','term_name');
			$crud->where('terms.taxonomy', Taxonomy::MENU);
			$crud->columns('parent','term_name','slug','font_icon','term_order');
			$crud->order_by('term_order,parent','asc');
			$crud->callback_before_delete(array($this,'remove_meta_and_relate_callback'));
			$output = $crud->render();
			$array = json_decode(json_encode($output), true);

			$this->twig->set($array);
			$this->twig->display('managercrud');
		}
	}

	public function addmenu(){
		if (!$this->ion_auth->is_admin()) {
			$this->twig->display('_layouts/permission_denied');
		}
		else{
			$data['languages'] = $this->LanguageModel->get_languages();
			$data['default_language'] = $this->language;
			$this->twig->set($data);
			$this->twig->display('term/menu_add');
		}
	}

	public function editmenu($id){
		if (!$this->ion_auth->is_admin()) {
			$this->twig->display('_layouts/permission_denied');
		}
		else{
			$data['languages'] = $this->LanguageModel->get_languages();
			$data['default_language'] = $this->language;
			$data['term'] = $this->TermsModel->get_any_term($id);
			$data['term_id'] = $id;
			$termmetas = $this->TermmetaModel->get_termmetas($id, $this->language);
			foreach ($termmetas as $key => $meta) {
				$data['seo_'.$meta['meta_key']]= $meta['meta_value'];
			}
			$this->twig->set($data);
			$this->twig->display('term/menu_edit');
		}
	}

	public function categories()
	{
		try
		{
			$crud = new grocery_CRUD();
			$crud->set_table('terms');
			$crud->set_subject('Categories');
			$crud->set_primary_key('term_id','terms');

			$crud->set_relation('parent','terms','term_name');

			$crud->columns('term_id','term_name', 'parent','term_order');
			$crud->display_as('parent','Parent category');
			//$crud->callback_insert(array($this,'covert_slug_insert_callback'));
			//$crud->callback_update(array($this,'covert_term_slug_after_callback'));
			$crud->callback_before_delete(array($this,'remove_meta_and_relate_callback'));
			
			$crud->where('terms.taxonomy', Taxonomy::CATEGORY);

			$output = $crud->render();
			$array = json_decode(json_encode($output), true);
			
			$this->twig->set($array);
			$this->twig->display('managercrud');
		}
		catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function addcategory(){
		if (!$this->ion_auth->is_admin()) {
			$this->twig->display('_layouts/permission_denied');
		}
		else{
			$data['languages'] = $this->LanguageModel->get_languages();
			$data['default_language'] = $this->language;
			$this->twig->set($data);
			$this->twig->display('term/term_add');
		}
	}

	public function editcategory(){
		if (!$this->ion_auth->is_admin()) {
			$this->twig->display('_layouts/permission_denied');
		}
		else{
			$data['languages'] = $this->LanguageModel->get_languages();
			$data['default_language'] = $this->language;
			$this->twig->set($data);
			$this->twig->display('term/term_edit');
		}
	}

	public function tags()
	{
		try{
			$crud = new grocery_CRUD();
			$crud->set_table('terms');
			$crud->set_subject('Quán lý Thẻ');
			$crud->set_relation('term_id','terms_translate','term_name');
			$crud->callback_before_delete(array($this,'remove_meta_and_relate_callback'));

			$crud->columns('term_name','slug','term_order');
			$crud->add_fields('term_name','slug','term_order');

			$crud->display_as('term_name','Thẻ');

			$crud->set_relation('term_id','terms_translate','term_name');

			$crud->where('terms.taxonomy', Taxonomy::TAG);

			$output = $crud->render();
			$array = json_decode(json_encode($output), true);

			$this->twig->set($array);
			$this->twig->display('managercrud');
		}
		catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function addtag(){
		if (!$this->ion_auth->is_admin()) {
			$this->twig->display('_layouts/permission_denied');
		}
		else{
			$data['languages'] = $this->LanguageModel->get_languages();
			$data['default_language'] = $this->language;
			$this->twig->set($data);
			$this->twig->display('term/tag_add');
		}
	}

	public function edittag(){
		if (!$this->ion_auth->is_admin()) {
			$this->twig->display('_layouts/permission_denied');
		}
		else{
			$data['languages'] = $this->LanguageModel->get_languages();
			$data['default_language'] = $this->language;
			$this->twig->set($data);
			$this->twig->display('term/tag_edit');
		}
	}

	// Helper
    function covert_slug_insert_callback($post_array) {
        
        if(trim($post_array['slug'])=='' || is_null($post_array['slug']))
        {
            $post_array['slug'] = slugify($post_array['term_name']);
        }

        if(trim($post_array['parent'])=='' || is_null($post_array['parent']))
        {
            $post_array['parent'] = 0;
        }

        return $this->db->insert('terms',$post_array);
	}
	
	function remove_meta_and_relate_callback($primary_key) {
        
		// remove meta
		$this->TermmetaModel->delete_all_termmeta($primary_key);
		// remove relation
		$this->TermsModel->delete_term_relationships($primary_key);
    }
	
}
