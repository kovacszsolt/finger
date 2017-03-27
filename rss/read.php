<?php

namespace finger\rss;

/**
 * RSS read Class
 * @package finger\rss
 */
class read
{
	/**
	 * read all items
	 * @param string $url
	 * @return mixed
	 */
	public static function read($url)
	{
		$_xml = simplexml_load_file($url);
		$json_string = json_encode($_xml);
		$result_array = json_decode($json_string, TRUE);
		return $result_array['channel']['item'];
	}

}