<?php
namespace finger;

/**
 * Filesystem Storage class
 * @package finger
 */
class storage
{

    /**
     * Convert path separator
     * @param $path
     * @return mixed string
     */
    private function pathConvert($path)
    {
        return str_replace('/', DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Get Main Storage Path
     * @return string
     */
    public static function getStoragePath()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ((substr($_SERVER['DOCUMENT_ROOT'], -1) == '/') ? '' : DIRECTORY_SEPARATOR) . '..' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR;
    }

    public static function getModulePath()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ((substr($_SERVER['DOCUMENT_ROOT'], -1) == '/') ? '' : DIRECTORY_SEPARATOR) . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR.'model'.DIRECTORY_SEPARATOR;
    }

    public static function getSitePath()
    {
        return $_SERVER['DOCUMENT_ROOT'] . ((substr($_SERVER['DOCUMENT_ROOT'], -1) == '/') ? '' : DIRECTORY_SEPARATOR) . '..' . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR;
    }

    /**
     * List Files from Storage Directory
     * @param $dir
     * @return array
     */
    public static function listFiles($dir)
    {
        $_return = array();
        foreach (scandir(self::getStoragePath() . $dir) as $_tmp) {
            if (is_file(self::getStoragePath() . $dir . DIRECTORY_SEPARATOR . $_tmp)) {
                $_return[] = array(
                    'filename' => $_tmp,
                    'localpath' => $dir . DIRECTORY_SEPARATOR . $_tmp,
                    'fullpath' => self::getStoragePath() . $dir . DIRECTORY_SEPARATOR . $_tmp
                );
            }
        }
        return $_return;
    }

    /**
     * Create driectory
     * @param $path
     */
    public static function mkDir($path, $isStorage = true)
    {
        $_dir = $path;
        if ($isStorage) {
            $_dir = self::getStoragePath() . $path;
        }
        if (!is_dir($_dir)) {
            mkdir($_dir, 0700, true);
        }
    }

    /**
     * Check file is exits
     * @param $filename
     * @return bool
     */
    public static function isExitsFile($filename, $relative = true)
    {
        $_return = false;
        $_fileName = $filename;
        if ($relative) {
            $_fileName = self::getStoragePath() . $filename;
        }
        if (is_file($_fileName)) {
            $_return = true;
        }
        return $_return;
    }


    /**
     * Copy File to Storage directory
     * @param $from
     * @param $target
     * @param bool $localStorage
     */
    public static function saveFile($from, $target, $localStorage = false)
    {
        if ($localStorage) {
            $from = self::getStoragePath() . $from;
        }
        $_dir = substr($target, 0, strrpos($target, '/') + 1);
        self::mkDir($_dir);
        copy($from, self::getStoragePath() . $target);
    }

    /**
     * Read file to Stream
     * @param $fileName
     */
    public static function getFile($fileName)
    {

        $_filename = self::getStoragePath();
        $imginfo = getimagesize($_filename . $fileName);
        header('Content-type: ' . $imginfo['mime']);
        readfile($_filename . $fileName);
        die();
    }

    /**
     * Delete file
     * @param $file
     */
    public static function removeFile($file, $isStorage = true)
    {
        $_file = self::getStoragePath() . $file;
        if (!$isStorage) {
            $_file = $file;
        }
        if (is_file($_file)) {
            unlink($_file);
        }
    }

    /**
     * Create ZIP file from Storage directory
     * @param $source_dir
     * @param $target_filename
     */
    public static function createZIPfromDir($source_dir, $target_filename)
    {
        $zip = new \ZipArchive();
        if (is_file(self::getStoragePath() . $target_filename)) {
            unlink(self::getStoragePath() . $target_filename);
        }
        $zip->open(self::getStoragePath() . $target_filename, \ZipArchive::CREATE);
        foreach (storage::listFiles($source_dir) as $_file) {
            $zip->addFile($_file['fullpath'], $_file['filename']);
        }
        $zip->close();
    }

    /**
     * Create file and put content
     * @param $fileName
     * @param $content
     * @param bool $force
     */
    public static function createFile($fileName, $content, $force = false)
    {
        $_filename = self::pathConvert($fileName);
        $_dir = substr($_filename, 0, strrpos($_filename, '/'));
        self::mkDir($_dir, false);
        if ((is_file($_filename)) && ($force)) {
            unlink($_filename);
        }
        if (!is_file($_filename)) {
            file_put_contents($_filename, $content);
        }
    }
}