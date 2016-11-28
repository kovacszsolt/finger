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
            case 'content' :
                $this->createContent($name);
                break;
            case 'language' :
                $this->createLanguage($name);
                break;
            case 'image':
                $this->createImage($name);
                break;
            case 'full':
                $this->createFile('model/content.table.template', array('modelname' => $name), 'app/model/' . $name . '/content/table.php');
                $this->createFile('model/content.record.template', array('modelname' => $name), 'app/model/' . $name . '/content/record.php');

                $this->createFile('model/language.table.template', array('modelname' => $name), 'app/model/' . $name . '/language/table.php');
                $this->createFile('model/language.record.template', array('modelname' => $name), 'app/model/' . $name . '/language/record.php');

                $this->createFile('model/image.table.template', array('modelname' => $name), 'app/model/' . $name . '/image/table.php');
                $this->createFile('model/image.record.template', array('modelname' => $name), 'app/model/' . $name . '/image/record.php');
                break;
            case 'contentrel':
                $this->createFile('model/contentrel.table.template', array('modelname' => $name), 'app/model/' . $name . '/content/table.php');
                $this->createFile('model/contentrel.record.template', array('modelname' => $name), 'app/model/' . $name . '/content/record.php');
                break;
            default:
                $this->createFile('model/' . $type . '.table.template', array('modelname' => $name), 'app/model/' . $name . '/' . $type . '/table.php');
                $this->createFile('model/' . $type . '.record.template', array('modelname' => $name), 'app/model/' . $name . '/' . $type . '/record.php');
        }
        echo 'ok' . PHP_EOL;
        exit;
    }

    private function createImage($name, $pre)
    {
        $this->createFile('table', 'image', $name, $pre);
        $this->createFile('record', 'image', $name, $pre);
    }

    /**
     * @param string $modelType Table / Record
     * @param $tableType
     * @param $name
     * @param string $pre
     */
    protected function createFile($modelType, $tableType, $name, $pre = '')
    {

        //$this->createFile('model' . DIRECTORY_SEPARATOR . $pre . 'content.table.template', array('modelname' => $name), 'app' . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'table.php');
        $pre = ($pre == '') ? $pre : '.' . $pre;
        parent::createFile('model' . DIRECTORY_SEPARATOR . $pre . $tableType . '.' . $modelType . '.template', array('modelname' => $name), 'app' . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $tableType . DIRECTORY_SEPARATOR . $modelType . '.php');
    }

    private function createContent($name, $pre = '')
    {
        $this->createFile('table', 'content', $name, $pre);
        $this->createFile('record', 'content', $name, $pre);
    }

    private function createLanguage($name, $pre = '')
    {
        $this->createFile('table', 'language', $name, $pre);
        $this->createFile('record', 'language', $name, $pre);
    }

}

?>