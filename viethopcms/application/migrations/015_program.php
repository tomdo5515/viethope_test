<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Program extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->_Program();
	}

	public function down() {
		$this->dbforge->drop_table('program');
	}

	public function _Program() {
		$fields = array(
			'id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'title varchar(500) NOT NULL',
			'acronym varchar(100) NULL',
			'hyperlink varchar(500) NOT NULL',
			'thumbnail varchar(500) NULL',
			'description_english varchar(2000) NULL',
			'description_vietnamese varchar(2000) NULL',
			'total_funded varchar(100) NULL',
			'num_program varchar(100) NULL',
			'program_order INT(11) NULL DEFAULT 0',
 			'status TINYINT(1) NOT NULL DEFAULT "1"',
		);
		
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('program');
	}
}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */