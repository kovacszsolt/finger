<?php

namespace finger\form;

use \finger\session as session;
use \finger\random as random;
use \finger\request as request;
use \finger\form\field as formfield;

class check
{
    private $fields;
    private $errorFields = array();

    public function getError()
    {
        return $this->errorFields;
    }

    public function addField($name, $required, $type, $length = '')
    {
        $this->fields[$name] = new formfield($name, $required, $type, $length);
    }

    public static function generateKey()
    {
        $_session = new session();
        $_key = random::char(10);
        $_value = random::char(10);
        $_return = $_key . '.' . $_value;
        $_session->setValue($_key, $_value);
        return $_return;
    }

    public function init($filename)
    {
        $_return = false;
        $_filename = MAINPATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'form' . DIRECTORY_SEPARATOR . \finger\server::host() . DIRECTORY_SEPARATOR . $filename . '.ini';
        if (is_file($_filename)) {
            $_data = parse_ini_file($_filename, true);
            foreach ($_data as $key => $value) {
                $_value_array = explode('.', $value);
                $this->addField($key, $_value_array[0], $_value_array[1], (isset($_value_array[1])) ? 0 : $_value_array[1]);
            }
            $_return = true;
        }
        return $_return;
    }

    private function checkFields()
    {
        $_return = false;
        foreach ($this->fields as $field_name => $field) {
            if ($field->checkRequired()) {
                $_return = $field->check();
            } else {
                $this->errorFields[] = array(
                    'field' => $field,
                    'type' => 'required'
                );
            }
        }
        return $_return;
    }

    public function check()
    {
        $_return = false;
        $_request = request::getPOST();
        $_key_compare = array_merge(array_diff_key($_request, $this->fields), array_diff_key($this->fields, $_request));
        if (sizeof($_key_compare) == 0) {
            foreach ($this->fields as $field_name => $field) {
                $field->setValue($_request[$field_name]);
            }
            $_return = $this->checkFields();
        }
        return $_return;
    }
}