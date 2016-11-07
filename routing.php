<?php
namespace finger;

use \finger\session as session;

/**
 * Routing handler
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
            header('Location: ' . $_session->getValue('prevURL', '/'));
            exit;
        }
        return $url;
    }

    public static function find($url,&$param='')
    {
        //echo 'r';exit;
        $_url = self::detectLanguage($url);
        $_configRouting = new config('routing');
        //$url=$_configRouting->get($url,$url);
        //return $url;
        $_url = $_configRouting->get($url, NULL);
        if (is_null($_url)) {
            $_like=array();
            foreach ($_configRouting->getAll() as $_routing_url=>$_routing) {
                if (strpos($_routing_url,'/%')==true) {
                    $_main_url=substr($_routing_url,0,-1);
                    if (!(strpos($url,$_main_url)===false)) {
                        $param=str_replace($_main_url,'',$url);
                        return $_routing;
                    }
                }
            }
        }
        return $url;
    }
}
