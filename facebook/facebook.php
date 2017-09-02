<?php

namespace finger\facebook;
class facebook {

	private $_facebookClass;
	private $_accessToken;
	private $_helper;
	private $ApplicationID;
	private $SecretID;
	private $_session;

	public function __construct() {
		$this->_session = new \finger\session();
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


	private function getAccessToken() {
		$this->_accessToken = $this->_session->getValue( 'facebook_access_token', '' );
		try {
			if ( ( $this->_accessToken == '' ) || ( is_null( $this->_accessToken ) ) ) {
				$this->_accessToken = $this->_helper->getAccessToken();
				$this->_session->setValue( 'facebook_access_token', $this->_accessToken );
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

	public function getLoginURL( $backURL = '/loginok/' ) {
		$permissions = [ 'email' ]; // optional
		$loginUrl    = $this->_helper->getLoginUrl( \finger\request::_getFullHost() . $backURL, $permissions );
		$helper      = $this->_facebookClass->getRedirectLoginHelper();

		return $loginUrl;
	}


	public function sendNotification() {

		$app_access_token = $this->ApplicationID . '|' . $this->SecretID;
		$response         = $this->_facebookClass->post( '/10211041450140088/notifications', array(

			'template' => 'You have received a new message.',

			'href' => 'https://greenroom.dev'
		), $this->_accessToken );
		print_r( $response );
		exit;
	}

	public function postToWall( $url, $message ) {
		$linkData = [
			'link'    => $this->_host . $url,
			'message' => $message,
		];
		$ret      = $this->_facebookClass->post( '/me/feed', $linkData, $this->_accessToken );
	}

	public function logout() {
		$this->_session->remove( 'facebook_access_token' );
		$this->_accessToken = '';
	}

	public function getPermissions() {
		$response = $this->_facebookClass->get( '/me/permissions', $this->_accessToken );

		$graphObject = $response->getGraphEdge();
		print_r( $graphObject );
		exit;
	}

}