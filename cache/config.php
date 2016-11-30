<?php

namespace finger\cache;

/**
 *
 * Cache Config Class main
 * @package finger\database
 */
class config extends \finger\config
{


    /**
     * string
     * @var mixed|string
     */
    public $hostname = '';

    /**
     * port
     * @var mixed|string
     */
    public $port = '';

    /**
     * config constructor.
     */
    public function __construct()
    {
        parent::__construct('cache');
        $this->hostname = $this->get('hostname');
        $this->port = $this->get('port');
    }
}
