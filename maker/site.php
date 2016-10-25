<?php
namespace finger\maker;

use \finger\storage as storage;

/**
 * Create Site
 * Class site
 * @package finger\maker
 */
class site extends \finger\maker\main
{

    /**
     * @param $name
     */
    public function create($name)
    {
        $this->createFile('site.controller.root.index.template',array('name'=>$name),'site/'.$name.'/root/controller/index.php');
        echo 'ok'.PHP_EOL;exit;
    }

}

?>