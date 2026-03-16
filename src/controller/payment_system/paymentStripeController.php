<?php

namespace src\controller\payment_system;

use \src\controller\baseController;

use \src\controller\entity\configController;
use \src\controller\entity\accountController;
use \src\controller\entity\accountPaymentMethodController;
use \src\controller\entity\quoteController;

use DateTime;
use DateTimeZone;

class paymentStripeController extends baseController
{
    /**
    *
    * Tries to pay a quote
    *
    * @param $quote object Quote to be paid
    *
    * @return $response array Mixed
    */
    public function payQuoteAction( $quote )
    {
if ( $_ENV['env_env'] == 'dev') { $folder = '';} else { $folder = 'payments/';}
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/'.$folder.'payment_paymentStripeController_'.__FUNCTION__.'_'.$quote->getId().'.txt', 'a+') or die('Unable to open file!');
$txt = '======================================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Quote to be paid ========> '.$quote->getId().PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $response = array(
                        'status' => '',
                        'result' => array(
                                            'msg' => '',
                                            'data' => array(),
                        )
        );

        if ( !$quote )
        {
$txt = 'No quote ========> KO'.PHP_EOL; fwrite($this->myfile, $txt);
            $response['status'] = 'KO';
            $response['result']['msg'] = $this->lang['ERR_QUOTE_NEEDED'];
        }

        if ( !$quote->getPaymentMethod() )
        {
$txt = 'No payment method ========> KO'.PHP_EOL; fwrite($this->myfile, $txt);
            $response['status'] = 'KO';
            $response['result']['msg'] = $this->lang['ERR_PAYMENT_METHOD_NEEDED'];
        }

        if ( $response['status'] == 'KO' )
        {
//$txt = 'Response ========> KO'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '======================================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
            return $response;
        }

        $config = new configController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account->getRegbyId( $quote->getAccount() );
$txt = 'Account ========> '.$account->getId().PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($account->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $account_payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account_payment_method->getRegbyId( $quote->getPaymentMethod() );
$txt = 'Payment method ========> '.$account_payment_method->getId().PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($account_payment_method->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $config->getRegbyName( 'web_currency' );
        $pi_extra = array(
            'currency' => $config->getConfigValue(),
            'statement_descriptor_suffix' => $this->lang['QUOTE'].' '.$quote->getId(),
            'metadata' => array(
                                'origin' => 'quote',
                                'quote' => $quote->getQuoteKey()
            ),
        );

        $payment_intent_response = $this->utils->createPI( $account, $account_payment_method, $quote->getTotalToPay(), $pi_extra );
$txt = 'CreatePI response ========== '.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($payment_intent_response, TRUE));$txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( $payment_intent_response['status'] == 'OK' )
        {
$txt = 'Payment Intent OK ========== '.PHP_EOL; fwrite($this->myfile, $txt);
            $payment_intent = $payment_intent_response['result']['data'];
$txt = 'Payment Intent ========== ('.$payment_intent['id'].')'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($payment_intent, TRUE));$txt = PHP_EOL; fwrite($this->myfile, $txt);

            $quote->setPaymentReference( $payment_intent['id'] );
            $quote->persist();

            $response['status'] = 'OK';
            $response['result']['msg'] = $payment_intent['status'];
            $response['result']['data'] = $payment_intent;

//$txt = '================= Payment intent successfuly created ================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '================= Payment intent Id > '.$payment_intent['id'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '================= Quote     > '.$payment_intent['metadata']['quote'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '================= Customer  > '.$payment_intent['customer'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '================= Card      > '.$payment_intent['source'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '================= Amount    > '.$payment_intent['amount'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '================= Method    > '.$payment_intent['capture_method'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '================= Status    > '.$payment_intent['status'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Payment intent ========> '.$payment_intent->id.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($payment_intent, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
$txt = 'Payment Intent KO ========== '.PHP_EOL; fwrite($this->myfile, $txt);
            $response['status'] = 'KO';
            $response['result']['msg'] = $payment_intent_response['result']['msg']; //'PAYMENT_PAYMENT_NOT_SUCCESS';
            $response['result']['data'] = $payment_intent_response['result']['data'];

//$txt = '================= Payment intent FAILED ================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '================= Payment intent Id > '.$payment_intent_response['id'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '================= Quote     > '.$payment_intent_response['metadata']['quote'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '================= Customer  > '.$payment_intent_response['customer'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '================= Card      > '.$payment_intent_response['source'].PHP_EOL; fwrite($this->myfile, $txt);
        }

//$txt = 'Response ========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = '======================================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $response;
    }
}