<?php
namespace finger;

/**
 * Class request
 * @package finger
 */
class request
{
    // Type Check
    const STRING = 'string';
    const NUMBER = 'number';

    /**
     * Get $_GET and $_POST parameter
     * @param $name
     * @param string $default
     * @param string $type
     * @return int|string
     */
    public static function get($name, $default = '', $type = request::STRING)
    {
        $_tmp_request = array_merge($_GET, $_POST);
        $_return = $default;
        if (isset($_tmp_request[$name])) {
            $_return = $_tmp_request[$name];
        }
        switch ($type) {
            case request::NUMBER :
                $_return = ($_return == '') ? 0 : $_return;
                break;
        }
        return $_return;
    }

    /**
     * return Current URL
     * @return mixed
     */
    public static function currentURL()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Get Current URL with host name
     * @return string
     */
    public static function currentFullURL()
    {
        return self::getProtocol() . '://' . self::getServerName() . self::currentURL();
    }

    /**
     * Get Current Server Name
     * @return mixed
     */
    public static function getServerName()
    {
        $servername = $_SERVER['SERVER_NAME'];
        return $servername;
    }

    /**
     * get Current Protocol
     * @return string
     */
    public static function getProtocol()
    {
        $protocol = 'http';
        if ((isset($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] == 'on')) {
            $protocol = 'https';
        }
        if ((isset($_SERVER['SERVER_PORT'])) && ($_SERVER['SERVER_PORT'] == '443')) {
            $protocol = 'https';
        }
        return $protocol;
    }

}