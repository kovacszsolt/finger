<?php

namespace finger\model;

use \finger\storage as storage;

/**
 * Image Record Class
 * @package finger\model
 */
class imagerecord extends \finger\model\record
{

    /**
     * Main ID join to the root table
     * @var
     */
    protected $a_rootid;

    /**
     * image name
     * @var
     */
    protected $a_name;

    /**
     * image alt text
     * @var
     */
    protected $a_alt;

    /**
     * image extension
     * @var
     */
    protected $a_extension;

    /**
     * image tmp path
     * @var
     */
    protected $tmpFileName;

    /**
     * Set root ID
     * @param $value
     */
    public function setRootId($value)
    {
        $this->a_rootid = $value;
    }

    /**
     * get Root id
     * @return mixed
     */
    public function getRootId()
    {
        return $this->a_rootid;
    }



    /**
     * set image temporary path
     * @param $value
     */
    public function setTmpFileName($value)
    {
        $this->tmpFileName = $value;
    }

    /**
     * Get image temporary path
     * @return mixed
     */
    public function getTmpFileName()
    {
        return $this->tmpFileName;
    }

    /**
     * Get image full storage path
     */
    public function getPath()
    {
        storage::getFile($this->path . DIRECTORY_SEPARATOR . $this->getID() . '.' . $this->getExtension());
    }

}
