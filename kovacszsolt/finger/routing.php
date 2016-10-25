<?php
namespace finger;

use \finger\session as session;

/**
 * Session handler
 * @package finger
 */
class routing
{

    private static function detectLanguage($url)
    {
        $_configSettings = new config('settings');
        $_lang_ok = array();
        foreach ($_configSettings->get('languages') as $d => $a) {
            $_lang_ok[] = 'lang/' . $d . '/';
        }
        if (in_array($url, $_lang_ok)) {
            $_session = new session();
            $_tmp = explode('/', $url);
            $_session->setValue('currentLang', $_tmp[1]);
            header('Location: /');
            exit;
        }
        return $url;
    }

    public static function find($url)
    {
        $url = self::detectLanguage($url);
        $_configRouting = new config('routing');
        $url=$_configRouting->get($url,$url);
        return $url;
    }
}
