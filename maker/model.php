<?php
namespace finger\maker;

use \finger\storage as storage;

/**
 * Create Model
 * Class model
 * @package finger\maker
 */
class model extends \finger\maker\main
{

    /**
     * model constructor.
     * @param $path
     */
    public function __construct($path)
    {
        parent::__construct($path, 'model');
    }

    /**
     * Create model
     * @param $type
     * @param $name
     */
    public function create($type, $name)
    {
        switch ($type) {
            case 'contentrel':
                $this->createFile('model.contentrel.table.template', array('modelname' => $name), 'app/model/' . $name . '/content/table.php');
                $this->createFile('model.contentrel.record.template', array('modelname' => $name), 'app/model/' . $name . '/content/record.php');
                break;
            default:
                $this->createFile('model.' . $type . '.table.template', array('modelname' => $name), 'app/model/' . $name . '/' . $type . '/table.php');
                $this->createFile('model.' . $type . '.record.template', array('modelname' => $name), 'app/model/' . $name . '/' . $type . '/record.php');
        }
        echo 'ok' . PHP_EOL;
        exit;
    }

}

?>