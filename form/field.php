<?php

namespace finger\form;

use \finger\session as session;
use \finger\request as request;
use \finger\random as random;
use \finger\config as config;

class field
{
	private $name;
	private $required;
	private $type;
	private $length;
	private $value;
	private $_session;
	private $_config;

	/**
	 * field constructor.
	 * @param string $name
	 * @param string $required
	 * @param string $type
	 * @param string $length
	 */
	public function __construct($name, $required, $type, $length = '')
	{
		$this->_session = new session();
		$this->setName($name);
		$this->setRequired($required);
		$this->setType($type);
		$this->setLength($length);
		$this->_config = new config('settings');
		$this->_config = $this->_config->get('secure');
	}

	/**
	 * Set Field name
	 * @param $value
	 */
	public function setName($value)
	{
		$this->name = $value;
	}

	/**
	 * Set required state
	 * @param $value
	 */
	public function setRequired($value)
	{
		$this->required = $value;
	}

	/**
	 * Set field type
	 * @param $value
	 */
	public function setType($value)
	{
		$this->type = $value;
	}

	/**
	 * Set field min lenght
	 * @param $value
	 */
	public function setLength($value)
	{
		$this->length = $value;
	}

	/**
	 * Set field Value
	 * @param $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * Check field
	 * @return bool
	 */
	public function check()
	{
		$_return = false;
		switch ($this->type) {
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
	private function checkGCaptcha()
	{
		$_return = false;

		$_config_secure = $this->_config['googlecaptcaptchasecret'];
		$secret = $this->_config['googlecaptcaptchasecret'];
		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . request::get('g-recaptcha-response', ''));
		$responseData = json_decode($verifyResponse);
		if ($responseData->success) {
			$_return = true;
		}
		return $_return;
	}


	/**
	 * Check valid email
	 * @return bool
	 */
	private function checkEmail()
	{
		$_return = ($this->value == filter_var($this->value, FILTER_VALIDATE_EMAIL)) ? true : false;
		return $_return;
	}

	private function checkString()
	{
		$_return = ($this->value != '') ? true : false;
		return $_return;
	}

	public function checkRequired()
	{
		$_return = false;
		if ($this->required == 'req') {
			$_return = true;
		}
		return $_return;
	}

	/**
	 * Check CSRF Token
	 * @return bool
	 */
	private function checkCSRF()
	{
		$_return = false;
		$_valueArray = explode('.', $this->value);
		if (sizeof($_valueArray) == 2) {
			$_keyPre = $this->_session->getValue($_valueArray[0], '');
			if ($_keyPre != '') {
				//$this->_session->remove($_valueArray[0]);
				if ($_keyPre == $_valueArray[1]) {
					$_return = true;
				}
			}
		}
		return $_return;
	}
}