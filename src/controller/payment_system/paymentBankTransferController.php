<?php

namespace src\controller\payment_system;

use \src\controller\baseController;

use \src\controller\entity\quoteLineController;
use \src\controller\entity\planController;
use \src\controller\entity\productController;
use \src\controller\entity\productTypeController;
use \src\controller\entity\accountController;
use \src\controller\entity\userController;
use \src\controller\entity\paymentTypeController;
use \src\controller\entity\leadFundingController;
use \src\controller\entity\bankAccountController;

use src\controller\entity\langTextController;
use \src\controller\entity\mailQueueController;


use DateTime;
use DateTimeZone;

class paymentBankTransferController extends baseController
{
    private $payment_system = 'BankTransfer';

    /**
     *
     * Treating payment with Bank transfer
     * This method is different from the other payment systems, it doesn't pays the quote.
     *
     * Adds a Bank transfer request, updates quote payment_reference with the funding_key
     *
     * Quote payment is done when the bank transfer is received.
     *
     * @param $quote object Quote to be paid object
     *
     * @throws
     * @return $result
     */
    public function payQuoteAction( $quote )
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paymentBankTransferController_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Quote to pay ========== '.$quote->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $quote->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']) );

        $quote_line = new quoteLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $plan = new planController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product = new productController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $product_type = new productTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $account = new accountController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $payment_type = new paymentTypeController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $lead_funding = new leadFundingController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $response = array (
                            'status' => 'KO',
                            'result' => array(),
        );

        if ( !$account->getRegbyId( $quote->getAccount() ) )
        {
//$txt = 'Account not found '.PHP_EOL; fwrite($this->myfile, $txt);
            $response['result']['msg'] = $this->lang['ERR_ACCOUNT_NOT_EXISTS'];
        }
        else
        {
//$txt = 'Account found '.$account->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account->getReg(), TRUE));
            if ( $account->getMainUser() )
            {
                $user->getRegbyId( $account->getMainUser() );
//$txt = 'Main user '.$account->getMainUser().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account->getReg(), TRUE));
            }

            if ( $quote->getInvoice() )
            {
//$txt = 'Quote paid '.$quote->getInvoice().PHP_EOL; fwrite($this->myfile, $txt);fwrite($this->myfile, print_r($quote->getReg(), TRUE));
                $response['result']['msg'] = $this->lang['ERR_QUOTE_ALREADY_PAID'];
            }
            else
            {
//$txt = 'Quote NOT paid'.PHP_EOL; fwrite($this->myfile, $txt);
                if (  !$quote->getPaymentType() )
                {
//$txt = 'Payment type empty'.PHP_EOL; fwrite($this->myfile, $txt);
                    $response['result']['msg'] = $this->lang['ERR_PAYMENT_TYPE_NOT_EXISTS'].' is NULL';
                }
                else
                {
                    if ( !$payment_type->getRegbyId( $quote->getPaymentType() ) )
                    {
//$txt = 'Payment type not found '.PHP_EOL; fwrite($this->myfile, $txt);
                        $response['result']['msg'] = $this->lang['ERR_PAYMENT_TYPE_NOT_EXISTS'];
                    }
                    else
                    {
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

//$txt = 'Quote line product ========== '.$quote_line->getProduct().PHP_EOL; fwrite($this->myfile, $txt);
                            if ( $plan->getRegbyPlanKey( $quote_line->getProduct() ) )
                            {
//$txt = 'Plan found ========== '.$plan->getId().PHP_EOL; fwrite($this->myfile, $txt);
                                $response['result']['msg'] = $this->lang['ERR_QUOTE_CHOOSE_PRODUCT'];
                            }
                            else
                            {
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
                        }
                    }
                }
            }
        }

        if ( empty( $response['result']['msg'] ) )
        {
            $response['status'] = 'OK';

            if ( empty( $quote->getPaymentReference() ) )
            {
                $lead_funding->setId( '');
                $lead_funding->setAccount( $account->getId() );
                $lead_funding->setUser( $user->getId() );
                $lead_funding->setDateReg( $now );
                $lead_funding->setFundingKey( '68247'.$account->getId().'-'.rand(500, 1500) );
                $lead_funding->setAmount( $quote->getTotalToPay() );

                $random = base64_encode( random_bytes(15) );
                $lead_funding->setToken( str_replace( '/' , '$' , $random) );

                $lead_funding->setPaymentType( PAYMENT_TYPE_FUNDS );
                $lead_funding->persist();
//$txt = 'Lead funding '.$lead_funding->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $lead_funding->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

                $quote->setPaymentReference( $lead_funding->getFundingKey() );
                $quote->persist();
            }
            else
            {
                if ( str_starts_with( '68247', $quote->getPaymentReference() ) )
                {
                    $lead_funding->getRegbyFundingKey( $quote->getPaymentReference() );
                }
                else
                {
                    $response['status'] = 'KO';
                    $response['result']['msg'] = 'Bad funding_key on quote '.$quote->getId().' found '.$quote->getPaymentReference();

                    $this->logger_err->error('*** Start *****************************************************************');
                    $this->logger_err->error('** '.__METHOD__.' **');
                    $this->logger_err->error('*************************************************************************');
                    $this->logger_err->error( 'Bad funding_key on quote '.$quote->getId().' found '.$quote->getPaymentReference() );
                    $this->logger_err->error('*************************************************************************');
                }
            }

            $response['result']['msg'] = $lead_funding->getReg();
        }

//$txt = 'Response '.$lead_funding->getId().PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose($this->myfile);
        return $response;
    }
}