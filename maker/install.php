<?php
namespace finger\maker;

use \finger\storage as storage;
use \model\system\module\table as module;
use \model\users\content\table as users;
use \model\language\content\table as language;
use \model\web\content\table as web;

/**
 * Class install
 * @package finger\maker
 */
class install extends \finger\maker\main
{

    /**
     * Create INI files and main site controller
     * @param $name Site name
     * @param $databasehostname Database Host name
     * @param $databasename Database Name
     * @param $databaseusername Database user name
     * @param $databaseuserpassword Database password
     */
    public function create($name, $databasehostname, $databasename, $databaseusername, $databaseuserpassword)
    {
        $vars = array(
            'hostname' => $databasehostname,
            'databasename' => $databasename,
            'username' => $databaseusername,
            'password' => $databaseuserpassword
        );
        $_defaultmodule=strtolower(str_replace('.','',$name));
        $this->createFile('config'.DIRECTORY_SEPARATOR.'database.ini.template', $vars, 'app'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.' . $name . '.database.ini');
        $this->createFile('config'.DIRECTORY_SEPARATOR.'settings.ini.template', array('defaultmodule'=>$_defaultmodule), 'app'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.' . $name . '.settings.ini');
        $this->createFile('site'.DIRECTORY_SEPARATOR.'controller.root.index.template',array('name'=>$_defaultmodule),'site'.DIRECTORY_SEPARATOR.$_defaultmodule.DIRECTORY_SEPARATOR.'root'.DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.'index.php');

    }
}

?>