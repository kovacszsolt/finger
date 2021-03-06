<?php

namespace finger\form;

use \finger\session as session;
use \finger\request as request;
use \finger\random as random;
use \finger\config as config;


class field {
	private $name;
	private $required;
	private $type;
	private $length;
	private $value;
	private $_session;
	private $_config;

	public function __construct( $name, $required, $type, $length = '' ) {
		$this->_session = new session();
		$this->setName( $name );
		$this->setRequired( $required );
		$this->setType( $type );
		$this->setLength( $length );
		$this->_config = new config( 'settings' );
		$this->_config = $this->_config->get( 'secure' );
	}

	public function setName( $value ) {
		$this->name = $value;
	}

	public function setRequired( $value ) {
		$this->required = $value;
	}

	public function setType( $value ) {
		$this->type = $value;
	}

	/**
	 * Set field min lenght
	 *
	 * @param $value
	 */
	public function setLength( $value ) {
		$this->length = $value;
	}

	/**
	 * Set field Value
	 *
	 * @param $value
	 */
	public function setValue( $value ) {
		$this->value = $value;
	}

	/**
	 * Check field
	 * @return bool
	 */
	public function check() {
		$_return = false;
		switch ( $this->type ) {
			case 'email' :
				$_return = $this->checkEmail();
				break;
			case 'string' :
				$_return = $this->checkString();
				break;
			case 'csrf':
				$_return = $this->checkCSRF();
				break;
			case 'gcaptcha':
				$_return = $this->checkGCaptcha();
				break;
			default:
				echo 'no: ' . $this->type;
				exit;

		}

		return $_return;
	}

	/**
	 * Google ReCaptcha check
	 * @return bool
	 */
	private function checkGCaptcha() {
		$_return = false;

		$data = array(
			'secret'   => $this->_config['googlecaptcaptchasecret'],
			'response' => request::get( 'g-recaptcha-response', '' )
		);

		$verify = curl_init();
		curl_setopt( $verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify" );
		curl_setopt( $verify, CURLOPT_POST, true );
		curl_setopt( $verify, CURLOPT_POSTFIELDS, http_build_query( $data ) );
		curl_setopt( $verify, CURLOPT_RETURNTRANSFER, true );
		$response     = curl_exec( $verify );
		$responseData = json_decode( $response );
		if ( $responseData->success ) {
			$_return = true;
		}

		return $_return;
	}


	/**
	 * Check valid email
	 * @return bool
	 */
	private function checkEmail() {
		$_return = ( $this->value == filter_var( $this->value, FILTER_VALIDATE_EMAIL ) ) ? true : false;

		return $_return;
	}

	private function checkString() {
		$_return = ( $this->value != '' ) ? true : false;

		return $_return;
	}

	public function checkRequired() {
		$_return = false;
		if ( $this->required == 'req' ) {
			$_return = true;
		}

		return $_return;
	}

	/**
	 * Check CSRF Token
	 * @return bool
	 */
	private function checkCSRF() {
		$_return     = false;
		$_valueArray = explode( '.', $this->value );
		if ( sizeof( $_valueArray ) == 2 ) {
			$_keyPre = $this->_session->getValue( $_valueArray[0], '' );
			if ( $_keyPre != '' ) {
				if ( $_keyPre == $_valueArray[1] ) {
					$_return = true;
				}
			}
		}

		return $_return;
	}
}