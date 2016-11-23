<?php
namespace finger;
/**
 * Class request
 * @package finger
 */
class request
{
    /**
     * String type
     */
    const STRING = 'string';

    /**
     * Number tyoe
     */
    const NUMBER = 'number';

    /**
     * File type JPG
     */
    const FILE_TYPE_JPG = 'image/jpeg';

    /**
     * File type PNG
     */
    const FILE_TYPE_PNG = 'image/png';

    /**
     * File type GIF
     */
    const FILE_TYPE_GIF = 'image/gif';

    public static function backPath($number)
    {
        $_return = '';
        for ($i = 0; $i < $number; $i++) {
            $_return .= DIRECTORY_SEPARATOR . '..';
        }
        $_return .= DIRECTORY_SEPARATOR;
        return $_return;
    }

    /**
     * $_FILES array
     * check file mime type
     * @param string $name
     * @param bool $check
     * @param null $allow
     * @return null
     */
    public static function files($name, $check = false, $allow = NULL)
    {
        $_return = NULL;
        if (isset($_FILES[$name])) {
            $_files = $_FILES[$name];
            $_return = $_files;
            if ($check) {
                foreach ($_files['tmp_name'] as $_file_id => $_file) {
                    if (!is_null($allow)) {
                        $_mime_content_type = mime_content_type($_files['tmp_name'][$_file_id]);
                        if (!in_array($_mime_content_type, $allow)) {
                            $_return = NULL;
                        }
                    }
                }
            }
        }
        return $_return;

    }

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
     * Get current client IP Address
     * @return string
     */
    public static function getClientIPAddress()
    {
        return $_SERVER['REMOTE_ADDR'];
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