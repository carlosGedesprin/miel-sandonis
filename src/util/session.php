<?php
namespace src\util;

use DateTime;
use DateTimeZone;

class session
{
    private $startup;
    private $db;
    private $utils;
    private $logger;
    private $logger_err;

    private $lang_code_2a;

    private $is_bot = false;

    private $myfile;

    public $config;

    public $uri;

    public function __construct( $startup, $db, $utils, $logger, $logger_err )
    {

//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/1_session_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $this->startup = $startup;
        $this->db = $db;
        $this->utils = $utils;
        $this->logger = $logger;
        $this->logger_err = $logger_err;

        $this->get_config();
//$txt = 'Config ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->config, TRUE));

        $this->checkWebEnabled();

        $this->is_bot();

        $this->setLanguage();
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
    }

    /**
     * Check if web is set to enable
     */
    private function checkWebEnabled()
    {
//$txt = '====================== '.__METHOD__.' start '.'==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Uri '.$_SERVER['REQUEST_URI'].PHP_EOL; fwrite($this->myfile, $txt);

        if ( !$this->config['web_enabled'] )
        {
            if ( $_SERVER['REQUEST_URI'] != '/web_disabled' )
            {
                $this->utils->redirect('/web_disabled', '200');
            }
        }
		/*
        $now = time();
        $time_elapse = 60 * 30;
        if ( $_SERVER["REQUEST_URI"] != '/webstatus' )
        {
            if ( $this->config['web_enabled'] )
            {
                if ( $this->config['web_enabled_last'] + $time_elapse < $now )
                {
                    if ( $_ENV['env_env'] == 'prod' )
                    {
                        $this->db->updateArray('config', 'config_name', 'web_enabled', ['config_value' => '0']);
                        $this->db->updateArray('config', 'config_name', 'web_enabled_last', ['config_value' => $now]);
                        //echo '<br />Changed';
                        $this->utils->redirect('/'.$this->config['web_name'].'_info.php', '200');
                    }
                }
            }
            else
            {
                $this->utils->redirect('/'.$this->config['web_name'].'_info.php', '200');
            }
        }
        $this->db->updateArray('config', 'config_name', 'web_enabled_last', ['config_value' => $now]);
		*/
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Elige el idioma del visitante (?lang=xx, sesión, navegador o el de la web)
     * y lo guarda en la sesión para que Twig lo lea.
     * La web no tiene login: no hay usuarios, cuentas ni grupos.
     */
    private function setLanguage()
    {
//$txt = '====================== '.__METHOD__.' start '.'==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'URI in startup ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->startup->getURI(), TRUE));

        $this->uri = $this->startup->getURI();

        if ( isset( $this->uri['lang'] ) && !empty( $this->uri['lang'] ) )
        {
//$txt = 'Locale in uri ======== '.$this->uri['lang'].PHP_EOL; fwrite($this->myfile, $txt);
            if ( $this->db->fetchField('lang', 'active', ['code_2a' => $this->uri['lang']]) == '1' )
            {
//$txt = 'Locale in uri exists and active ======== '.PHP_EOL; fwrite($this->myfile, $txt);
                $this->lang_code_2a = $this->uri['lang'];
            }
        }
        elseif ( isset( $_SESSION[$this->config['cookies_prefix'].'_locale'] ) && !empty( $_SESSION[$this->config['cookies_prefix'].'_locale'] ) )
        {
            $this->lang_code_2a = $_SESSION[$this->config['cookies_prefix'].'_locale'];
        }
        else
        {
//$txt = 'Locale NOT in uri ======== '.PHP_EOL; fwrite($this->myfile, $txt);
            if ( $this->is_bot )
            {
                $this->lang_code_2a = $this->config['web_locale'];
            }
            else
            {
                if ( !$this->lang_code_2a = $this->getBrowserLanguage() )
                {
                    $this->lang_code_2a = $this->config['web_locale'];
                }
            }
        }

        if ( session_status() !== PHP_SESSION_ACTIVE ) session_start();

        $_SESSION[$this->config['cookies_prefix'] . '_locale'] = $this->lang_code_2a;

//$txt = 'Locale ('.$this->lang_code_2a.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Populate public config array
     */
    private function get_config()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $config = $this->db->fetchAll('config', 'config_name, config_value' );
//$txt = 'Config ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($config, TRUE));
        if ( sizeof( $config ) == 0 )
        {
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Session');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Unable to load config table');
            $this->logger_err->error('*************************************************************************');
        }
        else
        {
        foreach( $config as $value)
        {
            $this->config[$value['config_name']] = $value['config_value'];
        }
    }
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Try to establish the best lang for the visitor
     */
    private function getBrowserLanguage()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Requested URI ('.$_SERVER['REQUEST_URI'].')'.PHP_EOL;fwrite($this->myfile, $txt);

//$txt = 'SERVER ========> '.$_ENV['env_env'].PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_SERVER, TRUE));
        if ( $langs = $this->db->fetchAll('lang', 'code_2a', ['active' => '1']) )
        {
//$txt = 'Langs on db ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($langs, TRUE));
            foreach ( $langs as $value )
            {
                $supported_languages[$value['code_2a']] = $value['code_2a'];
//$txt = 'Lang '.$value['id'].'=> '.$value['code_2a'] . PHP_EOL; fwrite($this->myfile, $txt);
            }
        }
        else
        {
//$txt = 'No langs found, check database.'.PHP_EOL; fwrite($this->myfile, $txt);
            die('No langs found, check database.');
        }
//$txt = 'Supported languages'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($supported_languages, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( !isset( $_SERVER["HTTP_ACCEPT_LANGUAGE"] ) )
        {
            return false;
        }
        $http_accept_language = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
//$txt = 'Browser language languages ('.$http_accept_language.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($http_accept_language, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $matches = array();
        preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($http_accept_language), $matches, PREG_SET_ORDER);

