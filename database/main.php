<?php

namespace finger\database;

use \finger\storage as Storage;

/**
 *
 * Main Database Class main
 * @package finger\database
 */
class main extends \finger\database\mysql
{

    /**
     * Export Path
     * @var string
     */
    private $_exportPath;

    /**
     * database table name
     * @var string
     */
    public $tableName;

    /**
     * Where parameters for querys
     * @var array
     */
    public $where = array();

    public $order = 'a.inorder';

    /**
     * Fetched records
     * @var
     */
    public $records;

    /**
     * Local SQL command
     * @var
     */
    private $_sql;

    /**
     * Values for Update
     * @var array
     */
    private $_values = array();

    /**
     * Join table and fields
     * @var array
     */
    private $_join = array();

    /**
     * Export tables
     * @var array
     */
    protected $_export_tables = array();

    /**
     * Export directorys
     * @var array
     */
    protected $_export_dirs = array();

    /**
     * Sepial association parameters
     */
    const ASSOC_NONE = 0;
    const ASSOC_ID = 1;
    const ASSOC_TITLE = 2;

    /**
     * main constructor.
     */
    public function __construct()
    {
        $this->_exportPath = Storage::getStoragePath() . '/_export/';
        parent::__construct();
    }

    /**
     * Add Join to model
     * @param $table
     * @param $table_id
     * @param $join_id
     */
    protected function addJoin($table, $table_id, $join_id)
    {
        $this->_join[] = array('table' => $table, 'table_id' => $table_id, 'join_id' => $join_id);
    }

    /**
     * Skeleton join
     */
    protected function joins()
    {

    }

    /**
     * Get Record Class name
     * @return string
     */
    private function _getClassName()
    {
        $_return = substr($this->className, 0, strrpos($this->className, '\\')) . '\record';
        return $_return;
    }

    /**
     * Values for debug
     * @return array
     */
    public function debugValues()
    {
        return $this->_values;
    }

    /**
     * SQL command for debug
     * @return mixed
     */
    public function debugSQL()
    {
        return $this->_sql;
    }

    /**
     * Model SQL create
     * @param bool $createTable true=create SQL table
     */
    public function install()
    {

        parent::createtable($this->fields);

    }

    /**
     * Find record where primary key
     * @param $id
     * @return mixed
     */
    public function find($id)
    {

        $_getClassName = $this->_getClassName();
        $_return = new $_getClassName();
        $this->addWhere('id', $id);
        $_record = $this->query();
        if (!is_null($_record)) {
            $_return = $_record[0];
        }
        return $_return;
    }

    /**
     * Find record where Keyname field
     * @param $keyname
     * @return mixed
     */
    public function findKey($keyname)
    {
        $_getClassName = $this->_getClassName();
        $_return = new $_getClassName();
        $this->addWhere('keyname', $keyname);
        $_record = $this->query();
        if (!is_null($_record)) {
            $_return = $_record[0];
        }
        return $_return;
    }

    /**
     * Create update SQL
     * @param $record
     * @return bool
     */
    public function update($record)
    {
        try {
            $_sql = 'UPDATE ' . $this->tableName . ' ';
            $_fieldPos = 0;
            foreach ($this->fields as $_fieldName => $_fieldAttribs) {
                $_fieldPos++;
                if ($_fieldPos == 1) {
                    $_sql .= ' SET ' . $_fieldName . '=:' . $_fieldName . ' ';
                } else {
                    $_sql .= ' ,' . $_fieldName . '=:' . $_fieldName . ' ';
                }
            }
            $_sql .= ' WHERE id=:id ';
            $this->_sql = $_sql;
            $_prepare = $this->prepare($_sql);

            foreach ($this->fields as $_fieldName => $_fieldAttribs) {
                $_functionName = 'get' . ucfirst($_fieldName);
                $this->_values[$_fieldName] = $record->$_functionName();
                $_prepare->bindValue(':' . $_fieldName, $record->$_functionName());
            }
            $this->_values['id'] = $record->getID();
            $_prepare->bindValue(':id', $record->getID());
            $this->beginTransaction();
            $_prepare->execute();
            if ($_prepare->errorCode() != '00000') {
                echo $_sql;
                print_r($this->fields);
                print_r($_prepare->errorInfo());
                exit;
            }
            $_return = true;
            $this->commit();
            return $_return;
        } catch (PDOExecption $e) {
            $this->rollback();
            print_r($e);
            exit;

            return false;
        }
    }

