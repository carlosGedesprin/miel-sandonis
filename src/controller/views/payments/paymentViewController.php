<?php

namespace src\controller\views\payments;

use \src\controller\baseViewController;
use \src\controller\entity\planController;
use \src\controller\entity\planTypeController;
use \src\controller\entity\productController;
use \src\controller\entity\productTypeController;
use \src\controller\entity\quoteController;
use \src\controller\entity\quoteLineController;
use \src\controller\entity\accountPaymentMethodController;
use \src\controller\entity\accountFundsController;

use \src\controller\entity\widgetController;
use \src\controller\entity\certificationController;
use \src\controller\entity\WCAGReportController;

use DateTime;
use DateTimeZone;
use Exception;

class paymentViewController extends baseViewController
{
    /**
     *
     * Distributes quote payment to gateway
     *
     * @Route("/payments/pay_a_quote/quote_key", name="payments_pay_a_quote_quote_key")
     *
     */
    public function payQuoteAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $data = array (
            'quote_key'  => $this->utils->request_var( 'quote_key', $vars['quote_key'], 'ALL'),
        );

//$txt = 'Vars =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($vars, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'DATA =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote_line = new quoteLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $plan = new planController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $plan_type = new planTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product_type = new productTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_funds = new accountFundsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

//        $payment_type = new paymentTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

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
//$txt = 'Quote found =========='.$quote->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( empty( $quote->getAccount() ) )
        {
//$txt = 'Quote not found ('.$data['quote_key'].')'.PHP_EOL; fwrite($this->myfile, $txt);
            $quote_lines = $quote_line->getLinesbyQuote( $quote->getId() );
            $quote_line->getRegbyId( $quote_lines[0]['quote'] );
            if ( $plan->getRegbyPlanKey( $quote_line->getProduct() ) )
            {
                $plan_type_temp = $plan->getPlanType();
                $plan_type->getRegbyId( $plan_type_temp->getId() );
                $origin = $plan_type->getTable();
            }
            else
            {
                $product->getRegbyId( $quote_line->getProduct() );
                $product_type_temp = $plan->getPlanType();
                $product_type->getRegbyId( $product_type_temp->getId() );
                $origin = $product_type->getTable();
            }
//$txt = 'Origin ('.$origin.')'.PHP_EOL; fwrite($this->myfile, $txt);
            $origin->getRegById( $quote_line->getItem() );
            $quote->setAccount( $origin->getAccount() );
            $quote->persist();

            if ( empty( $quote->getAccount() ) )
            {
//$txt = 'Quote not found 2 ('.$data['quote_key'].')'.PHP_EOL; fwrite($this->myfile, $txt);
                return $this->twig->render('web/' . $this->session->config['website_skin'] . '/common/show_message.html.twig', array(
                    'section' => $this->lang['PAYMENT_SECTION'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_PAYMENT_QUOTE_NO_ACCOUNT'],
                    'redirect_wait' => '5000',
                    'redirect' => '/',
                ));
            }
        }
//$txt = 'Quote found =========='.$quote->getId().PHP_EOL; fwrite($this->myfile, $txt);
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
//$txt = 'Quote line ==> '.$quote_line->getId().' Product '.$quote_line->getProduct().PHP_EOL; fwrite($this->myfile, $txt);

            if ( $plan->getRegbyPlanKey( $quote_line->getProduct() ) )
            {
//$txt = 'Quote line product is a plan, going to choose plan product'.PHP_EOL; fwrite($this->myfile, $txt);
                header( 'Location: /payments/choose_quote_product/'.$quote->getQuoteKey() );
                exit;
            }
        }
//$txt = 'Quote line products are OK.'.PHP_EOL; fwrite($this->myfile, $txt);fwrite($this->myfile, print_r($quote->getReg(), TRUE));

        if ( $quote->getTotalToPay() == '0' )
        {
//$txt = 'Quote with Total to pay = 0, no need to be invoiced'.PHP_EOL; fwrite($this->myfile, $txt);
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_QUOTE_NO_NEEDED_TO_BE_PAID'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
//$txt = 'Quote can be invoiced'.PHP_EOL; fwrite($this->myfile, $txt);

        $option_url = '';

        if ( empty( $quote->getPaymentType() ) )
        {
//$txt = 'Quote with NO payment type '.PHP_EOL; fwrite($this->myfile, $txt);

            $account_payment_methods = $account_payment_method->getAllActiveNotExpired( $quote->getAccount() );
//$txt = 'Account payment types  ==========> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account_payment_methods, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            if ( !sizeof( $account_payment_methods ) )
            {
//$txt = 'Account has NO payment methods (cards) --> get card'.PHP_EOL; fwrite($this->myfile, $txt);
                $option_url = '/pay_a_quote/d0fc4acbd986c8f3dafe/';
            }
            else
            {
//$txt = 'Account has payment methods -> choose payment method'.PHP_EOL; fwrite($this->myfile, $txt);
                $option_url = '/choose_quote_payment_method/';
            }
        }
        else
        {
//$txt = 'Quote with payment type '.$quote->getPaymentType().PHP_EOL; fwrite($this->myfile, $txt);
            switch ( $quote->getPaymentType() )
            {
                case '1':
                    //Stripe
                    if ( empty( $quote->getPaymentMethod() ) )
                    {
//$txt = 'Stripe -> Quote with NO payment method '.PHP_EOL; fwrite($this->myfile, $txt);
                        $option_url = '/pay_a_quote/d0fc4acbd986c8f3dafe/'; // We don't know the source
                    }
                    else
                    {
//$txt = 'Stripe -> Quote with payment method '.$quote->getPaymentMethod().PHP_EOL; fwrite($this->myfile, $txt);
                        $option_url = '/pay_a_quote/a9ce8c1201020e2b3e77/'; // We know the source: stripe
                    }
                    break;
                case '2':
                    // Redsys
                    if ( empty( $quote->getPaymentMethod() ) )
                    {
//$txt = 'Redsys -> Quote with NO payment method '.PHP_EOL; fwrite($this->myfile, $txt);
                        $option_url = '/pay_a_quote/7c976gd4640d1883b49b/'; // We don't know the source
                    }
                    else
                    {
//$txt = 'Stripe -> Quote with payment method '.$quote->getPaymentMethod().PHP_EOL; fwrite($this->myfile, $txt);
                        $option_url = '/pay_a_quote/5c2o6564f0db0aec4156/'; // We know the source: redsys
                    }
                    break;
                case '3':
                    // Bank Transfer
                    $option_url = '/pay_a_quote/fde97e0eda19a6d80c94/'; // We know the source: bank_transfer
                    break;
                case '4':
                    // Account Funds
//$txt = 'Funds -> Quote with payment method '.$account_funds->getBalancebyAccount( $quote->getAccount() ).PHP_EOL; fwrite($this->myfile, $txt);
                    $option_url = '/pay_a_quote/gd4640d18837c976b49b/'; // We know the source: funds
                    break;
            }
        }

        if ( empty( $option_url ) )
        {
//$txt = 'NO option url '.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
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
        else
        {
//$txt = 'Option url '.$option_url.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
            header( 'Location: /payments'.$option_url.$quote->getQuoteKey() );
            exit;
        }
    }
}
