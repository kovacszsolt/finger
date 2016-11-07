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
        $this->createFile('config.database.ini.template', $vars, 'app/config/config.' . $name . '.database.ini');
        $this->createFile('config.settings.ini.template', array('defaultmodule'=>$_defaultmodule), 'app/config/config.' . $name . '.settings.ini');
        $this->createFile('site.controller.root.index.template',array('name'=>$_defaultmodule),'site/'.$_defaultmodule.'/root/controller/index.php');

    }
}

?>