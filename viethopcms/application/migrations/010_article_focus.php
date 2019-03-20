<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Article_focus extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->_ModifyArticle();
	}

	public function down() {
		$this->dbforge->drop_column('articles', 'focus');
	}

	public function _ModifyArticle() {
		$fields = array(
			'focus VARCHAR(50) NULL' // all, student, member, supporter
		);
		
		$this->dbforge->add_column('articles', $fields);
	}
}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */