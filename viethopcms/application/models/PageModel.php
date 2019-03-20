<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PageModel extends CI_Model {
	
	// 
	public $article_author;

	public $article_date;

	public $article_time_gmt;

    public $thumbnail;
    
    public $article_password;

	public $article_status;

	public $article_type;

    public $favorite_count = 0;

	public $comment_count = 0;

	private $language_code = 'english';

    private $default_language = 'english';

    public $pin = 0;
	
    public function __construct()
    {
        parent::__construct();
        $this->default_language = $this->config->item('language');
        $this->language_code = $this->session->userdata('language');
    }
    // PAGE

    public function get_page($id)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, users.first_name, users.last_name, terms.term_id, terms.term_name, terms.slug');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, users.first_name, users.last_name, terms.term_id, terms.term_name, terms.slug');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }

        $this->db->from('articles');
        $this->db->join('users', 'articles.article_author = users.id');

        $this->db->join('term_relationships', 'term_relationships.object_id = articles.id');
        $this->db->join('terms', 'terms.term_id = term_relationships.term_id');

        $this->db->where('articles.id', $id);
        $this->db->where('articles.article_type', ArticleType::PAGE);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);

        $query = $this->db->get();
        if($query -> num_rows() > 0)
            return $query->row_array();
        else
            return FALSE;
    }

    public function get_page_by_menu($menu_slug)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, articles.article_password');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.language_code, article_translate.article_content, articles.thumbnail, articles.favorite_count, articles.comment_count,users.first_name, users.last_name, articles.article_status, articles.article_password');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }

        $this->db->from('articles');
        
        $this->db->join('users', 'articles.article_author = users.id');

        $this->db->join('term_relationships', 'term_relationships.object_id = articles.id');
        $this->db->join('terms', 'terms.term_id = term_relationships.term_id');

        $this->db->where('terms.slug', $menu_slug);
        $this->db->where('articles.article_type', ArticleType::PAGE);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);

        $query = $this->db->get();
        if($query -> num_rows() > 0)
            return $query->row_array();
        else
            return FALSE;
    }

    public function get_page_by_menuid($menu_id)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, articles.article_password');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.language_code, article_translate.article_content, articles.thumbnail, articles.favorite_count, articles.comment_count,users.first_name, users.last_name, articles.article_status, articles.article_password');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }

        $this->db->from('articles');
        
        $this->db->join('users', 'articles.article_author = users.id');

        $this->db->join('term_relationships', 'term_relationships.object_id = articles.id');
        $this->db->join('terms', 'terms.term_id = term_relationships.term_id');

        $this->db->where('terms.term_id', $menu_id);
        $this->db->where('articles.article_type', ArticleType::PAGE);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);

        $query = $this->db->get();
        if($query -> num_rows() > 0)
            return $query->row_array();
        else
            return FALSE;
    }

    public function get_any_page($id)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, articles.article_password');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, articles.article_password');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }

        $this->db->from('articles');

        $this->db->where('articles.id', $id);
        $this->db->where('articles.article_type', ArticleType::PAGE);

        $query = $this->db->get();
        if($query -> num_rows() > 0)
            return $query->row_array();
        else
            return FALSE;
    }

    public function get_feature_scholars($menu_id=0, $p=1)
    {
        //paging
        $p=($p-1);
        $from = $p*50;

        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_description, article_content, article_slug, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_description, article_translate.article_content, article_translate.article_slug, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }

        $this->db->from('articles');

        if($menu_id > 0){
            $this->db->join('term_relationships', 'term_relationships.object_id = articles.id');
            $this->db->join('terms', 'terms.term_id = term_relationships.term_id');
            $this->db->where('terms.term_id', $menu_id);
        }

        $this->db->where('articles.article_type', ArticleType::SCHOLAR);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);
        $this->db->where('articles.pin', ArticleStatus::PIN);

        $this->db->order_by('articles.article_time_gmt','desc');
        $this->db->limit(50, $from);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_scholars($menu_id=0, $p=1)
    {
        //paging
        $p=($p-1);
        $from = $p*50;

        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_description, article_content, article_slug, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_description, article_translate.article_content, article_translate.article_slug, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }

        $this->db->from('articles');

        if($menu_id > 0){
            $this->db->join('term_relationships', 'term_relationships.object_id = articles.id');
            $this->db->join('terms', 'terms.term_id = term_relationships.term_id');
            $this->db->where('terms.term_id', $menu_id);
        }

        $this->db->where('articles.article_type', ArticleType::SCHOLAR);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);

        $this->db->order_by('articles.article_time_gmt','desc');
        $this->db->limit(50, $from);

        $query = $this->db->get();
        return $query->result_array();
    }

    // BACKEND
    public function get_page_default_lang($id)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, articles.article_password');

        $this->db->from('articles');

        $this->db->where('articles.id', $id);
        $this->db->where('articles.article_type', ArticleType::PAGE);

        $query = $this->db->get();
        if($query -> num_rows() > 0)
            return $query->row_array();
        else
            return FALSE;
    }

    public function get_scholar_default_lang($id)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_description, article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, articles.article_password, articles.pin');

        $this->db->from('articles');

        $this->db->where('articles.id', $id);
        $this->db->where('articles.article_type', ArticleType::SCHOLAR);

        $query = $this->db->get();
        if($query -> num_rows() > 0)
            return $query->row_array();
        else
            return FALSE;
    }

    // CREATE
    public function insert_page($article_author)
    {
        $lang_code = $this->input->post('language');
        $metas = $this->input->post('metas');

        $this->article_author       = $article_author; // please read the below note
        $this->article_date         = $this->input->post('article_date');
        $this->article_time_gmt     = $this->input->post('article_time_gmt');
        $this->thumbnail            = $this->input->post('thumbnail');
        $this->article_password     = $this->input->post('article_password');
        $this->article_status       = $this->input->post('article_status');
        $this->article_type         = ArticleType::PAGE;
        $this->pin                  = $this->input->post('pin')=='true' ? 1:0;

        if($lang_code == $this->default_language){
            $this->article_title        = $this->input->post('article_title');
            $this->article_slug         = slugify($this->input->post('article_title'));
            // $this->article_description  = getDescription($this->input->post('article_content'));
            $this->article_description  = '';
            if(isset($metas[1][1])){
                $this->article_description  = $metas[1][1]; // SEO description
            }
            $this->article_content      = $this->input->post('article_content');

            if($this->db->insert('articles', $this)){
                return $this->db->insert_id();
            }
            else
                return FALSE;
        }
        else
        {
            if($this->db->insert('articles', $this)){
                $article_id = $this->db->insert_id();
                
                // reset_query
                $this->db->reset_query();

                $this->db->where('article_id',$article_id);
                $this->db->where('language_code',$lang_code);
                $q = $this->db->get('article_translate');
                $article_des = '';
                if(isset($metas[1][1])){
                    $article_des  = $metas[1][1]; // SEO description
                }
                $data_translate = [
                    'article_title'        => $this->input->post('article_title'),
                    'article_slug'         => slugify($this->input->post('article_title')),
                    // 'article_description'  => getDescription($this->input->post('article_content')),
                    'article_description'  => $article_des,
                    'article_content'      => $this->input->post('article_content')
                ];

                if ( $q->num_rows() > 0 )
                {
                    $this->db->where('article_id',$article_id);
                    $this->db->where('language_code',$lang_code);
                    $this->db->update('article_translate',$data_translate);
                } else {
                    $this->db->set('article_id', $article_id);
                    $this->db->set('language_code', $lang_code);
                    $this->db->insert('article_translate',$data_translate);
                }

                return $article_id;
            }
            else
                return FALSE;
        }
    }

    public function update_page()
    {
        $lang_code = $this->input->post('language');
        $metas = $this->input->post('metas');

        $update=[
            'article_date'          => $this->input->post('article_date'),
            'article_time_gmt'      => $this->input->post('article_time_gmt'),
            'thumbnail'             => $this->input->post('thumbnail'),
            'article_password'      => $this->input->post('article_password'),
            'article_status'        => $this->input->post('article_status'),
            'pin'                   => $this->input->post('pin')=='true' ? 1:0
        ];

        $article_id = $this->input->post('article_id');
        $article_des = '';
        if(isset($metas[1][1])){
            $article_des  = $metas[1][1]; // SEO description
        }

        if($lang_code == $this->default_language){

            $update_extension=[
                'article_title'        => $this->input->post('article_title'),
                'article_slug'         => slugify($this->input->post('article_title')),
                // 'article_description'  => getDescription($this->input->post('article_content')),
                'article_description'  => $article_des,
                'article_content'      => $this->input->post('article_content')
            ];
            $update = array_merge($update, $update_extension);
            
            if($this->db->update('articles', $update, ['id' => $article_id]))
                return $article_id;
            else
                return FALSE;
        }
        else
        {
            if($this->db->update('articles', $update, ['id' => $article_id]))
            {
                $this->db->reset_query();

                $this->db->where('article_id',$article_id);
                $this->db->where('language_code',$lang_code);
                $q = $this->db->get('article_translate');

                $data_translate = [
                    'article_title'        => $this->input->post('article_title'),
                    'article_slug'         => slugify($this->input->post('article_title')),
                    // 'article_description'  => getDescription($this->input->post('article_content')),
                    'article_description'  => $article_des,
                    'article_content'      => $this->input->post('article_content')
                ];

                if ( $q->num_rows() > 0 )
                {
                    $this->db->where('article_id',$article_id);
                    $this->db->where('language_code',$lang_code);
                    $this->db->update('article_translate',$data_translate);
                } else {
                    $this->db->set('article_id', $article_id);
                    $this->db->set('language_code', $lang_code);
                    $this->db->insert('article_translate',$data_translate);
                }

                return $article_id;
            }
            else{
                return FALSE;
            }
        }
    }

    // DELETE
    public function after_delete_article($article_id)
    {
        // delete all article translate
        $this->db->delete('article_translate', ['article_id' => $article_id]);
        // delete all meta of this
        $this->db->delete('articlemeta', ['article_id' => $article_id]);
    }


    // Scholar
    // CREATE
    public function insert_scholar($article_author)
    {
        $lang_code = $this->input->post('language');
        $metas = $this->input->post('metas');

        $this->article_author       = $article_author; // please read the below note
        $this->article_date         = $this->input->post('article_date');
        $this->article_time_gmt     = $this->input->post('article_time_gmt');
        $this->thumbnail            = $this->input->post('thumbnail');
        $this->article_password     = $this->input->post('article_password');
        $this->article_status       = $this->input->post('article_status');
        $this->article_type         = ArticleType::SCHOLAR;
        $this->pin                  = $this->input->post('pin')=='true' ? 1:0;

        if($lang_code == $this->default_language){
            $this->article_title        = $this->input->post('article_title');
            $this->article_slug         = slugify($this->input->post('article_title'));
            // $this->article_description  = getDescription($this->input->post('article_content'));
            $this->article_description  = '';
            if(isset($metas[1][1])){
                $this->article_description  = $metas[1][1]; // SEO description
            }
            $this->article_content      = $this->input->post('article_content');

            if($this->db->insert('articles', $this)){
                return $this->db->insert_id();
            }
            else
                return FALSE;
        }
        else
        {
            if($this->db->insert('articles', $this)){
                $article_id = $this->db->insert_id();
                
                // reset_query
                $this->db->reset_query();

                $this->db->where('article_id',$article_id);
                $this->db->where('language_code',$lang_code);
                $q = $this->db->get('article_translate');
                $article_des = '';
                if(isset($metas[1][1])){
                    $article_des  = $metas[1][1]; // SEO description
                }
                $data_translate = [
                    'article_title'        => $this->input->post('article_title'),
                    'article_slug'         => slugify($this->input->post('article_title')),
                    // 'article_description'  => getDescription($this->input->post('article_content')),
                    'article_description'  => $article_des,
                    'article_content'      => $this->input->post('article_content')
                ];

                if ( $q->num_rows() > 0 )
                {
                    $this->db->where('article_id',$article_id);
                    $this->db->where('language_code',$lang_code);
                    $this->db->update('article_translate',$data_translate);
                } else {
                    $this->db->set('article_id', $article_id);
                    $this->db->set('language_code', $lang_code);
                    $this->db->insert('article_translate',$data_translate);
                }

                return $article_id;
            }
            else
                return FALSE;
        }
    }

    public function update_scholar()
    {
        $lang_code = $this->input->post('language');
        $metas = $this->input->post('metas');

        $update=[
            'article_date'          => $this->input->post('article_date'),
            'article_time_gmt'      => $this->input->post('article_time_gmt'),
            'thumbnail'             => $this->input->post('thumbnail'),
            'article_password'      => $this->input->post('article_password'),
            'article_status'        => $this->input->post('article_status'),
            'pin'                   => $this->input->post('pin')=='true' ? 1:0
        ];

        $article_id = $this->input->post('article_id');
        $article_des = '';
        if(isset($metas[1][1])){
            $article_des  = $metas[1][1]; // SEO description
        }

        if($lang_code == $this->default_language){

            $update_extension=[
                'article_title'        => $this->input->post('article_title'),
                'article_slug'         => slugify($this->input->post('article_title')),
                // 'article_description'  => getDescription($this->input->post('article_content')),
                'article_description'  => $article_des,
                'article_content'      => $this->input->post('article_content')
            ];
            $update = array_merge($update, $update_extension);
            
            if($this->db->update('articles', $update, ['id' => $article_id]))
                return $article_id;
            else
                return FALSE;
        }
        else
        {
            if($this->db->update('articles', $update, ['id' => $article_id]))
            {
                $this->db->reset_query();

                $this->db->where('article_id',$article_id);
                $this->db->where('language_code',$lang_code);
                $q = $this->db->get('article_translate');

                $data_translate = [
                    'article_title'        => $this->input->post('article_title'),
                    'article_slug'         => slugify($this->input->post('article_title')),
                    // 'article_description'  => getDescription($this->input->post('article_content')),
                    'article_description'  => $article_des,
                    'article_content'      => $this->input->post('article_content')
                ];

                if ( $q->num_rows() > 0 )
                {
                    $this->db->where('article_id',$article_id);
                    $this->db->where('language_code',$lang_code);
                    $this->db->update('article_translate',$data_translate);
                } else {
                    $this->db->set('article_id', $article_id);
                    $this->db->set('language_code', $lang_code);
                    $this->db->insert('article_translate',$data_translate);
                }

                return $article_id;
            }
            else{
                return FALSE;
            }
        }
    }

}

/* End of file Article.php */
/* Location: ./application/models/Article.php */