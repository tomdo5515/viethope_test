<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * 	Install the initial tables:

 */
class Migration_Schema extends CI_Migration {

	public function up() {
		$this->load->database();

		$this->_track_behavior();
		$this->_languages();
		$this->_position();
		$this->_banners();

		$this->_options();
		$this->_terms();
		$this->_termmeta();
		$this->_term_relationships();

		$this->_articles();
		$this->_articlemeta();
		//
		$this->insertInitialData();
		$this->optimizeDatabase();
	}

	public function down() {
		
		$this->dbforge->drop_table('track_behavior');
		$this->dbforge->drop_table('languages');
		$this->dbforge->drop_table('position');
		$this->dbforge->drop_table('banners');
		$this->dbforge->drop_table('options');

		$this->dbforge->drop_table('terms');
		$this->dbforge->drop_table('termmeta');
		$this->dbforge->drop_table('term_relationships');

		$this->dbforge->drop_table('articles');
		$this->dbforge->drop_table('articlemeta');
	}

	public function _track_behavior() {
		$fields = array(
			'id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'ip_address VARCHAR(100) NOT NULL',
			'sessionid VARCHAR(255) NOT NULL',
			'time DATETIME DEFAULT "0000-00-00 00:00:00"',
			'url_request VARCHAR(1000)',
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('track_behavior');
    }

	public function _languages() {
		$fields = array(
			'language_code VARCHAR(20) NOT NULL PRIMARY KEY',
			'name VARCHAR(50) NOT NULL',
			'flag VARCHAR(1000)',
			'directory VARCHAR(32)',
			'status VARCHAR(20) DEFAULT "publish"',
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('languages');
	}

	public function _position() {
		$fields = array(
			'position_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'name VARCHAR(255) NOT NULL',
			'status VARCHAR(20) DEFAULT "publish"',
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('position');
	}

	public function _banners() {
		$fields = array(
			'banner_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'position_id VARCHAR(255) NOT NULL',
			'name VARCHAR(255) NOT NULL',
			'click_url VARCHAR(255)',
			'content TEXT NOT NULL',
			'banner_order INT(11) DEFAULT 0'
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('banners');
	}

	public function _options() {
		$fields = array(
			'option_id BIGINT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',			
			'option_key VARCHAR(20) NULL',
			'option_value TEXT NOT NULL',
			'option_type VARCHAR(20) NOT NULL',
			'autoload VARCHAR(20) DEFAULT "no"'
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('options');
	}

	public function _terms() {
		$fields = array(
			'term_id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'parent BIGINT(11) DEFAULT 0',
			'term_name VARCHAR(255) NULL',
			'slug VARCHAR(255)',
			'taxonomy VARCHAR(50) NOT NULL',//menu, category, tag, slider, etc.
			'font_icon VARCHAR(50) NULL',
			'term_order INT(11) DEFAULT 0',
			'count INT(11) DEFAULT 0'
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('terms');
	}

	public function _term_relationships() {
		$fields = array(
			'object_id BIGINT(20) NOT NULL',
			'term_id INT(11) NOT NULL'
		);
		$this->dbforge->add_key('object_id', TRUE);
		$this->dbforge->add_key('term_id', TRUE);
		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('term_relationships');
	}

	public function _termmeta() {
		$fields = array(
			'meta_id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'term_id INT(11) NOT NULL',
			'meta_key VARCHAR(255) NOT NULL',
			'meta_value TEXT NOT NULL'
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('termmeta');
	}

	public function _articles() {
		$fields = array(
			'id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'article_author INT(11) NOT NULL',
			'article_date DATETIME DEFAULT "0000-00-00 00:00:00"',
			'article_time_gmt INT(11) NOT NULL',
			'article_title VARCHAR(1000) NOT NULL',
			'article_slug VARCHAR(200) NOT NULL',
			'article_description VARCHAR(1000) NOT NULL',
			'article_content LONGTEXT NOT NULL',
			'article_password VARCHAR(255) DEFAULT ""',
			'article_status VARCHAR(20) DEFAULT "publish"',
			'article_type VARCHAR(20) DEFAULT "article"',//page
			'thumbnail VARCHAR(500)',
			'favorite_count INT(11) DEFAULT "0"',
			'comment_count INT(11) DEFAULT "0"',
			'pin TINYINT(1) DEFAULT 0'
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('articles');
	}

	public function _articlemeta() {
		$fields = array(
			'meta_id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'article_id BIGINT(20) NOT NULL',
			'meta_key VARCHAR(255) NOT NULL',
			'meta_value TEXT NOT NULL'
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->create_table('articlemeta');
	}

	protected function insertInitialData() {
		include(SOLUTION . '/migrations/initial_schema.php');

		if ( ! empty($insert_languages_data)) {
			$this->db->query($insert_languages_data);
		}

		if ( ! empty($insert_position_data)) {
			$this->db->query($insert_position_data);
		}

		if ( ! empty($insert_term_default_data)) {
			$this->db->query($insert_term_default_data);
		}

		if ( ! empty($insert_option_default_data)) {
			$this->db->query($insert_option_default_data);
		}
	}

	protected function optimizeDatabase() {

		// $this->load->dbutil();
		// $result = $this->dbutil->optimize_database();
		
		// if ($result !== FALSE)
		// {
		// 	print_r($result);
		// }
		// else{
		// 	echo('optimizeDatabase fail this fuction support only mysql');
		// }
	}
}

/* End of file 001_schema.php */
/* Location: ./setup/migrations/001_schema.php */