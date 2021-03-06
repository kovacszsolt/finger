<?php

namespace finger;

use finger\log;

/**
 * Filesystem Server enviroment class
 * @package finger
 */
class server
{
	/**
	 * get Document Root
	 * @return string
	 */
	public static function documentRoot()
	{
		$_return = $_SERVER['DOCUMENT_ROOT'];
		if (substr($_return, -1) != '/') {
			$_return .= '/';
		}
		return $_return;
	}

	/**
	 * get Server Name
	 * @return string
	 */
	public static function host()
	{
		// check host name maybe CLI?
		if (isset($_SERVER['HTTP_HOST'])) {
			$_return = str_replace(':', '_', $_SERVER['HTTP_HOST']);
		} elseif ((defined('HTTP_HOST'))) {
			$_return = HTTP_HOST;
		}
		if ($_return == '') {
			log::save('Server error! HTTP_HOST not found!');
			die();
		}
		return $_return;
	}


	/**
	 * Get Current URI
	 * @return bool|string
	 */
	public static function uri()
	{
		$_return = $_SERVER['REQUEST_URI'];
		if (substr($_return, 0, 1) == '/') {
			$_return = substr($_return, 1);
		}
		if (substr($_return, -1) != '/') {
			$_return .= '/';
		}
		return $_return;
	}

	/**
	 * Get Current Method
	 * @return string
	 */
	public static function method()
	{
		$_return = ucfirst(strtolower($_SERVER['REQUEST_METHOD']));
		return $_return;
	}
}