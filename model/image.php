<?php

namespace finger\model;

use \finger\storage as storage;

/**
 * Main Image Class
 * @package finger\model
 */
class image extends \finger\database\main {

	/**
	 * Database fields
	 * @var array
	 */
	public $fields = array(
		'rootid'    => array( 'type' => 'int(10)', 'update' => false ),
		'name'      => array( 'type' => 'varchar(200)', 'update' => true ),
		'alt'       => array( 'type' => 'varchar(200)', 'update' => true ),
		'extension' => array( 'type' => 'varchar(200)', 'update' => false ),
		'size'      => array( 'type' => 'int(10)', 'update' => true ),
	);

	/**
	 * image path
	 * @var
	 */
	protected $path;

	/**
	 * Save record to database and save file to storege dir
	 *
	 * @param $record
	 */
	public function add( $record ) {
		$_filename = $record->getTmpFileName();
		if ( substr( $record->getTmpFileName(), 0, 4 ) == 'http' ) {
			storage::mkDir( 'tmp' );
			$_extension = ( substr( $record->getTmpFileName(), strrpos( $record->getTmpFileName(), '.' ) + 1 ) );
			$record->setExtension( $_extension );
			$_id               = parent::add( $record );
			$_originalFileName = $_id . '.' . $record->getExtension();
			$_fileData         = storage::saveFileFromUrl( $_filename, $this->path . DIRECTORY_SEPARATOR . 'original' . DIRECTORY_SEPARATOR . $_originalFileName, true );
			$record->setSize( $_fileData['size'] );
			$this->saveImages( $this->path, $_originalFileName );
		} else {
			$record->setSize( storage::sizeFile( $_filename ) );
			$_id = parent::add( $record );
			storage::saveFile( $_filename, $this->path . DIRECTORY_SEPARATOR . $_id . '.' . $record->getExtension(), true );
		}
		//Get File size


	}

	private function saveImages( $_mainDir, $_originalFileName ) {
		$_imagesClass = new \finger\config( 'image' );
		$_images      = $_imagesClass->getAll();
		foreach ( $_images as $_imageName => $_imageData ) {
			\finger\storage::mkDir( $_mainDir . DIRECTORY_SEPARATOR . $_imageName );
			\finger\storage::imageResize( $_mainDir . DIRECTORY_SEPARATOR . 'original' . DIRECTORY_SEPARATOR . $_originalFileName, $_mainDir . DIRECTORY_SEPARATOR . $_imageName . DIRECTORY_SEPARATOR . $_originalFileName, $_imageData['width'], $_imageData['height'] );
		}
	}

	/**
	 * Delete record from database and remove file from storage
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function delete( $id ) {
		$_record = $this->find( $id );
		storage::removeFile( $this->path . DIRECTORY_SEPARATOR . $_record->getID() . '.' . $_record->getExtension() );

		return parent::delete( $id );
	}

	/**
	 * Delete from rootid
	 *
	 * @param $rootid
	 */
	public function deleteRoot( $rootid ) {
		foreach ( $this->findRootid( $rootid ) as $record ) {
			$this->delete( $record->getId() );
		}
	}

	/**
	 * Find by rootid
	 *
	 * @param $rootid
	 *
	 * @return array|null
	 */
	public function findRootid( $rootid ) {
		$_records = $this->query();

		return $_records;
	}


}