    /**
     * Create new record
     * @param $record
     * @return int|string
     */
    public function add($record)
    {
        try {
            $_recordCount = $this->count();
            if ($_recordCount != 0) {
                $newOrder = 0;
                $_sql = 'UPDATE ' . $this->tableName . ' ';
                $_sql .= 'SET inorder=inorder+1 ';
                $_sql .= 'WHERE inorder>=' . $record->getInorder() . ' ';
                $this->runSQL($_sql);
            }
            $_fieldValues = array();
            $_sql = 'INSERT INTO ' . $this->tableName . '(';
            $_fieldPos = 0;
            foreach ($this->fields as $_fieldName => $_fieldAttribs) {
                $_fieldPos++;
                if ($_fieldPos == 1) {
                    $_sql .= ' ' . $_fieldName . ' ';
                } else {
                    $_sql .= ' ,' . $_fieldName . ' ';
                }
            }
            $_sql .= ' ,inorder ';
            $_sql .= ' ,createhost ';
            $_sql .= ') VALUES(';
            $_fieldPos = 0;
            foreach ($this->fields as $_fieldName => $_fieldAttribs) {
                $_fieldPos++;
                if ($_fieldPos == 1) {
                    $_sql .= ' :' . $_fieldName . ' ';
                } else {
                    $_sql .= ' ,:' . $_fieldName . ' ';
                }
            }
            $_sql .= ' ,:inorder ';
            $_sql .= ' ,\'' . $_SERVER['REMOTE_ADDR'] . '\' ';
            $_sql .= ') ';
            $_prepare = $this->prepare($_sql);
            foreach ($this->fields as $_fieldName => $_fieldAttribs) {
                $_functionName = 'get' . ucfirst($_fieldName);
                $_fieldValues[':' . $_fieldName] = $record->$_functionName();
                $_prepare->bindValue(':' . $_fieldName, $record->$_functionName());
            }
            if ($_recordCount == 0) {
                $_prepare->bindValue(':inorder', 1);
            } else {
                $_prepare->bindValue(':inorder', $record->getInorder());
            }


            $this->beginTransaction();
            $_prepare->execute();
            if ($_prepare->errorCode() != '00000') {
                echo $_sql;
                print_r($_fieldValues);
                print_r($_prepare->errorInfo());
                exit;
            }
            $_return = $this->lastInsertId();
            $this->commit();


            return $_return;
        } catch (PDOExecption $e) {
            $this->rollback();
            print_r($e);
            exit;
            return -1;
        }
    }

    /**
     * Run full SQL command
     * @param $sql
     * @return array
     */
    public function runSQL($sql)
    {
        $_prepare = $this->prepare($sql);
        $_prepare->execute();
        $_records = $_prepare->fetchAll(\PDO::FETCH_ASSOC);
        return $_records;
    }

    /**
     * Reorder the records
     * @param $id
     * @param $newOrder
     */
    public function reOrder($id, $newOrder)
    {
        $_record = $this->find($id);
        $oldOrder = $_record->getInorder();
        if ($newOrder > $oldOrder) {
            $_sql = 'UPDATE ' . $this->tableName . ' ';
            $_sql .= 'SET inorder=inorder-1 ';
            $_sql .= 'WHERE inorder<=' . $newOrder . ' ';
            $_sql .= 'AND inorder>=' . $oldOrder . ' ';
            $this->runSQL($_sql);
            $_sql = 'UPDATE ' . $this->tableName . ' ';
            $_sql .= 'SET inorder=' . $newOrder . ' ';
            $_sql .= 'WHERE id=' . $id . ' ';
            $this->runSQL($_sql);
        } else {
            $_sql = 'UPDATE ' . $this->tableName . ' ';
            $_sql .= 'SET inorder=inorder+1 ';
            $_sql .= 'WHERE inorder>=' . $newOrder . ' ';
            $_sql .= 'AND inorder<=' . $oldOrder . ' ';

            $this->runSQL($_sql);
            $_sql = 'UPDATE ' . $this->tableName . ' ';
            $_sql .= 'SET inorder=' . $newOrder . ' ';
            $_sql .= 'WHERE id=' . $id . ' ';
            $this->runSQL($_sql);
        }
    }

