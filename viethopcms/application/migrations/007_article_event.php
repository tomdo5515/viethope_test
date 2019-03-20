<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Article_event extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->_ModifyActicle();
	}

	public function down() {
		$this->_UndoArticle();
	}

	public function _ModifyActicle() {
		$fields = array(
			'event_date datetime NULL',
			'event_time_gmt INT(11) NULL'
		);
		
		$this->dbforge->add_column('articles', $fields);
	}

	public function _UndoArticle() {
		$this->dbforge->drop_column('articles', 'event_date');
		$this->dbforge->drop_column('articles', 'event_time_gmt');
	}
}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */