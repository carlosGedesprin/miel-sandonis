<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\accountController;
use \src\controller\entity\userController;
use \src\controller\entity\userProfileController;
use \src\controller\entity\userNotesController;
use \src\controller\entity\userRoleController;

use \src\controller\entity\mailQueueController;
use \src\controller\entity\langController;

use DateTime;
use DateTimeZone;

class userEditViewController extends baseViewController
{
    private $crypt_options = array(
        'cost' => 12,
    );

    private $list_filters = array(
                                'name' => array(
													'type' => 'text',
													'caption' => '',
													'placeholder' => '',
													'width' => '0',	// if 0 uses the rest of the row
													'value' => '',
													'value_previous' => '',
                                ),
                                'account' => array(
                                                    'type' => 'select',
                                                    'caption' => '',
                                                    'placeholder' => '',
                                                    'width' => '0',	// if 0 uses the rest of the row
                                                    'value' => '',
                                                    'value_previous' => '',
                                                    'chain_childs' => '',
                                                    'options' => '',
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
        'agent'      => 5, 
        'integrator' => 6, 
    );

    private $folder = 'app';

    
    /**
     * @Route('/app/user/edit/id', name='app_user_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
//        $this->logger->info('==============='.__METHOD__.' User to edit '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/userEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/user/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $original_user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId( ( isset( $vars['id'] ) )? $vars['id'] : '0' );
        $reg->setAccount( $this->utils->request_var( 'account', '', 'ALL') );
        $reg->setRole( $this->utils->request_var( 'role', '', 'ALL') );
        $reg->setUserKey( $this->utils->request_var( 'user_key', '', 'ALL') );
        $reg->setEmail( $this->utils->request_var( 'email', '', 'ALL') );
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL', true) );
        $reg->setLastLogin( ($this->utils->request_var( 'lastlogin', '', 'ALL') == '' )? '' : \DateTime::createFromFormat('d-m-Y H:i:s', $this->utils->request_var( 'lastlogin', '', 'ALL'), new \DateTimeZone($this->session->config['time_zone'])) );
        //$reg->setLastLogin( $this->utils->request_var('lastlogin', NULL, 'ALL' ) );
        $reg->setAttempt( $this->utils->request_var( 'attempt', '0', 'ALL') );
        $reg->setLocale( $this->utils->request_var( 'locale', '', 'ALL') );
        $reg->setActivationKey( $this->utils->request_var( 'activation_key', '', 'ALL') );
        $reg->setChangePasswordKey( $this->utils->request_var( 'change_password_key', '', 'ALL') );
        $reg->setShowToStaff($this->utils->request_var( 'show_to_staff', '1', 'ALL'));
        $reg->setActive( $this->utils->request_var( 'active', '', 'ALL') );

        $userProfile = new userProfileController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $userProfile->setId( $this->utils->request_var_array( 'user_profile', 'id', '', 'ALL', true) );
        $userProfile->setUser(( isset( $vars['id'] ) )? $vars['id'] : '0' );
        $userProfile->setName($this->utils->request_var( 'name', '', 'ALL', true) );
        $userProfile->setAddress( $this->utils->request_var_array( 'user_profile', 'address', '', 'ALL', true) );
        $userProfile->setPostCode( $this->utils->request_var_array( 'user_profile', 'post_code', '', 'ALL') );
        $userProfile->setCountry( $this->utils->request_var_array( 'user_profile', 'country', '', 'ALL') );
        $userProfile->setRegion( $this->utils->request_var_array( 'user_profile', 'region', '', 'ALL') );
        $userProfile->setCity( $this->utils->request_var_array( 'user_profile', 'city', '', 'ALL') );
        $userProfile->setAltCity( $this->utils->request_var_array( 'user_profile', 'alt_city', '', 'ALL', true) );
        $userProfile->setPhone( $this->utils->request_var_array( 'user_profile', 'phone', '', 'ALL') );
        $userProfile->setPhoto( $this->utils->request_var_array( 'user_profile', 'photo', '', 'ALL', true) );

        $userNotes = new userNotesController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        
        $user_notes_form = $this->utils->request_var( 'user_notes', NULL, 'ALL', true);

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account->setName( $this->utils->request_var_array( 'new_account', 'name', '', 'ALL', true) );
        $account->setGroup( $this->utils->request_var_array( 'new_account', 'group', '', 'ALL') );
        $account->setVat( $this->utils->request_var_array( 'new_account', 'vat', '', 'ALL', true) );

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue->setToName( $reg->getName() );
        $mailQueue->setToAddress( $reg->getEmail() );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => ( $reg->getId() == '0' )? 'add' : 'edit',
            'password' => $this->utils->request_var_array( 'data', 'current-password', '', 'ALL'),
            //'password_first' => $this->utils->request_var_array( 'data', 'password_first', '', 'ALL'),
            //'password_second' => $this->utils->request_var_array( 'data', 'password_second', '', 'ALL'),
            //'email_second' => $this->utils->request_var_array( 'data', 'email_second', '', 'ALL'),
            'email_ini' => $this->utils->request_var_array( 'data', 'email_ini', '', 'ALL'),
            'account_ini'   => $this->utils->request_var_array( 'data', 'account_ini', '', 'ALL'),
            'account_options'   => '',
            'user_role_options'   => '',
// ********************** Locations start *******************************************
            'country_options' => '',
            'region_options' => '',
            'city_options' => '',
// ********************** Locations end *******************************************
            'locale_options' => '',
            'group_options'   => '',
            'active_ini' => $this->utils->request_var_array( 'data', 'active_ini', '', 'ALL'),
            'send_email' => $this->utils->request_var( 'send_email', '1', 'ALL'),
        );

        $error_ajax = array();

        if ( $reg->getId() != '0') $original_user->getRegbyId( $reg->getId() );

//$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'User profile =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user_profile, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'User notes =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user_notes, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'FILES =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_FILES, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'On start function end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        //********* File treatment start ******************
        $temp_path = APP_ROOT_PATH.$this->session->config['temp_images_folder'];
        $temp_images_url = $this->startup->getUrlApp().$this->session->config['temp_images_folder'];
        $files_folder = DOCUMENT_ROOT_PATH.'/users/';

        $files = array(
                            '1' => array (
                                            'input_id' => 'user_profile_photo',
                                            'input_name' => 'user_photo',
                                            'input_required' => false,
                                            'file_name' => '',
                                            'file_extension' => '',
                                            'file_allowed_extensions' => array('gif', 'jpeg', 'jpg', 'png', 'pdf'),
                                            'file_link' => '',
                                            'image_size_height' => '100',
                                            'image_size_width' => '100',
                                            'image_error_text' => $this->lang['ERR_USER_PROFILE_IMAGE_NEEDED'],
                                            'file_entity' => 'userProfile',
                                            'file_entity_method' => 'Photo',
                                        ),
        );

        foreach( $files as $i => $file_data )
        {
//$txt = '================= Getting file names from view start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '.................................. '.$i.' ...........................................................'.PHP_EOL; fwrite($this->myfile, $txt);
            $files[$i]['file_name'] = $this->utils->request_var( $files[$i]['input_name'].'_name', '', 'ALL');
            $files[$i]['file_extension'] = $this->utils->request_var( $files[$i]['input_name'].'_extension', '', 'ALL');
//$txt = 'File name '.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].PHP_EOL; fwrite($this->myfile, $txt);
    }
//$txt = '.....................................................................................................'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '================= Getting file names from view end =============================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        //********* File treatment end ******************

        if ( $data['submit'] )
        {
            //TODO:Carlos CSRF is not well resolved, when ajax is involved to send the whole form, startup destroys $_SESSION
            // so normal submit will find session and will not log out the user
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 5000);
            if (!$valid) {
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['USER_EDIT'],
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
                    'dom_object' => ['user_name'],
                    'msg' => $this->lang['ERR_NAME_NEEDED'],
                );
            }
            else 
            {
                if ( strlen( $reg->getName() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['user_name'],
                        'msg' => sprintf( $this->lang['ERR_NAME_SHORT'], '2' ),
                    );
                }
                else if ( strlen( $reg->getName() ) > 100 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['user_name'],
                        'msg' => sprintf( $this->lang['ERR_NAME_LONG'], '100' ),
                    );
                }
            }

            if ( $reg->getAccount() == '' )
            {
                $error_ajax[] = array (
                    'dom_object' => ['account'],
                    'msg' => $this->lang['ERR_ACCOUNT_NEEDED'],
                );
            }
            else
            {
                if ( $reg->getAccount() == '0' )
                {
                    // New account
                    if ( empty( $account->getName() ))
                    {
                        $error_ajax[] = array(
                            'dom_object' => ['account_name'],
                            'msg' => $this->lang['ERR_ACCOUNT_NAME_NEEDED'],
                        );
                    }
                    else
                    {
                        if (strlen( $account->getName() ) > 15) {
                            $error_ajax[] = array(
                                'dom_object' => ['account_name'],
                                'msg' => sprintf($this->lang['ERR_NAME_LONG'], '15'),
                            );
                        } else if (strlen( $account->getName() ) < 4) {
                            $error_ajax[] = array(
                                'dom_object' => ['account_name'],
                                'msg' => sprintf($this->lang['ERR_NAME_SHORT'], '4'),
                            );
                        } else if ($this->db->fetchOne('account', 'id', ['name' => $account->getName() ], ' OR company = \''.$reg->getName().'\'' )) {
                            $error_ajax[] = array(
                                'dom_object' => ['account_name'],
                                'msg' => $this->lang['ERR_NAME_EXISTS'],
                            );
                        }
                    }

                    if ( empty( $account->getGroup() ) ) {
                        $error_ajax[] = array(
                            'dom_object' => ['account_group'],
                            'msg' => $this->lang['ERR_GROUP_NEEDED'],
                        );
                    }
                }
            }

            if ( $data['action'] == 'add' )
            {
                if ( empty($data['password']) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['current_password'],
                        'msg' => $this->lang['ERR_PASSWORD_NEEDED'],
                    );
                }
                else
                {
                    if ( strlen($data['password']) < 3 )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['current_password'],
                            'msg' => sprintf( $this->lang['ERR_PASSWORD_SHORT'], '3' ),
                        );
                    }
                    else if ( strlen($data['password']) > 64 )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['current_password'],
                            'msg' => sprintf( $this->lang['ERR_PASSWORD_LONG'], '64' ),
                        );
                    }
                }
//                else if ( $data['password_first'] != $data['password_second'] )
//                {
//                    $error_ajax[] = array (
//                        'dom_object' => ['current_password'],
//                        'msg' => $this->lang['ERR_PASSWORD_NOT_MATCH'],
//                    );
//                }
            }
            else
            {
                if ( !empty($data['password']) )
                {
                    if ( strlen($data['password']) < 3 )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['current_password'],
                            'msg' => sprintf( $this->lang['ERR_PASSWORD_SHORT'], '3' ),
                        );
                    }
                    else if ( strlen($data['password']) > 64 )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['current_password'],
                            'msg' => sprintf( $this->lang['ERR_PASSWORD_LONG'], '64' ),
                        );
                    }
                }
            }

            if ( empty($reg->getLocale() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['user_locale'],
                    'msg' => $this->lang['ERR_USER_LOCALE_NEEDED'],
                );
            }

            $match = '/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9-.]+\.[A-Za-z]{2,20}$/';
            if ( $data['action'] == 'add' )
            {
                if ( empty( $reg->getEmail() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['user_email'],
                        'msg' => $this->lang['ERR_EMAIL_NEEDED'],
                    );
                }
                else
                {
                    if ( strlen( $reg->getEmail() ) > 255 )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['user_email'],
                            'msg' => sprintf( $this->lang['ERR_EMAIL_LONG'], '255' ),
                        );
                    }
                    else if ( strlen( $reg->getEmail() ) < 6 )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['user_email'],
                            'msg' => sprintf( $this->lang['ERR_EMAIL_SHORT'], '7' ),
                        );
                    }
                    if (!preg_match($match, $reg->getEmail() ))
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['user_email'],
                            'msg' => $this->lang['ERR_EMAIL_BAD'],
                        );
                    }
                    else if ( $this->db->fetchOne( $reg->getTableName(), 'id', ['email' => $reg->getEmail() ], ' AND id <> '.$reg->getId() ) )
                    {
                    // Check if email not exists already
                        $error_ajax[] = array (
                            'dom_object' => ['user_email'],
                            'msg' => $this->lang['ERR_EMAIL_EXISTS'],
                        );
                    }
                }
            }
            else
            {
                if ( $reg->getEmail() != $data['email_ini'] )
                {
                    if (!preg_match($match, $reg->getEmail() ) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['user_email'],
                            'msg' => $this->lang['ERR_EMAIL_BAD'],
                        );
                    }
                    else if ( $this->db->fetchOne( $reg->getTableName(), 'id', ['email' => $reg->getEmail()], ' AND id <> '.$reg->getId() ) )
                    {
                        // Check if email not exists already
                        $error_ajax[] = array (
                            'dom_object' => ['user_email'],
                            'msg' => $this->lang['ERR_EMAIL_EXISTS'],
                        );
                    }
                }
            }

            if( $reg->getActive() == '' )
            {
                $error_ajax[] = array (
                    'dom_object' => ['user_active'],
                    'msg' => $this->lang['ERR_ACTIVE_NEEDED'],
                );
            }
/*
            if ( empty($user_profile['address']) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['user_profile_address'],
                    'msg' => $this->lang['ERR_ADDRESS_NEEDED'],
                );   
            }
            else
            {
                if ( strlen($user_profile['address']) < 4 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['user_profile_address'],
                        'msg' => sprintf( $this->lang['ERR_ADDRESS_SHORT'], '4' ),
                    );
                }
                else if ( strlen($user_profile['address']) > 255 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['user_profile_address'],
                        'msg' => sprintf($this->lang['ERR_ADDRESS_LONG'], '255'),
                    );
                }
            }
*/
/*
            if ( empty($user_profile['post_code']) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['user_profile_post_code'],
                    'msg' => $this->lang['ERR_POST_CODE_NEEDED'],
                );   
            }
            else
            {
                if ( strlen($user_profile['post_code']) > 10 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['user_profile_post_code'],
                        'msg' => sprintf( $this->lang['ERR_POST_CODE_LONG'], '10' ),
                    );
                }
                else if ( strlen($user_profile['post_code']) < 4 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['user_profile_post_code'],
                        'msg' => sprintf( $this->lang['ERR_POST_CODE_SHORT'], '4' ),
                    );
                }
            }   
*/
// ********************** Locations start *******************************************
/*
            if ( empty($user_profile['country']) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['country'],
                    'msg' => $this->lang['ERR_COUNTRY_NEEDED'],
                );
            }

            if ( empty($user_profile['region']) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['region'],
                    'msg' => $this->lang['ERR_REGION_NEEDED'],
                );
            }
            else
            {
                if ( empty($user_profile['city']) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['city'],
                        'msg' => $this->lang['ERR_CITY_NEEDED'],
                    );
                }
                elseif ( $user_profile['city'] == '-' && empty($user_profile['alt_city']) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['alt_city'],
                        'msg' => $this->lang['ERR_CITY_NEEDED'],
                    );
                }
            }
*/
// ********************** Locations end *******************************************
/*
            if ( empty( $userProfile->getPhone() ))
            {
                $error_ajax[] = array (
                    'dom_object' => ['user_profile_phone'],
                    'msg' => $this->lang['ERR_PHONE_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $userProfile->getPhone() ) > 15 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['user_profile_phone'],
                        'msg' => sprintf($this->lang['ERR_PHONE_LONG'], '15'),
                    );
                }
            }       
*/
            if ( !empty( $reg->getActivationKey() ) )
            {
                if ( strlen( $reg->getActivationKey() ) > 10 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['user_activation_key'],
                        'msg' => sprintf($this->lang['ERR_USER_ACTIVATION_NOT_VALID'], '10'),
                    );
                }
            }

            //********* File treatment start ******************
//$txt = '================= Checking files errors and moving from view to temp start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
            if ( !empty($_FILES) )
            {
//$txt = '$_FILES not empty '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_FILES, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                foreach( $files as $i => $file_data )
                {
                    if ( !empty($_FILES['file_input_'.$i]["name"]) )
                    {
//$txt = 'File '.$i.PHP_EOL; fwrite($this->myfile, $txt);
                        $filename = basename($_FILES['file_input_'.$i]["name"]);
                        $filename = pathinfo($filename);
//$txt = 'Image pathinfo properties '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($filename, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        $time = (new DateTime("now", new DateTimeZone($this->session->config['time_zone'])))->format('YmdHis');

                        $files[$i]['file_name'] = $filename['filename'].'_'.$time.'_'.$i;
                        $files[$i]['file_extension'] = strtolower($filename['extension']);
//$txt = 'File '.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].PHP_EOL; fwrite($this->myfile, $txt);

                        if ( in_array($files[$i]['file_extension'], $files[$i]['file_allowed_extensions']) )
                        {
                            $file_size = $_FILES['file_input_'.$i]['size'];

//$txt = 'File '.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].' size '.$file_size.PHP_EOL; fwrite($this->myfile, $txt);
                            if ( $file_size < $this->session->config['max_size_file_upload'] )
                            {
                                // If image resize it
                                $file = $_FILES['file_input_'.$i]["tmp_name"];
                                $imgProperties = getimagesize($file);
//$txt = 'Image getimagesize properties '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($imgProperties, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
/*
 * https://www.cluemediator.com/resize-an-image-using-the-gd-library-in-php
 * https://code-boxx.com/resize-images-php/
                                if ( str_contains( $imgProperties['mime'], 'image' ) )
                                {
                                    $file_type = $imgProperties[2];

                                    if( $file_type == IMAGETYPE_JPEG )
                                    {
                                        $source = imagecreatefromjpeg( $file );
                                        $resizeImg = $this->utils->image_resize( $source, $imgProperties[0], $imgProperties[1], $files[$i]['image_size_width'], $files[$i]['image_size_height']);
                                        imagejpeg($resizeImg,$pathToThumbs.$imageName);
                                    }
                                    elseif ($img_type == IMAGETYPE_PNG ) {
                                        $source = imagecreatefrompng($image);

                                        $resizeImg = image_resize($source,$imgProperties[0],$imgProperties[1]);
                                        imagepng($resizeImg,$pathToThumbs.$imageName);
                                    }
                                    elseif ($img_type == IMAGETYPE_GIF ) {
                                        $source = imagecreatefromgif($image);
                                        $resizeImg = image_resize($source,$imgProperties[0],$imgProperties[1]);
                                        imagegif($resizeImg,$pathToThumbs.$imageName);
                                    }

                                }
*/
                                $tempFilePath = $temp_path.$files[$i]['file_name'].'.'.$files[$i]['file_extension'];

                                $image_extensions = array('gif', 'jpeg', 'jpg', 'png');

                                if ( move_uploaded_file($_FILES['file_input_'.$i]["tmp_name"], $tempFilePath) )
                                {
//$txt = 'File moved to temp as '.$tempFilePath.PHP_EOL; fwrite($this->myfile, $txt);
                                }
                                else
                                {
//$txt = 'File NOT moved to temp as '.$tempFilePath.PHP_EOL; fwrite($this->myfile, $txt);
                                }
                            }
                            else
                            {
//$txt = 'File too big'.PHP_EOL; fwrite($this->myfile, $txt);
                                $error_ajax[] = array (
                                    'dom_object' => ['image-holder-'.$i],
                                    'msg' => sprintf($this->lang['ERR_FILE_TOO_BIG'], ($this->session->config['max_size_file_upload'] / 1000000)),
                                );
                            }
                        }
                        else
                        {
//$txt = 'File extension wrong'.PHP_EOL; fwrite($this->myfile, $txt);
                            $error_ajax[] = array (
                                'dom_object' => ['image-holder-'.$i],
                                'msg' => $this->lang['ERR_FILE_ONLY_IMAGES_PDF'],
                            );
                        }
                    }
                }
            }

