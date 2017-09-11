<?php

namespace finger;
/**
 * Class request
 * @package finger
 */
class request {

	const METHOD_POST = 'post';

	const METHOD_GET = 'get';

	/**
	 * String type
	 */
	const STRING = 'string';

	/**
	 * Number tyoe
	 */
	const NUMBER = 'number';

	/**
	 * File type JPG
	 */
	const FILE_TYPE_JPG = 'image/jpeg';

	/**
	 * File type PNG
	 */
	const FILE_TYPE_PNG = 'image/png';

	/**
	 * File type GIF
	 */
	const FILE_TYPE_GIF = 'image/gif';

	public static function backPath( $number ) {
		$_return = '';
		for ( $i = 0; $i < $number; $i ++ ) {
			$_return .= DIRECTORY_SEPARATOR . '..';
		}
		$_return .= DIRECTORY_SEPARATOR;

		return $_return;
	}

	/**
	 * $_FILES array
	 * check file mime type
	 *
	 * @param string $name
	 * @param bool $check
	 * @param null $allow
	 *
	 * @return null
	 */
	public static function files( $name, $check = false, $allow = null ) {
		$_return = null;
		if ( isset( $_FILES[ $name ] ) ) {
			$_files  = $_FILES[ $name ];
			$_return = $_files;
			if ( $check ) {
				foreach ( $_files['tmp_name'] as $_file_id => $_file ) {
					if ( ! is_null( $allow ) ) {
						$_mime_content_type = mime_content_type( $_files['tmp_name'][ $_file_id ] );
						if ( ! in_array( $_mime_content_type, $allow ) ) {
							$_return = null;
						}
					}
				}
			}
		}
		return $_return;

	}

	/**
	 * Set GET or POST parameter
	 *
	 * @param $name name of key
	 * @param $value
	 * @param string $method GET/POST
	 */
	public static function set( $name, $value, $method = request::METHOD_GET ) {
		if ( $method == request::METHOD_GET ) {
			$_GET[ $name ] = $value;
		} elseif ( $method == request::METHOD_POST ) {
			$_POST[ $name ] = $value;
		}
	}

	/**
	 * Get $_GET and $_POST parameter
	 *
	 * @param string $name
	 * @param string $default
	 * @param string $type
	 *
	 * @return int|string
	 */
	public static function get( $name = '', $default = '', $type = request::STRING ) {
		$_tmp_request = self::getAll();
		$_return      = $default;
		if ( $name == '' ) {
			return $_tmp_request;
		}
		if ( isset( $_tmp_request[ $name ] ) ) {
			$_return = $_tmp_request[ $name ];
		}
		switch ( $type ) {
			case request::NUMBER :
				$_return = ( $_return == '' ) ? 0 : $_return;
				break;
		}

		return $_return;
	}

	/**
	 * Get all methods
	 * @return array
	 */
	public static function getAll() {
		return array_merge( self::getGET(), self::getPOST() );
	}

	/**
	 * Get POST method variables
	 * @return array
	 */
	public static function getPOST() {
		return $_POST;
	}

	/**
	 * Get GET method variables
	 * @return array
	 */
	public static function getGET() {
		return $_GET;
	}

	/**
	 * return Current URL
	 * @return mixed
	 */
	public static function _currentURL() {
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * Get Current URL with host name
	 * @return string
	 */
	public static function _currentFullURL() {
		return self::getProtocol() . '://' . self::getServerName() . self::currentURL();
	}

	/**
	 * Get Current Server Name
	 * @return mixed
	 */
	public static function _getServerName(): string {
		$servername = $_SERVER['SERVER_NAME'];

		return $servername;
	}

	/**
	 * Get current client IP Address
	 * @return string
	 */
	public static function _getClientIPAddress(): string {
		return $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * get Current Protocol
	 * @return string
	 */
	public static function _getProtocol(): string {
		$protocol = 'http';
		if ( ( isset( $_SERVER['HTTPS'] ) ) && ( $_SERVER['HTTPS'] == 'on' ) ) {
			$protocol = 'https';
		}
		if ( ( isset( $_SERVER['SERVER_PORT'] ) ) && ( $_SERVER['SERVER_PORT'] == '443' ) ) {
			$protocol = 'https';
		}

		return $protocol;
	}


	/**
	 * Get Hostname with protocol
	 * @return string
	 */
	public static function _getFullHost(): string {
		return self::_getProtocol() . '://' . self::_getServerName();
	}


	/**
	 * Get Params
	 *
	 * @param int $number
	 * @param string $default
	 *
	 * @return string
	 */
	public static function getParam( int $number, string $default = '' ): string {
		$_param  = self::get( '_params', array() );
		$_return = $default;
		if ( isset( $_param[ $number ] ) ) {
			$_return = $_param[ $number ];
		}
		return $_return;
	}

	/**
	 * get Module name
	 * @return int|string
	 */
	public static function getModule(): string {
		return self::get( '_module' );
	}

	/**
	 * Get current Controller name
	 * @return string
	 */
	public static function getController(): string {
		return self::get( '_controller' );
	}

	/**
	 * Get current Action name
	 * @return string
	 */
	public static function getAction(): string {
		return self::get( '_action' );
	}

	/**
	 * Get current Method
	 * @return string
	 */
	public static function getMethod(): string {
		return self::get( '_method' );
	}

	/**
	 * Page redirection
	 *
	 * @param string $url
	 */
	public static function redirect( string $url ) {
		header( 'Location: ' . $url );
		die();
	}

	/**
	 * get current uri
	 * module/controller/action/method
	 * @return string
	 */
	public static function getRouting(): string {
		return self::getModule() . '/' . self::getController() . '/' . self::getAction() . '/' . self::getMethod() . '/' . \finger\server::method();
	}
}