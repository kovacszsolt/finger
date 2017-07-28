<?php

namespace finger\database;

/**
 *
 * Database Config Class main
 * @package finger\database
 */
class config extends \finger\config
{


	public $hostname = '';
	public $databasename = '';
	public $username = '';
	public $password = '';

	/**
	 * config constructor.
	 */
	public function __construct()
	{
		parent::__construct('database');
		$this->hostname = $this->get('hostname');
		$this->databasename = $this->get('databasename');
		$this->username = $this->get('username');
		$this->password = $this->get('password');
	}
}
