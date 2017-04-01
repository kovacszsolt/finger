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
		'rootid' => array('type' => 'int(10)', 'update' => false),
		'name' => array('type' => 'varchar(200)', 'update' => true),
		'alt' => array('type' => 'varchar(200)', 'update' => true),
		'extension' => array('type' => 'varchar(200)', 'update' => false),
		'size' => array('type' => 'int(10)', 'update' => true),
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
		$_filename = $record->getTmpFileName();
		if (substr($record->getTmpFileName(), 0, 4) == 'http') {
			storage::mkDir('tmp');
			$_extension = (substr($record->getTmpFileName(), strrpos($record->getTmpFileName(), '.') + 1));
			$record->setExtension($_extension);
			$_id = parent::add($record);


			$_fileData = storage::saveFileFromUrl($_filename, $this->path . DIRECTORY_SEPARATOR . $_id . '.' . $record->getExtension(), true);
			$record->setSize($_fileData['size']);

		} else {
			$record->setSize(storage::sizeFile($_filename));
			$_id = parent::add($record);
			storage::saveFile($_filename, $this->path . DIRECTORY_SEPARATOR . $_id . '.' . $record->getExtension(), true);
		}
		//Get File size


	}

	/**
	 * Delete record from database and remove file from storage
	 * @param $id
	 * @return bool
	 */
	public function delete($id)
	{
		$_record = $this->find($id);
		storage::removeFile($this->path . DIRECTORY_SEPARATOR . $_record->getID() . '.' . $_record->getExtension());
		return parent::delete($id);
	}

	/**
	 * Delete from rootid
	 * @param $rootid
	 */
	public function deleteRoot($rootid)
	{
		foreach ($this->findRootid($rootid) as $record) {
			$this->delete($record->getId());
		}
	}

	/**
	 * Find by rootid
	 * @param $rootid
	 * @return array|null
	 */
	public function findRootid($rootid)
	{
		$this->addWhere('rootid', $rootid);
		$_records = $this->query();
		return $_records;
	}


}
