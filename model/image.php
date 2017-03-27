<?php

namespace finger\model;

use \finger\storage as storage;

/**
 * Main Image Class
 * @package finger\model
 */
class image extends \finger\database\main
{

    /**
     * Database fields
     * @var array
     */
    public $fields = array(
        'rootid' => array('type' => 'int(10)','update'=>false),
        'name' => array('type' => 'varchar(200)','update'=>true),
        'alt' => array('type' => 'varchar(200)','update'=>true),
        'extension' => array('type' => 'varchar(200)','update'=>false),
	    'size' => array('type' => 'int(10)','update'=>true),
    );

    /**
     * image path
     * @var
     */
    protected $path;

    /**
     * Save record to database and save file to storege dir
     * @param $record
     */
    public function add($record)
    {

        $_id = parent::add($record);
        storage::saveFile($record->getTmpFileName(), $this->path . DIRECTORY_SEPARATOR . $_id . '.' . $record->getExtension(), true);

    }

    /**
     * Delete record from database and remove file from storage
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $_record = $this->find($id);
        //print_r($_record);exit;
        storage::removeFile($this->path . DIRECTORY_SEPARATOR . $_record->getID() . '.' . $_record->getExtension());

        return parent::delete($id);
    }


}
