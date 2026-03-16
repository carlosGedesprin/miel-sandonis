<?php

namespace src\controller\payment_system;

use \src\controller\baseController;

use src\controller\entity\accountController;
use src\controller\entity\accountFundsController;
use \src\controller\entity\paymentTransactionController;
use \src\controller\entity\quoteController;
use \src\controller\entity\mailQueueController;
use \src\controller\payment_system\paymentResultController;

use DateTime;
use DateTimeZone;

class paymentFundsController extends baseController
{
    private $payment_system = 'Funds';

    /**
     *
     * Treating payment with Account funds
     *
     * @param $quote object Quote to be paid object
     *
     * @throws
     * @return $result
     */
    public function payQuoteAction( $quote )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/fundsController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Quote to pay ========== '.$quote->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $account_funds = new accountFundsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_transaction = new paymentTransactionController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_result_controller = new paymentResultController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_funds->getRegbyId( $quote->getAccount() );

        $payment_transaction->setId('');
        $payment_transaction->setOrigin('quote');
        $payment_transaction->setQuote( $quote->getId() );
        $payment_transaction->setAccount( $quote->getAccount() );
        $payment_transaction->setAccountPaymentMethod( $quote->getPaymentMethod() );
        $payment_transaction->setDateReg( $now );
        $payment_transaction->setPaymentType(PAYMENT_TYPE_FUNDS);
        //$payment_transaction->setOriginId( $data->id );
        //$payment_transaction->setTransactionId( $data->id );
        //$payment_transaction->setTransaction( $event );

        if ( $account_funds->getBalancebyAccount( $quote->getAccount() ) >= $quote->getTotalToPay() )
        {
            $account_funds->setId( '' );
            $account_funds->setFundingKey('34287' . $quote->getAccount() . '-' . $quote->getId());
            $account_funds->setAccount( $quote->getAccount() );
            //$account_funds->setUser( '' );
            $account_funds->setDate( $now );
            $account_funds->setDescription($this->lang['PAY_A_QUOTE_FUND_DESCRIPTION'].' '.$quote->getId());
            //$account_funds->setPaymentType( PAYMENT_TYPE_FUNDS );
            $account_funds->setCredit( '' );
            $account_funds->setDebit( $quote->getTotalToPay() );
            $account_funds->persistORL();
//$txt = 'Payment with funds ========== '.$account_funds->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $account_funds->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $payment_transaction->setResult('succeeded');
            $payment_transaction->setEventId( $account_funds->getId() );
//$txt = 'Payment transaction ========= '.$payment_transaction->getId().PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $payment_transaction->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $payment_transaction->persist();

            $quote->setPaymentReference( $payment_transaction->getId() );
            $quote->persist();

            $payment_result_controller->paymentResultSuccess( $quote, $payment_transaction );

            $response = array(
                                'status' => 'OK',
                                'result' => array (
                                                    'msg' => 'succeeded'
                                            ),
            );
        }
        else
        {
//$txt = 'Going to show failed ========= '.PHP_EOL;fwrite($this->myfile, $txt);

            $payment_transaction->setResult('failed: '.$this->lang['ACCOUNT_FUNDS_NOT_ENOUGHT'].' '.$account_funds->getBalancebyAccount( $quote->getAccount() ).$this->session->config['web_currency'] );
            //$payment_transaction->setEventId( '' );
//$txt = 'Payment transaction ========= '.$payment_transaction->getId().PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $payment_transaction->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $payment_transaction->persist();

            $payment_result_controller->paymentResultFailed( $quote, $payment_transaction);

            $response = array(
                                'status' => 'KO',
                                'result' => array (
                                                    'msg' => 'failed'
                                ),
            );
        }


//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $response;
    }

    /**
     *
     * Treating successful payment intent with Stripe
     *
     * @param $event array Stripe event response object
     *
     * @return void
     */
    private function paymentSuccessful( $event )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );
if ( $_ENV['env_env'] == 'dev') { $folder = '';} else { $folder = 'payments/';}
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/'.$folder.'/payment_stripeController_'.__FUNCTION__.'_'.$now->format('Y_m_d').'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==== '.$now->format('d-m-Y  H:i:s').' ============================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Stripe webhook response '.$event->id.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $event->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $payment_transaction = new paymentTransactionController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_result_controller = new paymentResultController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $data = $event->data->object;

        $payment_transaction->setId( '' );

        if ( $data->metadata['origin'] == 'quote' )
        {
//$txt = 'Quote key ========= '.$data->metadata['quote'].PHP_EOL; fwrite($this->myfile, $txt);
            $origin_object = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
            $origin_object->getRegbyQuoteKey( $data->metadata['quote'] );
//$txt = 'Quote found ========= '.$origin_object->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $origin_object->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $payment_transaction->setOrigin( 'quote' );
            $payment_transaction->setQuote( $origin_object->getId() );

            $method_to_load = 'paymentResultSuccess';
        }
        elseif ( $data->metadata['origin'] == 'funding' )
        {
//$txt = 'Lead funding token ========= '.$data->metadata['funding_token'].PHP_EOL; fwrite($this->myfile, $txt);
            $origin_object = new leadFundingController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
            $origin_object->getRegbyToken( $data->metadata['funding_token'] );
//$txt = 'Lead funding found ========= '.$origin_object->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $origin_object->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $payment_transaction->setOrigin( 'funding' );
            $payment_transaction->setFunding( $origin_object->getId() );

            $method_to_load = 'fundResultSuccess';
        }

        $payment_transaction->setAccount( $origin_object->getAccount() );
        $payment_transaction->setAccountPaymentMethod( $origin_object->getAccountPaymentMethod() );

        $date = new DateTime();
        $date->setTimestamp( $event->created );
        $payment_transaction->setDateReg( $date );

        $payment_transaction->setPaymentType( PAYMENT_TYPE_STRIPE );
        $payment_transaction->setResult( 'succeeded' );
        $payment_transaction->setEventId( $event->id );
        $payment_transaction->setOriginId( $data->id );
        $payment_transaction->setTransactionId( $data->id );
        $payment_transaction->setTransaction( $event );
        $payment_transaction->persist();
//$txt = 'Payment transaction ========= '.$payment_transaction->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $payment_transaction->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Send to '.get_class($payment_result_controller).' ========= '.$method_to_load.' -> origin_object '.$origin_object->getId().' transaction ->'.$payment_transaction->getId().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $payment_result_controller->$method_to_load( $origin_object, $payment_transaction );

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Treating failed payment intent
     *
     * @param $event array Stripe event response object
     *
     * @return void
     */
    private function paymentFailed( $event )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/stripeController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Stripe webhook response'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $event, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $payment_transaction = new paymentTransactionController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_result_controller = new paymentResultController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $data = $event->data->object;

        $payment_transaction->setId( '' );

        if ( $data->metadata['origin'] == 'quote' )
        {
//$txt = 'Quote key ========= '.$data->metadata['quote'].PHP_EOL; fwrite($this->myfile, $txt);
            $origin_object = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
            $origin_object->getRegbyQuoteKey( $data->metadata['quote'] );

//$txt = 'Quote found ========= '.$origin_object->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $origin_object->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $payment_transaction->setOrigin( 'quote' );
            $payment_transaction->setQuote( $origin_object->getId() );

            $method_to_load = 'paymentResultFailed';
        }
        elseif ( $data->metadata['origin'] == 'funding' )
        {
//$txt = 'Lead funding token ========= '.$data->metadata['funding_token'].PHP_EOL; fwrite($this->myfile, $txt);
            $origin_object = new leadFundingController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
            $origin_object->getRegbyToken( $data->metadata['funding_token'] );

            $payment_transaction->setOrigin( 'funding' );
            $payment_transaction->setFunding( $origin_object->getId() );

            $method_to_load = 'fundResultFailed';
        }

        $payment_transaction->setAccount( $origin_object->getAccount() );
        $payment_transaction->setAccountPaymentMethod( $origin_object->getAccountPaymentMethod() );

        $date = new DateTime();
        $date->setTimestamp( $event->created );
        $payment_transaction->setDateReg( $date );

        $payment_transaction->setPaymentType( PAYMENT_TYPE_STRIPE );
//$txt = 'last_payment_error ========= '.$data['last_payment_error'].'===='.$data['last_payment_error']['code'].PHP_EOL; fwrite($this->myfile, $txt);
        $payment_transaction->setResult( $data['last_payment_error']['code'] );
        $payment_transaction->setEventId( $event->id );
        $payment_transaction->setOriginId( $data->id );
//$txt = 'charge id ========= '.$data['charges']['data'].'===='.$data['charges']['data'][0]['id'].PHP_EOL; fwrite($this->myfile, $txt);
        $payment_transaction->setTransactionId( $data['charges']['data'][0]['id'] );
        $payment_transaction->setTransaction( $event );
//$txt = 'Payment transaction ========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $payment_transaction->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $payment_transaction->persist();

        $payment_result_controller->$method_to_load( $origin_object, $payment_transaction );

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}