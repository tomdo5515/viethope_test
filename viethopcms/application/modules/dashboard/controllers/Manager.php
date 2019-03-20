<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manager extends BackendController {

	public function __construct()
	{
		parent::__construct();

		$this->load->helper(['url','io']);
		$this->load->library('grocery_CRUD');
	}

	public function options()
	{
		try{
			$crud = new grocery_CRUD();
			$arr_setting_type = ['yes' =>'yes', 'no' =>'no'];
			$crud->set_table('options');
			$crud->set_subject('Setting site');
			$crud->set_primary_key('option_id');
			$crud->required_fields('option_id');
			$crud->columns('option_type','option_key','option_value','autoload');
			$crud->add_fields('option_type','option_key','option_value','autoload');
			$crud->edit_fields('option_type','option_key','option_value','autoload');
			$crud->unset_texteditor('option_value','full_text');
			$crud->field_type('option_type','dropdown', OptionType::ARRAY_TYPE, 'none');
			$crud->field_type('autoload','dropdown', $arr_setting_type);
			$output = $crud->render();
			$array = json_decode(json_encode($output), true);

			$this->twig->set($array);
			$this->twig->display('managercrud');

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function position()
	{
		try{
			$crud = new grocery_CRUD();
			
			$crud->set_table('position');
			$crud->set_subject('Vị trí');
			$crud->set_primary_key('position_id','position');
			$crud->required_fields('position_id');
			$crud->columns('position_id', 'name','status');
			$output = $crud->render();
			$array = json_decode(json_encode($output), true);

			$this->twig->set($array);
			$this->twig->display('managercrud');

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function banners()
	{
		try{
			$crud = new grocery_CRUD();
			
			$crud->set_table('banners');
			$crud->set_subject('Quảng cáo');
			$crud->set_primary_key('banner_id','banners');
			$crud->required_fields('banner_id');
			$crud->required_fields('position_id');
			$crud->set_relation('position_id','position','name');
			$crud->columns('position_id', 'name','click_url','sort','status');
			$crud->add_fields('position_id', 'name','click_url', 'content', 'sort','status');
			$crud->edit_fields('position_id',  'name', 'click_url','content','sort', 'status');

			$crud->display_as('position_id','Vị trí hiện thị');

			$output = $crud->render();
			$array = json_decode(json_encode($output), true);

			$this->twig->set($array);
			$this->twig->display('managercrud');

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function gallery()
	{
		try{
			$crud = new grocery_CRUD();
			
			$crud->set_table('gallery');
			$crud->set_subject('Gallery');
			$crud->set_primary_key('id','gallery');
			$crud->required_fields('id');
			$crud->required_fields('image');
			$crud->set_relation('term_id','terms','term_name');
			$crud->columns('id', 'term_id','image');

			$crud->display_as('term_id','menu');
			$crud->set_field_upload('image','/uploads/images');
			$output = $crud->render();
			$array = json_decode(json_encode($output), true);

			$this->twig->set($array);
			$this->twig->display('managercrud');

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function language()
	{
		try{
			$crud = new grocery_CRUD();
			
			$crud->set_table('languages');
			$crud->set_subject('Language');
			$crud->set_primary_key('language_code','Language');
			$crud->required_fields('language_code');
			$crud->required_fields('name');
			$crud->columns('language_code', 'name','status');
			$crud->add_fields('language_code', 'name','status');
			$crud->edit_fields('language_code', 'name','status');
			// $crud->set_field_upload('flag','/uploads/Images');
			$crud->field_type('status','dropdown', LanguageStatus::ARRAY_TYPE, 'none');
			
			$output = $crud->render();
			$array = json_decode(json_encode($output), true);

			$this->twig->set($array);
			$this->twig->display('managercrud');

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function partner()
	{
		try{
			$crud = new grocery_CRUD();
			
			$crud->set_table('partner');
			$crud->set_subject('Partnership');
			$crud->set_primary_key('id','id');
			$crud->required_fields('name');
			$crud->required_fields('thumbnail');
			$crud->required_fields('link');
			$crud->set_field_upload('thumbnail','uploads/images/');
			$output = $crud->render();
			$array = json_decode(json_encode($output), true);

			$this->twig->set($array);
			$this->twig->display('managercrud');

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function program()
	{
		try{
			$crud = new grocery_CRUD();
			
			$crud->set_table('program');
			$crud->set_subject('Program');
			$crud->set_primary_key('id','id');
			$crud->columns('title', 'thumbnail','total_funded','num_program','status');
			$crud->required_fields('name');
			$crud->required_fields('thumbnail');
			$crud->set_field_upload('thumbnail','uploads/images/');
			$output = $crud->render();
			$array = json_decode(json_encode($output), true);
			
			$this->twig->set($array);
			$this->twig->display('managercrud');

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function milestone()
	{
		try{
			$crud = new grocery_CRUD();
			
			$crud->set_table('milestone');
			$crud->set_subject('Milestone');
			$crud->set_primary_key('id','milestone');
			$crud->required_fields('id');
			$crud->columns('id', 'description_english','event_time','status');
			$output = $crud->render();
			$array = json_decode(json_encode($output), true);

			$this->twig->set($array);
			$this->twig->display('managercrud');

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
}