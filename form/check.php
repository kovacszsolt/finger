<?php

namespace finger\form;

use \finger\session as session;
use \finger\random as random;
use \finger\request as request;
use \finger\form\field as formfield;

/**
 * Form check Class
 * Class check
 * @package finger\form
 */
class check {
	private $fields;
	private $errorFields = array();

	const REQUEST_GET = 'GET';
	const REQUEST_POST = 'POST';
	const REQUEST_ALL = 'ALL';

	/**
	 * get errors
	 * @return array
	 */
	public function getError() {
		return $this->errorFields;
	}

	/**
	 * Add field to validation
	 * @param $name
	 * @param $required
	 * @param $type
	 * @param string $length
	 */
	public function addField( $name, $required, $type, $length = '' ) {
		$this->fields[ $name ] = new formfield( $name, $required, $type, $length );
	}

	/**
	 * Generate CSRF key
	 * @return string
	 */
	public static function generateKey() {
		$_session = new session();
		$_key     = random::char( 10 );
		$_value   = random::char( 10 );
		$_return  = $_key . '.' . $_value;
		$_session->setValue( $_key, $_value );

		return $_return;
	}

	/**
	 * Inicialize form from INI file
	 * @param $filename
	 *
	 * @return bool
	 */
	public function init( $filename ) {
		$_return   = false;
		$_filename = MAINPATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'form' . DIRECTORY_SEPARATOR . \finger\server::host() . DIRECTORY_SEPARATOR . $filename . '.ini';
		if ( is_file( $_filename ) ) {
			$_data = parse_ini_file( $_filename, true );
			foreach ( $_data as $key => $value ) {
				$_value_array = explode( '.', $value );
				$this->addField( $key, $_value_array[0], $_value_array[1], ( isset( $_value_array[1] ) ) ? 0 : $_value_array[1] );
			}
			$_return = true;
		}

		return $_return;
	}

	/**
	 * Check the fields
	 * @return bool
	 */
	private function checkFields() {
		$_return = false;
		foreach ( $this->fields as $field_name => $field ) {
			if ( $field->checkRequired() ) {
				$_return = $field->check();
			} else {
				$this->errorFields[] = array(
					'field' => $field,
					'type'  => 'required'
				);
			}
		}

		return $_return;
	}

	/**
	 * POST field check
	 * @param string $request_type
	 *
	 * @return bool
	 */
	public function check( $request_type = self::REQUEST_POST ) {
		$_return = false;
		switch ( $request_type ) {
			case self::REQUEST_POST:
				$_request = request::getPOST();
				break;
			case self::REQUEST_GET:
				$_request = request::getGET();
				break;
			case self::REQUEST_ALL:
				$_request = request::getAll();
				break;
		}

		$_key_compare = array_merge( array_diff_key( $_request, $this->fields ), array_diff_key( $this->fields, $_request ) );
		if ( ( sizeof( $_key_compare ) == 0 ) || ( is_null( $_key_compare ) ) ) {
			foreach ( $this->fields as $field_name => $field ) {
				$field->setValue( $_request[ $field_name ] );
			}
			$_return = $this->checkFields();
		}

		return $_return;
	}
}