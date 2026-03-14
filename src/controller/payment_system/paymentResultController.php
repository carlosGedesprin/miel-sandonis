<?php

namespace src\controller\payment_system;

use \src\controller\baseController;

use \src\controller\entity\accountController;
use \src\controller\entity\accountFundsController;
use \src\controller\entity\accountPaymentDetailsController;
use \src\controller\entity\accountPaymentMethodController;
use \src\controller\entity\paymentTypeController;
use \src\controller\entity\productTypeController;
use \src\controller\entity\userController;
use \src\controller\entity\productController;
use \src\controller\entity\couponController;
use \src\controller\entity\invoiceController;
use \src\controller\entity\invoiceLineController;
use \src\controller\entity\quoteController;
use \src\controller\entity\quoteLineController;
use \src\controller\entity\commissionController;
use \src\controller\entity\paymentController;
use \src\controller\entity\mailQueueController;
use \src\controller\entity\langTextController;

use DateTime;
use DateTimeZone;
use Exception;

class paymentResultController extends baseController
{
    /*
     *  Processes a successful payment
     *
     * @param $quote               object Quote
     * @param $payment_transaction object Payment Transaction
     * @return void
     */
    public function paymentResultSuccess( $quote, $payment_transaction )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );
$folder = ( $_ENV['env_env'] == 'dev' )?  '' : 'payments/';
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/'.$folder.'paymentResultController_'.__FUNCTION__.'_'.$now->format('Y_m_d').'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==== '.$now->format('d-m-Y  H:i:s').' ============================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = PHP_EOL.'Quote: ('.$quote->getId().') Transaction: ('.$payment_transaction->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $invoice = new invoiceController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $invoice_line = new invoiceLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product_type = new productTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote_line = new quoteLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment = new paymentController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $commission = new commissionController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $errors = array();
        /*
        if ( $quote_lines = $quote_line->getLinesbyQuote( $quote->getId() ) )
        {
$txt = 'Quote lines'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r( $quote_lines, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            foreach ( $quote_lines as $quote_line_temp )
            {
                if ( $quote_line->getRegbyId( $quote_line_temp['id'] )  )
                {
$txt = 'Quote line to treat'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r( $quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                    if ( $quote_line->getProduct() != '' )
                    {
                        if ( $product->getRegbyId( $quote_line->getProduct() ) )
                        {
$txt = 'Quote line product'.PHP_EOL; fwrite($this->myfile, $txt);
fwrite($this->myfile, print_r( $product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                        }
                        else
                        {
                            $errors[] = 'Quote '.$quote->getId().' line '.$quote_line->getId().' not found product ('.$quote_line->getProduct().').';
$txt = 'Quote '.$quote->getId().' line '.$quote_line->getId().' not found product ('.$quote_line->getProduct().').'; $txt .= PHP_EOL; fwrite($this->myfile, $txt);
                        }
                    }
                    else
                    {
                        $errors[] = 'Quote '.$quote->getId().' line '.$quote_line->getId().' product empty.';
$txt = 'Quote '.$quote->getId().' line '.$quote_line->getId().' product empty.'; $txt .= PHP_EOL; fwrite($this->myfile, $txt);
                    }
                }
            }
        }
        */
        $invoice->createInvoice( $quote, $now );
//$txt = 'Invoice created ('.$invoice->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);

        $quote->setInvoice( $invoice->getId() );
        $quote->persist();
