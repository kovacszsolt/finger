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
	public static function all($url)
	{
		$_xml = simplexml_load_file($url);
		$json_string = json_encode($_xml);
		$result_array = json_decode($json_string, TRUE);
		return $result_array['channel']['item'];
	}

	/**
	 * Find item via URL
	 * @param string $url
	 * @param array $itemUrl
	 * @return null
	 */
	public static function findUrl($url, $itemUrl)
	{
		$_all = self::all($url);
		$_return = NULL;
		foreach ($_all as $_item) {
			if ($_item['link'] == $itemUrl) {
				$_return = $_item;
			}
		}
		return $_return;
	}

}