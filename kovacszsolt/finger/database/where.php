<?php

namespace finger\database;

/**
 * Class where
 * @package finger\database
 */
class where {

    /**
     * field name
     * @var
     */
    private $fieldName;

    /**
     * field parameter
     * @var
     */
    private $fieldParam;

    /**
     * Set Where field name
     * @param $name
     */
    public function setName($name) {
        $this->fieldName=$name;
    }

    /**
     * Get current Where field name
     * @return mixed
     */
    public function getName() {
        return $this->fieldName;
    }

    /**
     * Set Where parametere value
     * @param $param
     */
    public function setParam($param) {
        $this->fieldParam=$param;
    }

    /**
     * Get Where parameter value
     * @return mixed
     */
    public function getParam() {
        return $this->fieldParam;
    }
}
