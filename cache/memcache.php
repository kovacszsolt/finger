<?php

namespace finger\cache;

use \finger\cache\config as config;

class memcache extends \Memcache
{
    /**
     * memcache constructor.
     */
    public function __construct()
    {
        try {
            $_config = new config('cache');
            $this->connect($_config->hostname, $_config->port) or die ("Could not connect");

        } catch (\Exception $e) {
            echo 'Cache error:' . PHP_EOL;
            echo 'error code:' . $e->getCode() . PHP_EOL;
            echo 'error message:' . $e->getMessage() . PHP_EOL;
            die();
        }
    }

    /**
     * Check the key
     * @param $key
     * @return bool
     */
    public function isExit($key)
    {
        $_return = true;
        $_tmp = $this->get($key);
        if ($_tmp === false) {
            $_return = false;
        }
        return $_return;
    }

}
