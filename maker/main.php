<?php
namespace finger\maker;

use \finger\storage as storage;

/**
 * Class main
 * @package finger\maker
 */
class main
{
    /**
     * Template Name
     * @var
     */
    protected $templateName;

    /**
     * Source directory
     * @var
     */
    protected $source;

    /**
     * Path
     * @var
     */
    protected $path;

    /**
     * main constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Convert Template to Content
     * @param $variables
     * @param string $filename
     * @return mixed|string
     */
    public function getContent($variables, $filename = '')
    {
        if ($filename != '') {
            $_return = file_get_contents($filename);
        } else {
            $_return = file_get_contents($this->templateName);
        }
        foreach ($variables as $name => $value) {
            $_return = str_replace('{{' . $name . '}}', $value, $_return);
        }
        return $_return;
    }

    /**
     * Create file from Template
     * @param $templateFileName
     * @param $variables
     * @param $targetFile
     */
    protected function createFile($templateFileName, $variables, $targetFile)
    {
        $_targetFile = $this->path . DIRECTORY_SEPARATOR . $targetFile;
        echo $_targetFile.PHP_EOL;
        $_templateFileName = __DIR__ . DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR . $templateFileName;
        $_content = $this->getContent($variables, $_templateFileName);
        storage::createFile($_targetFile, $_content);
    }
}

?>