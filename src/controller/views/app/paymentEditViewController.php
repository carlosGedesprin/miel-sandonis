<?php

namespace src\controller\views\app;

use \src\controller\baseViewController;

use \src\controller\entity\paymentController;
use src\controller\entity\paymentTypeController;

use DateTime;
use DateTimeZone;

class paymentEditViewController extends baseViewController
{
    private $list_filters = array(
                                'payment_type' => array(
                                            'type' => 'select',
                                            'caption' => '',
                                            'placeholder' => '',
                                            'width' => '0',	// if 0 uses the rest of the row
                                            'value' => '',
                                            'value_previous' => '',
                                            'chain_childs' => '',
                                            'options' => '',
                                ),
                                'result' => array(
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
     * @Route('/app/payment/edit/id', name='app_payment_edit')
     *
     * @param POST
     *
     * @return object    Twig template
     */
    public function edititemAction( $vars )
    {
        $this->logger->info('==============='.__METHOD__.' Product '.$vars['id'].' | User '.$this->user.' ===================================================');
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentEditViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'FILES =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_FILES, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'paymentEditViewController '.__FUNCTION__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $form_action = $this->folder.'/payment/editor';

        $this->pagination = $this->utils->request_pagination( $this->pagination );
        list( $this->list_filters, $this->pagination['num_page'] )  = $this->utils->request_filters( $this->list_filters, $this->pagination['num_page'] );

        $reg = new paymentController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $reg->setId( ( isset( $vars['id'] ) )? $vars['id'] : '0');
        $reg->setPaymentKey( $this->utils->request_var( 'payment_key', NULL, 'ALL') );
        $reg->setAccount( $this->utils->request_var( 'account', NULL, 'ALL') );
        $reg->setQuote( $this->utils->request_var( 'quote', NULL, 'ALL') );
        $reg->setPaymentType( $this->utils->request_var( 'paymenttype', NULL, 'ALL') );
        $reg->setInstalment( $this->utils->request_var( 'instalment', 1, 'ALL') );
        $reg->setDate( $this->utils->request_var('date', $now->format('d-m-Y H:i:s'), 'ALL' ) );
        $reg->setAmount( $this->utils->request_var( 'amount', '', 'ALL') );
        $reg->setResult( $this->utils->request_var( 'result', 0, 'ALL') );
        $reg->setTypeTrans( $this->utils->request_var( 'type_trans', '', 'ALL') );
        $reg->setIdTrans( $this->utils->request_var( 'id_trans', '', 'ALL') );
        $reg->setCodAproval( $this->utils->request_var( 'cod_aproval', '', 'ALL') );
        $reg->setCodError( $this->utils->request_var( 'cod_error', '', 'ALL') );
        $reg->setDesError( $this->utils->request_var( 'des_error', '', 'ALL') );

//$txt = '$reg '.print_r($reg->getReg(), TRUE).PHP_EOL; fwrite($this->myfile, $txt);

        $data = array(
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit' => (isset($_POST['btn_submit'])) ? true : false,
            'action' => ( $reg->getId() == '0' )? 'add' : 'edit',
            'account_options' => '',
            'quote_options' => '',
            'payment_type_options' => '',
            'result_options' => '',
        );
        $error_ajax = array();

//$txt = 'On start function start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'REG =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
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
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/logout',
                ));
            }
            */

            if ( empty( $reg->getAccount() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['account'],
                    'msg' => $this->lang['ERR_ACCOUNT_NEEDED'],
                );
            }

            if ( empty( $reg->getQuote() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['quote'],
                    'msg' => $this->lang['ERR_QUOTE_NEEDED'],
                );
            }
            
            if ( empty( $reg->getPaymentType() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['payment_type'],
                    'msg' => $this->lang['ERR_PAYMENT_TYPE_NEEDED'],
                );
            }

            if ( empty( $reg->getDate() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['date'],
                    'msg' => $this->lang['ERR_DATE_REG_NEEDED'],
                );
            }
            else
            {
//$txt = 'Reg date =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($reg->getDate(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                if ( $date = $this->utils->date_to_object( $reg->getDate() ) )
                {
                    $reg->setDate( $date );
//fwrite($this->myfile, print_r($reg->getDate(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                }
                else
                {
                    $error_ajax[] = array (
                        'dom_object' => ['date'],
                        'msg' => $this->lang['ERR_DATE_REG_BAD'],
                    );
                }
            }

            if ( empty( $reg->getAmount() ) )
            {
                $error_ajax[] = array (
                    'dom_object' => ['amount'],
                    'msg' => $this->lang['ERR_PAYMENT_AMOUNT_NEEDED'],
                );
            }
            
            if ( $data['action'] == 'add' )
            {
                if( $reg->getResult() == '' )
                {
                    $error_ajax[] = array (
                        'dom_object' => ['result'],
                        'msg' => $this->lang['ERR_PAYMENT_RESULT_NEEDED'],
                    );
                }
            }

            if ( !sizeof( $error_ajax ) )
            {
                if ( $data['action'] == 'add' )
                {
//$txt = '========== NEW =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    //$reg->persistORL();
                }
                else
                {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    //$reg->persistORL();
                }

                // Send success to be displayed
                $response['status'] = 'OK';
                $response['msg'] = $this->lang['PAYMENT_SAVED'];
                $response['action'] = '/'.$this->folder.'/payments';
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
//                if ( $this->list_filters['payment_type']['value'] != '0' ) $reg->getPaymentType() = $this->list_filters['payment_type']['value'];
            }
            else
            {
//$txt = '========== EDIT =========='.PHP_EOL; fwrite($this->myfile, $txt);
                if ( $reg->getRegbyId( $reg->getId() ) )
                {
                }
                else
                {
                    $_SESSION['alert'] = array('type'=>'danger', 'message'=>$this->lang['ERR_PAYMENT_NOT_EXISTS']);
                    header('Location: /'.$this->folder.'/'.$this->lang['PAYMENTS_LINK']);
                    exit();
                }
            }
        }

        if ( $reg->getDate() != '' ) $reg->setDate( $reg->getDate()->format('d-m-Y H:i:s') );

        // account select options list
        require_once(APP_ROOT_PATH . '/src/util/view_selects/account_account.php');

        // Quote select options list
        if ( $reg->getQuote() == '')
        {
            $data['quote_options'] .= '<option value="" selected="selected">'.$this->lang['QUOTE_SELECT'].'</option>';
            $data['quote_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $rows = $this->db->fetchAll('quote', 'id, account, date, total_to_pay', ['account' => $reg->getAccount()], 'ORDER BY date');
//$txt = 'Quotes =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($rows, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        foreach ( $rows as $row )
        {
            //$account_name = $this->db->fetchField('account', 'name', [ 'id' => $row['account'] ]);
            $data['quote_options'] .= '<option value="'.$row['id'].'"'.(($reg->getQuote() == $row['id'])? ' selected="selected" ' : '').'>'.$this->lang['QUOTE_DATE'].' '.$row['date'].' Total: '.number_format( (floatval($row['total_to_pay']) / 100), 2, ",", "." ).' '.$this->session->config['web_currency'].'</option>';
        }

        // Payment Type select options list
        if ( $reg->getPaymentType() == '' )
        {
            $data['payment_type_options'] .= '<option value="" selected="selected">'.$this->lang['PAYMENT_TYPE_SELECT'].'</option>';
            $data['payment_type_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $filter_select = array(
            'active' => '1',
        );
        $extra_select = 'ORDER BY `name`';
        $payment_type = new paymentTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $rows = $payment_type->getAll( $filter_select, $extra_select);
        foreach ($rows as $row) {
            $data['payment_type_options'] .= '<option value="' . $row['id'] . '"' . (( $reg->getPaymentType() == $row['id']) ? ' selected="selected" ' : '') . '>' . $row["name"] . '</option>';
        }

        // Result select options list
        if ( $reg->getResult() == '' )
        {
            $data['result_options'] .= '<option value="" selected="selected">'.$this->lang['PAYMENT_RESULT_SELECT']. '</option>';
            $data['result_options'] .= '<option disabled>'.str_repeat('&#x2500', 16).'</option>';
        }
        $data['result_options'] .= '<option value = 1 ' . (($reg->getResult() == '1') ? ' selected="selected" ' : '') . '>' . $this->lang['PAYMENT_RESULT_OK'] . '</option>';
        $data['result_options'] .='<option value = 0 ' . (($reg->getResult() == '0') ? ' selected="selected" ' : '') . '>' . $this->lang['PAYMENT_RESULT_NOT_OK'] . '</option>';

        return $this->twig->render('app/'.$this->session->config['app_skin'].'/'.$this->folder.'/paymentForm.html.twig', array(
            'reg' => $reg->getReg(),
            'filters' => $this->list_filters,
            'pagination' => $this->pagination,
            'data' => $data,
            'cancel' => '/'.$this->folder.'/payments',
        ));
    }
}
