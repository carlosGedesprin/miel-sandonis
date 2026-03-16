<?php

namespace src\controller\payment_system;

use \src\controller\baseController;

use \src\controller\entity\paymentTransactionController;
use \src\controller\entity\quoteController;
use \src\controller\entity\leadFundingController;
use \src\controller\payment_system\paymentResultController;
use \src\controller\entity\mailQueueController;

use DateTime;
use DateTimeZone;

class stripeController extends baseController
{
    private $payment_system = 'Stripe';

    /**
     * 
     * Webhook Stripe
     * 
     * @Route("/payments/stripe_things", name="payments_stripe_things")
     */
    public function WebhookStripe()
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $payload = @file_get_contents("php://input");
        $event = null;

if ( $_ENV['env_env'] == 'dev') { $folder = '';} else { $folder = 'payments/';}
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/'.$folder.'payment_stripe_webHook_'.$now->format('Y_m_d_H_i_s').'.txt', 'a+') or die('Unable to open file!');
$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/'.$folder.'payment_stripe_webHook.txt', 'a+') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ==== '.$now->format('d-m-Y  H:i:s').' ==========================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==== '.$now->format('d-m-Y  H:i:s').' ==========================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        try {
            $event = \Stripe\Webhook::constructEvent( $payload, $sig_header, $_ENV['stripe_w'] );
        }
        catch(\Stripe\Exception\UnexpectedValueException $e)
        {
            // Invalid payload
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Stripe payment Error -> Invalid payload.');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Payload ('.$payload.')');
            $this->logger_err->error('==================================================');
            $this->logger_err->error('Signature header ('.$sig_header.')');
            $this->logger_err->error('==================================================');
            $this->logger_err->error('Endpoint secret ('.$_ENV['stripe_w'].')');
            $this->logger_err->error('==================================================');
            $this->logger_err->error('Error ('.$e.')');
            $this->logger_err->error('*************************************************************************');
            http_response_code(400);
            exit();
        }
        catch(\Stripe\Exception\SignatureVerificationException $e)
        {
            // Invalid signature
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Stripe payment Error -> Invalid signature.');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Payload ('.$payload.')');
            $this->logger_err->error('==================================================');
            $this->logger_err->error('Signature header ('.$sig_header.')');
            $this->logger_err->error('==================================================');
            $this->logger_err->error('Endpoint secret ('.$_ENV['stripe_w'].')');
            $this->logger_err->error('==================================================');
            $this->logger_err->error('Error ('.$e.')');
            $this->logger_err->error('*************************************************************************');
            http_response_code(400);
            exit();
        }

//if ( $_ENV['env_env'] == 'dev') { $folder = '';} else { $folder = 'payments/';}
//$log_file_name = 'payment_stripe_webHook_'.str_replace('.', '_', $event['type']).'_'.$now->format('Y_m_d_H_i_s').'.txt';
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/'.$folder.$log_file_name, 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==== '.$now->format('d-m-Y  H:i:s').' ==========================================================='.PHP_EOL; fwrite($this->myfile, $txt);

$txt = PHP_EOL.'Stripe Webhook incoming event: '.$event['type'].''.PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $event, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        switch ( $event['type'] ) {
            // *********************************************
            // ******** Customer ***************************
            // *********************************************
            case 'customer.created':
//$txt = 'customer.created => do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->customerCreated( $event );
                break;
            case 'customer.updated':
//$txt = 'customer.deleted => do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                break;
            case 'customer.deleted':
//$txt = 'customer.deleted => do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                break;
            // *********************************************
            // ******** Payment methods ********************
            // *********************************************
            case 'customer.source.created':
//$txt = 'customer.source.created => do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                break;
            case 'payment_method.attached':
//$txt = 'payment_method.attached => do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                break;
            case 'payment_method.updated':
//$txt = 'payment_method.attached => do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->eventNotDeveloped( $event['type'], $event['data'] );
                break;
            case 'payment_method.automatically_updated':
//$txt = 'payment_method.attached => do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->eventNotDeveloped( $event['type'], $event['data'] );
                break;
            // *********************************************
            // ******** Charges ********************
            // *********************************************
            case 'charge.succeeded':
//$txt = 'charge.succeeded ==========> Do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                break;
            case 'charge.failed':
//$txt = 'charge.failed ==========> Do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                break;
            // *********************************************
            // ******** Payment intends ********************
            // *********************************************
            case 'payment_intent.created':
//$txt = 'payment_intent.created => calling $this->paymentIntentCreated'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->paymentIntentCreated( $event );
                break;
            case 'payment_intent.succeeded':
//$txt = 'payment_intent.succeeded => calling $this->paymentSuccessful'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->paymentSuccessful( $event );
                break;
            case 'payment_intent.requires_action':
$txt = 'payment_intent.requires_action => calling this->paymentNeedsAuthAction'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->paymentNeedsAuthAction( $event );
                break;
            case 'payment_intent.payment_failed':
$txt = 'payment_intent.payment_failed => calling this->paymentFailed'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->paymentFailed( $event );
                break;
                // *********************************************
                // ******** Disputes ***************************
                // *********************************************
            case 'charge.dispute.created':
$txt = 'charge.dispute.created ==========> Do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->eventNotDeveloped( $event['type'], $event['data'] );
                break;
            case 'charge.dispute.updated':
$txt = 'charge.dispute.updated ==========> Do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->eventNotDeveloped( $event['type'], $event['data'] );
                break;
            case 'charge.dispute.closed':
$txt = 'charge.dispute.closed ==========> Do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->eventNotDeveloped( $event['type'], $event['data'] );
                break;
                // *********************************************
                // ******** Balance ***************************
                // *********************************************
            case 'balance.available':
$txt = 'balance.available ==========> Do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->eventNotDeveloped( $event['type'], $event['data'] );
                break;
                // *********************************************
                // ******** Payout ***************************
                // *********************************************
            case 'payout.created':
$txt = 'balance.available ==========> Do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->eventNotDeveloped( $event['type'], $event['data'] );
                break;
            case 'payout.reconciliation_completed':
$txt = 'balance.available ==========> Do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->eventNotDeveloped( $event['type'], $event['data'] );
                break;
            case 'payout.paid':
$txt = 'balance.available ==========> Do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->eventNotDeveloped( $event['type'], $event['data'] );
                break;
            default:
$txt = PHP_EOL.'Untreated event type ('.$event['type'].')'.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'calling this->eventNotTreated'.PHP_EOL; fwrite($this->myfile, $txt);
                $this->eventNotDeveloped( $event['type'], $event['data'] );
        }

        http_response_code(200);
        
$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
fclose($this->myfile);
    }

    /**
     * 
     * Treating customer creation in stripe
     * 
     * @param $event array Stripe event response object
     *
     * @return void
     */
    private function customerCreated( $event )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/stripeController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Stripe webhook response ============='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $event->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
    }

    /**
     *
     * Treating payment intent creation in stripe
     *
     * @param $event object Stripe event response object
     *
     * @return void
     */
    private function paymentIntentCreated( $event )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/stripeController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Stripe webhook response ============='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r( $event, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $data = $event->data->object;

        $payment_transaction = new paymentTransactionController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

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
        }
        elseif ( $data->metadata['origin'] == 'funding' )
        {
            $origin_object = new leadFundingController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
            $origin_object->getRegbyToken( $data->metadata['funding_token'] );
//$txt = 'Quote found ========= '.$origin_object->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $origin_object->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            $payment_transaction->setOrigin( 'funding' );
            $payment_transaction->setFunding( $origin_object->getId() );
        }

        $payment_transaction->setAccount( $origin_object->getAccount() );
        $payment_transaction->setAccountPaymentMethod( $origin_object->getAccountPaymentMethod() );

        $date = new DateTime();
        $date->setTimestamp( $event->created );
        $payment_transaction->setDateReg( $date );

        $payment_transaction->setPaymentType( PAYMENT_TYPE_STRIPE );
        $payment_transaction->setResult( 'created' );
        $payment_transaction->setEventId( $event->id );
        //$payment_transaction->setOriginId( '' );
        $payment_transaction->setTransactionId( $data->id );
        $payment_transaction->setTransaction( $event );
