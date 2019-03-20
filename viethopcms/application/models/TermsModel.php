<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
* helper term 
*/
class termh
{
	public $id;

    public $text;

    public $slug;

	public $checked;

    public $hyperlink;

    public $acronym;
    
    public $children;
}

class TermsModel extends CI_Model
{
    public $term_id;
    
    public $parent;

    public $term_name;

    public $slug;

    public $taxonomy;

    public $font_icon;

    public $term_order;

    public $count;
    
    public $hyperlink;

    public $acronym;

    private $language_code = 'english';

    private $default_language = 'english';

    public function __construct()
    {
        parent::__construct();
        $this->default_language = $this->config->item('language');
        $this->language_code = $this->session->userdata('language');
    }

    // READ
    public function get_terms()
    {
        $this->db->select('*');
        $this->db->from('terms');
        if(IsNullOrEmpty($this->input->post('taxonomy')))
        {
            $this->db->where('taxonomy', $this->input->post('taxonomy'));
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_any_term($term_id)
    {
        $lang_code = $this->input->post('language') ? $this->input->post('language') : $this->default_language;

        if($lang_code != $this->default_language)
        {
            $this->db->select('`terms_translate.term_name`, `terms_translate.slug`, `terms.term_order`, `terms.parent`');
            $this->db->from('terms_translate');
            $this->db->join('terms', 'terms.term_id = terms_translate.term_id');
            $this->db->where('terms_translate.language_code', $lang_code);
        }
        else
        {
            $this->db->select('*');
            $this->db->from('terms');
        }

        $this->db->where('term_id', $term_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_terms_by_type($taxonomy)
    {
        $this->db->select('*');
        $this->db->from('terms');
        $this->db->where('taxonomy', $taxonomy);
        $this->db->order_by('term_order');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_transate_term($term_id, $lang_code)
    {
        if($lang_code == $this->default_language)
        {
            $this->db->select('`term_name`,`slug`,`term_order`');
            $this->db->from('terms');
            $this->db->where('term_id', $term_id);
        }
        else
        {
            $this->db->select('`terms_translate.term_name`,`terms_translate.slug`,`terms.term_order`');
            $this->db->from('terms_translate');
            $this->db->join('terms', 'terms.term_id = terms_translate.term_id', 'right');
            $this->db->where('language_code', $lang_code);
            $this->db->where('terms_translate.term_id', $term_id);
        }

        $query = $this->db->get();
        if($query -> num_rows() > 0)
            return $query->row_array();
        else
            return FALSE;
    }

    public function get_article_terms($article_id=0)
    {
        $this->db->select('*');
        $this->db->from('terms');
        $this->db->where_in('taxonomy', [Taxonomy::CATEGORY, Taxonomy::TAG]);
        if($article_id > 0){
            $this->db->join('term_relationships', 'term_relationships.term_id = terms.term_id');
            $this->db->where('term_relationships.object_id', $article_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_categories($article_id=0)
    {
        $this->db->select('*');
        $this->db->from('terms');
        $this->db->where('taxonomy', Taxonomy::CATEGORY);
        if($article_id > 0){
            $this->db->join('term_relationships', 'term_relationships.term_id = terms.term_id');
            $this->db->where('term_relationships.object_id', $article_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_tags($article_id=0)
    {
        $this->db->select('*');
        $this->db->from('terms');
        $this->db->where('taxonomy', Taxonomy::TAG);
        if($article_id>0){
            $this->db->join('term_relationships', 'term_relationships.term_id = terms.term_id');
            $this->db->where('term_relationships.object_id', $article_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_typehead_tags()
    {
        $this->db->select('term_id, term_name');
        $this->db->from('terms');
        $this->db->where('taxonomy', Taxonomy::TAG);
        if(IsNullOrEmpty($this->input->get('query')))
        {
            $this->db->like('term_name', $this->input->get('query'));
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_menus($article_id=0)
    {
        $this->db->select('*');
        $this->db->from('terms');
        $this->db->where('taxonomy', Taxonomy::MENU);
        if($article_id>0){
            $this->db->join('term_relationships', 'term_relationships.term_id = terms.term_id');
            $this->db->where('term_relationships.object_id', $article_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_children_menus($parent = -1)
    {
        $this->db->select('*');
        $this->db->from('terms');
        $this->db->where('taxonomy', Taxonomy::MENU);
        if($parent >= 0){
            $this->db->where('terms.parent', $parent);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getobjmenus()
	{
        $raw = $this->get_terms_by_type(Taxonomy::MENU);
		$format_data = [];
		foreach ($raw as $menu) {
			if($menu['parent'] === '0'){
				$temp = new termh();
				$temp->id = $menu['term_id'];
				$temp->text = $menu['term_name'];
                $temp->slug = $menu['slug'];
                $temp->hyperlink = $menu['hyperlink'];
                $temp->acronym = $menu['acronym'];
                $temp->checked = false;
				$temp->children = $this->findTermChild($temp, $raw);
				array_push($format_data, $temp); 
			}
        }
        
        return $format_data;
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
                $temp->slug = $value['slug'];
                $temp->hyperlink = $value['hyperlink'];
                $temp->acronym = $value['acronym'];
				$temp->checked = false;
				$temp->children = $this->findTermChild($temp, $arr);
				array_push($child, $temp); 
			}
		}

		return $child;
	}

    // CREATE
    
    // Insert Quick
    // insert default language
    public function insert_quick_category()
    {
        $this->parent               = $this->input->post('parent'); // please read the below note
        $this->count                = 0;
        $this->taxonomy             = Taxonomy::CATEGORY;
        $this->term_name        = $this->input->post('term_name');
        $this->slug             = slugify($this->input->post('term_name'));

        if($this->db->insert('terms', $this))
        {
            return $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function insert_quick_menu()
    {
        $this->parent               = $this->input->post('parent'); // please read the below note
        $this->count                = 0;
        $this->taxonomy             = Taxonomy::MENU;
        $this->term_name            = $this->input->post('term_name');
        $this->slug                 = slugify($this->input->post('term_name'));

        if($this->db->insert('terms', $this))
        {
            return $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }
    }

    public function insert_quick_tag()
    {
        $this->parent       = 0; // please read the below note
        $this->term_name    = $this->input->post('term_name');

        if(IsNullOrEmpty($this->input->post('slug')))
        {
            $this->slug     = slugify($this->input->post('term_name'));
        }
        else{
            $this->slug     = $this->input->post('slug');
        }

        $this->taxonomy     = Taxonomy::TAG;
        $isE = $this->isExistTerm();
        
        if($isE===FALSE){
            if($this->db->insert('terms', $this))
                return $this->db->insert_id();
            else
                return FALSE;
        }
        else{
            return $isE;
        }
    }

    // insert with language
    public function insert_category()
    {
        $lang_code = $this->input->post('language');

        $this->parent               = $this->input->post('parent'); // please read the below note
        $this->font_icon            = $this->input->post('font_icon');
        $this->term_order           = $this->input->post('term_order');
        $this->count                = 0;
        $this->taxonomy             = Taxonomy::CATEGORY;

        if($lang_code == $this->default_language)
        {
            $this->term_name        = $this->input->post('term_name');
            $this->slug             = slugify($this->input->post('term_name'));

            if($this->db->insert('terms', $this))
            {
                return $this->db->insert_id();
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            if($this->db->insert('terms', $this))
            {
                $term_id = $this->db->insert_id();
                // reset_query
                $this->db->reset_query();

                $this->db->where('term_id', $term_id);
                $this->db->where('language_code', $lang_code);
                $q = $this->db->get('terms_translate');

                $data_translate = [
                    'term_name'            => $this->input->post('term_name'),
                    'slug'                 => slugify($this->input->post('term_name')),
                    'language_code'        => $lang_code
                ];

                if ( $q->num_rows() > 0 )
                {
                    $this->db->where('term_id',$term_id);
                    $this->db->where('language_code',$lang_code);
                    $this->db->update('terms_translate',$data_translate);
                } else {
                    $this->db->set('term_id', $term_id);
                    $this->db->set('language_code', $lang_code);
                    $this->db->insert('terms_translate',$data_translate);

                    // insert language default first
                    if($lang_code != $this->config->item('language')){
                        $this->db->set('term_id', $article_id);
                        $this->db->set('language_code', $this->config->item('language'));
                        $this->db->insert('terms_translate',$data_translate);
                    }
                }

                return $term_id;
            }
            else
                return FALSE;
        }
    }

    public function insert_menu()
    {
        $lang_code = $this->input->post('language');

        $this->parent               = $this->input->post('parent'); // please read the below note
        $this->font_icon            = '';
        $this->term_order           = $this->input->post('term_order');
        $this->count                = 0;
        $this->hyperlink            = $this->input->post('term_hyperlink');
        $this->taxonomy             = Taxonomy::MENU;
        $this->acronym              = $this->input->post('acronym');

        if($lang_code == $this->default_language)
        {
            $this->term_name        = $this->input->post('term_name');
            if($this->input->post('term_slug')){
                $this->slug             = slugify($this->input->post('term_slug'));
            }
            else{
                $this->slug             = slugify($this->input->post('term_name'));
            }
            
            if($this->db->insert('terms', $this))
            {
                return $this->db->insert_id();
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            if($this->db->insert('terms', $this)){
                $term_id = $this->db->insert_id();
                // reset_query
                $this->db->reset_query();

                $this->db->where('term_id',$term_id);
                $this->db->where('language_code',$lang_code);
                $q = $this->db->get('terms_translate');

                $data_translate = [
                    'term_name'            => $this->input->post('term_name'),
                    'slug'                 => slugify($this->input->post('term_name')),
                    'language_code'        => $lang_code
                ];

                if($this->input->post('term_slug')){
                    $data_translate['slug'] = slugify($this->input->post('term_slug'));
                }

                if ( $q->num_rows() > 0 )
                {
                    $this->db->where('term_id',$term_id);
                    $this->db->where('language_code',$lang_code);
                    $this->db->update('terms_translate',$data_translate);
                } else {
                    $this->db->set('term_id', $term_id);
                    $this->db->set('language_code', $lang_code);
                    $this->db->insert('terms_translate',$data_translate);
                }

                return $term_id;
            }
            else
                return FALSE;
        }
    }

    public function insert_tag()
    {
        $this->parent       = 0; // please read the below note
        $this->term_name    = $this->input->post('term_name');

        if(IsNullOrEmpty($this->input->post('slug')))
        {
            $this->slug     = slugify($this->input->post('term_name'));
        }
        else{
            $this->slug     = $this->input->post('slug');
        }
        $this->taxonomy     = Taxonomy::TAG;
        $isE = $this->isExistTerm();
        
        if($isE===FALSE){
            if($this->db->insert('terms', $this))
                return $this->db->insert_id();
            else
                return FALSE;
        }
        else{
            return $isE;
        }


        $this->parent               = 0; // please read the below note
        $this->font_icon            = 'fa fa-tag';
        $this->count                = 0;
        $this->taxonomy             = Taxonomy::TAG;

        $isE = $this->isExistTerm();

        if($isE===FALSE){
            if($this->db->insert('terms', $this))
                return $this->db->insert_id();
            else
                return FALSE;
        }
        else{
            return $isE;
        }

        if($this->db->insert('terms', $this)){
            $term_id = $this->db->insert_id();
            $language_code = $this->input->post('language');

            // reset_query
            $this->db->reset_query();

            $this->db->where('term_id',$term_id);
            $this->db->where('language_code',$language_code);
            $q = $this->db->get('terms_translate');

            $data_translate = [
                'term_name'            => $this->input->post('term_name'),
                'slug'                 => slugify($this->input->post('term_name')),
                'language_code'        => $language_code
            ];

            if ( $q->num_rows() > 0 )
            {
                $this->db->where('term_id',$term_id);
                $this->db->where('language_code',$language_code);
                $this->db->update('terms_translate',$data_translate);
            } else {
                $this->db->set('term_id', $term_id);
                $this->db->set('language_code', $language_code);
                $this->db->insert('terms_translate',$data_translate);

                // insert language default first
                if($language_code != $this->config->item('language')){
                    $this->db->set('term_id', $article_id);
                    $this->db->set('language_code', $this->config->item('language'));
                    $this->db->insert('terms_translate',$data_translate);
                }
            }

            return $term_id;
        }
        else
            return FALSE;
    }

    public function insert_tag_input($tag_name)
    {
        $this->parent       = 0; // please read the below note
        $this->term_name    = $tag_name;
        $this->slug         = slugify($tag_name);
        $this->taxonomy     = Taxonomy::TAG;
        $isE = $this->isExistTerm();
        
        if($isE===FALSE){
            if($this->db->insert('terms', $this))
                return $this->db->insert_id();
            else
                return FALSE;
        }
        else{
            return $isE;
        }
    }

    // UPDATE
    public function update_menu()
    {
        $lang_code      = $this->input->post('language');
        $term_id        = $this->input->post('term_id');

        $update = [
            'parent' => $this->input->post('parent'),
            'term_order' => $this->input->post('term_order'),
            'acronym'    => $this->input->post('acronym'),
            'hyperlink'  => $this->input->post('term_hyperlink')
        ];

        if($this->input->post('term_slug')){
            $this->slug             = slugify($this->input->post('term_slug'));
        }
        else{
            $this->slug             = slugify($this->input->post('term_name'));
        }

        if($lang_code == $this->default_language)
        {
            $update_extension=[
                'term_name'        => $this->input->post('term_name'),
                'slug'             => $this->slug
            ];

            $update = array_merge($update, $update_extension);

            if($this->db->update('terms', $update, ['term_id' => $term_id]))
            {
                return $term_id;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            if($this->db->update('terms', $update, ['term_id' => $term_id]))
            {
                // reset_query
                $this->db->reset_query();

                $this->db->where('term_id', $term_id);
                $this->db->where('language_code', $lang_code);
                $q = $this->db->get('terms_translate');

                $data_translate = [
                    'term_name'        => $this->input->post('term_name'),
                    'slug'              => $this->slug,
                    'language_code'        => $lang_code
                ];

                if ( $q->num_rows() > 0 )
                {
                    $this->db->where('term_id', $term_id);
                    $this->db->where('language_code',$lang_code);
                    $this->db->update('terms_translate',$data_translate);
                } else {
                    $this->db->set('term_id', $term_id);
                    $this->db->set('language_code', $lang_code);
                    $this->db->insert('terms_translate',$data_translate);
                }

                return $term_id;
            }
            else
                return FALSE;
        }
    }
    
    

    // DELETE
    // public function delete_term()
    // {
    //     $this->db->delete('terms', ['term_id' => $this->input->post('term_id')]);
    // }
    
    // EXTRA
    public function insert_term_relationships($object_id, $term_id){
        if($this->isExistRelationships($object_id, $term_id)===FALSE){
            $data = [
                'object_id'=> $object_id,
                'term_id'=> $term_id
            ];

            if($this->db->insert('term_relationships', $data))
                return TRUE;
            else
                return FALSE;
        }
        else{
            return TRUE;
        }
    }

    public function delete_relationships_by_article($object_id){
        $this->db->delete('term_relationships', ['object_id' => $object_id]);
        // $this->db->join('terms','term_id');
        // $this->db->where_in('`terms`.`taxonomy`', [Taxonomy::TAG, Taxonomy::CATEGORY]);
        // $this->db->where('object_id',  $object_id);
        // $this->db->delete('term_relationships');  
        // Produces: // DELETE *
        // FROM term_relationships
        // INNER JOIN terms ON term_relationships.`term_id` = `terms`.`term_id`
        // WHERE `acronym` IN ('article_category','article_tag') AND `term_relationships`.`object_id` = 1
    }

    public function delete_term_relationships($term_id){
        $this->db->delete('term_relationships', ['term_id' => $term_id]);  // Produces: // DELETE FROM mytable  // WHERE id = $id
    }

    // HELPER
    function isExistTerm()
	{
		$this->db->select('term_id');
        $this->db->where('taxonomy', $this->taxonomy);
        $this->db->where('slug', $this->slug);
		$this->db->from('terms');
		$this->db->limit(1);
		$query = $this->db->get();

		if($query -> num_rows() > 0)
			return $query->unbuffered_row()->term_id;
		else
		    return FALSE;
    }
    
    function isExistRelationships($object_id, $term_id)
	{
		$this->db->select('object_id');
        $this->db->where('object_id', $object_id);
        $this->db->where('term_id', $term_id);
		$this->db->from('term_relationships');
		$this->db->limit(1);
		$query = $this->db->get();

		if($query -> num_rows() > 0)
			return TRUE;
		else
		    return FALSE;
	}
}
