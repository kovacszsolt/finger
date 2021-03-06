<?php

namespace finger;

/**
 * Filesystem Storage class
 * @package finger
 */
class storage {

	/**
	 * Convert path separator
	 *
	 * @param $path
	 *
	 * @return mixed string
	 */
	private function pathConvert( $path ) {
		return str_replace( '/', DIRECTORY_SEPARATOR, $path );
	}

	/**
	 * Get Main Storage Path
	 * @return string
	 */
	public static function getStoragePath() {
		$_return = \finger\server::documentRoot() . ( ( substr( \finger\server::documentRoot(), - 1 ) == '/' ) ? '' : DIRECTORY_SEPARATOR ) . '..' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . \finger\request::getModule() . DIRECTORY_SEPARATOR;

		return $_return;
	}

	/**
	 * Get Module Path
	 * @return string
	 */
	public static function getModulePath() {
		return $_SERVER['DOCUMENT_ROOT'] . ( ( substr( $_SERVER['DOCUMENT_ROOT'], - 1 ) == '/' ) ? '' : DIRECTORY_SEPARATOR ) . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR;
	}

	/*
	 * Get Site Path
	 */
	public static function getSitePath() {
		return $_SERVER['DOCUMENT_ROOT'] . ( ( substr( $_SERVER['DOCUMENT_ROOT'], - 1 ) == '/' ) ? '' : DIRECTORY_SEPARATOR ) . '..' . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR;
	}

	/**
	 * List Files from Storage Directory
	 *
	 * @param $dir
	 *
	 * @return array
	 */
	public static function listFiles( $dir ) {
		$_return = array();
		foreach ( scandir( self::getStoragePath() . $dir ) as $_tmp ) {
			if ( is_file( self::getStoragePath() . $dir . DIRECTORY_SEPARATOR . $_tmp ) ) {
				$_return[] = array(
					'filename'  => $_tmp,
					'localpath' => $dir . DIRECTORY_SEPARATOR . $_tmp,
					'fullpath'  => self::getStoragePath() . $dir . DIRECTORY_SEPARATOR . $_tmp
				);
			}
		}

		return $_return;
	}

	/**
	 * Create driectory
	 *
	 * @param $path
	 */
	public static function mkDir( $path, $isStorage = true ) {
		$_dir = $path;
		if ( $isStorage ) {
			$_dir = self::getStoragePath() . $path;
		}
		if ( ! is_dir( $_dir ) ) {
			mkdir( $_dir, 0700, true );
		}
	}

	/**
	 * Save Image from URL to localstorage
	 *
	 * @param string $source
	 * @param string $target
	 *
	 * @return array
	 */
	public static function saveFileFromUrl( $source, $target ) {
		$_extension = ( substr( $source, strrpos( $source, '.' ) + 1 ) );
		$_tmp_name  = self::getStoragePath() . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'formurl' . \finger\server::host() . date( 'U' ) . '.' . $_extension;


		$content = file_get_contents( $source );
//Store in the filesystem.
		$fp = fopen( $_tmp_name, "w" );
		fwrite( $fp, $content );
		fclose( $fp );
		$_tmp_size = filesize( $_tmp_name );
		$_return   = array(
			'extension' => $_extension,
			'size'      => $_tmp_size
		);

		self::saveFile( $_tmp_name, $target );

		self::remove( $_tmp_name );

		return $_return;
	}

	/**
	 * Delete file
	 *
	 * @param string $fileName
	 */
	public static function remove( $fileName ) {

		if ( is_file( $fileName ) ) {
			unlink( $fileName );
		}
	}

	/**
	 * Check file is exits
	 *
	 * @param $filename
	 *
	 * @return bool
	 */
	public static function isExitsFile( $filename, $relative = true ) {
		$_return   = false;
		$_fileName = $filename;
		if ( $relative ) {
			$_fileName = self::getStoragePath() . $filename;
		}
		if ( is_file( $_fileName ) ) {
			$_return = true;
		}

		return $_return;
	}


	/**
	 * Copy File to Storage directory
	 *
	 * @param $from
	 * @param $target
	 * @param bool $localStorage
	 */
	public static function saveFile( $from, $target, $localStorage = false ) {
		if ( $localStorage ) {
			$from = self::getStoragePath() . $from;
		}
		$_dir = substr( $target, 0, strrpos( $target, '/' ) + 1 );
		self::mkDir( $_dir );
		copy( $from, self::getStoragePath() . $target );
	}

