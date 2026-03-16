<?php

namespace  src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\accountController;
use \src\controller\entity\accountNotesController;
use \src\controller\entity\accountFundsSettingsController;
use \src\controller\entity\accountPaymentMethodController;
use src\controller\entity\langController;
use \src\controller\entity\userController;
use \src\controller\entity\userProfileController;
use \src\controller\entity\groupController;
use \src\controller\entity\mailQueueController;
use \src\controller\entity\langTextController;

use DateTime;
use DateTimeZone;
use src\controller\entity\vatTypeController;

class accountEditViewController extends baseViewController
{
    private $list_filters = array(
                                'name' => array(
													'type' => 'text',
													'caption' => '',
													'placeholder' => '',
													'width' => '0',	// if 0 uses the rest of the row
													'value' => '',
													'value_previous' => '',
                                ),
                                'active' => array(
                                                    'type' => 'select',
                                                    'caption' => '',
                                                    'placeholder' => '',
                                                    'width' => '0',	// if 0 uses the rest of the row
                                                    'value' => '',
                                                    'value_previous' => '',
                                                    'chain_childs' => '',
                                                    'options' => '',
                                ),
                                'show_to_staff' => array(
                                                    'type' => 'hidden',
                                                    'value' => '',
                                                    'value_previous' => '',
                                ),
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'id',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $notes_group = array(
        'admin'      => 2,
        'customer'   => 4,
    );

    private $folder = 'app';
   
    /**
     * @Route('/app/account/edit/id', name='app_account_edit')
     *
     * @param $vars array Params on route
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Account to edit '.$vars['id'].' | User '.$this->user.' ===================================================');
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/accountEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/account/editor';

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $reg_original = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId( ( isset( $vars['id'] ) )? $vars['id'] : '0');
        //$reg->setAccountKey( $this->utils->request_var( 'account_key', '', 'ALL') );
        $reg->setGroup( $this->utils->request_var( 'group', '', 'ALL') );
        $reg->setMainUser( $this->utils->request_var( 'main_user', '', 'ALL') );
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL', true ) );
        $reg->setCompany( $this->utils->request_var( 'company', '', 'ALL', true ) );
        $reg->setNotificationsEmail( $this->utils->request_var( 'notifications_email', '', 'ALL', true) );
        $reg->setLocale( $this->utils->request_var( 'locale', '', 'ALL', true) );
        $reg->setAddress( $this->utils->request_var( 'address', '', 'ALL', true ) );
        $reg->setPostCode( $this->utils->request_var( 'post_code', '', 'ALL') );
//********************* Locations start *******************************************
        $reg->setCountry( $this->utils->request_var( 'country', '', 'ALL') );
        $reg->setRegion( $this->utils->request_var( 'region', '', 'ALL') );
        $reg->setCity( $this->utils->request_var( 'city', '', 'ALL') );
        $reg->setAltCity( $this->utils->request_var( 'alt_city', '', 'ALL', true ) );
//********************* Locations end *******************************************
        $reg->setPhone( $this->utils->request_var( 'phone', '', 'ALL', true ) );
        $reg->setVat( $this->utils->request_var( 'vat', '', 'ALL', true ) );
        $reg->setVatType( $this->utils->request_var( 'vat_type', '', 'ALL' ) );
        $reg->setShowToStaff( $this->utils->request_var( 'show_to_staff', '1', 'ALL') );
        $reg->setAllowStaffUseCard( $this->utils->request_var( 'allow_staff_use_card', '1', 'ALL') );
        $reg->setPreferredPaymentType( $this->utils->request_var( 'preferred_payment_type', '', 'ALL') );
        $reg->setActive( $this->utils->request_var( 'active', '1', 'ALL') );

        $reg_original->getRegbyId( $reg->getId() );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user->setName( $this->utils->request_var_array( 'user', 'name', '', 'ALL', true ) );        
        $user->setEmail( $this->utils->request_var_array( 'user', 'email', '', 'ALL', true ) );
        $user->setLocale( $this->utils->request_var_array( 'user', 'locale', '', 'ALL') );

        $userProfile = new userProfileController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $userProfile->setName( $this->utils->request_var_array( 'user', 'name', '', 'ALL', true) );

        $accountNotes = new accountNotesController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        
        $account_notes_form = $this->utils->request_var( 'account_notes', NULL, 'ALL', true);

        $account_funds_settings = new accountFundsSettingsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account_funds_settings_original = new accountFundsSettingsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_funds_settings->setActive( $this->utils->request_var_array( 'account_funds_setttings', 'active', '0', 'ALL', true ) );
        $account_funds_settings->setMin( $this->utils->request_var_array( 'account_funds_setttings', 'min', '0', 'ALL', true ) );
        $account_funds_settings->setAutoFill( $this->utils->request_var_array( 'account_funds_setttings', 'autofill', '0', 'ALL', true ) );
        $account_funds_settings->setFillAmount( $this->utils->request_var_array( 'account_funds_setttings', 'fill_amount', '0', 'ALL', true ) );
        $account_funds_settings->setAccountPaymentMethod( $this->utils->request_var_array( 'account_funds_setttings', 'account_payment_method', '0', 'ALL', true ) );

        $accountPaymentMethod = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue->setToAddress( $reg->getNotificationsEmail() );
        $mailQueue->setToName( $reg->getName() );

        // Verifications
        $group = new groupController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => ( $reg->getId() == '0' )? 'add' : 'edit',
            'notifications_email_ini' => $this->utils->request_var_array( 'data', 'notifications_email_ini', '', 'ALL'),
            'group_options'   => '',
            'create_main_user' =>  $this->utils->request_var( 'create_main_user', '1', 'ALL'),
            'main_user_options'   => '',
// ********************** Locations start *******************************************
            'country_options' => '',
            'region_options' => '',
            'city_options' => '',
// ********************** Locations end *******************************************
            'vat_type_options' => '',
            'preferred_payment_type_options' => '',
            'account_payment_method_options' => '',
            'password' => $this->utils->request_var_array( 'data', 'current-password', '', 'ALL', true),
            //'password_first' => $this->utils->request_var_array( 'data', 'password_first', '', 'ALL', true ),
            //'password_second' => $this->utils->request_var_array( 'data', 'password_second', '', 'ALL', true ),
            //'email_second' => $this->utils->request_var_array( 'data', 'email_second', '', 'ALL', true ),
            'locale_options' => '',
            'user_locale_options' => '',
            'users' => array(),
            'send_email' => $this->utils->request_var( 'send_email', '1', 'ALL'),
        );

        $error_ajax = array();

//$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Vars =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Funds settings =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_funds_settings->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Notes =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_notes, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'User =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'User profile =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user_profile->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'FILES =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_FILES, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'On start function end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        if ( $data['submit'] )
        {
            //TODO:Carlos CSRF is not well resolved, when ajax is involved to send the whole form, startup destroys $_SESSION
            // so normal submit will find session and will not log out the user
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 5000);
            if (!$valid) {
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['ACCOUNT_EDIT'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */

            if ( empty( $reg->getName() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['name'],
                    'msg' => $this->lang['ERR_NAME_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getName() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => sprintf( $this->lang['ERR_NAME_SHORT'], '2' ),
                    );
                }
                else if ( strlen( $reg->getName() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => sprintf( $this->lang['ERR_NAME_LONG'], '100' ),
                    );
                }
                else
                {
                    if ( $reg->accountsWithSameName( $reg->getName(), 'id', $reg->getId() ) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['name'],
                            'msg' => $this->lang['ERR_NAME_EXISTS'],
                        );
                    }
                }
            }

            if ( empty( $reg->getGroup() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['group'],
                    'msg' => $this->lang['ERR_GROUP_NEEDED'],
                );
            }

            if ( !empty( $reg->getCompany() ) )
            {
                if ( strlen( $reg->getCompany() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['company'],
                        'msg' => sprintf( $this->lang['ERR_ACCOUNT_COMPANY_SHORT'], '2' ),
                    );
                }
                else if ( strlen( $reg->getCompany() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['company'],
                        'msg' => sprintf( $this->lang['ERR_ACCOUNT_COMPANY_LONG'], '100' ),
                    );
                }
            }
/*
            if ( $data['action'] != 'add' )
            {
                if ( empty( $reg->getMainUser() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['main_user'],
                        'msg' => $this->lang['ERR_ACCOUNT_MAIN_USER_NEEDED'],
                    );
                }
            }
*/
            $match = '/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9-.]+\.[A-Za-z]{2,20}$/';
            if ( empty( $reg->getNotificationsEmail() ))
            {
                $error_ajax[] = array (
                    'dom_object' => ['notifications_email'],
                    'msg' => $this->lang['ERR_NOTIFICATIONS_MAIL_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getNotificationsEmail() ) > 255 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['notifications_email'],
                        'msg' => sprintf( $this->lang['ERR_EMAIL_LONG'], '255' ),
                    );
                }
                else if ( strlen( $reg->getNotificationsEmail() ) < 6 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['notifications_email'],
                        'msg' => sprintf( $this->lang['ERR_EMAIL_SHORT'], '7' ),
                    );
                }
                else if (!preg_match($match, $reg->getNotificationsEmail() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['notifications_email'],
                        'msg' => $this->lang['ERR_NOTIFICATIONS_MAIL_BAD'],
                    );
                }
                else if ( $reg->accountsWithSameEmail( $reg->getNotificationsEmail(), 'id', $reg->getId() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['notifications_email'],
                        'msg' => $this->lang['ERR_NOTIFICATIONS_MAIL_EXISTS'],
                    );
                }
            }

            if ( !empty( $reg->getPhone() ) )
            {
                if ( strlen( $reg->getPhone() ) > 15 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['phone'],
                        'msg' => sprintf( $this->lang['ERR_PHONE_LONG'], '15' ),
                    );
                }
            }

            if ( !empty( $reg->getVat() ) )
            {
                if ( strlen( $reg->getVat() ) <= 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['vat'],
                        'msg' => sprintf( $this->lang['ERR_CUSTOMER_VAT_SHORT'], '2' ),
                    );
                }    
                else if ( strlen( $reg->getVat() ) > 25 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['vat'],
                        'msg' => sprintf($this->lang['ERR_CUSTOMER_VAT_LONG'], '25'),
                    );
                }
            }

            if ( empty( $reg->getVatType() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['vat_type'],
                    'msg' => $this->lang['ERR_VAT_TYPE_PERCENT_NEEDED'],
                );
            }

            if ( !empty( $reg->getAddress() ) )
            {
                if ( strlen( $reg->getAddress() ) < 4 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['address'],
                        'msg' => sprintf( $this->lang['ERR_ADDRESS_SHORT'], '4' ),
                    );
                }
                else if ( strlen( $reg->getAddress() ) > 255 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['address'],
                        'msg' => sprintf($this->lang['ERR_ADDRESS_LONG'], '255'),
                    );
                }
            }

            if ( !empty( $reg->getPostCode() ) )
            {
                if ( strlen( $reg->getPostCode() ) < 4 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['post_code'],
                        'msg' => sprintf( $this->lang['ERR_POST_CODE_SHORT'], '4' ),
                    );
                }
                else if ( strlen( $reg->getPostCode() ) > 10 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['post_code'],
                        'msg' => sprintf($this->lang['ERR_POST_CODE_LONG'], '10'),
                    );
                }
            }

// ********************** Locations start *******************************************
/*
            if ( empty( $reg->getCountry() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['country'],
                    'msg' => $this->lang['ERR_COUNTRY_NEEDED'],
                );
            }

            if ( empty( $reg->getRegion() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['region'],
                    'msg' => $this->lang['ERR_REGION_NEEDED'],
                );
            }
            else
            {
                if ( empty( $reg->getCity() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['city'],
                        'msg' => $this->lang['ERR_CITY_NEEDED'],
                    );
                }
                elseif ( $reg->getCity()  == '-' && empty( $reg->getAltCity() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['alt_city'],
                        'msg' => $this->lang['ERR_CITY_NEEDED'],
                    );
                }
            }
*/
// ********************** Locations end *******************************************

            if ( empty( $reg->getLocale() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['locale'],
                    'msg' => $this->lang['ERR_ACCOUNT_LOCALE_NEEDED'],
                );
            }

            if ( $data['action'] == 'add' )
            {
                if ( $data['create_main_user'] == '1' )
                {
                    if ( empty($data['password']) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['current_password'],
                            'msg' => $this->lang['ACCOUNT_MAIN_USER'].' - '.$this->lang['ERR_PASSWORD_NEEDED'],
                        );
                    }
                    else
                    {
                        if ( strlen($data['password']) < 3 )
                        {
                            $error_ajax[] = array (
                                'dom_object' => ['current_password'],
                                'msg' => $this->lang['ACCOUNT_MAIN_USER'].' - '.sprintf( $this->lang['ERR_PASSWORD_SHORT'], '3' ),
                            );
                        }
                        else if ( strlen($data['password']) > 64 )
                        {
                            $error_ajax[] = array (
                                'dom_object' => ['current_password'],
                                'msg' => $this->lang['ACCOUNT_MAIN_USER'].' - '.sprintf( $this->lang['ERR_PASSWORD_LONG'], '64' ),
                            );
                        }
                    }

                    if ( empty( $user->getEmail() ) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['user_email'],
                            'msg' => $this->lang['ACCOUNT_MAIN_USER'].' - '.$this->lang['ERR_EMAIL_NEEDED'],
                        );
                    }
                    else
                    {
                        if ( !preg_match( $match, $user->getEmail() ) )
                        {
                            $error_ajax[] = array (
                                'dom_object' => ['user_email'],
                                'msg' => $this->lang['ACCOUNT_MAIN_USER'].' - '.$this->lang['ERR_EMAIL_BAD'],
                            );
                        }
                        else if ( strlen( $user->getEmail() ) < 7 )
                        {
                            $error_ajax[] = array (
                                'dom_object' => ['user_email'],
                                'msg' => $this->lang['ACCOUNT_MAIN_USER'].' - '.sprintf( $this->lang['ERR_EMAIL_SHORT'], '6' ),
                            );
                        }
                        else if ( strlen( $user->getEmail() ) > 255 )
                        {
                            $error_ajax[] = array (
                                'dom_object' => ['user_email'],
                                'msg' => $this->lang['ACCOUNT_MAIN_USER'].' - '.sprintf( $this->lang['ERR_EMAIL_LONG'], '255' ),
                            );
                        }
                        elseif ( $user->usersWithSameEmail( $user->getEmail(), '' ) )
                        {
                            $error_ajax[] = array (
                                'dom_object' => ['user_email'],
                                'msg' => $this->lang['ACCOUNT_MAIN_USER'].' - '.$this->lang['ERR_EMAIL_EXISTS'],
                            );
                        }
                    }

                    if ( empty( $user->getName() ) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['user_name'],
                            'msg' => $this->lang['ACCOUNT_MAIN_USER'].' - '.$this->lang['ERR_NAME_NEEDED'],
                        );
                    }
                    else
                    {
                        if ( strlen( $user->getName() ) < 2 )
                        {
                            $error_ajax[] = array (
                                'dom_object' => ['user_name'],
                                'msg' => $this->lang['ACCOUNT_MAIN_USER'].' - '.sprintf ( $this->lang['ERR_NAME_SHORT'], '2' )
                            );
                        }
                        else if ( strlen( $user->getName() ) > 100 )
                        {
                            $error_ajax[] = array (
                                'dom_object' => ['user_name'],
                                'msg' => $this->lang['ACCOUNT_MAIN_USER'].' - '.sprintf($this->lang['ERR_NAME_LONG'], '100'),
                            );
                        }
                    }

                    if ( empty( $user->getLocale() ) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['user_locale'],
                            'msg' => $this->lang['ACCOUNT_MAIN_USER'].' - '.$this->lang['ERR_USER_LOCALE_NEEDED'],
                        );
                    }
                }
            }

