<?php

namespace src\controller\views\payments;

use \src\controller\baseViewController;

use \src\controller\entity\configController;
use \src\controller\entity\accountController;
use \src\controller\entity\accountPaymentDetailsController;
use \src\controller\entity\accountPaymentMethodController;
use \src\controller\entity\planController;
use \src\controller\entity\productController;
use \src\controller\entity\quoteController;
use \src\controller\entity\quoteLineController;
use \src\controller\entity\quoteExtraController;

use DateTime;
use DateTimeZone;
use Exception;

class paymentStripeViewController extends baseViewController
{
    /**
     *
     * Quote payment online
     *
     * Get a new source and tries to pay a quote with this new source
     *
     * @Route("/payments/pay_a_quote/d0fc4acbd986c8f3dafe/quote_key", name="payments_pay_a_quote_d0fc4acbd986c8f3dafe_quote_key")
     *
     */
    public function getSourceAndpayQuoteAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentStripeViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $data = array (
            'quote_key'  => $this->utils->request_var( 'quote_key', $vars['quote_key'], 'ALL'),
            'stripeToken' => ( isset($_POST['stripeToken']) )? $_POST['stripeToken'] : '',
            'terms_conditions' => ( isset($_POST['terms_conditions']) )? TRUE : FALSE,
            'payment_terms_agreement' => sprintf($this->lang['PAYMENT_TERMS_AGREEMENT'], $this->session->config['web_name']),
            'has_cards' => '',
            'make_preferred' => ( isset($_POST['make_preferred']) )? TRUE : FALSE,
//            'make_preferred'  => filter_input(INPUT_POST, 'make_preferred', FILTER_SANITIZE_STRING), //$this->utils->request_var( 'make_preferred', '1', 'ALL'),
            'submit' => ( isset($_POST['submitted']) )? '1' : '',
        );

//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Lang =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($this->lang, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        
        $config = new configController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $plan = new planController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        
        $quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        
        $quote_line = new quoteLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account_payment_method_preferred = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $error = array();

        if ( !$quote->getRegbyQuoteKey( $data['quote_key'] ) )
        {
//$txt = 'Quote not found ('.$data['quote_key'].')'.PHP_EOL; fwrite($this->myfile, $txt);
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_PAYMENT_NO_QUOTE'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
//$txt = 'Quote found'.PHP_EOL; fwrite($this->myfile, $txt);fwrite($this->myfile, print_r($quote->getReg(), TRUE));

        if ( $quote->getInvoice() )
        {
//$txt = 'Quote paid '.$quote->getInvoice().PHP_EOL; fwrite($this->myfile, $txt);fwrite($this->myfile, print_r($quote->getReg(), TRUE));
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_QUOTE_ALREADY_PAID'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }

        $lines = $quote_line->getLinesbyQuote( $quote->getId() );
//$txt = 'Quote lines =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lines, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        foreach ( $lines as $line )
        {
            $quote_line->getRegbyId( $line['id'] );
//$txt = 'Quote line =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            if ( $plan->getRegbyPlanKey( $quote_line->getProduct() ) )
            {
//$txt = 'Plan found =========='.PHP_EOL; fwrite($this->myfile, $txt);
                return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['PAYMENT_SECTION'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_QUOTE_CHOOSE_PRODUCT'],
                    'redirect_wait' => '5000',
                    'redirect' => '/payments/choose_quote_product/'.$quote->getQuoteKey(),
                ));
            }
//$txt = 'Product found =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        }

        if ( !$account->getRegbyId( $quote->getAccount() ) )
        {
//$txt = 'Account not found '.PHP_EOL; fwrite($this->myfile, $txt);
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_ACCOUNT_NOT_EXISTS'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
//$txt = 'Account found'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account->getReg(), TRUE));

        if ( !empty($data['submit']) )
        {
            if ( $data['stripeToken'] == '' )
            {
//$txt = 'No Stripe token ==================='.PHP_EOL; fwrite($this->myfile, $txt);
                $error[] = $this->lang['ERR_PAYMENT_NO_STRIPE_TOKEN'];
            }

            if ( !$data['terms_conditions'] )
            {
//$txt = 'No terms'.PHP_EOL; fwrite($this->myfile, $txt);
                $error[] = $this->lang['ERR_TERMS_MUST_AGREED'];
            }

//$txt = 'Errors found if any =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($error, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            if ( !sizeof($error) )
            {
//$txt = 'No errors found =========='.PHP_EOL; fwrite($this->myfile, $txt);
                if ( !$account->getStripeId() )
                {
//$txt = 'No account Stripe id =====> Create Stripe ID'.PHP_EOL; fwrite($this->myfile, $txt);

                    $response = $this->utils->createStripeCustomer( $account );

                    if ( $response['status'] == 'OK' )
                    {
                        $account->setPreferredPaymentType( PAYMENT_TYPE_STRIPE );
                        $account->setStripeId( $response['result']['data']['id'] );
                        $account->persist();
//$txt = 'Stripe customer Id ==========> '.$response['result']['data']['id'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response['result']['data'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    }
                    else
                    {
//$txt = 'Error creating Stripe customer =========='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Stripe error =========='.$response['result']['msg'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response['result']['data'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                        return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                            'section' => $this->lang['PAYMENT_SECTION'],
                            'alert_type' => 'danger',
                            'title' => $this->lang['WARNING'],
                            'message' => $this->lang['ERR_PAYMENT_CUSTOMER_CREATION'], //$response['result']['msg'], //$e->getError()->message,
                            'redirect_wait' => '5000',
                            'redirect' => '/payments/pay_a_quote/'.$data['quote_key'],
                        ));
                    }
                }
//$txt = 'Account Stripe id =====> '.$account->getStripeId().PHP_EOL; fwrite($this->myfile, $txt);

                $response = $this->utils->addStripeSourceToCustomer( $account->getStripeId(), $data['stripeToken'] );
                if ( $response['status'] == 'OK' )
                {
//$txt = 'Source added to Customer in Stripe  ========== '.$response['result']['data']['id'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response['result']['data'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $account_payment_method->setId('');
                    $account_payment_method->setKey( md5( $response['result']['data']['id'] ) );
                    $account_payment_method->setAccount( $account->getId() );
                    $account_payment_method->setPaymentType( PAYMENT_TYPE_STRIPE );
                    $account_payment_method->setName( $account->getName().' '.$response['result']['data']['last4'] );
                    $account_payment_method->setObject('card');
                    $account_payment_method->setObjectId($response['result']['data']['id']);
                    $account_payment_method->setLast4( $response['result']['data']['last4'] );
                    $account_payment_method->setExpMonth( $response['result']['data']['exp_month'] );
                    $account_payment_method->setExpYear( $response['result']['data']['exp_year'] );
                    $account_payment_method->setBrand( $response['result']['data']['brand'] );
                    $account_payment_method->setCountry( $response['result']['data']['country'] );
                    $account_payment_method->setCVCCheck( $response['result']['data']['cvc_check'] );
                    $account_payment_method->setFunding( $response['result']['data']['funding'] );

                    $account_payment_methods = $account_payment_method->getAll(['account' => $account->getId()]);
                    switch ( true )
                    {
                        case sizeof( $account_payment_methods ) == 0:
                            $account_payment_method->setPreferred( '1' );
                            break;
                        case sizeof( $account_payment_methods ) > 0:
                            if ( $data['make_preferred'] )
                            {
//$txt = 'Account payment methods =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_payment_methods, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                                foreach ( $account_payment_methods as $account_payment_method_temp )
                                {
                                    $account_payment_method_preferred->getRegbyId( $account_payment_method_temp['id'] );
//$txt = 'Account payment method treated ('.$account_payment_method_preferred->getId().') ('.$account_payment_method_preferred->getName().')'.PHP_EOL; fwrite($this->myfile, $txt);
                                    $account_payment_method_preferred->setPreferred( '0' );
                                    $account_payment_method_preferred->persist();
                                }
                                $account_payment_method->setPreferred( '1' );
                            }
                            break;
                    }
                    $account_payment_method->setActive( '1' );
//$txt = 'Payment method added to account ========== '.$account_payment_method->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_payment_method->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $account_payment_method->persist();

                    $quote->setPaymentType( PAYMENT_TYPE_STRIPE );
                    $quote->setPaymentMethod( $account_payment_method->getId() );
                    $quote->persist();
//$txt = 'Payment method added to quote ========== '.$quote->getPaymentMethod().PHP_EOL; fwrite($this->myfile, $txt);
                }
                else
                {
//$txt = 'Error adding source to Customer in Stripe  =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                        'section' => $this->lang['PAYMENT_SECTION'],
                        'alert_type' => 'danger',
                        'title' => $this->lang['WARNING'],
                        'message' => $this->lang['ERR_PAYMENT_ADD_SOURCE_TO_CUSTOMER'], //$e->getError()->message,
                        'redirect_wait' => '5000',
                        'redirect' => '/payments/pay_a_quote/'.$data['quote_key'],
                    ));
                }

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

                if ( $payment_intent_response['status'] == 'OK' )
                {
                    $payment_intent = $payment_intent_response['result']['data'];
//$txt = 'CreatePI ========== ('.$payment_intent['id'].')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($payment_intent, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    $quote->setPaymentReference( $payment_intent['id'] );
                    $quote->persist();

//$txt = 'Payment intend in Stripe  ==========> '.$payment_intent['id'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Payment intend status  ========== '.$payment_intent['status'].PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Going to $this->>paymentResultViewAction with quote ('.$quote->getQuoteKey().')'.PHP_EOL; fwrite($this->myfile, $txt);
                    $vars = array (
                                    'quote_key' => $quote->getQuoteKey(),
                                    //'make_preferred'  => $data['make_preferred'],
                    );
                    return $this->paymentResultViewAction( $vars );
                }
                else
                {
//$txt = 'Creating payment intent error =========='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Stripe error =========='.$payment_intent_response['result']['msg'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($payment_intent_response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                        'section' => $this->lang['PAYMENT_SECTION'],
                        'alert_type' => 'danger',
                        'title' => $this->lang['WARNING'],
                        'message' => $payment_intent_response['result']['msg'].' - '.$this->lang[$payment_intent_response['result']['msg']], //$e->getError()->message,
                        'redirect_wait' => '5000',
                        'redirect' => '/payments/pay_a_quote/'.$data['quote_key'],
                    ));
                }
//$txt = 'Pay Intent Stripe  =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($payment_intent->id, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            }
        }
        else
        {
//$txt = 'Not submit' . PHP_EOL; fwrite($this->myfile, $txt);
            $account_payment_methods = $account_payment_method->getAll( ['account' => $account->getId(), 'active' => '1'] );
            switch ( true )
            {
                case sizeof( $account_payment_methods ) == 0:
                    $data['has_cards'] = 'NO';
                    break;
                case sizeof( $account_payment_methods ) == 1:
                    $data['has_cards'] = 'ONE';
                    break;
                case sizeof( $account_payment_methods ) > 1:
                    $data['has_cards'] = 'MULTI';
                    break;
            }
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);

        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/stripe_get_card.html.twig', array(
            'data' => $data,
            'quote' => $quote->getReg(),
            'quote_line' => $quote_line->getReg(),
            'errors' => $error
        ));
    }

    /**
     *
     * Shows payment confirmation and
     * tries to pay a quote with existing source on quote
     *
     * @Route("/payments/pay_quote/a9ce8c1201020e2b3e77/quote_key", name="payments_pay_a_quote_a9ce8c1201020e2b3e77_quote_key")
     *
     */
    public function payQuoteAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentStripeViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']));

        $data = array (
            'quote_key'  => $this->utils->request_var( 'quote_key', $vars['quote_key'], 'ALL'),
            'make_preferred'  => $this->utils->request_var( 'make_preferred', '0', 'ALL'),
            'terms_conditions'  => ( isset($_POST['terms']) )? TRUE : FALSE,
            'submit' => isset($_POST['submit']) ? '1' : '',
            'payment_terms_agreement' => sprintf($this->lang['PAYMENT_TERMS_AGREEMENT'], $this->session->config['web_name']),
        );

//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $config = new configController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $plan = new planController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote_line = new quoteLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $error = array();

        if ( !$quote->getRegbyQuoteKey( $data['quote_key'] ) )
        {
//$txt = 'Quote not found ('.$data['quote_key'].')'.PHP_EOL; fwrite($this->myfile, $txt);
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_PAYMENT_NO_QUOTE'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
//$txt = 'Quote found '.$quote->getId().PHP_EOL; fwrite($this->myfile, $txt);fwrite($this->myfile, print_r($quote->getReg(), TRUE));

        if ( $quote->getInvoice() )
        {
//$txt = 'Quote paid '.$quote->getInvoice().PHP_EOL; fwrite($this->myfile, $txt);fwrite($this->myfile, print_r($quote->getReg(), TRUE));
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_QUOTE_ALREADY_PAID'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
//$txt = 'Quote NOT paid'.PHP_EOL; fwrite($this->myfile, $txt);

        $lines = $quote_line->getLinesbyQuote( $quote->getId() );
//$txt = 'Quote lines =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lines, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        foreach ( $lines as $line )
        {
            $quote_line->getRegbyId( $line['id'] );
//$txt = 'Quote line ========== '.$quote_line->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Quote line product ========== '.$quote_line->getProduct().PHP_EOL; fwrite($this->myfile, $txt);
            if( $plan->getRegbyPlanKey( $quote_line->getProduct() ) )
            {
//$txt = 'Plan found ========== '.$plan->getId().PHP_EOL; fwrite($this->myfile, $txt);
                return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['PAYMENT_SECTION'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_QUOTE_CHOOSE_PRODUCT'],
                    'redirect_wait' => '5000',
                    'redirect' => '/payments/choose_quote_product/'.$quote->getQuoteKey(),
                ));
            }
//$txt = 'Is a product ========== '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        }

        if ( !$account->getRegbyId( $quote->getAccount() ) )
        {
//$txt = 'Account not found '.PHP_EOL; fwrite($this->myfile, $txt);
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_ACCOUNT_NOT_EXISTS'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
//$txt = 'Account found '.$account->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account->getReg(), TRUE));

        if ( !$payment_method->getRegbyId( $quote->getPaymentMethod() ) )
        {
//$txt = 'Payment method not found '.PHP_EOL; fwrite($this->myfile, $txt);
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_PAYMENT_METHOD_NOT_EXISTS'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
//$txt = 'Payment method found '.$payment_method->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account->getReg(), TRUE));

        if ( !empty($data['submit'] ) )
        {
            if ( !$data['terms_conditions'] )
            {
//$txt = 'No terms'.PHP_EOL; fwrite($this->myfile, $txt);
                $error[] = $this->lang['ERR_TERMS_MUST_AGREED'];
            }

            if ( !sizeof($error) )
            {
//$txt = 'No errors found =========='.PHP_EOL; fwrite($this->myfile, $txt);

                $config->getRegbyName( 'web_currency' );
                $pi_extra = array(
                    'currency' => $config->getConfigValue(),
                    'statement_descriptor_suffix' => $this->lang['QUOTE'].' '.$quote->getId(),
                    'metadata' => array(
                                        'origin' => 'quote',
                                        'quote' => $quote->getQuoteKey()
                    ),
                );
                $payment_intent_response = $this->utils->createPI( $account, $payment_method, $quote->getTotalToPay(), $pi_extra );
//$txt = 'CreatePI response ========== '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($payment_intent_response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                if ( $payment_intent_response['status'] == 'OK' )
                {
                    $payment_intent = $payment_intent_response['result']['data'];
//$txt = 'Payment intent ========== ('.$payment_intent['id'].')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($payment_intent, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Payment Intent id ('.$payment_intent['id'].')'.PHP_EOL; fwrite($this->myfile, $txt);

                    $quote->setPaymentReference( $payment_intent['id'] );
                    $quote->persist();
//$txt = 'Payment intend exists in Stripe  ========== '.$payment_intent['id'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Payment intend status  ========== '.$payment_intent['status'].PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Going to $this->>paymentResultViewAction with quote ('.$quote->getQuoteKey().')'.PHP_EOL; fwrite($this->myfile, $txt);
                    $vars = array (
                        'quote_key' => $quote->getQuoteKey(),
                        //'make_preferred' => $data['make_preferred'],
                    );
                    return $this->paymentResultViewAction( $vars );
                }
                else
                {
//$txt = 'Creating payment intent error =========='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Stripe error =========='.$payment_intent_response['result']['msg'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($payment_intent_response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                        'section' => $this->lang['PAYMENT_SECTION'],
                        'alert_type' => 'danger',
                        'title' => $this->lang['WARNING'],
                        'message' => $payment_intent_response['result']['msg'].' - '.$this->lang[$payment_intent_response['result']['msg']], //$e->getError()->message,
                        'redirect_wait' => '5000',
                        'redirect' => '/payments/pay_a_renew_quote/1/'.$data['quote_key'],
                    ));
                }
//$txt = 'Pay Intent Stripe  =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($payment_intent->id, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            }
            else
            {
//$txt = 'Errors found' . PHP_EOL; fwrite($this->myfile, $txt);
            }
        }
        else
        {
//$txt = 'Not submit' . PHP_EOL; fwrite($this->myfile, $txt);
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);

        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/stripe_pay_quote.html.twig', array(
            'data' => $data,
            'quote' => $quote->getReg(),
            'quote_line' => $quote_line->getReg(),
            'payment_method' => $payment_method->getReg(),
            'errors' => $error
        ));
    }

    /**
     *
     * Inform about payment result, is also a route in Stripe Payment Intent creation as result auth callback
     *
     * Paying a quote online
     * Used on auth payment callback
     *
     * @Route("/payments/success_payment/", name="success_payment")
     *
     */
    public function paymentResultViewAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentStripeViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Quote key '.$vars['quote_key'].PHP_EOL;fwrite($this->myfile, $txt);

        $quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote_extra = new quoteExtraController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account_payment_method_preferred = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote->getRegbyQuoteKey( $vars['quote_key'] );
//$txt = 'Quote ============ '.$quote->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Payment intent on quote ('.$quote->getPaymentReference().')'.PHP_EOL; fwrite($this->myfile, $txt);

        $next_action = '';

        if ( $quote_extra->getRegbyQuote( $quote->getId() ) )
        {
//$txt = 'Quote next action ( '.$quote_extra->getNextAction().' )'.PHP_EOL; fwrite($this->myfile, $txt);
            $next_action = $quote_extra->getNextAction();
        }

        $response = $this->utils->retrieveStripePI( $quote->getPaymentReference() );
//$txt = 'retrieveStripePI Response from Stripe  ==========> '.$response['status'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( $response['status'] == 'OK' )
        {
            $payment_intent = $response['result']['data'];
//fwrite($this->myfile, print_r($payment_intent, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Payment intend exists in Stripe  ========== '.$payment_intent['id'].PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Payment intend status  ========== '.$payment_intent['status'].PHP_EOL; fwrite($this->myfile, $txt);

            switch ( $payment_intent['status'] )
            {
                case 'requires_payment_method':
//$txt = 'Payment intend requires payment method ========== Failed'.PHP_EOL; fwrite($this->myfile, $txt);
                    return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/info_payment_failed.html.twig', array(
                            'redirect' => '/choose_quote_payment_method/'.$quote->getQuoteKey(),
                    ));
                    break;
                case 'requires_confirmation':
//$txt = 'Payment intend requires confirmation ========== NOT USED'.PHP_EOL; fwrite($this->myfile, $txt);
                break;
                case 'requires_action':
//$txt = 'Payment intend requires action ========== Needs auth'.PHP_EOL; fwrite($this->myfile, $txt);
                    return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/info_payment_auth.html.twig', array(
                            'auth_pay_link' => $payment_intent->next_action->redirect_to_url->url,
                            //'product' => $product->getReg(),
                    ));
                    break;
                case 'processing':
//$txt = 'Payment intend processing ========== Not completed, processing. WebHook will do according action'.PHP_EOL; fwrite($this->myfile, $txt);
                    return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/info_payment_processing.html.twig');
                    break;
                case 'requires_capture':
//$txt = 'Payment intend requires capture ========== NOT USED'.PHP_EOL; fwrite($this->myfile, $txt);
                    break;
                case 'canceled':
//$txt = 'Payment intend canceled ========== User has cancelled, do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                    return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/info_payment_failed.html.twig', array(
                        'retry' => '/payments/pay_a_quote/'.$quote->getQuoteKey(),
                    ));
                    break;
                case 'failed':
//$txt = 'Pay Intent Payment failed ========== Payment has failed, do nothing'.PHP_EOL; fwrite($this->myfile, $txt);
                    return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/info_payment_failed.html.twig', array(
                        'retry' => '/payments/pay_a_quote/'.$quote->getQuoteKey(),
                    ));
                    break;
                case 'succeeded':
//$txt = 'Pay Intent Payment successful ========== WebHook will do according actions'.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( isset( $vars['make_preferred'] ) )
                    {
                        $account_payment_methods = $account_payment_method->getAll( ['account' => $quote->getAccount()] );

//$txt = 'Account payment methods =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_payment_methods, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        foreach ( $account_payment_methods as $account_payment_method_temp )
                        {
                            $account_payment_method_preferred->getRegbyId( $account_payment_method_temp['id'] );
//$txt = 'Account payment method treated ('.$account_payment_method_preferred->getId().') ('.$reg->getName().')'.PHP_EOL; fwrite($this->myfile, $txt);
                            $account_payment_method_preferred->setPreferred( '0' );
                            $account_payment_method_preferred->persist();
                        }

                        $account_payment_method->getRegbyId( $quote->getPaymentMethod() );
                        $account_payment_method->setPreferred( '1');
                        $account_payment_method->setActive( '1');
                        $account_payment_method->persist();
                    }
                    /*
                    $data = array (
                                    'next_action' => $next_action,
                    );
                    return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/info_payment_success.html.twig', array(
                        'data' => $data
                    ));
                    */

                    header( 'Location: /payments/success_payment_info/'.$quote->getQuoteKey() );
                    exit;
                    break;
            }
        }
        else
        {
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENTS'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_PAYMENT_TRANSACTION_ISSUE'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
    }

    /**
     *
     * Inform about success payment, route in Stripe Payment Intent creation as successful auth callback
     *
     * ¡¡¡¡ DO NOT USE IT ANYWHERE ELSE !!!!!
     *
     * Paying a quote online
     * Used on succeed auth payment callback
     *
     * @Route("/payments/success_payment/{quote_key}", name="success_payment")
     *
     */
    public function successPaymentAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentStripeViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote_extra = new quoteExtraController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote->getRegbyQuoteKey( $vars['quote_key'] );
//$txt = 'Quote ============ '.$quote->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Payment intent on quote ('.$quote->getPaymentReference().')'.PHP_EOL; fwrite($this->myfile, $txt);

        $data = array (
            'next_action' => '',
        );

        if ( $quote_extra->getRegbyQuote( $quote->getId() ) )
        {
//$txt = 'Quote next action ( '.$quote_extra->getNextAction().' )'.PHP_EOL; fwrite($this->myfile, $txt);
            $data['next_action'] = $quote_extra->getNextAction();
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/info_payment_success.html.twig', array(
            'data' => $data
        ));
    }

    /** 
     *
     * Add a new card when preferred has expired
     * 
     * @Route("/payments/renew_card/s22e8c1201020e2b3e77/{account_payment_method_key}", name="payment_renew_card_s22e8c1201020e2b3e77_account_payment_method_key")
     */
    public function renewCardAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentStripeViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = 'paymentStripeViewController '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $data = array (
            'account_payment_method_key'  => $this->utils->request_var( 'account_payment_method_key', $vars['account_payment_method_key'], 'ALL'),
            'stripeToken' => ( isset($_POST['stripeToken']) )? $_POST['stripeToken'] : '',
            'terms_conditions' => ( isset($_POST['terms_conditions']) )? TRUE : FALSE,
            'payment_terms_agreement' => sprintf($this->lang['PAYMENT_TERMS_AGREEMENT'], $this->session->config['web_name']),
            'has_cards' => '',
            'make_preferred'  => $this->utils->request_var( 'make_preferred', '1', 'ALL'),
            'submit' => ( isset($_POST['submitted']) )? '1' : '',
        );

//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $error = array();

        $account_payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $account_payment_method_preferred = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        if ( !$account_payment_method->getRegbyKey( $data['account_payment_method_key'] ) )
        {
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_PAYMENT_METHOD_NOT_EXISTS'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }

        $account->getRegbyId( $account_payment_method->getAccount() );

        if ( !empty($data['submit']) )
        {
//$txt = 'Is submit ==================='.PHP_EOL; fwrite($this->myfile, $txt);

            if ( $data['stripeToken'] == '' ) {
                $error[] = $this->lang['ERR_PAYMENT_NO_STRIPE_TOKEN'];
            }

            if ( !$data['terms_conditions'] )
            {
//$txt = 'No terms'.PHP_EOL; fwrite($this->myfile, $txt);
                $error[] = $this->lang['ERR_TERMS_MUST_AGREED'];
            }

//$txt = 'Errors found if any =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($error, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            if( !sizeof( $error ) )
            {
                $response = $this->utils->addStripeSourceToCustomer( $account->getStripeId(), $data['stripeToken'] );

                if ( $response['status'] == 'OK' )
                {
//$txt = 'Source added to Customer in Stripe  ========== '.$response['result']['data']['id'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response['result']['data'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $account_payment_method->setId('');
                    $account_payment_method->setKey( md5( $response['result']['data']['id'] ) );
                    $account_payment_method->setAccount( $account->getId() );
                    $account_payment_method->setPaymentType( PAYMENT_TYPE_STRIPE );
                    $account_payment_method->setName( $account->getName().' '.$response['result']['data']['last4'] );
                    $account_payment_method->setObject('card');
                    $account_payment_method->setObjectId($response['result']['data']['id']);
                    $account_payment_method->setLast4( $response['result']['data']['last4'] );
                    $account_payment_method->setExpMonth( $response['result']['data']['exp_month'] );
                    $account_payment_method->setExpYear( $response['result']['data']['exp_year'] );
                    $account_payment_method->setBrand( $response['result']['data']['brand'] );
                    $account_payment_method->setCountry( $response['result']['data']['country'] );
                    $account_payment_method->setCVCCheck( $response['result']['data']['cvc_check'] );
                    $account_payment_method->setFunding( $response['result']['data']['funding'] );

                    $account_payment_methods = $account_payment_method->getAll(['account' => $account->getId()]);
                    switch ( true )
                    {
                        case sizeof( $account_payment_methods ) == 0:
                            $account_payment_method->setPreferred( '1' );
                            break;
                        case sizeof( $account_payment_methods ) > 0:
                            if ( $data['make_preferred'] == '1' )
                            {
//$txt = 'Account payment methods =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_payment_methods, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                                foreach ( $account_payment_methods as $account_payment_method_temp )
                                {
                                    $account_payment_method_preferred->getRegbyId( $account_payment_method_temp['id'] );
//$txt = 'Account payment method treated ('.$account_payment_method_preferred->getId().') ('.$account_payment_method->getName().')'.PHP_EOL; fwrite($this->myfile, $txt);
                                    $account_payment_method_preferred->setPreferred( '0' );
                                    $account_payment_method_preferred->persist();
                                }
                                $account_payment_method->setPreferred( '1' );
                            }
                            break;
                    }
                    $account_payment_method->setActive( '1' );
//$txt = 'Payment method added to account ========== '.$account_payment_method->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_payment_method->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    $account_payment_method->persist();

                    $account_payment_method->getRegbyKey( $data['account_payment_method_key'] );
                    $account_payment_method->setActive( '0' );
                    $account_payment_method->persist();

                    return $this->twig->render('app/'.$this->session->config['app_skin'].'/common/show_message.html.twig', array(
                        'section' => $this->lang['PAYMENT_SECTION'],
                        'alert_type' => 'success',
                        'title' => $this->lang['PAYMENT_SECTION'],
                        'message' => $this->lang['PAYMENT_CARD_ADDED_SUCCESSFULLY'],
                        'redirect_wait' => '5000',
                        'redirect' => '/',
                    ));
                }
                else
                {
//$txt = 'Error adding source to Customer in Stripe  =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                        'section' => $this->lang['PAYMENT_SECTION'],
                        'alert_type' => 'danger',
                        'title' => $this->lang['WARNING'],
                        'message' => $this->lang['ERR_PAYMENT_ADD_SOURCE_TO_CUSTOMER'], //$e->getError()->message,
                        'redirect_wait' => '5000',
                        'redirect' => '/payments/pay_a_quote/'.$data['quote_key'],
                    ));
                }
            }
        }
        else
        {
//$txt = 'Is NOT submit ==================='.PHP_EOL; fwrite($this->myfile, $txt);
            $account_payment_methods = $account_payment_method->getAll( ['account' => $account->getId(), 'active' => '1'] );
            switch ( true )
            {
                case sizeof( $account_payment_methods ) == 0:
                    $data['has_cards'] = 'NO';
                    break;
                case sizeof( $account_payment_methods ) == 1:
                    $data['has_cards'] = 'ONE';
                    break;
                case sizeof( $account_payment_methods ) > 1:
                    $data['has_cards'] = 'MULTI';
                    break;
            }
        }

//$txt = 'Result ========>'.$response.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'paymentStripeViewController '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);

        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/stripe_renew_card.html.twig', array(
            'data' => $data,
            'errors' => $error
        ));
    }
}
