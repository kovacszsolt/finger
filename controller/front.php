<?php

namespace finger\controller;

use finger\form\check as form;
use finger\session as session;

/**
 * Class frontend main class
 * @package finger\controller
 */
class  front extends \finger\controller\main {
	/**
	 * current language
	 * @var string
	 */
	protected $currentLang;

	/**
	 * Form handler class
	 * @var form
	 */
	protected $_form;

	/**
	 * front constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->_form       = new form();
		$this->currentLang = session::get( 'currentLang', $this->settings['defaultlangcode'] );
	}
}
