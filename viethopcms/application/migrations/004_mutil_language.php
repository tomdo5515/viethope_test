<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Mutil_language extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->_ArticleTranslate();
		$this->_ModifyArticleMeta();

		$this->_TermTranslate();
		$this->_ModifyTermMeta();

		$this->insertInitialData();
	}

	public function down() {
		$this->dbforge->drop_table('article_translate');
		$this->_ModifyArticleMetaUndo();

		$this->dbforge->drop_table('terms_translate');
		$this->_ModifyTermUndo();
		$this->_ModifyTermMetaUndo();
	}

	// ARTICLE
	public function _ArticleTranslate() {
		$fields = array(
			'article_id BIGINT(20) NOT NULL',
			'article_title VARCHAR(1000) NOT NULL',
			'article_slug VARCHAR(200) NOT NULL',
			'article_description VARCHAR(1000) NOT NULL',
			'article_content LONGTEXT NOT NULL',
			'language_code VARCHAR(20) DEFAULT "english"',
			'PRIMARY KEY (`article_id`,`language_code`)'
		);
		
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('article_translate');
	}

	public function _ModifyArticleMeta() {
		$fields = array(
	        'language_code VARCHAR(20) DEFAULT "english"',
		);
		$this->dbforge->add_column('articlemeta', $fields);
	}

	public function _ModifyArticleMetaUndo() {
		$this->dbforge->drop_column('articlemeta', 'language_code');
	}

	// TERMS
	
	public function _TermTranslate() {
		$fields = array(
			'term_id BIGINT(20) NOT NULL',
			'term_name VARCHAR(1000) NOT NULL',
			'slug VARCHAR(200) NOT NULL',
			'language_code VARCHAR(20) DEFAULT "english"',
			'PRIMARY KEY (`term_id`,`language_code`)'
		);
		
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('terms_translate');
	}

	public function _ModifyTermMeta() {
		$fields = array(
	        'language_code VARCHAR(20) DEFAULT "english"',
		);
		$this->dbforge->add_column('termmeta', $fields);
	}

	public function _ModifyTermMetaUndo() {
		$this->dbforge->drop_column('termmeta', 'language_code');
	}

	protected function insertInitialData() {
		
		$insert_quotes_data = "";

		if ( ! empty($insert_quotes_data)) {
			$this->db->query($insert_quotes_data);
		}

	}

}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */