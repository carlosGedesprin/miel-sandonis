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

    public $cookies = array(
        "expire" => "+30 days",
        "path" => "/",
        "domain" => "",
        "key" => "c5xc436JdbK30f840vP9!@#$",
    );
    private $remember_me = true;    // Enable/Disable `Remember Me` feature

    /**
     * Prevent Brute Forcing
     * ---------------------
     * By enabling this, login System will deny login for the time mentioned
     * in the "brute_force"->"time_limit" seconds after "brute_force"->"tries"
     * number of incorrect login tries.
     */
    private $block_brute_force = array(
        // Enable / Disable `Blocking Brute Force Attacks`
        "enabled" => true,
        // No of tries alloted to each user
        "tries" => 5,
        // The time IN SECONDS for which block from login action should be done after incorrect
        // login attempts. Default : 5 minutes
        "time_limit" => 300
    );
    private $two_step_login = array(
        // Flag enabled
        'enabled' => false,
        // Message to show before displaying "Enter Token" form.
        'instruction' => '',
        // Callback when token is generated. Used to send message to user (Phone/E-Mail)
        'send_callback' => '',
        // The table to store user's sessions
        'devices_table' => 'user_devices',
        // The length of token generated. A low value is better for tokens sent via Mobile SMS
        'token_length' => 4,
        // Whether the token should be numeric only ?  Default Token : Alphabetic + Numeric mixed strings
        'numeric' => false,
        // The expire time of cookie that authorizes the device to login using the user's account with 2 Step Verification
        // The value is for setting in strtotime() function http://php.net/manual/en/function.strtotime.php
        'expire' => '+45 days',
        // Should logSys checks if device is valid, everytime logSys is initiated ie everytime a page loads
        // If you want to check only the first time a user loads a page, then set the value to TRUE, else FALSE
        'first_check_only' => true
    );
    private $keys = array(
        //Changing cookie key will expire all current active login sessions
        "cookie" => "ckxc436jd*^30f840v*9!@#$",
        //`salt` should not be changed after users are created
        "salt" => "^#94%9fJ1^p9)M@4M)V$",
        // Password Crypt options
        "crypt_options" => array(
            'cost' => 12,
        ),
    );

    private $lang_code_2a;

    private $user_account;
    private $user_account_key;
    private $user_key;
    private $user_name;
    private $user_group;
    private $user_folder;

    private $loggedIn = false;
    private $login, $cur_user, $remember_cookie;

    private $is_bot = false;

    private $myfile;

    public $config;

    public $uri;

    public function __construct( $startup, $db, $utils, $logger, $logger_err )
    {

        if ( str_contains( $_SERVER['REQUEST_URI'], 'cron_image') )
        {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/session_cron_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/1_session_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
        }

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

        $this->is_logged();

        //$this->reg_visit();

        $this->extras();

        if ( $this->loggedIn )
        {
            // Check Private messages
            //$this->check_pm();
        }
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
     * Check if user is logged
     */
    private function is_logged()
    {
//$txt = '====================== '.__METHOD__.' start '.'==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'URI ========> '.$_SERVER["REQUEST_URI"].PHP_EOL; fwrite($this->myfile, $txt);

        if ( session_status() === PHP_SESSION_ACTIVE  && isset( $_SESSION[$this->config['cookies_prefix'].'_CurUser'] ) )
        {
//$txt = 'SESSION CurUser ========> '.$_SESSION[$this->config['cookies_prefix'].'_CurUser'].PHP_EOL; fwrite($this->myfile, $txt);
            $this->login = isset($_COOKIE[$this->config['cookies_prefix'].'_Login']) ? $_COOKIE[$this->config['cookies_prefix'].'_Login'] : false;
            $this->cur_user = isset($_SESSION[$this->config['cookies_prefix'].'_CurUser']) ? $_SESSION[$this->config['cookies_prefix'].'_CurUser'] : false;
            $this->remember_cookie = isset($_COOKIE[$this->config['cookies_prefix'].'_Rememberme']) ? $_COOKIE[$this->config['cookies_prefix'].'_Rememberme'] : false;

            $encrypted_user_id = hash("sha256", $this->config['key_secret'] . $this->cur_user . $this->config['key_secret']);

            // We store in COOKIE[$this->config['cookies_prefix'].'_Login'] an encripted and 'salted' key from user_id
            // to be compared with SESSION[$this->config['cookies_prefix'].'_CurUser']
            // to detect if the visitor is a logged user in session.php            
            //echo ' CurUser ('.$this->login'.' => -'.$encrypted_user_id.'-)';die;
            if ( $this->login == $encrypted_user_id )
            {
                $this->loggedIn = true;
            }
            else
            {
                $this->loggedIn = false;
            }
//$txt = 'Logged by Session var ========> '.(($this->loggedIn)? 'Yes' : 'No').PHP_EOL; fwrite($this->myfile, $txt);
            /**
             * If there is a Remember Me cookie and the user is not logged in, then log in with the ID in the remember cookie,
             * if it matches with the decrypted value in `_login` cookie.
             */
//$txt = 'Logged by Remember me cookie ========> '.(($this->remember_cookie)? $this->remember_cookie : 'No').PHP_EOL; fwrite($this->myfile, $txt);
            if ( $this->remember_cookie !== false && $this->loggedIn === false )
            {
                $encrypted_user_id = hash("sha256", $this->config['key_secret'] . $this->remember_cookie . $this->config['key_secret']);

                if ( $this->login == $encrypted_user_id )
                {
                    $this->loggedIn = true;
                }
                else
                {
                    $this->loggedIn = false;
                }
            }
        }
        else
        {
//$txt = 'Not logged ======== '.$this->loggedIn.PHP_EOL; fwrite($this->myfile, $txt);
            $this->loggedIn = false;
        }

        if ( $this->loggedIn === true )
        {
//$txt = 'Logged - User data =======> '.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '   User => '.$this->cur_user.PHP_EOL; fwrite($this->myfile, $txt);
            $this->user_key= $this->utils->getUserKey( $this->cur_user );
//$txt = '   User key => '.$this->user_key.PHP_EOL; fwrite($this->myfile, $txt);

            $this->user_name= $this->utils->getUserName( $this->cur_user );

            $this->user_account = $this->utils->getUserAccount( $this->cur_user );
//$txt = '   Account => '.$this->user_account.PHP_EOL; fwrite($this->myfile, $txt);

            $this->user_account_key = $this->utils->getUserAccountKey( $this->cur_user );
//$txt = '   Account key => '.$this->user_account_key.PHP_EOL; fwrite($this->myfile, $txt);

            $this->user_group = $this->utils->getUserGroup( $this->cur_user );
//$txt = '   Group => '.$this->user_group.PHP_EOL; fwrite($this->myfile, $txt);

            $this->lang_code_2a = $this->utils->getUserLocale( $this->cur_user );
//$txt = '   Locale => '.$this->lang_code_2a.PHP_EOL; fwrite($this->myfile, $txt);

            // This folder is where the app has to be load (pe: app or control_panel)
            $this->user_folder = $this->utils->getGroupFolder( $this->user_group );
//$txt = '   Folder => '.$this->user_folder.PHP_EOL; fwrite($this->myfile, $txt);
        }
        elseif ( $this->loggedIn === false )
        {
//$txt = 'Setting locale ======== '.PHP_EOL; fwrite($this->myfile, $txt);
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

            $this->user_account = '';
            $this->user_account_key = '';
            $this->user_folder = '';
        }

        //$this->lang_code_2a = 'fr';

//$txt = 'Values in session'.PHP_EOL;
//$txt .= '   ==> Account ('.$this->user_account.')'.PHP_EOL;
//$txt .= '   ==> Account key ('.$this->user_account_key.')'.PHP_EOL;
//$txt .= '   ==> User key ('.$this->user_key.')'.PHP_EOL;
//$txt .= '   ==> Group ('.$this->user_group.')'.PHP_EOL;
//$txt .= '   ==> Locale ('.$this->lang_code_2a.')'.PHP_EOL;
//$txt .= '   ==> Folder ('.$this->user_folder.')'.PHP_EOL;
//$txt .= PHP_EOL; fwrite($this->myfile, $txt);
        // We set the session's values to be retrieved by twig even if the user is not logged in
        if ( session_status() !== PHP_SESSION_ACTIVE ) session_start();

        $_SESSION[$this->config['cookies_prefix'] . '_CurAccount'] = $this->user_account;
        $_SESSION[$this->config['cookies_prefix'] . '_CurAccountKey'] = $this->user_account_key;
        $_SESSION[$this->config['cookies_prefix'] . '_UserName'] = $this->user_name;
        $_SESSION[$this->config['cookies_prefix'] . '_UserGroup'] = $this->user_group;
        $_SESSION[$this->config['cookies_prefix'] . '_CurUserKey'] = $this->user_key;
        $_SESSION[$this->config['cookies_prefix'] . '_locale'] = $this->lang_code_2a;
        $_SESSION[$this->config['cookies_prefix'] . '_folder'] = $this->user_folder;

//$txt = 'Values in $_SESSION'.PHP_EOL;
//$txt .= '                    ==> Account('.(isset($_SESSION[$this->config['cookies_prefix'].'_CurAccount'])? $_SESSION[$this->config['cookies_prefix'].'_CurAccount'] : '').')'.PHP_EOL;
//$txt .= '                   ==> Account('.(isset($_SESSION[$this->config['cookies_prefix'].'_CurAccountKey'])? $_SESSION[$this->config['cookies_prefix'].'_CurAccountKey'] : '').')'.PHP_EOL;
//$txt .= '                   ==> Name ('.(isset($_SESSION[$this->config['cookies_prefix'].'_UserName'])? $_SESSION[$this->config['cookies_prefix'].'_UserName'] : '').')'.PHP_EOL;
//$txt .= '                   ==> Group('.(isset($_SESSION[$this->config['cookies_prefix'].'_UserGroup'])? $_SESSION[$this->config['cookies_prefix'].'_UserGroup'] : '').')'.PHP_EOL;
//$txt .= '                   ==> Locale ('.$_SESSION[$this->config['cookies_prefix'].'_locale'].')'.PHP_EOL;
//$txt .= '                   ==> Folder ('.$_SESSION[$this->config['cookies_prefix'].'_folder'].')'.PHP_EOL;
//$txt .= PHP_EOL; fwrite($this->myfile, $txt);
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
     * Check if page to be served is a protected one
     *
     * @param $folders array Page folder and group allowed
     */
    public function checkProtectedURL( $folders )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $page_is_protected = false;
        $groups_allowed = array();
        $user_allowed = false;
        $page_root = explode( '/', $this->utils->curPage() );

//$txt = 'Pages'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($folders, TRUE));
//$txt = 'URL exploded '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($page_root, TRUE));

        foreach ( $folders as $folder )
        {
//$txt = 'Page '.$page_root[1].' | '.$folder[0].PHP_EOL; fwrite($this->myfile, $txt);
            if( !$page_is_protected && isset( $page_root[1] ) && $page_root[1] == $folder[0] )
            {
                $groups_allowed = $folder[1];
                $page_is_protected = true;
                break;
            }
        }
//$txt = 'Page is protected ('.(($page_is_protected)? 'yes' : 'no').')'.PHP_EOL; fwrite($this->myfile, $txt);

        if ( $page_is_protected )
        {
//$txt = 'Groups allowed '.sizeof( $groups_allowed ).PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($groups_allowed, TRUE));
            /*
            $inactivity = ( $_ENV['env_env'] == 'dev' )? 60000 : 15 * 60;
//$txt = 'Inactivity '.$inactivity.PHP_EOL; fwrite($this->myfile, $txt);

            if ( isset( $_SESSION['timeout'] ) )
            {
                $sessionTTL = time() - $_SESSION['timeout'];
//$txt = 'Timeout '.$sessionTTL.' '.$inactivity.PHP_EOL; fwrite($this->myfile, $txt);
                if ( $sessionTTL > $inactivity )
                {
                    session_destroy();
                    header("Location: /session-expired");
                    exit;
                }
            }
            $_SESSION['timeout'] = time();
            */
            if ( sizeof( $groups_allowed ) )
            {
//$txt = 'Groups allowed > 0 ===> let\'s see if current user is allowed'.PHP_EOL; fwrite($this->myfile, $txt);
            if ( $this->loggedIn === false )
            {
//$txt = 'Not logged'.PHP_EOL; fwrite($this->myfile, $txt);
                    // Remember the page initially requested
                $_SESSION[$this->config['cookies_prefix'].'_CurPage'] = $this->utils->curPage();
                    //$this->utils->redirect($this->pages['login_page']);
//$txt = 'Go to session-expired'.PHP_EOL; fwrite($this->myfile, $txt);
                    header('Location: /session-expired');
                }
                else
                {
//$txt = 'Logged'.PHP_EOL; fwrite($this->myfile, $txt);
                    foreach ( $groups_allowed as $group_allowed )
                    {
//$txt = 'using ('.$group_allowed.') group ('.$_SESSION[$this->config['cookies_prefix'].'_UserGroup'].')' . PHP_EOL; fwrite($this->myfile, $txt);
                        if( !$user_allowed && $group_allowed == $_SESSION[$this->config['cookies_prefix'].'_UserGroup'] )
                    {
                        $user_allowed = true;
                            break;
                }
                    }

//$txt = (( !$user_allowed )? 'Redirecting 405' : 'Going to page'). PHP_EOL; fwrite($this->myfile, $txt);
                if ( !$user_allowed ) $this->utils->redirect('/error405');
            }
        }
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Register the visit
     */
    private function reg_visit()
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_FILES, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $register_visit = true;

        $reg = array(); // TODO:Carlos Just to avoid code error, if used this method inspect the origin for $reg

        if ( !$this->config['record_visits'] ) {
            $register_visit = false;
        }
        else
        {
//$remote_IP = ( isset($_SERVER['REMOTE_ADDR']) )? isset($_SERVER['REMOTE_ADDR']) : '';
//$txt = 'Remote IP ('.$remote_IP.') Private IPs ==>'.$this->config['private_ip'].PHP_EOL; fwrite($this->myfile, $txt);
            if ( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '' && $this->config['private_ip'] != '' )
            {
                $private_ip = unserialize( $this->config['private_ip'] );
//$txt = 'IPs ==========>'.$reg['ip'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($private_ip, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                if ( !array_search($_SERVER['REMOTE_ADDR'], $private_ip) )
                {
                // If the visit is ours - Not registering our visit
//$txt = 'IPs ==========> MATCH'.PHP_EOL; fwrite($this->myfile, $txt);
                $register_visit = false;
                }
            }
        }
        // Bot detection - Not registering bots visits
        if ( $register_visit )
        {
            $rows = $this->db->fetchAll('bot', '*');
            foreach ( $rows as $row )
            {
                if ($row['user_agent'] && preg_match('#' . str_replace('\*', '.*?', preg_quote($row['user_agent'], '#')) . '#i', $reg['user_agent']))
                {
                    $register_visit = false;
                    break;
                }
            }
        }
        // Avoid registering images
        if ( $register_visit )
        {
            if ( substr($reg['accept_header'], 0 , 5) == 'image' )
            {
                $register_visit = false;
            }
        }

        $reg = array(
            //'id'            => '',
            'ip'            => (isset($_SERVER['REMOTE_ADDR']))? $_SERVER['REMOTE_ADDR'] : '',
            'date'          => (new DateTime("now", new DateTimeZone($this->config['time_zone'])))->format('Y-m-d H:i:s'),
            'user_agent'    => '',
            'accept_header' => '',
            'accept_lang'   => '',
            'accept_encoding'   => '',
            'accept_charset'    => '',
            'page'              => $this->utils->curPage(),
        );
        //$script				= (isset($_SERVER['REQUEST_URI']))? $_SERVER['REQUEST_URI'] :  '';

        $reg['user_agent'] = (isset($_SERVER['HTTP_USER_AGENT']))? ( strlen($_SERVER['HTTP_USER_AGENT']) > 500 )? substr($_SERVER['HTTP_USER_AGENT'], 0, 500) :  $_SERVER['HTTP_USER_AGENT'] : '';
        $reg['accept_header'] = (isset($_SERVER['HTTP_ACCEPT']))? ( strlen($_SERVER['HTTP_ACCEPT']) > 500 )? substr($_SERVER['HTTP_ACCEPT'], 0, 500) :  $_SERVER['HTTP_ACCEPT'] : '';
        $reg['accept_lang'] = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))? ( strlen($_SERVER['HTTP_ACCEPT_LANGUAGE']) > 100 )? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 100) :  $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
        $reg['accept_encoding'] = (isset($_SERVER['HTTP_ACCEPT_ENCODING']))? ( strlen($_SERVER['HTTP_ACCEPT_ENCODING']) > 100 )? substr($_SERVER['HTTP_ACCEPT_ENCODING'], 0, 100) :  $_SERVER['HTTP_ACCEPT_ENCODING'] : '';
        //$reg['accept_charset'] = (isset($_SERVER['HTTP_ACCEPT_CHARSET']))? ( strlen($_SERVER['HTTP_ACCEPT_CHARSET']) > 100 )? substr($_SERVER['HTTP_ACCEPT_CHARSET'], 0, 100) :  $_SERVER['HTTP_ACCEPT_CHARSET'] : '';

        if ( $register_visit ) $this->db->insertArray('visits', $reg);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
    }

    /**
     * Check this user unread messages
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

                if ( !$this->is_bot )
                {
                    if ( $_SERVER['REMOTE_ADDR'] != '46.25.86.75' && $_SERVER['REMOTE_ADDR'] != '212.183.201.80' )
                    {
                        if ( substr($_SERVER['REQUEST_URI'], 0, 6 ) != '/cron/' )
                        {
$myfile = fopen(APP_ROOT_PATH.'/var/logs/session_not_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
$txt = $now->format('d-m-Y H:i:s').' - '.$_SERVER['REMOTE_ADDR'].' - '.$_SERVER['REQUEST_URI'].' Agent '.$_SERVER['HTTP_USER_AGENT'].PHP_EOL; fwrite($myfile, $txt);
fclose($myfile);
                        }
                    }
                }
            }
        }
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Check this user unread messages
     */
    private function check_pm()
    {
//$txt = '====================== '.__METHOD__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        // First get the folder id
        $rows = $this->db->fetchOne( 'pm_folder', 'id', ['user' => $this->user, 'name' => 'Inbox']);
        $inbox_folder = $rows['id'];
        unset($rows);
        $this->db->fetchAll('pm_box', 'id', ['user' => $this->user, 'folder' => $inbox_folder, 'msg_read' => '0']);
        $unread_msg = $this->db->rowCount();

        $_SESSION['pm_unread'] = '33';
        $_SESSION['pm_unread'] = $unread_msg;
//$txt = '====================== '.__METHOD__.' end ======================================'.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * Extras on development
     */
    private function extras()
    {
//$txt = '====================== '.__METHOD__.' start '.'==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
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

    public function getUserAccount()
    {
        return $this->user_account;
    }

    public function getUserFolder()
    {
        return $this->user_folder;
    }

    public function showConfig()
    {
        return $this->config;
    }
}
