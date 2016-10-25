<?php

namespace finger\model;

/**
 *
 * Main Language Database Class main
 * @package finger\database
 */
class language extends \finger\database\main {

    public function __construct() {
        $this->fields['rootid'] = array('type' => 'int(10)');
        $this->fields['langcode'] = array('type' => 'varchar(200)');
        parent::__construct();
    }

    public function findRecord($rootid, $langcode) {
        $this->addWhere('rootid', $rootid);
        $this->addWhere('langcode', $langcode);
        $_records = $this->query();
        return $this->getFirst();
    }

    public function findRootID($rootid) {
        $this->addWhere('rootid', $rootid);
        $_records = $this->query();
        $_return = array();
        if (is_array($_records)) {
            foreach ($_records as $_record) {
                $_return[$_record->getLangcode()] = $_record;
            }
        }
        return $_return;
    }

    public function install() {

        parent::createtable($this->fields);
    }

}
