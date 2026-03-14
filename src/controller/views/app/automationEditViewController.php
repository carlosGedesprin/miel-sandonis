<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\automationController;
use \src\controller\entity\accountController;

use DateTime;
use DateTimeZone;

class automationEditViewController extends baseViewController
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
    );
    private $pagination = array(
                                'num_page'       => '1',
                                'order'          => 'id',
                                'order_dir'      => 'ASC',
                                'rpp'            => '',
    );

    private $folder = 'app';    
   
    /**
     * @Route('/app/automation/edit/id', name='app_automation_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Automation '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/automationEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/automation/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new automationController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $reg_original = new automationController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId( ( isset( $vars['id'] ) )? $vars['id'] : '0' );
        $reg->setName( $this->utils->request_var( 'name', '', 'ALL', true) );
        $reg->setAccount( $this->utils->request_var( 'account', '', 'ALL') );
        $reg->setBillingAccount( $this->utils->request_var( 'billing_account', '', 'ALL') );
        $reg->setProductSetup( $this->utils->request_var( 'product_setup', '', 'ALL') );
        $reg->setProductRenewal( $this->utils->request_var( 'product_renewal', '', 'ALL') );
        $reg->setPriceSetup( $this->utils->request_var( 'price_setup', '', 'ALL') );
        $reg->setPriceRenewal( $this->utils->request_var( 'price_renewal', '', 'ALL') );
        $reg->setCoupon( $this->utils->request_var( 'coupon', '', 'ALL', true) );
        $reg->setDateReg( $this->utils->request_var( 'date_reg', $now->format('Y-m-d H:i:s'), 'ALL' ) );
        $reg->setAutoRenew( $this->utils->request_var( 'auto_renew', '1', 'ALL')  );
        $reg->setDateStart( $this->utils->request_var( 'date_start', NULL, 'ALL' ) );
        $reg->setDateEnd( $this->utils->request_var( 'date_end', NULL, 'ALL' ) );
        $reg->setAgent( $this->utils->request_var( 'agent', NULL, 'ALL' ) );
        $reg->setActive( $this->utils->request_var( 'active', '1', 'ALL') );

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => (isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' )? 'add' : 'edit',
            'account_options' => '',
            'billing_account_options' => '',
            'product_setup_options' => '',
            'product_renewal_options' => '',
            'agent_options' => '',
            'coupon_options' => '',
            'get_product_url' => '/api/get_product',
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
//$txt = 'Submit ========>'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
            //TODO:Carlos CSRF is not well resolved, when ajax is involved to send the whole form, startup destroys $_SESSION
            // so normal submit will find session and will not log out the user
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 5000);
            if (!$valid) {
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['AUTOMATION_EDIT'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */

            if ( empty( $reg->getAccount() ) )
            {
                $error_ajax[] = array(
                    'dom_object' => ['account'],
                    'msg' => $this->lang['ERR_ACCOUNT_NEEDED'],
                );
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
                        'msg' => sprintf( $this->lang['ERR_NAME_SHORT'], '2' )
                    );
                }
                else if ( strlen( $reg->getName() ) > 30 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['name'],
                        'msg' => sprintf($this->lang['ERR_NAME_LONG'],'30' )
                    );
                }
                else
                {
//$txt = 'account '.$reg->getAccount().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( !empty( $reg->getAccount() ) )
                    {
//$txt = 'Verification name duplicated'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
                        //automationsWithSameName( $name, $type_key, $key, $account )
                        if ( $reg->automationsWithSameName( $reg->getName(), 'id', $reg->getId(), $reg->getAccount() ) )
                        {
                            $error_ajax[] = array (
                                'dom_object' => ['name'],
                                'msg' => $this->lang['ERR_NAME_EXISTS'],
                            );
                        }
                    }
                }
            }
//$txt = 'Name OK'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);

            if ( empty( $reg->getBillingAccount() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['billing_account'],
                    'msg' => $this->lang['ERR_ACCOUNT_BILLING_ACCOUNT_NEEDED'],
                );
            }

//$txt = 'Product to validate => ('.$reg->getProduct().') Date end ('.$reg_original->getDateEnd().')'.PHP_EOL; fwrite($this->myfile, $txt);
            if ( empty( $reg->getProductSetup() ) && empty( $reg->getProductRenewal() ) )
            {
                $error_ajax[] = array(
                    'dom_object' => ['product_setup', 'product_renewal'],
                    'msg' => $this->lang['ERR_PRODUCT_NEEDED']
                );
            }

