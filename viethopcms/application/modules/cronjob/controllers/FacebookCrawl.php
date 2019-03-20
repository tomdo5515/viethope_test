<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FacebookCrawl extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library(['facebook']);
        $this->load->model(['OptionsModel','FacebookdataModel']);
    }

    // */1 * * * * php /home/v2.keonhacai888.com/public_html/assets/index.php cronjob FacebookCrawl getpage 10
    public function getpage($limit = 10)
    {
        $pageid = '2190971361132972';
        if(is_cli())
        {
            try
            {
                $access_token = $this->OptionsModel->get_setting('fb_access_token');
                if($access_token){

                    $this->session->set_userdata('fb_access_token', $access_token->option_value);

                    if($this->facebook->is_authenticated()){
                        $this->OptionsModel->insert_update_option('fb_access_token', $this->session->userdata('fb_access_token'));
                        $feed = $this->facebook->request('get', '/'.$pageid.'/feed?fields=full_picture,link,created_time,description,shares,source,reactions.type(LIKE).summary(total_count).limit(0).as(like),reactions.type(LOVE).summary(total_count).limit(0).as(love),reactions.type(WOW).summary(total_count).limit(0).as(wow),reactions.type(HAHA).summary(total_count).limit(0).as(haha),reactions.type(SAD).summary(total_count).limit(0).as(sad),reactions.type(ANGRY).summary(total_count).limit(0).as(angry)&limit=' . $limit);
                        
                        if(isset($feed['error'])){
                            print_r($feed);
                        }
                        else if(isset($feed['data'])){
                            print_r($feed);
                            foreach ($feed['data'] as $dd) {
                                $id = $dd['id'];
                                $full_picture = isset($dd['full_picture'])? $dd['full_picture'] : '';
                                $link= isset($dd['link'])? $dd['link'] : '';
                                $created_time= isset($dd['created_time'])? $dd['created_time'] : '';
                                $like= isset($dd['like'])? $dd['like']['summary']['total_count'] : '';
                                $love= isset($dd['love'])? $dd['love']['summary']['total_count'] : '';
                                $wow= isset($dd['wow'])? $dd['wow']['summary']['total_count'] : '';
                                $sad= isset($dd['sad'])? $dd['sad']['summary']['total_count'] : '';
                                $angry= isset($dd['angry'])? $dd['angry']['summary']['total_count'] : '';
                                $shares= isset($dd['shares'])? $dd['shares']['count'] : '';
                                $source= isset($dd['source'])? $dd['source'] : '';
                                $description= isset($dd['description'])? $dd['description'] : '';

                                $this->FacebookdataModel->insert_update($id, $full_picture, $link, $created_time, $like, $love, $wow, $sad, $angry, $shares, $source, $description);
                            }
                        }
                    }
                    else
                    {
                        echo "session Expired".PHP_EOL;
                    }
                }
                else{
                    echo "access_token Expired".PHP_EOL;
                }
            }
            catch (Exception $e) {
                echo "Caught exception: ".$e->getMessage().PHP_EOL;
            }
        }
        else
        {
            show_404($page = '', $log_error = TRUE);
        }
    }
}