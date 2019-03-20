<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FacebookManage extends BackendController {

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(['url','io']);
		$this->load->library(['grocery_CRUD','facebook']);
		$this->load->model(['OptionsModel']);
	}

	public function facebookpage()
	{
		try{
			if($this->ion_auth->is_admin())
			{
				$crud = new grocery_CRUD();
				$crud->set_table('facebookdata');
				$crud->set_subject('Facebook Page');

				$crud->unset_add();
				$crud->columns('id','link','created_time');
				$access_token = $this->OptionsModel->get_setting('fb_access_token');
				if($access_token){
					$this->session->set_userdata('fb_access_token', $access_token->option_value);
				}

				if(!$this->facebook->is_authenticated())
				{
					$crud->add_extra_function('Get token', $this->facebook->login_url(),'glyphicon-download');
				}

				$output = $crud->render();
				$array = json_decode(json_encode($output), true);

				$this->twig->set($array);
				$this->twig->display('managercrud');
			}
			else{
				$this->twig->display('_layouts/permission_denied');
			}
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}


	public function authenticate()
	{
		try{
			if($this->ion_auth->is_admin())
			{
				$array = [];
				$array['is_authenticated'] = $this->facebook->is_authenticated();
				if($array['is_authenticated']){
					$this->OptionsModel->insert_update_option('fb_access_token', $this->session->userdata('fb_access_token'));
					if($this->session->userdata('fb_expire')) {
						$this->OptionsModel->insert_update_option('fb_expire', $this->session->userdata('fb_expire'));
					}
				}
				$this->twig->set($array);
				$this->twig->display('facebookloadpost');
			}
			else{
				$this->twig->display('_layouts/permission_denied');
			}
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

}