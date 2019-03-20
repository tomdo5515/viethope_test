<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Crawl_facebook extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->_FacebookData();
	}

	public function down() {
		$this->dbforge->drop_table('facebookdata');
	}

	public function _FacebookData() {
		$fields = array(
			'id VARCHAR(100) NOT NULL',
			'full_picture VARCHAR(1000) DEFAULT ""',
			'link VARCHAR(500) DEFAULT ""',
			'created_time DATETIME NULL',
			'`like` INT(11) DEFAULT "0"',
			'love INT(11) DEFAULT "0"',
			'wow INT(11) DEFAULT "0"',
			'sad INT(11) DEFAULT "0"',
			'angry INT(11) DEFAULT "0"',
			'shares INT(11) DEFAULT "0"',
			'source VARCHAR(1000) DEFAULT ""',
			'description VARCHAR(2000) DEFAULT ""',
			'PRIMARY KEY (`id`)'
		);
		
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('facebookdata');
	}
}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */