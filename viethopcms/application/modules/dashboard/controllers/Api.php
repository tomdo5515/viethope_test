<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends BackendController {
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
	private $result = ['errorcode' => '0', 'result' => 'if you can call this, please contact thao31b@gmail.com'];

	public function __construct()
    {
		parent::__construct();
        // Ideally you would autoload the parser
		$this->output->set_content_type('application/json');
		$this->load->model(['ArticleModel','ArticlemetaModel','TermsModel','PageModel']);
    }
	
	public function index()
	{
        $this->output->set_output(json_encode($this->result));
	}

	public function addarticle()
	{
		try{
			$ar_res = $this->ArticleModel->insert_article($this->ion_auth->get_user_id());
			$this->result['result'] = site_url('dashboard/article/editarticle/' . $ar_res);
			if($ar_res===FALSE)
			{
				$this->result['errorcode'] = ErrorCode::DATABASE_ER;
				$this->output->set_output(json_encode($result));
				return;
			}
			$metas = $this->input->post('metas');
			$language_code = $this->input->post('language');
			if(isset($metas))
			{
				foreach ($metas as $meta) {
					$meta_res = $this->ArticlemetaModel->insert_update_articlemeta($ar_res, $meta[0], $meta[1], $language_code);
					if($meta_res===FALSE)
					{
						$this->result['errorcode'] = ErrorCode::DATABASE_ER;
						log_message('error', 'insert_term_relationships metas lỗi khi insert: ' . $this->result['errorcode'] . ', ar_res '. $ar_res
						. ', meta key '. $meta[0]. ', meta value '. $meta[0]);
					}
				}
			}
			else{
				// insert default
			}
			
			// refer categories
			$categories = $this->input->post('categories');
			if(isset($categories))
			{
				foreach ($categories as $cateid) {
					# code...
					if(!$this->TermsModel->insert_term_relationships($ar_res, $cateid))
						log_message('error', 'insert_term_relationships categories lỗi khi insert:' . $ar_res . ', '. $cateid);
				}
			}
			// refer tags
			$tags = $this->input->post('tags');
			if(isset($tags))
			{
				foreach ($tags as $tag) {
					# code...
					$tagid = 0;
					if($tag[0]==0){
						$tagid = $this->TermsModel->insert_tag_input($tag[1]);
					}
					else{
						$tagid = $tag[0];
					}
					if($tagid==0 || !$this->TermsModel->insert_term_relationships($ar_res, $tagid))
						log_message('error', 'insert_term_relationships tags lỗi khi insert:' . $ar_res . ', '. $tagid);
				}
			}

		}catch(Exception $e){
			$this->result['errorcode'] = ErrorCode::EX_ER;
			$this->result['result'] = $e->getMessage().' --- '.$e->getTraceAsString();
			log_message('error', '/api/addarticle lỗi exception:' .$e->getMessage().' --- '.$e->getTraceAsString());
		}

		$this->output->set_output(json_encode($this->result));
	}

	public function updatearticle()
	{
		try{
			$ar_res = $this->ArticleModel->update_article();
			if($ar_res===FALSE)
			{
				$this->result['errorcode'] = ErrorCode::DATABASE_ER;
				$this->output->set_output(json_encode($result));
				return;
			}

			$metas = $this->input->post('metas');
			$language_code = $this->input->post('language');
			
			if(isset($metas))
			{
				foreach ($metas as $meta) {
					$meta_res = $this->ArticlemetaModel->insert_update_articlemeta($ar_res, $meta[0], $meta[1], $language_code);
					if($meta_res===FALSE)
					{
						$this->result['errorcode'] = ErrorCode::DATABASE_ER;
						log_message('error', 'insert_term_relationships metas lỗi khi insert: ' . $this->result['errorcode'] . ', ar_res '. $ar_res
						. ', meta key '. $meta[0]. ', meta value '. $meta[0]);
					}
				}
			}
			else{
				// insert default
				
			}
			// delete
			$this->TermsModel->delete_relationships_by_article($ar_res);
			// refer categories
			$categories = $this->input->post('categories');
			if(isset($categories))
			{
				foreach ($categories as $cateid) {
					# code...
					if(!$this->TermsModel->insert_term_relationships($ar_res, $cateid))
						log_message('error', 'insert_term_relationships categories lỗi khi insert:' . $ar_res . ', '. $cateid);
				}
			}
			// refer tags
			$tags = $this->input->post('tags');
			if(isset($tags))
			{
				foreach ($tags as $tag) {
					# code...
					$tagid = 0;
					if($tag[0]==0){
						$tagid = $this->TermsModel->insert_tag_input($tag[1]);
					}
					else{
						$tagid = $tag[0];
					}
					
					if($tagid==0 || !$this->TermsModel->insert_term_relationships($ar_res, $tagid))
						log_message('error', 'insert_term_relationships tags lỗi khi insert:' . $ar_res . ', '. $tagid);
				}
			}

		}catch(Exception $e){
			$this->result['errorcode'] = ErrorCode::EX_ER;
			$this->result['result'] = $e->getMessage().' --- '.$e->getTraceAsString();
			log_message('error', '/api/updatearticle lỗi exception:' .$e->getMessage().' --- '.$e->getTraceAsString());
		}

		$this->output->set_output(json_encode($this->result));
	}

	// PAGE
	public function addpage()
	{
		try{
			$ar_res = $this->PageModel->insert_page($this->ion_auth->get_user_id());
			$this->result['errorcode'] = ErrorCode::DATABASE_ER;
			$this->result['result'] = site_url('dashboard/page/editpage/' . $ar_res);
			if($ar_res===FALSE)
			{
				$this->result['errorcode'] = ErrorCode::DATABASE_ER;
				$this->output->set_output(json_encode($result));
				return;
			}
			$metas = $this->input->post('metas');
			$language_code = $this->input->post('language');
			if(isset($metas))
			{
				foreach ($metas as $meta) {
					$meta_res = $this->ArticlemetaModel->insert_update_articlemeta($ar_res, $meta[0], $meta[1], $language_code);
					if($meta_res===FALSE)
					{
						$this->result['errorcode'] = ErrorCode::DATABASE_ER;
						log_message('error', 'insert_term_relationships metas lỗi khi insert: ' . $this->result['errorcode'] . ', ar_res '. $ar_res
						. ', meta key '. $meta[0]. ', meta value '. $meta[0]);
					}
				}
			}
			else{
				// insert default
			}
			
			// refer menu
			$menus = $this->input->post('menus');
			if(isset($menus))
			{
				foreach ($menus as $menuid) {
					if(!$this->TermsModel->insert_term_relationships($ar_res, $menuid))
						log_message('error', 'insert_term_relationships menus lỗi khi insert:' . $ar_res . ', '. $menu);
				}
			}

		}catch(Exception $e){
			$this->result['errorcode'] = ErrorCode::EX_ER;
			$this->result['result'] = $e->getMessage().' --- '.$e->getTraceAsString();
			log_message('error', '/api/addpage lỗi exception:' .$e->getMessage().' --- '.$e->getTraceAsString());
		}

		$this->output->set_output(json_encode($this->result));
	}

	public function updatepage()
	{
		try{
			$ar_res = $this->PageModel->update_page();
			if($ar_res===FALSE)
			{
				$this->result['errorcode'] = ErrorCode::DATABASE_ER;
				$this->output->set_output(json_encode($result));
				return;
			}
			$metas = $this->input->post('metas');
			$language_code = $this->input->post('language');
			if(isset($metas))
			{
				foreach ($metas as $meta) {
					$meta_res = $this->ArticlemetaModel->insert_update_articlemeta($ar_res, $meta[0], $meta[1], $language_code);
					if($meta_res===FALSE)
					{
						$this->result['errorcode'] = ErrorCode::DATABASE_ER;
						log_message('error', 'insert_term_relationships metas lỗi khi insert: ' . $this->result['errorcode'] . ', ar_res '. $ar_res
						. ', meta key '. $meta[0]. ', meta value '. $meta[0]);
					}
				}
			}
			else{
				// insert default
				
			}
			// delete
			$this->TermsModel->delete_relationships_by_article($ar_res);
			// refer categories
			$menus = $this->input->post('menus');
			if(isset($menus))
			{
				foreach ($menus as $menuid) {
					if(!$this->TermsModel->insert_term_relationships($ar_res, $menuid))
						log_message('error', 'insert_term_relationships menus lỗi khi insert:' . $ar_res . ', '. $menu);
				}
			}

		}catch(Exception $e){
			$this->result['errorcode'] = ErrorCode::EX_ER;
			$this->result['result'] = $e->getMessage().' --- '.$e->getTraceAsString();
			log_message('error', '/api/updatepage lỗi exception:' .$e->getMessage().' --- '.$e->getTraceAsString());
		}

		$this->output->set_output(json_encode($this->result));
	}
	
	public function addscholar()
	{
		try{
			$ar_res = $this->PageModel->insert_scholar($this->ion_auth->get_user_id());
			$this->result['errorcode'] = ErrorCode::DATABASE_ER;
			$this->result['result'] = site_url('dashboard/scholar/editscholar/' . $ar_res);
			if($ar_res===FALSE)
			{
				$this->result['errorcode'] = ErrorCode::DATABASE_ER;
				$this->output->set_output(json_encode($result));
				return;
			}
			$metas = $this->input->post('metas');
			$language_code = $this->input->post('language');
			if(isset($metas))
			{
				foreach ($metas as $meta) {
					$meta_res = $this->ArticlemetaModel->insert_update_articlemeta($ar_res, $meta[0], $meta[1], $language_code);
					if($meta_res===FALSE)
					{
						$this->result['errorcode'] = ErrorCode::DATABASE_ER;
						log_message('error', 'insert_term_relationships metas lỗi khi insert: ' . $this->result['errorcode'] . ', ar_res '. $ar_res
						. ', meta key '. $meta[0]. ', meta value '. $meta[0]);
					}
				}
			}
			else{
				// insert default
			}
			
			// refer menu
			$menus = $this->input->post('menus');
			if(isset($menus))
			{
				foreach ($menus as $menuid) {
					if(!$this->TermsModel->insert_term_relationships($ar_res, $menuid))
						log_message('error', 'insert_term_relationships menus lỗi khi insert:' . $ar_res . ', '. $menu);
				}
			}

		}catch(Exception $e){
			$this->result['errorcode'] = ErrorCode::EX_ER;
			$this->result['result'] = $e->getMessage().' --- '.$e->getTraceAsString();
			log_message('error', '/api/addpage lỗi exception:' .$e->getMessage().' --- '.$e->getTraceAsString());
		}

		$this->output->set_output(json_encode($this->result));
	}

	public function updatescholar()
	{
		try{
			$ar_res = $this->PageModel->update_scholar();
			if($ar_res===FALSE)
			{
				$this->result['errorcode'] = ErrorCode::DATABASE_ER;
				$this->output->set_output(json_encode($result));
				return;
			}
			$metas = $this->input->post('metas');
			$language_code = $this->input->post('language');
			if(isset($metas))
			{
				foreach ($metas as $meta) {
					$meta_res = $this->ArticlemetaModel->insert_update_articlemeta($ar_res, $meta[0], $meta[1], $language_code);
					if($meta_res===FALSE)
					{
						$this->result['errorcode'] = ErrorCode::DATABASE_ER;
						log_message('error', 'insert_term_relationships metas lỗi khi insert: ' . $this->result['errorcode'] . ', ar_res '. $ar_res
						. ', meta key '. $meta[0]. ', meta value '. $meta[0]);
					}
				}
			}
			else{
				// insert default
				
			}
			// delete
			$this->TermsModel->delete_relationships_by_article($ar_res);
			// refer categories
			$menus = $this->input->post('menus');
			if(isset($menus))
			{
				foreach ($menus as $menuid) {
					if(!$this->TermsModel->insert_term_relationships($ar_res, $menuid))
						log_message('error', 'insert_term_relationships menus lỗi khi insert:' . $ar_res . ', '. $menu);
				}
			}

		}catch(Exception $e){
			$this->result['errorcode'] = ErrorCode::EX_ER;
			$this->result['result'] = $e->getMessage().' --- '.$e->getTraceAsString();
			log_message('error', '/api/updatepage lỗi exception:' .$e->getMessage().' --- '.$e->getTraceAsString());
		}

		$this->output->set_output(json_encode($this->result));
	}

	public function addcategory(){

        try{
            $res = $this->TermsModel->insert_quick_category();
			if($res===FALSE){
                $this->result['errorcode'] = ErrorCode::DATABASE_ER;
            }
            else{
                $this->result['result'] = $res;
            }

		}catch(Exception $e){
            $this->result['errorcode'] = ErrorCode::EX_ER;
            $this->result['result'] = $e->getMessage().' --- '.$e->getTraceAsString();
        }

        $this->output->set_output(json_encode($this->result));
	}

	public function addmenu(){
		
		try{
			$res = $this->TermsModel->insert_quick_menu();
			if($res===FALSE){
				$this->result['errorcode'] = ErrorCode::DATABASE_ER;
			}
			else{
				$this->result['result'] = $res;
			}

		}catch(Exception $e){
			$this->result['errorcode'] = ErrorCode::EX_ER;
			$this->result['result'] = $e->getMessage().' --- '.$e->getTraceAsString();
		}

		$this->output->set_output(json_encode($this->result));
	}
		
	// TAGs
	public function addtag()
	{
		try{
			$res = $this->TermsModel->insert_quick_tag();
			if($res===FALSE){
                $this->result['errorcode'] = ErrorCode::DATABASE_ER;
            }
            else{
                $this->result['result'] = $res;
            }
		}catch(Exception $e){
            $this->result['errorcode'] = ErrorCode::EX_ER;
            $this->result['result'] = $e->getMessage().' --- '.$e->getTraceAsString();
        }

        $this->output->set_output(json_encode($this->result));
	}

	public function gettags()
	{
		$this->output->set_output(json_encode($this->TermsModel->get_typehead_tags()));
	}

	// Article translate
	public function getarticlelang()
	{
		$language_code = $this->input->post('language');
		$article_id = $this->input->post('article_id');
		$res = $this->ArticleModel->get_transate_article($article_id, $language_code);
		$this->result['metas'] = $this->ArticlemetaModel->get_articlemetas($article_id, $language_code);
		if($res===FALSE){
            $this->result['result'] = [
            	'article_title'=>'',
            	'article_slug'=>'',
            	'article_description'=>'',
            	'article_content'=>'',
            ];
        }
        else{
            $this->result['result'] = $res;
        }
		$this->output->set_output(json_encode($this->result));
	}
	
}

/* End of file Article.php */
/* Location: ./application/models/Article.php */