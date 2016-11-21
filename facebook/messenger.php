<?php

namespace finger\facebook;

/**
 * Facebook Class messenger
 * @package finger\facebook
 */
class messenger
{
    /**
     * Access Token
     * @var string
     */
    private $access_token;

    /**
     * messenger constructor.
     * @param $access_token
     */
    public function __construct($access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * Send image from URL
     * @param $url string
     */
    public function sendImage($url)
    {
        $_message = array(
            'attachment' => array(
                'type' => 'image',
                'payload' => array(
                    'url' => $url
                )
            )
        );
        $message = $this->createMessage($_message);
        error_log(json_encode($message));
        $this->send($message);
    }

    public function sendList($text, $items = array())
    {
        $buttons = array();
        foreach ($items as $item) {
            $buttons[] = array(
                'type' => 'postback',
                'title' => $item['title'],
                'payload' => $item['payload']
            );
        }
        $_message = array(
            'attachment' => array(
                'type' => 'template',
                'payload' => array(
                    'template_type' => 'button',
                    'text' => $text,
                    'buttons' => $buttons
                )
            )
        );
        $message = $this->createMessage($_message);
        $this->send($message);
    }

    /**
     * Send simple text
     * @param $text string
     */
    public function sendMessage($text)
    {
        $_message = array(
            'text' => $text
        );
        $message = $this->createMessage($_message);
        error_log(json_encode($message));
        $this->send($message);
    }

    /**
     * Create Message to CURL
     * @param $message
     * @return array
     */
    private function createMessage($message)
    {
        $_return = array(
            'recipient' => array(
                'id' => $this->sender
            ),
            'message' => $message
        );
        return $_return;
    }

    /**
     * Send Message to Facebook
     * @param $json
     */
    private function send($json)
    {
        if (is_array($json)) {
            $json = json_encode($json);
        }
        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . $this->access_token;
        $ch = \curl_init($url);
        $jsonDataEncoded = $json;
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
    }
}