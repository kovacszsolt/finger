<?php
namespace finger\view;

use finger\request;
use \finger\session as session;
use \finger\server;
use \finger\config as config;

/**
 * Template render Class
 * @package finger\view
 */
class render
{

    /**
     * Controller name
     * @var
     */
    public $controller;

    /**
     * Values
     * @var
     */
    private $values;

    /**
     * Session
     * @var session
     */
    protected $session;

    /**
     * Template file
     * @var null
     */
    private $file;

	/**
	 * Current settings
	 * @var config
	 */
	protected $settings;

	/**
	 * render constructor.
	 */
	public function __construct()
	{
		//read settings
		$_configClass = new config('settings');
		$this->settings = $_configClass;

		$this->session = new session();
		$this->file = NULL;
	}

    /**
     * Form URL
     * @return string
     */
    public function formURL()
    {
        $_return = '/' . $_GET['_module'] . '/' . $_GET['_controller'] . '/' . $_GET['_action'] . '/form/';
        return $_return;
    }

    /**
     * Set template file
     * @param $filename
     */
    public function setFile($filename)
    {
        $this->file = $filename;
    }

    /**
     * Add value to Template
     * @param $name
     * @param $value
     */
    public function addValue($name, $value)
    {
        $this->values[$name] = $value;
    }

    /**
     * Get Template stored value
     * @param $name
     * @param null $default
     * @return null
     */
    public function getValue($name, $default = NULL)
    {
        $return = $default;
        if (isset($this->values[$name])) {
            $return = $this->values[$name];
        }
        return $return;
    }

    /**
     * Show Template
     */
    public function render()
    {
        $_file = $_SERVER['DOCUMENT_ROOT'] . '../' . $this->file;
        if (is_file($_file)) {
            include_once $_file;
        } else {
            echo 'template not found = ' . $_file;
        }

    }

    /**
     * Stored value to JSON
     * @param $value
     */
    public function renderJSON($value)
    {
        echo json_encode($value);
    }

    /**
     * Check admin Javascript
     */
    public function adminJavascript()
    {
        $file = '/site/' . $_GET['_module'] . '/_method/js/' . $_GET['_method'] . '.js';
        $this->elementJavascript($file);
        $file = '/site/' . $_GET['_module'] . '/' . $_GET['_controller'] . '/' . $_GET['_action'] . '.' . $_GET['_method'] . '.js';
        $this->elementJavascript($file);
    }

    /**
     * Show Javascript
     * @param $file
     */
    public function elementJavascript($file)
    {
        $echo = '<!-- nojs::' . $file . ' -->' . PHP_EOL;
        if (is_file($_SERVER['DOCUMENT_ROOT'] . '/' . $file)) {
            $echo = '<script src="' . $file . '"></script>' . PHP_EOL;
        }
        echo $echo;
    }

    /**
     * Include file to Template
     * @param $file
     * @param null $var local variable
     */
    public function includeFile($file,$var=NULL)
    {
        $_file = MAINPATH . '/../site/' . $_GET['_module'] . '/' . $file;
        include $_file;
    }

    /**
     * Facebook Share button
     * @param $url
     */
    public function _facebookShare($url)
    {
        echo '
    <div class="fb-share-button" data-href="' . request::getProtocol() . '://' . request::getServerName() . $url . '" 
        data-layout="button_count" 
        data-size="small" 
        data-mobile-iframe="true">
        <a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='
            . urlencode(request::getProtocol() . '://' . request::getServerName() . $url) . '">Megosztás</a></div>
    ';
    }


}
