<?php

namespace finger\facebook;
class facebook
{

    private $_facebookClass;
    private $_accessToken;
    private $_host;
    private $_helper;
    private $_session;
    private $_session_name = 'facebook_access_token';

    public function __construct($ApplicationID, $SecretID, $Host, $session)
    {
        $this->_session = $session;
        $this->_host = $Host;
        $this->_facebookClass = new \Facebook\Facebook([
            'app_id' => $ApplicationID,
            'app_secret' => $SecretID,
            'default_graph_version' => 'v2.4',
        ]);
        $this->_helper = $this->_facebookClass->getRedirectLoginHelper();
        $this->getAccessToken();
        if ($this->_accessToken != '') {
            $this->_facebookClass->setDefaultAccessToken($this->_accessToken);
        }
    }


    private function getAccessToken()
    {
        $this->_accessToken = $this->_session->getValue('facebook_access_token', '');
        try {
            if (($this->_accessToken == '') || (is_null($this->_accessToken))) {
                $this->_accessToken = $this->_helper->getAccessToken();
                //var_dump($this->_accesstoken);exit;
                $this->_session->setValue('facebook_access_token', $this->_accessToken);
            }
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


    public function getMe()
    {
        $_return = NULL;
        if (!is_null($this->_accessToken)) {
            $profile_request = $this->_facebookClass->get('/me?fields=name,first_name,last_name,email,birthday');
            $profile = $profile_request->getGraphNode()->asArray();
            $_return = array('id' => $profile ['id'], 'name' => $profile ['name'], 'email' => $profile ['email']);


        }
        return $_return;
    }

    public function getFriends()
    {
        $profile_request = $this->_facebookClass->get('/me/taggable_friends?limit=1000');
        $profiles = $profile_request->getGraphEdge();
        $return = array();
        foreach ($profiles as $profile) {
            $return[] = array(
                'id' => $profile['id'],
                'name' => $profile['name']
            );
        }
        return $return;


    }

    public function getLoginURL($backURL = '/loginok/')
    {
        $permissions = ['email,user_birthday,user_friends']; // optional
        $loginUrl = $this->_helper->getLoginUrl($this->_host . $backURL, $permissions);
        $helper = $this->_facebookClass->getRedirectLoginHelper();
        return $loginUrl;
    }

}
