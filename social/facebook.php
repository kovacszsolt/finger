<?php

namespace finger\social;
use \finger\session as session;

/**
 * Facebook Class
 * @package finger\social
 */
class facebook {

	private $_facebookClass;
	private $_accessToken;
	private $_helper;
	private $ApplicationID;
	private $SecretID;

	/**
	 * facebook constructor.
	 */
	public function __construct() {
		$_configSocial  = new \finger\config( 'social' );

		$this->_facebookClass = new \Facebook\Facebook( [
			'app_id'                => $_configSocial->get( 'facebook.appid' ),
			'app_secret'            => $_configSocial->get( 'facebook.secret' ),
			'default_graph_version' => 'v2.8',
		] );
		$this->_helper        = $this->_facebookClass->getRedirectLoginHelper();
		$this->getAccessToken();
		if ( $this->_accessToken != '' ) {
			$this->_facebookClass->setDefaultAccessToken( $this->_accessToken );
		}
	}

	/**
	 * Get Facebook Access Token
	 */
	private function getAccessToken() {
		$this->_accessToken = session::get( 'facebook_access_token', '' );
		try {
			if ( ( $this->_accessToken == '' ) || ( is_null( $this->_accessToken ) ) ) {
				$this->_accessToken = $this->_helper->getAccessToken();
				session::set( 'facebook_access_token', $this->_accessToken );
			}
		} catch ( \Facebook\Exceptions\FacebookResponseException $e ) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch ( \Facebook\Exceptions\FacebookSDKException $e ) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
	}

	/**
	 * Get Current User Data
	 * @return array|null
	 */
	public function getMe() {
		try {
			$_return = null;
			if ( ! is_null( $this->_accessToken ) ) {
				$profile_request = $this->_facebookClass->get( '/me?fields=name,first_name,last_name,email' );
				$profile         = $profile_request->getGraphNode()->asArray();
				$_return         = $profile;
			}
			return $_return;
		} catch ( \Facebook\Exceptions\FacebookResponseException $e ) {
			return $_return;
		}
	}

	/**
	 * Get Facebook login URL
	 * @param string $backURL
	 *
	 * @return string
	 */
	public function getLoginURL( $backURL = '/loginok/' ) {
		$permissions = [ 'email' ]; // optional
		$loginUrl    = $this->_helper->getLoginUrl( \finger\request::_getFullHost() . $backURL, $permissions );
		$helper      = $this->_facebookClass->getRedirectLoginHelper();

		return $loginUrl;
	}

	/**
	 * Logout from facebook
	 */
	public function logout() {
		session::remove( 'facebook_access_token' );
		$this->_accessToken = '';
	}

}