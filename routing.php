<?php
namespace finger;

use \finger\session as session;
use \model\web\url\table as urlTable;
/**
 * Routing handler
 * @package finger
 */
class routing
{

	/**
	 * Detect language from URL
	 * http://sample.com/lang/en/ OR http://sample.com/lang/hu/
	 * @param $url
	 * @return mixed
	 */
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

	/**
	 * Find URL from routing
	 * @param $url
	 * @param string $param
	 * @return mixed
	 */
	public static function find($url,&$param='')
	{
		$_configSettings = new config('settings');
		$_url = self::detectLanguage($url);
		$_configRouting = new config('routing');
		$_url = $_configRouting->get($url, NULL);
		if (is_null($_url)) {
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
		$_url=(is_null($_url) ? $url : $_url);
		$_urlTable=new urlTable();
		$_urlRecord=$_urlTable->findURL($_url);
		if (!is_null($_urlRecord)) {
			$_url=$_configSettings->get('defaultmodule').'/'.$_urlRecord[0]->getMethod();
		}
		return $_url;
	}
}
