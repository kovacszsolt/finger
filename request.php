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
        $_tmp_request=array_merge($_GET,$_POST);
        $_return = $default;
        if (isset($_tmp_request[$name])) {
            $_return = $_tmp_request[$name];
        }
        switch ($type) {
            case request::NUMBER :
                $_return = ($_return == '') ? 0 : $_return ;
                break;
        }
        return $_return;
    }

}