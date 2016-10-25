<?php
namespace finger\maker;

use \finger\storage as storage;
use \model\users\content\table as users;
use \model\language\content\table as language;
use \model\web\content\table as web;

class database extends \finger\maker\main
{

    public function create($database)
    {
        define('HTTP_HOST',$database);
        $_object = new users();
        $_object->install();
        $_object = new language();
        $_object->install();
        $_object = new web();
        $_object->install();
        $_object = new language();
        $_object->install();

    }
}

?>