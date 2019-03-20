<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Menu_gallery extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->_Gallery();
		$this->_ModifyMenu();
	}

	public function down() {
		$this->dbforge->drop_table('gallery');
		$this->dbforge->drop_column('terms', 'hyperlink');
	}

	public function _Gallery() {
		$fields = array(
			'id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'term_id BIGINT(20) NULL',
			'image VARCHAR(1000) NOT NULL'
		);
		
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('gallery');
	}

	public function _ModifyMenu() {
		$fields = array(
			'hyperlink VARCHAR(255) NULL'
		);
		
		$this->dbforge->add_column('terms', $fields);
	}
}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */