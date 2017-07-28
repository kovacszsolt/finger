<?php

namespace finger\view;

use finger\request;
use \finger\session as session;
use \finger\server;
use \finger\config as config;

/**
 * Template render Class
 * @package finger\view
 */
class render
{

	/**
	 * Controller name
	 * @var
	 */
	public $controller;

	/**
	 * Values
	 * @var
	 */
	private $values;

	/**
	 * Session
	 * @var session
	 */
	protected $session;

	/**
	 * Template file
	 * @var null
	 */
	private $file;

	protected $settings;

	/**
	 * current path variables
	 * @var array
	 */
	protected $path;

	/**
	 * Special JS file
	 * /site/[module]/js.controller/[controller]/[action]/[method]/
	 * @var string
	 */
	protected $_jsPath = '';

	/**
	 * render constructor.
	 */
	public function __construct()
	{
		$_configClass = new config('settings');
		$this->settings = $_configClass;
		$this->session = new session();
		$this->file = NULL;
		$this->path = array(
			'module' => request::get('_module'),
			'controller' => request::get('_controller'),
			'action' => request::get('_action'),
			'method' => request::get('_method')
		);
		$_tmp = 'site' . DIRECTORY_SEPARATOR . $this->path['module'] . DIRECTORY_SEPARATOR . 'js.controller' . DIRECTORY_SEPARATOR . $this->path['controller'] . '.' . $this->path['action'] . '.' . $this->path['method'] . '.js';
		if (is_file(server::documentRoot() . $_tmp)) {
			$this->_jsPath = $_tmp;
		}
	}

	/**
	 * Form URL
	 * @return string
	 */
	public function formURL()
	{
		$_return = '/' . request::get('_module') . '/' . request::get('_controller') . '/' . request::get('_action') . '/form/';
		return $_return;
	}

	/**
	 * Set template file
	 * @param $filename
	 */
	public function setFile($filename)
	{
		$this->file = $filename;
	}

	/**
	 * Add value to Template
	 * @param $name
	 * @param $value
	 */
	public function addValue($name, $value)
	{
		$this->values[$name] = $value;
	}

	/**
	 * Get Template stored value
	 * @param $name
	 * @param null $default
	 * @return null
	 */
	public function getValue($name, $default = NULL)
	{
		$return = $default;
		if (isset($this->values[$name])) {
			$return = $this->values[$name];
		}
		return $return;
	}

	/**
	 * Render Template
	 * @param bool $print
	 * @return string
	 */
	public function render($print = true)
	{
		$_return = '';
		$_file = server::documentRoot() . '../' . $this->file;
		if (!$print) {
			ob_start();
		}
		if (is_file($_file)) {
			include_once $_file;
		} else {
			echo 'template not found = ' . $_file;
		}
		if (!$print) {
			$_return = ob_get_contents();
			ob_clean();

		}
		return $_return;
	}

	/**
	 * Stored value to JSON
	 * @param $value
	 */
	public function renderJSON($value)
	{
		echo json_encode($value);
	}



	/**
	 * Show Javascript
	 * @param $file
	 */
	public function elementJavascript($file)
	{
		$echo = '';
		if (is_file(server::documentRoot() . '/' . $file)) {
			$echo = '<script src="' . $file . '"></script>' . PHP_EOL;
		}
		echo $echo;
	}

	/**
	 * Include file to Template
	 * @param $file
	 * @param null $var local variable
	 */
	public function includeFile($file, $var = NULL)
	{
		$_file = MAINPATH . '/../site/' . request::get('_module') . '/' . $file;
		include $_file;
	}

	/**
	 * Facebook Share button
	 * @param $url
	 */
	public function _facebookShare($url)
	{
		echo '
    <div class="fb-share-button" data-href="' . request::getProtocol() . '://' . request::getServerName() . $url . '" 
        data-layout="button_count" 
        data-size="small" 
        data-mobile-iframe="true">
        <a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='
		     . urlencode(request::getProtocol() . '://' . request::getServerName() . $url) . '">Megosztás</a></div>
    ';
	}


}