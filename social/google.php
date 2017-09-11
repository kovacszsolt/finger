<?php

namespace finger\social;

use finger\request as request;
use finger\session as session;

/**
 * Google Account Class
 * @package finger\social
 */
class google {

	private $_googleClass;
	private $_accessToken = '';

	/**
	 * google constructor.
	 */
	public function __construct() {
		$_configSocial = new \finger\config( 'social' );
		$this->_googleClass = new \Google_Client();
		$this->_googleClass->setApplicationName( $_configSocial->get( 'google.applicationname' ) );
		$this->_googleClass->setClientId( $_configSocial->get( 'google.clientid' ) );
		$this->_googleClass->setClientSecret( $_configSocial->get( 'google.clientsecret' ) );
		$this->_googleClass->setScopes( explode( ';', $_configSocial->get( 'google.scope' ) ) );
	}

	/**
	 * Set Redirect URL for Login
	 * @param string $url
	 */
	public function setRedirectUri( string $url ) {
		$this->_googleClass->setRedirectUri( $url );
	}

	/**
	 * Read redirect URL
	 * @param string $url
	 *
	 * @return string
	 */
	public function getRedirectURL( string $url ) {
		$this->setRedirectUri( $url );
		$_return = $this->_googleClass->createAuthUrl();

		return $_return;
	}

	/**
	 * Get Access Token
	 * @return string
	 */
	public function getToken() {
		if ( $this->_accessToken == '' ) {
			$this->_accessToken = $this->_googleClass->authenticate( request::get( 'code', '' ) );
		}

		return $this->_accessToken;
	}

	/**
	 * Set Access Token
	 * @param string $token
	 */
	public function setToken( string $token ) {
		$this->_googleClass->setAccessToken( $token );
	}

	/**
	 * Get Current User Attributes
	 * @return mixed
	 */
	public function getAttributes() {
		if ( is_null( $this->_googleClass->getAccessToken() ) ) {
			$this->setToken( \finger\session::get( 'googlesession', '' ) );
		}
		$token_data = $this->_googleClass->verifyIdToken()->getAttributes();
		$_return    = $token_data['payload'];

		return $_return;
	}

	/**
	 * Logout
	 */
	public function logout() {
		session::remove( 'googlesession' );
		$this->_accessToken = '';
	}

}