//$txt = 'Invoice '.$quote->getInvoice().' assigned to quote '.$quote->getId().PHP_EOL; fwrite($this->myfile, $txt);

        $quote_lines = $quote_line->getLinesbyQuote( $quote->getId() );

        foreach ( $quote_lines as $quote_line_temp )
        {
            $quote_line->getRegbyId( $quote_line_temp['id'] );
//$txt = 'Quote line found ('.$quote_line->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);

            $product->getRegbyId( $quote_line->getProduct() );
//$txt = 'Product on Quote line found ('.$product->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);

            $product_type->getRegbyId( $product->getProductType() );
//$txt = 'Product type found ('.$product_type->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $product_type->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            if ( $product_type->getController() )
            {
                require_once APP_ROOT_PATH . '/src/controller/entity/'.$product_type->getController().'Controller.php';
                $class_to_load = '\\src\\controller\\entity\\'.$product_type->getController().'Controller';
//$txt = 'Paying class to load ( '.$class_to_load.' )'.PHP_EOL;
                $item = new $class_to_load( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );
                $method_to_load = $product->getPayedMethod();
//$txt .= ' method ('.$method_to_load.')'.PHP_EOL; fwrite($this->myfile, $txt);
                $item->$method_to_load( $quote_line );
            }

            $account->getRegbyId( $quote->getAccount() );
//$txt = 'Account '.$account->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $account->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            if ( $account->getMainUser() )
            {
                $user->getRegbyId( $account->getMainUser() );
                $locale = $user->getLocale();
                $name = $user->getName();
            }
            else
            {
                $locale = $account->getLocale();
                $name = ( $account->getName() )? $account->getName() : langTextController::getLangText( $this->utils, $locale, 'CUSTOMER');
            }

//$txt = 'Quote line description: '.$quote_line->getDescription().PHP_EOL; fwrite($this->myfile, $txt);
            $invoice_line->createInvoiceLine( $quote_line, $invoice->getId() );
//$txt = 'Invoice line created ============ '.$invoice_line->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $invoice_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            if ( $product->getGenerateCommission() )
            {
//$txt = 'Product generates commission === '.(( $product->getGenerateCommission() )?'Yes':'No').PHP_EOL; fwrite($this->myfile, $txt);
//                $this->commissionSettings( $quote_line, $item, $account, $invoice, $product_type->getName(), $product->getName() );
                $commission->createCommission( $quote_line );
                $commission->setInvoice( $invoice->getId() );
                $commission->persist();
//$txt = 'Commision created '.$commission->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $commission->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            }
        }

        $payment->createPayment( $quote, $payment_transaction );

        $payment->setPaymentType( $payment_transaction->getPaymentType() );
        $payment->persist();
//$txt = 'Payment created '.$payment->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $payment->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $invoice->setPayment( $payment->getId() );
//$txt = 'Payment assigned to invoice '.$invoice->getPayment().PHP_EOL; fwrite($this->myfile, $txt);
        $invoice->persist();

        $mailQueue->setId( '' );
        $mailQueue->setSend( $now );

        $mailQueue->setToName( $name );
        $mailQueue->setLocale( $locale );
        $mailQueue->setToAddress( $account->getNotificationsEmail() );

        $mailQueue->setTemplate('payment_successful');
        $mailQueue->setProcess(__METHOD__ );

        list( $product_name_key, $domain_name )  = $this->getItemDomainName( $quote );
//$txt = 'Product name key '.$product_name_key.PHP_EOL; fwrite($this->myfile, $txt);
        $product_name = langTextController::getLangText( $this->utils, $locale, $product_name_key) ;
//$txt = 'Product name '.$product_name.PHP_EOL; fwrite($this->myfile, $txt);

        $text_subject_success_payment = langTextController::getLangText( $this->utils, $locale, 'MAIL_PAYMENT_SUCCESSFUL_SUBJECT');
//$txt = 'Text subject success '.$text_subject_success_payment.PHP_EOL; fwrite($this->myfile, $txt);
        $text_subject = sprintf( $text_subject_success_payment, $this->session->config['web_name'], $product_name, $domain_name );
//$txt = 'Text subject '.$text_subject.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->setSubject( $text_subject );

        $text_preheader_success_payment = langTextController::getLangText( $this->utils, $locale, 'MAIL_PAYMENT_SUCCESSFUL_PREHEADER');
//$txt = 'Text preheader success '.$text_preheader_success_payment.PHP_EOL; fwrite($this->myfile, $txt);
        $text_preheader = sprintf( $text_preheader_success_payment, $this->session->config['web_name'], $product_name, $domain_name );
//$txt = 'Text preheader '.$text_preheader.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->setPreheader( $text_preheader );

        $total_f = floatval( $payment->getAmount() ) / 100;
        $total_real = number_format($total_f, 2, ',', '.');

        $text_info_success_payment = langTextController::getLangText( $this->utils, $locale, 'MAIL_PAYMENT_SUCCESSFUL_INFO');
//$txt = 'Text info success '.$text_info_success_payment.PHP_EOL; fwrite($this->myfile, $txt);
        $text_info = sprintf( $text_info_success_payment, $product_name, $this->session->config['web_name'], $domain_name, $total_real, $this->session->config['web_currency'] );
//$txt = 'Text in mail '.$text_info.PHP_EOL; fwrite($this->myfile, $txt);

        $mailQueue->addAssignVar( 'text_info', $text_info );

//$txt = 'Mail created'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->persist();
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose( $this->myfile);
        return true;
    }
    /*
     *  Processes a successful funding
     *
     * @param $payment_transaction     object Payment Transaction
     * @param $amount                  string Funding amount
     * @return void
     */
    public function fundResultSuccess( $lead_funding, $payment_transaction )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']));
