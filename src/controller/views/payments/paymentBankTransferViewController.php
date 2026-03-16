<?php

namespace src\controller\views\payments;

use \src\controller\baseViewController;

use \src\controller\entity\accountController;
use \src\controller\entity\userController;
use \src\controller\entity\planController;
use \src\controller\entity\productController;
use \src\controller\entity\productTypeController;
use \src\controller\entity\quoteController;
use \src\controller\entity\quoteLineController;
use \src\controller\entity\paymentTypeController;

use \src\controller\payment_system\paymentBankTransferController;

use \src\controller\entity\bankAccountController;

use \src\controller\entity\mailQueueController;
use \src\controller\entity\langTextController;

use DateTime;
use DateTimeZone;
use Exception;

class paymentBankTransferViewController extends baseViewController
{
    /**
     *
     * Pays a quote with bank transfer
     *
     * Not used because it can't be an online payment system
     * But developed like it was.
     *
     * @Route("/payments/pay_quote/fde97e0eda19a6d80c94/quote_key", name="payments_pay_a_renew_quote_fde97e0eda19a6d80c94_quote_key")
     *
     */
    public function payQuoteAction( $vars )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentBankTransferViewController_'.__FUNCTION__.'.txt', 'w') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Quote key ========== '.$vars['quote_key'].PHP_EOL; fwrite($this->myfile, $txt);
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

        $plan = new planController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product_type = new productTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote = new quoteController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $quote_line = new quoteLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_type = new paymentTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_bank_transfer = new paymentBankTransferController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $bank_account = new bankAccountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $mailQueue = new mailQueueController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

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

            $product->getRegbyId( $quote_line->getProduct() );
//$txt = 'Is a product ==========> '.$product->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            $product_type->getRegbyId( $product->getProductType() );
//$txt = 'Product type ==========> '.$product_type->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product_type->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $item_class_name = $product_type->getController().'Controller';
            require_once APP_ROOT_PATH . '/src/controller/entity/' . $item_class_name . '.php';
            $class_to_load = '\\src\\controller\\entity\\' . $item_class_name;
            $item_class = new $class_to_load(array('env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang));

            $item_class->getRegbyId( $quote_line->getItem() );
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

        if (  !$quote->getPaymentType() )
        {
//$txt = 'Payment type empty'.PHP_EOL; fwrite($this->myfile, $txt);
            return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                'section' => $this->lang['PAYMENT_SECTION'],
                'alert_type' => 'danger',
                'title' => $this->lang['WARNING'],
                'message' => $this->lang['ERR_PAYMENT_TYPE_NOT_EXISTS'],
                'redirect_wait' => '5000',
                'redirect' => '/',
            ));
        }
        else
        {
            if ( !$payment_type->getRegbyId( $quote->getPaymentType() ) )
            {
//$txt = 'Payment type not found '.PHP_EOL; fwrite($this->myfile, $txt);
                return $this->twig->render('web/'.$this->session->config['website_skin'].'/common/show_message.html.twig', array(
                    'section' => $this->lang['PAYMENT_SECTION'],
                    'alert_type' => 'danger',
                    'title' => $this->lang['WARNING'],
                    'message' => $this->lang['ERR_PAYMENT_TYPE_NOT_EXISTS'],
                    'redirect_wait' => '5000',
                    'redirect' => '/',
                ));
            }
        }
//$txt = 'Payment type found '.$payment_type->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($payment_type->getReg(), TRUE));

