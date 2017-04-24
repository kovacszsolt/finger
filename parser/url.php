<?php

namespace finger\parser;

/**
 * URL Parser Class
 * @package finger\parser
 */
class url
{
	/**
	 * get Domain name from URL
	 * @param string $url
	 * @return string
	 */
	public static function getDomain($url)
	{
		if (substr($url, 0, 4) != 'http') {
			$url = 'http://' . $url;
		}
		$_tmp = explode('/', $url);
		$_return = $_tmp[2];
		return $_return;
	}

}