//if ($_ENV['env_env'] == 'dev') { $folder = ''; } else { $folder = 'payments/'; }
//$this->myfile = fopen(APP_ROOT_PATH . '/var/logs/' . $folder . '/paymentResultController_' . __FUNCTION__ . '_' . $now->format('Y_m_d') . '.txt', 'w') or die('Unable to open file!');
//$txt = '====================== ' . __METHOD__ . ' start ==== ' . $now->format('d-m-Y  H:i:s') . ' =============================================' . PHP_EOL; fwrite($this->myfile, $txt);
//$txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Lead funding: (' . $lead_funding->getId() . ')' . PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Payment Transaction: '.$payment_transaction->getId().' Account '.$payment_transaction->getAccount().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $payment_transaction->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = PHP_EOL; fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_funds = new accountFundsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account->getRegbyId( $lead_funding->getAccount() );
//$txt = 'Account '.$account->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $account->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( $account->getMainUser() )
        {
            $user->getRegbyId( $account->getMainUser() );
            $locale = $user->getLocale();
            $name = $user->getName();
        }
        else
        {
            $locale = $account->getLocale();
            $name = ( $account->getName() )? $account->getName() : langTextController::getLangText( $this->utils, $locale, 'CUSTOMER');
        }

        $account_funds->setId('');
        $account_funds->setFundingKey( $lead_funding->getFundingKey() );
        $account_funds->setAccount( $lead_funding->getAccount() );
        $account_funds->setUser( $lead_funding->getUser() );
        $account_funds->setDate( $now );

        $account_funds_description_text = langTextController::getLangText( $this->utils, $locale, 'ACCOUNT_FUNDS_FUNDING_DESCRIPTION' );
        $account_funds->setDescription( $account_funds_description_text );

        $account_funds->setPaymentType( $payment_transaction->getPaymentType() );
        $account_funds->setAccountPaymentMethod( $payment_transaction->getAccountPaymentMethod() );
        $account_funds->setPaymentReference( $payment_transaction->getTransactionId() );

        $account_funds->setCredit( $lead_funding->getAmountReceived() );

        $account_funds->persist();
//$txt = 'Account fund '.$account_funds->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $account_funds->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $mailQueue->setToName( $name );
        $mailQueue->setLocale( $locale );
        $mailQueue->setToAddress( $account->getNotificationsEmail() );

        $mailQueue->setTemplate('funding_successful');
        $mailQueue->setProcess(__METHOD__ );

        $text_subject_success_fund = langTextController::getLangText( $this->utils, $locale, 'MAIL_ACCOUNT_FUNDS_SUCCESSFUL_SUBJECT');
//$txt = 'Text subject success '.$text_subject_success_fund.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->setSubject( $text_subject_success_fund );

        $text_preheader_success_fund = langTextController::getLangText( $this->utils, $locale, 'MAIL_ACCOUNT_FUNDS_SUCCESSFUL_PREHEADER');
//$txt = 'Text preheader success '.$text_preheader_success_fund.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->setPreheader( $text_preheader_success_fund );

        $total_f = floatval( $lead_funding->getAmountReceived() ) / 100;
        $total_real = number_format($total_f, 2, ',', '.');
        $total_real .= ' '.$this->session->config['web_currency'];

        $text_info_success_fund = langTextController::getLangText( $this->utils, $locale, 'MAIL_ACCOUNT_FUNDS_SUCCESSFUL_INFO');
//$txt = 'Text info success '.$text_info_success_fund.PHP_EOL; fwrite($this->myfile, $txt);
        $text_info = sprintf( $text_info_success_fund, $total_real );
//$txt = 'Text in mail '.$text_info.PHP_EOL; fwrite($this->myfile, $txt);

        $mailQueue->addAssignVar( 'text_info', $text_info );

//$txt = 'Mail created'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->persist();
//$txt = 'Mail payment failed'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *  Calculates commissions
     *
     * @param $quote_line   object Quote line to be treated
     * @param $item         object Service to be treated like widget, certification...
     * @param $account      object Account owner of the item, account to be invoiced
     * @param $invoice      object Invoice
     *
     * @throws
     * @return void
     */
    public function commissionSettings( $quote_line, $item, $account, $invoice, $product_type_name, $product_name )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $commisionist_account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $commisionist_user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $commission = new commissionController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $coupon = new couponController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $item->getRegbyId( $quote_line->getItem() );
//$txt = 'Item found '.$item->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $item->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Coupon on item '.$item->getCoupon().PHP_EOL; fwrite($this->myfile, $txt);
        if ( empty( $item->getCoupon() ) )
        {
            if ( empty( $item->getAgent() ) )
            {
                $item->setAgent( $account->getAgent() );
                $item->persist();
//$txt = 'Agent added to account from item ('.$account->getAgent().')'.PHP_EOL; fwrite($this->myfile, $txt);
            }
        }
        else 
        {
            $coupon->getRegbyId( $item->getCoupon() );

            $item->setAgent( $coupon->getAgent() );
            $item->persist();
//$txt = 'Agent added to item from coupon ('.$coupon->getAgent().')'.PHP_EOL; fwrite($this->myfile, $txt);
        }

        if ( empty( $item->getIntegrator() ) )
        {
            $item->setIntegrator( $account->getIntegrator() );
            $item->persist();
//$txt = 'Integrator added to account from item ('.$account->getIntegrator().')'.PHP_EOL; fwrite($this->myfile, $txt);
        }

        $commisionists = array();

        if ( !empty( $item->getAgent() ) ) $commisionists[] = $item->getAgent();
        if ( !empty( $item->getIntegrator() ) ) $commisionists[] = $item->getIntegrator();

