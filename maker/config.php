<?php
namespace finger\maker;

use \finger\storage as storage;
use \model\system\module\table as module;
use \model\users\content\table as users;
use \model\language\content\table as language;
use \model\web\content\table as web;

class config extends \finger\maker\main
{

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
        $this->createFile('config.database.ini.template', array('defaultmodule'=>$_defaultmodule), 'app/config/config.' . $name . '.settings.ini');
        $_tables = new \model\system\tables();
        if (!$_tables->findTable('systemmodule')) {
            $_object = new module();
            $_object->install();
            $_object = new users();
            $_object->install();
            $_object = new language();
            $_object->install();
            $_object = new web();
            $_object->install();
        }


    }
}

?>