<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class FrontendController extends CommonController
{
    protected $guest = NULL;

    protected $open_menu = NULL;

    public function __construct()
    {
        parent::__construct();
        if($this->config->item('maintenance_mode') == TRUE) {
            $this->twig->outscreen('_layouts/maintenance');
            die();
        }
        
        $this->load->model(['TermsModel','OptionsModel']);
        $data[OptionType::META_GLOBAL] = $this->OptionsModel->get_options(OptionType::META_GLOBAL);
        $data[OptionType::META_SEO_DEFAULT] = $this->OptionsModel->get_options(OptionType::META_SEO_DEFAULT);
        $data['menus'] = $this->TermsModel->getobjmenus();
        $this->twig->title($this->config->item('site_name'));

        if(!$this->guest)
        {
            $this->guest = get_cookie('guestmark', true);
        }

        if(!$this->open_menu)
        {
            $this->open_menu = get_cookie('open_menu', true);
        }

        $data['guest'] = $this->guest;
        $data['open_menu'] = $this->open_menu;

        $this->twig->set($data, NULL, TRUE);
        
        
    }
}
