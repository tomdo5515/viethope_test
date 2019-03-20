<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends FrontendController {
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
		// Ideally you would autoload the parser
		$this->load->library('Recaptcha');
		$this->twig->set(["current_id" => 40], NULL, TRUE);
	}

	public function index()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			if(validation_errors()){
				$data['validate']='Please complete all required fields!';
			}
		}
		else
		{
			$recaptcha = $this->input->post('g-recaptcha-response');
			$response = $this->recaptcha->verifyResponse($recaptcha);
			
			if($response['success']){
				$config = Array(
					'protocol' => 'smtp',
					'smtp_host' => 'ssl://smtp.googlemail.com',
					'smtp_port' => 465,
					'smtp_user' => $this->config->item('gmail_user'),
					'smtp_pass' => $this->config->item('gmail_password'),
					'mailtype'  => 'html', 
					'charset'   => 'utf-8'
				);
				
				$this->load->library('email', $config);
				$this->email->set_newline("\r\n");
				
				// Set to, from, message, etc.
				$this->email->from($this->config->item('gmail_user'), 'Viethope contact');
				$this->email->to($this->config->item('email_contact'));
	
				$this->email->subject('Viethope Contact Form');
	
				$message = "<h4>Name: ".$this->input->post('name')."</h4>";
				$message .= "<h4>From: ".$this->input->post('email')."</h4>";
				$message .= "<h4>Phone: ".$this->input->post('phone')."</h4>";
				$message .= "<p>Message: <b>".$this->input->post('message')."</b></p>";
	
				$this->email->message($message);
	
				if (!$this->email->send())
				{
					$data['validate']='Message sent fail!';
				}
				else{
					$data['validate']='Your message sent!';
				}
			}
			else{
				$data['validate']='Captcha invalid!';
			}
		}

		$data['recapcha'] = $this->recaptcha->render();
		$this->twig->set($data);
		$this->twig->display('contact');
	}
}
