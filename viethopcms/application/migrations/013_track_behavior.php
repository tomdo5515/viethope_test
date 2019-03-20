<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Track_behavior extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->_Modify();
	}

	public function down() {
		$this->dbforge->drop_column('track_behavior', 'ipinfo');
	}

	public function _Modify() {
		$fields = array(
			'ipinfo VARCHAR(1000) NULL'
		);
		
		$this->dbforge->add_column('track_behavior', $fields);
	}
}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */