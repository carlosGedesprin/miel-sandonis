<?php
namespace src\util;

/**
 * Class startup
 * @package StartUp
 */
class startup
{
    private $ip;
    private $url_app;
    private $uri = array();
    private $url;

    private $myfile;

    /**
     * startup constructor.
     */
    public function __construct()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/startup_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        if ( isset($_COOKIE[session_name()]) && 0 === preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $_COOKIE[session_name()]) )
        {
            unset($_COOKIE[session_name()]);
        }

        if ('' === session_id()) {
            session_start();
        }
        /*
                if ( preg_match('/^[-,a-zA-Z0-9]{1,128}$/', session_id()) > 0 )
                {
        $txt = 'Valid session id ====> '.session_id().PHP_EOL; fwrite($this->myfile, $txt);
                }
                else
                {
        $txt = 'Not valid session id =====> '.session_id().PHP_EOL; fwrite($this->myfile, $txt);
                }
        */
        $this->setEnvironment();

        $this->setUrlApp();

        $this->setURI();

        $this->setURL();
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Sets the environment variables:
     *
     * IP
     */
    private function setEnvironment()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $this->ip = ( !empty($_SERVER['REMOTE_ADDR'] ) ) ? (string)$_SERVER['REMOTE_ADDR'] : '';
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Sets the URL of the application set in ENV.txt
     */
    private function setUrlApp()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

//        $protocol = explode("/", $_SERVER['SERVER_PROTOCOL']);
//        $protocol = strtolower($protocol[0])."://";
//        $this->url_app = $protocol.$_SERVER['SERVER_NAME'];
        if ( is_array( $_ENV ) )
        {
            $this->url_app = $_ENV['protocol'].'://'.$_ENV['domain'];
        }
        else
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => 'Error in app. Try later.',
                'filters'       => '',
                'pagination'    => '',
            );
            header('Location: /');
            exit;
        }
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * setter $this->uri
     */
    private function setURI()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        if ( isset( $_SERVER["REQUEST_URI"] ) )
        {
//$txt = 'URI 1 ========> '.$_SERVER["REQUEST_URI"].PHP_EOL; fwrite($this->myfile, $txt);
            $uri = explode('?', $_SERVER["REQUEST_URI"]);
//$txt = 'URI 2 ========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($uri, TRUE));
            if ( isset( $uri[1] ) && !empty( $uri[1] ) )
            {
                $uri_items = explode('&', $uri[1]);
//$txt = 'URI 3 ========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($uri_items, TRUE));
                $uri = array();
                foreach ($uri_items as $uri_key => $uri_value)
                {
//$txt = 'URI 4 ========> Key '.$uri_key.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'URI 4 ========> value '.$uri_value.PHP_EOL; fwrite($this->myfile, $txt);
                    $uri_item = explode('=', $uri_value);
                    if (isset($uri_item[0]) && isset($uri_item[1]))
                    {
                        if (!empty($uri_item[0])) $uri[$uri_item[0]] = $uri_item[1];
                    }
                    else
                    {
//$txt = 'Error on URI ========> '.$uri_key.' -> '.$uri_value.PHP_EOL; fwrite($this->myfile, $txt);
                    }
                }
            }
            $this->uri = $uri;
//$txt = PHP_EOL.'This URI ========>'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->uri, TRUE));

        }
        else
        {
//$txt = 'No $_SERVER["REQUEST_URI"] ========> '.PHP_EOL; fwrite($this->myfile, $txt);
        }
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * getter $this->ip
     *
     * @return mixed
     */
    public function getIP()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->ip;
    }

    /**
     * setter $this->url
     *
     * @return mixed
     */
    public function setURL()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        if ( isset( $_SERVER["REQUEST_URI"] ) )
        {
//$txt = 'URL 1 ========> '.$_SERVER["REQUEST_URI"].PHP_EOL; fwrite($this->myfile, $txt);
            $uri = explode('?', $_SERVER["REQUEST_URI"]);
            $this->url = $uri[0];
        }
//$txt = 'URL 3 ========> '.$this->url.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * getter $this->url_app
     * @return mixed
     */
    public function getUrlApp()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->url_app;
    }

    /**
     * getter $this->uri
     *
     * @return mixed
     */
    public function getURI()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->uri;
    }

    /**
     * getter $this->url
     *
     * @return mixed
     */
    public function getURL()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->url;
    }
}
