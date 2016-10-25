<?php
namespace finger\maker;
/**
 * Class admin
 * @package finger\maker
 */
class admin extends \finger\maker\main
{
    public function create($name)
    {
        $this->createFile('admin.controller.index.template', array('modulename' => $name), 'site/admin/' . $name . '/controller/index.php');
        $this->createFile('admin.controller.content.template', array('modulename' => $name), 'site/admin/' . $name . '/controller/content.php');
        $this->createFile('admin.settings.menu.template', array('modulename' => $name), 'site/admin/' . $name . '/settings/menu.php');
        $this->createFile('admin.view.index.index.template', array('modulename' => $name), 'site/admin/' . $name . '/view/index.index.php');
        $this->createFile('admin.view.content.list.template', array('modulename' => $name), 'site/admin/' . $name . '/view/content.list.php');
        $this->createFile('admin.view.content.form.template', array('modulename' => $name), 'site/admin/' . $name . '/view/content.form.php');
        echo 'ok' . PHP_EOL;
        exit;
    }
}

?>