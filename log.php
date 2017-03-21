<?php
namespace finger;

/**
 * Logging
 * @package finger
 */
class log
{

	/**
	 * Save error message
	 * @param string $message
	 * @param bool $print
	 */
	public static function save($message,$print=true) {
		if ($print) {
			echo $message.PHP_EOL;
		}
		error_log($message);
	}
}
