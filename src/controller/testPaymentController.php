<?php

namespace src\controller;

use src\controller\entity\accountController;
use src\controller\entity\accountPaymentMethodController;
use src\controller\entity\accountFundsController;
use src\controller\entity\domainController;
use src\controller\entity\automationController;
use src\controller\entity\langTextController;
use src\controller\entity\mailQueueController;
use src\controller\entity\quoteController;
use src\controller\entity\WCAGReportController;
use src\controller\entity\widgetController;
use src\controller\payment_system\paymentStripeController;

use DateTime;
use DateTimeZone;
use Exception;

class testPaymentController extends baseViewController
{
    /**
     * Create customer in stripe
     *
     */
    public function createCustomerTest( $vars )
    {
$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/test_testPaymentController_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
$txt = '======================================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account->getRegbyId( $vars['account'] );

        $paymentStripe = new paymentStripeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

$txt = 'Account to send ----------------------------->' . PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($account->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $response = $this->utils->createStripeCustomer( $account );

        if ( $response['status'] == 'OK' )
        {
            $account->setPreferredPaymentType( PAYMENT_TYPE_STRIPE );
            $account->setStripeId( $response['result']['data']['id'] );
            $account->persist();
$txt = 'Customer Stripe Id ==========> '.$response['result']['data']['id'].PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($response['result']['data'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
$txt = 'Creating customer Stripe error =========='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Stripe error =========='.$response['result']['msg'].PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($response['result']['data'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        }

$txt = '======================================== ' . __METHOD__ . ' end ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);
        return '<pre>'.print_r($response, TRUE).'</pre>';
    }

    /**
     * Stripe tests
     *
     */
    public function stripeWebhookTest()
    {
$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/test_testPaymentController_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
$txt = '======================================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);
        /*
        // Missing arg
        $args = array (
                        // The required parameter currency is missing
                        'amount' => 2000,
                        'confirm' => True,
                        'payment_method' => 'pm_card_visa',
        );
        */
        /*
        // Card declined
        $args = array (
                        'currency' => 'eur',
                        'amount' => 2000,
                        'confirm' => True,
                        'payment_method' => 'pm_card_chargeDeclinedFraudulent',
        );
        */
        /*
        // This is OK, but too general
        $args = array (
            'currency' => 'eur',
            'amount' => 2000,
            'confirm' => True,
            'payment_method' => 'pm_card_visa',
            'metadata' => array('quote' => 'test0000000000000000000000000003'),
        );
        */
        // This is OK
        $args = array (
            'currency' => 'eur',
            'amount' => 2000,
            'confirm' => True,
            'payment_method' => 'card_1MuI0aH46FFvjTT7iwuccpEO',
            'metadata' => array('quote' => 'test0000000000000000000000000003'),
        );
        $payment_intent_response = $this->utils->createTestPI( $args );

$txt = '======================================== ' . __METHOD__ . ' end ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);
        return '<pre>'.print_r($payment_intent_response, TRUE).'</pre>';
    }

    /**
     * Pay a quote with stripe
     *
     */
    public function payQuoteTest( $vars )
    {
$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/test_testPaymentController_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
$txt = '======================================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $paymentStripe = new paymentStripeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote->getRegbyQuoteKey( $vars['quote'] );
$txt = 'Quote to pay ----------------------------->' . PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $response = $paymentStripe->payQuoteAction( $quote );

        if ( $response['status'] == 'OK' )
        {
$txt = 'Pay quote payment intent Id ==========> '.$response['result']['data']['id'].PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($response['result']['data'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
$txt = 'Creating Stripe payment intent error =========='.PHP_EOL; fwrite($this->myfile, $txt);
$txt = 'Stripe error =========='.$response['result']['msg'].PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($response['result']['data'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        }
$txt = 'Payment result ------------------------------------------------------------------------------' . PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = '======================================== ' . __METHOD__ . ' end ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);
        return '<pre>'.print_r($response, TRUE).'</pre>';
    }

    /**
     * Pay a quote with stripe
     *
     */
    public function newPItest()
    {
$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/test_testPaymentController_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
$txt = '======================================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);
        $response = array();

        $quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $quote->getRegbyQuoteKey( 'test0000000000000000000000000003' );
$txt = 'Quote ==========> '.$quote->getId().PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account->getRegbyId( $quote->getAccount() );
$txt = 'Account ==========> '.$account->getId().PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $account_payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account_payment_method->getRegbyId( $quote->getPaymentMethod() );
$txt = 'Payment method ==========> '.$account_payment_method->getId().PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($account_payment_method->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        // This is OK
        $args = array (
            'customer' => $account->getStripeId(),
            'currency' => $this->db->fetchField('config', 'config_value', ['config_name' => 'web_currency']), //'eur',
            'amount' => 2000,
            'confirm' => True,
            'payment_method' => $account_payment_method->getObjectId(), //'card_1MuI0aH46FFvjTT7iwuccpEO',
            'metadata' => array('quote' => 'test0000000000000000000000000003'),
            //'statement_descriptor' => 'Custom descriptor', //descripcion cargo en extracto bancario menos de 22 caracteres,
            //'setup_future_usage' => 'off_session',
            'return_url' => $_ENV['protocol'].'://'.$_ENV['domain'].'/payments/auth_payment_result/'.$quote->getQuoteKey(),
            //'use_stripe_sdk' => false,
        );
        $response = $this->utils->createTestPI( $args );

        if ( $response['status'] == 'OK' )
        {
$txt = 'Payment intent Id ==========> '.$response['result']['data']['id'].PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        }
        else
        {
$txt = 'Payment intent Error ==========> '.$response['result']['data']['message'].PHP_EOL; fwrite($this->myfile, $txt);
        }
fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
$txt = '======================================== ' . __METHOD__ . ' end ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);
        return '<pre>'.print_r($response, TRUE).'</pre>';
    }

    /**
     * Pay a quote with stripe
     *
     */
    public function testPaymentResultView()
    {
$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/test_testPaymentController_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
$txt = '======================================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $data = array (
            'next_action' => '/app/widgets',
        );
$txt = '======================================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/info_payment_success.html.twig', array(
            'data' => $data,
        ));
    }

    /**
     * Test free payment page
     *
     */
    public function testFreePaymentResultView()
    {
$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/test_testPaymentController_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
$txt = '======================================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $data = array (
            'next_action' => '/app/widgets',
        );
$txt = '======================================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/info_payment_free.html.twig', array(
            'data' => $data,
        ));
    }

    /**
     * Test account funds refill
     *
     */
    public function testAccountFundBalanceView()
    {
$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/test_testPaymentController_' . __FUNCTION__ . '.txt', 'w') or die('Unable to open file!');
$txt = '======================================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);

        $data = array (
        );

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account->getRegbyId( '9' );
        $account_funds = new accountFundsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $balance = $account_funds->enoughBalanceInAccount( $account->getId() );

$txt = '======================================== ' . __METHOD__ . ' start ===============================================================' . PHP_EOL; fwrite($this->myfile, $txt);
        return (floatval($balance / 100));
    }
}