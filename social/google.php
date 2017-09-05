<?php

namespace finger\social;

use finger\request as request;

class google {

	private $_googleClass;
	private $_accessToken = '';

	public function __construct() {
		//$this->_session = new \finger\session();
		$_configSocial = new \finger\config( 'social' );


		$this->_googleClass = new \Google_Client();
		$this->_googleClass->setApplicationName( $_configSocial->get( 'google.applicationname' ) );
		$this->_googleClass->setClientId( $_configSocial->get( 'google.clientid' ) );
		$this->_googleClass->setClientSecret( $_configSocial->get( 'google.clientsecret' ) );
		$this->_googleClass->setScopes( explode( ';', $_configSocial->get( 'google.scope' ) ) );
	}

	public function setRedirectUri( string $url ) {
		$this->_googleClass->setRedirectUri( $url );
	}

	public function getRedirectURL( string $url ) {
		$this->setRedirectUri( $url );
		$_return = $this->_googleClass->createAuthUrl();

		return $_return;
	}

	public function getToken() {
		if ( $this->_accessToken == '' ) {
			$this->_accessToken = $this->_googleClass->authenticate( request::get( 'code', '' ) );
		}

		return $this->_accessToken;
	}

	public function setToken( string $token ) {
		$this->_googleClass->setAccessToken( $token );
	}

	public function getAttributes() {
		if ( is_null( $this->_googleClass->getAccessToken() ) ) {
			$this->setToken( \finger\session::get( 'googlesession', '' ) );
		}
		$token_data = $this->_googleClass->verifyIdToken()->getAttributes();
		$_return    = $token_data['payload'];

		return $_return;
	}

	public function xxx() {
		var_dump( $this->_googleClass->verifyIdToken()->getAttributes() );
		exit;
		var_dump( $_SESSION );
		var_dump( $this->_googleClass->getAccessToken() );
		exit;
	}

}