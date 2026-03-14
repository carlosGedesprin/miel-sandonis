<?php

namespace src\controller\views\payments;

use \src\controller\baseViewController;

use DateTime;
use DateTimeZone;

class paymentRedsysViewController extends baseViewController
{
    /**
     *
     * Web payment
     *
     * @Route("/renew_domain/website_key/license_key/token", name="renew_domain_website_key_license_key_token")
     *
     */
    public function getQuoteAction( $vars )
    {
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
$txt = 'paymentController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $form_action = 'payment_gateway/quote_get';

        $data = array(
            'quote_key'  => $this->utils->request_var( 'quote_key', $vars['quote_key'], 'ALL'),
            'auth_token' => ( isset( $_POST['auth_token']) )? $_POST['auth_token'] : $this->utils->generateFormToken($form_action),
            'submit'     => ( isset($_POST['is_submit']) && $_POST['is_submit'] == '1' ) ? true : false,
            'token'      => $this->utils->request_var( 'token', '', 'ALL'),
            'errorCode'  => $this->utils->request_var( 'errorCode', '', 'ALL'),
            'errorMsg'   => '',
            'quote'      => $this->utils->request_var( 'order', '', 'ALL'),
            'result'     => '',
        );
$txt = 'Post :'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($_POST, TRUE));
$txt = 'Data :'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($data, TRUE));

        // Borrar
        if ( isset($_POST['order']) )
        {
            $data['quote_key'] = $_POST['order'];
            $this->db->updateArray( 'quote', 'id', '1', ['quote_key' => $data['quote_key']]);

            if ( !$quote = $this->db->fetchOne( 'quote', '*', ['quote_key' => $data['quote_key']]) )
            {
                return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['PAYMENT_SECTION'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_SECURITY_PAYMENT_QUOTE_NOT_FOUND'],
                    'redirect_wait' => '5000',
                    'redirect' => '/',
                ));
            }
        }

        if ( $data['submit'] )
        {
$txt = 'Is submit ==================='.PHP_EOL; fwrite($this->myfile, $txt);

            $valid = $this->utils->verifyFormToken($form_action, $data['auth_token'], 5000);
            if (!$valid) {
$txt = 'Auth token NOT valid ==================='.PHP_EOL; fwrite($this->myfile, $txt);
                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['PAYMENT_SECTION'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_BAD_TOKEN'],
                    'redirect' => '/',
                ));
            }

            if ( $data['errorCode'] != '' || $data['token'] == '' )
            {
$txt = 'Token ('.$data['token'].') Error Code ('.$data['errorCode'].') ==> '.$this->lang['ERR_SECURITY_PAYMENT_QUOTE_REDSYS_MSG'][$data['errorCode']].PHP_EOL; fwrite($this->myfile, $txt);
                $data['errorMsg'] = $this->lang['ERR_SECURITY_PAYMENT_QUOTE_REDSYS_MSG'][$data['errorCode']];

                return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['PAYMENT_SECTION'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $data['errorMsg'],
                    'redirect' => '',
                ));
            }
            else
            {
$txt = 'Search quote ... =>'.PHP_EOL; fwrite($this->myfile, $txt);
                $data['quote'] = $this->db->fetchOne( 'quote', '*', ['quote_key' => $data['quote_key']]);
$txt = '=================== Quote ID ('.$data['quote']['id'].')'.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Pedido en data ============ ('.$data['quote_key'].')'.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Token en data ============= ('.$data['token'].')'.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Total to pay ============== ('.$data['quote']['total_to_pay'].')'.PHP_EOL; fwrite($this->myfile, $txt);

                require_once APP_ROOT_PATH.'/src/controller/ApiRedsysREST/initRedsysApi.php';

                $cardRequest = new \RESTInitialRequestMessage();

                // Operation mandatory data
                //$cardRequest->setAmount($data['quote']['total_to_pay']); // i.e. 1,23 (decimal point depends on currency code)
                $cardRequest->setAmount('4900'); // i.e. 1,23 (decimal point depends on currency code)
                $cardRequest->setCurrency('978'); // ISO-4217 numeric currency code
                $cardRequest->setMerchant('356112847');
                $cardRequest->setTerminal('001');
                $cardRequest->setOrder( strval($data['quote_key']) );
                $cardRequest->setOperID( strval($data['token']) );
                $cardRequest->setTransactionType( \RESTConstants::$AUTHORIZATION );

                $signatureKey = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
                $service = new \RESTInitialRequestService($signatureKey, \RESTConstants::$ENV_SANDBOX);
                //$service = new \RESTInitialRequestService($signatureKey, \RESTConstants::$ENV_PRODUCTION);

$txt = 'Goes to Redsys'.PHP_EOL; fwrite($this->myfile, $txt);
                $response = $service->sendOperation( $cardRequest );

$txt = 'Response: ('.$response->getResult().')'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                switch ($response->getResult()) {
                    case \RESTConstants::$RESP_LITERAL_OK:
$txt = 'Operation was OK'.PHP_EOL; fwrite($this->myfile, $txt);
                            $data['result'] = 'OK '.\RESTConstants::$RESP_LITERAL_OK;
/*
                        //To get DCC information
                        //$dccCurrency = $response->getDCCCurrency();
                        //$dccAmount = $response->getDCCAmount();

                        //In this case the commerce can choose which kind of operation want to use
                        //directPaymentDCCOperation($orderID, $dccCurrency, $dccAmount);

                        return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                            'section' => $this->lang['PAYMENT_SECTION'],
                            'alert_type' => 'success',
                            'title' => 'Payment successful', //$this->lang['WARNING'],
                            'message' => 'We are the champions', //$data['errorMsg'],
                            'redirect' => '',
                            'redirect_wait' => '5000',
                        ));
*/
                        break;

                    case \RESTConstants::$RESP_LITERAL_AUT:
$txt = 'Operation requires authentication'.PHP_EOL; fwrite($this->myfile, $txt);
                        $data['result'] = 'AUTH '.\RESTConstants::$RESP_LITERAL_AUT;
                        break;

                    default:
$txt = 'Operation was not OK'.PHP_EOL; fwrite($this->myfile, $txt);
                        $data['result'] = 'KO';
                        break;
                }
            }
        }

//$txt = 'Result ========>'.$response.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'paymentController '.__FUNCTION__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
fclose($this->myfile);

print json_encode($this);

    return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/redsys_quote_show_insite_separate_fields.html.twig', array(
        'data' => $data,
    ));

}

/**
 *
 * Get card details
 *
 * @Route("this->getCardDetailsAction", name="getCardDetailsAction")
 */
    public function getCardDetailsAction( $data )
    {
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
$txt = 'paymentController '.__FUNCTION__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Data:'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($data, TRUE));
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/redsys_get_card_details.html.twig', array(
            'data' => $data,
        ));
    }

/**
 *
 * Web payment
 *
 * @Route("/pay_gateway/charge_show", name="chargeshow")
 */
    public function chargeshowAction( $vars )
    {
        $data = array(
            'id' =>  ( isset( $vars['id'] ) )? $vars['id'] : '0',
        );

        if ( $data['id'] != '0' )
        {
            $this->charge = $this->db->fetchOne( 'charge', '*', ['id' => $data['id']] );
        }
        else
        {
            $_SESSION['alert'] = array(
                'type'          => 'danger',
                'message'       => $this->lang['ERR_PAY_CHARGE_NOT_FOUND'],
            );
            header('Location: /pay_gateway/charge_get');
            exit;
        }

        return $this->twig->render($this->session->skin.'/web/charge_show.html.twig', array(
            'reg' => $this->charge,
        ));
    }

    /**
     *
     * Web payment: Recup info to send to TonePay
     *
     * @Route("/pay_gateway/charge_pay", name="charge_pay")
     */
    public function chargewebpayAction( $vars )
    {
        $data = array(
            'id'        =>  ( isset( $vars['id'] ) )? $vars['id'] : '0',
            'CoCode'    =>  '',
            'SessionID'    =>  '',
            'ReturnURL' =>  '',
            'Amount'    =>  '',
            'CustomerRef1'    =>  '',
            'CustomerRef2'    =>  '',
            'FundCode'    => '',
            //'CheckSum'    => '',
        );

        $this->charge = $this->db->fetchOne( 'charge', '*', ['id' => $data['id']] );

        $data['CoCode'] = $this->session->config['adelante_cocode'];
        $data['SessionID'] = session_id();
        $data['ReturnURL'] = $this->startup->getUrlApp().'/pay_gateway/show_result/'.$this->charge['reference'];
        $data['Amount'] = $this->charge['to_pay'] * 100;  // Has to be in pennies
        $data['CustomerRef1'] = $this->charge['reference'];
        $data['CustomerRef2'] = $this->charge['registration'];
        $data['FundCode'] = 'Web';

$this->myfile = fopen('debug_payment_'.$this->charge['reference'].'.txt', 'a+') or die('Unable to open file!');
$txt = 'paymentController chargewebpayAction start ==============================================================='.PHP_EOL;
fwrite($this->myfile, $txt);
$txt = PHP_EOL;
fwrite($this->myfile, $txt);
$txt = 'POST sent to Tonepay : '.PHP_EOL;
fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($data, TRUE));
//fwrite($this->myfile, print_r($reg, TRUE));
$txt = 'web chargeController chargewebpayAction end ==============================================================='.PHP_EOL;
fwrite($this->myfile, $txt);
fclose($this->myfile);

        return $this->twig->render($this->session->skin.'/web/charge_pay_send.html.twig', array(
            'data' => $data,
        ));
    }

    /**
     *
     * Web payment
     *
     * @Route("/pay_gateway/set_result_provisional", name="set_result_provisional")
     */
    public function setResultProvAction( $vars )
    {
        $reg = array(
            //'id'        => '',
            'charge'      => $vars['reference'],
            'SessionID'   => $this->utils->request_var('SessionID', '0', 'ALL'),
            'amount'      => $this->utils->request_var( 'Amount', '0', 'ALL'), // Received in pennies
            'ErrorStatus' => $this->utils->request_var( 'ErrorStatus', '0', 'ALL'),
            'AuthStatus'  => $this->utils->request_var( 'AuthStatus', '0', 'ALL'),
            'AuthCode'    =>  $this->utils->request_var( 'AuthCode', '0', 'ALL'),
            'MPOSID'      =>  $this->utils->request_var( 'MPOSID', '0', 'ALL'),
            'ErrorCode'   =>  $this->utils->request_var( 'ErrorCode', '0', 'ALL'),  //1 = Invalid Parameter 2 = System Error 3 = Duplicate 6200 = Payment Cancelled
            'ErrorDescription' =>  $this->utils->request_var( 'ErrorDescription', '0', 'ALL'),
        );
$this->myfile = fopen('debug_payment_'.$vars['reference'].'.txt', 'a+') or die('Unable to open file!');
$now = (new DateTime("now", new DateTimeZone($this->session->config['time_zone'])))->format('d-m-Y H:i:s');
$txt = 'paymentController setResultProvAction start '.$now.'==============================================================='.PHP_EOL;
fwrite($this->myfile, $txt);
$txt = PHP_EOL;
fwrite($this->myfile, $txt);
$txt = 'POST : '.PHP_EOL;
fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($_POST, TRUE));
$txt = 'reg : '.PHP_EOL;
fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($reg, TRUE));

        if ( $reg['amount'] > 0 )   $reg['amount'] = $reg['amount'] / 100;  // Received in pennies

        if ( $this->charge = $this->db->fetchOne( 'charge', '*', ['reference' => $reg['charge']] ) )
        {
$txt = 'Charge ======> ' . $this->charge['id'] . PHP_EOL;
fwrite($this->myfile, $txt);

            // Recup data to shown in the confirmation screen
            $account = $this->db->fetchOne('account', '*', ['id' => $this->charge['account']]);
            $account_billing = $this->db->fetchOne('account_billing', '*', ['id' => $this->charge['billing']]);
            $defendant = $this->db->fetchOne('charge_defendant', '*', ['charge' => $this->charge['id'], 'actual' => '1']);
            $contravention = $this->db->fetchOne('charge_contravention', '*', ['id' => $this->charge['contravention']]);
            $location = $this->db->fetchOne('account_location', '*', ['id' => $this->charge['contravention_location']]);

            $result = 'OK';
            $result_text = '';
        }
        else
        {
            $result = 'KO';
            $result_text = $this->lang['PAID_CHARGE_ERROR'];
        }

        if ( $result == 'OK' )
        {
            if ($reg['ErrorStatus'] == '1' && $reg['AuthStatus'] == '1')
            {
                // We do NOT Register the transaction here, we do it on the get_result which is firm
                //$this->db->insertArrayORL( 'charge_payments', '0', $reg);

                $result = 'OK';
                $result_text = '';
            }
            else
            {
                $result = 'KO';
                switch ( $reg['ErrorCode'] ) {
                    case '1':
                        // Invalid Parameter
                        $result_text = $this->lang['PAID_CHARGE_ERROR'];
                        break;
                    case '2':
                        // System Error
                        $result_text = $this->lang['PAID_CHARGE_ERROR'];
                        break;
                    case '3':
                        // Duplicate
                        $result_text = $this->lang['PAID_CHARGE_ALREADY_PAID'];
                        break;
                    case '6200':
                        // Payment Cancelled
                        $result_text = $this->lang['PAID_CHARGE_CANCELLED'];
                        break;
                    default:
                        $result_text = $this->lang['PAID_CHARGE_ERROR'];
                }
            }
        }

