<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

/**
 * Install the initial data structure for tables:
 *	countries, currencies, customer_groups, extensions,
 *  languages, layout_modules, layout_routes, layouts,
 *  mail_templates, mail_templates_data, pages, permalinks,
 *  permissions, security_questions, settings, staff_groups,
 *  statuses, uri_routes
 */

$insert_languages_data = "REPLACE INTO `".$this->db->dbprefix."languages` (`language_code`, `name`, `flag`)
VALUES ('english', 'English', 'system/flags/en.png');";

$insert_position_data = "REPLACE INTO `".$this->db->dbprefix."position` (`name`)
VALUES ('TOP_HEADER'),('HEADER'),('SLIDER'),('LEFT_FIX'),('RIGHT_FIX'),('BOTTOM_FIX'),('LEFT_BOTTOM_FIX'),('RIGHT_BOTTOM_FIX'),('POPUP')";

$insert_term_default_data = "REPLACE INTO `".$this->db->dbprefix."terms` (`term_id`,`parent`,`term_name`,`slug`,`taxonomy`)
VALUES ('1','0','Others','others','article_category')";

$insert_option_default_data = "REPLACE INTO `".$this->db->dbprefix."options` (`option_key`,`option_value`,`option_type`,`autoload`)
	VALUES ('og:type','\<meta property=\"og:type\" content=\"website\" \/\>','meta_global', 'yes'),
	('title','\<meta name=\"title\" content=\"T Framework\" \/\>','meta_seo_default', 'yes'), ('description','\<meta name=\"description\" content=\"website\" \/\>','meta_seo_default', 'yes'), 
	('keyword','\<meta name=\"keyword\" content=\"website, education\" \/\>','meta_seo_default', 'yes')";