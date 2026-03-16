<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;
use \src\controller\entity\paymentTransactionController;

use DateTime;
use DateTimeZone;

class payment_transactionEditViewController extends baseViewController
{
    private $list_filters = array(
                                    'transaction_key' => array(
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
     * @Route('/app/payment_transaction/edit/id', name='app_payment_transaction_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Payment transaction '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/payment_transactionEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'FILES =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_FILES, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/payment_transaction/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new paymentTransactionController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $reg_original = new paymentTransactionController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId(( isset( $vars['id'] ) )? $vars['id'] : '0');
        $reg->setAccount( $this->utils->request_var( 'account', '', 'ALL' ) );
        $reg->setAccountPaymentMethod( $this->utils->request_var( 'account_payment_method', '', 'ALL' ) );
        $reg->setQuote( $this->utils->request_var( 'quote', '', 'ALL' ) );
        $reg->setFunding( $this->utils->request_var( 'funding', '', 'ALL' ) );
        $reg->setDateReg( $this->utils->request_var( 'date_reg', $now->format('Y-m-d H:i:s'), 'ALL' ) );
        $reg->setPaymentType( $this->utils->request_var( 'payment_type', '', 'ALL') );
        $reg->setResult( $this->utils->request_var( 'result', '', 'ALL') );
        $reg->setEventId( $this->utils->request_var( 'event_id', '', 'ALL') );
        $reg->setOriginId( $this->utils->request_var( 'origin_id', '', 'ALL') );
        $reg->setTransactionId( $this->utils->request_var( 'transaction_id', '', 'ALL') );
        $reg->setTransaction( $this->utils->request_var( 'transaction', '', 'ALL') );

        $reg_original->getRegbyId( $reg->getId() );
//$txt = 'Original transaction'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg_original->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $data = array(
            'auth_token'    => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'        => ( isset($_POST['btn_submit'])) ? true : false,
            'action'        => ( $reg->getId() == '0' )? 'add' : 'edit',
            'payment_type_options' => '',
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
                    'section' => $this->lang['PAYMENT_TRANSACTION_EDIT'],
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
                    //$reg->persist();

                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $reg->setAccount( $reg_original->getAccount() );
                    $reg->setAccountPaymentMethod( $reg_original->getAccountPaymentMethod() );
                    $reg->setQuote( $reg_original->getQuote() );
                    $reg->setFunding( $reg_original->getFunding() );
                    $reg->setDateReg( $reg_original->getDateReg() );
                    $reg->setPaymentType( $reg_original->getPaymentType() );
                    $reg->setResult( $reg_original->getResult() );
                    $reg->setEventId( $reg_original->getEventId() );
                    $reg->setOriginId( $reg_original->getOriginId() );
                    $reg->setTransactionId( $reg_original->getTransactionId() );
                    $reg->setTransaction( $reg_original->getTransaction() );
                    //$reg->persist();
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['PAYMENT_TRANSACTION_SAVED'];
                $response['action'] = '/'.$this->folder.'/payment_transactions';
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
                if( $reg->getRegbyId( $reg->getId() ) )
                {
                    // Fields with special treatment
                    $reg->setDateReg( ( $reg->getDateReg() == '' )? NULL : $reg->getDateReg()->format('d-m-Y H:i:s') );
                }
                else
                {
                    $_SESSION['alert'] = array('type'=>'danger', 'message'=>$this->lang['ERR_PAYMENT_TRANSACTION_NOT_EXISTS']);
                    header('Location: /'.$this->folder.'/'.$this->lang['PAYMENT_TRANSACTIONS_LINK']);
                    exit;
                }
            }
        }

        // Payment Type select options list
        if ( $reg->getPaymentType() == '' )
        {
            $data['payment_type_options'] .= '<option value="" selected="selected">'.$this->lang['PAYMENT_TYPE_SELECT'].'</option>';
            $data['payment_type_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $rows = $this->db->fetchAll('payment_type', 'id, name', ['active' => '1'], 'ORDER BY `name`');
        foreach ($rows as $row) {
            $data['payment_type_options'] .= '<option value="' . $row['id'] . '"' . (( $reg->getPaymentType() == $row['id']) ? ' selected="selected" ' : '') . '>' . $row["name"] . '</option>';
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/payment_transactionForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/payment_transactions',
        ));
    }
}