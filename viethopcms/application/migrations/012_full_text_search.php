<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Full_text_search extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->_CreateIndex();
	}

	public function down() {
		$sql = "ALTER TABLE articles DROP INDEX article_title;";
		$this->db->query($sql);
		$sql = "ALTER TABLE article_translate DROP INDEX article_title;";
		$this->db->query($sql);
	}

	public function _CreateIndex() {
		
		$sql = "ALTER TABLE articles ADD FULLTEXT (article_title, article_content);";
		$this->db->query($sql);
		$sql = "ALTER TABLE article_translate ADD FULLTEXT (article_title, article_content);";
		$this->db->query($sql);
	}
}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */