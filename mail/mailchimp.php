<?php

namespace finger\mail;

use finger\config as config;
use mailchimp\member as mailchimp_member;

/**
 * Class mailchimp
 * @package finger\mail
 */
class mailchimp extends mailchimp_member {
	private $_settings;

	/**
	 * mailchimp constructor.
	 */
	public function __construct() {
		$_config         = new config( 'settings' );
		$this->_settings = $_config->get( 'mailchimp', array( 'apikey' => '', 'region' => '' ) );
		$this->init( $this->_settings['region'], $this->_settings['apikey'], $this->_settings['userid'] );
	}

	/**
	 * add email to list
	 * @param string $email
	 *
	 * @return mixed
	 */
	public function add( string $email ) {
		$this->setURL( '/lists/' . $this->_settings['listid'] . '/members/' );

		return $this->subscribe( $email );
	}

	/**
	 * remove email from list
	 * @param string $email
	 *
	 * @return bool
	 */
	public function remove( string $email ) {
		return parent::unsubscribe( $this->_settings['listid'], $email );
	}


}
