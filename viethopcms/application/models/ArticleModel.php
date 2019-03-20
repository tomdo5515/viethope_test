<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ArticleModel extends CI_Model {
	
	// 
	public $article_author;

	public $article_date;

	public $article_time_gmt;

    public $article_title;

    public $article_slug;

    public $article_description;

    public $article_content;    

    public $thumbnail;
    
    public $article_password;

	public $article_status;

	public $article_type;

    public $favorite_count = 0;

	public $comment_count = 0;

    public $event_date;

	public $event_time_gmt;

	private $language_code = 'english';

    private $default_language = 'english';

    public $pin = 0;

    public $focus = 'all';

    public function __construct()
    {
        parent::__construct();
        $this->default_language = $this->config->item('language');
        $this->language_code = $this->session->userdata('language');
    }

    // READ
    public function get_by_eventtime($guest, $categories=FALSE, $limit=10, $pined = FALSE)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.event_date, articles.event_time_gmt, users.first_name, users.last_name, terms.term_id, terms.term_name, terms.slug');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.event_date, articles.event_time_gmt, users.first_name, users.last_name, terms.term_id, terms.term_name, terms.slug');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }

        $this->db->from('articles');

        $this->db->join('users', 'users.id = articles.article_author');
        $this->db->join('term_relationships', 'term_relationships.object_id = articles.id');
        $this->db->join('terms', 'terms.term_id = term_relationships.term_id');
        // $this->db->where('articles.event_time_gmt>=',time());
        $this->db->where('terms.taxonomy', Taxonomy::CATEGORY);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);
        if ($pined) {
            $this->db->where('articles.pin', ArticleStatus::PIN);
        }
        if($categories !== FALSE){
            if(is_array($categories)){
                $this->db->where_in('terms.term_id', $categories);
            }else{
                $this->db->where('terms.term_id', $categories);
            }
        }

        $this->db->order_by('event_time_gmt', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_n_articles($guest, $from=0)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_description, articles.thumbnail, articles.favorite_count,  articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }
        
        $this->db->from('articles');
        $this->db->join('users', 'users.id = articles.article_author');

        $this->db->where('articles.article_type', ArticleType::ARTICLE);
        // $this->db->where('terms.taxonomy', Taxonomy::CATEGORY);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);
        // $this->db->where('articles.pin', ArticleStatus::UNPIN);
        
        if($guest){
            $this->db->order_by("case when articles.focus = '$guest' then 1 else 2 end, articles.article_date desc");
        }
        else{
            $this->db->order_by('articles.article_date','desc');
        }

        $total_item = $this->db->count_all_results('', FALSE);
        
        $this->db->limit(ArticleShow::ITEM_PER_PAGE, $from);
        $query = $this->db->get();
        $result['articles'] = $query->result_array();

        // extension totalpage
        $result['total_page'] = $total_item;
        return $result;
    }

    public function get_n_articles_by_category($guest, $from=0, $categories)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_description, articles.thumbnail, articles.favorite_count,  articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }
        
        $this->db->from('articles');
        $this->db->join('users', 'users.id = articles.article_author');
        $this->db->where('terms.taxonomy', Taxonomy::CATEGORY);
        $this->db->where('articles.article_type', ArticleType::ARTICLE);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);
        // $this->db->where('articles.pin', ArticleStatus::UNPIN);

        if($categories){
            $this->db->join('term_relationships', 'term_relationships.object_id = articles.id');
            $this->db->join('terms', 'terms.term_id = term_relationships.term_id');
            if(is_array($categories)){
                $this->db->where_in('terms.slug', $categories);
            }else{
                $this->db->where('terms.slug', $categories);
            }
        }

        if($guest){
            $this->db->order_by("case when articles.focus = '$guest' then 1 else 2 end, articles.article_date desc");
        }
        else{
            $this->db->order_by('articles.article_date','desc');
        }

        $total_item = $this->db->count_all_results('', FALSE);

        // $this->db->order_by('articles.article_date','desc');
        $this->db->limit(ArticleShow::ITEM_PER_PAGE, $from);
        $this->db->distinct();
        $query = $this->db->get();
        $result['articles'] = $query->result_array();

        // extension totalpage
        $result['total_page'] = $total_item;

        return $result;
    }

    public function get_n_articles_by_tag($guest ,$from=0, $tags)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_description, articles.thumbnail, articles.favorite_count,  articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }
        
        $this->db->from('articles');
        $this->db->join('users', 'users.id = articles.article_author');
        $this->db->where('terms.taxonomy', Taxonomy::TAG);
        $this->db->where('articles.article_type', ArticleType::ARTICLE);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);
        // $this->db->where('articles.pin', ArticleStatus::UNPIN);

        if($tags){
            $this->db->join('term_relationships', 'term_relationships.object_id = articles.id');
            $this->db->join('terms', 'terms.term_id = term_relationships.term_id');
            if(is_array($tags)){
                $this->db->where_in('terms.slug', $tags);
            }else{
                $this->db->where('terms.slug', $tags);
            }
        }

        $total_item = $this->db->count_all_results('', FALSE);

        if($guest){
            $this->db->order_by("case when articles.focus = '$guest' then 1 else 2 end, articles.article_date desc");
        }
        else{
            $this->db->order_by('articles.article_date','desc');
        }

        $this->db->limit(ArticleShow::ITEM_PER_PAGE, $from);
        $this->db->distinct();
        $query = $this->db->get();
        $result['articles'] = $query->result_array();

        // extension totalpage
        $result['total_page'] = $total_item;

        return $result;
    }

    public function get_n_articles_by_author($guest, $from=0, $authors=FALSE)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_description, articles.thumbnail, articles.favorite_count,  articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }
        
        $this->db->from('articles');
        $this->db->join('users', 'users.id = articles.article_author');

        $this->db->where('articles.article_type', ArticleType::ARTICLE);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);
        // $this->db->where('articles.pin', ArticleStatus::UNPIN);
        if($authors !== FALSE){
            if(is_array($authors)){
                $this->db->where_in('users.username', $authors);
            }else{
                $this->db->where('users.username', $authors);
            }
        }

        $total_item = $this->db->count_all_results('', FALSE);

        if($guest){
            $this->db->order_by("case when articles.focus = '$guest' then 1 else 2 end, articles.article_date desc");
        }
        else{
            $this->db->order_by('articles.article_date','desc');
        }

        $this->db->limit(ArticleShow::ITEM_PER_PAGE, $from);
        $query = $this->db->get();
        $result['articles'] = $query->result_array();

        // extension totalpage
        $result['total_page'] = $total_item;

        return $result;
    }

    public function get_limit_articles($guest, $categories=FALSE, $limit=10)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.event_date, users.first_name, users.last_name, terms.term_id, terms.term_name, terms.slug');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.event_date, users.first_name, users.last_name, terms.term_id, terms.term_name, terms.slug');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }

        $this->db->from('articles');

        $this->db->join('users', 'users.id = articles.article_author');
        $this->db->join('term_relationships', 'term_relationships.object_id = articles.id');
        $this->db->join('terms', 'terms.term_id = term_relationships.term_id');

        $this->db->where('terms.taxonomy', Taxonomy::CATEGORY);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);

        if($categories !== FALSE){
            if(is_array($categories)){
                $this->db->where_in('terms.term_id', $categories);
            }else{
                $this->db->where('terms.term_id', $categories);
            }
        }

        if($guest){
            $this->db->order_by("case when articles.focus = '$guest' then 1 else 2 end, articles.id desc");
        }
        else{
            $this->db->order_by('articles.id','desc');
        }

        $this->db->limit($limit);
        $this->db->distinct();
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_rand_articles($guest, $limit=10, $categories=FALSE)
    {

        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, users.first_name, users.last_name, terms.term_id, terms.term_name, terms.slug');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, users.first_name, users.last_name, terms.term_id, terms.term_name, terms.slug');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }

        $this->db->from('articles');

        $this->db->join('users', 'users.id = articles.article_author');
        $this->db->join('term_relationships', 'term_relationships.object_id = articles.id');
        $this->db->join('terms', 'terms.term_id = term_relationships.term_id');

        $this->db->where('terms.taxonomy', Taxonomy::CATEGORY);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);

        if($categories !== FALSE){
            if(is_array($categories)){
                $this->db->where_in('terms.term_id', $categories);
            }else{
                $this->db->where('terms.term_id', $categories);
            }
        }

        if($guest){
            $this->db->order_by("case when articles.focus = '$guest' then 1 else 2 end, rand()");
        }
        else{
            $this->db->order_by('rand()');
        }

        $this->db->limit($limit);
        $this->db->distinct();
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_pin_articles($guest, $limit = 4, $categories=FALSE)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_description, articles.thumbnail, articles.favorite_count,  articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }
        
        $this->db->from('articles');
        $this->db->join('users', 'users.id = articles.article_author');

        $this->db->where('articles.article_type', ArticleType::ARTICLE);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);
        $this->db->where('articles.pin', ArticleStatus::PIN);
        
        if($categories !== FALSE){
            $this->db->join('term_relationships', 'term_relationships.object_id = articles.id');
            $this->db->join('terms', 'terms.term_id = term_relationships.term_id');
            if(is_array($categories)){
                $this->db->where_in('terms.slug', $categories);
            }else{
                $this->db->where('terms.slug', $categories);
            }
        }
        
        if($guest){
            $this->db->order_by("case when articles.focus = '$guest' then 1 else 2 end, articles.article_date desc");
        }
        else{
            $this->db->order_by('articles.article_date','desc');
        }

        $this->db->limit($limit);
        $this->db->distinct();
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_near_articles($id)
    {
        // prev
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_description, articles.thumbnail, articles.favorite_count,  articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }
        
        $this->db->from('articles');
        $this->db->join('users', 'users.id = articles.article_author');

        $this->db->where('articles.article_type', ArticleType::ARTICLE);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);
        $this->db->where('articles.id<', $id);
        $this->db->order_by('articles.id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        $result['prev'] = $query->row_array();

        // next
        $this->db->reset_query();
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_description, articles.thumbnail, articles.favorite_count,  articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }
        
        $this->db->from('articles');
        $this->db->join('users', 'users.id = articles.article_author');

        $this->db->where('articles.article_type', ArticleType::ARTICLE);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);
        $this->db->where('articles.id>', $id);
        $this->db->order_by('articles.id', 'asc');
        $this->db->limit(1);
        $query = $this->db->get();
        $result['next'] = $query->row_array();

        return $result;
    }

    public function get_article($id)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, users.username, users.first_name, users.last_name');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, users.username, users.first_name, users.last_name');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }

        $this->db->from('articles');

        $this->db->join('users', 'users.id = articles.article_author');

        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);
        $this->db->where('articles.id', $id);

        $query = $this->db->get();
        if($query -> num_rows() > 0)
            return $query->row_array();
        else
            return FALSE;
    }

    public function get_any_article($id)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, articles.article_password, users.first_name, users.last_name');

        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, articles.article_password, users.first_name, users.last_name');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
        }

        $this->db->from('articles');

        $this->db->join('users', 'users.id = articles.article_author');

        $this->db->where('articles.id', $id);
        
        $query = $this->db->get();
        if($query -> num_rows() > 0)
            return $query->row_array();
        else
            return FALSE;
    }

    public function get_fts_article($guest, $keyword, $from=0)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, articles.article_password, users.first_name, users.last_name');
        $this->db->where("MATCH (article_title,article_content) AGAINST ('\"".$keyword."\" @10' IN BOOLEAN MODE)");
        if ($this->default_language != $this->language_code) {
            $this->db->reset_query();
            $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, articles.article_password, users.first_name, users.last_name');
            $this->db->join('article_translate', 'article_translate.article_id = articles.id');
            $this->db->where('article_translate.language_code', $this->language_code);
            $this->db->where("MATCH (article_translate.article_title,article_translate.article_content) AGAINST ('\"".$keyword."\" @10' IN BOOLEAN MODE)");
        }
        
        $this->db->from('articles');
        $this->db->where('articles.article_type', ArticleType::ARTICLE);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);
        $this->db->join('users', 'users.id = articles.article_author');
        
        $total_item = $this->db->count_all_results('', FALSE);

        if($guest){
            $this->db->order_by("case when articles.focus = '$guest' then 1 else 2 end, articles.article_date desc");
        }
        else{
            $this->db->order_by('articles.article_date','desc');
        }

        $this->db->limit(ArticleShow::ITEM_PER_PAGE, $from);
        $query = $this->db->get();
        $result['articles'] = $query->result_array();

        // extension totalpage
        $result['total_page'] = $total_item;

        return $result;
    }

    public function get_rss_articles($limit=0)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_description, articles.thumbnail, articles.favorite_count,  articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');

        // if ($this->default_language != $this->language_code) {
        //     $this->db->reset_query();
        //     $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_translate.article_title, article_translate.article_slug, article_translate.article_description, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.event_date, users.username, users.first_name, users.last_name');
        //     $this->db->join('article_translate', 'article_translate.article_id = articles.id');
        //     $this->db->where('article_translate.language_code', $this->language_code);
        // }

        $this->db->from('articles');
        $this->db->join('users', 'users.id = articles.article_author');
        $this->db->where('articles.article_type', ArticleType::ARTICLE);
        $this->db->where('articles.article_status', ArticleStatus::PUBLISH);
        $this->db->order_by('articles.id','desc');
        $this->db->limit($limit, 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    // For BACKEND
    public function get_article_default_lang($id)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, articles.event_date, articles.article_password, articles.focus, articles.pin, users.first_name, users.last_name, 
        terms.term_id, terms.term_name, terms.slug');

        $this->db->from('articles');

        $this->db->join('users', 'users.id = articles.article_author');
        $this->db->join('term_relationships', 'term_relationships.object_id = articles.id');
        $this->db->join('terms', 'terms.term_id = term_relationships.term_id');

        $this->db->where('articles.id', $id);
        
        $query = $this->db->get();
        if($query -> num_rows() > 0)
            return $query->row_array();
        else
            return FALSE;
    }

    public function get_event_default_lang($id)
    {
        $this->db->select('articles.id, articles.article_date, articles.article_time_gmt, article_title, article_slug, article_content, articles.thumbnail, articles.favorite_count, articles.comment_count, articles.article_status, articles.event_date, articles.article_password, users.first_name, users.last_name');

        $this->db->from('articles');

        $this->db->join('users', 'users.id = articles.article_author');
        $this->db->where('articles.id', $id);
        
        $query = $this->db->get();
        if($query -> num_rows() > 0)
            return $query->row_array();
        else
            return FALSE;
    }

    public function get_transate_article($articles_id ,$lang_code)
    {
        if($lang_code == $this->default_language)
        {
            $this->db->select('article_title, article_slug, 
            article_content');
            $this->db->from('articles');

            $this->db->where('id', $articles_id);
        }
        else
        {
            $this->db->select('article_title, article_slug, 
            article_content, language_code');
            $this->db->from('article_translate');

            $this->db->where('article_id', $articles_id);
            $this->db->where('language_code', $lang_code);
        }
        
        $query = $this->db->get();
        if($query -> num_rows() > 0)
            return $query->row_array();
        else
            return FALSE;
    }

    // CREATE
    public function insert_article($article_author)
    {
        $lang_code = $this->input->post('language');
        $metas = $this->input->post('metas');

        $this->article_author       = $article_author; // please read the below note
        $this->article_date         = $this->input->post('article_date');
        $this->article_time_gmt     = $this->input->post('article_time_gmt');
        $this->thumbnail            = $this->input->post('thumbnail');
        $this->article_password     = $this->input->post('article_password');
        $this->article_status       = $this->input->post('article_status');
        $this->article_type         = ArticleType::ARTICLE;
        $this->pin                  = $this->input->post('pin')=='true' ? 1:0;
        $this->focus                = $this->input->post('focus');
        if($this->input->post('event_date')){
            $this->event_date           = $this->input->post('event_date');
        }
        if($this->input->post('event_time_gmt')){
            $this->event_time_gmt       = $this->input->post('event_time_gmt');
        }

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

    // UPDATE
    public function update_article()
    {
        $lang_code = $this->input->post('language');
        $metas = $this->input->post('metas');

        $update=[
            'article_date'          => $this->input->post('article_date'),
            'article_time_gmt'      => $this->input->post('article_time_gmt'),
            'thumbnail'             => $this->input->post('thumbnail'),
            'article_password'      => $this->input->post('article_password'),
            'article_status'        => $this->input->post('article_status'),
            'pin'                   => $this->input->post('pin')=='true' ? 1:0,
            'focus'                 => $this->input->post('focus')
        ];

        if($this->input->post('event_date')){
            $update['event_date']   = $this->input->post('event_date');
        }
        if($this->input->post('event_time_gmt')){
            $update['event_time_gmt']       = $this->input->post('event_time_gmt');
        }


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

    // EVENT
    // CREATE
    public function insert_event($article_author)
    {
        $lang_code = $this->input->post('language');
        $metas = $this->input->post('metas');

        $this->article_author       = $article_author; // please read the below note
        $this->article_date         = $this->input->post('article_date');
        $this->article_time_gmt     = $this->input->post('article_time_gmt');
        $this->thumbnail            = $this->input->post('thumbnail');
        $this->article_password     = $this->input->post('article_password');
        $this->article_status       = $this->input->post('article_status');
        $this->article_type         = ArticleType::EVENT;
        $this->pin                  = $this->input->post('pin')=='true' ? 1:0;
        $this->event_date           = $this->input->post('event_date');
        $this->event_time_gmt       = $this->input->post('event_time_gmt');

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

    // UPDATE
    public function update_event()
    {
        $lang_code = $this->input->post('language');
        $metas = $this->input->post('metas');

        $update=[
            'article_date'          => $this->input->post('article_date'),
            'article_time_gmt'      => $this->input->post('article_time_gmt'),
            'thumbnail'             => $this->input->post('thumbnail'),
            'article_password'      => $this->input->post('article_password'),
            'article_status'        => $this->input->post('article_status'),
            'pin'                   => $this->input->post('pin')=='true' ? 1:0,
            'event_date'            => $this->input->post('event_date'),
            'event_time_gmt'        => $this->input->post('event_time_gmt')
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