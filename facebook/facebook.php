<?php

namespace finger\facebook;

class facebook
{

    private $_facebookClass;
    private $_accessToken;
    private $_host;

    public function __construct($ApplicationID, $SecretID,$Host)
    {
        $this->_host=$Host;
        $this->_facebookClass = new \Facebook\Facebook([
            'app_id' => $ApplicationID, // Replace {app-id} with your app id
            'app_secret' => $SecretID,
            'default_graph_version' => 'v2.8',
        ]);
    }

    private function createAccessToken()
    {
        if ($this->_accessToken == '') {
            $helper = $this->_facebookClass->getRedirectLoginHelper();
            try {
                $_accessToken = $helper->getAccessToken();
                $this->_accessToken = $_accessToken;
            } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        }
        return $this->_accessToken;
    }

    public function getAccessToken()
    {
        if (is_null($this->_accessToken)) {
            $this->createAccessToken();
        }
        return $this->_accessToken;
    }

    public function getMe()
    {
        $_return = NULL;
        if (!is_null($this->getAccessToken())) {
            try {
                $_return = $this->_facebookClass->get('/me?fields=id,name', $this->_accessToken);
                $_tmp = $this->_facebookClass->get('/me?fields=id,name', $this->_accessToken);
                $user = $_tmp->getGraphUser();
                $_return = array('id' => $user ['id'], 'name' => $user ['name'], 'email' => '');
                if (isset($user ['email'])) {
                    $_return['email'] = $user ['email'];
                }

            } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        }
        return $_return;
    }

    public function getLoginURL($backURL = '/loginok/')
    {
        $helper = $this->_facebookClass->getRedirectLoginHelper();
        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl($this->_host . $backURL, $permissions);
        return $loginUrl;
    }

}