$txt = 'Result ===========================>'.$result.' '.$result_text.PHP_EOL; fwrite($this->myfile, $txt);
$now = (new DateTime("now", new DateTimeZone($this->session->config['time_zone'])))->format('d-m-Y H:i:s');
$txt = 'paymentController setResultProvAction end '.$now.'==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
fclose($this->myfile);
        return $this->twig->render($this->session->skin.'/web/charge_pay_result.html.twig', array(
            'charge' => $this->charge,
            'defendant' => $defendant,
            'account' => $account,
            'account_billing' => $account_billing,
            'contravention' => $contravention,
            'location' => $location,
            'status' => 'PYD',
            'result' => $result,
            'result_text' => $result_text,
        ));

    }

    /**
     * @Route("/pay_gateway/set_result", name="set_result")
     *
     * @param $vars
     */
    public function setResultAction( $vars )
    {
        $reg = array(
            //'id'        => '',
            'charge'      => $vars['reference'],    // We change it to the right id afterwards
            'method'      => $this->utils->request_var( 'FundCode', '', 'ALL'),
            'SessionID'   => '',
            'amount'      => $this->utils->request_var( 'Amount', '0', 'ALL'), // Received in pennies
            'ErrorStatus' => '',
            'AuthStatus'  => '',
            'CheckSum'    => $this->utils->request_var( 'Signature', '', 'ALL'),
            'AuthCode'    =>  $this->utils->request_var( 'AuthCode', '', 'ALL'),
            'MPOSID'      =>  $this->utils->request_var( 'MPOSID', '0', 'ALL'),
            'ErrorCode'   =>  '',
            'ErrorDescription' =>  '',
        );
        $charge_pendent = '';

$this->myfile = fopen('debug_payment_'.$vars['reference'].'.txt', 'a+') or die('Unable to open file!');
$now = (new DateTime("now", new DateTimeZone($this->session->config['time_zone'])))->format('d-m-Y H:i:s');
$txt = 'pay_gatewayController setResultAction start '.$now.'==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'POST'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($_POST, TRUE));
$txt = 'reg'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($reg, TRUE));

// Signature
$txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Signature'.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'POST value '.((isset($_POST['Signature']))? $_POST['Signature'] : '').PHP_EOL; fwrite($this->myfile, $txt);

        $s = $reg['charge'].'.'.$reg['amount'].'.'.$reg['MPOSID'].'.'.$this->session->config['adelante_secret_key'];
        $signature = $this->SHA256($s);

$txt = 'OUR  value '.$signature.PHP_EOL;
fwrite($this->myfile, $txt);

        if ( $signature == $reg['CheckSum'] )
        {
$txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Signature ========> OK'.PHP_EOL; fwrite($this->myfile, $txt);
            // tested in http://phpfiddle.org/main/code/p3e5-bmac
            $reg['charge'] = $this->utils->getChargeIdbyReference( $reg['charge'] );

            if ( $this->charge = $this->utils->getChargeData( $reg['charge'] ) )
            {
$txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'this->charge ========>'.$this->charge['id'].PHP_EOL; fwrite($this->myfile, $txt);

                if ( $reg['amount'] > 0 ) $reg['amount'] = $reg['amount'] / 100;  // Converting pennies to pounds

                $this->db->insertArrayORL( 'charge_payments', '0', $reg);

                require_once(APP_ROOT_PATH.'/src/util/utils/chargeFunctions.php');
                //$chargeFunctions = new \Utils\utils\chargeFunctions( $this->session, $this->db, $this->lang, $this->utils );                       $chargeFunctions->setCharge( $reg );
                //$chargeFunctions->setCharge( $this->charge );
                //$charge_pendent = $chargeFunctions->pay_charge( $reg['amount'] );
            }
        }

$txt = 'result_payment ('.(($charge_pendent)? '0' : '1' ).')'.PHP_EOL; fwrite($this->myfile, $txt);
$now = (new DateTime("now", new DateTimeZone($this->session->config['time_zone'])))->format('d-m-Y H:i:s');
$txt = 'pay_gatewayController setResultAction end '.$now.'==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
fclose($this->myfile);

        $response = '<result status="OK"></result>';
        header("Content-type: text/xml;charset=utf-8");
        echo $response;
    }

    /**
     *
     * Function to calculate de base64 encoded SHA256 of a string
     *
     */
    private function SHA256($s)
    {
        return base64_encode(hash("SHA256", $s, true));
    }

// ******************************************************************************************************************************************************
// *********    DELETE    *********    DELETE    *********    DELETE    *********    DELETE    *********    DELETE    *********    DELETE
// ******************************************************************************************************************************************************
    /**
     * @Route("/pay_gateway/make_result", name="make_result")
     *
     * @param $vars
     */
    public function makeResultAction( $vars )
    {
/*
$this->myfile = fopen('debug_payment_'.$vars['reference'].'.txt', 'a+') or die('Unable to open file!');
$now = (new DateTime("now", new DateTimeZone($this->session->config['time_zone'])))->format('d-m-Y H:i:s');
$txt = 'pay_gatewayController setResultAction start '.$now.'==============================================================='.PHP_EOL;
fwrite($this->myfile, $txt);
        $reg = array(
            //'id'        => '',
            'charge'      => $vars['reference'],    // We change it to the right id afterwards
            'method'      => 'Test',
            'SessionID'   => '',
            'amount'      => 16000, // Received in pennies
            'ErrorStatus' => '',
            'AuthStatus'  => '',
            'CheckSum'    => '',
            'AuthCode'    =>  '',
            'MPOSID'      =>  'PEPITO',
            'ErrorCode'   =>  '',
            'ErrorDescription' =>  '',
        );
$txt = 'reg'.PHP_EOL;
fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($reg, TRUE));

// Signature
$txt = PHP_EOL;
fwrite($this->myfile, $txt);
$txt = 'Signature'.PHP_EOL;
fwrite($this->myfile, $txt);

        $reg['charge'] = $this->utils->getChargeIdbyReference( $reg['charge'] );

        if ( $this->charge = $this->utils->getChargeData( $reg['charge'] ) )
        {
$txt = PHP_EOL;
fwrite($this->myfile, $txt);
$txt = 'this->charge ========>'.$this->charge['id'].PHP_EOL;
fwrite($this->myfile, $txt);

            // Check if this operation (MPOSID) has not already been recorded
            if ( !$this->db->fetchOne('charge_payments', 'id', ['charge' => $this->charge['id'], 'MPOSID' => $reg['MPOSID']]) )
            {
                if ( $reg['amount'] > 0 ) $reg['amount'] = $reg['amount'] / 100;  // Converting pennies to pounds

                $this->db->insertArrayORL( 'charge_payments', '0', $reg);

                require_once(APP_ROOT_PATH.'/src/util/utils/chargeFunctions.php');
                $chargeFunctions = new \Utils\utils\chargeFunctions( $this->session, $this->db, $this->lang, $this->utils );                       $chargeFunctions->setCharge( $reg );
                $chargeFunctions->setCharge( $this->charge );
                $charge_pendent = $chargeFunctions->pay_charge( $reg['amount'] );
            }
            else
            {
                $txt = PHP_EOL;
                fwrite($this->myfile, $txt);
$txt = 'Payment operation already recorded ========>'.PHP_EOL; fwrite($this->myfile, $txt);

            }
        }

//$txt = 'result_payment ('.(($charge_pendent)? '0' : '1' ).')'.PHP_EOL;
//fwrite($this->myfile, $txt);
$now = (new DateTime("now", new DateTimeZone($this->session->config['time_zone'])))->format('d-m-Y H:i:s');
$txt = 'pay_gatewayController setResultAction end '.$now.'==============================================================='.PHP_EOL;
fwrite($this->myfile, $txt);
fclose($this->myfile);

        $response = '<result status="OK"></result>';
        header("Content-type: text/xml;charset=utf-8");
        echo $response;
*/
        return false;
    }

    /**
     *
     * Phone payment
     *
     * @Route("/pay_gateway/get_charge_phone", name="get_charge_phone")
     *
     */
    public function getChargePhoneAction( $vars )
    {
        $this->myfile = fopen('debug_payment_'.$vars['reference'].'.txt', 'a+') or die('Unable to open file!');
        $txt = 'paymentController getChargePhoneAction start ==============================================================='.PHP_EOL;
        fwrite($this->myfile, $txt);
        $txt = 'initial $vars :'.PHP_EOL;
        fwrite($this->myfile, $txt);
        fwrite($this->myfile, print_r($vars, TRUE));

        $charge_id = $this->utils->getChargeIdbyReference( $vars['reference'] );
        $charge = $this->utils->getChargeData( $charge_id );

        if ( !$charge )
        {
            $response = '<result status="Reference '.$vars['reference'].' Not found"></result>';
        }
        else
        {
            if ( $charge['status'] == 'PYD' )
            {
                $response = '<result status="Already paid"></result>';
            }
            else
            {
                $response = '<result status="OK">
                                <id>'.$vars['reference'].'</id>
                                <registration>'.$charge['registration'].'</registration>
                                <balance>'.($charge['to_pay'] * 100).'</balance>
                            </result>';
            }
        }
        $txt = PHP_EOL.'Response ========>'.$response.PHP_EOL;
        fwrite($this->myfile, $txt);
        $txt = 'paymentController getChargePhoneAction end ==============================================================='.PHP_EOL;
        fwrite($this->myfile, $txt);
        fclose($this->myfile);

        header("Content-type: text/xml;charset=utf-8");
        echo $response;
    }
}
