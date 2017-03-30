<?php

namespace finger\model;

use \model\web\url\table as urlTable;
use \model\web\url\record as urlRecord;

/**
 *
 * Main Language Database Class main
 * @package finger\database
 */
class language extends \finger\database\main
{

	public function __construct()
	{
		$this->fields['rootid'] = array('type' => 'int(10)');
		$this->fields['langcode'] = array('type' => 'varchar(200)');
		parent::__construct();
	}

	/**
	 * Find record
	 * @param integer $rootid
	 * @param string $langcode
	 * @return mixed|null
	 */
	public function findRecord($rootid, $langcode)
	{
		$this->addWhere('rootid', $rootid);
		$this->addWhere('langcode', $langcode);
		$_records = $this->query();
		return $this->getFirst();
	}

	/**
	 * Get inorder top
	 * @return integer
	 */
	public function maxInorder()
	{
		$_return = 1;
		$this->order = 'inorder DESC';
		$_records = $this->query();
		if (is_array($_records)) {
			$_record = $_records[0];
			$_return = $_record->getInorder();
		}
		return $_return;
	}

	/**
	 * Find all record via RootID
	 * @param integer $rootid
	 * @return array
	 */
	public function findRootID($rootid)
	{
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

	public function install()
	{

		parent::createtable($this->fields);
	}

	/**
	 * Add record
	 * @param $record
	 */
	public function add($record)
	{
		$_url = $record->getUrl();
		if (substr($_url, -1) != '/') {
			$_url .= '/';
		}
		$_urlTable = new urlTable();
		$_urlRecord = new urlRecord();
		$_urlRecord->setUrl($_url);
		$_urlid = $_urlTable->add($_urlRecord);
		$record->setUrlid($_urlid);
		$_id = parent::add($record);
		//Add record to URL table
		$_method = str_replace('language', '', $this->tableName) . '/content/index/' . $record->getLangcode() . '/' . $_id . '/';
		$_urlTable = new urlTable();
		//Update method
		$_urlRecord = $_urlTable->find($_urlid);
		$_urlRecord->setMethod($_method);
		$_urlTable->update($_urlRecord);

	}

	/**
	 * Update record
	 * @param $record
	 * @return bool
	 */
	public function update($record)
	{
		$_urlTable = new urlTable();
		$_urlRecord = $_urlTable->find($record->getUrlid());
		$_urlRecord->setUrl($record->getUrl());
		$_urlTable->update($_urlRecord);
		return parent::update($record); // TODO: Change the autogenerated stub
	}

}