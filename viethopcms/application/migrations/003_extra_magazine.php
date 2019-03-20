<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Extra_magazine extends CI_Migration {

	public function up() {
		$this->_survey();
		$this->_answer();
		$this->insertInitialData();
	}

	public function down() {
		$this->dbforge->drop_table('survey');
		$this->dbforge->drop_table('answer');
	}

	public function _survey() {
		$fields = array(
			'id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'parent BIGINT(20) NOT NULL DEFAULT 0',
			'article_author INT(11) NOT NULL',
			'title VARCHAR(100) NOT NULL',
			'question TEXT',
			'answer VARCHAR(1000)',
			'time DATETIME DEFAULT "0000-00-00 00:00:00"'
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('survey');
	}

	public function _answer() {
		$fields = array(
			'session_id BIGINT(20) NOT NULL',
			'id_answer BIGINT(20) NOT NULL',
			'ip_address VARCHAR(100) NOT NULL'
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('answer');
	}

	protected function insertInitialData() {
		
		$insert_quotes_data = "";

		if ( ! empty($insert_quotes_data)) {
			$this->db->query($insert_quotes_data);
		}

	}

}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */