<?php

namespace src\controller;


class baseController
{
    protected $env;
    protected $logger;
    protected $logger_err;
    protected $startup;
    protected $db;
    protected $utils;
    protected $session;
    protected $lang;
    
    protected $account;
    protected $account_key;
    protected $user;
    protected $user_key;
    protected $group;

    protected $myfile;

    public function __construct()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/baseController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/entity_'.basename(get_called_class()).'.txt', 'w') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $arguments = func_get_args();
        if(!empty($arguments))
            foreach($arguments[0] as $key => $property)
                if(property_exists($this, $key))
                    $this->{$key} = $property;

//$txt = 'Config on session ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->session->showConfig(), TRUE));
//$txt = 'Cookies prefix ('.$this->session->config['cookies_prefix'].')'.PHP_EOL;fwrite($this->myfile, $txt);
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
