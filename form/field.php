<?php

namespace finger\form;

use \finger\session as session;
use \finger\random as random;

class field
{
	private $name;
	private $required;
	private $type;
	private $length;
	private $value;

	/**
	 * field constructor.
	 * @param string $name
	 * @param string $required
	 * @param string $type
	 * @param string $length
	 */
	public function __construct($name, $required, $type, $length = '')
	{
		$this->setName($name);
		$this->setRequired($required);
		$this->setType($type);
		$this->setLength($length);
	}

	public function setName($value)
	{
		$this->name = $value;
	}

	public function setRequired($value)
	{
		$this->required = $value;
	}

	public function setType($value)
	{
		$this->type = $value;
	}

	public function setLength($value)
	{
		$this->length = $value;
	}

	public function setValue($value)
	{
		$this->value = $value;
	}
}