<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use src\controller\entity\accountFundsController;
use src\controller\entity\accountFundsSettingsController;
use src\controller\entity\accountPaymentMethodController;

use DateTime;
use DateTimeZone;
use src\controller\entity\paymentTypeController;

class accountFundEditViewController extends baseViewController
{
    private $list_filters = array(
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
     * @Route('/app/account_fund/edit/id', name='app_account_fund_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Account Lead funding '.$vars['id'].' | User '.$this->user.' ===================================================');
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/accountFundEditViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/account_funding/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new accountFundsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId(( isset( $vars['id'] ) )? $vars['id'] : '0');
        $reg->setFundingKey( '68247'.$this->account.'-'.rand(500, 1500) );
        $reg->setAccount( $this->utils->request_var( 'account', $this->account, 'ALL') );
        $reg->setUser( $this->utils->request_var( 'user', $this->user, 'ALL') );
        $reg->setDate( $this->utils->request_var( 'date', $now->format('d-m-Y H:i:s'), 'ALL' ) );
        $reg->setDescription( $this->utils->request_var( 'description', '', 'ALL', true) );
        $reg->setPaymentType( $this->utils->request_var( 'payment_type', PAYMENT_TYPE_BANK, 'ALL') );
        $reg->setAccountPaymentMethod( $this->utils->request_var( 'account_payment_method', '', 'ALL') );
        $reg->setPaymentReference( $this->utils->request_var( 'payment_reference', '', 'ALL') );
        $reg->setCredit( $this->utils->request_var( 'credit', '0', 'ALL') );
        $reg->setDebit( $this->utils->request_var( 'debit', '0', 'ALL') );

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => ( isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' )? 'add' : 'edit',
            'payment_type_options' => '',
            'account_options' => '',
        );

        $error_ajax = array();

$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'On start function end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        if ( $data['submit'] )
        {
            //TODO:Carlos CSRF is not well resolved, when ajax is involved to send the whole form, startup destroys $_SESSION
            // so normal submit will find session and will not log out the user
            /*
            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 5000);
            if (!$valid) {
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['ACCOUNT_FUND_EDIT'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */
            /*
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

            if ( empty( $reg->getUserAgent() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['user_agent'],
                    'msg' => $this->lang['ERR_USER_AGENT_NEEDED'],
                );
            }
            else
            {
                if ( strlen( $reg->getUserAgent() ) < 2 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['user_agent'],
                        'msg' => sprintf( $this->lang['ERR_USER_AGENT_SHORT'], '2' ),
                    );
                }
                else if ( strlen( $reg->getUserAgent() ) > 500 )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['user_agent'],
                        'msg' => sprintf( $this->lang['ERR_USER_AGENT_LONG'], '50' ),
                    );
                }
                else
                {
                    // Check if user_agent already exists
                    if ( $this->db->fetchOne( $reg->getTableName(), 'id', ['user_agent' => $reg->getUserAgent()], ' AND id <> '.$reg->getId()) )
                    {
                        $error_ajax[] = array (
                            'dom_object' => ['user_agent'],
                            'msg' => $this->lang['ERR_USER_AGENT_EXISTS'],
                        );
                    }
                }
            }
            */
            if ( !sizeof( $error_ajax ) )
            {
                if ( $data['action'] == 'add' )
                {
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->setDate( $now );

                    $reg->persistORL();
                    
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( !empty( $reg->getDate() ) )
                    {
                        $reg->setDate( DateTime::createFromFormat('d-m-Y H:i:s', $reg->getDate(), new DateTimeZone($this->session->config['time_zone'])));
                    }

                    //$reg->persistORL();
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['ACCOUNT_FUND_SAVED'];
                $response['action'] = '/'.$this->folder.'/account_funds';
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
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
            }
            else
            {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                if ( $reg->getRegbyId( $reg->getId() ) )
                {
$txt = 'Account funding found ========='.$reg->getId().PHP_EOL;fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($reg->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $reg->getDate() != '' ) $reg->setDate( $reg->getDate()->format('d-m-Y H:i:s') );
                }
                else
                {
                    $_SESSION['alert'] = array('type'=>'danger', 'message'=>$this->lang['ERR_ACCOUNT_FUND_NOT_EXISTS']);
                    header('Location: /'.$this->folder.'/'.$this->lang['ACCOUNT_FUNDS_LINK']);
                    exit;
                }
            }
        }

        // account select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/account_account.php');

        // Payment Type select options list
        if ( $reg->getPaymentType() == '' )
        {
            $data['payment_type_options'] .= '<option value="" selected="selected">'.$this->lang['PAYMENT_TYPE_SELECT'].'</option>';
            $data['payment_type_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $filter_select = array(
            'active' => '1',
        );
        $extra_select = ' AND `id` in ('.PAYMENT_TYPE_BANK.','.PAYMENT_TYPE_FUNDS.') ';
        $extra_select .= 'ORDER BY `name`';
        $payment_type = new paymentTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $payment_type->getAll( $filter_select, $extra_select);
        foreach ($rows as $row) {
            $data['payment_type_options'] .= '<option value="' . $row['id'] . '"' . (( $reg->getPaymentType() == $row['id']) ? ' selected="selected" ' : '') . '>' . $row["name"] . '</option>';
        }

        // Payment Method select options list
        /*
        $account_funds_settings = new accountFundsSettingsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account_funds_settings->getRegbyAccount( $reg->getAccount() );
        if ( $account_funds_settings->getAccountPaymentMethod() == '' )
        {
            $data['account_payment_method_options'] .= '<option value="" selected="selected">'.$this->lang['PAYMENT_METHOD_SELECT'].'</option>';
            $data['account_payment_method_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $data['account_payment_method_options'] .= '<option value="" selected="selected">'.$this->lang['ACCOUNT_FUND_PAYMENT_METHOD_SEND_MAIL'].'</option>';
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
        */


//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/accountFundForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/account_funds',
        ));
    }
}