            foreach( $files as $i => $file_data )
            {
                if ( $files[$i]['input_required'] && $files[$i]['file_name'] == '' )
                {
//$txt = 'File required error.'.PHP_EOL; fwrite($this->myfile, $txt);
                    $error_ajax[] = array (
                        'dom_object' => ['image-holder-'.$i],
                        'msg' => $files[$i]['image_error_text'],
                    );
                }
            }
//$txt = '================= Checking images errors and moving to temp end =============================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
            //********* File treatment end ******************

            if ( !sizeof( $error_ajax ) )
            {
                // Fields with special treatment
                // ********************** Locations start *******************************************
                if ( $userProfile->getCity() == '-') $userProfile->setCity('0'); // 0 means there is an alt city
                // ********************** Locations end *******************************************

                //********* File treatment start ******************
//$txt = '================= Moving images from temp to destiny files folder start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                foreach( $files as $i => $file_data )
                {
//$txt = 'Field_files index ('.$i.')'.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $files[$i]['file_name'] != '' )
                    {
//$txt = 'File name field_files '.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].PHP_EOL; fwrite($this->myfile, $txt);
                        $time = (new DateTime("now", new DateTimeZone($this->session->config['time_zone'])))->format('YmdHis') + $i;

                        $file_on_temp = $temp_path.$files[$i]['file_name'].'.'.$files[$i]['file_extension'];
                        $new_file_name = $reg->getTableName().'_'.$reg->getId().'_'.$time.'.'.$files[$i]['file_extension'];
                        $file_on_destiny = $files_folder.$new_file_name;
//$txt = 'File paths '.$file_on_temp.' -> '.$file_on_destiny.PHP_EOL; fwrite($this->myfile, $txt);

                        if ( file_exists( $file_on_temp ) )
                        {
//$txt = 'File exists in temp'.PHP_EOL; fwrite($this->myfile, $txt);
                            if ( file_exists( $file_on_destiny ) )
                            {
//$txt = 'File exists in files folder --> unlink'.PHP_EOL; fwrite($this->myfile, $txt);
                                unlink( $file_on_destiny );
                            }
/*
                            $original_image = $files_folder.${$files[$i]['file_array_name']}[$files[$i]['file_array_field']];
//$txt = 'Original file in files folder is: '.$original_image.PHP_EOL; fwrite($this->myfile, $txt);
                            if ( file_exists( $original_image ) && !is_dir( $original_image) )
                            {
//$txt = 'File original exists in files folder; '.$original_image.' --> unlink'.PHP_EOL; fwrite($this->myfile, $txt);
                                unlink($original_image);
                            }
*/
                            if ( copy( $file_on_temp, $file_on_destiny ) )
                            {
                                // Delete temp file
                                unlink( $file_on_temp );

                                $method = 'set'.$files[$i]['file_entity_method'];
//$txt = 'Populating object "'.$files[$i]['file_entity'].'" field "'.$method.'" with ('.$new_file_name.')'.PHP_EOL; fwrite($this->myfile, $txt);
                                ${$files[$i]['file_entity']}->$method( $new_file_name );
//                                ${$files[$i]['file_array_name']}[$files[$i]['file_array_field']] = $new_file_name;
//$txt = 'File moved from '.$file_on_temp.' to --> '.$file_on_destiny.PHP_EOL; fwrite($this->myfile, $txt);
                            }
                            else
                            {
//$txt = 'File NOT moved from '.$file_on_temp.' to --> '.$file_on_destiny.PHP_EOL; fwrite($this->myfile, $txt);
                            } // copy($file_on_temp, $file_on_destiny)
                        } // file_exists( $file_on_temp
                    } // $files[$i]['file_name'] != ''
                } // foreach( $files as $i => $file_data )
//$txt = '================= Moving images from temp to files destiny folder end =============================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                //********* File treatment end ******************

                if ( $data['action'] == 'add' )
                {
//$txt = '========== ADD =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->setPassword( password_hash($data['password'], PASSWORD_BCRYPT, $this->crypt_options) );
                    $reg->setlocale( ( empty( $reg->getLocale() ) )? $this->utils->getDefaultLang() : $reg->getLocale() );
                    $reg->setLastLogin( '' );

                    if ( $this->session->config['verify_account'] )
                    {
                        $random = base64_encode( random_bytes(5) );
                        $random = str_replace( '/' , '$' , $random);

                        $reg->setActivationKey( $random );
                        $reg->setActive( '0' );
                    }
                    else
                    {
                        $reg->setActivationKey('');
                        $reg->setActive( '1' );
                    }

//                    if ( $reg->getAccount() == '0') $reg->setAccount('3');  // Staff temporarly if new account

//$txt = 'User to DB '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();

                    $reg->setUserKey( md5( $reg->getId().$reg->getEmail() ) );
                    $reg->persistORL();
//$txt = 'User =========== '.$reg->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    // Create the profile
                    $userProfile->setUser( $reg->getId() );

//$txt = 'User profile to DB '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user_profile, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $userProfile->persistORL();

                    // Create the notes record
                    foreach ($user_notes_form as $note_key => $user_note_form)
                    {
                        if ( !empty( $user_note_form['notes'] ) ) 
                        {
                            $userNotes->setId('');
                            
                            $userNotes->setUser( $reg->getId() );
                            $userNotes->setGroup( $user_note_form['group'] );
                            $userNotes->setNotes( $user_note_form['notes'] );
                            
//$txt = 'User note to DB '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($userNotes->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                            
                            $userNotes->persistORL();
                        
                            unset($user_notes_form[$note_key]);

                            $note_key = $userNotes->getId();
                            $user_notes_form[$note_key]['user'] = $userNotes->getUser();
                            $user_notes_form[$note_key]['group'] = $userNotes->getGroup();
                            $user_notes_form[$note_key]['notes'] = $userNotes->getNotes();
//$txt = 'User note to DB Access-me '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user_note_form, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        }
                        else
                        {
                            unset($user_notes_form[$note_key]);
                        }
                    }

//$txt = 'Account create? '.$reg->getAccount().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $reg->getAccount() == '0' )
                    {
//$txt = 'Create account '.PHP_EOL; fwrite($this->myfile, $txt);
                        $account->createAccount(
                            $account->getName(),
                            $reg->getEmail(),
                            $reg->getLocale(),
                            $account->getName(),
                            $userProfile->getAddress(),
                            $userProfile->getPostCode(),
                            $userProfile->getCountry(),
                            $userProfile->getRegion(),
                            $userProfile->getCity(),
                            $userProfile->getAltCity(),
                            $userProfile->getPhone(),
                            $account->getVat(),
                            $account->getGroup(),
                            ((in_array($account->getGroup(), [GROUP_SUPER_ADMIN, GROUP_ADMIN, GROUP_STAFF]))? '0' : '1'), // Show to staff
                            '',
                            '',
                        );
//$txt = 'Account created '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        $reg->setAccount( $account->getId() );
                        $reg->persistORL();
                    }

                    if ( $data['send_email'] == '1' )
                    {
                        $reg->send_welcome_email();

                        if ( $this->session->config['verify_account'] )
                        {
                            //$reg->send_activation_email();
                        }
                    }
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);

//                    if ( $account['id'] != $data['account_ini'] )
//                    {
//                        $reg->getAccount() = $account['id'];
//                    }

                    // If password is empty the password is not touched
                    if ( !empty($data['password']) )
                    {
                        // Encoding the password
                        $reg->setPassword( password_hash($data['password'], PASSWORD_BCRYPT, $this->crypt_options) );
//$txt = 'Password changed ************************************'.PHP_EOL; fwrite($this->myfile, $txt);
                    }
                    else
                    {
                        $reg->setPassword( $reg->getUserPassword( $reg->getId() ) );
                    }

                    if ( $reg->getActive() == '1' && $reg->getActive() != $data['active_ini'] )
                    {
                        $reg->setActivationKey( '' );

                        $account->getRegbyId( $reg->getAccount() );
                        if ( $account->getMainUser() == $reg->getId() )
                        {
                            $account->setActive( '1' );
                            $account->persistORL();
                        }
                    }

                    // If email changes we do it all again
                    if ( $reg->getEmail() != $data['email_ini'] )
                    {
                        if ( $this->session->config['verify_account'] )
                        {
                            $random = base64_encode( random_bytes(5) );
                            $random = str_replace( '/' , '$' , $random);

                            $reg->setActivationKey( $random );
                            $reg->setActive( '0' );
                        }
                        else
                        {
                            $reg->setActivationKey('');
                            $reg->setActive( '1' );
                        }
                    }

                    $reg->setLastLogin( $original_user->getLastlogin() );

//$txt = '========== Before flushing to database ===================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'User =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();

//$txt = 'User profile =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user_profile, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $userProfile->persistORL();
                    
                    // Delete and create the notes record
//$txt = 'User notes =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user_notes_form, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    foreach ($user_notes_form as $note_key => $user_note_form)
                    {
//$txt = 'User note ==========('.$note_key.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user_note_form, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        $userNotes->setId('');

                        if ( is_numeric( $note_key )  ) 
                        {
                            $userNotes->getRegbyId( $note_key );
                        }
 
                        if ( empty( $user_note_form['notes'] ) ) {
//$txt = 'User note deleted ==========> ('.$userNotes->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
                            $userNotes->delete();
                            unset($user_notes_form[$note_key]);
                        }
                        else 
                        {
                            $userNotes->setUser( $reg->getId() );
                            $userNotes->setGroup( $user_note_form['group'] );
                            $userNotes->setNotes( $user_note_form['notes'] );
//$txt = 'User note to DB ==========('.$note_key.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($userNotes->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                            $userNotes->persistORL();

                            unset($user_notes_form[$note_key]);

                            $note_key = $userNotes->getId();
                            $user_notes_form[$note_key]['user'] = $userNotes->getUser();
                            $user_notes_form[$note_key]['group'] = $userNotes->getGroup();
                            $user_notes_form[$note_key]['notes'] = $userNotes->getNotes();
//$txt = 'User note to DB Access-me ('.$note_key.')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user_note_form, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        }
                    }

                    if ( $reg->getEmail() != $data['email_ini'] )
                    {
                        $reg->send_welcome_email();

                        if ( $this->session->config['verify_account'] )
                        {
                            //$reg->send_activation_email();
                        }
                    }
                }

                // Call api endpoint
                $this->utils->edit_user_api( $reg->getReg(), 'edit' );
                $this->utils->edit_user_profile_api( $userProfile->getReg(), 'edit' );
                if ( sizeof( $user_notes_form ) ) $this->utils->edit_user_notes_api( $user_notes_form, 'edit' );

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['USER_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['USERS_LINK'];
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found

                // Renew CSRF - It gives issues with ajax and session destroy in startup
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);

//$txt = '---> Error (location)'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($error_ajax, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                $response['status'] = 'KO';
                foreach( $error_ajax as $key => $value )
                {
                    $response['errors'][] = $value;
                }
//$txt = 'Response charge on error '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE));

                // Send errors to be displayed
                echo json_encode($response);
                exit();
            }
        }
        else
        {
            if ( $data['action'] == 'add' )
            {
                // new record
                if ( $this->group == GROUP_SUPER_ADMIN || $this->group == GROUP_ADMIN )
                {
                    foreach ($this->notes_group as $key => $group) 
                    {
                        $userNotes->setId( $key );
                        $userNotes->setGroup( $group );
                        $userNotes->setNotes( '' );
                        $user_notes_total[] = $userNotes->getReg();
                    }
                }
                else
                {
                    $userNotes->setId('');
                    $userNotes->setGroup( $this->group );
                    $userNotes->setNotes( '' );
                    $user_notes_total[] = $userNotes->getReg();
                }
            }
            else
            {
                // Edit record
//$txt = 'User to edit '.$item['id'].PHP_EOL; fwrite($this->myfile, $txt);
                if( $reg->getRegbyId( $reg->getId() ) )
                {
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    $data['account_ini'] = $reg->getAccount();
                    $data['email_ini'] = $reg->getEmail();
                    $data['active_ini'] = $reg->getActive();

                    $user_notes_total = array();

                    if ( $this->group == GROUP_SUPER_ADMIN || $this->group == GROUP_ADMIN )
                    {

                        foreach ($this->notes_group as $key => $group) 
                        {
                            if ( !( $userNotes->getRegbyUserAndGroup( $reg->getId(), $group ) ) )
                            {
                                $userNotes->setId( $key );
                                $userNotes->setGroup( $group );
                                $userNotes->setNotes( '' );
                            }
                            $user_notes_total[] = $userNotes->getReg();
                        }
                    }
                    else
                    {
                        if ( $userNotes->getRegbyUserAndGroup( $reg->getId(), $this->group ) )
                            $user_notes_total[] = $userNotes->getReg();
                        else
                        {
                            $userNotes->setId('');
                            $userNotes->setGroup( $this->group );
                            $userNotes->setNotes( '' );
                            $user_notes_total[] = $userNotes->getReg();
                        }
                    }

                    $userProfile->getRegByUser( $reg->getId() );
//$txt = 'User profile to edit '.$item['id'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user_profile, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    //********* File treatment start ******************
//$txt = '================= Getting file names from database and populating files array start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                    foreach( $files as $i => $file_data )
                    {
//$txt = 'Entity method for file '.$i.': '.$files[$i]['file_entity'].'->get'.$files[$i]['file_entity_method'].PHP_EOL; fwrite($this->myfile, $txt);
                        $method = 'get'.$files[$i]['file_entity_method'];
//$txt = 'Name on record ('.${$files[$i]['file_entity']}->$method().')'.PHP_EOL; fwrite($this->myfile, $txt);
                        if ( ${$files[$i]['file_entity']}->$method() != '' )
                        {
                            $file_temp = explode('.', ${$files[$i]['file_entity']}->$method());
                            $files[$i]['file_name'] = $file_temp[0];
                            $files[$i]['file_extension'] = $file_temp[1];
                            unset( $file_temp );
//$txt = 'Image name $files array '.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].PHP_EOL; fwrite($this->myfile, $txt);
                        }
                    }
//$txt = '================= Getting file names from database and populating files array end =============================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                    //********* File treatment end ******************

                    // Field with special treatment
                    // ********************** Locations start *******************************************
                    if ( $userProfile->getCity() == '0' ) $userProfile->setCity('-');
                    // ********************** Locations end *******************************************

                    //********* File treatment start ******************
//$txt = '================= Move from destiny folder to temp folder start =============================================='.PHP_EOL; fwrite($this->myfile, $txt);
                    foreach( $files as $i => $value)
                    {
//$txt = 'field_files '.$i.':'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($files[$i], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        if ( $files[$i]['file_name'] != '' )
                        {
//$txt = 'Copy image to temp ('.$i.')'.PHP_EOL; fwrite($this->myfile, $txt);
                            $destinyFilePath = $files_folder . $files[$i]['file_name'] . '.' . $files[$i]['file_extension'];
                            $tempFilePath = $temp_path . $files[$i]['file_name'] . '.' . $files[$i]['file_extension'];
//$txt = 'File paths '.$destinyFilePath.' -> '.$tempFilePath.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Source image ('.$i.') '.$destinyFilePath.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Destination image ('.$tempFilePath.')'.PHP_EOL; fwrite($this->myfile, $txt);
                            if ( file_exists( $destinyFilePath ) )
                            {
                                if ( file_exists( $tempFilePath ) )
                                {
                                    unlink( $tempFilePath );
                                }
                                if ( copy($destinyFilePath, $tempFilePath) )
                                {
//$txt = 'Copied'.PHP_EOL; fwrite($this->myfile, $txt);
                                }
                                else
                                {
//$txt = 'NOT copied'.PHP_EOL; fwrite($this->myfile, $txt);
                                }
                                $files[$i]['file_link'] = $temp_images_url.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].'?t='.time();
                            }
                            else
                            {
//$txt = 'Source image not exists'.PHP_EOL; fwrite($this->myfile, $txt);
                                $files[$i]['file_name'] = $files[$i]['file_extension'] = '';
                            }
                        } // $files[$i]['file_name'] != ''
                        else
                        {
//$txt = 'No source image on array'.PHP_EOL; fwrite($this->myfile, $txt);
                        }
//$txt = 'Image treated - '.$temp_images_url.$files[$i]['file_name'].'.'.$files[$i]['file_extension'].PHP_EOL; fwrite($this->myfile, $txt);
                    } // foreach( $files as $i => $value)
//$txt = '================= Move from user folder to temp folder end =============================================='.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                    //********* File treatment end ******************

                }
                else
                {
                    $_SESSION['alert'] = array(
                                                'type'          => 'danger',
                                                'message'       => $this->lang['ERR_USER_NOT_EXISTS'],
                                                'filters'       => $this->list_filters,
                                                'pagination'    => $this->pagination,
                    );
                    header('Location: /'.$this->folder.'/'.$this->lang['USERS_LINK']);
                    exit;
                }
            }
        }

        if ( $reg->getLastlogin() != '' ) $reg->setLastlogin( $reg->getLastlogin()->format('d-m-Y H:i:s') );

        // Account select options list
        $filter_select = '';
        $extra_select = 'ORDER BY `id`';
        $data_options_field = 'account_options';
        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        if ( $reg->getAccount() == '')
        {
            $data[$data_options_field] .= '<option value="0" selected="selected">'.$this->lang['ACCOUNT_SELECT'].'</option>';
            $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $rows = $account->getAll( $filter_select, $extra_select );
        foreach ( $rows as $row )
        {
            $data[$data_options_field] .= '<option value="'.$row['id'].'"'.(( $reg->getAccount() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].' - '.$row['id'].'</option>';
        }

// ********************** Locations start *******************************************
        // Country select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/country_profile_all.php');

        // Region select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/region_country_profile.php');

        // City select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/city_region_country_profile.php');
// ********************** Locations end *******************************************

        // User role select options list
        $filter_select = '';
        $extra_select = 'ORDER BY `id`';
        $data_options_field = 'user_role_options';
        $userRole = new userRoleController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $userRole->getAll( $filter_select, $extra_select );
        require_once(APP_ROOT_PATH.'/src/util/view_selects/user_roles.php');

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

        // account options
        //require_once(APP_ROOT_PATH.'/src/util/view_selects/user_new_account.php');

        // group options
        //require_once(APP_ROOT_PATH.'/src/util/view_selects/user_account_group.php');

//$txt = '========== Before displaying form ===================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'User profile =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user_profile, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/userForm.html.twig', array(
            'reg' => $reg->getReg(),
            'user_profile' => $userProfile->getReg(),
            'user_notes' => $user_notes_total,
            'account' => $account->getReg(),
            //********* File treatment start ******************
            'files' => $files,
            //********* File treatment end ******************
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['USERS_LINK'],
        ));
    }    
}