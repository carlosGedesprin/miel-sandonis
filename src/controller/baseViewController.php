<?php

namespace src\controller;


class baseViewController
{
    protected $env;
    protected $logger;
    protected $logger_err;
    protected $startup;
    protected $db;
    protected $utils;
    protected $session;
    protected $lang;
    protected $twig;
    protected $uri;

    protected $account;
    protected $account_key;
    protected $user;
    protected $user_key;
    protected $group;

    protected $myfile;

    public function __construct()
    {
$log_mode = ( $_ENV['env_env'] == 'dev' )? 'a+' : 'w';
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/baseViewController_'.__FUNCTION__.'.txt', $log_mode) or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $arguments = func_get_args();
//$txt = 'Arguments ===>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($arguments, TRUE));
        if(!empty($arguments))
            foreach($arguments[0] as $key => $property)
                if(property_exists($this, $key))
                    $this->{$key} = $property;

//$txt = 'Uri ===>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->uri, TRUE));
//$txt = 'Utils ===>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->utils, TRUE));

$txt = 'Cookies prefix ('.$this->session->config['cookies_prefix'].')'.PHP_EOL;fwrite($this->myfile, $txt);
        if ( isset( $this->session->config['cookies_prefix'] ) )
        {
            $this->account = (isset($_SESSION[$this->session->config['cookies_prefix'] . '_CurAccount'])) ? $_SESSION[$this->session->config['cookies_prefix'] . '_CurAccount'] : '';
            $this->user = (isset($_SESSION[$this->session->config['cookies_prefix'] . '_CurUser'])) ? $_SESSION[$this->session->config['cookies_prefix'] . '_CurUser'] : '';
            $this->group = (isset($_SESSION[$this->session->config['cookies_prefix'] . '_UserGroup'])) ? $_SESSION[$this->session->config['cookies_prefix'] . '_UserGroup'] : '';
$txt = 'account ('.$this->account.') user ('.$this->user.') Group ('.$this->group.')'.PHP_EOL;fwrite($this->myfile, $txt);
        }
$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}
