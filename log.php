<?php

namespace finger;

/**
 * Logging
 * @package finger
 */
class log {

	/**
	 * Save error message
	 *
	 * @param string $message
	 * @param bool $print
	 */
	public static function save( $message, bool $print = false ) {
		if ( $print ) {
			echo $message . PHP_EOL;
		}
		error_log( $message );
	}
}