    /**
     * Remove record from Database
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        try {
            $_record = $this->find($id);
            $oldOrder = $_record->getInorder();

            $_sql = 'DELETE FROM ' . $this->tableName . ' WHERE id = :id ';
            $_prepare = $this->prepare($_sql);
            $_prepare->bindValue(':id', $id);
            $this->beginTransaction();
            $_prepare->execute();
            $_return = true;
            $this->commit();
            $this->beginTransaction();
            $_sql = 'UPDATE ' . $this->tableName . ' ';
            $_sql .= 'SET inorder=inorder-1 ';
            $_sql .= 'WHERE inorder>' . $oldOrder . ' ';
            $this->commit();
            $this->runSQL($_sql);

        } catch (PDOExecption $e) {
            $this->rollback();
            print_r($e);
            exit;
            return false;
        }
    }

    /**
     * Add Where parameter
     * @param $field
     * @param $parameter
     */
    public function addWhere($field, $parameter, $method = '=')
    {
        $_where = new \finger\database\where();
        $_where->setName($field);
        $_where->setParam($parameter);
        $_where->setMethod($method);
        $this->where[] = $_where;
    }

    /**
     * Query record from Database
     * @param int $id_assoc
     * @return array|null
     */
    public function query($id_assoc = 0)
    {
        $_whereData = array();
        $this->joins();
        try {
            $_records = NULL;
            $_sql = 'SELECT  a.id AS a_id  ';
            $_sql .= ' ,a.inorder AS a_inorder  ';
            $_sql .= ' ,a.createdate AS a_createdate  ';
            foreach ($this->fields as $_fieldName => $_fieldAttrib) {
                $_sql .= ' ,a.' . $_fieldName . ' AS a_' . $_fieldName . ' ';
            }
            foreach ($this->_join as $_join_id => $_join) {
                $_joinClassName = '\model\\' . $_join['table'] . '\content\table';
                $_joinClass = new $_joinClassName();
                $_sql .= ' ,' . chr($_join_id + 98) . '.id AS ' . chr($_join_id + 98) . '_id ';
                $_sql .= ' ,' . chr($_join_id + 98) . '.inorder AS ' . chr($_join_id + 98) . '_inorder ';
                foreach ($_joinClass->fields as $_joinFieldName => $_joinField) {
                    $_sql .= ' ,' . chr($_join_id + 98) . '.' . $_joinFieldName . ' AS ' . chr($_join_id + 98) . '_' . $_joinFieldName . ' ';
                }
            }

            $_sql .= 'FROM ' . $this->tableName . ' a ';
            if (sizeof($this->_join) > 0) {
                foreach ($this->_join as $_join_id => $_join) {
                    $_sql .= 'INNER jOIN ' . $_join['table'] . ' ' . chr($_join_id + 98) . ' ';
                    $_sql .= ' ON (a.' . $_join['join_id'] . '=' . chr($_join_id + 98) . '.' . $_join['table_id'] . ') ';
                }
            }
            $_sql .= ' WHERE 1=1 ';
            foreach ($this->where as $_where) {
                $_sql .= ' AND a.' . $_where->getName() . $_where->getMethod() . ':' . $_where->getName() . ' ';
            }
            if ($this->order != '') {
                $_sql .= ' ORDER BY ' . $this->order . ' ';
            }
            $this->_sql = $_sql;
            $_prepare = $this->prepare($_sql);
            foreach ($this->where as $_where) {
                $this->_values[$_where->getName()] = $_where->getParam();
                $_prepare->bindValue(':' . $_where->getName(), $_where->getParam());
            }
            error_log(json_encode($_sql));
            $_prepare->execute();
            if ($_prepare->rowCount() > 0) {
                $_classRecord = $this->_getClassName();
                $_records = $_prepare->fetchAll(\PDO::FETCH_CLASS, $_classRecord);
            }
            $this->records = $_records;
            if (is_array($this->records)) {
                switch ($id_assoc) {
                    case $this::ASSOC_ID :
                        $_records = array();
                        foreach ($this->records as $_record) {
                            $_records[$_record->getId()] = $_record;
                        }
                        break;
                    case $this::ASSOC_TITLE: {
                        $_records = array();
                        foreach ($this->records as $_record) {
                            $_records[$_record->getTitle()] = $_record;
                        }
                        break;
                    }
                }
            }
            return $_records;
        } catch (\Exception $e) {
            error_log($_sql);
            print_r($e);
            exit;
        }
    }