//$txt = 'Payment transaction ========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $payment_transaction->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $payment_transaction->persist();

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
    }

    /**
     *
     * Treating successful payment intent
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
$txt = '====================== '.__METHOD__.' start ==== '.$now->format('d-m-Y  H:i:s').' ============================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Stripe webhook response '.$event->id.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $event->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $payment_transaction = new paymentTransactionController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_result_controller = new paymentResultController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $data = $event->data->object;

        $payment_transaction->setId( '' );

        if ( $data->metadata['origin'] == 'quote' )
        {
$txt = 'Quote key ========= '.$data->metadata['quote'].PHP_EOL; fwrite($this->myfile, $txt);
            $origin_object = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
            $origin_object->getRegbyQuoteKey( $data->metadata['quote'] );
$txt = 'Quote found ========= '.$origin_object->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $origin_object->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $payment_transaction->setOrigin( 'quote' );
            $payment_transaction->setQuote( $origin_object->getId() );

            $method_to_load = 'paymentResultSuccess';
        }
        elseif ( $data->metadata['origin'] == 'funding' )
        {
$txt = 'Lead funding token ========= '.$data->metadata['funding_token'].PHP_EOL; fwrite($this->myfile, $txt);
            $origin_object = new leadFundingController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
            $origin_object->getRegbyToken( $data->metadata['funding_token'] );
$txt = 'Lead funding found ========= '.$origin_object->getId().PHP_EOL; fwrite($this->myfile, $txt);
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
$txt = 'Payment transaction ========= '.$payment_transaction->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $payment_transaction->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

$txt = 'Send to '.get_class($payment_result_controller).' ========= '.$method_to_load.' -> origin_object '.$origin_object->getId().' transaction ->'.$payment_transaction->getId().PHP_EOL.PHP_EOL; fwrite($this->myfile, $txt);
        $payment_result_controller->$method_to_load( $origin_object, $payment_transaction );

$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
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
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Stripe webhook response'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $event, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $payment_transaction = new paymentTransactionController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_result_controller = new paymentResultController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $data = $event->data->object;

        $payment_transaction->setId( '' );

        if ( $data->metadata['origin'] == 'quote' )
        {
$txt = 'Quote key ========= '.$data->metadata['quote'].PHP_EOL; fwrite($this->myfile, $txt);
            $origin_object = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
            $origin_object->getRegbyQuoteKey( $data->metadata['quote'] );

$txt = 'Quote found ========= '.$origin_object->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $origin_object->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $payment_transaction->setOrigin( 'quote' );
            $payment_transaction->setQuote( $origin_object->getId() );

            $method_to_load = 'paymentResultFailed';
        }
        elseif ( $data->metadata['origin'] == 'funding' )
        {
$txt = 'Lead funding token ========= '.$data->metadata['funding_token'].PHP_EOL; fwrite($this->myfile, $txt);
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
$txt = 'Payment transaction ========='.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r( $payment_transaction->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $payment_transaction->persist();

        $payment_result_controller->$method_to_load( $origin_object, $payment_transaction );

$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     * 
     * Send mail to authorize payment
     *
     * @param $event array Stripe event response object
     *
     * @return void
     */
    private function paymentNeedsAuthAction( $event )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/stripeController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Stripe webhook response ============='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $event, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $payment_transaction = new paymentTransactionController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_result_controller = new paymentResultController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $data = $event->data->object;

        if ( $data->metadata['origin'] == 'quote' )
        {
$txt = 'Quote key ========= '.$data->metadata['quote'].PHP_EOL; fwrite($this->myfile, $txt);
            $origin_object = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
            $origin_object->getRegbyQuoteKey( $data->metadata['quote'] );

$txt = 'Quote found ========= '.$origin_object->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $origin_object->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $payment_transaction->setOrigin( 'quote' );
            $payment_transaction->setQuote( $origin_object->getId() );

            $method_to_load = 'paymentMailRequireAuth';
        }
        elseif ( $data->metadata['origin'] == 'funding' )
        {
$txt = 'Lead funding token ========= '.$data->metadata['funding_token'].PHP_EOL; fwrite($this->myfile, $txt);
            $origin_object = new leadFundingController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
            $origin_object->getRegbyToken( $data->metadata['funding_token'] );

            $payment_transaction->setOrigin( 'funding' );
            $payment_transaction->setFunding( $origin_object->getId() );

            $method_to_load = 'fundMailRequireAuth';
        }

        $payment_transaction->setId( '' );

        $payment_transaction->setAccount( $origin_object->getAccount() );
        $payment_transaction->setAccountPaymentMethod( $origin_object->getAccountPaymentMethod() );

        $date = new DateTime();
        $date->setTimestamp( $event->created );
        $payment_transaction->setDateReg( $date );

        $payment_transaction->setPaymentType( PAYMENT_TYPE_STRIPE );
        $payment_transaction->setResult( 'need_auth' );
        $payment_transaction->setEventId( $event->id );
        $payment_transaction->setOriginId( $data->id );
        $payment_transaction->setTransactionId( $data->id );
        $payment_transaction->setTransaction( $event );
        $payment_transaction->persist();
$txt = 'Payment transaction ========= '.$payment_transaction->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $payment_transaction->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
/*
        $data = array();

        if ( !empty( $event['auth_card'] ) )
        {
            $data['payment_data'] = isset( $event['auth_card'] ) ? $event['auth_card'] : '';
        }
$txt = 'data_stripe '.PHP_EOL.print_r($data, true).' '.PHP_EOL; fwrite($this->myfile, $txt);

        $this->utils->setStripeKey( $_ENV['stripe_s'] );

        $customer = $this->utils->retrieveStripeCustomer( $data['payment_data']['customer'] );

        $account_key = $customer->metadata['account'];

        $payment_origin = $data['payment_data']->metadata['payment_origin'];

*/
$txt = 'Payment intent ========= '.$payment_transaction->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $next_action = $data['next_action'];
//        $auth_link = ( $next_action['type'] == 'use_stripe_sdk' )? $next_action['use_stripe_sdk']['stripe_js'] : $next_action['redirect_to_url']['url'];
        $auth_link = $next_action['redirect_to_url']['url'];
$txt = 'Auth link ========= '.$auth_link.PHP_EOL; fwrite($this->myfile, $txt);

        $payment_result_controller->$method_to_load( $origin_object, $payment_transaction, $auth_link );

$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *  Send mail to alert event not treated
     *
     * @param $event array Stripe event response object
     *
     * @return void
     */
    private function eventNotTreated( $event_type, $event )
    {
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Event type ('.$event_type.')'.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Stripe webhook response'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r( $event->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue->setId( '' );

        $mailQueue->setToName( 'Carlos' );
        $mailQueue->setLocale( 'es' );
        $mailQueue->setToAddress( 'carlos@'.$_ENV['domain'] );

        $mailQueue->setTemplate('custom_message');
        $mailQueue->setProcess(__METHOD__ );

        $mailQueue->setSubject( 'Stripe WebHook event not treated ('.$event_type.')' );
        $mailQueue->setPreheader( 'Stripe WebHook event not treated' );

        $message = $event;
        $mailQueue->addAssignVar( 'message', $message );

        $mailQueue->persist();
$txt = 'Mail event not treated'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r( $mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *  Send mail to alert event not developed
     *
     * @param $event array Stripe event response object
     *
     * @return void
     */
    private function eventNotDeveloped( $event_type, $event )
    {
$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Event type ('.$event_type.')'.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Stripe webhook response'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r( $event->data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue->setId( '' );

        $mailQueue->setToName( 'Carlos' );
        $mailQueue->setLocale( 'es' );
        $mailQueue->setToAddress( 'carlos@'.$_ENV['domain'] );

        $mailQueue->setTemplate('custom_message');
        $mailQueue->setProcess(__METHOD__ );

        $mailQueue->setSubject( 'Stripe WebHook event not developed ('.$event_type.')' );
        $mailQueue->setPreheader( 'Stripe WebHook event not developed' );

        $message = $event;
        $mailQueue->addAssignVar( 'message', $message );

        $mailQueue->persist();
$txt = 'Mail event not developed'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r( $mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
}