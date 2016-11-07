<?php

namespace finger\controller;

use \finger\session as session;
use \finger\config as config;

/**
 * Main kontrolller
 */
abstract class main {

    /**
     * Current Session
     * @var session
     */
    protected $session;

    /**
     * Current module name
     * @var
     */
    public $_module;

    /**
     * Current controller name
     * @var
     */
    public $_controller;

    /**
     * Current action name
     * @var
     */
    public $_action;

    /**
     * Current method
     * @var
     */
    public $_method;

    /**
     * Extra parameters
     * @var
     */
    public $_params;
    
    protected $settings;

    /**
     * Table JSON for ....
     * @var array
     */
    protected $tableJSON = array();

    /**
     * Current controller table
     * @var
     */
    protected $table;

    /**
     * Current view
     * @var \finger\view
     */
    protected $view;
    protected $languages;

    /**
     * main constructor.
     */
    public function __construct() {
        $tmp = new config('settings');
        $this->settings=$tmp->getAll();
        $this->languages = $tmp->get('languages');
        $this->session = new session();
        $this->view = new \finger\view\render();
        $this->view->addValue('languages', $this->languages);
    }

    /**
     * Render the view from class controller class name
     */
    protected function render() {
        $this->view->setFile('site/' . $this->_module . '/' . $this->_controller . '/view/' . $this->_action . '.' . $this->_method . '.php');
        $this->view->render();
    }

    /**
     * Get Flash message
     */
    public function messageGet() {
        $_message = $this->session->flash('message');
        $this->view->renderJSON(array('message' => $_message));
    }

    /**
     * Main root
     */
    public function indexGet() {
        $this->render();
    }

}
