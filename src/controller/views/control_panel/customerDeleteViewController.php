<?php

namespace src\controller\views\control_panel;

use \src\controller\baseViewController;

use \src\controller\entity\accountController;
use \src\controller\entity\accountNotesController;
use src\controller\entity\langController;
use src\controller\entity\langNameController;
use \src\controller\entity\userController;
use \src\controller\entity\userProfileController;
use \src\controller\entity\vatTypeController;

use DateTime;
use DateTimeZone;

class customerDeleteViewController extends baseViewController
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
                                    'company' => array(
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
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'name',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'control_panel';

    /**
     * @Route('/control_panel/customer/delete/id', name='control_panel_customer_delete')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/accountControler_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/customer/delete';

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['account_key']) || $vars['account_key'] == '' || $vars['account_key'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_ACCOUNT_CUSTOMER_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/customers');
            exit;
        }

        $reg = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyAccountKey( $vars['account_key'] );

        $accountNotes = new accountNotesController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $accountNotes->getRegbyAccountAndGroup( $reg->getId(), $this->group );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user->getRegbyId( $reg->getMainUser() );

        $userProfile = new userProfileController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $userProfile->getRegByUser( $user->getId() );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            //'group_options'   => '',
            'main_user_options'   => '',
// ********************** Locations start *******************************************
            'country_options' => '',
            'region_options' => '',
            'city_options' => '',
// ********************** Locations end *******************************************
            'vat_type_options' => '',
            'locale_options' => '',
            //'agent_options' => '',
            'users' => array(),
        );

        $error_ajax = array();

        if ( $data['submit'] )
        {
            // CSRF Token validation
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 1000);
            if(!$valid){
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['ACCOUNT_CUSTOMER_EDIT'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */

           // Account is as agent in another account
            if ( $this->db->fetchOne( 'account', 'id', ['agent' => $reg->getId()] ) )

            {
                $error_ajax[] = array (
                    'dom_object' => [''],
                    'msg' => $this->lang['ERR_ACCOUNT_IS_AGENT'],
                );
            }

            // Account has more than one user
            $users = $this->db->fetchAll('user', 'id', ['account' => $reg->getId()]);
            if ( sizeof($users) > 1 )
            {
                $error_ajax[] = array (
                    'dom_object' => [''],
                    'msg' => $this->lang['ERR_ACCOUNT_CUSTOMER_HAS_USERS'],
                );
            }
            unset($users);

            // Account has websites
            if ( $this->db->fetchOne('website', 'id', ['account' => $reg->getId()]) )
            {
                $error_ajax[] = array (
                    'dom_object' => [],
                    'msg' => $this->lang['ERR_ACCOUNT_CUSTOMER_HAS_WEBSITES'],
                );
            }

            // Account has invoices
            if ( $this->db->fetchOne('invoice', 'id', ['account' => $reg->getId()]) )
            {
                $error_ajax[] = array (
                    'dom_object' => [],
                    'msg' => $this->lang['ERR_ACCOUNT_CUSTOMER_HAS_INVOICES'],
                );
            }

            // Account has payments
            if ( $this->db->fetchOne('payment', 'id', ['account' => $reg->getId()]) )
            {
                $error_ajax[] = array (
                    'dom_object' => [],
                    'msg' => $this->lang['ERR_ACCOUNT_CUSTOMER_HAS_PAYMENTS'],
                );
            }

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' Customer to delete '.$vars['account_key'].' | User '.$this->user.' ===================================================');

                // Call api endpoint
                $this->utils->edit_user_api( $user->getReg(), 'delete');
                $this->utils->edit_account_api( $reg->getReg(), 'delete');

                $user->delete();
                // Delete userProfile ---> onDelete= Cascade does it for you
                // Delete userNotes ---> onDelete= Cascade does it for you

                $reg->delete();
                // Delete notes ---> onDelete= Cascade does it for you

                $this->pagination['num_page'] = '1';

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['ACCOUNT_CUSTOMER_DELETED'];
                $response['action'] = '/'.$this->folder.'/customers';
                echo json_encode($response);
                exit();
            }
            else
            {
                // Renew CSRF - It gives issues with ajax and session destroy in startup
                //$data['auth_token'] = $this->utils->generateFormToken($form_action);
                $response['status'] = 'KO';
                foreach( $error_ajax as $key => $value )
                {
                    $response['errors'][] = $value;
                }
//$txt = 'Response on error '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE));

                // Send errors to be displayed
                echo json_encode($response);
                exit();
            }
        }
        else
        {
            if ( !empty( $reg->getId() ) )
            {
                // users list
                if ( $rows = $reg->getUsers() )
                {
                    foreach ( $rows as $row )
                    {
                        $user_line = '<a href="/control_panel/user/edit/'.$row['id'].'" target="_blank">'.$row['name'].' - '.$row['email'].'</a>';
                        if ( $reg->getMainUser() == $row['id'] ) $user_line .= ' -> '.$this->lang['USER_IS_MAIN'];
                        $data['users'][] = '<li>'.$user_line.'</li>';
                    }
                }

                // Field with special treatment
// ********************** Locations start *******************************************
                if ( $reg->getCity() == '0' ) $reg->setCity('-');
// ********************** Locations end *******************************************
            }
            else
            {
                $_SESSION['alert'] = array(
                                            'type'          => 'danger',
                                            'message'       => $this->lang['ERR_ACCOUNT_CUSTOMER_NOT_EXISTS'],
                                            'filters'       => $this->list_filters,
                                            'pagination'    => $this->pagination,
                );
                header('Location: /'.$this->folder.'/customers');
                exit;
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

        // Locale options
        if ( $reg->getLocale() == '')
        {
            $data['locale_options'] .= '<option value="" selected="selected">'.$this->lang['LANG_SELECT'].'</option>';
            $data['locale_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $lang = new langController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $lang_name = new langNameController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $filter_select = array(
            'active' => '1'
        );
        $extra_select = '';
        $langs = $lang->getAll( $filter_select, $extra_select );
        foreach ( $langs as $lang_key => $lang_value )
        {
//$txt = 'Lang ====> '.$lang_value['code_2a'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lang_value, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            $lang->getRegbyId( $lang_value['id'] );

            $lang_name->getRegbyCodeAndLang( $lang->getCode2a(), $this->session->getLanguageCode2a());

            $data['locale_options'] .= '<option value="'.$lang->getCode2a().'"'.(( $reg->getLocale() == $lang->getCode2a() )? ' selected="selected" ' : '').'>'.$lang_name->getName().'</option>';
        }

        // User locale options
        if ( $user->getLocale() == '')
        {
            $data['user_locale_options'] .= '<option value="" selected="selected">'.$this->lang['LANG_SELECT'].'</option>';
            $data['user_locale_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        foreach ( $langs as $lang_key => $lang_value )
        {
            $lang->getRegbyId( $lang_value['id'] );

            $lang_name->getRegbyCodeAndLang( $lang->getCode2a(), $this->session->getLanguageCode2a());

            $data['user_locale_options'] .= '<option value="'.$lang->getCode2a().'"'.(($user->getLocale() == $lang->getCode2a())? ' selected="selected" ' : '').'>'.$lang_name->getName().'</option>';
        }

        // mainuser options
        if ( $data['action'] != 'add' )
        {
            if ( $reg->getMainUser() == '' )
            {
                $data['main_user_options'] .= '<option value="" selected="selected">'.$this->lang['USER_SELECT'].'</option>';
                $data['main_user_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
            }
            $rows = $reg->getUsers();
            foreach ( $rows as $row)
            {
                $data['main_user_options'] .= '<option value="'.$row['id'].'"'.(($reg->getMainUser() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].'</option>';
            }
        }

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
            if ( $data['action'] == 'add' )
            {
                $data[$data_options_field] .= '<option value="'.$row['id'].'"'.(($reg->getVatType() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].' ('.(floatval( $row['percent'] ) / 100 ).'%)</option>';
            }
            else
            {
                if ( $reg->getVatType() == $row['id'] )
                {
                    $data[$data_options_field] .= '<option value="'.$row['id'].'"'.(($reg->getVatType() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].' ('.(floatval( $row['percent'] ) / 100 ).'%)</option>';
                }
            }
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/customerForm.html.twig', array(
            'reg' => $reg->getReg(),
            'account_notes' => $accountNotes->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/customers',
        ));
    }
}