    /**
     * Get the First reocrd from query
     * @return mixed|null
     */
    public function getFirst()
    {
        $_return = NULL;
        if ((is_array($this->records)) && (sizeof($this->records) > 0)) {
            $_return = $this->records[0];
        }
        return $_return;
    }

    /**
     * Get Record count
     * @return int
     */
    public function count()
    {
        $_return = 0;
        try {
            $_records = NULL;
            $_sql = 'SELECT  COUNT(id) AS c ';
            $_sql .= 'FROM ' . $this->tableName . ' ';
            $_sql .= ' WHERE 1=1 ';
            foreach ($this->where as $_where) {
                $_sql .= ' AND ' . $_where->getName() . '=:' . $_where->getName() . ' ';
            }
            $_prepare = $this->prepare($_sql);
            foreach ($this->where as $_where) {
                $_prepare->bindValue(':' . $_where->getName(), $_where->getParam());
            }
            $_prepare->execute();
            if ($_prepare->rowCount() > 0) {
                $_records = $_prepare->fetch(\PDO::FETCH_ASSOC);
                $_return = $_records['c'];
            }
            return $_return;
        } catch (\Exception $e) {
            print_r($e);
            exit;
        }
    }

    /**
     * Convert Record object to Array
     * @param null $records
     * @return array
     */
    public function RecordsToArray($records = NULL)
    {
        if (is_null($records)) {
            $records = $this->records;
        }
        $return = array();
        foreach ($records as $record) {
            $_tmp = array();
            $_tmp['id'] = $record->getID();
            foreach ($this->fields as $_fieldName => $_fieldAttribs) {
                $_functionName = 'get' . ucfirst($_fieldName);
                $_tmp[$_fieldName] = $record->$_functionName();
            }
            $return[] = $_tmp;
        }
        return $return;
    }

    /**
     * List table fields
     * @param $tablename
     * @return array
     */
    private function getTableFieldsName($tablename)
    {
        $_fields = $this->getTableFields($tablename);
        $_return = array_keys($_fields);
        return $_return;
    }

    /**
     * List table fields name
     * @param $tablename
     * @return array
     */
    private function getTableFields($tablename)
    {
        $_return = array();
        $_prepare = $this->prepare('DESCRIBE ' . $tablename . ' ');
        $_prepare->execute();
        foreach ($_prepare->fetchAll(\PDO::FETCH_ASSOC) as $_record) {
            $_return[$_record['Field']] = array(
                'name' => $_record['Field'],
                'type' => $_record['Type']
            );
        }
        return ($_return);
    }

    /**
     * Export current Table to SQL script
     * @param $tableName
     */
    public function exportTable($tableName)
    {
        $_filename = $this->_exportPath . $tableName . '.sql';
        Storage::removeFile($_filename);
        file_put_contents($_filename, $this->createTable($tableName), FILE_APPEND | LOCK_EX);
        $_fieldsName = $this->getTableFieldsName($tableName);
        $_sql_header = 'INSERT INTO ' . $tableName . ' (' . implode(',', $_fieldsName) . ') VALUES (\'';
        foreach ($this->getTableRecords($tableName) as $_record) {
            $_sql = $_sql_header . implode('\',\'', $_record) . '\');' . PHP_EOL;
            file_put_contents($_filename, $_sql, FILE_APPEND | LOCK_EX);
        }
    }

    /**
     * List Table record for Export
     * @param $tablename
     * @return array
     */
    private function getTableRecords($tablename)
    {
        $_prepare = $this->prepare('SELECT * FROM ' . $tablename . ' ');
        $_prepare->execute();
        $_return = $_prepare->fetchAll(\PDO::FETCH_ASSOC);
        return ($_return);
    }

    /**
     * Export current tables
     */
    public function exportData()
    {
        foreach ($this->_export_tables as $_export_table) {
            $this->exportTable($_export_table);
        }
        foreach ($this->_export_dirs as $_export_dir) {
            $this->exportDir($_export_dir);
        }

    }

    /**
     * Export the table image dir
     * @param $dir
     */
    private function exportDir($dir)
    {
        storage::createZIPfromDir($dir, '_export' . DIRECTORY_SEPARATOR . $dir . '.zip');
    }

    public function getClassName()
    {
        return $this->className;
    }

}
