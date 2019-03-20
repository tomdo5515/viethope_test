<?php defined('BASEPATH') OR exit('No direct script access allowed');

class UserManage extends BackendController {
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
	private $result = ['errorcode' => '0', 'result' => 'if you can call this, please contact thao31b@gmail.com'];
	
	public function __construct()
    {
		parent::__construct();
		$this->load->library('grocery_CRUD');
		$this->load->model('TrackBehaviorModel');
        // Ideally you would autoload the parser
    }
	
	public function index()
	{
		try{
			$this->TrackBehaviorModel->track($this->ion_auth->get_user_id());
			if (!$this->ion_auth->is_admin()) {
				$this->twig->display('_layouts/permission_denied');
			}
			else{
				$crud = new grocery_CRUD();
				$crud->unset_add();
				$crud->unset_edit();
				$crud->set_table('users');
				$crud->set_subject('User Management');
				$crud->set_relation_n_n('groups', 'users_groups', 'groups', 'user_id', 'group_id', 'name');
				$crud->columns('username','email','active','first_name','groups');
				$crud->add_fields('username','password','email','groups','first_name','last_name','company','phone','active');
				$crud->edit_fields('active','username','password','email','groups','first_name','last_name','company','phone');

				$crud->unset_delete();
				$crud->add_extra_function('Thêm user', '/dashboard/usermanage/adduser','glyphicon-plus');
				$crud->add_action('Edit', '', '/dashboard/usermanage/edituser', 'glyphicon-pencil');
				

				$output = $crud->render();
				$array = json_decode(json_encode($output), true);

				$this->twig->set($array);
				$this->twig->display('managercrud');
			}
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
 	}

    public function adduser()
    {
		$this->TrackBehaviorModel->track($this->ion_auth->get_user_id());
        try{
            if (!$this->ion_auth->is_admin()) {
                $this->twig->display('_layouts/permission_denied');
            }
            else{
				$data['groups']=$this->ion_auth->groups()->result_array();
				$this->twig->set($data);
                $this->twig->display('usermanage/user_add');
            }
        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
	}
	
	public function postuser()
    {
		$this->output->set_content_type('application/json');
        try{
            if (!$this->ion_auth->is_admin()) {
				$this->result['errorcode'] = ErrorCode::PERMISSION_DENIED;
                $this->output->set_output(json_encode($this->result));
            }
            else{
				$identity_column 	= $this->config->item('identity','ion_auth');
				$email    			= strtolower($this->input->post('post_user_email'));
				$identity 			= ($identity_column==='email') ? $email : $this->input->post('post_username');
				$password 			= $this->input->post('post_password');
				$additional_data = array(
					'first_name' => $this->input->post('post_first_name'),
					'last_name'  => $this->input->post('post_last_name'),
					'company'    => $this->input->post('post_company'),
					'phone'      => $this->input->post('post_phone')
				);
				$groupData = $this->input->post('post_groups');

				$userid = $this->ion_auth->register($identity, $password, $email, $additional_data, $groupData);

				if ($userid)
				{
					$this->result['result'] = $this->ion_auth->messages();
					$this->output->set_output(json_encode($this->result));
				}
				else
				{
					$this->result['errorcode'] = ErrorCode::DATABASE_ER;
					$this->result['result'] = $this->ion_auth->messages();
					$this->output->set_output(json_encode($this->result));
				}
            }
        }catch(Exception $e){
			$this->result['errorcode'] = ErrorCode::EX_ER;
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
			$this->output->set_output(json_encode($this->result));
        }
    }

	public function edituser($id)
    {
        try{
            if (!$this->ion_auth->is_admin()) {
                $this->twig->display('_layouts/permission_denied');
            }
            else{
				$data['user'] = $this->ion_auth->user($id)->row();
				$data['groups'] = $this->ion_auth->groups()->result_array();
				$data['currentGroups'] = $this->ion_auth->get_users_groups($id)->result();
				$this->twig->set($data);
                $this->twig->display('usermanage/user_edit');
            }
        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
	}

	public function updateuser($id)
    {
		$this->output->set_content_type('application/json');
        try{
            if (!$this->ion_auth->is_admin()) {
				$this->result['errorcode'] = ErrorCode::PERMISSION_DENIED;
                $this->output->set_output(json_encode($this->result));
            }
            else{
				$password 			= $this->input->post('post_password');
				$put_data = array(
					'first_name' => $this->input->post('post_first_name'),
					'last_name'  => $this->input->post('post_last_name'),
					'company'    => $this->input->post('post_company'),
					'phone'      => $this->input->post('post_phone')
				);

				if($password && $password != ''){
					$put_data['password'] = $password;
				}

				$userid = $this->input->post('put_id');

				if ($userid && $userid==$id)
				{
					// Only allow updating groups if user is admin
					// Update the groups user belongs to
					$groupData = $this->input->post('post_groups');
					if (isset($groupData) && !empty($groupData)) {
						$this->ion_auth->remove_from_group('', $userid);
						foreach ($groupData as $grp) {
							$this->ion_auth->add_to_group($grp, $userid);
						}
					}

					// check to see if we are updating the user
					if ($this->ion_auth->update($userid, $put_data)) {
						$this->result['result'] = $this->ion_auth->messages();
					}
					else {
						$this->result['errorcode'] = ErrorCode::DATABASE_ER;
						$this->result['result'] = $this->ion_auth->messages();
						$this->output->set_output(json_encode($this->result));
					}

					$this->output->set_output(json_encode($this->result));
				}
				else
				{
					$this->result['errorcode'] = ErrorCode::DATA_EMPTY;
					$this->result['result'] = '';
					$this->output->set_output(json_encode($this->result));
				}
            }
        }catch(Exception $e){
			$this->result['errorcode'] = ErrorCode::EX_ER;
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
			$this->output->set_output(json_encode($this->result));
        }
    }

    public function groups()
	{
		try{
			if (!$this->ion_auth->is_admin()) {
				$this->twig->display('_layouts/permission_denied');
			}
			else{
				$crud = new grocery_CRUD();
				$crud->set_table('groups');
				$crud->set_subject('User group');
				
				$output = $crud->render();
				$array = json_decode(json_encode($output), true);

				$this->twig->set($array);
				$this->twig->display('managercrud');
			}
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function loginattempts()
	{
		try{
			if (!$this->ion_auth->is_admin()) {
				$this->twig->display('_layouts/permission_denied');
			}
			else{
				$crud = new grocery_CRUD();
				$crud->set_table('login_attempts');
				$crud->set_subject('User Attempts');
				$crud->unset_add();
				$crud->unset_delete();
				$output = $crud->render();
				$array = json_decode(json_encode($output), true);

				$this->twig->set($array);
				$this->twig->display('managercrud');
			}

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}


	public function loginlogs()
	{
		try{
			if (!$this->ion_auth->is_admin()) {
				$this->twig->display('_layouts/permission_denied');
			}
			else
			{
				$crud = new grocery_CRUD();
				$crud->set_table('track_behavior');
				$crud->set_subject('User logs');
				$crud->set_relation('user_id','users','first_name');
				$crud->unset_add();
				$crud->unset_edit();
				$crud->unset_delete();
				$crud->order_by('id', 'desc');
				$output = $crud->render();
				$array = json_decode(json_encode($output), true);

				$this->twig->set($array);
				$this->twig->display('managercrud');
			}

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	// Helper
}
