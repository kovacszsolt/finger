<?php

namespace finger;

use finger\server;
use finger\log;

/**
 * main configuration
 * Class config
 * @package finger
 */
class config
{

    /**
     * config file type
     * @var string
     */
    private $type;

    /**
     * config file variables
     * @var array
     */
    private $data;

    /**
     * config file path
     * @var string
     */
    private $fileName;

    /**
     * Get Config File Name
     * @param $host
     * @param string $type
     * @return string
     */
    private function getConfigFileName($host, $type = '')
    {
        if ($type == '') {
            $type = $this->type;
        }
        $_return = MAINPATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $host . DIRECTORY_SEPARATOR . $type . '.ini';
        return $_return;
    }

    /**
     * Check config file name
     * @return string
     */
    private function getFileName()
    {
        $_configFileName = $this->getConfigFileName(server::host());
        if (!is_file($_configFileName)) {
            /*check alias*/
            $_tmp = $this->getConfigFileName(server::host(), 'alias');
            if (is_file($_tmp)) {
                $__tmp = parse_ini_file($_tmp);
                /*check special settings*/
                if (isset($a[$this->type])) {
                    $_configFileName = $this->getConfigFileName($__tmp[$this->type]);
                } else {
                    /*read from global*/
                    $_configFileName = $this->getConfigFileName($__tmp['global']);
                }
            } else {
                log::save('no config file:' . $_configFileName);
                die();
            }
        }
        return $_configFileName;
    }

    /**
     * Read ini file to array
     */
    private
    function getData()
    {
        $this->data = parse_ini_file($this->fileName, true);
    }

    /**
     * config constructor.
     * @param $iniType
     */
    public
    function __construct($iniType)
    {
        $this->type = $iniType;
        $this->fileName = $this->getFileName();
        $this->getData();
    }

    /**
     * Get value from INI
     * @param $name
     * @param $default
     * @return mixed
     */
    public
    function get($name, $default = '')
    {
        $_return = $default;
        if (isset($this->data[$name])) {
            $_return = $this->data[$name];
        } else {
            if (strpos($name, '.') > 0) {
                $_arrayName = substr($name, 0, strpos($name, '.'));
                if (isset($this->data[$_arrayName][substr($name, strpos($name, '.') + 1)])) {
                    $_return = $this->data[$_arrayName][substr($name, strpos($name, '.') + 1)];
                }
            }
        }
        return $_return;
    }

    /**
     * All variables
     * @return array
     */
    public
    function getAll()
    {
        return $this->data;
    }

}