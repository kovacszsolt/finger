<?php
namespace finger;

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
     * Check config file name
     * @return string
     */
    private function getFileName()
    {
        $_HTTP_HOST = '';
        if (isset($_SERVER['HTTP_HOST'])) {
            $_HTTP_HOST = $_SERVER['HTTP_HOST'];
        } elseif ((defined('HTTP_HOST'))) {
            $_HTTP_HOST = HTTP_HOST;
        }
        if ($_HTTP_HOST == '') {
            echo 'Server error!' . PHP_EOL;
            echo 'HTTP_HOST not found!' . PHP_EOL;
            die();
        }
        $_configFileName = MAINPATH . '/../app/config/config.' . $_HTTP_HOST . '.' . $this->type . '.ini';
        if (!is_file($_configFileName)) {
            echo 'no config file:' . $_configFileName;
            exit;
        }
        return $_configFileName;
    }

    /**
     * Read ini file to array
     */
    private function getData()
    {
        $this->data = parse_ini_file($this->fileName,true);
    }

    /**
     * config constructor.
     * @param $iniType
     */
    public function __construct($iniType)
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
    public function get($name, $default = '')
    {
        $_return = $default;
        if (isset($this->data[$name])) {
            $_return = $this->data[$name];
        }
        return $_return;
    }
    
    public function getAll() {
        return $this->data;
    }

}
