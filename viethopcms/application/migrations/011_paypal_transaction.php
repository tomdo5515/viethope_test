<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Paypal_transaction extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {
		$this->_Payments();
	}

	public function down() {
		$this->dbforge->drop_table('payments');
	}

	public function _Payments() {
		$fields = array(
			'id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'price float(10,2) NULL',
			'payer_email varchar(100) NULL',
			'txn_id varchar(100) NULL',
			'logs text NULL',
 			'status INT(11) NOT NULL DEFAULT "0"',
		);
		
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('payments');
	}
}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */