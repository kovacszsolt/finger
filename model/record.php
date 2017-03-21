<?php

namespace finger\model;

/**
 * Main Database record Class
 * @package finger\model
 */
class record {

    /**
     * Primary key ID
     * @var
     */
    protected $a_id = 0;

    /**
     * Title
     * @var string
     */
    protected $a_title = '';

    /**
     * Order nubmer
     * @var
     */
    protected $a_inorder = 0;

    /**
     * Create date
     * @var
     */
    protected $a_createdate = '';

    /**
     * Content text
     * @var
     */
    protected $a_content = '';

    /**
     * Intro text
     * @var
     */
    protected $a_intro = '';

    /**
     * Key name
     * @var
     */
    protected $a_keyname = '';

    /**
     * Get Primary ID
     * @return int
     */
    public function getID() {
        return is_null($this->a_id) ? 0 : $this->a_id;
    }

    /**
     * Set Primary ID
     * @param $value
     */
    public function setID($value) {
        $this->a_id = $value;
    }

    /**
     * Get images
     * @return mixed
     */
    public function getImages() {
        $_className = str_replace('content', 'image', $this->className);
        $_className = str_replace('record', 'table', $_className);

        $_table = new $_className();
        $_table->addWhere('rootid', $this->getID());
        $_record = $_table->query();
        return $_record;
    }

    /**
     * Get create date
     * @return mixed
     */
    public function getCreateDate() {
        return $this->a_createdate;
    }

    /**
     * Magic get function
     * @param $fieldName
     * @return mixed
     */
    public function get($fieldName) {
        return $this->$fieldName;
    }

    private function getLanguageVar($varName, $languageCode) {
        $_return = '';
        $_className = str_replace('\content\\', '\language\\', $this->getClassName());
        $_className = str_replace('\record', '\table', $_className);
        $_class = new $_className;
        $_classLanguage = $_class->findRecord($this->getID(), $languageCode);
        if (!is_null($_classLanguage)) {
            $_return = $_classLanguage->$varName();
        }
        return $_return;
    }

    /**
     * @param $name
     * @param $arguments
     * @return null|string
     * @throws \Exception
     */
    public function __call($name, $arguments) {
        $_return = NULL;
        $_allowedMethods = array('get', 'set');
        $_method = substr($name, 0, 3);
        if (!in_array($_method, $_allowedMethods)) {
            throw new \Exception('method not found. ' . __CLASS__ . '->' . $name);
        }
        $_varName = 'a_' . strtolower(substr($name, 3));
        if (!property_exists($this, $_varName)) {
            throw new \Exception('variable not found. ' . __CLASS__ . '->' . $_varName);
        }
        switch ($_method) {
            case 'get':
                $_return = $this->$_varName;
                if (sizeof($arguments) == 1) {
                    $_return = $this->getLanguageVar($name, $arguments[0]);
                }
                break;
            case 'set':
                $this->$_varName = $arguments[0];
                $_return = $arguments[0];
                break;
        }
        return $_return;
    }

    /**
     * Get Class Name
     * @return mixed
     */
    public function getClassName() {
        return $this->className;
    }

    /**
     * Create Prety URL String
     * @param $str
     * @return mixed|string
     */
    protected function to_prety_url($str)
    {
        if ($str !== \mb_convert_encoding(mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
            $str = \mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
        $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
        $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $str);
        $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
        $str = preg_replace(array('`[^a-z0-9]`i', '`[-]+`'), '-', $str);
        $str = strtolower(trim($str, '-'));
        return $str;
    }
}
