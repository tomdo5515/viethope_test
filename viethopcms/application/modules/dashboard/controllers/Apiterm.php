<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Apiterm extends BackendController {
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
		$this->load->model(['TermsModel', 'TermmetaModel', 'LanguageModel']);
    }
	
	public function index()
	{
        $this->output->set_output(json_encode($this->result));
	}

	public function gettermlang()
	{
        $language_code = $this->input->post('language');
		$term_id = $this->input->post('term_id');
		$res = $this->TermsModel->get_transate_term($term_id, $language_code);
		$this->result['metas'] = $this->TermmetaModel->get_termmetas($term_id, $language_code);
		if($res===FALSE){
            $this->result['result'] = [
            	'term_name'=>'',
            	'slug'=>'',
            	'term_order'=>''
            ];
        }
        else{
            $this->result['result'] = $res;
        }
		$this->output->set_output(json_encode($this->result));
	}

	// MENU
	public function getmenus()
	{
		$raw = $this->TermsModel->get_terms_by_type(Taxonomy::MENU);
		$format_data = [];
		foreach ($raw as $menu) {
			if($menu['parent'] === '0'){
				$temp = new termh();
				$temp->id = $menu['term_id'];
				$temp->text = $menu['term_name'];
				$temp->checked = false;

				$temp->children = $this->findTermChild($temp, $raw);
				array_push($format_data, $temp); 
			}
		}

        $this->output->set_output(json_encode($format_data));
	}

	// helper
	public function findTermChild($parent, $arr)
	{
		$child = [];
		// nếu còn con de quy vao trong
		foreach ($arr as $value) {
			if($parent->id === $value['parent'])
			{
				$temp = new termh();
				$temp->id = $value['term_id'];
				$temp->text = $value['term_name'];
				$temp->checked = false;
				$temp->children = $this->findTermChild($temp, $arr);
				array_push($child, $temp); 
			}
		}

		return $child;
	}

	public function addmenu()
	{
		try{
			$ar_res = $this->TermsModel->insert_menu();
			$this->result['result'] = site_url('dashboard/terms/editmenu/' . $ar_res);
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
					$meta_res = $this->TermmetaModel->insert_update_termmeta($ar_res, $meta[0], $meta[1], $language_code);
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

		}catch(Exception $e){
			$this->result['errorcode'] = ErrorCode::EX_ER;
			$this->result['result'] = $e->getMessage().' --- '.$e->getTraceAsString();
			log_message('error', '/api/addmenu lỗi exception:' .$e->getMessage().' --- '.$e->getTraceAsString());
		}

		$this->output->set_output(json_encode($this->result));
	}

	public function updatemenu()
	{
		try{

			$ar_res = $this->TermsModel->update_menu();
			if($ar_res===FALSE)
			{
				$this->result['errorcode'] = ErrorCode::DATABASE_ER;
				$this->output->set_output(json_encode($result));
				return;
			}

			$metas = $this->input->post('metas');
			$lang_code = $this->input->post('language');
			
			if(isset($metas))
			{
				foreach ($metas as $meta) {
					$meta_res = $this->TermmetaModel->insert_update_termmeta($ar_res, $meta[0], $meta[1], $lang_code);
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

		}catch(Exception $e){
			$this->result['errorcode'] = ErrorCode::EX_ER;
			$this->result['result'] = $e->getMessage().' --- '.$e->getTraceAsString();
			log_message('error', '/api/updatearticle lỗi exception:' .$e->getMessage().' --- '.$e->getTraceAsString());
		}

		$this->output->set_output(json_encode($this->result));
	}
	
	public function addcategory()
	{
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

	public function gettags()
	{
		$this->output->set_output(json_encode($this->TermsModel->get_typehead_tags()));
	}

}

/* End of file Article.php */
/* Location: ./application/models/Article.php */