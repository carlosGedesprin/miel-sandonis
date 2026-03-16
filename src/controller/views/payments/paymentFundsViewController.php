<?php

namespace src\controller\views\payments;

use \src\controller\baseViewController;

use \src\controller\entity\accountController;
use \src\controller\entity\accountPaymentMethodController;
use \src\controller\entity\accountFundsController;
use \src\controller\entity\userController;
use \src\controller\entity\planController;
use \src\controller\entity\productController;
use \src\controller\entity\quoteController;
use \src\controller\entity\quoteLineController;
use \src\controller\entity\quoteExtraController;
use \src\controller\entity\paymentTypeController;
use \src\controller\entity\invoiceController;
use \src\controller\entity\invoiceLineController;

use \src\controller\entity\paymentTransactionController;
use \src\controller\payment_system\paymentResultController;

use DateTime;
use DateTimeZone;
use Exception;

class paymentFundsViewController extends baseViewController
{
    /**
     *
     * Pays a quote with funds and shows payment confirmation
     *
     * @Route("/payments/pay_quote/gd4640d18837c976b49b/quote_key", name="payments_pay_a_renew_quote_gd4640d18837c976b49b_quote_key")
     *
     */
    public function payQuoteAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentFundsViewController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $data = array (
            'quote_key'  => $this->utils->request_var( 'quote_key', $vars['quote_key'], 'ALL'),
        );

//$txt = 'POST =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($_POST, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Data =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_funds = new accountFundsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $plan = new planController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote_line = new quoteLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_type = new paymentTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $invoice = new invoiceController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $invoice_line = new invoiceLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_transaction = new paymentTransactionController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_result_controller = new paymentResultController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

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
            if ( $plan->getRegbyPlanKey( $quote_line->getProduct() ) )
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
        else
        {
            $user->getRegbyId( $account->getMainUser() );
        }
//$txt = 'Account found '.$account->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account->getReg(), TRUE));

        if ( !$payment_type->getRegbyId( $quote->getPaymentType() ) )
        {
//$txt = 'Payment method not found '.PHP_EOL; fwrite($this->myfile, $txt);
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_PAYMENT_TYPE_NOT_EXISTS'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
//$txt = 'Payment method found '.$payment_method->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account->getReg(), TRUE));

        if ( $account_funds->getBalancebyAccount( $quote->getAccount() ) >= $quote->getTotalToPay() )
        {
            $account_funds->setId( '' );
            $account_funds->setFundingKey('34287' . $quote->getAccount() . '-' . $quote->getId());
            $account_funds->setAccount( $quote->getAccount() );
            $account_funds->setUser( $this->user );
            $account_funds->setDate( $now );
            $account_funds->setDescription($this->lang['PAY_A_QUOTE_FUND_DESCRIPTION'] . ' ' . $quote->getId());
            //$account_funds->setPaymentType( PAYMENT_TYPE_FUNDS );
            $account_funds->setCredit( '0' );
            $account_funds->setDebit( $quote->getTotalToPay() );
            $account_funds->persistORL();
//$txt = 'Payment with funds ========== '.$account_funds->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $account_funds->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $payment_transaction->setId('');
            $payment_transaction->setOrigin('quote');
            $payment_transaction->setQuote( $quote->getId() );
            $payment_transaction->setAccount( $quote->getAccount() );
            $payment_transaction->setAccountPaymentMethod( $quote->getPaymentMethod() );
            $payment_transaction->setDateReg( $now );
            $payment_transaction->setPaymentType(PAYMENT_TYPE_FUNDS);
            $payment_transaction->setResult('succeeded');
            $payment_transaction->setEventId( $account_funds->getId() );
            //$payment_transaction->setOriginId( $data->id );
            //$payment_transaction->setTransactionId( $data->id );
            //$payment_transaction->setTransaction( $event );
            $payment_transaction->persist();
//$txt = 'Payment transaction ========= '.$payment_transaction->getId().PHP_EOL;fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $payment_transaction->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $quote->setPaymentReference( $payment_transaction->getId() );
            $quote->persist();

            $payment_result_controller->paymentResultSuccess( $quote, $payment_transaction );

//$txt = 'Going to show success ========= '.PHP_EOL;fwrite($this->myfile, $txt);
            echo $this->successPaymentAction( $quote );
            exit;
        }
        else
        {
//$txt = 'Going to show failed ========= '.PHP_EOL;fwrite($this->myfile, $txt);
            echo $this->failedPaymentAction( $quote );
            exit;
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
    }

    /**
     *
     * Inform about success payment
     *
     */
    public function successPaymentAction( $quote )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        //$quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote_extra = new quoteExtraController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        //$quote->getRegbyQuoteKey( $vars['quote_key'] );
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
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/info_payment_success.html.twig', array(
            'data' => $data
        ));
    }

    /**
     *
     * Inform about failed payment
     *
     */
    public function failedPaymentAction( $quote )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $quote_extra = new quoteExtraController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote->setPaymentType('');
        $quote->persist();
//$txt = 'Quote ============ '.$quote->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Quote key ============ '.$quote->getQuoteKey().PHP_EOL; fwrite($this->myfile, $txt);

        $data = array (
            'next_action' => '/payments/pay_a_quote/'.$quote->getQuoteKey(),
        );
/*
        if ( $quote_extra->getRegbyQuote( $quote->getId() ) )
        {
$txt = 'Quote next action ( '.$quote_extra->getNextAction().' )'.PHP_EOL; fwrite($this->myfile, $txt);
            $data['next_action'] = $quote_extra->getNextAction();
        }
*/
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL;fwrite($this->myfile, $txt);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/info_payment_failed.html.twig', array(
            'data' => $data
        ));
    }
}