        $available_languages = array();

//$txt = 'Language Matches '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($matches, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        foreach ( $matches as $match )
        {
            list($language_code, $language_region) = explode('-', $match[1]) + array('', '');

            $priority = isset($match[2]) ? (float)$match[2] : 1.0;

            $available_languages[][$language_code] = $priority;

            $available_languages[][$language_code][$language_region] = $language_region; // No used
        }

//$txt = 'Available languages'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($available_languages, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $default_priority = (float)0;
        $default_language_code = false;

        foreach ( $available_languages as $value )
        {
            $language_code = key($value);
            $priority = $value[$language_code];

            if ($priority > $default_priority && array_key_exists($language_code, $supported_languages))
            {
                $default_priority = $priority;
                $default_language_code = $language_code;
            }
        }
//$txt = 'Language selected ('.$default_language_code.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        return $default_language_code;
    }

    /**
     * Detecta si el visitante es un robot (tabla `bot`)
     */
    private function is_bot()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        $now = new DateTime('now', new DateTimeZone($this->config['time_zone']) );

        if ( isset($_SERVER['HTTP_USER_AGENT']) && preg_match('#' . str_replace('\*', '.*?', preg_quote('blog.accedeme.com', '#')) . '#i', $_SERVER['HTTP_USER_AGENT']) )
        {

        }
        else
        {
            if ( isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] != '' )
            {
                $rows = $this->db->fetchAll('bot', '*');
                foreach ( $rows as $row )
                {
                    // Examples: preg_match('/Googlebot|Twitterbot|crawl|ia_archiver|Yahoo! slurp|facebookexternalhit|Baiduspider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
                    if ( $row['user_agent'] && preg_match('#' . str_replace('\*', '.*?', preg_quote($row['user_agent'], '#')) . '#i', $_SERVER['HTTP_USER_AGENT']) )
                    {
                        $bot_lang = ( isset( $_SERVER["HTTP_ACCEPT_LANGUAGE"] ) )? $_SERVER["HTTP_ACCEPT_LANGUAGE"] : '';

$myfile = fopen(APP_ROOT_PATH.'/var/logs/session_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
$txt = $now->format('d-m-Y H:i:s').' - '.'Bot '.$row['name'].' - Lang '.$bot_lang.' - Agent '.$_SERVER['HTTP_USER_AGENT'].' - URL '.$_SERVER['REQUEST_URI'].PHP_EOL; fwrite($myfile, $txt);
fclose($myfile);
                        $this->is_bot = true;
                        break;
                    }
                }
            }
        }
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    public function setLanguageCode2a( $lang_code_2a )
    {
        $this->lang_code_2a = $lang_code_2a ;
    }

    public function getLanguageCode2a()
    {
        return $this->lang_code_2a;
    }

    public function showConfig()
    {
        return $this->config;
    }
}