//$txt = 'Errors ('.sizeof( $error_ajax ).')'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
            if ( !sizeof( $error_ajax ) )
            {
//$txt = 'No errors ========>'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);

                if ( $data['action'] == 'add' )
                {
//$txt = '========== ADD =========='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->setDateReg( ( !empty( $reg->getDateReg() ) )? DateTime::createFromFormat('d-m-Y H:i:s', $reg->getDateReg(), new DateTimeZone($this->session->config['time_zone'])) : NULL );
                    $reg->setDateStart( ( !empty( $reg->getDateStart() ) )? DateTime::createFromFormat('d-m-d', $reg->getDateStart(), new DateTimeZone($this->session->config['time_zone'])) : NULL );
                    $reg->setDateEnd( ( !empty( $reg->getDateEnd() ) )? DateTime::createFromFormat('d-m-d', $reg->getDateEnd(), new DateTimeZone($this->session->config['time_zone'])) : NULL );

                    $reg->persistORL();

                    $reg->setAutomationKey( md5( $reg->getId().$reg->getName() ) );
                    $reg->persist();
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( !empty( $reg->getDateReg() ) )
                    {
                        $reg->setDateReg( DateTime::createFromFormat('d-m-Y H:i:s', $reg->getDateReg(), new DateTimeZone($this->session->config['time_zone'])));
                    }
                    if ( !empty($reg->getDateStart()) )
                    {
                        $reg->setDateStart( DateTime::createFromFormat('d-m-Y', $reg->getDateStart(), new DateTimeZone($this->session->config['time_zone'])) );
                    }
                    if ( !empty($reg->getDateEnd()) )
                    {
                        $reg->setDateEnd( DateTime::createFromFormat('d-m-Y', $reg->getDateEnd(), new DateTimeZone($this->session->config['time_zone'])) );
                    }

                    $reg_original->getRegbyId( $reg->getId() );
                    $reg->setAutomationKey( $reg_original->getAutomationKey() );

//$txt = 'Reg =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->persistORL();
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['AUTOMATION_SAVED'];
                $response['action'] = '/'.$this->folder.'/'.$this->lang['AUTOMATIONS_LINK'];
//$txt = 'Response =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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
//$txt = 'Response =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                echo json_encode($response);
                exit();
            }
        }
        else
        {
//$txt = 'Not submit ========>'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
            if ( $data['action'] == 'add' )
            {
//$txt = '========== ADD =========='.PHP_EOL; fwrite($this->myfile, $txt);
                $reg->setDateReg( $now->format('d-m-Y H:i:s') );
                $reg->setDateStart( $now->format('d-m-Y') );

                $dt2 = new DateTime('+1 month');
                $reg->setDateEnd( $dt2->format('d-m-Y') );
            }
            else
            {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                if ( $reg->getRegbyId( $reg->getId() ) )
                {
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    // Field with special treatment
                    $reg->setDateReg( ( $reg->getDateReg() == '' )? NULL : $reg->getDateReg()->format('d-m-Y H:i:s') );
                    $reg->setDateStart( ( $reg->getDateStart() == '' )? NULL : $reg->getDateStart()->format('d-m-Y') );
                    $reg->setDateEnd( ( $reg->getDateEnd() == '' )? NULL : $reg->getDateEnd()->format('d-m-Y') );
                }
                else
                { 
                    $_SESSION['alert'] = array(
                                               'type'=>'danger',
                                               'message'=>$this->lang['ERR_AUTOMATION_NOT_EXISTS']
                    );
                    header('Location: /'.$this->folder.'/'.$this->lang['AUTOMATIONS_LINK']);
                    exit();
                }
            }
        }

        // account select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/account_account.php');

        // billing account select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/account_billing_account.php');

        // product setup select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/product_automation_setup.php');

        // product select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/product_automation_renewal.php');

        // coupon select options list
        require_once(APP_ROOT_PATH.'/src/util/view_selects/coupon.php');

        // agent options
        require_once(APP_ROOT_PATH.'/src/util/view_selects/account_agent.php');

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/automationForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/'.$this->lang['AUTOMATIONS_LINK'],
        ));
    }
}
