<?php

namespace finger\database;

use \finger\database\config as config;

/**
 * MYSQL connector Class mysql
 * @package finger\database
 */
class mysql extends \PDO
{

    /**
     * mysql constructor.
     */
    public function __construct()
    {
        try {
            $_config = new config('database');
            parent::__construct("mysql:host=" . $_config->hostname . ";dbname=" . $_config->databasename, $_config->username, $_config->password);
        } catch (\Exception $e) {
            echo 'Database error:' . PHP_EOL;
            echo 'error code:' . $e->getCode() . PHP_EOL;
            echo 'error message:' . $e->getMessage() . PHP_EOL;
            die();
        }
    }

    /**
     * Create table MYSQL syntax
     * @param $fields
     */
    protected function createtable($fields)
    {

        $sql = "CREATE TABLE IF NOT EXISTS " . $this->tableName . "( ";
        $sql .= " id INT(11) NOT NULL AUTO_INCREMENT ";
        $sql .= " ,inorder INT(11) ";
        foreach ($fields as $name => $attribs) {
            $type = $attribs['type'];
            $sql .= "," . $name . " " . $type . " ";
        }
        $sql .= " ,createdate TIMESTAMP ";
        $sql .= " ,createhost VARCHAR(20) ";
        $sql .= ", PRIMARY KEY (id) )";
        $query = $this->exec($sql);
    }
}