//$txt = 'accounts to commission'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $commisionists, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( !empty( $commisionists ) )
        {
            foreach ( $commisionists as $commisionist )
            {
                $commission->setId( '' );

                $commisionist_account->getRegbyId( $commisionist );
//$txt = 'account to commission ('.$commisionist_account->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);

                $commisionist_user->getRegbyId( $commisionist_account->getMainUser() );
//$txt = 'main user from commisionist ('.$commisionist_user->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);

                if ( empty( $item->getCoupon() ) || $coupon->getAgent() != $commisionist_account->getId() )
                {
                    $percent_commission = $commisionist_account->getCommissionPercent();

                    $commission_no_coupon_description_text = langTextController::getLangText( $this->utils, $commisionist_user->getLocale(), 'COMMISSION_DESCRIPTION_TEXT_NO_COUPON' );
//$txt = 'Commision description text no coupon raw for COMMISSION_NO_COUPON_DESCRIPTION_TEXT ('.$commission_no_coupon_description_text.') in '.$commisionist_user->getLocale().PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Product type name ('.$product_type_name.') Product name ('.$product_name.' Doamin name ('.$item->getDomainName().')'.PHP_EOL; fwrite($this->myfile, $txt);
                    $commission->setDescription( sprintf( $commission_no_coupon_description_text, $product_type_name, $product_name, $item->getDomainName() ) );
//$txt = 'Commision description text ('.$commission->getDescription().')'.PHP_EOL; fwrite($this->myfile, $txt);
                }
                else
                {
                    $coupon->getRegbyAgent( $commisionist_account->getId() );
                    $percent_commission = $coupon->getCommissionPercent();

                    $commission_coupon_description_text = langTextController::getLangText( $this->utils, $commisionist_user->getLocale(), 'COMMISSION_DESCRIPTION_TEXT_COUPON' );
//$txt = 'Commision description text coupon raw for COMMISSION_COUPON_DESCRIPTION_TEXT ('.$commission_coupon_description_text.')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Product type name ('.$product_type_name.') Product name ('.$product_name.' Doamin name ('.$item->getDomainName().')'.PHP_EOL; fwrite($this->myfile, $txt);
                    $commission->setDescription( sprintf( $commission_coupon_description_text, $product_type_name, $product_name, $item->getDomainName()) );
//$txt = 'Commision description text ('.$commission->getDescription().') in '.$commisionist_user->getLocale().PHP_EOL; fwrite($this->myfile, $txt);
                }
                
                $total_commission = round( intval( $quote_line->getTotal() ) * ( intval( $percent_commission ) / 10000) );
//$txt = 'Commission of coupon ('.$total_commission.')'.PHP_EOL; fwrite($this->myfile, $txt);

                $commission->setAccount( $commisionist_account->getId() );
                $commission->setInvoice( $invoice->getId() );
                $commission->setDate( $now );
                $commission->setInvoiceNet( $quote_line->getTotal() );
                $commission->setCommissionPercent( $percent_commission );
                $commission->setTotal( $total_commission );
                $commission->setPayed( '0' );

                $commission->persist();
//$txt = 'Commission created'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $commission->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            }
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *  Payment failed treatment
     *
     * @param $quote                object Quote
     * @param $payment_transaction  object Payment transaction
     *
     * @return void
     */
    public function paymentResultFailed( $quote, $payment_transaction )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );
if ( $_ENV['env_env'] == 'dev') { $folder = '';} else { $folder = 'payments/';}
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/'.$folder.'/paymentResultController_'.__FUNCTION__.'_'.$now->format('Y_m_d').'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Quote '.$quote->getId(). Payment transaction '.$payment_transaction->getId().PHP_EOL;fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account->getRegbyId( $quote->getAccount() );

        if ( $account->getMainUser() )
        {
            $user->getRegbyId( $account->getMainUser() );
            $locale = $user->getLocale();
            $name = $user->getName();
        }
        else
        {
            $locale = $account->getLocale();
            $name = ( $account->getName() )? $account->getName() : langTextController::getLangText( $this->utils, $locale, 'CUSTOMER');
        }

        $mailQueue->setToName( $name );
        $mailQueue->setLocale( $locale );
        $mailQueue->setToAddress( $account->getNotificationsEmail() );

        /*
        $mailQueue->addAssignVar('currency', $this->session->config['web_currency']);

        $total_f = floatval( $payment->getAmount() ) / 100;
        $total_real = number_format($total_f, 2, ',', '.');
        $mailQueue->addAssignVar('total', $total_real);
        */
        $mailQueue->setTemplate('payment_failed');
        $mailQueue->setProcess(__METHOD__ );

        list( $product_name_key, $domain_name )  = $this->getItemDomainName( $quote );
//$txt = 'Product name key '.$product_name_key.PHP_EOL; fwrite($this->myfile, $txt);
        $product_name = langTextController::getLangText( $this->utils, $locale, $product_name_key) ;
//$txt = 'Product name '.$product_name.PHP_EOL; fwrite($this->myfile, $txt);

        $text_subject_failed_payment = langTextController::getLangText( $this->utils, $locale, 'MAIL_PAYMENT_FAILED_SUBJECT') ;
//$txt = 'Text subject failed '.$text_subject_failed_payment.PHP_EOL; fwrite($this->myfile, $txt);
        $text_subject = sprintf( $text_subject_failed_payment, $this->session->config['web_name'], $product_name, $domain_name);
//$txt = 'Text subject '.$text_subject.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->setSubject( $text_subject );

        $text_preheader_failed_payment = langTextController::getLangText( $this->utils, $locale, 'MAIL_PAYMENT_FAILED_PREHEADER') ;
//$txt = 'Text preheader failed '.$text_preheader_failed_payment.PHP_EOL; fwrite($this->myfile, $txt);
        $text_preheader = sprintf( $text_preheader_failed_payment, $product_name, $this->session->config['web_name'], $domain_name);
//$txt = 'Text preheader '.$text_preheader.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->setPreheader( $text_preheader );

        $total_f = floatval( $quote->getTotalToPay() ) / 100;
        $total_real = number_format($total_f, 2, ',', '.');
        $total_real .= ' '.$this->session->config['web_currency'];

        $text_info_failed_payment = langTextController::getLangText( $this->utils, $locale, 'MAIL_PAYMENT_FAILED_PAYMENT');
//$txt = 'Text info failed '.$text_info_failed_payment.PHP_EOL; fwrite($this->myfile, $txt);
        $text_info = sprintf( $text_info_failed_payment, $product_name, $this->session->config['web_name'], $domain_name, $total_real );
//$txt = 'Text in mail '.$text_info.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->addAssignVar( 'failed_payment_text', $text_info );

        $text_payment_button = langTextController::getLangText( $this->utils, $locale, 'MAIL_WIDGET_PENDING_PAYMENT_BUTTON');
        $mailQueue->addAssignVar('failed_payment_button', $text_payment_button );

        $mailQueue->addAssignVar('pay_link', $_ENV['protocol'].'://'.$_ENV['domain'].'/payments/choose_quote_product/' . $quote->getQuoteKey());

        $mailQueue->persist();
//$txt = 'Mail payment failed'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( $payment_transaction->getResult() == 'card_declined' || $payment_transaction->getResult() == 'blocked' )
        {
            $account_payment_method->getRegbyId( $quote->getPaymentMethod() );

            $mailQueue->setId( '' );

            $mailQueue->setTemplate('account_renew_bad_card');
            $mailQueue->setProcess(__METHOD__ );

            $mail_subject_text = langTextController::getLangText( $this->utils, $locale, 'MAIL_RENEW_BAD_CARD_SUBJECT');
            $mailQueue->setSubject( sprintf( $mail_subject_text, $this->session->config['web_name'] ) );
            $mail_preheader_text = langTextController::getLangText( $this->utils, $locale, 'MAIL_RENEW_BAD_CARD_PREHEADER');
            $mailQueue->setPreheader( sprintf( $mail_preheader_text, $this->session->config['web_name'] ) );

            $mailQueue->addAssignVar( 'card_digits', $account_payment_method->getLast4() );
            $mailQueue->addAssignVar( 'renew_link', $this->startup->getUrlApp().'/payments/renew_card/'.$account->getAccountKey() );

            //$mailQueue->persist();
//$txt = 'Mail card declined -> '.$mailQueue->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *  Payment failed treatment
     *
     * @param $lead_funding         object Lead funding
     * @param $payment_transaction  object Payment transaction
     *
     * @return void
     */
    public function fundResultFailed( $lead_funding, $payment_transaction )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );
if ( $_ENV['env_env'] == 'dev') { $folder = '';} else { $folder = 'payments/';}
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/'.$folder.'/paymentResultController_'.__FUNCTION__.'_'.$now->format('Y_m_d').'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Lead funding '.$lead_funding->getId(). Payment transaction '.$payment_transaction->getId().PHP_EOL;fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account_payment_method = new accountPaymentMethodController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account->getRegbyId( $lead_funding->getAccount() );

        if ( $account->getMainUser() )
        {
            $user->getRegbyId( $account->getMainUser() );
            $locale = $user->getLocale();
            $name = $user->getName();
        }
        else
        {
            $locale = $account->getLocale();
            $name = ( $account->getName() )? $account->getName() : langTextController::getLangText( $this->utils, $locale, 'CUSTOMER');
        }

        $mailQueue->setToName( $name );
        $mailQueue->setLocale( $locale );
        $mailQueue->setToAddress( $account->getNotificationsEmail() );

        /*
        $mailQueue->addAssignVar('currency', $this->session->config['web_currency']);

        $total_f = floatval( $lead_funding->getAmount() ) / 100;
        $total_real = number_format($total_f, 2, ',', '.');
        $mailQueue->addAssignVar('total', $total_real);
        */
        $mailQueue->setTemplate('funding_failed');
        $mailQueue->setProcess(__METHOD__ );

        $text_subject_failed_funding = langTextController::getLangText( $this->utils, $locale, 'MAIL_FUNDING_FAILED_SUBJECT') ;
//$txt = 'Text subject failed '.$text_subject_failed_funding.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->setSubject( $text_subject_failed_funding );

        $text_preheader_failed_funding = langTextController::getLangText( $this->utils, $locale, 'MAIL_FUNDING_FAILED_PREHEADER') ;
//$txt = 'Text preheader failed '.$text_preheader_failed_funding.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->setPreheader( $text_preheader_failed_funding );

        $total_f = floatval( $lead_funding->getTotalToPay() ) / 100;
        $total_real = number_format($total_f, 2, ',', '.');

        $text_info_failed_funding = langTextController::getLangText( $this->utils, $locale, 'MAIL_FUNDING_FAILED_INFO');
//$txt = 'Text info failed '.$text_info_failed_payment.PHP_EOL; fwrite($this->myfile, $txt);
        $text_info = sprintf( $text_info_failed_funding, $total_real, $this->session->config['web_currency'] );
//$txt = 'Text in mail '.$text_info.PHP_EOL; fwrite($this->myfile, $txt);

        $mailQueue->addAssignVar( 'failed_payment', $text_info );

        //$mailQueue->addAssignVar('pay_link', $_ENV['protocol'].'://'.$_ENV['domain'].'/payments/choose_quote_product/' . $quote->getQuoteKey());

        $mailQueue->persist();
//$txt = 'Mail payment failed'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( $payment_transaction->getResult() == 'card_declined' || $payment_transaction->getResult() == 'blocked' )
        {
            $account_payment_method->getRegbyId( $lead_funding->getAccountPaymentMethod() );

            $mailQueue->setId( '' );

            $mailQueue->setTemplate('account_renew_bad_card');
            $mailQueue->setProcess(__METHOD__ );

            $mail_subject_text = langTextController::getLangText( $this->utils, $locale, 'MAIL_RENEW_BAD_CARD_SUBJECT');
            $mailQueue->setSubject( sprintf( $mail_subject_text, $this->session->config['web_name'] ) );
            $mail_preheader_text = langTextController::getLangText( $this->utils, $locale, 'MAIL_RENEW_BAD_CARD_PREHEADER');
            $mailQueue->setPreheader( sprintf( $mail_preheader_text, $this->session->config['web_name'] ) );

            $mailQueue->addAssignVar( 'card_digits', $account_payment_method->getLast4() );
            $mailQueue->addAssignVar( 'renew_link', $this->startup->getUrlApp().'/payments/renew_card/'.$account->getAccountKey() );

            //$mailQueue->persist();
//$txt = 'Mail card declined -> '.$mailQueue->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }
    /**
     *  Send mail to request payment authorization
     *
     * @param $quote                Object Quote
     * @param $payment_transaction  Object Payment transaction
     * @param $auth_link            string Auth link
     *
     * @return void
     */
    public function paymentMailRequireAuth( $quote, $payment_transaction, $auth_link )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );
//if ( $_ENV['env_env'] == 'dev') { $folder = '';} else { $folder = 'payments/';}
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/'.$folder.'/paymentResultController_'.__FUNCTION__.'_'.$now->format('Y_m_d').'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Quote '.$quote->getId().' Payment transaction '.$payment_transaction->getId().' Authorization link '.$auth_link.PHP_EOL;fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

//$txt = 'Quote origin '.$quote->getPaymentOrigin().PHP_EOL; fwrite($this->myfile, $txt);
        if ( $quote->getPaymentOrigin() != 'online' )
        {

            $account->getRegbyId( $quote->getAccount() );
//$txt = 'Account found '.$account->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $account->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            if ( $account->getMainUser() )
            {
                $user->getRegbyId( $account->getMainUser() );
                $locale = $user->getLocale();
                $name = $user->getName();
            }
            else
            {
                $locale = $account->getLocale();
                $name = ( $account->getName() )? $account->getName() : langTextController::getLangText( $this->utils, $locale, 'CUSTOMER');
            }

            $mailQueue->setToName( $name );
            $mailQueue->setLocale( $locale );
            $mailQueue->setToAddress( $account->getNotificationsEmail() );

            $mailQueue->setTemplate('payment_need_auth' );
            $mailQueue->setProcess(__METHOD__ );

            list( $product_name_key, $domain_name )  = $this->getItemDomainName( $quote );
//$txt = 'Product name key '.$product_name_key.PHP_EOL; fwrite($this->myfile, $txt);
            $product_name = langTextController::getLangText( $this->utils, $locale, $product_name_key) ;
//$txt = 'Product name '.$product_name.PHP_EOL; fwrite($this->myfile, $txt);

            $text_subject_auth_payment = langTextController::getLangText( $this->utils, $locale, 'MAIL_PAYMENT_AUTH_SUBJECT') ;
//$txt = 'Text subject auth '.$text_subject_auth_payment.PHP_EOL; fwrite($this->myfile, $txt);
            $text_subject = sprintf( $text_subject_auth_payment, $this->session->config['web_name'], $product_name, $domain_name );
            //%1s: Autorización de pago de suscripción del %2s para el dominio %3s
//$txt = 'Text subject '.$text_subject.PHP_EOL; fwrite($this->myfile, $txt);
            $mailQueue->setSubject( $text_subject );

            $text_preheader_auth_payment = langTextController::getLangText( $this->utils, $locale, 'MAIL_PAYMENT_AUTH_PREHEADER') ;
//$txt = 'Text preheader auth '.$text_preheader_auth_payment.PHP_EOL; fwrite($this->myfile, $txt);
            $text_preheader = sprintf( $text_preheader_auth_payment, $this->session->config['web_name'], $product_name, $domain_name );
//$txt = 'Text preheader '.$text_preheader.PHP_EOL; fwrite($this->myfile, $txt);
            $mailQueue->setPreheader( $text_preheader );

            $total_f = floatval( $quote->getTotalToPay() ) / 100;
            $total_real = number_format($total_f, 2, ',', '.');

            $text_info_auth_payment = langTextController::getLangText( $this->utils, $locale, 'MAIL_PAYMENT_AUTH_PAYMENT');
//$txt = 'Text info auth '.$text_info_auth_payment.PHP_EOL; fwrite($this->myfile, $txt);
            $text_info = sprintf( $text_info_auth_payment, $product_name, $this->session->config['web_name'], $domain_name, $total_real, $this->session->config['web_currency'] );
//$txt = 'Text in mail '.$text_info.PHP_EOL; fwrite($this->myfile, $txt);

            $mailQueue->addAssignVar( 'auth_payment', $text_info );

//$txt = 'Mail created '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            $mailQueue->persist();
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *  Send mail to request payment authorization
     *
     * @param $lead_funding         Object Lead funding
     * @param $payment_transaction  Object Payment transaction
     * @param $auth_link            string Auth link
     *
     * @return void
     */
    public function fundMailRequireAuth( $lead_funding, $payment_transaction, $auth_link )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );
//if ( $_ENV['env_env'] == 'dev') { $folder = '';} else { $folder = 'payments/';}
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/'.$folder.'/paymentResultController_'.__FUNCTION__.'_'.$now->format('Y_m_d').'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Lead funding '.$lead_funding->getId().' Payment transaction '.$payment_transaction->getId().' Authorization link '.$auth_link.PHP_EOL;fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );


        $account->getRegbyId( $lead_funding->getAccount() );
//$txt = 'Account found '.$account->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $account->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( $account->getMainUser() )
        {
            $user->getRegbyId( $account->getMainUser() );
            $locale = $user->getLocale();
            $name = $user->getName();
        }
        else
        {
            $locale = $account->getLocale();
            $name = ( $account->getName() )? $account->getName() : langTextController::getLangText( $this->utils, $locale, 'CUSTOMER');
        }

        $mailQueue->setToName( $name );
        $mailQueue->setLocale( $locale );
        $mailQueue->setToAddress( $account->getNotificationsEmail() );

        $mailQueue->setTemplate('funding_need_auth' );
        $mailQueue->setProcess(__METHOD__ );

        $text_subject_auth_funding = langTextController::getLangText( $this->utils, $locale, 'MAIL_FUNDING_AUTH_SUBJECT') ;
//$txt = 'Text subject auth '.$text_subject_auth_funding.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->setSubject( $text_subject_auth_funding );

        $text_preheader_auth_funding = langTextController::getLangText( $this->utils, $locale, 'MAIL_FUNDING_AUTH_PREHEADER') ;
//$txt = 'Text preheader auth '.$text_preheader_auth_funding.PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->setPreheader( $text_preheader_auth_funding );

        $total_f = floatval( $lead_funding->getTotalToPay() ) / 100;
        $total_real = number_format($total_f, 2, ',', '.');

        $text_info_auth_funding = langTextController::getLangText( $this->utils, $locale, 'MAIL_FUNDING_AUTH_AUTHORIZE');
//$txt = 'Text info auth '.$text_info_auth_funding.PHP_EOL; fwrite($this->myfile, $txt);
        //$text_info = sprintf( $text_info_auth_funding, $total_real, $this->session->config['web_currency'] );
        $text_info = $text_info_auth_funding;
//$txt = 'Text in mail '.$text_info.PHP_EOL; fwrite($this->myfile, $txt);

        $mailQueue->addAssignVar( 'auth_payment', $text_info );

//$txt = 'Mail created '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $mailQueue->persist();

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *  Get a domain name from a quote
     *
     * @param $quote object Quote
     *
     * @throws
     * @return array Product type, Domain name
     */
    public function getItemDomainName( $quote )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        if ( $_ENV['env_env'] == 'dev') { $folder = '';} else { $folder = 'payments/';}
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/'.$folder.'/paymentResultController_'.__FUNCTION__.'_'.$now->format('Y_m_d').'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==== '.$now->format('d-m-Y  H:i:s').' ============================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account Key: '.$account_key.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Expiration Month: '.$exp_month.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Expiration Year: '.$exp_year.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Last 4 CC digits: '.$last4.PHP_EOL; fwrite($this->myfile, $txt);

        $quote_line = new quoteLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product_type = new productTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $item_domain_name = '';

        $quote_lines = $quote_line->getLinesbyQuote( $quote->getId() );
//$txt = 'Quote lines ================== '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $quote_lines, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $quote_line->getRegbyId( $quote_lines[0]['id'] );
//$txt = 'Quote line ================== '.$quote_line->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $product->getRegbyId( $quote_line->getProduct() );
//$txt = 'Product ================== '.$product->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $product_type->getRegbyId( $product->getProductType() );
//$txt = 'Product type table '.$product_type->getId().' - '.$product_type->getTable().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $product_type->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        if ( !empty( $product_type->getController() ) )
        {
            require_once APP_ROOT_PATH . '/src/controller/entity/'.$product_type->getController().'Controller.php';
            $class_to_load = '\\src\\controller\\entity\\'.$product_type->getController().'Controller';
//$txt = 'Entity class to load '.$class_to_load.PHP_EOL; fwrite($this->myfile, $txt);
            $entity_class = new $class_to_load(array('env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang));
//$txt = 'Entity class item '.$quote_line->getItem().PHP_EOL; fwrite($this->myfile, $txt);
            $entity_class->getRegbyId( $quote_line->getItem() );
//$txt = 'Entity class '.$entity_class->getId().' - '.$entity_class->getDomainName().PHP_EOL; fwrite($this->myfile, $txt);
            $item_domain_name = $entity_class->getDomainName();
//$txt = 'Product name key '.$product_type->getNameKey().' Domain name found '.$item_domain_name.PHP_EOL; fwrite($this->myfile, $txt);
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return array( $product_type->getNameKey(), $item_domain_name );
    }

    /**
     *  Record card details
     *
     * @param $account_key string Account key owning the card
     * @param $exp_month   string Expirity month
     * @param $exp_year    string Expirity year
     * @param $last4       string Last 4 digits of card
     *
     * @return void
     */
    public function cardDetailsResult( $account_key, $exp_month, $exp_year, $last4 )
    {
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        if ( $_ENV['env_env'] == 'dev') { $folder = '';} else { $folder = 'payments/';}
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/'.$folder.'/paymentResultController_cardDetails_'.$now->format('Y_m_d').'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==== '.$now->format('d-m-Y  H:i:s').' ============================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account Key: '.$account_key.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Expiration Month: '.$exp_month.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Expiration Year: '.$exp_year.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Last 4 CC digits: '.$last4.PHP_EOL; fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $accountPaymentDetail = new accountPaymentDetailsController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        if ( !empty( $account_key ) )
        {
            $account->getRegbyAccountKey( $account_key );

//            $check_day = cal_days_in_month(CAL_GREGORIAN, $exp_month,$exp_year);
            $check_day = '15';

            $exp_date = new DateTime($exp_year.'-'.$exp_month.'-'.$check_day, new DateTimeZone($this->session->config['time_zone']));

            if ( !$accountPaymentDetail->getRegbyAccount( $account->getId() ) )
            {
                $accountPaymentDetail->setAccount( $account->getId() );
            }
            $accountPaymentDetail->setLast4( $last4 );
            $accountPaymentDetail->setExpDate( $exp_date );

            $accountPaymentDetail->persist();
//$txt = 'Account payment detail'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $accountPaymentDetail->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        }
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
    }
}