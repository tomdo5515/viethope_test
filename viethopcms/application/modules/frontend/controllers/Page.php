<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends FrontendController {
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
		$this->load->model(['PageModel','ArticleModel','ArticlemetaModel','TermsModel','PaymentsModel','GalleryModel']);
        // Ideally you would autoload the parser
	}
	
	public function LoadContent($menu_id)
	{
		// process donate
		if($this->input->post() && $this->input->post('xdonate')){
			$query = array();
			$amount=0;
			// debug($this->input->post());
			$query['cmd'] = $this->input->post('donationcmd');
			$query['business'] = $this->config->item('business');
			$query['currency_code'] = $this->config->item('paypal_currency_code');
			
			// debug(http_build_query($query));
			if($query['cmd']=='_xclick-subscriptions'){
				if(is_numeric($this->input->post('gifOfEducation')))
				{
					$amount = $this->input->post('gifOfEducation');
					switch ($amount) {
						case '5':
							$query['item_name'] = "Friend of VietHope: \$$amount";
							break;
						case '10':
							$query['item_name'] = "Merit Scholar Sponsor: \$$amount";
							break;
						case '17':
							$query['item_name'] = "USP Scholar Sponsor: \$$amount";
							break;
						case '100':
							$query['item_name'] = "VietHope Ambassador: \$$amount";
							break;
					}
				}
				else{
					$amount = $this->input->post('other');
					$query['item_name'] = "Custom price: \$$amount";
				}
				$query['a3'] = $amount;
				$query['p3'] = 1;
				$query['t3'] = 'M';
			}
			else{
				if(is_numeric($this->input->post('gifOfEducation1')))
				{
					$amount = $this->input->post('gifOfEducation1');
					switch ($amount) {
						case '50':
							$query['item_name'] = "Friend of VietHope: \$$amount";
							break;
						case '120':
							$query['item_name'] = "Merit Scholar Sponsor: \$$amount";
							break;
						case '200':
							$query['item_name'] = "USP Scholar Sponsor: \$$amount";
							break;
						case '1200':
							$query['item_name'] = "VietHope Ambassador: \$$amount";
							break;
					}
				}
				else{
					$amount = $this->input->post('other1');
					$query['item_name'] = "Custom price: \$$amount";
				}
				$query['amount'] = $amount;
			}

			
			// debug($query);
			$order_token = $this->PaymentsModel->insert_update_option(0, $amount, '', '', '');

			$query['return'] = base_url() . 'payment/success/ordertoken/' . $order_token;
			$query['cancel_return'] = base_url() . 'payment/canceled/ordertoken/' . $order_token;

			$link = $this->config->item('sandbox') ? 'https://www.sandbox.paypal.com/cgi-bin/webscr?' : 'https://www.paypal.com/cgi-bin/webscr?';
			header("Location: $link" . http_build_query($query));
			exit(0);
		}
		
		// end donate

		//get content
		// $data['article'] = $this->PageModel->get_page_by_menu($menu_code);
		$data['article'] = $this->PageModel->get_page_by_menuid($menu_id);

		if($data['article'] != FALSE){
			$data['seo'] = $this->ArticlemetaModel->get_articlemetas($data['article']['id'], $this->language);
			$this->twig->title($data['article']['article_title']);
		}

		$data['term_parent'] = $this->TermsModel->get_transate_term($menu_id, $this->language);

		// Extensions
		$data['article']['article_content'] = $this->GetExtensions($data['article']['article_content'], $menu_id);
		
		$this->twig->set(["current_id" => $menu_id], NULL, TRUE);
		$this->twig->set($data);
        $this->twig->display('pagestatic');
	}

	public function LoadChildPage($menu_parent_id, $menu_id)
	{
		//get content
		// $data['article'] = $this->PageModel->get_page_by_menu($menu_code);
		$data['article'] = $this->PageModel->get_page_by_menuid($menu_id);

		if($data['article'] != FALSE){
			$data['seo'] = $this->ArticlemetaModel->get_articlemetas($data['article']['id'], $this->language);
			$this->twig->title($data['article']['article_title']);
		}

		$data['term_parent'] = $this->TermsModel->get_transate_term($menu_parent_id, $this->language);
		
		// Extensions
		$data['article']['article_content'] = $this->GetExtensions($data['article']['article_content'], $menu_id);
		$this->twig->set(["current_id" => $menu_parent_id], NULL, TRUE);
		$this->twig->set($data);
        $this->twig->display('pagestatic');
	}

	private function GetExtensions($article_content, $menu_id=0){

		// Extensions
		// Check
		if(strpos($article_content, '<!--[SCHOLAR_CAR]-->'))
		{
			$datarender['scholars'] = $this->PageModel->get_scholars();
			$this->twig->set($datarender);
			$rep = $this->twig->render('/_extensions/scholar_carousel');
			$article_content = str_replace('<!--[SCHOLAR_CAR]-->', $rep, $article_content);
		}
		
		if(strpos($article_content, '<!--[EVENT_CALENDAR]-->'))
		{
			$datarender['events'] = $this->ArticleModel->get_by_eventtime($this->guest, 31, 20, TRUE);
			$length = sizeof($datarender['events']);
			for ($i=0; $i < $length; $i++) { 
				$datarender['events'][$i]['new'] = $datarender['events'][$i]['event_time_gmt'] >= time();
			}
			$this->twig->set($datarender);
			$rep = $this->twig->render('/_extensions/event_calendar');
			$article_content = str_replace('<!--[EVENT_CALENDAR]-->', $rep, $article_content);
		}

		if(strpos($article_content, '<!--[DONATE_FORM]-->'))
		{
			$rep = $this->twig->render('/_extensions/donate');
			$article_content = str_replace('<!--[DONATE_FORM]-->', $rep, $article_content);
		}

		if(strpos($article_content, '<!--[GALLERY_CAR]-->'))
		{
			$datarender['gallery'] = $this->GalleryModel->get_gallery($menu_id);
			$this->twig->set($datarender);
			$rep = $this->twig->render('/_extensions/gallery_carousel');
			$article_content = str_replace('<!--[GALLERY_CAR]-->', $rep, $article_content);
		}

		return $article_content;
	}
}
