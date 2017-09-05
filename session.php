<?php

namespace finger;

/**
 * Session handler
 * @package finger
 */
class session {

	/**
	 * Inicialization
	 */
	private static function _init() {
		/**
		 * Start Session if not started
		 */
		if ( session_status() == PHP_SESSION_NONE ) {
			session_start();
		}
	}

	/**
	 * Read value from Session
	 *
	 * @param $name Name
	 * @param null $default default value when not exits
	 *
	 * @return null Value
	 */
	public static function get( string $name, $default = null ) {
		self::_init();
		$_return = $default;
		if ( isset( $_SESSION[ $name ] ) ) {
			$_return = $_SESSION[ $name ];
		}

		return $_return;
	}

	/**
	 * Get all Session variable
	 * @return mixed
	 */
	public static function getAll() {
		self::_init();

		return $_SESSION;
	}

	/**
	 * Session ID
	 * @return string
	 */
	public static function getSessionID() {
		return session_id();
	}

	/**
	 * Save data to Session
	 *
	 * @param $name Name
	 * @param $value Value
	 */
	public static function set( string $name, $value ) {
		self::_init();
		$_SESSION[ $name ] = $value;
	}

	/**
	 * Remove item from Session
	 *
	 * @param $name
	 */
	public static function remove( string $name ) {
		if ( isset( $_SESSION[ $name ] ) ) {
			unset( $_SESSION[ $name ] );
		}
	}

	/**
	 * Session Flash
	 * Remove Session after read
	 *
	 * @param $name
	 * @param null $value
	 *
	 * @return null
	 */
	public static function flash( string $name, $value = null
	) {
		$_name = 'flash.' . $name;
		if ( is_null( $value ) ) {
			$_return = self::get( $_name, '' );
			self::remove( $_name );

			return $_return;
		} else {
			self::set( $_name, $value );
		}
	}

	/**
	 * Kill all Session value
	 */
	public static function removeAll() {
		if ( is_array( $_SESSION ) ) {
			foreach ( $_SESSION as $session ) {
				self::remove( $session );
			}
		}
	}
}
