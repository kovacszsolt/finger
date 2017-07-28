<?php

namespace finger;
/**
 * Special Convert Class
 * Class convert
 * @package finger
 */
class convert {

	/**
	 * Convert integer to Bool
	 * @param $value
	 *
	 * @return bool
	 */
	public static function intToBoolean( $value ) {
		$_return = false;
		if ( is_int( $value ) ) {
			if ( $value != 0 ) {
				$_return = true;
			}
		}
		return $_return;
	}
}