        $lines = $quote_line->getLinesbyQuote( $quote->getId() );
//$txt = 'Quote lines =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($lines, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        foreach ( $lines as $line )
        {
            $quote_line->getRegbyId( $line['id'] );
//$txt = 'Quote line ========== '.$quote_line->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $product->getRegbyId( $quote_line->getProduct() );
//$txt = 'Is a product ==========> '.$product->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            $product_type->getRegbyId( $product->getProductType() );
//$txt = 'Product type ==========> '.$product_type->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($product_type->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

            $item_class_name = $product_type->getController().'Controller';
            require_once APP_ROOT_PATH . '/src/controller/entity/' . $item_class_name . '.php';
            $class_to_load = '\\src\\controller\\entity\\' . $item_class_name;
            $item = new $class_to_load(array('env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang));

            $item->getRegbyId( $quote_line->getItem() );
        }

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

        $payment_result = $payment_bank_transfer->payQuoteAction( $quote );

        if ( isset( $payment_result['status'] ) && $payment_result['status'] = 'OK' )
        {
//$txt = 'Payment successfull'.PHP_EOL; fwrite($this->myfile, $txt);
            $mailQueue->setId( '' );

            $mailQueue->setTemplate('payment_successful');
            $mailQueue->setProcess(__METHOD__ );

            $text_subject_fund_bank_transfer = langTextController::getLangText( $this->utils, $this->session->config['web_locale'], 'MAIL_QUOTE_PAY_BANK_TRANSFER_SUBJECT');
//$txt = 'Text subject success '.$text_subject_fund_bank_transfer.PHP_EOL; fwrite($this->myfile, $txt);
            $mailQueue->setSubject( $text_subject_fund_bank_transfer );

            $text_preheader_fund_bank_transfer = langTextController::getLangText( $this->utils, $this->session->config['web_locale'], 'MAIL_QUOTE_PAY_BANK_TRANSFER_PREHEADER');
//$txt = 'Text preheader success '.$text_preheader_fund_bank_transfer.PHP_EOL; fwrite($this->myfile, $txt);
            $mailQueue->setPreheader( $text_preheader_fund_bank_transfer );

            $mailQueue->setToName( $name );
            $mailQueue->setLocale( $locale );
            $mailQueue->setToAddress( $account->getNotificationsEmail() );

            $total_f = floatval( $quote->getTotalToPay() ) / 100;
            $total_real = number_format($total_f, 2, ',', '.');
            $total_real .= ' '.$this->session->config['web_currency'];

            $bank_account->getRegbyDefault();

            $text_info_pay_quote_bank_transfer = langTextController::getLangText( $this->utils, $locale, 'MAIL_QUOTE_PAY_BANK_TRANSFER_INFO_CUSTOMER');
//$txt = 'Text info success '.$text_info_fund_bank_transfer.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Vars amount'.$total_real.' transfer id '.$lead_funding->getFundingKey().' Bank Account '.$this->session->config['company_bank_account'].PHP_EOL; fwrite($this->myfile, $txt);
            $text_info = sprintf( $text_info_pay_quote_bank_transfer, $product_type->getName(), $item->getDomainName(), $total_real, $payment_result['result']['msg']['funding_key'], $bank_account->getIban().'-'.$bank_account->getNumber() );
            //$txt = 'Text in mail '.$text_info.PHP_EOL; fwrite($this->myfile, $txt);

            $mailQueue->addAssignVar( 'text_info', $text_info );

//$txt = 'Mail created to account user'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $mailQueue->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            $mailQueue->persist();

            $data['result'] = 'OK';
            $data['text'] = $text_info;

            $folder = ( in_array($this->group, [GROUP_SUPER_ADMIN, GROUP_ADMIN]) )? 'app' : 'control_panel';
            $data['next_action'] = '/'.$folder.'/dashboard';
        }
        else
        {
//$txt = 'Payment failed'.PHP_EOL; fwrite($this->myfile, $txt);
            $text_info_pay_quote_bank_transfer = langTextController::getLangText( $this->utils, $locale, 'MAIL_QUOTE_PAY_BANK_TRANSFER_INFO_CUSTOMER_FAILED');
//$txt = 'Text info failed '.$text_info_fund_bank_transfer.PHP_EOL; fwrite($this->myfile, $txt);
            $text_info = sprintf( $text_info_pay_quote_bank_transfer, $product_type->getName(), $item->getDomainName() );
//$txt = 'Text in mail '.$text_info.PHP_EOL; fwrite($this->myfile, $txt);

            $data['result'] = 'KO';
            $data['text'] = $text_info;
            $data['next_action'] = '/';
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $this->twig->render('web/'.$this->session->config['website_skin'].'/payment/info_payment_bank_transfer.html.twig', array(
            'data' => $data
        ));
    }
}
