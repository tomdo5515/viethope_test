<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Menu_acronym extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->_ModifyMenu();
	}

	public function down() {
		$this->_Undomenu();
	}

	public function _ModifyMenu() {
		$fields = array(
			'acronym VARCHAR(50) NULL'
		);
		
		$this->dbforge->add_column('terms', $fields);
	}

	public function _Undomenu() {
		$this->dbforge->drop_column('terms', 'acronym');
	}
}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */