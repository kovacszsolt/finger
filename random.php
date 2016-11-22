<?php
namespace finger;

/**
 * Random generator
 * Class random
 * @package finger
 */
class random
{
    /**
     * Create random string
     * @param int $number
     * @return string
     */
    public static function char($number)
    {
        $_return = '';
        for ($i = 0; $i < $number; $i++) {
            $_return .= chr(rand(ord('a'), ord('z')));
        }
        return $_return;
    }

    /**
     * Create random hash
     * @param int $number
     * @return string
     */
    public static function hash($number)
    {
        $_return = hash('sha256', self::char($number), false);
        return $_return;
    }
}