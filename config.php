<?php
namespace finger;

use finger\server;
use finger\log;
/**
 * main configuration
 * Class config
 * @package finger
 */
class config
{

	/**
	 * config file type
	 * @var string
	 */
	private $type;

	/**
	 * config file variables
	 * @var array
	 */
	private $data;

	/**
	 * config file path
	 * @var string
	 */
	private $fileName;

	/**
	 * Check config file name
	 * @return string
	 */
	private function getFileName()
	{
		$_host = server::host();
		$_configFileName = MAINPATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $_host . DIRECTORY_SEPARATOR . $this->type . '.ini';
		if (!is_file($_configFileName)) {
			log::save('no config file:' . $_configFileName);
			die();
		}
		return $_configFileName;
	}

	/**
	 * Read ini file to array
	 */
	private function getData()
	{
		$this->data = parse_ini_file($this->fileName, true);
	}

	/**
	 * config constructor.
	 * @param $iniType
	 */
	public function __construct($iniType)
	{
		$this->type = $iniType;
		$this->fileName = $this->getFileName();
		$this->getData();
	}

	/**
	 * Get value from INI
	 * @param $name
	 * @param $default
	 * @return mixed
	 */
	public function get($name, $default = '')
	{
		$_return = $default;
		if (isset($this->data[$name])) {
			$_return = $this->data[$name];
		}
		return $_return;
	}

	public function getAll()
	{
		return $this->data;
	}

}