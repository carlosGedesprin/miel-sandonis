<?php

namespace src\controller\web;

use DateTime;
use DateTimeZone;
use \src\controller\baseViewController;

use \src\controller\entity\configController;
use \src\controller\entity\userController;
use \src\controller\entity\userProfileController;
use \src\controller\entity\groupController;
use \src\controller\entity\accountController;

use \src\controller\entity\leadController;
use \src\controller\entity\leadFairController;
use \src\util\lang;
use \src\controller\entity\mailQueueController;

/*
 * IMPORTANT: Login, logout, remember and reset password are WEBSITE utilities
 *
 */

class securityViewController extends baseViewController
{
    private $crypt_options = array(
                                    'cost' => 12,
                                );

    private $loggedIn = false;      // Flag to see if the user is logged in
    private $remember_me = true;    // Enable/Disable `Remember Me` feature
    /**
     * Prevent Brute Forcing
     * ---------------------
     * By enabling this, login System will deny login for the time mentioned
     * in the 'block_brute_force'->'time_limit' seconds after 'block_brute_force'->'tries'
     * number of incorrect login tries.
     */
    private $block_brute_force = array(
        // Enable / Disable `Blocking Brute Force Attacks`
        'enabled' => true,
        // No of tries alloted to each user
        'tries' => 3,
        // The time IN SECONDS for which block from login action should be done after incorrect
        // login attempts. Default : 5 minutes
        'time_limit' => 300
    );
    private $cookies = array(   // Settings about cookie creation
        // Default : cookies expire in 30 days.
        'expire' => '+30 days',
        'path' => '/',
        'domain' => '',
        'key' => 'c5xc436JdbK30f840vP9!@#$',
    );
    
    /**
     * @Route('/webstatus', name='webstatus')
     */
    public function webstatusAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/securityViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'securityViewController '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $config = new configController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $config->getRegbyName( 'web_enabled' );

        $config->setConfigValue( ($vars['status'] == 'enabled')? '1' : '0' );
        $config->persist();

        return $this->twig->render('web/default/common/show_message.html.twig', array(
            'section' => $this->lang['SECURITY'],
            'alert_type' => 'success',
            'title' => $this->lang['WARNING'],
            'message' => $this->lang['SECURITY_STATUS_CHANGE_OK'],
            'redirect_wait' => '3500',
            'redirect' => '/',
        ));
    }

    /**
     * @Route('/login', name='login')
     */
    public function loginAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/securityViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'securityViewController '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Cookies prefix ========> '.$this->session->config['cookies_prefix'].PHP_EOL; fwrite($this->myfile, $txt);
        $now = new \DateTime('now', new \DateTimeZone($_ENV['time_zone']));
        $form_action = 'login';

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        
        $user_profile = new userProfileController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $group = new groupController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $post = array(
                        'email' => ( isset( $_POST['email']) )? $_POST['email'] : '',
                        'password' => ( isset( $_POST['current-password']) )? $_POST['current-password'] : '',
                        'remember_me' => ( isset( $_POST['remember_me']) )? '1' : '0',
                        'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
        );
        $data = array(
                        'submit'    => ( isset($_POST['btn_submit']) ) ? true : false,
        );
//$txt = 'POST ========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($post, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( isset($_SESSION[$this->session->config['cookies_prefix'].'_CurUser']) && $_SESSION[$this->session->config['cookies_prefix'].'_CurUser'] != '' )
        {
            $this->logger->info('=============== Already logged user: ('.$_SESSION[$this->session->config['cookies_prefix'].'_CurUser'].')');

            return $this->twig->render('web/default/common/show_message.html.twig', array(
                'section' => 'Seguridad',
                'alert_type' => 'success',
                'title' => 'Login',
                'message' => 'Ya estas logeado',
                'redirect_wait' => '4000',
                'redirect' => '/'.(($this->session->getUserFolder())? $this->session->getUserFolder().'/dashboard' : '' ),
            ));
        }

        if ( $data['submit'] && ( $post['email'] != '' && $post['password'] !== '' )) //no check
        {
            // CSRF Token validation
            $valid = $this->utils->verifyFormToken($form_action, $post['auth_token'], 5000);
            if( !$valid ) {
//$txt = 'Auth token not valid'.PHP_EOL; fwrite($this->myfile, $txt);
                return $this->twig->render('web/default/common/show_message.html.twig', array(
                    'section' => 'Seguridad',
                    'alert_type' => 'danger',
                    'title' => 'Advertencia',
                    'message' => 'Token erroneo',
                    'redirect_wait' => '6000',
                    'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                ));
            }

            $this->logger->info('==============='.__METHOD__.' IP ('.$this->startup->getIP().') email ('.$post['email'].') | PWD ('.$post['password'].') ===================================================');
            
            if( !$user->getRegByEmail( $post['email'] ) )
            {
//$txt = 'User not found in database'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'securityViewController loginAction end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
                // No such user
                return $this->twig->render('web/default/common/show_message.html.twig', array(
                    'section' => 'Seguridad',
                    'alert_type' => 'danger',
                    'title' => 'Advertencia',
                    'message' => 'Este usuario no existe',
                    'redirect_wait' => '6000',
                    'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                ));
            }
            else
            {
//$txt = 'User found'.PHP_EOL; fwrite($this->myfile, $txt);

//            $this->logger->info('==============='.__METHOD__.' User logging ('.$user->getId().') Active ('.$user->getActive().') Act. Key ('.$user->getActivation_key().') Atempt. ('.$user->getAttempt().')===================');
//            $this->logger->info('==============='.__METHOD__.' User logging ('.$user->getId().') Active ('.$user->getActive().')===================');

                // User is not active
                if ( $user->getActive() == false )
                {
//$txt = 'User not active'.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $user->getActivationKey() != '' )
                    {
//$txt = 'User not activated'.PHP_EOL; fwrite($this->myfile, $txt);
                        return $this->twig->render('web/default/common/show_message.html.twig', array(
                            'section' => 'Seguridad',
                            'alert_type' => 'danger',
                            'title' => 'Advertencia',
                            'message' => 'Usuario no activado',
                            'redirect_wait' => '6000',
                            'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                        ));
                    }
                    else
                    {
//$txt = 'User disactivated'.PHP_EOL; fwrite($this->myfile, $txt);
                        return $this->twig->render('web/default/common/show_message.html.twig', array(
                            'section' => 'Seguridad',
                            'alert_type' => 'danger',
                            'title' => 'Advertencia',
                            'message' => 'Usuario no activado',
                            'redirect_wait' => '6000',
                            'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                        ));
                    }
                }

                // To avoid Brute Force attacks a time in needed after an user is blocked
                if ( $this->block_brute_force['enabled'] === true )
                {
                    if ( substr($user->getAttempt(), 0, 2) == 'b-' )
                    {
//$txt = 'User is blocked'.PHP_EOL; fwrite($this->myfile, $txt);
                        $blockedTime = substr($user->getAttempt(), 2);
                        if (time() < $blockedTime)
                        {
                            return $this->twig->render('web/default/common/show_message.html.twig', array(
                                'section' => 'Seguridad',
                                'alert_type' => 'danger',
                                'title' => 'Advertencia',
                                'message' => sprintf('Este usuario esta bloqueado por %s segundos', round(abs($blockedTime - time()) / 60, 0)),
                                'redirect_wait' => '6000',
                                'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                            ));
                        }
                        else
                        {
                            // remove the block, because the time limit is over
                            $user->setAttempt('NULL');
                            $user->persist();
                        }
                    }
                }

                if ( password_verify( $post['password'], $user->getPassword()) )
                {
                    $this->logger->info('==============='.__METHOD__.' User logged in ');

//$txt = 'Password verified'.PHP_EOL; fwrite($this->myfile, $txt);
                    $this->loggedIn = true;

//$txt = 'User logged ('.$user->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
                    $user_profile->getRegByUser( $user->getId() );

                    // We store in COOKIE[$this->session->config['cookies_prefix'].'_Login'] an encripted and 'salted' key from user_id
                    // to be compared with SESSION[$this->session->config['cookies_prefix'].'_CurUser']
                    // to detect if the visitor is a logged user in session.php
                    //echo ' CurUser ('.$this->session->config['cookies_prefix'].'_CurUser'.' => -'.$user->getId() .'-)';die;
                    $_SESSION[$this->session->config['cookies_prefix'].'_CurUser'] = $user->getId() ;
                    setcookie(  $this->session->config['cookies_prefix'].'_Login',
                                    hash('sha256', $_ENV['key_secret'] . $user->getId() . $_ENV['key_secret']),
                                    strtotime($this->cookies['expire']),
                                    $this->cookies['path'],
                                    $this->cookies['domain']
                    );
//$txt = 'User in $_SESSION ('.$_SESSION[$this->session->config['cookies_prefix'].'_CurUser'].')'.PHP_EOL; fwrite($this->myfile, $txt);

                    // If the remember_me function is enabled and the user wants to be remembered
                    // store a cookie with this election
                    if ( $post['remember_me'] && $this->remember_me === true )
                    {
                        setcookie($this->session->config['cookies_prefix'].'_Rememberme',
                                        $user->getId() ,
                                        strtotime($this->cookies['expire']),
                                        $this->cookies['path'],
                                        $this->cookies['domain']
                        );
//$txt = 'Remember me in $_SESSION ('.$_SESSION[$this->session->config['cookies_prefix'].'_Rememberme'].')'.PHP_EOL; fwrite($this->myfile, $txt);
                    }

                    // Update lastlogin and attempt field
                    $user->setLastLogin( $now );
                    if ( $this->block_brute_force['enabled'] === true )
                    {
                        // If Brute Force Protection is Enabled, reset the attempt status
                        $user->setAttempt( 'NULL' );
                    }
                    $user->persist();

                    // We add user details to session to be retrieved from twig
                    $_SESSION[$this->session->config['cookies_prefix'].'_CurAccount'] = $user->getAccount();
                    
                    $_SESSION[$this->session->config['cookies_prefix'].'_UserName'] = ($user_profile->getName() != '')? $user_profile->getName() : $user->getEmail();
                    
                    $account->getRegbyId( $user->getAccount() );

                    $_SESSION[$this->session->config['cookies_prefix'].'_UserGroup'] = $account->getGroup();

                    $group->getRegbyId( $account->getGroup() );

                    $_SESSION[$this->session->config['cookies_prefix'].'_folder'] = $group->getFolder();

//$txt = 'Other values in $_SESSION ==> Group('.$_SESSION[$this->session->config['cookies_prefix'].'_UserGroup'].')'.PHP_EOL;
//$txt .= '                          ==> Name ('.$_SESSION[$this->session->config['cookies_prefix'].'_UserName'].')'.PHP_EOL;
//$txt .= '                          ==> Folder ('.$_SESSION[$this->session->config['cookies_prefix'].'_folder'].')'.PHP_EOL;
//$txt .= PHP_EOL; fwrite($this->myfile, $txt);
                }
                else
                {
                    $this->logger->info('==============='.__METHOD__.' User NOT logged in ');

                    // Incorrect password
//$txt = 'Password NOT verified'.PHP_EOL; fwrite($this->myfile, $txt);

                    // Check if brute force protection is enabled
                    if( $this->block_brute_force['enabled'] === true )
                    {
                        $max_tries = $this->block_brute_force['tries'];

                        if( $user->getAttempt() == '' )
                        {
                            // User has not logged in before
                            $user->setAttempt( '1' );
                            $user->persist();
                            return $this->twig->render('web/default/common/show_message.html.twig', array(
                                'section' => 'Seguridad',
                                'alert_type' => 'danger',
                                'title' => 'Advertencia',
                                'message' => sprintf('Este usuario ya ha ointentado entrar %s veces', $max_tries - 1),
                                'redirect_wait' => '6000',
                                'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                            ));
                        }
                        else if( $user->getAttempt() == $max_tries )
                        {
                            // User Blocked. User will be only able to re-login at the time in UNIX timestamp
                            $eligible_for_next_login_time = strtotime('+'. $this->brute_force['time_limit'] .' seconds', time());
                            
                            $user->setAttempt( 'b-'.$eligible_for_next_login_time );
                            $user->persist();
                            
                            return $this->twig->render('web/default/common/show_message.html.twig', array(
                                'section' => 'Seguridad',
                                'alert_type' => 'danger',
                                'title' => 'Advertencia',
                                // $blockedTime needs to be calculated, in the meanwhile we don't use it.
                                //'message' => sprintf($this->lang['ERR_SECURITY_USER_HAS_BEEN_BLOCKED'], round(abs($blockedTime - time()) / 60, 0)),
                                'message' => 'Este usuario ha sido bloqueado',
                                'redirect_wait' => '6000',
                                'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                            ));
                        }
                        else if ( $user->getAttempt() < $max_tries )
                        {
                            // If the attempts are less than Max and not Max
                            // Increase the no of tries by +1.
                            $user->setAttempt( ($user->getAttempt() == '')? '1' : intval($user->getAttempt()) + 1 );
                            $user->persist();
                            
                            return $this->twig->render('web/default/common/show_message.html.twig', array(
                                'section' => 'Seguridad',
                                'alert_type' => 'danger',
                                'title' => 'Advertencia',
                                'message' => sprintf('Este usuario ya ha intentado %s veces entrar', $max_tries - 1),
                                'redirect_wait' => '6000',
                                'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                            ));
                        }
                        else
                        {
                            return $this->twig->render('web/default/common/show_message.html.twig', array(
                                'section' => 'Seguridad',
                                'alert_type' => 'danger',
                                'title' => 'Advertencia',
                                'message' => 'Este usuario no puede logearse',
                                'redirect_wait' => '6000',
                                'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                            ));
                        }
                    }
                    else
                    {
                        return $this->twig->render('web/default/common/show_message.html.twig', array(
                            'section' => 'Seguridad',
                            'alert_type' => 'danger',
                            'title' => 'Advertencia',
                            'message' => 'Este usuario no puede logearse',
                            'redirect_wait' => '6000',
                            'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                        ));
                    }
                }
            }

            if ( $this->loggedIn )
            {
                if ( isset($_SESSION[$this->session->config['cookies_prefix'].'_CurPage']) && $_SESSION[$this->session->config['cookies_prefix'].'_CurPage'] != '' )
                {
                    $page_to_redirect = $_SESSION[$this->session->config['cookies_prefix'].'_CurPage'];
                }
                else
                {
                    $page_to_redirect = $group->getFolder().'/dashboard';
				}
            }

            return $this->twig->render('web/default/common/show_message.html.twig', array(
                'section' => 'Seguridad',
                'alert_type' => 'success',
                'title' => 'Alerta',
                'message' => 'Te has logeado con exito',
                'redirect_wait' => '3500',
                'redirect' => $page_to_redirect,
            ));
        }
//$txt = 'securityViewController loginAction end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        return $this->twig->render('web/default/security/login.html.twig', array(
                                                                    'post' => $post,
                                                                    'data' => $data,
        ));
    }

    /**
     *
     * @Route('/activate-user/{token}', name='activate_user')
     *
     */
    public function activateUserAction( $vars )
    {
        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/securityViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'securityViewController '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        if( $user->getRegByActivationKey( $vars['token'] ) )
        {
//$txt = 'User ========>' . PHP_EOL . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user, TRUE));

            $user->setActivationKey( 'NULL' );
            $user->setActive( '1' );
            $user->persist();

            $account->getRegbyId( $user->getAccount() );

            if ( $account->isUserMainInAccount( $account->getId(), $user->getId() ) )
            {
                $account->setActive( '1' );
                $account->persist();
            }
        }
        else
        {
            return $this->twig->render('web/default/common/show_message.html.twig', array(
                'section' => $this->lang['SECURITY'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_SECURITY_ACCOUNT_NO_TOKEN'],
                'redirect_wait' => '6000',
                'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
            ));
        }

//$txt = 'securityViewController send_activate_user end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        return $this->twig->render('web/default/common/show_message.html.twig', array(
            'section' => $this->lang['SECURITY'],
            'alert_type' => 'success',
            'title' => $this->lang['WARNING'],
            'message' => $this->lang['REGISTER_ACCOUNT_ACTIVATED'],
            'redirect_wait' => '6000',
            'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
        ));
    }

    /**
     * Logout the current logged in user by deleting the cookies and destroying session
     */
    public function logoutAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/securityViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'securityViewController '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->logger->info('==============='.__METHOD__.' IP ('.$this->startup->getIP().') user ('.$this->user.') ===================================================');

        // Remember the name to say goodbay
        $user_name = (isset($_SESSION[$this->session->config['cookies_prefix'].'_UserName']))? $_SESSION[$this->session->config['cookies_prefix'].'_UserName'] : '';

        // Release session on server
        session_destroy();
        setcookie(  $this->session->config['cookies_prefix'].'_Login',
            '',
            time() - 10,
            $this->session->cookies['path'],
            $this->session->cookies['domain']
        );
        setcookie(  $this->session->config['cookies_prefix'].'_Rememberme',
            '',
            time() - 10,
            $this->session->cookies['path'],
            $this->session->cookies['domain']
        );

        /**
         * Wait for the cookies to be removed, then redirect
         */
        usleep(2000);
        return $this->twig->render('web/default/common/show_message.html.twig', array(
            'section' => 'Seguridad',
            'alert_type' => 'success',
            'title' => 'Logout',
            'message' => 'Te has deslogado con exito',
            'redirect_wait' => '4000',
            'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
        ));
    }

    /**
     * Expired session
     */
    public function expiredAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/securityViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->logger->info('==============='.__METHOD__.' IP ('.$this->startup->getIP().') user ('.$this->user.') ===================================================');

        //session_destroy();
        setcookie(  $this->session->config['cookies_prefix'].'_Login',
            '',
            time() - 10,
            $this->session->cookies['path'],
            $this->session->cookies['domain']
        );
//        setcookie(  $this->session->config['cookies_prefix'].'_Rememberme',
//            '',
//            time() - 10,
//            $this->session->cookies['path'],
//            $this->session->cookies['domain']
//        );
//        setcookie(  $this->session->config['cookies_prefix'].'_UKey',
//            '',
//            time() - 10,
//            $this->session->cookies['path'],
//            $this->session->cookies['domain']
//        );

        // Wait for the cookies to be removed, then redirect
        usleep(2000);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
            'section' => $this->lang['SECURITY'],
            'alert_type' => 'success',
            'title' => $this->lang['SECURITY_EXPIRED'],
            'message' => sprintf($this->lang['SECURITY_EXPIRED_TEXT'], $this->lang['SECURITY_LOGIN_LINK']),
            'redirect_wait' => '5000',
            'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
        ));
    }

    /**
     *
     * @Route('/forgot-password', name='forgot-password')
     *
     */
    public function forgotPasswordAction()
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/securityViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'securityViewController '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $data = array(
            'email' => ( isset( $_POST['email']) )? $_POST['email'] : '',
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
        );
        $error = array();

        if ( $data['submit'] )
        {
            if ( empty($data['email']) )
            {
                $error[] = $this->lang['ERR_SECURITY_USERNAME_MAIL_NEEDED'];
            }

            if( !$user->getRegByEmail( $data['email'] ) )
            {
                $error[] = $this->lang['ERR_SECURITY_NO_USERNAME_EMAIL_USER'];
            }

            if ( !sizeof( $error ) )
            {
                $random = base64_encode( random_bytes(5) );
                $random = str_replace( '/' , '$' , $random);
                $user->setChangePasswordKey( $random );
                $user->persist();

                $mailQueue->addAssignVar( 'name', $user->getName() );
                $mailQueue->addAssignVar( 'mail', $user->getEmail());
                $mailQueue->addAssignVar( 'token', $random );
                $mailQueue->addAssignVar( 'confirmation_link', $this->startup->getUrlApp().'/change-password/'.$user->getEmail().'/'.$random );
                
                $mailQueue->addMailField( 'to_name', $user->getName() );
                $mailQueue->addMailField( 'to_address', $user->getEmail() );
                $mailQueue->addMailField( 'template', 'user_pass_change' );
                $mailQueue->addMailField( 'subject', $this->session->config['web_name'].' - '.$this->lang['SECURITY_FORGOT_PASS'] );
                $mailQueue->addMailField( 'pre_header', $this->lang['SECURITY_FORGOT_PASS'] );
                $mailQueue->addMailField( 'token', $random );
                $mailQueue->persist();

                return $this->twig->render('web/default/common/show_message.html.twig', array(
                    'section' => $this->lang['SECURITY'],
                    'alert_type' => 'success',
                    'title' => $this->lang['SECURITY_FORGOT_PASS'],
                    'message' => $this->lang['SECURITY_FORGOT_PASS_MAIL'],
                    'redirect_wait' => '10000',
                    'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                ));
            }
        }

        return $this->twig->render('web/default/security/forgotPassForm.html.twig', array(
            'data' => $data,
            'errors' => $error
        ));
    }

    /**
     * @Route('/change-password/{email}/{token}', name='change_password')
     */
    public function changePasswordAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/securityViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'securityViewController '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $data = array(
            'email' => ( isset( $_POST['email']) )? $_POST['email'] : $vars['email'],
            'token' => ( isset( $_POST['token']) )? $_POST['token'] : $vars['token'],
            'password' => ( isset( $_POST['new-password']) )? $_POST['new-password'] : '',
            'password_second' => ( isset( $_POST['new-password-confirm']) )? $_POST['new-password-confirm'] : '',
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
        );

        $error = array();

        if ( $data['submit'] )
        {
//$txt = 'submit'.PHP_EOL; fwrite($this->myfile, $txt);

            if (empty($data['password'])) {
                $error[] = $this->lang['ERR_PASSWORD_NEEDED'];
            } else if ( strlen($data['password']) < 3 ) {
                $error[] = sprintf($this->lang['ERR_PASSWORD_SHORT'], '3');
            } else if ( strlen($data['password']) > 100 ) {
                $error[] = sprintf($this->lang['ERR_PASSWORD_LONG'], '100');
            } else if ( $data['password'] != $data['password_second']){
                $error[] = $this->lang['ERR_PASSWORD_NOT_MATCH'];
            }
            if ( !sizeof( $error ) )
            {
//$txt = 'texto ('.$data['email'].')'.PHP_EOL; fwrite($this->myfile, $txt);

                if ( !$user->getRegByEmail( $data['email'] ) || $data['token'] != $user->getChangePasswordKey() )
                {
                    return $this->twig->render('web/default/common/show_message.html.twig', array(
                        'section' => $this->lang['SECURITY'],
                        'alert_type' => 'danger',
                        'title' => $this->lang['SECURITY_CHANGE_PASSWORD'],
                        'message' => $this->lang['ERR_SECURITY_NO_USERNAME_EMAIL_USER'],
                        'redirect_wait' => '4000',
                        'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                    ));
                }

                $user->setPassword( password_hash($data['password'], PASSWORD_BCRYPT, $this->crypt_options) );
                $user->persist();

                $mailQueue->addAssignVar( 'name', $user->getName() );
                $mailQueue->addAssignVar( 'mail', $user->getEMail());

                $now = (new \DateTime('now', new \DateTimeZone($_ENV['time_zone'])));

                $mailQueue->setSend( $now );
                $mailQueue->setPriority('3');
                $mailQueue->setFromAddress( $this->session->config['email_system_address'] );
                $mailQueue->setFromName( $this->session->config['email_system_name'] );
                
                $mailQueue->setToName( $user->getName() );
                $mailQueue->setLocale($user->getLocale());
                $mailQueue->setToAddress( $user->getEmail() );

                $mailQueue->setTemplate( 'user_pass_change' );
                $mailQueue->setSubject( $this->session->config['web_name'].' - '.$this->lang['SECURITY_FORGOT_PASS'] );
                $mailQueue->setPreheader( $this->lang['SECURITY_FORGOT_PASS'] );
                
                $mailQueue->persist();
                
                return $this->twig->render('web/default/common/show_message.html.twig', array(
                    'section' => $this->lang['SECURITY'],
                    'alert_type' => 'success',
                    'title' => $this->lang['SECURITY_CHANGE_PASSWORD'],
                    'message' => $this->lang['SECURITY_CHANGE_PASSWORD_OK'],
                    'redirect_wait' => '6000',
                    'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                ));
            }
        }
        else
        {
            // Comes from email link
            // if it is correct let's show the form
            if ( $data['email'] == '' or $data['token'] == '' )
            {
                return $this->twig->render('web/default/common/show_message.html.twig', array(
                    'section' => $this->lang['SECURITY'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['SECURITY_CHANGE_PASSWORD'],
                    'message' => $this->lang['ERR_SECURITY_CONTACT_ADMIN'],
                    'redirect_wait' => '6000',
                    'redirect' => '/'.$this->lang['WEB_LOGIN_LINK'],
                ));
            }
        }
        
        return $this->twig->render('web/default/security/changePassForm.html.twig', array(
                'data' => $data,
                'errors' => $error
        ));
    }

    /**
     * @Route('/unsubscribe/{email}/{token}', name='unsubscribe')
     */
    public function unsubscribeAction( $vars )
    {
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/securityViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

$txt = '====================== '.__METHOD__.' start '.$now->format('Y-m-d H:i:s').' ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lead = new leadController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lead_fair = new leadFairController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $data = array(
                        'email' => ( isset( $_POST['email']) )? $_POST['email'] : $vars['email'],
                        'token' => ( isset( $_POST['token']) )? $_POST['token'] : $vars['token'],
                        'submit' => ( isset( $_POST['btn_submit'] ) ) ? true : false,
        );

//$txt = 'POST ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Data ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( $data['email'] == '' or $data['token'] == '' )
        {
$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
fclose($this->myfile);
            return $this->twig->render('web/default/common/show_message.html.twig', array(
                'section' => $this->lang['SECURITY'],
                'alert_type' => 'danger',
                'title' => $this->lang['SECURITY_UNSUBSCRIBE'],
                'message' => $this->lang['ERR_SECURITY_UNSUBSCRIBE_CREDENTIALS'],
                'redirect_wait' => '6000',
                'redirect' => '/',
            ));
        }

//$txt = 'Email received ('.$data['email'].')'.PHP_EOL; fwrite($this->myfile, $txt);
        $found = false;

        if ( $user->getRegByEmail( $data['email'] ) )
        {
$txt = 'Is a user ->'.$user->getId().PHP_EOL; fwrite($this->myfile, $txt);
            $found = true;

            $name = $user->getName();
            $email = $user->getEmail();
            $locale = $user->getLocale();

            $user->setActivationKey( '' );
            $user->setActive( '0' );
            $user->persist();
        }
        if ( $lead->getRegByEmail( $data['email'] ) )
        {
$txt = 'Is a lead ->'.$lead->getId().PHP_EOL; fwrite($this->myfile, $txt);
            $found = true;

            $name = $lead->getName();
            $email = $lead->getEmail();
            $locale = $lead->getLocale();

            $lead->setSendEmails( '0' );
            $lead->persist();
        }
        if ( $lead_fair->getRegByEmail( $data['email'] ) )
        {
$txt = 'Is a lead fair ->'.$lead_fair->getId().PHP_EOL; fwrite($this->myfile, $txt);
            $found = true;

            $name = $lead_fair->getName();
            $email = $lead_fair->getEmail();
            $locale = $lead_fair->getLocale();

            $lead_fair->setSendEmails( '0' );
            $lead_fair->persist();
        }

        if ( $found )
        {
$txt = 'Found ->'.$name.' '.$email.PHP_EOL; fwrite($this->myfile, $txt);
            $this->session->setLanguageCode2a( $locale );

            $lang_class = new lang( $_ENV, $this->logger, $this->logger_err, $this->startup, $this->db, $this->utils, $this->session );
            $this->lang = $lang_class->getLangTexts();

            $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

            $mailQueue->addAssignVar( 'name', $name );
            $mailQueue->addAssignVar( 'mail', $email );
            $mailQueue->setLocale( $locale );
            $mailQueue->addAssignVar( 'token', '' );

            $mailQueue->addMailField( 'to_name', $name );
            $mailQueue->addMailField( 'to_address', $email );
            $mailQueue->addMailField( 'template', 'user_de_activated' );
            $mailQueue->addMailField( 'subject', $this->session->config['web_name'].' - '.$this->lang['MAIL_DE_ACTIVATION_ACCOUNT_SUBJECT'] );
            $mailQueue->addMailField( 'pre_header', $this->lang['MAIL_DE_ACTIVATION_ACCOUNT_PREHEADER'] );
            $mailQueue->addMailField( 'token', '' );
            $mailQueue->persist();

$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
fclose($this->myfile);
            return $this->twig->render('web/default/common/show_message.html.twig', array(
                'section' => $this->lang['SECURITY'],
                'alert_type' => 'danger',
                'title' => $this->lang['SECURITY_UNSUBSCRIBE'],
                'message' => $this->lang['SECURITY_UNSUBSCRIBE_DONE'],
                'redirect_wait' => '10000',
                'redirect' => '/',
            ));
        }
        else
        {
$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            fclose($this->myfile);
            return $this->twig->render('web/default/common/show_message.html.twig', array(
                'section' => $this->lang['SECURITY'],
                'alert_type' => 'danger',
                'title' => $this->lang['SECURITY_UNSUBSCRIBE'],
                'message' => $this->lang['ERR_SECURITY_NO_USERNAME_EMAIL_USER'],
                'redirect_wait' => '6000',
                'redirect' => '/',
            ));
        }
    }
}