//$txt = 'Errors =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($error_ajax, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            if ( !sizeof( $error_ajax ) )
            {
                // Fields with special treatment
// ********************** Locations start *******************************************
                if ( $reg->getCity() == '-') $reg->setCity('0'); // 0 means there is an alt city
// ********************** Locations end *******************************************

                if ( $data['action'] == 'add' )
                {
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->setAccountKey( md5( $reg->getName().$data['password'] ) );
                    // group -> from view
                    // main_user -> added afterwoods
                    // name -> from view
                    // company -> from view
                    // notifications_email -> from view
                    // locale -> from view
                    // address -> from view
                    // post_code -> from view
                    // country -> from view
                    // region -> from view
                    // city -> from view
                    // alt_city -> from view
                    // phone -> from view
                    // vat -> from view
                    // vat_type -> from view
                    // show_to_staff -> from view
                    // preferred_payment_type -> default
                    // coupon -> default
                    // stripe_id -> default
                    $reg->setActive( ( $this->session->config['verify_account'] )? '1' : '0' );
//$txt = 'Account to DB '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();

                    if ( $data['create_main_user'] == '1' )
                    {
                        $user->createUser(
                                            $reg->getId(),
                                            $user->getName(),
                                            ( empty( $user->getEmail() ) ) ? $reg->getNotificationsEmail() : $user->getEmail(),
                                            $data['password'],
                                            $user->getLocale(),
                            );
//$txt = 'User ========= '.$user->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        $user->setShowToStaff( $reg->getShowToStaff() );
                        $user->persistORL();
//$txt = 'User ========= '.$user->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        $userProfile->setUser( $user->getId() );
                        $userProfile->persistORL();
//$txt = 'User profile to DB '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user_profile->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        $reg->setMainUser( $user->getId() );
                        $reg->persistORL();
                    }

                    foreach ( $account_notes_form as $note_key => $account_note_form )
                    {
                        if ( !empty( $account_note_form['notes'] ) ) 
                        {
                            $accountNotes->setId('');
                            
                            $accountNotes->setAccount( $reg->getId() );
                            $accountNotes->setGroup( $account_note_form['group'] );
                            $accountNotes->setNotes( $account_note_form['notes'] );
                            
//$txt = 'Account note to DB '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($accountNotes->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                            
                            $accountNotes->persistORL();
                        
                            unset($account_notes_form[$note_key]);

                            $note_key = $accountNotes->getId();
                            $account_notes_form[$note_key]['account'] = $accountNotes->getAccount();
                            $account_notes_form[$note_key]['group'] = $accountNotes->getGroup();
                            $account_notes_form[$note_key]['notes'] = $accountNotes->getNotes();
//$txt = 'Account note to DB Access-me '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_note_form, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        }
                        else
                        {
                            unset($account_notes_form[$note_key]);
                        }
                    }

//$txt = 'Account id ========== '.$reg->getId().PHP_EOL; fwrite($this->myfile, $txt);
                    if ( !$account_funds_settings_original->getRegbyAccount( $reg->getId() ) )
                    {
//$txt = 'Account funds settings NOT found =========='.PHP_EOL; fwrite($this->myfile, $txt);
                        $account_funds_settings->setId( '' );
                        $account_funds_settings->setAccount( $reg->getId() );
                    }
                    else
                    {
//$txt = 'Account funds settings found =========='.PHP_EOL; fwrite($this->myfile, $txt);
                        $account_funds_settings->setId( $account_funds_settings_original->getId() );
                        $account_funds_settings->setAccount( $account_funds_settings_original->getAccount() );
                    }

//$txt = 'Account funds settings to DB ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_funds_settings->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $account_funds_settings->persistORL();

                    if ( $data['create_main_user'] == '1' )
                    {
                        if ( $data['send_email'] == '1' )
                        {
                            $user->send_welcome_email();

                            if ( $this->session->config['verify_account'] )
                            {
                                //$user->send_activation_email();
                            }
                        }
                    }
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->setAccountKey( $reg_original->getAccountKey() );
                    $reg->setStripeId( $reg_original->getStripeId() );

$txt = '========== Before flushing to database ===================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Account =========='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();

                    if ( !empty( $reg->getStripeId() ) )
                    {
                        $this->utils->setStripeKey( $_ENV['stripe_s'] );
                        $this->utils->updateStripeCustomer( $reg );
                    }

//$txt = 'Account notes =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_notes_form, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    foreach ( $account_notes_form as $note_key => $account_note_form )
                    {
//$txt = 'Account note ==========('.$note_key.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_note_form, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        $accountNotes->setId('');

                        if ( is_numeric( $note_key )  ) 
                        {
                            $accountNotes->getRegbyId( $note_key );
                        }
 
                        if ( empty( $account_note_form['notes'] ) ) {
//$txt = 'Account note deleted ==========> ('.$accountNotes->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
                            $accountNotes->delete();
                            unset($account_notes_form[$note_key]);
                        }
                        else 
                        {
                            $accountNotes->setAccount( $reg->getId() );
                            $accountNotes->setGroup( $account_note_form['group'] );
                            $accountNotes->setNotes( $account_note_form['notes'] );
//$txt = 'Account note to DB ==========('.$note_key.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($accountNotes->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                            $accountNotes->persistORL();

                            unset($account_notes_form[$note_key]);

                            $note_key = $accountNotes->getId();
                            $account_notes_form[$note_key]['account'] = $accountNotes->getAccount();
                            $account_notes_form[$note_key]['group'] = $accountNotes->getGroup();
                            $account_notes_form[$note_key]['notes'] = $accountNotes->getNotes();
//$txt = 'Account note to DB Access-me ('.$note_key.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_note_form, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        }
                    }

//$txt = 'Account id ========== '.$reg->getId().PHP_EOL; fwrite($this->myfile, $txt);
                    if ( !$account_funds_settings_original->getRegbyAccount( $reg->getId() ) )
                    {
//$txt = 'Account funds settings NOT found =========='.PHP_EOL; fwrite($this->myfile, $txt);
                        $account_funds_settings->setId( '' );
                        $account_funds_settings->setAccount( $reg->getId() );
                    }
                    else
                    {
//$txt = 'Account funds settings found =========='.PHP_EOL; fwrite($this->myfile, $txt);
                        $account_funds_settings->setId( $account_funds_settings_original->getId() );
                        $account_funds_settings->setAccount( $account_funds_settings_original->getAccount() );
                    }

//$txt = 'Account funds settings to DB ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_funds_settings->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $account_funds_settings->persistORL();
                }

                if ( $data['action'] == 'add' )
                {
                    if ( $data['create_main_user'] == '1' )
                    {
                        $this->utils->edit_user_api( $user->getReg(), 'edit');
                        $this->utils->edit_user_profile_api( $userProfile->getReg(), 'edit' );
                    }
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['ACCOUNT_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['ACCOUNTS_LINK'];
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found

                // Renew CSRF - It gives issues with ajax and session destroy in startup
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);

                $response['status'] = 'KO';
                foreach( $error_ajax as $key => $value )
                {
                    $response['errors'][] = $value;
                }
//$txt = 'Errors =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response['errors'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                echo json_encode($response);
                exit();
            }
        }
        else
        {
            if ( $data['action'] == 'add' )
            {
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);

                if ( $this->group == GROUP_SUPER_ADMIN || $this->group == GROUP_ADMIN )
                {
                    foreach ( $this->notes_group as $key => $notes_group )
                    {
                        $accountNotes->setId( $key );
                        $accountNotes->setGroup( $notes_group );
                        $accountNotes->setNotes( '' );
                        $account_notes_total[] = $accountNotes->getReg();
                    }
                }
                else
                {
                    $accountNotes->setId('');
                    $accountNotes->setGroup( $this->group );
                    $accountNotes->setNotes( '' );
                    $account_notes_total[] = $accountNotes->getReg();
                }

                $account_payment_methods = NULL;
            }
            else
            {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                if ( $reg->getRegbyId( $reg->getId() ) )
                {
//$txt = 'Account to edit ====> ('.$reg->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
                    $data['notifications_email_ini'] = $reg->getNotificationsEmail();

                    $account_notes_total = array();

                    if ( $this->group == GROUP_SUPER_ADMIN || $this->group == GROUP_ADMIN )
                    {
//$txt = 'Notes group ====> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->notes_group, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        foreach ( $this->notes_group as $key => $notes_group )
                        {
                            if ( !$accountNotes->getRegbyAccountAndGroup( $reg->getId(), $notes_group ) )
                            {
                                $accountNotes->setId( $key );
                                $accountNotes->setGroup( $notes_group );
                                $accountNotes->setNotes( '' );
                            }
                            $account_notes_total[] = $accountNotes->getReg();
                        }
//$txt = 'Notes Admin ====> ('.$reg->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_notes_total, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    }
                    else
                    {
                        if ( $accountNotes->getRegbyAccountAndGroup( $reg->getId(), $this->group ) )
                            $account_notes_total[] = $accountNotes->getReg();
                        else
                        {
                            $accountNotes->setId('');
                            $accountNotes->setGroup( $this->group );
                            $accountNotes->setNotes( '' );
                            $account_notes_total[] = $accountNotes->getReg();
                        }
//$txt = 'Notes NO Admin ====> ('.$reg->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_notes_total, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    }

                    // users list
                    if ( $rows = $user->getAll( [ 'account' => $reg->getId() ], NULL ) )
                    {
//$txt = 'Users =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($rows, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Users =========='.PHP_EOL; fwrite($this->myfile, $txt);
                        foreach ( $rows as $row )
                        {
//$txt = '----------------------------------------------------'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($row, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                            $user_line = '<a href="/app/user/edit/'.$row['id'].'" target="_blank">'.$row['id'].' - '.$row['name'].' - '.$row['email'].'</a>';
                            if ( $reg->getMainUser() == $row['id'] ) $user_line .= ' -> '.$this->lang['USER_IS_MAIN'];
                            $data['users'][] = '<li>'.$user_line.'</li>';
                        }
//$txt = 'Users on data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data['users'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    }

                    $account_funds_settings->getRegbyAccount( $reg->getId() );

                    $account_payment_methods = $accountPaymentMethod->getAll( ['account' => $reg->getId()] );

                    // Field with special treatment
// ********************** Locations start *******************************************
                    if ( $reg->getCity() == '0' ) $reg->setCity('-');
// ********************** Locations end *******************************************
                }
                else
                {
                    $_SESSION['alert'] = array(
                                                'type'          => 'danger',
                                                'message'       => $this->lang['ERR_ACCOUNT_NOT_EXISTS'],
                                                'filters'       => $this->list_filters,
                                                'pagination'    => $this->pagination,
                    );
                    header('Location: /'.$this->folder.'/'.$this->lang['ACCOUNTS_LINK']);
                    exit();
                }
//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            }
        }
// ********************** Locations start *******************************************
        // Country select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/country_all.php');

        // Region select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/region_country.php');

        // City select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/city_region_country.php');
// ********************** Locations end *******************************************

        // Group select options list
        $filter_select = ( in_array($this->group, [GROUP_SUPER_ADMIN, GROUP_ADMIN]) )? NULL : ['show_to_staff' => '1'];
        $extra_select = 'ORDER BY `name`';
        $data_options_field = 'group_options';
        $rows = $group->getAll( $filter_select, $extra_select );
        require_once(APP_ROOT_PATH.'/src/util/view_selects/groups.php');

        // VAT type select options list
        $filter_select = ['active' => '1'];
        $extra_select = 'ORDER BY `name`';
        $data_options_field = 'vat_type_options';
        $vat_type = new vatTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $vat_type->getAll( $filter_select, $extra_select );
        if ( empty( $reg->getVatType() ) )
        {
            $data[$data_options_field] .= '<option value="">'.$this->lang['SELECT'].'</option>';
            $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        foreach ( $rows as $row )
        {
            $data[$data_options_field] .= '<option value="'.$row['id'].'"'.(($reg->getVatType() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].' ('.(floatval( $row['percent'] ) / 100 ).'%)</option>';
        }

        // Locale select options list
        $filter_select = ['active' => '1'];
        $extra_select = 'ORDER BY `id`';
        $data_options_field = 'locale_options';
        $lang = new langController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        if ( $reg->getLocale() == '')
        {
            $data[$data_options_field] .= '<option value="0" selected="selected">'.$this->lang['LANG_SELECT'].'</option>';
            $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $rows = $lang->getAll( $filter_select, $extra_select );
        foreach ( $rows as $row )
        {
            $lang_name = $this->utils->getLangName($row['code_2a'], $this->session->getLanguageCode2a());
            $data[$data_options_field] .= '<option value="'.$row['code_2a'].'"'.(( $reg->getLocale() == $row['code_2a'])? ' selected="selected" ' : '').'>'.$lang_name.'</option>';
        }

        // mainuser options
        $filter_select = array(
                            'account' => $reg->getId()
        );
        $extra_select = 'ORDER BY `name`';
        $data_options_field = 'main_user_options';
        $rows = $user->getAll( $filter_select, $extra_select );
        require_once(APP_ROOT_PATH.'/src/util/view_selects/main_users.php');

        // User locale options
        $filter_select = ['active' => '1'];
        $extra_select = 'ORDER BY `id`';
        $data_options_field = 'user_locale_options';
        $user_lang = new langController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        if ( $user->getLocale() == '')
        {
            $data[$data_options_field] .= '<option value="0" selected="selected">'.$this->lang['LANG_SELECT'].'</option>';
            $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $rows = $lang->getAll( $filter_select, $extra_select );
        foreach ( $rows as $row )
        {
            $lang_name = $this->utils->getLangName($row['code_2a'], $this->session->getLanguageCode2a());
            $data[$data_options_field] .= '<option value="'.$row['code_2a'].'"'.(( $user->getLocale() == $row['code_2a'])? ' selected="selected" ' : '').'>'.$lang_name.'</option>';
        }

        // Preferred Payment Type select options list
        if ( $account->getPreferredPaymentType() == '' )
        {
            $data['preferred_payment_type_options'] .= '<option value="">'.$this->lang['LANG_SELECT'].'</option>';
            $data['preferred_payment_type_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $data['preferred_payment_type_options'] .= '<option value="'.PAYMENT_TYPE_STRIPE.'"'.(( $reg->getPreferredPaymentType() == PAYMENT_TYPE_STRIPE) ? ' selected="selected" ' : '').'>'.$this->lang['PAYMENT_METHOD_CARD'].'</option>';
        $data['preferred_payment_type_options'] .= '<option value="'.PAYMENT_TYPE_FUNDS.'"'.(( $reg->getPreferredPaymentType() == PAYMENT_TYPE_FUNDS) ? ' selected="selected" ' : '').'>'.$this->lang['PAYMENT_METHOD_FUNDS'].'</option>';
        $data['preferred_payment_type_options'] .= '<option value="'.PAYMENT_TYPE_BANK.'"'.(( $reg->getPreferredPaymentType() == PAYMENT_TYPE_BANK) ? ' selected="selected" ' : '').'>'.$this->lang['PAYMENT_METHOD_BANK_LONG'].'</option>';

        // Payment Method select options list
        if ( $account_funds_settings->getAccountPaymentMethod() == '' )
        {
            $data['account_payment_method_options'] .= '<option value="" selected="selected"><b>'.$this->lang['PAYMENT_METHOD_SELECT'].'</b></option>';
            $data['account_payment_method_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $data['account_payment_method_options'] .= '<option value="" selected="selected"><b>'.$this->lang['ACCOUNT_FUND_PAYMENT_METHOD_SEND_MAIL'].'</b></option>';
        $data['account_payment_method_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        $filter_select = array(
            'account' => $reg->getId(),
            'active' => '1',
        );
        $extra_select = 'ORDER BY `name`';
        $account_payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $account_payment_method->getAll( $filter_select, $extra_select);
        foreach ($rows as $row) {
            $data['account_payment_method_options'] .= '<option value="' . $row['id'] . '"' . (( $account_funds_settings->getAccountPaymentMethod() == $row['id']) ? ' selected="selected" ' : '') . '>' . $row["name"] . '</option>';
        }

//$txt = '========== Before displaying form ===================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account notes =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_notes_total, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account funds settings =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_funds_settings->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/accountForm.html.twig', array(
            'reg' => $reg->getReg(),
            'account_notes' => $account_notes_total,
            'account_funds_settings' => $account_funds_settings->getReg(),
            'account_payment_methods' => $account_payment_methods,
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['ACCOUNTS_LINK'],
        ));
    }
}