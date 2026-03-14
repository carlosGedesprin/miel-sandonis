<?php

namespace  src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\accountController;
use \src\controller\entity\accountNotesController;
use \src\controller\entity\userController;
use \src\controller\entity\groupController;
use \src\controller\entity\userProfileController;
use \src\controller\entity\widgetController;
use \src\controller\entity\invoiceController;
use \src\controller\entity\paymentController;

class accountDeleteViewController extends baseViewController
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
                                'agent' => array(
                                                    'type' => 'select',
                                                    'caption' => '',
                                                    'placeholder' => '',
                                                    'width' => '0',	// if 0 uses the rest of the row
                                                    'value' => '',
                                                    'value_previous' => '',
                                                    'chain_childs' => '',
                                                    'options' => '',
                                ),
                                'integrator' => array(
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

    private $folder = 'app';

    /**
     * @Route('/app/account/delete/id', name='app_account_delete')
     *
     * @param $vars array Params on route
     *
     * @return object    Twig template
     */
    public function deleteitemAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/accountDeleteViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $form_action = $this->folder.'/account/delete';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        if ( !isset( $vars['id']) || $vars['id'] == '' || $vars['id'] == '0' )
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_ACCOUNT_NOT_EXISTS'],
                'filters'       => $this->list_filters,
                'pagination'    => $this->pagination,
            );
            header('Location: /'.$this->folder.'/'.$this->lang['ACCOUNTS_LINK']);
            exit;
        }

        $reg = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->getRegbyId( $vars['id'] );

        $accountNotes = new accountNotesController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user->getRegbyId( $reg->getMainUser() );

        $userProfile = new userProfileController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $userProfile->getRegbyId( $user->getId() );

        $widget = new widgetController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $invoice = new invoiceController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment = new paymentController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => 'delete',
            'group_options'   => '',
            'main_user_options'   => '',
// ********************** Locations start *******************************************
            'country_options' => '',
            'region_options' => '',
            'city_options' => '',
// ********************** Locations end *******************************************
            'locale_options' => '',
            'agent_options' => '',
            'integrator_options' => '',
            'users' => array(),
        );
        $error_ajax = array();

//$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Vars =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Notes =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_notes, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'User =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'User profile =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($user_profile, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'FILES =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_FILES, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'On start function end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        if ( $data['submit'] )
        {
            // CSRF Token validation
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 1000);
            if(!$valid){
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['ACCOUNT_DELETE'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */

            // Account has more than one user
            if ( $user->howManyUsersOnAccount( $reg->getId() ) > 1 )
            {
                $error_ajax[] = array (
                    'dom_object' => [''],
                    'msg' => $this->lang['ERR_ACCOUNT_HAS_USERS'],
                );
            }

            // Account is as agent in another account
            if ( $reg->accountIsAgentOfAccounts( 'id', $reg->getId() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => [''],
                    'msg' => $this->lang['ERR_ACCOUNT_IS_AGENT'],
                );
            }

            // Account is as integrator in another account
            if ( $reg->accountIsIntegratorOfAccounts( 'id', $reg->getId() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => [''],
                    'msg' => $this->lang['ERR_ACCOUNT_IS_INTEGRATOR'],
                );
            }

            // Account has widgets
            if ( $widget->howManyWidgetsOnAccount( $reg->getId() ) > 0 )
            {
                $error_ajax[] = array (
                    'dom_object' => [''],
                    'msg' => $this->lang['ERR_ACCOUNT_HAS_WidgetS'],
                );
            }

            // Account has invoices
            if ( $invoice->howManyInvoicesOnAccount( $reg->getId() ) > 0 )
            {
                $error_ajax[] = array (
                    'dom_object' => [''],
                    'msg' => $this->lang['ERR_ACCOUNT_HAS_INVOICES'],
                );
            }

            // Account has payments
            if ( $payment->howManyPaymentsOnAccount( $reg->getId() ) > 0 )
            {
                $error_ajax[] = array (
                    'dom_object' => [''],
                    'msg' => $this->lang['ERR_ACCOUNT_HAS_PAYMENTS'],
                );
            }

            if ( !sizeof( $error_ajax ) )
            {
                $this->logger->info('==============='.__METHOD__.' Account to delete '.$vars['id'].' | User '.$this->user.' ===================================================');

                $this->utils->edit_account_api( $reg->getReg(), 'delete');

                $user->delete();
                // Delete userProfile ---> onDelete= Cascade does it for you

                $reg->delete();
                // Delete notes ---> onDelete= Cascade does it for you

                $this->pagination['num_page'] = '1';

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['ACCOUNT_DELETED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['ACCOUNTS_LINK'];
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
                if ( $this->group == GROUP_SUPER_ADMIN || $this->group == GROUP_ADMIN )
                {
                    $account_notes_total = $accountNotes->getAll( ['account' => $reg->getId()] );
                }
                else
                {
                    $accountNotes->getRegbyAccountAndGroup( $reg->getId(), $this->group );
                    $account_notes_total[] = $accountNotes->getReg();
                }

                if ( sizeof($account_notes_total) <= 0 )
                {
                    $accountNotes->setId('0');
                    $accountNotes->setGroup( $reg->getId() );
                    $accountNotes->setGroup( $this->group );
                    $accountNotes->setNotes( '' );
                    $account_notes_total[] = $accountNotes->getReg();
                }

                // users list
                if ( $rows = $reg->getUsers() )
                {
                    foreach ( $rows as $row )
                    {
                        $user_line = '<a href="/app/user/edit/'.$row['id'].'" target="_blank">'.$row['name'].' - '.$row['email'].'</a>';
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
                                            'message'       => $this->lang['ERR_ACCOUNT_NOT_EXISTS'],
                                            'filters'       => $this->list_filters,
                                            'pagination'    => $this->pagination,
                );
                header('Location: /'.$this->folder.'/'.$this->lang['ACCOUNTS_LINK']);
                exit();
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

        // group options
        if ( $reg->getGroup() == '' )
        {
            $data['group_options'] .= '<option value="" selected="selected">'.$this->lang['GROUP_SELECT'].'</option>';
            $data['group_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $filter_select = NULL;
        $extra_select = 'ORDER BY `name`';
        $group = new groupController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $group->getAll( $filter_select, $extra_select);
        foreach ( $rows as $row)
        {
            $data['group_options'] .= '<option value="'.$row['id'].'"'.(( $reg->getGroup() == $row['id'])? ' selected="selected" ' : '').'>'.$row['name'].'</option>';
        }

        // Locale options
        if ( $reg->getLocale() == '')
        {
            $data['locale_options'] .= '<option value="" selected="selected">'.$this->lang['LANG_SELECT'].'</option>';
            $data['locale_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }

        $api_data = array();
        $rows = $this->utils->get_from_lang_api( '/api/get_active_langs', $api_data );

        foreach ( $rows as $row )
        {
            $lang_name = $this->utils->getLangName($row['code_2a'], $this->session->getLanguageCode2a());
            $data['locale_options'] .= '<option value="'.$row['code_2a'].'"'.(( $reg->getLocale() == $row['code_2a'] )? ' selected="selected" ' : '').'>'.$lang_name.'</option>';
        }

        // mainuser options
        $filter_select = array(
            'account' => $reg->getId()
        );
        $extra_select = 'ORDER BY `name`';
        $data_options_field = 'main_user_options';
        $rows = $user->getAll( $filter_select, $extra_select );
        require_once(APP_ROOT_PATH.'/src/util/view_selects/main_users.php');

        if ( in_array($this->group, [GROUP_SUPER_ADMIN, GROUP_ADMIN]) )
        {
            // agent options
            $filter_select = ( in_array($this->group, [GROUP_SUPER_ADMIN, GROUP_ADMIN]) )?  array( 'group' => GROUP_AGENT ) : array( 'group' => GROUP_AGENT, 'show_to_staff' => '1' );
            $extra_select = 'ORDER BY `name`';
            $data_options_field = 'agent_options';
            $rows = $account->getAll( $filter_select, $extra_select );
            require_once(APP_ROOT_PATH.'/src/util/view_selects/account_agents.php');

            // integrator options
            $filter_select = ( in_array($this->group, [GROUP_SUPER_ADMIN, GROUP_ADMIN]) )?  array( 'group' => GROUP_INTEGRATOR ) : array( 'group' => GROUP_INTEGRATOR, 'show_to_staff' => '1' );
            $extra_select = 'ORDER BY `name`';
            $data_options_field = 'integrator_options';
            $rows = $account->getAll( $filter_select, $extra_select );
            require_once(APP_ROOT_PATH.'/src/util/view_selects/account_integrators.php');
        }

//$txt = '====================== '.__FUNCTION__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/accountForm.html.twig', array(
            'reg' => $reg->getReg(),
            'account_notes' => $account_notes_total,
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['ACCOUNTS_LINK'],
        ));
    }
}
