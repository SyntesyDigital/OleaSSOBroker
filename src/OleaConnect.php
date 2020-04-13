<?php

namespace Olea\SSOBroker;

class OleaConnect
{
    public function __construct()
    {
        $this->broker = new Broker(env('SSO_SERVER'), env('SSO_BROKER_ID'), env('SSO_BROKER_SECRET'));
        $this->broker->attach(true);
    }

    /*
     *  Execute login on SSO server
     *
     *  @param string username
     *  @param string password
     *  @param string language (fr, es, en)
     *  @param string mode (dev, rec or prod)
     */
    public function login($username, $password, $language, $mode = 'prod')
    {
        $this->broker->login($username, $password, $language);

        try {
            return $this->broker->getUserInfo();
        } catch (NotAttachedException $e) {
            header('Location: '.$_SERVER['REQUEST_URI']);
            exit;
        } catch (SsoException $e) {
            header('Location: error.php?sso_error='.$e->getMessage(), true, 307);
        }

        return false;
    }

    public function logout()
    {
        return $this->broker->logout();
    }

    public function getUser($params = [])
    {
        return $this->broker->getUserInfo($params);
    }

    public function autologin($credentials)
    {
        return $this->broker->autologin($credentials);
    }
}
