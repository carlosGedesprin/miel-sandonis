<?php

namespace src\controller\views\payments;

use \src\controller\baseViewController;

use \src\controller\entity\accountPaymentMethodController;
use \src\controller\entity\websitePPVController;
use \src\controller\entity\quoteController;
use \src\controller\entity\quoteLineController;
use \src\controller\entity\planController;
use \src\controller\entity\productController;
use \src\controller\entity\couponController;

use DateTime;
use DateTimeZone;
use Exception;

class chooseProductViewController extends baseViewController
{
    /**
     * Quote payment process
     *
     * @Route("/payments/choose_quote_product/{quote_key}", name="payment_choose_quote_product_quote_key")
     *
     * @param $vars array Params on route
     * @return mixed Twig render
     * @throws
     *
     */
    public function chooseProductAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/payment_chooseProductViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $now = new DateTime('now', new DateTimeZone( $this->session->config['time_zone'] ));

        $data = array(
            'quote_key'  => $this->utils->request_var( 'quote_key', $vars['quote_key'], 'ALL'),
            'coupon' => strtoupper( $this->utils->request_var( 'coupon', '', 'ALL') ),
            'product' => $this->utils->request_var( 'product', '', 'ALL'),
            'use_preferred' => $this->utils->request_var( 'use_preferred', '1', 'ALL'),
            'submit' => isset($_POST['btn_submit']) ? '1' : '',
        );
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $widget_ppv = new websitePPVController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote_line = new quoteLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $plan_quote_line = new planController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
        $plan_coupon = new planController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $coupon = new couponController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

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
//$txt = 'Quote found =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( !empty( $quote->getInvoice() ) )
        {
//$txt = 'Quote has been already paid and invoiced'.PHP_EOL; fwrite($this->myfile, $txt);
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_QUOTE_ALREADY_PAID'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
//$txt = 'Quote not invoiced'.PHP_EOL; fwrite($this->myfile, $txt);

        $quote_lines = $quote_line->getLinesbyQuote( $quote->getId() );

        foreach ( $quote_lines as $quote_line_temp )
        {
            $quote_line->getRegbyId( $quote_line_temp['id'] );

            if( !$plan_quote_line->getRegbyPlanKey( $quote_line->getProduct() ) )
            {
//$txt = 'Product in quote line is not a plan ('.$quote_line->getProduct().')'.PHP_EOL; fwrite($this->myfile, $txt);
                return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['PAYMENT_SECTION'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_QUOTE_LINE_PRODUCT_IS_NOT_PLAN'],
                    'redirect_wait' => '5000',
                    'redirect' => '/',
                ));
            }
//$txt = 'Plan in quote line found ==========>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($plan_quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            if ( !empty( $data['coupon'] ) )
            {
//$txt = 'Has coupon =====> '.$data['coupon'].PHP_EOL; fwrite($this->myfile, $txt);
                if ( $coupon->getRegbyCode( $data['coupon'] ) )
                {
//$txt = 'Coupon found =========>' . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($coupon->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    if ( !empty($coupon->getPlan()) )
                    {
//$txt = 'Coupon has plan =====>'.$coupon->getPlan().PHP_EOL; fwrite($this->myfile, $txt);
                        $plan_coupon->getRegbyId( $coupon->getPlan() );
//$txt = 'Coupon plan =====>'.$plan_coupon->getPlanKey().PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Plan on quote line =====>'.$quote_line->getProduct().PHP_EOL; fwrite($this->myfile, $txt);
                        if ( $plan_coupon->getId() != $plan_quote_line->getId() )
                        {
//$txt = 'Plan on quote line does not match with plan on coupon'.PHP_EOL; fwrite($this->myfile, $txt);
                            return $this->twig->render('web/' . $this->session->config['website_skin'] . '/common/show_message.html.twig', array(
                                'section' => $this->lang['PAYMENT_SECTION'],
                                'alert_type' => 'danger',
                                'title' => $this->lang['WARNING'],
                                'message' => $this->lang['ERR_DISCOUNT_CODE_NOT_VALID'].' ref.: 1864385',
                                'redirect_wait' => '5000',
                                'redirect' => '/payments/choose_quote_product/' . $data['quote_key'],
                            ));
                        }
                    }
                    else
                    {
//$txt = 'Coupon NOT has plan =====> ('.$coupon->getPlan().')';
                    }

                    if ( $coupon->getActive() != '1' )
                    {
//$txt = 'Coupon not active'.PHP_EOL; fwrite($this->myfile, $txt);
                        return $this->twig->render('web/' . $this->session->config['website_skin'] . '/common/show_message.html.twig', array(
                            'section' => $this->lang['PAYMENT_SECTION'],
                            'alert_type' => 'danger',
                            'title' => $this->lang['WARNING'],
                            'message' => $this->lang['ERR_DISCOUNT_CODE_NOT_VALID'].' ref.: 24385531',
                            'redirect_wait' => '5000',
                            'redirect' => '/payments/choose_quote_product/' . $data['quote_key'],
                        ));
                    }

                    if ( !empty( $coupon->getValidityDateEnd() ) )
                    {
                        $using_after_start_date = ( $coupon->getValidityDateStart()->format('Ymd') > $now->format('Ymd') )? true : false;
                        $using_before_start_date = ( $coupon->getValidityDateEnd()->format('Ymd') < $now->format('Ymd') )? true : false;
                        if ( $using_after_start_date && $using_before_start_date )
                        {
//$txt = 'Coupon not valid by date: starts '.$coupon->getValidityDateStart()->format('d-m-Y').PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Coupon not valid by date: ends '.$coupon->getValidityDateEnd()->format('d-m-Y').PHP_EOL; fwrite($this->myfile, $txt);
                            return $this->twig->render('web/' . $this->session->config['website_skin'] . '/common/show_message.html.twig', array(
                                'section' => $this->lang['PAYMENT_SECTION'],
                                'alert_type' => 'danger',
                                'title' => $this->lang['WARNING'],
                                'message' => $this->lang['ERR_DISCOUNT_CODE_NOT_VALID'].' ref.: 38438385',
                                'redirect_wait' => '5000',
                                'redirect' => '/payments/choose_quote_product/' . $data['quote_key'],
                            ));
                        }
                    }
                }
                else
                {
                    return $this->twig->render('web/' . $this->session->config['website_skin'] . '/common/show_message.html.twig', array(
                        'section' => $this->lang['PAYMENT_SECTION'],
                        'alert_type' => 'danger',
                        'title' => $this->lang['WARNING'],
                        'message' => $this->lang['ERR_DISCOUNT_CODE_NOT_VALID'].' ref.: 48785436',
                        'redirect_wait' => '5000',
                        'redirect' => '/payments/choose_quote_product/' . $data['quote_key'],
                    ));
                }
            }
        }

        if ( !empty($data['submit']) )
        {
//$txt = 'Is submit ==================='.PHP_EOL; fwrite($this->myfile, $txt);

            if ( empty($data['product']) )
            {
//$txt = 'No product to pay ==================='.PHP_EOL; fwrite($this->myfile, $txt);
                $error[] = $this->lang['ERR_PAYMENT_PRODUCT_NEEDED'];
            }
//$txt = 'Errors found =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($error, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            if( !sizeof($error) )
            {
//$txt = 'No errors found =========='.PHP_EOL; fwrite($this->myfile, $txt);

                foreach ( $data['product'] as $quote_line_to_process => $product_temp )
                {
                    $quote_line->getRegbyId( $quote_line_to_process );
//$txt = 'Quote Line  =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    $product->getRegbyId( $product_temp );
//$txt = 'Product  =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    $quote_line->setProduct( $product->getId() );
//$txt = 'Product '.$product->getId().' assigned to quote line '.$quote_line->getId().PHP_EOL; fwrite($this->myfile, $txt);

                    if ( !$product->getNeedsVisits() )
                    {
////////////////////////////////// Special case: Kit Digital start //////////////////////////////
                        if ( $_ENV['country'] == 'ES' )
                        {
                            $quotes_account = $quote->getAll(['account' => $quote->getAccount()]);
//$txt = 'Account quotes =========== '.sizeof($quotes_account).PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quotes_account, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                            foreach ( $quotes_account as $key_quote => $value_quote )
                            {
                                if ( empty( $value_quote['invoice'] ) )
                                {
                                    unset( $quotes_account[$key_quote] );
                                }
                            }
//$txt = 'Account paid quotes =========== '.sizeof($quotes_account).PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quotes_account, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                            $amount_kit_digital = 0;
                            foreach ( $quotes_account as $key_quote => $value_quote )
                            {
                                $quote_lines = $quote_line->getAll(['quote' => $value_quote['id']]);
                                foreach ( $quote_lines as $key_quote_line => $value_quote_line )
                                {
//$txt = 'Quote line =========== '.sizeof($quote_lines).PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($value_quote_line, TRUE));$txt = PHP_EOL; fwrite($this->myfile, $txt);
                                    if ( $value_quote_line['product'] == '16' || $value_quote_line['product'] == 'b8fc681708b8a4bd7de19facb389na89')
                                    {
                                        $amount_kit_digital++;
                                    }
                                }
                            }
                            if ( $amount_kit_digital != 0 ) $amount_kit_digital++;
//$txt = 'Amount paid Kit Digital '.$amount_kit_digital.PHP_EOL; fwrite($this->myfile, $txt);
                            switch ( true )
                            {
                                case ( $amount_kit_digital > 100 ):
//$txt = 'Has more than 100 Kit Digital '.$amount_kit_digital.PHP_EOL; fwrite($this->myfile, $txt);
                                    $product->setPrice( '10000' );
                                    break;
                                case ( $amount_kit_digital > 50 ):
//$txt = 'Has more than 50 Kit Digital '.$amount_kit_digital.PHP_EOL; fwrite($this->myfile, $txt);
                                    $product->setPrice( '15000' );
                                    break;
                                case ( $amount_kit_digital > 10 ):
//$txt = 'Has more than 10 Kit Digital '.$amount_kit_digital.PHP_EOL; fwrite($this->myfile, $txt);
                                    $product->setPrice( '17500' );
                                    break;
                                case ( $amount_kit_digital > 5 ):
//$txt = 'Has more than 5 Kit Digital '.$amount_kit_digital.PHP_EOL; fwrite($this->myfile, $txt);
                                    $product->setPrice( '19000' );
                                    break;
                            }
                        }
////////////////////////////////// Special case: Kit Digital end //////////////////////////////
                        $total_price_line = $product->getPrice();
                    }
                    else
                    {
                        $total_price_line = $widget_ppv->getDomainVisitsAction( $product->getId(), $quote_line->getItem() );
                        if ( $total_price_line <= 0 )
                        {
                            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                                'section' => $this->lang['PAYMENT_SECTION'],
                                'alert_type' => 'danger',
                                'title' => $this->lang['WARNING'],
                                //'message' => $this->lang['ERR_'],
                                'message' => $this->lang['ERR_PAYMENT_NO_VISITS_FOUND'],
                                'redirect_wait' => '5000',
                                //'redirect' => '/payments/choose_quote_product/'.$data['quote_key'],
                                'redirect' => '/',
                            ));
                        }
                    }
//$txt = 'Product price ('.$total_price_line.')'.PHP_EOL; fwrite($this->myfile, $txt);

                    $quote_line->setPrice( $total_price_line );
                    $quote_line->setAmount( $total_price_line * $quote_line->getUnits() );
//$txt = 'Quote line price ('.$quote_line->getPrice().') amount ('.$quote_line->getAmount().')'.PHP_EOL; fwrite($this->myfile, $txt);

                    if ( !empty( $data['coupon'] ) )
                    {
//$txt = 'Quote line has coupon ('.$data['coupon'].')'.PHP_EOL; fwrite($this->myfile, $txt);

                        $quote_line->setCoupon( $coupon->getId() );
//$txt = 'Coupon assigned to quote line ('.$quote_line->getCoupon().')'.PHP_EOL; fwrite($this->myfile, $txt);

                        $quote_line->setDiscount( $coupon->getDiscountAmount( $total_price_line ) );
//$txt = 'Quote line amount coupon discounted ('.$quote_line->getDiscount().')'.PHP_EOL; fwrite($this->myfile, $txt);

                    }

                    $quote_line->setTotal( $quote_line->getAmount() - $quote_line->getDiscount() );

                    $quote_line->persistORL();
//$txt = 'Quote Line finished =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                }

                $quote->setPaymentOrigin( 'online' );

                $quote->calculate_net_vat_total( $quote );
//$txt = 'Quote with new figures =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                $quote->persistORL();

                if ( $data['use_preferred'] != '1' )
                {
//$txt = 'Use preferred payment method =========='.PHP_EOL; fwrite($this->myfile, $txt);
                    $account_payment_method->getRegbyAccountPreferred( $quote->getAccount() );
                    $quote->setAccountPaymentMethod( $account_payment_method->getId() );
                    $quote->persistORL();

                    header( 'Location: /payments/pay_a_quote/'.$quote->getQuoteKey() );
                    exit;
                }
                else
                {
//$txt = 'Do NOT use preferred payment method'.PHP_EOL; fwrite($this->myfile, $txt);
                    $payment_methods = $account_payment_method->getAllActiveNotExpired( $quote->getAccount() );
                    if ( sizeof( $payment_methods ) == '0' )
                    {
//$txt = 'Account has NO payment methods'.PHP_EOL; fwrite($this->myfile, $txt);
                        header( 'Location: /payments/pay_a_quote/'.$quote->getQuoteKey() );
                        exit;
                    }
                    else
                    {
//$txt = 'Account has multiple payment methods'.PHP_EOL; fwrite($this->myfile, $txt);
                        header( 'Location: /payments/choose_quote_payment_method/'.$quote->getQuoteKey() );
                        exit;
                    }
                }

            }
        }
        else
        {
        // Not submit
//$txt = 'Not submit =========================================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Website  =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($website->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            $total_price = 0;
            foreach ( $quote_lines as $key => $line )
            {
                $quote_line->getRegbyId( $line['id'] );
//$txt = 'Quote Line  =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                $lines[$key]['description'] = $quote_line->getDescription();

                if ( $plan_quote_line->getRegbyPlanKey( $quote_line->getProduct() ) )
                {
//$txt = 'Plan on quote line  ==========> '.$plan_quote_line->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($plan_quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    if ( !empty( $plan_quote_line->getPeriodicityMonthly() ) )
                    {
                        $product->getRegbyId( $plan_quote_line->getPeriodicityMonthly() );
//$txt = 'Product monthly on plan  ==========> '.$product->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        $lines[$key]['products'][] = $product->getReg();
                        //total_price solo se usa en widgets de pago por visita, pero para simplificar la eleccion de website utilizamos el item de la quote_line
                        if ( $product->getNeedsVisits() ) 
                        {
                            $total_price = $widget_ppv->getDomainVisitsAction( $product->getId(), $quote_line->getItem() );
                            if ( $total_price <= 0 )
                            {
                                return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                                    'section' => $this->lang['PAYMENT_SECTION'],
                                    'alert_type' => 'danger',
                                    'title' => $this->lang['WARNING'],
                                    //'message' => $this->lang['ERR_'],
                                    'message' => $this->lang['ERR_PAYMENT_NO_VISITS_FOUND'],
                                    'redirect_wait' => '5000',
                                    //'redirect' => '/payments/choose_quote_product/'.$data['quote_key'],
                                    'redirect' => '/',
                                ));
                            }
                        }

//$txt = 'Monthly product  ==========' . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL;fwrite($this->myfile, $txt);
                    }

                    if ( !empty( $plan_quote_line->getPeriodicityAnnual() ) )
                    {
                        $product->getRegbyId($plan_quote_line->getPeriodicityAnnual());
//$txt = 'Product annualy on plan  =========='.$product->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

////////////////////////////////// Special case: Kit Digital start //////////////////////////////
/*
                        if ( $_ENV['country'] == 'ES' )
                        {
                            $quotes_account = $quote->getAll(['account' => $quote->getAccount()]);
//$txt = 'Account quotes =========== '.sizeof($quotes_account).PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quotes_account, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                            foreach ( $quotes_account as $key_quote => $value_quote )
                            {
                                if ( empty($value_quote['invoice']) )
                                {
                                    unset($quotes_account[$key_quote]);
                                }
                            }
//$txt = 'Account paid quotes =========== '.sizeof($quotes_account).PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quotes_account, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                            $amount_kit_digital = 0;
                            foreach ( $quotes_account as $key_quote => $value_quote )
                            {
                                $quote_lines = $quote_line->getAll(['quote' => $value_quote['id']]);
                                foreach ( $quote_lines as $key_quote_line => $value_quote_line )
                                {
//$txt = 'Quote line =========== '.sizeof($quote_lines).PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($value_quote_line, TRUE));$txt = PHP_EOL; fwrite($this->myfile, $txt);
                                    if ( $value_quote_line['product'] == '16' || $value_quote_line['product'] == 'b8fc681708b8a4bd7de19facb389na89')
                                    {
                                        $amount_kit_digital++;
                                    }
                                }
                            }
                            if ( $amount_kit_digital != 0 ) $amount_kit_digital++;
//$txt = 'Amount paid Kit Digital '.$amount_kit_digital.PHP_EOL; fwrite($this->myfile, $txt);
                            switch ( true )
                            {
                                case ( $amount_kit_digital > 100 ):
//$txt = 'Has more than 100 Kit Digital '.$amount_kit_digital.PHP_EOL; fwrite($this->myfile, $txt);
                                    $product->setPrice( '10000' );
                                    break;
                                case ( $amount_kit_digital > 50 ):
//$txt = 'Has more than 50 Kit Digital '.$amount_kit_digital.PHP_EOL; fwrite($this->myfile, $txt);
                                    $product->setPrice( '15000' );
                                    break;
                                case ( $amount_kit_digital > 10 ):
//$txt = 'Has more than 10 Kit Digital '.$amount_kit_digital.PHP_EOL; fwrite($this->myfile, $txt);
                                    $product->setPrice( '17500' );
                                    break;
                                case ( $amount_kit_digital > 5 ):
//$txt = 'Has more than 5 Kit Digital '.$amount_kit_digital.PHP_EOL; fwrite($this->myfile, $txt);
                                    $product->setPrice( '19000' );
                                    break;
                            }
                        }
*/
////////////////////////////////// Special case: Kit Digital end //////////////////////////////

                        $lines[$key]['products'][] = $product->getReg();
                        //total_price solo se usa en widgets de pago por visita, pero para simplificar la eleccion de website utilizamos el item de la quote_line
                        if ( $product->getNeedsVisits() ) 
                        {
                            $total_price = $widget_ppv->getDomainVisitsAction( $product->getId(), $quote_line->getItem() );
                            if ( $total_price <= 0 )
                            {
                                return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                                    'section' => $this->lang['PAYMENT_SECTION'],
                                    'alert_type' => 'danger',
                                    'title' => $this->lang['WARNING'],
                                    //'message' => $this->lang['ERR_'],
                                    'message' => $this->lang['ERR_PAYMENT_NO_VISITS_FOUND'],
                                    'redirect_wait' => '5000',
                                    //'redirect' => '/payments/choose_quote_product/'.$data['quote_key'],
                                    'redirect' => '/',
                                ));
                            }
                        }
                        
                        $lines[$key]['product_preferred'] = $product->getId();
    //$txt = 'Annual product ==========' . PHP_EOL; fwrite($this->myfile, $txt);
    //fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    }

                    if ( !empty( $plan_quote_line->getPeriodicityBiennial() ) )
                    {
                        $product->getRegbyId( $plan_quote_line->getPeriodicityBiennial() );
                        $lines[$key]['products'][] = $product->getReg();
                        //total_price solo se usa en widgets de pago por visita, pero para simplificar la eleccion de website utilizamos el item de la quote_line
                        if ( $product->getNeedsVisits() ) 
                        {
                            $total_price = $widget_ppv->getDomainVisitsAction( $product->getId(), $quote_line->getItem() );
                            if ( $total_price <= 0 )
                            {
                                return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                                    'section' => $this->lang['PAYMENT_SECTION'],
                                    'alert_type' => 'danger',
                                    'title' => $this->lang['WARNING'],
                                    //'message' => $this->lang['ERR_'],
                                    'message' => $this->lang['ERR_PAYMENT_NO_VISITS_FOUND'],
                                    'redirect_wait' => '5000',
                                    //'redirect' => '/payments/choose_quote_product/'.$data['quote_key'],
                                    'redirect' => '/',
                                ));
                            }
                        }

    //$txt = 'Biennial product ==========' . PHP_EOL; fwrite($this->myfile, $txt);
    //fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    }
                }
                elseif ( $product->getRegbyId( $quote_line->getProduct() ) ) 
                {
                    $lines[$key]['products'][] = $product->getReg();
                    //total_price solo se usa en widgets de pago por visita, pero para simplificar la eleccion de website utilizamos el item de la quote_line
                    if ( $product->getNeedsVisits() ) 
                    {
                        $total_price = $widget_ppv->getDomainVisitsAction( $product->getId(), $quote_line->getItem() );
                        if ( $total_price <= 0 )
                        {
                            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                                'section' => $this->lang['PAYMENT_SECTION'],
                                'alert_type' => 'danger',
                                'title' => $this->lang['WARNING'],
                                //'message' => $this->lang['ERR_'],
                                'message' => $this->lang['ERR_PAYMENT_NO_VISITS_FOUND'],
                                'redirect_wait' => '5000',
                                //'redirect' => '/payments/choose_quote_product/'.$data['quote_key'],
                                'redirect' => '/',
                            ));
                        }
                    }
                } 
            }
//$txt = 'Products ==========' . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lines[$key]['products'], TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        }

        // Has a preferred payment method
        $data['has_preferred'] = ( $accountPaymentMethod->getRegbyAccountPreferred( $quote->getAccount() ) )? $accountPaymentMethod->getLast4() : '0';

//$txt = 'paymentViewController '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);

        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/choose_quote_products.html.twig', array(
            'data' => $data,
            'quote_lines' => $lines,
            'total_price' => $total_price,
            'errors' => $error
        ));
    }

    /**
     * 
     * Get discounted product
     * 
     * @Route("/get_product_discounted_post", name="discountedProductPost")
     */
    public function discountedProductPost()
    {
        $data = array();
        $error = array();

        $now = new DateTime('now', new DateTimeZone( $this->session->config['time_zone'] ));
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = 'paymentViewController '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $api_request = $this->utils->checkAPIRequest();
//$txt = 'Request after checkAPIRequest call ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));
        if ( $api_request['status'] == 'KO' )
        {
//$txt = 'Error '.$api_request['data']['error_code'].' '.$api_request['data']['error_des'].' in Request ========>'.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));
            $error[] = 'Request with errors.';
        }
        else
        {
//$txt = 'No errors ========> '.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request, TRUE));

            $coupon_code = $api_request['data']['coupon_code'];
            $products = $api_request['data']['products_id'];

            $coupon = new couponController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
    
            $totals = [];

            if ( $coupon->getRegbyCode( $coupon_code ) )
            {
//$txt = 'Coupon ========> '.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($coupon->getReg(), TRUE));
                $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

//$txt = 'Products ========> '.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($api_request['data']['products_id'], TRUE));
                foreach ( $products as $product_temp )
                    {
//$txt = 'Product to treat ========> ('.$product_temp.')'.PHP_EOL;fwrite($this->myfile, $txt);
                    if ( $product->getRegbyId( $product_temp ) )
                    {
//$txt = 'Product found ========> ('.$product_temp.')'.PHP_EOL;fwrite($this->myfile, $txt);
                        $totals[$product->getId()] = $coupon->getDiscountedAmount( $product->getPrice() );
                    }
                }
//$txt = 'Totals ========> '.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($totals, TRUE));
            }
            
            $data = array(
                'status' => 'ok',
                'data' => array(
                    'discounted_price' => $totals
                ),
            );
            
        }

        if ( sizeof($error) )
        {
            $data = array(
                'status' => 'ko',
                'messages' => $error
            );
        }
//$txt = 'Data to ES6 ========> '.PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE));
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        header('Content-type: application/json');
        return json_encode( $data );
    }
}