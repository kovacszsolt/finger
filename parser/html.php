<?php

namespace finger\parser;

/**
 * HTML Parser Class
 * @package finger\parser
 */
class html
{
	/**
	 * Get Facebook OG tags from Page
	 * @param string $url
	 * @param bool $convertUTF8
	 * @return array
	 */
    public static function getFacebookData($url, $convertUTF8 = false)
    {
        $_return = array(
            'title' => '',
            'descrition' => '',
            'image' => ''
        );
        $page_content = file_get_contents($url);
        $dom_obj = new \DOMDocument();
        $dom_obj->loadHTML($page_content);
        $meta_val = null;
        foreach ($dom_obj->getElementsByTagName('meta') as $meta) {
            switch ($meta->getAttribute('property')) {
                case 'og:title' :
                    $_return['title'] = ($convertUTF8) ? utf8_decode($meta->getAttribute('content')) : $meta->getAttribute('content');
                    break;
                case 'og:description' :
                    $_return['descrition'] = ($convertUTF8) ? utf8_decode($meta->getAttribute('content')) : $meta->getAttribute('content');
                    break;
                case 'og:image' :
                    $_return['image'] = ($convertUTF8) ? utf8_decode($meta->getAttribute('content')) : $meta->getAttribute('content');
                    break;
                case 'og:url':
                case 'og:site_name':
                case 'fb:app_id':
                case '':
                    break;
                default:
            }

        }
        return $_return;
    }

}