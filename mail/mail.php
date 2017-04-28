<?php

namespace finger\mail;

use finger\config as config;

/**
 * Class mail
 * use Swift mailer
 * @package finger\mail
 */
class mail
{

    private $subject = '';
    private $from = array();
    private $body = '';
    private $to = array();
    private $_transport;

    /**
     * mail constructor.
     * read mail settings from config
     */
    public function __construct()
    {
        $_config = new config('mail');
        $this->_transport = \Swift_SmtpTransport::newInstance($_config->get('server'), 25);
        $this->_transport->setUsername($_config->get('username'));
        $this->_transport->setPassword($_config->get('password'));
    }

    /**
     * Set mail From
     * @param string $email
     * @param string $name
     */
    public function setFrom($email, $name = '')
    {
        $this->from = array($email => ($name == '') ? $email : $name);
    }

    /**
     * Set mail subject
     * @param string $value
     */
    public function setSubject($value)
    {
        $this->subject = $value;
    }

    /**
     * Set mail body
     * @param string $value
     */
    public function setBody($value)
    {
        $this->body = $value;
    }

    /**
     * Add mail address
     * @param string $email
     */
    public function addTo($email)
    {
        $this->to[] = $email;
    }

    /**
     * Send mail
     */
    public function send()
    {
        $_message = \Swift_Message::newInstance();
        $_message->setSubject($this->subject);
        $_message->setFrom($this->from);
        $_message->setTo($this->to);
        $_message->setContentType('text/html');
        $_message->setBody($this->body);
        $_mailer = \Swift_Mailer::newInstance($this->_transport);
        $result = $_mailer->send($_message);
    }

}
