<?php
/**
 * GoFramework
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	Gogosloution
 * @author	Joh Tran
 * @copyright	Copyright (c) 2017 - 2020, GoFramework, Inc.
 * @copyright	Copyright (c) 2017 - 2020, EllisLab, Inc. (https://ellislab.com/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://Gogosloution.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration Class
 *
 * All migrations should implement this, forces up() and down() and gives
 * access to the CI super-global.
 *
 * @package		Gogosloution
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Joh Tran
 * @link
 */
class TF_Migration extends CI_Migration {
	
	/**
	 * Initialize Migration Class
	 *
	 * @param	array	$config
	 * @return	void
	 */
	public function __construct($config = array())
	{

		foreach ($config as $key => $val)
		{
			$this->{'_'.$key} = $val;
		}

		log_message('info', 'Migrations Class Initialized');

		// Are they trying to use migrations while it is disabled?
		if ($this->_migration_enabled !== TRUE)
		{
			show_error('Migrations has been loaded but is disabled or set up incorrectly.');
		}

		// If not set, set it
		$this->_migration_path !== '' OR $this->_migration_path = array( APPPATH.'migrations/', );

		// Add trailing slash if not set
		foreach ($this->_migration_path as $key => $value) 
		{
			$this->_migration_path[$key] = rtrim($value, '/').'/';
		}

		// Load migration language
		$this->lang->load('migration');

		// They'll probably be using dbforge
		$this->load->dbforge();

		// Make sure the migration table name was set.
		if (empty($this->_migration_table))
		{
			show_error('Migrations configuration file (migration.php) must have "migration_table" set.');
		}

		// Migration basename regex
		$this->_migration_regex = ($this->_migration_type === 'timestamp')
			? '/^\d{14}_(\w+)$/'
			: '/^\d{3}_(\w+)$/';

		// Make sure a valid migration numbering type was set.
		if ( ! in_array($this->_migration_type, array('sequential', 'timestamp')))
		{
			show_error('An invalid migration numbering type was specified: '.$this->_migration_type);
		}

		// If the migrations table is missing, make it
		if ( ! $this->db->table_exists($this->_migration_table))
		{
			$this->dbforge->add_field(array(
				'version' => array('type' => 'BIGINT', 'constraint' => 20),
			));

			$this->dbforge->create_table($this->_migration_table, TRUE);

			$this->db->insert($this->_migration_table, array('version' => 0));
		}

		// Do we auto migrate to the latest migration?
		if ($this->_migration_auto_latest === TRUE && ! $this->latest())
		{
			show_error($this->error_string());
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Retrieves list of available migration scripts
	 *
	 * @return	array	list of migration file paths sorted by version
	 */
	public function find_migrations()
	{
		$migrations = array();

		foreach ($this->_migration_path  as $migration_path) {
			// Load all *_*.php files in the migrations path
			foreach (glob($migration_path.'*_*.php') as $file)
			{
				$name = basename($file, '.php');

				// Filter out non-migration files
				if (preg_match($this->_migration_regex, $name))
				{
					$number = $this->_get_migration_number($name);

					// There cannot be duplicate migration numbers
					if (isset($migrations[$number]))
					{
						$this->_error_string = sprintf($this->lang->line('migration_multiple_version'), $number);
						show_error($this->_error_string);
					}

					$migrations[$number] = $file;
				}
			}
		}
		

		ksort($migrations);
		return $migrations;
	}

}
