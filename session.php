<?php
namespace finger;

use \finger\random as random;
use \finger\request as request;

/**
 * Session handler
 * @package finger
 */
class session
{
    /**
     * session constructor.
     */
    public function __construct()
    {
        /**
         * Start Session if not started
         */
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Read value from Session
     * @param $name Name
     * @param null $default default value when not exits
     * @return null Value
     */
    public function getValue($name, $default = NULL)
    {
        $_return = $default;
        if (isset($_SESSION[$name])) {
            $_return = $_SESSION[$name];
        }
        return $_return;
    }

    /**
     * Session ID
     * @return string
     */
    public function getSessionID()
    {
        return session_id();
    }

    /**
     * Save data to Session
     * @param $name Name
     * @param $value Value
     */
    public function setValue($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Remove item from Session
     * @param $name
     */
    public function remove($name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * Session Flash
     * Remove Session after read
     * @param $name
     * @param null $value
     * @return null
     */
    public function flash($name, $value = NULL)
    {
        $_name = 'flash.' . $name;
        if (is_null($value)) {
            $_return = $this->getValue($_name, '');
            $this->remove($_name);
            return $_return;
        } else {
            $this->setValue($_name, $value);
        }
    }

    /**
     * Kill all Session value
     */
    public function removeAll()
    {
        if (is_array($_SESSION)) {
            foreach ($_SESSION as $session) {
                $this->remove($session);
            }
        }
    }
}
