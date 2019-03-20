<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Partner extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->_Partner();
	}

	public function down() {
		$this->dbforge->drop_table('partner');
	}

	public function _Partner() {
		$fields = array(
			'id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'name varchar(100) NULL',
			'link varchar(500) NULL',
			'thumbnail varchar(500) NULL',
 			'status TINYINT(1) NOT NULL DEFAULT "1"',
		);
		
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('partner');
	}
}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */