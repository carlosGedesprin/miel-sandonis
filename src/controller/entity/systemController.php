<?php

namespace src\controller\entity;

use \src\controller\baseController;

use DateTime;
use DateTimeZone;

class systemController extends baseController
{
    private $cookies = array(   // Settings about cookie creation
        // Default : cookies expire in 30 days.
        "expire" => "+30 days",
        "path" => "/",
        "domain" => "",
        "key" => "c5xc436JdbK30f840vP9!@#$",
    );

    /**
     *
     * Clean cookies
     *
     */
    public function cleanCookiesAction()
    {
        $cookies_to_delete = array(
                                    '_Login',
                                    '_Rememberme',
                                    '_CurUser',
                                    '_UserGroup',
                                    '_UserName',
                                    '_folder',
                                    '_locale',
                                    '_CurPage',
        );

        foreach ( $cookies_to_delete as $cookie)
        {
            if (isset($_COOKIE[$this->session->config['cookies_prefix'].$cookie]))
            {
                unset($_COOKIE[$this->session->config['cookies_prefix'].$cookie]);

                setcookie(
                    $this->session->config['cookies_prefix'].$cookie,
                    '',
                    time() - 10,
                    $this->cookies['path'],
                    $this->cookies['domain']
                );
            }
            $_SESSION[$this->session->config['cookies_prefix'].$cookie] =  "";
        }

        $_SESSION['csrf'] = "";
        $_SESSION['alert'] = "";

        return true;
    }
    /**
     *
     * Upload Lang texts
     *
     */
    public function setLangTextsAction()
    {
        $lang_class = new \src\util\lang( $this->db, $this->session->getLanguageCode2a() );
        $lang_class->toDB();
        if ( $lang = $lang_class->getLangTexts() )
        {
            return $lang;
        }
        else
        {
            return false;
        }
    }

}
