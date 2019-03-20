<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Active_logs extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->_ModifyTrackBehavior();
	}

	public function down() {
		$this->_UndoTrackBehavior();
	}

	public function _ModifyTrackBehavior() {
		$fields = array(
			'user_id BIGINT(20) NULL'
		);
		
		$this->dbforge->add_column('track_behavior', $fields);
	}

	public function _UndoTrackBehavior() {
		$this->dbforge->drop_column('track_behavior', 'user_id');
	}
}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */