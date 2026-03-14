<?php

namespace src\controller\views\payments;

use \src\controller\baseViewController;

use \src\controller\entity\accountController;
use \src\controller\entity\userController;
use \src\controller\entity\accountFundsController;
use \src\controller\entity\accountPaymentMethodController;
use \src\controller\entity\websitePPVController;
use \src\controller\entity\quoteController;
use \src\controller\entity\quoteLineController;
use \src\controller\entity\quoteExtraController;
use \src\controller\entity\planController;
use \src\controller\entity\productController;
use \src\controller\entity\productTypeController;
use \src\controller\entity\couponController;
use \src\controller\entity\invoiceController;
use \src\controller\entity\vatTypeController;

use \src\controller\entity\langTextController;
use \src\controller\entity\mailQueueController;

use DateTime;
use DateTimeZone;
use Exception;

class chooseProductViewController extends baseViewController
{
    /**
     * Choose product from quote line plan
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
                        'product' => $this->utils->request_var( 'product', '', 'ALL'),
                        'coupon' => strtoupper( $this->utils->request_var( 'coupon', '', 'ALL') ),
                        'use_payment_method' => $this->utils->request_var( 'use_payment_method', '', 'ALL'),
                        'submit' => isset($_POST['btn_submit']) ? '1' : '',
                        'vat_percent' => '',
                        'product_preferred' => '',
                        'account_payment_method_preferred' => '',
                        'account_allow_staff_use_card' => '',
                        'payable' => '',
        );
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote_line = new quoteLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote_extra = new quoteExtraController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $plan_quote_line = new planController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $plan_coupon = new planController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_funds = new accountFundsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product_type = new productTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $widget_ppv = new websitePPVController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $coupon = new couponController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $invoice = new invoiceController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $vat_type = new vatTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

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
//$txt = 'Quote found ========== '.$quote->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $account->getRegbyId( $quote->getAccount() );
//$txt = 'Account found ========== '.$account->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $vat_type->getRegbyId( $quote->getVatType() );
//$txt = 'Vat type found ========== '.$vat_type->getId().PHP_EOL; fwrite($this->myfile, $txt);
        $data['vat_percent'] = $vat_type->getPercent();

        if ( !empty( $quote->getInvoice() ) )
        {
            if ( $invoice->getRegbyId( $quote->getInvoice() ) )
            {
                if ( $invoice->getPayed() )
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
            }
        }
//$txt = 'Quote not invoiced'.PHP_EOL; fwrite($this->myfile, $txt);

        $quote_lines = $quote_line->getLinesbyQuote( $quote->getId() );
//$txt = 'Quote lines ==========> '.$plan_quote_line->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $quote_lines, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        foreach ( $quote_lines as $quote_line_temp )
        {
            $quote_line->getRegbyId( $quote_line_temp['id'] );
//$txt = 'Quote line treated ==========> '.$quote_line->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            if ( !$plan_quote_line->getRegbyPlanKey( $quote_line->getProduct() ) )
            {
//$txt = 'Product in quote line is not a plan ('.$quote_line->getProduct().')'.PHP_EOL; fwrite($this->myfile, $txt);
/*
                return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['PAYMENT_SECTION'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_QUOTE_LINE_PRODUCT_IS_NOT_PLAN'],
                    'redirect_wait' => '5000',
                    'redirect' => '/',
                ));
*/
                header( 'Location: /payments/pay_a_quote/'.$quote->getQuoteKey() );
                exit;
            }
//$txt = 'Plan in quote line found ==========> '.$plan_quote_line->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($plan_quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            if ( !empty( $data['coupon'] ) )
            {
//$txt = 'Has coupon =====> '.$data['coupon'].PHP_EOL; fwrite($this->myfile, $txt);
                if ( $coupon->getRegbyCode( $data['coupon'] ) )
                {
//$txt = 'Coupon found =========>' . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($coupon->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    if ( !empty( $coupon->getPlan() ) )
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
                                'message' => $this->lang['ERR_DISCOUNT_CODE_NOT_VALID'], //.' ref.: 1864385',
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
                            'message' => $this->lang['ERR_DISCOUNT_CODE_NOT_VALID'], //.' ref.: 24385531',
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
                        'message' => $this->lang['ERR_DISCOUNT_CODE_NOT_VALID'], //.' ref.: 48785436',
                        'redirect_wait' => '5000',
                        'redirect' => '/payments/choose_quote_product/' . $data['quote_key'],
                    ));
                }
            }
        }

        if ( !empty( $data['submit'] ) )
        {
//$txt = 'Is submit ==================='.PHP_EOL; fwrite($this->myfile, $txt);

            if ( empty( $data['product'] ) )
            {
//$txt = 'No product to pay ==================='.PHP_EOL; fwrite($this->myfile, $txt);
                $error[] = $this->lang['ERR_PAYMENT_PRODUCT_NEEDED'];
            }
//$txt = 'Errors found =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($error, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            if ( !sizeof( $error ) )
            {
//$txt = 'No errors found =========='.PHP_EOL; fwrite($this->myfile, $txt);

                foreach ( $data['product'] as $quote_line_to_process => $product_temp )
                {
                    $quote_line->getRegbyId( $quote_line_to_process );
//$txt = 'Quote Line  =========='.$quote_line->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    $product->getRegbyId( $product_temp );
//$txt = 'Product  ========== '.$product->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                    $product_type->getRegbyId( $product->getProductType() );
//$txt = 'Product type ('.$product_type->getId().') ('.$product_type->getName().')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product_type->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    if ( !empty( $data['coupon'] ) )
                    {
//$txt = 'Quote line has coupon ('.$data['coupon'].')'.PHP_EOL; fwrite($this->myfile, $txt);
                        $coupon->getRegbyCode( $data['coupon'] );

                        // Old Agent and Integrator gets overwritten
                        if ( !empty( $coupon->getAgent() ) ) $account->setAgent( $coupon->getAgent() );
                        if ( !empty( $coupon->getIntegrator() ) ) $account->setIntegrator( $coupon->getIntegrator() );

                        if ( $account->getIntegrator() == $account->getId() ) $account->setIntegrator( '' );
                        if ( $account->getAgent() == $account->getId() ) $account->setAgent( '' );

                        $account->persist();

                        $quote_line->setCoupon( $coupon->getId() );
//$txt = 'Coupon assigned to quote line ('.$quote_line->getCoupon().')'.PHP_EOL; fwrite($this->myfile, $txt);
                    }

                    $quote_line->setProduct( $product->getId() );
//$txt = 'Product '.$product->getId().' assigned to quote line '.$quote_line->getId().PHP_EOL; fwrite($this->myfile, $txt);

                    $data['product_original'][$quote_line->getId()] = $quote_line->getProduct();

                    // Calculate price on product (entity object) per product type, like discounts if is a kind of product type
                    $product->setPricePerProductType();
//$txt = 'Product price ('.$product->getPrice().')'.PHP_EOL; fwrite($this->myfile, $txt);

                    $quote_line->setPrice( $product->getPrice() );

//$txt = 'Item is a '.$product_type->getController().PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $product_type->getController() )
                    {
                        require_once APP_ROOT_PATH . '/src/controller/entity/'.$product_type->getController().'Controller.php';
                        $class_to_load = '\\src\\controller\\entity\\'.$product_type->getController().'Controller';
                        $item = new $class_to_load( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

                        $item->getRegbyId( $quote_line->getItem() );
//$txt = 'Item found '.$item->getId().PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'For domain '.$item->getDomainName().PHP_EOL; fwrite($this->myfile, $txt);

                        $item->setProduct( $product->getId() );
//$txt = 'Quote line product '.$quote_line->getProduct().' assigned to item '.$item->getProduct().PHP_EOL; fwrite($this->myfile, $txt);
                        $item->setPrice( $quote_line->getPrice() );
//$txt = 'Quote line price '.$quote_line->getPrice().' assigned to item '.$item->getId().PHP_EOL; fwrite($this->myfile, $txt);

                        if ( !empty( $data['coupon'] ) )
                        {
//$txt = 'Quote line has coupon ('.$data['coupon'].') so we put it on the item '.$product_type->getController().PHP_EOL; fwrite($this->myfile, $txt);
                            $item->setCoupon($coupon->getId());
                            if (!$item->getAgent()) $item->setAgent($coupon->getAgent());
                            if (!$item->getIntegrator()) $item->setIntegrator($coupon->getIntegrator());
                        }

                        $item->persist();
                    }

                    $quote_line->setAmount( $quote_line->getUnits() * $quote_line->getPrice() );
//$txt = 'Quote line price ('.$quote_line->getPrice().') amount ('.$quote_line->getAmount().')'.PHP_EOL; fwrite($this->myfile, $txt);

                    if ( !empty( $data['coupon'] ) )
                    {
//$txt = 'Quote line has coupon ('.$data['coupon'].')'.PHP_EOL; fwrite($this->myfile, $txt);

                        $quote_line->setDiscount( $coupon->getDiscountAmount( $quote_line->getAmount() ) );
//$txt = 'Quote line amount coupon discounted ('.$quote_line->getDiscount().')'.PHP_EOL; fwrite($this->myfile, $txt);
                    }

                    $quote_line->setTotal( $quote_line->getAmount() - $quote_line->getDiscount() );
//$txt = 'Quote Line total ========== '.$quote_line->getTotal().PHP_EOL; fwrite($this->myfile, $txt);

                    $quote_line->persist();
//$txt = 'Quote Line finished =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                }

                $quote->setPaymentOrigin( 'online' );

                $quote->calculate_net_vat_total( $quote );
//$txt = 'Quote with new figures =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                $quote->persist();

                if ( $quote->getTotalToPay() != 0 )
                {
//$txt = 'Use this payment method ======>'.$data['use_payment_method'].PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $data['use_payment_method'] == 'funds' )
                    {
//$txt = 'Use funds as payment method =========='.PHP_EOL; fwrite($this->myfile, $txt);

                        $quote->setPaymentType( PAYMENT_TYPE_FUNDS );
                        $quote->persist();

                        header( 'Location: /payments/pay_a_quote/'.$quote->getQuoteKey() );
                        exit;
                    }
                    elseif ( $data['use_payment_method'] == 'preferred_card' )
                    {
//$txt = 'Use card as payment method =========='.PHP_EOL; fwrite($this->myfile, $txt);

                        $quote->setPaymentType( PAYMENT_TYPE_STRIPE );

//$txt = 'Use preferred payment method =========='.PHP_EOL; fwrite($this->myfile, $txt);
                        if ( $account_payment_method->getRegbyAccountPreferred( $account->getId() ) )
                        {
//$txt = 'Account preferred payment method =====>'.$account_payment_method->getId().' payment type '.$account_payment_method->getPaymentType().PHP_EOL; fwrite($this->myfile, $txt);
                            $quote->setPaymentMethod( $account_payment_method->getId() );
                        }
                        else
                        {
//$txt = 'Account has NO preferred payment method =========='.PHP_EOL; fwrite($this->myfile, $txt);
                            //$quote->setPaymentType( PAYMENT_TYPE_STRIPE );
                        }
//$txt = 'Quote with payment method =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        $quote->persist();

                        header( 'Location: /payments/pay_a_quote/'.$quote->getQuoteKey() );
                        exit;
                    }
                    elseif ( $data['use_payment_method'] == 'new_card' )
                    {
//$txt = 'Use new card as payment method =========='.PHP_EOL; fwrite($this->myfile, $txt);

                        $quote->setPaymentType( PAYMENT_TYPE_STRIPE );
                        $quote->persistORL();

                        header( 'Location: /payments/pay_a_quote/'.$quote->getQuoteKey() );
                        exit;
                    }
                    elseif ( $data['use_payment_method'] == 'bank_transfer' )
                    {
//$txt = 'Use new card as payment method =========='.PHP_EOL; fwrite($this->myfile, $txt);

                        $quote->setPaymentType( PAYMENT_TYPE_BANK);
                        $quote->persistORL();

                        header( 'Location: /payments/pay_a_quote/'.$quote->getQuoteKey() );
                        exit;
                    }
                    else
                    {
//$txt = 'NO payment method recieved'.PHP_EOL; fwrite($this->myfile, $txt);

                        header( 'Location: /payments/pay_a_quote/'.$quote->getQuoteKey() );
                        exit;
                    }
                }
                else
                {
//$txt = 'Free quote'.PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $quote_extra->getRegbyQuote( $quote->getId() ) )
                    {
                        if ( !empty( $quote_extra->getNextAction() ) )
                        {
                            header( 'Location: /'.$quote_extra->getNextAction() );
                            exit;
                        }
                    }

                    header( 'Location: /payments/payment_result/free_quote/'.$quote->getQuoteKey() );
                    exit;
                }
            }
        }
        else
        {
//$txt = 'Not submit =========================================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Quote ========== '.$quote->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account ========== '.$account->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            $total_price_ppv = 0;
            $total_price_plans = 0;
            foreach ( $quote_lines as $key => $line )
            {
                $quote_line->getRegbyId( $line['id'] );
//$txt = 'Quote Line  ========== '.$quote_line->getId().PHP_EOL; fwrite($this->myfile, $txt);
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
                        $total_price_plans = $product->getPrice();
                        //total_price_ppv solo se usa en widgets de pago por visita, pero para simplificar la eleccion de website utilizamos el item de la quote_line
                        if ( $product->getNeedsVisits() ) 
                        {
                            $total_price_ppv = $widget_ppv->getDomainVisitsAction( $product->getId(), $quote_line->getItem() );
                            if ( $total_price_ppv <= 0 )
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

                        $data['product_preferred'] = $key;
//$txt = 'Monthly product  ==========' . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL;fwrite($this->myfile, $txt);
                    }

                    if ( !empty( $plan_quote_line->getPeriodicityAnnual() ) )
                    {
                        $product->getRegbyId( $plan_quote_line->getPeriodicityAnnual() );
//$txt = 'Product annualy on plan  =========='.$product->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

////////////////////////////////// Special case: Kit Digital start //////////////////////////////
                        //$product->setPrice( $this->applyDiscountKitDigital( $quote, $product ) );
////////////////////////////////// Special case: Kit Digital end //////////////////////////////

                        $lines[$key]['products'][] = $product->getReg();
                        $total_price_plans += $product->getPrice();
                        //total_price_ppv solo se usa en widgets de pago por visita, pero para simplificar la eleccion de website utilizamos el item de la quote_line
                        if ( $product->getNeedsVisits() ) 
                        {
                            $total_price_ppv = $widget_ppv->getDomainVisitsAction( $product->getId(), $quote_line->getItem() );
                            if ( $total_price_ppv <= 0 )
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
                        
                        $data['product_preferred'] = $key;
//$txt = 'Annual product ==========' . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    }

                    if ( !empty( $plan_quote_line->getPeriodicityBiennial() ) )
                    {
                        $product->getRegbyId( $plan_quote_line->getPeriodicityBiennial() );
//$txt = 'Product Biennal on plan  =========='.$product->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        $lines[$key]['products'][] = $product->getReg();
                        $total_price_plans += $product->getPrice();
                        //total_price_ppv solo se usa en widgets de pago por visita, pero para simplificar la eleccion de website utilizamos el item de la quote_line
                        if ( $product->getNeedsVisits() ) 
                        {
                            $total_price_ppv = $widget_ppv->getDomainVisitsAction( $product->getId(), $quote_line->getItem() );
                            if ( $total_price_ppv <= 0 )
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

                        $data['product_preferred'] = $key;
//$txt = 'Biennial product ==========' . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    }
                }
                elseif ( $product->getRegbyId( $quote_line->getProduct() ) ) 
                {
                    $lines[$key]['products'][] = $product->getReg();
                    $total_price_plans += $product->getPrice();
                    //total_price_ppv solo se usa en widgets de pago por visita, pero para simplificar la eleccion de website utilizamos el item de la quote_line
                    if ( $product->getNeedsVisits() ) 
                    {
                        $total_price_ppv = $widget_ppv->getDomainVisitsAction( $product->getId(), $quote_line->getItem() );
                        if ( $total_price_ppv <= 0 )
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

        $lines[0]['products'][$data['product_preferred']]['product_preferred'] = '1';
//$txt = 'Preferred card ==========' . PHP_EOL; fwrite($this->myfile, $txt);
//$popo = $account_payment_method->getRegbyAccountPreferred( $account->getId() );
//fwrite($this->myfile, print_r($popo, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $data['account_allow_staff_use_card'] = $account->getAllowStaffUseCard();

        if ( $this->user == $account->getMainUser() )
        {
            $allowed_to_use_card = 1;
        }
        else
        {
            $allowed_to_use_card = $account->getAllowStaffUseCard();
        }

        $data['funds_balance'] = $account_funds->getBalancebyAccount( $account->getId() );

        if ( $allowed_to_use_card )
        {
            $data['payable'] = 1;

            $data['account_payment_method_preferred'] = ( $account_payment_method->getRegbyAccountPreferred( $account->getId() ) )? $account_payment_method->getReg() : '';

            $data['use_payment_method'] = ( $data['funds_balance'] != 0 )? 'funds' : ( ( empty( $data['account_payment_method_preferred'] ) )? 'new_card' : 'preferred_card' );
        }
        else
        {
            if ( $data['funds_balance'] == 0 )
            {
                $data['payable'] = 0;
            }
            else
            {
                $data['payable'] = 1;

                $data['use_payment_method'] = 'funds';
            }
        }

//$txt = 'Lines ==========' . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lines, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data ==========' . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'paymentViewController '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);

        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/choose_quote_products.html.twig', array(
            'data' => $data,
            'quote_lines' => $lines,
            'total_price_ppv' => $total_price_ppv,
            'total_price_plans' => $total_price_plans,
            'errors' => $error
        ));
    }

    /**
     *
     * Get discounted product when populating coupon in display
     * Ajax call
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