	/**
	 * Get File size
	 *
	 * @param string $file
	 *
	 * @return int
	 */
	public static function sizeFile( $file ) {
		$from      = self::getStoragePath() . $file;
		$_filesize = filesize( $from );

		return $_filesize;
	}

	/**
	 * Read file to Stream
	 *
	 * @param $fileName
	 */
	public static function getFile( $fileName ) {
		$_filename  = self::getStoragePath();
		$__filename = $_filename . $fileName;
		if ( ! is_file( $__filename ) ) {
			$_config    = new \finger\config( 'settings' );
			$__filename = server::documentRoot() . $_config->get( 'social.defaultimage' );
		}
		$imginfo = getimagesize( $__filename );
		if ( ! is_array( $imginfo ) ) {
			$_config    = new \finger\config( 'settings' );
			$__filename = server::documentRoot() . $_config->get( 'social.defaultimage' );
			$imginfo    = getimagesize( $__filename );
		}
		header( 'Content-type: ' . $imginfo['mime'] );
		readfile( $__filename );
		die();
	}

	/**
	 * Delete file
	 *
	 * @param $file
	 */
	public static function removeFile( $file, $isStorage = true ) {
		$_file = self::getStoragePath() . $file;
		if ( ! $isStorage ) {
			$_file = $file;
		}
		if ( is_file( $_file ) ) {
			unlink( $_file );
		}
	}

	/**
	 * Create ZIP file from Storage directory
	 *
	 * @param $source_dir
	 * @param $target_filename
	 */
	public static function createZIPfromDir( $source_dir, $target_filename ) {
		$zip = new \ZipArchive();
		if ( is_file( self::getStoragePath() . $target_filename ) ) {
			unlink( self::getStoragePath() . $target_filename );
		}
		$zip->open( self::getStoragePath() . $target_filename, \ZipArchive::CREATE );
		foreach ( storage::listFiles( $source_dir ) as $_file ) {
			$zip->addFile( $_file['fullpath'], $_file['filename'] );
		}
		$zip->close();
	}

	/**
	 * Create file and put content
	 *
	 * @param $fileName
	 * @param $content
	 * @param bool $force
	 */
	public static function createFile( $fileName, $content, $force = false ) {
		$_filename = self::pathConvert( $fileName );
		$_dir      = substr( $_filename, 0, strrpos( $_filename, '/' ) );
		self::mkDir( $_dir, false );
		if ( ( is_file( $_filename ) ) && ( $force ) ) {
			unlink( $_filename );
		}
		if ( ! is_file( $_filename ) ) {
			file_put_contents( $_filename, $content );
		}
	}

	/**
	 * Resize and save image
	 *
	 * @param $source
	 * @param $target
	 * @param $newwidth
	 * @param $newheight
	 */
	public static function imageResize( $source, $target, $newwidth, $newheight ) {
		$_souceFile  = self::getStoragePath() . DIRECTORY_SEPARATOR . $source;
		$_targetFile = self::getStoragePath() . DIRECTORY_SEPARATOR . $target;
		list( $source_width, $source_height ) = getimagesize( $_souceFile );
		$target_image = imagecreatetruecolor( $newwidth, $newheight );
		switch (exif_imagetype($_souceFile)) {
			case 2:
				$source_image = imagecreatefromjpeg( $_souceFile );
				imagecopyresized( $target_image, $source_image, 0, 0, 0, 0, $newwidth, $newheight, $source_width, $source_height );
				imagejpeg( $target_image, $_targetFile, 80 );
				break;
			case 3:
				$source_image = imagecreatefrompng( $_souceFile );
				imagecopyresized( $target_image, $source_image, 0, 0, 0, 0, $newwidth, $newheight, $source_width, $source_height );
				imagepng( $target_image, $_targetFile, 6 );
				break;
		}
	}

	/**
	 * Save images
	 *
	 * @param $source
	 * @param $targetPath
	 * @param $targetName
	 */
	public static function saveImage( $source, $targetPath, $targetName ) {
		$_originalPath = $targetPath . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'original' . DIRECTORY_SEPARATOR . $targetName;
		$_minimalPath  = $targetPath . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'minimal' . DIRECTORY_SEPARATOR;
		self::saveFile( $source, $_originalPath );
		self::mkDir( $_minimalPath );
		self::imageResize( $_originalPath, $_minimalPath . $targetName, 100, 100 );
	}

}