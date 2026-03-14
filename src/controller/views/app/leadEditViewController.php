<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;
use src\controller\entity\langController;
use \src\controller\entity\leadController;

use src\controller\entity\accountController;
use src\controller\entity\userController;
use src\controller\entity\groupController;

use DateTime;
use DateTimeZone;

class leadEditViewController extends baseViewController
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
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'id',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'app';

    /**
     * @Route('/app/lead/edit/id', name='app_lead_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Lead process '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/leadEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/lead/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new leadController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId(( isset( $vars['id'] ) )? $vars['id'] : '0');
        $reg->setDateReg( $this->utils->request_var( 'date_reg', '', 'ALL') );
        $reg->setAccount( $this->utils->request_var( 'account', '', 'ALL') );
        $reg->setUser( $this->utils->request_var( 'user', '', 'ALL') );
        $reg->setGroup( $this->utils->request_var( 'group', '', 'ALL') );
        $reg->setUserName( $this->utils->request_var( 'username', '', 'ALL') );
        $reg->setPassword( $this->utils->request_var( 'password', '', 'ALL') );
        $reg->setEmail( $this->utils->request_var( 'email', '', 'ALL') );
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL') );
        $reg->setLocale( $this->utils->request_var( 'locale', '', 'ALL') );
        $reg->setCompany( $this->utils->request_var( 'company', '', 'ALL') );
        $reg->setAddress( $this->utils->request_var( 'address', '', 'ALL') );
        $reg->setPostCode( $this->utils->request_var( 'post_code', '', 'ALL') );
//********************* Locations start *******************************************
        $reg->setCountry( $this->utils->request_var( 'country', '', 'ALL') );
        $reg->setRegion( $this->utils->request_var( 'region', '', 'ALL') );
        $reg->setCity( $this->utils->request_var( 'city', '', 'ALL') );
        $reg->setAltCity( $this->utils->request_var( 'alt_city', '', 'ALL', true ) );
//********************* Locations end *******************************************
        $reg->setPhone( $this->utils->request_var( 'phone', '', 'ALL') );
        $reg->setSendEmails( $this->utils->request_var( 'send_emails', '1', 'ALL') );
        $reg->setActive( $this->utils->request_var( 'active', '1', 'ALL') );

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => ( isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' )? 'add' : 'edit',
            'account_options' => '',
            'user_options' => '',
            'group_options' => '',
            'locale_options' => '',
// ********************** Locations start *******************************************
            'country_options' => '',
            'region_options' => '',
            'city_options' => '',
// ********************** Locations end *******************************************
        );

        $error_ajax = array();

//$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'On start function end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        if ( $data['submit'] )
        {
            //TODO:Carlos CSRF is not well resolved, when ajax is involved to send the whole form, startup destroys $_SESSION
            // so normal submit will find session and will not log out the user
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 5000);
            if (!$valid) {
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['LEAD_EDIT'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */

            if ( empty( $reg->getGroup() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['group'],
                    'msg' => $this->lang['ERR_GROUP_NEEDED'],
                );
            }

            $match = '/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9-.]+\.[A-Za-z]{2,20}$/';
            if ( empty( $reg->getEmail() ))
            {
                $error_ajax[] = array (
                    'dom_object' => ['email'],
                    'msg' => $this->lang['ERR_MAIL_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getEmail() ) > 255 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['email'],
                        'msg' => sprintf( $this->lang['ERR_EMAIL_LONG'], '255' ),
                    );
                }
                else if ( strlen( $reg->getEmail() ) < 6 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['email'],
                        'msg' => sprintf( $this->lang['ERR_EMAIL_SHORT'], '7' ),
                    );
                }
                else if (!preg_match($match, $reg->getEmail() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['email'],
                        'msg' => $this->lang['ERR_MAIL_BAD'],
                    );
                }
                else if ( $reg->leadsWithSameEmail( $reg->getEmail(), 'id', $reg->getId() ) )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['email'],
                        'msg' => $this->lang['ERR_MAIL_EXISTS'],
                    );
                }
            }
            
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
                else if ( strlen( $reg->getName() ) > 50 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => sprintf( $this->lang['ERR_NAME_LONG'], '50' ),
                    );
                }
                else
                {
                    // Check if name already exists
                    if ( $this->db->fetchOne( $reg->getTableName(), 'id', ['name' => $reg->getName()], ' AND id <> '.$reg->getId()) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['name'],
                            'msg' => $this->lang['ERR_NAME_EXISTS'],
                        );
                    }
                }
            }

            if ( empty( $reg->getLocale() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['locale'],
                    'msg' => $this->lang['ERR_LOCALE_NEEDED'],
                );
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

            if ( !sizeof( $error_ajax ) )
            {
                // Fields with special treatment
// ********************** Locations start *******************************************
                if ( $reg->getCity() == '-') $reg->setCity('0'); // 0 means there is an alt city
// ********************** Locations end *******************************************

                if ( $data['action'] == 'add' )
                {
                    // new record
                    $reg->persistORL();
                }
                else
                {
                    // Edit record

                    if ( !empty($reg->getDateReg()) )
                    {
//$txt = 'Date reg ('.$reg->getDateReg().')'.PHP_EOL; fwrite($this->myfile, $txt);
                        $reg->setDateReg( DateTime::createFromFormat('d-m-Y H:i:s', $reg->getDateReg(), new DateTimeZone($this->session->config['time_zone'])));
//$txt = 'Date reg o ('.$reg->getDateReg()->format('Y-M-d H:i:s').')'.PHP_EOL; fwrite($this->myfile, $txt);
                    }
                    $reg->persistORL();
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['LEAD_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['LEADS_LINK'];
                echo json_encode($response);
                exit();
            }
            else
            {
                // Errors found

                // Renew CSRF
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);

                // Send errors to be displayed
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
                // new record
                $reg->setDateReg( $now->format('d-m-Y H:i:s') );
            }
            else
            {
                // Edit record
                if( $reg->getRegbyId( $reg->getId() ) )
                {
                    // Field with special treatment
                    $reg->setDateReg( ( $reg->getDateReg() == '' )? NULL : $reg->getDateReg()->format('d-m-Y H:i:s') );
// ********************** Locations start *******************************************
                    if ( $reg->getCity() == '0' ) $reg->setCity('-');
// ********************** Locations end *******************************************
                }
                else
                {
                    $_SESSION['alert'] = array('type'=>'danger', 'message'=>$this->lang['ERR_LEAD_NOT_EXISTS']);
                    header('Location: /'.$this->folder.'/'.$this->lang['LEADS_LINK']);
                    exit;
                }
            }
        }

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

        // User select options list
        $filter_select = '';
        $extra_select = 'ORDER BY `id`';
        $data_options_field = 'user_options';
        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        if ( $reg->getUser() == '')
        {
            $data[$data_options_field] .= '<option value="0" selected="selected">'.$this->lang['USER_SELECT'].'</option>';
            $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $rows = $user->getAll( $filter_select, $extra_select );
        foreach ( $rows as $row )
        {
            $data[$data_options_field] .= '<option value="'.$row['id'].'"'.(( $reg->getUser() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].' - '.$row['id'].'</option>';
        }

        // Group select options list
        $filter_select = '';
        $extra_select = 'ORDER BY `id`';
        $data_options_field = 'group_options';
        $group = new groupController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        if ( $reg->getGroup() == '')
        {
            $data[$data_options_field] .= '<option value="0" selected="selected">'.$this->lang['GROUP_SELECT'].'</option>';
            $data[$data_options_field] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $rows = $group->getAll( $filter_select, $extra_select );
        foreach ( $rows as $row )
        {
            $data[$data_options_field] .= '<option value="'.$row['id'].'"'.(( $reg->getGroup() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].' - '.$row['id'].'</option>';
        }

// ********************** Locations start *******************************************
        // Country select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/country_all.php');

        // Region select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/region_country.php');

        // City select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/city_region_country.php');
// ********************** Locations end *******************************************

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

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/leadForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['LEADS_LINK'],
        ));
    }
}